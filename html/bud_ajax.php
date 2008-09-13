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
 *\todo the security must be added here
 *
 */
extract ($_POST);
require_once ('class_dossier.php');
require_once ('class_bud_data.php');
require_once ('debug.php');

echo_debug(__FILE__.':'.__LINE__,' $POST ',$_POST);


$cn=DbConnect(dossier::id());

if ( $_POST['action'] == 'delete') {
  if ( $bd_id != 0 ) {
  $obj=new Bud_Data($cn);
  $obj->bd_id=$bd_id;
  $obj->delete_by_bd_id();
  }
  header("Content-type: application/json charset=\"utf8\"",true);
  echo '{"bd_id":"0","form_id":"'.$form_id.'"}';

}

if ( $_POST['action'] == 'save' ) {
  $a=$_POST;
  $a['bc_id']=${"bc_id".$form_id};
  $a['pcm_val']=${"account_".$form_id."_hidden"};

  $obj=new Bud_Data($cn);
  $obj->get_from_array($a);
  $obj->verify();
  if ( $bd_id == 0 ) {
    $obj->add();
  } else {
    $obj->update();

  }
  header("Content-type: application/json charset=\"utf8\"",true);
  echo '{"bd_id":"'.$obj->bd_id.'","form_id":"'.$form_id.'"}';

}

  
  
 
