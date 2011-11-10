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
/*! \file
 * \brief send the document template
 */

require_once('class_database.php');
require_once("ac_common.php");

require_once('class_dossier.php');
$gDossier=dossier::id();
$cn=new Database($gDossier);


require_once ('class_user.php');
$User=new User(new Database());
/*!\todo Add security here
 */
$User->Check();
// retrieve the document
$r=$cn->exec_sql("select md_id,md_lob,md_filename,md_mimetype
                 from document_modele where md_id=$1",array($_REQUEST['md_id']));
if ( Database::num_row($r) == 0 )
{
    echo_error("Invalid Document");
    exit;
}
$row=Database::fetch_array($r,0);


$cn->start();

$tmp=tempnam($_ENV['TMP'],'document_');
$cn->lo_export($row['md_lob'],$tmp);
ini_set('zlib.output_compression','Off');
header("Pragma: public");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: must-revalidate");
header('Content-type: '.$row['md_mimetype']);
header('Content-Disposition: attachment;filename="'.$row['md_filename'].'"',FALSE);
header("Accept-Ranges: bytes");
$file=fopen($tmp,'r');
while ( !feof ($file) )
    echo fread($file,8192);

fclose($file);

unlink ($tmp);

$cn->commit();
