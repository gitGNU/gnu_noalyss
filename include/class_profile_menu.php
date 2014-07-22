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
	/**
	 * Display the content of a profile
	 * @param type $resource
	 * @param type $p_id
	 */
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
				$me_def=($menu['pm_default']=='1')?'<span class="notice" style="display:inline">Défaut</span>':'';
				$js = sprintf(
						'<a class="line" href="javascript:void(0)" onclick="mod_menu(\'%s\',\'%s\')">%s</A>', $gDossier, $menu['pm_id'], $me_code);
				?>
				<li>

					<?php echo  $me_menu?>
					( <?php echo  $js?> )
					<?php echo  $me_desc?>  <?php echo $me_def?>
					<?php 
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
					$me_def=($array[$i]['pm_default']=='1')?'<span class="notice" style="display:inline">Défaut</span>':'';
					$js = sprintf('<a class="line" style="display:inline;text-decoration:underline"
						href="javascript:void(0)" onclick="mod_menu(\'%s\',\'%s\')">%s</A>', $gDossier, $array[$i]['pm_id'], $array[$i]['me_code']);
					echo "<li>" . $array[$i]['me_menu'] . " (" . $js . ")" . $array[$i]['me_description']." ".$me_def;

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
					p_id=$1 and  p_type_display not in ('M','P') and me_code_dep is null
					order by p_order asc
							", array($p_id));
				if (Database::num_row($ret))
				{
					echo "<h2>Menu sans module</h2>";
					$this->sub_menu($ret, $p_id);
				}
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
		/**
		 * Show the available profile for the profile $p_id, it concerns only the action of management (action-gestion)
		 * @param $p_id is the profile p_id
		 */
		function available_profile($p_id)
		{
			$array=$this->cn->get_array("
					select p.p_id,p.p_name,s.p_granted,s.ua_id,s.ua_right
						from profile as p
						join user_sec_action_profile as s on (s.p_granted=p.p_id)
						where s.p_id=$1
					union
						select p2.p_id, p2.p_name,null,null,'X'
						from profile as p2
						where
						p2.p_id not in (select p_granted from user_sec_action_profile where p_id = $1) order by p_name;
				",array($p_id));
			$aright_value=array(
								array('value'=>'R','label'=>_('Lecture')),
								array('value'=>'W','label'=>_('Ecriture')),
								array('value'=>'X','label'=>_('Aucun accès'))
					);
			require_once 'template/user_sec_profile.php';
		}
		/**
		 * Show the available repository for the profile $p_id
		 * @param $p_id is the profile p_id
		 */
		function available_repository($p_id)
		{
			$array=$this->cn->get_array("
					select p.r_id,p.r_name,s.ur_id,s.ur_right
						from stock_repository as p
						join profile_sec_repository as s on (s.r_id=p.r_id)
						where s.p_id=$1
					union
						select p2.r_id, p2.r_name,null,'X'
						from stock_repository as p2
						where
						p2.r_id not in (select r_id from profile_sec_repository where p_id = $1) order by r_name;
				",array($p_id));
			$aright_value=array(
								array('value'=>'R','label'=>_('Lecture')),
								array('value'=>'W','label'=>_('Ecriture')),
								array('value'=>'X','label'=>_('Aucun accès'))
					);
			require_once 'template/profile_sec_repository.php';
		}
	}
	//end class
	?>