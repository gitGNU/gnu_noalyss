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
/* $Revision*/
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/*!\file
*\brief this file let you debug and test the different functionnalities, there are 2 important things to do
*  - first do not forget to create the authorized_debug file in the html folder
*  - secund the test must adapted to this page : if you do a post (or get) from a test, you won't get any result
* if the $_REQUEST[test_select] is not set, so set it . 
*/
include_once("ac_common.php");
include_once("constant.php");
require_once('class_database.php');
require_once ('class_dossier.php');
require_once('class_html_input.php');
require_once ('function_javascript.php');
load_all_script();
global $cn,$g_user,$g_succeed,$g_failed;
if ( ! file_exists('authorized_debug') )
{
    echo "Pour pouvoir utiliser ce fichier vous devez creer un fichier nomme authorized_debug
    dans le repertoire html du server";
    exit();

}
html_page_start();
function start_test($p_array)
{
    echo '<h1>'.$p_array['desc'].'</h1>';
        require $p_array['file'];
        call_user_func($p_array['function']);
}
// Test the connection
echo __FILE__.":".__LINE__;
print_r($_REQUEST);
if ( ! isset($_REQUEST['gDossier']))
{
    echo "Vous avez oublie de specifier le gDossier ;)";
    echo "L'url aurait du etre test.php?gDossier=xx";
    exit();
}
$cn=new Database($_GET['gDossier']);

$a_route[]=array('desc'=>'test sur les menus par défauts','file'=>'class_default_menu.php','function'=>'Default_Menu::test_me');
$a_route[]=array('desc'=>'test sur Acc_Operations','file'=>'class_acc_operation.php','function'=>'Acc_Operation::test_me');
$a_route[]=array('desc'=>'test sur INVOICING','file'=>'../include/ext/invoicing/include/class_acc_ledger_sold_generate.php','function'=>'Acc_Ledger_Sold_Generate::test_me');
$called=HtmlInput::default_value_get("called", -1);
if ($called == -1 )
{
    for ($i=0;$i< count($a_route);$i++)
    {
        start_test($a_route[$i]);
    }
}
 else
{
    start_test($a_route[$called]);
}