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
 * - an invoice
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
  * \enum \todo replace attribut frmo document by an object document 
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
/*!  constructor
 * \brief constructor
 * \param p_cn database connection
 */
  function action ($p_cn)
    {
      $this->db=$p_cn;
      $this->f_id=0;
    }
/*!  Display
 * \brief Display the object
 *
 * \return string containing the html code
 */
  function Display() 
    {
      $r="";
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

      // state
      // Retrieve the value
      // Retrieve the value if there is an attached doc
      $doc_ref="";
      if ( strlen ($this->d_state) > 0 )
	{
	  $a=ExecSql($this->db,"select s_value from document_state where s_id=".$this->d_state);
	  $v=pg_fetch_array($a,0);
	  $str_state=$v[0];
	  $state=new widget("hidden");
	  $state->name="d_state";
	  $state->value=$this->d_state;
	  $doc=new Document($this->db,$this->d_id);
	  $doc_ref="<p> Document ".$doc->a_ref().'</p>';
	}
      else 
	$str_state="";
      // title
      $title=new widget("text");
      $title->readonly=true;
      $title->name="ag_title";
      $title->value=FormatString($this->ag_title);

      // ag_ref
      $ag_ref=new widget("text");
      $ag_ref->readonly=true;
      $ag_ref->name="ag_ref";
      $ag_ref->value=FormatString($this->ag_ref);


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
      // f_id
      $tiers=new fiche($this->db);
      $tiers->GetByQCode($this->qcode);
      // Preparing the return string

      $r.="<p>Date : ".$date->IOValue()."</p>";
      if ( $str_type != "" )
	$r.="<p>Type du document $str_type".$doc_type->IOValue()."</p>";
      $r.="<p> Tiers : ".$this->qcode." ".$tiers->strAttribut(1).'</p>';
      $r.="<p> Titre : ".$title->IOValue().' Ref :'.$ag_ref->IOValue();
      $r.=$doc_ref;
      $r.="<p>Description :".urldecode(urldecode($desc->IOValue()))."</p>";

      return $r;
 
    }
  /*!\brief This function shows the detail of an action thanks the ag_id
   */
  function get()
    {
      echo_debug('class_action',__LINE__,'Action::Get() ');
      $sql="select * from action_gestion left join document using (ag_id) where ag_id=".$this->ag_id;
      $r=ExecSql($this->db,$sql);
      $row=pg_fetch_all($r);
      if ( $row==false) return;
      $this->ag_comment=$row[0]['ag_comment'];
      $this->ag_timestamp=$row[0]['ag_timestamp'];
      $this->f_id=$row[0]['f_id'];
      $this->ag_title=$row[0]['ag_title'];
      $this->ag_type=$row[0]['ag_type'];
      $this->ag_ref=$row[0]['ag_ref'];
      $this->d_id=$row[0]['d_id'];
      echo_debug('class_action',__LINE__,' Document id = '.$this->d_id);
      // if there is no document set 0 to d_id
      if ( $this->d_id == "" ) 
	$this->d_id=0;
      // if there is a document fill the object
      if ($this->d_id != 0 )
	{
	  $this->d_state=$row[0]['d_state'];
	}
      echo_debug('class_action',__LINE__,' After test Document id = '.$this->d_id);
      $this->dt_id=$this->ag_type;
      $a=new fiche($this->db,$this->f_id);
      $this->qcode=$a->strAttribut(ATTR_DEF_QUICKCODE);

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
      /*! \todo add the hour */
      if ( isDate($this->ag_timestamp) == NULL )
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
      // f_id
      $tiers=new fiche($this->db);
      $tiers->GetByQCode($this->qcode);
      // Preparing the return string
      $r=$retour."<form method=\"post\">";
      $r.="<p>Date : ".$date->IOValue()."</p>";
      $r.="<p>Etat $str_state".$state->IOValue()."</p>";
      $r.="<p>Type du document $str_type".$doc_type->IOValue()."</p>";
      $r.="<p> Tiers : ".$this->qcode." ".$tiers->strAttribut(1).'</p>';
      $r.="<p> Titre : ".$title->IOValue();
      $r.="<p>Description :".$desc->IOValue()."</p>";

      // if no document exist for this type then do not display the question
      if ( sizeof($doc_gen->value) != 0) 
	$r.="<p> G&eacute;n&eacute;rer un document ".$gen->IOValue()." ".$doc_gen->IOValue()."</p>";

      // Add the hidden tag
      $r.='<input type="hidden" name="sa" value="save_action_st2">';
      $r.='<input type="hidden" name="p_action" value="suivi_courrier">';
      $r.='<input type="hidden" name="tiers" value="'.$this->qcode.'">';
      if ( $tiers->strAttribut(1) != "- ERROR -")
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
      // Get The sequence id, 
      $seq_name="seq_doc_type_".$this->dt_id;
      $str_file="";
      $add_file='';
      // f_id
      $tiers=new fiche($this->db);
      $tiers->GetByQCode($this->qcode);
      if ( trim($this->ag_title) == "") {
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

      $sql=sprintf("insert into action_gestion(ag_id,ag_type,ag_title,f_id,ag_comment,ag_ref) values (%d,'%s','%s',%d,'%s','%s')",
		   $this->ag_id,
		   $this->dt_id,
		   FormatString($this->ag_title),
		   $tiers->id,
		   FormatString(urlencode($this->ag_comment)),
		   $ref
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

      $r=$this->Display();
      $r.="<hr>";
      $r.='<form enctype="multipart/form-data" method="post">';
      // Add the hidden tag
      $r.='<input type="hidden" name="sa" value="save_action_st3">';
      $r.='<input type="hidden" name="p_action" value="suivi_courrier">';
      $r.='<input type="hidden" name="ag_id" value="'.$this->ag_id.'">';
      // add the d_id
      $r.=$d;

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
      // no generated doc
      if ( $d_id == 0 ) return;
      // if we save the generated doc
      if ( isset($_POST['save_generate']))
	{
	  return;
	}

      // Upload a new document
      $doc=new Document($this->db);
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
   select ag_id,to_char(ag_timestamp,'DD-MM-YYYY HH:MI') as my_date,f_id,ag_title,d_id,md_type,dt_value,ag_ref 
   from action_gestion 
      left outer join document using (ag_id)
      left outer join document_modele on (ag_type=md_type) 
      join document_type on (ag_type=dt_id)
   where dt_id in ($p_filter) $p_search";
      $max_line=CountSql($this->db,$sql);
      $step=$_SESSION['g_pagesize'];
      $page=(isset($_GET['offset']))?$_GET['page']:1;
      $offset=(isset($_GET['offset']))?$_GET['offset']:0;
      $limit=" LIMIT $step OFFSET $offset ";  
      $bar=jrn_navigation_bar($offset,$max_line,$step,$page);

      $Res=ExecSql($this->db,$sql.$limit);
      $a_row=pg_fetch_all($Res);
      $r='<div class="u_redcontent">';
      $r.=$bar;
      $r.="<table>";
      $r.="<tr>";
      $r.="<th>Date</th>";
      $r.="<th>Société</th>";
      $r.="<th>Titre</th>";
      $r.="<th>type</th>";
      $r.="<th>Référence</th>";
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
	  $r.="<tr>";
	  $r.="<td>".$row['my_date']."</td>";
	  $f=new fiche($this->db);
	  $f->id=$row['f_id'];
	  $q=$f->strAttribut(ATTR_DEF_QUICKCODE);
	  $js=sprintf("javascript:showfiche('%s','%s')",
		      $_REQUEST['PHPSESSID'],$q);
	  $r.="<td>".'<A HREF="'.$js.'">'.$q." : ".$f->getName().'</A></td>';
	  $r.='<td><A HREF="commercial.php?p_action=suivi_courrier&sa=detail&ag_id='.$row['ag_id'].'">'.
	    $row['ag_title']."</A></td>";
	  $r.="<td>".$row['dt_value']."</td>";
	  $r.="<td>".$row['ag_ref']."</td>";

	  $doc=new Document($this->db,$row['d_id']);
	  $r.="<td>".$doc->a_ref()."</td>";
	  $r.="</tr>";

	}
      $r.="</table>";
      $r.=$bar;
      $r.="</div>";
      return $r;
    }



}