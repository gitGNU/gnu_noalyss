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
require_once("class_acc_parm_code.php");
require_once("class_widget.php");

require_once('class_fiche.php');
require_once('class_acc_account_ledger.php');
require_once('user_common.php');
/*! \file
 * \brief Derived from class fiche Administration are a specific kind of card
 *        concerned only by official (or not) administration
 */
/*! 
 * \brief  class  admin are a specific kind of card
 */

// Use the view vw_supplier
// 
class Admin extends fiche{

  var $name;        /*!< $name name of the company */
  var $street;      /*!< $street Street */
  var $country;     /*!< $country Country */
  var $cp;          /*!< $cp Zip code */
  var $vat_number;  /*!< $vat_number vat number */

  /*! \brief Constructor 
  /* only a db connection is needed */
  function Admin($p_cn,$p_id=0) {
      $this->fiche_def_ref=FICHE_TYPE_ADM_TAX;
      fiche::fiche($p_cn,$p_id) ;

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
	  $str_dossier=dossier::get();
      $p_search=FormatString($p_search);
      $url=urlencode($_SERVER['REQUEST_URI']);
      $script=$_SERVER['PHP_SELF'];
      // Creation of the nav bar
      // Get the max numberRow
      $all_admin=$this->CountByDef($this->fiche_def_ref,$p_search); 
      // Get offset and page variable
      $offset=( isset ($_REQUEST['offset'] )) ?$_REQUEST['offset']:0;
      $page=(isset($_REQUEST['page']))?$_REQUEST['page']:1;
      $bar=jrn_navigation_bar($offset,$all_admin,$_SESSION['g_pagesize'],$page);
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
      $step_admin=$this->GetAll($offset,$search);
      if ( $all_admin == 0 ) return "";
      $r=$bar;
      $r.='<table  width="95%">
<TR style="background-color:lightgrey;">
<TH>Quick Code</TH>
<th>Nom</th>
<th>Adresse</th>
<th>Action </th>
</TR>';
      if ( sizeof ($step_admin ) == 0 )
	return $r;
      foreach ($step_admin as $admin ) {
	$r.="<TR>";
	$e=sprintf('<A HREF="%s?p_action=admin&sa=detail&f_id=%d&%s&url=%s" title="Detail"> ',
			   $script,$admin->id,$str_dossier,$url);

	$r.="<TD> $e".$admin->strAttribut(ATTR_DEF_QUICKCODE)."</A></TD>";
	$r.="<TD>".$admin->strAttribut(ATTR_DEF_NAME)."</TD>";
	$r.="<TD>".$admin->strAttribut(ATTR_DEF_ADRESS).
	  " ".$admin->strAttribut(ATTR_DEF_CP).
	  " ".$admin->strAttribut(ATTR_DEF_PAYS).
	  "</TD>";
	$r.="<td>";
	$r.=sprintf('<A class="mtitle" HREF="%s?p_action=contact&qcode=%s&%s&url=%s" title="Contact">Contact</A> - ',
				$script,$admin->strAttribut(ATTR_DEF_QUICKCODE),$str_dossier,$url);
	$r.=sprintf('<A class="mtitle" HREF="%s?p_action=suivi_courrier&sa=list&qcode=%s&%s&url=%s" title="Action">Action</A> - ',
				$script,$admin->strAttribut(ATTR_DEF_QUICKCODE) ,$str_dossier,$url);
	$r.=sprintf('<A class="mtitle" HREF="%s?liste&p_action=bank&sa=list&qcode=%s&%s&url=%s&p_periode=-1" title="Financier">Financier</A> - ',
				$script,$admin->strAttribut(ATTR_DEF_QUICKCODE) ,$str_dossier,$url);

	$r.='</TD>';

	$r.="</TR>";

      }
      $r.="</TABLE>";
      $r.=$bar;
      return $r;
    }

}

?>
