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
// $Revision$
include_once("constant.php");
/* function AnalyzeError
 * Purpose : Following the error code
 *        show adialog box
 * parm : 
 *	- $p_result
 * gen :
 *	- none
 * return: none
 */

function AnalyzeError( $p_result) {

    switch( $p_result) {
      case NOERROR:
	break;
    case BADPARM:
      break;
    case BADDATE:
      echo "<SCRIPT> alert('Invalid Date'); </SCRIPT>";
      break;
    case NOTPERIODE:
	echo "<SCRIPT> alert('Date n\'est pas dans la période par défaut, changez vos préférences'); </SCRIPT>";
	break;
    case PERIODCLOSED:
      echo "<SCRIPT> alert('La date est dans une période cloturé'); </SCRIPT>"; 
      break;
    case INVALID_ECH:
	echo "<SCRIPT> alert('Invalid Echeance Date'); </SCRIPT>";
	break;
    case RAPPT_ALREADY_USED:
      echo '<SCRIPT>alert("Ne peut enregister le rapprochement l\'opération '.$p_rapt. " est déjà utilisée\")</SCRIPT>";
      break;
    case RAPPT_NOT_EXIST:
	echo '<SCRIPT>alert("Ne peut enregister le rapprochement l\'opération '.$p_rapt. " n\'existe pas \")</SCRIPT>";
	break;
    case DIFF_AMOUNT:
      echo "<SCRIPT>alert(\"Le Montant au débit n'est pas égal au montant au crédit\"); </SCRIPT>";
      break;
    case RAPPT_NOMATCH_AMOUNT:
	echo '<SCRIPT>alert("Ne peut enregister le rapprochement l\'opération '.$p_rapt. " les montants ne correspondent pas \")</SCRIPT>";
	break;
    }
}

?>