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
/** \file
 * \brief Users Security
 */
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
include_once("ac_common.php");
require_once('class_database.php');
include_once("user_menu.php");
include_once ("class_user.php");

$rep = new Database();

if (!isset($_REQUEST['use_id']))
{
    html_page_stop();
    exit();
}
$uid = $_REQUEST['use_id'];
$UserChange = new User($rep, $uid);

if ($UserChange->id == false)
{
    // Message d'erreur
    html_page_stop();
}

/*  Save the changes */
if (isset($_POST['SAVE']))
{
    $uid = $_POST['UID'];

    // Update User
    $cn = new Database();
    $UserChange = new User($cn, $uid);
    if ($UserChange->load() == -1)
    {
        alert("Cet utilisateur n'existe pas");
    }
    else
    {
        $UserChange->first_name = $_POST['fname'];
        $UserChange->last_name = $_POST['lname'];
        $UserChange->active = $_POST['Actif'];
        $UserChange->admin = $_POST['Admin'];
        if ( trim($_POST['password'])<>'')
        {
                    $UserChange->pass = md5($_POST['password']);
        }		else
		{
			$UserChange->pass=$UserChange->password;
		}
        $UserChange->save();

        // Update Priv on Folder
        foreach ($_POST as $name => $elem)
        {
            if (substr_count($name, 'PRIV') != 0)
            {
                $cn = new Database();
				if ( defined ('MULTI')&& MULTI==0)
				{
					$name=dbname;
					$db_id=MONO_DATABASE;
					$UserChange->set_folder_access($db_id, $elem);
				}
				else
				{
				    $db_id = mb_substr($name, 4);
					$name=$cn->format_name($db_id, "dos");
					if ( $cn->exist_database($name) == 1 )
					{
						$UserChange->set_folder_access($db_id, $elem);
						Dossier::synchro_admin($db_id);
					}
				}
			}
        }
    }
}
else
{
    if (isset($_POST["DELETE"]))
    {
        $cn = new Database();
        $Res = $cn->exec_sql("delete from priv_user where priv_jnt in ( select jnt_id from jnt_use_dos where use_id=$1)", array($uid));
        $Res = $cn->exec_sql("delete from jnt_use_dos where use_id=$1", array($uid));
        $Res = $cn->exec_sql("delete from ac_users where use_id=$1", array($uid));

        echo "<center><H2 class=\"info\"> User " . h($_POST['fname']) . " " . h($_POST['lname']) . " est effacé</H2></CENTER>";
        require_once("class_iselect.php");
        require_once("user.inc.php");
        return;
    }
}
$UserChange->load();
$it_pass=new IText('password');
$it_pass->value="";
?>
<h1 class="info">Modification</h1>
<?php echo HtmlInput::button_anchor('Retour', 'admin_repo.php?action=user_mgt'); ?>
<FORM  METHOD="POST">

<?php echo HtmlInput::hidden('UID',$uid)?>
    <TABLE BORDER=0>
        <TR>

<?php printf('<td>login</td><td> %s</td>', $UserChange->login); ?>
            </TD>
        </tr>
        <TR>
            <TD>
            <?php printf('Nom de famille </TD><td><INPUT class="input_text"  type="text" NAME="lname" value="%s"> ', $UserChange->name); ?>
            </TD>
        </TR>
        <TR>
          <?php printf('<td>prénom</td><td>
             <INPUT class="input_text" type="text" NAME="fname" value="%s"> ', $UserChange->first_name);
                ?>
        </TD>
        </TR>
        <tr>
            <td>
                Mot de passe :<span class="info">Laisser à VIDE pour ne PAS le changer</span>
            </td>
            <td>
                <?php echo $it_pass->input();?>
            </td>
        </tr>
    </table>

    <TABLE>
<?php
if ($UserChange->active == 1)
{
    $ACT = "CHECKED";
    $NACT = "UNCHECKED";
}
else
{
    $ACT = "UNCHECKED";
    $NACT = "CHECKED";
}
echo "<TR><TD>";
printf('<INPUT type="RADIO" NAME="Actif" VALUE="1" %s> Actif', $ACT);
echo "</TD><TD>";
printf('<INPUT type="RADIO" NAME="Actif" VALUE="0" %s> Non Actif', $NACT);
echo "</TD></TR>";
?>
    </TABLE>
</TD>
<TD>
    <TABLE>
<?php
if ($UserChange->admin == 1)
{
    $ACT = "CHECKED";
    $NACT = "UNCHECKED";
}
else
{
    $ACT = "UNCHECKED";
    $NACT = "CHECKED";
}
echo "<TR><TD>";
printf('<INPUT type="RADIO" NAME="Admin" VALUE="1" %s> Administrateur global', $ACT);
echo "</TD><TD>";
printf('<INPUT type="RADIO" NAME="Admin" VALUE="0" %s> Pas administrateur global ', $NACT);
echo "</TD></TR>";
?>
    </TABLE>
</TD>
</TR>
<TR>
    <TD>
        <!-- Show all database and rights -->
        <H2 class="info"> Droit sur les dossiers pour les utilisateurs normaux </H2>
        <p class="notice">
            Les autres droits doivent être réglés dans les dossiers (paramètre->sécurité), le fait de changer un utilisateur d'administrateur à utilisateur
			normal ne change pas le profil administrateur dans les dossiers.
			Il faut aller dans CFGSECURITY pour diminuer ses privilèges.
        </p>
        <TABLE>
<?php
$array = array(
    array('value' => 'X', 'label' => 'Aucun Accès'),
    array('value' => 'R', 'label' => 'Utilisateur normal')
);
$repo = new Dossier(0);

$Dossier = $repo->show_dossier('all', 1, 0);
if (empty($Dossier))
{
    echo hb('* Aucun Dossier *');
    echo '</div>';
    exit();
}

$mod_user = new User(new Database(), $uid);
foreach ($Dossier as $rDossier)
{
	if (defined ("MULTI") && MULTI==0)
	{
			$priv = $mod_user->get_folder_access(MONO_DATABASE);
			$priv=($priv=='L')?'R':$priv;
	}
		else
			$priv = $mod_user->get_folder_access($rDossier['dos_id']);
    printf("<TR><TD> Dossier : %s </TD>", h($rDossier['dos_name']));

    $select = new ISelect();
    $select->table = 1;
    $select->name = sprintf('PRIV%s', $rDossier['dos_id']);
    $select->value = $array;
    $select->selected = $priv;
    echo $select->input();
    echo "</TD></TR>";
}
?>
        </TABLE>





        <input type="Submit" class="button" NAME="SAVE" VALUE="Sauver les changements" onclick="return confirm('Confirmer changement ?');">

        <input type="Submit"  class="button" NAME="DELETE" VALUE="Effacer" onclick="return confirm('Confirmer effacement ?');" >
<?php echo HtmlInput::button_anchor('Retour', 'admin_repo.php?action=user_mgt'); ?>
</FORM>

</DIV>










<?php
html_page_stop();
?>


