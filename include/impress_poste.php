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
include_once("class_widget.php");
/*! \file
 * \brief Print account (html or pdf)
 *        file included from user_impress
 *
 * some variable are already defined $cn, $User ...
 * 
 */

//-----------------------------------------------------
// If print is asked
// First time in html
// after in pdf or cvs
//-----------------------------------------------------
if ( isset( $_POST['bt_html'] ) ) {
  include("class_poste.php");
  $go=0;
// we ask a poste_id
  if ( strlen(trim($_POST['poste_id'])) != 0 && isNumber($_POST['poste_id']) )
    {
      if ( isset ($_POST['poste_fille']) )
      {
	$parent=$_POST['poste_id'];
	$a_poste=getarray($cn,"select pcm_val from tmp_pcmn where pcm_val like '$parent%'");
	$go=3;
      } 
      // Check if the post is numeric and exists
      elseif (  CountSql($cn,'select * from tmp_pcmn where pcm_val='.FormatString($_POST['poste_id'])) != 0 )
	{
	  $Poste=new poste($cn,$_POST['poste_id']);$go=1;
	}
    }
  if ( strlen(trim($_POST['f_id'])) != 0 )
    {
      require_once("class_fiche.php");
      // thanks the qcode we found the poste account
      $fiche=new fiche($cn);
      $qcode=$fiche->GetByQCode($_POST['f_id']);
      $p=$fiche->strAttribut(ATTR_DEF_ACCOUNT);
      if ( $p != "- ERROR -") {
	$go=2;  
      }
   }
  
  // A account  is given
  if ( $go == 1) 
    {
      echo '<div class="u_content">';
      $Poste->HtmlTableHeader();
      $Poste->HtmlTable();
      $Poste->HtmlTableHeader();
      echo "</div>";
      exit;
   }
  
  // A QuickCode  is given
  if ( $go == 2) 
    {
      echo '<div class="u_content">';
      $fiche->HtmlTableHeader();
      $fiche->HtmlTable($qcode);
      $fiche->HtmlTableHeader();
      echo "</div>";
      exit;
   }

  // All the children account
  if ( $go == 3 )
    {

      if ( sizeof($a_poste) == 0 ) 
	exit;
      echo '<div class="u_content">';


      $Poste=new poste($cn,$_POST['poste_id']);
      $Poste->HtmlTableHeader($_POST['poste_id']);
      $Poste->HtmlTable();

      foreach ($a_poste as $poste_id ) 
	{
	  $Poste=new poste ($cn,$poste_id['pcm_val']);
	  $Poste->HtmlTable();
	}
      $Poste->HtmlTableHeader($_POST['poste_id']);
      echo "</div>";
      
      exit;
    }
} 
//-----------------------------------------------------
// Show the jrn and date
//-----------------------------------------------------
include_once("postgres.php");
//-----------------------------------------------------
// Form
//-----------------------------------------------------
echo '<div class="u_content">';
echo JS_SEARCH_POSTE;
echo JS_SEARCH_CARD;
echo '<FORM ACTION="?p_action=impress&type=poste" METHOD="POST">';
echo '<TABLE><TR>';
$span=new widget("span");

$w=new widget("js_search_poste");
$w->table=1;
$w->label="Choississez le poste";
print $w->IOValue("poste_id");
echo $span->IOValue('poste_id_label');
$w_poste=new widget("js_search_only");
$w_poste->table=1;
$w_poste->label="Ou Choississez la fiche";
$w_poste->extra='all';
print $w_poste->IOValue("f_id");
echo $span->IOValue('f_id_label');
print '</TR>';
print '<TR>';
// filter on the current year
$select=new widget("select");
$select->table=1;
$filter_year=" where p_exercice='".$User->getExercice()."'";
$periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by p_start,p_end");
$select->label="Depuis";
print $select->IOValue('from_periode',$periode_start);
$select->label=" jusqu'à ";
$periode_end=make_array($cn,"select p_id,to_char(p_end,'DD-MM-YYYY') from parm_periode  $filter_year order by p_start,p_end");
print $select->IOValue('to_periode',$periode_end);
print "</TR>";
print "<TR><TD>";
$all=new widget("checkbox");
$all->label="Tous les postes qui en dépendent";
$all->disabled=false;
echo $all->IOValue("poste_fille");
echo '</TABLE>';
print $w->Submit('bt_html','Impression');

echo '</FORM>';
echo '</div>';
?>
