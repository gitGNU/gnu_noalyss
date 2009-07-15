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
 * \brief contains several function to replace the header in generated document
 *
 */

require_once ('class_database.php');
require_once ('debug.php');
require_once ('class.ezpdf.php');
require_once ('class_own.php');
require_once('class_dossier.php');


date_default_timezone_set ('Europe/Brussels');
function header_pdf($p_cn,&$p_pdf) {
  $own=new own($p_cn);
  $soc=$own->MY_NAME;
  $date=date('d / m / Y H:i ');
  $dossier=" Dossier : ".dossier::name();
  $p_pdf->ezText($dossier." ".$soc." ".$date,9);
  }
function header_txt($p_cn) {
  $own=new own($p_cn);
  $soc=$own->MY_NAME;

  $date=date('d / m / Y H:i ');
  $dossier=" Dossier : ".dossier::name();
  return $dossier." ".$soc." ".$date;
  }

?>
