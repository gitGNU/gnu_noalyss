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
require_once('class_own.php');
require_once('class_poste.php');
require_once('class_action.php');
/*! \file 
 * \brief Class Document corresponds to the table document
 */
/*! \brief Class Document corresponds to the table document
 */
class Document 
{
  var $db;          /*! \enum $db Database connexion*/
  var $d_id;        /*! \enum $d_id Document id */ 
  var $ag_id;       /*! \enum $ag_id action_gestion.ag_id (pk) */
  var $d_mimetype;  /*! \enum $d_mimetype  */
  var $d_filename;  /*! \enum $d_filename */
  var $d_lob;       /*! \enum $d_lob the oid of the lob */
  var $d_number;    /*! \enum $d_number number of the document */
  var $md_id;       /*! \enum $md_id document's template */
  var $d_state;     /*! \enum $d_state document.d_state status of the document */
  /* Constructor
   * \param $p_cn Database connection
   */
  function Document($p_cn,$p_d_id=0)
    {
      $this->db=$p_cn;
      $this->d_id=$p_d_id;
    } 
  /*!\brief insert a minimal document and set the d_id
   */
  function blank()
    {
      $this->d_id=NextSequence($this->db,"document_d_id_seq");
      // affect a number
      $this->d_number=NextSequence($this->db,"seq_doc_type_".$this->md_type);
      $sql=sprintf('insert into document(d_id,ag_id,d_number) values(%d,%d,%d)',
		   $this->d_id,
		   $this->ag_id,
		   $this->d_number);
      ExecSql($this->db,$sql);

    }
  /*!\brief Save save the state of the document
   */
  function save()
    {
      $sql="update document set d_state=".$this->d_state.
	" where d_id=".$this->d_id;
      ExecSql($this->db,$sql);
    }
/*!  
 * \brief Generate the document, Call $this-\>Replace to replace
 *        tag by value
 *        
 * \param none
 * 
 *
 * \return an array : the url where the generated doc can be found, the name
 * of the file and his mimetype
 */
  function Generate() 
    {
      // create a temp directory in /tmp to unpack file and to parse it
      $dirname=tempnam($_ENV['TMP'],'doc_');
      

      unlink($dirname);
      mkdir ($dirname);
      echo_debug('class_action',__LINE__,"Dirname is $dirname");
      // Retrieve the lob and save it into $dirname
      StartSql($this->db);
      $dm_info="select md_type,md_lob,md_filename,md_mimetype 
                   from document_modele where md_id=".$this->md_id;
      $Res=ExecSql($this->db,$dm_info);

      $row=pg_fetch_array($Res,0);
      $this->d_lob=$row['md_lob'];
      $this->d_filename=$row['md_filename'];
      $this->d_mimetype=$row['md_mimetype'];

      echo_debug('class_document',__LINE__,"OID is ".$row['md_lob']);

      chdir($dirname);
      $filename=$row['md_filename'];
      pg_lo_export($this->db,$row['md_lob'],$filename);
      $type="n";
      echo_debug('class_document',__LINE__,'The document type is '.$row['md_mimetype']);
      // if the doc is a OOo, we need to unzip it first
      // and the name of the file to change is always content.xml
      if ( strpos($row['md_mimetype'],'vnd.oasis') != 0 )
	{
	  echo_debug('class_document',__LINE__,'Unzip the OOo');
	  echo '<span id="gen_msg">';
	  echo '<blink><font color="red">Un moment de patience, le document se prépare...</font></blink>';
	  echo '</span><br>';
	  ob_start();
	  system("unzip ".$filename);
	  // Remove the file we do  not need anymore
	  unlink($filename);
	  ob_end_clean();
	  $file_to_parse="content.xml";
	  $type="OOo";
	}
      else 
	$file_to_parse=$filename;
      // affect a number
      $this->d_number=NextSequence($this->db,"seq_doc_type_".$row['md_type']);


      // parse the document - return the doc number ?
      $this->ParseDocument($dirname,$file_to_parse,$type);

      Commit($this->db);
      // if the doc is a OOo, we need to re-zip it 
      if ( strpos($row['md_mimetype'],'vnd.oasis') != 0 )
	{
	  ob_start();
	  system ("zip -r ".$filename." *");
	  ob_end_clean();
	  echo "  ";
?>
<script language="javascript">
	this.document.getElementById('gen_msg').innerHTML='<font color="green">le document est prêt</color>';
</script>

<?php
	  $file_to_parse=$filename;
	}
      // Create a directory 
      $l_dir=$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.$dirname;
      echo_debug('class_document','',"l_dir   = $l_dir");
      mkdir ($l_dir);

      // we need to rename the new generated file
      rename($dirname.DIRECTORY_SEPARATOR.$file_to_parse,$_SERVER['DOCUMENT_ROOT'].$dirname.DIRECTORY_SEPARATOR.$file_to_parse);
      $this->SaveGenerated($_SERVER['DOCUMENT_ROOT'].$dirname.DIRECTORY_SEPARATOR.$file_to_parse);
      // Invoice
      $ret='<A class="mtitle" HREF="show_document.php?d_id='.$this->d_id.'&'.dossier::get().'">Document généré</A>';
      return $ret;
    }
    
  /*! ParseDocument
   * \brief This function parse a document and replace all
   *        the predefined tags by a value. This functions 
   *        generate diffent documents (invoice, order, letter)
   *        with the info from the database
   *
   * \param $p_dir directory name
   * \param $p_file filename
   * \param $p_type For the OOo document the tag are &lt and &gt instead of < and >
   */
  function ParseDocument($p_dir,$p_file,$p_type)
    {

      echo_debug('class_document',__LINE__,'Begin parsing of '.$p_dir.' '.$p_file.'Type = '.$p_type);
      
      /*!\note Replace in the doc the tags by their values.
       *  - MY_*   table parameter
       *  - ART_VEN* table quant_sold for invoice
       *  - CUST_* table quant_sold and fiche for invoice
       *  - e_* for the invoice in the $_POST 
       */ 
      // open the document
      $infile_name=$p_dir.DIRECTORY_SEPARATOR.$p_file;
      echo_debug("class_document.php",__LINE__,"Open the document $p_dir/$p_file");
      $h=fopen($infile_name,"r");

      // check if tmpdir exist otherwise create it
      $temp_dir=$_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR.'tmp';
      if ( is_dir($temp_dir) == false )
	{
	  if ( mkdir($temp_dir) == false )
	    {
	      echo "Ne peut pas créer le répertoire ".$temp_dir;
	      exit();
	    }
	}
      // Compute output_name
      $output_name=tempnam($temp_dir,"gen_doc_");
      $output_file=fopen($output_name,"w+");
      // check if the opening is sucessfull
      if (  $h == false ) 
	{
	  echo "cannot open $p_dir $p_file ";
	  exit();
	}
      if ( $output_file == false) 
	{
	  echo "ne peut pas ouvrir le fichier de sortie";
	  exit();
	}
      // compute the regex
      if ( $p_type=='OOo')
	{
	  $regex="&lt;&lt;[A-Z]+_*[A-Z]*_*[A-Z]*_*[A-Z]*_*[0-9]*&gt;&gt;";
	  $lt="&lt;";
	  $gt="&gt;";
	}
      else
	{
	  $regex="<<[A-Z]+_*[A-Z]*_*[A-Z]*_*[A-Z]*_*[0-9]*>>";
	  $lt="<";
	  $gt=">";
	}
      //read the file
      while(! feof($h)) 
	{
	  // replace the tag
	  $buffer=fgets($h);
	  // search in the buffer the magic << and >>
	  // while ereg finds something to replace
	  while ( ereg ($regex,$buffer,$f) )
	    {

	    echo_debug('class_document',__LINE__,'var_export '.var_export( $f,true));
	    foreach ( $f as $pattern )
	      {
		echo_debug('class_document',__LINE__, "pattern");
		echo_debug('class_document',__LINE__, var_export($pattern,true));
		$to_remove=$pattern;
		// we remove the < and > from the pattern
		$pattern=str_replace($lt,'',$pattern);
		$pattern=str_replace($gt,'',$pattern);


		// if the pattern if found we replace it
		$value=$this->Replace($pattern);

		// if the document is OOo, we need to transform accentuate letters
		if ( $p_type=='OOo' )
		  $value = utf8_encode($value);

		// replace into the $buffer
		// take the position in the buffer 
		$pos=strpos($buffer,$to_remove);
		// get the length of the string to remove
		$len=strlen($to_remove);
		$buffer=substr_replace($buffer,$value,$pos,$len);

		//		echo_debug('class_document',__LINE__, $buffer);
		// if the pattern if found we replace it
		echo_debug('class_document',__LINE__,"Transform $pattern by $value");
	      }
	  }
	  // write into the output_file
	  fwrite($output_file,$buffer);

	}
      fclose($h);
      fclose($output_file);
      rename ($output_name,$infile_name);
      // Save the document into the database

    }
  /*! SaveGenerated
   * \brief Save the generated Document
   * \param $p_file is the generated file
   * 
   *
   * \return 0 if no error otherwise 1
   */
  function SaveGenerated($p_file) 
    {
      echo_debug('class_document',__LINE__,'Save generated');
      // We save the generated file
      $doc=new Document($this->db);
      StartSql($this->db);
      $this->d_lob=pg_lo_import($this->db,$p_file);
      if ( $this->d_lob == false ) { 
	Rollback($this->db); echo_debug('class_document',__LINE__,"can't save file $p_file");
	return 1; }
    
      $sql=sprintf("insert into document(ag_id,d_lob,d_number,d_filename,d_mimetype,d_state) 
                        values (%d,%s,%d,'%s','%s',%d)",
		   $this->ag_id,
		   $this->d_lob,
		   $this->d_number,
		   $this->d_filename,
		   $this->d_mimetype,
		   $this->d_state
		   );
      ExecSql($this->db,$sql);
      $this->d_id=GetSequence($this->db,"document_d_id_seq");
      echo_debug('class_document',__LINE__,'document sauvé : d_id'.$this->d_id);
      // Clean the file
      unlink ($p_file);
      Commit($this->db);
      return 0;
    }
  /*! Upload
   * \brief Upload a file into document 
   *  all the needed data are in $_FILES we don't increment the seq
   * 
   *
   * \return
   */
  function Upload() 
    {

      // nothing to save
      if ( sizeof($_FILES) == 0 ) return;

      // Start Transaction
      StartSql($this->db);
      $new_name=tempnam($_ENV['TMP'],'doc_');


      // check if a file is submitted
      if ( strlen($_FILES['file_upload']['tmp_name']) != 0 )
	{
	  // upload the file and move it to temp directory
	  if ( move_uploaded_file($_FILES['file_upload']['tmp_name'],$new_name))
	  {
	    $oid=pg_lo_import($this->db,$new_name);
	    // check if the lob is in the database
	    if ( $oid == false ) 
	      {
		Rollback($this->db);
		return 1;
	      }
	  }
	  // the upload in the database is successfull
	  $this->d_lob=$oid;
	  $this->d_filename=$_FILES['file_upload']['name'];
	  $this->d_mimetype=$_FILES['file_upload']['type'];
	  // now we have to update the col.
	  // We retrieve the row to remove a possible existing lob (replace)
	  $sql="select d_lob from document where d_id=".$this->d_id;
	  $ret=ExecSql($this->db,$sql);

	  if (pg_num_rows($ret) != 0)  
	    {
	      // a result is found, the old oid is keept in order to
	      // remove it later
	      $r=pg_fetch_array($ret,0) ;
	      $old_oid=$r['d_lob'] ;
	      if (strlen($old_oid) != 0) { pg_lo_unlink ($this->db,$old_oid);}
	    }
	  // Update the table
	  $sql=sprintf("update document set d_lob=%s,d_filename='%s',d_mimetype='%s' where d_id=%d",
		       $this->d_lob,$this->d_filename,$this->d_mimetype,$this->d_id);
	  ExecSql($this->db,$sql);
	}
      Commit($this->db);

    }
/*! a_ref
 * \brief create and compute a string for reference the doc <A ...>
 *
 * \return a string
 */
  function a_ref() 
    {
      if ( $this->d_id == 0 )
	return '';
      $image='<IMG SRC="image/insert_table.gif" title="'.$this->d_filename.'" border="0">';
      $r="";
      $r='<A class="mtitle" HREF="show_document.php?d_id='.$this->d_id.'">'.$image.'</A>';
      return $r;
    }
  /* ! Get
   * \brief Send the document
   */
  function Send() 
    {
      // retrieve the template and generate document
      StartSql($this->db);
      $ret=ExecSql($this->db,
		   "select d_id,d_lob,d_filename,d_mimetype from document where d_id=".$this->d_id );
      if ( pg_num_rows ($ret) == 0 )
	return;
      $row=pg_fetch_array($ret,0);
      //the document  is saved into file $tmp
      $tmp=tempnam($_ENV['TMP'],'document_');
      pg_lo_export($this->db,$row['d_lob'],$tmp);
      $this->d_mimetype=$row['d_mimetype'];
      $this->d_filename=$row['d_filename'];

      // send it to stdout
      ini_set('zlib.output_compression','Off');
      header("Pragma: public");
      header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
      header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
      header("Cache-Control: must-revalidate");
      header('Content-type: "'.$this->d_mimetype.'"');
      header('Content-Disposition: attachment;filename="'.$this->d_filename.'"',FALSE);
      header("Accept-Ranges: bytes");
      $file=fopen($tmp,'r');
      while ( !feof ($file) )
	{
	  echo fread($file,8192);
	}
      fclose($file);
      
      unlink ($tmp);
      
      Commit($this->db);
      
    }
  /*!\brief Get  complete all the data member thx info from the database
   */
  function get()
    {
      $sql="select * from document where d_id=".$this->d_id;
      $ret=ExecSql($this->db,$sql);
      if ( pg_num_rows($ret) == 0 )
	return;
      $row=pg_fetch_array($ret,0);
      $this->ag_id=$row['ag_id'];
      $this->d_mimetype=$row['d_mimetype'];
      $this->d_filename=$row['d_filename'];
      $this->d_lob=$row['d_lob'];
      $this->d_state=$row['ag_id'];
      $this->d_number=$row['d_number'];

    }
/*! 
 * \brief replace the TAG by the real value, this value can be into
 * the database or in $_POST 
 * The possible tags are
 *  - [CUST_NAME] customer's name
 *  - [CUST_ADDR_1] customer's address line 1
 *  - [CUST_CP] customer's ZIP code
 *  - [CUST_CO] customer's country
 *  - [CUST_COUNTRY]
 *  - [CUST_VAT] customer's VAT
 *  - [MARCH_NEXT]   end this item and increment the counter $i
 *  - [VEN_ART_NAME]
 *  - [VEN_ART_PRICE]
 *  - [VEN_ART_QUANT]
 *  - [VEN_ART_TVA_CODE]
 *  - [VEN_ART_TVA_AMOUNT]
 *  - [VEN_ART_STOCK_CODE]
 *  - [VEN_HTVA]
 *  - [VEN_TVAC]
 *  - [VEN_TVA]
 *  - [TOTAL_VEN_HTVA]
 *  - [DATE]
 *  - [NUMBER_ID]
 *  - [MY_NAME]
 *  - [MY_CP]
 *  - [MY_COMMUNE]
 *  - [MY_TVA]
 *  - [MY_STREET]
 *  - [MY_NUMBER]
 *
 * \param TAG
 * \return String which must replace the tag
 */
  function Replace($p_tag)
    {
      $r="Tag inconnu";
      static $counter=0;
      switch ($p_tag)
	{
	case 'DATE':
	  $r=' Date inconnue ';
	  // Date are in $_REQUEST['ag_date'] 
	  // or $_POST['e_date']
	  if ( isset ($_REQUEST['ag_date']))
	    $r=$_REQUEST['ag_date'];
	  if ( isset ($_REQUEST['e_date']))
	    $r=$_REQUEST['e_date'];

	  break; 
	  //
	  //  the company priv

	case 'MY_NAME':
	  $my=new own($this->db);
	  $r=$my->MY_NAME;
	  break;
	case 'MY_CP':
	  $my=new own($this->db);
	  $r=$my->MY_CP;
	  break;
	case 'MY_COMMUNE':
	  $my=new own($this->db);
	  $r=$my->MY_COMMUNE;
	  break;
	case 'MY_TVA':
	  $my=new own($this->db);
	  $r=$my->MY_TVA;
	  break;
	case 'MY_STREET':
	  $my=new own($this->db);
	  $r=$my->MY_STREET;
	  break;
	case 'MY_NUMBER':
	  $my=new own($this->db);
	  $r=$my->MY_NUMBER;
	  break;
	case 'MY_TEL':
	  $my=new own($this->db);
	  $r=$my->MY_TEL;
	  break;
	case 'MY_FAX':
	  $my=new own($this->db);
	  $r=$my->MY_FAX;
	  break;
	case 'MY_PAYS':
	  $my=new own($this->db);
	  $r=$my->MY_PAYS;
	  break;

	  // customer 
	  /*\note The CUST_* are retrieved thx the $_REQUEST['tiers'] 
	   * which contains the quick_code
	   */
	case 'SOLDE':
	  $tiers=new fiche($this->db);
	  $qcode=isset($_REQUEST['qcode_dest'])?$_REQUEST['qcode_dest']:$_REQUEST['e_client'];
	  $tiers->Getbyqcode($qcode,false);
	  $p=$tiers->strAttribut(ATTR_DEF_ACCOUNT);
	  $poste=new Poste($this->db,$p);
	  $r=$poste->GetSolde(' true' );
	  break;
	case 'CUST_NAME':
	  $tiers=new fiche($this->db);
	  $qcode=isset($_REQUEST['qcode_dest'])?$_REQUEST['qcode_dest']:$_REQUEST['e_client'];
	  $tiers->getByQcode($qcode,false);
	  $r=$tiers->strAttribut(ATTR_DEF_NAME);
	  break;
	case 'CUST_ADDR_1':
	  $tiers=new fiche($this->db);
	  $qcode=isset($_REQUEST['qcode_dest'])?$_REQUEST['qcode_dest']:$_REQUEST['e_client'];
	  $tiers->getByQcode($qcode,false);
	  $r=$tiers->strAttribut(ATTR_DEF_ADRESS);
	  
	  break ;
	case 'CUST_CP':
	  $tiers=new fiche($this->db);
	  $qcode=isset($_REQUEST['qcode_dest'])?$_REQUEST['qcode_dest']:$_REQUEST['e_client'];
	  $tiers->getByQcode($qcode,false);
	  $r=$tiers->strAttribut(ATTR_DEF_CP);

	  break;
	case 'CUST_CO':
	  $tiers=new fiche($this->db);
	  $qcode=isset($_REQUEST['qcode_dest'])?$_REQUEST['qcode_dest']:$_REQUEST['e_client'];
	  $tiers->getByQcode($qcode,false);
	  $r=$tiers->strAttribut(ATTR_DEF_PAYS);

	  break; 
	  // Marchandise in $_POST['e_march*']
	  // \see user_form_achat.php or user_form_ven.php
	case 'CUST_VAT':
	  $tiers=new fiche($this->db);
	  $qcode=isset($_REQUEST['qcode_dest'])?$_REQUEST['qcode_dest']:$_REQUEST['e_client'];
	  $tiers->getByQcode($qcode,false);
	  $r=$tiers->strAttribut(ATTR_DEF_NUMTVA);
	  break; 
	  // Marchandise in $_POST['e_march*']
	  // \see user_form_achat.php or user_form_ven.php
	case 'NUMBER':
	  $r=$this->d_number;
	  break;
	case 'REFERENCE':
	  $act=new action($this->db);
	  $act->ag_id=$this->ag_id;
	  $act->get();
	  $r=$act->ag_ref;
	  break;

	  /*
	   *  - [VEN_ART_NAME]
	   *  - [VEN_ART_PRICE]
	   *  - [VEN_ART_QUANT]
	   *  - [VEN_ART_TVA_CODE]
	   *  - [VEN_ART_TVA_AMOUNT]
	   *  - [VEN_ART_STOCK_CODE]
	   *  - [VEN_HTVA]
	   *  - [VEN_TVAC]
	   *  - [VEN_TVA]
	   *  - [TOTAL_VEN_HTVA]
	   */
	case 'MARCH_NEXT':
	  $counter++;
	  $r='';
	  break;
	  
	case 'VEN_ART_NAME':
	  extract ($_POST);
	  $id='e_march'.$counter;
	  // check if the march exists
	  if ( ! isset (${$id})) return "";
	  // check that something is sold
	  if ( ${'e_march'.$counter.'_sell'} != 0 && ${'e_quant'.$counter} != 0 )
	    {
	      $f=new fiche($this->db);
	      $f->GetByQCode(${$id},false);
	      $r=$f->strAttribut(ATTR_DEF_NAME);
	    } else $r = "";
	  break;

	case 'VEN_ART_PRICE':
	  extract ($_POST);
	  $id='e_march'.$counter.'_sell' ;
	  if ( !isset (${$id}) ) return "";
	  $r=${$id};
	  break;

	case 'TVA_CODE':
	  extract ($_POST);
	  $id='e_march'.$counter.'_tva_id';
	  if ( !isset (${$id}) ) return "";
	  if ( ${$id} == -1 ) return "";
	  $qt='e_quant'.$counter;
	  $price='e_march'.$counter.'_sell' ;
	  if ( ${$price} == 0 || ${$qt} == 0 
	       || strlen(trim( $price )) ==0 
	       || strlen(trim($qt)) ==0)
	    return "";

	  $r=${$id};
	  break;

	case 'TVA_LABEL':
	  extract ($_POST);
	  $id='e_march'.$counter.'_tva_id';
	  if ( !isset (${$id}) ) return "";
	  $tva=GetTvaRate($this->db,${$id});
	  if ( $tva == null || $tva==0 ) return "";
	  $r=$tva['tva_label'];
	  break;


	case 'TVA_AMOUNT':
	  extract ($_POST);
	  $qt='e_quant'.$counter;
	  $price='e_march'.$counter.'_sell' ;
	  $tva='e_march'.$counter.'_tva_id';
	  if ( !isset (${'e_march'.$counter}) ) return "";
	  // check that something is sold
	  if ( ${$price} == 0 || ${$qt} == 0 
	       || strlen(trim( $price )) ==0 
	       || strlen(trim($qt)) ==0)
	    return "";
	  $a_tva=GetTvaRate($this->db,${$tva});
	  echo_debug('class_document',__LINE__,'Tva  :'.var_export($a_tva,true));
	  // if no vat returns 0
	  if ( sizeof($a_tva) == 0 ) return "";
	  $r=round(${$price},2)*${$qt}*$a_tva['tva_rate'];
	  $r=round($r,2);
	  break;

	case 'VEN_ART_QUANT':
	  extract ($_POST);
	  $id='e_quant'.$counter;
	  if ( !isset (${$id}) ) return "";
	  // check that something is sold
	  if ( ${'e_march'.$counter.'_sell'} == 0 
	       || ${'e_quant'.$counter} == 0 
	       || strlen(trim( ${'e_march'.$counter.'_sell'} )) ==0 
	       || strlen(trim(${'e_quant'.$counter})) ==0 )
	    return "";
	  $r=${$id};
	  break;

	case 'VEN_HTVA':
	  extract ($_POST);
	  $id='e_march'.$counter.'_sell' ;
	  $quant='e_quant'.$counter;
	  if ( !isset (${$id}) ) return "";

	  // check that something is sold
	  if ( ${'e_march'.$counter.'_sell'} == 0 || ${'e_quant'.$counter} == 0 
	       || strlen(trim( ${'e_march'.$counter.'_sell'} )) ==0 
	       || strlen(trim(${'e_quant'.$counter})) ==0)
	    return "";
	  /*!\todo verify that price and quant are numeric
	   */
	  /*!\todo carefull round problem ?
	   */

	  $r=round(${$id}*${$quant},2); 
	  break;

	case 'VEN_TVAC':
	  extract ($_POST);
	  $id='e_march'.$counter.'_sell' ;
	  $quant='e_quant'.$counter;
	  // if it is exist
	  if ( ! isset(${$id})) 
	    return "";
	  // check that something is sold
	  if ( ${'e_march'.$counter.'_sell'} == 0 || ${'e_quant'.$counter} == 0 )
	    return "";
	  /*!\todo verify that price and quant are numeric
	   */
	  /*!\todo carefull round problem ?
	   */
	  $r=${$id}*${$quant}; 
	  $tva=GetTvaRate($this->db,${'e_march'.$counter.'_tva_id'});
	  // if there is no vat we return now
	  if ( $tva == null || $tva == 0 ) return $r;
	  // we compute with the vat included
	  $r=$r+$r*$tva['tva_rate'];
	  $r=round($r,2);
	  break;
	case 'TOTAL_VEN_HTVA':
	  extract($_POST);
	  echo_debug('class_document',__LINE__,'TOTAL_VEN_TVA item :'.$nb_item);

	  $sum=0.0;
	  for ($i=0;$i<$nb_item;$i++)
	    {
	      $sell='e_march'.$i.'_sell';
	      $qt='e_quant'.$i;
	      echo_debug('class_document',__LINE__,'sell :'.$sell.' qt = '.$qt);

	      echo_debug('class_document',__LINE__,'counter :'.$i.' sur '.$nb_item);
	      if ( ! isset (${$sell}) ) break;
	      echo_debug('class_document',__LINE__,'sell :'.${$sell}.' qt = '.${$qt});

	      if ( strlen(trim(${$sell})) == 0 ||
		   strlen(trim(${$qt})) == 0 ||
		   ${$qt}==0 || ${$sell}==0)
		continue;
	      $sum+=${$sell}*${$qt};
	      echo_debug('class_document',__LINE__,'sum :'.$sum);

	    }
	  $r=round($sum,2);
	  break;
	case 'TOTAL_VEN_TVAC':
	  extract($_POST);
	  $sum=0.0;
	  for ($i=0;$i<$nb_item;$i++)
	    {
	      $tva=GetTvaRate($this->db,${'e_march'.$i.'_tva_id'});
	      $tva_rate=( $tva == null || $tva == 0 )?0.0:$tva['tva_rate'];
	      echo_debug('class_document',__LINE__,' :'.$i.' sur '.$nb_item);
	      $sell=${'e_march'.$i.'_sell'};
	      $qt=${'e_quant'.$i};
	      echo_debug('class_document',__LINE__,'sell :'.$sell.' qt = '.$qt);

	      $sum+=$sell*$qt*(1+$tva_rate);
	      //	      $sum+=${'e_march'.$i.'_sell'}*${'e_quant'.$i}*(1+$tva_rate);
	    }
	  $r=round($sum,2);

	  break;

	}
      return $r;
    }
  /*!\brief remove a row from the table document, the lob object is not deleted
   *        because can be linked elsewhere
   */
  function remove()
    {
      $sql='delete from document where d_id='.$this->d_id;
      ExecSql($this->db,$sql);
    }
  /*!\brief Move a document from the table document into the concerned row
   *        the document is not copied : it is only a link
   *
   * \param $p_internal internal code
   *
   */
  function MoveDocumentPj($p_internal)
    {

      $sql=sprintf("update jrn set jr_pj=%s,jr_pj_name='%s',jr_pj_type='%s' where jr_internal='%s'",
		   $this->d_lob,$this->d_filename,$this->d_mimetype,$p_internal);
      ExecSql($this->db,$sql);
      // clean the table document
      //$this->remove();
    }


}
