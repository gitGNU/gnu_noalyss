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
 * \brief export the operation in pdf
 *
 */
require_once ('class_anc_balance_simple.php');
require_once ('header_print.php');

$cn=new Database(dossier::id());

$balance=new Anc_Balance_Simple($cn);



$pdf= new Cezpdf("A4");
$pdf->selectFont('./addon/fonts/Helvetica.afm');
header_pdf($cn,$pdf);
$balance->get_request();
$balance->display_pdf();

