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
 *< dt_id pk document_type
 *< dt_value value
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
        $sql="select * from document_type where dt_id=$1";
        $R=$this->db->exec_sql($sql,array($this->dt_id));
        $r=Database::fetch_array($R,0);
        $this->dt_id=$r['dt_id'];
        $this->dt_value=$r['dt_value'];
    }
    /**
     *@brief get a list
     *@parameter $p_cn database connection
     *@return array of data from document_type
     */
    static function get_list($p_cn)
    {
        $sql="select * from document_type order by dt_value";
        $r=$p_cn->get_array($sql);
        $array=array();
        for ($i=0;$i<count($r);$i++)
        {
            $tmp['dt_value']=$r[$i]['dt_value'];
            $tmp['dt_prefix']=$r[$i]['dt_prefix'];

            $bt=new IButton('X'.$r[$i]['dt_id']);
            $bt->label=_('Effacer');
            $bt->javascript="if (confirm('"._('Vous confirmez')."')==true) {";
            $bt->javascript.="cat_doc_remove('".$r[$i]['dt_id']."','".Dossier::id()."');";
            $bt->javascript.='}';

            $tmp['js_remove']=$bt->input();
            $tmp['dt_id']=$r[$i]['dt_id'];
            $array[$i]=$tmp;
        }
        return $array;
    }
    function insert($p_value,$p_prefix)
    {
        $sql="insert into document_type(dt_value,dt_prefix) values ($1,$2)";
        try
        {
            if( $this->db->count_sql('select * from document_type where upper(dt_value)=upper(trim($1))',array($p_value))>0)
                throw new Exception('Nom en double');
            if ( strlen(trim($p_value))>0)
                $this->db->exec_sql($sql,array($p_value,$p_prefix));
        }
        catch (Exception $e)
        {
            alert(j(_("Impossible d'ajouter [$p_value] ").$e->getMessage()));
        }
    }
}
