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
/*! \file
 * \brief Base of the module "Gestion", the p_action indicates what
 *        file must included and this file will manage the request 
 *        (customer, supplier, contact,...)
 */

include_once ("ac_common.php");
require_once("constant.php");
include_once ("postgres.php");
echo JS_AJAX_FICHE;
require_once('class_dossier.php');
$gDossier=dossier::id();

$g_name=dossier::name();


include_once ("postgres.php");
/* Admin. Dossier */
$rep=DbConnect($gDossier);
require_once ("class_user.php");
$User=new User($rep);
$User->Check();

//-----------------------------------------------------
// update preference
//-----------------------------------------------------
if ( isset ( $_POST['style_user']) ) {
	$User->update_global_pref('THEME',$_POST['style_user']);
      $_SESSION['g_theme']=$_POST['style_user'];

}
// Met a jour le pagesize
if ( isset ( $_POST['p_size']) ) {
	$User->update_global_pref('PAGESIZE',$_POST['p_size']);
      $_SESSION['g_pagesize']=$_POST['p_size'];

}

///
html_page_start($_SESSION['g_theme'],"","richtext.js");

if ( ! isset ( $gDossier ) ) {
  echo "Vous devez choisir un dossier ";
  exit -2;
}
require_once('class_widget.php');
include_once("preference.php");
include_once("user_menu.php");
$str_dossier=dossier::get();
echo '<div class="u_tmenu">';
<<<<<<< commercial.php
echo '<div style="float:left;background-color:#879ED4">';
echo '<div style="float:left;margin-left:10px">';
echo "<H2 class=\"info\">Commercial ".dossier::name()."</h2> ";
echo '</div>';
/* echo '<div style="text-align:right" title="Recherche"> */
/* <input type="IMAGE" src="image/search.png" width="36" onclick="openRecherche(\''.$_REQUEST['PHPSESSID'].'\','.$gDossier.');"> */
/* <A HREF="?p_action=pref&'.$str_dossier.'" title="Pr&eacute;f&eacute;rence"><IMG SRC="image/preference.png" width="36" border="0" ></A> */

/* <A HREF="comptanalytic.php?gDossier='.$gDossier.'" title="CA"><IMG SRC="image/comptaanal.png" width="36"  border="0" ></A> */

/* <A HREF="parametre.php?gDossier='.$gDossier.'" title="Param&egrave;tre"><IMG SRC="image/param.png" width="36" border="0" ></A> */
/* <A HREF="login.php" title="Accueil"><IMG src="image/home.png" width="36" title="Accueil"  border="0"  ></A> */
/* <A HREF="logout.php" title="Sortie"><IMG src="image/logout.png" title="Logout"  width="36"  border="0"></A> */

/* </div> '; */

echo '<div style="float:right;margin-right:10px;text-align:right">';


$amodule=array(
	       array('value'=>'budget','label'=>'Budget'),
	       array('value'=>'compta','label'=>'Comptabilite'),
	       array('value'=>'analytic','label'=>'Compt. Analytique'),
	       array('value'=>'home','label'=>'Accueil'),
	       array('value'=>'param','label'=>'Parametre'),
	       array('value'=>'logout','label'=>'Sortir')
	       );
    
echo '<form method="GET" action="control.php">';
$w=new widget('select');
$w->name='m';
$w->value=$amodule;
echo    '<table><tr><td class="mtitle">';
echo '<A class="mtitle" HREF="javascript:openRecherche(\''.$_REQUEST['PHPSESSID'].'\','.$gDossier.')">'.
'Recheche</a></td>';
echo '<td>'.$w->IOValue().'</td>';
echo dossier::hidden();
echo '<td>'.widget::submit('','Acces Direct').'</td>';
echo '</table>';
echo '</form>';
echo '</div>';
=======
echo '<div style="float:left">';
echo "<H2 class=\"info\">Commercial ".dossier::name()."</h2> ";
>>>>>>> 1.35
echo '</div>';

<<<<<<< commercial.php
echo '</div>';
=======
<A HREF="comptanalytic.php?gDossier='.$gDossier.'" title="CA"><IMG SRC="image/comptaanal.png" width="36"  border="0" ></A>

<A HREF="parametre.php?gDossier='.$gDossier.'" title="Param&egrave;tre"><IMG SRC="image/param.png" width="36" border="0" ></A>
<A HREF="login.php" title="Accueil"><IMG src="image/home.png" width="36" title="Accueil"  border="0"  ></A>
<A HREF="logout.php" title="Sortie"><IMG src="image/logout.png" title="Logout"  width="36"  border="0"></A>

</div> ';
>>>>>>> 1.35

$p_action=(isset ($_REQUEST['p_action']))?$_REQUEST['p_action']:"";
// TODO Menu with all the customer
//echo '<div class="u_tmenu">';
echo '<div style="float:left">';
echo ShowItem(array(
		    array('?p_action=client&'.$str_dossier,'Client'),
		    array('?p_action=facture&'.$str_dossier,'Vente/Facture'),
		    array('?p_action=fournisseur&'.$str_dossier,'Fournisseur'),
		    array('?p_action=depense&'.$str_dossier,'Achat/D&eacute;pense'),
		    array('?p_action=quick_writing&'.$str_dossier,'Ecriture directe'),
		    array('?p_action=impress&'.$str_dossier,'Impression'),
		    array('?p_action=stock&'.$str_dossier,'Stock'),
		    array('?p_action=bank&'.$str_dossier,'Banque'),
		    array('?p_action=fiche&'.$str_dossier,'Fiche'),
		    array('?p_action=periode&'.$str_dossier,'Ferm. Periode'),
		    array('?p_action=central&'.$str_dossier,'Centralisation'),
		    array('?p_action=contact&'.$str_dossier,'Contact'),
		    array('?p_action=suivi_courrier&'.$str_dossier,'Suivi Courrier'),
		    ),
	      'H',"mtitle","mtitle","?p_action=$p_action&".$str_dossier,' width="100%"');


		    //
echo '</div>';


$cn=DbConnect($gDossier);
$User->can_request($cn,SEC_GESTION);
echo JS_VIEW_JRN_MODIFY;
echo JS_AJAX_FICHE;

//-----------------------------------------------------
// p_action == pref
//-----------------------------------------------------
if ( $p_action == "pref" ) 
{
  require_once("pref.inc.php");
}
//-----------------------------------------------------
// p_action == client
//-----------------------------------------------------
if ( $p_action == "client" ) 
{
  require_once("client.inc.php");
}// $p_action == fournisseur
//-----------------------------------------------------
// Fournisseur
if ( $p_action == 'fournisseur') 
{
  require_once("supplier.inc.php");
}
//-----------------------------------------------------
// action
if ( $p_action == 'suivi_courrier') 
{
  require_once("action.inc.php");
}
//-----------------------------------------------------
// p_action == facture
//-----------------------------------------------------
if ( $p_action == "facture" ) 
{
  require_once("facture.inc.php");
}
//-----------------------------------------------------
// Contact
if ( $p_action == 'contact') 
{
  require_once("contact.inc.php");
}
//-----------------------------------------------------
// Expense
if ( $p_action == 'depense') 
{
  require_once("depense.inc.php");
}
//-----------------------------------------------------
// Banque
if ( $p_action == 'bank') 
{
  require_once("bank.inc.php");
}
if ( $p_action=='quick_writing') {
  require_once ('quick_writing.inc.php');
 }

//-----------------------------------------------------
// Impression
if ( $p_action == 'impress') 
{
  if ( $User->check_action($cn,IMP) == 0)
    {
      NoAccess();
      exit;
    }

  require_once("impress.inc.php");
}
if ( $p_action == 'fiche') {
  require_once('fiche.inc.php');
}
if ( $p_action == 'stock') {
  require_once('stock.inc.php');
}
if ( $p_action=='periode') {
  require_once ('periode.inc.php');
 }
if ( $p_action=='central') {
  require_once ('central.inc.php');
 }
