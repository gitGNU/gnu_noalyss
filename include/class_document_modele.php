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
/*! \file
 * \brief Class for the document template 
 */
/*!
 * \brief Class for the document template 
 */
require_once('class_icheckbox.php');
require_once('class_ihidden.php');
require_once('class_ifile.php');
class Document_modele {
  var $cn;         	/*!< $cn  database connection */
  var $md_id;	        /*!< $md_id pk */
  var $md_name;         /*!< $md_name template's name */
  var $md_type;         /*!< $md_type template's type (letter, invoice, order...) */
  var $md_lob;          /*!< $md_lob Document file */
  var $md_sequence;     /*!<  $md_sequence sequence name (autogenerate) */
  var $sequence;        /*!< $sequence sequence number used by the create sequence start with */
  //Constructor parameter = database connexion
  function Document_modele($p_cn,$p_id=-1) {
    $this->cn=$p_cn;	
    $this->md_id=$p_id;
  }
  
  /*!
   **************************************************
   * \brief : show all the stored document_modele.
   *        return a string containing all the data
   *        separate by TD & TR tag
   * \return table in HTML Code
   */
  function myList() { 
	$s=dossier::get();
    $sql="select md_id,md_name,dt_value from document_modele join document_type on(dt_id=md_type)";
    $Res=ExecSql($this->cn,$sql);
    $all=pg_fetch_all($Res);
    if ( pg_NumRows($Res) == 0 ) return "";
    $r='<p><form method="post">';
	$r.=dossier::hidden();
    $r.="<table>";
    $r.="<tr> <th> Nom </th> <th>Type</Th><th>Fichier</th><th> Effacer</th>"; 
    $r.="</tr>";
    foreach ( $all as $row) {
      $r.="<tr>";
      $r.="<td>";
      $r.=h($row['md_name']);
      $r.="</td>";
      $r.="<td>";
      $r.=$row['dt_value'];
      $r.="</td>";
      $r.="<td>";
      $r.='<A HREF="show_document_modele.php?md_id='.$row['md_id'].'&'.$s.'">Document</a>';
      $r.="</td>";
      $r.="<TD>";
      $c=new ICheckBox();
      $c->name="dm_remove_".$row['md_id'];
      $r.=$c->input();
      $r.="</td>";
      $r.="</tr>";
    } 
    $r.="</table>";
    
    // need hidden parameter for subaction
    $a=new IHidden();
    $a->name="sa";
    $a->value="rm_template";
    $r.=$a->input();
    $r.=HtmlInput::submit("rm_template","Effacer la sélection");
    $r.="</form></p>";
    return $r;
  }
  /*!  
   **************************************************
   * \brief :  Save a document_modele in the database, 
   *       if the document_modele doesn't exist yet it will be
   *       first created (-> insert into document_modele)
   *       in that case the name and the type must be set
   *       set before calling Save, the name will be modified
   *       with FormatString
   *        
   */
  function Save() 
    {
      // if name is empty return immediately
      if ( trim(strlen($this->md_name))==0) 
	return;
      try 
	{
	  // Start transaction
	  StartSql($this->cn);
	  // Save data into the table document_modele
	  // if $this->md_id == -1 it means it is a new document model
	  // so first we have to insert it
	  // the name and the type must be set before calling save
	  if ( $this->md_id == -1) 
	    {
	      
	      // insert into the table document_modele
	      $this->name=FormatString($this->md_name);
	      $this->md_id=NextSequence($this->cn,'document_modele_md_id_seq');
	      $sql=sprintf("insert into document_modele(md_id,md_name,md_type) 
                              values (%d,'%s',%d)",
			   $this->md_id,$this->name,$this->md_type);
	      $Ret=ExecSql($this->cn,$sql);
	      // create the sequence for this modele of document
	      $this->md_sequence="document_".NextSequence($this->cn,"document_seq");
	      // if start is not equal to 0 and he's a number than the user
	      // request a number change
	      echo_debug('class_document_modele',__LINE__, "this->start ".$this->start." a number ".isNumber($this->start));
	      
	      if ( $this->start != 0 && isNumber($this->start) == 1 )
		{
		  $sql="alter sequence seq_doc_type_".$this->md_type." restart ".$this->start;
		  ExecSql($this->cn,$sql);
		}
	      
	    }
	  // Save the file
	  $new_name=tempnam($_ENV['TMP'],'document_');
	  echo_debug('class_document_modele.php',__LINE__,"new name=".$new_name);
	  if ( strlen ($_FILES['doc']['tmp_name']) != 0 ) 
	    {
	      if (move_uploaded_file($_FILES['doc']['tmp_name'],
				     $new_name)) 
		{
		  // echo "Image saved";
		  $oid= pg_lo_import($this->cn,$new_name);
		  if ( $oid == false ) 
		    {
		      echo_error('class_document_modele.php',__LINE__,"cannot upload document");
		      Rollback($cn);
		      return;
		    }
		  echo_debug('class_document_modele.php',__LINE__,"Loading document");
		  // Remove old document
		  $ret=ExecSql($this->cn,"select md_lob from document_modele where md_id=".$this->md_id);
		  if (pg_num_rows($ret) != 0) 
		    {
		      $r=pg_fetch_array($ret,0);
		      $old_oid=$r['md_lob'];
		      if (strlen($old_oid) != 0) 
			pg_lo_unlink($this->cn,$old_oid);
		    }
		  // Load new document
		  ExecSql($this->cn,"update document_modele set md_lob=".$oid.", md_mimetype='".$_FILES['doc']['type']."' ,md_filename='".$_FILES['doc']['name']."' where md_id=".$this->md_id);
		  Commit($this->cn);
		}
	      else 
		{
		  echo "<H1>Error</H1>";
		  Rollback($this->cn);
		  exit;
		}
	    }
	}
      catch (Exception $e)
	{
	  echo_debug(__FILE__.":".__LINE__." Erreur : ".$e->getCode()." msg ".$e->getMessage());
	  rollback($this->cn); 
	  return ;
	}
    }
  /*!
   * \brief Remove a template 
   * \return nothing
   */
  function Delete() 
    {
      StartSql($this->cn);
      // first we unlink the document
      $sql="select md_lob from document_modele where md_id=".$this->md_id;
      $res=ExecSql($this->cn,$sql);
      $r=pg_fetch_array($res,0);
      // if a lob is found
      if ( strlen ($r['md_lob']) != 0 )
	{
	  // we remove it first
	  pg_lo_unlink($r['md_lob']);
	}
      // now we can delete the row
      $sql="delete from document_modele where md_id =".$this->md_id;
      $sql=ExecSql($this->cn,$sql);
      Commit($this->cn);
    }
  
  /*!
   * \brief show the form for loading a template
   * \param p_action for the field action = destination url 
   * 
   *
   * \return string containing the forms
   */
  function form($p_action) 
    {
      $r='<p class="notice">';
      $r.='Veuillez introduire les mod&egrave;les servant à g&eacute;n&eacute;rer vos documents';
      $r.='</p>';
      $r.='<form enctype="multipart/form-data"  action="'.$p_action.'" method="post">';
      $r.=dossier::hidden();
      // we need to add the sub action as hidden
      $h=new IHidden();
      $h->name="sa";
      $h->value="add_document";

      $r.=$h->input();

      $r.='<table>';
      $t=new IText();
      $t->name="md_name";
      $r.="<tr><td> Nom </td><td>".$t->input()."</td>";

      $r.="</tr>";
      $r.="<tr><td>Type de document </td>";
      $w=new ISelect();
      $w->name="md_type";

      $w->value=make_array($this->cn,'select dt_id,dt_value from document_type');
      $r.="<td>".$w->input()."</td></tr>";

      $f=new IFile();
      $f->name="doc";
      $r.="<tr><td>fichier</td><td> ".$f->input()."</td></tr>";

      $start=new IText();
      $start->name="start_seq";
      $start->size=9;
      $start->value="0";

      $r.="<tr><td> Numerotation commence a</td><td> ".$start->input()."</td>";
      $r.='<td class="notice">Si vous laissez &agrave; 0, la num&eacute;rotation ne changera pas, la prochaine facture sera n+1, n étant le n° que vous avez donn&eacute;</td>';
      $r.="</tr>";
      $r.='</table>';
      $r.=HtmlInput::submit('add_document','Ajout');
      $r.="</form></p>";
      return $r;
    }

}
?>
