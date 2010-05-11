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
/* $Revision: 1992 $ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/*!\file 
 * \brief Fid for the ajax request for cards
 */


require_once  ("constant.php");
require_once('class_database.php');
require_once ("user_common.php");
require_once('class_dossier.php');
require_once('class_acc_report.php');
require_once('class_user.php');
if ( ! isset($_GET['gDossier']) ||
     ! isset($_GET['f']) )
  {
    $a='{"answer":"nok"}';
    header("Content-type: text/html; charset: utf8",true);
    print $a;
    exit();
  }  
$cn=new Database(dossier::id());

$User=new User($cn);
$User->Check();
$User->check_dossier(dossier::id());
$User->can_request('PARRAP',0);

  

$gDossier=dossier::id();
if ( ! is_dir('tmp') ) {
  mkdir ('tmp');
}

$cn=new Database($gDossier);
if ( isset($_SESSION['isValid']) && $_SESSION['isValid'] == 1)
{ 
  $rap=new Acc_Report($cn,$_GET['f']);

  $name=tempnam('tmp','report_').'.bin';

  $file= fopen($name,"a+");
  $rap->export($file);
  fclose ($file);
  $name='tmp'.DIRECTORY_SEPARATOR.basename($name);
  $name=dirname($_SERVER['REQUEST_URI']).DIRECTORY_SEPARATOR.$name;

  $a='{"answer":"ok","link":"'.$name.'"}';

}
     else
     $a='{"answer":"nok"}';
header("Content-type: text/html; charset: utf8",true);
print $a;
?>
