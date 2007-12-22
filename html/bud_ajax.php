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
 * \brief Ajax action for budget remove or save record
 *
 */
  //print_r($_POST);
extract ($_POST);

require_once ('class_bud_detail.php');

$cn=DbConnect(dossier::id());

if ( $action == 'delete') {
  if ( $bd_id == 0 ) return;
  $obj=new Bud_Detail($cn,$bd_id);
  $obj->delete();
  header("Content-type: application/json charset=\"ISO8859-1\"",true);
  echo '{"bd_id":"'.$obj->bd_id.'","form_id":"'.$form_id.'"}';

 }

if ( $action == 'save' ) {
  if ( $bd_id == 0 ) {
    $obj=new Bud_Detail($cn);
    $obj->po_id=$po_id;
    $obj->bc_id=${"bc_id".$form_id};
    $obj->pcm_val=${"account_".$form_id."_hidden"};
    $obj->bh_id=$bh_id;
    $obj->add();
    header("Content-type: application/json charset=\"ISO8859-1\"",true);
    echo '{"bd_id":"'.$obj->bd_id.'","form_id":"'.$form_id.'"}';
    exit()
  } else {
    $obj=new Bud_Detail($cn);
    $obj->po_id=$po_id;
    $obj->bc_id=${"bc_id".$form_id};
    $obj->pcm_val=${"account_".$form_id."_hidden"};
    $obj->bh_id=$bh_id;
    $obj->update();
  }

 }
