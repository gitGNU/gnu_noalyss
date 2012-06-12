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
// Verify parameters
/*! \file
 * \brief retrieve a document
 */

require_once('class_database.php');
require_once("ac_common.php");
require_once( "class_document.php");
require_once('class_dossier.php');
$gDossier=dossier::id();
$cn=new Database($gDossier);
$action=(isset($_REQUEST['a']))?$_REQUEST['a']:'sh';

require_once ('class_user.php');
$User=new User(new Database());
$User->Check();
$User->check_dossier($gDossier);

/* Show the document */
if ( $action == 'sh')
{
    if ( $User->check_action(VIEWDOC)==1)
    {
        // retrieve the document
        $doc=new Document($cn,$_REQUEST['d_id']);
        $doc->Send();
    }
}
/* remove the document */
if ( $action == 'rm' )
{
    if ($User->check_action(RMDOC)==1)
    {
        $doc=new Document($cn,$_REQUEST['d_id']);
        $doc->remove();
        $json=sprintf('{"d_id":"%s"}',$_REQUEST['d_id']);
        header("Content-type: text/html; charset: utf8",true);
        print $json;
    }
}
/* remove the operation from action_gestion_operation*/
if ( $action == 'rmop' )
{
	$dt_id=$cn->get_value("select ag_id from action_gestion_operation where ago_id=$1",$_REQUEST['id']);
    if ($User->check_action(RMDOC)==1 && $User->can_write_action($dt_id)==true)
    {
		$cn->exec_sql("delete from action_gestion_operation where ago_id=$1",
				array($_REQUEST['id']));
        $json=sprintf('{"ago_id":"%s"}',$_REQUEST['id']);
        header("Content-type: text/html; charset: utf8",true);
        print $json;
    }
}
/* remove the comment from action_gestion_operation*/
if ( $action == 'rmcomment' )
{
	$dt_id=$cn->get_value("select ag_id from action_gestion_comment where agc_id=$1",$_REQUEST['id']);
    if ($User->check_action(RMDOC)==1 && $User->can_write_action($dt_id)==true)
    {
		$cn->exec_sql("delete from action_gestion_comment where agc_id=$1",
				array($_REQUEST['id']));
        $json=sprintf('{"agc_id":"%s"}',$_REQUEST['id']);
        header("Content-type: text/html; charset: utf8",true);
        print $json;
    }
}
/* remove the action from action_gestion_operation*/
if ( $action == 'rmaction' )
{
    if ($User->check_action(RMDOC)==1 && $User->can_write_action($_REQUEST['id']) == true && $User->can_write_action($_REQUEST['ag_id'])== true )
    {
		$cn->exec_sql("delete from action_gestion_related where aga_least=$1 and aga_greatest=$2",
				array($_REQUEST['id'],$_REQUEST['ag_id']));
		$cn->exec_sql("delete from action_gestion_related where aga_least=$2 and aga_greatest=$1",
				array($_REQUEST['id'],$_REQUEST['ag_id']));
        $json=sprintf('{"act_id":"%s"}',$_REQUEST['id']);
        header("Content-type: text/html; charset: utf8",true);
        print $json;
    }
}
