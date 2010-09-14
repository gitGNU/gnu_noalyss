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
 * \brief this class extends PDF and let you export the detailled printing
 *  of any ledgers
 */
require_once('class_pdf.php');

class Print_Ledger_Detail extends PDF
{
    public function __construct ($p_cn = null, $orientation = 'P', $unit = 'mm', $format = 'A4')
    {

        if($p_cn == null) die("No database connection. Abort.");

        parent::__construct($p_cn,'L', 'mm', 'A4');
        date_default_timezone_set ('Europe/Paris');

    }

    function setDossierInfo($dossier = "n/a")
    {
        $this->dossier = dossier::name()." ".$dossier;
    }

    function Header()
    {
        //Arial bold 12
        $this->SetFont('DejaVu', 'B', 12);
        //Title
        $this->Cell(0,10,$this->dossier, 'B', 0, 'C');
        //Line break
        $this->Ln(20);
    }
    function Footer()
    {
        //Position at 2 cm from bottom
        $this->SetY(-20);
        //Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        //Page number
        $this->Cell(0,8,'Date '.$this->date." - Page ".$this->PageNo().'/{nb}',0,0,'C');
        $this->Ln(3);
        // Created by PhpCompta
        $this->Cell(0,8,'Created by Phpcompta, a professional opensource accounting software http://www.phpcompta.eu',0,0,'C',false,'http://www.phpcompta.eu');
    }
    function Cell ($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $txt = str_replace("\\", "", $txt);
        return parent::Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);
    }

    function export($Jrn)
    {
        // detailled printing
        $rap_deb=0;
        $rap_cred=0;
        // take all operations from jrn
        $array=$Jrn->get_operation($_GET['from_periode'],$_GET['to_periode']);

        $this->SetFont('DejaVu','BI',7);
        $this->Cell(145,7,'report Débit',0,0,'R');
        $this->Cell(30,7,sprintf('%10.2f',$rap_deb),0,0,'R');
        $this->Ln(4);
        $this->Cell(145,7,'report Crédit',0,0,'R');
        $this->Cell(30,7,sprintf('%10.2f',$rap_cred),0,0,'R');
        $this->Ln(4);

        // print all operation
        for ($i=0;$i< count($array);$i++)
        {
            $this->SetFont('DejaVuCond','B',7);
            $row=$array[$i];

            $this->Cell(20,7,$row['pj']);
            $this->Cell(15,7,$row['date_fmt']);
            $this->Cell(20,7,$row['internal']);
            $this->Cell(100,7,$row['comment']);
            $this->Cell(20,7,$row['montant'],0,0,'R');

            $this->Ln(4);
            // get the entries
            $aEntry=$this->cn->get_array("select j_id,j_poste,j_qcode,j_montant,j_debit, ".
                                         " pcm_lib ".
                                         " from jrnx join tmp_pcmn on (j_poste=pcm_val) where j_grpt = $1".
                                         " order by j_debit,j_id",
                                         array($row['jr_grpt_id']));
            for ($j=0;$j<count($aEntry);$j++)
            {
                $this->SetFont('DejaVuCond','',7);
                $entry=$aEntry[$j];
                // $this->Cell(15,6,$entry['j_id'],0,0,'R');
                $this->Cell(32,6,$entry['j_qcode'],0,0,'R');
                $this->Cell(23,6,$entry['j_poste'],0,0,'R');

                // if j_qcode is not empty retrieve name
                if ( $entry['j_qcode'] != '')
                {
                    $f_id=$this->cn->get_value('select f_id from vw_poste_qcode where j_qcode=$1',array($entry['j_qcode']));
                    if ($f_id != '')
                        $name=$this->cn->get_value('select av_text from attr_value join jnt_fic_att_value using(jft_id) where f_id=$1 and ad_id=1',
                                                   array($f_id));
                    else
                        $name=$entry['pcm_lib'];
                }
                else
                    $name=$entry['pcm_lib'];
                $this->Cell(80,6,$name,0,0,'L');

                // print amount
                $str_amount=sprintf('%10.2f',$entry['j_montant']);
                if ( $entry['j_debit']=='t')
                {
                    $this->Cell(20,6,$str_amount,0,0,'R');
                    $this->Cell(20,6,'',0,0,'R');
                }
                else
                {
                    $this->Cell(20,6,'',0,0,'R');
                    $this->Cell(20,6,$str_amount,0,0,'R');
                }
                $this->Ln(4);
            }
        }
    }
}
