<?php  
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
require_once("class_ispan.php");
require_once("class_itva.php");
require_once("class_isearch.php");
require_once("class_itext.php");
require_once("class_ihidden.php");
require_once('class_fiche_def.php');
require_once('class_iposte.php');

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
  var $cn;           /*! < $cn database connection */
  var $id;           /*! < $id fiche.f_id */
  var $fiche_def;    /*! < $fiche_def fd_id */
  var $attribut;     /*! < $attribut array of attribut object */
  var $fiche_def_ref; /*!< $fiche_def_ref Type */
  var $row;           /*! < All the row from the ledgers */
  var $quick_code;		/*!< quick_code of the card */
  function fiche($p_cn,$p_id=0) {
    $this->cn=$p_cn;
    $this->id=$p_id;
    $this->quick_code='';
  }
/*!   get_by_qcode($p_qcode)
 * \brief Retrieve a card thx his quick_code
 *        complete the object,, set the id member of the object
 * \param $p_qcode quick_code (ad_id=23)
 * \param $p_all retrieve all the attribut of the card, possible value
 * are true, false retrieve only the f_id
 * \return 0 success 1 error not found
 */

  function get_by_qcode($p_qcode=null,$p_all=true)
    {
      if ( $p_qcode == null )
	$p_qcode=$this->quick_code;

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
               join jnt_fic_attr on (jnt_fic_attr.fd_id=fiche.fd_id and jnt_fic_attr.ad_id=jnt_fic_att_value.ad_id)
               join attr_def on (attr_def.ad_id=jnt_fic_att_value.ad_id) where f_id=".$this->id.
       " order by jnt_order";

    $Ret=ExecSql($this->cn,$sql);
    if ( ($Max=pg_NumRows($Ret)) == 0 )
      return ;
    for ($i=0;$i<$Max;$i++) {
      $row=pg_fetch_array($Ret,$i);
      $this->fiche_def=$row['fd_id'];
      $t=new Attribut ($row['ad_id']);
      $t->ad_text=$row['ad_text'];
      $t->av_text=$row['av_text'];
      $t->jnt_order=$row['jnt_order'];
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
	  $t->jnt_order=$f->jnt_order;
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
 * \param $p_frd_id the fiche_def_ref.frd_id
 * \param $p_search p_search is a filter on the name
 * \param $p_sql extra sql condition
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
 * \param  $p_frd_id the fiche_def_ref.frd_id
 * \param  $p_offset
 * \param  $p_search is an optional filter
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
		$limit=($_SESSION['g_pagesize']!=-1)?"limit ".$_SESSION['g_pagesize']:"";
		$sql="select * 
           from
               fiche join fiche_Def using (fd_id)
            where frd_id=".$p_frd_id." $p_search order by f_id "
		  .$limit." offset ".$p_offset;

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
	echo_debug(__FILE__,__LINE__,$all);
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
 * \param $p_ad_id the AD_ID from attr_def.ad_id
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
			  $w=new IPoste();
			  //  account created automatically
			  $sql="select account_auto($p_fiche_def)";
			  echo_debug("class_fiche",__LINE__,$sql);
			  $ret_sql=ExecSql($this->cn,$sql);
			  $a=pg_fetch_array($ret_sql,0);
			  $label=new ISpan();
			  $label->name="av_text".$attr->ad_id."_label";
			  
			  if ( $a['account_auto'] == 't' )
				$msg.="<TD>".$label->input()."<br> <font color=\"red\">Rappel: Poste créé automatiquement !</font></TD> ";
			  else 
				{
				  // if there is a class base in fiche_def_ref, this account will be the
				  // the default one
				  if ( strlen(trim($f->class_base)) != 0 ) 
					{
					  $msg.="<TD>".$label->input()."<br> <font color=\"red\">Rappel: Poste par défaut sera ".
						$f->class_base.
						" !</font></TD> ";
					}
				  
				}


	     }
	  elseif ( $attr->ad_id == ATTR_DEF_TVA) 
	    {
	      $r.=JS_SHOW_TVA;
	      $w=new ITva();

	    }
	  elseif ( $attr->ad_id == ATTR_DEF_COMPANY )
	    {
	      $r.=JS_SEARCH_CARD;
	      $w=new ISearch();
	      // filter on frd_id
	      $w->extra=FICHE_TYPE_CLIENT.','.FICHE_TYPE_FOURNISSEUR.','.FICHE_TYPE_ADM_TAX; 
	      $w->extra2=0;      // jrn = 0
	      $label=new ISpan();
	      $label->name="av_text".$attr->ad_id."_label";
	      $msg=$label->input();
	    }
	  else
	    {
	      $w=new IText();
	    }
	  $w->table=1;
	  $w->label=$attr->ad_text;
	  $w->name="av_text".$attr->ad_id;

	  $r.="<TR>".$w->input()."$msg </TR>";
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
	if ( empty ($attr) ) {
		return "Fiche non trouvée";
	}
      foreach ( $attr as $r) 
	{
	  $msg="";
	  if ( $p_readonly) 
	    {
	      $w=new IText();
	    }
	  if ($p_readonly==false)
	    {
	      if ( $r->ad_id == ATTR_DEF_ACCOUNT) 
		{
		  $ret.=JS_SEARCH_POSTE;
		  $w=new IPoste();
		  //  account created automatically
		  $sql="select account_auto($this->fiche_def)";
		  echo_debug("class_fiche",__LINE__,$sql);
		  $ret_sql=ExecSql($this->cn,$sql);
		  $a=pg_fetch_array($ret_sql,0);
		  $msg='<tD><span id="'.$r->ad_id.'_label"></span></td>';

		  if ( $a['account_auto'] == 't' )
		    $msg.="<TD> <font color=\"red\">si vide le Poste sera créé automatiquement</font> </TD> ";
		  $msg.="<td><font color=\"red\">ATTENTION changer le poste comptable d'une fiche modifiera toutes les opérations où cette fiche est utilisée</font></td>";
		}
	      elseif ( $r->ad_id == ATTR_DEF_TVA) 
		{
		  $ret.=JS_SHOW_TVA;
		  $w=new ITva();
	    }
	      elseif ( $r->ad_id == ATTR_DEF_COMPANY )
		{
		  $ret.=JS_SEARCH_CARD;
		  $w=new ISearch();
		  // filter on frd_id
		  $w->extra=FICHE_TYPE_CLIENT.','.FICHE_TYPE_FOURNISSEUR.','.FICHE_TYPE_ADM_TAX; 
		  $w->extra2=0;      // jrn = 0
		  $label=new ISpan();
		  $label->name="av_text".$r->ad_id."_label";
		  $msg=$label->input();
		}
	    
	      else 
		{
		  $w=new IText();
		}
	    }
	  $w->label=$r->ad_text;
	  $w->value=$r->av_text;
	  $w->name="av_text".$r->ad_id;
	  $w->readonly=$p_readonly;
	  $w->table=1;


	  $ret.="<TR>".$w->input()."$msg </TR>";
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
 * \param p_array is the array containing the data
 */
  function insert($p_fiche_def,$p_array=null) 
    {
      if ( $p_array == null)
	$p_array=$_POST;

      $fiche_id=NextSequence($this->cn,'s_fiche');
      $this->id=$fiche_id;
      // first we create the card
      StartSql($this->cn);
      try 
	{
	  $sql=sprintf("insert into fiche(f_id,fd_id)". 
		       " values (%d,%d)",
		       $fiche_id,$p_fiche_def);
	  $Ret=ExecSql($this->cn,$sql);
	  // parse the $p_array array
	  foreach ($p_array as $name=>$value ) 
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
	      // stock 
	      if ( $id == ATTR_DEF_STOCK ) {
		$st=count_sql($this->cn,'select * from stock_goods where '.
			     " upper(sg_code)=upper('$value')");
		if ( $st == 0 ) {
		  $user=new User($this->cn);
		  $exercice=$user->get_exercice();
		  if ( $exercice == 0 ) throw new Exception ('Annee invalide erreur');

		  $str_stock=sprintf('insert into stock_goods(f_id,sg_quantity,sg_comment,sg_code,sg_type,sg_exercice) '.
				     ' values (%d,0,\'%s\',upper(\'%s\'),\'d\',\'%s\')',
				     $fiche_id,
				     'initial',
				     FormatString($value),
				     $exercice);
				     
		  ExecSql($this->cn,$str_stock);
		}
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
		  try {
		    if ( 
			isNumber($v) == 1 
			 )
		      {
			$sql=sprintf("select account_insert(%d,%f)",
				     $this->id,$v);
		      }
		    else 
		      {
			$sql=sprintf("select account_insert(%d,null)",
				     $this->id);
		      }
		    ExecSql($this->cn,$sql);
		  } catch (Exception $e) {
		    throw new Exception ("Erreur : ce compte [$v] n'a pas de compte parent.".
					 "L'op&eacute;ration est annul&eacute;e",
					 1);
		  }
		  continue;
		}
	      // TVA
	      if ( $id == ATTR_DEF_TVA ) 
		{
		  echo_debug("Modify ATTR_DEF_TVA");
		  // Verify if the rate exists, if not then do not update
		  if ( strlen(trim($value)) != 0 ) 
		    {
		      if ( count_sql($this->cn,"select * from tva_rate where tva_id=".$value) == 0) 
			{
			  echo_debug("class_fiche",__LINE__,"Tva invalide $value");
			  continue;
			}
		    }
		}
	      // The contact has a company attribut
	      if ( $id == ATTR_DEF_COMPANY ) 
		{
		  $exist=count_sql($this->cn,"select f_id from fiche join fiche_def using (fd_id) ".
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
	}  catch (Exception $e) 
	     {
	       echo '<span class="error">'.
		 $e->getMessage().
		 '</span>';
	       Rollback($this->cn);
	       return;
	     }
      Commit($this->cn);
      return;
    }

   

  /*!\brief update a card
   */
 function update($p_array=null) 
     {
      if ( $p_array == null)
	$p_array=$_POST;

       try {
	 StartSql($this->cn);
	 // parse the $p_array array
	 foreach ($p_array as $name=>$value ) 
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
	     	      if ( $id == ATTR_DEF_STOCK ) {
		$st=count_sql($this->cn,'select * from stock_goods where '.
			     " f_id=".$this->id);
		if ( $st == 0 ) {
		  $user=new User($this->cn);
		  $exercice=$user->get_exercice();
		  if ( $exercice == 0 ) throw new Exception ('Annee invalide erreur');

                  $str_stock=sprintf('insert into stock_goods(f_id,sg_quantity,sg_comment,sg_code,sg_type,sg_exercice) '.
                                     ' values (%d,0,\'%s\',upper(\'%s\'),\'d\',\'%s\')',
                                     $this->id,
                                     'initial',
                                     FormatString($value),
                                     $exercice);

		  ExecSql($this->cn,$str_stock);
		}else {
		$str_stock=sprintf("update stock_goods set sg_code=upper('%s') where f_id=%d",
				FormatString($value),
				$this->id);
		  ExecSql($this->cn,$str_stock);
		}
	      }

	     // account
	     if ( $id == ATTR_DEF_ACCOUNT ) 
	       {
		 echo_debug("Modify ATTR_DEF_ACCOUNT");
		 $v=FormatString($value);
		 echo_debug("Value = $v");
		 if ( isNumber($v) == 1 )
		   {
		     $sql=sprintf("select account_update(%d,%d)",
				  $this->id,$v);
		     try {
		       ExecSql($this->cn,$sql);
		     /* update also the jrnx  */
		     $sql='update jrnx set j_poste=$1 where j_qcode in (select quick_code from vw_fiche_attr where f_id=$2)';
		     ExecSqlParam($this->cn,
				  $sql,
				  array($v,$this->id));

		     } catch (Exception $e) {
		       throw new Exception(__LINE__."Erreur : ce compte [$v] n'a pas de compte parent.".
					   "L'op&eacute;ration est annul&eacute;e");
		     }
		     continue;		   
		   }
		 if ( strlen (trim($v)) == 0 ) 
                 {

                   $sql=sprintf("select account_update(%d,null)",
                                $this->id);
		   try {
		     $Ret=ExecSql($this->cn,$sql);
		     /* update also the jrnx  */

		     $sql='update jrnx set j_poste=$1 where j_qcode in (select quick_code from vw_fiche_attr where f_id=$2)';
		     ExecSqlParam($this->cn,
				  $sql,
				  array($v,$this->id));

		   } catch (Exception $e) {
		       throw new Exception(__LINE__."Erreur : ce compte [$v] n'a pas de compte parent.".
					   "L'op&eacute;ration est annul&eacute;e");
		   }

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
		     if ( count_sql($this->cn,"select * from tva_rate where tva_id=".$value) == 0) 
		       {
			 echo_debug("class_fiche",__LINE__,"Tva invalide $value");
			 continue;
		       }
		   }
	       }
	     if ( $id == ATTR_DEF_COMPANY ) 
	       {
		 $exist=count_sql($this->cn,"select f_id from fiche join fiche_def using (fd_id) ".
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
       } catch (Exception $e ) {
	 echo '<span class="error">'.
	   $e->getMessage().
	   '</span>';
	 Rollback($this->cn);
	 return;
       }
       Commit($this->cn);
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

       if ( count_sql($this->cn,"select * from jrnx where j_qcode='".pg_escape_string($qcode)."'") != 0)
	 {
	   alert('Impossible cette fiche est utilisée dans un journal'); 
	   return;
	 }

       if ( $fiche_def->create_account=='t' ) {
	 // Retrieve the 'poste comptable'
	 $class=$this->strAttribut(ATTR_DEF_ACCOUNT);
	 $is_used_jrnx=0;
	 if ( trim(strlen($class)) != 0 && isNumber($class) == 1 )
	 	$is_used_jrnx= count_sql($this->cn,"select * from jrnx where j_poste=$class");
	 // if class is not NULL and if we use it before, we can't remove it
	 if (FormatString($class) != null && $is_used_jrnx     != 0 ) 
	   {
	     alert('Impossible ce poste est utilisée dans un journal'); 
	     return;
	   }
	 else
	   // Remove in PCMN
	   if ( trim(strlen($class)) != 0 && isNumber($class) == 1 && $is_used_jrnx == 0)
	     {
	       ExecSql($this->cn,"delete from tmp_pcmn where pcm_val=$class");
	     }
	 
       }
       $this->delete();
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

   /*!\brief return the quick_code of a card
    * \return null if not quick_code is found
    */
   function get_quick_code() 
     {
       $sql="select av_text from jnt_fic_att_value join attr_value 
            using (jft_id)  where ad_id=23 and f_id=".$this->id;
       $Res=ExecSql($this->cn,$sql);
       $r=pg_fetch_all($Res);
       if ( sizeof($r) == 0 ) 
         return null;
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
    * \return array of fiche object 
    */
   function GetAll($p_offset=-1,$p_search="") 
     {
       return fiche::GetByDef($this->fiche_def_ref,$p_offset,$p_search);
    }
   /*!\brief retrieve the frd_id of the fiche it is the type of the
    *        card (bank, purchase...)
    *        (fiche_def_ref primary key)
    */
  function get_fiche_def_ref_id() 
    {
      $result=get_array($this->cn,"select frd_id from fiche join fiche_Def using (fd_id) where f_id=".$this->id);
      if ( $result == null )
	return null;
      
      return $result[0]['frd_id'];
    }

  /*! 
   * \brief  Get data for poste 
   * 
   * \param  $p_from periode from
   * \param  $p_to   end periode
   * \return double array (j_date,deb_montant,cred_montant,description,jrn_name,j_debit,jr_internal)
   *         (tot_deb,tot_credit
   *
   */ 
  function get_row($p_from,$p_to)
    {
      if ( $this->id == 0 ) 
	{
	  echo_error("class_fiche",__LINE__,"id is 0");
	  return;
	}
      $qcode=$this->strAttribut(ATTR_DEF_QUICKCODE);
      $periode=sql_filter_per($this->cn,$p_from,$p_to,'p_id','jr_tech_per');

      $Res=ExecSql($this->cn,"select to_char(j_date,'DD.MM.YYYY') as j_date,j_qcode,".
	       "case when j_debit='t' then j_montant else 0 end as deb_montant,".
	       "case when j_debit='f' then j_montant else 0 end as cred_montant,".
	       " jr_comment as description,jrn_def_name as jrn_name,".
	       "j_debit, jr_internal,jr_id ".
	       " from jrnx left join jrn_def on jrn_def_id=j_jrn_def ".
	       " left join jrn on jr_grpt_id=j_grpt".
	       " where j_qcode='".$qcode."' and ".$periode.
	       " order by j_date::date");
      $array=array();
      $tot_cred=0.0;
      $tot_deb=0.0;
      $Max=pg_NumRows($Res);
      if ( $Max == 0 ) return null;
      for ($i=0;$i<$Max;$i++) {
	$array[]=pg_fetch_array($Res,$i);
	if ($array[$i]['j_debit']=='t') {
	  $tot_deb+=$array[$i]['deb_montant'] ;
	} else {
	  $tot_cred+=$array[$i]['cred_montant'] ;
	}
      }
      $this->row=$array;
      return array($array,$tot_deb,$tot_cred);
    }
  /*! 
   * \brief HtmlTable, display a HTML of a card for the asked period
   * \return none
   */
  function HtmlTableDetail($p_array=null)
    {     
      if ( $p_array == null)
	$p_array=$_REQUEST;

      $name=$this->getName();
      
      list($array,$tot_deb,$tot_cred)=$this->get_row( $p_array['from_periode'],
						     $p_array['to_periode']
						     );
      
      if ( count($this->row ) == 0 ) 
	return;
      
      $rep="";
      
      echo '<h2 class="info">'.$this->id." ".$name.'</h2>';
      echo "<TABLE class=\"result\" width=\"100%\">";
      echo "<TR>".
	"<TH> Code interne </TH>".
	"<TH> Date</TH>".
	"<TH> Description </TH>".
	"<TH> Montant  </TH>".
	"<TH> Débit/Crédit </TH>".
	"</TR>";
     
      foreach ( $this->row as $op ) { 
	echo "<TR  style=\"text-align:center;background-color:lightgrey\">".
	  "<td >".$op['jr_internal']."</td>".
	  "<td >".$op['j_date']."</td>".
	  "<td >".h($op['description'])."</td>".
	  "<td >"."</td>".
	  "<td >"."</td>".
	  "</TR>";
	$ac=new Acc_Operation($this->cn);
	$ac->jr_id=$op['jr_id'];
	echo $ac->display_jrnx_detail(1);
	
      }
      $solde_type=($tot_deb>$tot_cred)?"solde débiteur":"solde créditeur";
      $diff=round(abs($tot_deb-$tot_cred),2);
      echo "<TR>".
	"<TD>$solde_type".
	"<TD>$diff</TD>".
	"<TD></TD>".
	"<TD>$tot_deb</TD>".
	"<TD>$tot_cred</TD>".
	"</TR>";
      
      echo "</table>";
      
      return;
    }
  /*! 
   * \brief HtmlTable, display a HTML of a card for the asked period
   * \param $p_array default = null keys = from_periode, to_periode 
   */
  function HtmlTable($p_array=null)
    {     
      if ( $p_array == null)
	$p_array=$_REQUEST;

      $name=h($this->getName());
      
      list($array,$tot_deb,$tot_cred)=$this->get_row( $p_array['from_periode'],
						     $p_array['to_periode']
						     );
      
      if ( count($this->row ) == 0 ) 
	return;
      
      $rep="";
      
      echo '<h2 class="info">'.$this->id." ".$name.'</h2>';
      echo "<TABLE class=\"result\" width=\"100%\">";
      echo "<TR>".
	"<TH> Code interne </TH>".
	"<TH> Date</TH>".
	"<TH> Description </TH>".
	"<TH> Débit  </TH>".
	"<TH> Crédit </TH>".
	"</TR>";
     
      foreach ( $this->row as $op ) { 
	echo "<TR>".
	  "<TD>".$op['jr_internal']."</TD>".
	  "<TD>".$op['j_date']."</TD>".
	  "<TD>".h($op['description'])."</TD>".
	  "<TD>".$op['deb_montant']."</TD>".
	  "<TD>".$op['cred_montant']."</TD>".
	  "</TR>";
	
      }
      $solde_type=($tot_deb>$tot_cred)?"solde débiteur":"solde créditeur";
      $diff=round(abs($tot_deb-$tot_cred),2);
      echo "<TR>".
	"<TD>$solde_type</TD>".
	"<TD>$diff</TD>".
	"<TD></TD>".
	"<TD>$tot_deb</TD>".
	"<TD>$tot_cred</TD>".
	"</TR>";
      
      echo "</table>";
      
      return;
    }
 /*! 
  * \brief Display HTML Table Header (button)
  *
  * \return none
  */
 function HtmlTableHeader($p_array=null)
   {
     if ( $p_array == null)
       $p_array=$_REQUEST;

     $hid=new IHidden();
     echo '<div class="noprint">';
     echo "<table >";
     echo '<TR>';
     
     echo '<TD><form method="GET" ACTION="">'.
       HtmlInput::submit('bt_other',"Autre poste").
	dossier::hidden().
       $hid->input("type","poste").$hid->input('p_action','impress')."</form></TD>";
     
     echo '<TD><form method="POST" ACTION="quick_code_pdf.php">'.
       HtmlInput::submit('bt_pdf',"Export PDF").
	dossier::hidden().
       $hid->input("type","poste").
       $hid->input('p_action','impress').
       $hid->input("f_id",$this->id).
	dossier::hidden().
       $hid->input("from_periode",$p_array['from_periode']).
       $hid->input("to_periode",$p_array['to_periode']);
     if (isset($p_array['oper_detail']))
       echo $hid->input('oper_detail','on');

     echo "</form></TD>";
     
     echo '<TD><form method="POST" ACTION="quick_code_csv.php">'.
       HtmlInput::submit('bt_csv',"Export CSV").
	dossier::hidden().
       $hid->input("type","poste").
       $hid->input('p_action','impress').
       $hid->input("f_id",$this->id).
       $hid->input("from_periode",$p_array['from_periode']).
       $hid->input("to_periode",$p_array['to_periode']);
     if (isset($p_array['oper_detail']))
       echo $hid->input('oper_detail','on');
     
     echo "</form></TD>";
     echo "</table>";
     echo '</div>';
     
   }
  /*! 
   * \brief   give the balance of an card
   * \return
   *      balance of the card
   *
   */ 
function get_solde_detail($p_cond="") {
  if ( $this->id == 0 ) exit('fiche->id est nul');
  $qcode=$this->strAttribut(ATTR_DEF_QUICKCODE);

	if ( $p_cond != "") $p_cond=" and ".$p_cond;
  $Res=ExecSql($this->cn,"select sum(deb) as sum_deb, sum(cred) as sum_cred from 
          ( select j_poste, 
             case when j_debit='t' then j_montant else 0 end as deb, 
             case when j_debit='f' then j_montant else 0 end as cred 
          from jrnx 
              where  
            j_qcode = ('$qcode'::text)
            $p_cond
          ) as m  ");
  $Max=pg_NumRows($Res);
  if ($Max==0) return 0;
  $r=pg_fetch_array($Res,0);
  
  return array('debit'=>$r['sum_deb'],
	       'credit'=>$r['sum_cred'],
	       'solde'=>abs($r['sum_deb']-$r['sum_cred']));
}
/*!\brief check if an attribute is empty
 *\param $p_attr the id of the attribut to check (ad_id)
 *\return return true is the attribute is empty or missing
 */
function empty_attribute($p_attr) {
  $sql="select av_text
           from jnt_fic_att_value 
               natural join fiche 
               natural join attr_value
               left join attr_def using (ad_id) where f_id=".$this->id.
    " and ad_id = ".$p_attr.
    " order by ad_id";
  $res=ExecSql($this->cn,$sql);
  if ( pg_NumRows($res) == 0 ) return true;
  $text=pg_fetch_result($res,0,0);
  return (strlen(trim($text)) > 0)?false:true;

  
}

/*! 
 * \brief get the fd_id of the card : fd_id, it set the attribute fd_id
 */
 function get_categorie() 
   {
     if ( $this->id == 0 ) exit('class_fiche : f_id = 0 ');
     $sql='select fd_id from fiche where f_id='.$this->id;
     $R=getDbValue($this->cn,$sql);
     if ( $R == "" )
       $this->fd_id=0;
     else
       $this->fd_id=$R;
   }
/*!  
 ***************************************************
 * \brief   Check if a fiche is used by a jrn
 *  return 1 if the  fiche is in the range otherwise 0, the quick_code
 *  or the id  must be set
 *        
 * 
 * \param   $p_jrn journal_id
 * \param   $p_type : deb or cred default empty
 * 
 * \return 1 if the fiche is in the range otherwise < 1
 *        -1 the card doesn't exist
 *        -2 the ledger has no card to check
 * 
 */
function belong_ledger($p_jrn,$p_type="")
{
  // check if we have a quick_code or a f_id
  if (($this->quick_code==null || $this->quick_code == "" ) 
      && $this->id == 0 ) {
    echo 'erreur ni quick_code ni f_id ne sont donnes';
    exit();
  }

  //retrieve the quick_code
  if ( $this->quick_code=="") 
    $this->quick_code=$this->get_quick_code();


  if ( $this->quick_code==null) 
    return -1;

  if ( $this->id == 0 ) 
    if ( $this->get_by_qcode(null,false) == 1)
      return -1;

  $get="";
  if ( $p_type == 'deb' ) {
    $get='jrn_def_fiche_deb';
  }
  if ( $p_type == 'cred' ) {
    $get='jrn_def_fiche_cred';
  }
  if ( $get != "" ) {
    $Res=ExecSql($this->cn,"select $get as fiche from jrn_def where jrn_def_id=$p_jrn");
  } else {
    // Get all the fiche type (deb and cred)
    $Res=ExecSql($this->cn," select jrn_def_fiche_cred as fiche  
                         from jrn_def where jrn_def_id=$p_jrn
                        union
                         select jrn_def_fiche_deb 
                         from jrn_def where jrn_def_id=$p_jrn"
		 );
  }
  $Max=pg_NumRows($Res);
  if ( $Max==0) {
    return -2;
  }
  /* convert the array to a string */
  $list=pg_fetch_all($Res);
  $str_list="";
  $comma='';
  foreach ($list as $row) {
    if ( $row['fiche'] != '' ) {
      $str_list.=$comma.$row['fiche'];
      $comma=',';
    }
  }
  // Normally Max must be == 1

  if ( $str_list=="") {
    return -3;
  }

  $sql="select *
        from fiche
        where  
           fd_id in (".$str_list.") and f_id= ".$this->id;

  $Res=ExecSql($this->cn,$sql);
  $Max=pg_NumRows($Res);
  if ($Max==0 ) 
    return 0;
  else
    return 1;
}
/*!\brief  get all the card from a categorie
 *\param $p_cn database connx
 *\param $pFd_id is the category id
 *\return an array of card, but only the fiche->id is set
 */
static function get_fiche_def($p_cn,$pFd_id) {
  $sql='select f_id from fiche where fd_id=$1';
  $array=get_array($p_cn,$sql,array($pFd_id));
  if ( empty ($array) ) return null;
  foreach ($array as $ret ) {
    $r[]=new fiche($p_cn,$ret['f_id']);
  }
  return $r;
}
/*!\brief check if a card is used
 *\return return true is a card is used otherwise false
 */
function is_used() {
  /* retrieve first the quickcode */
  $qcode=$this->strAttribut(ATTR_DEF_QUICKCODE);
  $sql='select count(*) as c from jrnx where j_qcode=$1';
  $count=getDbValue($this->cn,$sql,array($qcode));
  if ( $count == 0 ) return false; 
  return true;
}
/*\brief remove a card without verification */
 function delete() {
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
}
?>
