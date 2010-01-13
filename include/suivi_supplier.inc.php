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
 * \brief this file is used for the follow up of the customer (mail, meeting...)
 *  - p_action = supplier
 *  - sb = detail 
 *  - sc = sv
 *  - sd = this parameter is used here
 *  - $cn = database connection 
 */
require_once('class_action.php');
$User->can_request(GECOUR);
echo js_include('prototype.js');
echo js_include('scriptaculous.js');
echo js_include('effects.js');
echo js_include('controls.js');
echo js_include('infobulle.js');
echo js_include('acc_ledger.js');
echo js_include('ajax_fiche.js');
echo JS_CARD;

/**
 *\note problem with ShowActionList, this function is local
 * to the file action.inc.php. And this function must different for each
 *  suivi
 */
$sub_action=(isset($_REQUEST['sa']))?$_REQUEST['sa']:"list";
$ag_id=(isset($_REQUEST['ag_id']))?$_REQUEST['ag_id']:0;
$p_action=$_REQUEST['p_action'];
$base="p_action=$p_action&sc=sv&sb=detail&f_id=".$_REQUEST['f_id'];
$retour=HtmlInput::button_anchor('Retour','?'.dossier::get().'&'.$base);
$fiche=new Fiche($cn,$_REQUEST['f_id']);

$_GET['qcode']=$fiche->get_quick_code();
$_REQUEST['qcode']=$fiche->get_quick_code();
echo '<div class="content">';
require_once('action.common.inc.php');
echo '</div>';
