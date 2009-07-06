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
 * \brief this file respond to an ajax request and return an object with the ctl and the html string
 * {'ctl':'','html':''}
 * The parameters are
 * - PHPSESSID
 * - gDossier
 * - f_id 
 * - op action to take this action can be 
      - dc Detail of the account
      - op Account history
      - mf Financial move
      - hc history of mail, all the contact : phone call, mail, offer...
      - lc list of the contacts
 * - p page 
 * - ctl (to return)
 */
$var=array('PHPSESSID','gDossier','f_id','op','p','ctl');
$cont=0;
foreach ($var as $v) {
  if ( ! isset ($_GET[$v] ) ) {
    echo "$v is not set ";
    $cont=1;
  }
}
if ( $cont != 0 ) exit();
extract($_GET);

switch($op) {
case "op":
  $html="Vous demandez Historique Operation ";

}
sleep(5);
header("Content-type: text/html; charset: utf8",true);
echo "{'ctl':'".$_GET['ctl']."','html':'".$html."'}";
