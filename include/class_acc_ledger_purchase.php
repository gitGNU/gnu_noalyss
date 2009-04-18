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
 * \brief class for the purchase, herits from acc_ledger
 */
require_once("class_iselect.php");
require_once("class_icard.php");
require_once("class_ispan.php");
require_once("class_ihidden.php");
require_once("class_iperiod.php");
require_once("class_idate.php");
require_once("class_itext.php");
require_once("class_ifile.php");
require_once('class_acc_ledger.php');
require_once('class_acc_compute.php');
require_once('class_anc_operation.php');
require_once('user_common.php');
require_once('class_acc_parm_code.php');
require_once('class_acc_payment.php');
require_once('ac_common.php');
/*!\brief Handle the ledger of purchase, 
 *
 *
 */
class  Acc_Ledger_Purchase extends Acc_Ledger { 
  function __construct ($p_cn,$p_init) {
    parent::__construct($p_cn,$p_init);
  }
  /*!\brief verify that the data are correct before inserting or confirming
   *\param an array (usually $_POST)
   *\return String
   *\throw Exception if an error occurs
   */
  public function verify($p_array) {
    extract ($p_array);

    /* check for a double reload */
    if ( isset($mt) && count_sql($this->db,'select jr_mt from jrn where jr_mt=$1',array($mt)) != 0 )
      throw new Exception ('Double Encodage',5);

    /* check if there is a customer */
    if ( strlen(trim($e_client)) == 0 ) 
      throw new Exception('Vous n\'avez pas donné de fournisseur',11);

    /*  check if the date is valid */
    if ( isDate($e_date) == null ) {
      throw new Exception('Date invalide', 2);
    }
	$oPeriode=new Periode($this->db);
	if ( $this->check_periode() == false) {
		$tperiode=$oPeriode->find_periode($e_date);
	}else {
		$tperiode=$period;
		$oPeriode->id=$tperiode;
		/* check that the datum is in the choosen periode */
        list ($min,$max)=$oPeriode->get_date_limit($tperiode);
	    if ( cmpDate($e_date,$min) < 0 ||
		 cmpDate($e_date,$max) > 0) 
		throw new Exception('Date et periode ne correspondent pas',6);
	}
    /* check if the periode is closed */
    if ( $this->is_closed($tperiode)==1 )
      {
	throw new Exception('Periode fermee',6);
      }

    


    /* check if we are using the strict mode */
    if( $this->check_strict() == true) {
      /* if we use the strict mode, we get the date of the last
	 operation */
      $last_date=$this->get_last_date();
      if ( $last_date != null  && cmpDate($e_date,$last_date) < 0 )
	throw new Exception('Vous utilisez le mode strict la dernière operation est à la date du '
			      .$last_date.' vous ne pouvez pas encoder à une '.
			      ' date antérieure dans ce journal',13);

    }

    /* check the account */
    $fiche=new fiche($this->db);
    $fiche->get_by_qcode($e_client);
    if ( $fiche->empty_attribute(ATTR_DEF_ACCOUNT) == true)
      throw new Exception('La fiche '.$e_client.'n\'a pas de poste comptable',8);

    /* The account exists */
    $poste=new Acc_Account_Ledger($this->db,$fiche->strAttribut(ATTR_DEF_ACCOUNT));
    if ( $poste->load() == false ){
      throw new Exception('Pour la fiche '.$e_client.' le poste comptable ['.$poste->id.'] n\'existe pas',9);
    }

    /* Check if the card belong to the ledger */
    $fiche=new fiche ($this->db);
    $fiche->get_by_qcode($e_client,'cred');
    if ( $fiche->belong_ledger($p_jrn) !=1 )
	throw new Exception('La fiche '.$e_client.'n\'est pas accessible à ce journal',10);

    $nb=0;
    //------------------------------------------------------
    // The "Paid By"  check
    //------------------------------------------------------
    if ($e_mp != 0 ) $this->check_payment($e_mp,${"e_mp_qcode_".$e_mp});

 
    //----------------------------------------
    // foreach item
    //----------------------------------------
    for ($i=0;$i< $nb_item;$i++) {
      if ( strlen(trim(${'e_march'.$i}))== 0) continue;
      /* check if amount are numeric and */
      if ( isNumber(${'e_march'.$i.'_price'}) == 0 )
	throw new Exception('La fiche '.${'e_march'.$i}.'a un montant invalide ['.${'e_march'.$i}.']',6);
      if ( isNumber(${'e_quant'.$i}) == 0 )
	throw new Exception('La fiche '.${'e_march'.$i}.'a une quantité invalide ['.${'e_quant'.$i}.']',7);

      /* check if all card has a ATTR_DEF_ACCOUNT*/
      $fiche=new fiche($this->db);
      $fiche->get_by_qcode(${'e_march'.$i});
      if ( $fiche->empty_attribute(ATTR_DEF_ACCOUNT) == true)
	throw new Exception('La fiche '.${'e_march'.$i}.'n\'a pas de poste comptable',8);
      /* The account exists */
      $poste=new Acc_Account_Ledger($this->db,$fiche->strAttribut(ATTR_DEF_ACCOUNT));
      if ( $poste->load() == false ){
	throw new Exception('Pour la fiche '.${'e_march'.$i}.' le poste comptable ['.$poste->id.'n\'existe pas',9);
      }
      /* Check if the card belong to the ledger */
      $fiche=new fiche ($this->db);
      $fiche->get_by_qcode(${'e_march'.$i});
      if ( $fiche->belong_ledger($p_jrn,'deb') !=1 )
	throw new Exception('La fiche '.${'e_march'.$i}.'n\'est pas accessible à ce journal',10);
      $nb++;
    }
    if ( $nb == 0 )
      throw new Exception('Il n\'y a aucune marchandise',12);
  }

  public function save() {
    echo "<h2> Acc_Ledger_Purchase::save Not implemented</h2>";
  }

  /*!\brief insert into the database, it calls first the verify function
   *\param $p_array is usually $_POST or a predefined operation
   *\return string
   *\note throw an Exception
   */
  public function insert($p_array) {
    extract ($p_array);
    $this->verify($p_array) ; 

    $own=new own($this->db);
    $group=NextSequence($this->db,"s_oa_group"); /* for analytic */
    $seq=NextSequence($this->db,'s_grpt');
    $this->id=$p_jrn;
    $internal=$this->compute_internal_code($seq);
    $cust=new fiche($this->db);
    $cust->get_by_qcode($e_client);
    $poste=$cust->strAttribut(ATTR_DEF_ACCOUNT);
    $oPeriode=new Periode($this->db);
    $check_periode=$this->check_periode();
	
    if ( $check_periode == true ) 
      $tperiode=$period;
    else 
      $tperiode=$oPeriode->find_periode($e_date);
    
    bcscale(4);
    try {
      $tot_amount=0;
      $tot_tva=0;
      $tot_debit=0;
      StartSql($this->db);
      $tot_nd=0;
      $tot_perso=0;
      $tot_tva_nd=0;
      $tot_tva_ndded=0;

      /* Save all the items without vat and no deductible vat and expense*/
      for ($i=0;$i< $nb_item;$i++) {
	if ( strlen(trim(${'e_march'.$i})) == 0 ) continue;
	if ( ${'e_march'.$i.'_price'} == 0 ) continue;
	if ( ${'e_quant'.$i} == 0 ) continue;
	
	/* First we save all the items without vat */
	$fiche=new fiche($this->db);
	$fiche->get_by_qcode(${"e_march".$i});
	
	/* tva */
	if ($own->MY_TVA_USE=='Y') {
	  $idx_tva=${'e_march'.$i.'_tva_id'};
	  $oTva=new Acc_Tva($this->db);
	  $oTva->set_parameter('id',$idx_tva);
	  $oTva->load();
	}

	/* We have to compute all the amount thanks Acc_Compute */
	$amount=bcmul(${'e_march'.$i.'_price'},${'e_quant'.$i});
	$acc_amount=new Acc_Compute();
	$acc_amount->check=false;
	$acc_amount->set_parameter('amount',$amount);
	if ( $own->MY_TVA_USE=='Y') {
	  $acc_amount->set_parameter('amount_vat_rate',$oTva->get_parameter('rate'));
	  if ( strlen(trim(${'e_march'.$i.'_tva_amount'})) ==0) {
	    $acc_amount->compute_vat();
	    echo_debug( 'vat is computed = '.$acc_amount->amount_vat);
	    
	  } else {
	    $acc_amount->amount_vat= ${'e_march'.$i.'_tva_amount'};
	    echo_debug( 'vat is given = '.$acc_amount->amount_vat);
	    
	  }
	  $tot_tva+=$acc_amount->amount_vat;
	}

	$acc_operation=new Acc_Operation($this->db);
	$acc_operation->date=$e_date;
	$acc_operation->grpt=$seq;
	$acc_operation->jrn=$p_jrn;
	$acc_operation->type='d';
	$acc_operation->periode=$tperiode;
	$acc_operation->qcode="";

	 
	 

	if ( ! $fiche->empty_attribute(ATTR_DEF_DEPENSE_NON_DEDUCTIBLE)) {
	  $acc_amount->amount_nd_rate=$fiche->strAttribut(ATTR_DEF_DEPENSE_NON_DEDUCTIBLE);
	  $acc_amount->compute_nd();
	  $tot_nd+=$acc_amount->amount_nd;
	
	}

	if ( ! $fiche->empty_attribute(ATTR_DEF_DEP_PRIV)) {
	  $acc_amount->amount_perso_rate=$fiche->strAttribut(ATTR_DEF_DEP_PRIV);
	  $acc_amount->compute_perso();
	  $tot_perso+=$acc_amount->amount_perso;
	}

	if ( ! $fiche->empty_attribute(ATTR_DEF_TVA_NON_DEDUCTIBLE)) {
	  $acc_amount->nd_vat_rate=$fiche->strAttribut(ATTR_DEF_TVA_NON_DEDUCTIBLE);
	  $acc_amount->compute_nd_vat();
	  $tot_tva_nd+=$acc_amount->nd_vat;
	  /* save op. */

	}
	if ( ! $fiche->empty_attribute(ATTR_DEF_TVA_NON_DEDUCTIBLE_RECUP)) {
	  $acc_amount->nd_ded_vat_rate=$fiche->strAttribut(ATTR_DEF_TVA_NON_DEDUCTIBLE_RECUP);
	  $acc_amount->compute_ndded_vat();
	  /* save op. */
	  $tot_tva_ndded+=$acc_amount->nd_ded_vat;
	}
	$acc_amount->correct();
	$tot_amount+=$amount;

	$acc_operation->poste=$fiche->strAttribut(ATTR_DEF_ACCOUNT);
	$acc_operation->amount=$acc_amount->amount;
	$acc_operation->qcode=${"e_march".$i};
	if( $acc_amount->amount > 0 ) $tot_debit=bcadd($tot_debit,$acc_amount->amount);

	$j_id=$acc_operation->insert_jrnx();

	/* Compute sum vat */

	if ( $own->MY_TVA_USE=='Y') {
	  $tva_item=$acc_amount->amount_vat;
	  
	  if (isset($tva[$idx_tva] ) )
	    $tva[$idx_tva]+=$tva_item;
	  else
	    $tva[$idx_tva]=$tva_item;

	}
	/* Save the stock */
	/* if the quantity is < 0 then the stock increase (return of
	 *  material)
	 */
	$nNeg=(${"e_quant".$i}<0)?-1:1;
		
	// always save quantity but in withStock we can find 
	// what card need a stock management
	
	InsertStockGoods($this->db,$j_id,${'e_march'.$i},$nNeg*${'e_quant'.$i},'d') ;

	if ( $own->MY_ANALYTIC != "nu" )
	  {
	    // for each item, insert into operation_analytique */
	    $op=new Anc_Operation($this->db); 
	    $op->oa_group=$group;
	    $op->j_id=$j_id;
	    $op->oa_date=$e_date;
	    $op->oa_debit=($amount < 0 )?'t':'f';		    
	    echo_debug(__FILE__.':'.__LINE__,"Description is $e_comm");
	    $op->oa_description=FormatString($e_comm);
	    $op->save_form_plan($_POST,$i);
	  }
	// insert into quant_purchase
	//-----
	if ( $own->MY_TVA_USE=='Y') {
	  $r=ExecSql($this->db,"select insert_quant_purchase ".
		     "('".$internal."'".
		     ",".$j_id.
		     ",'".${"e_march".$i}."'".
		     ",".${"e_quant".$i}.",".
		     round($amount,2).
		     ",".$acc_amount->amount_vat.
		     ",".$oTva->get_parameter('id').
		     ",".$acc_amount->amount_nd.
		     ",".$acc_amount->nd_vat.
		     ",".$acc_amount->nd_ded_vat.
		     ",".$acc_amount->amount_perso.
		     ",'".$e_client."')");
	  
	} else {
	  $r=ExecSql($this->db,"select insert_quant_purchase ".
		     "('".$internal."'".
		     ",".$j_id.
		     ",'".${"e_march".$i}."'".
		     ",".${"e_quant".$i}.",".
		     round($amount,2).
		     ",0".
		     ",null".
		     ",".$acc_amount->amount_nd.
		     ",0".
		     ",".$acc_amount->nd_ded_vat.
		     ",".$acc_amount->amount_perso.
		     ",'".$e_client."')");
	  

	}

      }       // end loop : save all items
    /*  save total customer */
    $cust_amount=round(bcadd($tot_amount,$tot_tva),2);
    $acc_operation=new Acc_Operation($this->db);
    $acc_operation->date=$e_date;
    $acc_operation->poste=$poste;
    $acc_operation->amount=$cust_amount;
    $acc_operation->grpt=$seq;
    $acc_operation->jrn=$p_jrn;
    $acc_operation->type='c';
    $acc_operation->periode=$tperiode;
    $acc_operation->qcode=${"e_client"};
    if ( $cust_amount < 0 ) $tot_debit=bcadd($tot_debit,abs($cust_amount));
    $acc_operation->insert_jrnx();
    /* 
     * Save all the no deductible
     */
    if ( $tot_nd != 0) {
      /* save op. */
      $dna=new Acc_Parm_Code($this->db,'DNA');
      $acc_operation->type='d';
      $acc_operation->amount=$tot_nd;
      $acc_operation->poste=$dna->p_value;
      $acc_operation->qcode='';
      if ( $tot_nd > 0 ) $tot_debit=bcadd($tot_debit,$tot_nd);
      $j_id=$acc_operation->insert_jrnx();

    }  
    if ( $tot_perso != 0) {
      /* save op. */
      $acc_operation->type='d';
      $dna=new Acc_Parm_Code($this->db,'DEP_PRIV');
      $acc_operation->amount=$tot_perso;
      $acc_operation->poste=$dna->p_value;
      $acc_operation->qcode='';
      if ( $tot_perso > 0 ) $tot_debit=bcadd($tot_debit,$tot_perso);
      $j_id=$acc_operation->insert_jrnx();
      
    }  
    if ( $tot_tva_nd != 0) {
      /* save op. */
      $acc_operation->type='d';
      $acc_operation->qcode='';
      $dna=new Acc_Parm_Code($this->db,'TVA_DNA');
      $acc_operation->amount=$tot_tva_nd;
      $acc_operation->poste=$dna->p_value;
      if ( $tot_tva_nd > 0 ) $tot_debit=bcadd($tot_debit,$tot_tva_nd);
      $j_id=$acc_operation->insert_jrnx();
            
    }  
    if ( $tot_tva_ndded != 0) {
      /* save op. */
      $dna=new Acc_Parm_Code($this->db,'TVA_DED_IMPOT');

      $acc_operation->type='d';
      $acc_operation->qcode='';
      $acc_operation->amount=$tot_tva_ndded;
      $acc_operation->poste=$dna->p_value;
      if ( $tot_tva_ndded > 0 ) $tot_debit=bcadd($tot_debit,$tot_tva_ndded);
      $j_id=$acc_operation->insert_jrnx();

            
    }  

    if ( $own->MY_TVA_USE=='Y') {
      /* save all vat 
       * $i contains the tva_id and value contains the vat amount
       */
      foreach ($tva as $i => $value) {
	$oTva=new Acc_Tva($this->db);
	$oTva->set_parameter('id',$i);
	$oTva->load();

	$poste_vat=$oTva->get_side('d');
      
	$cust_amount=bcadd($tot_amount,$tot_tva);
	$acc_operation=new Acc_Operation($this->db);
	$acc_operation->date=$e_date;
	$acc_operation->poste=$poste_vat;
	$acc_operation->amount=$value;
	$acc_operation->grpt=$seq;
	$acc_operation->jrn=$p_jrn;
	$acc_operation->type='d';
	$acc_operation->periode=$tperiode;
	if ( $value > 0 ) $tot_debit=bcadd($tot_debit,$value);
	$acc_operation->insert_jrnx();
      
      }
    }
    /* insert into jrn */
    $acc_operation=new Acc_Operation($this->db);
    $acc_operation->date=$e_date;
    $acc_operation->echeance=$e_ech;
    $acc_operation->amount=abs(round($tot_debit,2));
    $acc_operation->desc=$e_comm;
    $acc_operation->grpt=$seq;
    $acc_operation->jrn=$p_jrn;
    $acc_operation->periode=$tperiode;
    $acc_operation->pj=$e_pj;
    $acc_operation->mt=$mt;
    $acc_operation->insert_jrn();
    $this->pj=$acc_operation->set_pj();

    // Set Internal code 
    $this->grpt_id=$seq;
    $this->update_internal_code($internal);

    /* if e_suggest != e_pj then do not increment sequence */
      if ( strcmp($e_pj,$e_pj_suggest) == 0 && strlen(trim($e_pj)) != 0 ) {
      	$this->inc_seq_pj();
      }

    /* Save the attachment */
    if ( isset ($_FILES)) {
      if ( sizeof($_FILES) != 0 )
	save_upload_document($this->db,$seq);
    }
    /* Generate an document  and save it into the database */
    if ( isset($_POST['gen_invoice']) && $e_mp != 0) {
      $p_array['e_client']=${'e_mp_qcode_'.$e_mp};
      echo $this->create_document($internal,$p_array);
      echo '<br>';
    }

    //----------------------------------------
    // Save the payer 
    //----------------------------------------
    if ( $e_mp != 0 ) {
      /* mp */
      $mp=new Acc_Payment($this->db,$e_mp);
      $mp->load();

      /* fiche */
      if ($mp->get_parameter('qcode') == '') 
	$fqcode=${'e_mp_qcode_'.$e_mp};
      else
	$fqcode=$mp->get_parameter('qcode');

      $acfiche = new fiche($this->db);
      $acfiche->get_by_qcode($fqcode);

      /* jrnx */
      $acseq=NextSequence($this->db,'s_grpt');
      $acjrn=new Acc_Ledger($this->db,$mp->get_parameter('ledger'));
      $acinternal=$acjrn->compute_internal_code($acseq);

      /* Insert paid by  */
      $acc_pay=new Acc_Operation($this->db);
      $acc_pay->date=$e_date;
      $acc_pay->poste=$acfiche->strAttribut(ATTR_DEF_ACCOUNT);
      $acc_pay->qcode=$fqcode;
      $acc_pay->amount=abs(round($tot_debit,2));
      $acc_pay->desc=$e_comm;
      $acc_pay->grpt=$acseq;
      $acc_pay->jrn=$mp->get_parameter('ledger');
      $acc_pay->periode=$tperiode;
      $acc_pay->type='c';
      $acc_pay->insert_jrnx();

      /* Insert supplier  */
      $acc_pay=new Acc_Operation($this->db);
      $acc_pay->date=$e_date;
      $acc_pay->poste=$poste;
      $acc_pay->qcode=$e_client;
      $acc_pay->amount=abs(round($tot_debit,2));
      $acc_pay->desc=$e_comm;
      $acc_pay->grpt=$acseq;
      $acc_pay->jrn=$mp->get_parameter('ledger');
      $acc_pay->periode=$tperiode;
      $acc_pay->type='d';
      $acc_pay->insert_jrnx();
      
      /* insert into jrn */
      $acc_pay->mt=$mt;
      $acc_pay->insert_jrn();
      $acjrn->grpt_id=$acseq;
      $acjrn->update_internal_code($acinternal);
    
      $r1=$this->get_id($internal);
      $r2=$this->get_id($acinternal);

      /* set the flag paid */
      $Res=ExecSqlParam($this->db,"update jrn set jr_rapt='paid' where jr_id=$1",array($r1));

      /* Reconcialiation */
      $rec=new Acc_Reconciliation($this->db);
      $rec->set_jr_id($r1);
      $rec->insert($r2);
    }
  }//end try
    catch (Exception $e)
      {
	echo '<span class="error">'.
	  'Erreur dans l\'enregistrement '.
	  __FILE__.':'.__LINE__.' '.
	  $e->getMessage().$e->getTrace();
	Rollback($this->db);
	exit();
      }
    Commit($this->db);
    return $internal;
  }

  public function update() {
    echo "<h2> Acc_Ledger_Purchase::update Not implemented</h2>";
  }

  public function load() {
    echo "<h2> Acc_Ledger_Purchase::load Not implemented</h2>";

  }
  /*!\brief Show all the operation, propose a form to select the
   *ledger and the periode
   *\return none
   *\note echo directly, there is no return with the html code
   */
  public function show_ledger() {
    $w=new ISelect();
    $User=new User($this->db); 
    // filter on the current year
    $filter_year=" where p_exercice='".$User->get_exercice()."'";
    
    $periode_start=make_array($this->db,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by  p_start,p_end",1);
    $current=(isset($_GET['p_periode']))?$_GET['p_periode']:-1;
    $w->selected=$current;
    $w->noadd=true;
    echo 'Période  '.$w->input("p_periode",$periode_start);
    /* select ledger */
    $wLedger=$this->select_ledger('ACH',3);
    if ($wLedger == null) exit ('Pas de journal disponible');
    if ( count($wLedger->value) > 1) {
      $aValue=$wLedger->value;
      $wLedger->value[0]=array('value'=>-1,'label'=>'Tous les journaux d\'achat');
      $idx=1;
      foreach ($aValue as $a) {
	$wLedger->value[$idx]=$a;
	$idx++;
      }
    }
    echo 'Journal '.$wLedger->input();

    $qcode=(isset($_GET['qcode']))?$_GET['qcode']:"";
    $this->type='ACH';
    $all=$this->get_all_fiche_def();
    echo JS_SEARCH_CARD;
    echo JS_PROTOTYPE;
    echo JS_AJAX_FICHE;
    $w=new ICard();
	$w->jrn=$this->id;
    $w->name='qcode';
    $w->value=$qcode;
    $w->label='';
    $w->extra='filter';
    $w->extra2='QuickCode';
    $w->table=0;
    $w->noadd='no';
    $sp=new ISpan();
    echo $sp->input("qcode_label","",$qcode);
    echo $w->input();

    echo HtmlInput::submit('gl_submit','Recherche');
 // Show list of sell
 // Date - date of payment - Customer - amount
    if ( $current == -1) {
      $cond=" and jr_tech_per in (select p_id from parm_periode where p_exercice='".$User->get_exercice()."')";
    } else {
      $cond=" and jr_tech_per=".$current;
    }
    if ( $this->id != -1) 
      $sql=SQL_LIST_ALL_INVOICE.$cond." and jr_def_id=".$this->id ;
    else
      $sql=SQL_LIST_ALL_INVOICE.$cond." and ".$User->get_ledger_sql('ACH',3);

    $step=$_SESSION['g_pagesize'];
    $page=(isset($_REQUEST['offset']))?$_REQUEST['page']:1;
    $offset=(isset($_REQUEST['offset']))?$_REQUEST['offset']:0;
    /* security  */
    $available_ledger=$User->get_ledger_sql();

    $l="";
    // check if qcode contains something
    if ( $qcode != "" )
      {
	$qcode=Formatstring($qcode);
	// add a condition to filter on the quick code
	$l=" and jr_grpt_id in (select j_grpt from jrnx where j_qcode=upper('$qcode')) ";
	$sql="where jrn_def_type='ACH' $cond $l and $available_ledger ";
      }

    list($max_line,$list)=ListJrn($this->db,$sql,null,$offset,1);
    $bar=jrn_navigation_bar($offset,$max_line,$step,$page);
    
    echo "<hr>$bar";
    echo '<form method="POST">';
    echo dossier::hidden();  
    $hid=new IHidden();
    
    echo $list;
    if ( $max_line !=0 ) {
      echo HtmlInput::hidden('page',$page);
      echo HtmlInput::hidden('offset',$offset);
      echo HtmlInput::submit('paid','Mise à jour paiement');
    }
    echo '</FORM>';
    echo "$bar <hr>";
    
    echo '</div>';
    
    
  }
  public function delete() {
    echo "<h2> Acc_Ledger_Purchase::delete Not implemented</h2>";
  }
  /*!\brief display the form for entering data for invoice
   *\param $p_array is null or you can put the predef operation or the $_POST
   *\return string
   */
  public function display_form($p_array=null) {
    if ( $p_array != null ) extract($p_array);

    $user = new User($this->db);
    $own=new Own($this->db);
    // The first day of the periode 
    $oPeriode=new Periode($this->db);
    list ($l_date_start,$l_date_end)=$oPeriode->get_date_limit($user->get_periode());

    $op_date=( ! isset($e_date) ) ?$l_date_start:$e_date;
    $e_ech=(isset($e_ech))?$e_ech:"";
    $e_comm=(isset($e_comm))?$e_comm:"";

    $r="";
    $r.="<FORM NAME=\"form_detail\" METHOD=\"POST\">";
    $r.=JS_INFOBULLE;
    $r.=JS_SEARCH_CARD;
    $r.=JS_SHOW_TVA;    
    $r.=JS_TVA;
    $r.=JS_AJAX_FICHE;

  
    $r.=dossier::hidden();
    $r.=HtmlInput::hidden('phpsessid',$_REQUEST['PHPSESSID']);  
    $r.="<fieldset>";
    $r.="<legend>En-tête facture fournisseur  </legend>";
    
    $r.='<TABLE  width="100%">';
    //  Date
    //--
    $Date=new IDate();
    $Date->setReadOnly(false);
    $Date->table=1;
    $Date->tabindex=1;
    $r.="<tr>";
    $r.=td('Date : '.$Date->input("e_date",$op_date));
    // Payment limit
    //--
    $Echeance=new IDate();
    $Echeance->setReadOnly(false);
    $Echeance->tabindex=2;
    $label=HtmlInput::infobulle(4);
    $r.=td("Echeance".$label.":".$Echeance->input("e_ech",$e_ech));

	if ($this->check_periode() == true) {
	    // Periode 
	    //--
	    $l_user_per=$user->get_periode();
	    $def=(isset($periode))?$periode:$l_user_per;
		
		$period=new IPeriod("period");
		$period->user=$user;
		$period->cn=$this->db;
		$period->value=$def;
		$period->type=OPEN;
		$l_form_per=$period->input();
	    
		$r.="<td>";
	    $label=HtmlInput::infobulle(3);
	    $r.="Période comptable $label</td><td>".$l_form_per;
	    $r.="</td>";
	}
    $r.="</tr><tr>";
	
    // Ledger (p_jrn)
    //--
    /* if we suggest the next pj, then we need a javascript */
    $add_js="";
    if ( $own->MY_PJ_SUGGEST=='Y') {
      $add_js="update_pj();";
    }
    $wLedger=$this->select_ledger('ACH',2);
    if ($wLedger == null) exit ('Pas de journal disponible');
    $wLedger->javascript="onChange='update_predef(\"ach\",\"f\");$add_js'";
    $label=" Journal ".HtmlInput::infobulle(2) ;

    $r.=td($label,"input_text").td($wLedger->input());
    // Comment
    //--
    $Commentaire=new IText();
    $Commentaire->table=0;
    $Commentaire->setReadOnly(false);
    $Commentaire->size=60;
    $Commentaire->tabindex=3;
    $label=" Description ".HtmlInput::infobulle(1) ;
    $r.="<tr>";
    $r.='<td class="input_text">'.$label.'</td>'.
      '<td colspan="3">'.$Commentaire->input("e_comm",$e_comm)."</td>";
    // PJ
    //--
    /* suggest PJ ? */
    $default_pj='';
    if ( $own->MY_PJ_SUGGEST=='Y') {
      $default_pj=$this->guess_pj();
    } 
    $pj->value=(isset($e_pj))?$e_pj:$default_pj;

    $pj=new IText();
    $pj->table=0;
    $pj->name="e_pj";
    $pj->size=10;
    $pj->readonly=false;
    $pj->value=$default_pj;

    $r.=HtmlInput::hidden('e_pj_suggest',$default_pj);
    $r.='<td class="input_text">Num.PJ</td><td>'.$pj->input().'</td>';
    // Display the customer
    //--
    $fiche='cred';

    // Save old value and set a new one
    //--
    $e_client=( isset ($e_client) )?$e_client:"";
    $e_client_label="&nbsp;";//str_pad("",100,".");
  
  
    // retrieve e_client_label
    //--

    if ( strlen(trim($e_client)) !=  0)   {
      $fClient=new fiche($this->db);
      $fClient->get_by_qcode($e_client);
      $e_client_label=$fClient->strAttribut(ATTR_DEF_NAME).' '.
	' Adresse : '.$fClient->strAttribut(ATTR_DEF_ADRESS).' '.
	$fClient->strAttribut(ATTR_DEF_CP).' '.
	$fClient->strAttribut(ATTR_DEF_CITY).' ';


    }
    
    $W1=new ICard();
	$W1->jrn=$this->id;
    $W1->label="Fournisseur ".HtmlInput::infobulle(0) ;
    $W1->name="e_client";
    $W1->tabindex=3;
    $W1->value=$e_client;
    $W1->table=0;
    $W1->extra=$fiche;  // list of card
    $W1->extra2="Recherche";
    $r.='<TR><td colspan="5" >'.$W1->input();
    $client_label=new ISpan();
    $client_label->table=0;
    $r.=$client_label->input("e_client_label",$e_client_label)."</TD></TR>";
    
    $r.="</TABLE>";
    
    // Record the current number of article
    $Hid=new IHidden();
    $p_article= ( isset ($p_article))?$p_article:MAX_ARTICLE;
    $r.=$Hid->input("nb_item",$p_article);
    $e_comment=(isset($e_comment))?$e_comment:"";
    $r.="</fieldset>";
    
    // Start the div for item to sell
    $r.="<DIV>";
    $r.='<fieldset><legend>D&eacute;tail articles achetés</legend>';
    $r.='<TABLE ID="sold_item">';
    $r.='<TR>';
    $r.="<th></th>";
    $label=HtmlInput::infobulle(0) ;
    $r.="<th>Code $label</th>";
    $r.="<th>D&eacute;nomination</th>";
    $label=HtmlInput::infobulle(6) ;
    $r.="<th>$label prix / unité htva </th>";
    /* use vat */
    if ( $own->MY_TVA_USE=='Y') {
      $r.="<th>tva</th>";
      $label=HtmlInput::infobulle(8) ;
      $r.="<th> $label Total  tva</th>";
    } 
    $r.="<th>quantit&eacute;</th>";


    $r.='</TR>';
    // For each article
    //--
    for ($i=0;$i< MAX_ARTICLE;$i++) {
      // Code id, price & vat code
      //--
      $march=(isset(${"e_march$i"}))?${"e_march$i"}:"";
      $march_price=(isset(${"e_march".$i."_price"}))?${"e_march".$i."_price"}:"";
      /* use vat */
      if ( $own->MY_TVA_USE=='Y') {
	$march_tva_id=(isset(${"e_march$i"."_tva_id"}))?${"e_march$i"."_tva_id"}:"";
	$march_tva_amount=(isset(${"e_march$i"."_tva_amount"}))?${"e_march$i"."_tva_amount"}:"";
      }
		
      

      $march_label="&nbsp;";
      // retrieve the tva label and name
      //--
      if ( strlen(trim($march))!=0 ) {
	$fMarch=new fiche($this->db);
	$fMarch->get_by_qcode($march);
	$march_label=$fMarch->strAttribut(ATTR_DEF_NAME);
	/* vat use */
	if ( ! isset($march_tva_id) && $own->MY_TVA_USE=='Y' )
	  $march_tva_id=$fMarch->strAttribut(ATTR_DEF_TVA);
      }
      // Show input
      //--
      $W1=new ICard();
	  $W1->jrn=$this->id;
      $W1->label="";
      $W1->name="e_march".$i;
      $W1->value=$march;
      $W1->table=1;
      $W1->extra2="Recherche";
      $W1->extra='deb';  // debits
      $W1->javascript=sprintf('onBlur="ajaxFid(\'%s\',\'%s\',\'%s\');compute_purchase(%d)"',
			      $W1->name,
			      $W1->extra, //deb or cred
			      $_REQUEST['PHPSESSID'],
			      $i
			      );

      $W1->readonly=false;
      $r.="<TR>".$W1->input();
      // For computing we need some hidden field for holding the value
      if ( $own->MY_TVA_USE=='Y') {
	$r.=HtmlInput::hidden('tva_march'.$i,0);      
      }
      $r.=HtmlInput::hidden('tvac_march'.$i,0);      
      $r.=HtmlInput::hidden('htva_march'.$i,0);      
      $r.="</TD>";
      $Span=new ISpan();
      $Span->setReadOnly(false);
      // card's name, price
      //--
      $r.='<TD style="width:55%;border-bottom:1px dotted grey;">'.$Span->input("e_march".$i."_label",$march_label)."</TD>";
      // price
      $Price=new IText();
      $Price->setReadOnly(false);
      $Price->table=1;
      $Price->size=9;
      $Price->javascript="onBlur='clean_tva($i);compute_purchase($i)'";
      $r.=$Price->input("e_march".$i."_price",$march_price);
      if ( $own->MY_TVA_USE=='Y') {
	// vat label
	//--
	$select_tva=make_array($this->db,"select tva_id,tva_label from tva_rate order by tva_rate desc",0);
	$Tva=new ISelect();
	$Tva->javascript="onChange=\"clean_tva($i);compute_purchase($i);\"";
	$Tva->table=1;
	$Tva->selected=$march_tva_id;
	$r.=$Tva->input("e_march$i"."_tva_id",$select_tva);
	// Tva_amount
	
	// price
	$Tva_amount=new IText();
	$Tva_amount->setReadOnly(false);
	$Tva_amount->table=1;
	$Tva_amount->size=9;
	$Tva_amount->javascript="onBlur='compute_purchase($i)'";
	$r.=$Tva_amount->input("e_march".$i."_tva_amount",$march_tva_amount);
      } 
      // quantity
      //--
      $quant=(isset(${"e_quant$i"}))?${"e_quant$i"}:"1";
      $Quantity=new IText();
      $Quantity->setReadOnly(false);
      $Quantity->table=1;
      $Quantity->size=9;
      $Quantity->javascript="onChange=compute_purchase($i)";
      $r.=$Quantity->input("e_quant".$i,$quant);

      $r.="</tr>";
    }

    
    
    $r.="</TABLE>";
    $r.='<div style="position:float;float:right;text-align:right;padding-right:5px;font-size:1.2em;font-weight:bold;color:blue">';
    $r.=HtmlInput::button('act','Actualiser','onClick="compute_all_purchase();"');
    $r.="</div>";

    $r.='<div style="position:float;float:right;text-align:left;font-size:1.2em;font-weight:bold;color:blue" id="sum">';
    if ( $own->MY_TVA_USE=='Y') {
      $r.='<br><span id="htva">0.0</span>';
      $r.='<br><span id="tva">0.0</span>';
      $r.='<br><span id="tvac">0.0</span>';
    } else {
      $r.='<br><span id="htva">0.0</span>';
    }
    $r.="</div>";



    $r.='<div style="position:float;float:right;text-align:right;padding-right:5px;font-size:1.2em;font-weight:bold;color:blue">';
    if ( $own->MY_TVA_USE=='Y') {
      $r.='<br>Total HTVA';
      $r.='<br>Total TVA';
      $r.='<br>Total TVAC';
    } else 
      $r.='<br>Total';

    $r.="</div>";
    $r.=HtmlInput::button('add_item','Ajout article',      ' onClick="ledger_sold_add_row()"');

    $r.="</fieldset>";
    //----------------------------------------------------------------------
    /* Paid By */
    $r.='<fieldset>';
    $r.='<legend> Pay&eacute par </legend>';
    $mp=new Acc_Payment($this->db);
    $mp->set_parameter('type','ACH');
    $r.=$mp->select();
    $r.='</fieldset>';



    // Set correctly the REQUEST param for jrn_type 
    $r.=HtmlInput::hidden('jrn_type','ACH');

    $r.=HtmlInput::submit("view_invoice","Enregistrer");
    $r.=HtmlInput::reset('Effacer ');
    $r.='</form>';
    $r.="</DIV>";

    /* if we suggest the pj n# the run the script */
    if ( $own->MY_PJ_SUGGEST=='Y') {
      $r.='<script> update_pj();</script>';
    } 


    $r.=JS_CALC_LINE;
    return $r;
  }
  /*!\brief show the summary of the operation and propose to save it
   *\param array contains normally $_POST. It proposes also to save
   * the Analytic accountancy
   *\return string
   */
  function confirm($p_array) {
    extract ($p_array);
    $this->verify($p_array) ; 
	
    // to show a select list for the analytic
    // if analytic is op (optionnel) there is a blank line
    $own = new Own($this->db);

    bcscale(4);
    $client=new fiche($this->db);
    $client->get_by_qcode($e_client,true);

    $client_name=h($client->getName().
      ' '.$client->strAttribut(ATTR_DEF_ADRESS).' '.
      $client->strAttribut(ATTR_DEF_CP).' '.
      $client->strAttribut(ATTR_DEF_CITY));
    $lPeriode=new Periode($this->db);
	if ($this->check_periode() == true) {
			$lPeriode->id=$period;
		} else {
			$lPeriode->find_periode($e_date);
		}
    $date_limit=$lPeriode->get_date_limit();
    $r="";
    $r.="<fieldset>";
    $r.="<legend>En-tête facture fournisseur  </legend>";
    $r.='<TABLE  width="100%">';
    $r.='<tr>';
    $r.='<td> Date '.$e_date.'</td>';
    $r.='<td>Echeance '.$e_ech.'</td>';
    $r.='<td> Période Comptable '.$date_limit['p_start'].'-'.$date_limit['p_end'].'</td>';
    $r.='<tr>';
    $r.='<td> Journal '.h($this->get_name()).'</td>';
    $r.='</tr>';
    $r.='<tr>';
    $r.='<td colspan="2"> Description '.h($e_comm).'</td><td> Pj :'.h($e_pj).'</td>';
    $r.='</tr>';
    $r.='<tr>';
    $r.='<td colspan="3"> Fournisseur '.h($e_client.':'.$client_name).'</td>';
    $r.='</tr>';
    $r.='</table>';
    $r.='</fieldset>';
    $r.='<fieldset><legend>D&eacute;tail articles achetés</legend>';
    $r.='<table width="100%" border="0">';
    $r.='<TR>';
    $r.="<th>Code</th>";
    $r.="<th>D&eacute;nomination</th>";
    $r.="<th>prix</th>";
        /* vat use */
    if ( $own->MY_TVA_USE=='Y') {
      $r.="<th>tva</th>";
      $r.="<th>quantit&eacute;</th>";
      $r.='<th> Montant TVA</th>';
      $r.='<th>Montant HTVA</th>';
    } else {
      $r.="<th>quantit&eacute;</th>";
      $r.='<th> Total</th>';
    }
    $r.=($own->MY_ANALYTIC!='nu')?'<th>Compt. Analytique</th>':'';
    $r.='</tr>';
    $tot_amount=0.0;
    $tot_tva=0.0;
    //--
    // For each item
    //--
    for ($i = 0; $i < $nb_item;$i++) {
      if ( strlen(trim(${"e_march".$i})) == 0 ) continue;

      /* retrieve information for card */
      $fiche=new fiche($this->db);
      $fiche->get_by_qcode(${"e_march".$i});
      $fiche_name=h($fiche->getName());
      
      if ( $own->MY_TVA_USE=='Y') {
	$idx_tva=${"e_march".$i."_tva_id"};
	$oTva=new Acc_Tva($this->db);
	$oTva->set_parameter('id',$idx_tva);
	$oTva->load();
      }
      $amount=bcmul(${"e_march".$i."_price"},${'e_quant'.$i});

      if ( $own->MY_TVA_USE=='Y') {
	//----- if tva_amount is not given we compute the vat ----
	if ( strlen (trim (${'e_march'.$i.'_tva_amount'})) == 0) {
	  $op=new Acc_Compute();
	  
	  $op->set_parameter("amount",$amount);
	  $op->set_parameter('amount_vat_rate',$oTva->get_parameter('rate'));
	  $op->compute_vat();
	  $tva_item=$op->get_parameter('amount_vat');
	} else 
	  $tva_item=round(${'e_march'.$i.'_tva_amount'},2);
	
	if (isset($tva[$idx_tva] ) )
	  $tva[$idx_tva]+=$tva_item;
	else
	  $tva[$idx_tva]=$tva_item;
	$tot_tva=round(bcadd($tva_item,$tot_tva),2);
      }
      $tot_amount=round(bcadd($tot_amount,$amount),2);

      $r.='<tr>';
      $r.='<td>';
      $r.=${"e_march".$i};
      $r.='</td>';
      $r.='<TD style="width:60%;border-bottom:1px dotted grey;">';
      $r.=$fiche_name;
      $r.='</td>';
      $r.='<td align="right">';
      $r.=${"e_march".$i."_price"};
      $r.='</td>';
      $r.='<td align="right">';
      $r.=${"e_quant".$i};
      $r.='</td>';
	  if ($own->MY_TVA_USE == 'Y') {
		  $r.='<td align="right">';
		  $r.=$oTva->get_parameter('label');
		  $r.='</td>';
		  $r.='<td align="right">';
		  $r.=$tva_item;
		  $r.='</td>';
	  }
      $r.='<td align="right">';
      $r.=$amount;
      $r.='</td>';

      // encode the pa
      if ( $own->MY_ANALYTIC!='nu') // use of AA
	{
	  // show form
	  $anc_op=new Anc_Operation($this->db);
	  $null=($own->MY_ANALYTIC=='op')?1:0;
	  $r.='<td>';
	  $p_mode=1;
	  $r.=$anc_op->display_form_plan($p_array,$null,$p_mode,$i,$amount);
	  $r.='</td>';
	}
		

      $r.='</tr>';
      
    }


    $r.='</table>';
      if ( $own->MY_ANALYTIC!='nu') // use of AA
	 $r.='<input type="button" value="verifie CA" onClick="verify_ca(\'ok\');">';
    $r.='</fieldset>';


    $r.='<fieldset> <legend>Totaux</legend>';
    $tot=round(bcadd($tot_amount,$tot_tva),2);
    $r.='<div style="position:float;float:left;text-align:right;padding-left:5%;padding-right:5%;color:blue;font-size:1.2em;font-weight:bold">';
    $r.='<br>Total HTVA';
	if ($own->MY_TVA_USE=='Y') {
	    foreach ($tva as $i=>$value) {
	      $oTva->set_parameter('id',$i);
	      $oTva->load();

	      $r.='<br>  TVA à '.$oTva->get_parameter('label');
		  $r.='<br>Total TVA';
	    }
	   
	    $r.='<br>Total TVAC';
	}
    $r.="</div>";


    $r.='<div style="position:float;float:left;text-align:right;color:blue;font-size:1.2em;font-weight:bold">';
    $r.='<br><span id="htva">'.$tot_amount.'</span>';

	if ( $own->MY_TVA_USE=='Y') {
	    foreach ($tva as $i=>$value) {
	      $r.='<br>'.$tva[$i];
	    }
	    $r.='<br><span id="tva">'.$tot_tva.'</span>';
	    $r.='<br><span id="tvac">'.$tot.'</span>';
		}
    $r.="</div>";



    $r.='</fieldset>';
    /*  Add hidden */
    $r.=HtmlInput::hidden('e_client',$e_client);
    $r.=HtmlInput::hidden('nb_item',$nb_item);
    $r.=HtmlInput::hidden('p_jrn',$p_jrn);
    if ( isset($period))
      $r.=HtmlInput::hidden('period',$period);
    $r.=HtmlInput::hidden('e_comm',$e_comm);
    $r.=HtmlInput::hidden('e_date',$e_date);
    $r.=HtmlInput::hidden('e_ech',$e_ech);
    $r.=HtmlInput::hidden('jrn_type',$jrn_type);
    $r.=HtmlInput::hidden('e_pj',$e_pj);
    $r.=HtmlInput::hidden('e_pj_suggest',$e_pj_suggest);
    $mt=microtime(true);
    $r.=HtmlInput::hidden('mt',$mt);

    $e_mp=(isset($e_mp))?$e_mp:0;
    $r.=HtmlInput::hidden('e_mp',$e_mp);
    /* Paid by */
    /* if the paymethod is not 0 and if a quick code is given */
    if ( $e_mp!=0 && strlen (trim (${'e_mp_qcode_'.$e_mp})) != 0 ) {
      $r.=HtmlInput::hidden('e_mp_qcode_'.$e_mp,${'e_mp_qcode_'.$e_mp});

      /* needed for generating a invoice */
      $r.=HtmlInput::hidden('qcode_dest',${'e_mp_qcode_'.$e_mp});

      $r.="Payé par ".${'e_mp_qcode_'.$e_mp};
      $r.='<br>';
    }
    for ($i=0;$i < $nb_item;$i++) {
      $r.=HtmlInput::hidden("e_march".$i,${"e_march".$i});
      $r.=HtmlInput::hidden("e_march".$i."_price",${"e_march".$i."_price"});
	  if ( $own->MY_TVA_USE=='Y' ) {
		$r.=HtmlInput::hidden("e_march".$i."_tva_id",${"e_march".$i."_tva_id"});
		$r.=HtmlInput::hidden('e_march'.$i.'_tva_amount', ${'e_march'.$i.'_tva_amount'});
		}
		$r.=HtmlInput::hidden("e_quant".$i,${"e_quant".$i});
		
    }
    // check for upload piece
    $file=new IFile();
    $file->table=0;
    $r.="Ajoutez une pi&egrave;ce justificative ";
    $r.=$file->input("pj","");
    /* Propose to generate a note of fee */
    if ( count_sql($this->db,
		  "select md_id,md_name from document_modele where md_type=10") > 0 )
      {

	
	$r.='ou g&eacute;n&eacute;rer une note de frais <input type="checkbox" name="gen_invoice" UNCHECKED>';
	// We propose to generate  the invoice and some template
	$doc_gen=new ISelect();
	$doc_gen->name="gen_doc";
	$doc_gen->value=make_array($this->db,
				   "select md_id,md_name from document_modele where md_type=10");
	$r.=$doc_gen->input().'<br>';  
      }

    return $r;
  }


  /*!\brief update the payment
   */
  function show_unpaid() {
    // Show list of unpaid sell
    // Date - date of payment - Customer - amount
    // Nav. bar 
    $step=$_SESSION['g_pagesize'];
    $page=(isset($_GET['offset']))?$_GET['page']:1;
    $offset=(isset($_GET['offset']))?$_GET['offset']:0;
    
    
    $sql=SQL_LIST_UNPAID_INVOICE_DATE_LIMIT." and jr_def_id=".$this->id ;
    list($max_line,$list)=ListJrn($this->db,$sql,null,$offset,1);
    $sql=SQL_LIST_UNPAID_INVOICE." and jr_def_id=".$this->id ;
    list($max_line2,$list2)=ListJrn($this->db,$sql,null,$offset,1);

    // Get the max line
    $m=($max_line2>$max_line)?$max_line2:$max_line;
    $bar2=jrn_navigation_bar($offset,$m,$step,$page);

    echo $bar2;
    echo '<h2 class="info"> Echeance dépassée </h2>';
    echo $list;
    echo  '<h2 class="info"> Non Payée </h2>';
    echo $list2;
    echo $bar2;
    // Add hidden parameter
    $hid=new IHidden();

    echo '<hr>';

    if ( $m != 0 )
      echo HtmlInput::submit('paid','Mise à jour paiement');


  }
  /*!\brief Test function 
   */	
  static function test_me() {
  }
  
}




  
