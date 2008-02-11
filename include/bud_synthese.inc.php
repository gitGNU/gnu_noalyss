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
 * \brief Manage the hypothese for the budget module
 */
$do=(isset($_REQUEST['do']))?$_REQUEST['do']:'xx';
$def=-1;
echo '<div class="u_subtmenu">';
switch($do) {
 case 'po':
   $def=1;
   break;
 case 'ga':
   $def=2;
   break;
 case 'vglobal':
   $def=3;
   break;
 case 'acc':
   $def=4;
   break;
   

 }

echo ShowItem(array(
		    array('?p_action=synthese&do=po&'.$str_dossier,'D&eacute;tail Poste Analytique',"Donne le detail par poste analytique ",1),
		    array('?p_action=synthese&do=ga&'.$str_dossier,' Groupe Analytique',"Synthese de l'utilisation des groupes analytiques dans une hypothese",2),
		    array('?p_action=synthese&do=vglobal&'.$str_dossier,'Vue Globale',"Detail d'une hypothese",3),
		    array('?p_action=synthese&do=acc&'.$str_dossier,'Detail CE',"Donne le d&eacute;tail par poste comptable",4)
		    ),
	      'H',"mtitle","mtitle",$def,' width="100%"');


		    //
echo '</div>';

if ( $do=='po') {
  if ($obj->size_analytic() == 0 ) {
    echo '<h2 class="info">Desole pas d\'hypothese definie avec un plan analytique</h2>';
    exit();

  }
  require_once ('bud_spo.inc.php');
}

if ( $do=='ga') {
  if ($obj->size_analytic() == 0 ) {
    echo '<h2 class="info">Desole pas d\'hypothese definie avec un plan analytique</h2>';
    exit();

  }

  require_once ('bud_sga.inc.php');
}
if ( $do=='vglobal')
  require_once ('bud_svglobal.inc.php');

if ( $do == 'acc')
  require_once ('bud_sacc.inc.php');
