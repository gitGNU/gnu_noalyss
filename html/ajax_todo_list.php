<?php
/*
 *   This file is part of NOALYSS.
 *
 *   NOALYSS is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   NOALYSS is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with NOALYSS; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Copyright Author Dany De Bontridder danydb@aevalys.eu

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
define ('ALLOWED',1);
require_once '../include/constant.php';
require_once ('class_dossier.php');
require_once ('class_todo_list.php');
require_once ('class_database.php');
require_once ('class_user.php');
mb_internal_encoding("UTF-8");

$cn= Dossier::connect();
global $g_user;
$g_user=new User($cn);
$g_user->check(true);
$g_user->check_dossier(Dossier::id(),true);
ajax_disconnected('add_todo_list');

if (isset($_REQUEST['show']))
{
    $cn=new Database(dossier::id());
    $todo=new Todo_list($cn);
    $todo->set_parameter('id',$_REQUEST['id']);
    $todo->load();
    $content=$todo->display();
    header('Content-type: text/xml; charset=UTF-8');
    $dom=new DOMDocument('1.0','UTF-8');
    $tl_id=$dom->createElement('tl_id',$todo->get_parameter('id'));
    $tl_content=$dom->createElement('tl_content',$content);
    
    
    $root=$dom->createElement("root");
    
    $root->appendChild($tl_id);
    $root->appendChild($tl_content);
    $dom->appendChild($root);
   
    echo $dom->saveXML();
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
$ac=HtmlInput::default_value_get('act', 'save');

if ($ac == 'save')
{
    $cn=new Database(dossier::id());
    $todo=new Todo_List($cn);
    $todo->set_parameter("id", HtmlInput::default_value_get("id", 0));
    $todo->set_parameter("date", HtmlInput::default_value_get("p_date_todo", ""));
    $todo->set_parameter("title", HtmlInput::default_value_get("p_title", ""));
    $todo->set_parameter("desc", HtmlInput::default_value_get("p_desc", ""));
    $todo->set_is_public(HtmlInput::default_value_get("p_public", "N"));
    $todo->save();
    $todo->load();
     header('Content-type: text/xml; charset=UTF-8');
    $dom=new DOMDocument('1.0','UTF-8');
    $tl_id=$dom->createElement('tl_id',$todo->get_parameter('id'));
    $tl_content=$dom->createElement('row',$todo->display_row('class="odd"','N'));
     $root=$dom->createElement("root");
     $todo_class=$todo->get_class();
     $todo_class=($todo_class=="")?' odd ':$todo_class;
     $class=$dom->createElement("style",$todo_class);
    
    $root->appendChild($tl_id);
    $root->appendChild($tl_content);
    $root->appendChild($class);
    $dom->appendChild($root);
   
    echo $dom->saveXML();
    exit();
}

