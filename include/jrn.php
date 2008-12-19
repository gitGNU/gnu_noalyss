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
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/* $Revision$ */

/*! \file
 * \brief work with the ledger
 */
require_once('class_fiche.php');
require_once ('class_gestion_sold.php');
require_once ('class_gestion_purchase.php');
require_once ('class_anc_plan.php');
require_once ('class_anc_operation.php');
require_once ('class_acc_ledger.php');
require_once ('class_acc_operation.php');
require_once ('class_acc_ledger_info.php');

/*! 
 * \brief  Display the form to UPDATE account operation in the expert view
 *          
 * \param $p_cn database connection
 * \param $jr_id pk of jrn
 * \param mode 1 editable, 0 for CA
 *
 * \return none
 *
 *
 */ 
function ShowOperationExpert($p_cn,$p_jr_id,$p_mode=1)
{

  echo_debug('jrn.php',__LINE__,"function UpdateJrn");
  // own
  $own=new own($p_cn);
  $gDossier=dossier::id();
  $l_array=get_dataJrnJrId($p_cn,$p_jr_id);
  if ( $l_array == null ) {
    echo_error ("Not data found for UpdateJrn p_jr_id = $p_jr_id");
    return ;
  }
  echo_debug('jrn.php',__LINE__,$l_array);
  // Javascript
  $r=JS_VIEW_JRN_MODIFY;

  // Build the form
  $col_vide="<TD></TD>";
  $disable=($p_mode==0)?"disabled":"";
  $str_dossier=dossier::get();
  $count=0;
  for ( $i =0 ; $i < sizeof($l_array); $i++) {
    $content=$l_array[$i] ;

      // for the first line
      if ( $i == 0 ) {
		$r.="<TABLE>";
		$r.="<TR>";
		// Date
		$r.="<TD>";
		$r.=$content['jr_date'];
		$r.="</TD>";
		// for upload document we need the grpt_id   
		$r.='<Input type="hidden" name="jr_grpt_id" value="'.$content['jr_grpt_id'].'">';
		
		// comment can be changed
		$r.="<TD>";
		$comment=new widget("text");
		$comment->table=0;
		$comment->name="comment";
		$comment->readonly=($p_mode==0)?true:false;
		$comment->value=$content['jr_comment'];
		$comment->size=40;
		$r.=$comment->IOValue();
		$r.="</TD>";
		
	// Internal
		$r.="<TD>";
		$r.=$content['jr_internal'];
		$r.="</TD>";

		if ( $content['jrn_def_type'] == 'ACH' or 
			 $content['jrn_def_type'] == 'VEN' )
		  {
			// Is Paid
			$r.="<TD>";
			$check=( $content['jr_rapt'] != null )?"CHECKED":"UNCHECKED";
			$r.='<TD>Payé <INPUT TYPE="CHECKBOX"'.$disable.' name="is_paid" '.$check.'></TD>';
		  }
		$r.="</TR>";
		$r.="</TABLE>";
		$r.="<TABLE>";
		$r.="<tr>";
		$r.='<th colspan="2">Postes</th>';
		$r.='<th>Description</th>';
		$r.='<th>D&eacute;bit</th>';
		$r.='<th>Cr&eacute;dit</th>';

		$own = new Own($p_cn);
		$r.="</tr>";
      }
      $r.="<TR>";
      if ( $content['j_debit'] == 'f' ) $r.=$col_vide;
      //      $r.="<TD>".$content['j_debit']."</td>";
      
      $r.="<TD>".$content['j_poste']."</td>";
      if ( $content['j_debit'] == 't' ) $r.=$col_vide;
      $qc=($content['j_qcode'] != "")?"  [".$content['j_qcode']."]":"";
      if ( $content['j_text'] == '') 
	$r.="<TD>".$qc.h($content['vw_name'])."</td>";
      else
	$r.="<TD>".$qc.h($content['j_text'])."</td>";
      if ( $content['j_debit'] == 'f' ) $r.=$col_vide;
      $r.="<TD>".$content['j_montant']."</td>";
      if ( $content['j_debit'] == 't' ) $r.=$col_vide;
	  //-- add ca 
	  //
	  if ( $own->MY_ANALYTIC != "nu" && ereg("^[6,7]+",$content['j_poste']))
	    {
	      
	      $r.=display_table_ca($p_cn,$count,$content['j_id'],$own,$p_mode,$content['j_montant']);
		  $count++;
	    }
	  
	  $r.="</TR>";

      //    }//     foreach ($l_array[$i]  as $value=>$content) 
  }// for ( $i =0 ; $i < sizeof($l_array); $i++) 
  if ( $p_mode == 1) {
    $file=new widget("file");
    $file->table=1;
	//doc
	if ( $content['jr_pj_name'] != "")
	  $r.='<TD>Effacer PJ <INPUT TYPE="CHECKBOX" name="to_remove" ></TD>';
  }
  $r.="<TD>".sprintf('<A class="detail" HREF="show_pj.php?jrn=%s&jr_grpt_id=%s&%s&PHPSESSID=%s">%s</A>',
		     $content['jr_id'],
		     $content['jr_grpt_id'],
		     $str_dossier,
		     $_REQUEST['PHPSESSID'],
		     $content['jr_pj_name']

		     )."</TD>";
    $r.="</TR></TABLE>";

	if ( $p_mode == 1 ) {
	  $r.="<hr>";
	  $r.= "<table>"; 
	  $r.="<TR>".$file->IOValue("pj","","Pièce justificative")."</TR>";
	  $r.="</table>";
	}
	$r.="<hr>";
	
	$r.="</table>";
	$r.="Total ".$content['jr_montant']."<br>";
	if ( $content['jrn_def_type'] == 'VEN' ) {
	  /* count the number of additionnal info */
	  $acc_jrn_info=new Acc_Ledger_Info($p_cn);
	  $acc_jrn_info->set_jrn_id($p_jr_id);
	  
	  /* if additional info > 0 show them */
	  if ( $acc_jrn_info->count() > 0 ) {
	    $array=$acc_jrn_info->load_all();
	    foreach ($array as $row) {
	      if ( strpos($row->id_type,'BON_COMMANDE') ===0) {
		$r.="Num bon de commande : ".$row->ji_value.'<br>';
	      }
	      if ( strpos($row->id_type,'OTHER') ===0) {
		$r.="Autre info : ".$row->ji_value.'<br>';
	      }
	      
	    }
	  }
	}
	if ( $p_mode==1) {
	// show all the related operation
	$a=GetConcerned($p_cn,$content['jr_id']);
	$sessid=$_REQUEST["PHPSESSID"];	  
	if ( $a != null ) {
      $r.="<b>Operation concernée</b> <br>";

	  $r.= '<div style="margin-left:30px;">';
	  foreach ($a as $key => $element) {
		$operation=new Acc_operation($p_cn);
		$operation->jr_id=$element;
		$r.=sprintf ('%s <INPUT TYPE="BUTTON" VALUE="Détail" onClick="modifyOperation(\'%s\',\'%s\',%d)">', 
					 $operation->get_internal($p_cn,$element),
					 $element,
					 $sessid,
					 $gDossier);
		$r.=sprintf('<INPUT TYPE="button" value="Efface" onClick="dropLink(\'%s\',\'%s\',\'%s\',%d)"><BR>',
					$content['jr_id'],$element,$sessid,$gDossier);
	  }//for
	  $r.= "</div>";
	}// if ( $a != null ) 
  
	$search='<INPUT TYPE="BUTTON" VALUE="Cherche" OnClick="SearchJrn(\''.$sessid."',".$gDossier.",'rapt','".$content['jr_montant']."')\">";
	
	$r.= '<H2 class="info">rapprochement </H2> 
       <INPUT TYPE="TEXT" name="rapt" value="">'.$search;
	
	} // if mode == 1
	
  $r.='<input type="hidden" name="jr_id" value="'.$content['jr_id'].'">';
  return $r;
}

/*! 
 * \brief  Display the form to UPDATE account operation in the user view
 *          
 * \param $p_cn database connection
 * \param $jr_id pk of jrn
 * \param mode 1 editable, 0 for CA
 * \return none
 *
 *
 */ 
function ShowOperationUser($p_cn,$p_jr_id,$p_mode=1)
{

  echo_debug('jrn.php',__LINE__,"function ShowOperationUser($p_cn,$p_jr_id,$p_mode) ");
  $gDossier=dossier::id();  
  $l_array=get_dataJrnJrIdUser($p_cn,$p_jr_id);
  $str_dossier=dossier::get();
  /* if the operation doesn't exist in the quant_xxx table then we
   *  show the expert view
   */

  if ( $l_array == null || empty($l_array) == true) {
    // If the  operation is not in quant_sold or quant_purchase 
    // because those tables comes later
    $r=ShowOperationExpert($p_cn,$p_jr_id,$p_mode);
    return $r;
  }

  // own
  $own=new own($p_cn);

  echo_debug('jrn.php',__LINE__,$l_array);
  // Javascript
  $r=JS_VIEW_JRN_MODIFY;

  // Build the form
  $col_vide="<TD></TD>";
  

  $content=$l_array[0] ;
  // for the first line
  $internal=$content['jr_internal'];

  $r.='<TABLE>';
  $r.="<TR>";
  // Date
  $r.='<TD colspan="4">';
  $r.=$content['jr_date'];
  $disable=($p_mode==0)?"disabled":"";

  // for upload document we need the grpt_id   
  $r.='<Input type="hidden" name="jr_grpt_id" value="'.$content['jr_grpt_id'].'">';
  
  // comment can be changed
  $comment=new widget("text");
  $comment->table=0;
  $comment->name="comment";
  $comment->readonly=($p_mode==0)?true:false;
  $comment->value=$content['jr_comment'];
  $comment->size=40;
  $r.=$comment->IOValue();
  

  
  // Internal

  $r.=$content['jr_internal'];
  $r.="</TD>";
		
  // Is Paid
  $r.="<TD>";
  $check=( $content['jr_rapt'] != null )?"CHECKED":"UNCHECKED";
  $r.='<TD>Payé <INPUT TYPE="CHECKBOX" '.$disable.' name="is_paid" '.$check.'></TD>';
  
  $r.="</TR>";
  
  echo_debug(__FILE__.":".__LINE__."jrn_Def_type =  ".$content['jrn_def_type']);
  // for others lines
  
  // for purchase ledger
  if ( $content['jrn_def_type'] == 'ACH' )
	{
	  $r.='<tr><td><table>';
	  echo_debug(__FILE__.":".__LINE__." content['qp_supplier'] ".$content['qp_supplier']);
	  $client=new fiche($p_cn,$content['qp_supplier']);
	  $r.="Client : ".$client->getName();
	  echo_debug(__FILE__,__LINE__,$content);	  
	  
	  /* now we get the different lines for this operation thanks */
	  /* the qp_internal == jr_internal */
	  $r.='<tr>';
	  $r.='<th>Nom</th>';
	  $r.='<th>PU</th>';
	  $r.='<th>Quantit&eacute;</th>';
	  $r.='<th> Dep. priv. </th>';
	  $r.='<th>tva</th>';
	  $r.='<th>tva</th>';
	  $r.='<th>non ded.</th>';
	  $r.='<th>tva nd</th>';
	  $r.='<th>tva d impot</th>';
	  $r.='<th>total htva</th>';
	  $r.='<th>total tvac</th>';

	  $r.='</tr>';
	  $object=new gestion_purchase($p_cn);
	  $object->qp_internal=$internal;
	  $array=$object->get_list();
	  $tot_tva=0.0;
	  $tot_amount=0.0;
	  $tot_nd=0.0;
	  $tot_tva_nd=0.0;
	  $tot_tva_nd_recup=0.0;
	  $tot_dep_priv=0.0;
	  $i=0;
	  $i_march=0;
	  foreach ($array as $row) {

		$fiche=new fiche($p_cn,$row->qp_fiche);
		// compute sum
		$tot_tva+=$row->qp_vat;
		$tot_nd+=$row->qp_nd_amount;
		$tot_tva_nd+=$row->qp_nd_tva;
		$tot_tva_nd_recup+=$row->qp_nd_tva_recup;
		$tot_amount+=$row->qp_price;
		$tot_dep_priv+=$row->qp_dep_priv;

		//		$hid_jid=new widget("hidden","","p_jid_".$row->j_id,$row->j_id);
		$r.=($i%2==0)?"<tr class=\"odd\">":'<tr>';		$i++;
		$pu=0.0;
		if ( $row->qp_price != 0.0 && $row->qp_price != 0 ) 
		  $pu=round(($row->qp_nd_amount+$row->qp_price)/$row->qp_quantite,2);
		$r.='<td> '.$fiche->strAttribut(ATTR_DEF_NAME).'</td>';
		$r.='<tD>'.$pu.'</td>';
		$r.='<td align="right">'.$row->qp_quantite.'</td>';
		$r.='<td align="right">'.$row->qp_dep_priv.'</td>';
		$r.='<td align="right">'.$row->qp_vat.'</td>';
		$r.='<td>'.$row->tva_label.'</td>';
		$r.='<td>'.$row->qp_nd_amount.'</td>';
		$r.='<td>'.$row->qp_nd_tva.'</td>';
		$r.='<td>'.$row->qp_nd_tva_recup.'</td>';
		$r.='<td align="right">'.sprintf("% 12.2f",$row->qp_price).'</td>';
		$r.='<td align="right">'.sprintf("% 12.2f",$row->qp_vat+$row->qp_nd_amount+$row->qp_nd_tva+$row->qp_nd_tva_recup+$row->qp_price).'</td>';
		//-- add ca 
		//

		$content['j_poste']=$fiche->strAttribut(ATTR_DEF_ACCOUNT);
		//		echo "j_poste= ".$content['j_poste'];
		if ( $own->MY_ANALYTIC != "nu" && ereg("^[6,7]+",$content['j_poste']))
		  {
		    $r.=display_table_ca($p_cn,$i_march,$row->j_id,$own,$p_mode,$tot_tva);
			$i_march++;
		  }


	  }
	  // display sum
	  $r.='<tr><td colspan="8"><hr style="color:blue;"></td></tr>';
	  $r.='<tr  style="font-size:13px;color:green;">'.
		'<td colspan="7">Total HTVA</td>'.
		'<td>'.sprintf('% 12.2f',$tot_amount)."</td>".
		"</tr>";
	if ( $tot_tva != 0 ) 
	  $r.='<tr  style="font-size:13px;color:green;">'.
		'<td colspan="7">Total TVA</td>'.
		'<td>'.sprintf('%12.2f',$tot_tva)
		."</td>".
		"</tr>";
	if ( $tot_nd !=0 )
	  $r.='<tr  style="font-size:13px;color:green;">'.
		'<td colspan="7">Total nd </td>'.
		'<td style="font-size:13px;color:green;">'.sprintf('%12.2f',$tot_nd)."</td>".
		"</tr>";
	if ( $tot_tva_nd !=0 )
	  $r.='<tr  style="font-size:13px;color:green;">'.
		'<td colspan="7">Total tva nd</td>'.
		'<td style="font-size:13px;color:green;">'.sprintf('%12.2f',$tot_tva_nd)."</td>".
		"</tr>";
	if ( $tot_tva_nd_recup !=0 )
	  $r.='<tr  style="font-size:13px;color:green;">'.
		'<td colspan="7">Total tva nd recup. par impot</td>'.
		'<td style="font-size:13px;color:green;">'.sprintf('%12.2f',$tot_tva_nd_recup)."</td>".
		"</tr>";
	if ( $tot_dep_priv !=0 )
	  $r.='<tr  style="font-size:13px;color:green;">'.
		'<td colspan="7">Total tva nd recup. par impot</td>'.
		'<td style="font-size:13px;color:green;">'.sprintf('%12.2f',$tot_dep_priv)."</td>".
		"</tr>";

	  $r.='<tr  style="font-size:13px;color:green;">'.
		'<td colspan="7">Total </td>'.
		'<td style="font-size:13px;color:green;">'.sprintf('%12.2f',$tot_dep_priv+$tot_amount+$tot_tva+$tot_tva_nd_recup+$tot_nd+$tot_tva_nd)."</td>".
		"</tr>";

	  $r.= '</table></td></tr>';

	  
	}
  
  // for selling ledger
  if ( $content['jrn_def_type'] == 'VEN' )
	{
	  echo_debug(__FILE__.":".__LINE__." content['qs_client'] ".$content['qs_client']);
	  $client=new fiche($p_cn,$content['qs_client']);
	  $r.="Client : ".$client->getName();
	  echo_debug(__FILE__,__LINE__,$content);	  
	  
	  /* now we get the different lines for this operation thanks */
	  /* the qs_internal == jr_internal */
	  $r.='<tr>';
	  $r.='<th>Nom</th>';
	  $r.='<th>PU</th>';
	  $r.='<th>Quantit&eacute;</th>';
	  $r.='<th>tva</th>';
	  $r.='<th>code tva</th>';
	  $r.='<th>prix</th>';

	  $own = new Own($p_cn);

	  $r.='</tr>';
	  $object=new gestion_sold($p_cn);
	  $object->qs_internal=$internal;
	  $array=$object->get_list();
	  $tot_tva=0.0;
	  $tot_amount=0.0;
	  $i=0;
	  $i_march=0;
	  foreach ($array as $row) {
	    $fiche=new fiche($p_cn,$row->qs_fiche);
	    $tot_tva+=$row->qs_vat;
	    $tot_amount+=$row->qs_price;
	    $r.=($i%2==0)?"<tr class=\"odd\">":'<tr>';		$i++;
	     $pu=0.0;
                if ( $row->qs_price != 0.0 && $row->qs_price != 0 ) $pu=round($row->qs_price/$row->qs_quantite,2);
	    $r.=($i%2==0)?"<tr class=\"odd\">":'<tr>';		$i++;
	    $r.='<td> '.$fiche->strAttribut(ATTR_DEF_NAME).'</td>';
	    $r.='<td align="right">'.$pu.'</td>';
	    $r.='<td align="right">'.$row->qs_quantite.'</td>';
	    $r.='<td align="right">'.$row->qs_vat.'</td>';
	    $r.='<td align="center">'.$row->tva_label.'</td>';
	    $r.='<td align="right">'.$row->qs_price.'</td>';
	    //-- add ca 
	    //
	    
	    $content['j_poste']=$fiche->strAttribut(ATTR_DEF_ACCOUNT);
	    echo_debug(__FILE__.':'.__LINE__,'$content["j_poste"]',$content['j_poste']);
	    
	    //		echo "j_poste= ".$content['j_poste'];
	    if ( $own->MY_ANALYTIC != "nu" && ereg("^[6,7]+",$content['j_poste']))
	      {
		echo_debug(__FILE__.':'.__LINE__,'showUser VEN $content',$content);
		echo_debug(__FILE__.':'.__LINE__,'showUser VEN $row ',$row);
		$r.=display_table_ca($p_cn,$i_march,$row->j_id,$own,$p_mode,$tot_amount);
	      }
	    $i_march++;


	  }
	  $r.="<tr>".
		"<td>"."</td>".
		"<td>"."</td>".
		"<td>"."</td>".
		"<td>Total HTVA</td>".
		'<td style="font-size:13px;color:green;">'.sprintf('% 12.2f',$tot_amount)."</td>".
		"</tr>";
	  $r.="<tr>".
		"<td>"."</td>".
		"<td>"."</td>".
		"<td>"."</td>".
		"<td>Total TVA</td>".
		'<td style="text-justify:right;font-size:13px;color:green;">'.sprintf('%12.2f',$tot_tva)
		."</td>".
		"</tr>";
	  $r.="<tr>".
		"<td>"."</td>".
		"<td>"."</td>".
		"<td>"."</td>".
		"<td>Total </td>".
		'<td style="font-size:13px;color:green;">'.sprintf('%12.2f',$tot_amount+$tot_tva)."</td>".
		"</tr>";

	}
  $r.="</TABLE>";
      
  $file=new widget("file");
  $file->table=1;
  //doc
  if ( $p_mode ==1 && $content['jr_pj_name'] != "") 
	  $r.='<tr><TD>Effacer Pj <INPUT TYPE="CHECKBOX" name="to_remove" ></TD>';
  $r.="<TD>".sprintf('<A class="detail" HREF="show_pj.php?jrn=%s&jr_grpt_id=%s&'.$str_dossier.'&PHPSESSID=%s">%s</A>',
		     $content['jr_id'],
		     $content['jr_grpt_id'],
		     $_REQUEST['PHPSESSID'],
		     $content['jr_pj_name'])."</TD>";
  $r.="</TR></TABLE>";
  if ( $content['jrn_def_type'] == 'VEN' ) {
    /* count the number of additionnal info */
    $acc_jrn_info=new Acc_Ledger_Info($p_cn);
    $acc_jrn_info->set_jrn_id($p_jr_id);

    /* if additional info > 0 show them */
    if ( $acc_jrn_info->count() > 0 ) {
      $array=$acc_jrn_info->load_all();
      foreach ($array as $row) {
	if ( strpos($row->id_type,'BON_COMMANDE') ===0) {
	  $r.="Num bon de commande : ".$row->ji_value.'<br>';
	}
	if ( strpos($row->id_type,'OTHER') ===0) {
	  $r.="Autre info : ".$row->ji_value.'<br>';
	}

      }
    }
  }
  $r.="<hr>";
  if ( $p_mode == 1 ) {
	$r.= "<table>"; 

	$r.="<TR>".$file->IOValue("pj","","Pièce justificative")."</TR>";
	$r.="</table>";
	$r.="<hr>";
	
	$r.="</table>";
	$r.="Total ".$content['jr_montant']."<br>";
	// show all the related operation
	$a=GetConcerned($p_cn,$content['jr_id']);
	$sessid=$_REQUEST["PHPSESSID"];	  
  
	if ( $a != null ) {
      $r.="<b>Operation concernée</b> <br>";
      
      $r.= '<div style="margin-left:30px;">';
      foreach ($a as $key => $element) {
		$operation=new Acc_operation($p_cn);
		$operation->jr_id=$element;
		$r.=sprintf ('%s <INPUT TYPE="BUTTON" VALUE="Détail" onClick="modifyOperation(\'%s\',\'%s\',%d)">', 
					 $operation->get_internal(),
					 $element,
					 $sessid,
					 $gDossier);
		$r.=sprintf('<INPUT TYPE="button" value="Efface" onClick="dropLink(\'%s\',\'%s\',\'%s\',%d)"><BR>',
					$content['jr_id'],$element,$sessid,$gDossier);
	  }//for
	  $r.= "</div>";
  }// if ( $a != null )
  
  $search='<INPUT TYPE="BUTTON" VALUE="Cherche" OnClick="SearchJrn(\''.$sessid."',".$gDossier.",'rapt','".$content['jr_montant']."')\">";

  $r.= '<H2 class="info">rapprochement </H2> 
       <INPUT TYPE="TEXT" name="rapt" value="">'.$search;
  } // if p_mode==1
  $r.='<input type="hidden" name="jr_id" value="'.$content['jr_id'].'">';
  //  echo $r;
  return $r;
}

/*! 
 * \brief  Vue des écritures comptables
 * 
 * parm : 
 *	- p_dossier,
 *      - p_user,
 *      - p_jrn
 *      - p_url for modif
 *      - array
 * gen :
 *	-
 * return:
 *	-
 *
 */ 
function ViewJrn($p_dossier,$p_user,$p_jrn,$p_url,$p_array=null) {
  echo_debug('jrn.php',__LINE__,"function ViewJrn($p_dossier,$p_user,$p_jrn,$p_array=null) ");

  $db=sprintf("dossier%d",$p_dossier);
  $cn=DbConnect($p_dossier);
  $oJrn=new Acc_Ledger($cn,$p_jrn);
  $l_prop=$oJrn->get_propertie();
  echo "<H2 class=\"info\">".$l_prop['jrn_def_name']."( ".$l_prop['jrn_def_code'].")"."</H2>";
  if ( $p_array == null) {
    include_once("preference.php");
    $l_periode=GetUserPeriode($cn,$p_user);
    $Res=ExecSql($cn,"select jr_id,j_id,jr_internal,to_char(j_date,'DD.MM.YYYY') as j_date,
                       j_montant,j_poste,pcm_lib,j_grpt,j_debit,j_centralized,j_tech_per,
                       pcm_lib
                   from jrnx inner join tmp_pcmn on j_poste=pcm_val
                             inner join jrn on jr_grpt_id=j_grpt
                   where 
                   j_jrn_def=$p_jrn and j_tech_per=$l_periode
                   order by j_id,j_grpt,j_debit desc");
  } else {
    // Construction Query 
    foreach ( $p_array as $key=>$element) {
      ${"l_$key"}=$element;
      echo_debug ("l_$key $element");
    }
    $sql="select j_id,to_char(j_date,'DD.MM.YYYY') as j_date,j_montant,j_poste,
                 pcm_lib,j_grpt,jr_id,j_debit,j_centralized,j_tech_per,jr_internal
                   from jrnx inner join tmp_pcmn on j_poste=pcm_val
                        inner join jrn on jr_grpt_id=j_grpt
                   where 
                   j_jrn_def=$p_jrn";
    $l_and="and ";
    if ( ereg("^[0-9]+$", $l_s_montant) || ereg ("^[0-9]+\.[0-9]+$", $l_s_montant) ) {
    $sql.=" and jr_montant $l_mont_sel $l_s_montant";
    }
    if ( isDate($l_date_start) != null ) {
      $sql.=$l_and." j_date >= to_date('".$l_date_start."','DD.MM.YYYY')";
    }
    if ( isDate($l_date_end) != null ) {
      $sql.=$l_and." j_date <= to_date('".$l_date_end."','DD.MM.YYYY')";
    }
    $l_s_comment=FormatString($l_s_comment);
    if ( $l_s_comment != null ) {
      $sql.=$l_and." upper(jr_comment) like upper('%".$l_s_comment."%') ";
    }

    $sql.=" order by j_id,j_grpt,j_debit desc";
    echo_debug ("search query is $sql");
    $Res=ExecSql($cn,$sql);
  }
  $MaxLine=pg_NumRows($Res);
  if ( $MaxLine == 0 ) return;
  $col_vide="<TD></TD>";
  echo '<TABLE ALIGN="center">';
  $l_id=0;
  for ( $i=0; $i < $MaxLine; $i++) {
    $l_line=pg_fetch_array($Res,$i);
    if ( $l_line['j_debit'] == 't' ) {
      echo '<TR style="background-color:lightblue;">';
    }
    else {
      echo '<TR>';
    }
    if ( $l_id == $l_line['j_grpt'] ) {
      echo $col_vide.$col_vide.$col_vide;
    } else {
      echo "<TD>";
      echo $l_line['j_date'];
      echo "</TD>";
      
      
	  echo "<TD>";
	  if ( isset ($_GET["PHPSESSID"])  ) {
	    $sessid=$_GET["PHPSESSID"];
	  } else {
	    $sessid=$_POST["PHPSESSID"];
	  }

	  list($z_type,$z_num,$num_op)=split("-",$l_line['jr_internal']);
	  printf ('<INPUT TYPE="BUTTON" VALUE="%s" onClick="modifyOperation(\'%s\',\'%s\')">', 
		  $num_op,$sessid,$l_line['jr_id']);
	  //	  echo $num_op;
	  echo "</TD>";


	  // no modification only cancel of wrong op.
	  echo '<TD class="mlltitle">';
	  echo "<A class=\"mtitle\" HREF=$p_url?action=update&line=".$l_line['jr_id'].">";
	  echo "M";
	  echo "</A></TD>";
      $l_id=$l_line['j_grpt'];
    }
    if ($l_line['j_debit']=='f')
      echo $col_vide;
    
    echo '<TD>';
    echo $l_line['j_poste'];
    echo '</TD>';

    echo '<TD>';
    echo $l_line['pcm_lib'];
    echo '</TD>';
    echo $col_vide;

    echo '<TD>';
    echo $l_line['j_montant'];
    echo '</TD>';

    if ( $l_line['j_debit']=='t')
      echo $col_vide;

    echo "</TR>";


  }
  echo '</TABLE>';
}
/*! 
 * \brief  Get data from jrnx where p_grpt=jrnx(j_grpt)
 * 
 * parm : 
 *	- connection
 *      - p_grpt
 * gen :
 *	- none
 * return:
 *	- return array
 *
 */ 
function get_data ($p_cn,$p_grpt) {
  echo_debug('jrn.php',__LINE__,"get_data $p_cn $p_grpt");
  $Res=ExecSql($p_cn,"select 
                        to_char(j_date,'DD.MM.YYYY') as j_date,
                        j_text,
                        j_debit,
                        j_poste,
                        j_montant,
                        j_id,
                        jr_comment,
			to_char(jr_ech,'DD.MM.YYYY') as jr_ech,
                        to_char(jr_date,'DD.MM.YYYY') as jr_date,
                        jr_id,jr_internal,jr_def_id,jr_pj
                     from jrnx inner join jrn on j_grpt=jr_grpt_id where j_grpt=$p_grpt");
  $MaxLine=pg_NumRows($Res);
  if ( $MaxLine == 0 ) return null;
  $deb=0;$cred=0;
  for ( $i=0; $i < $MaxLine; $i++) {
    
    $l_line=pg_fetch_array($Res,$i);
    $l_array['op_date']=$l_line['j_date'];
    if ( $l_line['j_debit'] == 't' ) {
      $l_class=sprintf("class_deb%d",$deb);
      $l_montant=sprintf("mont_deb%d",$deb);
      $l_text=sprintf("text_deb%d",$deb);
      $l_array[$l_class]=$l_line['j_poste'];
      $l_array[$l_montant]=$l_line['j_montant'];
      $l_array[$l_text]=$l_line['j_text'];
      $l_id=sprintf("op_deb%d",$deb);
      $l_array[$l_id]=$l_line['j_id'];
      $deb++;
    }
    if ( $l_line['j_debit'] == 'f' ) {
      $l_class=sprintf("class_cred%d",$cred);
      $l_montant=sprintf("mont_cred%d",$cred);
      $l_array[$l_class]=$l_line['j_poste'];
      $l_array[$l_montant]=$l_line['j_montant'];
      $l_id=sprintf("op_cred%d",$cred);
      $l_array[$l_id]=$l_line['j_id'];
      $l_text=sprintf("text_cred%d",$deb);
      $l_array[$l_text]=$l_line['j_text'];

      $cred++;
    }
    $l_array['jr_internal']=$l_line['jr_internal'];
    $l_array['comment']=$l_line['jr_comment'];
    $l_array['ech']=$l_line['jr_ech'];
    $l_array['jr_id']=$l_line['jr_id'];
    $l_array['jr_def_id']=$l_line['jr_def_id'];
   }
  return array($l_array,$deb,$cred);
}

/*! 
 * \brief  Get data from jrn and jrnx thanks the jr_id
 * 
 *
 *     \param connection
 *     \param p_jr_id (jrn.jr_id)
 *
 *
 * \return array
 *
 */ 
function get_dataJrnJrId ($p_cn,$p_jr_id) {

  echo_debug('jrn.php',__LINE__,"get_dataJrn $p_cn $p_jr_id");
  $Res=ExecSql($p_cn,"select 
                        j_text,
                        j_debit,
                        j_poste,
                       pcm_lib,
                        j_montant,
                        jr_montant,
                        j_id,
                        jr_pj_name,
                        jr_grpt_id,
                        jr_comment,
                        to_char(jr_ech,'DD.MM.YYYY') as jr_ech,
                        to_char(jr_date,'DD.MM.YYYY') as jr_date,
                        jr_id,jr_internal, jr_rapt,jrn_def_type,
                        j_qcode
                     from 
                          jrnx 
                        inner join jrn on j_grpt=jr_grpt_id 
                        inner join jrn_def on jrn_def.jrn_def_id=jrn.jr_def_id
                        left outer join tmp_pcmn on  j_poste=pcm_val
                      where 
                         jr_id=$p_jr_id 
                      order by j_debit desc,j_id asc");
  $MaxLine=pg_NumRows($Res);
  echo_debug('jrn.php',__LINE__,"Found $MaxLine lines");
  if ( $MaxLine == 0 ) return null;

  for ( $i=0; $i < $MaxLine; $i++) {
    $line=pg_fetch_array($Res,$i);
    $array['j_debit']=$line['j_debit'];
    // is there a name from this j_qcode
    //
    if ( strlen( $line['j_qcode']) != 0 )
      {
		$fiche=new fiche($p_cn);
		$fiche->get_by_qcode($line['j_qcode']);
		
		$array['vw_name']=$fiche->getName();
      }
    else
      {
		$array['vw_name']=$line['pcm_lib'];
      }
    $array['j_text']=$line['j_text'];
    $array['jr_comment']=$line['jr_comment'];
    $array['j_montant']=$line['j_montant'];
    $array['jr_id']=$line['jr_id'];
    $array['j_id']=$line['j_id'];
    $array['jr_date']=$line['jr_date'];
    $array['jr_internal']=$line['jr_internal'];
    $array['j_poste']=$line['j_poste'];
    $array['jr_montant']=$line['jr_montant'];
    $array['jr_rapt']=$line['jr_rapt'];
    $array['jrn_def_type']=$line['jrn_def_type'];
    $array['jr_grpt_id']=$line['jr_grpt_id'];
    $array['jr_pj_name']=$line['jr_pj_name'];
    $array['j_qcode']=$line['j_qcode'];
    //    $array['']=$line[''];

    $ret_array[$i]=$array;
    }
  return $ret_array;
}
/*! 
 * \brief  Get data from quant_sold or quand_purchase for the user
 * view mode
 * 
 *
 * \param connection
 * \param p_jr_id (jrn.jr_id)
 *
 *
 * \return array or  null if there is no value
 *
 */ 
function get_dataJrnJrIdUser ($p_cn,$p_jr_id) {

  echo_debug(__FILE__.":".__LINE__."get_dataJrnJrIdUser");

  $Res=ExecSql($p_cn,"select ".
	       "*".
	       "  from quant_sold join jrn on (qs_internal=jr_internal) ". 
	       "       join jrn_def on (jr_def_id=jrn_def_id) ".
		   "   inner join jrnx on (j_grpt=jr_grpt_id )".
			   " join tmp_pcmn on (pcm_val=j_poste)".
	       " where jr_id=$p_jr_id order by jrnx.j_id");


  $MaxLine=pg_NumRows($Res);
  echo_debug('jrn.php',__LINE__,"Found $MaxLine lines");


  // if no info found in quant_sold try in quant_purchase 
  if ( $MaxLine == 0 ) 
    {
      $Res=ExecSql($p_cn,"select ".
		   "*".
		   "  from quant_purchase join jrn on (qp_internal=jr_internal)".
		   "       join jrn_def on (jr_def_id=jrn_def_id) ".
		   "   inner join jrnx on (j_grpt=jr_grpt_id) ".
				   " join tmp_pcmn on (pcm_val=j_poste)".
		   " where jr_id=$p_jr_id");
      $MaxLine=pg_NumRows($Res);
      if ( $MaxLine == 0 ) 	  return null;
      
      
      
    }


  for ( $i=0; $i < $MaxLine; $i++) {
    $line=pg_fetch_array($Res,$i);


    $array['j_debit']=$line['j_debit'];
    // is there a name from this j_qcode
    //
    if ( strlen( $line['j_qcode']) != 0 )
      {
		$fiche=new fiche($p_cn);
		$fiche->get_by_qcode($line['j_qcode']);
		
		$array['vw_name']=$fiche->getName();
      }
    else
      {
		$array['vw_name']=$line['pcm_lib'];
      }
	
    if ( isset ($line['qs_client'])) 
      {
		/* It is an invoice */
		$array['qs_client']=$line['qs_client'];
      }
    else
      {
		/* it a purchase */
		$array['qp_supplier']=$line['qp_supplier'];

      }

    $array['jr_comment']=$line['jr_comment'];
    $array['j_montant']=$line['j_montant'];
    $array['jr_id']=$line['jr_id'];
    $array['j_id']=$line['j_id'];
    $array['jr_date']=$line['jr_date'];
    $array['jr_internal']=$line['jr_internal'];
    $array['j_poste']=$line['j_poste'];
    $array['jr_montant']=$line['jr_montant'];
    $array['jr_rapt']=$line['jr_rapt'];
    $array['jrn_def_type']=$line['jrn_def_type'];
    $array['jr_grpt_id']=$line['jr_grpt_id'];
    $array['jr_pj_name']=$line['jr_pj_name'];
    //    $array['']=$line[''];
    echo_debug(__FILE__.':'.__LINE__," get_dataJrnjrIdUser ",$array);
    $ret_array[$i]=$array;
    }
  return $ret_array;
}
/*\brief Display a table with analytic accounting in modify_op.php
 *       (detail of operation )
 *\param $p_cn database cnx
 *\param $p_seq sequence (nb item)
 *\param $p_jid the concerned j_id 
 *\param $p_own object own
 *\param $p_mode readonly or writable
 *\param $p_amount amount 
 *\return string to display
 */
function display_table_ca($p_cn,$p_seq,$p_jid,$p_own,$p_mode,$p_amount) {
  echo_debug(__FILE__.':'.__LINE__,'parameter $p_cn,$p_seq,$p_jid,$p_own,$p_mode',"$p_cn,$p_seq,$p_jid,p_own,$p_mode");

  $op=new Anc_Operation($p_cn);
  $array=$op->get_by_jid($p_jid) ;
  echo_debug(__FILE__.':'.__LINE__,"display_table_ca \$p_jid",$p_jid);
  echo_debug(__FILE__.':'.__LINE__,"display_table_ca \$array",$array);
  if ( $array != null ) {
    $request=$op->to_request($array,$p_seq);
    echo_debug(__FILE__.':'.__LINE__,"request =",$request);
    return "<td>".$op->display_form_plan($request,1,$p_mode,$p_seq,$p_amount)."</td>";
  } else {
    return '<td>'.$op->display_form_plan(null,1,$p_mode,$p_seq,$p_amount)."</TD>";
  }
  return "";
  
}
?>
