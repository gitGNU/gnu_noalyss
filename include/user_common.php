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

include_once("postgres.php");

/* function GetTvaRate
 **************************************************
 * Purpose : Return the rate of the p_tva_id
 *        
 * parm : 
 * -  p_cn database connection
 *	- p_tva_id tva.tva_id
 * gen :
 *	- none
 * return:
 *       an array containing the rate and the label
 *       the tva rate or null if a problem occured
 */

function GetTvaRate($p_cn,$p_tva_id) {
$Res=pg_exec($p_cn,"select tva_id,tva_rate,tva_label from tva_rate where tva_id=".$p_tva_id);
if (pg_NumRows($Res) == 0 ) return null;

$r=pg_fetch_array($Res,0);
return $r;

}
/* function ComputeVat($p_cn,	$a_good,$a_quant,$p_type);
 **************************************************
 * Purpose : Compute the vat,
 *           the fiche.f_id are in a_fiche
 *           the quantity in a_quant
 *           
 *        
 * parm : 
 *	- database connection
 *  - fiche
 *  - quantity
 *  - p_type ATTR_DEF_PRIX_VENTE ATTR_DEF_PRIX_ACHAT
 * gen :
 *	-
 * return: array
 *       a[tva_id] =  amount vat
 */
function ComputeVat($p_cn,	$a_fiche,$a_quant,$p_type) {
echo_debug("ComputeVat $a_fiche $a_quant $p_type");
// foreach goods 
for ( $i=0 ; $i < sizeof($a_fiche);$i++) {
// Get the tva_id
$tva_id=GetFicheAttribut($p_cn,$a_fiche[$i],ATTR_DEF_TVA);

	// for each fiche find the tva_rate and tva_id
	$a_vat=GetTvaRate($p_cn,$tva_id);
	
	// Get the attribut price of the card(fiche)
	$amount=GetFicheAttribut($p_cn,$a_fiche[$i],$p_type);
	
	if ( $a_vat != null ) {
		$a=$a_vat['tva_id'];
		$vat_amount=$amount*$a_vat['tva_rate']*$a_quant[$i];
		$r[$a]=isset ( $r[$a] )?$r[$a]+$vat_amount:$vat_amount;
		} else {
			echo_error("Not vat here in ComputeVat !");
			return 0;
		}
	}
	echo_debug(" return $r");
	return $r;


}



/* function GetTvaPoste($p_cn,$tva_id,$p_type);
 **************************************************
 * Purpose : Get the account of tva_rate.tva_poste
 *        return the credit or the debit account
 * parm : connection
 *	-     tva_rate.tva_id
 *        type ( d or credit)
 * gen :
 *	-
 * return:
 *        return the credit or the debit account
 *        null if error
 */
function GetTvaPoste($p_cn,$p_tva_id,$p_cred) {
	$Res=ExecSql($p_cn,"select tva_poste from tva_rate where tva_id=$p_tva_id");
	if ( pg_NumRows($Res) == 0 ) return null;
	$a=pg_fetch_array($Res,0);
	list ($deb,$cred)=split(",",$a['tva_poste']);
	if ( $p_cred=='c' ) return $cred;
	if ($p_cred=='d') return $deb;
	echo_error ("Invalid $p_cred in GetTvaRate");
	return null;
}




/* function InsertJrnx($p_cn,$p_type,$p_user,$p_jrn,$p_poste,$p_date,$p_amount,$p_grpt,$p_periode);
 **************************************************
 * Purpose : Insert into the table Jrn
 *        
 * parm : 
 *	- $p_cn database connection
 *  - $p_type debit or credit
 *  - $p_user the current user
 *  - $p_jrn the current 'journal' (folder)
 *  - $p_poste the account
 *  - $p_date
 *  - $p_amount amount to insert
 *  - $p_periode the concerned periode
 * gen :
 *	- none
 * return:
 *   - nothing
 */

function InsertJrnx($p_cn,$p_type,$p_user,$p_jrn,$p_poste,$p_date,$p_amount,$p_grpt,$p_periode)
{
	echo_debug ("InsertJrnx param 
	    type = $p_type p_user $p_user p_date $p_date p_poste $p_poste p_amount $p_amount p_grpt = $p_grpt p_periode = $p_periode");
    $debit=($p_type=='c')?'false':'true';
	$sql=sprintf("insert into jrnx (j_date,j_montant, j_poste,j_grpt, j_jrn_def,j_debit,j_tech_user,j_tech_per)
			values (to_date('%s','DD.MM.YYYY'),%.2f,%d,%d,%d,%s,'%s',%d)",
			$p_date,$p_amount,$p_poste,$p_grpt,$p_jrn,$debit,$p_user,$p_periode);
	echo_debug("InsertJrnx $sql");
	$Res=ExecSql($p_cn,$sql);

}
/* function InsertJrn($p_cn,$p_date,$p_jrn,$p_comment,$p_amount,$p_grpt,$p_periode);
 **************************************************
 * Purpose : Insert into the table Jrnx
 *        
 * parm : 
 *	- $p_cn database connection
 *  - $p_date date
 *  - $p_jrn the current 'journal' (folder)
 *  - $p_poste the account
 *  - $p_amount amount to insert
 *  - $p_periode the concerned periode
 * gen :
 *	- none
 * return:
 *   - nothing
 */

function InsertJrn($p_cn,$p_date,$p_echeance,$p_jrn,$p_comment,$p_amount,$p_grpt,$p_periode)
{
	echo_debug ("InsertJrn param 
	    p_date $p_date p_poste $p_comment p_amount $p_amount p_grpt = $p_grpt p_periode = $p_periode p_echeance = $p_echeance");
	if ( $p_echeance == "" or $p_echeance==null) {
		$p_echeance='null';
	} else {
		$p_echeance=sprintf("to_date('%s','DD.MM.YYYY')",$p_echeance);
	}
	$sql=sprintf("insert into jrn (jr_def_id,jr_montant,jr_comment,jr_date,jr_ech,jr_grpt_id,jr_tech_per)
	         values ( %d,%.2f,'%s',to_date('%s','DD.MM.YYYY'),%s,%d,%d)",
					 $p_jrn, $p_amount,$p_comment,$p_date,$p_echeance,$p_grpt,$p_periode);
	echo_debug("InsertJrn $sql");
	$Res=ExecSql($p_cn,$sql);				 
	return GetSequence($p_cn,'s_jrn');
}
/* function ListJrn($p_cn,$p_jrn,$p_wherel)
 **************************************************
 * Purpose : show all the lines of the asked jrn
 *        
 * parm : 
 *	- $p_cn database connection
 *  - $p_jrn jrn_id jrn.jrn_def_id
 *  - $p_sql the sql query (where clause)
 * gen :
 *	- none
 * return:
 * 
 */
function ListJrn($p_cn,$p_jrn,$p_where="")
{
  // TODO Show modify button but only for  no centralized operations
  //TODO add a print button but only if type of jrn is VEN !!

$Res=ExecSql($p_cn,"select jr_id	,
			jr_montant,
			jr_comment,
			jr_ech,
			jr_date,
			jr_grpt_id,
			jr_rapt,
			jr_internal,
			jrn_def_id,
			jrn_def_name,
			jrn_def_ech,
			jrn_def_type 
		       from 
			jrn join jrn_def on jrn_def_id=jr_def_id 
                       $p_where 
			 order by jr_date");
$r="";
$r.=JS_VIEW_JRN_DETAIL;
$Max=pg_NumRows($Res);
if ($Max==0) return "No row selected";
$r.="<TABLE>";
$l_sessid=(isset($_POST['PHPSESSID']))?$_POST['PHPSESSID']:$_GET['PHPSESSID'];
 $r.="<tr>";
 $r.="<th> Date </th>";
 $r.="<th> Echéance </th>";
 $r.="<th> Description</th>";
 $r.="<th> Montant </th>";
 $r.="<th>Op. Concernée</th>";
 $r.="</tr>";

for ($i=0; $i < $Max;$i++) {
	$row=pg_fetch_array($Res,$i);
	
	if ( $i % 2 == 0 ) $tr='<TR class="odd">'; 
		else $tr='<TR>';
	$r.=$tr;
// date
	$r.="<TD>";
	$r.=$row['jr_date'];
	$r.="</TD>";
	// echeance
	$r.="<TD>";
	$r.=$row['jr_ech'];
	$r.="</TD>";
	
// comment
	$r.="<TD>";
 	$r.=sprintf('<input TYPE="BUTTON" VALUE="%s" onClick="viewDetail(\'%s\',\'%s\')"> %s',
		    "Détail",$row['jr_grpt_id'],$l_sessid,$row['jr_comment']);
	$r.="</span>";
	$r.="</TD>";

	
// Amount
	$r.="<TD>";
	$r.=$row['jr_montant'];
	$r.="</TD>";
	
// Rapt
	$r.="<TD>";
	$r.='<A HREF="">';
	$r.=$row['jr_rapt'];
	$r.="</A>";
	$r.="</TD>";
// TODO Add print

// end row
	$r.="</tr>";
	
	}
$r.="</table>";
return $r;
}
