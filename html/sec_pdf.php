<?

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
// Copyright Stanislas Pinte stanpinte@sauvages.be
// $Revision$

if ( ! isset($_SESSION['g_dossier']) ) {
  echo "INVALID G_DOSSIER UNKNOWN !!! ";
  exit();
}
include_once("ac_common.php");
include_once("postgres.php");
include_once("class.ezpdf.php");

echo_debug(__FILE__,__LINE__,"imp pdf securité");
$cn=DbConnect($_SESSION['g_dossier']);

$pdf=& new Cezpdf("A4");
$pdf->selectFont('./addon/fonts/Helvetica.afm');

$pdf->ezText("hello",12,array('justification'=>'right'));
$pdf->ezStream();

?>
