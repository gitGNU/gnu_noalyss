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
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

    include_once("jrn.php");
    include_once("ac_common.php");
    include_once("postgres.php");
    include_once("class.ezpdf.php");
    include_once("impress_inc.php");
    echo_debug(__FILE__,__LINE__,"imp pdf journaux");
    $l_Db=sprintf("dossier%d",$g_dossier);
    $cn=DbConnect($l_Db);

    $name_jrn=GetJrnName($cn,$_POST["p_id"]);
    $ret="";
    $array=GetDataJrnPdf($cn,$HTTP_POST_VARS);
    $pdf=& new Cezpdf();
    $pdf->selectFont('./fonts/Helvetica.afm');
    $pdf->ezTable($array,array ('j_date' => 'Date',
				'description' => 'Description',
				'deb_montant'=> 'Montant',
				'cred_montant'=>'Montant'),$name_jrn);
    $pdf->ezStream();

?>
