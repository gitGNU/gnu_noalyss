<?
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
include_once ("ac_common.php");
if ( !isset ($_GET['jrn'] ) ||
     !isset($_GET['jr_grpt_id'])) {
	echo_error("Missing parameters");
}

include_once ("postgres.php");

$jrn=$_GET['jrn'] ;
$jr_grpt_id=$_GET['jr_grpt_id'];

$cn=DbConnect($_SESSION['g_dossier']);


include ('class_user.php');
$User=new cl_user($cn);
$User->Check();

if ( $User->admin == 0 ) {
  if (CheckJrn($_SESSION['g_dossier'],$_SESSION['g_user'],$jrn) == 0 ){
            /* Cannot Access */
            NoAccess();
            exit -1;
          }
}

StartSql($cn);
$ret=ExecSql($cn,"select jr_pj,jr_pj_name,jr_pj_type from jrn where jr_grpt_id=$jr_grpt_id");
if ( pg_num_rows ($ret) == 0 )
	return;
$row=pg_fetch_array($ret,0);
$tmp=tempnam('/tmp/','document_');
pg_lo_export($cn,$row['jr_pj'],$tmp);
header('Content-type: '.$row['jr_pj_type']);
header('Content-Disposition: attachment;filename="'.$row['jr_pj_name'].'"',FALSE);
$file=fopen($tmp,'r');
while ( !feof ($file) )
	echo fread($file,8192);

fclose($file);

unlink ($tmp);

Commit($cn);
