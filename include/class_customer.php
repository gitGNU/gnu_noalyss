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
require_once("class_parm_code.php");
require_once("class_widget.php");

require_once('class_fiche.php');
require_once('class_poste.php');
require_once('user_common.php');
/*! \file
 * \brief Derived from class fiche Customer are a specific kind of card
 */
/*! 
 * \brief  class  Customer are a specific kind of card
 */

// Use the view vw_customer
// 
class Customer extends fiche{

  var $poste;      /*! \enum $poste poste comptable */
  var $name;        /*! \enum $name name of the company */
  var $street;      /*! \enum $street Street */
  var $country;     /*! \enum $country Country */
  var $cp;          /*! \enum $cp Zip code */
  var $vat_number;  /*! \enum $vat_number vat number */

  /*! \brief Constructor 
  /* only a db connection is needed */
  function Customer($p_cn,$p_id=0) {
      $this->fiche_def_ref=FICHE_TYPE_CLIENT;
      fiche::fiche($p_cn,$p_id) ;

  }
  /*! \brief  Get all info contains in the view
   *  thanks to the poste elt (account)
  */
  function GetFromPoste($p_poste=0) {
    $this->poste=($p_poste==0)?$this->poste:$p_poste;
    $sql="select * from vw_client where poste_comptable=".$this->poste;
    $Res=ExecSql($this->cn,$sql);
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
/*! 
 **************************************************
 * \brief  Get all the info for making a vat listing
 *           for the vat administration
 * \todo  optimize SQL
 *  
 * \param	 periode
 * 
 * \return  double array structure is 
 *            ( j_poste,name,vat_number,amount,tva,customer(object)
 *
 */
  function VatListing($p_year) {
    $cond_sql=" and A.j_date = B.j_date 
      and extract(year from A.j_date) ='$p_year'";
    
    // BASE ACCOUNT
    // for belgium
    $s=new parm_code($this->cn,'VENTE');
    $s->Get();
    $SOLD=$s->p_value;

    $c=new parm_code($this->cn,'CUSTOMER');
    $c->Get();
    $CUSTOMER=$c->p_value;

    $t=new parm_code($this->cn,'COMPTE_TVA');
    $t->Get();
    $TVA=$t->p_value;
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

    $Res=ExecSql($this->cn,$sql);
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
      $Res2=ExecSql($this->cn,"select j_poste,j_montant,j_debit from jrnx where j_grpt=".$row1['j_grpt']); 
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
  //Dany : yes we need it because the decla. concerns only the registered customer at the VAT 
	if (!isset($a_Res[$customer]['vat_number']) || strcmp($a_Res[$customer]['vat_number'], "") == 0)
	  {
	    unset($a_Res[$customer]);
	  }
	
      }// foreach $a

    }
    return $a_Res;
  }
/*! \function  Summary
 **************************************************
 \Brief  show the default screen
 *        
 * parm : 
 *	- p_search (filter)
 * gen :
 *	-
 * return: string to display
 */
  function Summary($p_search) 
    {
      $p_search=FormatString($p_search);
      $url=urlencode($_SERVER['REQUEST_URI']);
      $script=$_SERVER['PHP_SELF'];
      // Creation of the nav bar
      // Get the max numberRow
      $all_client=$this->CountByDef($this->fiche_def_ref,$p_search); 
      // Get offset and page variable
      $offset=( isset ($_REQUEST['offset'] )) ?$_REQUEST['offset']:0;
      $page=(isset($_REQUEST['page']))?$_REQUEST['page']:1;
      $bar=jrn_navigation_bar($offset,$all_client,$_SESSION['g_pagesize'],$page);
      // set a filter ?
      $search="";
      if ( trim($p_search) != "" )
	{
	  $search=" and f_id in
(select f_id from jnt_fic_att_value 
                  join fiche using (f_id) 
                  join attr_value using (jft_id)
                where
                ad_id=1 and av_text ~* '$p_search')";
	}
      // Get The result Array
      $step_client=$this->GetAll($offset,$search);
      if ( $all_client == 0 ) return "";
      $r=$bar;
      $r.='<table>
<TR style="background-color:lightgrey;">
<TH>Quick Code</TH>
<th>Nom</th>
<th>Adresse</th>
<th>Solde</th>
<th colspan="4">Action </th>
</TR>';
      if ( sizeof ($step_client ) == 0 )
	return $r;
      foreach ($step_client as $client ) {
	$r.="<TR>";
	$e=sprintf('<A HREF="%s?p_action=client&sa=detail&f_id=%d&url=%s" title="D�tail"> ',
		    $script,$client->id,$url);

	$r.="<TD> $e".$client->strAttribut(ATTR_DEF_QUICKCODE)."</A></TD>";
	$r.="<TD>".$client->strAttribut(ATTR_DEF_NAME)."</TD>";
	$r.="<TD>".$client->strAttribut(ATTR_DEF_ADRESS).
	  " ".$client->strAttribut(ATTR_DEF_CP).
	  " ".$client->strAttribut(ATTR_DEF_PAYS).
	  "</TD>";

	$post=new poste($this->cn,$client->strAttribut(ATTR_DEF_ACCOUNT));
	/* Filter on the default year */
	$User=new cl_user($this->cn);
	$filter_year="  j_tech_per in (select p_id from parm_periode ".
                     "where p_exercice='".$User->getExercice()."')";
	$a=$post->GetSoldeDetail($filter_year);
	$r.=sprintf('<TD align="right"> %15.2f&euro;</TD>',$a['solde']);
	$r.="<TD>";

	$r.=sprintf('<A HREF="%s?p_action=contact&qcode=%s&url=%s" title="Contact">Contact</A></td>',
		    $script,$client->strAttribut(ATTR_DEF_QUICKCODE),$url);
	$r.=sprintf('<td><A HREF="%s?p_action=suivi_courrier&sa=list&qcode=%s&url=%s" title="Action">Courrier</A></td> ',
		    $script,$client->strAttribut(ATTR_DEF_QUICKCODE) ,$url);



	$r.='<td><A HREF="commercial.php?p_action=facture&sa=list&p_periode=-1&qcode='.$client->strAttribut(ATTR_DEF_QUICKCODE).'&url='.$url.'" title="Historique Facture">Facture</A></td>';
	$r.=sprintf('<td><A class="mtitle" HREF="%s?liste&p_action=bank&sa=list&qcode=%s&url=%s&p_periode=-1" title="Financier">Financier</A></td>',
		    $script,$client->strAttribut(ATTR_DEF_QUICKCODE) ,$url);

	$r.='</TD>';

	$r.="</TR>";

      }
      $r.="</TABLE>";
      $r.=$bar;
      return $r;
    }

}

?>
