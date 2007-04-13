<?
/*
 *   This file is part of PhpCompta.
 *
 *   PhpCompta is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   PhpCompta is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with PhpCompta; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
/* $Revision$ */
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
include_once("class_attribut.php");
require_once('class_fiche_def.php');
require_once('class_widget.php');
/*! \file
 * \brief define Class fiche, this class are using
 *        class attribut
 */
/*!
 * \brief define Class fiche and fiche def, those class are using
 *        class attribut
 */

//-----------------------------------------------------
// class fiche
//-----------------------------------------------------
class fiche {
  var $cn;           /*! \enum $cn database connection */
  var $id;           /*! \enum $id fiche.f_id */
  var $fiche_def;    /*! \enum $fiche_def fd_id */
  var $attribut;     /*! \enum $attribut array of attribut object */
  var $fiche_def_ref; /*!\enum $fiche_def_ref Type of the card here always FICHE_TYPE_CONTACT */

  function fiche($p_cn,$p_id=0) {
    $this->cn=$p_cn;
    $this->id=$p_id;
  }
/*!   GetByQCode($p_qcode)
 * \brief Retrieve a card thx his quick_code
 *        complete the object
 * \param $p_qcode quick_code (ad_id=23)
 * \param $p_all retrieve all the attribut of the card, possible value are true false
 * \return 0 success 1 error not found
 */

  function GetByQCode($p_qcode,$p_all=true)
    {

      $p_qcode=FormatString($p_qcode);
      $sql="select f_id from jnt_fic_att_value join attr_value 
           using (jft_id)  where ad_id=23 and av_text=upper('".$p_qcode."')";
      $Res=ExecSql($this->cn,$sql);
      $r=pg_fetch_all($Res);
      echo_debug('fiche',__LINE__,'result:'.var_export($r,true).'size '.sizeof($r));
      if ( $r == null  ) 
	return 1;
      $this->id=$r[0]['f_id'];
      echo_debug('class_fiche',__LINE__,'f_id = '.$this->id);

      if ( $p_all )
	$this->getAttribut();
      return 0;
    }
/*!
 **************************************************
 * \brief  get all the attribute of a card, add missing ones
 *         and sort the array ($this-\>attribut) by ad_id
 */
  function getAttribut() {
    if ( $this->id == 0){
      return;
    }
     $sql="select * 
           from jnt_fic_att_value 
               natural join fiche 
               natural join attr_value
               left join attr_def using (ad_id) where f_id=".$this->id.
       " order by ad_id";

    $Ret=ExecSql($this->cn,$sql);
    if ( ($Max=pg_NumRows($Ret)) == 0 )
      return ;
    for ($i=0;$i<$Max;$i++) {
      $row=pg_fetch_array($Ret,$i);
      $this->fiche_def=$row['fd_id'];
      $t=new Attribut ($row['ad_id']);
      $t->ad_text=$row['ad_text'];
      $t->av_text=$row['av_text'];
      $this->attribut[$i]=$t;
    }
    $e=new Fiche_def($this->cn,$this->fiche_def);
    $e->GetAttribut();

    if ( sizeof($this->attribut) != sizeof($e->attribut ) ) {

      // !!! Missing attribute
      foreach ($e->attribut as $f ) {
	$flag=0;
	foreach ($this->attribut as $g ) {
	  if ( $g->ad_id == $f->ad_id ) 
	    $flag=1;
	}
	if ( $flag == 0 ) { 
	  // there's a missing one, we insert it
	  $t=new Attribut ($f->ad_id);
	  $t->av_text="";
	  $t->ad_text=$f->ad_text;
	  $this->attribut[$Max]=$t;
	  $Max++;
	} // if flag == 0

      }// foreach 
      $this->attribut=SortAttributeById($this->attribut);


    }//missing attribut
  }


/*! 
 * \brief give the size of a card object
 *
 * \return size
 */
  function size() {
    if ( isset ($this->ad_id))
      return sizeof($this->ad_id);
    else
      return 0;
  }


/*!  
 **************************************************
 * \brief  Return array of card from the frd family
 *        
 * \param the fiche_def_ref.frd_id
 * \param p_search is a filter on the name
 * \param p_sql extra sql condition
 *
 * \return array of fiche object
 */
  function CountByDef($p_frd_id,$p_search="",$p_sql="") {
     $sql="select * 
           from
               fiche join fiche_Def using (fd_id)
            where frd_id=".$p_frd_id;
     if ( $p_search != "" ) 
       {
	 $a=FormatString($p_search);
	 $sql="select * from vw_fiche_attr where frd_id=".$p_frd_id.
	   " and vw_name ~* '$p_search'";
       }

    $Ret=ExecSql($this->cn,$sql.$p_sql);
    
    return pg_NumRows($Ret) ;
  }
/*!   
 **************************************************
 * \brief  Return array of card from the frd family
 *        
 * 
 * \param the fiche_def_ref.frd_id
 * \param  p_search is an optional filter
 *
 * \return array of fiche object
 */
  function GetByDef($p_frd_id,$p_offset=-1,$p_search="") {
    if ( $p_offset == -1 ) 
      {
	$sql="select * 
           from
               fiche join fiche_Def using (fd_id)
            where frd_id=".$p_frd_id." $p_search order by f_id";
      } 
    else 
      {
	$limit=$_SESSION['g_pagesize'];
	$sql="select * 
           from
               fiche join fiche_Def using (fd_id)
            where frd_id=".$p_frd_id." $p_search order by f_id
           limit ".$limit." offset ".$p_offset;

      }

    $Ret=ExecSql($this->cn,$sql);
    if ( ($Max=pg_NumRows($Ret)) == 0 )
      return ;
    $all[0]=new fiche($this->cn);

    for ($i=0;$i<$Max;$i++) {
      $row=pg_fetch_array($Ret,$i);
      $t=new fiche($this->cn,$row['f_id']);
      $t->getAttribut();
      $all[$i]=$t;

    }
    return $all;
  }
  function ShowTable() {
    echo "<TR><TD> ".
      $this->id."</TD>".
      "<TR> <TD>".
      $this->attribut_value."</TD>".
      "<TR> <TD>".
      $this->attribut_def."</TD></TR>";
  }
/*! 
 **************************************************
 * \brief  return the string of the given attribute
 *        (attr_def.ad_id) 
 * \param the AD_ID from attr_def.ad_id
 * \see constant.php
 * \return string
 */
  function strAttribut($p_ad_id) 
    {
      if ( sizeof ($this->attribut) == 0 ) 
	{
	  if ($this->id==0) return '- ERROR -';
	  // object is not in memory we need to look into the database
	  $sql="select av_text from attr_value join jnt_fic_att_value using(jft_id)
                where f_id=".FormatString($this->id)." and ad_id=".$p_ad_id;
	  $Res=ExecSql($this->cn,$sql);
	  $row=pg_fetch_all($Res);
	  // if not found return error
	  if ( $row == false ) 
	    return ' - ERROR -';
	  
	  return $row[0]['av_text'];
	}

      foreach ($this->attribut as $e)
	{
	  if ( $e->ad_id == $p_ad_id ) 
	    return $e->av_text;
	}
	return '- ERROR -';
    }
/*!   
 **************************************************
 * \brief  insert a new record
 *         show a blank card to be filled
 *        
 * \param  $p_fiche_def is the fiche_def.fd_id
 *     
 * \return HTML Code
 */
  function blank($p_fiche_def) 
    {
      // array = array of attribute object sorted on ad_id
      $f=new fiche_def($this->cn,$p_fiche_def);
      $f->Get();
      $array=$f->getAttribut();
      $r='<table>';
      foreach ($array as $attr)
	{
	  $msg="";
	  if ( $attr->ad_id == ATTR_DEF_ACCOUNT) 
	    {
	      $r.=JS_SEARCH_POSTE;
	      $w=new widget("js_search_poste");
	      //  account created automatically
	      $sql="select account_auto($p_fiche_def)";
	      echo_debug("class_fiche",__LINE__,$sql);
	      $ret_sql=ExecSql($this->cn,$sql);
	      $a=pg_fetch_array($ret_sql,0);
	      if ( $a['account_auto'] == 't' )
		$msg="<TD> <font color=\"red\">Rappel: Poste créé automatiquement !</font></TD> ";
	      else 
		{
		  // if there is a class base in fiche_def_ref, this account will be the
		  // the default one
		  if ( strlen(trim($f->class_base)) != 0 ) 
		    {
		      $msg="<TD> <font color=\"red\">Rappel: Poste par défaut sera ".
			$f->class_base.
			" !</font></TD> ";
		    }

		}

	     }
	  elseif ( $attr->ad_id == ATTR_DEF_TVA) 
	    {
	      $r.=JS_SHOW_TVA;
	      $w=new widget("js_tva");

	    }
	  elseif ( $attr->ad_id == ATTR_DEF_COMPANY )
	    {
	      $r.=JS_SEARCH_CARD;
	      $w=new widget("js_search");
	      // filter on frd_id
	      $w->extra=FICHE_TYPE_CLIENT.','.FICHE_TYPE_FOURNISSEUR.','.FICHE_TYPE_ADM_TAX; 
	      $w->extra2=0;      // jrn = 0
	      $label=new widget("span");
	      $label->name="av_text".$attr->ad_id."_label";
	      $msg=$label->IOValue();
	    }
	  else
	    {
	      $w=new widget("text");
	    }
	  $w->table=1;
	  $w->label=$attr->ad_text;
	  $w->name="av_text".$attr->ad_id;

	  $r.="<TR>".$w->IOValue()."$msg </TR>";
	}
      $r.= '</table>';
      return $r;
    }

  
/*!  
 **************************************************
 * \brief  Display object instance, getAttribute
 *        sort the attribute and add missing ones
 * \param $p_readonly true= if can not modify, otherwise false
 *	
 *
 * \return string to display
 */
  function Display($p_readonly) 
    {
      $this->GetAttribut();
      $attr=$this->attribut;

      $ret="<table>";

      foreach ( $attr as $r) 
	{
	  $msg="";
	  if ( $p_readonly) 
	    {
	      $w=new widget("text");
	    }
	  if ($p_readonly==false)
	    {
	      if ( $r->ad_id == ATTR_DEF_ACCOUNT) 
		{
		  $ret.=JS_SEARCH_POSTE;
		  $w=new widget("js_search_poste");
		  //  account created automatically
		  $sql="select account_auto($this->fiche_def)";
		  echo_debug("class_fiche",__LINE__,$sql);
		  $ret_sql=ExecSql($this->cn,$sql);
		  $a=pg_fetch_array($ret_sql,0);
		  if ( $a['account_auto'] == 't' )
		    $msg="<TD> <font color=\"red\">si vide le Poste sera créé automatiquement</font></TD> ";
		}
	      elseif ( $r->ad_id == ATTR_DEF_TVA) 
		{
		  $ret.=JS_SHOW_TVA;
		  $w=new widget("js_tva");
	    }
	      elseif ( $r->ad_id == ATTR_DEF_COMPANY )
		{
		  $ret.=JS_SEARCH_CARD;
		  $w=new widget("js_search");
		  // filter on frd_id
		  $w->extra=FICHE_TYPE_CLIENT.','.FICHE_TYPE_FOURNISSEUR.','.FICHE_TYPE_ADM_TAX; 
		  $w->extra2=0;      // jrn = 0
		  $label=new widget("span");
		  $label->name="av_text".$r->ad_id."_label";
		  $msg=$label->IOValue();
		}
	    
	      else 
		{
		  $w=new widget("text");
		}
	    }
	  $w->label=$r->ad_text;
	  $w->value=$r->av_text;
	  $w->name="av_text".$r->ad_id;
	  $w->readonly=$p_readonly;
	  $w->table=1;

	  $ret.="<TR>".$w->IOValue()."$msg </TR>";
	}
      $ret.="</table>";
      return $ret;
    }
  /*!   
 **************************************************
 * \brief  Save a card, call insert or update
 *        
 * \param p_fiche_def (default 0)
 */
  function Save($p_fiche_def=0) 
    {
      // new card or only a update ?
      if ( $this->id == 0 ) 
	$this->insert($p_fiche_def);
      else
	$this->update();
    }
/*! 
 **************************************************
 * \brief  insert a new record
 *        
 * \param p_fiche_def fiche_def.fd_id
 */
  function insert($p_fiche_def) 
    {
      $fiche_id=NextSequence($this->cn,'s_fiche');
      $this->id=$fiche_id;
      // first we create the card
      StartSql($this->cn);
      $sql=sprintf("insert into fiche(f_id,fd_id)". 
		   " values (%d,%d)",
		   $fiche_id,$p_fiche_def);
      $Ret=ExecSql($this->cn,$sql);
      // parse the $_POST array
      foreach ($_POST as $name=>$value ) 
	{
	  echo_debug ("class_fiche",__LINE__,"Name = $name value $value") ;
	  list ($id) = sscanf ($name,"av_text%d");
	  if ( $id == null ) continue;
	  echo_debug("class_fiche",__LINE__,"add $id");
	  
	  // Special traitement
	  // quickcode
	  if ( $id == ATTR_DEF_QUICKCODE) 
	    {
	      echo_debug("Modify ATTR_DEF_QUICKCODE");
	      $sql=sprintf("select insert_quick_code(%d,'%s')",
			   $fiche_id,FormatString($value));
	      ExecSql($this->cn,$sql);
	      continue;
	    }
	  // name
	  if ( $id == ATTR_DEF_NAME ) 
	    {
	      echo_debug("Modify ATTR_DEF_NAME");
	      if ( strlen(trim($value)) == 0 )
		$value="pas de nom";
	      
	    }
	  // account
	  if ( $id == ATTR_DEF_ACCOUNT ) 
	    {
	      echo_debug("insert ATTR_DEF_ACCOUNT");
	      $v=FormatString($value);
	      if ( isNumber($v) == 1 )
		{
		  $sql=sprintf("select account_insert(%d,%f)",
			       $this->id,$v);
		}
	      else 
		{
		  $sql=sprintf("select account_insert(%d,null)",
			       $this->id);
		}
	      $Ret=ExecSql($this->cn,$sql);
	      print "$sql $Ret";
	      continue;
	    }
	// TVA
	  if ( $id == ATTR_DEF_TVA ) 
	    {
	      echo_debug("Modify ATTR_DEF_TVA");
	      // Verify if the rate exists, if not then do not update
	      if ( strlen(trim($value)) != 0 ) 
		{
		  if ( CountSql($this->cn,"select * from tva_rate where tva_id=".$value) == 0) 
		    {
		      echo_debug("class_fiche",__LINE__,"Tva invalide $value");
		      continue;
		    }
		}
	    }
	  // The contact has a company attribut
	  if ( $id == ATTR_DEF_COMPANY ) 
	    {
	      $exist=CountSql($this->cn,"select f_id from fiche join fiche_def using (fd_id) ".
			      " join jnt_fic_att_value using (f_id) join attr_value using (jft_id) ".
			      " where frd_id in (8,9,14) and ad_id=".ATTR_DEF_QUICKCODE.
			      " and av_text='".FormatString($value)."'");
	      if ( $exist == 0 && FormatString($value) != null ) 
		{
		  $value="";
		}
	    }
	  // Normal traitement
	  $value2=FormatString($value);

	  $sql=sprintf("select attribut_insert(%d,%d,'%s')",
		       $fiche_id,$id,trim($value2));
	  ExecSql($this->cn,$sql);
	}
      Commit($this->cn);
      return;
      

    }

   

  /*!\brief update a card
   * \todo add a check to return an error and rollback operation
   */
 function update() 
     {
       // parse the $_POST array
       foreach ($_POST as $name=>$value ) 
         {
           echo_debug ("class_fiche",__LINE__,"Name = $name value $value") ;
           list ($id) = sscanf ($name,"av_text%d");
           if ( $id == null ) continue;
           echo_debug("class_fiche",__LINE__,"modify $id");
           
           // retrieve jft_id to update table attr_value
           $sql=" select jft_id from jnt_fic_att_value where ad_id=$id and f_id=$this->id";
           $Ret=ExecSql($this->cn,$sql);
           if ( pg_NumRows($Ret) != 1 ) {
	     // we need to insert this new attribut
             echo_debug ("class_fiche ".__LINE__." adding id !!! ");
	     $jft_id=NextSequence($this->cn,'s_jnt_fic_att_value');

	     $sql2=sprintf("insert into jnt_fic_att_value(jft_id,ad_id,f_id) values (%s,%s,%s)",
			   $jft_id,$id,$this->id);

	     $ret2=ExecSql($this->cn,$sql2);
	     // insert a null value for this attribut
	     $sql3=sprintf("insert into attr_value(jft_id,av_text) values (%s,null)",
                        $jft_id);
	     $ret3=ExecSql($this->cn,$sql3);
           } else 
	     {
	       $tmp=pg_fetch_array($Ret,0);
	       $jft_id=$tmp['jft_id'];
	     }
           // Special traitement
           // quickcode
           if ( $id == ATTR_DEF_QUICKCODE) 
             {
               echo_debug("Modify ATTR_DEF_QUICKCODE");
               $sql=sprintf("select update_quick_code(%d,'%s')",
                            $jft_id,FormatString($value));
               ExecSql($this->cn,$sql);
               continue;
             }
           // name
           if ( $id == ATTR_DEF_NAME ) 
             {
               echo_debug("Modify ATTR_DEF_NAME");
               if ( strlen(trim($value)) == 0 )
                 continue;
               
             }
           // account
           if ( $id == ATTR_DEF_ACCOUNT ) 
             {
               echo_debug("Modify ATTR_DEF_ACCOUNT");
               $v=FormatString($value);
               if ( isNumber($v) == 1 )
                 {
                   $sql=sprintf("select account_update(%d,%d)",
                                $this->id,$v);
                   $Ret=ExecSql($this->cn,$sql);
                 }
               if ( strlen (trim($v)) == 0 ) 
                 {
                   $sql=sprintf("select account_update(%d,null)",
                                $this->id);
                   $Ret=ExecSql($this->cn,$sql);
                   continue;
                 }
             }
         // TVA
           if ( $id == ATTR_DEF_TVA ) 
             {
               echo_debug("Modify ATTR_DEF_TVA");
               // Verify if the rate exists, if not then do not update
               if ( strlen(trim($value)) != 0 ) 
                 {
                   if ( CountSql($this->cn,"select * from tva_rate where tva_id=".$value) == 0) 
                     {
                       echo_debug("class_fiche",__LINE__,"Tva invalide $value");
                       continue;
                     }
                 }
             }
           if ( $id == ATTR_DEF_COMPANY ) 
             {
               $exist=CountSql($this->cn,"select f_id from fiche join fiche_def using (fd_id) ".
                               " join jnt_fic_att_value using (f_id) join attr_value using (jft_id) ".
                               " where frd_id in (8,9,14) and ad_id=".ATTR_DEF_QUICKCODE.
                               " and av_text='".FormatString($value)."'");
 
 
               if ( $exist == 0 && FormatString($value) != null ) 
                 {
                   $value="Attention : pas de société ";
                 }
             }
           
           // Normal traitement
           $value2=FormatString($value);
           $sql=sprintf("update attr_value set av_text='%s' where jft_id=%d",
                        trim($value2),$jft_id);
           ExecSql($this->cn,$sql);
         }
       return;
       
     }
 /*!\brief  remove a card
  */
   function remove() 
     {
       if ( $this->id==0 ) return;
       // verify if that card has not been used is a ledger
       // if the card has its own account in PCMN
       // Get the fiche_def.fd_id from fiche.f_id
       $this->Get();
       $fiche_def=new fiche_def($this->cn,$this->fiche_def);
       $fiche_def->Get();

       // if the card is used do not removed it
       $qcode=$this->strAttribut(ATTR_DEF_QUICKCODE);

       if ( CountSql($this->cn,"select * from jrnx where j_qcode='$qcode'") != 0)
	 {
	   echo "<SCRIPT> alert('Impossible cette fiche est utilisée dans un journal'); </SCRIPT>";
	   return;
	 }

       if ( $fiche_def->create_account=='t' ) {
	 // Retrieve the 'poste comptable'
	 $class=$this->strAttribut(ATTR_DEF_ACCOUNT);
	 $is_used_jrnx= CountSql($this->cn,"select * from jrnx where j_poste=$class");
	 // if class is not NULL and if we use it before, we can't remove it
	 if (FormatString($class) != null && $is_used_jrnx     != 0 ) 
	   {
	     echo "<SCRIPT> alert('Impossible ce poste est utilisée dans un journal'); </SCRIPT>";
	     return;
	   }
	 else
	   // Remove in PCMN
	   if ( trim(strlen($class)) != 0 && isNumber($class) == 1 && $is_used_jrnx == 0)
	     {
	       ExecSql($this->cn,"delete from tmp_pcmn where pcm_val=".$class);
	     }
	 
       }
       // Remove from attr_value
       $Res=ExecSql($this->cn,"delete from attr_value 
                        where jft_id in (select jft_id 
                                          from jnt_fic_att_value 
                                                natural join fiche where f_id=".$this->id.")");
       // Remove from jnt_fic_att_value
       $Res=ExecSql($this->cn,"delete from jnt_fic_att_value where f_id=".$this->id);
       
       // Remove from fiche
       $Res=ExecSql($this->cn,"delete from fiche where f_id=".$this->id);
     }


   /*!\brief return the name of a card
    */
   function getName() 
     {
       $sql="select av_text from jnt_fic_att_value join attr_value 
            using (jft_id)  where ad_id=1 and f_id=".$this->id;
       $Res=ExecSql($this->cn,$sql);
       $r=pg_fetch_all($Res);
       if ( sizeof($r) == 0 ) 
         return 1;
       return $r[0]['av_text'];
     }
   /*!\brief Synonum of fiche::getAttribut
    */
   function Get() 
     {
       echo_debug('class_client',__LINE__,'Get');
       fiche::getAttribut();
     }
   /*!\brief get all the card thanks the fiche_def_ref
    * \param $p_offset (default =-1)
    * \param $p_search sql condition
    * \return fiche::GetByDef
    */
   function GetAll($p_offset=-1,$p_search="") 
     {
       return fiche::GetByDef($this->fiche_def_ref,$p_offset,$p_search);
    }
   /*!\brief retrieve the frd_id of the fiche 
    *        (fiche_def_ref primary key)
    */
  function get_fiche_def_ref_id() 
    {
      $result=GetArray($this->cn,"select frd_id from fiche join fiche_Def using (fd_id) where f_id=".$this->id);
      if ( $result == null )
	return null;
      
      return $result[0]['frd_id'];
    }
    
}
?>
