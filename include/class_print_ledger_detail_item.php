<?php
/**
 * Print detail of operation PURCHASE or SOLD plus the items
 * There is no report of the different amounts
 *
 * @author danydb
 */
class Print_Ledger_Detail_Item extends PDF
{
    public function __construct ($p_cn,$p_jrn)
    {

        if($p_cn == null) die("No database connection. Abort.");

        parent::__construct($p_cn,'L', 'mm', 'A4');
        $this->ledger=$p_jrn;
        
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
        
    }
    /**
     *@brief write the Footer
     */
    function Footer()
    {
        //Position at 3 cm from bottom
        $this->SetY(-20);
        /* write reporting  */
        $this->Cell(143,6,'Total page ','T',0,'R'); /* HTVA */
        $this->Cell(15,6,nbm($this->tp_htva),'T',0,'R'); /* HTVA */
        if ( $this->jrn_type !='VEN')
        {
            $this->Cell(15,6,nbm($this->tp_priv),'T',0,'R');  /* prive */
            $this->Cell(15,6,nbm($this->tp_nd),'T',0,'R');  /* Tva ND */
        }
        foreach($this->a_Tva as $line_tva)
        {
            $l=$line_tva['tva_id'];
            $this->Cell(15,6,nbm($this->tp_tva[$l]),'T',0,'R');
        }
        $this->Cell(15,6,nbm($this->tp_tvac),'T',0,'R'); /* Tvac */
        $this->Ln(2);

        $this->Cell(143,6,'report',0,0,'R'); /* HTVA */
        $this->Cell(15,6,nbm($this->rap_htva),0,0,'R'); /* HTVA */
        if ( $this->jrn_type !='VEN')
        {
            $this->Cell(15,6,nbm($this->rap_priv),0,0,'R');  /* prive */
            $this->Cell(15,6,nbm($this->rap_nd),0,0,'R');  /* Tva ND */
        }
        foreach($this->a_Tva as $line_tva)
        {
            $l=$line_tva['tva_id'];
            $this->Cell(15,6,nbm($this->rap_tva[$l]),0,0,'R');
        }
        $this->Cell(15,6,nbm($this->rap_tvac),0,0,'R'); /* Tvac */
        $this->Ln(2);

        //Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        //Page number
        $this->Cell(0,8,'Date '.$this->date." - Page ".$this->PageNo().'/{nb}',0,0,'L');
        // Created by PhpCompta
        $this->Cell(0,8,'Created by Phpcompta, online on http://www.aevalys.eu',0,0,'R',false,'http://www.aevalys.eu');
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
      bcscale(2);
        $a_jrn=$this->ledger->get_operation($_GET['from_periode'],
                                            $_GET['to_periode']);

        if ( $a_jrn == null ) return;
        for ( $i=0;$i<count($a_jrn);$i++)
        {
            
        }
    }

}
?>
