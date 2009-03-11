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

/*!\file
 * \brief the class Acc_Ledger_Fin inherits from Acc_Ledger, this
 * object permit to manage the financial ledger
 */
require_once("class_idate.php");
require_once("class_icard.php");
require_once("class_ispan.php");
require_once("class_itext.php");
require_once("class_iconcerned.php");
require_once("class_ifile.php");
require_once("class_ihidden.php");
require_once("class_iselect.php");
require_once('class_acc_ledger.php');
require_once('poste.php');
require_once('ac_common.php');
require_once('class_acc_reconciliation.php');

class Acc_Ledger_Fin extends Acc_Ledger {
  /*!\brief verify that the data are correct before inserting or confirming
   *\param an array (usually $_POST)
   *\return String
   *\note return an AcException if an error occurs
   */
  public function verify($p_array) {
    extract ($p_array);
    /* check if there is a customer */
    if ( strlen(trim($e_bank_account)) == 0 ) 
      throw new AcException('Vous n\'avez pas donné de banque',11);

    /*  check if the date is valid */
    if ( isDate($e_date) == null ) {
      throw new AcException('Date invalide', 2);
    }

    /* check if the periode is closed */
    if ( $this->is_closed($periode)==1 )
      {
	throw new AcException('Periode fermee',6);
      }

    /* check that the datum is in the choosen periode */
    $per=new Periode($this->db);
    list ($min,$max)=$per->get_date_limit($periode);
    if ( cmpDate($e_date,$min) < 0 ||
	 cmpDate($e_date,$max) > 0) 
	throw new AcException('Date et periode ne correspondent pas',6);

    /* check if we are using the strict mode */
    if( $this->check_strict() == true) {
      /* if we use the strict mode, we get the date of the last
	 operation */
      $last_date=$this->get_last_date();
      if ( cmpDate($e_date,$last_date) < 0 )
	throw new AcException('Vous utilisez le mode strict la dernière operation est à la date du '
			      .$last_date.' vous ne pouvez pas encoder à une date antérieure',15);

    }



    $fiche=new fiche($this->db);
    $fiche->get_by_qcode($e_bank_account);
    if ( $fiche->empty_attribute(ATTR_DEF_ACCOUNT) == true)
      throw new AcException('La fiche '.$e_bank_account.'n\'a pas de poste comptable',8);

    /* The account exists */
    $poste=new Acc_Account_Ledger($this->db,$fiche->strAttribut(ATTR_DEF_ACCOUNT));
    if ( $poste->load() == false ){
      throw new AcException('Pour la fiche '.$e_bank_account.' le poste comptable ['.$poste->id.'] n\'existe pas',9);
    }

    /* Check if the card belong to the ledger */
    $fiche=new fiche ($this->db);
    $fiche->get_by_qcode($e_bank_account);
    if ( $fiche->belong_ledger($p_jrn,'deb') !=1 )
	throw new AcException('La fiche '.$e_bank_account.'n\'est pas accessible à ce journal',10);

    $nb=0;
    $tot_amount=0;
    //----------------------------------------
    // foreach item
    //----------------------------------------
    for ($i=0;$i< $nb_item;$i++) {
      if ( strlen(trim(${'e_other'.$i}))== 0) continue;
      /* check if amount are numeric and */
      if ( isNumber(${'e_other'.$i.'_amount'}) == 0 )
	throw new AcException('La fiche '.${'e_other'.$i}.'a un montant invalide ['.${'e_other'.$i}.']',6);

      /* compute the total */
      $tot_amount+=round(${'e_other'.$i.'_amount'},2);
      echo_debug(__FILE__,__LINE__,' tot_amount =  '.$tot_amount.' e_other'.$i.'_amount'.${'e_other'.$i.'_amount'});
      /* check if all card has a ATTR_DEF_ACCOUNT*/
      $fiche=new fiche($this->db);
      $fiche->get_by_qcode(${'e_other'.$i});
      if ( $fiche->empty_attribute(ATTR_DEF_ACCOUNT) == true)
	throw new AcException('La fiche '.${'e_other'.$i}.'n\'a pas de poste comptable',8);
      /* The account exists */
      $poste=new Acc_Account_Ledger($this->db,$fiche->strAttribut(ATTR_DEF_ACCOUNT));
      if ( $poste->load() == false ){
	throw new AcException('Pour la fiche '.${'e_other'.$i}.' le poste comptable ['.$poste->id.'n\'existe pas',9);
      }
      /* Check if the card belong to the ledger */
      $fiche=new fiche ($this->db);
      $fiche->get_by_qcode(${'e_other'.$i});
      if ( $fiche->belong_ledger($p_jrn,'cred') !=1 )
	throw new AcException('La fiche '.${'e_other'.$i}.'n\'est pas accessible à ce journal',10);
      $nb++;
    }
    if ( $nb == 0 )
      throw new AcException('Il n\'y a aucune opération',12);

    /* Check if the last_saldo and first_saldo are correct */
    if ( strlen(trim($last_sold)) != 0 && isNumber($last_sold) &&
	 strlen(trim($first_sold)) != 0 && isNumber($first_sold))
      {
	$diff=$last_sold-$first_sold;
	echo_debug(__FILE__,__LINE__,' Diff saldo = '.$last_sold.' - '.$first_sold.' = '.$diff);
	$diff=round($diff,2)-round($tot_amount,2);
	if ( $first_sold != 0 && $last_sold !=0) {
	  if ( $diff != 0 )
	    throw new AcException('Le montant de l\'extrait est incorrect'.
				  $tot_amount.' extrait '.$diff,13);
	}
      }

  }


  /*!\brief 
  *\param $p_array contains the value usually it is $_POST 
   *\return string with html code
   *\note the form tag are not  set here
   */
  function display_form($p_array=null) {
    if ( $p_array != null)
      extract ($p_array);
    $pview_only=false;
    $user = new User($this->db);
    
    // The first day of the periode 
    $pPeriode=new Periode($this->db);
    list ($l_date_start,$l_date_end)=$pPeriode->get_date_limit($user->get_periode());
    
    $op_date=( ! isset($e_date) ) ?$l_date_start:$e_date;
    $ext_no=( ! isset($ext_no) ) ?'':$ext_no;
    
    $r="";

    $r.=JS_INFOBULLE;
    $r.=JS_SEARCH_CARD;
    $r.=JS_AJAX_FICHE;
    $r.=JS_CONCERNED_OP;

    $r.=dossier::hidden();
    $r.=HtmlInput::hidden('phpsessid',$_REQUEST['PHPSESSID']);


    //$r.=HtmlInput::hidden('p_jrn',$this->id);
    $r.='<fieldset><legend>Banque, caisse </legend>';
    $r.='<TABLE  width="100%">';
    //  Date
    //--
    $Date=new IDate("e_date",$op_date);
    $Date->setReadOnly($pview_only);
    $Date->tabindex=1;
    $r.="<tr>";
    $r.='<td>Date : </td><td>'.$Date->input().'</td>';
    // Periode 
    //--
    $l_user_per=(isset($periode))?$periode:$user->get_periode();
    $l_form_per=FormPeriode($this->db,$l_user_per,OPEN);

    $r.="<td class=\"input_text\">";
    $label=HtmlInput::infobulle(3);
    $r.="Période comptable $label</td><td>".$l_form_per;
    $r.="</td>";
    $r.="</tr>";
    // Ledger (p_jrn)
    //--
    $wLedger=$this->select_ledger('FIN',2);
    if ($wLedger == null) exit ('Pas de journal disponible');

    $wLedger->label=" Journal ".HtmlInput::infobulle(2) ;
    $r.='<tr>';
    $r.=$wLedger->input();
    $r.='</tr>';

    //retrieve bank name
    $e_bank_account=( isset ($e_bank_account) )?$e_bank_account:"";
    $e_bank_account_label="";  

    // retrieve e_bank_account_label
    if ( $e_bank_account != ""  ) {
      $fBank=new fiche($this->db);
      $fBank->get_by_qcode($e_bank_account);
      $e_bank_account_label=$fBank->strAttribut(ATTR_DEF_NAME).' '.
	' Adresse : '.$fBank->strAttribut(ATTR_DEF_ADRESS).' '.
	$fBank->strAttribut(ATTR_DEF_CP).' '.
	$fBank->strAttribut(ATTR_DEF_CITY).' ';
      
    }  

    $W1=new ICard();
    $W1->readonly=$pview_only;
    $W1->label="Banque ".HtmlInput::infobulle(0);
    $W1->name="e_bank_account";
    $W1->value=$e_bank_account;
    $W1->extra='deb';  // credits
    $W1->extra2="Recherche";

    $W1->javascript=  sprintf('onBlur="ajaxFid(\'%s\',\'%s\',\'%s\',\'%s\',\'%s\');ajax_saldo(\'%s\',\'%s\')"',
			      $W1->name,
			      $W1->extra, //deb or cred
			      $_REQUEST['PHPSESSID'],
			      'js_search_only',
			      'none',
			      $_REQUEST['PHPSESSID'],
			      $W1->name
			      );
    $r.="<TR><td colspan=\"4\">".$W1->input();
    $Span=new ISpan("e_bank_account_label",$e_bank_account_label);
    $Span->setReadOnly($pview_only);
    $r.=$Span->input()."</TD>";

    $r.="</TABLE>";
  
    $r.='</fieldset>';




    $r.='<fieldset><legend>Opérations financières</legend>';
    //--------------------------------------------------
    // Saldo begin end 
    //-------------------------------------------------
    $r.='<fieldset><legend>Extrait de compte</legend>';
    $r.='<table>';
    $r.='<tr>';
    // Extrait
    //--
    $wExt=new IText("ext_no",$ext_no);
    $label=HtmlInput::infobulle(5);
    $wExt->label='Numéro d\'extrait '.$label;
    $r.=$wExt->input();
    $label=HtmlInput::infobulle(7);
    $r.='<td class="input_text">Solde début extrait'.$label.' </td>';
    $first_sold=(isset($first_sold))?$first_sold:"";
    $wFirst=new IText('first_sold',$first_sold);

    $r.='<td>'.$wFirst->input().'</td>';

    $last_sold= isset($last_sold)?$last_sold:"";
    $wLast=new IText('last_sold',$last_sold);

    $r.='<td  class="input_text">Solde fin extrait'.$label.' </td>';
    $r.='<td>'.$wLast->input().'</td>';
    $r.='</table>';
    $r.='</fieldset>';
    $max=(isset($nb_item))?$nb_item:MAX_ARTICLE;

    $r.= HtmlInput::hidden('nb_item',$max);
    //--------------------------------------------------
    // financial operation
    //-------------------------------------------------
    $r.='<TABLE id="fin_item">';
    $r.="<TR>";
    $r.="<th colspan=\"2\">code".HtmlInput::infobulle(0)."</TH>";
    $r.="<th>Commentaire</TH>";
    $r.="<th>Montant</TH>";
    $r.='<th colspan="2"> Op. Concern&eacute;e(s)</th>';
    $r.="</TR>";

    // Parse each " tiers" 
    for ($i=0; $i < $max; $i++) {
      $tiers=(isset(${"e_other".$i}))?${"e_other".$i}:"";
      $tiers_label="";
      $tiers_amount=(isset(${"e_other$i"."_amount"}))?round(${"e_other$i"."_amount"},2):0;
      
      $tiers_comment=(isset (${"e_other$i"."_comment"}))?${"e_other$i"."_comment"}:"";
      // If $tiers has a value
      if ( $tiers != ""  ) 
	{
	  $fTiers=new fiche($this->db);
	  $fTiers->get_by_qcode($tiers);
	
	  $tiers_label=$fTiers->strAttribut(ATTR_DEF_NAME);
	
	}
      ${"e_other$i"."_amount"}=(isset (${"e_other$i"."_amount"}))?${"e_other$i"."_amount"}:0;
      
      $W1=new ICard();
      $W1->label="";
      $W1->name="e_other".$i;
      $W1->value=$tiers;
      $W1->extra='cred';  // credits
      $W1->extra2='Recherche';
      $W1->readonly=$pview_only;
      $r.="<TR><td>".$W1->input()."</TD>";
      // label
      $other=new ISpan("e_other$i"."_label", $tiers_label);
      $r.='<TD style="width:25%;border-bottom:1px dotted grey;">';
      $r.=$other->input();
      // Comment
      $wComment=new IText("e_other$i"."_comment",$tiers_comment);

      $wComment->size=35;
      $wComment->setReadOnly($pview_only);
      $r.=$wComment->input();
      // amount
      $wAmount=new IText("e_other$i"."_amount",$tiers_amount);

      $wAmount->size=7;
      $wAmount->setReadOnly($pview_only);
      $r.=$wAmount->input();
      // concerned
      ${"e_concerned".$i}=(isset(${"e_concerned".$i}))?${"e_concerned".$i}:"";
      $wConcerned=new IConcerned("e_concerned".$i,${"e_concerned".$i});
      $wConcerned->setReadOnly($pview_only);
      $wConcerned->extra=0;

      $wConcerned->extra2='paid';
      $r.=$wConcerned->input();
      $r.='</TR>';
    }
    $r.="</TABLE>";
    
    $r.='</fieldset>';
    

    
    return $r;
    
  }
  /*!\brief show the summary before inserting into the database, it
   *calls the function for adding a attachment. The function verify
   *should be called before
   *\param $p_array an array usually is $_POST 
   *\return string with code html
   */
  public function confirm($p_array) {
    $r="";
    bcscale(2);
    extract ($p_array);
    $pPeriode=new Periode($this->db);
    $pPeriode->id=$periode;
    list ($l_date_start,$l_date_end)=$pPeriode->get_date_limit($periode);
    $exercice=$pPeriode->get_exercice();
    $r.='';
    $r.='<fieldset><legend>Banque, caisse </legend>';
    $r.='<TABLE  width="100%">';
    //  Date
    //--
    $r.="<tr>";
    $r.='<td> Date : </td><td>'.$e_date;
    // Periode 
    //--
    $r.="<td>";
    $r.="Période comptable </td><td>";
    $r.=$l_date_start.' - '.$l_date_end;
    $r.="</td>";
    $r.="</tr>";
    // Ledger (p_jrn)
    //--
    $r.='<tr>';
    $r.='<td> Journal </td>';
    $this->id=$p_jrn;
    $r.='<td>';
    $r.=h($this->get_name());
    $r.='</td>';
    $r.='</tr>';

    //retrieve bank name
    $e_bank_account=( isset ($e_bank_account) )?$e_bank_account:"";
    $e_bank_account_label="";  

    $fBank=new fiche($this->db);
    $fBank->get_by_qcode($e_bank_account);
    $e_bank_account_label=$fBank->strAttribut(ATTR_DEF_NAME).' '.
      ' Adresse : '.$fBank->strAttribut(ATTR_DEF_ADRESS).' '.
      $fBank->strAttribut(ATTR_DEF_CP).' '.
      $fBank->strAttribut(ATTR_DEF_CITY).' ';

    $filter_year=" and j_tech_per in (select p_id from parm_periode where  p_exercice='".$exercice."')";

      
    $solde=get_solde($this->db,$fBank->strAttribut(ATTR_DEF_ACCOUNT),$filter_year);
    $new_solde=$solde;

    $r.="<TR><td colspan=\"4\"> Banque ";
    $r.=$e_bank_account_label;

    $r.="</TABLE>";
  
    $r.='</fieldset>';

    $r.='<fieldset><legend>Opérations financières</legend>';
    //--------------------------------------------------
    // Saldo begin end 
    //-------------------------------------------------
    $r.='<fieldset><legend>Extrait de compte</legend>';
    $r.='<table>';
    $r.='<tr>';
    // Extrait
    //--
    $r.='<td> Numéro d\'extrait</td>'.h($ext_no);
    $r.='<td >Solde début extrait </td>';
    $r.='<td>'.$first_sold.'</td>';
    $r.='<td>Solde fin extrait </td>';
    $r.='<td>'.$last_sold.'</td>';
    $r.='</table>';
    $r.='</fieldset>';

    //--------------------------------------------------
    // financial operation
    //-------------------------------------------------
    $r.='<TABLE style="width:100%" id="fin_item">';
    $r.="<TR>";
    $r.="<th colspan=\"2\">code</TH>";
    $r.="<th>Commentaire</TH>";
    $r.="<th>Montant</TH>";
    $r.='<th colspan="2"> Op. Concern&eacute;e(s)</th>';
    $r.="</TR>";
    // Parse each " tiers" 
    $tot_amount=0;
    //--------------------------------------------------
    // For each items
    //--------------------------------------------------
    for ($i=0; $i < $nb_item; $i++) {
      
      $tiers=(isset(${"e_other".$i}))?${"e_other".$i}:"";

      if ( strlen(trim($tiers))==0)continue;
      $tiers_label="";
      $tiers_amount=round(${"e_other$i"."_amount"},2);
      $tot_amount=bcadd($tot_amount,$tiers_amount);
      $tiers_comment=h(${"e_other$i"."_comment"});
      // If $tiers has a value
      $fTiers=new fiche($this->db);
      $fTiers->get_by_qcode($tiers);
      
      $tiers_label=$fTiers->strAttribut(ATTR_DEF_NAME);
		
      $r.="<TR><td>".${'e_other'.$i}."</TD>";
      // label
      $other=new ISpan();
      $r.='<TD style="width:25%;border-bottom:1px dotted grey;">';
      $r.=$fTiers->strAttribut(ATTR_DEF_NAME);
      $r.='</td>';
      // Comment
      $r.='<td style="width:40%">'.$tiers_comment.'</td>';
      // amount
      $r.='<td>'.$tiers_amount.'</td>';
      // concerned
      $r.='<td>';
      $r.=${"e_concerned".$i};
      $r.='</td>';
      $r.='</TR>';
    }
    $r.="</TABLE>";

    // saldo
    $r.='<br>Ancien solde = '.$solde;
    $new_solde+=$tot_amount;
    $r.='<br>Nouveau solde = '.$new_solde;
    // check for upload piece
    $file=new IFile();

    $r.="<br>Ajoutez une pi&egrave;ce justificative ";
    $r.=$file->input("pj","");
    
    $r.='</fieldset>';
    //--------------------------------------------------
    // Hidden variables
    //--------------------------------------------------
    $r.=dossier::hidden();
    $r.=HtmlInput::hidden('phpsessid',$_REQUEST['PHPSESSID']);
    $r.=HtmlInput::hidden('p_jrn',$this->id);
    $r.=HtmlInput::hidden('nb_item',$nb_item);
    $r.=HtmlInput::hidden('last_sold',$last_sold);
    $r.=HtmlInput::hidden('first_sold',$first_sold);
    $r.=HtmlInput::hidden('e_bank_account',$e_bank_account);
    $r.=HtmlInput::hidden('ext_no',$ext_no);
    $r.=HtmlInput::hidden('e_date',$e_date);
    $r.=HtmlInput::hidden('periode',$periode);
    $r.=dossier::hidden();
    $r.=HtmlInput::hidden('sa','n');
    for ($i=0; $i < $nb_item; $i++) {      
      $tiers=(isset(${"e_other".$i}))?${"e_other".$i}:"";
      $r.=HtmlInput::hidden('e_other'.$i,$tiers);
      $r.=HtmlInput::hidden('e_other'.$i.'_comment',${'e_other'.$i.'_comment'});
      $r.=HtmlInput::hidden('e_other'.$i.'_amount',${'e_other'.$i.'_amount'});
      $r.=HtmlInput::hidden('e_concerned'.$i,${'e_concerned'.$i});
    }

    return $r;
  }
  /*!\brief save the data into the database, included the attachment,
   *and the reconciliations
   *\param $p_array usually $_POST 
   *\return string with HTML code
   */
  public function insert($p_array) {
    $internal_code="";
    $oid=0;
    extract ($p_array);
    
    // Debit = banque

    $fBank=new fiche($this->db);
    $fBank->get_by_qcode($e_bank_account);
    // Get the saldo
    $pPeriode=new Periode($this->db);
    $pPeriode->id=$periode;
    $exercice=$pPeriode->get_exercice();

    $filter_year=" and j_tech_per in (select p_id from parm_periode where  p_exercice='".$exercice."')";
    $solde=get_solde($this->db,$fBank->strAttribut(ATTR_DEF_ACCOUNT),$filter_year);
    $new_solde=$solde;
    
    try 
      {
	StartSql($this->db);
	$amount=0.0;  
	// Credit = goods 
	for ( $i = 0; $i < $nb_item;$i++) {
	  // if tiers is set and amount != 0 insert it into the database 
	  // and quit the loop ?
	  if ( strlen(trim(${"e_other$i"}))==0 ) continue;

	  $fPoste=new fiche($this->db);
	  $fPoste->get_by_qcode(${"e_other$i"});
	  // round it
	  ${"e_other$i"."_amount"}=round( ${"e_other$i"."_amount"},2);

	  $amount+=${"e_other$i"."_amount"};
	  // Record a line for the bank
	  // Compute the j_grpt
	  $seq=NextSequence($this->db,'s_grpt');

	  $acc_operation=new Acc_Operation($this->db);
	  $acc_operation->date=$e_date;
	  $acc_operation->poste=$fPoste->strAttribut(ATTR_DEF_ACCOUNT);
	  $acc_operation->amount=${"e_other$i"."_amount"}*(-1);
	  $acc_operation->grpt=$seq;
	  $acc_operation->jrn=$p_jrn;
	  $acc_operation->type='d';
	  $acc_operation->periode=$periode;
	  $acc_operation->qcode=${"e_other".$i};
	  $acc_operation->insert_jrnx();

	  $acc_operation=new Acc_Operation($this->db);
	  $acc_operation->date=$e_date;
	  $acc_operation->poste=$fBank->strAttribut(ATTR_DEF_ACCOUNT);
	  $acc_operation->amount=${"e_other$i"."_amount"};
	  $acc_operation->grpt=$seq;
	  $acc_operation->jrn=$p_jrn;
	  $acc_operation->type='d';
	  $acc_operation->periode=$periode;
	  $acc_operation->qcode=$e_bank_account;
	  $acc_operation->insert_jrnx();

	
	  if ( FormatString(${"e_other$i"."_comment"}) == null ) {
	    // if comment is blank set a default one
	    $comment="ext :".$ext_no."  compte : ".$fBank->strAttribut(ATTR_DEF_NAME).' a '.
	      $fPoste->strAttribut(ATTR_DEF_NAME);
	  } else {
	    $comment='ext: '.$ext_no.' '.${'e_other'.$i.'_comment'};
	  }


	  $acc_operation=new Acc_Operation($this->db);
	  $acc_operation->jrn=$p_jrn;
	  $acc_operation->amount=abs(${"e_other$i"."_amount"});
	  $acc_operation->date=$e_date;
	  $acc_operation->desc=$comment;
	  $acc_operation->grpt=$seq;
	  $acc_operation->periode=$periode;
	  $jr_id=$acc_operation->insert_jrn();

	  $internal=$this->compute_internal_code($seq);


	  if ( trim(${"e_concerned".$i}) != "" ) {
	    if ( strpos(${"e_concerned".$i},',') != 0 )
	      {
		$aRapt=split(',',${"e_concerned".$i});
		foreach ($aRapt as $rRapt) {
		  // Add a "concerned operation to bound these op.together
		  //
		  $rec=new Acc_Reconciliation ($this->db);
		  $rec->set_jr_id($jr_id);
		  $rec->insert($l_array['jr_id']);

		  if ( isNumber($rRapt) == 1 ) 
		    {
		      $rec->insert($rRapt);
		    }
		}
	      } else 
	      if ( isNumber(${"e_concerned".$i}) == 1 ) 
		{
		  $rec=new Acc_Reconciliation ($this->db);
		  $rec->set_jr_id($jr_id);
		  $rec->insert(${"e_concerned$i"});
		}
	  }
	  
	  // Set Internal code 
	  $this->grpt_id=$seq;
	  $this->update_internal_code($internal);



	if ( $i == 0 )
	  {
	    // first record we upload the files and
	    // keep variable to update other row of jrn
	    if ( isset ($_FILES))
	      $oid=save_upload_document($this->db,$seq);

	  } else {
	  if ( $oid  != 0 ) 
	    {
	      ExecSql($this->db,"update jrn set jr_pj=".$oid.", jr_pj_name='".$_FILES['pj']['name']."', ".
		      "jr_pj_type='".$_FILES['pj']['type']."'  where jr_grpt_id=$seq");
	    }
	}
	
      } // for nbitem
    } 
  catch (Exception $e)
    {
       echo '<span class="error">'.
	'Erreur dans l\'enregistrement '.
	__FILE__.':'.__LINE__.' '.
	$e->getMessage();
      Rollback($this->db);
      exit();
 
    }
  Commit($this->db);
  $r="";
  $r.="<br>Ancien solde ".$solde;
  $new_solde+=$amount;
  $r.="<br>Nouveau solde ".$new_solde;
  return $r;
  }
  /*!\brief display operation of a FIN ledger
   *\return html code into a string
   */
  function show_ledger() {
    echo dossier::hidden();
    $hid=new IHidden();
    
    $hid->name="p_action";
    $hid->value="bank";
    echo $hid->input();
    
    
    $hid->name="sa";
    $hid->value="l";
    echo $hid->input();

    $User=new User($this->db);

    $w=new ISelect();
    // filter on the current year
    $filter_year=" where p_exercice='".$User->get_exercice()."'";
    
    $periode_start=make_array($this->db,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by p_start,p_end",1);
  // User is already set User=new User($this->db);
    $current=(isset($_GET['p_periode']))?$_GET['p_periode']:$User->get_periode();
    $w->selected=$current;

    echo JS_SEARCH_CARD;
    echo JS_PROTOTYPE;
    echo JS_AJAX_FICHE;
    echo '<form>';
    echo 'Période  '.$w->input("p_periode",$periode_start);
    $wLedger=$this->select_ledger('fin',3);
    if ($wLedger == null) exit ('Pas de journal disponible');
    echo 'Journal '.$wLedger->input();
    $w=new ICard();
    $qcode=(isset($_GET['qcode']))?$_GET['qcode']:"";
    echo dossier::hidden();
    echo HtmlInput::hidden('p_action','bank');
    echo HtmlInput::hidden('sa','l');
    $w->name='qcode';
    $w->value=$qcode;
    $w->label='';
    $this->type='FIN';
    $all=$this->get_all_fiche_def();
    $w->extra=$all;
    $w->extra2='QuickCode';
    $sp=new ISpan();
    echo $sp->input("qcode_label","",$qcode);
    echo $w->input();

    echo HtmlInput::submit('gl_submit','Rechercher');
    echo '</form>';

    // Show list of sell
    // Date - date of payment - Customer - amount
    if ( $current != -1 )
      {
	$filter_per=" and jr_tech_per=".$current;
      }
    else 
      {
	$filter_per=" and jr_tech_per in (select p_id from parm_periode where p_exercice::integer=".
	  $User->get_exercice().")";
      }
    /* security  */
    $available_ledger=" and jr_def_id= ".$this->id." and ".$User->get_ledger_sql();
    
    // Show list of sell
    // Date - date of payment - Customer - amount
    $sql=SQL_LIST_ALL_INVOICE.$filter_per." and jr_def_type='FIN'".
      " $available_ledger" ;
    $step=$_SESSION['g_pagesize'];
    $page=(isset($_GET['offset']))?$_GET['page']:1;
    $offset=(isset($_GET['offset']))?$_GET['offset']:0;
    
    $l="";
    
    // check if qcode contains something
    if ( $qcode != "" )
      {
	// add a condition to filter on the quick code
	$l=" and jr_grpt_id in (select j_grpt from jrnx where j_qcode='$qcode') ";
      }
    
    list($max_line,$list)=ListJrn($this->db,0,"where jrn_def_type='FIN' $filter_per $l $available_ledger "
				  ,null,$offset,0);
    $bar=jrn_navigation_bar($offset,$max_line,$step,$page);
    
    echo "<hr> $bar";
    echo $list;
    echo "$bar <hr>";

    


  }
}
