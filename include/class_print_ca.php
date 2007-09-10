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
 */

/* \brief this class is the mother class for the CA printing
 *        
 *
 */
require_once('postgres.php');
require_once('debug.php');
require_once('constant.php');
require_once ('class_widget.php');
require_once('class_dossier.php');

class print_ca {
  var $db;						/*!< $db database connection */
  var $to;						/*!< $to start date */
  var $from; 					/*!< $from end date */
  var $from_poste;				/*!< $from_poste from poste  */
  var $to_poste;				/*!< $to_poste to the poste */
  
  function print_ca($p_cn) {
	$this->db=$p_cn;
	$this->from="";
	$this->to="";
	$this->from_poste="";
	$this->to_poste="";

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
  function get_request() {
	if ( isset($_REQUEST['from']))
	  $this->from=$_REQUEST['from'];

	if ( isset($_REQUEST['to']))
	  $this->to=$_REQUEST['to'];

	if ( isset($_REQUEST['from_poste']))
	  $this->from_poste=$_REQUEST['from_poste'];

	if ( isset($_REQUEST['to_poste']))
	  $this->to_poste=$_REQUEST['to_poste'];
	if ( isset($_REQUEST['pa_id']))
	  $this->pa_id=$_REQUEST['pa_id'];


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
  function display_form($p_hidden="") {
	$from=new widget  ('text','from','from');
	$from->size=10;
	$from->value=$this->from;
	
	$to=new widget('text','to','to');
	$to->value=$this->to;
	$to->size=10;

	$from_poste=new widget  ('text','from_poste','from_poste');
	$from_poste->size=10;
	$from_poste->value=$this->from_poste;
	
	$to_poste=new widget('text','to_poste','to_poste');
	$to_poste->value=$this->to_poste;
	$to_poste->size=10;

	$hidden=new widget("hidden");	
	$r=dossier::hidden();
	$r.=$hidden->IOValue("result","1");
	$r.="Depuis : ".$from->IOValue();
	$r.= "jusque : ".$to->IOValue();
	$r.=$p_hidden;
	return $r;	
  }
}
