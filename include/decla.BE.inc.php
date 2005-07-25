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
/* $Revision$ */
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/* function MakeListingVat
 **************************************************
 * Purpose : Créer le fichier à déposer pour la TVA
 *           voir le fichier BE_fr_list_client_nonpapier.pdf
 *        
 * parm : 
 *	-array
 * gen :
 *	-none
 * return:
 *     - none
 */
function MakeListingVat($p_cn,$p_array,$p_year) {
  // declarant
  //--
  require_once("class_own.php");
  // load the data
  $my=new Own($p_cn);
  //Make the first record
  $a="000000";
  $a.=sprintf("% 32s",$my->MY_NAME);
  $b=$my->MY_STREET.",".$my->MY_NUMBER;
  $a.=sprintf("% 24s",$b);
  $b=$my->MY_CP." ".$my->MY_COMMUNE;
  $a.=sprintf("% 27s",$b);
  $a.="BE";
  $a.=sprintf("%9s",$my->MY_TVA);
  // special zone
  $a.=sprintf("% 20s",' ');
  //
  $a.="E";
  $a.=$p_year;
  $a.="\n";
  // customer record
  $rec_id=0;
  $tot_amount=0;
  $tot_tva=0;
  foreach ($p_array as $client) {
    if ( strlen(trim($client['tva'])) != 0 ) {
      $rec_id++;
      $a.=sprintf("%06d",$rec_id);
      $a.=sprintf("% 32s",$client['name']);
      $a.=str_repeat(" ",51);
      $a.=sprintf("BE%s",$client['vat_number']);
      $a.=sprintf("%010d",$client['amount']*100);
      $a.=sprintf("%010d",$client['tva']*100);
      $a.=str_repeat(" ",10);
      $a.="\n";
      $tot_amount=+$client['amount']*100;
      $tot_tva+=$client['tva']*100;
    }
  }
    //Last Record
  $a.="999999";
  $a.=sprintf("%016d",$tot_amount);
  $a.=sprintf("%016d",$tot_tva);
  $a.=str_repeat(" ",51);
  $a.="BE";
  $a.=$my->MY_TVA;
  $a.=str_repeat(" ",28);
  $a.="\n";
  return $a;
}