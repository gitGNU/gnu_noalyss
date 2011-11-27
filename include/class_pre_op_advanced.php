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
 * \brief definition of the class Pre_Op_Advanced
 */
require_once ('class_pre_operation.php');

/*---------------------------------------------------------------------- */
/*!\brief concerns the predefined operation for the operation from 'Ecriture direct'
 */
class Pre_Op_Advanced extends Pre_operation_detail
{
    var $op;
    function Pre_Op_Advanced($cn)
    {
        parent::__construct($cn);
        $this->operation->od_direct='t';
    }
    function get_post()
    {
        parent::get_post();

        extract($_POST);

        for ($i=0;$i<$this->operation->nb_item;$i++)
        {
            if ( ! isset ($_POST['poste'.$i]) && ! isset ($_POST['qc_'.$i]))
                continue;
            $this->{'poste'.$i}=(isset($_POST['qc_'.$i]))?$_POST['qc_'.$i]:$_POST['poste'.$i];
            $this->{'isqc'.$i}=(isset($_POST['qc_'.$i]))?'t':'f';
            $this->{"amount".$i}=$_POST['amount'.$i];
            $this->{"ck".$i}=(isset($_POST['ck'.$i]))?'t':'f';

        }
    }
    /*!
     * \brief save the detail and op in the database
     *
     */
    function save()
    {
        try
        {
            $this->db->start();
            if ($this->operation->save() == false )
                return;
            // save the selling
            for ($i=0;$i<$this->operation->nb_item;$i++)
            {
                if ( ! isset ($this->{"poste".$i}))
                    continue;

                $sql=sprintf('insert into op_predef_detail (opd_poste,opd_amount,'.
                             'opd_debit,od_id,opd_qc)'.
                             ' values '.
                             "('%s',%.2f,'%s',%d,'%s')",
                             $this->{"poste".$i},
                             $this->{"amount".$i},
                             $this->{"ck".$i},
                             $this->operation->od_id,
                             $this->{'isqc'.$i}
                            );

                $this->db->exec_sql($sql);

            }
        }
        catch (Exception $e)
        {
            echo ($e->getMessage());
            $this->db->rollback();
        }

    }
    /*!\brief compute an array accordingly with the FormVenView function
     */
    function compute_array()
    {
        $count=0;
        $a_op=$this->operation->load();
        $array=$this->operation->compute_array($a_op);
        $array['desc']=$array['e_comm'];
        $p_array=$this->load();
		if (empty($p_array)) return array();
        foreach ($p_array as $row)
        {
            $tmp_array=array("qc_".$count=>'',
                             "poste".$count=>'',
                             "amount".$count=>$row['opd_amount'],
                             'ck'.$count=>$row['opd_debit']
                            );

            if ( $row['opd_qc'] == 't' )
                $tmp_array['qc_'.$count]=$row['opd_poste'];
            else
                $tmp_array['poste'.$count]=$row['opd_poste'];


            if ( $row['opd_debit'] == 'f' )
                unset ($tmp_array['ck'.$count]);

            $array+=$tmp_array;
            $count++;

        }

        return $array;
    }
    /*!\brief load the data from the database and return an array
     * \return an array
     */
    function load()
    {
        $sql="select opd_id,opd_poste,opd_amount,opd_debit,".
             " opd_qc from op_predef_detail where od_id=".$this->operation->od_id.
             " order by opd_id";
        $res=$this->db->exec_sql($sql);
        $array=Database::fetch_all($res);
        return $array;
    }
    function set_od_id($p_id)
    {
        $this->operation->od_id=$p_id;
    }
}
