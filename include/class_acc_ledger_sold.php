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
 * \brief class for the sold, herits from acc_ledger
 */
require_once("class_iselect.php");
require_once("class_icard.php");
require_once("class_ispan.php");
require_once("class_ihidden.php");
require_once("class_idate.php");
require_once("class_itext.php");
require_once("class_ifile.php");
require_once('class_acc_ledger.php');
require_once('class_acc_compute.php');
require_once('class_anc_operation.php');
require_once('user_common.php');
require_once('class_acc_payment.php');
require_once('ac_common.php');
require_once('class_own.php');
require_once('class_itva_popup.php');
require_once('class_acc_ledger_fin.php');
require_once 'class_stock_goods.php';

/*!\brief Handle the ledger of sold,
 *
 *@exception throw an exception is something is wrong
 */
class  Acc_Ledger_Sold extends Acc_Ledger
{
    function __construct ($p_cn,$p_init)
    {
        parent::__construct($p_cn,$p_init);
        $this->type='VEN';
    }
    /*!\brief verify that the data are correct before inserting or confirming
     *\param an array (usually $_POST)
     *\return String
     *\throw Exception if an error occurs
     */
    public function verify($p_array)
    {
        global $g_parameter,$g_user;
        extract ($p_array);
        /* check for a double reload */
        if (  isset($mt) && $this->db->count_sql('select jr_mt from jrn where jr_mt=$1',array($mt)) != 0 )
            throw new Exception (_('Double Encodage'),5);

        /* check if we can write into this ledger */
        if ( $g_user->check_jrn($p_jrn) != 'W' )
            throw new Exception (_('Accès interdit'),20);

        /* check if there is a customer */
        if ( strlen(trim($e_client)) == 0 )
            throw new Exception(_('Vous n\'avez pas donné de client'),11);

        /*  check if the date is valid */
        if ( isDate($e_date) == null )
        {
            throw new Exception(_('Date invalide'), 2);
        }

        $oPeriode=new Periode($this->db);
        if ( $this->check_periode() == true)
        {
            $tperiode=$period;
            /* check that the datum is in the choosen periode */
            $oPeriode->p_id=$period;
            list ($min,$max)=$oPeriode->get_date_limit();

            if ( cmpDate($e_date,$min) < 0 ||
                    cmpDate($e_date,$max) > 0)
                throw new Exception(_('Date et periode ne correspondent pas'),6);
        }
        else
        {
            $per=new Periode($this->db);
            $tperiode=$per->find_periode($e_date);
        }

        /* check if the periode is closed */
        if ( $this->is_closed($tperiode)==1 )
        {
            throw new Exception(_('Periode fermee'),6);
        }
        /* check if we are using the strict mode */
        if( $this->check_strict() == true)
        {
            /* if we use the strict mode, we get the date of the last
            operation */
            $last_date=$this->get_last_date();
            if ( $last_date != null  && cmpDate($e_date,$last_date) < 0 )
                throw new Exception(_('Vous utilisez le mode strict la dernière operation est date du ')
                                    .$last_date._(' vous ne pouvez pas encoder à une date antérieure'),13);

        }


        $fiche=new Fiche($this->db);
        $fiche->get_by_qcode($e_client);
        if ( $fiche->empty_attribute(ATTR_DEF_ACCOUNT) == true)
            throw new Exception(_('La fiche ').$e_client._('n\'a pas de poste comptable'),8);

        /* get the account and explode if necessary */
        $sposte=$fiche->strAttribut(ATTR_DEF_ACCOUNT);
        // if 2 accounts, take only the debit one for customer
        if ( strpos($sposte,',') != 0 )
        {
            $array=explode(',',$sposte);
            $poste_val=$array[0];
        }
        else
        {
            $poste_val=$sposte;
        }
        /* The account exists */

        $poste=new Acc_Account_Ledger($this->db,$poste_val);

        if ( $poste->load() == false )
        {
            throw new Exception(_('Pour la fiche ').$e_client._(' le poste comptable [').$poste->id._('] n\'existe pas'),9);
        }

        /* Check if the card belong to the ledger */
        $fiche=new Fiche ($this->db);
        $fiche->get_by_qcode($e_client,'deb');
        if ( $fiche->belong_ledger($p_jrn) !=1 )
            throw new Exception(_('La fiche ').$e_client._('n\'est pas accessible à ce journal'),10);

        $nb=0;

        //----------------------------------------
        // foreach item
        //----------------------------------------
        for ($i=0;$i< $nb_item;$i++)
        {
            if ( strlen(trim(${'e_march'.$i}))== 0) continue;
            /* check if amount are numeric and */
            if ( isNumber(${'e_march'.$i.'_price'}) == 0 )
                throw new Exception(_('La fiche ').${'e_march'.$i}._('a un montant invalide [').${'e_march'.$i}.']',6);
            if ( isNumber(${'e_quant'.$i}) == 0 )
                throw new Exception(_('La fiche ').${'e_march'.$i}._('a une quantité invalide [').${'e_quant'.$i}.']',7);
            /* check if all card has a ATTR_DEF_ACCOUNT*/
            $fiche=new Fiche($this->db);
            $fiche->get_by_qcode(${'e_march'.$i});
            if ( $fiche->empty_attribute(ATTR_DEF_ACCOUNT) == true)
                throw new Exception(_('La fiche ').${'e_march'.$i}._('n\'a pas de poste comptable'),8);

            // Check if the given tva id is valid
            if ( $g_parameter->MY_TVA_USE=='Y')
            {
                if (  isNumber(${'e_march'.$i.'_tva_id'}) == 0 )
                    throw new Exception(_('La fiche ').${'e_march'.$i}._('a un code tva invalide').' ['.${'e_march'.$i.'_tva_id'}.']',13);
                $tva_rate=new Acc_Tva($this->db);
                $tva_rate->set_parameter('id',${'e_march'.$i.'_tva_id'});
                if ( $tva_rate->load() != 0 )
                    throw new Exception(_('La fiche ').${'e_march'.$i}._('a un code tva invalide').' ['.${'e_march'.$i.'_tva_id'}.']',13);

		/*
		 * check if the accounting for VAT are valid
		 */
		$a_poste=explode(',',$tva_rate->tva_poste);

		if (
		    $this->db->get_value('select count(*) from tmp_pcmn where pcm_val=$1',array($a_poste[0])) == 0 ||
		    $this->db->get_value('select count(*) from tmp_pcmn where pcm_val=$1',array($a_poste[1])) == 0 )
		  throw new Exception(_(" La TVA ".$tva_rate->tva_label." utilise des postes comptables inexistants"));
            }
            // if 2 accounts, take only the credit one
            /* The account exists */
            $sposte=$fiche->strAttribut(ATTR_DEF_ACCOUNT);

            if ( strpos($sposte,',') != 0 )
            {
                $array=explode(',',$sposte);
                $poste_val=$array[1];
            }
            else
            {
                $poste_val=$sposte;
            }
            $poste=new Acc_Account_Ledger($this->db,$poste_val);
            if ( $poste->load() == false )
            {
                throw new Exception(_('Pour la fiche ').${'e_march'.$i}._(' le poste comptable [').$poste->id._('n\'existe pas'),9);
            }
            /* Check if the card belong to the ledger */
            $fiche=new Fiche ($this->db);
            $fiche->get_by_qcode(${'e_march'.$i});
            if ( $fiche->belong_ledger($p_jrn,'cred') !=1 )
                throw new Exception(_('La fiche ').${'e_march'.$i}._('n\'est pas accessible à ce journal'),10);
            $nb++;
        }
        if ( $nb == 0 )
            throw new Exception(_('Il n\'y a aucune marchandise'),12);
        //------------------------------------------------------
        // The "Paid By"  check
        //------------------------------------------------------

        if ($e_mp != 0 ) {
            $this->check_payment($e_mp,${"e_mp_qcode_".$e_mp});
        }

    }


    /*!\brief insert into the database, it calls first the verify function,
     * change the value of this->jr_id and this->jr_internal
     * * It generates the document if gen_invoice is set and save the middle of payment if any ($e_mp)
     *
     *\param $p_array is usually $_POST or a predefined operation
     *\return string
     *\note throw an Exception
     */
    public function insert($p_array=null)
    {
        global $g_parameter;
        extract ($p_array);
        $this->verify($p_array) ;

        $group=$this->db->get_next_seq("s_oa_group"); /* for analytic */
        $seq=$this->db->get_next_seq('s_grpt');
        $this->id=$p_jrn;
        $internal=$this->compute_internal_code($seq);
        $this->internal=$internal;

        $oPeriode=new Periode($this->db);
        $check_periode=$this->check_periode();

        if ( $check_periode == true )
            $tperiode=$period;
        else
            $tperiode=$oPeriode->find_periode($e_date);

        $cust=new Fiche($this->db);
        $cust->get_by_qcode($e_client);
        $sposte=$cust->strAttribut(ATTR_DEF_ACCOUNT);

        // if 2 accounts, take only the debit one for the customer
        //
        if ( strpos($sposte,',') != 0 )
        {
            $array=explode(',',$sposte);
            $poste=$array[0];
        }
        else
        {
            $poste=$sposte;
        }

        bcscale(4);
        try
        {
            $tot_amount=0;
            $tot_tva=0;
            $tot_debit=0;
            $this->db->start();
            /* Save all the items without vat */
            for ($i=0;$i< $nb_item;$i++)
            {
				$n_both=0;
                if ( strlen(trim(${'e_march'.$i})) == 0 ) continue;
                if ( ${'e_march'.$i.'_price'} == 0 ) continue;
                if ( ${'e_quant'.$i} == 0 ) continue;

                /* First we save all the items without vat */
                $fiche=new Fiche($this->db);
                $fiche->get_by_qcode(${"e_march".$i});
                $amount=bcmul(${'e_march'.$i.'_price'},${'e_quant'.$i});
                $tot_amount=bcadd($tot_amount,$amount);
                $acc_operation=new Acc_Operation($this->db);
                $acc_operation->date=$e_date;
                $sposte=$fiche->strAttribut(ATTR_DEF_ACCOUNT);

                // if 2 accounts, take only the credit one
                if ( strpos($sposte,',') != 0 )
                {
                    $array=explode(',',$sposte);
                    $poste_val=$array[1];
                }
                else
                {
                    $poste_val=$sposte;
                }

                $acc_operation->poste=$poste_val;
                $acc_operation->amount=$amount;
                $acc_operation->grpt=$seq;
                $acc_operation->jrn=$p_jrn;
                $acc_operation->type='c';
                $acc_operation->periode=$tperiode;
                if ( $g_parameter->MY_UPDLAB=='Y')
                    $acc_operation->desc=strip_tags(${"e_march".$i."_label"});
                else
                    $acc_operation->desc=null;

                $acc_operation->qcode=${"e_march".$i};
                if ( $amount < 0 ) $tot_debit=bcadd($tot_debit,abs($amount));

                $j_id=$acc_operation->insert_jrnx();

                if ($g_parameter->MY_TVA_USE == 'Y' )
                {
                    /* Compute sum vat */
                    $oTva=new Acc_Tva($this->db);
                    $idx_tva=${'e_march'.$i.'_tva_id'};
                    $tva_item=${'e_march'.$i.'_tva_amount'};
		    $oTva->set_parameter("id",$idx_tva);
		    $oTva->load();
                    /* if empty then we need to compute it */
                    if (trim($tva_item)=='')
                    {
                        /* retrieve tva */
                        $l=new Acc_Tva($this->db,$idx_tva);
                        $l->load();
                        $tva_item=bcmul($amount,$l->get_parameter('rate'));
                    }
                    if (isset($tva[$idx_tva] ) )
                        $tva[$idx_tva]+=$tva_item;
                    else
                        $tva[$idx_tva]=$tva_item;
                    if ($oTva->get_parameter("both_side")==0) $tot_tva=round(bcadd($tva_item,$tot_tva),2);
                    else $n_both=$tva_item;
                }

                /* Save the stock */
                /* if the quantity is < 0 then the stock increase (return of
                 *  material)
                 */
                $nNeg=($
                       {"e_quant".$i
                       }
                       <0)?-1:1;

                // always save quantity but in withStock we can find
                // what card need a stock management
                if ( $g_parameter->MY_STOCK='Y' && isset ($repo))
                    Stock_Goods::insert_goods($this->db,array('j_id'=>$j_id,'goods'=>${'e_march'.$i},'quant'=>$nNeg*${'e_quant'.$i},'dir'=>'c','repo'=>$repo)) ;


                if ( $g_parameter->MY_ANALYTIC != "nu" )
                {
                    // for each item, insert into operation_analytique */
                    $op=new Anc_Operation($this->db);
                    $op->oa_group=$group;
                    $op->j_id=$j_id;
                    $op->oa_date=$e_date;
                    $op->oa_debit=($amount < 0 )?'t':'f';
                    $op->oa_description=sql_string($e_comm);
                    $op->save_form_plan($_POST,$i,$j_id);
                }
                if ( $g_parameter->MY_TVA_USE=='Y')
                {
                    /* save into quant_sold */
                    $r=$this->db->exec_sql("select insert_quant_sold ($1,$2,$3,$4,$5,$6,$7,$8,$9)",
                                           array(null, /* 1 */
                                                 $j_id,	/* 2 */
                                                 ${'e_march'.$i} , /* 3 */
                                                 ${'e_quant'.$i},  /* 4 */
                                                 round($amount,2),	       /* 5 */
                                                 $tva_item,	       /* 6 */
                                                 $idx_tva,	       /* 7 */
                                                 $e_client,      /* 8 */
                                                 $n_both));

                }
                else
                {
                    $r=$this->db->exec_sql("select insert_quant_sold ($1,$2,$3,$4,$5,$6,$7,$8,$9) ",
                                           array(null, /* 1 */
                                                 $j_id,	  /* 2 */
                                                 ${'e_march'.$i}, /* 3 */
                                                 ${'e_quant'.$i}, /* 4 */
                                                 $amount,		// 5
                                                 0,
                                                 null,
                                                 $e_client,
                                                         0));

                }  // if ( $g_parameter->MY_TVA_USE=='Y') {
            }// end loop : save all items

            /*  save total customer */
            $cust_amount=bcadd($tot_amount,$tot_tva);
            $acc_operation=new Acc_Operation($this->db);
            $acc_operation->date=$e_date;
            $acc_operation->poste=$poste;
            $acc_operation->amount=$cust_amount;
            $acc_operation->grpt=$seq;
            $acc_operation->jrn=$p_jrn;
            $acc_operation->type='d';
            $acc_operation->periode=$tperiode;
            $acc_operation->qcode=${"e_client"};
            if ( $cust_amount > 0 ) $tot_debit=bcadd($tot_debit,$cust_amount);
            $let_tiers=$acc_operation->insert_jrnx();


            /** save all vat
             * $i contains the tva_id and value contains the vat amount
             * if if ($g_parameter->MY_TVA_USE == 'Y' )
             */
            if ($g_parameter->MY_TVA_USE == 'Y' )
            {
                foreach ($tva as $i => $value)
                {
                    $oTva=new Acc_Tva($this->db);
                    $oTva->set_parameter('id',$i);
                    $oTva->load();

                    $poste_vat=$oTva->get_side('c');

                    $cust_amount=bcadd($tot_amount,$tot_tva);
                    $acc_operation=new Acc_Operation($this->db);
                    $acc_operation->date=$e_date;
                    $acc_operation->poste=$poste_vat;
                    $acc_operation->amount=$value;
                    $acc_operation->grpt=$seq;
                    $acc_operation->jrn=$p_jrn;
                    $acc_operation->type='c';
                    $acc_operation->periode=$tperiode;
                    if ($value < 0 ) $tot_debit=bcadd($tot_debit,abs($value));
                    $acc_operation->insert_jrnx();

                    // if TVA is on both side, we deduce it immediately
                    if ( $oTva->get_parameter("both_side")==1)
                    {
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
						$acc_operation->insert_jrnx();
						$tot_debit=bcadd($tot_debit,$value);
                        $n_both=$value;
                    }


                }
            } // if ($g_parameter->MY_TVA_USE=='Y')
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

            /* if e_suggest != e_pj then do not increment sequence */
            /* and e_pj is not null */
            if ( strcmp($e_pj,$e_pj_suggest) == 0 && strlen( trim($e_pj)) != 0 )
            {
                $this->inc_seq_pj();
            }

            $this->db->exec_sql("update jrn set jr_internal='".$internal."' where ".
                                " jr_grpt_id = ".$seq);

            /* update quant_sold */
            $this->db->exec_sql('update quant_sold set qs_internal = $1 where j_id in (select j_id from jrnx where j_grpt=$2)',
                                array($internal,$seq));

            /* Save the attachment or generate doc*/
            if ( isset ($_FILES['pj']))
            {
                if ( strlen(trim($_FILES['pj']['name'])) != 0 )
                    $this->db->save_upload_document($seq);
                else
                    /* Generate an invoice and save it into the database */
                    if ( isset($_POST['gen_invoice']))
                    {
                        $file=$this->create_document($internal,$p_array);
                        $this->doc= _('Document généré')."  : "."<br>";
                        $this->doc.='<A class="line" HREF="show_pj.php?'.dossier::get().'&jr_grpt_id='.$seq.'&jrn='.$this->id.'">'.$file.'</A>';
                    }
            }
            //----------------------------------------
            // Save the payer
            //----------------------------------------
            if ( $e_mp != 0 )
            {
                /* mp */
                $mp=new Acc_Payment($this->db,$e_mp);
                $mp->load();

                /* fiche */
                $fqcode=${'e_mp_qcode_'.$e_mp};
                $acfiche = new Fiche($this->db);
                $acfiche->get_by_qcode($fqcode);

                /* jrnx */
                $acseq=$this->db->get_next_seq('s_grpt');
                $acjrn=new Acc_Ledger($this->db,$mp->get_parameter('ledger_target'));
                $acinternal=$acjrn->compute_internal_code($acseq);

                /* Insert paid by  */
                $acc_pay=new Acc_Operation($this->db);
                $acc_pay->date=$e_date;
                /* get the account and explode if necessary */
                $sposte=$acfiche->strAttribut(ATTR_DEF_ACCOUNT);
                // if 2 accounts, take only the debit one for customer
                if ( strpos($sposte,',') != 0 )
                {
                    $array=explode(',',$sposte);
                    $poste_val=$array[0];
                }
                else
                {
                    $poste_val=$sposte;
                }
                $famount=bcsub($cust_amount,$acompte);
                $acc_pay->poste=$poste_val;
                $acc_pay->qcode=$fqcode;
                $acc_pay->amount=abs(round($famount,2));
                $acc_pay->desc=null;

                $acc_pay->grpt=$acseq;
                $acc_pay->jrn=$mp->get_parameter('ledger_target');
                $acc_pay->periode=$tperiode;
                $acc_pay->type=($famount>=0)?'d':'c';
                $acc_pay->insert_jrnx();

                /* Insert supplier  */
                $acc_pay=new Acc_Operation($this->db);
                $acc_pay->date=$e_date;
                $acc_pay->poste=$poste;
                $acc_pay->qcode=$e_client;
                $acc_pay->amount=abs(round($famount,2));
                $acc_pay->desc=null;
                $acc_pay->grpt=$acseq;
                $acc_pay->jrn=$mp->get_parameter('ledger_target');
                $acc_pay->periode=$tperiode;
				$acc_pay->type=($famount>=0)?'c':'d';
                $let_other=$acc_pay->insert_jrnx();

                /* insert into jrn */
				$acjrn->desc=$e_comm;
                $acjrn->grpt_id=$acseq;
                $mp_jr_id=$acc_pay->insert_jrn();
                $acjrn->update_internal_code($acinternal);

                $r1=$this->get_id($internal);
                $r2=$this->get_id($acinternal);

		/*
		 * add lettering
		 */
		$oletter=new Lettering($this->db);
		$oletter->insert_couple($let_tiers,$let_other);


                /* set the flag paid */
                $Res=$this->db->exec_sql("update jrn set jr_rapt='paid' where jr_id=$1",array($r1));

                /* Reconcialiation */
                $rec=new Acc_Reconciliation($this->db);
                $rec->set_jr_id($r1);
                $rec->insert($r2);


		/*
		 * save also into quant_fin
		 */

		/* get ledger property */
		$ledger=new Acc_Ledger_Fin($this->db,$acc_pay->jrn);
		$prop=$ledger->get_propertie();

		/* if ledger is FIN then insert into quant_fin */
		if ( $prop['jrn_def_type'] == 'FIN' )
		  {
		    $ledger->insert_quant_fin($acfiche->id,$mp_jr_id,$cust->id,bcmul($famount,1));
		  }

            }

        }
        catch (Exception $e)
        {
            echo '<span class="error">'.
            'Erreur dans l\'enregistrement '.
            __FILE__.':'.__LINE__.' '.
            $e->getMessage();
            echo $e->getTrace();

            $this->db->rollback();
            exit();
        }
        $this->db->commit();

        return $internal;
    }


    /*!@brief show the summary of the operation and propose to save it
     *@param array contains normally $_POST. It proposes also to save
     * the Analytic accountancy
	 *@param $p_summary false for the feedback, true to show the summary
     *@return string
	 *
     */
    function confirm($p_array,$p_summary=false)
    {
        global $g_parameter;
        extract ($p_array);

		// don't need to verify for a summary
        if ( ! $p_summary ) $this->verify($p_array) ;
        $anc=null;
        // to show a select list for the analytic & VAT USE
        // if analytic is op (optionnel) there is a blank line

        bcscale(4);
        $client=new Fiche($this->db);
        $client->get_by_qcode($e_client,true);

        $client_name=$client->getName().
                     ' '.$client->strAttribut(ATTR_DEF_ADRESS).' '.
                     $client->strAttribut(ATTR_DEF_CP).' '.
                     $client->strAttribut(ATTR_DEF_CITY);
        $lPeriode=new Periode($this->db);
        if ($this->check_periode() == true)
        {
            $lPeriode->p_id=$period;
        }
        else
        {
            $lPeriode->find_periode($e_date);
        }
        $date_limit=$lPeriode->get_date_limit();
        $r="";
        $r.="<fieldset>";
        $r.="<legend>"._('En-tête facture client')."  </legend>";
		$r.='<div id="jrn_name_div">';
		$r.='<h2 id="jrn_name" style="display:inline">' . $this->get_name() . '</h2>';
		$r.= '</div>';
        $r.='<TABLE  width="100%">';
        $r.='<tr>';
        $r.='<td> '._('Date').' '.$e_date.'</td>';
        $r.='<td>'._('Echeance').' '.$e_ech.'</td>';
        $r.='<td> '._('Période Comptable').' '.$date_limit['p_start'].'-'.$date_limit['p_end'].'</td>';
        $r.='<tr>';
        $r.='<td> '._('Journal').' '.h($this->get_name()).'</td>';
        $r.='</tr>';
        $r.='<tr>';
        $r.='<td colspan="3"> '._('Libellé').' '.h($e_comm).'</td><td>PJ Num:  '.h($e_pj).'</td>';
        $r.='</tr>';
        $r.='<tr>';
        $r.='<td colspan="3"> '._('Client').' '.h($e_client.':'.$client_name).'</td>';
        $r.='</tr>';
        $r.='</table>';
        $r.='</fieldset>';
        $r.='<fieldset><legend>'._('Détail articles vendus').'</legend>';
        $r.='<table width="100%" border="0">';
        $r.='<TR>';
        $r.="<th>"._('Code')."</th>";
        $r.="<th>"._('Dénomination')."</th>";
        $r.="<th style=\"text-align:right\">"._('prix')."</th>";
        $r.="<th style=\"text-align:right\">"._('quantité')."</th>";


        if ( $g_parameter->MY_TVA_USE=='Y')
        {
			$r.="<th style=\"text-align:right\">"._('tva')."</th>";
            $r.='<th style="text-align:right"> '._('Montant TVA').'</th>';
            $r.='<th style="text-align:right">'._('Montant HTVA').'</th>';
            $r.='<th style="text-align:right">'._('Montant TVAC').'</th>';
        }
		else
		{
			$r.='<th style="text-align:right">'._('Montant').'</th>';
		}
        /* if we use the AC */
        if ($g_parameter->MY_ANALYTIC!='nu')
        {
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
        for ($i = 0; $i < $nb_item;$i++)
        {
            if ( strlen(trim(${"e_march".$i})) == 0 ) continue;

            /* retrieve information for card */
            $fiche=new Fiche($this->db);
            $fiche->get_by_qcode(${"e_march".$i});
            if ( $g_parameter->MY_UPDLAB=='Y')
                $fiche_name=h(${"e_march".$i."_label"});
            else
                $fiche_name=$fiche->strAttribut (ATTR_DEF_NAME);
            if ( $g_parameter->MY_TVA_USE=='Y')
            {
                $oTva=new Acc_Tva($this->db);
                $idx_tva=${"e_march".$i."_tva_id"};

                $oTva->set_parameter('id',$idx_tva);
                $oTva->load();
            }
            $op=new Acc_Compute();
            $amount=bcmul(${"e_march".$i."_price"},${'e_quant'.$i});
            $op->set_parameter("amount",$amount);
            if ( $g_parameter->MY_TVA_USE=='Y')
            {
                $op->set_parameter('amount_vat_rate',$oTva->get_parameter('rate'));
                $op->compute_vat();
                $tva_computed=$op->get_parameter('amount_vat');
                $tva_item=${"e_march".$i."_tva_amount"};
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
            $r.='<td class="num">';
            $r.=nbm(${"e_march".$i."_price"});
            $r.='</td>';
            $r.='<td class="num">';
            $r.=nbm(${"e_quant".$i});
            $r.='</td>';
            if ( $g_parameter->MY_TVA_USE=='Y')
            {
                $r.='<td class="num">';
                $r.=$oTva->get_parameter('label');
                $r.='</td>';
                $r.='<td class="num">';
                /* warning if tva_computed and given are not the
                   same */
                if ( bcsub($tva_item,$tva_computed) != 0)
                {
                    $r.='<a href="#" class="error" style="display:inline" title="'. _("Attention Différence entre TVA calculée et donnée").'">'
							.nbm($tva_item).'<a>';
                }
				else
					$r.=nbm($tva_item);
                $r.='</td>';
				$r.='<td class="num">';
				$r.=nbm($amount);
				$r.='</td>';
				$tot_row=bcadd($tva_item,$amount);
				$r.=td(nbm($tot_row),'class="num"');
            }
			else
			{
				$r.='<td class="num">';
				$r.=nbm($amount);
				$r.='</td>';
			}
			// encode the pa
            if ( $g_parameter->MY_ANALYTIC!='nu') // use of AA
            {
                // show form
                $anc_op=new Anc_Operation($this->db);
                $null=($g_parameter->MY_ANALYTIC=='op')?1:0;
                $r.='<td>';
                $p_mode=($p_summary==false)?1:0;
                $p_array['pa_id']=$a_anc;
                /* op is the operation it contains either a sequence or a jrnx.j_id */
                $r.=HtmlInput::hidden('op[]=',$i);
                $r.=$anc_op->display_form_plan($p_array,$null,$p_mode,$i,$amount);
                $r.='</td>';
            }


            $r.='</tr>';

        }


        $r.='</table>';
        if ( $g_parameter->MY_ANALYTIC!='nu' && $p_summary ) // use of AA
            $r.='<input type="button" class="button" value="'._('Vérifiez Imputation Analytique').'" onClick="verify_ca(\'\');">';
        $r.='</fieldset>';
        if (! $p_summary )
		{
			$r.=$this->extra_info();
                         $r.='<div style="width:40%;position:float;float:right;text-align:right;padding-left:5%;padding-right:5%;color:blue;font-size:1.2em;font-weight:bold">';
		}
		else
			$r.='<div style="width:60%;position:float;float:left;text-align:right;padding-left:5%;padding-right:5%;color:blue;font-size:1.2em;font-weight:bold">';
        $r.='<fieldset> <legend>Totaux</legend>';
        $tot=round(bcadd($tot_amount,$tot_tva),2);
        $r.='<div style="width:40%;position:float;float:left;text-align:right;padding-left:5%;padding-right:5%;color:blue;font-size:1.2em;font-weight:bold">';
        /* use VAT */
        if ($g_parameter->MY_TVA_USE == 'Y' )
        {
            $r.='<br>Total HTVA';
            foreach ($tva as $i=>$value)
            {
                $oTva->set_parameter('id',$i);
                $oTva->load();

                $r.='<br>  TVA à '.$oTva->get_parameter('label');
            }
            $r.='<br>Total TVA';
            $r.='<br>Total TVAC';
        }
        else
        {
            $r.='<br>Total ';
        }
        $r.='</div>';

        $r.='<div style="position:float;float:left;text-align:right;color:blue;font-size:1.2em;font-weight:bold">';
        $r.='<br><span id="htva">'.nbm($tot_amount).'</span>';

        if ($g_parameter->MY_TVA_USE == 'Y' )
        {
            foreach ($tva as $i=>$value)
            {
	      $r.='<br>'.nbm($tva[$i]);
            }
            $r.='<br><span id="tva">'.nbm($tot_tva).'</span>';
	    $r.='<br><span id="tvac">'.nbm($tot).'</span>';
        }
        $r.="</div>";



        $r.='</fieldset>';
        $r.="</div>";

        /*  Add hidden */
        $r.=HtmlInput::hidden('e_client',$e_client);
        $r.=HtmlInput::hidden('nb_item',$nb_item);
        $r.=HtmlInput::hidden('p_jrn',$p_jrn);
        $mt=microtime(true);
        $r.=HtmlInput::hidden('mt',$mt);

        if ( isset($period))
            $r.=HtmlInput::hidden('period',$period);
        /*\todo comment les types hidden gérent ils des contenus avec des quotes, double quote ou < > ??? */
        $r.=HtmlInput::hidden('e_comm',$e_comm);
        $r.=HtmlInput::hidden('e_date',$e_date);
        $r.=HtmlInput::hidden('e_ech',$e_ech);
        $r.=HtmlInput::hidden('e_pj',$e_pj);
        $r.=HtmlInput::hidden('e_pj_suggest',$e_pj_suggest);

        $e_mp=(isset($e_mp))?$e_mp:0;
        $r.=HtmlInput::hidden('e_mp',$e_mp);
		// Show the available repository
		if ($g_parameter->MY_STOCK == 'Y')
		{
			$sel = HtmlInput::select_stock($this->db, 'repo', 'W');
			$sel->readOnly = $p_summary;
			if ($p_summary == true)
				$sel->selected = $repo;
			$r.="<div style=\"clear:both\"></div>";
			$r.='<div style="float:left"><h2 class="info">Dépôt</h2>';
			$r.="<p> Dans le dépôt : ";
			$r.=$sel->input();
			$r.='</p>';
			$r.='</div>';
		}
        /* Paid by */
        /* if the paymethod is not 0 and if a quick code is given */
        if ( $e_mp!=0 && strlen (trim (${'e_mp_qcode_'.$e_mp})) != 0 )
        {
            $r.=HtmlInput::hidden('e_mp_qcode_'.$e_mp,${'e_mp_qcode_'.$e_mp});
            $r.=HtmlInput::hidden('acompte',$acompte);
            /* needed for generating a invoice */
            $r.=HtmlInput::hidden('qcode_benef',${'e_mp_qcode_'.$e_mp});

			$fname=new Fiche($this->db);
			$fname->get_by_qcode(${'e_mp_qcode_'.$e_mp});
			$r.="<div style=\"clear:both\"></div>";
			$r.='<div style="float:left"><h2 class="info">'."Payé par ".${'e_mp_qcode_'.$e_mp}.
				   " ".$fname->getName().'</H2> '._('Déduction acompte ').h($acompte).'</div>';
            $r.='<br>';
        }

        $r.=HtmlInput::hidden('jrn_type',$jrn_type);
        for ($i=0;$i < $nb_item;$i++)
        {
            $r.=HtmlInput::hidden("e_march".$i,${"e_march".$i});
            if (isset (${"e_march".$i."_label"})) $r.=HtmlInput::hidden("e_march".$i."_label",${"e_march".$i."_label"});
            $r.=HtmlInput::hidden("e_march".$i."_price",${"e_march".$i."_price"});
            if ( $g_parameter->MY_TVA_USE=='Y')
            {
                $r.=HtmlInput::hidden("e_march".$i."_tva_id",${"e_march".$i."_tva_id"});
                $r.=HtmlInput::hidden("e_march".$i."_tva_amount",${"e_march".$i."_tva_amount"});
            }
            $r.=HtmlInput::hidden("e_quant".$i,${"e_quant".$i});
        }

        return $r;
    }
    /*!\brief the function extra info allows to
     * - add a attachment
     * - generate an invoice
     * - insert extra info
     *\return string
     */
    public function extra_info()
    {
        $r="";
        $r.='<div style="position:float;float:left;width:50%;text-align:right;line-height:3em;">';
        $r.='<fieldset> <legend> Facturation</legend>';
        // check for upload piece
        $file=new IFile();
        $file->table=0;
        $r.=_("Ajoutez une pièce justificative ");
        $r.=$file->input("pj","");

        if ( $this->db->count_sql("select md_id,md_name from document_modele where md_affect='VEN'") > 0 )
        {


            $r.=_('ou générer une facture').' <input type="checkbox" name="gen_invoice" CHECKED>';
            // We propose to generate  the invoice and some template
            $doc_gen=new ISelect();
            $doc_gen->name="gen_doc";
            $doc_gen->value=$this->db->make_array(
                                "select md_id,md_name ".
                                " from document_modele where md_affect='VEN'");
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
    function show_unpaid()
    {
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
        $bar2=navigation_bar($offset,$m,$step,$page);

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
    /*!\brief display the form for entering data for invoice,
    *\param $p_array is null or you can put the predef operation or the $_POST
    *
    *\return HTML string
    */
    function input($p_array=null,$p_readonly=0)
    {
        global $g_parameter,$g_user;
        if ( $p_array != null ) extract($p_array);

        $flag_tva=$g_parameter->MY_TVA_USE;
        /* Add button */
        $f_add_button=new IButton('add_card');
        $f_add_button->label=_('Créer une nouvelle fiche');
        $f_add_button->set_attribute('ipopup','ipop_newcard');
        $f_add_button->set_attribute('jrn',$this->id);
        $f_add_button->javascript="this.jrn=\$('p_jrn').value; select_card_type(this);";

        $f_add_button2=new IButton('add_card2');
        $f_add_button2->label=_('Créer une nouvelle fiche');
        $f_add_button2->set_attribute('ipopup','ipop_newcard');
        $f_add_button2->set_attribute('filter',$this->get_all_fiche_def ());
        //    $f_add_button2->set_attribute('jrn',$this->id);
        $f_add_button2->javascript=" this.jrn=\$('p_jrn').value;select_card_type(this);";

		$str_add_button="";
		$str_add_button2="";
		if ($g_user->check_action(FICADD)==1)
		{
			$str_add_button=$f_add_button->input();
			$str_add_button2=$f_add_button2->input();
		}
        // The first day of the periode
        $oPeriode=new Periode($this->db);
        list ($l_date_start,$l_date_end)=$oPeriode->get_date_limit($g_user->get_periode());
        if (  $g_parameter->MY_DATE_SUGGEST=='Y' )
            $op_date=( ! isset($e_date) ) ?$l_date_start:$e_date;
        else
            $op_date=( ! isset($e_date) ) ?'':$e_date;


        $e_ech=(isset($e_ech))?$e_ech:"";
        $e_comm=(isset($e_comm))?$e_comm:"";

        $r='';
        $r.=dossier::hidden();
        $f_legend=_('En-tête facture client');

        $Echeance=new IDate();
        $Echeance->setReadOnly(false);

        $Echeance->tabindex=2;
        $label=HtmlInput::infobulle(4);
        $f_echeance=$Echeance->input('e_ech',$e_ech,_('Echéance').$label);
        $Date=new IDate();
        $Date->setReadOnly(false);

        $f_date=$Date->input("e_date",$op_date);

        $f_periode='';
        // Periode
        //--
        if ($this->check_periode() == true)
        {
            $l_user_per=$g_user->get_periode();
            $def=(isset($periode))?$periode:$l_user_per;

            $period=new IPeriod("period");
            $period->user=$g_user;
            $period->cn=$this->db;
            $period->value=$def;
            $period->type=OPEN;
            try
            {
                $l_form_per=$period->input();
            }
            catch (Exception $e)
            {
                if ($e->getCode() == 1 )
                {
                    echo _("Aucune période ouverte");
                    exit();
                }
            }
            $label=HtmlInput::infobulle(3);
            $f_periode=_("Période comptable")." $label ".$l_form_per;
        }
        /* if we suggest the next pj, then we need a javascript */
        $add_js="";
        if ( $g_parameter->MY_PJ_SUGGEST=='Y')
        {
            $add_js="update_pj();";
        }
        $add_js.='get_last_date();';
		$add_js.='update_name();';
		$add_js.='update_pay_method()';

        $wLedger=$this->select_ledger('VEN',2);
        if ( $wLedger == null )
            exit(_('Pas de journal disponible'));
        $wLedger->table=1;
        $wLedger->javascript="onChange='update_predef(\"ven\",\"f\");$add_js'";
        $wLedger->label=" Journal ".HtmlInput::infobulle(2) ;

        $f_jrn=$wLedger->input();

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
        if ( $g_parameter->MY_PJ_SUGGEST=='Y')
        {
            $default_pj=$this->guess_pj();
        }

        $pj=new IText();

        $pj->table=0;
        $pj->name="e_pj";
        $pj->size=10;
        $pj->value=(isset($e_pj))?$e_pj:$default_pj;
        $f_pj=$pj->input().HtmlInput::hidden('e_pj_suggest',$default_pj);
        // Display the customer
        //--
        $fiche='deb';

        // Save old value and set a new one
        //--
        $e_client=( isset ($e_client) )?$e_client:"";
        $e_client_label="&nbsp;";//str_pad("",100,".");


        // retrieve e_client_label
        //--

        if ( strlen(trim($e_client)) !=  0)
        {
            $fClient=new Fiche($this->db);
            $fClient->get_by_qcode($e_client);
            $e_client_label=$fClient->strAttribut(ATTR_DEF_NAME).' '.
                            ' Adresse : '.$fClient->strAttribut(ATTR_DEF_ADRESS).' '.
                            $fClient->strAttribut(ATTR_DEF_CP).' '.
                            $fClient->strAttribut(ATTR_DEF_CITY).' ';


        }

        $W1=new ICard();
        $W1->label="Client ".HtmlInput::infobulle(0) ;
        $W1->name="e_client";
        $W1->tabindex=3;
        $W1->value=$e_client;
        $W1->table=0;
        $W1->set_dblclick("fill_ipopcard(this);");
        $W1->set_attribute('ipopup','ipopcard');

        // name of the field to update with the name of the card
        $W1->set_attribute('label','e_client_label');
        // name of the field to update with the name of the card
        $W1->set_attribute('typecard','deb');

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
        $max=($p_article < MAX_ARTICLE)?MAX_ARTICLE:$p_article;


        $f_legend_detail=_("Détail articles vendus");

        // For each article
        //--
        for ($i=0;$i< $max;$i++)
        {
            // Code id, price & vat code
            //--
            $march=(isset(${"e_march$i"}))?${"e_march$i"}:""
                   ;
            $march_price=(isset(${"e_march".$i."_price"}))?${"e_march".$i."_price"}:""
                         ;
            if ( $flag_tva=='Y')
            {
                $march_tva_id=(isset(${"e_march$i"."_tva_id"}))?${"e_march$i"."_tva_id"}:"";
                $march_tva_amount=(isset(${"e_march$i"."_tva_amount"}))?${"e_march$i"."_tva_amount"}:"";
            }
            $march_label=(isset(${"e_march".$i."_label"}))?${"e_march".$i."_label"}:"";

            // retrieve the tva label and name
            //--
            if ( strlen(trim($march))!=0 && strlen(trim($march_label))==0)
            {
                $fMarch=new Fiche($this->db);
                $fMarch->get_by_qcode($march);
                $march_label=$fMarch->strAttribut(ATTR_DEF_NAME);
                if ( $flag_tva=='Y')
                {
                    if ( ! (isset(${"e_march$i"."_tva_id"})))
                        $march_tva_id=$fMarch->strAttribut(ATTR_DEF_TVA);
                }
            }
            // Show input
            //--
            $W1=new ICard();
            $W1->label="";
            $W1->name="e_march".$i;
            $W1->value=$march;
            $W1->table=1;
            $W1->set_attribute('typecard','cred');
            $W1->set_dblclick("fill_ipopcard(this);");
            $W1->set_attribute('ipopup','ipopcard');

            // name of the field to update with the name of the card
            $W1->set_attribute('label','e_march'.$i.'_label');
            // name of the field with the price
            $W1->set_attribute('price','e_march'.$i.'_price');
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
            // For computing we need some hidden field for holding the value
            $array[$i]['hidden']='';
            if ( $flag_tva=='Y') $array[$i]['hidden'].=HtmlInput::hidden('tva_march'.$i,0);

            $htva=new INum('htva_march'.$i);
            $htva->readOnly=1;
            $htva->value=0;
            $array[$i]['htva']=$htva->input();

            if ( $g_parameter->MY_TVA_USE=='Y')
                $tvac=new INum('tvac_march'.$i);
            else
                $tvac=new IHidden('tvac_march'.$i);

            $tvac->readOnly=1;
            $tvac->value=0;
            $array[$i]['tvac']=$tvac->input();

            if ( $g_parameter->MY_UPDLAB == 'Y')
            {
                $Span=new IText("e_march".$i."_label");

                $Span->css_size="100%";
            } else
            {
                $Span=new ISpan("e_march".$i."_label");
            }
            $Span->value=$march_label;
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
            $array[$i]['tva']='';
            $array[$i]['amount_tva']='';
            // if tva is not needed then no tva field
            if ( $flag_tva == 'Y' )
            {
                // vat label
                //--
                $Tva=new ITva_Popup($this->db);
                $Tva->in_table=true;
                $Tva->set_attribute('compute',$i);

                $Tva->js='onblur="format_number(this);clean_tva('.$i.');compute_ledger('.$i.')"';
                $Tva->value=$march_tva_id;
                $array[$i]['tva']=$Tva->input("e_march$i"."_tva_id");
                // vat amount
                //--
                $wTva_amount=new INum();
                $wTva_amount->readOnly=false;
                $wTva_amount->size=6;
                $wTva_amount->javascript="onBlur='format_number(this);compute_ledger($i)'";
                $array[$i]['amount_tva']=$wTva_amount->input("e_march".$i."_tva_amount",$march_tva_amount);
            }
            // quantity
            //--
            $quant=(isset(${"e_quant$i"}))?${"e_quant$i"}:"1"
                   ;
            $Quantity=new INum();
            $Quantity->setReadOnly(false);
            $Quantity->size=8;
            $Quantity->javascript="onChange='format_number(this);clean_tva($i);compute_ledger($i)'";
            $array[$i]['quantity']=$Quantity->input("e_quant".$i,$quant);

        }// foreach article
        $f_type=_('Client');


        ob_start();
        require_once('template/form_ledger_detail.php');
        $r.=ob_get_contents();
        ob_end_clean();



        // Set correctly the REQUEST param for jrn_type
        $r.=HtmlInput::hidden('jrn_type','VEN');

        $r.=HtmlInput::button('add_item',_('Ajout article'),      ' onClick="ledger_add_row()"');

        return $r;
    }

    /*!\brief test function
     */
    static function test_me($p_string='')
    {
        $cn=new Database(dossier::id());
        $a=new Acc_Ledger_Sold($cn,2);
        echo $a->input();
    }

}





