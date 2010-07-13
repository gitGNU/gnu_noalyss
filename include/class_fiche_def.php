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
include_once("class_fiche_attr.php");
require_once("class_itext.php");
require_once('class_fiche_def_ref.php');
require_once('class_fiche.php');
require_once('user_common.php');
require_once('class_iradio.php');

/*! \file
 * \brief define Class fiche and fiche def, those class are using
 *        class attribut
 */
/*!
 * \brief define Class fiche and fiche def, those class are using
 *        class attribut
 */
class Fiche_Def {
  var $cn;           // database connection
  var $id;			// id (fiche_def.fd_id
  var $label;			// fiche_def.fd_label
  var $class_base;		// fiche_def.fd_class_base
  var $fiche_def;		// fiche_def.frd_id = fiche_def_ref.frd_id
  var $create_account;		// fd_create_account: flag
  var $all;
  var $attribut;		// get from attr_xxx tables
  function __construct($p_cn,$p_id = 0) {
    $this->cn=$p_cn;
    $this->id=$p_id;
  }
/*!\brief show the content of the form to create  a new Fiche_Def_Ref
*/
function input () 
{
  $ref=$this->cn->get_array("select * from fiche_def_ref order by frd_text");
  $iradio=new IRadio();
  /* the accounting item */
  $class_base=new IPoste('class_base');
  $class_base->set_attribute('ipopup','ipop_account');
  $class_base->set_attribute('account','class_base');
  $class_base->set_attribute('label','acc_label');
  $f_class_base=$class_base->input();
  require_once ('template/fiche_def_input.php');
  return;
}

/*!
 *  \brief  Get attribut of a fiche_def
 *        
 * \return string value of the attribute
 */
  function GetAttribut() {
    $sql="select * from jnt_fic_attr ".
      " natural join attr_def where fd_id=".$this->id.
      " order by jnt_order";

    $Ret=$this->cn->exec_sql($sql);

    if ( ($Max=Database::num_row($Ret)) == 0 )
      return ;
    for ($i=0;$i < $Max;$i++) {
      $row=Database::fetch_array($Ret,$i);
      $t = new Fiche_Attr($this->cn);
      $t->ad_id=$row['ad_id'];
      $t->ad_text=$row['ad_text'];
      $t->jnt_order=$row['jnt_order'];
      $t->ad_size=$row['ad_size'];
      $t->ad_type=$row['ad_type'];
      $this->attribut[$i]=$t;
    }
    return $this->attribut;
  }

 /*!
 * \brief  Get attribut of the fiche_def
 *        
 */
  function Get() {
    if ( $this->id == 0 ) 
      return 0;
    $this->cn->exec_sql('select fiche_attribut_synchro($1)',
		 array($this->id));

    $sql="select * from fiche_def ".
      " where fd_id=".$this->id;
    $Ret=$this->cn->exec_sql($sql);
    if ( ($Max=Database::num_row($Ret)) == 0 )
      return ;
    $row=Database::fetch_array($Ret,0);
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

    $Ret=$this->cn->exec_sql($sql);
    if ( ($Max=Database::num_row($Ret)) == 0 )
      return ;

    for ( $i = 0; $i < $Max;$i++) {
      $row=Database::fetch_array($Ret,$i);
      $this->all[$i]=new Fiche_Def($this->cn,$row['fd_id']);
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
   return ($this->cn->count_sql("select * from vw_fiche_def where ad_id=$p_attr and fd_id=".$this->id)>0)?true:false;

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
 /*!\brief Add a fiche category thanks the element from the array 
  * you cannot add twice the same cat. name
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
     foreach ( $array as $key=>$element ) {
       ${"p_$key"}=$element;
     }
     // Format correctly the name of the cat. of card
     $p_nom_mod=FormatString($p_nom_mod);
     
     // Name can't be empty
     if ( strlen(trim($p_nom_mod)) == 0 ) 
       return;

     // $p_FICHE_REF cannot be null !!! (== fiche_def_ref.frd_id
     if (! isset ($p_FICHE_REF) || strlen($p_FICHE_REF) == 0 ) {
       alert (_('Vous devez choisir une categorie'));
       return;
     }
     $fiche_Def_ref=new Fiche_Def_Ref($this->cn,$p_FICHE_REF);
     $fiche_Def_ref->Get();
     // build the sql request for fiche_def
     // and insert into fiche_def
     // if p_class_base is null get the default class base from
     // fiche_def_ref
     if ( FormatString($p_class_base) == null )
       { // p_class is null
	 // So we take the default one
	 $p_class_base=$fiche_Def_ref->frd_class_base;
       }
     /* check if the cat. name already exists */
     $sql="select count(*) from fiche_Def where upper(fd_label)=upper($1)";
     $count=$this->cn->get_value($sql,array(trim($p_nom_mod)));
     if ($count != 0 ) return -1;
     // Set the value of fiche_def.fd_create_account
     // automatic creation for 'poste comptable'
     if ( isset($p_create) && strlen(trim($p_class_base)) != 0) 
       $p_create='true';
     else
       $p_create='false';
     
     // Class is valid ?
     if ( FormatString($p_class_base) != null || strpos(',',$p_class_base) != 0 ) {
       
       // p_class is a valid number
       $sql="insert into fiche_def(fd_label,fd_class_base,frd_id,fd_create_account) 
                values ($1,$2,$3,$4) returning fd_id";

       $this->id=$this->cn->get_value($sql,array($p_nom_mod,$p_class_base,$p_FICHE_REF,$p_create));

       // p_class must be added to tmp_pcmn if it is a single accounting       
       if ( strpos(',',$p_class_base) ==0) {
	 $sql="select account_add($1,$2)";
	 $Res=$this->cn->exec_sql($sql,array($p_class_base,$p_nom_mod));
       }

       // Get the fd_id
       $fd_id=$this->cn->get_current_seq('s_fdef');
    
       // update jnt_fic_attr       
       $sql=sprintf("insert into jnt_fic_attr(fd_id,ad_id,jnt_order) 
                     values (%d,%d,10)",$fd_id,ATTR_DEF_ACCOUNT);
       $Res=$this->cn->exec_sql($sql);

     } else {
       //There is no class base not even as default
       $sql=sprintf("insert into fiche_def(fd_label,frd_id,fd_create_account) values ('%s',%d,'%s') returning fd_id",
		    $p_nom_mod,$p_FICHE_REF,$p_create);
       
       $this->id=$this->cn->get_value($sql);
       
       // Get the fd_id
       $fd_id=$this->cn->get_current_seq('s_fdef');
       
     }
     
     // Get the default attr_def from attr_min
     $def_attr=$this->get_attr_min($p_FICHE_REF);
     
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
	 $this->cn->exec_sql($sql);
       }
     }
     $this->id=$fd_id;
     return 0;     
     
   }//--------------end function Add ----------------------------
/*! 
 * \brief Get all the card where the fiche_def.fd_id is given in parameter
 * \param $step = 0 we don't use the offset, page_size,...
 *        $step = 1 we use the jnr_bar_nav
 *
 * \return array of object fiche
 *\see fiche
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

    $Ret=$this->cn->exec_sql($sql);
    if ( ($Max=Database::num_row($Ret)) == 0 )
      return ;
    $all[0]=new Fiche($this->cn);

    for ($i=0;$i<$Max;$i++) {
      $row=Database::fetch_array($Ret,$i);
      $t=new Fiche($this->cn,$row['f_id']);
      $t->getAttribut();
      $all[$i]=$t;

    }
    return $all;
  }
/*! 
 * \brief Get all the card where the fiche_def.frd_id is given in parameter
 * \return array of fiche or null is nothing is found
 */
  function get_by_category($p_cat) {
    $sql="select f_id
           from
               fiche join fiche_def using(fd_id)
            where frd_id=$1";

    $Ret=$this->cn->exec_sql($sql,array($p_cat));
    if ( ($Max=Database::num_row($Ret)) == 0 )
      return null;
    $all[0]=new Fiche($this->cn);

    for ($i=0;$i<$Max;$i++) {
      $row=Database::fetch_array($Ret,$i);
      $t=new Fiche($this->cn,$row['f_id']);
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
	$max_line=$this->cn->count_sql("select f_id,av_text  from 
                          fiche join jnt_fic_att_value using (f_id) 
                                join attr_value using (jft_id)
                       where fd_id='".$this->id."' and ad_id=".ATTR_DEF_NAME." order by f_id");
	$sql_limit=" limit ".$step;
	$sql_offset=" offset ".$offset;
	$bar=jrn_navigation_bar($offset,$max_line,$step,$page);
      }
      
      // Get all name the cards of the select category
      // 1 for attr_def.ad_id is always the name
      $Res=$this->cn->exec_sql("select f_id,vw_name,quick_code  from ".
		   " vw_fiche_attr ".
		   " where fd_id='".$this->id.
		   "' order by f_id $sql_offset $sql_limit ");
      $Max=Database::num_row($Res);
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
      echo HtmlInput::hidden("fiche",$this->id);
      echo HtmlInput::submit('add','Ajout fiche');
      echo '</FORM>';
      $str_dossier=dossier::get();
      echo '<table>';
      for ( $i = 0; $i < $Max; $i++) {
		$l_line=Database::fetch_array($Res,$i);
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
      echo HtmlInput::hidden("fiche",$this->id);
      echo HtmlInput::submit('add','Ajout fiche');
      echo '</FORM>';
      echo $bar;

    }
  /*!\brief show input for the basic attribute : label, class_base, create_account
   * use only when we want to update
   * 
   *\return HTML string with the form
   */
  function input_base() {
    $r="";
    $r.=_('Label');
    $label=new IText('label',$this->label);
    $r.=$label->input();
    $r.='<br>';
    /* the accounting item */
    $class_base=new IPoste('class_base',$this->class_base);
    $class_base->set_attribute('ipopup','ipop_account');
    $class_base->set_attribute('account','class_base');
    $class_base->set_attribute('label','acc_label');
    $r.=_('Poste Comptable de base').' : ';
    $r.=$class_base->input();
    $r.='<span id="acc_label"></span><br>';
    /* auto Create */
    $ck=new ICheckBox('create');
    $ck->selected=($this->create_account=='f')?false:true;
    $r.=_('Chaque fiche aura automatiquement son propre poste comptable : ');
    $r.=$ck->input();
    return $r;
  }
  /*!\brief Display all the attribut of the fiche_def
   *\param $str give the action possible values are remove, empty 
   */
  function DisplayAttribut($str="")
    {
      if ( $this->id == 0 )
	return ;
      $this->cn->exec_sql('select fiche_attribut_synchro($1)',
		   array($this->id));

      $MaxLine=sizeof($this->attribut);
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
	  continue;
	} else {
		if ( $str == "remove" ) {
		  //Only for the not mandatory attribute (not defined in attr_min)
		  if ( $this->cn->count_sql("select * from attr_min where frd_id=".
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
	  $r.='</td><td> '.$order->input();
	}
	$r.= '</td></tr>';
      }
      
      // Show the possible attribute which are not already attribute of the model
      // of card
      $Res=$this->cn->exec_sql("select ad_id,ad_text from attr_def 
                       where 
                 ad_id not in (select ad_id from fiche_def natural join jnt_fic_attr
                           where fd_id=".$this->id.")");
      $M=Database::num_row($Res);
      
      // Show the unused attribute
      $r.='<TR> <TD>';
      $r.= '<SELECT NAME="ad_id">';
      for ($i=0;$i<$M;$i++) {	$l=Database::fetch_array($Res,$i);
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
	return;
      }
      $sql=sprintf("update   fiche_def set fd_label='%s' ".
		   "where                    fd_id=%d", 
		   $p_label,$this->id);
      $Res=$this->cn->exec_sql($sql);
      
    }
  /*!\brief set the auto create accounting item for each card and
   * save it into the database
   * \param $p_label true or false
   */
  function set_autocreate($p_label)
    {
      if ( $this->id == 0 ) return;
      if ($p_label==true) 
	$t='t';
      if ($p_label==false)
	$t='f';

      $sql="update   fiche_def set fd_create_account=$1 ".
	"where                    fd_id=$2";

      $Res=$this->cn->exec_sql($sql,array($t,$this->id));
      
    }
  /*!\brief Save the class base
   * \param $p_label label
   */
  function save_class_base($p_label)
    {
      if ( $this->id == 0 ) return;
      $p_label=FormatString($p_label);

      $sql="update   fiche_def set fd_class_base=$1 ".
	"where                    fd_id=$2";

      $Res=$this->cn->exec_sql($sql,array($p_label,$this->id));
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
      $Res=$this->cn->exec_sql($sql);
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
	   $this->cn->start();
	   $sql="delete from jnt_fic_attr where fd_id=".$this->id.
	     "   and ad_id=".$ch;
	   $this->cn->exec_sql($sql);

	   $sql="delete from attr_value where jft_id in ( select ".
	     " jft_id from attr_value join jnt_fic_att_value using (jft_id) ".
	     " join fiche using(f_id) ".
	     " where ".
	     "fd_id = ".$this->id." and ".
	     "ad_id=".$ch.")";
	   $this->cn->exec_sql($sql);

	   $sql="delete from jnt_fic_att_value where jft_id in (".
	     " select jft_id from jnt_fic_att_value join fiche using (f_id) ".
	     " where ".
	     " fd_id = ".$this->id." and ".
	     " ad_id = ".$ch.")";
	   $this->cn->exec_sql($sql);
	   $this->cn->commit();
	 }
     }

  /*!\brief save the order of a card, update the column jnt_fic_attr.jnt_order
   *\param $p_array containing the order
   */
     function save_order($p_array) {
       extract($p_array);
       $this->GetAttribut();
       foreach ($this->attribut as $row){
	 if ( $row->ad_id == 1 ) continue;
	 if ( ${'jnt_order'.$row->ad_id} <= 0 ) continue;
	 $sql='update jnt_fic_attr set jnt_order=$1 where fd_id=$2 and ad_id=$3';
	 $this->cn->exec_sql($sql,array(${'jnt_order'.$row->ad_id},
					   $this->id,
					   $row->ad_id));
	 
       }
       /* correct the order */
       $this->cn->exec_sql('select attribute_correct_order()');
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
	   $this->cn->exec_sql($sql,array($this->id));
	   $sql='delete from fiche_def where fd_id=$1';
	   $this->cn->exec_sql($sql,array($this->id));
       }

       return $remain;

     }
/*!   
 * \brief  retrieve the mandatory field of the card model
 *        
 * \param $p_fiche_def_ref 
 * \return array of ad_id  (attr_min.ad_id) and  labels (attr_def.ad_text)
 */
function get_attr_min($p_fiche_def_ref) {

  // find the min attr for the fiche_def_ref
  $Sql="select ad_id,ad_text from attr_min natural join attr_def 
         natural join fiche_def_ref
      where
      frd_id= $1";
  $Res=$this->cn->exec_sql($Sql,array($p_fiche_def_ref));
  $Num=Database::num_row($Res);

  // test the number of returned rows
  if ($Num == 0 ) return null;

  // Get Results & Store them in a array
  for ($i=0;$i<$Num;$i++) {
    $f=Database::fetch_array($Res,$i);
    $array[$i]['ad_id']=$f['ad_id'];
    $array[$i]['ad_text']=$f['ad_text'];
  }
  return $array;
}
  /*!\brief count the number of fiche_def (category) which has the frd_id (type of category)
   *\param $p_frd_id is the frd_id in constant.php the FICHE_TYPE_
   *\return the number of cat. of card of the given type
   *\see constant.php
   */
function count_category($p_frd_id) {
  $ret=$this->cn->count_sql("select fd_id from fiche_def where frd_id=$1",array($p_frd_id));
  return $ret;
}



}
?>
