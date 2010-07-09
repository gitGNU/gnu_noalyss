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
 * \brief Manage the attribut of the card
 */
/*! 
 * \brief Manage the attribut of the card
 */

class Attribut {
  var $ad_id;			/*!<  pk for attribut */
  var $ad_text;			/*!<  type of content */
  var $av_text;			/*!< Description (content) */
  var $jnt_order;		/*!< order of the attribut */
  var $cn;			/*!<  database connection */
  function Attribut($p_ad_id) {
    $this->ad_id=$p_ad_id;
    $this->ad_type=0;
    $this->ad_size=0;

    $this->cn=-1;
  }



/*! 
 *@brief used with a usort function, to sort an array of Attribut on the attribut_id (ad_id)
 */
  static function sort_by_id($o1,$o2) {  
    if ( $o1->ad_id > $o2->ad_id ) return 1;
    if ( $o1->ad_id == $o2->ad_id ) return 0;
    return -1;
  }
}
