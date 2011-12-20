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
require_once('class_acc_account_ledger.php');
require_once('class_follow_up.php');
require_once('class_acc_tva.php');
require_once('class_user.php');
require_once('class_zip_extended.php');

/*! \file
 * \brief Class Document corresponds to the table document
 */
/*! \brief Class Document corresponds to the table document
 */
class Document
{
    var $db;          /*!< $db Database connexion*/
    var $d_id;        /*!< $d_id Document id */
    var $ag_id;       /*!< $ag_id action_gestion.ag_id (pk) */
    var $d_mimetype;  /*!< $d_mimetype  */
    var $d_filename;  /*!< $d_filename */
    var $d_lob;       /*!< $d_lob the oid of the lob */
    var $d_number;    /*!< $d_number number of the document */
    var $md_id;       /*!< $md_id document's template */
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
        $this->d_id=$this->db->get_next_seq("document_d_id_seq");
        // affect a number
        $this->d_number=$this->db->get_next_seq("seq_doc_type_".$this->md_type);
        $sql=sprintf('insert into document(d_id,ag_id,d_number) values(%d,%d,%d)',
                     $this->d_id,
                     $this->ag_id,
                     $this->d_number);
        $this->db->exec_sql($sql);

    }

    /*!
     * \brief Generate the document, Call $this-\>Replace to replace
     *        tag by value
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
        // Retrieve the lob and save it into $dirname
        $this->db->start();
        $dm_info="select md_name,md_type,md_lob,md_filename,md_mimetype
                 from document_modele where md_id=".$this->md_id;
        $Res=$this->db->exec_sql($dm_info);

        $row=Database::fetch_array($Res,0);
        $this->d_lob=$row['md_lob'];
        $this->d_filename=$row['md_filename'];
        $this->d_mimetype=$row['md_mimetype'];
        $this->d_name=$row['md_name'];


        chdir($dirname);
        $filename=$row['md_filename'];
        $exp=$this->db->lo_export($row['md_lob'],$dirname.DIRECTORY_SEPARATOR.$filename);
        if ( $exp === false ) echo_warning( __FILE__.":".__LINE__."Export NOK $filename");

        $type="n";
        // if the doc is a OOo, we need to unzip it first
        // and the name of the file to change is always content.xml
        if ( strpos($row['md_mimetype'],'vnd.oasis') != 0 )
        {
            ob_start();
	    $zip = new Zip_Extended;
	    if ($zip->open($filename) === TRUE) {
	      $zip->extractTo($dirname.DIRECTORY_SEPARATOR);
	      $zip->close();
	    } else {
	      echo __FILE__.":".__LINE__."cannot unzip model ".$filename;	
	    }

            // Remove the file we do  not need anymore
            unlink($filename);
            ob_end_clean();
            $file_to_parse="content.xml";
            $type="OOo";
        }
        else
            $file_to_parse=$filename;
        // affect a number
        $this->d_number=$this->db->get_next_seq("seq_doc_type_".$row['md_type']);

        // parse the document - return the doc number ?
        $this->ParseDocument($dirname,$file_to_parse,$type);

        $this->db->commit();
        // if the doc is a OOo, we need to re-zip it
        if ( strpos($row['md_mimetype'],'vnd.oasis') != 0 )
        {
            ob_start();
	    $zip = new Zip_Extended;
            $res = $zip->open($filename, ZipArchive::CREATE);
            if($res !== TRUE)
	      {
		echo __FILE__.":".__LINE__."cannot recreate zip";
		exit;
	      }
	    $zip->add_recurse_folder($dirname.DIRECTORY_SEPARATOR);
	    $zip->close();

            ob_end_clean();

            $file_to_parse=$filename;
        }

        $this->SaveGenerated($dirname.DIRECTORY_SEPARATOR.$file_to_parse);
        // Invoice
        $ret='<A class="mtitle" HREF="show_document.php?d_id='.$this->d_id.'&'.dossier::get().'">Document g&eacute;n&eacute;r&eacute;</A>';
        @rmdir($dirname);
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

        /*!\note Replace in the doc the tags by their values.
         *  - MY_*   table parameter
         *  - ART_VEN* table quant_sold for invoice
         *  - CUST_* table quant_sold and fiche for invoice
         *  - e_* for the invoice in the $_POST 
         */
        // open the document
        $infile_name=$p_dir.DIRECTORY_SEPARATOR.$p_file;
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
        if (  $h === false )
        {
            echo __FILE__.":".__LINE__."cannot open $p_dir $p_file ";
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
            $regex="/=*&lt;&lt;[A-Z]+_*[A-Z]*_*[A-Z]*_*[A-Z]*_*[0-9]*&gt;&gt;/i";
            $lt="&lt;";
            $gt="&gt;";
        }
        else
        {
            $regex="/=*<<[A-Z]+_*[A-Z]*_*[A-Z]*_*[A-Z]*_*[0-9]*>>/i";
            $lt="<";
            $gt=">";
        }

        //read the file
        while(! feof($h))
	  {
            // replace the tag
            $buffer=fgets($h);
            // search in the buffer the magic << and >>
            // while preg_match_all finds something to replace
            while ( preg_match_all ($regex,$buffer,$f) >0  )
	      {

			    
                foreach ( $f as $apattern )
		  {


		    foreach($apattern as $pattern)
		      {


			$to_remove=$pattern;
			// we remove the < and > from the pattern
			$pattern=str_replace($lt,'',$pattern);
			$pattern=str_replace($gt,'',$pattern);


			// if the pattern if found we replace it
			$value=$this->Replace($pattern);
			if ( strpos($value,'ERROR') != false ) 		  $value="";
			// replace into the $buffer
			// take the position in the buffer
			$pos=strpos($buffer,$to_remove);
			// get the length of the string to remove
			$len=strlen($to_remove);
			if ( $p_type=='OOo' )
			  {
			    $value=str_replace('&','&amp;',$value);
			    $value=str_replace('<','&lt;',$value);
			    $value=str_replace('>','&gt;',$value);
			    $value=str_replace('"','&quot;',$value);
			    $value=str_replace("'",'&apos;',$value);
			  }
			$buffer=substr_replace($buffer,$value,$pos,$len);

			// if the pattern if found we replace it
		      }
		  }
	      }
            // write into the output_file
            fwrite($output_file,$buffer);

	  }
        fclose($h);
        fclose($output_file);
        if ( ($ret=copy ($output_name,$infile_name)) == FALSE )
        {
            echo _('Ne peut pas sauver '.$output_name.' vers '.$infile_name.' code d\'erreur ='.$ret);
        }


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
        // We save the generated file
        $doc=new Document($this->db);
        $this->db->start();
        $this->d_lob=$this->db->lo_import($p_file);
        if ( $this->d_lob == false )
        {
            echo "ne peut pas importer [$p_file]";
            return 1;
        }

        $sql="insert into document(ag_id,d_lob,d_number,d_filename,d_mimetype)
             values ($1,$2,$3,$4,$5)";

        $this->db->exec_sql($sql,      array($this->ag_id,
                                             $this->d_lob,
                                             $this->d_number,
                                             $this->d_filename,
                                             $this->d_mimetype));
        $this->d_id=$this->db->get_current_seq("document_d_id_seq");
        // Clean the file
        unlink ($p_file);
        $this->db->commit();
        return 0;
    }
    /*! Upload
     * \brief Upload a file into document 
     *  all the needed data are in $_FILES we don't increment the seq
     * \param $p_file : array containing by default $_FILES
     *
     * \return
     */
    function Upload($p_ag_id)
    {
        // nothing to save
        if ( sizeof($_FILES) == 0 ) return;

        /* for several files  */
        /* $_FILES is now an array */
        // Start Transaction
        $this->db->start();
        $name=$_FILES['file_upload']['name'];
        for ($i = 0; $i < sizeof($name);$i++)
        {
            $new_name=tempnam($_ENV['TMP'],'doc_');
            // check if a file is submitted
            if ( strlen($_FILES['file_upload']['tmp_name'][$i]) != 0 )
            {
                // upload the file and move it to temp directory
                if ( move_uploaded_file($_FILES['file_upload']['tmp_name'][$i],$new_name))
                {
                    $oid=$this->db->lo_import($new_name);
                    // check if the lob is in the database
                    if ( $oid == false )
                    {
                        $this->db->rollback();
                        return 1;
                    }
                }
                // the upload in the database is successfull
                $this->d_lob=$oid;
                $this->d_filename=$_FILES['file_upload']['name'][$i];
                $this->d_mimetype=$_FILES['file_upload']['type'][$i];

                // insert into  the table
                $sql="insert into document (ag_id, d_lob,d_filename,d_mimetype,d_number) values ($1,$2,$3,$4,5)";
                $this->db->exec_sql($sql,array($p_ag_id,$this->d_lob,$this->d_filename,$this->d_mimetype));
            }
        } /* end for */
        $this->db->commit();

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
        $r='<A class="mtitle" HREF="show_document.php?d_id='.$this->d_id.'&'.dossier::get().'">'.$image.'</A>';
        return $r;
    }
    /* ! Get
     * \brief Send the document
     */
    function Send()
    {
        // retrieve the template and generate document
        $this->db->start();
        $ret=$this->db->exec_sql(
                 "select d_id,d_lob,d_filename,d_mimetype from document where d_id=".$this->d_id );
        if ( Database::num_row ($ret) == 0 )
            return;
        $row=Database::fetch_array($ret,0);
        //the document  is saved into file $tmp
        $tmp=tempnam($_ENV['TMP'],'document_');
        $this->db->lo_export($row['d_lob'],$tmp);
        $this->d_mimetype=$row['d_mimetype'];
        $this->d_filename=$row['d_filename'];

        // send it to stdout
        ini_set('zlib.output_compression','Off');
        header("Pragma: public");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate");
        header('Content-type: '.$this->d_mimetype);
        header('Content-Disposition: attachment;filename="'.$this->d_filename.'"',FALSE);
        header("Accept-Ranges: bytes");
        $file=fopen($tmp,'r');
        while ( !feof ($file) )
        {
            echo fread($file,8192);
        }
        fclose($file);

        unlink ($tmp);

        $this->db->commit();

    }
    /*!\brief get all the document of a given action
     *\param $ag_id the ag_id from action::ag_id (primary key)
     *\return an array of objects document or an empty array if nothing found
     */
    function get_all($ag_id)
    {
        $res=$this->db->get_array('select d_id, ag_id, d_lob, d_number, d_filename,'.
                                  ' d_mimetype from document where ag_id=$1',array($ag_id));
        $a=array();
        for ($i=0;$i<sizeof($res); $i++ )
        {
            $doc=new Document($this->db);
            $doc->d_id=$res[$i]['d_id'];
            $doc->ag_id=$res[$i]['ag_id'];
            $doc->d_lob=$res[$i]['d_lob'];
            $doc->d_number=$res[$i]['d_number'];
            $doc->d_filename=$res[$i]['d_filename'];
            $doc->d_mimetype=$res[$i]['d_mimetype'];
            $a[$i]=clone $doc;
        }
        return $a;
    }

    /*!\brief Get  complete all the data member thx info from the database
     */
    function get()
    {
        $sql="select * from document where d_id=".$this->d_id;
        $ret=$this->db->exec_sql($sql);
        if ( Database::num_row($ret) == 0 )
            return;
        $row=Database::fetch_array($ret,0);
        $this->ag_id=$row['ag_id'];
        $this->d_mimetype=$row['d_mimetype'];
        $this->d_filename=$row['d_filename'];
        $this->d_lob=$row['d_lob'];
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
     *  - [CUST_CITY] customer's city
     *  - [CUST_VAT] customer's VAT
     *  - [MARCH_NEXT]   end this item and increment the counter $i
     *  - [DATE_LIMIT] 
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
     *  - [NUMBER]
     *  - [MY_NAME]
     *  - [MY_CP]
     *  - [MY_COMMUNE]
     *  - [MY_TVA]
     *  - [MY_STREET]
     *  - [MY_NUMBER]
     *  - [TVA_CODE]
     *  - [TVA_RATE]
     *  - [BON_COMMANDE]
     *  - [OTHER_INFO]
     *  - [CUST_NUM]
     *  - [CUST_BANQUE_NAME]
     *  - [CUST_BANQUE_NO]
     *  - [USER]
     *  - [REFERENCE]
     *  - [BENEF_NAME]
     *  - [BENEF_BANQUE_NAME]
     *  - [BENEF_BANQUE_NO]
     *  - [BENEF_ADDR_1]
     *  - [BENEF_CP]
     *  - [BENEF_CO]
     *  - [BENEF_CITY]
     *  - [BENEF_VAT]
     *
     * \param $p_tag TAG
     * \return String which must replace the tag
     */
    function Replace($p_tag)
    {
        $p_tag=strtoupper($p_tag);
        $p_tag=str_replace('=','',$p_tag);
        $r="Tag inconnu";
        static $counter=0;
        switch ($p_tag)
        {
        case 'DATE':
                $r=' Date inconnue ';
            // Date are in $_REQUEST['ag_date']
            // or $_POST['e_date']
            if ( isset ($_REQUEST['ag_timestamp']))
                $r=$_REQUEST['ag_timestamp'];
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
            $tiers=new Fiche($this->db);
            $qcode=isset($_REQUEST['qcode_dest'])?$_REQUEST['qcode_dest']:$_REQUEST['e_client'];
            $tiers->get_by_qcode($qcode,false);
            $p=$tiers->strAttribut(ATTR_DEF_ACCOUNT);
            $poste=new Acc_Account_Ledger($this->db,$p);
            $r=$poste->get_solde(' true' );
            break;
        case 'CUST_NAME':
            $tiers=new Fiche($this->db);
            $qcode=isset($_REQUEST['qcode_dest'])?$_REQUEST['qcode_dest']:$_REQUEST['e_client'];
            $tiers->get_by_qcode($qcode,false);
            $r=$tiers->strAttribut(ATTR_DEF_NAME);
            break;
        case 'CUST_ADDR_1':
            $tiers=new Fiche($this->db);
            $qcode=isset($_REQUEST['qcode_dest'])?$_REQUEST['qcode_dest']:$_REQUEST['e_client'];
            $tiers->get_by_qcode($qcode,false);
            $r=$tiers->strAttribut(ATTR_DEF_ADRESS);

            break ;
        case 'CUST_CP':
            $tiers=new Fiche($this->db);

            $qcode=isset($_REQUEST['qcode_dest'])?$_REQUEST['qcode_dest']:$_REQUEST['e_client'];
            $tiers->get_by_qcode($qcode,false);
            $r=$tiers->strAttribut(ATTR_DEF_CP);

            break;
        case 'CUST_CITY':
            $tiers=new Fiche($this->db);

            $qcode=isset($_REQUEST['qcode_dest'])?$_REQUEST['qcode_dest']:$_REQUEST['e_client'];
            $tiers->get_by_qcode($qcode,false);
            $r=$tiers->strAttribut(ATTR_DEF_CITY);

            break;

        case 'CUST_CO':
            $tiers=new Fiche($this->db);

            $qcode=isset($_REQUEST['qcode_dest'])?$_REQUEST['qcode_dest']:$_REQUEST['e_client'];
            $tiers->get_by_qcode($qcode,false);
            $r=$tiers->strAttribut(ATTR_DEF_PAYS);

            break;
            // Marchandise in $_POST['e_march*']
            // \see user_form_achat.php or user_form_ven.php
        case 'CUST_VAT':
            $tiers=new Fiche($this->db);

            $qcode=isset($_REQUEST['qcode_dest'])?$_REQUEST['qcode_dest']:$_REQUEST['e_client'];
            $tiers->get_by_qcode($qcode,false);
            $r=$tiers->strAttribut(ATTR_DEF_NUMTVA);
            break;
        case 'CUST_NUM':
            $tiers=new Fiche($this->db);
            $qcode=isset($_REQUEST['qcode_dest'])?$_REQUEST['qcode_dest']:$_REQUEST['e_client'];
            $tiers->get_by_qcode($qcode,false);
            $r=$tiers->strAttribut(ATTR_DEF_NUMBER_CUSTOMER);
            break;
        case 'CUST_BANQUE_NO':
            $tiers=new Fiche($this->db);
            $qcode=isset($_REQUEST['qcode_dest'])?$_REQUEST['qcode_dest']:$_REQUEST['e_client'];
            $tiers->get_by_qcode($qcode,false);
            $r=$tiers->strAttribut(ATTR_DEF_BQ_NO);
            break;
        case 'CUST_BANQUE_NAME':
            $tiers=new Fiche($this->db);
            $qcode=isset($_REQUEST['qcode_dest'])?$_REQUEST['qcode_dest']:$_REQUEST['e_client'];
            $tiers->get_by_qcode($qcode,false);
            $r=$tiers->strAttribut(ATTR_DEF_BQ_NAME);
            break;
            /* -------------------------------------------------------------------------------- */
            /* BENEFIT (fee notes */
        case 'BENEF_NAME':
            $tiers=new Fiche($this->db);
            $qcode=isset($_REQUEST['qcode_benef'])?$_REQUEST['qcode_benef']:'';
            if ( $qcode=='')
            {
                $r='';
                break;
            }
            $tiers->get_by_qcode($qcode,false);
            $r=$tiers->strAttribut(ATTR_DEF_NAME);
            break;
        case 'BENEF_ADDR_1':
            $tiers=new Fiche($this->db);
            $qcode=isset($_REQUEST['qcode_benef'])?$_REQUEST['qcode_benef']:'';
            if ( $qcode=='')
            {
                $r='';
                break;
            }
            $tiers->get_by_qcode($qcode,false);
            $r=$tiers->strAttribut(ATTR_DEF_ADRESS);

            break ;
        case 'BENEF_CP':
            $tiers=new Fiche($this->db);

            $qcode=isset($_REQUEST['qcode_benef'])?$_REQUEST['qcode_benef']:'';
            if ( $qcode=='')
            {
                $r='';
                break;
            }
            $tiers->get_by_qcode($qcode,false);
            $r=$tiers->strAttribut(ATTR_DEF_CP);

            break;
        case 'BENEF_CITY':
            $tiers=new Fiche($this->db);

            $qcode=isset($_REQUEST['qcode_benef'])?$_REQUEST['qcode_benef']:'';
            if ( $qcode=='')
            {
                $r='';
                break;
            }
            $tiers->get_by_qcode($qcode,false);
            $r=$tiers->strAttribut(ATTR_DEF_CITY);

            break;

        case 'BENEF_CO':
            $tiers=new Fiche($this->db);

            $qcode=isset($_REQUEST['qcode_benef'])?$_REQUEST['qcode_benef']:'';
            if ( $qcode=='')
            {
                $r='';
                break;
            }
            $tiers->get_by_qcode($qcode,false);
            $r=$tiers->strAttribut(ATTR_DEF_PAYS);

            break;
            // Marchandise in $_POST['e_march*']
            // \see user_form_achat.php or user_form_ven.php
        case 'BENEF_VAT':
            $tiers=new Fiche($this->db);

            $qcode=isset($_REQUEST['qcode_benef'])?$_REQUEST['qcode_benef']:'';
            if ( $qcode=='')
            {
                $r='';
                break;
            }
            $tiers->get_by_qcode($qcode,false);
            $r=$tiers->strAttribut(ATTR_DEF_NUMTVA);
            break;
        case 'BENEF_NUM':
            $tiers=new Fiche($this->db);
            $qcode=isset($_REQUEST['qcode_benef'])?$_REQUEST['qcode_benef']:'';
            if ( $qcode=='')
            {
                $r='';
                break;
            }
            $tiers->get_by_qcode($qcode,false);
            $r=$tiers->strAttribut(ATTR_DEF_NUMBER_CUSTOMER);
            break;
        case 'BENEF_BANQUE_NO':
            $tiers=new Fiche($this->db);
            $qcode=isset($_REQUEST['qcode_benef'])?$_REQUEST['qcode_benef']:'';
            if ( $qcode=='')
            {
                $r='';
                break;
            }
            $tiers->get_by_qcode($qcode,false);
            $r=$tiers->strAttribut(ATTR_DEF_BQ_NO);
            break;
        case 'BENEF_BANQUE_NAME':
            $tiers=new Fiche($this->db);
            $qcode=isset($_REQUEST['qcode_benef'])?$_REQUEST['qcode_benef']:'';
            if ( $qcode=='')
            {
                $r='';
                break;
            }
            $tiers->get_by_qcode($qcode,false);
            $r=$tiers->strAttribut(ATTR_DEF_BQ_NAME);
            break;

            // Marchandise in $_POST['e_march*']
            // \see user_form_achat.php or user_form_ven.php
        case 'NUMBER':
            $r=$this->d_number;
            break;

        case 'USER' :
            return $_SESSION['use_name'].', '.$_SESSION['use_first_name'];

            break;
        case 'REFERENCE':
            $act=new Follow_Up($this->db);
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
             *  - [DATE_LIMIT]
             */
        case 'DATE_LIMIT':
            extract ($_POST);
            $id='e_ech' ;
            if ( !isset (${$id}) ) return "";
            $r=${$id};
            break;

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
            if ( ${'e_march'.$counter.'_price'} != 0 && ${'e_quant'.$counter} != 0 )
            {
                $f=new Fiche($this->db);
                $f->get_by_qcode(${$id},false);
                $r=$f->strAttribut(ATTR_DEF_NAME);
            }
            else $r = "";
            break;
       case 'VEN_ART_LABEL':
            extract ($_POST);
            $id='e_march'.$counter."_label";
            // check if the march exists
            if ( ! isset (${$id})) return "";
            $r=${'e_march'.$counter.'_label'};
            break;

        case 'VEN_ART_PRICE':
            extract ($_POST);
            $id='e_march'.$counter.'_price' ;
            if ( !isset (${$id}) ) return "";
            $r=${$id};
            break;

        case 'TVA_RATE':
        case 'VEN_ART_TVA_RATE':
            extract ($_POST);
            $id='e_march'.$counter.'_tva_id';
            if ( !isset (${$id}) ) return "";
            if ( ${$id} == -1 || ${$id}=='' ) return "";
            $march_id='e_march'.$counter.'_price' ;
            if ( ! isset (${$march_id})) return '';
            $tva=new Acc_Tva($this->db);
            $tva->set_parameter("id",${$id});
            if ( $tva->load() == -1) return '';
            return $tva->get_parameter("rate");
            break;

        case 'TVA_CODE':
        case 'VEN_ART_TVA_CODE':
            extract ($_POST);
            $id='e_march'.$counter.'_tva_id';
            if ( !isset (${$id}) ) return "";
            if ( ${$id} == -1 ) return "";
            $qt='e_quant'.$counter;
            $price='e_march'.$counter.'_price' ;
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
            $march_id='e_march'.$counter.'_price' ;
            if ( ! isset (${$march_id})) return '';
            if ( ${$march_id} == 0) return '';
            $tva=new Acc_Tva($this->db,${$id});
            if ($tva->load() == -1 ) return "";
            $r=$tva->get_parameter('label');

            break;

            /* total VAT for one sold */
        case 'TVA_AMOUNT':
            extract ($_POST);
            $qt='e_quant'.$counter;
            $price='e_march'.$counter.'_price' ;
            $tva='e_march'.$counter.'_tva_id';
            /* if we do not use vat this var. is not set */
            if ( !isset(${$tva}) ) return '';
            if ( !isset (${'e_march'.$counter}) ) return "";
            // check that something is sold
            if ( ${$price} == 0 || ${$qt} == 0
                    || strlen(trim( $price )) ==0
                    || strlen(trim($qt)) ==0)
                return "";
            $r=${'e_march'.$counter.'_tva_amount'};
            break;
            /* TVA automatically computed */
        case 'VEN_ART_TVA':
            extract ($_POST);
            $qt='e_quant'.$counter;
            $price='e_march'.$counter.'_price' ;
            $tva='e_march'.$counter.'_tva_id';
            if ( !isset (${'e_march'.$counter}) ) return "";
            // check that something is sold
            if ( ${$price} == 0 || ${$qt} == 0
                    || strlen(trim( $price )) ==0
                    || strlen(trim($qt)) ==0)
                return "";
            $oTva=new Acc_Tva($this->db,${$tva});
            if ($oTva->load() == -1 ) return "";
            $r=round(${$price},2)*$oTva->get_parameter('rate');
            $r=round($r,2);
            break;

        case 'VEN_ART_TVAC':
            extract ($_POST);
            $qt='e_quant'.$counter;
            $price='e_march'.$counter.'_price' ;
            $tva='e_march'.$counter.'_tva_id';
            if ( !isset (${'e_march'.$counter}) ) return "";
            // check that something is sold
            if ( ${$price} == 0 || ${$qt} == 0
                    || strlen(trim( $price )) ==0
                    || strlen(trim($qt)) ==0)
                return "";
            if ( ! isset (${$tva}) ) return '';
            $tva=new Acc_Tva($this->db,${$tva});
            if ($tva->load() == -1 )
            {
                $r=round(${$price},2);
            }
            else
            {
                $r=round(${$price}*$tva->get_parameter('rate')+${$price},2);
            }

            break;

        case 'VEN_ART_QUANT':
            extract ($_POST);
            $id='e_quant'.$counter;
            if ( !isset (${$id}) ) return "";
            // check that something is sold
            if ( ${'e_march'.$counter.'_price'} == 0
                    || ${'e_quant'.$counter} == 0
                    || strlen(trim( ${'e_march'.$counter.'_price'} )) ==0
                    || strlen(trim(${'e_quant'.$counter})) ==0 )
                return "";
            $r=${$id};
            break;

        case 'VEN_HTVA':
            extract ($_POST);
            $id='e_march'.$counter.'_price' ;
            $quant='e_quant'.$counter;
            if ( !isset (${$id}) ) return "";

            // check that something is sold
            if ( ${'e_march'.$counter.'_price'} == 0 || ${'e_quant'.$counter} == 0
                    || strlen(trim( ${'e_march'.$counter.'_price'} )) ==0
                    || strlen(trim(${'e_quant'.$counter})) ==0)
                return "";

            $r=round(${$id}*${$quant},2);
            break;

        case 'VEN_TVAC':
            extract ($_POST);
            $id='e_march'.$counter.'_tva_amount' ;
            $price='e_march'.$counter.'_price' ;
            $quant='e_quant'.$counter;
            if ( ! isset(${'e_march'.$counter.'_price'})|| !isset(${'e_quant'.$counter}))
                return "";
            // check that something is sold
            if ( ${'e_march'.$counter.'_price'} == 0 || ${'e_quant'.$counter} == 0 )
                return "";


            // if it is exist
            if ( ! isset(${$id}))
                $r=round(${$price}*${$quant},2);
            else
                $r=round($
                         {
                             $price
                         }
                         *${$quant}+$id,2);
            break;

        case 'TOTAL_VEN_HTVA':
            extract($_POST);

            $sum=0.0;
            for ($i=0;$i<$nb_item;$i++)
            {
                $sell='e_march'.$i.'_price';
                $qt='e_quant'.$i;

                if ( ! isset (${$sell}) ) break;

                if ( strlen(trim(${$sell})) == 0 ||
                        strlen(trim(${$qt})) == 0 ||
                        ${$qt}==0 || ${$sell}==0)
                    continue;
                $sum+=${$sell}*${$qt};
                $sum=round($sum,2);


            }
            $r=round($sum,2);
            break;
        case 'TOTAL_VEN_TVAC':
            extract($_POST);
            $sum=0.0;
            for ($i=0;$i<$nb_item;$i++)
            {
                $tva='e_march'.$i.'_tva_amount';
                $tva_amount=0;
                /* if we do not use vat this var. is not set */
                if ( isset(${$tva}) )
                {
                    $tva_amount=${$tva};
                }
                $sell=${'e_march'.$i.'_price'};
                $qt=${'e_quant'.$i};


                $sum+=$sell*$qt+$tva_amount;
                $sum=round($sum,2);
            }
            $r=round($sum,2);

            break;
        case 'TOTAL_TVA':
            extract($_POST);
            $sum=0.0;
            for ($i=0;$i<$nb_item;$i++)
            {
                $tva='e_march'.$i.'_tva_amount';
                if (! isset(${$tva})) $tva_amount=0.0;
                else $tva_amount=$
                                     {
                                         $tva
                                     };
                $sum+=$tva_amount;
                $sum=round($sum,2);
            }
            $r=$sum;

            break;
        case 'BON_COMMANDE':
            if ( isset($_REQUEST['bon_comm']))
                return $_REQUEST['bon_comm'];
            else
                return "";
            break;
        case 'PJ':
            if ( isset($_REQUEST['e_pj']))
                return $_REQUEST['e_pj'];
            else
                return "";

        case 'OTHER_INFO':
            if ( isset($_REQUEST['other_info']))
                return $_REQUEST['other_info'];
            else
                return "";
            break;
        case 'COMMENT':
            if ( isset($_REQUEST['e_comm']))
                return $_REQUEST['e_comm'];
            break;
        }
        return $r;
    }
    /*!\brief remove a row from the table document, the lob object is not deleted
     *        because can be linked elsewhere
     */
    function remove()
    {
      $d_lob=$this->db->get_value('select d_lob from document where d_id=$1',
				  array($this->d_id));
        $sql='delete from document where d_id='.$this->d_id;
        $this->db->exec_sql($sql);
        if ( $d_lob != 0 )
            $this->db->lo_unlink($d_lob);
    }
    /*!\brief Move a document from the table document into the concerned row
     *        the document is not copied : it is only a link
     *
     * \param $p_internal internal code
     *
     */
    function MoveDocumentPj($p_internal)
    {
        $sql="update jrn set jr_pj=$1,jr_pj_name=$2,jr_pj_type=$3 where jr_internal=$4";

        $this->db->exec_sql($sql,array($this->d_lob,$this->d_filename,$this->d_mimetype,$p_internal));
        // clean the table document
        $sql='delete from document where d_id='.$this->d_id;
        $this->db->exec_sql($sql);


    }


}
