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
require_once("constant.php");
require_once("postgres.php");
// Use the view vw_customer
// 
class Customer {
  var $id;         // f_id
  var $poste;      // poste comptable
  var $db;        // base de donnée
  var $name;
  var $street;
  var $country;
  var $cp;
  var $vat_number;
  // Constructor
  // only a db connection is needed
  function Customer($p_cn) {
    $this->db=$p_cn;
  }
  // Get all info contains in the view
  // thanks to the poste elt
  function GetFromPoste($p_poste=0) {
    $this->poste=($p_poste==0)?$this->poste:$p_poste;
    $sql="select * from vw_client where poste_comptable=".$this->poste;
    $Res=ExecSql($this->db,$sql);
    if ( pg_NumRows($Res) == 0) return null;
    // There is only _one_ row by customer
    $row=pg_fetch_array($Res,0);
    $this->name=$row['name'];
    $this->id=$row['f_id'];    
    $this->street=$row['rue'];    
    $this->cp=$row['code_postal'];
    $this->country=$row['pays'];
    $this->vat_number=$row['tva_num'];

  }
/* function ListingVat
 **************************************************
 * Purpose : Get all the info for making a vat listing
 *           for the vat administration
 *        
 * parm : 
 *	- periode
 * 
 * return:
 * 
 *	- array
 */
  function VatListing($p_year) {
    $cond_sql=" and A.j_date = B.j_date 
      and extract(year from A.j_date) ='$p_year'";
    
    // BASE ACCOUNT
    // for belgium
    // TODO : those values should be in a table because 
    // they are _national_ parameters
    //-----
    $SOLD="70";
    $CUSTOMER="400";
    $TVA="451";
    // Get all the sell operation
    //----
    $sql="select  j_grpt 
      from
jrnx as A
 join jrnx as B using (j_grpt)
where
       A.j_poste like '".$CUSTOMER."%' and
       B.j_poste like '".$SOLD."%' 
      $cond_sql
";

    $Res=ExecSql($this->db,$sql);
    // Foreach operation 
    // where 7% or tva account are involved
    // and store the result in an array (a_Res)
    //---
    $a_Res=array();
    for ($i=0; $i < pg_NumRows($Res);$i++) {
      // Get each row
      //---
      $row1=pg_fetch_array($Res,$i);
  
      // select the operation
      //----
      $Res2=ExecSql($this->db,"select j_poste,j_montant,j_debit from jrnx where j_grpt=".$row1['j_grpt']); 
      $a_row=array();
      // Store the result in the array 
      //---
      for ($e=0;$e < pg_NumRows($Res2);$e++) {
	$a_row[]=pg_fetch_array($Res2,$e);
      }
      
      // Seek the customer
      //---
      foreach ($a_row as $e) {
	if ( substr($e['j_poste'],0, strlen($CUSTOMER))==$CUSTOMER) {
	  $customer=$e['j_poste'];
	  // Retrieve name and vat number
	  $this->GetFromPoste($customer);
	  $a_Res[$customer]['name']=$this->name;
	  $a_Res[$customer]['vat_number']=$this->vat_number;
	  break;
	  
	}
      }// foreach $a
      // Store the amount in the array
      //---
      foreach ($a_row as $e) {
	$amount=0;
	$tva=0;
	if ( substr($e['j_poste'],0, strlen($SOLD))==$SOLD) {
	  $amount=($e['j_debit']=='f')?$e['j_montant']:$e['j_montant']*-1;
	}
	if ( substr($e['j_poste'],0, strlen($TVA))==$TVA) {
	  $tva=($e['j_debit']=='f')?$e['j_montant']:$e['j_montant']*-1;
	}
	// store sold
	//---
	$a_Res[$customer]['amount']=(isset($a_Res[$customer]['amount']))?$a_Res[$customer]['amount']:0;    	
  $a_Res[$customer]['amount']+=$amount;

	// store vat
	//---
	$a_Res[$customer]['tva']=(isset($a_Res[$customer]['tva']))?$a_Res[$customer]['tva']:0;
	$a_Res[$customer]['tva']+=$tva;  
  
	// store customef info
	//---
	$a_Res[$customer]['customer']=$customer;

  //if not submitted to VAT, remove from list:
  //STAN: currently commented out because I don't know if it is really what we need.
  //if (!isset($a_Res[$customer]['vat_number']) || strcmp($a_Res[$customer]['vat_number'], "") == 0)
  //{
  //  unset($a_Res[$customer]);
  //}
  
      }// foreach $a

    }
    return $a_Res;
  }
}

?>
