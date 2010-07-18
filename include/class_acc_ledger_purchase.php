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
require_once('class_itva_popup.php');
require_once('class_acc_ledger_info.php');

/*!\brief Handle the ledger of purchase,
 *
 *
 */
class  Acc_Ledger_Purchase extends Acc_Ledger {
  function __construct ($p_cn,$p_init) {
    $this->type='ACH';
    parent::__construct($p_cn,$p_init);
  }
  /*!\brief verify that the data are correct before inserting or confirming
   *\param an array (usually $_POST)
   *\return String
   *\throw Exception if an error occurs
   */
  public function verify($p_array) {
    extract ($p_array);
    /* check if we can write into this ledger */
    $user=new User($this->db);
    if ( $user->check_jrn($p_jrn) != 'W' )
      throw new Exception (_('Accès interdit'),20);


    /* check for a double reload */
    if (  isset($mt) && $this->db->count_sql('select jr_mt from jrn where jr_mt=$1',array($mt)) != 0 )
      throw new Exception (_('Double Encodage'),5);

    /* check if there is a customer */
    if ( strlen(trim($e_client)) == 0 )
      throw new Exception(_('Vous n\'avez pas donné de fournisseur'),11);

    /*  check if the date is valid */
    if ( isDate($e_date) == null ) {
      throw new Exception(_('Date invalide'), 2);
    }
    $oPeriode=new Periode($this->db);
    if ( $this->check_periode() == false) {
      $tperiode=$oPeriode->find_periode($e_date);
    }else {
      $tperiode=$period;
      $oPeriode->p_id=$tperiode;
      /* check that the datum is in the choosen periode */
      list ($min,$max)=$oPeriode->get_date_limit($tperiode);
      if ( cmpDate($e_date,$min) < 0 ||
	   cmpDate($e_date,$max) > 0)
	throw new Exception(_('Date et periode ne correspondent pas'),6);
    }
    /* check if the periode is closed */
    if ( $this->is_closed($tperiode)==1 )
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
			    .$last_date._(' vous ne pouvez pas encoder à une '.
					  ' date antérieure dans ce journal'),13);

    }

    /* check the account */
    $fiche=new Fiche($this->db);
    $fiche->get_by_qcode($e_client);

    if ( $fiche->empty_attribute(ATTR_DEF_ACCOUNT) == true)
      throw new Exception(_('La fiche ').$e_client._('n\'a pas de poste comptable'),8);

    /* get the account and explode if necessary */
    $sposte=$fiche->strAttribut(ATTR_DEF_ACCOUNT);
    // if 2 accounts, take only the credit one for supplier
    if ( strpos($sposte,',') != 0 ) {
      $array=explode(',',$sposte);
      $poste_val=$array[1];
    } else {
      $poste_val=$sposte;
    }

    /* The account exists */
    $poste=new Acc_Account_Ledger($this->db,$poste_val);
    if ( $poste->load() == false ){
      throw new Exception(_('Pour la fiche ').$e_client._(' le poste comptable [').$poste->id.'] '._('n\'existe pas'),9);
    }
    /* Check if the card belong to the ledger */
    $fiche=new Fiche ($this->db);
    $fiche->get_by_qcode($e_client,'cred');
    if ( $fiche->belong_ledger($p_jrn) !=1 )
      throw new Exception(_('La fiche ').$e_client._('n\'est pas accessible à ce journal'),10);

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
	throw new Exception(_('La fiche ').${'e_march'.$i}._('a un montant invalide').' ['.${'e_march'.$i}.']',6);
      if ( isNumber(${'e_quant'.$i}) == 0 )
	throw new Exception(_('La fiche ').${'e_march'.$i}._('a une quantité invalide').' ['.${'e_quant'.$i}.']',7);

      $owner=new Own($this->db);
      // Check if the given tva id is valid
      if ( $owner->MY_TVA_USE=='Y') {
	if (${'e_march'.$i.'_tva_id'} == 0 )
	  throw new Exception(_('La fiche ').${'e_march'.$i}._('a un code tva invalide').' ['.${'e_march'.$i.'_tva_id'}.']',13);
      $tva_rate=new Acc_Tva($this->db);
      $tva_rate->set_parameter('id',${'e_march'.$i.'_tva_id'});

      if ( $tva_rate->load() != 0 ) 
	throw new Exception(_('La fiche ').${'e_march'.$i}._('a un code tva invalide').' ['.${'e_march'.$i.'_tva_id'}.']',13);
    }
    /* check if all card has a ATTR_DEF_ACCOUNT*/
    $fiche=new Fiche($this->db);
    $fiche->get_by_qcode(${'e_march'.$i});
    if ( $fiche->empty_attribute(ATTR_DEF_ACCOUNT) == true)
	throw new Exception(_('La fiche ').${'e_march'.$i}._('n\'a pas de poste comptable'),8);

      /* get the account and explode if necessary */
      $sposte=$fiche->strAttribut(ATTR_DEF_ACCOUNT);
      // if 2 accounts, take only the  debit
      if ( strpos($sposte,',') != 0 ) {
	$array=explode(',',$sposte);
	$poste_val=$array[0];
      } else {
	$poste_val=$sposte;
      }

      /* The account exists */
      $poste=new Acc_Account_Ledger($this->db,$poste_val);
      if ( $poste->load() == false ){
	throw new Exception(_('Pour la fiche ').${'e_march'.$i}._(' le poste comptable').' ['.$poste->id._('n\'existe pas'),9);
      }
      /* Check if the card belong to the ledger */
      $fiche=new Fiche ($this->db);
      $fiche->get_by_qcode(${'e_march'.$i});
      if ( $fiche->belong_ledger($p_jrn,'deb') !=1 )
	throw new Exception(_('La fiche ').${'e_march'.$i}._('n\'est pas accessible à ce journal'),10);
    /** 
     * we have to check also if the different accountings exist
     "ATTR_DEF_DEP_PRIV"
     "ATTR_DEF_DEPENSE_NON_DEDUCTIBLE"
     "ATTR_DEF_TVA_NON_DEDUCTIBLE"
     "ATTR_DEF_TVA_NON_DEDUCTIBLE_RECUP"
    */
      foreach (array(
		     array(ATTR_DEF_DEPENSE_NON_DEDUCTIBLE,'DNA'),
		     array(ATTR_DEF_DEP_PRIV,'DEP_PRIV'),
		     array(ATTR_DEF_TVA_NON_DEDUCTIBLE_RECUP,'TVA_DED_IMPOT'),
		     array(ATTR_DEF_TVA_NON_DEDUCTIBLE,'TVA_DNA')) as $key) {
	if ( ! $fiche->empty_attribute($key[0])) {
	  $a=new Acc_Parm_Code($this->db,$key[1]);
	  if ( $this->db->count_sql('select pcm_val from tmp_pcmn where pcm_val=$1',array($a->p_value))==0)
	    throw new Exception ($key._("ce code n'a pas de poste comptable, créez ce poste : [".$a->p_value."]"));
	}
      }
      $nb++;
    }
    if ( $nb == 0 )
      throw new Exception(_('Il n\'y a aucune marchandise'),12);
    
  }

  public function save($p_array) {
    echo "<h2> Acc_Ledger_Purchase::save Not implemented</h2>";
  }

  /*!\brief insert into the database, it calls first the verify function
   * change the value of this->jr_id and this->jr_internal.
   * It generates the document and save the middle of payment, if 'gen_invoice is set
   * and e_mp
   *\param $p_array is usually $_POST or a predefined operation
\code
   Array
(

    [e_client] =>BELGACOM
    [nb_item] =>9
    [p_jrn] =>3
    [period] =>117
    [e_comm] =>Frais de téléphone
    [e_date] =>01.09.2009
    [e_ech] =>
    [jrn_type] =>ACH
    [e_pj] =>ACH53
    [e_pj_suggest] =>ACH53
    [mt] =>1265318941.39
    [e_mp] =>0
    [e_march0] =>TEL
    [e_march0_price] =>63.6700
    [e_march0_tva_id] =>1
    [e_march0_tva_amount] =>13.3700
    [e_quant0] =>1.000
    ...
    [bon_comm] =>
    [other_info] =>
    [record] =>Enregistrement
)
\endcode
   *\return string
   *\note throw an Exception
   */
  public function insert($p_array) {
    extract ($p_array);
    $this->verify($p_array) ;

    $owner=new own($this->db);
    $group=$this->db->get_next_seq("s_oa_group"); /* for analytic */
    $seq=$this->db->get_next_seq('s_grpt');
    $this->id=$p_jrn;

    $internal=$this->compute_internal_code($seq);
    $this->internal=$internal;

    $cust=new Fiche($this->db);
    $cust->get_by_qcode($e_client);
    $sposte=$cust->strAttribut(ATTR_DEF_ACCOUNT);
    // if 2 accounts, take only the credit Supplier
    if ( strpos($sposte,',') != 0 ) {
      $array=explode(',',$sposte);
      $poste=$array[1];
    } else {
      $poste=$sposte;
    }

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
      $this->db->start();
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
	$fiche=new Fiche($this->db);
	$fiche->get_by_qcode(${"e_march".$i});

	/* tva */
	if ($owner->MY_TVA_USE=='Y') {
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
	if ( $owner->MY_TVA_USE=='Y') {
	  $acc_amount->set_parameter('amount_vat_rate',$oTva->get_parameter('rate'));
	  if ( strlen(trim(${'e_march'.$i.'_tva_amount'})) ==0) {
	    $acc_amount->compute_vat();

	  } else {
	    $acc_amount->amount_vat= ${'e_march'.$i.'_tva_amount'};

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

	/* get the account and explode if necessary */
	$sposte=$fiche->strAttribut(ATTR_DEF_ACCOUNT);
	// if 2 accounts, take only the debit one for customer
	if ( strpos($sposte,',') != 0 ) {
	  $array=explode(',',$sposte);
	  $poste_val=$array[0];
	} else {
	  $poste_val=$sposte;
	}

	$acc_operation->poste=$poste_val;
	$acc_operation->amount=$acc_amount->amount;
	$acc_operation->qcode=${"e_march".$i};
	if( $acc_amount->amount > 0 ) $tot_debit=bcadd($tot_debit,$acc_amount->amount);

	$j_id=$acc_operation->insert_jrnx();

	/* Compute sum vat */

	if ( $owner->MY_TVA_USE=='Y') {
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

	if ( $owner->MY_ANALYTIC != "nu" )
	  {
	    // for each item, insert into operation_analytique */
	    $op=new Anc_Operation($this->db);
	    $op->oa_group=$group;
	    $op->j_id=$j_id;
	    $op->oa_date=$e_date;
	    $op->oa_debit=($amount < 0 )?'t':'f';
	    $op->oa_description=FormatString($e_comm);
	    $op->save_form_plan($_POST,$i,$j_id);
	  }
	// insert into quant_purchase
	//-----
	if ( $owner->MY_TVA_USE=='Y') {
	  $r=$this->db->exec_sql("select insert_quant_purchase ".
		     "(null".
		     ",".$j_id.		 /* 2 */
		     ",'".${"e_march".$i}."'". /* 3 */
		     ",".${"e_quant".$i}.",".  /* 4 */
		     round($amount,2).	       /* 5 */
		     ",".$acc_amount->amount_vat. /* 6 */
		     ",".$oTva->get_parameter('id'). /* 7 */
		     ",".$acc_amount->amount_nd.     /* 8 */
		     ",".$acc_amount->nd_vat.	     /* 9 */
		     ",".$acc_amount->nd_ded_vat.    /* 10 */
		     ",".$acc_amount->amount_perso.  /* 11 */
		     ",'".$e_client."')");	     /* 12 */

	} else {
	  $r=$this->db->exec_sql("select insert_quant_purchase ".
		     "(null".
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

    if ( $owner->MY_TVA_USE=='Y') {
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
    $this->jr_id=$acc_operation->insert_jrn();
    $this->pj=$acc_operation->set_pj();

    // Set Internal code
    $this->grpt_id=$seq;
    $this->update_internal_code($internal);
    /* update quant_purchase */
    $this->db->exec_sql('update quant_purchase set qp_internal = $1 where j_id in (select j_id from jrnx where j_grpt=$2)',
			array($internal,$seq));
  
    /* if e_suggest != e_pj then do not increment sequence */
      if ( strcmp($e_pj,$e_pj_suggest) == 0 && strlen(trim($e_pj)) != 0 ) {
      	$this->inc_seq_pj();
      }

    /* Save the attachment */
    if ( isset ($_FILES)) {
      if ( sizeof($_FILES) != 0 )
	$this->db->save_upload_document($seq);
    }
    $str_file="";
    /* Generate an document  and save it into the database (Note de frais only)
     */
    if ( isset($_POST['gen_invoice']) && $e_mp != 0) {
      $this->create_document($internal,$p_array);
      $p_array['e_client']=${'e_mp_qcode_'.$e_mp};
      $this->doc= _('Document généré');
      $this->doc.='<A style="display:inline" HREF="show_pj.php?'.dossier::get().'&jr_grpt_id='.$seq.'&jrn='.$this->id.'">'._('Note de frais').'</A>';
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

      $acfiche = new Fiche($this->db);
      $acfiche->get_by_qcode($fqcode);

      /* jrnx */
      $acseq=$this->db->get_next_seq('s_grpt');
      $acjrn=new Acc_Ledger($this->db,$mp->get_parameter('ledger'));
      $acinternal=$acjrn->compute_internal_code($acseq);

      /* Insert paid by  */
      $acc_pay=new Acc_Operation($this->db);
      $acc_pay->date=$e_date;

      /* get the account and explode if necessary */
      $sposte=$acfiche->strAttribut(ATTR_DEF_ACCOUNT);
      // if 2 accounts, take only the debit one for customer
      if ( strpos($sposte,',') != 0 ) {
	$array=explode(',',$sposte);
	$poste_val=$array[1];
      } else {
	$poste_val=$sposte;
      }
      $acc_pay->poste=$poste_val;
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
      $Res=$this->db->exec_sql("update jrn set jr_rapt='paid' where jr_id=$1",array($r1));

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
	$this->db->rollback();
	exit();
      }
    $this->db->commit();
    return $internal;
  }

  public function update() {
    echo "<h2> Acc_Ledger_Purchase::update Not implemented</h2>";
  }

  public function load() {
    echo "<h2> Acc_Ledger_Purchase::load Not implemented</h2>";

  }

  public function delete() {
    echo "<h2> Acc_Ledger_Purchase::delete Not implemented</h2>";
  }
  /*!\brief display the form for entering data for invoice
   *\param $p_array is null or you can put the predef operation or the $_POST
\code
array
  'sa' => string 'n' (length=1)
  'p_action' => string 'ach' (length=3)
  'gDossier' => string '28' (length=2)
  'e_client' => string 'ASEKURA' (length=7)
  'nb_item' => string '9' (length=1)
  'p_jrn' => string '3' (length=1)
  'period' => string '126' (length=3)
  'e_comm' => string 'descriptio' (length=10)
  'e_date' => string '01.05.2010' (length=10)
  'e_ech' => string '' (length=0)
  'jrn_type' => string 'ACH' (length=3)
  'e_pj' => string 'ACH37' (length=5)
  'e_pj_suggest' => string 'ACH37' (length=5)
  'mt' => string '1273759434.5701' (length=15)
  'e_mp' => string '0' (length=1)
  'e_march0' => string 'DOC' (length=3)
  'e_march0_price' => string '2000' (length=4)
  'e_march0_tva_id' => string '3' (length=1)
  'e_march0_tva_amount' => string '120' (length=3)
  'e_quant0' => string '1' (length=1)
  'gen_invoice' => string 'on' (length=2)
  'gen_doc' => string '7' (length=1)
  'bon_comm' => string '' (length=0)
  'other_info' => string '' (length=0)
  'correct' => string 'Corriger' (length=8)
\endcode
   *\return HTML string
   */
  public function input($p_array=null) {
    if ( $p_array != null ) extract($p_array);

    $user = new User($this->db);
    $owner=new Own($this->db);
    $flag_tva=$owner->MY_TVA_USE;
    /* Add button */
    $f_add_button=new IButton('add_card');
    $f_add_button->label=_('Créer une nouvelle fiche');
    $f_add_button->set_attribute('ipopup','ipop_newcard');
    $f_add_button->set_attribute('jrn',$this->id);
    $f_add_button->javascript=" select_card_type(this);";

    $f_add_button2=new IButton('add_card2');
    $f_add_button2->label=_('Créer une nouvelle fiche');
    $f_add_button2->set_attribute('ipopup','ipop_newcard');
    $f_add_button2->set_attribute('filter',$this->get_all_fiche_def ());
    //    $f_add_button2->set_attribute('jrn',$this->id);
    $f_add_button2->javascript=" select_card_type(this);";

    $str_add_button=$f_add_button->input();
    $str_add_button2=$f_add_button2->input();

    // The first day of the periode
    $oPeriode=new Periode($this->db);
    list ($l_date_start,$l_date_end)=$oPeriode->get_date_limit($user->get_periode());
    if (  $owner->MY_DATE_SUGGEST=='Y' )
      $op_date=( ! isset($e_date) ) ?$l_date_start:$e_date;
    else 
      $op_date=( ! isset($e_date) ) ?'':$e_date;

    $e_ech=(isset($e_ech))?$e_ech:"";
    $e_comm=(isset($e_comm))?$e_comm:"";

    $r="";
    $r.=dossier::hidden();
    $f_legend=_("En-tête facture fournisseur");
    $f_legend_detail=_("Détail articles acheté");

    //  Date
    //--
    $Date=new IDate();
    $Date->setReadOnly(false);
    $Date->table=1;
    $Date->tabindex=1;
    $f_date=$Date->input("e_date",$op_date);
    // Payment limit
    //--
    $Echeance=new IDate();
    $Echeance->setReadOnly(false);
    $Echeance->tabindex=2;
    $label=HtmlInput::infobulle(4);
    $f_echeance=$Echeance->input('e_ech',$e_ech,'Echéance'.$label);
    $f_periode="";
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
		try {
		  $l_form_per=$period->input();
		} catch (Exception $e) {
		  if ($e->getCode() == 1 ) {
		    echo _("Aucune période ouverte");
		    exit();
		  }
		}

		$r.="<td>";
	    $label=HtmlInput::infobulle(3);
	    $f_periode=_("Période comptable")." $label ".$l_form_per;
	}
    // Ledger (p_jrn)
    //--
    /* if we suggest the next pj, then we need a javascript */
    $add_js="";
    if ( $owner->MY_PJ_SUGGEST=='Y') {
      $add_js="update_pj();";
    }
    $add_js.='get_last_date();';

    $wLedger=$this->select_ledger('ACH',2);
    if ($wLedger == null) exit (_('Pas de journal disponible'));
    $wLedger->javascript="onChange='update_predef(\"ach\",\"f\");$add_js'";
    $label=" Journal ".HtmlInput::infobulle(2) ;

    $f_jrn=$wLedger->input();

    // Comment
    //--
    $Commentaire=new IText();
    $Commentaire->table=0;
    $Commentaire->setReadOnly(false);
    $Commentaire->size=60;
    $Commentaire->tabindex=3;
    $label=HtmlInput::infobulle(1) ;
    $f_desc=$label.$Commentaire->input("e_comm",h($e_comm));

    // PJ
    //--
    /* suggest PJ ? */
    $default_pj='';
    if ( $owner->MY_PJ_SUGGEST=='Y') {
      $default_pj=$this->guess_pj();
    }

    $pj=new IText();
    $pj->value=(isset($e_pj))?$e_pj:$default_pj;


    $pj->table=0;
    $pj->name="e_pj";
    $pj->size=10;
    $pj->readonly=false;

    $f_pj=$pj->input().HtmlInput::hidden('e_pj_suggest',$default_pj);

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
      $fClient=new Fiche($this->db);
      $fClient->get_by_qcode($e_client);
      $e_client_label=$fClient->strAttribut(ATTR_DEF_NAME).' '.
	' Adresse : '.$fClient->strAttribut(ATTR_DEF_ADRESS).' '.
	$fClient->strAttribut(ATTR_DEF_CP).' '.
	$fClient->strAttribut(ATTR_DEF_CITY).' ';


    }

    $W1=new ICard();
    $W1->label=_("Fournisseur ").HtmlInput::infobulle(0) ;
    $W1->name="e_client";
    $W1->tabindex=3;
    $W1->value=$e_client;
    $W1->table=0;
    $W1->set_dblclick("fill_ipopcard(this);");
    $W1->set_attribute('ipopup','ipopcard');

    // name of the field to update with the name of the card
    $W1->set_attribute('label','e_client_label');
    // name of the field to update with the name of the card
    $W1->set_attribute('typecard','cred');
    $W1->extra='cred';
    // Add the callback function to filter the card on the jrn
    $W1->set_callback('filter_card');
    $W1->set_function('fill_data');
    $W1->javascript=sprintf(' onchange="fill_data_onchange(\'%s\');" ',
			    $W1->name);
    $f_client_qcode=$W1->input();
    $client_label=new ISpan();
    $client_label->table=0;
    $f_client=$client_label->input("e_client_label",$e_client_label);
    $f_client_bt=$W1->search();


    // Record the current number of article
    $Hid=new IHidden();
    $p_article= ( isset ($nb_item))?$nb_item:MAX_ARTICLE;
    $r.=$Hid->input("nb_item",$p_article);
    $e_comment=(isset($e_comment))?$e_comment:"";
    $max=($p_article < MAX_ARTICLE)?MAX_ARTICLE:$p_article;

    // For each article
    //--
    for ($i=0;$i< $max ;$i++) {
      // Code id, price & vat code
      //--
      $march=(isset(${"e_march$i"}))?${"e_march$i"}:"";
      $march_price=(isset(${"e_march".$i."_price"}))?${"e_march".$i."_price"}:"";
      /* use vat */
      if ( $owner->MY_TVA_USE=='Y') {
	$march_tva_id=(isset(${"e_march$i"."_tva_id"}))?${"e_march$i"."_tva_id"}:"";
	$march_tva_amount=(isset(${"e_march$i"."_tva_amount"}))?${"e_march$i"."_tva_amount"}:"";
      }



      $march_label="&nbsp;";
      // retrieve the tva label and name
      //--
      if ( strlen(trim($march))!=0 ) {
	$fMarch=new Fiche($this->db);
	$fMarch->get_by_qcode($march);
	$march_label=$fMarch->strAttribut(ATTR_DEF_NAME);
	/* vat use */
	if ( ! isset($march_tva_id) && $owner->MY_TVA_USE=='Y' )
	  $march_tva_id=$fMarch->strAttribut(ATTR_DEF_TVA);
      }
      // Show input
      //--
      $W1=new ICard();
      $W1->label="";
      $W1->name="e_march".$i;
      $W1->value=$march;
      $W1->table=1;
      $W1->set_dblclick("fill_ipopcard(this);");
      $W1->set_attribute('ipopup','ipopcard');

      $W1->set_attribute('typecard','deb');
      $W1->extra='deb';  // debits
       // name of the field to update with the name of the card
      $W1->set_attribute('label','e_march'.$i.'_label');
      // name of the field with the price
      $W1->set_attribute('purchase','e_march'.$i.'_price');
      // name of the field with the TVA_ID
      $W1->set_attribute('tvaid','e_march'.$i.'_tva_id');
      // Add the callback function to filter the card on the jrn
      $W1->set_callback('filter_card');
      $W1->set_function('fill_data');
      $W1->javascript=sprintf(' onchange="fill_data_onchange(\'%s\');" ',
			    $W1->name);
      $W1->readonly=false;
      $array[$i]['quick_code']=$W1->input();
      $array[$i]['bt']=$W1->search();

      $array[$i]['hidden']='';
      // For computing we need some hidden field for holding the value
      if ( $owner->MY_TVA_USE=='Y') {
	$array[$i]['hidden'].=HtmlInput::hidden('tva_march'.$i,0);
      }

      if ( $owner->MY_TVA_USE=='Y')
	$tvac=new INum('tvac_march'.$i);
      else
	$tvac=new IHidden('tvac_march'.$i);

      $tvac->readOnly=1;
      $tvac->value=0;
      $array[$i]['tvac']=$tvac->input();

      $htva=new INum('htva_march'.$i);
      $htva->readOnly=1;

      $htva->value=0;
      $array[$i]['htva']=$htva->input();

      $Span=new ISpan();
      $Span->setReadOnly(false);
      // card's name, price
      //--
      $array[$i]['denom']=$Span->input("e_march".$i."_label",$march_label);
      // price
      $Price=new INum();
      $Price->setReadOnly(false);
      $Price->size=9;
      $Price->javascript="onBlur='format_number(this);clean_tva($i);compute_ledger($i)'";
      $array[$i]['pu']=$Price->input("e_march".$i."_price",$march_price);
      if ( $owner->MY_TVA_USE=='Y') {

	// vat label
	//--
	$Tva=new ITva_Popup($this->db);
	$Tva->js="onblur=\"format_number(this);onChange=clean_tva($i);compute_ledger($i)\"";
	$Tva->in_table=true;
	$Tva->set_attribute('compute',$i);
	$Tva->value=$march_tva_id;
	$array[$i]['tva']=$Tva->input("e_march$i"."_tva_id");

	// Tva_amount

	// price
	$Tva_amount=new INum();
	$Tva_amount->setReadOnly(false);
	$Tva_amount->size=9;
	$Tva_amount->javascript="onBlur='format_number(this);compute_ledger($i)'";
	$array[$i]['amount_tva']=$Tva_amount->input("e_march".$i."_tva_amount",$march_tva_amount);
      }
      // quantity
      //--
      $quant=(isset(${"e_quant$i"}))?${"e_quant$i"}:"1";
      $Quantity=new INum();
      $Quantity->setReadOnly(false);
      $Quantity->size=9;
      $Quantity->javascript="onChange=format_number(this);clean_tva($i);compute_ledger($i)";
      $array[$i]['quantity']=$Quantity->input("e_quant".$i,$quant);

    }
    $f_type=_('Fournisseur');
    // show compute
    $cal=new IButton('calc');
    $cal->label='Calculatrice';
    $cal->javascript=" showIPopup('compute') ";
    $str_cal_button=$cal->input();

    ob_start();
    require_once('template/form_ledger_detail.php');
    $r.=ob_get_contents();
    ob_clean();

  // Set correctly the REQUEST param for jrn_type
  $r.= HtmlInput::hidden('jrn_type','ACH');
  $r.= HtmlInput::button('add_item',_('Ajout article'),      ' onClick="ledger_add_row()"');



    /* if we suggest the pj n# the run the script */
    if ( $owner->MY_PJ_SUGGEST=='Y') {
      $r.='<script> update_pj();</script>';
    }
    return $r;
  }
  function input_paid() {
    $r='';
    $r.='<fieldset>';
    $r.='<legend> '._('Payé par').' </legend>';
    $mp=new Acc_Payment($this->db);
    $mp->set_parameter('type','ACH');
    $r.=$mp->select();
    $r.='</fieldset>';
    return $r;
  }

  /*!\brief show the summary of the operation and propose to save it
   *\param array contains normally $_POST. It proposes also to save
   * the Analytic accountancy
   *\return string
   *\todo verify also the vat
   */
  function confirm($p_array) {
    extract ($p_array);
    $this->verify($p_array) ;
    $anc=null;
    // to show a select list for the analytic
    // if analytic is op (optionnel) there is a blank line
    $owner = new Own($this->db);

    bcscale(4);
    $client=new Fiche($this->db);
    $client->get_by_qcode($e_client,true);

    $client_name=h($client->getName().
      ' '.$client->strAttribut(ATTR_DEF_ADRESS).' '.
      $client->strAttribut(ATTR_DEF_CP).' '.
      $client->strAttribut(ATTR_DEF_CITY));
    $lPeriode=new Periode($this->db);
	if ($this->check_periode() == true) {
			$lPeriode->p_id=$period;
		} else {
			$lPeriode->find_periode($e_date);
		}
    $date_limit=$lPeriode->get_date_limit();
    $r="";
    $r.="<fieldset>";
    $r.="<legend>"._('En-tête facture fournisseur')."  </legend>";
    $r.='<TABLE  width="100%">';
    $r.='<tr>';
    $r.='<td> '._('Date').' '.$e_date.'</td>';
    $r.='<td>'._('Echeance').' '.$e_ech.'</td>';
    $r.='<td> '._('Période Comptable').' '.$date_limit['p_start'].'-'.$date_limit['p_end'].'</td>';
    $r.='<tr>';
    $r.='<td> '._('Journal').' '.h($this->get_name()).'</td>';
    $r.='</tr>';
    $r.='<tr>';
    $r.='<td colspan="2"> '._('Libellé').' '.h($e_comm).'</td><td> Pj :'.h($e_pj).'</td>';
    $r.='</tr>';
    $r.='<tr>';
    $r.='<td colspan="3"> '._('Fournisseur').' '.h($e_client.':'.$client_name).'</td>';
    $r.='</tr>';
    $r.='</table>';
    $r.='</fieldset>';
    $r.='<fieldset><legend>'._('Détail articles achetés').'</legend>';
    $r.='<table width="100%" border="0">';
    $r.='<TR>';
    $r.="<th>Code</th>";
    $r.="<th>"._('Dénomination')."</th>";
    $r.="<th>"._('prix')."</th>";
        /* vat use */
    if ( $owner->MY_TVA_USE=='Y') {
      $r.="<th>"._('quantité')."</th>";
      $r.="<th>tva</th>";
      $r.='<th> '._('Montant TVA').'</th>';
      $r.='<th>'._('Montant HTVA').'</th>';
    } else {
      $r.="<th>"._('quantité')."</th>";
      $r.='<th> '._('Total')."</th>";
    }

    /* if we use the AC */
    if ($owner->MY_ANALYTIC!='nu') {
      $anc=new Anc_Plan($this->db);
      $a_anc=$anc->get_list();
      $x=count($a_anc);
      /* set the width of the col */
      $r.='<th colspan="'.$x.'">'._('Compt. Analytique').'</th>';

      /* add hidden variables pa[] to hold the value of pa_id */
      $r.=Anc_Plan::hidden($a_anc);
    }

    $r.='</tr>';
    $tot_amount=0.0;
    $tot_tva=0.0;
    //--
    // For each item
    //--
    for ($i = 0; $i < $nb_item;$i++) {
      if ( strlen(trim(${"e_march".$i})) == 0 ) continue;

      /* retrieve information for card */
      $fiche=new Fiche($this->db);
      $fiche->get_by_qcode(${"e_march".$i});
      $fiche_name=h($fiche->getName());

      if ( $owner->MY_TVA_USE=='Y') {
	$idx_tva=${"e_march".$i."_tva_id"};
	$oTva=new Acc_Tva($this->db);
	$oTva->set_parameter('id',$idx_tva);
	$oTva->load();
      }
      $amount=bcmul(${"e_march".$i."_price"},${'e_quant'.$i});

      if ( $owner->MY_TVA_USE=='Y') {
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
	  if ($owner->MY_TVA_USE == 'Y') {
		  $r.='<td align="right">';
		  $r.=$oTva->get_parameter('label');
		  $r.='</td>';
		  $r.='<td align="right">';
		  $r.=$tva_item;
		  $r.='</td>';
	  }
      $r.='<td align="right">';
      $r.=round($amount,2);
      $r.='</td>';

      // encode the pa
      if ( $owner->MY_ANALYTIC!='nu') // use of AA
	{
	  // show form
	  $anc_op=new Anc_Operation($this->db);
	  $null=($owner->MY_ANALYTIC=='op')?1:0;
	  $r.='<td>';
	  $p_mode=1;
	  $p_array['pa_id']=$a_anc;
	  /* op is the operation it contains either a sequence or a jrnx.j_id */
	  $r.=HtmlInput::hidden('op[]=',$i);
	  $r.=$anc_op->display_form_plan($p_array,$null,$p_mode,$i,$amount);
	  $r.='</td>';
	}


      $r.='</tr>';

    }


    $r.='</table>';
      if ( $owner->MY_ANALYTIC!='nu') // use of AA
	$r.='<input type="button" value="'._('verifie CA').'" onClick="verify_ca(\'ok\');">';
    $r.='</fieldset>';


    $r.='<fieldset> <legend>Totaux</legend>';
    $tot=round(bcadd($tot_amount,$tot_tva),2);
    $r.='<div style="position:float;float:left;text-align:right;padding-left:5%;padding-right:5%;color:blue;font-size:1.2em;font-weight:bold">';
    $r.='<br>'._('Total HTVA');
	if ($owner->MY_TVA_USE=='Y') {
	    foreach ($tva as $i=>$value) {
	      $oTva->set_parameter('id',$i);
	      $oTva->load();

	      $r.='<br>  '._('TVA à ').$oTva->get_parameter('label');
	      $r.='<br>'._('Total TVA');
	    }

	    $r.='<br>'._('Total TVAC');
	}
    $r.="</div>";


    $r.='<div style="position:float;float:left;text-align:right;color:blue;font-size:1.2em;font-weight:bold">';
    $r.='<br><span id="htva">'.$tot_amount.'</span>';

	if ( $owner->MY_TVA_USE=='Y') {
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
      if ( $owner->MY_TVA_USE=='Y' ) {
	$r.=HtmlInput::hidden("e_march".$i."_tva_id",${"e_march".$i."_tva_id"});
	$r.=HtmlInput::hidden('e_march'.$i.'_tva_amount', ${'e_march'.$i.'_tva_amount'});
      }
      $r.=HtmlInput::hidden("e_quant".$i,${"e_quant".$i});

    }
    // check for upload piece
    $r.=HtmlInput::warnbulle(12);
    $r.=$this->extra_info();

    return $r;
  }

  /*!\brief the function extra info allows to
   * - add a attachment
   * - generate an invoice
   * - insert extra info
   *\return html string
   */
  public function extra_info() {
    $r="";
    $r.='<div style="position:float;float:left;width:50%;text-align:right;line-height:3em;">';
    $r.='<fieldset> <legend> '._('Document à générer').'</legend>';
       // check for upload piece
    $file=new IFile();
    $file->table=0;
    $r.=_("Ajoutez une pièce justificative ");
    $r.=$file->input("pj","");

    if ( $this->db->count_sql("select md_id,md_name from document_modele where md_affect='ACH'") > 0 )
      {

	$r.='<hr>';
	$r.=_('ou générer un document').' <input type="checkbox" name="gen_invoice" CHECKED>';
	// We propose to generate  the note of fee
	$doc_gen=new ISelect();
	$doc_gen->name="gen_doc";
	$doc_gen->value=$this->db->make_array(
				   "select md_id,md_name ".
				   " from document_modele where md_affect='ACH'");
	$r.=$doc_gen->input().'<br>';
      }
    $r.='<br>';
    $obj=new IText();
    $r.=_('Numero de bon de commande : ').$obj->input('bon_comm').'<br>';
    $r.=_('Autre information : ').$obj->input('other_info').'<br>';

    $r.="</fieldset>";
    $r.='</div>';
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
    echo '<h2 class="info"> '._('Echeance dépassée').' </h2>';
    echo $list;
    echo  '<h2 class="info"> '._('Non Payée').' </h2>';
    echo $list2;
    echo $bar2;
    // Add hidden parameter
    $hid=new IHidden();

    echo '<hr>';

    if ( $m != 0 )
      echo HtmlInput::submit('paid',_('Mise à jour paiement'));


  }

}





