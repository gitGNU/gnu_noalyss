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
/*! \file
 * \brief Page who manage the different actions (meeting, letter)
 */
require_once('class_ipopup.php');
global $g_user;
$retour=HtmlInput::button_anchor(_('Retour liste'),'?ac='.$_REQUEST['ac'].'&my_action&'.dossier::get());
//-----------------------------------------------------
// Follow_Up
//-----------------------------------------------------
require_once("class_icard.php");
require_once("class_ispan.php");
require_once("class_ifile.php");
require_once("class_itext.php");
require_once("class_follow_up.php");
require_once('class_iaction.php');
/*!\brief Show the list of action, this code should be common
 *        to several webpage. But for the moment we keep like that
 *        because it is used only by this file.
 *\param $cn database connection
 * \param $retour button for going back
 * \param $h_url calling url
 */

// We need a sub action (3rd level)
// show a list of already taken action
// propose to add one
// permit also a search
// show detail
$sub_action=(isset($_REQUEST['sa']))?$_REQUEST['sa']:"";
/* if ag_id is set then we give it otherwise we have problem
 * with the generation of document
 */
$ag_id=(isset($_REQUEST['ag_id']))?$_REQUEST['ag_id']:0;
$ac=$_REQUEST['ac'];
$base='ac='.$ac;

require_once('action.common.inc.php');
echo "</div>";

?>
