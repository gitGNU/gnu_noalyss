<?
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
require_once("ac_common.php");
require_once("postgres.php");
//////////////////////////////////////////////////////////////////////
// Check User
include ('class_user.php');
/* Admin. Dossier */
$cn=DbConnect($_SESSION['g_dossier']);

$User=new cl_user($cn);
$User->Check();

// TODO a specific level of security for the "bilan" ???
// Change must be done here
if ($User->CheckAction($cn,IMP) == 0 ){
    /* Cannot Access */
    NoAccess();
  }

header('Content-type: application/bin');
header('Content-Disposition: attachment;filename="declaration.bin"',FALSE);

////////////////////////////////////////////////////////////////////////////////
// Submit for a magnetic declaration
// Belgium only
////////////////////////////////////////////////////////////////////////////////
if ( isset($_POST['bt_disk'])) {
  require_once("class_customer.php");
  $customer=new Customer($cn);
  $a_Res=$customer->VatListing($_POST['year']);

  require_once("decla.BE.inc.php");
  $a=MakeListingVat($cn,$a_Res,$_POST['year']);
  echo "$a";
  return;
 }
?>
