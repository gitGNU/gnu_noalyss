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
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/* $Revision$ */
if ( ! isset ($_GET['action']) ) {
    return;
}
$dossier=sprintf("dossier%d",$g_dossier);
$cn=DbConnect($dossier);
$action=$_GET['action'];
if ( $action == 'insert_vente' ) {
// Check privilege
    if ( CheckJrn($g_dossier,$g_user,$g_jrn) != 2 )    {
       NoAccess();
       exit -1;
    }
// Show an empyt form of invoice
    include_once("form_input.php");
    $form=FormVente($cn,$g_jrn,$g_user,'deb');
    echo '<div class="redcontent">';
    echo $form;
    echo '</div>';
}
if ( $action == 'voir_vente' ) {
// Show list of sell
// Date - date of payment - Customer - amount
     $list=ListSell();
    echo '<div class="redcontent">';
    echo $list;
    echo '</div>';
}
if ( $action == 'voir_vente_non_paye' ) {
// Show list of unpaid sell
// Date - date of payment - Customer - amount
     $list=ListSell('filtre');
    echo '<div class="redcontent">';
    echo $list;
    echo '</div>';
}
if ( $action == 'impress' ) {
// Print list of sell or print the current invoice
}
if ( $action == 'search' ) {
// display a search box
}
?>