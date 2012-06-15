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
require_once 'class_irelated_action.php';

/* !\file
 * \brief class_action for manipulating actions
 * action can be :
 * <ul>
 * <li>an invoice
 * <li>a meeting
 * <li>an order
 * <li>a letter
 * </ul>
 * The table document_type are the possible actions
 */
/* !
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

	var $db; /* !<  $db  database connexion    */
	var $ag_timestamp;  /* !<   $ag_timestamp document date (ag_gestion.ag_timestamp) */
	var $dt_id;   /* !<   $dt_id type of the document (document_type.dt_id) */
	var $ag_state; /* !<   $ag_state stage of the document (printed, send to client...) */
	var $d_number;   /* !<   $d_number number of the document */
	var $d_filename; /* !<   $d_filename filename's document      */
	var $d_mimetype; /* !<   $d_mimetype document's filename      */
	var $ag_title;   /* !<   $ag_title title document	      */
	var $f_id; /* !<   $f_id_dest fiche id (From field )  */
	var $ag_ref;  /* !< $ag_ref is the ref  */
	var $ag_hour;  /* !< $ag_hour is the hour of the meeting, action */
	var $ag_priority; /* !< $ag_priority is the priority 1 High, 2 medium, 3 low */
	var $ag_dest;  /* !< $ag_dest person who is in charged */
	var $ag_contact;  /* !< $ag_contact contact */
	var $ag_remind_date;  /* !< $ag_contact contact */

	/* !  constructor
	 * \brief constructor
	 * \param p_cn database connection
	 */

	function __construct($p_cn)
	{
		$this->db = $p_cn;
		$this->ag_id=0;
		$this->f_id = 0;
	}
	static function sql_security_filter($cn,$p_mode)
	{
		global $g_user;
		$profile=$cn->get_value("select p_id from profile_user where user_name=$1",array($g_user->login));
		if ($profile == '') die ("Security");
		if ($p_mode == 'R')
		{
			$sql=" (ag_dest in (select p_granted from user_sec_action_profile where p_id=$profile ) ) ";
		}
		if ($p_mode == 'W')
		{
			$sql=" ( ag_dest in (select p_granted from user_sec_action_profile where p_id=$profile and ua_right='W' ) )";
		}
		return $sql;
	}
	//----------------------------------------------------------------------
	/* !
	 * \brief Display the object, the tags for the FORM
	 *        are in the caller. It will be used for adding and updating
	 *        action
	 * \note  If  ag_id is not equal to zero then it is an update otherwise
	 *        it is a new document
	 *
	 * \param $p_view if set to true the form will be in readonly mode (value: true or false)
	 * \param $p_gen true we show the tag for generating a doc (value : true or false)
	 * \param $p_base is the ac parameter
	 * \param $retour is the html code for the return button
	 * \note  update the reference number or the document type is not allowed
	 *
	 *
	 * \return string containing the html code
	 */
	function Display($p_view, $p_gen, $p_base, $retour = "")
	{
		global $g_user;
		if ($p_view == 'UPD')
		{
			$upd = true;
			$readonly = false;
		}
		elseif ($p_view == "NEW")
		{
			$upd = false;
			$readonly = false;
			$this->ag_ref=_("Nouveau");
		}
		elseif ($p_view == 'READ')
		{
			$upd = true;
			$readonly = true;
		}
		else
		{
			exit('class_action' . __LINE__ . 'Follow_Up::Display error unknown parameter' . $p_view);
		}
		// Compute the widget
		// Date
		$date = new IDate();
		$date->readOnly = $readonly;
		$date->name = "ag_timestamp";
		$date->value = $this->ag_timestamp;

		$remind_date = new IDate();
		$remind_date->readOnly = $readonly;
		$remind_date->name = "ag_remind_date";
		$remind_date->value = $this->ag_remind_date;


		// Doc Type
		$doc_type = new ISelect();
		$doc_type->name = "dt_id";
		$doc_type->value = $this->db->make_array("select dt_id,dt_value from document_type order by dt_value");
		$doc_type->selected = $this->dt_id;
		$doc_type->readOnly = $readonly;
		$str_doc_type = $doc_type->input();

		// Description
		$desc = new ITextArea();
		$desc->width = 70;
		$desc->heigh = 5;
		$desc->name = "ag_comment";
		$desc->readOnly = $readonly;
		if (strlen($desc->value) > 300)
		{
			$desc->width = 120;
			$desc->heigh = 40;
		}
		$acomment = $this->db->get_array("SELECT agc_id, ag_id, to_char(agc_date,'DD.MM.YYYY') as str_agc_date, agc_comment, tech_user
				 FROM action_gestion_comment where ag_id=$1 order by agc_id;", array($this->ag_id)
		);

		// List opération liées
		$operation = $this->db->get_array("select ago_id,j.jr_id,j.jr_internal,j.jr_comment,to_char(j.jr_date,'DD.MM.YY') as str_date
			from jrn as j join action_gestion_operation as ago on (j.jr_id=ago.jr_id)
			where ag_id=$1 order by jr_date", array($this->ag_id));
		$iconcerned = new IConcerned('operation');

		// List related action
		$action = $this->db->get_array("
			select ag_id,ag_ref,substr(ag_title,1,40) as sub_title,to_char(ag_timestamp,'DD.MM.YY') as str_date ,
				ag_timestamp,dt_value
					from action_gestion
					 join document_type on (ag_type=dt_id)
				where
				ag_id in (select aga_greatest from action_gestion_related where aga_least =$1)
				or
				ag_id in (select aga_least from action_gestion_related where aga_greatest =$1)
				order by ag_timestamp", array($this->ag_id));
		$iaction = new IRelated_Action('action');
		$iaction->value = (isset($this->action)) ? $this->action : "";

		// state
		// Retrieve the value
		$a = $this->db->make_array("select s_id,s_value from document_state ");
		$state = new ISelect();
		$state->readOnly = $readonly;
		$state->name = "ag_state";
		$state->value = $a;
		$state->selected = $this->ag_state;
		$str_state = $state->input();

		// Retrieve the value if there is an attached doc
		$doc_ref = "";
		// Document id

		$h2 = new IHidden();
		$h2->name = "d_id";
		$h2->value = $this->d_id;

		if ($this->d_id != 0 && $this->d_id != "")
		{
			$h2->readonly = ($p_view == 'NEW') ? false : true;
			$doc = new Document($this->db, $this->d_id);
			$doc->get();
			if (strlen(trim($doc->d_lob)) != 0)
			{
				$d_id = new IHidden();
				$doc_ref = "<p> Document " . $doc->anchor() . '</p>';
				$doc_ref.=$h2->input() . $d_id->input('d_id', $this->d_id);
			}
		}


		// title
		$title = new IText();
		$title->readOnly = $readonly;
		$title->name = "ag_title";
		$title->value = $this->ag_title;
		$title->size = 60;


		// Priority of the ag_priority
		$ag_priority = new ISelect();
		$ag_priority->readOnly = $readonly;
		$ag_priority->name = "ag_priority";
		$ag_priority->selected = $this->ag_priority;
		$ag_priority->value = array(array('value' => 1, 'label' => 'Haute'),
			array('value' => 2, 'label' => 'Moyenne'),
			array('value' => 3, 'label' => 'Basse')
		);
		$str_ag_priority = $ag_priority->input();

		// hour of the action (meeting) ag_hour
		$ag_hour = new IText();
		$ag_hour->readOnly = $readonly;
		$ag_hour->name = "ag_hour";
		$ag_hour->value = $this->ag_hour;
		$ag_hour->size = 6;
		$ag_hour->javascript = " onblur=check_hour('ag_hour');";
		$str_ag_hour = $ag_hour->input();

		// Profile in charged of the action
		$ag_dest = new ISelect();
		$ag_dest->readOnly = $readonly;
		$ag_dest->name = "ag_dest";
		// select profile
		$aAg_dest = $this->db->make_array("select  p_id as value, " .
				"p_name as label " .
				" from profile  where p_id in (select p_granted from user_sec_action_profile where ua_right='W' and p_id=".$g_user->get_profile().") order by 2");

		$ag_dest->value = $aAg_dest;
		$ag_dest->selected = $this->ag_dest;
		$str_ag_dest = $ag_dest->input();

		// ag_ref
		// Always false for update

		$client_label = new ISpan();

		/* Add button */
		$f_add_button = new IButton('add_card');
		$f_add_button->label = _('Créer une nouvelle fiche');
		$f_add_button->set_attribute('ipopup', 'ipop_newcard');
		$filter = $this->db->make_list('select fd_id from fiche_def ');
		$f_add_button->set_attribute('filter', $filter);

		$f_add_button->javascript = " select_card_type(this);";
		$str_add_button = $f_add_button->input();

		// f_id_dest sender
		if ($this->qcode_dest != NOTFOUND && strlen(trim($this->qcode_dest)) != 0)
		{
			$tiers = new Fiche($this->db);
			$tiers->get_by_qcode($this->qcode_dest);
			$qcode_dest_label = $tiers->strAttribut(1);
			$this->f_id_dest = $tiers->id;
		}
		else
		{
			$qcode_dest_label = ($this->f_id_dest == 0 || trim($this->qcode_dest) == "") ? 'Interne ' : 'Error';
		}

		$h_ag_id = new IHidden();
		// if concerns another action : show the link otherwise nothing
		//
		// sender
		$w = new ICard();
		$w->readOnly = $readonly;
		$w->jrn = 0;
		$w->name = 'qcode_dest';
		$w->value = ($this->f_id_dest != 0) ? $this->qcode_dest : "";
		$w->label = "";
		$list_recipient = $this->db->make_list('select fd_id from fiche_def where frd_id in (14,25,8,9,16)');
		$w->extra = $list_recipient;
		$w->set_attribute('typecard', $list_recipient);
		$w->set_dblclick("fill_ipopcard(this);");
		$w->set_attribute('ipopup', 'ipopcard');

		// name of the field to update with the name of the card
		$w->set_attribute('label', 'qcode_dest_label');
		// name of the field to update with the name of the card
		$w->set_attribute('typecard', $w->extra);
		$w->set_function('fill_data');
		$w->javascript = sprintf(' onchange="fill_data_onchange(\'%s\');" ', $w->name);

		$sp = new ISpan();
		$sp->name = 'qcode_dest_label';
		$sp->value = $qcode_dest_label;

		// contact
		$ag_contact = new ICard();
		$ag_contact->readOnly = $readonly;
		$ag_contact->jrn = 0;
		$ag_contact->name = 'ag_contact';
		$ag_contact->value = '';
		$ag_contact->set_attribute('ipopup', 'ipopcard');

		if ($this->ag_contact != 0)
		{
			$contact = new Fiche($this->db, $this->ag_contact);
			$ag_contact->value = $contact->get_quick_code();
		}

		$ag_contact->label = "";

		$list_contact = $this->db->make_list('select fd_id from fiche_def where frd_id=16');
		$ag_contact->extra = $list_contact;

		$ag_contact->set_dblclick("fill_ipopcard(this);");
		// name of the field to update with the name of the card
		$ag_contact->set_attribute('label', 'ag_contact_label');
		// name of the field to update with the name of the card
		$ag_contact->set_attribute('typecard', $list_contact);
		$ag_contact->set_function('fill_data');
		$ag_contact->javascript = sprintf(' onchange="fill_data_onchange(\'%s\');" ', $ag_contact->name);

		$spcontact = new ISpan();
		$spcontact->name = 'ag_contact_label';
		$spcontact->value = '';
		$fiche_contact = new Fiche($this->db);
		$fiche_contact->get_by_qcode($this->ag_contact);
		if ($fiche_contact->id != 0)
		{
			$spcontact->value = $fiche_contact->strAttribut(ATTR_DEF_NAME);
		}


		$h_agrefid = new IHidden();
		$iag_ref=new IText("ag_ref");
		$iag_ref->value=$this->ag_ref;
		$iag_ref->readOnly = ($p_view == "NEW" ||$p_view == 'READ')?true:false;
		$str_ag_ref =$iag_ref->input();
		// Preparing the return string
		$r = "";

		/* for new files */
		$upload = new IFile();
		$upload->name = "file_upload[]";
		$upload->readOnly=$readonly;
		$upload->value = "";
		$aAttachedFile = $this->db->get_array('select d_id,d_filename,d_mimetype,' .
				'\'show_document.php?' .
				Dossier::get() . '&d_id=\'||d_id as link' .
				' from document where ag_id=$1', array($this->ag_id));
		/* create the select for document */
		$aDocMod = new ISelect();
		$aDocMod->name = 'doc_mod';
		$aDocMod->value = $this->db->make_array('select md_id,dt_value||\' : \'||md_name as md_name' .
				' from document_modele join document_type on (md_type=dt_id)' .
				' order by md_name');
		$str_select_doc = $aDocMod->input();
		/* if no document then do not show the generate button */
		if (empty($aDocMod->value) )
			$str_submit_generate = "";
		else
			$str_submit_generate = HtmlInput::submit("generate", _("Génére le document"));

		$ag_id = $this->ag_id;

		/* fid = Icard  */
		$icard = new ICard();
		$icard->jrn = 0;
		$icard->table = 0;
		$icard->extra2 = 'QuickCode';
		$icard->noadd = "no";
		$icard->extra = 'all';

		/* Text desc  */
		$text = new IText();
		$num = new INum();

		/* TVA */
		$itva = new ITva_Popup($this->db);
		$itva->in_table = true;

		/* create aArticle for the detail section */
		for ($i = 0; $i < MAX_ARTICLE; $i++)
		{
			/* fid = Icard  */
			$icard = new ICard();
			$icard->jrn = 0;
			$icard->table = 0;
			$icard->noadd = "no";
			$icard->extra = 'all';
			$icard->name = "e_march" . $i;
			$tmp_ad = (isset($this->aAction_detail[$i])) ? $this->aAction_detail[$i] : false;
			$icard->readOnly=$readonly;
			$icard->value = '';
			if ($tmp_ad)
			{
				$march = new Fiche($this->db);
				$f = $tmp_ad->get_parameter('qcode');
				if ($f != 0)
				{
					$march->id = $f;
					$icard->value = $march->get_quick_code();
				}
			}
			$icard->set_dblclick("fill_ipopcard(this);");
			// name of the field to update with the name of the card
			$icard->set_attribute('label', "e_march" . $i . "_label");
			// name of the field to update with the name of the card
			$icard->set_attribute('typecard', $icard->extra);
			$icard->set_attribute('ipopup', 'ipopcard');
			$icard->set_function('fill_data');
			$icard->javascript = sprintf(' onchange="fill_data_onchange(\'%s\');" ', $icard->name);

			$aArticle[$i]['fid'] = $icard->search() . $icard->input();

			$text->javascript = ' onchange="clean_tva(' . $i . ');compute_ledger(' . $i . ')"';
			$text->name = "e_march" . $i . "_label";
			$text->size = 40;
			$text->value = ($tmp_ad) ? $tmp_ad->get_parameter('text') : "";
			$text->readOnly=$readonly;
			$aArticle[$i]['desc'] = $text->input();

			$num->javascript = ' onchange="format_number(this);clean_tva(' . $i . ');compute_ledger(' . $i . ')"';
			$num->name = "e_march" . $i . "_price";
			$num->size = 8;
			$num->readOnly=$readonly;
			$num->value = ($tmp_ad) ? $tmp_ad->get_parameter('price_unit') : 0;
			$aArticle[$i]['pu'] = $num->input();

			$num->name = "e_quant" . $i;
			$num->size = 8;
			$num->value = ($tmp_ad) ? $tmp_ad->get_parameter('quantity') : 0;
			$aArticle[$i]['quant'] = $num->input();

			$itva->name = 'e_march' . $i . '_tva_id';
			$itva->value = ($tmp_ad) ? $tmp_ad->get_parameter('tva_id') : 0;
			$itva->readOnly=$readonly;
			$itva->js = ' onchange="format_number(this);clean_tva(' . $i . ');compute_ledger(' . $i . ')"';
			$itva->set_attribute('compute', $i);

			$aArticle[$i]['tvaid'] = $itva->input();

			$num->name = "e_march" . $i . "_tva_amount";
			$num->value = ($tmp_ad) ? $tmp_ad->get_parameter('tva_amount') : 0;
			$num->javascript = " onchange=\"compute_ledger(' . $i . ')\"";
			$num->size = 8;
			$aArticle[$i]['tva'] = $num->input();

			$num->name = "tvac_march" . $i;
			$num->value = ($tmp_ad) ? $tmp_ad->get_parameter('total') : 0;
			$num->size = 8;
			$aArticle[$i]['tvac'] = $num->input();

			$aArticle[$i]['hidden_htva'] = HtmlInput::hidden('htva_march' . $i, 0);
			$aArticle[$i]['hidden_tva'] = HtmlInput::hidden('tva_march' . $i, 0);
			$aArticle[$i]['ad_id'] = ($tmp_ad) ? HtmlInput::hidden('ad_id' . $i, $tmp_ad->get_parameter('id')) : HtmlInput::hidden('ad_id' . $i, 0);
		}

		/* Add the needed hidden values */
		$r.=dossier::hidden();

		/* add the number of item */
		$Hid = new IHidden();
		$r.=$Hid->input("nb_item", MAX_ARTICLE);
		$r.=HtmlInput::request_to_hidden(array("remind_date","sag_ref","only_internal","state","qcode", "ag_dest_query", "query", "tdoc", "date_start", "date_end", "see_all", "all_action"));
		/* get template */
		ob_start();
		require_once 'template/detail-action.php';
		$content = ob_get_contents();
		ob_end_clean();
		$r.=$content;

		//hidden
		$r.="<p>";
		$r.=$h2->input();
		$r.=$h_ag_id->input('ag_id', $this->ag_id);
		$hidden2 = new IHidden();
		$r.=$hidden2->input('f_id_dest', $this->f_id_dest);
		$r.="</p>";

		return $r;
	}

	//----------------------------------------------------------------------
	/* !\brief This function shows the detail of an action thanks the ag_id
	 */
	function get()
	{
		$sql = "select ag_id,to_char (ag_timestamp,'DD.MM.YYYY') as ag_timestamp," .
				" f_id_dest,ag_title,ag_ref,d_id,ag_type,ag_state, ag_owner, " .
				"  ag_dest, ag_hour, ag_priority, ag_contact,to_char (ag_remind_date,'DD.MM.YYYY') as ag_remind_date " .
				" from action_gestion left join document using (ag_id) where ag_id=" . $this->ag_id;
		$r = $this->db->exec_sql($sql);
		$row = Database::fetch_all($r);
		if ($row == false){
			$this->ag_id=0;
			return;
		}
		$this->ag_timestamp = $row[0]['ag_timestamp'];
		$this->ag_contact = $row[0]['ag_contact'];
		$this->f_id_dest = $row[0]['f_id_dest'];
		$this->ag_title = $row[0]['ag_title'];
		$this->ag_type = $row[0]['ag_type'];
		$this->ag_ref = $row[0]['ag_ref'];
		$this->ag_state = $row[0]['ag_state'];
		$this->d_id = $row[0]['d_id'];
		$this->ag_dest = $row[0]['ag_dest'];
		$this->ag_hour = $row[0]['ag_hour'];
		$this->ag_priority = $row[0]['ag_priority'];
		$this->ag_remind_date = $row[0]['ag_remind_date'];
		$this->ag_owner= $row[0]['ag_owner'];

		$action_detail = new Follow_Up_Detail($this->db);
		$action_detail->set_parameter('ag_id', $this->ag_id);
		$this->aAction_detail = $action_detail->load_all();


		// if there is no document set 0 to d_id
		if ($this->d_id == "")
			$this->d_id = 0;
		// if there is a document fill the object
		if ($this->d_id != 0)
		{
			$this->state = $row['0']['ag_state'];
			$this->ag_state = $row[0]['ag_state'];
		}
		$this->dt_id = $this->ag_type;
		$aexp = new Fiche($this->db, $this->f_id_dest);
		$this->qcode_dest = $aexp->strAttribut(ATTR_DEF_QUICKCODE);
	}

	/* !
	 * \brief Save the document and propose to save the generated document or
	 *  to upload one, the data are included except the file. Temporary the generated
	 * document is save
	 *
	 * \return
	 */

	function save()
	{

		// Get The sequence id,
		$seq_name = "seq_doc_type_" . $this->dt_id;
		$str_file = "";
		$add_file = '';

		// f_id exp
		$exp = new Fiche($this->db);
		$exp->get_by_qcode($this->qcode_dest);

		$contact = new Fiche($this->db);
		$contact->get_by_qcode($this->ag_contact);

		if (trim($this->ag_title) == "")
		{
			$doc_mod = new document_type($this->db);
			$doc_mod->dt_id = $this->dt_id;
			$doc_mod->get();
			$this->ag_title = $doc_mod->dt_value;
		}
		$this->ag_id = $this->db->get_next_seq('action_gestion_ag_id_seq');

		// Create the reference
		$ag_ref=$this->db->get_value('select dt_prefix from document_type where dt_id=$1',array($this->dt_id)).'-'.$this->db->get_next_seq($seq_name);
		$this->ag_ref = $ag_ref;

		// save into the database
		if ($this->ag_remind_date != null || $this->ag_remind_date != '')
		{
			$sql = "insert into action_gestion" .
					"(ag_id,ag_timestamp,ag_type,ag_title,f_id_dest,ag_ref, ag_dest, " .
					" ag_hour, ag_priority,ag_owner,ag_contact,ag_state,ag_remind_date) " .
					" values ($1,to_date($2,'DD.MM.YYYY'),$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,to_date($13,'DD.MM.YYYY'))";
		}
		else
		{
			$this->ag_remind_date = null;
			$sql = "insert into action_gestion" .
					"(ag_id,ag_timestamp,ag_type,ag_title,f_id_dest,ag_ref, ag_dest, " .
					" ag_hour, ag_priority,ag_owner,ag_contact,ag_state,ag_remind_date) " .
					" values ($1,to_date($2,'DD.MM.YYYY'),$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13)";
		}
		if ($this->ag_dest == 0)
		{
			$this->ag_dest = null;
		}
		$this->db->exec_sql($sql, array($this->ag_id, /* 1 */
			$this->ag_timestamp, /* 2 */
			$this->dt_id, /* 3 */
			$this->ag_title, /* 4 */
			$exp->id, /* 5 */
			$ag_ref, /* 6 */
			$this->ag_dest, /* 7 */
			$this->ag_hour, /* 8 */
			$this->ag_priority, /* 9 */
			$_SESSION['g_user'], /* 10 */
			$contact->id, /* 11 */
			$this->ag_state, /* 12 */
			$this->ag_remind_date /* 13 */
				)
		);

		/* insert also the details */
		for ($i = 0; $i < MAX_ARTICLE; $i++)
		{
			$act = new Follow_Up_Detail($this->db);
			$act->from_array($_POST, $i);
			if ($act->f_id == 0)
				continue;
			$act->ag_id = $this->ag_id;
			$act->save();
		}

		/* Upload the documents */
		$doc = new Document($this->db);
		$doc->Upload($this->ag_id);
		if (trim($this->ag_comment) != '')
		{
			$this->db->exec_sql("insert into action_gestion_comment (ag_id,tech_user,agc_comment) values ($1,$2,$3)"
					, array($this->ag_id, $_SESSION['g_user'], $this->ag_comment));
		}
		$this->insert_operation();
		$this->insert_action();
	}

	/* ! myList($p_filter="")
	 * \brief Show list of action by default if sorted on date
	 * \param $p_base base url with ac...
	 * \param $p_filter filters on the document_type
	 * \param $p_search must a valid sql command ( ex 'and  ag_title like upper('%hjkh%'))
	 * \return string containing html code
	 */

	function myList($p_base, $p_filter = "", $p_search = "")
	{
		// for the sort
		$url = HtmlInput::get_to_string(array("remind_date","sag_ref","only_internal","state","qcode", "ag_dest_query", "query", "tdoc", "date_start", "date_end", "see_all", "all_action")) . '&' . $p_base;

		$table = new Sort_Table();
		$table->add('Date', $url, 'order by ag_timestamp asc', 'order by ag_timestamp desc', 'da', 'dd');
		$table->add('Date Limite', $url, 'order by ag_remind_date asc', 'order by ag_remind_date  desc', 'ra', 'rd');
		$table->add('Réf.', $url, 'order by ag_ref asc', 'order by ag_ref desc', 'ra', 'rd');
		$table->add('Groupe', $url, "order by coalesce((select p_name from profile where p_id=ag_dest),'Aucun groupe')", "order by coalesce((select p_name from profile where p_id=ag_dest),'Aucun groupe') desc", 'dea', 'ded');
		$table->add('Dest/Exp', $url, 'order by name asc', 'order by name desc', 'ea', 'ed');
		$table->add('Titre', $url, 'order by ag_title asc', 'order by ag_title desc', 'ta', 'td');

		$ord = (!isset($_GET['ord'])) ? "dd" : $_GET['ord'];
		$sort = $table->get_sql_order($ord);

		if (strlen(trim($p_filter)) != 0)
			$p_filter_doc = " dt_id in ( $p_filter )";
		else
			$p_filter_doc = " 1=1 ";

		$sql = "
             select ag_id,to_char(ag_timestamp,'DD.MM.YYYY') as my_date,
			 to_char(ag_remind_date,'DD.MM.YYYY') as my_remind,
			 f_id_dest,
             ag_title,dt_value,ag_ref, ag_priority,ag_state,
			coalesce((select p_name from profile where p_id=ag_dest),'Aucun groupe') as dest,
				(select ad_value from fiche_Detail where f_id=action_gestion.f_id_dest and ad_id=1) as name,
		     (select count(d_id) from document where document.ag_id=action_gestion.ag_id) as cnt_doc
             from action_gestion
             join document_type on (ag_type=dt_id)
             where $p_filter_doc $p_search $sort";
		$max_line = $this->db->count_sql($sql);
		$step = $_SESSION['g_pagesize'];
		$page = (isset($_GET['offset'])) ? $_GET['page'] : 1;
		$offset = (isset($_GET['offset'])) ? Database::escape_string($_GET['offset']) : 0;
		if ($step != -1)
			$limit = " LIMIT $step OFFSET $offset ";
		else
			$limit = '';
		$bar = jrn_navigation_bar($offset, $max_line, $step, $page);

		$Res = $this->db->exec_sql($sql . $limit);
		$a_row = Database::fetch_all($Res);

		$r = "";
		$r.=$bar;
		$r.='<table class="document">';
		$r.="<tr>";
		$r.='<th>' . $table->get_header(0) . '</th>';
		$r.='<th>' . $table->get_header(1) . '</th>';
		$r.='<th>' . $table->get_header(2) . '</th>';
		$r.='<th>' . $table->get_header(3) . '</th>';
		$r.='<th>' . $table->get_header(4) . '</th>';
		$r.='<th>' . $table->get_header(5) . '</th>';
		$r.=th(_("Nbre doc."));
		$r.='<th>' . _('type') . '</th>';
		$r.=th('Etat');
		$r.=th('Priorité');
		$r.="</tr>";


		// if there are no records return a message
		if (sizeof($a_row) == 0 or $a_row == false)
		{
			$r = '<div style="clear:both">';
			$r.='<hr>Aucun enregistrement trouvé';
			$r.="</div>";
			return $r;
		}
		$today = date('d.m.Y');
		$i = 0;
		//show the sub_action
		foreach ($a_row as $row)
		{
			$href = '<A class="document" HREF="do.php?'  . $p_base .HtmlInput::get_to_string(array("remind_date","sag_ref","only_internal","state","gDossier", "qcode", "ag_dest_query", "query", "tdoc", "date_start", "date_end", "see_all", "ac", "all_action"),"&") . '&sa=detail&ag_id=' . $row['ag_id'] . '">';
			$i++;
			$tr = ($i % 2 == 0) ? 'even' : 'odd';
			if ($row['ag_priority'] < 2)
				$tr = 'priority1';
			$st = '';
			if ($row['my_date'] == $today)
				$st = ' style="font-weight:bold; border:2px solid orange;"';
			$date_remind = format_date($row['my_remind'], 'DD.MM.YYYY', 'YYYYMMDD');
			$date_today = date('Ymd');
			if ($date_remind != "" && $date_remind == $date_today)
				$st = ' style="font-weight:bold;background:orange"';
			if ($date_remind != "" && $date_remind < $date_today)
				$st = ' style="font-weight:bold;background:#FF0000"';
			$r.="<tr class=\"$tr\" $st>";
			$r.="<td>" . $href . smaller_date($row['my_date']) . '</a>' . "</td>";
			$r.="<td>" . $href . smaller_date($row['my_remind']) . '</a>' . "</td>";
			$r.="<td>" . $href . $row['ag_ref'] . '</a>' . "</td>";
			$r.="<td>" . $href . h($row['dest']) . '</a>' . "</td>";

			// Expediteur
			$fexp = new Fiche($this->db);
			$fexp->id = $row['f_id_dest'];
			$qcode_dest = $fexp->strAttribut(ATTR_DEF_QUICKCODE);

			$qexp = ($qcode_dest == NOTFOUND) ? "Interne" : $qcode_dest;
			$jsexp = sprintf("javascript:showfiche('%s')", $qexp);
			if ($qexp != 'Interne')
			{
				$r.="<td>$href" . $qexp . " : " . $fexp->getName() . '</a></td>';
			}
			else
				$r.="<td>$href Interne </a></td>";

			$ref = "";


			$r.='<td>' . $href .
					h($row['ag_title']) . "</A></td>";
			if ($row['cnt_doc'] != 0)
				$r.="<td style=\"text-align:center\">" . $href . h($row['cnt_doc']) . '</a>' . "</td>";
			else
				$r.="<td ></td>";

			$r.="<td>" . $row['dt_value'] . "</td>";
			/*
			 * State
			 */
			switch ($row['ag_state'])
			{
				case 1:
					$state = 'Fermé';
					break;
				case 2:
					$state = "A suivre";
					break;
				case 3:
					$state = "A faire";
					break;
				case 4:
					$state = "Abandonné";
					break;
			}
			$r.=td($state);
			/*
			 * State
			 */
			switch ($row['ag_priority'])
			{
				case 1:
					$priority = 'Haute';
					break;
				case 2:
					$priority = "Moyenne";
					break;
				case 3:
					$priority = "Important";
					break;
			}
			$r.=td($priority);

			$r.="<td>" . $ref . "</td>";
			$r.="</tr>";
		}

		$r.="</table>";

		$r.=$bar;
		//$r.="</div>";
		return $r;
	}

	//----------------------------------------------------------------------
	/* !\brief Update the data into the database
	 *
	 * \return true on success otherwise false
	 */
	function Update()
	{
		// if ag_id == 0 nothing to do
		if ($this->ag_id == 0)
			return;
		// retrieve customer
		// f_id

		if (trim($this->qcode_dest) == "")
		{
			// internal document
			$this->f_id_dest = 0; // internal document
		}
		else
		{
			$tiers = new Fiche($this->db);
			if ($tiers->get_by_qcode($this->qcode_dest) == -1) // Error we cannot retrieve this qcode
				return false;
			else
				$this->f_id_dest = $tiers->id;
		}
		$contact = new Fiche($this->db);
		if ($contact->get_by_qcode($this->ag_contact) == -1)
			$contact->id = 0;


		if ($this->ag_dest == 0)
		{
			$this->ag_dest = null;
		}
		if ($this->ag_remind_date != null)
		{
			$this->db->exec_sql("update action_gestion set " .
					" ag_timestamp=to_date($1,'DD.MM.YYYY')," .
					" ag_title=$2," .
					" ag_type=$3, " .
					" f_id_dest=$4, " .
					"ag_state=$5," .
					" ag_hour = $7 ," .
					" ag_priority = $8 ," .
					" ag_dest = $9 , " .
					" ag_contact = $10, " .
					" ag_ref = $11, " .
					" ag_remind_date=to_date($12,'DD.MM.YYYY') " .
					" where ag_id = $6", array(
				$this->ag_timestamp, /* 1 */
				$this->ag_title, /* 2 */
				$this->dt_id, /* 3 */
				$this->f_id_dest, /* 4 */
				$this->ag_state, /* 5 */
				$this->ag_id, /* 6 */
				$this->ag_hour, /* 7 */
				$this->ag_priority, /* 8 */
				$this->ag_dest, /* 9 */
				$contact->id, /* 10*/
				$this->ag_ref, /* 11 */
				$this->ag_remind_date /* 12 */
			));
		}
		else
		{
			$this->db->exec_sql("update action_gestion set " .
					" ag_timestamp=to_date($1,'DD.MM.YYYY')," .
					" ag_title=$2," .
					" ag_type=$3, " .
					" f_id_dest=$4, " .
					"ag_state=$5," .
					" ag_hour = $7 ," .
					" ag_priority = $8 ," .
					" ag_dest = $9 , " .
					" ag_contact = $10, " .
					" ag_ref = $11, " .
					" ag_remind_date=null " .
					" where ag_id = $6", array(
				$this->ag_timestamp, /* 1 */
				$this->ag_title, /* 2 */
				$this->dt_id, /* 3 */
				$this->f_id_dest, /* 4 */
				$this->ag_state, /* 5 */
				$this->ag_id, /* 6 */
				$this->ag_hour, /* 7 */
				$this->ag_priority, /* 8 */
				$this->ag_dest, /* 9 */
				$contact->id, /* 10 */
				$this->ag_ref /* 11 */
			));
		}
		// Upload  documents
		$doc = new Document($this->db);
		$doc->Upload($this->ag_id);

		/* save action details */
		for ($i = 0; $i < MAX_ARTICLE; $i++)
		{
			$act = new Follow_Up_Detail($this->db);
			$act->from_array($_POST, $i);
			if ($act->f_id == 0 && $act->ad_id != 0)
				$act->delete();
			if ($act->f_id == 0)
				continue;
			$act->save();
		}
		if (trim($this->ag_comment) != '')
		{
			$this->db->exec_sql("insert into action_gestion_comment (ag_id,tech_user,agc_comment) values ($1,$2,$3)"
					, array($this->ag_id, $_SESSION['g_user'], $this->ag_comment));
		}
		$this->insert_operation();
		$this->insert_action();
		return true;
	}

	/* !\brief generate the document and add it to the action
	 * \param md_id is the id of the document_modele
	 * \param $p_array contains normally the $_POST
	 */

	function generate_document($md_id, $p_array)
	{
		$doc = new Document($this->db);
		$mod = new Document_Modele($this->db, $md_id);
		$mod->load();
		$doc->f_id = $this->f_id_dest;
		$doc->md_id = $md_id;
		$doc->ag_id = $this->ag_id;
		$doc->Generate($p_array);
	}

	/* !\brief put an array in the variable member, the indice
	 * is the member name
	 * \param $p_array to parse
	 * \return nothing
	 */

	function fromArray($p_array)
	{
		$this->ag_id = (isset($p_array['ag_id'])) ? $p_array['ag_id'] : "";
		$this->ag_ref = (isset($p_array['ag_ref'])) ? $p_array['ag_ref'] : "";
		$this->qcode_dest = (isset($p_array['qcode_dest'])) ? $p_array['qcode_dest'] : "";
		$this->f_id_dest = (isset($p_array['f_id_dest'])) ? $p_array['f_id_dest'] : 0;
		$this->ag_timestamp = (isset($p_array['ag_timestamp'])) ? $p_array['ag_timestamp'] : date('d.m.Y');
		$this->qcode_dest = (isset($p_array['qcode_dest'])) ? $p_array['qcode_dest'] : "";
		$this->dt_id = (isset($p_array['dt_id'])) ? $p_array['dt_id'] : "";
		$this->ag_state = (isset($p_array['ag_state'])) ? $p_array['ag_state'] : 2;
		$this->ag_ref = (isset($p_array['ag_ref'])) ? $p_array['ag_ref'] : "";
		$this->ag_title = (isset($p_array['ag_title'])) ? $p_array['ag_title'] : "";
		$this->ag_hour = (isset($p_array['ag_hour'])) ? $p_array['ag_hour'] : "";
		$this->ag_dest = (isset($p_array['ag_dest'])) ? $p_array['ag_dest'] : "";
		$this->ag_priority = (isset($p_array['ag_priority'])) ? $p_array['ag_priority'] : 2;
		$this->ag_contact = (isset($p_array['ag_contact'])) ? $p_array['ag_contact'] : "";
		$this->ag_comment = (isset($p_array['ag_comment'])) ? $p_array['ag_comment'] : "";
		$this->ag_remind_date = (isset($p_array['ag_remind_date'])) ? $p_array['ag_remind_date'] : null;
		$this->operation = (isset($p_array['operation'])) ? $p_array['operation'] : null;
		$this->op = (isset($p_array['op'])) ? $p_array['op'] : null;
		$this->action = (isset($p_array['action'])) ? $p_array['action'] : null;
	}

	/* !\brief remove the action
	 *
	 */

	function remove()
	{
		$this->get();
		// remove the key
		$sql = "delete from action_gestion where ag_id=$1";
		$this->db->exec_sql($sql, array($this->ag_id));

		/*  check the number of attached document */
		$doc = new Document($this->db);
		$aDoc = $doc->get_all($this->ag_id);
		if (!empty($aDoc))
		{
			// if there are documents
			for ($i = 0; $i < sizeof($aDoc); $i++)
			{
				$aDoc[$i]->remove();
			}
		}
	}

	/* !\brief return the last p_limit operation into an array
	 * \param $p_limit is the max of operation to return
	 * \return $p_array of Follow_Up object
	 */

	function get_last($p_limit)
	{
		$sql = "select coalesce(vw_name,'Interne') as vw_name,ag_id,ag_title,ag_ref, dt_value,to_char(ag_timestamp,'DD.MM.YYYY') as ag_timestamp_fmt,ag_timestamp " .
				" from action_gestion join document_type " .
				" on (ag_type=dt_id) left join vw_fiche_attr on (f_id=f_id_dest) where ag_state in (2,3) order by ag_timestamp desc limit $p_limit";
		$array = $this->db->get_array($sql);
		return $array;
	}

	function get_today()
	{
		$sql = "select coalesce(vw_name,'Interne') as vw_name,ag_id,ag_title,ag_ref, dt_value,to_char(ag_timestamp,'DD.MM.YYYY') as ag_timestamp_fmt,ag_timestamp " .
				" from action_gestion join document_type " .
				" on (ag_type=dt_id) left join vw_fiche_attr on (f_id=f_id_dest) where ag_state in (2,3)
				and to_char(ag_remind_date,'DDMMYYYY')=to_char(now(),'DDMMYYYY') ";
		$array = $this->db->get_array($sql);
		return $array;
	}
	/**
	 * insert a related operation
	 */
	function insert_operation()
	{
		if (trim($this->operation) == '')
			return;
		$array = explode(",", $this->operation);
		for ($i = 0; $i < count($array); $i++)
		{
			if ($this->db->get_value("select count(*) from action_gestion_operation
				where ag_id=$1 and jr_id=$2", array($this->ag_id, $array[$i])) == 0)
			{
				$this->db->exec_sql("insert into action_gestion_operation (ag_id,jr_id) values ($1,$2)", array($this->ag_id, $array[$i]));
			}
		}
	}

	/**
	 * remove a related operation
	 */
	function remove_operation()
	{
		if ($this->op == null)
			return;
		$op = $this->op;
		for ($i = 0; $i < count($op); $i++)
		{
			$this->db->exec_sql("delete from action_gestion_operation where ago_id=$1", array($op[$i]));
		}
	}

	/**
	 * Display only a search box for searching an action
	 * @param $cn database connx
	 */
	static function display_search($cn, $inner = false)
	{
		$a = (isset($_GET['query'])) ? $_GET['query'] : "";
		$qcode = (isset($_GET['qcode'])) ? $_GET['qcode'] : "";

		$supl_hidden = '';
		if (isset($_REQUEST['sc']))
			$supl_hidden.=HtmlInput::hidden('sc', $_REQUEST['sc']);
		if (isset($_REQUEST['f_id']))
		{
			$supl_hidden.=HtmlInput::hidden('f_id', $_REQUEST['f_id']);
			$f = new Fiche($cn, $_REQUEST['f_id']);
			$supl_hidden.=HtmlInput::hidden('qcode_dest', $f->get_quick_code());
		}
		if (isset($_REQUEST['sb']))
			$supl_hidden.=HtmlInput::hidden('sb', $_REQUEST['sb']);
		$supl_hidden.=HtmlInput::hidden('ac', $_REQUEST['ac']);

		/**
		 * Show the default button (add action, show search...)
		 */
		if (!$inner)
			require_once 'template/action_button.php';

		$w = new ICard();
		$w->name = 'qcode';
		$w->id = $w->generate_id($w->name);
		$w->value = $qcode;
		$w->extra = "all";
		$w->typecard = 'all';
		$w->jrn = 0;
		$w->table = 0;
		$list = $cn->make_list("select fd_id from fiche_def where frd_id in (4,8,9,14,15,16,25)");
		$w->extra = $list;


		/* type of documents */
		$type_doc = new ISelect('tdoc');
		$aTDoc = $cn->make_array('select dt_id,dt_value from document_type order by dt_value');
		$aTDoc[] = array('value' => '-1', 'label' => _('Tous les types'));
		$type_doc->value = $aTDoc;
		$type_doc->selected = (isset($_GET['tdoc'])) ? $_GET['tdoc'] : -1;

		/* State of documents */
		$type_state= new ISelect('state');
		$aState = $cn->make_array('select s_id,s_value from document_state order by s_value');
		$aState[] = array('value' => '-1', 'label' => _('Tous les Etats'));
		$type_state->value = $aState;
		$type_state->selected = (isset($_GET['state'])) ? $_GET['state'] : -1;


		// date
		$start = new IDate('date_start');
		$start->value = (isset($_GET['date_start'])) ? $_GET['date_start'] : "";
		$end = new IDate('date_end');
		$end->value = (isset($_GET['date_end'])) ? $_GET['date_end'] : "";

		$see_all = new ICheckBox('see_all');
		$see_all->selected = (isset($_GET['see_all'])) ? true : false;
		$my_action = new ICheckBox('all_action');
		$my_action->selected = (isset($_GET['all_action'])) ? true : false;
		$only_internal= new ICheckBox('only_internal');
		$only_internal->selected = (isset($_GET['only_internal'])) ? true : false;
		// select profile
		$aAg_dest = $cn->make_array("select  p_id as value, " .
				"p_name as label " .
				" from profile order by 2");
		$ag_dest = new ISelect();
		$ag_dest->name = "ag_dest_query";
		$ag_dest->value = $aAg_dest;
		$ag_dest->selected = (isset($_GET["ag_dest_query"])) ? $_GET["ag_dest_query"] : 0;
		$str_ag_dest = $ag_dest->input();
		$osag_ref=new IText("sag_ref");
		$osag_ref->value=(isset($_GET['sag_ref']))?$_GET['sag_ref']:"";
		$remind_date=new IDate('remind_date');
		$remind_date->value=(isset($_GET['remind_date']))?$_GET['remind_date']:"";
		// show the  action in
		require_once 'template/action_search.php';
	}

	static function ShowActionList($cn, $p_base)
	{

		Follow_Up::display_search($cn);

		$act = new Follow_Up($cn);
		/* ! \brief
		 *  \note The field 'recherche' is   about a part of the title or a ref. number
		 */
		$query = Follow_Up::create_query($cn);



		$r = $act->myList($p_base, "", $query);
		echo $r;
	}

	/**
	 * Get date from $_GET and create the sql stmt for the query
	 * @note the query is taken in $_REQUEST
	 * @see Follow_Up::ShowActionList
	 * @return string SQL condition
	 */
	static function create_query($cn, $p_array = null)
	{
		if ($p_array == null)
			$p_array = $_GET;

		extract($p_array);
		$query = "";
		/**
		 *@todo check why we need to use $_REQUEST['query']
		 * instead of $_GET['query']
		 */
		if (isset($_REQUEST['query']))
		{
			// if a query is request build the sql stmt
			$query = "and (ag_title ~* '" . sql_string($_REQUEST['query']) . "' " .
					"or ag_ref ='" . trim(sql_string($_REQUEST['query'])) .
					"' or ag_id in (select ag_id from action_gestion_comment where agc_comment ~* '" . trim(sql_string($_REQUEST['query'])) . "')" .
					")";
		}

		$str = "";
		if (isset($qcode))
		{
			// verify that qcode is not empty
			if (strlen(trim($qcode)) != 0)
			{

				$fiche = new Fiche($cn);
				$fiche->get_by_qcode($_REQUEST['qcode']);
				// if quick code not found then nothing
				if ($fiche->id == 0)
					$str = ' and false ';
				else
					$str = " and (f_id_dest= " . $fiche->id . " ) ";
			}
		}
		if (isset($tdoc) && $tdoc != -1)
		{
			$query .= ' and dt_id = ' . sql_string($tdoc);
		}
		if (isset($state) && $state!= -1)
		{
			$query .= ' and ag_state= ' . sql_string($state);
		}
		if (isset($sag_ref) && trim($sag_ref) != "")
		{
			$query .= ' and ag_ref= \'' . sql_string($sag_ref)."'";
		}
		if (!isset($_GET['see_all']))
			$query .= ' and ag_state in (2,3) ';
		if (isset($_GET['only_internal']))
			$query .= ' and f_id_dest=0 ';
		if ( isset($all_action))
		{
			$query .=" and (ag_owner='" . $_SESSION['g_user'] . "' or ".self::sql_security_filter($cn, "R")." or ag_dest=-1 )";
		}
		if (isset($date_start) && isDate($date_start) != null)
		{
			$query.=" and ag_timestamp >= to_date('$date_start','DD.MM.YYYY')";
		}
		if (isset($date_end) && isDate($date_end) != null)
		{
			$query.=" and ag_timestamp <= to_date('$date_end','DD.MM.YYYY')";
		}
		if (isset($ag_dest_query)&& ! isset($all_action) )
		{
				$query.= " and ag_dest = " . sql_string($ag_dest_query);
		}
		if (isNumber($ag_id) == 1 && $ag_id != 0)
		{
			$query = " and ag_id= " . sql_string($ag_id);
		}
		if ( isset($remind_date) && $remind_date != "" && isDate($remind_date)==$remind_date)
		{
			$query = " and to_char(ag_remind_date,'DD.MM.YYYY')= '".sql_string($remind_date)."'";
		}
		return $query . $str;
	}

	/**
	 * Show the result of a search in an inner windows, the result is limited to 25
	 * @param type $cn database connx
	 * @param type $p_sql the query
	 */
	static function short_list($cn, $p_sql)
	{
		$sql = "
             select ag_id,to_char(ag_timestamp,'DD.MM.YY') as my_date,
			 f_id_dest,
             substr(ag_title,1,40) as sub_ag_title,dt_value,ag_ref, ag_priority,ag_state,
			coalesce((select p_name from profile where p_id=ag_dest),'Aucun groupe') as dest,
				(select ad_value from fiche_Detail where f_id=action_gestion.f_id_dest and ad_id=1) as name
             from action_gestion
             join document_type on (ag_type=dt_id)
             where $p_sql";
		$max_line = $cn->count_sql($sql);

		$limit = ($max_line > 25) ? 25 : $max_line;
		$Res = $cn->exec_sql($sql . "limit " . $limit);
		$a_row = Database::fetch_all($Res);
		require_once 'template/action_search_result.php';
	}

	/**
	 * Insert a related action into the table action_gestion_related
	 */
	function insert_action()
	{
		if (trim($this->action) == '')
			return;
		$array = explode(",", $this->action);
		for ($i = 0; $i < count($array); $i++)
		{
			if ($this->db->get_value("select count(*) from action_gestion_related
				where (aga_least=$1 and aga_greatest=$2) or (aga_greatest=$1 and aga_least=$2)", array($array[$i], $this->ag_id)) == 0 && $this->ag_id != $array[$i])
			{
				$this->db->exec_sql("insert into action_gestion_related(aga_least,aga_greatest) values ($1,$2)", array($this->ag_id, $array[$i]));
			}
		}
	}

	/**
	 * export to CSV the query the p_array has
	 * @param array $p_array
	 */
	function export_csv($p_array)
	{
		extract($p_array);


		$p_search=self::create_query($this->db, $p_array);
		$sql = "
             select ag_id,
			to_char(ag_timestamp,'DD.MM.YYYY') as my_date,
			 to_char(ag_remind_date,'DD.MM.YYYY') as my_remind,
				(select ad_value from fiche_Detail where f_id=action_gestion.f_id_dest and ad_id=1) as name,
             ag_title,
			dt_value,
			ag_ref,
			ag_priority,
			ag_state,
			coalesce((select p_name from profile where p_id=ag_dest),'Aucun groupe') as dest
             from action_gestion
             join document_type on (ag_type=dt_id)
             where  true  $p_search order by ag_timestamp,ag_id";
		$ret=$this->db->exec_sql($sql);

		if ( Database::num_row($ret)==0)exit();
		$this->db->query_to_csv($ret,array(
				array("title"=>"doc id","type"=>"string"),
				array("title"=>"date","type"=>"date"),
				array("title"=>"rappel","type"=>"date"),
				array("title"=>"nom","type"=>"string"),
				array("title"=>"titre","type"=>"string"),
				array("title"=>"type document","type"=>"string"),
				array("title"=>"ref","type"=>"string"),
				array("title"=>"priorite","type"=>"string"),
				array("title"=>"etat","type"=>"string"),
				array("title"=>"profil","type"=>"string")
				)
			);
	}

}
