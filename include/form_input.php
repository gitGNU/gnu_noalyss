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
/* $Revision$ */
include_once("preference.php");

/* function InputType
 * Purpose : Create the tag <INPUT TYPE=... Name=...>
 *        
 * parm : 
 *	- Label
 *      - The type
 *      - Name of the variable
 *      - Default Value
 *      - View_only
 *      - $p_list for the select, give the possible values item[0] is the val and item[1] 
 *        is the label
 * gen :
 *	- None
 * return: string
 */
function InputType($p_label,$p_type,$p_name,$p_value,$p_viewonly=false,$p_list=null)
{
  // View only
  if ( $p_viewonly==false) 
    return "<TD>$p_label</TD><TD>$value</TD>";

  // Input type == select
  if ( strtolower($p_type)=="select" ) {
    $r="<TD> $p_label</TD><TD>";
    $r.=sprintf('<SELECT NAME="%s">',$p_name);
    foreach ($p_list as $item) {
      $selected="";
      if ( $p_value == $item[0] ) {
	$selected="SELECTED";
      }
      $r.=sprintf('<OPTION VALUE="%s" %s>%s',
		  $item[0],
		  $selected,
		  $item[1]);
    }
    $r.="</SELECT></TD>";
    return $r;
  }

  // input type == TEXT
  if ( strtolower($p_type)=="text") {
    $r=sprintf('<TD>%s</TD><TD> <INPUT TYPE="%s" NAME="%s" VALUE="%s"></TD>',
	       $p_label,
	       $p_type,
	       $p_name,
	       $p_value);
    return $r;
  }
}

/* function FormVente
 * Purpose : Display the form for a sell
 *           Used to show detail, encode a new invoice 
 *           or update one
 *        
 * parm : 
 *	- p_array which can be empty
 *      - the "journal"
 *      - $p_user = $g_user
 *      - view_only if we cannot change it (no right or centralized op)
 * gen :
 *	-
 * return: string with the form
 */
function FormVente($p_cn,$p_jrn,$p_user,$p_array=null,$view_only=true)
{ 
  $userPref=GetUserPeriode($p_cn,$p_user);

  // The date
  list ($l_date_start,$l_date_end)=GetPeriode($p_cn,$userPref);
  
  $op_date="01".substr($l_date_start,2,8);
  echo_debug("form_input.php.FormVentep_op_date is $op_date");
  $r="";
  if ( $view_only == false) {
    $r="<FORM ACTION=user_jrn.php?action=add_invoice>";
    
  }
  $r.='<TABLE>';
  $r.='<TR>'.InputType("Date ","Text","e_date",$op_date,$view_only).'</TR>';
  include_once("fiche_inc.php");
  // Display the customer
  $fiche=GetFicheJrn($p_cn,$p_jrn,'cred',FICHE_TYPE_CLIENT);

  $r.='<TR>'.InputType("Client ","SELECT","e_client","",$view_only,$fiche).'</TR>';
  $r.="</TABLE>";

  // Start the div for item to sell
  $r.="<DIV>";
  $r.='<TABLE>';
  $fiche=GetFicheJrn($p_cn,$p_jrn,'deb',FICHE_TYPE_VENTE);
  $r.='<TR>'.InputType("Article","SELECT","e_march","",$view_only,$fiche);
  $r.=InputType("Quantité","TEXT","e_quant","1",$view_only);

  // TODO Show the price

  // TODO PERMIT multi lines

  $r.='</TR>';
  $r.="</TABLE>";

  $r.='<INPUT TYPE="SUBMIT" NAME="add_invoice" VALUE="Voir cette facture">';
  $r.="</DIV>";
  $r.="</FORM>";

  return $r;


}