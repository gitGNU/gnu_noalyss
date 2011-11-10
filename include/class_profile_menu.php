<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'class_profile_menu_sql.php';

/**
 * Manage the menu of a profile
 *
 * @author dany
 */
class Profile_Menu
{

	function __construct($p_cn)
	{
		$this->cn = $p_cn;
	}

	function sub_menu($resource, $p_id)
	{
		if (Database::num_row($resource) != 0)
		{
			$gDossier = dossier::id();
			echo '<ul style="list-style-type:none">';
			for ($e = 0; $e < Database::num_row($resource); $e++)
			{
				$menu = Database::fetch_array($resource, $e);
				$me_code = $menu['me_code'];

				$me_code_dep = $menu['me_code_dep'];

				$mp_type = $menu['p_type_display'];

				$me_menu = $menu['me_menu'];
				$me_desc = $menu['me_description'];
				$js = sprintf(
						'<a class="line" href="javascript:void(0)" onclick="mod_menu(\'%s\',\'%s\')">%s</A>', $gDossier, $menu['pm_id'], $me_code);
				?>
				<li>
					<?= $me_menu?>
					( <?= $js?> )
					<?= $me_desc?>
					<?
					$ret2 = $this->cn->exec_sql("
									SELECT pm_id,
										pm.me_code,
										me_code_dep,
										p_id,
										p_order,
										p_type_display,
										pm_default,
										pm_desc,
										me_menu,
										me_description
										FROM profile_menu as pm
											join profile_menu_type on (p_type_display=pm_type)
											join menu_ref as mr on (mr.me_code=pm.me_code)
										where
										p_id=$1 and me_code_dep=$2
										order by p_order asc
							", array($p_id, $me_code));
					$this->sub_menu($ret2, $p_id);
					echo "</li>";
				} //end loop e
				echo '</ul>';
			} // end if
		}

		/**
		 * Show a table with all the menu and the type
		 * @param type $p_id profile.p_id
		 */
		function listing_profile($p_id)
		{
			$array = $this->cn->get_array("
			SELECT pm_id,
					pm.me_code,
					me_code_dep,
					p_id,
					p_order,
					p_type_display,
					pm_default,
					pm_desc,
					me_menu,
					me_description
			FROM profile_menu as pm join profile_menu_type on (p_type_display=pm_type)
			join menu_ref as mr on (mr.me_code=pm.me_code)
			where
			p_id=$1 and p_type_display='M'
			order by p_order asc
			", array($p_id));
			if (count($array) == 0)
			{
				// if not module show only menu
				$ret = $this->cn->exec_sql("
										SELECT pm_id,
										pm.me_code,
										me_code_dep,
										p_id,
										p_order,
										p_type_display,
										pm_default,
										pm_desc,
										me_menu,
										me_description
										FROM profile_menu as pm
											join profile_menu_type on (p_type_display=pm_type)
											join menu_ref as mr on (mr.me_code=pm.me_code)
					where
					p_id=$1 and p_type_display='E'
					order by p_order asc
							", array($p_id));
				echo '<ul style="list-style-type:none">';

				$this->sub_menu($ret, $p_id);
				// $this->listing_profile($p_id,$ret, $array[$i]['me_code']);

				echo "</li>";
				echo '</ul>';
			}
			else
			{
				$this->cn->prepare("menu", "
				SELECT pm_id,
										pm.me_code,
										me_code_dep,
										p_id,
										p_order,
										p_type_display,
										pm_default,
										pm_desc,
										me_menu,
										me_description
										FROM profile_menu as pm
											join profile_menu_type on (p_type_display=pm_type)
											join menu_ref as mr on (mr.me_code=pm.me_code)
					where
					p_id=$1 and me_code_dep=$2 and p_type_display in ('E','S')
					order by p_order asc
							");
				echo '<ul style="list-style-type:none">';
				// Menu by module
				$gDossier = Dossier::id();
				for ($i = 0; $i < count($array); $i++)
				{
					$js = sprintf('<a class="line" style="display:inline;text-decoration:underline"
						href="javascript:void(0)" onclick="mod_menu(\'%s\',\'%s\')">%s</A>', $gDossier, $array[$i]['pm_id'], $array[$i]['me_code']);
					echo "<li>" . $array[$i]['me_menu'] . " (" . $js . ")" . $array[$i]['me_description'];

					$ret = $this->cn->execute("menu", array($p_id, $array[$i]['me_code']));
					$this->sub_menu($ret, $p_id);

					echo "</li>";
				}// end loop i
				echo '</ul>';
				//*******************************************
				// show also menu without a module
				//*******************************************
				$ret = $this->cn->exec_sql("
										SELECT pm_id,
										pm.me_code,
										me_code_dep,
										p_id,
										p_order,
										p_type_display,
										pm_default,
										pm_desc,
										me_menu,
										me_description
										FROM profile_menu as pm
											join profile_menu_type on (p_type_display=pm_type)
											join menu_ref as mr on (mr.me_code=pm.me_code)
					where
					p_id=$1 and p_type_display='E' and me_code_dep is null
					order by p_order asc
							", array($p_id));

				$this->sub_menu($ret, $p_id);
			}
		}

		function printing($p_id)
		{
			$ret = $this->cn->exec_sql("
				SELECT pm_id,
										pm.me_code,
										me_code_dep,
										p_id,
										p_order,
										p_type_display,
										pm_default,
										pm_desc,
										me_menu,
										me_description
										FROM profile_menu as pm
											join profile_menu_type on (p_type_display=pm_type)
											join menu_ref as mr on (mr.me_code=pm.me_code)
					where
					p_id=$1 and me_type='PR'
					order by p_order asc
							", array($p_id));
			// Menu by module
			$gDossier = Dossier::id();
			$this->sub_menu($ret, $p_id);
		}

	}

	//end class
	?>