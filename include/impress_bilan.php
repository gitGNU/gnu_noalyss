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
require_once ('class_bilan.php');
/*! \file
 * \brief form who call the printing of the bilan in RTF
 *        file included by user_impress
 *
 * some variable are already defined ($cn, $User ...)
 */

//-----------------------------------------------------
// Show the jrn and date
//-----------------------------------------------------
include_once("postgres.php");
/*$ret=make_array($cn,"select fr_id,fr_label
                 from formdef
                 order by fr_label");
*/
//-----------------------------------------------------
// Form
//-----------------------------------------------------
$filter_year=" where p_exercice='".$User->getExercice()."'";
$bilan=new Bilan($cn);
echo '<div class="u_redcontent">';
echo '<FORM ACTION="bilan.php" METHOD="GET">';
echo $bilan->display_form ($filter_year);
echo '</FORM>';
echo '</div>';
?>
