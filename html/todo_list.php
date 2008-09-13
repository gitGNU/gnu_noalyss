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
 * \brief handle the ajax request for the todo_list (delete, update
 * and insert)
 * for add, needed parameters 
 * - gDossier
 * - d date, 
 * - t title 
 * - de description
 * for update, same as ADD + 
 * - i id
 * for delete
 * - gDossier 
 * - i id
 */
require_once ('class_dossier.php');
require_once ('class_todo_list.php');
require_once ('postgres.php');

  /*!\todo needs security for the users */
if (isset($_REQUEST['show'])) {
  $cn=DbConnect(dossier::id());
  $todo=new Todo_list($cn);
  $todo->set_parameter('id',$_REQUEST['id']);
  $todo->load();
  header("Content-type: text/html; charset: utf8",true);
  echo $todo->toJSON();
  exit();
}
  /*!\todo needs security for the users */
if (isset($_REQUEST['del'])) {
  $cn=DbConnect(dossier::id());
  $todo=new Todo_list($cn);
  $todo->set_parameter('id',$_REQUEST['id']);
  $todo->delete();
  exit();
}
