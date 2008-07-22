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
require_once('class_acc_ledger.php');
require_once('class_acc_compute.php');
require_once('class_anc_operation.php');
require_once('user_common.php');
require_once('class_acc_parm_code.php');
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
   *\note return an AcException if an error occurs
   */
  public function verify($p_array) {
    extract ($p_array);
    /* check if there is a customer */
    if ( strlen(trim($e_client)) == 0 ) 
      throw new AcException('Vous n\'avez pas donné de fournisseur',11);

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
    $fiche=new fiche($this->db);
    $fiche->get_by_qcode($e_client);
    if ( $fiche->empty_attribute(ATTR_DEF_ACCOUNT) == true)
      throw new AcException('La fiche '.$e_client.'n\'a pas de poste comptable',8);

    /* The account exists */
    $poste=new Acc_Account_Ledger($this->db,$fiche->strAttribut(ATTR_DEF_ACCOUNT));
    if ( $poste->load() == false ){
      throw new AcException('Pour la fiche '.$e_client.' le poste comptable ['.$poste->id.'] n\'existe pas',9);
    }

    /* Check if the card belong to the ledger */
    $fiche=new fiche ($this->db);
    $fiche->get_by_qcode($e_client,'cred');
    if ( $fiche->belong_ledger($p_jrn) !=1 )
	throw new AcException('La fiche '.$e_client.'n\'est pas accessible à ce journal',10);

    $nb=0;

    //----------------------------------------
    // foreach item
    //----------------------------------------
    for ($i=0;$i< $nb_item;$i++) {
      if ( strlen(trim(${'e_march'.$i}))== 0) continue;
      /* check if amount are numeric and */
      if ( isNumber(${'e_march'.$i.'_buy'}) == 0 )
	throw new AcException('La fiche '.${'e_march'.$i}.'a un montant invalide ['.${'e_march'.$i}.']',6);
      if ( isNumber(${'e_quant'.$i}) == 0 )
	throw new AcException('La fiche '.${'e_march'.$i}.'a une quantité invalide ['.${'e_quant'.$i}.']',7);

      /* check if all card has a ATTR_DEF_ACCOUNT*/
      $fiche=new fiche($this->db);
      $fiche->get_by_qcode(${'e_march'.$i});
      if ( $fiche->empty_attribute(ATTR_DEF_ACCOUNT) == true)
	throw new AcException('La fiche '.${'e_march'.$i}.'n\'a pas de poste comptable',8);
      /* The account exists */
      $poste=new Acc_Account_Ledger($this->db,$fiche->strAttribut(ATTR_DEF_ACCOUNT));
      if ( $poste->load() == false ){
	throw new AcException('Pour la fiche '.${'e_march'.$i}.' le poste comptable ['.$poste->id.'n\'existe pas',9);
      }
      /* Check if the card belong to the ledger */
      $fiche=new fiche ($this->db);
      $fiche->get_by_qcode(${'e_march'.$i});
      if ( $fiche->belong_ledger($p_jrn,'deb') !=1 )
	throw new AcException('La fiche '.${'e_march'.$i}.'n\'est pas accessible à ce journal',10);
      $nb++;
    }
    if ( $nb == 0 )
      throw new AcException('Il n\'y a aucune marchandise',12);
  }

  public function save() {
    echo "<h2> Acc_Ledger_Purchase::save Not implemented</h2>";
  }

  /*!\brief insert into the database, it calls first the verify function
   *\param $p_array is usually $_POST or a predefined operation
   *\return string
   *\note throw an AcException
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
    bcscale(4);
    try {
      $tot_amount=0;
      $tot_tva=0;
      StartSql($this->db);
      $tot_nd=0;
      $tot_perso=0;
      $tot_tva_nd=0;
      $tot_tva_ndded=0;

      /* Save all the items without vat and no deductible vat and expense*/
      for ($i=0;$i< $nb_item;$i++) {
	if ( strlen(trim(${'e_march'.$i})) == 0 ) continue;
	if ( ${'e_march'.$i.'_buy'} == 0 ) continue;
	if ( ${'e_quant'.$i} == 0 ) continue;
	
	/* First we save all the items without vat */
	$fiche=new fiche($this->db);
	$fiche->get_by_qcode(${"e_march".$i});
	
	/* tva */
	$idx_tva=${'e_march'.$i.'_tva_id'};
	$oTva=new Acc_Tva($this->db);
	$oTva->set_parameter('id',$idx_tva);
	$oTva->load();

	/* We have to compute all the amount thanks Acc_Compute */
	$amount=bcmul(${'e_march'.$i.'_buy'},${'e_quant'.$i});
	$acc_amount=new Acc_Compute();
	$acc_amount->check=false;
	$acc_amount->set_parameter('amount',$amount);
	$acc_amount->set_parameter('amount_vat_rate',$oTva->get_parameter('rate'));
	if ( strlen(trim(${'e_march'.$i.'_tva_amount'})) ==0) {
	  $acc_amount->compute_vat();
	  echo_debug( 'vat is computed = '.$acc_amount->amount_vat);

	} else {
	  $acc_amount->amount_vat= ${'e_march'.$i.'_tva_amount'};
	  echo_debug( 'vat is given = '.$acc_amount->amount_vat);

	}
	$tot_tva+=$acc_amount->amount_vat;

	$acc_operation=new Acc_Operation($this->db);
	$acc_operation->date=$e_date;
	$acc_operation->grpt=$seq;
	$acc_operation->jrn=$p_jrn;
	$acc_operation->type='d';
	$acc_operation->periode=$periode;
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


	$j_id=$acc_operation->insert_jrnx();

	/* Compute sum vat */


	$tva_item=$acc_amount->amount_vat;
	
	if (isset($tva[$idx_tva] ) )
	  $tva[$idx_tva]+=$tva_item;
	else
	  $tva[$idx_tva]=$tva_item;


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
    $acc_operation->periode=$periode;
    $acc_operation->qcode=${"e_client"};
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
      $j_id=$acc_operation->insert_jrnx();

    }  
    if ( $tot_perso != 0) {
      /* save op. */
      $acc_operation->type='d';
      $dna=new Acc_Parm_Code($this->db,'DEP_PRIV');
      $acc_operation->amount=$tot_perso;
      $acc_operation->poste=$dna->p_value;
      $acc_operation->qcode='';
      $j_id=$acc_operation->insert_jrnx();
      
    }  
    if ( $tot_tva_nd != 0) {
      /* save op. */
      $acc_operation->type='d';
      $acc_operation->qcode='';
      $dna=new Acc_Parm_Code($this->db,'TVA_DNA');
      $acc_operation->amount=$tot_tva_nd;
      $acc_operation->poste=$dna->p_value;
      $j_id=$acc_operation->insert_jrnx();
            
    }  
    if ( $tot_tva_ndded != 0) {
      /* save op. */
      $dna=new Acc_Parm_Code($this->db,'TVA_DED_IMPOT');

      $acc_operation->type='d';
      $acc_operation->qcode='';
      $acc_operation->amount=$tot_tva_ndded;
      $acc_operation->poste=$dna->p_value;
      $j_id=$acc_operation->insert_jrnx();

            
    }  

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
      $acc_operation->periode=$periode;
      $acc_operation->insert_jrnx();
      
    }
    /* insert into jrn */
    $acc_operation=new Acc_Operation($this->db);
    $acc_operation->date=$e_date;
    $acc_operation->echeance=$e_ech;
    $acc_operation->amount=round($tot_amount+$tot_tva,2);
    $acc_operation->desc=$e_comm;
    $acc_operation->grpt=$seq;
    $acc_operation->jrn=$p_jrn;
    $acc_operation->periode=$periode;
    $acc_operation->insert_jrn();

    // Set Internal code 
    $this->grpt_id=$seq;
    $this->update_internal_code($internal);
    
    /* Save the attachment */
    if ( isset ($_FILES)) {
      if ( sizeof($_FILES) != 0 )
	save_upload_document($this->db,$seq);
    }
  }//end try
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
    $w=new widget("select");
    $User=new User($this->db); 
    // filter on the current year
    $filter_year=" where p_exercice='".$User->get_exercice()."'";
    
    $periode_start=make_array($this->db,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by  p_start,p_end",1);
    $current=(isset($_GET['p_periode']))?$_GET['p_periode']:$User->get_periode();
    $w->selected=$current;
    
    echo 'Période  '.$w->IOValue("p_periode",$periode_start);
    $wLedger=$this->select_ledger('ACH',2);
    echo 'Journal '.$wLedger->IOValue();
    $qcode=(isset($_GET['qcode']))?$_GET['qcode']:"";
    $this->type='ACH';
    $all=$this->get_all_fiche_def();
    echo JS_SEARCH_CARD;
    echo JS_PROTOTYPE;
    echo JS_AJAX_FICHE;
    $w=new widget('js_search_only');
    $w->name='qcode';
    $w->value=$qcode;
    $w->label='';
    $w->extra=$all;
    $w->extra2='QuickCode';
    $w->table=0;
    $sp= new widget("span");
    echo $sp->IOValue("qcode_label","",$qcode);
    echo $w->IOValue();

    echo widget::submit('gl_submit','Recherche');
 // Show list of sell
 // Date - date of payment - Customer - amount
    if ( $current == -1) {
      $cond=" and jr_tech_per in (select p_id from parm_periode where p_exercice='".$User->get_exercice()."')";
    } else {
      $cond=" and jr_tech_per=".$current;
    }
    
    $sql=SQL_LIST_ALL_INVOICE.$cond." and jr_def_id=".$this->id ;
    $step=$_SESSION['g_pagesize'];
    $page=(isset($_GET['offset']))?$_GET['page']:1;
    $offset=(isset($_GET['offset']))?$_GET['offset']:0;
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

    list($max_line,$list)=ListJrn($this->db,$this->id,$sql,null,$offset,1);
    $bar=jrn_navigation_bar($offset,$max_line,$step,$page);
    
    echo "<hr>$bar";
    echo '<form method="POST">';
    echo dossier::hidden();  
    $hid=new widget("hidden");
    
    echo $list;
    if ( $max_line !=0 )
      echo widget::submit('paid','Mise à jour paiement');
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

    // The first day of the periode 
    $oPeriode=new Periode($this->db);
    list ($l_date_start,$l_date_end)=$oPeriode->get_date_limit($user->get_periode());

    $op_date=( ! isset($e_date) ) ?$l_date_start:$e_date;
    $e_ech=(isset($e_ech))?$e_ech:"";
    $e_comm=(isset($e_comm))?$e_comm:"";
    

    $r="";

    $r.=JS_INFOBULLE;
    $r.=JS_SEARCH_CARD;
    $r.=JS_SHOW_TVA;    
    $r.=JS_TVA;
    $r.=JS_AJAX_FICHE;

  
    $r.=dossier::hidden();
    $r.=widget::hidden('phpsessid',$_REQUEST['PHPSESSID']);  
    $r.="<fieldset>";
    $r.="<legend>En-tête facture fournisseur  </legend>";
    
    $r.='<TABLE  width="100%">';
    //  Date
    //--
    $Date=new widget("js_date");
    $Date->SetReadOnly(false);
    $Date->table=1;
    $Date->tabindex=1;
    $r.="<tr>";
    $r.=$Date->IOValue("e_date",$op_date,"Date");
    // Payment limit
    //--
    $Echeance=new widget("js_date");
    $Echeance->SetReadOnly(false);
    $Echeance->table=1;
    $Echeance->tabindex=2;
    $label=widget::infobulle(4);
    $r.=$Echeance->IOValue("e_ech",$e_ech,"Echeance ".$label);

    // Periode 
    //--
    $l_user_per=$user->get_periode();
    $def=(isset($periode))?$periode:$l_user_per;
    $l_form_per=FormPeriode($this->db,$def,OPEN);
    $r.="<td class=\"input_text\">";
    $label=widget::infobulle(3);
    $r.="Période comptable $label</td><td>".$l_form_per;
    $r.="</td>";
    $r.="</tr><tr>";
    // Ledger (p_jrn)
    //--
    $wLedger=$this->select_ledger('ACH',2);

    $wLedger->table=1;
    $wLedger->javascript="onChange='update_predef(\"ach\",\"f\")'";
    $wLedger->label=" Journal ".widget::infobulle(2) ;

    $r.=$wLedger->IOValue();
    // Comment
    //--
    $Commentaire=new widget("text");
    $Commentaire->table=0;
    $Commentaire->SetReadOnly(false);
    $Commentaire->size=80;
    $Commentaire->tabindex=3;
    $label=" Description ".widget::infobulle(1) ;
    $r.="<tr>";
    $r.='<td class="input_text">'.$label.'</td>'.
      '<td colspan="5">'.$Commentaire->IOValue("e_comm",$e_comm)."</td>";
    $r.="</tr>";

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
    
    $W1=new widget("js_search_only");
    $W1->label="Client ".widget::infobulle(0) ;
    $W1->name="e_client";
    $W1->tabindex=3;
    $W1->value=$e_client;
    $W1->table=0;
    $W1->extra=$fiche;  // list of card
    $W1->extra2="Recherche";
    $r.='<TR><td colspan="5" >'.$W1->IOValue();
    $client_label=new widget("span");
    $client_label->table=0;
    $r.=$client_label->IOValue("e_client_label",$e_client_label)."</TD></TR>";
    
    $r.="</TABLE>";
    
    // Record the current number of article
    $Hid=new widget('hidden');
    $p_article= ( isset ($p_article))?$p_article:MAX_ARTICLE;
    $r.=$Hid->IOValue("nb_item",$p_article);
    $e_comment=(isset($e_comment))?$e_comment:"";
    $r.="</fieldset>";
    
    // Start the div for item to sell
    $r.="<DIV>";
    $r.='<fieldset><legend>D&eacute;tail articles achetés</legend>';
    $r.='<TABLE ID="sold_item">';
    $r.='<TR>';
    $r.="<th></th>";
    $label=widget::infobulle(0) ;
    $r.="<th>Code $label</th>";
    $r.="<th>D&eacute;nomination</th>";
    $label=widget::infobulle(6) ;
    $r.="<th>$label prix / unité htva </th>";
    $r.="<th>tva</th>";
    $label=widget::infobulle(8) ;
    $r.="<th> $label Total  tva</th>";
    $r.="<th>quantit&eacute;</th>";

    $r.='</TR>';
    // For each article
    //--
    for ($i=0;$i< MAX_ARTICLE;$i++) {
      // Code id, price & vat code
      //--
      $march=(isset(${"e_march$i"}))?${"e_march$i"}:"";
      $march_buy=(isset(${"e_march".$i."_buy"}))?${"e_march".$i."_buy"}:"";
      $march_tva_id=(isset(${"e_march$i"."_tva_id"}))?${"e_march$i"."_tva_id"}:"";
      $march_tva_amount=(isset(${"e_march$i"."_tva_amount"}))?${"e_march$i"."_tva_amount"}:"";
      

      $march_label="&nbsp;";
      // retrieve the tva label and name
      //--
      if ( strlen(trim($march))!=0 ) {
	$fMarch=new fiche($this->db);
	$fMarch->get_by_qcode($march);
	$march_label=$fMarch->strAttribut(ATTR_DEF_NAME);
	if ( ! isset($march_tva_id) )
	  $march_tva_id=$fMarch->strAttribut(ATTR_DEF_TVA);
      }
      // Show input
      //--
      $W1=new widget("js_search_only");
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
      $r.="<TR>".$W1->IOValue();
      // For computing we need some hidden field for holding the value
      $r.=widget::hidden('tva_march'.$i,0);      
      $r.=widget::hidden('htva_march'.$i,0);      
      $r.=widget::hidden('tvac_march'.$i,0);      
      $r.="</TD>";
      $Span=new widget ("span");
      $Span->SetReadOnly(false);
      // card's name, price
      //--
      $r.='<TD style="width:55%;border-bottom:1px dotted grey;">'.$Span->IOValue("e_march".$i."_label",$march_label)."</TD>";
      // price
      $Price=new widget("text");
      $Price->SetReadOnly(false);
      $Price->table=1;
      $Price->size=9;
      $Price->javascript="onBlur='compute_purchase($i)'";
      $r.=$Price->IOValue("e_march".$i."_buy",$march_buy);
      // vat label
      //--
      $select_tva=make_array($this->db,"select tva_id,tva_label from tva_rate order by tva_rate desc",0);
      $Tva=new widget("select");
      $Tva->javascript="onChange=\"clean_tva($i);compute_purchase($i);\"";
      $Tva->table=1;
      $Tva->selected=$march_tva_id;
      $r.=$Tva->IOValue("e_march$i"."_tva_id",$select_tva);
      // Tva_amount
      // price
      $Tva_amount=new widget("text");
      $Tva_amount->SetReadOnly(false);
      $Tva_amount->table=1;
      $Tva_amount->size=9;
      $Tva_amount->javascript="onBlur='compute_purchase($i)'";
      $r.=$Tva_amount->IOValue("e_march".$i."_tva_amount",$march_tva_amount);
      
      // quantity
      //--
      $quant=(isset(${"e_quant$i"}))?${"e_quant$i"}:"1";
      $Quantity=new widget("text");
      $Quantity->SetReadOnly(false);
      $Quantity->table=1;
      $Quantity->size=9;
      $Quantity->javascript="onChange=compute_purchase($i)";
      $r.=$Quantity->IOValue("e_quant".$i,$quant);

      $r.="</tr>";
    }

    
    
    $r.="</TABLE>";
    $r.='<div style="position:float;float:right;text-align:right;padding-right:5px;font-size:1.2em;font-weight:bold;color:blue">';
    $r.=widget::button('act','Actualiser','onClick="compute_all_purchase();"');
    $r.="</div>";

    $r.='<div style="position:float;float:right;text-align:left;font-size:1.2em;font-weight:bold;color:blue" id="sum">';
    $r.='<br><span id="htva">0.0</span>';
    $r.='<br><span id="tva">0.0</span>';
    $r.='<br><span id="tvac">0.0</span>';
    $r.="</div>";



    $r.='<div style="position:float;float:right;text-align:right;padding-right:5px;font-size:1.2em;font-weight:bold;color:blue">';
    $r.='<br>Total HTVA';
    $r.='<br>Total TVA';
    $r.='<br>Total TVAC';
    $r.="</div>";

    $r.="</fieldset>";
    // Set correctly the REQUEST param for jrn_type 
    $r.=widget::hidden('jrn_type','ACH');

    $r.=widget::button('add_item','Ajout article',      ' onClick="ledger_sold_add_row()"');
    $r.=widget::submit("view_invoice","Enregistrer");
    $r.=widget::reset('Effacer ');

    $r.="</DIV>";

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

    $client_name=$client->getName().
      ' '.$client->strAttribut(ATTR_DEF_ADRESS).' '.
      $client->strAttribut(ATTR_DEF_CP).' '.
      $client->strAttribut(ATTR_DEF_CITY);
    $lPeriode=new Periode($this->db);
    $date_limit=$lPeriode->get_date_limit($periode);
    $r="";
    $r.="<fieldset>";
    $r.="<legend>En-tête facture fournisseur  </legend>";
    $r.='<TABLE  width="100%">';
    $r.='<tr>';
    $r.='<td> Date '.$e_date.'</td>';
    $r.='<td>Echeance '.$e_ech.'</td>';
    $r.='<td> Période Comptable '.$date_limit['p_start'].'-'.$date_limit['p_end'].'</td>';
    $r.='<tr>';
    $r.='<td> Journal '.$this->get_name().'</td>';
    $r.='</tr>';
    $r.='<tr>';
    $r.='<td colspan="3"> Description '.$e_comm.'</td>';
    $r.='</tr>';
    $r.='<tr>';
    $r.='<td colspan="3"> Fournisseur '.$e_client.':'.$client_name.'</td>';
    $r.='</tr>';
    $r.='</table>';
    $r.='</fieldset>';
    $r.='<fieldset><legend>D&eacute;tail articles achetés</legend>';
    $r.='<table width="100%" border="0">';
    $r.='<TR>';
    $r.="<th>Code</th>";
    $r.="<th>D&eacute;nomination</th>";
    $r.="<th>prix</th>";
    $r.="<th>tva</th>";
    $r.="<th>quantit&eacute;</th>";

    $r.='<th> Montant TVA</th>';
    $r.='<th>Montant HTVA</th>';
    $r.=($own->MY_ANALYTIC!='nu')?'<th>Compt. Analytique</th>':'';
    $r.='</tr>';
    $tot_amount=0.0;
    $tot_tva=0.0;
    for ($i = 0; $i < $nb_item;$i++) {
      if ( strlen(trim(${"e_march".$i})) == 0 ) continue;

      /* retrieve information for card */
      $fiche=new fiche($this->db);
      $fiche->get_by_qcode(${"e_march".$i});
      $fiche_name=$fiche->getName();
      $idx_tva=${"e_march".$i."_tva_id"};
      $oTva=new Acc_Tva($this->db);
      $oTva->set_parameter('id',$idx_tva);
      $oTva->load();
      $amount=bcmul(${"e_march".$i."_buy"},${'e_quant'.$i});

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
      $tot_amount=round(bcadd($tot_amount,$amount),2);
      $tot_tva=round(bcadd($tva_item,$tot_tva),2);
      $r.='<tr>';
      $r.='<td>';
      $r.=${"e_march".$i};
      $r.='</td>';
      $r.='<TD style="width:60%;border-bottom:1px dotted grey;">';
      $r.=$fiche_name;
      $r.='</td>';
      $r.='<td align="right">';
      $r.=${"e_march".$i."_buy"};
      $r.='</td>';
      $r.='<td align="right">';
      $r.=${"e_quant".$i};
      $r.='</td>';

      $r.='<td align="right">';
      $r.=$oTva->get_parameter('label');
      $r.='</td>';
      $r.='<td align="right">';
      $r.=$tva_item;
      $r.='</td>';
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

    foreach ($tva as $i=>$value) {
      $oTva->set_parameter('id',$i);
      $oTva->load();

      $r.='<br>  TVA à '.$oTva->get_parameter('label');
    }
    $r.='<br>Total TVA';
    $r.='<br>Total TVAC';
    $r.="</div>";


    $r.='<div style="position:float;float:left;text-align:right;color:blue;font-size:1.2em;font-weight:bold">';
    $r.='<br><span id="htva">'.$tot_amount.'</span>';

    foreach ($tva as $i=>$value) {
      $r.='<br>'.$tva[$i];
    }
    $r.='<br><span id="tva">'.$tot_tva.'</span>';
    $r.='<br><span id="tvac">'.$tot.'</span>';
    $r.="</div>";



    $r.='</fieldset>';
    /*  Add hidden */
    $r.=widget::hidden('e_client',$e_client);
    $r.=widget::hidden('nb_item',$nb_item);
    $r.=widget::hidden('p_jrn',$p_jrn);
    $r.=widget::hidden('periode',$periode);
    $r.=widget::hidden('e_comm',$e_comm);
    $r.=widget::hidden('e_date',$e_date);
    $r.=widget::hidden('e_ech',$e_ech);
    $r.=widget::hidden('jrn_type',$jrn_type);
    for ($i=0;$i < $nb_item;$i++) {
      $r.=widget::hidden("e_march".$i,${"e_march".$i});
      $r.=widget::hidden("e_march".$i."_buy",${"e_march".$i."_buy"});
      $r.=widget::hidden("e_march".$i."_tva_id",${"e_march".$i."_tva_id"});
      $r.=widget::hidden('e_march'.$i.'_tva_amount', ${'e_march'.$i.'_tva_amount'});
      $r.=widget::hidden("e_quant".$i,${"e_quant".$i});
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
    list($max_line,$list)=ListJrn($this->db,$this->id,$sql,null,$offset,1);
    $sql=SQL_LIST_UNPAID_INVOICE." and jr_def_id=".$this->id ;
    list($max_line2,$list2)=ListJrn($this->db,$this->id,$sql,null,$offset,1);

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
    $hid=new widget("hidden");

    echo '<hr>';

    if ( $m != 0 )
      echo widget::submit('paid','Mise à jour paiement');


  }
  /*!\brief
   *\param
   *\return
   *\note
   *\see
   *\todo
   */	
  static function test_me() {
  }
  
}




  
