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
 * $Revision$
*/
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/*! \file
 * \brief Administration of the repository : creation of user, folder, security, 
 *        templates... Accessible only by the administrator
 */
require_once("user_common.php");
include_once("ac_common.php");
include_once("postgres.php");
include_once("debug.php");
include_once("user_menu.php");
$rep=DbConnect();
include_once ("class_user.php");
$User=new User($rep);
$User->Check();


html_page_start($User->theme);
echo_debug('admin_repo.php',__LINE__,"entering admin_repo");

if ($User->admin != 1) {
  html_page_stop();
  return;
}

echo '<H2 class="info"> Administration Globale</H2>';
echo "<div>".MenuAdmin()."</div>";




?>
<DIV >
<?php
if ( isset ($_REQUEST["action"]) ) {
  if ( $_REQUEST["action"]=="user_mgt" ) 
    {
      //----------------------------------------------------------------------
      // User management
      //----------------------------------------------------------------------
      require_once("user.inc.php");
    }
  // action=user_mgt
  if ( $_REQUEST["action"]=="dossier_mgt") 
    {
      //-----------------------------------------------------------------------
      // action = dossier_mgt
      //-----------------------------------------------------------------------
      require_once("dossier.inc.php");
    } 
  if ( $_REQUEST["action"] == "modele_mgt" ) 
    {
      //-----------------------------------------------------------------------
      //  Template Management
      //-----------------------------------------------------------------------
      require_once("modele.inc.php");
    } // action is set
}// action = modele_mgt

?>
</DIV>
<?php

html_page_stop();
?>
