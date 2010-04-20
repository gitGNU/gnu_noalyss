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
 * \brief concerns the centralisation of the operations
 */
include_once ("ac_common.php");
require_once('class_iperiod.php');

html_page_start($_SESSION['g_theme']);

require_once('class_dossier.php');
$gDossier=dossier::id();

require_once('class_database.php');
/* Admin. Dossier */
$rep=new Database(dossier::id());
include_once ("class_user.php");
$User=new User($rep);
$User->Check();

$cn=new Database($gDossier);

include_once("central_inc.php");


$User->can_request(PARCENT,1);



echo '<DIV CLASS="u_redcontent">';
echo '<H2 CLASS="info"> Centralise </H2><BR>';
if ( isset ($_POST["central"] )) {

  //demande centralise
if ( $_POST["period"] != "" ) {
    $ret=Centralise($cn,$_POST["period"]);
    if ($ret==NOERROR) {
      echo '<H2 class="info">La p&eacute;riode '.$_POST["period"].' est centralis&eacute;e</H2>';
    } else {
      echo '<H2 class="error">La p&eacute;riode '.$_POST["period"].' n\' a pu être centralis&eacute;e</H2>';
    }
  } 
}// if ( isset ($_POST["central"] ))
$period=new IPeriod("period");
$period->user=$User;
$period->cn=$cn;
$period->value=0;
$period->type=NOTCENTRALIZED;
try {
$ret=$period->input();
} catch (Exception $e) {
	if ( $e->getCode() != 0 ) {
		echo $e->getMessage();
		exit;
	} else	{
		echo $e->getTrace();
		exit;
	}
}
if ( $ret != null) {
  echo '<FORM METHOD="POST">';
  echo HtmlInput::hidden('p_action','central');
  echo dossier::hidden();
  echo $ret;
  echo HtmlInput::submit('central','Centralise');
  echo '</FORM>';
} else {
  echo '<H2 class="info"> Aucune période à centraliser</H2>';
}

echo "</DIV>";
html_page_stop();
?>
