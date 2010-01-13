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
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/* $Revision$ */
/*! \file
 * \brief Search a card in a popup window
 *
 * This parameter given to this file via the javascript popup windows are 
 * caller = searchcard
   * - first  when is the first call it doesn't lookup directly
   * - search
   * - p_jrn the ledger id
   * - PHPSESSID
   * - type  deb only the deb card from the ledger, cred means credit only, all the card, filter all the card of the ledger
   * - name of the control
   * - gDossier the dossier id
   * - the caller (what jascript function triggers this window)
   *
   *  caller = searchcardCtrl
   * - first 
   * - searh
   * - p_jrn the ledger id
   * - PHPSESSID
   * - type   
   *     - deb only the deb card from the ledger, 
   *     - cred means credit only, 
   *     - all the card, 
   *     - filter     get int the setting of the ledger the FD_ID 
   *     - List of value (fd_id)
   *     - start with the [sql] tag if we use a SQL where clause
   * - name of the control
   * - ctrl the second control to set up
   * - gDossier the dossier id
   * -the caller (what jascript function triggers this window)
   * 
   *\note if the p_jrn is -1 then all the card are shown
 */
print_r( $_REQUEST);
include_once ("ac_common.php");
require_once('class_database.php');
include_once("jrn.php");
require_once("class_ibutton.php");
require_once('class_dossier.php');
include_once ("class_user.php");
$gDossier=dossier::id();
$cn=new Database($gDossier);

$User=new User($cn);
$User->Check();
$User->check_dossier($gDossier);

echo JS_LEDGER;
echo JS_MINTOOLKIT;
//determine focus:
if ( isset ( $_GET['search']) )
{
  html_min_page_start($User->theme,"onLoad=\"window.focus();SetFocus('select0',0)\"");
} else
{
  html_min_page_start($User->theme,"onLoad=\"window.focus();SetFocus('fic_search',0)\"");
}



function get_list_fiche($p_cn,$get,$p_jrn)
{

  $sql="select $get as fiche from jrn_def where jrn_def_id=$1";
  $Res=$p_cn->exec_sql($sql,array($p_jrn));

  // fetch it
  $Max=$p_cn->size();
  if ( $Max==0) {
    echo_warning("Aucune fiche trouvée");
    exit();
  }
  // Normally Max must be == 1
  $list=$p_cn->fetch(0);
  if ( $list['fiche']=="") {
    echo_warning("Journal mal paramètré");
	echo_warning('Changez-en les réglages dans paramètre->journaux');
    exit();
  }
  $list_fiche=$list['fiche'];
  return $list_fiche;
}
?>
<script language="javascript">
function SetData (name_ctl,value,value_2,value_3,value_4,value_5,value_6) {
  self.opener.SetData(name_ctl,value,value_2,value_3,value_4,value_5,value_6);
   window.close();
}
function setCtrl(name_ctl,value,name_ctl2,value_3) {
	self.opener.setCtrl(name_ctl,value,name_ctl2,value_3);
	window.close();
	}
</script>
<?php
$cn=new Database($gDossier);
$r="";
// Propose to add a card if the ledger is not 0 (the great ledger)

$add=1;
if ( isset($_REQUEST['add'])&& $_REQUEST['add']=='no' ) $add=0;

if ($add==1 && $User->check_action(FICADD)==1 ) {
  if ( $User->check_action(FICADD)==1) {
    $add_card=new IButton();
    $add_card->javascript=sprintf("NewCard('%s','%s','%s',%s)",
				  $_REQUEST['PHPSESSID'],
				  $_GET['type'],
				  "fic_search",
				  $_REQUEST['p_jrn']);
    $add_card->label="Ajout d'une fiche";
  }
}
foreach ($_GET as $key=>$element) {
  // The value are e_name e_type e_PHPSESSID
  ${"e_$key"}=$element;

}
extract($_GET,EXTR_PREFIX_ALL ,'e_');

$e_fic_search=(isset ($_REQUEST['fic_search']))?$_REQUEST['fic_search']:"";

$r.="<FORM METHOD=\"GET\" >";
$r.="Recherche : ".'<INPUT TYPE="TEXT" id="fic_search" NAME="fic_search" VALUE="'.$e_fic_search.'">';
if ( isset($_REQUEST['noadd' ])) $r.=HtmlInput::hidden('noadd','noadd');
$r.='<INPUT TYPE="submit" name="search" value="Go">';
if ( isset ($_REQUEST['p_jrn']))
  $r.= HtmlInput::hidden('p_jrn',$_REQUEST ['p_jrn']);
$r.= dossier::hidden();
$r.="<div>";
echo $r;
$r="";
foreach ($_GET as $k=>$h)
{
  if ( $k != "fic_search" && $k != "first" )
    echo '<input type="HIDDEN" name="'.
      $k.'" value="'.$h.'">';
}
// Show result of the search 
if ( 
    (isset($_GET['first']) && strlen(trim($_GET['fic_search'])) != 0) 
    || ! isset($_GET['first'] )
    )
  {

    if ( $_GET['p_jrn'] == -1 )
      $e_type='all';
    
    // Get the field from database
    if ( $e_type == 'deb' ) {
      $get='jrn_def_fiche_deb';
      $list_fiche=get_list_fiche($cn,$get,$_GET['p_jrn']);
      $sql="select * from vw_fiche_attr where fd_id in ( $list_fiche )";
    }
    elseif ( $e_type == 'cred' ) {
      $get='jrn_def_fiche_cred';
      $list_fiche=get_list_fiche($cn,$get,$_GET['p_jrn']);
      $sql="select * from vw_fiche_attr where fd_id in ( $list_fiche )";
    }
    /*!\brief if $e_type (from widget::extra) equal all, all the card are shown 
     * without restriction
     */
    elseif ( $e_type == 'all' ) {
      $sql="select * from vw_fiche_attr where true";
    }
    elseif ($e_type=='filter') {
      
      $get='jrn_def_fiche_cred';
      $list_cred=get_list_fiche($cn,$get,$_GET['p_jrn']);
      
      
      $get='jrn_def_fiche_deb';
      $list_deb=get_list_fiche($cn,$get,$_GET['p_jrn']);
      $list_fiche=$list_cred.','.$list_deb;
      
      if ( $list_fiche == ',' ) 
	exit("Vous n'avez pas bien configure ce journal, vous devez aller dans Paramètre->Journal et indiquez les fiches utilisables");
      
      $sql="select * from vw_fiche_attr where fd_id in ( $list_fiche )";
      
    }  elseif (strpos($e_type,'sql') != 0 ) {
      $t=str_replace('[sql]',' ',$e_type);
      $sql="select * from vw_fiche_attr where true $t";
    }
    // if e_type contains a list of value for filtering on fiche_def_ref.frd_id
    else{
      $list_fiche=$e_type;
      $sql="select * from vw_fiche_attr where fd_id in ( $list_fiche )";
    }
 
    // e_fic_search contains the pattern
    
    if (strlen(trim($e_fic_search) ) == 0 ) {
      $Res=$cn->exec_sql($sql); 
    } else {
      $e_fic_search=FormatString($e_fic_search);
      $Res=$cn->exec_sql("$sql and ( upper(vw_name) like upper('%$e_fic_search%') or ".
		   "upper(quick_code) like upper('%$e_fic_search%'))" ); 
    }
    
    // Test whether rows are returned
    if ( ($Max = Database::num_row($Res) ) == 0 && $_GET['p_jrn'] != 0) {
      echo_warning("Pas de fiche trouvée");
      if (isset($add_card)) echo $add_card->input();
      return;
 } 
 // Show the cards
 for ( $i=0; $i < $Max; $i++)  {

  //set focus on first "Select" button.
   
  $row=Database::fetch_array($Res,$i);
  // if quick code is empty pass
  if (trim($row['quick_code']) == "")
    continue;


  if ( $i %2  == 0 ) 
    $class="even";
  else
    $class="odd";
  $text=FormatString($row['vw_name']);
  $r.="<span class=\"$class\">";
  $qcode=    $row['quick_code'] ;
if ( $e_caller=='searchcard') {
// SetData set the quickcode, name, price, and VAT
  $r.=sprintf ('<input name="%s" type="button" onClick="'."SetData('%s','%s','%s','%s','%s','%s','%s')".'" value="%s">',
        "select" . $i,
	      $e_name,
	      $row['quick_code'] ,
	      $text, 
	      $row['vw_sell'], 
	      $row['vw_buy'], 
	      $row['tva_id'], 
	       $row['tva_label']  ,
	       $qcode
	       );
	} else {
	//SetCtrl set only the quickcode and the label in the control given as parameter
	$r.=sprintf ('<input name="%s" type="button" onClick="'."setCtrl('%s','%s','%s','%s')".'" value="%s">',
		     "select" . $i,
		     $e_name,
		     $row['quick_code'] ,
		     $e_extra,
		     $text,
		     $row['quick_code']
		     );
	}
  $r.="&nbsp;".h($row['vw_name']);
  if ( $row['vw_addr'] !="")
    $r.="<br><font size=-1>Adresse:&nbsp;".h($row['vw_addr'])."&nbsp;".h($row['vw_cp'])."</font>";
  $r.="</span>";
 }
}
$r.="</div>";
$r.="</FORM>";
echo $r;

?>

<?php
if ( isset($add_card) )echo $add_card->input();
html_page_stop();
?>
