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

 //$Revision$
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/*! \file
 * \brief Contains the procedure for connecting to postgresql
 */

/*!
 * \param  p_type string : all for all dossiers lim for only the 
 *             dossier where we've got rights
 * 
 * Show the folder where user have access. Return    : nothing
++*/ 
function ShowDossier($p_type,$p_first=0,$p_max=10,$p_Num=0) {
  $l_user=$_SESSION['g_user'];
  if ( $p_max == 0 ) {
    $l_step="";
  } else {
    $l_step="LIMIT $p_max OFFSET $p_first";
  }
  $cn=DbConnect();
  if ( $p_type == "all") {
    $l_sql="select *, 'W' as priv_priv from ac_dossier ORDER BY dos_name  ";
    $p_Num=count_sql($cn,$l_sql);
  } else {
    $l_sql="select * from jnt_use_dos 
                               natural join ac_dossier 
                               natural join ac_users 
                               inner join priv_user on priv_jnt=jnt_id where 
                               use_login='".$l_user."' and priv_priv !='NO'
                               order by dos_name ";
    $p_Num=count_sql($cn,$l_sql);
  }
  $l_sql=$l_sql.$l_step;
  $p_res=ExecSql($cn,$l_sql);

  echo_debug('postgres.php',__LINE__,"ShowDossier:".$p_res." Line = $p_Num");

  $Max=pg_NumRows($p_res);
  if ( $Max == 0 ) return null;
  for ( $i=0;$i<$Max; $i++) {	
    //    echo_debug ("i = $i");
    $row[]=pg_fetch_array($p_res,$i);
    //echo $row[dossier];
  }
  return $row;
}

/*! 
 * \brief connect to the database
 *  <br>example
 *  <ul>
 *    <li> DbConnect () connects to account_repository</li>
 *    <li> DbConnect(1) connects to dossier1</li>
 *    <li> DbConnect(1,'mod') connects to mod1</li>
 *    <li> DbConnect(-2,'postgres') connects to the db postgres</li>
 *</ul>
 * 
 * \param p_db : db_name id  (or -1 for account_repository or -2 if p_type contains the full name of a database
 *       
 * \param p_type = dossier, mod or the name of a datase
 * \return the connection
 */
function DbConnect($p_db=-1,$p_type='dossier') {
  if ( IsNumber($p_db) == false || strlen($p_db) > 5 )
	die ("Dossier invalide");
	
  if ( $p_db==-1) 
    $l_dossier=sprintf("%saccount_repository",domaine);
  else {
    if ( $p_db == -2 )
      $l_dossier=$p_type;
    else 
      switch ($p_type) {
      case 'dossier':
	$l_dossier=sprintf("%sdossier%d",domaine,$p_db);
	break;
      case 'mod':
	$l_dossier=sprintf("%smod%d",domaine,$p_db);
	break;
      }
  }
  $phpcompta_user=phpcompta_user;
  $password=phpcompta_password;
  $port=phpcompta_psql_port;
  ob_start();
 $a=pg_connect("dbname=$l_dossier host=127.0.0.1 user='$phpcompta_user'
password='$password' port=$port");

  if ( $a == false )
  {
  	ob_end_clean();
	echo '<h2 class="error">Impossible de se connecter &agrave; postgreSql !</h2>';
  	echo "Vos param&egrave;tres sont incorrectes : <br>";
  	echo "<br>";
  	echo "base de donn&eacute;e : $l_dossier<br>";
	echo "Domaine : ".domaine."<br>";
  	echo "Port $port <br>";
  	echo "Utilisateur : $phpcompta_user <br>";

  	exit ("Connection impossible : v&eacute;rifiez vos param&egrave;tres de base
de donn&eacute;es");
	
  }
  ob_end_clean();
  echo_debug ('postgres.php',__LINE__,"connect to $p_db dbname $l_dossier");
  return $a;
}
/*! 
 * \brief send a sql string to the database
 * \param $p_connection db connection 
 * \param $p_string     sql string
 * \return false if error otherwise true
 */
function ExecSql($p_connection, $p_string,$p_encoding='utf8') {
  echo_debug('postgres.php',__LINE__,"SQL = $p_string");
  // probl. with Ubuntu & UTF8
  //----
  try {
    pg_set_client_encoding($p_connection,$p_encoding);
    $ret=pg_query($p_connection,$p_string);
    if ( ! $ret )       throw new Exception (" SQL ERROR $p_string ",1);
  } catch (Exception  $a) {
    echo $p_string;
    $a->getMessage();
    $a->getTrace();
    return $ret;
  }
  return $ret;

}
/*! 
 * \brief send a sql string to the database using the function
 * pg_query_params. Useful for avoiding the problem with the quotes
 * and to execute several times the same command 
 * \param $p_connection db connection 
 * \param $p_string     sql string
 * \param $p_array array containing the value
 * \return false if error otherwise true
 */
function ExecSqlParam($p_connection, $p_string,$p_array) {
  echo_debug('postgres.php',__LINE__,"SQL = $p_string");
  echo_debug('postgres.php',__LINE__,$p_array);
  // probl. with Ubuntu & UTF8
  //----

  pg_set_client_encoding($p_connection,'utf8');
  //ob_start();
  $ret=pg_query_params($p_connection,$p_string,$p_array);
  if ( ! $ret )   {
    //ob_end_clean();
    $r=$p_string."array ".var_export($p_array,TRUE);
    throw new Exception (" SQL ERROR $r",1);
  }
  //ob_end_clean();
  return $ret;

}

/*! 
 * \brief Return all the users
 * as an array
 */
function GetAllUser() {
  echo_debug('postgres.php',__LINE__,"GetUser");
  $cn=DbConnect();
  $sql="select * from ac_users where use_login!='phpcompta'";
  echo_debug('postgres.php',__LINE__,"ExecSql");
  $Res=ExecSql($cn,$sql);
  $Num=pg_NumRows($Res);
  if ( $Num == 0 ) return null;
  for ($i=0;$i < $Num; $i++) {
    $User[]=pg_fetch_array($Res,$i);
  }
  return $User;
}


/*! \brief Count the number of row
 * 
 * \param $p_conn connection handler
 * \param $p_sql sql string
 * \param $array if not null we use the safer ExecSqlParam
 */

function count_sql($p_conn,$p_sql,$array=null)
{
  if ( $array == null )
    $r_sql=ExecSql($p_conn,$p_sql);
  else
    $r_sql=ExecSqlParam($p_conn,$p_sql,$array);
  return pg_NumRows($r_sql);

}
/*!\brief get the current sequence
 */
function GetSequence($p_cn,$p_seq)
{
  $Res=ExecSql($p_cn,"select currval('$p_seq') as seq");
  $seq=pg_fetch_array($Res,0);
  return $seq['seq'];
}

/*!\brief  get the current sequence
 */
function NextSequence($p_cn,$p_seq)
{
  $Res=ExecSql($p_cn,"select nextval('$p_seq') as seq");
  $seq=pg_fetch_array($Res,0);
  return $seq['seq'];
}
function StartSql($p_cn) {
  $Res=ExecSql($p_cn,"start transaction");
}
function EndSql($p_cn) {
  $Res=ExecSql($p_cn,"end transaction");
}
function Commit($p_cn) {
  $Res=ExecSql($p_cn,"commit");
}
function Rollback($p_cn) {
  $Res=ExecSql($p_cn,"rollback");
}
function AlterSequence($p_cn,$p_name,$p_value) {
  
  $Res=ExecSql($p_cn,"alter sequence $p_name restart $p_value");
}

 /*!\brief  purpose return the result of a sql statment 
 * in a array
 * \param $p_cn database connection
 * \param $p_sql sql query
 * \param $p_array if not null we use ExecSqlParam
 */
function get_array($p_cn,$p_sql,$p_array=null) {
  echo_debug('postgres.php',__LINE__,"get_array");
  if ( $p_array == null ) {

    $r=ExecSql($p_cn,$p_sql);
  } else  {
    $r=ExecSqlParam($p_cn,$p_sql,$p_array);
  }
  if ( ($Max=  pg_NumRows($r)) == 0 ) return null;
  $array=pg_fetch_all($r);
  echo_debug('postgres.php',__LINE__,var_export($array,true));
  return $array;
}
/*!   save_upload_document
 * \brief Save a "piece justificative"
 *
 * \param $cn database connection
 * \param $seq jr_grpt_id
 * \return $oid of the lob file if success
 *         null if a error occurs

 * 
 */
function save_upload_document ($cn,$seq) {

  $new_name=tempnam($_ENV['TMP'],'pj');
  echo_debug('postgres.php',__LINE__,"new name=".$new_name);
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
	$oid= pg_lo_import($cn,$new_name);
	if ( $oid == false ) {
	  echo_error('postgres.php',__LINE__,"cannot upload document");
	  Rollback($cn);
	  return;
	}
	echo_debug('postgres.php',__LINE__,"Loading document");
	// Remove old document
	$ret=ExecSql($cn,"select jr_pj from jrn where jr_grpt_id=$seq");
	if (pg_num_rows($ret) != 0) {
	  $r=pg_fetch_array($ret,0);
	  $old_oid=$r['jr_pj'];
	  if (strlen($old_oid) != 0) 
	    pg_lo_unlink($cn,$old_oid);
	}
	// Load new document
	ExecSql($cn,"update jrn set jr_pj=".$oid.", jr_pj_name='".$_FILES['pj']['name']."', ".
		"jr_pj_type='".$_FILES['pj']['type']."'  where jr_grpt_id=$seq");
	return $oid;

      }      else {
	echo "<H1>Error</H1>";
	Rollback($cn);

      }
    }
  return 0;
 }
/*!\brief return the value of the sql, the sql will return only one value
 *        with the value
 * \param $p_cn database connection
 * \param $p_sql the sql stmt example :select s_value from
    document_state where s_id=2
 *\param $p_array if array is not null we use the ExecSqlParm (safer)
 *\see ExecSqlParm
 * \return only the first value or an empty string if nothing is found
 */ 
function getDbValue($p_cn,$p_sql,$p_array=null)
{

  if ( $p_array == null) {
    $ret=ExecSql($p_cn,$p_sql);
  } else {
    $ret=ExecSqlParam($p_cn,$p_sql,$p_array);
  }
  if ( pg_NumRows($ret) == 0 ) return "";
  $r=pg_fetch_row($ret,0);
  return $r[0];
    
}
/*!\brief test if a sequence exist */
/* \return true if the seq. exist otherwise false
 */
function exist_sequence($p_cn,$p_name) {
  $r=count_sql($p_cn,"select relname from pg_class where relname=lower('".$p_name."')");
  if ( $r==0)
    return false;
  return true;
}
function create_sequence($p_cn,$p_name,$min=1) {
  $sql="create sequence ".$p_name." minvalue $min";
  ExecSql($p_cn,$sql);
}

/*!
 **************************************************
 * \brief Get version of a database, the content of the
 *        table version
 *        
 * \param  $p_cn database connection
 *
 * \return version number
 *      
 */
function get_version($p_cn) {
	$Res=ExecSql($p_cn,"select val from version");
	$a=pg_fetch_array($Res,0);
	return $a['val'];
}
/*!  execute_script
 **************************************************
 * \brief Execute a sql script
 *        
 * \param $p_cn database 
 * \param $script script name
 */
function execute_script($p_cn,$script) {

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
    if ( ExecSql($p_cn,$sql) == false ) {
	    Rollback($p_cn);
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
/*!\brief loop to apply all the path to a folder or 
 *         a template
 * \param $p_cn database connexion
 * \param $p_name database name
 *
 */
function apply_patch($p_cn,$p_name,$from_setup=1)
{
  $MaxVersion=DBVERSION-1;
  echo '<ul>';
  $add=($from_setup==0)?'admin/':'';
  for ( $i = 4;$i <= $MaxVersion;$i++)
	{
	$to=$i+1;
	  if ( get_version($p_cn) <= $i ) { 
	  echo "<li>Patching ".$p_name.
		" from the version ".get_version($p_cn)." to $to</h3> </li>";

		execute_script($p_cn,$add.'sql/patch/upgrade'.$i.'.sql');
	  if ( DEBUG=='false' ) ob_start();
		// specific for version 4
		if ( $i == 4 )
		  {      
			$sql="select jrn_def_id from jrn_def ";
			$Res=ExecSql($p_cn,$sql);
			$Max=pg_NumRows($Res);
			for ($seq=0;$seq<$Max;$seq++) {
			  $row=pg_fetch_array($Res,$seq);
			  $sql=sprintf ("create sequence s_jrn_%d",$row['jrn_def_id']);
			  ExecSql($p_cn,$sql);
			}
		  }
		// specific to version 7
		if ( $i == 7 )
		  {
			// now we use sequence instead of computing a max
			// 
			$Res2=ExecSql($p_cn,'select coalesce(max(jr_grpt_id),1) as l from jrn');
			$Max2= pg_NumRows($Res2) ;
			if ( $Max2 == 1) {
			  $Row=pg_fetch_array($Res2,0);
			  var_dump($Row);
			  $M=$Row['l'];
			  ExecSql($p_cn,"select setval('s_grpt',$M,true)");
			}
		  }
		// specific to version 17
		if ( $i == 17 ) 
		  { 
		    execute_script($p_cn,$add.'sql/patch/upgrade17.sql');
		    $max=getDbValue($p_cn,'select last_value from s_jnt_fic_att_value');
		    AlterSequence($p_cn,'s_jnt_fic_att_value',$max+1);
		  } // version 
		
		// reset sequence in the modele
		//--
		if ( $i == 30 && $p_name=="mod" ) 
		  {
			$a_seq=array('s_jrn','s_jrn_op','s_centralized',
						 's_stock_goods','c_order','s_central');
			foreach ($a_seq as $seq ) {
			  $sql=sprintf("select setval('%s',1,false)",$seq);
			  $Res=ExecSql($p_cn,$sql);
			}
			$sql="select jrn_def_id from jrn_def ";
			$Res=ExecSql($p_cn,$sql);
			$Max=pg_NumRows($Res);
			for ($seq=0;$seq<$Max;$seq++) {
			  $row=pg_fetch_array($Res,$seq);
			  $sql=sprintf ("select setval('s_jrn_%d',1,false)",$row['jrn_def_id']);
			  ExecSql($p_cn,$sql);
			}
			
		  }
		if ( $i == 36 ) {
		  /* check the country and apply the path */
		  $res=ExecSql($p_cn,"select pr_value from parameter where pr_id='MY_COUNTRY'");
		  $country=pg_fetch_result($res,0,0);
		  execute_script($p_cn,$add."sql/patch/upgrade36.".$country.".sql");
		  ExecSql($p_cn,'update tmp_pcmn set pcm_type=find_pcm_type(pcm_val)');
		}
	  if ( DEBUG == 'false') ob_end_clean();
	}
	}
  echo '</ul>';
}
?>
