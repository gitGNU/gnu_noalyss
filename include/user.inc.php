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

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
if ( !defined ('ALLOWED')) die('Forbidden');
/*!\file
 *
 *
 * \brief user managemnt, included from admin_repo,
 * action=user_mgt
 *
 */
require_once 'class_sort_table.php';
echo '<div class="content" style="width:80%;margin-left:10%">';
/******************************************************/
// Add user
/******************************************************/
if ( isset ($_POST["ADD"]) )
{
    $cn=new Database();
    $pass5=md5($_POST['PASS']);

    $first_name=Database::escape_string($_POST['FNAME']);
    $last_name=Database::escape_string($_POST['LNAME']);
    $login=$_POST['LOGIN'];
    $login=str_replace("'","",$login);
    $login=str_replace('"',"",$login);
    $login=str_replace(" ","",$login);
    $login=strtolower($login);
	if ( trim($login)=="")
	{
		alert("Le login ne peut pas être vide");
	}
	else
	{
    $Res=$cn->exec_sql("insert into ac_users(use_first_name,use_name,use_login,use_active,use_pass)
                       values ($1,$2,$3,1,$4)",
                       array($first_name,$last_name,$login,$pass5));

	}
} //SET login

// View user detail
if ( isset($_REQUEST['det']))
{
    require_once("user_detail.inc.php");

    exit();
}
?>

<div id="create_user" style="display:none">
<h2>Gestion Utilisateurs</h2>
<TABLE> <TR>
<form action="admin_repo.php?action=user_mgt" method="POST">
             <TD><H3>
             <?php
             echo _("Ajout d'utilisateur");
echo '<H3></TD></TR>';
echo '<TR><TD> First Name </TD><TD><INPUT class="input_text" TYPE="TEXT" NAME="FNAME"></TD>';
echo '<TD> Last Name </TD><TD><INPUT class="input_text"  TYPE="TEXT" NAME="LNAME"></TD></TR>';
echo '<TR><TD> login </TD><TD><INPUT class="input_text"  TYPE="TEXT" NAME="LOGIN"></TD>';
echo '<TD> password </TD><TD> <INPUT class="input_text" TYPE="TEXT" NAME="PASS"></TD></TR>';
echo '</TABLE>';
echo HtmlInput::submit("ADD",'Créer Utilisateur');
echo HtmlInput::button_action("Fermer", "$('create_user').style.display='none';$('cu').style.display='block'");


?>
</FORM>
</div>

<?php
echo '<p>';
echo HtmlInput::button_action("Ajout utilisateur", "$('create_user').show();$('cu').hide()","cu");
echo '</p>';
// Show all the existing user on 7 columns
$repo=new Dossier(0);
/******************************************************/
// Detail of a user
/******************************************************/



$compteur=0;
$header=new Sort_Table();
$url=basename($_SERVER['PHP_SELF'])."?action=".$_REQUEST['action'];
$header->add("Login", $url," order by use_login asc", "order by use_login desc","la", "ld");
$header->add("Nom", $url," order by use_name asc,use_first_name asc", "order by use_name desc,use_first_name desc","na", "nd");
$header->add('Dossier',$url,' order by ag_dossier asc','order by ag_dossier desc',
        'da','dd');
$header->add("Actif", $url," order by use_active asc", "order by  use_active desc","aa", "ad");
$ord=(isset($_REQUEST['ord']))?$_REQUEST['ord']:'la';
$sql=$header->get_sql_order($ord);

$a_user=$repo->get_user_folder($sql);

if ( !empty ($a_user) )
{
	echo '<span style="display:block">';
	echo _('Filtre').HtmlInput::infobulle(22);
	echo HtmlInput::filter_table("user", "0,1,2,5","1");
	echo '</span>';
    echo '<table id="user" class="result">';
    echo '<tr>';
    echo '<th>'.$header->get_header(0).'</th>';
    echo '<th>'.$header->get_header(1).'</th>';
    echo th("Prénom");
    echo '<th>'.$header->get_header(3).'</th>';
	echo "<th>Type</th>";
    echo '<th>'.$header->get_header(2).'</th>';
    echo '</tr>';

    foreach ( $a_user as $r_user)
    {
        $compteur++;
        $class=($compteur%2==0)?"odd":"even";

        echo "<tr class=\"$class\">";
        if ( $r_user['use_active'] == 0 )
        {
            $Active="non actif";
        }
        else
        {
            $Active="Actif";
        }
        $det_url=$url."&det&use_id=".$r_user['use_id'];
        echo "<td>";
        echo HtmlInput::anchor($r_user['use_login'],$det_url);
        echo "</td>";

        echo td($r_user['use_name']);
        echo td($r_user['use_first_name']);
        echo td($Active);
		$type=($r_user['use_admin']==1)?"Administrateur":"Utilisateur";
		echo "<td>".$type."</td>";
		echo td($r_user['ag_dossier']);
        echo '</tr>';
    }// foreach
    echo '</table>';
} // $cn != null
?>

</div>