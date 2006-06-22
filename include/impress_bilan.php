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
include_once("class_widget.php");
/*! \file
 * \brief form who call the printing of the bilan in RTF
 */

////////////////////////////////////////////////////////////////////////////////
// Show the jrn and date
////////////////////////////////////////////////////////////////////////////////
include_once("postgres.php");
$ret=make_array($cn,"select fr_id,fr_label
                 from formdef
                 order by fr_label");
////////////////////////////////////////////////////////////////////////////////
// Form
////////////////////////////////////////////////////////////////////////////////
$w=new widget("select");
$w->table=1;

echo '<div class="u_redcontent">';
echo '<FORM ACTION="bilan.php" METHOD="POST">';
echo '<TABLE>';

print '<TR>';
$periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode order by p_id");
$w->label="Depuis";
print $w->IOValue('from_periode',$periode_start);
$w->label=" jusqu'à ";
$periode_end=make_array($cn,"select p_id,to_char(p_end,'DD-MM-YYYY') from parm_periode order by p_id");
print $w->IOValue('to_periode',$periode_end);
print "</TR>";
echo '</TABLE>';
print $w->Submit('bt_rtf','Impression');

echo '</FORM>';
echo '</div>';
?>
