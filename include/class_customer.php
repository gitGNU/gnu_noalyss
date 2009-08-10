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
require_once("constant.php");
require_once('class_database.php');
require_once("class_acc_parm_code.php");
require_once('class_periode.php');
require_once('class_fiche.php');
require_once('class_acc_account_ledger.php');
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

  var $poste;      /*!<  $poste poste comptable */
  var $name;        /*!<  $name name of the company */
  var $street;      /*!<  $street Street */
  var $country;     /*!<  $country Country */
  var $cp;          /*!<  $cp Zip code */
  var $vat_number;  /*!<  $vat_number vat number */

  /*! \brief Constructor 
  /* only a db connection is needed */
  function Customer($p_cn,$p_id=0) {
      $this->fiche_def_ref=FICHE_TYPE_CLIENT;
      fiche::fiche($p_cn,$p_id) ;

  }
  /*! \brief  Get all info contains in the view
   *  thanks to the poste elt (account)
  */
  function get_by_account($p_poste=0) {
    $this->poste=($p_poste==0)?$this->poste:$p_poste;
    $sql="select * from vw_client where poste_comptable=$1";
    $Res=$this->cn->exec_sql($sql,array($this->poste));
    if ( Database::num_row($Res) == 0) return null;
    if ( Database::num_row($Res) > 1 ) throw new Exception ('Plusieurs fiches avec le même poste',1);
    // There is only _one_ row by customer
    $row=Database::fetch_array($Res,0);
    $this->name=$row['name'];
    $this->id=$row['f_id'];    
    $this->street=$row['rue'];    
    $this->cp=$row['code_postal'];
    $this->country=$row['pays'];
    $this->vat_number=$row['tva_num'];

  }
/*! 
 * \brief  Get all the info for making a vat listing
 *           for the vat administration
 *
 * \param	 $p_year
 * 
 * \return  double array structure is 
 *            ( j_poste,name,vat_number,amount,tva,customer(object)
 *
 */
  function VatListing($p_year) {
    $cond_sql=" and   A.j_date = B.j_date and extract(year from A.j_date) ='$p_year'";
    /* List of customer  */
    $aCustomer=$this->cn->get_array('select f_id,name,quick_code,tva_num,poste_comptable from vw_client '.
	      " where tva_num !='' ");

    /* Use the code */

    // BASE ACCOUNT
    // for belgium
    $s=new Acc_Parm_Code($this->cn,'VENTE');
    $s->load();
    $SOLD=$s->p_value;

    $c=new Acc_Parm_Code($this->cn,'CUSTOMER');
    $c->load();
    $CUSTOMER=$c->p_value;

    $t=new Acc_Parm_Code($this->cn,'COMPTE_TVA');
    $t->load();
    $TVA=$t->p_value;

    $a_Res=array();
    /* for each customer compute VAT, Amount...*/
    foreach ($aCustomer as $l ) {
      // Seek the customer
      //---
      $customer=$l['quick_code'];
      $a_Res[$customer]['name']=$l['name'];
      $a_Res[$customer]['vat_number']=$l['tva_num'];
      $a_Res[$customer]['amount']=0;
      $a_Res[$customer]['tva']=0;
      $a_Res[$customer]['poste_comptable']=$l['poste_comptable'];
      /* retrieve only operation of sold and vat */
    // Get all the sell operation
    //----
    $sql="select distinct j_grpt 
      from
jrnx as A
 join jrnx as B using (j_grpt)
where
       A.j_qcode = '".$l['quick_code']."' and
       B.j_poste::text like '".$SOLD."%' 
      $cond_sql
";

    $Res=$this->cn->exec_sql($sql);
    // Foreach operation 
    // where 7% or tva account are involved
    // and store the result in an array (a_Res)
    //---

    for ($i=0; $i < Database::num_row($Res);$i++) {
      // Get each row
      //---
      $row1=Database::fetch_array($Res,$i);
  

      // select the operation
      //----
      $Res2=$this->cn->exec_sql("select j_poste,j_montant,j_debit from jrnx where j_grpt=".$row1['j_grpt']); 
      $a_row=Database::fetch_all($Res2);

      // Store the amount in the array
      //---
      foreach ($a_row as $e) {
	$amount=0;
	$tva=0;
	if ( substr($e['j_poste'],0, strlen($SOLD))===$SOLD) {
	  $amount=($e['j_debit']=='f')?$e['j_montant']:$e['j_montant']*-1;
	}
	if ( substr($e['j_poste'],0, strlen($TVA))===$TVA) {
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
      }
    }// foreach $a
    } // foreach ( customer)

    return $a_Res;
  }
/*! Summary
 **************************************************
 * \brief  show the default screen
 *        
 * \param p_search (filter) 
 * \param p_action show the action column
 *	 
 * \return: string to display
 */
  function Summary($p_search,$p_action=1) 
    {
      $str_dossier=dossier::get();
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

      $user=new User($this->cn);
      $exercice=$user->get_exercice();
      $tPeriode=new Periode($this->cn);
      list($max,$min)=$tPeriode->get_limit($exercice);


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
      $r.='<table  width="95%">
<TR style="background-color:lightgrey;">
<TH>Quick Code</TH>
<th>Nom</th>
<th>Adresse</th>
<th>Total d&eacute;bit</th>
<th>Total cr&eacute;dit</th>
<th>Solde</th>';
      $r.=($p_action==1)?'<th colspan="5">Action</th>':'';
$r.='</TR>';
	  echo_debug(__FILE__,__LINE__,$step_client);
      if ( sizeof ($step_client ) == 0 )
	return $r;
      foreach ($step_client as $client ) {
	$r.="<TR>";
	$e=sprintf('<A HREF="%s?p_action=client&sb=detail&f_id=%d&%s&url=%s" title="Détail"> ',
			   $script,$client->id,$str_dossier,$url);

	$r.="<TD> $e".$client->strAttribut(ATTR_DEF_QUICKCODE)."</A></TD>";
	$r.="<TD>".h($client->strAttribut(ATTR_DEF_NAME))."</TD>";
	$r.="<TD>".h($client->strAttribut(ATTR_DEF_ADRESS).
	  " ".$client->strAttribut(ATTR_DEF_CP).
	  " ".$client->strAttribut(ATTR_DEF_PAYS)).
	  "</TD>";


	/* Filter on the default year */
	$User=new User($this->cn);
	$filter_year="  j_tech_per in (select p_id from parm_periode ".
                     "where p_exercice='".$User->get_exercice()."')";
	$a=$client->get_solde_detail($filter_year);

	$r.=sprintf('<TD align="right"> %15.2f&euro;</TD>',$a['debit']);
	$r.=sprintf('<TD align="right"> %15.2f&euro;</TD>',$a['credit']);
	$r.=sprintf('<TD align="right"> %15.2f&euro;</TD>',$a['solde']);
	if ( $p_action == 1 ) {
	  if ( basename($script)=='commercial.php') {
	    $r.="<TD>";
	  
	    $r.=sprintf('<A class="mtitle" HREF="%s?p_action=contact&qcode=%s&%s&url=%s" title="Contact">Contact</A></td>',
			$script,$client->strAttribut(ATTR_DEF_QUICKCODE),$str_dossier,$url);
	    $r.=sprintf('<td><A  class="mtitle"  HREF="%s?p_action=suivi_courrier&sa=list&qcode=%s&%s&url=%s" title="Action">Courrier</A></td> ',
			$script,$client->strAttribut(ATTR_DEF_QUICKCODE) ,$str_dossier,$url);
	  
	  
	  } 
	  $p_action_ven=( $script == "commercial.php")?"p_action=client&sa=f":"p_action=ven&sa=l";
	  $r.='<td><A  class="mtitle"  HREF="?'.$p_action_ven.'&p_periode=-1&'.$str_dossier.'&qcode='.$client->strAttribut(ATTR_DEF_QUICKCODE).'&url='.$url.'" title="Historique Facture">Facture</A></td>';
	  $r.=sprintf('<td><A class="mtitle" HREF="%s?liste&p_action=bank&sa=l&qcode=%s&%s&url=%s&p_periode=-1" title="Financier">Financier</A></td>',
		      $script,$client->strAttribut(ATTR_DEF_QUICKCODE) ,$str_dossier,$url);
	  
	  $r.=sprintf('<td><A class="mtitle" HREF="%s?p_action=impress&type=poste&f_id=%s&%s&from_periode=%s&to_periode=%s&bt_html=Visualisation" 
title="Operation">Operation</A></td>',
		      $script,$client->strAttribut(ATTR_DEF_QUICKCODE) ,$str_dossier,$max->p_id,$min->p_id);
	  
	  $r.='</TD>';
	
	}
	
	$r.="</TR>";

      }
      $r.="</TABLE>";
      $r.=$bar;
      return $r;
    }

}

?>
