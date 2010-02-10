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
 * \brief this file is included from parameters and its purpose is to
 * enable, disable and manage security for the extensions
 *
 * variable :
 * - p_action is ext
 * - sb is nothing vw (view) or save
 * - $cn database connection
 *@todo Extension : ne doit faire de location.reload mais utiliser javascript pour metter
 * à jour la table présentant les extensions : delete -> enlève une ligne, add / update -> mettre
 *  la ligne à jour. 
 * Le bouton delete doit être déplacé dans le form AJAX
 */

require_once('class_extension.php');
require_once('class_html_input.php');
require_once('class_ibutton.php');
require_once('class_ipopup.php');
echo js_include('extension.js');

$ipopup=new IPopup('dtext');
$ipopup->value='';
$ipopup->title=_('Détail extension');
echo HtmlInput::hidden('popup','dtext').dossier::hidden().HtmlInput::phpsessid();
echo $ipopup->input();

echo '<div class="content">';

$ext=Extension::listing($cn);
$new=new IButton('new');
$new->label=_('Nouvelle extension');
$new->javascript="new_extension()";
$str_new=$new->input();
require_once('template/extension.php');


echo '</div>';
