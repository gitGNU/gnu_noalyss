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
   * \brief contains the class for connecting to a postgresql database
   */
require_once('constant.php');
require_once('ac_common.php');
require_once('debug.php');

/*!\brief  
 * This class allow you to connect to the postgresql database, execute sql, retrieve data 
 *
 */
class Database
{

  private $db; 			/*!< database connection */
  private $ret;			/*!< return value  */
  /*!\brief constructor
   *\param $p_database_id is the id of the dossier, or the modele following the 
   * p_type if = 0 then connect to the repository 
   *\param $p_type is 'DOS' (defaut) for dossier or 'MOD'
   */
  function __construct ($p_database_id=0,$p_type='dos') {
    if ( IsNumber($p_database_id) == false || strlen($p_database_id) > 5 ) 	die ("-->Dossier invalide [$p_database_id]");
    $phpcompta_user=phpcompta_user;
    $password=phpcompta_password;
    $port=phpcompta_psql_port;
  
    if ( $p_database_id == 0 ) { /* connect to the repository */
      $l_dossier=sprintf("%saccount_repository",domaine);      
    } else if ( $p_type == 'dos') { /* connect to a folder (dossier) */
      $l_dossier=sprintf("%sdossier%d",domaine,$p_database_id);
    } else if ($p_type=='mod') {	  /* connect to a template (modele) */
      $l_dossier=sprintf("%smod%d",domaine,$p_database_id);
    } else if ($p_type=='template') {
      $l_dossier='template1';
    } else { throw new Exception ('Connection invalide'); }

    ob_start();
    $a=pg_connect("dbname=$l_dossier host=127.0.0.1 user='$phpcompta_user'
password='$password' port=$port");
    
    if ( $a == false )
      {
	ob_end_clean();
	echo '<h2 class="error">Impossible de se connecter &agrave; postgreSql !</h2>';
	echo '<p>';
  	echo "Vos param&egrave;tres sont incorrectes : <br>";
  	echo "<br>";
  	echo "base de donn&eacute;e : $l_dossier<br>";
	echo "Domaine : ".domaine."<br>";
  	echo "Port $port <br>";
  	echo "Utilisateur : $phpcompta_user <br>";
	echo '</p>';

  	exit ("Connection impossible : v&eacute;rifiez vos param&egrave;tres de base
de donn&eacute;es");
	
      }
    $this->db=$a;
    ob_end_clean();
  }

  public function verify() {
    // Verify that the elt we want to add is correct
  }
    function set_encoding($p_charset) {
      pg_set_client_encoding($this->db,$p_charset);
    }
    /*! 
     * \brief send a sql string to the database
     * \param $p_connection db connection 
     * \param $p_string     sql string
     * \return false if error otherwise true
     */
    function exec_sql( $p_string,$p_array=null) {
      try {

	if ( $p_array==null ) {
	  $this->ret=pg_query($this->db,$p_string);
	} else {
	  $this->ret=pg_query_params($this->db,$p_string,$p_array);
	}
       	if ( ! $this->ret ) { throw new Exception ("  SQL ERROR $p_string ",1);}
      }catch (Exception  $a) {
	if (DEBUG) {
	  print_r ($p_string);
	  echo $a->getMessage();
	  echo $a->getTrace();
	}
	throw ($a);
      }

      return $this->ret;

    }
    /*! \brief Count the number of row returned by a sql statement
     * 
     * \param $p_sql sql string
     * \param $p_array if not null we use the safer ExecSqlParam
     */

    function count_sql($p_sql,$p_array=null)
    {
      $r_sql=$this->exec_sql($p_sql,$p_array);
      return pg_NumRows($r_sql);
    }
    /*!\brief get the current sequence
     */
    function get_current_seq($p_seq)
    {
      $Res=$this->exec_sql("select currval('$p_seq') as seq");
      $seq=pg_fetch_array($Res,0);
      return $seq['seq'];
    }


    /*!\brief  get the current sequence
     */
    function get_next_seq($p_seq)
    {
      $Res=$this->exec_sql("select nextval('$p_seq') as seq");
      $seq=pg_fetch_array($Res,0);
      return $seq['seq'];
    }
    function start() {
      $Res=$this->exec_sql("start transaction");
    }
    function commit() {
      $Res=$this->exec_sql("commit");
    }
    function rollback() {
      $Res=$this->exec_sql("rollback");
    }
    function alter_seq($p_name,$p_value) {
  
      $Res=$this->exec_sql("alter sequence $p_name restart $p_value");
    }
    /*!  
     * \brief Execute a sql script
     * \param $script script name
     */
    function execute_script($script) {

      if ( DEBUG=='false' ) ob_start();
      $hf=fopen($script,'r');
      if ( $hf == false ) {
	echo 'Ne peut ouvrir '.$script;
	exit();
      }
      $sql="";
      $flag_function=false;
      while (!feof($hf)) {
	$buffer=fgets($hf);
	$buffer=str_replace ("$","\$",$buffer);
	print $buffer."<br>";
	// comment are not execute
	if ( substr($buffer,0,2) == "--" ) {
	  //echo "comment $buffer";
	  continue;
	}
	// Blank Lines Are Skipped
	If ( Strlen($buffer)==0) {
	  //echo "Blank $buffer";
	  Continue;
	}
	if ( strpos(strtolower($buffer),"create function")===0 ) {
	  echo "found a function";
	  $flag_function=true;
	  $sql=$buffer;
	  continue;
	}
	if ( strpos(strtolower($buffer),"create or replace function")===0 ) {
	  echo "found a function";
	  $flag_function=true;
	  $sql=$buffer;
	  continue;
	}
	// No semi colon -> multiline command
	if ( $flag_function== false && strpos($buffer,';') == false ) {
	  $sql.=$buffer;
	  continue;
	} 
	if ( $flag_function ) {
	  if ( strpos(strtolower($buffer), "language plpgsql") === false && 
	       strpos(strtolower($buffer), "language 'plpgsql'") === false ) {
	    $sql.=$buffer;
	    continue;
	  }
	} else  {
	  // cut the semi colon
	  $buffer=str_replace (';','',$buffer);
	}
	$sql.=$buffer;
	echo_debug('setup.php',__LINE__,"Execute sql $sql");
	if ( $this->exec_sql($sql) == false ) {
	  $this->rollback();
	  if ( DEBUG=='false' ) ob_end_clean();
	  print "ERROR : $sql";
	  exit();
	}
	$sql="";
	$flag_function=false;
	print "<hr>";
      } // while (feof)
      fclose($hf);
      if ( DEBUG=='false' ) ob_end_clean();
    }
    /*!
     * \brief Get version of a database, the content of the
     *        table version
     *        
     * \return version number
     *      
     */
    function get_version() {
      $Res=$this->get_value("select val from version");
      return $Res;
    }


    function fetch($p_indice) {
      if ( $this->ret == false ) throw new Exception ('this->ret is empty');
      return pg_fetch_array($this->ret ) ;
    }
    function size() {
      return pg_NumRows($this->ret);
    }
    function count() {
      return pg_NumRows($this->ret);
    }

    /*!\brief loop to apply all the path to a folder or 
     *         a template
     * \param $p_cn database connexion
     * \param $p_name database name
     * \param $from_setup == 1 if called from setup.php
     *
     */
    function apply_patch($p_name,$from_setup=1)
    {
      $MaxVersion=DBVERSION-1;
      echo '<ul>';
      $add=($from_setup==0)?'admin/':'';
      for ( $i = 4;$i <= $MaxVersion;$i++)
	{
	  $to=$i+1;
	  if ( $this->get_version() <= $i ) { 
	    echo "<li>Patching ".$p_name.
	      " from the version ".$this->get_version()." to $to</h3> </li>";

	    $this->execute_script($add.'sql/patch/upgrade'.$i.'.sql');
	    if ( DEBUG=='false' ) ob_start();
	    // specific for version 4
	    if ( $i == 4 )
	      {      
		$sql="select jrn_def_id from jrn_def ";
		$Res=$this->exec_sql($sql);
		$Max=$this->size();
		for ($seq=0;$seq<$Max;$seq++) {
		  $row=pg_fetch_array($Res,$seq);
		  $sql=sprintf ("create sequence s_jrn_%d",$row['jrn_def_id']);
		  $this->exec_sql($sql);
		}
	      }
	    // specific to version 7
	    if ( $i == 7 )
	      {
		// now we use sequence instead of computing a max
		// 
		$Res2=$this->exec_sql('select coalesce(max(jr_grpt_id),1) as l from jrn');
		$Max2= pg_NumRows($Res2) ;
		if ( $Max2 == 1) {
		  $Row=pg_fetch_array($Res2,0);
		  var_dump($Row);
		  $M=$Row['l'];
		  $this->exec_sql("select setval('s_grpt',$M,true)");
		}
	      }
	    // specific to version 17
	    if ( $i == 17 ) 
	      { 
		$this->execute_script($add.'sql/patch/upgrade17.sql');
		$max=$this->get_value('select last_value from s_jnt_fic_att_value');
		$this->alter_seq($p_cn,'s_jnt_fic_att_value',$max+1);
	      } // version 
		
	    // reset sequence in the modele
	    //--
	    if ( $i == 30 && $p_name=="mod" ) 
	      {
		$a_seq=array('s_jrn','s_jrn_op','s_centralized',
			     's_stock_goods','c_order','s_central');
		foreach ($a_seq as $seq ) {
		  $sql=sprintf("select setval('%s',1,false)",$seq);
		  $Res=$this->exec_sql($sql);
		}
		$sql="select jrn_def_id from jrn_def ";
		$Res=$this->exec_sql($sql);
		$Max=pg_NumRows($Res);
		for ($seq=0;$seq<$Max;$seq++) {
		  $row=pg_fetch_array($Res,$seq);
		  $sql=sprintf ("select setval('s_jrn_%d',1,false)",$row['jrn_def_id']);
		  $this->exec_sql($sql);
		}
			
	      }
	    if ( $i == 36 ) {
	      /* check the country and apply the path */
	      $res=$this->exec_sql("select pr_value from parameter where pr_id='MY_COUNTRY'");
	      $country=pg_fetch_result($res,0,0);
	      $this->execute_script($add."sql/patch/upgrade36.".$country.".sql");
	      $this->exec_sql('update tmp_pcmn set pcm_type=find_pcm_type(pcm_val)');
	    }
	    if ( DEBUG == 'false') ob_end_clean();
	  }
	}
      echo '</ul>';
    }
    /*!\brief return the value of the sql, the sql will return only one value
     *        with the value
     * \param $p_sql the sql stmt example :select s_value from
     document_state where s_id=2
     *\param $p_array if array is not null we use the ExecSqlParm (safer)
     *\see exec_sql
     * \return only the first value or an empty string if nothing is found
     */ 
    function get_value($p_sql,$p_array=null)
    {
      $this->ret=$this->exec_sql($p_sql,$p_array);
      if ( pg_NumRows($this->ret) == 0 ) return "";
      $r=pg_fetch_row($this->ret,0);
      return $r[0];
    
    }
    /*!\brief  purpose return the result of a sql statment 
     * in a array
     * \param $p_sql sql query
     * \param $p_array if not null we use ExecSqlParam
     */
    function get_array($p_sql,$p_array=null) {
      $r=$this->exec_sql($p_sql,$p_array);
      if ( ($Max=  pg_NumRows($r)) == 0 ) return null;
      $array=pg_fetch_all($r);
      return $array;
    }

    function create_sequence($p_name,$min=1) {
      $sql="create sequence ".$p_name." minvalue $min";
      $this->exec_sql($sql);
    }
    /*!\brief test if a sequence exist */
    /* \return true if the seq. exist otherwise false
     */
    function exist_sequence($p_name) {
      $r=$this->count_sql("select relname from pg_class where relname=lower('".$p_name."')");
      if ( $r==0)
	return false;
      return true;
    }
    /*! 
     * \brief make a array with the sql
     *        
     * \param $p_sql related sql 
     *  \param $p_null if the array start with a null value
     *
     * \return: a double array [value,label]
     */
    function make_array($p_sql,$p_null=0) {
      $a=$this->exec_sql($p_sql);
      $max=pg_NumRows($a);
      if ( $max==0) return null;
      for ($i=0;$i<$max;$i++) {
	$row=pg_fetch_row($a);
	$r[$i]['value']=$row[0];
	$r[$i]['label']=$row[1];
      }
      // add a blank item ?
      if ( $p_null == 1 ) {
	for ($i=$max;$i!=0;$i--) {
	  $r[$i]['value']=    $r[$i-1]['value'];
	  $r[$i]['label']=    $r[$i-1]['label'];
	}
	$r[0]['value']=-1;
	$r[0]['label']=" ";
      } //   if ( $p_null == 1 ) 

      return $r;
    }
    /*!   
     * \brief Save a "piece justificative"
     *
     * \param $seq jr_grpt_id
     * \return $oid of the lob file if success
     *         null if a error occurs
     * 
     */
    function save_upload_document ($seq) {

      $new_name=tempnam($_ENV['TMP'],'pj');
      echo_debug(__FILE__,__LINE__,"new name=".$new_name);
      echo_debug(__FILE__.":".__LINE__.$_FILES);
      if ($_FILES["pj"]["error"] > 0)
	{
	  echo_error(__FILE__.":".__LINE__."Error: " . $_FILES["pj"]["error"] );
	}
      if ( strlen ($_FILES['pj']['tmp_name']) != 0 ) {
	echo_debug(__FILE__.":".__LINE__.'_FILE is'.$_FILES['pj']['tmp_name']);
	if (move_uploaded_file($_FILES['pj']['tmp_name'],
			       $new_name)) {
	  // echo "Image saved";
	  echo_debug(__FILE__.":".__LINE__."Doc saved");
	  $oid= pg_lo_import($this->db,$new_name);
	  if ( $oid == false ) {
	    echo_error('postgres.php',__LINE__,"cannot upload document");
	    $this->rollback();
	    return;
	  }
	  echo_debug(__FILE__,__LINE__,"Loading document");
	  // Remove old document
	  $ret=$this->ExecSql("select jr_pj from jrn where jr_grpt_id=$seq");
	  if (pg_num_rows($ret) != 0) {
	    $r=pg_fetch_array($ret,0);
	    $old_oid=$r['jr_pj'];
	    if (strlen($old_oid) != 0) 
	      pg_lo_unlink($cn,$old_oid);
	  }
	  // Load new document
	  $this->exec_sql("update jrn set jr_pj=".$oid.", jr_pj_name='".$_FILES['pj']['name']."', ".
			  "jr_pj_type='".$_FILES['pj']['type']."'  where jr_grpt_id=$seq");
	  return $oid;

	}      else {
	  echo "<H1>Error</H1>";
	  $this->rollback();

	}
      }
      return 0;
    }
  /*!\brief wrapper for the function pg_NumRows
   *\param $ret is the result of a exec_sql
   *\return number of line affected
   */
    static function num_row($ret) { return pg_NumRows($ret); }


  /*!\brief wrapper for the function pg_fetch_array
   *\param $ret is the result of a pg_exec
   *\param $p_indice is the index
   *\return $array of column 
   */
    static function fetch_array($ret,$p_indice) { return pg_fetch_array($ret,$p_indice); }

  /*!\brief wrapper for the function pg_fetch_all
   *\param $ret is the result of pg_exec (exec_sql)
   *\return double array (row x col )
   */
    static function fetch_all($ret) { return pg_fetch_all($ret); }

  /*!\brief wrapper for the function pg_fetch_all
   *\param $ret is the result of pg_exec (exec_sql)
   *\param $p_row is the indice of the row
   *\param $p_col is the indice of the col
   *\return a string or an integer
   */
    static function fetch_result($ret,$p_row=0,$p_col=0) { return pg_fetch_result($ret,$p_row,$p_col); }

    /*!\brief wrapper for the function pg_lo_unlink 
     *\param $p_oid is the of oid 
     *\return return the result of the operation
     */   
    function lo_unlink($p_oid) { return pg_lo_unlink($this->db,$p_oid); }

    /*!\brief wrapper for the function pg_lo_unlink 
     *\param $p_string string name for pg_prepare function
     *\param $p_sql  is the sql to prepare 
     *\return return the result of the operation
     */   
    function prepare($p_string,$p_sql) { return pg_prepare($this->db,$p_string,$p_sql); }
    /*!\brief wrapper for the function pg_lo_unlink 
     *\param $p_string string name for pg_prepare function
     *\param $p_array contains the variables
     *\return return the result of the operation
     */   
    function execute($p_string,$p_array) { return pg_execute($this->db,$p_string,$p_array); }

  /*!\brief wrapper for the function pg_lo_export
   *\param $p_oid is the oid of the log
   *\param $tmp  is the file
   *\return result of the operation
   */
    function lo_export($p_oid,$tmp) {
      return pg_lo_export($this->db,$oid,$tmp);
    }
  /*!\brief wrapper for the function pg_lo_export
   *\param $p_oid is the oid of the log
   *\param $tmp  is the file
   *\return result of the operation
   */
    function lo_import($p_oid) {
      return pg_lo_import($this->db,$oid);
    }

  /*!\brief wrapper for the function pg_escape_string
   *\param $p_string is the string to escape
   *\return escaped string
   */
    static function escape_string($p_string) {
      return pg_escape_string($p_string);
    }
  /*!\brief wrapper for the function pg_close 
   */
    function close() { pg_close($this->db);}
    /*!\brief
     *\param
     *\return
     *\note
     *\see
     *\todo
     */	
    static function test_me() {
    }
  
}

/* test::test_me(); */
