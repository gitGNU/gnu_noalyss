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
 * \brief Main file
 */
require_once 'class_database.php';
require_once ('class_dossier.php');
require_once('user_common.php');
require_once('ac_common.php');
require_once 'function_javascript.php';
html_page_start();
global $g_user,$cn;

$cn=new Database(Dossier::id());
$g_user=new User($cn);
// Display available menu in the right order
load_all_script();

$module_selected = -1;

if (isset($_REQUEST['ac']))
{
    $all = explode('/', $_REQUEST['ac']);
    $module_selected = $all[0];

// Show module and highligt selected one
    show_module($module_selected);
   for ( $i=0;$i != count($all);$i++)
	{   // show the menu
	    show_menu($all,$i);
	}
}
else
{
    show_module(-1);
}


