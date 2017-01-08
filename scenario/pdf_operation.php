<?php
/*
 *   This file is part of NOALYSS.
 *
 *   NOALYSS is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   NOALYSS is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with NOALYSS; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */
//@description: Test the class PDF_OPERATION

require_once NOALYSS_INCLUDE."/class/class_pdf_operation.php";



$pdf=new PDF_Operation ($cn,4677);
//$pdf=new PDF_Operation ($cn,47);
//$pdf=new PDF_Operation ($cn,4638);

$pdf->export_pdf(array("anc","acc"));

$a=$pdf->get_pdf();
$a->Output("/tmp/t.pdf","F");

echo $pdf->get_pdf_filename();