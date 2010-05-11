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
require_once("constant.php");
require_once('class_database.php');
require_once  ("class_user.php");
require_once ('ac_common.php');

$rep=new Database();
$User=new User($rep);
$User->Check();


if ($User->admin != 1) {
  echo "<script>alert('"."Vous n\'êtes pas administrateur"."') </script>";
  return;
}

/*!\file
 * \brief Make and restore backup
 */
if ( isset ($_REQUEST['sa']) ) {
  if ( defined ('PG_PATH') ) 
    putenv("PATH=".PG_PATH);
  

  if ( ! isset ($_REQUEST['d']) ||
       ! isset($_REQUEST['t']))
    {
      echo "Erreur : paramètre manquant "; 
      exit();
    }
      
  $sa=$_REQUEST['sa'];
  // backup
  if ( $sa=='b') {
    $cmd=escapeshellcmd (PG_DUMP);
    putenv("PGPASSWORD=".phpcompta_password);
    putenv("PGUSER=".phpcompta_user);
    
    if ( $_REQUEST['t'] == 'd' ) {
      $database=domaine."dossier".$_REQUEST['d'];
      $args= " -Fc -Z9 --no-owner -p ".phpcompta_psql_port." ".$database;
      header('Content-type: application/octet');
      header('Content-Disposition:attachment;filename="'.$database.'.bin"',FALSE);
      
      passthru ($cmd.$args,$a);

    }

  if ( $_REQUEST['t'] == 'm' ) {
      $database=domaine."mod".$_REQUEST['d'];
      $args= " -Fc -Z9 --no-owner -p ".phpcompta_psql_port." ".$database;
      header('Content-type: bin/x-application');
      header('Content-Disposition: attachment;filename="'.$database.'.bin"',FALSE);
      $a=passthru ($cmd.$args);
    }
  }
 }

