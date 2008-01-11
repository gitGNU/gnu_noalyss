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
include_once ("ac_common.php");
/*! \file
 * \brief Login page
 */
/* $Revision$ */

include_once ("postgres.php");
include_once("debug.php");


if (  isset ($_POST["p_user"] ) ) {
  echo_debug('login.php',__LINE__,"user is set");
  $g_user=FormatString($_POST["p_user"]);
  $g_pass=$_POST["p_pass"];
  $_SESSION['g_user']=$g_user; 
  $_SESSION['g_pass']=$g_pass;

  // Verif if User and Pass match DB
  // if no, then redirect to the login page
  $rep=DbConnect();
  include_once ("class_user.php");
  $User=new User($rep);
  $User->Check();

  echo "<META HTTP-EQUIV=\"REFRESH\" content=\"0;url=user_login.php?PHPSESSID=" . $_REQUEST["PHPSESSID"] . "\">";

} else
{
  $rep=DbConnect();
  include_once ("class_user.php");
  $User=new User($rep);
  $User->Check();
  
  echo "<META HTTP-EQUIV=\"REFRESH\" content=\"0;url=user_login.php?PHPSESSID=" . $_REQUEST["PHPSESSID"] . "\">";  
    // }
}
html_page_stop();
?>
