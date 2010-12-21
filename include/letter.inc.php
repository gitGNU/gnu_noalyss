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
 * \brief file to include for the "lettrage" (lettering?)
 */

$array=array(
           array('?'.dossier::get().'&p_action=let&sa=poste',_('Par poste'),
                     _('Lettrage par poste'), 1),
               array('?'.dossier::get().'&p_action=let&sa=qc',_('Par fiche'),
                         _('Lettrage par fiche'), 2)
               );
$sa=(isset($_REQUEST['sa']))?$_REQUEST['sa']:0;
switch($sa)
{
case 'poste':
    $subdef=1;
    break;
case 'qc':
    $subdef=2;
    break;
}
echo '<div class="lmenu">';
echo ShowItem($array,'H','mtitle','mtitle',$subdef);
echo '</div>';
if ($sa == 'poste')
{
    require_once('lettering.account.inc.php');
    exit;
}

if ($sa=='qc')
{
    require_once('lettering.card.inc.php');
    exit;
}
