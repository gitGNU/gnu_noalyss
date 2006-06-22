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
 * \brief Class to manage the company parameter (address, name...)
 */
/*! 
 * \brief Class to manage the company parameter (address, name...)
 */

class Own {
  var $db;
  // constructor 
  function Own($p_cn) {
    $this->db=$p_cn;
    $Res=ExecSql($p_cn,"select * from parameter where pr_id like 'MY_%'");
    for ($i = 0;$i < pg_NumRows($Res);$i++) {
      $row=pg_fetch_array($Res,$i);
      $key=$row['pr_id'];
      $elt=$row['pr_value'];
      // store value here
      $this->{"$key"}=$elt;
    }

  }
/*! 
 **************************************************
 * \brief  Update a row
 *        
 *  
 * \param give the attribut name
 * 
 */
  function UpdateRow($p_attr) {
    $value=FormatString($this->{"$p_attr"});
    $Res=ExecSql($this->db,"update parameter set pr_value='$value' where pr_id='$p_attr'");
  }

/*! 
 **************************************************
 * \brief  save data
 *        
 *
 */
  function Save() {
    $this->UpdateRow('MY_NAME');
    $this->UpdateRow('MY_TVA');
    $this->UpdateRow('MY_STREET');
    $this->UpdateRow('MY_NUMBER');
    $this->UpdateRow('MY_CP');
    $this->UpdateRow('MY_COMMUNE');

  }

}