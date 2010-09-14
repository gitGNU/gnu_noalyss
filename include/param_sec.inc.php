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

/* $Revision$ */

/*! \file
 * \brief Set the security for an user
 */

include_once ("ac_common.php");
require_once("class_iselect.php");
require_once('class_dossier.php');
$gDossier=dossier::id();
$str_dossier=dossier::get();

require_once('class_database.php');
/* Admin. Dossier */
$cn=new Database($gDossier);
include_once ("class_user.php");
$User=new User($cn);
$User->Check();
$User->check_dossier($gDossier);

include_once ("user_menu.php");
$cn_dossier=new Database($gDossier);


if ( $User->check_action(PARSEC) == 0 )
{
    /* Cannot Access */
    NoAccess();
    exit -1;
}

$cn=new Database();
/*  Show all the users, included local admin */
$user_sql=$cn->exec_sql("select  use_id,use_first_name,use_name,use_login,use_admin,priv_priv from ac_users natural join jnt_use_dos ".
                        " join priv_user on (jnt_id=priv_jnt) where use_login != 'phpcompta' and dos_id=".$gDossier.' order by use_login,use_name');
$MaxUser=Database::num_row($user_sql);
if ( ! isset($_REQUEST['action']))
{
    echo '<DIV class="content" >';

    echo '<TABLE CELLSPACING="20" ALIGN="CENTER">';
    for ($i = 0;$i < $MaxUser;$i++)
    {
        $l_line=Database::fetch_array($user_sql,$i);
        //  echo '<TR>';
        if ( $i % 3 == 0 && $i != 0)
            echo "</TR><TR>";
        $str=($l_line['priv_priv'] == 'L')?'Local Admin':' ';
        $str=($l_line['priv_priv'] == 'P')?'Uniquement Extension':$str;
        $str=($l_line['priv_priv'] == 'R')?'Utilisateur Normal':$str;
        if ( $l_line['use_admin'] == 1 )
            $str=' Super Admin';

        printf ('<TD><A href="?p_action=sec&action=view&user_id=%s&'.$str_dossier.'">%s %s ( %s )[%s]</A></TD>',
                $l_line['use_id'],
                $l_line['use_first_name'],
                $l_line['use_name'],
                $l_line['use_login'],
                $str);

    }
    echo "</TR>";
    echo '</TABLE>';
}
$action="";

if ( isset ($_GET["action"] ))
{
    $action=$_GET["action"];

}
//----------------------------------------------------------------------
// Action = save
//----------------------------------------------------------------------
if ( isset($_POST['ok']))
{

    $sec_User=new User($cn_dossier,$_POST['user_id']);
    /* Save first the ledger */
    $cn_dossier=new Database(dossier::id());
    $a=$cn_dossier->get_array('select jrn_def_id from jrn_def');
    foreach ($a as $key)
    {
        $id=$key['jrn_def_id'];
        $priv=sprintf("jrn_act%d",$id);
        $count=$cn_dossier->get_value('select count(*) from user_sec_jrn where uj_login=$1 '.
                                      ' and uj_jrn_id=$2',array($sec_User->login,$id));
        if ( $count == 0 )
        {
            $cn_dossier->exec_sql('insert into user_sec_jrn (uj_login,uj_jrn_id,uj_priv)'.
                                  ' values ($1,$2,$3)',
                                  array($sec_User->login,$id,$_POST[$priv]));

        }
        else
        {
            $cn_dossier->exec_sql('update user_sec_jrn set uj_priv=$1 where uj_login=$2 and uj_jrn_id=$3',
                                  array($_POST[$priv],$sec_User->login,$id));
        }
    }
    /* now save all the actions */
    $a=$cn_dossier->get_array('select ac_id from action');

    foreach ($a as $key)
    {
        $id=$key['ac_id'];
        $priv=sprintf("action%d",$id);
        $count=$cn_dossier->get_value('select count(*) from user_sec_act where ua_login=$1 '.
                                      ' and ua_act_id=$2',array($sec_User->login,$id));
        if ( $_POST[$priv] == 1 && $count == 0)
        {
            $cn_dossier->exec_sql('insert into user_sec_act (ua_login,ua_act_id)'.
                                  ' values ($1,$2)',
                                  array($sec_User->login,$id));

        }
        if ($_POST[$priv] == 0 )
        {
            $cn_dossier->exec_sql('delete from user_sec_act  where ua_login=$1 and ua_act_id=$2',
                                  array($sec_User->login,$id));
        }
    }

}




//--------------------------------------------------------------------------------
// Action == View detail for users
//--------------------------------------------------------------------------------

if ( $action == "view" )
{
    $l_Db=sprintf("dossier%d",$gDossier);

    $cn=new Database();
    $User=new User($cn,$_GET['user_id']);
    $admin=0;
    $access=$User->get_folder_access($gDossier);

    if ( $access == 'L')
    {
        $str='Local Admin';
        $admin=1;
    }
    elseif ($access=='R')
    {
        $str=' Utilisateur normal';
    }
    elseif ($access=='P')
    {
        $str=' Extension uniquement';
    }


    if ( $User->admin==1 )
    {
        $str=' Super Admin';
        $admin=1;
    }

    echo '<h2>'.h($User->first_name).' '.h($User->name).' '.hi($User->login)."($str)</h2>";


    if ( $admin != 0 )
    {
        echo '<h2 class="info"> Cet utilisateur est administrateur, il a tous les droits</h2>';
        exit();
    }
    //
    // Check if the user can access that folder
    if ( $access == 'X' )
    {
        echo "<H2 class=\"error\">L'utilisateur n'a pas accès à ce dossier</H2>";
        $action="";
        return;
    }

    //--------------------------------------------------------------------------------
    // Show access for journal
    //--------------------------------------------------------------------------------

    $Res=$cn_dossier->exec_sql("select jrn_def_id,jrn_def_name  from jrn_def ".
                               " order by jrn_def_name");
    $sec_User=new User($cn_dossier,$_GET['user_id']);

    echo '<form method="post">';
    $sHref=sprintf ('sec_pdf.php?p_action=sec&user_id=%s&'.$str_dossier ,
                    $_GET ['user_id']
                   );

    echo HtmlInput::button('Imprime','imprime',"onclick=\"window.open('".$sHref."');\"");
    echo HtmlInput::submit('ok','Sauve');
    echo HtmlInput::reset('Annule');
    echo HtmlInput::button_anchor('Retour à la liste','?p_action=sec&'.dossier::get(),'retour');

    echo dossier::hidden();
    echo HtmlInput::hidden('action','sec');
    echo HtmlInput::hidden('user_id',$_GET['user_id']);

    echo '<Fieldset><legend>Journaux </legend>';
    echo '<table align="CENTER" width="100%">';
    $MaxJrn=Database::num_row($Res);
    $jrn_priv=new ISelect();
    $array=array(
               array ('value'=>'R','label'=>'Uniquement lecture'),
               array ('value'=>'W','label'=>'Lecture et écriture'),
               //	       array ('value'=>'O','label'=>'Uniquement opérations prédéfinies'),
               array ('value'=>'X','label'=>'Aucun accès')
           );

    for ( $i =0 ; $i < $MaxJrn; $i++ )
    {
        /* set the widget */
        $l_line=Database::fetch_array($Res,$i);

        echo '<TR> ';
        if ( $i == 0 ) echo '<TD> <B> Journal </B> </TD>';
        else echo "<TD></TD>";
        echo "<TD> $l_line[jrn_def_name] </TD>";

        $jrn_priv->name='jrn_act'.$l_line['jrn_def_id'];
        $jrn_priv->value=$array;
        $jrn_priv->selected=$sec_User->get_ledger_access($l_line['jrn_def_id']);

        echo '<td>';
        echo $jrn_priv->input();
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
    echo '</fieldset>';

    //**********************************************************************
    // Show Priv. for actions
    //**********************************************************************
    echo '<fieldset> <legend>Actions </legend>';
    include('template/security_list_action.php');
    echo '</fieldset>';
    echo HtmlInput::button('Imprime','imprime',"onclick=\"window.open('".$sHref."');\"");
    echo HtmlInput::submit('ok','Sauve');
    echo HtmlInput::reset('Annule');
    echo '</form>';
} // end of the form
echo "</DIV>";
html_page_stop();
?>
