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
 * \brief Manage the hypothese for the budget module
 */

  /*!\todo Add the security for the budget module
   */

require_once ('class_dossier.php');

if (! isset($_GET['do'])) return "";
$do=$_GET['do'];
$cn=DbConnect(dossier::id());
/* First synthese */
if ( $do == 'po') {
  require_once ('class_bud_synthese_anc.php');
  $obj=new Bud_Synthese_Anc($cn);

  $obj->from_array($_GET);
  $res=$obj->load();
  header('Content-type: application/csv');
  header('Content-Disposition: attachment;filename="bud_detail_po.csv"',FALSE);
  echo $obj->display_csv($res);

 }
/* 2nd Synthese */
if ( $do == 'ga') {
  header('Content-type: application/csv');
  header('Content-Disposition: attachment;filename="bud_groupe.csv"',FALSE);
  require_once ('class_bud_synthese_hypo.php');
  
  $obj=new Bud_Synthese_Hypo($cn);

  $obj->from_array($_GET);
  $res=$obj->load();
  
  echo $obj->display_csv($res);

 }

/* 3rd Synthese */
if ( $do == 'vglobal') {
  require_once ('class_bud_synthese_group.php');
  $obj=new Bud_Synthese_Group($cn);
  $obj->from_array($_GET);
  $res=$obj->load();
  header('Content-type: application/csv');
  header('Content-Disposition: attachment;filename="bud_global.csv"',FALSE);
  echo $obj->display_csv($res);

 }

/* 4st Synthese */
if ( $do == 'acc') {
  require_once ('class_bud_synthese_acc.php');
  $obj=new Bud_Synthese_Acc($cn);

  $obj->from_array($_GET);
  $res=$obj->load();
  header('Content-type: application/csv');
  header('Content-Disposition: attachment;filename="bud_compta.csv"',FALSE);
  echo $obj->display_csv($res);

 }
