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
if ( !defined ('ALLOWED')) die('Forbidden');
require_once('class_iradio.php');
require_once('class_ifile.php');

/*!\file
 * \brief restaure a database
 */
if ( isset ($_REQUEST['sa'] ))
{
    if ( defined ('PG_PATH') )
        putenv("PATH=".PG_PATH);

    $cmd=escapeshellcmd (PG_RESTORE);
    putenv("PGPASSWORD=".phpcompta_password);
    putenv("PGUSER=".phpcompta_user);
    putenv("PGPORT=".phpcompta_psql_port);
	putenv("PGHOST=").phpcompta_psql_host;

    $retour='<hr>'.HtmlInput::button_anchor("Retour","?action=restore");
    if ( ! isset($_REQUEST['t']))
    {
        echo '<div class="content">';
        echo ("<span class=\"error\">Vous devez préciser s'il s'agit d'un modèle ou d'un dossier</span>");
        echo $retour;
        echo '</div>';
        exit();
    }
    if ( empty ($_FILES['file']['name']) ||
            strlen(trim($_FILES['file']['name']))==0
       )
    {
        echo '<div class="content">';

        echo ("<span class=\"error\">Vous devez donner un fichier </span>");
        echo $retour;
        echo '</div>';
        exit();
    }
    //---------------------------------------------------------------------------
    // Restore a folder (dossier)
    if ( $_REQUEST['t']=='d')
    {
        ini_set('upload_max_filesize','25M');
        ini_set('post_max_size','25M');
        echo '<div class="content" style="width:80%;margin-left:10%">';

        $cn=new Database();
        $id=$cn->get_next_seq('dossier_id');

        if ( strlen(trim($_REQUEST['database'])) == 0 )
		{
            $lname=$id." Restauration :".sql_string($_FILES['file']['name']);
		}
        else
		{
            $lname=$id." ".$_REQUEST['database'];
		}

		if (strlen(trim($_REQUEST['desc']))==0)
		{
			$ldesc=$lname;
		}
		else
		{
			$ldesc=sql_string($_REQUEST['desc']);
		}

        $sql="insert into ac_dossier (dos_id,dos_name,dos_description) values (".$id.",'".$lname."','".$ldesc."') ";
        $cn->start();
        try
        {
            $cn->get_value($sql);


        }
        catch ( Exception $e)
        {
            echo '<span class="error">'."Echec de la restauration ".'</span>';
            $cn->rollback();
            exit();
        }
        $cn->commit();
        $name=domaine."dossier".$id;
        echo $name;
        $cn->exec_sql("create database ".$name." encoding='utf8'");
        $args=" --no-owner  -d $name ".$_FILES['file']['tmp_name'];

        exec(PG_RESTORE.$args);
        $test=new Database($id);
        if ( $test->exist_table('version') )
        {
            echo '<h2 class="info"> Restauration réussie du dossier '.$lname.'</h2>';
            $test->close();
        }
        else
        {
            $test->close();
            echo '<h2 class="error"> Problème lors de la restauration '.$lname.'</h2>';
            $cn->exec_sql('delete from ac_dossier where dos_id=$1',array($id));
            $cn->exec_sql('drop database '.$name);
            exit();
        }
        $new_cn=new Database($id);

        $new_cn->apply_patch($name,0);
        echo '<span class="error">'.'Ne pas recharger la page, sinon votre base de données sera restaurée une fois de plus'.'</span>';
		Dossier::synchro_admin($id);
        echo $retour;

        echo '</div>';
    }
    //---------------------------------------------------------------------------
    // Restore a modele

    if ( $_REQUEST['t']=='m')
    {
        ini_set('upload_max_filesize','25M');
        ini_set('post_max_size','25M');

        echo '<div class="content">';

        $cn=new Database();
        $id=$cn->get_next_seq('s_modid');

        if ( strlen(trim($_REQUEST['database'])) == 0 )
            $lname=$id." Restauration :".sql_string($_FILES['file']['name']);
        else
            $lname=$id." ".$_REQUEST['database'];


        $sql="insert into modeledef (mod_id,mod_name,mod_desc) values (".$id.",'Restauration".$lname."','".$ldesc."') ";
        $cn->start();
        try
        {
            $cn->get_value($sql);

        }
        catch ( Exception $e)
        {
            echo '<span class="error">'."Echec de la restauration ".'</span>';
            $cn->rollback();
            exit();
        }
        $cn->commit();

        $name=domaine."mod".$id;
        $cn->exec_sql("create database ".$name." encoding='utf8'");
        $args="   -d $name ".$_FILES['file']['tmp_name'];
        $status=exec(PG_RESTORE.$args);

        $test=new Database($id,'mod');
        if ( $test->exist_table('version') )
        {
            echo '<h2 class="info"> Restauration réussie du dossier '.$lname.'</h2>';
            $test->close();
        }
        else
        {
            $test->close();
            echo '<h2 class="error"> Problème lors de la restauration '.$lname.'</h2>';
            $cn->exec_sql('delete from modeledef where mod_id=$1',array($id));
            $cn->exec_sql('drop database '.$name);
            exit();
        }

        $new_cn=new Database($id,'mod');

        $new_cn->apply_patch($name,0);

        echo '<span class="error">'.'Ne pas recharger la page, sinon votre base de données sera restaurée une fois de plus'.'</span>';
        echo $retour;

        echo '</div>';
    }
}
else
{
    echo '<div class="content" style="width:80%;margin-left:10%">';
    echo '<form method="POST" action="admin_repo.php" enctype="multipart/form-data" >';
    echo HtmlInput::hidden('action','restore');
    echo HtmlInput::hidden('sa','r');
    echo '<table>';
    echo '<tr><td>'."Nom de la base de donnée".HtmlInput::infobulle(29)
			.'</td>';
    $wNom=new IText();
    $wNom->name="database";
    $wNom->size=30;
    echo '<td>'.$wNom->input().'</td></tr>';
    echo '<tr><td>'."Type de backup :".'</td>';
    $chk=new IRadio();
    $chk->name="t";
    $chk->value="d";
    echo '<td> '.$chk->input()."Dossier".'</td>';
    echo '</tr><tr><td></td>';
    $chk->name="t";
    $chk->value="m";
    echo '<td>'.$chk->input()."Modele".'</td>';
    echo '<tr>';
    $file=new IFile();
    $file->name="file";
    $file->value="mod";
    echo td('Fichier ').
    td($file->input());
	$desc=new ITextarea('desc');
	echo '</tr>';
    echo '</table>';
	echo "<p>Description </p>";
	$desc->heigh=4;$desc->width=60;
	echo $desc->input();
	echo '<p>';
    echo HtmlInput::submit("","Restauration");
	echo '</p>';
    echo '</form>';
    echo '</div>';
}
