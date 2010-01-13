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
 * \brief ajax file called by the import screen
 */

/*! \brief update , delete or remove a record from the confirmed list
 *
 */
include_once ("constant.php");
require_once('class_database.php');
require_once ('class_user.php');
require_once ('class_dossier.php');



$cn=new Database(dossier::id());

$User=new User($cn);
$User->check_dossier(dossier::id());
$User->can_request('GEBQ');
$User->Check();
if ( isset ($_GET['action']) &&
     $_GET['action']=='update'
     )

  {
    $code=FormatString($_GET['code']);
    $count=FormatString($_GET['count']);
    $poste=FormatString($_GET['poste']);
    $concern=FormatString($_GET['concerned']);
    $sql = "update import_tmp set poste_comptable='$poste' ,status='w',".
      "jr_rapt='$concern' where code='$code'";
    
    $Res=$cn->exec_sql($sql);
    exit();
  }

if ( isset ($_GET['action']) &&
     $_GET['action']=='delete'
     )

  {
    $code=FormatString($_GET['code']);
    $sql="update import_tmp set status='d' where code='".$code."'";
    $cn->exec_sql($sql);
    exit();
  }
if ( isset ($_GET['action']) &&
     $_GET['action']=='not_confirmed'
     )

  {
    $code=FormatString($_GET['code']);
    $sql="update import_tmp set status='n' where code='".$code."'";
    $cn->exec_sql($sql);
    exit();
  }
