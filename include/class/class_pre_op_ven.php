<?php
/*
 *   This file is part of NOALYSS.
 *
 *   NOALYSS is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   NOALYSS is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with NOALYSS; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Copyright Author Dany De Bontridder danydb@aevalys.eu

/*!\file
 * \brief definition of the class Pre_op_ven
 */
require_once  NOALYSS_INCLUDE.'/class/class_pre_operation.php';

/*---------------------------------------------------------------------- */
/*!\brief concerns the predefined operation for VEN ledger
*/
class Pre_op_ven extends Pre_operation_detail
{
    var $op;
    //< Pre_operation
    function __construct($cn,$p_id=0)
    {
        parent::__construct($cn,$p_id);
        $this->operation->od_direct='f'; //< Pre_operation
    }

    function get_post()
    {
        parent::get_post();
        $this->operation->od_direct='f';
        $this->e_client=$_POST['e_client'];
        for ($i=0;$i<$this->operation->nb_item;$i++)
        {
            $march="e_march".$i;
            $this->$march=$_POST['e_march'.$i];
            $this->{"e_march".$i."_price"}=$_POST['e_march'.$i."_price"];
            $this->{"e_march".$i."_tva_id"}=(isset($_POST['e_march'.$i."_tva_id"]))?$_POST['e_march'.$i."_tva_id"]:0;
            $this->{"e_march".$i."_tva_amount"}=(isset($_POST['e_march'.$i."_tva_amount"]))?$_POST['e_march'.$i."_tva_amount"]:0;
            $this->{"e_march".$i."_tva_id"}=(isset($_POST['e_march'.$i."_tva_id"]))?$_POST['e_march'.$i."_tva_id"]:0;
            $this->{"e_march".$i."_label"}=(isset($_POST['e_march'.$i."_label"]))?$_POST['e_march'.$i."_label"]:null;
            $this->{"e_quant".$i}=$_POST['e_quant'.$i];

        }
    }
    /*!
     * \brief save the detail and op in the database
     *
     */
    function save()
    {
        try
        {
            $this->db->start();
            if ($this->operation->save() == false )
                return;
            // save the client
            $sql=sprintf('insert into op_predef_detail (od_id,opd_poste,opd_debit)'.
                         ' values '.
                         "(%d,'%s','%s')",
                         $this->operation->od_id,
                         $this->e_client,
                         "t");
            $this->db->exec_sql($sql);
            // save the selling
            for ($i=0;$i<$this->operation->nb_item;$i++)
            {
                if ( strlen(trim($this->{"e_march".$i}))=="") continue;
                $sql= 'insert into op_predef_detail (opd_poste,'
                        . 'opd_amount,'
                        . 'opd_tva_id,'
                        . 'opd_quantity,'
                        . 'opd_debit,'
                        . 'od_id ,'
                        . 'opd_tva_amount,'
                        . 'opd_comment'.
                        ')'.
                             ' values ($1,$2,$3,$4,$5,$6,$7,$8)';
                $this->db->exec_sql($sql,
                        array($this->{"e_march".$i},
                             $this->{"e_march".$i."_price"},
                             $this->{"e_march".$i."_tva_id"},
                             $this->{"e_quant".$i},
                             'f',
                             $this->operation->od_id,
                             $this->{"e_march".$i."_tva_amount"},
                             $this->{"e_march".$i."_label"},
                            ));
            }
        }
        catch (Exception $e)
        {
            record_log($e->getTraceAsString());
            echo ($e->getMessage());
            $this->db->rollback();
        }
        $this->db->commit();

    }
    /*!\brief compute an array accordingly with the FormVenView function
     */
    function compute_array()
    {
        $count=0;
        $a_op=$this->operation->load();
        $array=$this->operation->compute_array($a_op);
        $p_array=$this->load();
		if (empty($p_array)) return array();
        foreach ($p_array as $row)
        {
            if ( $row['opd_debit']=='t')
            {
                $array+=array('e_client'=>$row['opd_poste']);
            }
            else
            {
                if ( trim($row['opd_poste']) != "")
                {
                    $array+=array("e_march".$count=>$row['opd_poste'],
                              "e_march".$count."_price"=>$row['opd_amount'],
                              "e_march".$count."_tva_id"=>$row['opd_tva_id'],
                              "e_quant".$count=>$row['opd_quantity'],
                              "e_march".$count."_label"=>$row['opd_comment'],
                              "e_march".$count."_tva_amount"=>$row['opd_tva_amount'],
                             );
                    $count++;
                }
            }
        }
        // Find the ledger
        $ledger=new Acc_Ledger($this->db,$this->operation->jrn_def_id);
        // Find the max line of the ledger
        $max_row=$ledger->GetDefLine();
        
        // compute nb_item
        $array['nb_item']=($max_row > $count)?$max_row:$count;
        return $array;
    }
    /*!\brief load the data from the database and return an array
     * \return an array
     */
    function load()
    {
        $sql="select opd_id,opd_poste,opd_amount,opd_tva_id,opd_debit,".
             " opd_quantity , opd_comment,opd_tva_amount from op_predef_detail where od_id=".$this->operation->od_id.
             " order by opd_id";
        $res=$this->db->exec_sql($sql);
        $array=Database::fetch_all($res);
        return $array;
    }
    function set_od_id($p_id)
    {
        $this->operation->od_id=$p_id;
    }
    function display($p_array)
    {
        global $g_parameter,$g_user;
        if ( $p_array != null ) extract($p_array, EXTR_SKIP);
        require_once NOALYSS_INCLUDE.'/class/class_acc_ledger_sold.php';
        $ledger=new Acc_Ledger_Sold($this->db,$this->jrn_def_id);

        $flag_tva=$g_parameter->MY_TVA_USE;
        /* Add button */
        $f_add_button=new IButton('add_card');
		$f_add_button->tabindex=-1;
        $f_add_button->label=_('Créer une nouvelle fiche');
        $f_add_button->set_attribute('ipopup','ipop_newcard');
        $f_add_button->set_attribute('jrn',$ledger->id);
        $f_add_button->javascript="this.jrn=\$('p_jrn').value; select_card_type(this);";

        $f_add_button2=new IButton('add_card2');
		$f_add_button2->tabindex=-1;
        $f_add_button2->label=_('Créer une nouvelle fiche');
        $f_add_button2->set_attribute('ipopup','ipop_newcard');
        $f_add_button2->set_attribute('filter',$ledger->get_all_fiche_def ());
        //    $f_add_button2->set_attribute('jrn',$ledger->id);
        $f_add_button2->javascript=" this.jrn=\$('p_jrn').value;select_card_type(this);";

        $str_add_button="";
        $str_add_button2="";
        if ($g_user->check_action(FICADD)==1)
        {
                $str_add_button=$f_add_button->input();
                $str_add_button2=$f_add_button2->input();
        }

        $r='';
        $r.=dossier::hidden();
        $f_legend=_('En-tête facture client');


        /* if we suggest the next pj, then we need a javascript */

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
            $fClient=new Fiche($ledger->db);
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
		$min=$ledger->get_min_row();
        $p_article= ( isset ($nb_item))?$nb_item:$min;
        $max=($p_article < $min)?$min:$p_article;

        $e_comment=(isset($e_comment))?$e_comment:"";
        $Hid=new IHidden();
        $r.=$Hid->input("nb_item",$p_article);

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
                $fMarch=new Fiche($ledger->db);
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
            $Price->prec=4;
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
                $Tva=new ITva_Popup($ledger->db);
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
        require_once NOALYSS_TEMPLATE.'/predf_ledger_detail.php';
        $r.=ob_get_contents();
        ob_end_clean();



        // Set correctly the REQUEST param for jrn_type
        $r.=HtmlInput::hidden('jrn_type','VEN');

        $r.=HtmlInput::button('add_item',_('Ajout article'),      ' onClick="ledger_add_row()"');
        return $r;
    }
}
