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
require_once('ac_common.php');
require_once('class_acc_reconciliation.php');

class Acc_Ledger_Fin extends Acc_Ledger {
  function __construct ($p_cn,$p_init) {
    parent::__construct($p_cn,$p_init);
    $this->type='FIN';
  }

  /*!\brief verify that the data are correct before inserting or confirming
   *\param an array (usually $_POST)
   *\return String
   *\throw Exception on error occurs
   */
  public function verify($p_array) {
    extract ($p_array);
    /* check for a double reload */
    if ( isset($mt) && $this->db->count_sql('select jr_mt from jrn where jr_mt=$1',array($mt)) != 0 )
      throw new Exception (_('Double Encodage'),5);

    /* check if we can write into this ledger */
    $user=new User($this->db);
    if ( $user->check_jrn($p_jrn) != 'W' )
      throw new Exception (_('Accès interdit'),20);

    /* check if there is a customer */
    if ( strlen(trim($e_bank_account)) == 0 ) 
      throw new Exception(_('Vous n\'avez pas donné de banque'),11);

    /*  check if the date is valid */
    if ( isDate($e_date) == null ) {
      throw new Exception('Date invalide', 2);
    }
    $oPeriode=new Periode($this->db);
    if ($this->check_periode()==false) {
      $periode=$oPeriode->find_periode($e_date);
    } else {
      $oPeriode->p_id=$periode;
      list ($min,$max)=$oPeriode->get_date_limit();
      if ( cmpDate($e_date,$min) < 0 ||
	   cmpDate($e_date,$max) > 0) 
	throw new Exception(_('Date et periode ne correspondent pas'),6);
    }
	
    /* check if the periode is closed */
    if ( $this->is_closed($periode)==1 )
      {
	throw new Exception(_('Periode fermee'),6);
      }
    /* check if we are using the strict mode */
    if( $this->check_strict() == true) {
      /* if we use the strict mode, we get the date of the last
	 operation */
      $last_date=$this->get_last_date();
      if ( $last_date != null  && cmpDate($e_date,$last_date) < 0 )
	throw new Exception(_('Vous utilisez le mode strict la dernière operation est à la date du ')
			      .$last_date._(' vous ne pouvez pas encoder à une date antérieure'),15);

    }



    $fiche=new fiche($this->db);
    $fiche->get_by_qcode($e_bank_account);
    if ( $fiche->empty_attribute(ATTR_DEF_ACCOUNT) == true)
      throw new Exception('La fiche '.$e_bank_account.'n\'a pas de poste comptable',8);

    /* get the account and explode if necessary */
    $sposte=$fiche->strAttribut(ATTR_DEF_ACCOUNT);
    // if 2 accounts, take only the debit one for customer
    if ( strpos($sposte,',') != 0 ) {
      $array=explode(',',$sposte);
      $poste_val=$array[0];
    } else {
      $poste_val=$sposte;
    }

    $acc_pay=new Acc_Operation($this->db);
    /* The account exists */
    $poste=new Acc_Account_Ledger($this->db,$poste_val);
    if ( $poste->load() == false ){
      throw new Exception('Pour la fiche '.$e_bank_account.' le poste comptable ['.$poste->id.'] n\'existe pas',9);
    }

    /* Check if the card belong to the ledger */
    $fiche=new fiche ($this->db);
    $fiche->get_by_qcode($e_bank_account);
    if ( $fiche->belong_ledger($p_jrn,'deb') !=1 )
	throw new Exception('La fiche '.$e_bank_account.'n\'est pas accessible à ce journal',10);

    $nb=0;
    $tot_amount=0;
    //----------------------------------------
    // foreach item
    //----------------------------------------
    for ($i=0;$i< $nb_item;$i++) {
      if ( strlen(trim(${'e_other'.$i}))== 0) continue;
      /* check if amount are numeric and */
      if ( isNumber(${'e_other'.$i.'_amount'}) == 0 )
	throw new Exception('La fiche '.${'e_other'.$i}.'a un montant invalide ['.${'e_other'.$i}.']',6);

      /* compute the total */
      $tot_amount+=round(${'e_other'.$i.'_amount'},2);
      echo_debug(__FILE__,__LINE__,' tot_amount =  '.$tot_amount.' e_other'.$i.'_amount'.${'e_other'.$i.'_amount'});
      /* check if all card has a ATTR_DEF_ACCOUNT*/
      $fiche=new fiche($this->db);
      $fiche->get_by_qcode(${'e_other'.$i});
      if ( $fiche->empty_attribute(ATTR_DEF_ACCOUNT) == true)
	throw new Exception('La fiche '.${'e_other'.$i}.'n\'a pas de poste comptable',8);

      $sposte=$fiche->strAttribut(ATTR_DEF_ACCOUNT);
      // if 2 accounts, take only the debit one for customer
      if ( strpos($sposte,',') != 0 ) {
	$array=explode(',',$sposte);
	$poste_val=$array[1];
      } else {
	$poste_val=$sposte;
      }
      /* The account exists */
      $poste=new Acc_Account_Ledger($this->db,$poste_val);
      if ( $poste->load() == false ){
	throw new Exception('Pour la fiche '.${'e_other'.$i}.' le poste comptable ['.$poste->id.'n\'existe pas',9);
      }
      /* Check if the card belong to the ledger */
      $fiche=new fiche ($this->db);
      $fiche->get_by_qcode(${'e_other'.$i});
      if ( $fiche->belong_ledger($p_jrn,'cred') !=1 )
	throw new Exception('La fiche '.${'e_other'.$i}.'n\'est pas accessible à ce journal',10);
      $nb++;
    }
    if ( $nb == 0 )
      throw new Exception('Il n\'y a aucune opération',12);

    /* Check if the last_saldo and first_saldo are correct */
    if ( strlen(trim($last_sold)) != 0 && isNumber($last_sold) &&
	 strlen(trim($first_sold)) != 0 && isNumber($first_sold))
      {
	$diff=$last_sold-$first_sold;
	echo_debug(__FILE__,__LINE__,' Diff saldo = '.$last_sold.' - '.$first_sold.' = '.$diff);
	$diff=round($diff,2)-round($tot_amount,2);
	if ( $first_sold != 0 && $last_sold !=0) {
	  if ( $diff != 0 )
	    throw new Exception('Le montant de l\'extrait est incorrect'.
				  $tot_amount.' extrait '.$diff,13);
	}
      }

  }


  /*!\brief 
  *\param $p_array contains the value usually it is $_POST 
   *\return string with html code
   *\note the form tag are not  set here
   */
  function input($p_array=null) {
    if ( $p_array != null)
      extract ($p_array);
    $pview_only=false;
    $user = new User($this->db);
    $f_add_button=new IButton('add_card');
    $f_add_button->label=_('Créer une nouvelle fiche');
    $f_add_button->set_attribute('ipopup','ipop_newcard');
    $f_add_button->set_attribute('filter',$this->get_all_fiche_def ());
    $f_add_button->javascript=" select_card_type(this);";
    $str_add_button=$f_add_button->input();

    // The first day of the periode 
    $pPeriode=new Periode($this->db);
    list ($l_date_start,$l_date_end)=$pPeriode->get_date_limit($user->get_periode());
    
    $op_date=( ! isset($e_date) ) ?$l_date_start:$e_date;
    $ext_no=( ! isset($ext_no) ) ?'':$ext_no;
    
    $r="";

    $r.=dossier::hidden();
    $r.=HtmlInput::hidden('phpsessid',$_REQUEST['PHPSESSID']);
    $f_legend='Banque, caisse';
    //  Date
    //--
    $Date=new IDate("e_date",$op_date);
    $Date->setReadOnly($pview_only);
    $f_date=$Date->input();
    $f_period='';
    if ($this->check_periode() == true) {
      // Periode 
      //--
      $l_user_per=(isset($periode))?$periode:$user->get_periode();
      $period=new IPeriod();
      $period->cn=$this->db;
      $period->type=OPEN;
      $period->value=$l_user_per;
      $period->user=$user;
      $period->name='periode';
      try {
	$l_form_per=$period->input();
      } catch (Exception $e) {
	if ($e->getCode() == 1 ) { 
	  echo "Aucune période ouverte";
	  exit();
	}
      }
      $label=HtmlInput::infobulle(3);
      $f_period="Période comptable $label".$l_form_per;
    }

    // Ledger (p_jrn)
    //--
    $wLedger=$this->select_ledger('FIN',2);
    if ($wLedger == null) exit ('Pas de journal disponible');

    $label=" Journal ".HtmlInput::infobulle(2) ;
    $f_jrn=$label.$wLedger->input();

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
    $f_bank=$e_bank_account.$e_bank_account_label;

    $ibank=new ICard();
    $ibank->readonly=$pview_only;
    $ibank->label="Banque ".HtmlInput::infobulle(0);
    $ibank->name="e_bank_account";
    $ibank->value=$e_bank_account;
    $ibank->extra='deb';  // credits
    $ibank->typecard='deb';
    $ibank->set_dblclick("fill_ipopcard(this);");
    $ibank->set_attribute('ipopup','ipopcard');

    // name of the field to update with the name of the card
    $ibank->set_attribute('label','e_bank_account_label');
    // Add the callback function to filter the card on the jrn
    $ibank->set_callback('filter_card');
    $ibank->set_function('fill_fin_data');
    $ibank->javascript=sprintf(' onchange="fill_fin_data_onchange(\'%s\');" ',
			    $ibank->name);

    $f_legend_detail='Opérations financières';
    //--------------------------------------------------
    // Saldo begin end 
    //-------------------------------------------------
    // Extrait
    //--
    $wExt=new IText("ext_no",$ext_no);
    $label=HtmlInput::infobulle(5);
    $wExt->label='Numéro d\'extrait '.$label;
    $f_extrait=$wExt->input();
    $label=HtmlInput::infobulle(7);

    $first_sold=(isset($first_sold))?$first_sold:"";
    $wFirst=new INum('first_sold',$first_sold);

    $last_sold= isset($last_sold)?$last_sold:"";
    $wLast=new INum('last_sold',$last_sold);


    $max=(isset($nb_item))?$nb_item:MAX_ARTICLE;

    $r.= HtmlInput::hidden('nb_item',$max);
    //--------------------------------------------------
    // financial operation
    //-------------------------------------------------

    $array=array();
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
      $W1->typecard='cred';
      $W1->set_dblclick("fill_ipopcard(this);");
      $W1->set_attribute('ipopup','ipopcard');

      // name of the field to update with the name of the card
      $W1->set_attribute('label','e_other'.$i.'_comment');
      // name of the field to update with the name of the card
      $W1->set_attribute('typecard','filter');
      // Add the callback function to filter the card on the jrn
      $W1->set_callback('filter_card');
      $W1->set_function('fill_data');
      $W1->javascript=sprintf(' onchange="fill_data_onchange(\'%s\');" ',
			      $W1->name);
      $W1->readonly=$pview_only;
      $array[$i]['qcode']=$W1->input();
      $array[$i]['search']=$W1->search();

      // label
      $other=new ISpan("e_other$i"."_label", $tiers_label);
      $array[$i]['span']=$other->input();
      // Comment
      $wComment=new IText("e_other$i"."_comment",$tiers_comment);

      $wComment->size=35;
      $wComment->setReadOnly($pview_only);
      $array[$i]['comment']=$wComment->input();
      // amount
      $wAmount=new INum("e_other$i"."_amount",$tiers_amount);

      $wAmount->size=7;
      $wAmount->setReadOnly($pview_only);
      $array[$i]['amount']=$wAmount->input();
      // concerned
      ${"e_concerned".$i}=(isset(${"e_concerned".$i}))?${"e_concerned".$i}:"";
      $wConcerned=new IConcerned("e_concerned".$i,${"e_concerned".$i});
      $wConcerned->setReadOnly($pview_only);
      $wConcerned->extra=0;

      $wConcerned->extra2='paid';
      $array[$i]['concerned']=$wConcerned->input();
    }
    
    ob_start();
    require_once('template/form_ledger_fin.php');
    $r.=ob_get_contents();
    ob_clean();

    
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
	if ($this->check_periode() == true) {
			$pPeriode->p_id=$periode;
		} else {
			$pPeriode->find_periode($e_date);
		}
	
    list ($l_date_start,$l_date_end)=$pPeriode->get_date_limit();
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

    $filter_year="  j_tech_per in (select p_id from parm_periode where  p_exercice='".$exercice."')";

    $acc_account=new Acc_Account_Ledger($this->db,$fBank->strAttribut(ATTR_DEF_ACCOUNT));
    $solde=$acc_account->get_solde($filter_year);
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
    $r.='<br>Difference ='.$tot_amount;
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
    $mt=microtime(true);
    $r.=HtmlInput::hidden('mt',$mt);

    if (isset($periode))    $r.=HtmlInput::hidden('periode',$periode);
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
	if ( $this->check_periode() == true ) {
		$pPeriode->p_id=$periode;
	} else {
		$pPeriode->find_periode($e_date);
		}
    $exercice=$pPeriode->get_exercice();

    $filter_year="  j_tech_per in (select p_id from parm_periode where  p_exercice='".$exercice."')";
    $sposte=$fBank->strAttribut(ATTR_DEF_ACCOUNT);
    // if 2 accounts, take only the debit one for customer
    if ( strpos($sposte,',') != 0 ) {
      $array=explode(',',$sposte);
      $poste_val=$array[0];
    } else {
      $poste_val=$sposte;
    }

    $acc_account=new Acc_Account_Ledger($this->db,$poste_val);
    $solde=$acc_account->get_solde($filter_year);
    $new_solde=$solde;
    
    try 
      {
	$this->db->start();
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
	  $seq=$this->db->get_next_seq('s_grpt');

	  $acc_operation=new Acc_Operation($this->db);
	  $acc_operation->date=$e_date;
	  $sposte=$fPoste->strAttribut(ATTR_DEF_ACCOUNT);
	  // if 2 accounts
	  if ( strpos($sposte,',') != 0 ) {
	    $array=explode(',',$sposte);
	    if ( ${"e_other$i"."_amount"} < 0 )
	      $poste_val=$array[0];
	    else
	      $poste_val=$array[1];
	  } else {
	    $poste_val=$sposte;
	  }
	  
	  
	  $acc_operation->poste=$poste_val;
	  $acc_operation->amount=${"e_other$i"."_amount"}*(-1);
	  $acc_operation->grpt=$seq;
	  $acc_operation->jrn=$p_jrn;
	  $acc_operation->type='d';
	  
	  if ( isset($periode)) 
	    $tperiode=$periode;
	  else 
	    {
	      $per=new Periode($this->db);
	      $tperiode=$per->find_periode($e_date);
	      
	    }
	  $acc_operation->periode=$tperiode;
	  $acc_operation->qcode=${"e_other".$i};
	  $acc_operation->insert_jrnx();

	  $acc_operation=new Acc_Operation($this->db);
	  $acc_operation->date=$e_date;
	  $sposte=$fBank->strAttribut(ATTR_DEF_ACCOUNT);

	  // if 2 accounts
	  if ( strpos($sposte,',') != 0 ) {
	    $array=explode(',',$sposte);
	    if ( ${"e_other$i"."_amount"} < 0 )
	      $poste_val=$array[1];
	    else
	      $poste_val=$array[0];
	  } else {
	    $poste_val=$sposte;
	  }

	  $acc_operation->poste=$poste_val;
	  $acc_operation->amount=${"e_other$i"."_amount"};
	  $acc_operation->grpt=$seq;
	  $acc_operation->jrn=$p_jrn;
	  $acc_operation->type='d';
	  $acc_operation->periode=$tperiode;
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
	  $acc_operation->periode=$tperiode;
	  $acc_operation->mt=$mt;
	  $jr_id=$acc_operation->insert_jrn();

	  $internal=$this->compute_internal_code($seq);


	  if ( trim(${"e_concerned".$i}) != "" ) {
	    if ( strpos(${"e_concerned".$i},',') != 0 )
	      {
		$aRapt=explode(',',${"e_concerned".$i});
		foreach ($aRapt as $rRapt) {
		  // Add a "concerned operation to bound these op.together
		  //
		  $rec=new Acc_Reconciliation ($this->db);
		  $rec->set_jr_id($jr_id);

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
	      $oid=$this->db->save_upload_document($seq);

	  } else {
	  if ( $oid  != 0 ) 
	    {
	      $this->db->exec_sql("update jrn set jr_pj=".$oid.", jr_pj_name='".$_FILES['pj']['name']."', ".
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
      $this->db->rollback();
      exit();
 
    }
  $this->db->commit();
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
    
    $periode_start=$this->db->make_array("select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by p_start,p_end",1);
  // User is already set User=new User($this->db);
    $current=(isset($_GET['p_periode']))?$_GET['p_periode']:-1;
    $w->selected=$current;

    echo JS_LEDGER;
    echo JS_PROTOTYPE;
    echo JS_AJAX_FICHE;
    echo '<form>';
    echo 'Période  '.$w->input("p_periode",$periode_start);
    $wLedger=$this->select_ledger('fin',3);
    if ($wLedger == null) exit (_('Pas de journal disponible'));
    if ( count($wLedger->value) > 1) {
      $aValue=$wLedger->value;
      $wLedger->value[0]=array('value'=>-1,'label'=>_('Tous les journaux financiers'));
      $idx=1;
      foreach ($aValue as $a) {
	$wLedger->value[$idx]=$a;
	$idx++;
      }
    }



    echo 'Journal '.$wLedger->input();
    $w=new ICard();
    $w->noadd='no';
    $w->jrn=$this->id;
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

    echo HtmlInput::submit('gl_submit',_('Rechercher'));
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
    if( $this->id != -1)
      $available_ledger=" and jr_def_id= ".$this->id." and ".$User->get_ledger_sql();
    else
          $available_ledger=" and ".$User->get_ledger_sql();
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
	$l=" and jr_grpt_id in (select j_grpt from jrnx where j_qcode=upper('$qcode')) ";
      }
    
    list($max_line,$list)=ListJrn($this->db,"where jrn_def_type='FIN' $filter_per $l $available_ledger "
				  ,null,$offset,0);
    $bar=jrn_navigation_bar($offset,$max_line,$step,$page);
    
    echo "<hr> $bar";
    echo $list;
    echo "$bar <hr>";

    


  }
}
