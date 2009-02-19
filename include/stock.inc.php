
<?php  
/*
 *   This file is part of PHPCOMPTA.
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
 *   along with PHPCOMPTA; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
// Auteur Dany De Bontridder ddebontridder@yahoo.fr

/* $Revision$ */

/*!\file 
 * \brief Manage the stock by year
 */
require_once('class_dossier.php');
include_once("preference.php");
include_once ("ac_common.php");
include_once("postgres.php");
include_once("stock_inc.php");
include_once("check_priv.php");
require_once('class_dossier.php');
$gDossier=dossier::id();

html_page_start($_SESSION['g_theme']);

include_once ("postgres.php");
/* Admin. Dossier */
$gDossier=dossier::id();

$cn=DbConnect($gDossier);
include_once ("class_user.php");
$User=new User($cn);
$User->Check();


$href=basename($_SERVER['PHP_SELF']);
if ($href=='compta.php')
{
  //Show the top menu
  include_once ("user_menu.php");
  
  // Show Menu Left
  $left_menu=ShowMenuAdvanced(5);
  //echo '<div class="lmenu">';
  echo $left_menu;
}
// Get The priv on the selected folder
$User->can_request(STOLE,1);


$action= ( isset ($_GET['action']))? $_GET['action']:"";
include_once("stock_inc.php");

// Adjust the stock
if ( isset ($_POST['sub_change'])) 
{
  $User->can_request(STOWRITE,1);
  $change=$_POST['stock_change'];
  $sg_code=$_POST['sg_code'];
  $sg_date=$_POST['sg_date'];
  $year=$_POST['year'];
  $comment=$_POST['comment'];

  if ( isDate($sg_date) == null 
       or isNumber($change) == 0 
       or isNumber($year) == 0 ) 
    {
      $msg="Stock données non conformes";
      echo "<script> alert('$msg');</script>";
      echo_error($msg);
    } else 
      {
	// Check if User Can change the stock 
	if ( check_action($gDossier,$_SESSION['g_user'],STOCK_WRITE) == 0 ) {
	  NoAccess();
	  exit (-1);
    }

    // if neg the stock decrease => credit
    $type=( $change < 0 )?'c':'d';
    if ( $change != 0)
      {
	$comment=FormatString($comment);
	$Res=ExecSql($cn,"insert into stock_goods
                     (  j_id,
                        f_id, 
                        sg_code,
                        sg_quantity,
                        sg_type,
                        sg_date,
                        sg_exercice,
                        sg_comment,
                         sg_tech_user)
                    values (
                        null,
                        0,
                        '$sg_code',
                        abs($change),
                        '$type',
                        to_date('$sg_date','DD.MM.YYYY'),
                        '$year',
                        '$comment',
                        '".$_SESSION['g_user']."');
                     ");
      }
  // to update the view
  $action="detail";
  }
}
echo JS_VIEW_JRN_MODIFY;
// View the summary

// if year is not set then use the year of the user's periode
if ( ! isset ($_GET['year']) ) {
  // get defaut periode
  $a=$User->get_periode();
  // get exercice of periode
  $year=get_exercice($cn,$a);
  } else
  { 
    $year=$_GET['year'];
  }

// View details
if ( $action == 'detail' ) {
  // Check if User Can see the stock 
  $User->can_request(STOLE,1);
  $sg_code=(isset ($_GET['sg_code'] ))?$_GET['sg_code']:$_POST['sg_code'];
  $year=(isset($_GET['year']))?$_GET['year']:$_POST['year'];
  $a=ViewDetailStock($cn,$sg_code,$year);
  $write=$User->check_action(STOWRITE);

  $b="";

   
  echo '<div class="u_redcontent">' ;
  echo $a;
  if ( $write != 0) 
    {
      echo 'Entrer la valeur qui doit augmenter ou diminuer le stock';
      echo '<form action="?p_action=stock" method="POST">';
      echo ChangeStock($sg_code,$year);
      echo '<input type="submit" name="sub_change" value="Ok">';
	  echo dossier::hidden();
      echo '</form>';
    }
  echo '<A class="mtitle" HREF="?p_action=stock&'.dossier::get().'"><INPUT TYPE="BUTTON" value="Retour"</A>';
  
  
  echo '</div>';
  exit();
}

// Show the possible years
$sql="select distinct (p_exercice) as exercice from parm_periode ";
$Res=ExecSql($cn,$sql);
$r="";
for ( $i = 0; $i < pg_NumRows($Res);$i++) {
  $l=pg_fetch_array($Res,$i);
  $r.=sprintf('<A class="mtitle" HREF="?p_action=stock&year=%d&'.dossier::get().'">%d</a> - ',
	      $l['exercice'],
	      $l['exercice']);
 
}
// Check if User Can see the stock 


// Show the current stock
echo '<div class="u_redcontent">';
echo $r;
$a=ViewStock($cn,$year);
if ( $a != null ) {
  echo $a;
}
echo '</div>';
html_page_stop();
?>
