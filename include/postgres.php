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
    $p_Num=CountSql($cn,$l_sql);
  } else {
    $l_sql="select * from jnt_use_dos 
                               natural join ac_dossier 
                               natural join ac_users 
                               inner join priv_user on priv_jnt=jnt_id where 
                               use_login='".$l_user."' and priv_priv !='NO'
                               order by dos_name ";
    $p_Num=CountSql($cn,$l_sql);
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
  	ob_clean();
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
  ob_clean();
  echo_debug ('postgres.php',__LINE__,"connect to $p_db dbname $l_dossier");
  return $a;
}
/*! 
 * \brief send a sql string to the database
 * \param $p_connection db connection 
 * \param $p_string     sql string
 * \return false if error otherwise true
 */
function ExecSql($p_connection, $p_string) {
  echo_debug('postgres.php',__LINE__,"SQL = $p_string");
  // probl. with Ubuntu & UTF8
  //----

  pg_set_client_encoding($p_connection,'latin1');
  ob_start();
  $ret=pg_query($p_connection,$p_string);
  if ( ! $ret )   {
    ob_clean();
    throw new Exception (" SQL ERROR $p_string ",1);
  }
  ob_flush();
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

  pg_set_client_encoding($p_connection,'latin1');
  if (!DEBUG ) ob_start();
  $ret=pg_query_params($p_connection,$p_string,$p_array);
  if ( ! $ret )   {
     if (!DEBUG )ob_clean();
    $r=$p_string."array ".var_export($p_array,TRUE);
    throw new Exception (" SQL ERROR $r",1);
  }
   if (!DEBUG ) ob_flush();
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
/*!   
 * \brief Check if the User is valid
 * and return an array with his property
 */
function GetUid($p_uid) {
  $cn=DbConnect();
  $Res=ExecSql($cn,"select * from ac_users where use_id=".$p_uid);
  $Num=pg_NumRows($Res);
  if ( $Num == 0 ) { return false; }
  for ($i=0;$i < $Num; $i++) {
    $Prop[]=pg_fetch_array($Res,$i);
  }
  return $Prop;
}
/*!   
 * \brief Get the privilege of an user on a folder
 */
function GetPriv($p_dossier,$p_login)
{
  $cn=DbConnect();
  $Res=ExecSql($cn,"select priv_priv 
                    from priv_user  left join jnt_use_dos on jnt_id=priv_jnt
			inner join ac_users on ac_users.use_id=jnt_use_dos.use_id
                    where use_login='$p_login' and dos_id=$p_dossier");
  $Num=pg_NumRows($Res);
  echo_debug('postgres.php',__LINE__,"Found ".$Num." rows in GetPriv");
  if ( $Num==0) { return 0;}
  for($i=0;$i < $Num;$i++) {
    $Right=pg_fetch_array($Res,$i); 
    $Priv[]=$Right['priv_priv'];
  }
  return $Priv;
}
/*!   
 * \brief Get the number of rows
 * from table jnt_use_dos where $p_dossier = dos_id and 
 * use_id=$p_user. 
 */
function ExisteJnt($p_dossier,$p_user)
{
  $cn=DbConnect();
  $Res=ExecSql($cn,"select * from jnt_use_dos where dos_id=".$p_dossier." and use_id=".$p_user);
  return pg_NumRows($Res);
}
/* ExistePriv
 * 
 * 
 * 
 */
function ExistePriv($p_jntid)
{
  $cn=DbConnect();
  $Res=ExecSql($cn,"select * from priv_user where priv_jnt=".$p_jntid);
  return pg_NumRows($Res);
}
/* GetJnt
 * Get the jnt
 * 
 * 
 */
function GetJnt($p_dossier,$p_user)
{
  $cn=DbConnect();
  $Res=ExecSql($cn,"select jnt_id from jnt_use_dos where dos_id=".$p_dossier." and use_id=".$p_user);
  $R=pg_fetch_array($Res,0);
  return $R['jnt_id'];
}
/* GetDbId
 * Get the dos_id of a dossier
 * parm: name of the folder
 */
function GetDbId($p_name)
{
  $cn=DbConnect();
  $r_sql=ExecSql($cn,"select dos_id from ac_dossier
                 where dos_name='".$p_name."'");
  $num=pg_NumRows($r_sql);
  if ( $num == 0 ) {
    return 0;
  } else {
    $l_db=pg_fetch_array($r_sql,0);
    return $l_db['dos_id'];
  }
     
}
/* CountSql
 * \brief Count the number of row
 * 
 * \param $p_conn connection handler
 * \param $p_sql sql string
 */

function CountSql($p_conn,$p_sql)
{
  $r_sql=ExecSql($p_conn,$p_sql);
  return pg_NumRows($r_sql);

}
/*! 
 * \param id of a dossier
 * \return  Name of the dossier
 */
function GetDossierName($p_dossier)
{
  $cn=DbConnect();
  $Ret=ExecSql($cn,"select dos_name from ac_dossier where dos_id=".$p_dossier);
  $r= pg_fetch_array($Ret,0);
  return $r['dos_name'];
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
function GetLogin($p_uid)
{
  $cn=DbConnect();
  $Res=ExecSql($cn,"select use_login from ac_users where use_id=$p_uid");
  if ( pg_NumRows($Res) == 0 ) return null;
  $a_login=pg_fetch_array($Res,0);
  return $a_login['use_login'];
}

/*!   SyncRight
 * \brief  Synchronize les droits par d�faut
 *           avec  les journaux existants
 *\param $p_dossier dossier id
 * \param $p_user user id
 *
 */ 
function SyncRight($p_dossier,$p_user) {
  $priv=GetPriv($p_dossier,$p_user);
  $right=$priv[0];

  $cn=DbConnect($p_dossier);

 $sql="insert into user_sec_jrn(uj_login,uj_jrn_id,uj_priv) ".
   "select '".$p_user."',jrn_def_id,'".$right."' from jrn_def ".
   "where jrn_def_id not in ".
   "(select uj_jrn_id from user_sec_jrn where uj_login='".$p_user."')";
 $Res=ExecSql($cn,$sql);
}
/*!
 * \brief  Get the properties of an user
 *           it means theme, profile, admin...
 *        
 * \param $p_user    user login
 * \param $p_cn      connection 
 *
 * \return an array containing 
 *                 - use_admin
 *                 -  use_usertype
 *                 -  g_theme
 *                 -  use_name
 *                 -  use_login
 */
function GetUserProperty($p_cn,$p_user)
{
 $sql="select use_login,use_first_name,use_name,use_admin,use_usertype,g_theme
     from ac_users where use_login='$p_user'";
 $Ret=ExecSql($p_cn,$sql);
 if ( pg_NumRows($Ret) == 0) 
   return array('use_first_name'=>'?',
                'use_name'=>'Unknown',
                'use_admin'=>0,
		'use_usertype'=>'user',
		'g_theme'=>'classic',
		'use_login'=>$p_user);

 $a=pg_fetch_array($Ret,0);
 return $a;
}
/*!
 * \brief   Give the mod_id from modeledef
 *        
 *  
 * \param $p_cn database connection (repository)
 * \param     $p_modname template name
 * \return template id or 0 if not found
 *        
 */
function GetModeleId($p_cn,$p_modname) {
  $Res=ExecSql($p_cn,"select mod_id from modeledef where mod_name='$p_modname'");
  if (pg_NumRows($Res) == 0) return 0;
  $name=pg_fetch_array($Res,0);
  return $name['mod_id'];
}


 /*!\brief  purpose return the result of a sql statment 
 * in a array
 * \param $p_cn database connection
 * \param $p_sql sql query
 */
function GetArray($p_cn,$p_sql) {
  echo_debug('postgres.php',__LINE__,"GetArray");
  $r=ExecSql($p_cn,$p_sql);
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

  $new_name=tempnam('/tmp','pj');
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
 * \param $p_sql the sql stmt example :select s_value from document_state where s_id=2
 * \return only the first value
 */ 
function getDbValue($p_cn,$sql)
{
  $ret=ExecSql($p_cn,$sql);
  if ( pg_NumRows($ret) == 0 ) return "";
  $r=pg_fetch_row($ret,0);
  return $r[0];
}
/*!\brief test if a sequence exist */
/* \return true if the seq. exist otherwise false
 */
function exist_sequence($p_cn,$p_name) {
  $r=CountSql($p_cn,"select relname from pg_class where relname=lower('".$p_name."')");
  if ( $r==0)
    return false;
  return true;
}
function create_sequence($p_cn,$p_name) {
  $sql="create sequence ".$p_name;
  ExecSql($p_cn,$sql);
}
?>
