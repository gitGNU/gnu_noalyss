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
 * \brief the class for the dossier, everywhere we need to know to
 * which folder we are connected, because we can't use $_SESSION, we
 * need to pass the dossier_id via a _GET or a POST variable
 */

/*! \brief manage the current dossier, everywhere we need to know to
 * which folder we are connected, because we can't use $_SESSION, we
 * need to pass the dossier_id via a _GET or a POST variable
 *
 */
require_once('postgres.php');
include_once('debug.php');
require_once('ac_common.php');

class dossier {

  /*!\brief return the $_REQUEST['gDossier'] after a check */
  static function id() {
	echo_debug(__FILE__,__LINE__,"id");
	echo_debug(__FILE__,__LINE__,$_REQUEST);
	self::check();
	return $_REQUEST['gDossier'];
  }
  /*!\brief check if gDossier is set */
  static function check() {
	if ( ! isset ($_REQUEST['gDossier']) ){
	  echo_error ('Dossier inconnu ');
	  exit('Dossier invalide');
	}
			  
  }
  /*!\brief return a string to put to gDossier into a GET */
  static function get() {
	echo_debug(__FILE__,__LINE__,"get");
	echo_debug(__FILE__,__LINE__,$_REQUEST);
	self::check();
    return "gDossier=".$_REQUEST['gDossier'];

  }

  /*!\brief return a string to set gDossier into a FORM */
  static function hidden() {
	echo_debug(__FILE__,__LINE__,"hidden");
	echo_debug(__FILE__,__LINE__,$_REQUEST);
	self::check();
	return '<input type="hidden" id="gDossier" name="gDossier" value="'.$_REQUEST['gDossier'].'">';
  }
  /*!\brief retrieve the name of the current dossier */
  static function name() {
	echo_debug(__FILE__,__LINE__,"get_name");
	echo_debug(__FILE__,__LINE__,$_REQUEST);
	self::check();

	$cn=DbConnect();
	$name=getDbValue($cn,"select dos_name from ac_dossier where dos_id=".$_REQUEST['gDossier']);
	return $name;
  }
}
