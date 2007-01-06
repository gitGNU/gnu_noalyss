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
//!\brief class for the contact, contact are derived from fiche
require_once("class_fiche.php");
require_once("constant.php");
require_once("user_common.php");
/*! \file
 * \brief Contact are a card which are own by a another card (customer, supplier...)
 */
/*! 
 * \brief Class contact (customer, supplier...)
 */

class contact extends fiche
{
  var $company; /*!\enum $company company of the contact (ad_id=ATTR_DEF_COMPANY)*/
  /*!\brief constructor */
  function contact($p_cn,$p_id=0)
    {
      $this->fiche_def_ref=FICHE_TYPE_CONTACT;
      fiche::fiche($p_cn,$p_id) ;
      $this->company="";
    }
/*!   Summary
 **************************************************
 * \brief  show the default screen
 *        
 * \param  p_search (filter)
 *	
 * \return string to display
 */
  function Summary($p_search) 
    {
      $p_search=FormatString($p_search);
      $extra_sql="";
      if ( $this->company != "") 
	{
	  $extra_sql="and f_id in (select f_id from jnt_fic_att_value join 
attr_value using (jft_id) where av_text='".$this->company."' and ad_id=".ATTR_DEF_COMPANY.") ";
	}
      $url=urlencode($_SERVER['REQUEST_URI']);
      $script=$_SERVER['PHP_SELF'];
      // Creation of the nav bar
      // Get the max numberRow
      $all_contact=$this->CountByDef($this->fiche_def_ref,$p_search,$extra_sql); 
      // Get offset and page variable
      $offset=( isset ($_REQUEST['offset'] )) ?$_REQUEST['offset']:0;
      $page=(isset($_REQUEST['page']))?$_REQUEST['page']:1;
      $bar=jrn_navigation_bar($offset,$all_contact,$_SESSION['g_pagesize'],$page);
      // set a filter ?
      $search="";
      if ( trim($p_search) != "" )
	{
	  $search=" and f_id in
(select f_id from jnt_fic_att_value 
                  join fiche using (f_id) 
                  join attr_value using (jft_id)
                where
                ad_id=1 and av_text ~* '$p_search') ";
	}
      // Get The result Array
      $step_contact=$this->GetAll($offset,$search.$extra_sql);
      if ( $all_contact == 0 ) return "";
      $r=$bar;
      $r.='<table border="0"  width="95%">
<TR style="background-color:lightgrey;">
<th>Quick Code</th>
<th>Nom</th>
<th>Téléphone</th>
<th>email</th>
<th>Fax</th>
<th colspan="2">Société</th>
</TR>';
      $base=$_SERVER['PHP_SELF'];
      // Compute the url
      $url="";
      $and="?";
      $get=$_GET;
      if ( isset ($get) ) {
	foreach ($get as $name=>$value ) {
	  // we clean the parameter offset, step, page and size
	  if (  ! in_array($name,array('f_id','detail'))) {
	    $url.=$and.$name."=".$value;
	    $and="&";
	  }// if
	}//foreach
      }// if
$back_url=urlencode($_SERVER['REQUEST_URI']);
      if ( sizeof ($step_contact ) == 0 )
	return $r;
      echo JS_SEARCH_CARD;
      foreach ($step_contact as $contact ) {
	$l_company=new fiche($this->cn);
	$l_company->GetByQCode($contact->strAttribut(ATTR_DEF_COMPANY),false);
	$l_company_name=$l_company->strAttribut(ATTR_DEF_NAME);
	if ( $l_company_name == '- ERROR -' ) $l_company_name="";
	// add popup for detail
	if ( $l_company_name !="")
	  {
	    $l_company_name=sprintf("<A HREF=\"javascript:showfiche('%s','%s')\">%s  - %s</A>",
				    $_REQUEST['PHPSESSID'],
				    $contact->strAttribut(ATTR_DEF_COMPANY),
				    $contact->strAttribut(ATTR_DEF_COMPANY),
				    $l_company_name
				    );
	  }
	$r.="<TR>";
	$r.='<TD><A HREF="'.$url."&sa=detail&f_id=".$contact->id."\">".$contact->strAttribut(ATTR_DEF_QUICKCODE).
            "</A></TD>";
	$r.="<TD>".$contact->strAttribut(ATTR_DEF_NAME)."</TD>";
	$r.="<TD>".$contact->strAttribut(ATTR_DEF_TEL)."</TD>";
	$r.="<TD>".$contact->strAttribut(ATTR_DEF_EMAIL)."</TD>".
            "<TD> ".$contact->strAttribut(ATTR_DEF_FAX)."</TD>".
            "<TD> ".$l_company_name. "</TD>".
            '<TD> <A href="?p_action=suivi_courrier&qcode='.$contact->strAttribut(ATTR_DEF_QUICKCODE).'&url='.$back_url.'">Courrier</A></TD>';

	$r.="</TR>";

      }
      $r.="</TABLE>";
      $r.=$bar;
      return $r;
    }

}