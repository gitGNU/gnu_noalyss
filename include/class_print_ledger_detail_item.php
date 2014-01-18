<?php
/**
 * Print detail of operation PURCHASE or SOLD plus the items
 * There is no report of the different amounts
 *
 * @author danydb
 */
require_once 'class_acc_ledger_sold.php';
require_once 'class_acc_ledger_purchase.php';
class Print_Ledger_Detail_Item extends PDFLand
{
    public function __construct (Database $p_cn,Acc_Ledger $p_jrn)
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
        $this->Ln(2);
        $this->Cell(0,8,' Journal '.$this->ledger->get_name(),0,0,'C');
        //Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        //Page number
        $this->Cell(0,8,'Date '.$this->date." - Page ".$this->PageNo().'/{nb}',0,0,'L');
        // Created by NOALYSS
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
      $jrn_type=$this->ledger->get_type();
      switch ($jrn_type)
      {
          case 'VEN':
              $ledger=new Acc_Ledger_Sold($this->cn, $this->ledger->jrn_def_id);
              $ret_detail=$ledger->get_detail_sale($_GET['from_periode'],$_GET['to_periode']);
              break;
          case 'ACH':
                $ledger=new Acc_Ledger_Purchase($this->cn, $this->ledger->jrn_def_id);
                $ret_detail=$ledger->get_detail_purchase($_GET['from_periode'],$_GET['to_periode']);
              break;
          default:
              die (__FILE__.":".__LINE__.'Journal invalide');
              break;
      }
        if ( $ret_detail == null ) return;
        $nb=Database::num_row($ret_detail);
        $this->SetFont('DejaVu', '', 6);
        $internal="";
        $this->SetFillColor(220,221,255);
        $high=4;
        for ( $i=0;$i< $nb ;$i++)
        {
            $row=Database::fetch_array($ret_detail, $i);
            if ($internal != $row['jr_internal'])
            {
                // Print the general info line width=270mm
                $this->LongLine(20, $high, $row['jr_date'],1,  'L', true);
                $this->Cell(20, $high, $row['jr_internal'], 1, 0, 'L', true);
                $this->LongLine(70, $high, $row['quick_code']." ".$row['tiers_name'],1,'L',true);
                $this->LongLine(100, $high, $row['jr_comment'],1,'L',true);
                $this->Cell(20, $high, nbm($row['htva']), 1, 0, 'R', true);
                $this->Cell(20, $high, nbm($row['tot_vat']), 1, 0, 'R', true);
                $sum=bcadd($row['htva'],$row['tot_vat']);
                $this->Cell(20, $high, nbm($sum), 1, 0, 'R', true);
                $internal=$row['jr_internal'];
                $this->Ln(6);
                //
                // Header detail
                $this->LongLine(30,$high,'QuickCode');
                $this->Cell(30,$high,'Poste');
                $this->LongLine(90,$high,'Libellé');
                $this->Cell(20,$high,'Prix/Unit',0,0,'R');
                $this->Cell(20,$high,'Quant.',0,0,'R');
                $this->Cell(20,$high,'HTVA',0,0,'R');
                $this->Cell(20,$high,'Code TVA');
                $this->Cell(20,$high,'TVA',0,0,'R');
                $this->Cell(20,$high,'TVAC',0,0,'R');
                $this->Ln(6);
            }
            // Print detail sale / purchase
            $this->LongLine(30,$high,$row['j_qcode']);
            $this->Cell(30,$high,$row['j_poste']);
            $comment=($row['j_text']=="")?$row['item_name']:$row['j_text'];
            $this->LongLine(90,$high,$comment);
            $this->Cell(20,$high,nbm($row['price_per_unit']),0,0,'R');
            $this->Cell(20,$high,nbm($row['quantity']),0,0,'R');
            $this->Cell(20,$high,nbm($row['price']),0,0,'R');
            $this->Cell(20,$high,$row['vat_code']." ".$row['tva_label']);
            $this->Cell(20,$high,nbm($row['vat']),0,0,'R');
            $sum=bcadd($row['price'],$row['vat']);
            $this->Cell(20,$high,nbm($sum),0,0,'R');
            $this->Ln(6);
            
        }
    }

}
?>
