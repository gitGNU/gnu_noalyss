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
// Copyright Author Dany De Bontridder ddebontridder@yahoo.f
require_once('class_itextarea.php');
require_once("class_idate.php");
require_once("class_iselect.php");
require_once("class_ihidden.php");
require_once("class_itext.php");
require_once("class_ispan.php");
require_once("class_icard.php");
require_once("class_icheckbox.php");
require_once("class_ifile.php");
require_once("class_fiche.php");
require_once("class_document.php");
require_once("class_document_type.php");
require_once("class_document_modele.php");
require_once("user_common.php");
require_once('class_follow_up_detail.php');
require_once('class_inum.php');
require_once 'class_sort_table.php';

/*!\file
 * \brief class_action for manipulating actions
 * action can be :
 * <ul>
 * <li>an invoice
 * <li>a meeting
 * <li>an order
 * <li>a letter
 *</ul>
 * The table document_type are the possible actions
 */
/*!
 * \brief class_action for manipulating actions
 * action can be :
 * <ul>
 * <li> a meeting
 * <li>an order
 * <li>a letter
 * </ul>
 * The table document_type are the possible actions
 */

class Follow_Up
{
    var $db;	      /*!<  $db  database connexion    */
    var $ag_comment;    /*!<  $ag_comment description (ag_gestion.ag_comment) */
    var $ag_timestamp;  /*!<   $ag_timestamp document date (ag_gestion.ag_timestamp)*/
    var $dt_id;         /*!<   $dt_id type of the document (document_type.dt_id)*/
    var $ag_state;       /*!<   $ag_state stage of the document (printed, send to client...)*/
    var $d_number;      /*!<   $d_number number of the document*/
    var $d_filename;    /*!<   $d_filename filename's document      */
    var $d_mimetype;    /*!<   $d_mimetype document's filename      */
    var $ag_title;      /*!<   $ag_title title document	      */
    var $f_id;	      /*!<   $f_id_dest fiche id (From field )  */
    var $ag_ref_ag_id;   /*!<   $ag_ref_ag_id concern previous action*/
    var $ag_ref;	       /*!< $ag_ref is the ref  */
    var $ag_hour;	       /*!< $ag_hour is the hour of the meeting, action */
    var $ag_priority;    /*!< $ag_priority is the priority 1 High, 2 medium, 3 low */
    var $ag_dest;	       /*!< $ag_dest person who is in charged */
    var $ag_contact;     /*!< $ag_contact contact */
    /*!  constructor
    * \brief constructor
    * \param p_cn database connection
    */
    function __construct ($p_cn)
    {
        $this->db=$p_cn;
        $this->f_id=0;

    }
    //----------------------------------------------------------------------
    /*!
     * \brief Display the object, the tags for the FORM
     *        are in the caller. It will be used for adding and updating
     *        action
     *\note  If  ag_id is not equal to zero then it is an update otherwise
     *        it is a new document
     *
     * \param $p_view if set to true the form will be in readonly mode (value: true or false)
     * \param $p_gen true we show the tag for generating a doc (value : true or false)
     *\param $p_base is the ac parameter
     *\param $retour is the html code for the return button
     * \note  update the reference number or the document type is not allowed
     *
     *
     * \return string containing the html code
     */
    function Display($p_view,$p_gen,$p_base,$retour="")
    {
        if ( $p_view=='UPD')
        {
            $upd=true;
            $readonly=false;
        }
        elseif ($p_view=="NEW")
        {
            $upd=false;
            $readonly=false;
        }
        elseif ($p_view=='READ')
        {
            $upd=true;
            $readonly=true;
        }
        else
        {
            exit('class_action'.__LINE__.'Follow_Up::Display error unknown parameter'.$p_view);

        }
        // Compute the widget
        // Date
        $date=new IDate();
        $date->readonly=$readonly;
        $date->name="ag_timestamp";
        $date->value=$this->ag_timestamp;

        // for insert mode only
        if ( $upd == false )
        {
            // Doc Type
            $doc_type=new ISelect();
            $doc_type->name="dt_id";
            $doc_type->value=$this->db->make_array("select dt_id,dt_value from document_type order by dt_value");
            $doc_type->selected=$this->dt_id;
            $doc_type->readonly=false;
            $str_doc_type=$doc_type->input();
        }
        else
        {
            /*// Doc Type
            $doc_type=new IHidden();
            $doc_type->name="dt_id";
            $doc_type->value=$this->dt_id;
            $str_doc_type=$doc_type->input().$this->db->get_value("select dt_value from document_type where dt_id=".$this->dt_id);*/
             $doc_type=new ISelect();
            $doc_type->name="dt_id";
            $doc_type->value=$this->db->make_array("select dt_id,dt_value from document_type order by dt_value");
            $doc_type->selected=$this->dt_id;
            $doc_type->readonly=false;
            $str_doc_type=$doc_type->input();
        }

        // Description
        $desc=new ITextArea();
        $desc->width=70;
        $desc->heigh=5;
        $desc->name="ag_comment";
        $desc->readonly=$readonly;
        $desc->value=$this->ag_comment;
        if ( strlen($desc->value) >300 )
        {
            $desc->width=120;
            $desc->heigh=40;
        }

        // state
        // Retrieve the value
        $a=$this->db->make_array("select s_id,s_value from document_state ");
        $state=new ISelect();
        $state->readonly=$readonly;
        $state->name="ag_state";
        $state->value=$a;
        $state->selected=$this->ag_state;
        $str_state=$state->input();

        // Retrieve the value if there is an attached doc
        $doc_ref="";
        // Document id

        $h2=new IHidden();
        $h2->name="d_id";
        $h2->value=$this->d_id;

        if ( $this->d_id != 0 && $this->d_id != "" )
        {
            $h2->readonly=($p_view=='NEW')?false:true;
            $doc=new Document($this->db,$this->d_id);
            $doc->get();
            if ( strlen(trim($doc->d_lob)) != 0 )
            {
                $d_id=new IHidden();
                $doc_ref="<p> Document ".$doc->a_ref().'</p>';
                $doc_ref.=$h2->input().$d_id->input('d_id',$this->d_id);
            }

        }


        // title
        $title=new IText();
        $title->readonly=$readonly;
        $title->name="ag_title";
        $title->value=$this->ag_title;
        $title->size=60;

        // ag_cal
        $ag_cal=new ICheckBox('ag_cal');
        $ag_cal->readonly=$readonly;
        $ag_cal->name="ag_cal";

        if ( $this->ag_cal=='C' )
            $ag_cal->selected=true;
        else
            $ag_cal->selected=false;

        $str_ag_cal=$ag_cal->input();

        // Priority of the ag_priority
        $ag_priority=new ISelect();
        $ag_priority->readonly=$readonly;
        $ag_priority->name="ag_priority";
        $ag_priority->selected=$this->ag_priority;
        $ag_priority->value=array(array('value'=>1,'label'=>'Haute'),
                                  array('value'=>2,'label'=>'Moyenne'),
                                  array('value'=>3,'label'=>'Basse')
                                 );
        $str_ag_priority=$ag_priority->input();

        // hour of the action (meeting) ag_hour
        $ag_hour=new IText();
        $ag_hour->readonly=$readonly;
        $ag_hour->name="ag_hour";
        $ag_hour->value=$this->ag_hour;
        $ag_hour->size=6;
        $ag_hour->javascript=" onblur=check_hour('ag_hour');";
        $str_ag_hour=$ag_hour->input();

        // Person in charged of the action
        $ag_dest=new ISelect();
        $ag_dest->readonly=$readonly;
        $ag_dest->name="ag_dest";
        $repo=new Database();
        $aAg_dest=$repo->make_array("select  use_id as value, ".
                                    "use_first_name||' '||use_name||'('||use_login||')' as label ".
                                    " from ac_users natural join jnt_use_dos ".
                                    " join priv_user on (jnt_id=priv_jnt) ".
                                    "where dos_id= ".$_REQUEST['gDossier']);
        $aAg_dest[]=array('value'=>0,'label'=>'phpcompta');
        $ag_dest->value=$aAg_dest;
        $ag_dest->selected=$this->ag_dest;
        $str_ag_dest=$ag_dest->input();

        // ag_ref
        // Always false for update
        $ag_ref=new IText();
        $ag_ref->readonly=$upd;
        $ag_ref->name="ag_ref";
        $ag_ref->value=sql_string($this->ag_ref);
        $client_label=new ISpan();

        /* Add button */
        $f_add_button=new IButton('add_card');
        $f_add_button->label=_('Créer une nouvelle fiche');
        $f_add_button->set_attribute('ipopup','ipop_newcard');
        $filter=$this->db->make_list('select fd_id from fiche_def ');
        $f_add_button->set_attribute('filter',$filter);

        $f_add_button->javascript=" select_card_type(this);";
        $str_add_button=$f_add_button->input();

        // f_id_dest sender
        if ( $this->qcode_dest != NOTFOUND && strlen(trim($this->qcode_dest)) != 0)
        {
            $tiers=new Fiche($this->db);
            $tiers->get_by_qcode($this->qcode_dest);
            $qcode_dest_label=$tiers->strAttribut(1);
            $this->f_id_dest=$tiers->id;
        }
        else
        {
            $qcode_dest_label=($this->f_id_dest==0 || trim($this->qcode_dest)=="")?'Interne ':'Error';
        }

        $h_ag_id=new IHidden();
        // if concerns another action : show the link otherwise nothing
        $lag_ref_ag_id=" 00 / 00 ";

        if ( $this->ag_ref_ag_id != 0 )
        {
            $supl_hidden='';
            if( isset($_REQUEST['sc']))
                $supl_hidden.='&sc='.$_REQUEST['sc'];
            if( isset($_REQUEST['f_id']))
                $supl_hidden.='&f_id='.$_REQUEST['f_id'];
            if( isset($_REQUEST['sb']))
                $supl_hidden.='&sb='.$_REQUEST['sb'];

            $lag_ref_ag_id='<a class="line" href="?ac='.$_REQUEST['ac'].$supl_hidden.'&sa=detail&ag_id='.
                           $this->ag_ref_ag_id.'&'.dossier::get().'">'.
                               $this->db->get_value("select ag_ref from action_gestion where ag_id=$1",array($this->ag_ref_ag_id)).
                               "</A>";
        }
        // sender
        $w=new ICard();
        $w->readonly=$readonly;
        $w->jrn=0;
        $w->name='qcode_dest';
        $w->value=($this->f_id_dest != 0)?$this->qcode_dest:"";
        $w->label="";
        $list_recipient=$this->db->make_list('select fd_id from fiche_def where frd_id in (14,25,8,9,16)');
        $w->extra=$list_recipient;
        $w->set_attribute('typecard',$list_recipient);
        $w->set_dblclick("fill_ipopcard(this);");
        $w->set_attribute('ipopup','ipopcard');

        // name of the field to update with the name of the card
        $w->set_attribute('label','qcode_dest_label');
        // name of the field to update with the name of the card
        $w->set_attribute('typecard',$w->extra);
        $w->set_function('fill_data');
        $w->javascript=sprintf(' onchange="fill_data_onchange(\'%s\');" ',
                               $w->name);

        $sp=new ISpan();
        $sp->name='qcode_dest_label';
        $sp->value=$qcode_dest_label;

        // contact
        $ag_contact=new ICard();
        $ag_contact->readonly=$readonly;
        $ag_contact->jrn=0;
        $ag_contact->name='ag_contact';
        $ag_contact->value='';
        $ag_contact->set_attribute('ipopup','ipopcard');

        if( $this->ag_contact != 0 )
        {
            $contact=new Fiche($this->db,$this->ag_contact);
            $ag_contact->value=$contact->get_quick_code();
        }

        $ag_contact->label="";

        $list_contact=$this->db->make_list('select fd_id from fiche_def where frd_id=16');
        $ag_contact->extra=$list_contact;

        $ag_contact->set_dblclick("fill_ipopcard(this);");
        // name of the field to update with the name of the card
        $ag_contact->set_attribute('label','ag_contact_label');
        // name of the field to update with the name of the card
        $ag_contact->set_attribute('typecard',$list_contact);
        $ag_contact->set_function('fill_data');
        $ag_contact->javascript=sprintf(' onchange="fill_data_onchange(\'%s\');" ',
                                        $ag_contact->name);

        $spcontact=new ISpan();
        $spcontact->name='ag_contact_label';
        $spcontact->value='';
        $fiche_contact=new Fiche($this->db);
        $fiche_contact->get_by_qcode($this->ag_contact);
        if ( $fiche_contact->id != 0 )
        {
            $spcontact->value=$fiche_contact->strAttribut(ATTR_DEF_NAME);
        }


        $h_agrefid=new IHidden();
        $str_ag_ref="<b>".(($this->ag_ref != "")?$this->ag_ref:" Nouveau ")."</b>";
        // Preparing the return string
        $r="";

        /* for new files */
        $upload=new IFile();
        $upload->name="file_upload[]";
        $upload->value="";
        $aAttachedFile=$this->db->get_array('select d_id,d_filename,d_mimetype,'.
                                            '\'show_document.php?'.
                                            Dossier::get().'&d_id=\'||d_id as link'.
                                                ' from document where ag_id=$1',
                                                array($this->ag_id));
        /* create the select for document */
        $aDocMod=new ISelect();
        $aDocMod->name='doc_mod';
        $aDocMod->value=$this->db->make_array('select md_id,dt_value||\' : \'||md_name as md_name'.
                                              ' from document_modele join document_type on (md_type=dt_id)'.
                                              ' order by md_name');
        $str_select_doc=$aDocMod->input();
        /* if no document then do not show the generate button */
        if ( empty($aDocMod->value) )
            $str_submit_generate="";
        else
            $str_submit_generate=HtmlInput::submit("generate",_("Génére le document"));

        $ag_id=$this->ag_id;

        /* fid = Icard  */
        $icard=new ICard();
        $icard->jrn=0;
        $icard->table=0;
        $icard->extra2='QuickCode';
        $icard->noadd="no";
        $icard->extra='all';

        /* Text desc  */
        $text=new IText();
        $num=new INum();

        /* TVA */
        $itva=new ITva_Popup($this->db);
        $itva->in_table=true;

        /* create aArticle for the detail section */
        for ($i=0;$i< MAX_ARTICLE;$i++)
        {
            /* fid = Icard  */
            $icard=new ICard();
            $icard->jrn=0;
            $icard->table=0;
            $icard->noadd="no";
            $icard->extra='all';
            $icard->name="e_march".$i;
            $tmp_ad=(isset($this->aAction_detail[$i]))?$this->aAction_detail[$i]:false;
            $icard->value='';
            if ( $tmp_ad )
            {
                $march=new Fiche($this->db);
                $f=$tmp_ad->get_parameter('qcode');
                if ( $f != 0 )
                {
                    $march->id=$f;
                    $icard->value=$march->get_quick_code();
                }
            }
            $icard->set_dblclick("fill_ipopcard(this);");
            // name of the field to update with the name of the card
            $icard->set_attribute('label',"e_march".$i."_label");
            // name of the field to update with the name of the card
            $icard->set_attribute('typecard',$icard->extra);
            $icard->set_attribute('ipopup','ipopcard');
            $icard->set_function('fill_data');
            $icard->javascript=sprintf(' onchange="fill_data_onchange(\'%s\');" ',
                                       $icard->name);

            $aArticle[$i]['fid']=$icard->search().$icard->input();

            $text->javascript=' onchange="clean_tva('.$i.');compute_ledger('.$i.')"';
            $text->name="e_march".$i."_label";
            $text->size=40;
            $text->value=($tmp_ad)?$tmp_ad->get_parameter('text'):"";
            $aArticle[$i]['desc']=$text->input();

            $num->javascript=' onchange="format_number(this);clean_tva('.$i.');compute_ledger('.$i.')"';
            $num->name="e_march".$i."_price";
            $num->size=8;
            $num->value=($tmp_ad)?$tmp_ad->get_parameter('price_unit'):0;
            $aArticle[$i]['pu']=$num->input();

            $num->name="e_quant".$i;
            $num->size=8;
            $num->value=($tmp_ad)?$tmp_ad->get_parameter('quantity'):0;
            $aArticle[$i]['quant']=$num->input();

            $itva->name='e_march'.$i.'_tva_id';
            $itva->value=($tmp_ad)?$tmp_ad->get_parameter('tva_id'):0;
            $itva->javascript=' onchange="format_number(this);clean_tva('.$i.');compute_ledger('.$i.')"';
            $itva->set_attribute('compute',$i);

            $aArticle[$i]['tvaid']=$itva->input();

            $num->name="e_march".$i."_tva_amount";
            $num->value=($tmp_ad)?$tmp_ad->get_parameter('tva_amount'):0;
            $num->javascript=' onchange="compute_ledger('.$i.')"';
            $num->size=8;
            $aArticle[$i]['tva']=$num->input();

            $num->name="tvac_march".$i;
            $num->value=($tmp_ad)?$tmp_ad->get_parameter('total'):0;
            $num->size=8;
            $aArticle[$i]['tvac']=$num->input();

            $aArticle[$i]['hidden_htva']=HtmlInput::hidden('htva_march'.$i,0);
            $aArticle[$i]['hidden_tva']=HtmlInput::hidden('tva_march'.$i,0);
            $aArticle[$i]['ad_id']=	($tmp_ad)?HtmlInput::hidden('ad_id'.$i,$tmp_ad->get_parameter('id')):HtmlInput::hidden('ad_id'.$i,0);


        }

        /* Add the needed hidden values */
        $r.=dossier::hidden();

        /* add the number of item */
        $Hid=new IHidden();
        $r.=$Hid->input("nb_item",MAX_ARTICLE);

        /* get template */
        ob_start();
        require_once ('template/detail-action.php');
        $content=ob_get_contents();
        ob_end_clean();
        $r.=$content;

        //hidden
        $r.="<p>";
        $r.=$h2->input();
        $r.=$h_agrefid->input("ag_ref_ag_id",$this->ag_ref_ag_id);
        $r.=$h_ag_id->input('ag_id',$this->ag_id);
        $hidden2=new IHidden();
        $r.=$hidden2->input('f_id_dest',$this->f_id_dest);
        $r.="</p>";

        // show the list of the concern operation
        if ( $this->db->count_sql('select * from action_gestion where ag_ref_ag_id!=0 and ag_ref_ag_id='.$this->ag_id.
                                  " limit 2") > 0 )
        {
            $sql=sprintf(" and ag_id in (select action_get_tree from comptaproc.action_get_tree(%s)) ",$this->ag_id);
            $r.=$this->myList($p_base,"",$sql);
        }
		// New referecne
		ob_start();
        require_once ('template/action-reference.php');
        $content=ob_get_contents();
        ob_end_clean();

		$r.=$content;
        return $r;

    }
    //----------------------------------------------------------------------
    /*!\brief This function shows the detail of an action thanks the ag_id
     */
    function get()
    {
        $sql="select ag_id, ag_comment,to_char (ag_timestamp,'DD.MM.YYYY') as ag_timestamp,".
             " f_id_dest,ag_title,ag_comment,ag_ref,d_id,ag_type,ag_state,  ".
             " ag_ref_ag_id, ag_dest, ag_hour, ag_priority, ag_cal,ag_contact ".
             " from action_gestion left join document using (ag_id) where ag_id=".$this->ag_id;
        $r=$this->db->exec_sql($sql);
        $row=Database::fetch_all($r);
        if ( $row==false) return;
        $this->ag_comment=$row[0]['ag_comment'];
        $this->ag_timestamp=$row[0]['ag_timestamp'];
        $this->ag_contact=$row[0]['ag_contact'];
        $this->f_id_dest=$row[0]['f_id_dest'];
        $this->ag_title=$row[0]['ag_title'];
        $this->ag_type=$row[0]['ag_type'];
        $this->ag_ref=$row[0]['ag_ref'];
        $this->ag_state=$row[0]['ag_state'];
        $this->ag_ref_ag_id=$row[0]['ag_ref_ag_id'];
        $this->d_id=$row[0]['d_id'];
        $this->ag_dest=$row[0]['ag_dest'];
        $this->ag_hour=$row[0]['ag_hour'];
        $this->ag_priority=$row[0]['ag_priority'];
        $this->ag_cal=$row[0]['ag_cal'];

        $action_detail=new Follow_Up_Detail($this->db);
        $action_detail->set_parameter('ag_id',$this->ag_id);
        $this->aAction_detail=$action_detail->load_all();


        // if there is no document set 0 to d_id
        if ( $this->d_id == "" )
            $this->d_id=0;
        // if there is a document fill the object
        if ($this->d_id != 0 )
        {
            $this->state=$row['0']['ag_state'];
            $this->ag_state=$row[0]['ag_state'];
        }
        $this->dt_id=$this->ag_type;
        $aexp=new Fiche($this->db,$this->f_id_dest);
        $this->qcode_dest=$aexp->strAttribut(ATTR_DEF_QUICKCODE);

    }

    /*!
     * \brief Save the document and propose to save the generated document or
     *  to upload one, the data are included except the file. Temporary the generated
     * document is save
     *
     * \return
     */
    function save()
    {

        // Get The sequence id,
        $seq_name="seq_doc_type_".$this->dt_id;
        $str_file="";
        $add_file='';

        // f_id exp
        $exp=new Fiche($this->db);
        $exp->get_by_qcode($this->qcode_dest);

        $contact=new Fiche($this->db);
        $contact->get_by_qcode($this->ag_contact);

        if ( trim($this->ag_title) == "")
        {
            $doc_mod=new document_type($this->db);
            $doc_mod->dt_id=$this->dt_id;
            $doc_mod->get();
            $this->ag_title=$doc_mod->dt_value;
        }
        $this->ag_id=$this->db->get_next_seq('action_gestion_ag_id_seq');

        // Create the reference
        $ref=$this->dt_id.'/'.$this->ag_id;
        $this->ag_ref=$ref;
        if ( $this->ag_cal=='on')
            $ag_cal='C';
        else
            $ag_cal='I';
        $this->ag_ref_ag_id=(strlen(trim($this->ag_ref_ag_id))==0)?0:$this->ag_ref_ag_id;
        // save into the database
        $sql="insert into action_gestion".
             "(ag_id,ag_timestamp,ag_type,ag_title,f_id_dest,ag_comment,ag_ref,ag_ref_ag_id, ag_dest, ".
             " ag_hour, ag_priority,ag_cal,ag_owner,ag_contact,ag_state) ".
             " values ($1,to_date($2,'DD.MM.YYYY'),$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15)";
        $this->db->exec_sql($sql,array($this->ag_id, /* 1 */
                                       $this->ag_timestamp, /* 2 */
                                       $this->dt_id,	/* 3 */
                                       $this->ag_title, /* 4 */
                                       $exp->id,	  /* 5 */
                                       $this->ag_comment,	  /* 6 */
                                       $ref,		  /* 7 */
                                       $this->ag_ref_ag_id,  /* 8 */
                                       $this->ag_dest,	   /* 9 */
                                       $this->ag_hour,	   /* 10 */
                                       $this->ag_priority,   /* 11 */
                                       $ag_cal,	   /* 12 */
                                       $_SESSION['g_user'], /* 13 */
                                       $contact->id,	  /* 14 */
                                       $this->ag_state	  /* 15 */
                                      )
                           );

        /* insert also the details */
        for ( $i = 0; $i < MAX_ARTICLE;$i++)
        {
            $act=new Follow_Up_Detail($this->db);
            $act->from_array($_POST,$i);
            $act->ag_id=$this->ag_id;
            $act->save();
        }

        /* Upload the documents */
        $doc=new Document($this->db);
        $doc->Upload($this->ag_id);
    }
    /*! myList($p_filter="")
     * \brief Show list of action by default if sorted on date
     *\param $p_base base url with ac...
     * \param $p_filter filters on the document_type
     * \param $p_search must a valid sql command ( ex 'and  ag_title like upper('%hjkh%'))
     * \return string containing html code
     */
    function myList($p_base,$p_filter="",$p_search="")
    {
        $str_dossier=dossier::get();
        // for the sort
        $url="?".$str_dossier.'&'.$p_base;


		$table=new Sort_Table();
		$table->add('Date',$url,'order by ag_timestamp asc','order by ag_timestamp desc','da','dd');
		$table->add('Réf.',$url,'order by ag_ref asc','order by ag_ref desc','ra','rd');
		$table->add('Expéditeur',$url,'order by name asc','order by name desc','ea','ed');
		$table->add('Titre',$url,'order by ag_title asc','order by ag_title desc','ta','td');
		$table->add('Concerne',$url,'order by ag_ref_ag_id asc','order by ag_ref_ag_id desc','ca','cd');

		$ord=(! isset($_GET['ord']))?"dd":$_GET['ord'];
		$sort=$table->get_sql_order($ord);

        if ( strlen(trim($p_filter)) != 0 )
            $p_filter_doc=" dt_id in ( $p_filter )";
        else
            $p_filter_doc=" 1=1 ";

        $sql="
             select ag_id,to_char(ag_timestamp,'DD.MM.YYYY') as my_date,ag_ref_ag_id,f_id_dest".
             ",ag_title,dt_value,ag_ref, ag_priority,ag_state,
				(select ad_value from fiche_Detail where f_id=action_gestion.f_id_dest and ad_id=1) as name
             from action_gestion
             join document_type on (ag_type=dt_id)
             where $p_filter_doc $p_search $sort";
        $max_line=$this->db->count_sql($sql);
        $step=$_SESSION['g_pagesize'];
        $page=(isset($_GET['offset']))?$_GET['page']:1;
        $offset=(isset($_GET['offset']))?Database::escape_string($_GET['offset']):0;
        if ( $step != -1 ) $limit=" LIMIT $step OFFSET $offset ";
        else $limit='';
        $bar=jrn_navigation_bar($offset,$max_line,$step,$page);

        $Res=$this->db->exec_sql($sql.$limit);
        $a_row=Database::fetch_all($Res);

        $r="";
        $r.=$bar;
        $r.='<table class="document">';
        $r.="<tr>";
        $r.='<th>'.$table->get_header(0).'</th>';
        $r.='<th>'.$table->get_header(1).'</th>';
        $r.='<th>'.$table->get_header(2).'</th>';
        $r.='<th>'.$table->get_header(3).'</th>';
        $r.='<th>type</th>';
		$r.=th('Etat');
		$r.=th('Priorité');
        $r.='<th>'.$table->get_header(4).'</th>';
		if (isset($this->suppress)) {
			$r.="<th> Suppr. dépendance</th>";
		}
        $r.="</tr>";


        // if there are no records return a message
        if ( sizeof ($a_row) == 0 or $a_row == false )
        {
            $r='<div style="clear:both">';
            $r.='<hr>Aucun enregistrement trouvé';
            $r.="</div>";
            return $r;

        }
        $today=date('d.m.Y');
        $i=0;
        //show the sub_action
        foreach ($a_row as $row )
        {
            $href='<A class="document" HREF="do.php?'.$p_base.'&sa=detail&ag_id='.$row['ag_id'].'&'.$str_dossier.'&ac='.$_REQUEST['ac'].'">';
            $i++;
            $tr=($i%2==0)?'even':'odd';
            if ($row['ag_priority'] < 2) $tr='priority1';
            $st='';
            if ($row['my_date']==$today) $st=' style="font-family:bold; border:2px solid orange;"';
            $r.="<tr class=\"$tr\" $st>";
            $r.="<td>".$href.$row['my_date'].'</a>'."</td>";
			$r.="<td>".$href.$row['ag_ref'].'</a>'."</td>";

            // Expediteur
            $fexp=new Fiche($this->db);
            $fexp->id=$row['f_id_dest'];
            $qcode_dest=$fexp->strAttribut(ATTR_DEF_QUICKCODE);

            $qexp=($qcode_dest==NOTFOUND)?"Interne":$qcode_dest;
            $jsexp=sprintf("javascript:showfiche('%s')",
                           $qexp);
            if ( $qexp != 'Interne' )
            {
                $r.="<td>$href".$qexp." : ".$fexp->getName().'</a></td>';
            }
            else
                $r.="<td>$href Interne </a></td>";

            $ref="";

            // show reference
            if ( $row['ag_ref_ag_id'] != 0 )
            {
                $retSqlStmt=$this->db->exec_sql(
                                "select ag_ref from action_gestion where ag_id=".$row['ag_ref_ag_id']);
                $retSql=Database::fetch_all($retSqlStmt);
                if ( $retSql != null )
                {
                    foreach ($retSql as $line)
                    {
                        $ref.='<A  href="do.php?'.$p_base.'&query='.$line['ag_ref'].'&'.$str_dossier.'">'.
                              $line['ag_ref']."<A>";
                    }
                }
            }

            $r.='<td>'.$href.
                h($row['ag_title'])."</A></td>";
            $r.="<td>".$row['dt_value']."</td>";
	    /*
	     * State
	     */
	    switch ( $row['ag_state'] )
	      {
	      case 1:
		$state='Fermé';
		break;
	      case 2:
		$state="A suivre";
		break;
	      case 3:
		$state="A faire";
		break;
	      case 4:
		$state="Abandonné";
		break;
	      }
	    $r.=td($state);
	    /*
	     * State
	     */
	    switch ( $row['ag_priority'] )
	      {
	      case 1:
		$priority='Haute';
		break;
	      case 2:
		$priority="Moyenne";
		break;
	      case 3:
		$priority="Important";
		break;
	      }
	    $r.=td($priority);

            $r.="<td>" . $ref . "</td>";
			if (isset($this->suppress))
			{
				$ck = new ICheckBox('sup_dep[]');
				$ck->value = $row['ag_id'];
				$r.="<td>" . $ck->input() . "</td>";
			}
            $r.="</tr>";

        }

        $r.="</table>";

        $r.=$bar;
        //$r.="</div>";
        return $r;
    }
    //----------------------------------------------------------------------
    /*!\brief Update the data into the database
     *
     * \return true on success otherwise false
     */
    function Update()
    {
        // if ag_id == 0 nothing to do
        if ( $this->ag_id == 0 ) return ;
        // retrieve customer
        // f_id

        if ( trim($this->qcode_dest) =="" )
        {
            // internal document
            $this->f_id_dest=0; // internal document
        }
        else
        {
            $tiers=new Fiche($this->db);
            if ( $tiers->get_by_qcode($this->qcode_dest) == -1 ) // Error we cannot retrieve this qcode
                return false;
            else
                $this->f_id_dest=$tiers->id;

        }
        $contact=new Fiche($this->db);
        if ( $contact->get_by_qcode($this->ag_contact) == -1 )
            $contact->id=0;


        $ref=$this->dt_id.'/'.$this->ag_id;
        if ( $this->ag_cal == 'on') $ag_cal='C';
        else $ag_cal='I';
        $this->ag_ref=$ref;
        $this->db->exec_sql("update action_gestion set ag_comment=$1,".
                            " ag_timestamp=to_date($2,'DD.MM.YYYY'),".
                            " ag_title=$3,".
                            " ag_type=$4, ".
                            " f_id_dest=$5, ".
                            " ag_ref_ag_id=$6 ,".
                            "ag_state=$7,".
                            " ag_hour = $9 ,".
                            " ag_priority = $10 ,".
                            " ag_dest = $11 , ".
                            " ag_cal = $12 ,".
                            " ag_contact = $13, ".
							" ag_ref = $14 ".
                            " where ag_id = $8",
                            array ( $this->ag_comment, /* 1 */
                                    $this->ag_timestamp, /* 2 */
                                    $this->ag_title,     /* 3 */
                                    $this->dt_id,	       /* 4 */
                                    $this->f_id_dest,     /* 5 */
                                    $this->ag_ref_ag_id, /* 6 */
                                    $this->ag_state,     /* 7 */
                                    $this->ag_id,      /* 8 */
                                    $this->ag_hour,    /* 9 */
                                    $this->ag_priority, /* 10 */
                                    $this->ag_dest,     /* 11 */
                                    $ag_cal,	      /* 12 */
                                    $contact->id,   /* 13 */
									$this->ag_ref
                                  ));
        // Upload  documents
        $doc=new Document($this->db);
        $doc->Upload($this->ag_id);

        /* save action details */
        for ( $i = 0; $i < MAX_ARTICLE;$i++)
        {
            $act=new Follow_Up_Detail($this->db);
            $act->from_array($_POST,$i);
            $act->save();
        }
        return true;

    }

    /*!\brief generate the document and add it to the action
     * \param md_id is the id of the document_modele
     * \param $p_array contains normally the $_POST
     */
    function generate_document($md_id,$p_array)
    {
        $doc=new Document($this->db);
        $mod=new Document_Modele($this->db,$md_id);
        $mod->load();
        $doc->f_id=$this->f_id_dest;
        $doc->md_id=$md_id;
        $doc->ag_id=$this->ag_id;
        $doc->Generate($p_array);
    }
    /*!\brief put an array in the variable member, the indice
     * is the member name
    *\param $p_array to parse
    *\return nothing
    */
    function fromArray($p_array)
    {
        $this->ag_id=(isset($p_array['ag_id']))?$p_array['ag_id']:"";
        $this->qcode_dest=(isset($p_array['qcode_dest']))?$p_array['qcode_dest']:"";
        $this->f_id_dest=(isset($p_array['f_id_dest']))?$p_array['f_id_dest']:0;
        $this->ag_ref_ag_id=(isset($p_array['ag_ref_ag_id']))?$p_array['ag_ref_ag_id']:0;
        $this->ag_timestamp=(isset($p_array['ag_timestamp']))?$p_array['ag_timestamp']:date('d.m.Y');
        $this->qcode_dest=(isset($p_array['qcode_dest']))?$p_array['qcode_dest']:"";
        $this->dt_id=(isset($p_array['dt_id']))?$p_array['dt_id']:"";
        $this->ag_state=(isset($p_array['ag_state']))?$p_array['ag_state']:2;
        $this->ag_ref=(isset($p_array['ag_ref']))?$p_array['ag_ref']:"";
        $this->ag_title=(isset($p_array['ag_title']))?$p_array['ag_title']:"";
        $this->ag_hour=(isset($p_array['ag_hour']))?$p_array['ag_hour']:"";
        $this->ag_dest=(isset($p_array['ag_dest']))?$p_array['ag_dest']:"";
        $this->ag_priority=(isset($p_array['ag_priority']))?$p_array['ag_priority']:2;
        $this->ag_cal=(isset($p_array['ag_cal']))?'on':"";
        $this->ag_contact=(isset($p_array['ag_contact']))?$p_array['ag_contact']:"";
        $this->ag_comment=(isset($p_array['ag_comment']))?$p_array['ag_comment']:"";

    }
    /*!\brief remove the action
     *
     */
    function remove()
    {
        $this->get();
        // remove the key
        $sql="delete from action_gestion where ag_id=$1";
        $this->db->exec_sql($sql,array($this->ag_id));

        // remove the ref of the depending action
        $sql="update action_gestion set ag_ref_ag_id=0 where ag_ref_ag_id=$1";
        $this->db->exec_sql($sql,array($this->ag_id));

        /*  check the number of attached document */
        $doc=new Document($this->db);
        $aDoc=$doc->get_all($this->ag_id);
        if ( ! empty ($aDoc))
        {
            // if there are documents
            for ($i=0;$i <sizeof($aDoc);$i++)
            {
                $aDoc[$i]->remove();
            }
        }
    }
    /*!\brief return the last p_limit operation into an array
     *\param $p_limit is the max of operation to return
     *\return $p_array of Follow_Up object
     */
    function get_last($p_limit)
    {
        $sql="select coalesce(vw_name,'Interne') as vw_name,ag_id,ag_title,ag_ref, dt_value,to_char(ag_timestamp,'DD.MM.YYYY') as ag_timestamp_fmt,ag_timestamp ".
             " from action_gestion join document_type ".
             " on (ag_type=dt_id) left join vw_fiche_attr on (f_id=f_id_dest) where ag_state in (2,3) order by ag_timestamp desc limit $p_limit";
        $array=$this->db->get_array($sql);
        return $array;
    }
	/**
	 *@brief add an related action to the  current one
	 */
	function add_depend($p_ref)
	{
		if ($p_ref == 0 || $p_ref==$this->ag_id) return;
		// check if action exist and is not already related to the current action
		$exist=$this->db->get_value("select count(*) from action_gestion where ag_id=$1",array($p_ref));
		if ( $exist == 0) return;

		$this->db->exec_sql("update action_gestion set ag_ref_ag_id=$1 where ag_id=$2",
				array($this->ag_id,$p_ref));

	}
	function suppress_depend($p_array)
	{
		for ($i=0;$i<count($p_array);$i++)
		{
			$this->db->exec_sql("update action_gestion set ag_ref_ag_id=0 where ag_id=$1",
				array($p_array[$i]));
		}
	}
}
