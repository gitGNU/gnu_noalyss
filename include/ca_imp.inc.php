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

/* !\file 
 */

/* \brief this file is included for printing the analytic
 * accountancy. 
 *
 */
require_once('class_anc_operation.php');
require_once('class_anc_plan.php');
require_once('ac_common.php');
require_once('class_widget.php');

//-- the menu
$menu=array(array("?p_action=ca_imp&sub=listing&$str_dossier","Listing","Listing des op&eacute;rations","listing"),
			array("?p_action=ca_imp&sub=bs&$str_dossier","Balance simple","Balance simple d'un plan analytique","bs"),
			array("?p_action=ca_imp&sub=bc2&$str_dossier","Balance crois&eacute;","Balance crois&eacute; de 2 plans analytiques","bc2")
			);
$sub=(isset($_GET['sub']))?$_GET['sub']:'no';
echo ShowItem($menu,"H","mtitle","mtitle",$sub);

$hidden=new widget("hidden");
$str_hidden=$hidden->IOValue("p_action","ca_imp");
$str_hidden.=$hidden->IOValue("sub",$sub);

// select following the sub action
//------------------------------------------------------------------------------
// listing
if ( $sub=='listing') {
  require_once ('class_anc_listing.php');
  $list=new Anc_Listing($cn);
  $list->get_request();

  echo $list->display_form($str_hidden);
  //---- result
  if ( isset($_GET['result']) ){
	echo '<div class="u_redcontent">';

	//--------------------------------
	// export Buttons 
	//---------------------------------
	echo $list->show_button($str_hidden);
	echo $list->display_html();
	echo '</div>';
  }
 }

//------------------------------------------------------------------------------
// Simple balance 
if ($sub == 'bs') {
  require_once ('class_anc_balance_simple.php');
  $bs=new Anc_Balance_Simple($cn);
  $bs->get_request();
  echo '<form method="get">';
  echo $bs->display_form($str_hidden);
  echo '</form>';
  if ( isset($_GET['result'])) {
	echo $bs->show_button('ca_bs_csv.php','ca_bs_pdf.php',$str_hidden);
	echo $bs->display_html();
  }
 }

//------------------------------------------------------------------------------
// crossed balance
if ( $sub == 'bc2') {
  require_once ('class_anc_balance_double.php');
  $bc=new Anc_Balance_Double($cn);
  $bc->get_request ();
  echo '<form method="get">';
  echo $bc->display_form($str_hidden);
  echo '</form>';
  if ( isset($_GET['result'])) {
	echo $bc->show_button('ca_bc_csv.php','ca_bc_pdf.php',$str_hidden);
	echo $bc->display_html();
  }
 }

