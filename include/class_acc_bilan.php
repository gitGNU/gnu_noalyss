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
 */

/*! \brief this class handle the different bilan, from the table bilan
 *
 */
require_once ('postgres.php');
require_once ('class_widget.php');
require_once ('class_dossier.php');
require_once ('impress_inc.php');
require_once ('header_print.php');

class Acc_Bilan {
  var $db;						/*!< database connection */
  var $b_id;					/*!< id of the bilan (bilan.b_id) */
  var $from;					/*!< from periode */
  var $to;					/*!< end periode */

  function Acc_Bilan($p_cn) {
	$this->db=$p_cn;
  }
/*! 
 * \brief return a string with the form for selecting the periode and
 * the type of bilan
 * \param $p_filter_year filter on a year
 *
 * \return a string
 */
  function display_form($p_filter_year="") {
	$r="";
	$r.=dossier::hidden();
	$r.= '<TABLE>';

	$r.='<TR>';
// filter on the current year
	$w=new widget("select");
	$w->table=1;

	$periode_start=make_array($this->db,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $p_filter_year order by p_start,p_end");
	
	$periode_end=make_array($this->db,"select p_id,to_char(p_end,'DD-MM-YYYY') from parm_periode $p_filter_year order by p_start,p_end");

	$w->label="Depuis";
	$w->value=$this->from;
	$w->selected=$this->from;
	$r.= $w->IOValue('from_periode',$periode_start);
	$w->label=" jusque ";
	$w->value=$this->to;
	$w->selected=$this->to;
	$r.= $w->IOValue('to_periode',$periode_end);
	$r.= "</TR>";
	$r.="<tr>";
	$mod=new widget('select');
	$mod->table=1;
	$mod->value=make_array($this->db,"select b_id, b_name from bilan order by b_name");
	$mod->label="Choix du bilan";
	$r.=$mod->IOValue('b_id');
	$r.="</tr>";
	$r.= '</TABLE>';
	$r.= $w->Submit('result','Impression');
	return $r;
  }
/*! 
 * \brief get data from the $_GET
 *
 */
  function get_request_get() {
	$this->id=(isset($_GET['b_id']))?$_GET['b_id']:"";
	$this->from=( isset ($_GET['from_periode']))?$_GET['from_periode']:-1;
	$this->to=( isset ($_GET['to_periode']))?$_GET['to_periode']:-1;

  }
  /*!\brief load from the database the document data  */
  function load(){
	try {
	  if ( $this->id=="") 
		throw new Exception("le formulaire id n'est pas donnée");
	  
	  $sql="select b_name,b_file_template,b_file_form,lower(b_type) as b_type from bilan where".
		" b_id = ".$this->id;
	  $res=ExecSql($this->db,$sql);
	  
	  if ( pg_NumRows($res)==0)
		throw new Exception ('Aucun enregistrement trouve');
	  $array=pg_fetch_array($res,0);
	  foreach ($array as $name=>$value)
		$this->$name=$value;
	  
	}catch(Exception $Ex) {
	  echo $Ex->getMessage();
	  exit();
	}
  }
  /*!\brief open the file of the form */
  /*\return an handle to this file */
  function file_open_form()
  {
	$form=fopen($this->b_file_form,'r');
	if ( $form == false) {
	  echo 'Cannot Open';
	  exit();
	}
	return $form;
  }
  /*!\brief open the file with the template */
  /*\return an handle to this file */
  function file_open_template()
  {
	$templ=fopen($this->b_file_template,'r');
	if ( $templ == false) {
	  echo 'Cannot Open';
	  exit();
	}
	return $templ;

  }
/*! 
 * \brief Compute all the formula
 * \param $p_handle the handle to the file
 * \param
 * \param
 * 
 *
 * \return
 */
  function compute_formula($p_handle) {
	while (! feof ($p_handle)) {
	  $buffer=trim(fgets($p_handle));
	  echo_debug(__FILE__.":".__LINE__." get buffer = ",$buffer);
	  // $a=(CheckFormula($buffer)  == true)?"$buffer ok<br>":'<font color="red">'.'Pas ok '.$buffer."</font><br>";
	  // echo $a;
	  // blank line are skipped
	  if (strlen(trim($buffer))==0) 
		continue;
	  // skip comment
	   if ( strpos($buffer,'#') === true )  
		 continue; 
	  // buffer contains a formula A$=....
	  // We need to eval it 
	  //ereg("\\$[a-zA-Z]+[0-9]*=",$buffer,$e);
	  //  echo $e[0];
	  //echo "<br>".$form;
	  $a=ParseFormula($this->db,"$buffer",$buffer,$this->from,$this->to,false);
	  echo_debug(__FILE__.":".__LINE__."  evaluate \$a",$a);
	  $b=str_replace("$","\$this->",$a);
	  echo_debug(__FILE__.":".__LINE__."  evaluate \$b",$b);
	  
	  eval("$b;");
	  
	}// end read form line per line
	echo_debug(__FILE__.':'.__LINE__," End of reading form");
  }
  /*!\brief generate the ods document 
  * \param the handle to the template file 
  * \return the xml 
  */
  function generate_odt()
 {
   // create a temp directory in /tmp to unpack file and to parse it
   $dirname=tempnam($_ENV['TMP'],'bilan_');
   
   
   unlink($dirname);
   mkdir ($dirname);
   chdir($dirname);

   echo_debug(__FILE__.':'.__LINE__.'- ','dirname is ',$dirname);
   $file_base=dirname($_SERVER['SCRIPT_FILENAME']).DIRECTORY_SEPARATOR.$this->b_file_template;
   echo_debug(__FILE__.':'.__LINE__.'-','$file_base',$file_base);
   echo_debug(__FILE__.':'.__LINE__.'- ','this is ',$this->b_file_template);
   $work_file=basename($file_base);
   if ( copy ($file_base,$work_file) == false )
	 {
	   echo "Je ne peux pas ouvrir ce fichier ";
	   exit();
	 }
   ob_start();
   system('unzip "'.$work_file.'"');
   ob_end_clean();
   unlink($work_file);
   // remove the zip file
   $p_file=fopen('content.xml','r');

   if ( $p_file == false) {
	  echo 'Cannot Open';
	  exit();
	}

	$r="";
	$regex="&lt;&lt;\\$[A-Z]*[0-9]*&gt;&gt;";
	$lt="&lt;";
	$gt="&gt;";
	while ( !feof($p_file) ) {
	  echo_debug(__FILE__.':'.__LINE__.'- ','read line OD FILE');
	  $line_rtf=fgets($p_file);
	  //	  echo_debug(__FILE__.':'.__LINE__.'-','$line_rtf',$line_rtf);
	  if ( ereg('&lt;&lt;header&gt;&gt;',$line_rtf) ) {
		// Create the header
		$line_rtf=str_replace('&lt;&lt;header&gt;&gt;',header_txt($this->db),$line_rtf);
		$r.=$line_rtf;
		echo_debug(__FILE__.':'.__LINE__.'- ','Header : ');
		continue;
	  }
	  //	  echo_debug(__FILE__.':'.__LINE__.'- ','$r',$r);
	  // the line contains the magic <<
	  $tmp="";
	  while (ereg($regex,$line_rtf,$f2) == true) {
		// DEBUG
		//    echo $r.'<br>';
		echo_debug(__FILE__.':'.__LINE__.'- ','Pattern found',$f2);
		// the f2 array contains all the magic << in the line
		foreach ($f2 as $f2_str) {
		  echo_debug(__FILE__.':'.__LINE__.'- ','f2_str',$f2_str);
		  // DEBUG
		  // echo "single_f2 = $f2_str <br>";
		  // replace single_f2 by its value
		  $to_remove=$f2_str;
		  $f2_value=str_replace("&lt;","",$f2_str);
		  $f2_value=str_replace("&gt;","",$f2_value);
		  $f2_value=str_replace("$","",$f2_value);
		  // DEBUG
		  //echo "f2_value=$f2_value";
		  //		  $a=${"$f2_value"};
		  $a=$this->$f2_value;
		  // DEBUG      echo " a = $a";
		  $line_rtf=str_replace($f2_str,$a,$line_rtf);

		}// foreach end
	  }
	  $r.=$line_rtf;
		
	}// rtf file is read
	// DEBUG
	//  fwrite($out,$r);
	//	echo $r;
	return $r;
	  
	  

	
  }

/*! 
 * \brief generate the plain  file (rtf,txt, or html)
 * \param the handle to the template file
 */
  function generate_plain($p_file) {
	$r="";
	if ( $this->b_type=='html') {
	  $lt='&lt;';$gt='&gt;';
	} else {
	  $lt='<';$gt='>';
	}

	while ( !feof($p_file) ) {
	  $line_rtf=fgets($p_file);
	  echo_debug(__FILE__.':'.__LINE__.'-','$line_rtf',$line_rtf);
	  if ( ereg($lt.$lt.'header'.$gt.$gt,$line_rtf) ) {
		// Create the header
		$line_rtf=str_replace($lt.$lt.'header'.$gt.$gt,header_txt($this->db),$line_rtf);
		$r.=$line_rtf;
		continue;
	  }
	  echo_debug(__FILE__.':'.__LINE__.'- ','$r',$r);
	  // the line contains the magic <<
	  if (ereg($lt.$lt."\\$[a-zA-Z]*[0-9]*".$gt.$gt,$line_rtf,$f2) == true) {
		// DEBUG
		//    echo $r.'<br>';
		echo_debug(__FILE__.':'.__LINE__.'- ','Pattern found',$f2);
		// the f2 array contains all the magic << in the line
		foreach ($f2 as $f2_str) {
		  // DEBUG
		  // echo "single_f2 = $f2_str <br>";
		  // replace single_f2 by its value
		  $f2_value=str_replace($lt,"",$f2_str);
		  $f2_value=str_replace($gt,"",$f2_value);
		  $f2_value=str_replace("$","",$f2_value);
		  // DEBUG
		  //echo "f2_value=$f2_value";
		  //		  $a=${"$f2_value"};
		  $a=$this->$f2_value;
		  // DEBUG      echo " a = $a";
		  $line_rtf=str_replace($f2_str,$a,$line_rtf);
		  
		}// foreach end
	  }
	  $r.=$line_rtf;
		
	}// rtf file is read
	// DEBUG
	//  fwrite($out,$r);
	return $r;
	  
	  

	
  }
  /*!\brief generate the document and send it to the browser
   */
  function generate() {
	// Load the data
	$this->load();
	// Open the files
	$form=$this->file_open_form();

	// Compute all the formula and add the value to this
	$this->compute_formula($form);
	fclose($form);
	// open the form
	$templ=$this->file_open_template();
	switch ($this->b_type) {
	case 'rtf':
	  $result=$this->generate_plain($templ);
	  $this->send($result);
	  break;
	case 'txt':
	  $result=$this->generate_plain($templ);
	  $this->send($result);
	case 'html':
	  $result=$this->generate_plain($templ);
	  $this->send($result);

	  break;
	case 'odt':
	case 'ods':
	  $result=$this->generate_odt($templ);
	  
	  $this->send($result);
	  break;

	}
	fclose($templ);
  }
  /*!\brief send the result of generate plain to the browser
   * \param $p_result is the string returned by generate_...
   */
  function send($p_result) {
	switch ($this->b_type) {
	case 'rtf':
	  // A rtf file is generated
	  header('Content-type: application/rtf');
	  header('Content-Disposition: attachment; filename="'.$this->b_name.'.rtf"');
	  echo $p_result;
	  break;

	case 'txt':
	  // A txt file is generated
	  header('Content-type: application/txt');
	  header('Content-Disposition: attachment; filename="'.$this->b_name.'.txt"');

	  echo $p_result;
	  break;
	case 'html':
	  // A txt file is generated
	  header('Content-type: application/html');
	  header('Content-Disposition: attachment; filename="'.$this->b_name.'.html"');

	  echo $p_result;
	  break;
	case 'odt':
	case 'ods':
	  /*	  header("Pragma: public");
      header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
      header("Cache-Control: must-revalidate");
      header('Content-type: "application/vnd.oasis.opendocument.text"');
      header('Content-Disposition: attachment;filename="'.$this->b_name.'.odt"',FALSE);
      header("Accept-Ranges: bytes");
	  */
	  ob_start();
	  // save the file in a temp folder
	  // create a temp directory in /tmp to unpack file and to parse it
	  $dirname=tempnam($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'tmp','bilan_');
	  
	  
	  unlink($dirname);
	  mkdir ($dirname);
	  chdir($dirname);
	  echo_debug(__FILE__.':'.__LINE__.'- ','dirname is ',$dirname);
	  // create a temp directory in /tmp to unpack file and to parse it
	  $file_base=dirname($_SERVER['SCRIPT_FILENAME']).DIRECTORY_SEPARATOR.$this->b_file_template;
	  echo_debug(__FILE__.':'.__LINE__.'-','$file_base',$file_base);
	  echo_debug(__FILE__.':'.__LINE__.'- ','this is ',$this->b_file_template);
	  $work_file=basename($file_base);
	  if ( copy ($file_base,$work_file) == false )
		{
		  echo "Je ne peux pas ouvrir ce fichier ";
		  echo_debug(__FILE__.':'.__LINE__.'- Je ne peux pas ouvrir ce fichier ');
		  exit();
		}
	  system('unzip "'.$work_file.'"');
	  // remove the zip file
	  unlink ($work_file);


	  // replace the file
	  $p_file=fopen($dirname.DIRECTORY_SEPARATOR.'content.xml','wb');
	  if ( $p_file == false ){
		echo_debug(__FILE__.':'.__LINE__.'- Je ne peux pas ouvrir content.xml ');
		exit('Je ne peux pas ouvrir content.xml');

	  }
	  $a=fwrite($p_file,$p_result);
	  if ( $a==false) {
		echo "Je ne peux pas ecrire dans content.xml";
		echo_debug(__FILE__.':'.__LINE__.'- Je ne peux pas ecrire dans content.xml ');
		exit();
	  }
	  // repack
	  system ('zip -r "'.$this->b_name.'.'.$this->b_type.'" *');
	  ob_clean();
	  fclose($p_file);
	  echo_debug(__FILE__.':'.__LINE__.'- ','Send the file');
	  $fdoc=fopen($dirname.DIRECTORY_SEPARATOR.$this->b_name.'.'.$this->b_type,'r');
	  if ( $fdoc == false ){
		echo_debug(__FILE__.':'.__LINE__.'- Je ne peux pas ouvrir ce document');
		exit('Je ne peux pas ouvrir ce document');
	  }
	  $buffer=fread ($fdoc,filesize($dirname.DIRECTORY_SEPARATOR.$this->b_name.'.'.$this->b_type));
	  echo $buffer;

	  break;
	  // and send
	}

  }
  static function test_me() {

	if ( isset($_GET['result'])) {
	  ob_start();
	  $cn=DbConnect(dossier::id());
	  $a=new Acc_Bilan($cn);
	  $a->get_request_get();

	  $a->load();
	  $form=$a->file_open_form();
	  $a->compute_formula($form);
	  fclose($form);
	// open the form
	  $templ=$a->file_open_template();
	  $r=$a->generate_odt($templ);
	  fclose($templ);
	  ob_clean();

	  //	  echo_debug(__FILE__.':'.__LINE__,"resutl ",$r);
	  $a->send($r);
	}
	else {
	$cn=DbConnect(dossier::id());
	$a=new Acc_Bilan($cn);
	$a->get_request_get();

	echo '<form method="get">';
	echo $a->display_form();
	echo '</form>';
	}
  }
}

