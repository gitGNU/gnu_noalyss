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
/*! \file 
 * \brief send a Bilan in RTF format
 */
include_once("ac_common.php");
include_once("impress_inc.php");
include_once("postgres.php");
require_once ('header_print.php');
require_once ('class_acc_bilan.php');

include ('class_user.php');
require_once('class_dossier.php');
$gDossier=dossier::id();

/* Admin. Dossier */
$cn=DbConnect($gDossier);

$User=new User($cn);
$User->Check();

// TODO a specific level of security for the "bilan" ???
// Change must be done here
if ( $User->admin == 0 ) {
  if ($User->check_action($cn,IMP) 
								  ==0
     )
  {
    /* Cannot Access */
    NoAccess();
  }

}

$bilan=new Acc_Bilan($cn);
$bilan->get_request_get();
$bilan->load();
/*\!bug the headers shouldn't be sent here, but it doesn't work
 * a html header is send before, to fix asap */
if ( $bilan->b_type=='odt')
  {
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Cache-Control: must-revalidate");
	header('Content-type: "application/vnd.oasis.opendocument.text"');
	header('Content-Disposition: attachment;filename="'.$bilan->b_name.'.odt"',FALSE);
	header("Accept-Ranges: bytes");

  }
if ( $bilan->b_type=='ods')
  {
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Cache-Control: must-revalidate");
	header('Content-type: "application/vnd.oasis.opendocument.spreadsheet"');
	header('Content-Disposition: attachment;filename="'.$bilan->b_name.'.ods"',FALSE);
	header("Accept-Ranges: bytes");

  }

$bilan->generate();
?>
