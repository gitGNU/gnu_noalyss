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
 * \brief
 */

require_once('fpdf/fpdf.php');

class PDF extends FPDF {

    var $cn  = null;
    var $own = null;
    var $soc = "";
    var $dossier =  "n/a";
    var $date = "";

    public function PDF($p_cn = null, $orientation = 'P', $unit = 'mm', $format = 'A4') {

        if($p_cn == null) die("No database connection. Abort.");

        parent::FPDF($orientation, $unit, $format);

        date_default_timezone_set ('Europe/Paris');

        $this->cn  = $p_cn;
        $this->own = new own($this->cn);
        $this->soc = $this->own->MY_NAME;
        $this->date = date('d.m.Y');
    }

    function setDossierInfo($dossier = "n/a") {
        $this->dossier = utf8_decode(dossier::name()." ".$dossier);
    }

    function Header() {
        //Arial bold 12
        $this->SetFont('Arial', 'B', 12);
        //Title
        $this->Cell(0,10,$this->dossier, 'B', 0, 'C');
        //Line break
        $this->Ln(20);
    }
    function Footer() {
        //Position at 1.5 cm from bottom
        $this->SetY(-15);
        //Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        //Page number
        $this->Cell(0,10,'Date '.$this->date." - Page ".$this->PageNo().'/{nb}',0,0,'C');
    }
    function Cell ($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
        $txt = str_replace("\\", "", $txt);
        return parent::Cell($w, $h, utf8_decode($txt), $border, $ln, $align, $fill, $link);
    }
}

