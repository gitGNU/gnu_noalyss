<?
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

require_once("class_fiche.php");
require_once("class_document.php");
require_once("class_document_type.php");
require_once("user_common.php");
/*!\file 
 * \brief class_action for manipulating actions
 * action can be :
 * - an invoice
 * - a meeting
 * - an order
 * - a letter
 *
 * The table document_type are the possible actions
 */
/*!
 * \brief class_action for manipulating actions
 * action can be :
 * - a meeting
 * - an order
 * - a letter
 *
 * The table document_type are the possible actions
 */

class action 
{
 /*! \enum $db  database connexion   
  * \enum $ag_comment description (ag_gestion.ag_comment)
  * \enum $ag_timestamp document date (ag_gestion.ag_timestamp)
  * \enum $dt_id type of the document (document_type.dt_id)
  * \enum $d_state stage of the document (printed, send to client...)
  * \enum $d_number number of the document
  * \enum $d_filename filename's document
  * \enum $d_mimetype document's filename
  * \enum $ag_title title document
  * \enum $f_id fiche id
  * \enum $ag_ref_ag_id concern previous action
  * \todo replace attribut from class document  document by an object document 
  */
  var $db;
  var $ag_comment;
  var $ag_timestamp;
  var $dt_id;
  var $d_state;
  var $d_number;
  var $d_filename;
  var $d_mimetype;
  var $ag_title;
  var $f_id;
  var $ag_ref_ag_id;
/*!  constructor
 * \brief constructor
 * \param p_cn database connection
 */
  function action ($p_cn)
    {
      $this->db=$p_cn;
      $this->f_id=0;

    }
  //----------------------------------------------------------------------
  /*!  Display
   * \brief Display the object, the tags for the FORM
   *        are in the caller. It will be used for adding and updating 
   *        action 
   *\note  If  ag_id is not equal to zero then it is an update otherwise
   *        it is a new document
   *
   * \param $p_view if set to true the form will be in readonly mode 
   * \\param $p_gen true we show the tag for generating a doc
   * \todo change this parameter by UPD : ag_ref and document type are read only,
   *  the other are writable, NEW: everything is writable, READ readonly mode
   * \note  update the reference number or the document type is not allowed
   *       
   *
   * \return string containing the html code
   */
  function Display($p_view,$p_gen) 
    {
      echo_debug('class_action',__LINE__,'Display()  :'.var_export($_POST,true));
      echo_debug('class_action',__LINE__,'Display $this  :'.var_export($this,true));


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
	  exit('class_action'.__LINE__.'action::Display error unknown parameter'.$p_view);

	}
      // Compute the widget
      // Date 
      $date=new widget("text");
      $date->readonly=$readonly;
      $date->name="ag_timestamp";
      $date->value=$this->ag_timestamp;
      
      
      // for insert mode only
      if ( $upd == false )
	{
	  // Doc Type
	  $doc_type=new widget("select");
	  $doc_type->name="dt_id";
	  $doc_type->value=make_array($this->db,"select dt_id,dt_value from document_type where dt_id in (".ACTION.")");
	  $doc_type->selected=$this->dt_id;
	  $doc_type->readonly=false;
	  $str_doc_type=$doc_type->IOValue();
	  echo_debug('class_action',__LINE__,var_export($doc_type,true));
	}
      else {
	// Doc Type
	$doc_type=new widget("hidden");
	$doc_type->name="dt_id";
	$doc_type->value=$this->dt_id;
	$str_doc_type=$doc_type->IOValue()." ".getDbValue($this->db,"select dt_value from document_type where dt_id=".$this->dt_id);
      }

      // Description
      $desc=new widget('RICHTEXT');
      $desc->width=500;
      $desc->heigh=250;
      $desc->name="ag_comment";
      $desc->readonly=$readonly;
      $desc->value=urldecode($this->ag_comment);

      // state
      // Retrieve the value
      if ( $p_view != 'READ' )
	{
	  $a=make_array($this->db,"select s_id,s_value from document_state ");
	  $state=new widget("select");
	  $state->readonly=$readonly;
	  $state->name="d_state";
	  $state->value=$a;
	  $state->selected=$this->d_state;
	  $str_state=$state->IOValue();
	} else {
	  $str_state=getDbValue($this->db,"select s_value from document_state where s_id=".$this->d_state);
	  $g=new widget("hidden");
	  $str_state.=$g->IOValue('d_state',$this->d_state);
	}
      // Retrieve the value if there is an attached doc
      $doc_ref="";
      // Document id

      $h2=new widget("hidden");
      $h2->name="d_id";
      $h2->value=$this->d_id;
      
      if ( $this->d_id != 0 && $this->d_id != "" )
	{
	  $h2->readonly=($p_view=='NEW')?false:true;
	  $doc=new Document($this->db,$this->d_id);
	  $d_id=new widget("hidden");
	  $doc_ref="<p> Document ".$doc->a_ref().'</p>';
	  $doc_ref.=$h2->IOValue().$d_id->IOValue('d_id',$this->d_id);
	  
	}


      // title
      $title=new widget("text");
      $title->readonly=$readonly;
      $title->name="ag_title";
      $title->value=FormatString($this->ag_title);

      // ag_ref
      // Always false for update
      $ag_ref=new widget("text");
      $ag_ref->readonly=true;
      $ag_ref->name="ag_ref";
      $ag_ref->value=FormatString($this->ag_ref);
      $client_label=new widget("span");

      // f_id
      if ( $this->qcode != '- ERROR -' && trim($this->qcode) != "")
	{
	  $tiers=new fiche($this->db);
	  $tiers->GetByQCode($this->qcode);
	  $qcode_label=$tiers->strAttribut(1);
	} else {
	  echo "f_id $this->f_id";
	  $qcode_label=($this->f_id==0 || trim($this->qcode)=="")?'Interne ':'Error';
	}
      $h_ag_id=new widget("hidden");
      // if concerns another action : show the link otherwise nothing
      $lag_ref_ag_id="";
      
      if ( $this->ag_ref_ag_id != 0 )
	{
	  $lag_ref_ag_id='<a class="mtitle" href="commercial.php?p_action=suivi_courrier&sa=detail&ag_id='.
	    $this->ag_ref_ag_id.'">'.
	    $this->GetAgRef("ag_ref_ag_id")."</A>";
	}  

      $w=new widget('js_search_only');
      $w->readonly=$readonly;
      $w->name='qcode';
      $w->value=($this->f_id != 0)?$this->qcode:"";
      $w->label="";
      $w->extra='4,8,9,14,16';
      $sp= new widget("span");
      $h_agrefid=new widget('hidden');

      // Preparing the return string
      $r="";
      $r.=JS_SEARCH_CARD;
      $r.= "<p>Date : ".$date->IOValue()." Reference  ". $this->ag_ref."</p>";
      $r.="<p>Concerne :".$lag_ref_ag_id."</p>";
      $r.= '<p>Type d\' action';
      echo_debug('class_action',__LINE__,"str_doc_type $str_doc_type");
      $r.= $str_doc_type."</p>";

      // state
      $r.="<p>Etat :".$str_state;


      $r.= "</p><p> ";
      $r.=$w->IOValue();
      $r.=$sp->IOValue('qcode_label',$qcode_label)."</TD></TR>";

      $r.="</p>";
      echo_debug('class_action',__LINE__,' ag_id is '.$this->ag_id);

      $r.= "<p> Titre : ".$title->IOValue().' Ref :'.$ag_ref->IOValue();
      $r.= $doc_ref;
      $r.= "<p>Description :".$desc->IOValue()."</p>";

      //hidden
      $r.="<p>";
      $r.=$h2->IOValue();
      $r.=$h_agrefid->IOValue("ag_ref_ag_id",$this->ag_ref_ag_id); 
      $r.=$h_ag_id->IOValue('ag_id',$this->ag_id);
      $r.="</p>";

      // show the list of the concern operation
      if ( CountSql($this->db,'select * from action_gestion where ag_ref_ag_id!=0 and ag_ref_ag_id='.$this->ag_id.' limit 5') >0 )
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
	" f_id,ag_title,ag_comment,ag_ref,d_id,ag_type,d_state,  ".
	" ag_ref_ag_id ".
	" from action_gestion left join document using (ag_id) where ag_id=".$this->ag_id;
      $r=ExecSql($this->db,$sql);
      $row=pg_fetch_all($r);
      if ( $row==false) return;
      $this->ag_comment=$row[0]['ag_comment'];
      $this->ag_timestamp=$row[0]['ag_timestamp'];
      $this->f_id=$row[0]['f_id'];
      $this->ag_title=$row[0]['ag_title'];
      $this->ag_type=$row[0]['ag_type'];
      $this->ag_ref=$row[0]['ag_ref'];
      $this->ag_ref_ag_id=$row[0]['ag_ref_ag_id'];
      $this->d_id=$row[0]['d_id'];
      //
      echo_debug('class_action',__LINE__,' Document id = '.$this->d_id);
      // if there is no document set 0 to d_id
      if ( $this->d_id == "" ) 
	$this->d_id=0;
      // if there is a document fill the object
      if ($this->d_id != 0 )
	{
	  $this->state=$row['0']['d_state'];
	  $this->d_state=$row[0]['d_state'];
	}
      echo_debug('class_action',__LINE__,' After test Document id = '.$this->d_id);
      $this->dt_id=$this->ag_type;
      $a=new fiche($this->db,$this->f_id);
      $this->qcode=$a->strAttribut(ATTR_DEF_QUICKCODE);
      echo_debug('class_action',__LINE__,'Detail end ()  :'.var_export($_POST,true));
      echo_debug('class_action',__LINE__,'Detail $this  :'.var_export($this,true));

    }
/*!  Confirm
 * \brief Display the encoded data and ask a confirmation
 *        this correspond to the stage 1, before the generation
 *        or the upload of document
 * 
 * 
 * \return string containing the form
 */
  function Confirm()
    {
      echo_debug('class_action',__LINE__,'confirm()  :'.var_export($_POST,true));
      echo_debug('class_action',__LINE__,'confirm $this  :'.var_export($this,true));
      /*! \todo add the hour */
      if ( isDate($this->ag_timestamp) == null )
	{
	  // if the date is invalid, default date is today
	  $this->ag_timestamp=date("d.m.Y");
	}
      // Compute the widget
      // Date 
      $date=new widget("text");
      $date->readonly=true;
      $date->name="ag_timestamp";
      $date->value=$this->ag_timestamp;
      // Doc Type
      $doc_type=new widget("hidden");
      $doc_type->name="dt_id";
      $doc_type->value=$this->dt_id;
      $a=ExecSql($this->db,"select dt_value from document_type where dt_id=".$this->dt_id);
      $v=pg_fetch_array($a,0);
      $str_type=$v[0];
      if ( isset ($_REQUEST['url'])) 
	{
	  $retour=sprintf('<A HREF="%s"><input type="button" value="Retour"></A>',urldecode($_REQUEST['url']));
	  $h_url=sprintf('<input type="hidden" name="url" value="%s">',urldecode($_REQUEST['url']));
	}
      else 
	{ 
	  $retour="";
	  $h_url="";
	}

      // state
      $a=ExecSql($this->db,"select s_value from document_state where s_id=".$this->d_state);
      $v=pg_fetch_array($a,0);
      $str_state=$v[0];
      $state=new widget("hidden");
      $state->name="d_state";
      $state->value=$this->d_state;
	
      // title
      $title=new widget("text");
      $title->readonly=true;
      $title->name="ag_title";
      $title->value=FormatString($this->ag_title);

      // Description
      $desc=new widget('textarea');
      $desc->name="ag_comment";
      $desc->readonly=" disabled ";
      $desc->value=$this->ag_comment;
      // Propose to generate a document
      $gen=new widget ("checkbox");
      $gen->name="p_gen";
      $doc_gen=new widget("select");
      $doc_gen->name="gen_doc";
      $doc_gen->value=make_array($this->db,
				 "select md_id,md_name from document_modele where md_type=".$this->dt_id);

      $h_agrefid=new widget('hidden');

      // f_id
      if ( trim($this->qcode) =="" && $this->dt_id==1)
	{
	  // internal document
	  $f_id=0; // internal document
	  $name="interne";
	}
      elseif ( trim($this->qcode) !="" && $this->dt_id != 1 )
	{
	  $tiers=new fiche($this->db);
	  $tiers->GetByQCode($this->qcode);
	  $f_id=$tiers->id;
	  $name=$tiers->strAttribut(1);
	}
      else {
	$name="ERROR";
	$f_id=-1;
      }

      // Preparing the return string
      $r=$retour."<form method=\"post\">";
      $r.="<p>Date : ".$date->IOValue()."</p>";
      $r.="<p>Etat $str_state".$state->IOValue()."</p>";
      $r.="<p>Type du document $str_type".$doc_type->IOValue()."</p>";
      $r.="<p> Tiers : ".$this->qcode." ".$name.'</p>';
      $r.="<p> Titre : ".$title->IOValue();
      $r.="<p>Description :".$desc->IOValue()."</p>";

      // if no document exist for this type then do not display the question
      if ( sizeof($doc_gen->value) != 0) 
	$r.="<p> G&eacute;n&eacute;rer un document ".$gen->IOValue()." ".$doc_gen->IOValue()."</p>";
	

      // Add the hidden tag
      $r.='<input type="hidden" name="sa" value="save_action_st2">';
      $r.='<input type="hidden" name="p_action" value="suivi_courrier">';
      $r.='<input type="hidden" name="tiers" value="'.$this->qcode.'">';
      $r.=	$h_agrefid->IOValue("ag_ref_ag_id",$this->ag_ref_ag_id);
	
      // retrieve customer


      if ( $f_id != -1 )
	$r.=$desc->Submit("Save","Sauve");
      $r.=$desc->Submit("corr","Corrige");

      $r.=$h_url."</form>";
      return $r;
    }
/*!  SaveStage2
 * \brief Save the document and propose to save the generated document or  
 *  to upload one, the data are included except the file. Temporary the generated
 * document is save
 *
 * \return
 */
  function SaveStage2() 
    {
      echo_debug('class_action',__LINE__,'saveStage2()  :'.var_export($_POST,true));
      echo_debug('class_action',__LINE__,' saveStage2()  $this  :'.var_export($this,true));

      // Get The sequence id, 
      $seq_name="seq_doc_type_".$this->dt_id;
      $str_file="";
      $add_file='';
      // f_id
      $tiers=new fiche($this->db);
      $tiers->GetByQCode($this->qcode);
      if ( trim($this->ag_title) == "") 
	{
	  $doc_mod=new document_type($this->db);
	  $doc_mod->dt_id=$this->dt_id;
	  $doc_mod->get();
	  $this->ag_title=$doc_mod->dt_value;
	}
      echo_debug('class_action',__LINE__," tiers->id  ".$tiers->id);
      $this->ag_id=NextSequence($this->db,'action_gestion_ag_id_seq');
      // Create the reference 
      $ref=$this->dt_id.'/'.$this->ag_id;
      $this->ag_ref=$ref;
      /*!\brief the ag_comment is already urlencoded 
       */
      //we remove newline 
      $this->ag_comment=str_replace("+%OA+","",$this->ag_comment);
      $this->ag_comment=str_replace("+%OD+","",$this->ag_comment);
      $this->ag_comment=str_replace("%OD","",$this->ag_comment);
      $this->ag_comment=str_replace("%OA","",$this->ag_comment);
      // save into the database
      $sql=sprintf("insert into action_gestion(ag_id,ag_timestamp,ag_type,ag_title,f_id,ag_comment,ag_ref,ag_ref_ag_id) ".
		   " values (%d,to_date('%s','DD-MM-YYYY'),'%d','%s',%d,'%s','%s',%d)",
		   $this->ag_id,
		   $this->ag_timestamp,
		   $this->dt_id,
		   FormatString($this->ag_title),
		   $tiers->id,
		   $this->ag_comment,
		   $ref,
		   $this->ag_ref_ag_id
		   );
      ExecSql($this->db,$sql);



      // the lob filename and the mimetype needed if we want to generate a doc.
      if ( $this->gen == 'on' ) 
	{
	  $doc=new Document($this->db);
	  $doc->f_id=$tiers->id;
	  $doc->md_id=$this->md_id;
	  $doc->ag_id=$this->ag_id;
	  $doc->d_state=$this->d_state;
	  $str_file=$doc->Generate();
	  $d='<input type="hidden" name="d_id" value="'.$doc->d_id.'">';
	}
      $r="";
      // readonly and upload of a file
      $r.="<hr>";
      $r.='<form enctype="multipart/form-data" method="post">';
      $r.=$this->Display('READ',false);
      // Add the hidden tag
      $r.='<input type="hidden" name="sa" value="save_action_st3">';
      $r.='<input type="hidden" name="p_action" value="suivi_courrier">';
      // add the d_id
      $r.='<input type="hidden" name="d_id" value="'.$this->d_id.'">'; 
      // ag_comment must be saved in urlcode
      $r.='<input type="hidden" name="ag_comment" value="'.urlencode($this->ag_comment).'">';
      // Value for the generated document
      if ( $this->gen == 'on' ) 
	{
	  $r.='<input type="hidden" name="d_id" value="'.$doc->d_id.'">';
	  $r.="Sauver le document généré :";
	  $r.=$str_file;
	  $checkbox=new widget("checkbox");
	  $checkbox->name="save_generate";
	  $r.=$checkbox->IOValue();
	  $r.="<hr>";
	}
      $upload=new widget("file");
      $upload->name="file_upload";
      $upload->value="";
      $r.="Enregistrer le fichier ".$upload->IOValue();
      $r.=$upload->Submit("save","Sauve le fichier");
      $r.="</form>";
      return $r;
    }
/*! SaveStage3
 * \brief Upload the document or save the generated document
 * \param $d_id document_id when we upload we don't increment seq. if = 0 then no file
 *
 */
  function SaveStage3($d_id) 
    {
      echo_debug('class_action',__LINE__,'SaveStage3()  :'.var_export($_POST,true));
      echo_debug('class_action',__LINE__,'SaveStage3  :'.var_export($this,true));

      // if we save the generated doc
      if ( isset($_POST['save_generate']))
	{
	  return;
	}

      // Upload a new document
      $doc=new Document($this->db);
      $doc->ag_id=$this->ag_id;
      // if there is a document and it is not an update
      if ( sizeof($_FILES) !=0 && $d_id==0) 
	{
	  $doc->md_type=$this->dt_id;
	  $doc->blank();
	}
      else 
	$doc->d_id=$d_id;

      $doc->Upload($this->ag_id);
    }

/*! myList($p_filter="")
 * \brief Show list of action
 * \param  $p_filter filters on the document_type
 * \param $p_search must a valid sql command ( ex 'and  ag_title like upper('%hjkh%'))
 * 
 * 
 * \return string containing html code
 */
  function myList($p_filter="",$p_search="")
    {
      $sql="
   select ag_id,to_char(ag_timestamp,'DD-MM-YYYY') as my_date,ag_ref_ag_id,f_id,ag_title,d_id,md_type,dt_value,ag_ref 
   from action_gestion 
      left outer join document using (ag_id)
      left outer join document_modele on (ag_type=md_type) 
      join document_type on (ag_type=dt_id)
   where dt_id in ($p_filter) $p_search order by ag_timestamp desc,ag_ref desc";
      $max_line=CountSql($this->db,$sql);
      $step=$_SESSION['g_pagesize'];
      $page=(isset($_GET['offset']))?$_GET['page']:1;
      $offset=(isset($_GET['offset']))?$_GET['offset']:0;
      $limit=" LIMIT $step OFFSET $offset ";  
      $bar=jrn_navigation_bar($offset,$max_line,$step,$page);

      $Res=ExecSql($this->db,$sql.$limit);
      $a_row=pg_fetch_all($Res);
      //      $r='<div class="u_redcontent">';
      $r="";
      $r.=$bar;
      $r.="<table>";
      $r.="<tr>";
      $r.="<th>Date</th>";
      $r.="<th>Société</th>";
      $r.="<th>Titre</th>";
      $r.="<th>type</th>";
      $r.="<th>Référence</th>";
      $r.="<th>concerne</th>";
      $r.="<th>Document</th>";
      $r.="</tr>";

      // if there are no records return a message
      if ( sizeof ($a_row) == 0 or $a_row == false ) 
	{
	  $r='<div class="u_redcontent">';
	  $r.='<hr>Aucun enregistrement trouvé';
	  $r.="</div>";
	  return $r;

	}
      echo JS_SEARCH_CARD;
      foreach ($a_row as $row )
	{
	  $f=new fiche($this->db);
	  $f->id=$row['f_id'];
	  $qcode=$f->strAttribut(ATTR_DEF_QUICKCODE);

	  $r.="<tr>";
	  $r.="<td>".$row['my_date']."</td>";
	  $q=($qcode=="- ERROR -")?"Interne":$qcode;
	  $js=sprintf("javascript:showfiche('%s','%s')",
		      $_REQUEST['PHPSESSID'],$q);
	  if ( $q != 'Interne' )
	    {
	      $r.="<td>".'<A HREF="'.$js.'">'.$q." : ".$f->getName().'</A></td>';
	    }
	  else
	    $r.="<td>Interne </td>";

	  // show reference
	  if ( $row['ag_ref_ag_id'] != 0 ) 
	    {
	      $retSqlStmt=ExecSql($this->db,
				  "select ag_ref from action_gestion where ag_id=".$row['ag_ref_ag_id']);
	      $retSql=pg_fetch_all($retSqlStmt);
	      $ref="";
	      foreach ($retSql as $line) 
		{
		  $ref.='<A  href="commercial.php?p_action=suivi_courrier&query='.$line['ag_ref'].'">'.
		    $line['ag_ref']."<A>";
		}
	    }
	  else 
	    $ref="";

	  $r.='<td><A HREF="commercial.php?p_action=suivi_courrier&sa=detail&ag_id='.$row['ag_id'].'">'.
	    $row['ag_title']."</A></td>";
	  $r.="<td>".$row['dt_value']."</td>";
	  $r.="<td>".$row['ag_ref']."</td>";
	  $r.="<td>".$ref."</td>";
	  $doc=new Document($this->db,$row['d_id']);
	  $r.="<td>".$doc->a_ref()."</td>";
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
     
      if ( trim($this->qcode) =="" )
	{
	  // internal document
	  $this->f_id=0; // internal document
	}
      else
	{
	  $tiers=new fiche($this->db);
	  if ( $tiers->GetByQCode($this->qcode) ==1 ) // Error we cannot retrieve this qcode
	    return false; 
	  else
	    $this->f_id=$tiers->id;

	}

      //remove newline from ag_comment
      $this->ag_comment=str_replace("\n","",$this->ag_comment);
      $this->ag_comment=str_replace("\r","",$this->ag_comment);

      // url encoded
      $this->ag_comment=urlencode($this->ag_comment);

      // bug PHP : sometimes the newline remains
      $this->ag_comment=str_replace("%A0","",$this->ag_comment);
      $this->ag_comment=str_replace("%0D","",$this->ag_comment);
      $this->ag_comment=str_replace("+%A0+","",$this->ag_comment);
      $this->ag_comment=str_replace("+%0D+","",$this->ag_comment);


      $sql=sprintf("update action_gestion set ag_comment='%s',".
		   " ag_timestamp=to_date('%s','DD.MM.YYYY'),".
		   " ag_title='%s',".
		   " ag_type=%d, ".
		   " f_id=%d, ".
		   " ag_ref_ag_id=%d".
		   " where ag_id = %d",
		   $this->ag_comment,
		   $this->ag_timestamp,
		   FormatString($this->ag_title),
		   $this->dt_id,
		   $this->f_id,
		   $this->ag_ref_ag_id,
		   $this->ag_id);
      ExecSql($this->db,$sql);
      
      if ( sizeof($_FILES) !=0 ) 
	{
	  // Upload a new document
	  $doc=new Document($this->db);
	  if ( $this->d_id !=0 )
	    {
	      $doc->d_id=$this->d_id;
	    } else {
	      // we need to increment the counter 
	      $doc->ag_id=$this->ag_id;
	      $doc->md_type=$this->dt_id;
	      $doc->blank();
	    }
	  $doc->Upload($this->ag_id);
      
	}
	   
      return true;
    }
  /*!\brief GetAgRef returns the ag_ref value of the ag_id or ag_ref_ag_id passed
   * as parameter. This function doesn't change the current object.
   * \param $p_method : can be ag_ref_ag_id or ag_id
   * \return string with the ag_ref
   */
  function GetAgRef($p_method)
    {
      if ($p_method=="ag_ref_ag_id")
	$sql="select ag_ref from action_gestion where ag_id=".$this->ag_ref_ag_id;
      elseif ($p_method=="ag_id")
	$sql="select ag_ref from action_gestion where ag_id=".$this->ag_id;
      return Getdbvalue($this->db,$sql);

    }

}