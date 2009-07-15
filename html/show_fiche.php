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
 * \brief This file show in a popup window the detail a of card in read only mode
 * the parameter are q (for qcode) & PHPSESSID
 */
/*! \brief 
 *
 */
require_once ('class_user.php');
require_once ('class_fiche.php');
require_once ('class_database.php');
require_once ('debug.php');
require_once ('ac_common.php');
require_once('class_dossier.php');
$gDossier=dossier::id();

$User=new User(new Database());
$User->Check();
html_page_start($_SESSION['g_theme'],"onLoad='window.focus();'");


if ( ! isset ($_REQUEST['q'])) {
  echo 'appel invalide';
  exit();
}
// connect to the database
$cn=new Database($gDossier);

// Create a object fiche

$f=new fiche($cn);
$f->get_by_qcode($_REQUEST['q'],false);

echo $f->Display(true);
?>
