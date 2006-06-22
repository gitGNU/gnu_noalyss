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
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/*! \file
 * \brief Class invoice to manage the customer invoice 
 * \todo obsolete ? then delete it
 */
/*! 
 * \brief Class invoice to manage the customer invoice 
 * 
 */

class Invoice {
  var $cn;          /*! \enum $cn database conx */
  var $id;	    /*! \enum  $id iv_id */
  var $template;    /*! \enum $template template's name */

	//Constructor parameter = database connexion
	function Invoice($p_cn) {
		$this->cn=$p_cn;	
	}
	// show the stored invoice template
 	function myList() { 
 		$sql="select iv_id,iv_name from invoice";
 		$Res=ExecSql($this->cn,$sql);
 		$all=pg_fetch_all($Res);
		if ( pg_NumRows($Res) == 0 ) return "";
 		foreach ( $all as $row) {
 			var_dump($row);
 		} 
 	}
	// Save a template in the database
	function Save() {
	// Values are in $_POST
	StartSql($this->cn);
	// Save data into the table invoice

	// Save the file
	$new_name=tempnam('/tmp','invoice_');
	echo_debug('class_invoice.php',__LINE__,"new name=".$new_name);
	if ( strlen ($_FILES['pj']['tmp_name']) != 0 ) 
		{
		if (move_uploaded_file($_FILES['invoice']['tmp_name'],
					$new_name)) 
			{
			// echo "Image saved";
			$oid= pg_lo_import($this->cn,$new_name);
			if ( $oid == false ) 
			{
			echo_error('class_invoice.php',__LINE__,"cannot upload document");
			Rollback($cn);
			return;
			}
			echo_debug('class_invoice.php',__LINE__,"Loading document");
			// Remove old document
			$ret=ExecSql($this->cn,"select iv_file from invoice where iv_id=".$this->id);
			if (pg_num_rows($ret) != 0) 
			{
				$r=pg_fetch_array($ret,0);
				$old_oid=$r['iv_file'];
				if (strlen($old_oid) != 0) 
					pg_lo_unlink($this->cn,$old_oid);
			}
				// Load new document
				ExecSql($this->cn,"update invoice set iv_file where iv_id=".$this->id);
		}
		else 
		{
			echo "<H1>Error</H1>";
			Rollback($this->cn);
			exit;
		}
	}
	}
	// Remove a template 
	function Delete() {
	}
	// return the string containing the form for
	// adding a template
	function FormAdd() {
		$r="";
		$r='<FORM METHOD="POST" ACTION="dossier_prefs.php">';
		$r.="</FORM>";
		return $r;
	}
	// return the string containing the form for
	// removing a template
	function FormDelete() {
	}
	// Parse and remplace the tag in the invoice 
	// By the value found in quant_sold
	// p_id is the id of the invoice
	// p_internal reference (quant_sold qs_internal)
	function Get($p_id,$p_internal) 
	{
	// retrieve info from the table invoice
		
	// retrieve the template and generate document
		StartSql($this->cn);
		$ret=ExecSql($cn,"select iv_name,iv_file from invoice where iv_id".$this->iv_id);
		if ( pg_num_rows ($ret) == 0 )
			return;
		$row=pg_fetch_array($ret,0);
		//the template is saved into file $tmp
		$tmp=tempnam('/tmp/','invoice_');
		pg_lo_export($cn,$row['iv_file'],$tmp);
		// Parse the file 




		// send it to stdout
		ini_set('zlib.output_compression','Off');
		header("Pragma: public");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate");
		header('Content-type: rtf/x-application');
		header('Content-Disposition: attachment;filename="invoice'.$p_internal.'.rtf"',FALSE);
		header("Accept-Ranges: bytes");
		$file=fopen($tmp,'r');
		while ( !feof ($file) )
		{
			echo fread($file,8192);
		}
		fclose($file);
		
		unlink ($tmp);
		
		Commit($cn);

	}
}
?>