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

class Print_Ledger_Simple extends PDF
{
    public function __construct ($p_cn,$p_jrn)
    {

        if($p_cn == null) die("No database connection. Abort.");

        parent::__construct($p_cn,'L', 'mm', 'A4');
        $this->ledger=$p_jrn;
        $this->a_Tva=$this->ledger->existing_vat();
        foreach($this->a_Tva as $line_tva)
        {
            //initialize Amount TVA
            $tmp1=$line_tva['tva_id'];
            $this->rap_tva[$tmp1]=0;
        }
        $this->jrn_type=$p_jrn->get_type();
        //----------------------------------------------------------------------
        /* report
         *
         * get rappel to initialize amount rap_xx
         *the easiest way is to compute sum from quant_
         */
        $this->previous=$this->ledger->previous_amount($_GET['from_periode']);

        /* initialize the amount to report */
        foreach($this->previous['tva'] as $line_tva)
        {
            //initialize Amount TVA
            $tmp1=$line_tva['tva_id'];
            $this->rap_tva[$tmp1]=$line_tva['sum_vat'];
        }

        $this->rap_htva=$this->previous['price'];
        $this->rap_tvac=$this->previous['price']+$this->previous['vat'];
        $this->rap_priv=$this->previous['priv'];
        $this->rap_nd=$this->previous['tva_nd_recup'];
    }

    function setDossierInfo($dossier = "n/a")
    {
        $this->dossier = dossier::name()." ".$dossier;
    }
    /**
     *@brief write the header of each page
     */
    function Header()
    {
        //Arial bold 12
        $this->SetFont('DejaVu', 'B', 12);
        //Title
        $this->Cell(0,10,$this->dossier, 'B', 0, 'C');
        //Line break
        $this->Ln(20);
        $this->SetFont('DejaVu', 'B', 8);
        /* column header */
        //----------------------------------------------------------------------
        // Show column header, if $flag_tva is false then display vat as column
        foreach($this->a_Tva as $line_tva)
        {
            //initialize Amount TVA
            $tmp1=$line_tva['tva_id'];
            $this->rap_tva[$tmp1]=(isset($this->rap_tva[$tmp1]))?$this->rap_tva[$tmp1]:0;
        }
        $this->Cell(15,6,'Pièce');
        $this->Cell(10,6,'Date');
        $this->Cell(20,6,'ref');
        if ( $this->jrn_type=='ACH')
            $this->Cell(60,6,'Client');
        else
            $this->Cell(60,6,'Fournisseur');

        $flag_tva=(count($this->a_Tva) > 4)?true:false;
        if ( !$flag_tva )      $this->Cell(65,6,'Description');

        $this->Cell(15,6,'HTVA',0,0,'R');
        if ( $this->jrn_type=='ACH')
        {
            $this->Cell(15,6,'Privé',0,0,'R');
            $this->Cell(15,6,'TVA ND',0,0,'R');
        }
        foreach($this->a_Tva as $line_tva)
        {
            $this->Cell(15,6,$line_tva['tva_label'],0,0,'R');
        }
        $this->Cell(15,6,'TVAC',0,0,'R');
        $this->Ln(5);

        $this->SetFont('DejaVu','',6);
        // page Header
        $this->Cell(170,6,'report',0,0,'R');
        $this->Cell(15,6,sprintf('%.2f',$this->rap_htva),0,0,'R'); /* HTVA */
        if ( $this->jrn_type != 'VEN')
        {
            $this->Cell(15,6,sprintf('%.2f',$this->rap_priv),0,0,'R');  /* prive */
            $this->Cell(15,6,sprintf('%.2f',$this->rap_nd),0,0,'R');  /* Tva ND */
        }
        foreach($this->rap_tva as $line_tva)
        $this->Cell(15,6,sprintf('%.2f',$line_tva),0,0,'R');
        $this->Cell(15,6,sprintf('%.2f',$this->rap_tvac),0,0,'R'); /* Tvac */

        $this->Ln(6);
        //total page
        $this->tp_htva=0.0;
        $this->tp_tvac=0.0;
        $this->tp_priv=0;
        $this->tp_nd=0;
        foreach($this->a_Tva as $line_tva)
        {
            //initialize Amount TVA
            $tmp1=$line_tva['tva_id'];
            $this->tp_tva[$tmp1]=0.0;
        }
    }
    /**
     *@brief write the Footer
     */
    function Footer()
    {
        //Position at 3 cm from bottom
        $this->SetY(-20);
        /* write reporting  */
        $this->Cell(170,6,'Total page ','T',0,'R'); /* HTVA */
        $this->Cell(15,6,sprintf('%.2f',$this->tp_htva),'T',0,'R'); /* HTVA */
        if ( $this->jrn_type !='VEN')
        {
            $this->Cell(15,6,sprintf('%.2f',$this->tp_priv),'T',0,'R');  /* prive */
            $this->Cell(15,6,sprintf('%.2f',$this->tp_nd),'T',0,'R');  /* Tva ND */
        }
        foreach($this->a_Tva as $line_tva)
        {
            $l=$line_tva['tva_id'];
            $this->Cell(15,6,sprintf('%.2f',$this->tp_tva[$l]),'T',0,'R');
        }
        $this->Cell(15,6,sprintf('%.2f',$this->tp_tvac),'T',0,'R'); /* Tvac */
        $this->Ln(2);

        $this->Cell(170,6,'report',0,0,'R'); /* HTVA */
        $this->Cell(15,6,sprintf('%.2f',$this->rap_htva),0,0,'R'); /* HTVA */
        if ( $this->jrn_type !='VEN')
        {
            $this->Cell(15,6,sprintf('%.2f',$this->rap_priv),0,0,'R');  /* prive */
            $this->Cell(15,6,sprintf('%.2f',$this->rap_nd),0,0,'R');  /* Tva ND */
        }
        foreach($this->a_Tva as $line_tva)
        {
            $l=$line_tva['tva_id'];
            $this->Cell(15,6,sprintf('%.2f',$this->rap_tva[$l]),0,0,'R');
        }
        $this->Cell(15,6,sprintf('%.2f',$this->rap_tvac),0,0,'R'); /* Tvac */
        $this->Ln(2);

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
    /**
     *@brief export the ledger in  PDF
     */
    function export()
    {

        $a_jrn=$this->ledger->get_operation($_GET['from_periode'],
                                            $_GET['to_periode']);

        if ( $a_jrn == null ) return;
        for ( $i=0;$i<count($a_jrn);$i++)
        {
            /* initialize tva */
            for ($f=0;$f<count($this->a_Tva);$f++)
            {
                $l=$this->a_Tva[$f]['tva_id'];
                $atva_amount[$l]=0;
            }

            // retrieve info from ledger
            $aAmountVat=$this->ledger->vat_operation($a_jrn[$i]['jr_grpt_id']);

            // put vat into array
            for ($f=0;$f<count($aAmountVat);$f++)
            {
                $l=$aAmountVat[$f]['tva_id'];
                $atva_amount[$l]=$aAmountVat[$f]['sum_vat'];
                $this->tp_tva[$l]+=$aAmountVat[$f]['sum_vat'];
                $this->rap_tva[$l]+=$aAmountVat[$f]['sum_vat'];
            }

            $row=$a_jrn[$i];
            $this->Cell(15,5,($row['pj']),0,0);
            $this->Cell(10,5,$row['date_fmt'],0,0);
            $this->Cell(20,5,$row['internal'],0,0);
            list($qc,$name)=$this->get_tiers($row['id'],$this->jrn_type);
            $this->Cell(20,5,$qc,0,0);
            $this->Cell(40,5,$name,0,0);

            $this->Cell(65,5,substr($row['comment'],0,47),0,0);

            /* get other amount (without vat, total vat included, private, ND */
            $other=$this->ledger->get_other_amount($a_jrn[$i]['jr_grpt_id']);
            $this->tp_htva+=$other['price'];
            $this->tp_tvac+=$other['price']+$other['vat'];
            $this->tp_priv+=$other['priv'];
            $this->tp_nd+=$other['priv'];
            $this->rap_htva+=$other['price'];
            $this->rap_tvac+=$other['price']+$other['vat'];
            $this->rap_priv+=$other['priv'];
            $this->rap_nd+=$other['priv'];


            $this->Cell(15,5,sprintf("%.2f",$other['price']),0,0,'R');
            if ( $this->jrn_type !='VEN')
            {
                $this->Cell(15,5,sprintf("%.2f",$other['priv']),0,0,'R');
                $this->Cell(15,5,sprintf("%.2f",$other['tva_nd_recup']),0,0,'R');
            }
            foreach ($atva_amount  as $row_atva_amount)
            {
                $this->Cell(15,5,sprintf("%.2f",$row_atva_amount),0,0,'R');
            }
            $this->Cell(15,5,sprintf("%.2f",($other['price']+$other['vat'])),0,0,'R');
            $this->Ln(5);
        }
    }

}
