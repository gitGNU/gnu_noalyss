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
require_once 'class_profile_sql.php';
global $cn;

//**********************************************
// Save avail. profiles
//**********************************************
if (isset($_POST['change_profile']))
{
	extract($_POST);
	try
	{
		for ($e = 0; $e < count($right); $e++)
		{
			if ($right[$e] == 'X' && $ua_id[$e]=='')
				continue;
			if ($right[$e] == 'X' && $ua_id[$e]!='')
			{
				$cn->exec_sql("delete from user_sec_action_profile where p_id=$1 and p_granted=$2", array($p_id, $ap_id[$e]));
				continue;
			}
			if ($ua_id[$e] == "")
			{
				$cn->exec_sql("insert into user_sec_action_profile (p_id,p_granted,ua_right) values($1,$2,$3)", array($p_id, $ap_id[$e], $right[$e]));
				continue;
			}
			if ($ua_id[$e] != '')
			{
				$cn->exec_sql("update user_sec_action_profile set ua_right=$3 where  p_id=$1 and p_granted=$2 ", array($p_id, $ap_id[$e], $right[$e]));
				continue;
			}
		}
	}
	catch (Exception $exc)
	{
		echo $exc->getTraceAsString();
		throw $exc;
	}
}
//**********************************************
// Save_name
// *********************************************

if (isset($_POST['save_name']))
{

	extract($_POST);
	try
	{
		if (strlen(trim($p_name)) == 0)
			throw new Exception("Nom ne peut être vide");
		if (isNumber($p_id) == 0)
			throw new Exception("profile Invalide");
		$wc = (isset($with_calc)) ? 1 : 0;
		$wd = (isset($with_direct_form)) ? 1 : 0;
		$p_desc = (strlen(trim($p_desc)) == 0) ? null : trim($p_desc);
		if ($p_id != -1)
		{
			$cn->exec_sql("update profile set p_name=$1,p_desc=$2,
					with_calc=$3, with_direct_form=$4 where p_id=$5", array($p_name,
				$p_desc, $wc, $wd, $p_id));
		}
		else
		{
			$p_id = $cn->get_value("insert into profile (p_name,
				p_desc,with_calc,with_direct_form) values
				($1,$2,$3,$4) returning p_id", array(
				$p_name, $p_desc, $wc, $wd
					));
		}
	}
	catch (Exception $e)
	{
		alert($e->getMessage());
	}
}
//************************************
// Clone
//************************************
if (isset($_POST['clone']))
{
	extract($_POST);
	try
	{
		$cn->start();
		$new_id = $cn->get_value("insert into profile(p_name,p_desc,with_calc,
			with_direct_form)
			select 'copie de '||p_name,p_desc,with_calc,
			with_direct_form from profile where p_id=$1 returning p_id", array($p_id));
		$cn->exec_sql("
				insert into profile_menu (p_id,me_code,me_code_dep,p_order,p_type_display,pm_default)
				select $1,me_code,me_code_dep,p_order,p_type_display,pm_default from profile_menu
				where p_id=$2
			", array($new_id, $p_id));
		$cn->commit();
		$p_id = $new_id;
	}
	catch (Exception $exc)
	{
		echo alert($exc->getMessage());
		$cn->rollback();
	}
}
//************************************
// Delete
//************************************
if (isset($_POST['delete_profil']))
{
	extract($_POST);
	try
	{
		$cn->start();
		if ($p_id == 1)
		{
			throw new Exception('On ne peut effacer le profil par défaut');
		}
		$new_id = $cn->get_value("delete from profile
			where p_id=$1 ", array($p_id));
		$cn->commit();
	}
	catch (Exception $exc)
	{
		echo alert($exc->getMessage());
		;
		$cn->rollback();
	}
}
//************************************
// Modify the menu or delete it
//************************************
if (isset($_POST['mod']))
{
	extract($_POST);
	if (isset($delete) || isset($del_dep))
	{
		try
		{
			$cn->start();
			if (isset($del_dep))
			{
				$cn->exec_sql("delete from profile_menu where pm_id in (select * from get_menu_dependency($1))", array($pm_id));
			}
			$cn->exec_sql("delete from profile_menu where pm_id=$1", array($pm_id));
			$cn->commit();
		}
		catch (Exception $exc)
		{
			echo $exc->getMessage();
			$cn->rollback();
		}
	}
	else
		try
		{
			/**
			 * Printing cannot be a menu and do not depend of anything
			 */
			$menu_type = $cn->get_value("select me_type from menu_ref
			where me_code=$1", array($me_code));

			if ($menu_type == 'PR')
			{
				$p_type = 'P';
				$me_code_dep = -1;
			}

			$cn->start();
			$me_code_dep = ($me_code_dep == -1) ? null : $me_code_dep;
			$pm_default = (isset($pm_default)) ? 1 : 0;
			$p_order = (strlen(trim($p_order)) == 0) ? "0" : $p_order;
			if ($pm_default == 1)
			{
				$cn->exec_sql("update profile_menu set pm_default=0
				where p_id=(select p_id from profile_menu
								where
								pm_id=$1)", array($pm_id));
			}
			$cn->exec_sql("update profile_menu set me_code=$1,me_code_dep=$2,p_order=$3,pm_default=$4
			where pm_id=$5", array($me_code, $me_code_dep, $p_order, $pm_default, $pm_id));
			$cn->commit();
		}
		catch (Exception $e)
		{
			$cn->rollback();
			alert($e->getMessage());
		}
}

//****************************************************
// Add a menu, module, submenu,plugin...
//****************************************************
if (isset($_POST['add_menu']))
{
	extract($_POST);
	try
	{
		$cn->start();

		/**
		 * Printing cannot be a menu and do not depend of anything
		 */
		$menu_type = $cn->get_value("select me_type from menu_ref
			where me_code=$1", array($me_code));

		if ($menu_type == 'PR')
		{
			$p_type = 'P';
			$me_code_dep = -1;
		}

		// Module never depends of anything
		if ($p_type == 'M')
		{
			$me_code_dep = -1;
		}
		/**
		 * Check for infinite loop
		 */
		$inf = $cn->get_value("select count(*) from profile_menu
			where p_id=$1 and me_code_dep=$2 and me_code=$3", array($p_id, $me_code, $me_code_dep));
		if ($inf > 0)
			throw new Exception("Boucle infinie");
		/**
		 * if me_code_dep == -1, it means it is null
		 */
		$me_code_dep = ($me_code_dep == -1) ? null : $me_code_dep;

		$pm_default = (isset($pm_default)) ? 1 : 0;
		$cn->exec_sql("
				insert into profile_menu (me_code,me_code_dep,p_id,p_order,pm_default,p_type_display)
				values ($1,$2,$3,$4,$5,$6)
				", array($me_code, $me_code_dep, $p_id, $p_order, $pm_default, $p_type));

		$cn->commit();
	}
	catch (Exception $exc)
	{
		alert($exc->getMessage());
	}
}

echo '<div id="list_profile" class="content">';
$table = new Sort_Table();
$url = $_SERVER['REQUEST_URI'];

$table->add('Nom', $url, "order by p_name asc", "order by p_name desc", "na", "nd");
$table->add('Description', $url, "order by p_desc asc", "order by p_desc desc", "da", "dd");
$table->add('Calculatrice visible', $url, "order by with_calc asc", "order by with_calc desc", "ca", "cd");
$table->add('Form Direct visible', $url, "order by with_direct_form asc", "order by with_direct_form desc", "fa", "fd");

$ord = (isset($_REQUEST['ord'])) ? $_REQUEST['ord'] : 'na';

$order = $table->get_sql_order($ord);

$menu = new Profile_sql($cn);
$ret = $menu->seek("where p_id > 0 ".$order);
echo '<table class="result">';
echo '<tr>';
echo '<th>' . $table->get_header(0) . '</th>';
echo '<th>' . $table->get_header(1) . '</th>';
echo '<th>' . $table->get_header(2) . '</th>';
echo '<th>' . $table->get_header(3) . '</th>';
echo '</tr>';
$gDossier = Dossier::id();
for ($i = 0; $i < Database::num_row($ret); $i++)
{
	$row = $menu->get_object($ret, $i);

	$js = sprintf('<a href="javascript:void(0)" style="text-decoration:underline" onclick="get_profile_detail(\'%s\',\'%s\')">', $gDossier, $row->p_id);
	echo '<tr>';
	echo "<td>" . $js . $row->p_name . '</a>' . '</td>';
	echo td($row->p_desc);
	echo td($row->with_calc);
	echo td($row->with_direct_form);
	echo '</tr>';
}
$js = sprintf('<a href="javascript:void(0)"  class="button" onclick="get_profile_detail(\'%s\',\'%s\')">', $gDossier, -1);
echo '<tr>';
echo "<td>" . $js . "Ajouter un profil </td>";
echo '</tr>';
echo '</table>';
echo '</div>';


//*******************************************************
// Show details of the selected profile
//*******************************************************
echo '<div id="detail_profile" class="content">';
if (isset($_POST['p_id']))
{
	require_once 'ajax_get_profile.php';
}
echo '</div>';
?>
