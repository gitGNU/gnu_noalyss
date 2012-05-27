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
require_once ('class_database.php');
require_once ('class_user.php');
mb_internal_encoding("UTF-8");

$cn= Dossier::connect();
$user=new User($cn);
$user->check(true);
$user->check_dossier(Dossier::id(),true);
ajax_disconnected('add_todo_list');

if (isset($_REQUEST['show']))
{
    $cn=new Database(dossier::id());
    $todo=new Todo_list($cn);
    $todo->set_parameter('id',$_REQUEST['id']);
    $todo->load();
    header('Content-type: text/xml; charset=UTF-8');
    header ('<?xml version="1.0" encoding="UTF-8"?>');
    echo $todo->toXML();
    exit();
}

if (isset($_REQUEST['del']))
{
    $cn=new Database(dossier::id());
    $todo=new Todo_list($cn);
    $todo->set_parameter('id',$_REQUEST['id']);
    $todo->delete();
    exit();
}
