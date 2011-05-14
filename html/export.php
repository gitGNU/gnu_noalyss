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


require_once('class_database.php');
require_once('class_user.php');
$gDossier=dossier::id();
$cn=new Database($gDossier);

/**
 *@todo check the security here
 */

$user=new User($cn);
$user->Check();
$act=$user->check_dossier($gDossier);

if ( $act=='X' || ! isset($_GET['act']) )
  {
    echo alert('Accès interdit');
    exit();
  }

switch( $_GET['act']) 
  {
  case 'CSV/histo':
    $user->can_request(IMPJRN,0);
    require_once('export_histo_csv.php');
    exit();
    break;
  case 'CSV/ledger':
    $user->can_request(IMPJRN,0);
    require_once('export_ledger_csv.php');
    exit();
    break;
  case 'PDF/ledger':
    $user->can_request(IMPJRN,0);
    require_once('export_ledger_pdf.php');
    exit();
    break;

  case 'CSV/postedetail':
    $user->can_request(IMPPOSTE,0);
    require_once('export_poste_detail_csv.php');
    exit();
    break;
  case 'PDF/postedetail':
    $user->can_request(IMPPOSTE,0);
    require_once('export_poste_detail_pdf.php');
    exit();
    break;
  case 'CSV/fichedetail':
    $user->can_request(IMPPOSTE,0);
    require_once('export_fiche_detail_csv.php');
    exit();
    break;
  case 'PDF/fichedetail':
    $user->can_request(IMPPOSTE,0);
    require_once('export_fiche_detail_pdf.php');
    exit();
    break;

  case 'CSV/fiche_balance':
    $user->can_request(IMPFIC,0);
    require_once('export_fiche_balance_csv.php');
    exit();
    break;
  case 'PDF/fiche_balance':
    $user->can_request(IMPFIC,0);
    require_once('export_fiche_balance_pdf.php');
    exit();
    break;
  case 'CSV/report':
    require_once('export_form_csv.php');
    exit();
    break;
  case 'PDF/report':
    require_once('export_form_pdf.php');
    exit();
    break;
  case 'CSV/fiche':
    $user->can_request(IMPFIC,0);
    require_once('export_fiche_csv.php');
    exit();
    break;
  case 'PDF/fiche':
    $user->can_request(IMPFIC,0);
    require_once('export_fiche_pdf.php');
    exit();
    break;
  case 'CSV/glcompte':
    $user->can_request(IMPBIL,0);
    require_once('export_gl_csv.php');
    exit();
    break;
  case 'PDF/glcompte':
    $user->can_request(IMPBIL,0);
    require_once('export_gl_pdf.php');
    exit();
    break;
  case 'PDF/sec':
    $user->can_request(PARSEC,1);
    require_once('export_security_pdf.php');
    exit();
    break;
  case 'CSV/AncList':
    require_once('export_anc_list_csv.php');
    exit();
    break;
  case 'CSV/AncBalSimple':
    require_once('export_anc_balance_simple_csv.php');
    exit();
    break;
  case 'PDF/AncBalSimple':
    require_once('export_anc_balance_simple_pdf.php');
    exit();
    break;
  case 'CSV/AncBalDouble':
    require_once('export_anc_balance_double_csv.php');
    exit();
    break;
  case 'PDF/AncBalDouble':
    require_once('export_anc_balance_double_pdf.php');
    exit();
    break;
  case 'CSV/balance':
    require_once('export_balance_csv.php');
    exit();
    break;
  case 'PDF/balance':
    require_once('export_balance_pdf.php');
    exit();
    break;
  case 'CSV/AncTable':
    require_once('export_anc_table_csv.php');
    exit();
    break;
  case 'CSV/AncAccList':
    require_once('export_anc_acc_list_csv.php');
    exit();
    break;

   default:
    alert('Action inconnue '.$_GET['act']);
    exit();
  }