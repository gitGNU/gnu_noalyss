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

/*!\file
 * \brief show the history of a card of an accounting
 * for the card f_id is set and for an accounting : pcm_val
 */
require_once('class_database.php');
require_once('class_user.php');
require_once('class_dossier.php');
require_once('class_periode.php');
require_once('class_html_input.php');
require_once('class_acc_account.php');

$cn=new Database(dossier::id());
$user=new User($cn);
/* security */
if ( $user->check_dossier(dossier::id(),true) == 'X' ) exit();

$div=$_REQUEST['div'];

///////////////////////////////////////////////////////////////////////////
/* first detail for a card */
///////////////////////////////////////////////////////////////////////////
if ( isset($_GET['f_id']))
{
    $fiche=new Fiche($cn,$_GET['f_id']);
    $year=$user->get_exercice();
    if ( $year == 0 )
    {
        $html="erreur aucune période par défaut, allez dans préférence pour en choisir une";
    }
    else
    {
        $per=new Periode($cn);
        $limit_periode=$per->get_limit($year);
        $array['from_periode']=$limit_periode[0]->first_day();
        $array['to_periode']=$limit_periode[1]->last_day();

        ob_start();
        require_once('template/history_top.php');
	if (   $fiche->HtmlTable($array)==-1){
	  echo h2info("Aucun opération pour l'exercice courant");
	}
        $html=ob_get_contents();
        ob_clean();
    }
}
///////////////////////////////////////////////////////////////////////////
// for an account
///////////////////////////////////////////////////////////////////////////
if ( isset($_REQUEST['pcm_val']))
{
    $poste=new Acc_Account_Ledger($cn,$_REQUEST['pcm_val']);
    $year=$user->get_exercice();
    if ( $year == 0 )
    {
        $html="erreur aucune période par défaut, allez dans préférence pour en choisir une";
    }
    else
    {
        $per=new Periode($cn);
        $limit_periode=$per->get_limit($year);
        $array['from_periode']=$limit_periode[0]->first_day();
        $array['to_periode']=$limit_periode[1]->last_day();

        ob_start();
        require_once('template/history_top.php');
        if ( $poste->HtmlTable($array) == -1)
	  {
	    echo h2info("Aucun opération pour l'exercice courant");
	  }
        $html=ob_get_contents();
        ob_clean();
    }
}
$html=escape_xml($html);
header('Content-type: text/xml; charset=UTF-8');
echo <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<data>
<ctl>$div</ctl>
<code>$html</code>
</data>
EOF;
