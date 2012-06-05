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
 * \brief manage all the export to CSV or PDF
 *   act can be
 *
 */

global $g_user;
require_once('class_database.php');
require_once('class_user.php');
$gDossier=dossier::id();
$cn=new Database($gDossier);
mb_internal_encoding("UTF-8");
$g_user=new User($cn);
$g_user->Check();
$action=$g_user->check_dossier($gDossier);

if ( $action=='X' || ! isset($_GET['act']) || $g_user->check_print($_GET['act'])==0 )
  {
    echo alert('Accès interdit');
    redirect("do.php?".dossier::get());
    exit();
  }

  switch( $_GET['act'])
  {
  case 'CSV:histo':
    require_once('export_histo_csv.php');
    exit();
    break;
  case 'CSV:ledger':
    require_once('export_ledger_csv.php');
    exit();
    break;
  case 'PDF:ledger':
    require_once('export_ledger_pdf.php');
    exit();
    break;

  case 'CSV:postedetail':
    require_once('export_poste_detail_csv.php');
    exit();
    break;
  case 'PDF:postedetail':
    require_once('export_poste_detail_pdf.php');
    exit();
    break;
  case 'CSV:fichedetail':
    require_once('export_fiche_detail_csv.php');
    exit();
    break;
  case 'PDF:fichedetail':
    require_once('export_fiche_detail_pdf.php');
    exit();
    break;

  case 'CSV:fiche_balance':
    require_once('export_fiche_balance_csv.php');
    exit();
    break;
  case 'PDF:fiche_balance':
    require_once('export_fiche_balance_pdf.php');
    exit();
    break;
  case 'CSV:report':
    require_once('export_form_csv.php');
    exit();
    break;
  case 'PDF:report':
    require_once('export_form_pdf.php');
    exit();
    break;
  case 'CSV:fiche':
    require_once('export_fiche_csv.php');
    exit();
    break;
  case 'PDF:fiche':
    require_once('export_fiche_pdf.php');
    exit();
    break;
  case 'CSV:glcompte':
    require_once('export_gl_csv.php');
    exit();
    break;
  case 'PDF:glcompte':
    require_once('export_gl_pdf.php');
    exit();
    break;
  case 'PDF:sec':
    require_once('export_security_pdf.php');
    exit();
    break;
  case 'CSV:AncList':
    require_once('export_anc_list_csv.php');
    exit();
    break;
  case 'CSV:AncBalSimple':
    require_once('export_anc_balance_simple_csv.php');
    exit();
    break;
  case 'PDF:AncBalSimple':
    require_once('export_anc_balance_simple_pdf.php');
    exit();
    break;
  case 'CSV:AncBalDouble':
    require_once('export_anc_balance_double_csv.php');
    exit();
    break;
  case 'PDF:AncBalDouble':
    require_once('export_anc_balance_double_pdf.php');
    exit();
    break;
  case 'CSV:balance':
    require_once('export_balance_csv.php');
    exit();
    break;
  case 'PDF:balance':
    require_once('export_balance_pdf.php');
    exit();
    break;
  case 'CSV:AncTable':
    require_once('export_anc_table_csv.php');
    exit();
    break;
  case 'CSV:AncAccList':
    require_once('export_anc_acc_list_csv.php');
    exit();
    break;
  case 'CSV:AncBalGroup':
    require_once('export_anc_balance_group_csv.php');
    exit();
    break;
  case 'OTH:Bilan':
      require_once 'export_bilan_oth.php';
      exit();
      break;
  case 'CSV:AncGrandLivre':
      require_once 'export_anc_grandlivre_csv.php';
      break;
  case 'CSV:reportinit':
	  require_once('export_reportinit_csv.php');
	  break;
  case 'CSV:ActionGestion':
	  require_once 'export_follow_up_csv.php';
	  break;
   default:
    alert('Action inconnue '.$_GET['act']);
    exit();
  }