
<?
/*
 *   This file is part of PHPCOMPTA.
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
 *   along with PHPCOMPTA; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
// Auteur Dany De Bontridder ddebontridder@yahoo.fr
include_once ("ac_common.php");
// /* $Revision$ */
include_once("preference.php");
// Show the form for period
echo '<div class="u_redcontent">';
echo '<FORM action="print_balance.php" method="post">';

$a=FormPeriodeMult($cn);
echo $a;
echo '<input type="checkbox" name="central"> centralisé ?';
echo '<input type="submit" name="view" value="ok">';
echo '</div>';

?>