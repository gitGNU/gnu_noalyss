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
/*! \file
 * \brief Manage the attribut of the card
 */
/*! 
 * \brief Manage the attribut of the card
 */

class Attribut {
  var $ad_id;			/*! \enum  pk for attribut */
  var $ad_text;			/*! \enum  type of content */
  var $av_text;			/*! \enum Description (content) */
  var $cn;			/*! \enum  database connection */
  function Attribut($p_ad_id) {
    $this->ad_id=$p_ad_id;
    $this->cn=-1;
  }

}

/*! 
 **************************************************
 * \brief  Sort an array of object attribut thx the
 *        ad_id
 * 
 *\param  array of Attribut objects
 * 
 *	
 * \return the ordered array
 */
function SortAttributeById($p_attribut) {
  for ($i=0;$i< sizeof($p_attribut)-1;$i++) {
      if ( $p_attribut[$i]->ad_id > $p_attribut[$i+1]->ad_id ) {
	// then swap them
	$t=$p_attribut[$i];
	$p_attribut[$i]=$p_attribut[$i+1];
	$p_attribut[$i+1] = $t;
	$i=0;
      }
    }
  
  return $p_attribut;
}
