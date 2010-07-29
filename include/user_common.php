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
  $code=new Fiche($p_cn);
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


/*!
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
      if (  ! in_array($name,array('offset','step','page','size','s','o','r_jrn'))) {
	$url.=$and.$name."=".$value;
	$and="&";
      }// if
    }//foreach
    if ( isset($_GET['r_jrn'])) {
      $r_jrn=$_GET['r_jrn'];
      if (count($r_jrn) > 0 ){
	foreach ($r_jrn as $key=>$value) {
	  $url.=$and."r_jrn[$key]=".$value;
	  $and="&";
	}
      }
    }
  }// if
return $url;
}
function redirect($p_string,$p_time=0) {
  echo '<HTML><head><META HTTP-EQUIV="REFRESH" content="'.$p_time.';url='.$p_string.'"></head><body> Connecting... </body></html>';
}
/*!\brief remove the useless space, change comma by period and try to return
 * a number
 *\param $p_num number to format
 *\return the formatted number
 */
function toNumber($p_num) {
  $p_num=trim($p_num);
  if ($p_num=='') return 0;
  $p_num=str_replace(',','.',$p_num);
  return $p_num;
}
?>
