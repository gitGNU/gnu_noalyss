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
 * \brief Manage the detail for each hypo by analytic account
 */
require_once ('class_widget.php');

echo '<form method="get">';

$post_anc="";

$wHypo=new widget("select","","bh_id");
$wHypo->value=make_array($cn,"select bh_id,bh_name from bud_hypothese");
$wHypo->selected=(isset($_REQUEST['bh_id']))?$_REQUEST['bh_id']:"";
$wHypo->javascript='onChange="this.form.submit();"';

if (  isset($_REQUEST['bh_id']) ) {
  /* if bh_id is set it means that the hypothese has been choosed */
  $wPost_Analytic=new widget("select","","po_id");
  $wPost_Analytic->value=make_array($cn,"select po_id,po_name from poste_analytique join ".
				    " bud_hypothese as a using (pa_id) ".
				    " where a.bh_id=".$_REQUEST['bh_id']);
  if ( empty ($wPost_Analytic->value) )
    $post_anc="";
  else {
    $wPost_Analytic->selected=(isset($_REQUEST['po_id']))?$_REQUEST['po_id']:"";
    $post_anc=$wPost_Analytic->IOValue();
  }
  echo widget::hidden('bh_id',$_REQUEST['bh_id']);
  $wHypo->readonly=true;
 } else {
  /* if bh_id the first step is to select an hypothese */

 }

echo $wHypo->IOValue();


echo $post_anc;
echo widget::submit_button('recherche','recherche');
echo dossier::hidden();
echo widget::hidden("p_action","detail");
echo '</form>';


if ( ! isset($_REQUEST['bh_id']) || ! isset ($_REQUEST['po_id'])) {
	exit();
}


echo '<hr>';
