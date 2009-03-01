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
require_once("class_itext.php");
require_once('class_fiche_def_ref.php');
require_once('class_fiche.php');
require_once('user_common.php');

/*! \file
 * \brief define Class fiche and fiche def, those class are using
 *        class attribut
 */
/*!
 * \brief define Class fiche and fiche def, those class are using
 *        class attribut
 */
class fiche_def {
  var $cn;           // database connection
  var $id;			// id (fiche_def.fd_id
  var $label;			// fiche_def.fd_label
  var $class_base;		// fiche_def.fd_class_base
  var $fiche_def;		// fiche_def.frd_id = fiche_def_ref.frd_id
  var $create_account;		// fd_create_account: flag
  var $all;
  var $attribut;		// get from attr_xxx tables
  function fiche_def($p_cn,$p_id = 0) {
    $this->cn=$p_cn;
    $this->id=$p_id;
  }

/*!
 **************************************************
 *  \brief  Get attribut of a fiche_def
 *        
 * \return string value of the attribute
 * none
 */
  function GetAttribut() {
    $sql="select * from jnt_fic_attr ".
      " natural join attr_def where fd_id=".$this->id.
      " order by jnt_order";

    $Ret=ExecSql($this->cn,$sql);

    if ( ($Max=pg_NumRows($Ret)) == 0 )
      return ;
    for ($i=0;$i < $Max;$i++) {
      $row=pg_fetch_array($Ret,$i);
      $t = new Attribut($row['ad_id']);
      $t->ad_text=$row['ad_text'];
      $t->jnt_order=$row['jnt_order'];
      $this->attribut[$i]=$t;
    }
    return $this->attribut;
  }

 /*!
 **************************************************
 * \brief  Get attribut of the fiche_def
 *        
 */
  function Get() {
    if ( $this->id == 0 ) 
      return 0;
    ExecSqlParam($this->cn,'select fiche_attribut_synchro($1)',
		 array($this->id));

    $sql="select * from fiche_def ".
      " where fd_id=".$this->id;
    $Ret=ExecSql($this->cn,$sql);
    if ( ($Max=pg_NumRows($Ret)) == 0 )
      return ;
    $row=pg_fetch_array($Ret,0);
    $this->label=$row['fd_label'];
    $this->class_base=$row['fd_class_base'];
    $this->fiche_def=$row['frd_id'];
    $this->create_account=$row['fd_create_account'];
  }
/*!   
 **************************************************
 * \brief  Get all the fiche_def
 *        
 * \return an array of fiche_def object 
 */
 function GetAll() {
   $sql="select * from fiche_def ";

    $Ret=ExecSql($this->cn,$sql);
    if ( ($Max=pg_NumRows($Ret)) == 0 )
      return ;

    for ( $i = 0; $i < $Max;$i++) {
      $row=pg_fetch_array($Ret,$i);
      $this->all[$i]=new fiche_def($this->cn,$row['fd_id']);
      $this->all[$i]->label=$row['fd_label'];
      $this->all[$i]->class_base=$row['fd_class_base'];
      $this->all[$i]->fiche_def=$row['frd_id'];
      $this->all[$i]->create_account=$row['fd_create_account'];
    }
  }
/*!   
 **************************************************
 * \brief  Check in vw_fiche_def if a fiche has 
 *           a attribut X
 *        
 *  
 * \param  $p_attr attribut to check
 * \return  true or false
 */
 function HasAttribute($p_attr) {
   return (CountSql($this->cn,"select * from vw_fiche_def where ad_id=$p_attr and fd_id=".$this->id)>0)?true:false;

 }
/*!   
 **************************************************
 * \brief  Display a fiche_def object into a table
 *        
 * \return HTML row 
 */
 function Display() 
   {

     $r=sprintf("<TD>%s</TD>",$this->id);
     $r.=sprintf("<TD>%s</TD>",$this->label);
     $r.=sprintf("<TD>%s</TD>",$this->class_base);
     $r.=sprintf("<TD>%s</TD>",$this->fiche_def);
     return $r;
   }
 /*!\brief Add a fiche thanks the element from the array 
  * table : insert into fiche_def
  *         insert into attr_def
  *
  * \param $array array 
  *        index FICHE_REF
  *              nom_mod
  *              class_base
  */
 function Add($array)
   {
     echo_debug('class_fiche',__LINE__,"Add Modele");

  // Show what we receive for debug purpose only
  //

     foreach ( $array as $key=>$element ) {
       echo_debug('class_fiche_def',__LINE__,"p_$key $element");
       ${"p_$key"}=$element;
     }
     // Format correctly the name of the cat. of card
     $p_nom_mod=FormatString($p_nom_mod);
     echo_debug('class_fiche_def',__LINE__,"Adding $p_nom_mod");
     // Format the p_class_base 
     // must be an integer
     if ( isNumber($p_class_base) == 0 && FormatString($p_class_base) != null ) {
       echo_error ('p_class_base is NOT a number');
     }
     // Name can't be empty
     if ( strlen(trim($p_nom_mod)) == 0 ) 
       return;

     // $p_FICHE_REF cannot be null !!! (== fiche_def_ref.frd_id
     if (! isset ($p_FICHE_REF) or strlen($p_FICHE_REF) == 0 ) {
       echo_error ("AddModele : fiche_ref MUST NOT be null or empty");
       return;
     }
     $fiche_Def_ref=new fiche_def_ref($this->cn,$p_FICHE_REF);
     $fiche_Def_ref->Get();
     echo_debug('class_fiche_def',__LINE__,$fiche_Def_ref);
     // build the sql request for fiche_def
     // and insert into fiche_def
     // if p_class_base is null get the default class base from
     // fiche_def_ref
     if ( FormatString($p_class_base) == null )
       { // p_class is null
	 // So we take the default one
	 $p_class_base=$fiche_Def_ref->frd_class_base;
	 echo_debug('class_fiche_def',__LINE__,'Found class base ='.$p_class_base);
       }

     // Set the value of fiche_def.fd_create_account
     // automatic creation for 'poste comptable'
     if ( isset($p_create) && strlen(trim($p_class_base)) != 0) 
       $p_create='true';
     else
       $p_create='false';
     
     // Class is valid ?
     if ( FormatString($p_class_base) != null) {
       
       // p_class is a valid number
       $sql=sprintf("insert into fiche_def(fd_label,fd_class_base,frd_id,fd_create_account) 
                values ('%s',%s,%d,'%s')",
		 $p_nom_mod,$p_class_base,$p_FICHE_REF,$p_create);
       $Res=ExecSql($this->cn,$sql);
       
       // p_class must be added to tmp_pcmn 
       $sql=sprintf("select account_add(%d,'%s')",
		    $p_class_base,$p_nom_mod);
       
       $Res=ExecSql($this->cn,$sql);
       
       // Get the fd_id
       $fd_id=GetSequence($this->cn,'s_fdef');
    
       // Add the class_base if needed
       
       $sql=sprintf("insert into jnt_fic_attr(fd_id,ad_id,jnt_order) 
                     values (%d,%d,10)",$fd_id,ATTR_DEF_ACCOUNT);
       $Res=ExecSql($this->cn,$sql);

     } else {
       //There is no class base not even as default
       $sql=sprintf("insert into fiche_def(fd_label,frd_id,fd_create_account) values ('%s',%d,'%s')",
		    $p_nom_mod,$p_FICHE_REF,$p_create);
       
       $Res=ExecSql($this->cn,$sql);
       
       // Get the fd_id
       $fd_id=GetSequence($this->cn,'s_fdef');
       
     }
     
     // Get the default attr_def from attr_min
     $def_attr=Get_attr_min($this->cn,$p_FICHE_REF);
     
     //if defaut attr not null 
     // build the sql insert for the table attr_def
     if (sizeof($def_attr) != 0 ) {
       // insert all the mandatory fields into jnt_fiche_attr
       foreach ( $def_attr as $i=>$v) {
	 $jnt_order=10;
	 if ( $v['ad_id'] == ATTR_DEF_NAME ) 
	   $jnt_order=0;
	 $sql=sprintf("insert into jnt_fic_Attr(fd_id,ad_id,jnt_order)
                   values (%d,%s,%d)",
		      $fd_id,$v['ad_id'],$jnt_order);
	 ExecSql($this->cn,$sql);
       }
     }
     
     
   }//--------------end function Add ----------------------------
/*! 
 * \brief Get all the card where the fiche_def.fd_id is given in parameter
 * \param $step = 0 we don't use the offset, page_size,...
 *        $step = 1 we use the jnr_bar_nav
 *
 * \return double array (f_id,fd_id)
 */
  function GetByType($step=0) {
    $sql="select * 
           from
               fiche 
            where fd_id=".$this->id;

    // we use jrn_navigation_bar    
    if ($step == 1  && $_SESSION['g_pagesize'] != -1   )
      {
	$offset=(isset($_GET['offset']))?$_GET['offset']:0;
	$step=$_SESSION['g_pagesize'];
	$sql.=" offset $offset limit $step";
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

  /*!\brief list the card of a fd_id
   */
  function myList()
    {
      $this->Get();
      echo '<H2 class="info">'.$this->label.'</H2>';

      $step=$_SESSION['g_pagesize'];
      $sql_limit="";
      $sql_offset="";
      $bar="";
      if ( $step != -1 ) {

	$page=(isset($_GET['page']))?$_GET['page']:1;
	$offset=(isset($_GET['offset']))?$_GET['offset']:0;
	$max_line=CountSql($this->cn,"select f_id,av_text  from 
                          fiche join jnt_fic_att_value using (f_id) 
                                join attr_value using (jft_id)
                       where fd_id='".$this->id."' and ad_id=".ATTR_DEF_NAME." order by f_id");
	$sql_limit=" limit ".$step;
	$sql_offset=" offset ".$offset;
	$bar=jrn_navigation_bar($offset,$max_line,$step,$page);
      }
      
      // Get all name the cards of the select category
      // 1 for attr_def.ad_id is always the name
      $Res=ExecSql($this->cn,"select f_id,vw_name,quick_code  from ".
		   " vw_fiche_attr ".
		   " where fd_id='".$this->id.
		   "' order by f_id $sql_offset $sql_limit ");
      $Max=pg_NumRows($Res);
      echo $bar;
      $str="";
      // save the url 
      // with offet &offset=15&step=15&page=2&size=15
      if ( $_SESSION['g_pagesize'] != -1) {
	$str=sprintf("&offset=%s&step=%s&page=%s&size=%s",
		     $offset,
		     $step,
		     $page,
		     $max_line);
      }
      
		       
      echo '<FORM METHOD="POST" action="?p_action=fiche&action=vue'.$str.'">';
	  echo dossier::hidden();
      echo '<INPUT TYPE="HIDDEN" name="fiche" value="'.$this->id.'">';
      echo '<INPUT TYPE="SUBMIT" name="add" Value="Ajout fiche">';
      echo '</FORM>';
      $str_dossier=dossier::get();
      echo '<table>';
      for ( $i = 0; $i < $Max; $i++) {
		$l_line=pg_fetch_array($Res,$i);
		if ( $i%2 == 0) 
		  echo '<TR class="odd">';
		else
		  echo '<TR class="even">';

		$span_mod='<TD><A href="?p_action=fiche&'.$str_dossier.'&action=detail&fiche_id='.$l_line['f_id'].$str.'&fiche='.$_GET['fiche'].'">'.$l_line['quick_code'].'</A></TD>';
	
		echo $span_mod.'<TD>'.h($l_line['vw_name'])."</TD>";
		echo '</tr>';
      }
      echo '</table>';
      echo '<FORM METHOD="POST" action="?p_action=fiche&action=vue'.$str.'">';
	  echo dossier::hidden();
      echo '<INPUT TYPE="HIDDEN" name="fiche" value="'.$this->id.'">';
      echo '<INPUT TYPE="SUBMIT" name="add" Value="Ajout fiche">';
      echo '</FORM>';
      echo $bar;

    }
  /*!\brief Display all the attribut of the fiche_def
   *\param $str give the action possible values are remove, empty 
   */
  function DisplayAttribut($str="")
    {
      echo_debug("class_fiche_def",__LINE__,"DisplayAttribut");
      if ( $this->id == 0 )
	return ;
      ExecSqlParam($this->cn,'select fiche_attribut_synchro($1)',
		   array($this->id));

      $MaxLine=sizeof($this->attribut);
      echo_debug("class_fiche_def",__LINE__,"MaxLine = ".$MaxLine);
      $r="<TABLE>";
      // Display each attribute
      $add_action="";
      for ($i=0;$i<$MaxLine;$i++) {
	$class="even";
	if ( $i % 2 == 0 )
		$class="odd";
		
	$r.='<TR class="'.$class.'"><td>';
	// Can change the name
	if ( $this->attribut[$i]->ad_id == ATTR_DEF_NAME ) {
	  $a=sprintf('Label</TD><TD><INPUT TYPE="TEXT" NAME="label" VALUE="%s">',
		 $this->label);
	  $r.=$a;
	  $r.='</td><TD><input type="submit" NAME="change_name" value="Change Nom">';
	} else {
		if ( $str == "remove" ) {
		  //Only for the not mandatory attribute (not defined in attr_min)
		  if ( CountSql($this->cn,"select * from attr_min where frd_id=".
			   $this->fiche_def." and ad_id = ".$this->attribut[$i]->ad_id) == 0
		       && $this->attribut[$i]->ad_id != ATTR_DEF_QUICKCODE
		       && $this->attribut[$i]->ad_id != ATTR_DEF_ACCOUNT
		       ) 
		    {
		       $add_action=sprintf( '</TD><TD> Supprimer <input type="checkbox" name="chk_remove[]" value="%d">',
				$this->attribut[$i]->ad_id);
			}
		  else
		    $add_action="</td><td>";
		}	
	  // The attribut.
	  $a=sprintf('%s ',  $this->attribut[$i]->ad_text);
	  $r.=$a.$add_action;
	  /*----------------------------------------  */
	  /*  ORDER OF THE CARD */
	  /*----------------------------------------  */
	  $order=new IText();
	  $order->name='jnt_order'.$this->attribut[$i]->ad_id;
	  $order->size=3;
	  $order->value=$this->attribut[$i]->jnt_order;
	  $r.='</td><td> '.$order->IOValue();
	}
	$r.= '</td></tr>';
      }
      
      // Show the possible attribute which are not already attribute of the model
      // of card
      $Res=ExecSql($this->cn,"select ad_id,ad_text from attr_def 
                       where 
                 ad_id not in (select ad_id from fiche_def natural join jnt_fic_attr
                           where fd_id=".$this->id.")");
      $M=pg_NumRows($Res);
      
      // Show the unused attribute
      $r.='<TR> <TD>';
      $r.= '<SELECT NAME="ad_id">';
      for ($i=0;$i<$M;$i++) {	$l=pg_fetch_array($Res,$i);
	$a=sprintf('<OPTION VALUE="%s"> %s',
	       $l['ad_id'],$l['ad_text']);
	$r.=$a;
      }
      $r.='</SELECT>';

      $r.="</TABLE>";
      return $r;
    }
  /*!\brief Save the label of the fiche_def
   * \param $p_label label
   */
  function SaveLabel($p_label)
    {
      if ( $this->id == 0 ) return;
      $p_label=FormatString($p_label);
      if (strlen(trim ($p_label)) == 0 ) {
	echo_debug('class_fiche_def',__LINE__,'Name empty');
	return;
      }
      $sql=sprintf("update   fiche_def set fd_label='%s' ".
		   "where                    fd_id=%d", 
		   $p_label,$this->id);
      $Res=ExecSql($this->cn,$sql);
      
    }
  /*!\brief insert a new attribut for this fiche_def
   * \param $p_ad_id id of the attribut
   */
  function InsertAttribut($p_ad_id)
    {
      if ( $this->id == 0 ) return;
      /* ORDER */
      $this->GetAttribut();
      $max=sizeof($this->attribut);
      // Insert a new attribute for the model
      // it means insert a row in jnt_fic_attr
      $sql=sprintf("insert into jnt_fic_attr (fd_id,ad_id,jnt_order) values (%d,%d,%d)", 
		   $this->id,$p_ad_id,$max);
      $Res=ExecSql($this->cn,$sql);
      // update all the existing card
      
    }
    /*!\brief remove an attribut for this fiche_def
     * \param array of ad_id to remove
     * \remark you can't remove the attribut defined in attr_min
     */
     function RemoveAttribut($array) 
     {
       foreach ($array as $ch) 
	 {
	   StartSql($this->cn);
	   $sql="delete from jnt_fic_attr where fd_id=".$this->id.
	     "   and ad_id=".$ch;
	   ExecSql($this->cn,$sql);

	   $sql="delete from attr_value where jft_id in ( select ".
	     " jft_id from attr_value join jnt_fic_att_value using (jft_id) ".
	     " join fiche using(f_id) ".
	     " where ".
	     "fd_id = ".$this->id." and ".
	     "ad_id=".$ch.")";
	   ExecSql($this->cn,$sql);

	   $sql="delete from jnt_fic_att_value where jft_id in (".
	     " select jft_id from jnt_fic_att_value join fiche using (f_id) ".
	     " where ".
	     " fd_id = ".$this->id." and ".
	     " ad_id = ".$ch.")";
	   ExecSql($this->cn,$sql);
	   Commit($this->cn);
	 }
     }

  /*!\brief save the order of a card, update the column jnt_fic_attr.jnt_order
   *\param $p_array containing the order
   */
     function save_order($p_array) {
       extract($p_array);
       echo_debug('class_fiche_def::save_order',$p_array);
       $this->GetAttribut();
       foreach ($this->attribut as $row){
	 if ( $row->ad_id == 1 ) continue;
	 if ( ${'jnt_order'.$row->ad_id} <= 0 ) continue;
	 $sql='update jnt_fic_attr set jnt_order=$1 where fd_id=$2 and ad_id=$3';
	 ExecSqlParam($this->cn,$sql,array(${'jnt_order'.$row->ad_id},
					   $this->id,
					   $row->ad_id));
	 
       }
       /* correct the order */
       ExecSql($this->cn,'select attribute_correct_order()');
     }


  /*!\brief remove all the card from a categorie after having verify
   *that the card is not used and then remove also the category
   *\return the remains items, not equal to 0 if a card remains and
   *then the category is not removed
   */
     function remove() {
       $remain=0;
       /* get all the card */
       $aFiche=fiche::get_fiche_def($this->cn,$this->id);
       if ( $aFiche != null ) {
	 /* check if the card is used */
	 foreach ($aFiche as $fiche) {      

	   /* if the card is not used then remove it otherwise increment remains */
	   if ( $fiche->is_used() == false ) {
	     $fiche->delete();
	   } else 
	   $remain++;
	 }
       }
	 /* if remains == 0 then remove cat */
	 if ( $remain == 0 ) {
	   $sql='delete from jnt_fic_attr where fd_id=$1';
	   ExecSqlParam($this->cn,$sql,array($this->id));
	   $sql='delete from fiche_def where fd_id=$1';
	   ExecSqlParam($this->cn,$sql,array($this->id));
       }

       return $remain;

     }



}
?>
