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
 * \brief  class for the table document_type
 */

/*! \brief class for the table document_type
 * \enum dt_id pk document_type
 *  \enum dt_value value
 */
class Document_type
{
  /*! document_type
 * \brief constructor
 * \param $p_cn database connx
 */
  function document_type($p_cn)
    {
      $this->db=$p_cn;
      $this->dt_id=-1;
    }
/*!
 * \brief Get all the data for this dt_id
 */
  function get()
    {
      $sql="select * from document_type where dt_id=".$this->dt_id;
      $R=ExecSql($this->db,$sql);
      $r=pg_fetch_array($R,0);
      $this->dt_id=$r['dt_id'];
      $this->dt_value=$r['dt_value'];
    }

}