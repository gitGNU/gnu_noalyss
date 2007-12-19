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

/* !\file 
 * \brief this class is the mother class for the CA balance 
 */

/* \brief this class is the mother class for the CA balance 
 *        
 *
 */
require_once('postgres.php');
require_once('debug.php');
require_once('constant.php');


class print_ca {
  var $db;						/*!< $db database connection */
  var $to;						/*!< $to start date */
  var $from; 					/*!< $from end date */
  var $from_poste;				/*!< $from_poste from poste  */
  var $to_poste;				/*!< $to_poste to the poste */
  
  function balance($p_cn) {
	$this->db=$p_cn;
  }
/*! 
 * \brief fill a object with request
 */
  function get_request() {
	$this->from=(isset($_REQUEST['from']))?$_REQUEST['from']:"";
	$this->to=(isset($_REQUEST['to']))?$_REQUEST['to']:"";
	$this->from_poste=(isset($_REQUEST['from_poste']))?$_REQUEST['from_poste']:"";
	$this->to_poste=(isset($_REQUEST['to_poste']))?$_REQUEST['to_poste']:"";

  }
/*! 
 * \brief
 * \param
 * \param
 * \param
 * 
 *
 * \return
 */
  function display_form() {

  }

}
