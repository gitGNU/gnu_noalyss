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
define ('ALLOWED',1);
require_once '../include/constant.php';
global $g_user,$cn,$g_parameter;
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
// get file and execute it

 $prfile=$cn->get_value("select me_file from menu_ref where me_code=$1",array($_GET['act']));
 require_once $prfile;
 ?>