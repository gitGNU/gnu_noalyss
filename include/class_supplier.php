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
require_once("postgres.php");
require_once("class_parm_code.php");
require_once("class_widget.php");
require_once('class_periode.php');
require_once('class_fiche.php');
require_once('class_acc_account_ledger.php');
require_once('user_common.php');
/*! \file
 * \brief Derived from class fiche Supplier are a specific kind of card
 */
/*! 
 * \brief  class  Supplier are a specific kind of card
 */

// Use the view vw_supplier
// 
class Supplier extends fiche{

  var $poste;      /*!< $poste poste comptable */
  var $name;        /*!< $name name of the company */
  var $street;      /*!< $street Street */
  var $country;     /*!< $country Country */
  var $cp;          /*!< $cp Zip code */
  var $vat_number;  /*!< $vat_number vat number */

  /*! \brief Constructor 
  /* only a db connection is needed */
  function Supplier($p_cn,$p_id=0) {
      $this->fiche_def_ref=FICHE_TYPE_FOURNISSEUR;
      fiche::fiche($p_cn,$p_id) ;

  }
  /*! \brief  Get all info contains in the view
   *  thanks to the poste elt (account)
  */
  function get_by_account($p_poste=0) {
    $this->poste=($p_poste==0)?$this->poste:$p_poste;
    $sql="select * from vw_supplier where poste_comptable=".$this->poste;
    $Res=ExecSql($this->cn,$sql);
    if ( pg_NumRows($Res) == 0) return null;
    // There is only _one_ row by supplier
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
 * \brief  show the default screen
 *        
 * \param p_search (filter)
 * \return string to display
 */
  function Summary($p_search) 
    {
      $str_dossier=dossier::get();
      $p_search=FormatString($p_search);
      $url=urlencode($_SERVER['REQUEST_URI']);
      $script=$_SERVER['PHP_SELF'];
      // Creation of the nav bar
      // Get the max numberRow
      $all_supplier=$this->CountByDef($this->fiche_def_ref,$p_search); 
      // Get offset and page variable
      $offset=( isset ($_REQUEST['offset'] )) ?$_REQUEST['offset']:0;
      $page=(isset($_REQUEST['page']))?$_REQUEST['page']:1;
      $bar=jrn_navigation_bar($offset,$all_supplier,$_SESSION['g_pagesize'],$page);
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
      $step_supplier=$this->GetAll($offset,$search);
      if ( $all_supplier == 0 ) return "";
      $r=$bar;
      $r.='<table width="95%">
<TR style="background-color:lightgrey;">
<TH>Quick Code</TH>
<th>Nom</th>
<th>Adresse</th>
<th>Total d&eacute;bit</th>
<th>Total cr&eacute;dit</th>
<th>Solde</th>
<th colspan="5">Action </th>
</TR>';
      if ( sizeof ($step_supplier ) == 0 )
	return $r;
      foreach ($step_supplier as $supplier ) {
	$r.="<TR>";
	$e=sprintf('<A HREF="%s?p_action=fournisseur&'.$str_dossier.'&sa=detail&f_id=%d&url=%s" title="Détail"> ',
		    $script,$supplier->id,$url);

	$r.="<TD> $e".$supplier->strAttribut(ATTR_DEF_QUICKCODE)."</A></TD>";
	$r.="<TD>".$supplier->strAttribut(ATTR_DEF_NAME)."</TD>";
	$r.="<TD>".$supplier->strAttribut(ATTR_DEF_ADRESS).
	  " ".$supplier->strAttribut(ATTR_DEF_CP).
	  " ".$supplier->strAttribut(ATTR_DEF_PAYS).
	  "</TD>";

	$post=new Acc_Account_Ledger($this->cn,$supplier->strAttribut(ATTR_DEF_ACCOUNT));
	/* Filter on the default year */
	$User=new User($this->cn);
	$filter_year="  j_tech_per in (select p_id from parm_periode ".
                     "where p_exercice='".$User->get_exercice()."')";
	$a=$post->get_solde_detail($filter_year);
	$r.=sprintf('<TD align="right"> %15.2f&euro;</TD>',$a['debit']);
	$r.=sprintf('<TD align="right"> %15.2f&euro;</TD>',$a['credit']);
	$r.=sprintf('<TD align="right"> %15.2f&euro;</TD>',$a['solde']);


	$r.=sprintf('<td><A HREF="%s?p_action=contact&qcode=%s&%s&url=%s" title="Contact">Contact</A></td>',
		    $script,$supplier->strAttribut(ATTR_DEF_QUICKCODE),$str_dossier,$url);
	$r.=sprintf('<td><A HREF="%s?p_action=suivi_courrier&sa=list&qcode=%s&%s&url=%s" title="Action">Courrier</A></td> ',
		    $script,$supplier->strAttribut(ATTR_DEF_QUICKCODE) ,$str_dossier,$url);



	$r.='<td><A HREF="commercial.php?p_action=depense&sa=list&'.$str_dossier.'&p_periode=-1&qcode='.$supplier->strAttribut(ATTR_DEF_QUICKCODE).'&'.$str_dossier.'&url='.$url.'" title="Historique Facture">Facture</A></td>';
	$r.=sprintf('<td><A class="mtitle" HREF="%s?liste&p_action=bank&sa=list&'.$str_dossier.'&qcode=%s&url=%s&p_periode=-1" title="Financier">Financier</A></td>',
		    $script,$supplier->strAttribut(ATTR_DEF_QUICKCODE) ,$url);

	$r.=sprintf('<td><A class="mtitle" HREF="%s?p_action=impress&type=poste&f_id=%s&%s&from_periode=%s&to_periode=%s&oper_detail=on&bt_html=Visualisation" 
title="Operation">Operation</A></td>',
		    $script,$supplier->strAttribut(ATTR_DEF_QUICKCODE) ,$str_dossier,$max->p_id,$min->p_id);

	$r.='</TD>';

	$r.="</TR>";

      }
      $r.="</TABLE>";
      $r.=$bar;
      return $r;
    }

}

?>
