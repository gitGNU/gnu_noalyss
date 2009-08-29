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

require_once('class_database.php');
require_once("class_icheckbox.php");
require_once("class_ihidden.php");
require_once("class_document.php");
require_once("class_acc_operation.php");
/*! \file
 * \brief Common functions 
 */
/*! 

/*!   
 *\brief  show all the lines of the asked jrn, uses also the $_GET['o'] for the sort
 *        
 * 
 * \param $p_cn database connection
 * \param $p_where the sql query where clause
 * \param $p_array param. for a search
 * \param $p_value offset
 * \param $p_paid value : 0 nothing is shown, 1 check box; 2 check_box disable
 * \return array (entryCount,generatedHTML);
 * 
 */
function ListJrn($p_cn,$p_where="",$p_array=null,$p_value=0,$p_paid=0)
{
  $user=new User($p_cn);
  $gDossier=dossier::id();
  $amount_paid=0.0;
  $amount_unpaid=0.0;
  include_once("central_inc.php");
  $limit=($_SESSION['g_pagesize']!=-1)?" LIMIT ".$_SESSION['g_pagesize']:"";
  $offset=($_SESSION['g_pagesize']!=-1)?" OFFSET ".Database::escape_string($p_value):"";
  $order="  order by jr_date_order asc,jr_internal asc";
  // Sort
  $url=CleanUrl();
  $str_dossier=dossier::get();
  $image_asc='<IMAGE SRC="image/down.png" border="0" >';
  $image_desc='<IMAGE SRC="image/up.png" border="0">';
  $image_sel_desc='<IMAGE SRC="image/select1.png">';
  $image_sel_asc='<IMAGE SRC="image/select2.png">';
  
  $sort_date="<th>  <A class=\"mtitle\" HREF=\"?$url&o=da\">$image_asc</A>Date <A class=\"mtitle\" HREF=\"?$url&o=dd\">$image_desc</A></th>";
  $sort_description="<th>  <A class=\"mtitle\" HREF=\"?$url&o=ca\">$image_asc</A>Description <A class=\"mtitle\" HREF=\"?$url&o=cd\">$image_desc</A></th>";
  $sort_amount="<th>  <A class=\"mtitle\" HREF=\"?$url&o=ma\">$image_asc</A>Montant <A class=\"mtitle\" HREF=\"?$url&o=md\">$image_desc</A></th>";
  $sort_pj="<th>  <A class=\"mtitle\" HREF=\"?$url&o=pja\">$image_asc</A>pj <A class=\"mtitle\" HREF=\"?$url&o=pjd\">$image_desc</A></th>";
  $sort_echeance="<th>  <A class=\"mtitle\" HREF=\"?$url&o=ea\">$image_asc</A>Ech. <A class=\"mtitle\" HREF=\"?$url&o=ed\">$image_desc</A> </th>";

$own=new Own($p_cn);
  // if an order is asked
  if ( isset ($_GET['o']) ) 
    {
      switch ($_GET['o'])
	{
	case 'pja':
	  // pj asc
	  $sort_pj="<th>$image_sel_asc PJ <A class=\"mtitle\" HREF=\"?$url&o=pjd\">$image_desc</A></th>";
	  $order=" order by jr_pj_number asc ";
	  break;
	case 'pjd':
	  $sort_pj="<th> <A class=\"mtitle\" HREF=\"?$url&o=pja\">$image_asc</A> PJ $image_sel_desc</th>";
	  // pj desc
	  $order=" order by jr_pj_number desc ";
	  break;

	case 'da':
	  // date asc
	  $sort_date="<th>$image_sel_asc Date <A class=\"mtitle\" HREF=\"?$url&o=dd\">$image_desc</A></th>";
	  $order=" order by jr_date_order asc ";
	  break;
	case 'dd':
	  $sort_date="<th> <A class=\"mtitle\" HREF=\"?$url&o=da\">$image_asc</A> Date $image_sel_desc</th>";
	  // date desc
	  $order=" order by jr_date_order desc ";
	  break;
	case 'ma':
	  // montant asc
	  $sort_amount="<th> $image_sel_asc Montant <A class=\"mtitle\" HREF=\"?$url&o=md\">$image_desc</A></th>";
	  $order=" order by jr_montant asc ";
	  break;
	case 'md':
	  // montant desc
	  $sort_amount="<th>  <A class=\"mtitle\" HREF=\"?$url&o=ma\">$image_asc</A>Montant $image_sel_desc</th>";
	  $order=" order by jr_montant desc ";
	  break;
	case 'ca':
	  // jr_comment asc
	  $sort_description="<th> $image_sel_asc Description <A class=\"mtitle\" HREF=\"?$url&o=cd\">$image_desc</A></th>";
	  $order=" order by jr_comment asc ";
	  break;
	case 'cd':
	  // jr_comment desc
	  $sort_description="<th>  <A class=\"mtitle\" HREF=\"?$url&o=ca\">$image_asc</A>Description $image_sel_desc</th>";
	  $order=" order by jr_comment desc ";
	  break;
	case 'ea':
	  // jr_comment asc
	  $sort_echeance="<th> $image_sel_asc Ech. <A class=\"mtitle\" HREF=\"?$url&o=ed\">$image_desc</A></th>";
	  $order=" order by jr_ech asc ";
	  break;
	case 'ed':
	  // jr_comment desc
	  $sort_echeance="<th>  <A class=\"mtitle\" HREF=\"?$url&o=ea\">$image_asc</A> Ech. $image_sel_desc</th>";
	  $order=" order by jr_ech desc ";
	  break;

	}
    }
  // set a filter for the FIN 
  $a_parm_code=$p_cn->get_array("select p_value from parm_code where p_code in ('BANQUE','COMPTE_COURANT','CAISSE')");
  $sql_fin="(";
  $or="";
  foreach ($a_parm_code as $code) {
    $sql_fin.="$or j_poste::text like '".$code['p_value']."%'";
    $or=" or ";
  }
  $sql_fin.=")";
  /*  compute the sql stmt */
  $sql=Acc_Ledger::compute_sql($p_array,$order,$p_where);

  // Count 
  $count=$p_cn->count_sql($sql);
  // Add the limit 
  $sql.=$limit.$offset;

  // Execute SQL stmt
  $Res=$p_cn->exec_sql($sql);

  //starting from here we can refactor, so that instead of returning the generated HTML, 
  //this function returns a tree structure.
  
  $r="";

  $r.=JS_VIEW_JRN_CANCEL;
  $r.=JS_VIEW_JRN_MODIFY;

  $Max=Database::num_row($Res);

  if ($Max==0) return array(0,"Aucun enregistrement trouv&eacute;");

  $r.='<table class="result">';
  $l_sessid=$_REQUEST['PHPSESSID'];

  $r.="<tr >";
  $r.="<th>Internal</th>";
  $r.=$sort_date;
  $r.=$sort_echeance;
  $r.=$sort_pj;
  $r.=$sort_description;
  $r.=$sort_amount;
  // if $p_paid is not equal to 0 then we have a paid column
  if ( $p_paid != 0 ) 
    {
      $r.="<th> Pay&eacute;</th>";
    }
  $r.="<th>Op. Concern&eacute;e</th>";
  if ($own->MY_STRICT=='N' &&  $user->check_action(GEOP)==1)
    $r.='<th>Action</th>';
  $r.="<th>Document</th>";
  $r.="</tr>";
  // Total Amount
  $tot=0.0;
  $gDossier=dossier::id();
  for ($i=0; $i < $Max;$i++) {

    
    $row=Database::fetch_array($Res,$i);
    
    if ( $i % 2 == 0 ) $tr='<TR class="odd">'; 
		else $tr='<TR class="even">';
    $r.=$tr;
    //internal code
	// button  modify
    $r.="<TD>";
    // If url contains
    //   

    $href=basename($_SERVER['PHP_SELF']);
	echo_debug(__FILE__,__LINE__,"href = $href");
    switch ($href)
      {
		// user_jrn.php
      case 'compta.php':
		$vue="E"; //Expert View
		break;
      case 'commercial.php':
		$vue="S"; //Simple View
		break;
	  case 'recherche.php':
		$vue=(isset($_GET['expert']))?'E':'S';
		break;
      default:
		echo_error('user_form_ach.php',__LINE__,'Erreur invalid request uri');
		exit (-1);
      }
    //DEBUG
    //    $r.=$l_sessid;
    $r.=sprintf('<A class="detail" HREF="javascript:modifyOperation(\'%s\',\'%s\',\'%s\',\'%s\',\'%s\')" >%s</A>',
				$row['jr_id'], $l_sessid,$gDossier, $row['jrn_def_id'],$vue, $row['jr_internal']);
    $r.="</TD>";
    // date
    $r.="<TD>";
    $r.=$row['jr_date'];
    $r.="</TD>";
    // echeance
    $r.="<TD>";
    $r.=$row['jr_ech'];
    $r.="</TD>";

    // pj
    $r.="<TD>";
    $r.=$row['jr_pj_number'];
    $r.="</TD>";
    
    // comment
    $r.="<TD>";
    $tmp_jr_comment=h($row['jr_comment']);
    $r.=$tmp_jr_comment;
    $r.="</TD>";
    
    // Amount
    // If the ledger is financial :
    // the credit must be negative and written in red
    $positive=0;

    // Check ledger type : 
     if (  $row['jrn_def_type'] == 'FIN' ) 
     {
       $positive = $p_cn->count_sql("select * from jrn inner join jrnx on jr_grpt_id=j_grpt ".
 			   " where jr_id=".$row['jr_id']." and $sql_fin ".
 			   " and j_debit='f'");
     }
    $r.="<TD align=\"right\">";

    $tot=($positive != 0)?$tot-$row['jr_montant']:$tot+$row['jr_montant'];
    //STAN $positive always == 0
     $r.=( $positive != 0 )?"<font color=\"red\">  - ".sprintf("%8.2f",$row['jr_montant'])."</font>":sprintf("%8.2f",$row['jr_montant']);
    $r.="</TD>";


    // Show the paid column if p_paid is not null
    if ( $p_paid !=0 )
      {
		$w=new ICheckBox();
		$w->name="rd_paid".$row['jr_id'];
		$w->selected=($row['jr_rapt']=='paid')?true:false;
		// if p_paid == 2 then readonly
		$w->readonly=( $p_paid == 2)?true:false;
		$h=new IHidden();
		$h->name="set_jr_id".$row['jr_id'];
		$r.='<TD>'.$w->input().$h->input().'</TD>';
		if ( $row['jr_rapt']=='paid') 
		  $amount_paid+=$row['jr_montant'];
		else
		  $amount_unpaid+=$row['jr_montant'];
      }
    
    // Rapprochement
    $rec=new Acc_Reconciliation($p_cn);
    $rec->set_jr_id($row['jr_id']);
    $a=$rec->get();
    $r.="<TD>";
    if ( $a != null ) {
      
      foreach ($a as $key => $element) 
      {      
	$operation=new Acc_Operation($p_cn);
	$operation->jr_id=$element;
	$l_amount=$p_cn->get_value("select jr_montant from jrn ".
					 " where jr_id=$element");
	$r.= "<A class=\"detail\" HREF=\"javascript:modifyOperation('".$element."','".$l_sessid."',".$gDossier.")\" > ".$operation->get_internal()." [ $l_amount &euro; ]</A>";
      }//for
    }// if ( $a != null ) {
    $r.="</TD>";

    if ( $row['jr_valid'] == 'f'  ) {
      $r.="<TD> Op&eacute;ration annul&eacute;e</TD>";
    }    else if ( $own->MY_STRICT=='N' ) {
      // all operations can be removed either by setting to 0 the amount
      // or by writing the opposite operation if the period is closed
      $r.="<TD>";
      // cancel operation
      if ( $user->check_action(GEOP)==1)
	$r.=sprintf('<input TYPE="BUTTON" VALUE="%s" onClick="cancelOperation(\'%s\',\'%s\',%d,\'%s\')">',
		    "Annuler",$row['jr_grpt_id'],$l_sessid,$gDossier,$row['jrn_def_id']);
      $r.="</TD>";
    } // else
    //document
    if ( $row['jr_pj_name'] != "") 
      {
	$image='<IMG SRC="image/insert_table.gif" title="'.$row['jr_pj_name'].'" border="0">';
	$r.="<TD>".sprintf('<A class="detail" HREF="show_pj.php?jrn=%s&jr_grpt_id=%s&%s&PHPSESSID=%s">%s</A>',
			   $row['jrn_def_id'],
			   $row['jr_grpt_id'],
			   $str_dossier,
			   $_REQUEST['PHPSESSID'],
			   $image)
			   ."</TD>";
      }
    else
      $r.="<TD></TD>";

    // end row
    $r.="</tr>";
    
  }
  $amount_paid=round($amount_paid,4);
  $amount_unpaid=round($amount_unpaid,4);
  $tot=round($tot,4);
  $r.="<TR>";
  $r.='<TD COLSPAN="5">Total</TD>';
  $r.='<TD ALIGN="RIGHT">'.$tot."</TD>";
  $r.="</tr>";
  if ( $p_paid != 0 ) {
	$r.="<TR>";
	$r.='<TD COLSPAN="5">Pay&eacute;</TD>';
	$r.='<TD ALIGN="RIGHT">'.$amount_paid."</TD>";
	$r.="</tr>";
	$r.="<TR>";
	$r.='<TD COLSPAN="5">Non pay&eacute;</TD>';
	$r.='<TD ALIGN="RIGHT">'.$amount_unpaid."</TD>";
	$r.="</tr>";
  }
  $r.="</table>";
  
  return array ($count,$r);
}



/*!   
 *\brief  Insert data into stock_goods,
 *        
 * \param  $p_cn Database object 
 * \param $p_j_id the j_id
 * \param $p_good the goods
 * \param $p_quant  quantity
 * \param $p_type c for credit or d for debit
 *
 * \return none
 * \note Link to jrn gives the date
 */
function InsertStockGoods($p_cn,$p_j_id,$p_good,$p_quant,$p_type)
{
  
  // Retrieve the good account for stock
 echo "here 114";
  $code=new fiche($p_cn);
  $code->get_by_qcode($p_good);
  $code_marchandise=$code->strAttribut(ATTR_DEF_STOCK);
  $p_good=FormatString($p_good);
  $sql="select f_id from vw_poste_qcode where j_qcode=upper('$p_good')";
  $Res=$p_cn->exec_sql($sql);
  $r=Database::fetch_array($Res,0);
  $f_id=$r['f_id'];
  $user=new User($p_cn);
  $exercice=$user->get_exercice();
  if ( $exercice == 0 ) throw new Exception ('Annee invalide erreur');


  $Res=$p_cn->exec_sql("insert into stock_goods (
                            j_id,
                            f_id,
                            sg_code, 
                            sg_quantity,
                             sg_type,sg_exercice ) values (
                            $p_j_id,
                            $f_id,
                            '$code_marchandise',
                            $p_quant, '$p_type',$exercice) 
                     ");
 return $Res;
}


/*!   isValid ($p_cn, $p_grpt_id
 **************************************************
 *\brief   test if a jrn op is valid
 *        
 * \param $p_cn db 
 * \param $p_grpt_id
 * \return:
 *        - 1 is valid
 *        - 0 is not valid
 */
function isValid ($p_cn,$p_grpt_id) {
  $Res=$p_cn->exec_sql("select jr_valid from jrn where jr_grpt_id=$p_grpt_id");

  if ( ( $M = Database::num_row($Res)) == 0 ) return 0;

  $a=Database::fetch_array($Res,0);

  if ( $a['jr_valid'] == 't') return 1;
  if ( $a['jr_valid'] == 'f') return 0;

  echo_error ("Invalid result = ".$a['result']);


}

/*!    
 **************************************************
 *\brief  
 *     Create a navigation_bar (pagesize)
 *        
 * \param $p_offset first record number  
 * \param $p_line total of returned row
 * \param $p_size current g_pagesize user's preference
 * \param $p_page number of the page where the user is 
 * \param $p_javascript javascript code to add
 * \note example :     
\verbatim
   $step=$_SESSION['g_pagesize'];
   $page=(isset($_GET['offset']))?$_GET['page']:1;
   $offset=(isset($_GET['offset']))?$_GET['offset']:0;

   list ($max_ligne,$list)=ListJrn($cn,$_GET['p_jrn'],$sql,null,$offset,1);
   $bar=jrn_navigation_bar($offset,$max_ligne,$step,$page);
\endverbatim
 * \return   string with the nav. bar
 */
function jrn_navigation_bar($p_offset,$p_line,$p_size=0,$p_page=1,$p_javascript="")
{
  echo_debug('user_common',__LINE__,"function jrn_navigation_bar($p_offset,$p_line,$p_size=0,$p_page=1)");
  // if the pagesize is unlimited return ""
  // in that case there is no nav. bar
  if ( $_SESSION['g_pagesize'] == -1  ) return "";
  if ( $p_size==0) {
    $p_size= $_SESSION['g_pagesize'];
  }
  // if there is no row return an empty string
  if ( $p_line == 0 ) return "";

  // Clean url, cut away variable coming frm here
  $url=cleanUrl();
  // action to clean
  $url=str_replace('&p_action=delete','',$url);

  // compute max of page
  $nb_page=($p_line-($p_line%$p_size))/$p_size;
  echo_debug('user_common',__LINE__,"nb_page = $nb_page");
  // if something remains
  if ( $p_line % $p_size != 0 ) $nb_page+=1;

  // if max page == 1 then return a empty string
  if ( $nb_page == 1) return "";

  // restore the sort
  if ( isset($_GET['o']))
       $url=$url.'&o='.$_GET['o'];

  $r="";
  // previous
  if ($p_page !=1) {
    $e=$p_page-1;
    $step=$p_size;
    $offset=($e-1)*$step;

    $r='<A class="mtitle" href="'.$_SERVER['PHP_SELF']."?".$url."&offset=$offset&step=$step&page=$e&size=$step".'" '.$p_javascript.'>';
    //$r.="Pr&eacute;c&eacute;dent";
    $r.='<INPUT TYPE="IMAGE" width="12" SRC="image/go-previous.png">';
    $r.="</A>&nbsp;&nbsp;";
  }
  //----------------------------------------------------------------------
  // Create a partial bar 
  // if current page < 11 show 1 to 20 
  // otherwise            show $p_page -10 to $p_page + 10
  //----------------------------------------------------------------------
  $start_bar=($p_page < 11 )?1:$p_page-10;
  $end_bar  =($p_page < 11 )?20:$p_page+10;
  $end_bar  =($end_bar > $nb_page )?$nb_page:$end_bar;

  // Create the bar
  for ($e=$start_bar;$e<=$end_bar;$e++) {
    // do not included current page
    if ( $e != $p_page ) {
      $step=$p_size;
    $offset=($e-1)*$step;

    $go=$_SERVER['PHP_SELF']."?".$url."&offset=$offset&step=$step&page=$e&size=$step";

    $r.=sprintf('<A class="mtitle" HREF="%s" CLASS="one" %s >%d</A>&nbsp;',$go,$p_javascript,$e);
    } else {
      $r.="<b> [ $e ] </b>";
    } //else
  } //for
  // next
  
  if ($p_page !=$nb_page) {
    // If we are not at the last page show the button next
    $e=$p_page+1;
    $step=$p_size;
    $offset=($e-1)*$step;

    $r.='&nbsp;<A class="mtitle" href="'.$_SERVER['PHP_SELF']."?".$url."&offset=$offset&step=$step&page=$e&size=$step".'" '.$p_javascript.' >';
    //$r.="Suivant";
    $r.='<INPUT TYPE="IMAGE" width="12" SRC="image/go-next.png">';
    $r.="</A>";
  }


  return $r;
}

/*! 
 * \brief Clean the url, remove the $_GET offset,step, page and size
 * \param none
 *
 * \return the cleaned url
 */

function CleanUrl()
{
  // Compute the url
  $url="";
  $and="";
  $get=$_GET;
  if ( isset ($get) ) {
    foreach ($get as $name=>$value ) {
      // we clean the parameter offset, step, page and size
      if (  ! in_array($name,array('offset','step','page','size','s','o'))) {
	$url.=$and.$name."=".$value;
	$and="&";
      }// if
    }//foreach
  }// if
return $url;
}
function redirect($p_string,$p_time=0) {
  echo '<HTML><head><META HTTP-EQUIV="REFRESH" content="'.$p_time.';url='.$p_string.'"></head><body> Connecting... </body></html>';
}

?>
