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
// Verify parameters
/** 
 * \file
 * \brief retrieve a document
 */
if ( ! defined ('ALLOWED')) die (_('Non autorisé'));

require_once NOALYSS_INCLUDE.'/lib/ac_common.php';
require_once NOALYSS_INCLUDE.'/class/class_document.php';
require_once NOALYSS_INCLUDE.'/class/class_dossier.php';
$action = (isset($_REQUEST['a'])) ? $_REQUEST['a'] : 'sh';

$d_id=HtmlInput::default_value_request('d_id',0);
$id=HtmlInput::default_value_request('id',0);
$ag_id=HtmlInput::default_value_request('ag_id',0);
$value=HtmlInput::default_value_request('value', null);

if (    isNumber($id)    == 0 ||
        isNumber($ag_id) == 0 ||
        isNumber($d_id)  == 0 
        )
{
    die (_('Données invalides'));
}
/* Show the document */
if ($action == 'sh')
{
	if ($g_user->check_action(VIEWDOC) == 1)
	{
		// retrieve the document
		$doc = new Document($cn, $d_id);
		$doc->Send();
	}
}
/* remove the document */
if ($action == 'rm')
{
	$json='{"d_id":"-1"}';
	if ($g_user->check_action(RMDOC) == 1)
	{
		$doc = new Document($cn, $d_id);
		$doc->remove();
		$json = sprintf('{"d_id":"%s"}', $d_id);
	}
	header("Content-type: text/html; charset: utf8", true);
	print $json;
}
/* update the description of the document */
if ( $action == "upd_doc") 
{
    	if ($g_user->check_action(VIEWDOC) == 1)
	{
            $doc = new Document($cn, $d_id);
            $doc->get();
            if ( $g_user->can_write_action($doc->ag_id))
		// retrieve the document
		$doc->update_description(strip_tags ($value));
	}

}
/* remove the operation from action_gestion_operation */
if ($action == 'rmop')
{
	$json = '{"ago_id":"-1"}';
	$dt_id = $cn->get_value("select ag_id from action_gestion_operation where ago_id=$1",array( $id));
	if ($g_user->check_action(RMDOC) == 1 && $g_user->can_write_action($dt_id) == true)
	{
		$cn->exec_sql("delete from action_gestion_operation where ago_id=$1", array($id));
		$json = sprintf('{"ago_id":"%s"}', $id);
	}
	header("Content-type: text/html; charset: utf8", true);
	print $json;
}
/* remove the comment from action_gestion_operation */
if ($action == 'rmcomment')
{
	$json = '{"agc_id":"-1"}';
	$dt_id = $cn->get_value("select ag_id from action_gestion_comment where agc_id=$1", array($id));
	if ($g_user->check_action(RMDOC) == 1 && $g_user->can_write_action($dt_id) == true)
	{
		$cn->exec_sql("delete from action_gestion_comment where agc_id=$1", array($id));
		$json = sprintf('{"agc_id":"%s"}', $id);
	}
	header("Content-type: text/html; charset: utf8", true);
	print $json;
}
/* remove the action from action_gestion_operation */
if ($action == 'rmaction')
{
	$json = '{"act_id":"-1"}';
	if ($g_user->check_action(RMDOC) == 1 && $g_user->can_write_action($id) == true && $g_user->can_write_action($ag_id) == true)
	{
		$cn->exec_sql("delete from action_gestion_related where aga_least=$1 and aga_greatest=$2", array($id, $ag_id));
		$cn->exec_sql("delete from action_gestion_related where aga_least=$2 and aga_greatest=$1", array($id, $ag_id));
		$json = sprintf('{"act_id":"%s"}', $id);
	}
	header("Content-type: text/html; charset: utf8", true);
	print $json;
}
