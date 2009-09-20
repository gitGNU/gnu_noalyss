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

class Action 
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
   *
   * \note  update the reference number or the document type is not allowed
   *       
   *
   * \return string containing the html code
   */
  function Display($p_view,$p_gen,$retour="") 
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
		  exit('class_action'.__LINE__.'Action::Display error unknown parameter'.$p_view);
		  
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
	  $doc_type->value=$this->db->make_array("select dt_id,dt_value from document_type where dt_id in (".ACTION.")");
	  $doc_type->selected=$this->dt_id;
	  $doc_type->readonly=false;
	  $str_doc_type=$doc_type->input();
	  echo_debug('class_action',__LINE__,var_export($doc_type,true));
	}
      else {
	// Doc Type
	$doc_type=new IHidden();
	$doc_type->name="dt_id";
	$doc_type->value=$this->dt_id;
	$str_doc_type=$doc_type->input().$this->db->get_value("select dt_value from document_type where dt_id=".$this->dt_id);
      }

      // Description
      $desc=new ITextArea();
      $desc->width=70;
      $desc->heigh=5;
      $desc->name="ag_comment";
      $desc->readonly=$readonly;
      $desc->value=$this->ag_comment;

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
      $ag_ref->value=FormatString($this->ag_ref);
      $client_label=new ISpan();

      // f_id_dest sender
      if ( $this->qcode_dest != '- ERROR -' && strlen(trim($this->qcode_dest)) != 0)
	{
	  $tiers=new fiche($this->db);
	  $tiers->get_by_qcode($this->qcode_dest);
	  $qcode_dest_label=$tiers->strAttribut(1);
	} else {
	  $qcode_dest_label=($this->f_id_dest==0 || trim($this->qcode_dest)=="")?'Interne ':'Error';
	}

      $h_ag_id=new IHidden();
      // if concerns another action : show the link otherwise nothing
      $lag_ref_ag_id=" 00 / 00 ";
      
      if ( $this->ag_ref_ag_id != 0 )
	{
	  $lag_ref_ag_id='<a class="mtitle" href="?p_action='.$_REQUEST['p_action'].'&sa=detail&ag_id='.
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
      $w->extra='[sql] and frd_id in (14,25,8,9,16)';
      $w->extra2='Recherche';
      $sp=new ISpan();
      $sp->name='qcode_dest_label';
      $sp->value=$qcode_dest_label;

      // contact
      $ag_contact=new ICard();
      $ag_contact->readonly=$readonly;
      $ag_contact->jrn=0;
      $ag_contact->name='ag_contact';
      $ag_contact->value=$this->ag_contact;
      $ag_contact->label="";
      $ag_contact->extra='[sql] and frd_id = 16'; /* frd = 16 for contact */
      $ag_contact->extra2='Recherche';
      $spcontact=new ISpan();
      $spcontact->name='ag_contact_label';
      $spcontact->value='';
      $fiche_contact=new Fiche($this->db);
      $fiche_contact->get_by_qcode($this->ag_contact);
      if ( $fiche_contact->id != 0 ) {
	$spcontact->value=$fiche_contact->strAttribut(ATTR_DEF_NAME);
      }


      $h_agrefid=new IHidden();
      $str_ag_ref="<b>".(($this->ag_ref != "")?$this->ag_ref:" Nouveau ")."</b>";
      // Preparing the return string
      $r="";
      $r.=JS_SEARCH_CARD;
      /* for new files */
      $upload=new IFile();
      $upload->name="file_upload[]";
      $upload->value="";
      $aAttachedFile=$this->db->get_array('select d_id,d_filename,d_mimetype,'.
					  '\'show_document.php?PHPSESSID='.$_REQUEST['PHPSESSID'].'&'.Dossier::get().'&d_id=\'||d_id as link'.
					  ' from document where ag_id=$1',
					 array($this->ag_id));
      /* create the select for document */
      $aDocMod=new ISelect();
      $aDocMod->name='doc_mod';
      $aDocMod->value=$this->db->make_array('select md_id,dt_value||\' : \'||md_name as md_name'.
					    ' from document_modele join document_type on (md_type=dt_id)'.
					    ' order by md_name');
      $str_select_doc=$aDocMod->input();
      $ag_id=$this->ag_id;

      /* create aArticle for the detail section */
      for ($i=0;$i<10;$i++) {
	$aArticle[$i]['fid']='fid';
	$aArticle[$i]['desc']='desc';
	$aArticle[$i]['pu']='pu';
	$aArticle[$i]['quant']='quant';
	$aArticle[$i]['htva']='htva';
	$aArticle[$i]['atva']='atva';
	$aArticle[$i]['ctva']='ctva';
	$aArticle[$i]['totaltvac']='totaltvac';
      }
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
	$r.=$this->myList(ACTION," and ag_ref_ag_id=".$this->ag_id);
      return $r;
 
    }
  //----------------------------------------------------------------------
  /*!\brief This function shows the detail of an action thanks the ag_id
   */
  function get()
    {
      echo_debug('class_action',__LINE__,'Action::Get() ');
      $sql="select ag_id, ag_comment,to_char (ag_timestamp,'DD-MM-YYYY') as ag_timestamp,".
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
      //
      echo_debug('class_action',__LINE__,' Document id = '.$this->d_id);
      // if there is no document set 0 to d_id
      if ( $this->d_id == "" ) 
	$this->d_id=0;
      // if there is a document fill the object
      if ($this->d_id != 0 )
	{
	  $this->state=$row['0']['ag_state'];
	  $this->ag_state=$row[0]['ag_state'];
	}
      echo_debug('class_action',__LINE__,' After test Document id = '.$this->d_id);
      $this->dt_id=$this->ag_type;
      $aexp=new fiche($this->db,$this->f_id_dest);
      $this->qcode_dest=$aexp->strAttribut(ATTR_DEF_QUICKCODE);

      echo_debug('class_action',__LINE__,'Detail end ()  :'.var_export($_POST,true));
      echo_debug('class_action',__LINE__,'Detail $this  :'.var_export($this,true));

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
      echo_debug('class_action',__LINE__,'save()  :'.var_export($_POST,true));
      echo_debug('class_action',__LINE__,' save()  $this  :'.var_export($this,true));

      // Get The sequence id, 
      $seq_name="seq_doc_type_".$this->dt_id;
      $str_file="";
      $add_file='';

      // f_id exp
      $exp=new fiche($this->db);
      $exp->get_by_qcode($this->qcode_dest);
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
	"(ag_id,ag_timestamp,ag_type,ag_title,f_id_dest,ag_comment,ag_ref,ag_ref_ag_id, ag_dest, ag_hour, ag_priority,ag_cal,ag_owner,ag_contact) ".
	" values ($1,to_date($2,'DD-MM-YYYY'),$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14)";
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
				     $this->ag_contact
				     )
			  );
      /* Upload the documents */
      $doc=new Document($this->db);
      $doc->Upload($this->ag_id);
    }
/*! myList($p_filter="")
 * \brief Show list of action by default if sorted on date
 * \param  $p_filter filters on the document_type
 * \param $p_search must a valid sql command ( ex 'and  ag_title like upper('%hjkh%'))
 * 
 * 
 * \return string containing html code
 */
  function myList($p_filter="",$p_search="")
    {
      $str_dossier=dossier::get();
      // for the sort
      $sort="";
      $image_asc='<IMAGE SRC="image/down.png" border="0" >';
      $image_desc='<IMAGE SRC="image/up.png" border="0" >';
      $image_sel_desc='<IMAGE SRC="image/select1.png" border="0" >';
      $image_sel_asc='<IMAGE SRC="image/select2.png" border="0" >';

      $url=CleanUrl();
      $url.=$str_dossier.'&p_action='.$_REQUEST['p_action'];
      $sort_date='<th><A class="mtitle" href="?'.$url.'&s=date_a">'.$image_asc.'</A>'.
	'Date'.
	'<A class="mtitle"  href="?'.$url.'&s=date_d&'.$str_dossier.'">'.$image_desc.'</A></th>';
      $sort_exp='<th><A  class="mtitle"  href="?'.$url.'&s=exp&'.$str_dossier.'">'.$image_asc.'</A>'.
	'Expéditeur'.
	'<A  class="mtitle"  href="?'.$url.'&s=exp_d&'.$str_dossier.'">'.$image_desc.'</A></th>';
      $sort_titre='<th><A  class="mtitle"  href="?'.$url.'&s=ti&'.$str_dossier.'">'.$image_asc.'</A>'.
	'Titre'.
	'<A  class="mtitle"  href="?'.$url.'&s=ti_d&'.$str_dossier.'">'.$image_desc.'</A></th>';
      $sort_concerne='<th><A  class="mtitle"  href="?'.$url.'&s=conc&'.$str_dossier.'">'.$image_asc.'</A>'.
	'Concerne'.
	'<A class="mtitle"  href="?'.$url.'&s=conc_d&'.$str_dossier.'">'.$image_desc.'</A></th>';
      $sort_reference='<th><A class="mtitle"  href="?'.$url.'&s=ref&'.$str_dossier.'">'.$image_asc.'</A>'.
	'Référence'.
	'<A  class="mtitle"  href="?'.$url.'&s=ref_d&'.$str_dossier.'">'.$image_desc.'</A></th>';

      if ( isset($_GET['s'])){
	  switch ($_GET['s']) {
	  case "date_a":
	    $sort=" ag_timestamp asc";
	    $sort_date='<th>'.$image_sel_asc.'</A>'.
	      'Date'.
	      '<A  class="mtitle"  href="?'.$url.'&s=date_d">'.$image_desc.'</A></th>';
	    break;

	  case "date_d":
	    $sort=" ag_timestamp desc";
	    $sort_date='<th><A class="mtitle"  href="?'.$url.'&s=date_a">'.$image_asc.'</A>'.
	      'Date'.
	      $image_sel_desc.'</th>';
	    break;

	  case "exp":
	    $sort_exp='<th>'.$image_sel_asc.'</A>'.
	      'Expéditeur'.
	      '<A  class="mtitle"  href="?'.$url.'&s=exp_d">'.$image_asc.'</A></th>';
	    $sort=" f_id_dest asc";
	    break;

	  case "exp_d":
	    $sort_exp='<th><A  class="mtitle"  href="?'.$url.'&s=exp">'.$image_asc.'</A>'.
	      'Expéditeur'.
	      $image_sel_desc.'</th>';

	    $sort=" f_id_dest desc";
	    break;

	  case "ti":
	    $sort_titre='<th>'.$image_sel_asc.
	      'Titre'.
	      '<A class="mtitle"  href="?'.$url.'&s=ti_d">'.$image_desc.'</A></th>';

	    $sort=" ag_title  asc";
	    break;
	  case "ti_d":
	    $sort_titre='<th><A  class="mtitle" href="?'.$url.'&s=ti">'.$image_asc.'</A>'.
	      'Titre'.
	      $image_sel_desc.'</th>';
	    
	    $sort=" ag_title desc";
	    break;
	    
	  case "conc":
	    $sort_concerne='<th>'.$image_sel_asc.
	      'Concerne'.
	      '<A  class="mtitle" href="?'.$url.'&s=conc_d">'.$image_desc.'</A></th>';

	    $sort=" ag_ref_ag_id asc";
	    break;
	  case "conc_d":
	    $sort_concerne='<th><A  class="mtitle"  href="?'.$url.'&s=conc">'.$image_asc.'</A>'.
	      'Concerne'.
	      $image_sel_desc.'</th>';

	    $sort=" ag_ref_ag_id desc";
	    break;

	  case "ref":
	    $sort_reference='<th>'.$image_sel_asc.
	      'Référence'.
	      '<A  class="mtitle"  href="?'.$url.'&s=ref_d">'.$image_desc.'</A></th>';

	    $sort=" ag_ref ";
	    break;

	  case "ref_d":
	    $sort_reference='<th><A class="mtitle"  href="?'.$url.'&s=ref">'.$image_asc.'</A>'.
	      'Référence'.
	      $image_sel_desc.'</th>';

	    $sort=" ag_ref desc";
	    break;


	  }
	
      }  else {
	$sort=" ag_timestamp asc";
	$sort_date='<th>'.$image_sel_asc.'</A>'.
	  'Date'.
	  '<A  class="mtitle"  href="?'.$url.'&s=date_d">'.$image_desc.'</A></th>';
      }

      $sort=" order by ".$sort;

      if ( strlen(trim($p_filter)) != 0 ) 
	$p_filter_doc=" dt_id in ( $p_filter )";
      else 
	$p_filter_doc=" 1=1 ";

      $sql="
   select ag_id,to_char(ag_timestamp,'DD-MM-YYYY') as my_date,ag_ref_ag_id,f_id_dest".
	",ag_title,md_type,dt_value,ag_ref, ag_priority
   from action_gestion 
      left outer join document_modele on (ag_type=md_type) 
      join document_type on (ag_type=dt_id)
   where $p_filter_doc $p_search $sort";
      $max_line=$this->db->count_sql($sql);
      $step=$_SESSION['g_pagesize'];
      $page=(isset($_GET['offset']))?$_GET['page']:1;
      $offset=(isset($_GET['offset']))?Database::escape_string($_GET['offset']):0;
      $limit=" LIMIT $step OFFSET $offset ";  
      $bar=jrn_navigation_bar($offset,$max_line,$step,$page);

      $Res=$this->db->exec_sql($sql.$limit);
      $a_row=Database::fetch_all($Res);

      $r="";
      $r.=$bar;
      $r.='<table class="document">';
      $r.="<tr>";
      $r.=$sort_date;
      $r.=$sort_exp;
      $r.=$sort_titre;
      $r.='<th>type</th>';
      $r.=$sort_reference;
      $r.=$sort_concerne;
      $r.="</tr>";

      
      // if there are no records return a message
      if ( sizeof ($a_row) == 0 or $a_row == false ) 
	{
	  $r='<div class="u_redcontent">';
	  $r.='<hr>Aucun enregistrement trouvé';
	  $r.="</div>";
	  return $r;

	}
      $r.=JS_SEARCH_CARD;
      $i=0;
      foreach ($a_row as $row )
	{
	  $href='<A class="document" HREF="commercial.php?p_action=suivi_courrier&sa=detail&ag_id='.$row['ag_id'].'&'.$str_dossier.'">';
	  $i++;
	  $tr=($i%2==0)?'even':'odd';
	  if ($row['ag_priority'] < 2) $tr='priority1';	
	  $r.="<tr class=\"$tr\">";
	  $r.="<td>".$href.$row['my_date'].'</a>'."</td>";

	  // Expediteur
	  $fexp=new fiche($this->db);
	  $fexp->id=$row['f_id_dest'];
	  $qcode_dest=$fexp->strAttribut(ATTR_DEF_QUICKCODE);

	  $qexp=($qcode_dest=="- ERROR -")?"Interne":$qcode_dest;
	  $jsexp=sprintf("javascript:showfiche('%s','%s')",
		      $_REQUEST['PHPSESSID'],$qexp);
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
		      $ref.='<A  href="commercial.php?p_action=suivi_courrier&query='.$line['ag_ref'].'&'.$str_dossier.'">'.
			$line['ag_ref']."<A>";
		    }
		}
	    }

	  $r.='<td>'.$href.
	    $row['ag_title']."</A></td>";
	  $r.="<td>".$row['dt_value']."</td>";
	  $r.="<td>".$row['ag_ref']."</td>";
	  $r.="<td>".$ref."</td>";
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
      echo_debug('class_action',__LINE__,'Update() $_POST()  :'.var_export($_POST,true));
      echo_debug('class_action',__LINE__,'Update()  $this  :'.var_export($this,true));

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
	  $tiers=new fiche($this->db);
	  if ( $tiers->get_by_qcode($this->qcode_dest) == -1 ) // Error we cannot retrieve this qcode
	    return false; 
	  else
	    $this->f_id_dest=$tiers->id;

	}
      $ref=$this->dt_id.'/'.$this->ag_id;
      if ( $this->ag_cal == 'on') $ag_cal='C'; else $ag_cal='I';
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
			  " ag_contact = $13 ".
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
				  $this->ag_contact   /* 13 */
				  ));
      echo_debug('class_action',__LINE__,$_FILES);
      // Upload  documents
      $doc=new Document($this->db);
      $doc->Upload($this->ag_id); 
      return true;
    }

  /*!\brief generate the document and add it to the action
   * \param md_id is the id of the document_modele
   */
  function generate_document($md_id) {
    $doc=new Document($this->db);
    $mod=new Document_Modele($this->db,$md_id);
    $mod->load();
    $doc->f_id=$this->f_id_dest;
    $doc->md_id=$md_id;
    $doc->ag_id=$this->ag_id;
    $doc->Generate();
  }
   /*!\brief put an array in the variable member, the indice
    * is the member name
   *\param $p_array to parse
   *\return nothing
   */
  function fromArray($p_array) {
      $this->ag_id=(isset($p_array['ag_id']))?$p_array['ag_id']:"";
      $this->qcode_dest=(isset($p_array['qcode_dest']))?$p_array['qcode_dest']:"";
      $this->f_id_dest=(isset($p_array['f_id_dest']))?$p_array['f_id_dest']:0;
      $this->ag_ref_ag_id=(isset($p_array['ag_ref_ag_id']))?$p_array['ag_ref_ag_id']:0;
      $this->ag_timestamp=(isset($p_array['ag_timestamp']))?$p_array['ag_timestamp']:"";
      $this->qcode_dest=(isset($p_array['qcode_dest']))?$p_array['qcode_dest']:"";
      $this->dt_id=(isset($p_array['dt_id']))?$p_array['dt_id']:"";
      $this->ag_state=(isset($p_array['ag_state']))?$p_array['ag_state']:2;
      $this->ag_ref=(isset($p_array['ag_ref']))?$p_array['ag_ref']:"";
      $this->ag_title=(isset($p_array['ag_title']))?$p_array['ag_title']:"";
      $this->ag_hour=(isset($p_array['ag_hour']))?$p_array['ag_hour']:"";
      $this->ag_dest=(isset($p_array['ag_dest']))?$p_array['ag_dest']:"";
      $this->ag_priority=(isset($p_array['ag_priority']))?$p_array['ag_priority']:2;
      $this->ag_cal=(isset($p_array['ag_cal']))?$p_array['ag_cal']:"";
      $this->ag_contact=(isset($p_array['ag_contact']))?$p_array['ag_contact']:"";
  }
  /*!\brief remove the action 
   *
   */
  function remove()
  {
    $this->get();
    // remove the key
    $sql=sprintf("delete from action_gestion where ag_id=%d",$this->ag_id);
    $this->db->exec_sql($sql);
      
    // remove the ref of the depending action
    $sql=sprintf("update action_gestion set ag_ref_ag_id=0 where ag_ref_ag_id=%d",
		 $this->ag_id);
    $this->db->exec_sql($sql);
    /*  check the number of attached document */
    $doc=new Document($this->db);
    $aDoc=$doc->get_all($this->ag_id);
    if ( ! empty ($aDoc)) {
      // if there are documents
      for ($i=0;$i <sizeof($aDoc);$i++) {
	$aDoc[$i]->remove();
      }
    }
  }
 
}
