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

/*!\file
 * \brief file included to manage all the sold operation
 */
require_once("class_acc_ledger_sold.php");
require_once ('class_pre_op_ven.php');


$cn=DbConnect(dossier::id());
  //menu = show a list of ledger
$str_dossier=dossier::get();
$array=array( 
	     array('?p_action=ven&sa=n&'.$str_dossier,'Nouvelle vente','Nouvelle vente',1),
	     array('?p_action=ven&sa=l&'.$str_dossier,'Liste ventes','Liste des ventes',2),
	     array('?p_action=ven&sa=lnp&'.$str_dossier,'Liste vente non payées','Liste des ventes non payées',3),
	     array('?p_action=impress&type=jrn&'.$str_dossier,'Impression','Impression'),
	     array('?p_action=ven&sa=f&'.$str_dossier,'Clients','Solde des clients')
	      );

$sa=(isset ($_REQUEST['sa']))?$_REQUEST['sa']:-1;
switch ($sa) {
 case 'n':
   $def=1;
   $use_predef=0;
   break;
 case 'p':
   $def=1;
   $use_predef=1;
   break;
 case 'l':
   $def=2;
   break;
 case 'lnp':
   $def=3;
   break;
 default:
   $def=1;
 }
echo '<div class="lmenu">';
echo ShowItem($array,'H','mtitle','mtitle',$def);
echo '</div>';
// empty form for encoding
if ( $def==1 || $def == 4 ) {

  echo '<div class="content">';
  echo JS_PROTOTYPE;
  $Ledger=new Acc_Ledger_Sold($cn,0);
  echo "<FORM NAME=\"form_detail\" METHOD=\"POST\">";
  if ( isset($use_predef) && $use_predef == 1 ) {
    // used a predefined operation
    //
    $op=new Pre_op_ven($cn);
    $op->set_od_id($_REQUEST['pre_def']);
    
    $p_post=$op->compute_array();
    
    echo $Ledger->display_form($p_post);
    echo '<script>compute_sold(0)</script>';
  }
  else
    echo $Ledger->display_form();
  echo "</FORM>";
 //
 // pre defined operation
 //
  $def_ledger=$Ledger->get_first('ven');

  echo '<form method="GET" action="compta.php">';
  echo widget::hidden("sa","p");
  echo widget::hidden("p_action","ven");
  echo dossier::hidden();
  $op=new Pre_op_ven($cn);
  $op->set('ledger',$def_ledger['jrn_def_id']);
  $op->set('ledger_type',"VEN");
  $op->set('direct','f');
  echo $op->form_get();
  echo '</form>';

  echo '</div>';
}
