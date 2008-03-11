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
extract ($_GET);
/* m for requested module 
 * f from module
 */
foreach (array('gDossier','m') as $i) {
  if ( ! isset (${$i}) ) {
    echo "Erreur parametre manquant";
    exit();
  }
}

require_once('class_dossier.php');
require_once('user_common.php');

$phpsessid='&PHPSESSID='.$_REQUEST['PHPSESSID'];
switch ($m) {
 case 'budget':
   get_redirect('budget.php?'.dossier::get().$phpsessid);
   break;
 case 'compta':
   get_redirect('user_compta.php?'.dossier::get().$phpsessid);
   break;
 case 'analytic':
   get_redirect('comptanalytic.php?'.dossier::get().$phpsessid);
   break;
 case 'home':
   get_redirect('user_login.php?'.$phpsessid);
   break;
 case 'gestion':
   get_redirect('commercial.php?'.dossier::get().$phpsessid);
   break;
 case 'param':
   get_redirect('parametre.php?'.dossier::get().$phpsessid);
   break;
 case 'pref':
   get_redirect('user_pref.php?'.dossier::get().$phpsessid);
   break;
 case 'home':
   get_redirect('user_login.php');
  break;
 case 'access':
   get_redirect('access.php?'.dossier::get());
   break;

 case 'logout':
   get_redirect('logout.php');
}

