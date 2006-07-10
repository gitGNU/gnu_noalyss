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
 * \brief Derived from class fiche Supplier are a specific kind of card
 */
/*! 
 * \brief  class  Supplier are a specific kind of card
 */

// Use the view vw_supplier
// 
class Supplier extends fiche{

  var $poste;      /*! \enum $poste poste comptable */
  var $name;        /*! \enum $name name of the company */
  var $street;      /*! \enum $street Street */
  var $country;     /*! \enum $country Country */
  var $cp;          /*! \enum $cp Zip code */
  var $vat_number;  /*! \enum $vat_number vat number */

  /*! \brief Constructor 
  /* only a db connection is needed */
  function Supplier($p_cn,$p_id=0) {
      $this->fiche_def_ref=FICHE_TYPE_FOURNISSEUR;
      fiche::fiche($p_cn,$p_id) ;

  }
  /*! \brief  Get all info contains in the view
   *  thanks to the poste elt (account)
  */
  function GetFromPoste($p_poste=0) {
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
      $script=$_SERVER['SCRIPT_NAME'];
      // Creation of the nav bar
      // Get the max numberRow
      $all_supplier=$this->CountByDef($this->fiche_def_ref,$p_search); 
      // Get offset and page variable
      $offset=( isset ($_REQUEST['offset'] )) ?$_REQUEST['offset']:0;
      $page=(isset($_REQUEST['page']))?$_REQUEST['page']:1;
      $bar=jrn_navigation_bar($offset,$all_supplier,$_SESSION['g_pagesize'],$page);
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
      $step_supplier=$this->GetAll($offset,$search);
      if ( $all_supplier == 0 ) return "";
      $r=$bar;
      $r.='<table>
<TR style="background-color:lightgrey;">
<TH>Quick Code</TH>
<th>Nom</th>
<th>Adresse</th>
<th>Solde</th>
<th colspan="3">Action </th>
</TR>';
      if ( sizeof ($step_supplier ) == 0 )
	return $r;
      foreach ($step_supplier as $supplier ) {
	$r.="<TR>";
	$e=sprintf('<A HREF="%s?p_action=fournisseur&sa=detail&f_id=%d&url=%s" title="Détail"> ',
		    $script,$supplier->id,$url);

	$r.="<TD> $e".$supplier->strAttribut(ATTR_DEF_QUICKCODE)."</A></TD>";
	$r.="<TD>".$supplier->strAttribut(ATTR_DEF_NAME)."</TD>";
	$r.="<TD>".$supplier->strAttribut(ATTR_DEF_ADRESS).
	  " ".$supplier->strAttribut(ATTR_DEF_CP).
	  " ".$supplier->strAttribut(ATTR_DEF_PAYS).
	  "</TD>";

	$post=new poste($this->cn,$supplier->strAttribut(ATTR_DEF_ACCOUNT));
	$a=$post->GetSoldeDetail();
	$r.=sprintf('<TD align="right"> %15.2f&euro;</TD>',$a['solde']);


	$r.=sprintf('<td><A HREF="%s?p_action=contact&qcode=%s&url=%s" title="Contact">Contact</A></td>',
		    $script,$supplier->strAttribut(ATTR_DEF_QUICKCODE),$url);
	$r.=sprintf('<td><A HREF="%s?p_action=suivi_courrier&sa=list&qcode=%s&url=%s" title="Action">Courrier</A></td> ',
		    $script,$supplier->strAttribut(ATTR_DEF_QUICKCODE) ,$url);



	$r.='<td><A HREF="commercial.php?p_action=depense&sa=list&p_periode=-1&qcode='.$supplier->strAttribut(ATTR_DEF_QUICKCODE).'&url='.$url.'" title="Historique Facture">Facture</A></td>';

	$r.='</TD>';

	$r.="</TR>";

      }
      $r.="</TABLE>";
      $r.=$bar;
      return $r;
    }

}

?>
