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
// Verify parameters
/*!\file
 * \brief show an attach of an operation
 */
include_once ("ac_common.php");
require_once('class_dossier.php');
$gDossier=dossier::id();

if ( !isset ($_GET['jrn'] ) ||
     !isset($_GET['jr_grpt_id'])) {
	echo_error("Missing parameters");
}

include_once ("postgres.php");


$jr_grpt_id=$_GET['jr_grpt_id'];

$cn=DbConnect($gDossier);


include_once ('class_user.php');
$User=new User(DbConnect());
$User->Check();
// retrieve the jrn
$r=ExecSql($cn,"select jr_def_id from jrn where jr_grpt_id=$jr_grpt_id");
if ( pg_num_rows($r) == 0 ) {
  echo_error("Invalid operation id jr_grpt_id=$jr_grpt_id");
  exit;
 }
$a=pg_fetch_array($r,0);
$jrn=$a['jr_def_id'];

if ($User->AccessJrn($cn,$jrn) == false ){
  /* Cannot Access */
  NoAccess();
  exit -1;
 }

StartSql($cn);
$ret=ExecSql($cn,"select jr_pj,jr_pj_name,jr_pj_type from jrn where jr_grpt_id=$jr_grpt_id");
if ( pg_num_rows ($ret) == 0 )
	return;
$row=pg_fetch_array($ret,0);
$tmp=tempnam($_ENV['TMP'],'document_');
pg_lo_export($cn,$row['jr_pj'],$tmp);
ini_set('zlib.output_compression','Off');
header("Pragma: public");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: must-revalidate");
header('Content-type: '.$row['jr_pj_type']);
header('Content-Disposition: attachment;filename="'.$row['jr_pj_name'].'"',FALSE);
header("Accept-Ranges: bytes");
$file=fopen($tmp,'r');
while ( !feof ($file) )
	echo fread($file,8192);

fclose($file);

unlink ($tmp);

Commit($cn);
