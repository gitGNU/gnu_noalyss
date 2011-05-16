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
 * \brief link between accountancy and analytic, like table but as a listing
 */
require_once('class_anc_print.php');

class Anc_Acc_Link extends Anc_Print
{
  function __contruct($p_cn)
  {
    $this->cn=$p_cn;
  }

  function check()
  {
    
    /*
     * check date
     */
    if (($this->from != '' && isDate ($this->from) == 0)
	|| 
	($this->to != '' && isDate ($this->to) == 0))
      return -1;

    return 0;
  }  
  /**
   *@brief get the parameters
   */
  function get_request()
  {
    parent::get_request();
    $this->card_poste=HtmlInput::default_value('card_poste',1,$_GET);
  }
    function set_sql_filter()
    {
        $sql="";
        $and=" and ";
        if ( $this->from != "" )
        {
            $sql.="$and oa_date >= to_date('".$this->from."','DD.MM.YYYY')";
        }
        if ( $this->to != "" )
        {
            $sql.=" $and oa_date <= to_date('".$this->to."','DD.MM.YYYY')";
        }

        return $sql;

    }


}

