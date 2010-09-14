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
 * \brief definition of Anc_Listing
 */

require_once("class_ihidden.php");
require_once ('class_anc_plan.php');
require_once ('class_anc_print.php');
require_once ('class_anc_operation.php');
/*!
 * \brief manage the CA listing
 *
 * \return
 */

class Anc_Listing extends Anc_Print
{
    function display_form($p_string="")
    {
        echo '<form method="get">';
        $r=parent::display_form($p_string);
        $r.=HtmlInput::submit('result','Afficher');
        $r.= '</form>';
        return $r;

    }
    /*!
     * \brief complete the object with the data in $_REQUEST
     */

    function get_request()
    {
        parent::get_request();
        $this->pa_id=(isset($_REQUEST['pa_id']))?$_REQUEST['pa_id']:"";
    }
    /*!
     * \brief compute the html display
     *
     *
     * \return string
     */

    function display_html()
    {
        $r="";
        //---Html
        $array=$this->load();
        if ( is_array($array) == false )
        {
            return $array;

        }

        if ( empty($array) )
        {
            $r.= _("aucune donnée");
            return $r;
        }
        $r.= '<table>';
        $r.= '<tr>'.
             '<th>'._('Date').'</th>'.
             '<th>'._('Nom').'</th>'.
             '<th>'._('Description').'</th>'.
             '<th>'._('Montant').'</th>'.
             '<th>'._('D/C').'</th>'.
             '</tr>';
        foreach ( $array as $row )
        {
            $r.= '<tr>';
            $r.=
                '<td>'.$row['oa_date'].'</td>'.
                '<td>'.h($row['po_name']).'</td>'.
                '<td>'.h($row['oa_description']).'</td>'.
                '<td>'.$row['oa_amount'].'</td>'.
                '<td>'.(($row['oa_debit']=='f')?'CREDIT':'DEBIT').'</td>';
            $r.= '</tr>';
        }
        $r.= '</table>';
        return $r;
    }
    /*!
     * \brief load the data from the database
     *
     * \return array
     */
    function load()
    {
        $op=new Anc_Operation ($this->db);
        $op->pa_id=$this->pa_id;
        $array=$op->get_list($this->from,$this->to,$this->from_poste,$this->to_poste);
        return $array;
    }
    /*!
     * \brief Compute the csv export
     * \return string with the csv
     */

    function display_csv()
    {
        $array=$this->load($this->from,$this->to,$this->from_poste,$this->to_poste);
        if ( empty($array) == true )
        {
            return $array;

        }
        $r="";
        foreach ( $array as $row)
        {
            // the name and po_id
            $r.=sprintf("'%s',",$row['oa_date']);
            $r.=sprintf("'%s',",$row['po_name']);
            $r.=sprintf("'%s',",$row['oa_description']);
            $r.=sprintf("%12.2f,",$row['oa_amount']);
            $r.=sprintf("'%s'",(($row['oa_debit']=='f')?'CREDIT':'DEBIT'));
            $r.="\r\n";
        }
        return $r;

    }

    /*!
     * \brief show the export button to pdf and CSV
     * \param $p_string string containing some HTML tag as hidden field
     * \param
     * \param
     *
     *
     * \return string containing the html code
     */
    function show_button($p_string='')
    {
        $r="";
        $submit=HtmlInput::submit('','');
        $hidden=new IHidden();
        /* for the export in PDF
         * Not yet needed, the html print should be enough
        $r.= '<form method="GET" action="ca_list_pdf.php" style="display:inline">';
        $r.= $p_string;
        $r.= dossier::hidden();
        $r.= $hidden->input("to",$this->to);
        $r.= $hidden->input("from",$this->from);
        $r.= $hidden->input("pa_id",$this->pa_id);
        $r.= $hidden->input("from_poste",$this->from_poste);
        $r.= $hidden->input("to_poste",$this->to_poste);
        $r.=HtmlInput::submit('bt_pdf',"Export en PDF");
        $r.= '</form>';
        */

        $r.= '<form method="GET" action="ca_list_csv.php"  style="display:inline">';
        $r.= HtmlInput::hidden("to",$this->to);
        $r.= HtmlInput::hidden("from",$this->from);
        $r.= HtmlInput::hidden("pa_id",$this->pa_id);
        $r.= HtmlInput::hidden("from_poste",$this->from_poste);
        $r.= HtmlInput::hidden("to_poste",$this->to_poste);

        $r.= $p_string;
        $r.= dossier::hidden();
        $r.=HtmlInput::submit('bt_csv',"Export en CSV");
        $r.= '</form>';
        return $r;

    }
    /*!
     * \brief debugging and test function for dev. only
     * \param
     * \param
     * \param
     *
     *
     * \return
     */
    static  function test_me()
    {
    }
}
