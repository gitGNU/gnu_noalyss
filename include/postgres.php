<? //$Revision$
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


/*++ 
 * function : CheckUser
 * Parameter : none
 * return    : not use
 * Description :
 * Check if user is active and exists in the
 * repository
 * Automatically redirect
 * 
++*/
/* obsolete  function CheckUser()
{
	global $g_user,$g_pass,$IsValid;
	echo_debug(__FILE__,__LINE__,"CheckUser");
	$res=0;
	$pass5=md5($g_pass);
	if ( $IsValid == 1 ) { return; }
	$cn=DbConnect("account_repository");
	if ( $cn != false ) {
	  $sql="select ac_users.use_login,ac_users.use_active, ac_users.use_pass
				from ac_users  
				 where ac_users.use_login='$g_user' 
					and ac_users.use_active=1
					and ac_users.use_pass='$pass5'";
	    echo_debug(__FILE__,__LINE__,"Sql = $sql");
	    $ret=pg_exec($cn,$sql);
	    $res=pg_NumRows($ret);
	    echo_debug(__FILE__,__LINE__,"Number of found rows : $res");
	  }
	  
	if ( $res == 0  ) {
	  	echo '<META HTTP-EQUIV="REFRESH" content="4;url=index.html">';
		echo "<BR><BR><BR><BR><BR><BR>";
		echo "<P ALIGN=center><BLINK>
			<FONT size=+12 COLOR=RED>
			Invalid user <BR> or<BR> Invalid password 
			</FONT></BLINK></P></BODY></HTML>";
		session_unset();
		
		exit -1;			
	} else $IsValid=1;
	
	return $ret;
	
}
*/
/*++
 * function : ShowDossier
 * Parameter : p_type string : all for all dossiers lim for only the 
 *             dossier where we've got rights
 * Return    : nothing
 * Description :
 * Show the folder where user have access
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

  echo_debug(__FILE__,__LINE__,"ShowDossier:".$p_res." Line = $p_Num");

  $Max=pg_NumRows($p_res);
  if ( $Max == 0 ) return null;
  for ( $i=0;$i<$Max; $i++) {	
    //    echo_debug ("i = $i");
    $row[]=pg_fetch_array($p_res,$i);
    //echo $row[dossier];
  }
  return $row;
}
// Check if the user has admin right
// to be complete
/* obsolete function CheckAdmin() {
  $l_user=$_SESSION($g_user);
  $l_pass=$_SESSION($g_pass);

  $res=0;

  if ( $l_user != 'phpcompta') {
	$pass5=md5($l_pass);
	$sql="select use_id from ac_users where use_login='$l_user'
		and use_active=1 and use_admin=1 and use_pass='$pass5'";

	$cn=DbConnect();

	$isAdmin=CountSql($cn,$sql);
	} else $isAdmin=1;

  return $isAdmin;
}
*/

/* function DbConnect
 * purpose : connect to the database
 * parameter : p_db : db_name
 * return the connection
 * todo : replace hardcoded by variable placed in
 *        constant.php
 */
function DbConnect($p_db="account_repository") {
  $password=phpcompta_password;
  $a=pg_connect("dbname=$p_db host=127.0.0.1 user='phpcompta' password='$password'");
  echo_debug ("connect to $p_db");
  return $a;
}
/* function ExecSql
 * purpose : send a sql string to the database
 * parameter p_connection db connection 
 *           p_string     sql string
 * return false if error otherwise true
 */
function ExecSql($p_connection, $p_string) {
  echo_debug(__FILE__,__LINE__,"SQL = $p_string");

  $ret=pg_exec($p_connection,$p_string);
  if ( $ret == false ) { 
    echo_error ("SQL ERROR ::: $p_string");
    exit(" Operation cancelled due to error : $p_string");
  }

  return $ret;
}
/*
 * GetAllUser
 * Return all the users
 * as an array
 */
function GetAllUser() {
  echo_debug(__FILE__,__LINE__,"GetUser");
  $cn=DbConnect();
  $sql="select * from ac_users where use_login!='phpcompta'";
  echo_debug(__FILE__,__LINE__,"ExecSql");
  $Res=ExecSql($cn,$sql);
  $Num=pg_NumRows($Res);
  if ( $Num == 0 ) return null;
  for ($i=0;$i < $Num; $i++) {
    $User[]=pg_fetch_array($Res,$i);
  }
  return $User;
}
/* GetUid
 * Check if the User is valid
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
/*
 * GetPriv
 * Get the privilege of an user on a folder
 */
function GetPriv($p_dossier,$p_login)
{
  $cn=DbConnect();
  $Res=ExecSql($cn,"select priv_priv 
                    from priv_user  left join jnt_use_dos on jnt_id=priv_jnt
			inner join ac_users on ac_users.use_id=jnt_use_dos.use_id
                    where use_login='$p_login' and dos_id=$p_dossier");
  $Num=pg_NumRows($Res);
  echo_debug(__FILE__,__LINE__,"Found ".$Num." rows in GetPriv");
  if ( $Num==0) { return 0;}
  for($i=0;$i < $Num;$i++) {
    $Right=pg_fetch_array($Res,$i); 
    $Priv[]=$Right['priv_priv'];
  }
  return $Priv;
}
/* ExisteJnt
 * Get the number of rows
 * from table jnt_use_dos where $p_dossier = dos_id and 
 * use_id=$p_user
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
 * Count the number of row
 * 
 * parm: p_conn connection handler
 *       p_sql sql string
 */

function CountSql($p_conn,$p_sql)
{
  $r_sql=ExecSql($p_conn,$p_sql);
  return pg_NumRows($r_sql);

}
/* GetDossierName
 * Param: id of a dossier
 * return : Name of the dossier
 */
function GetDossierName($p_dossier)
{
  $cn=DbConnect();
  $Ret=ExecSql($cn,"select dos_name from ac_dossier where dos_id=".$p_dossier);
  $r= pg_fetch_array($Ret,0);
  return $r['dos_name'];
}
/* GetSequence
 * get the current sequence
 */
function GetSequence($p_cn,$p_seq)
{
  $Res=ExecSql($p_cn,"select currval('$p_seq') as seq");
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
  
  $Res=ExecSql($p_cn,"drop sequence $p_name");
  $Res=ExecSql($p_cn,"create sequence $p_name start $p_value");
}
function GetLogin($p_uid)
{
  $cn=DbConnect();
  $Res=ExecSql($cn,"select use_login from ac_users where use_id=$p_uid");
  if ( pg_NumRows($Res) == 0 ) return null;
  $a_login=pg_fetch_array($Res,0);
  return $a_login['use_login'];
}

/* function SyncRight
 * Purpose : Synchronize les droits par défaut
 *           avec  les journaux existants
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-
 *
 */ 
function SyncRight($p_dossier,$p_user) {
  $priv=GetPriv($p_dossier,$p_user);
  $right=$priv[0];
  $ldb=sprintf("dossier%d",$p_dossier);
  $cn=DbConnect($ldb);

 $sql="insert into user_sec_jrn(uj_login,uj_jrn_id,uj_priv) ".
   "select '".$p_user."',jrn_def_id,'".$right."' from jrn_def ".
   "where jrn_def_id not in ".
   "(select uj_jrn_id from user_sec_jrn where uj_login='".$p_user."')";
 $Res=ExecSql($cn,$sql);
}
/* function GetUserProperty
 * Purpose : Get the properties of an user
 *           it means theme, profile, admin...
 *        
 * parm : 
 *	- $p_user    user login
 *      - $p_cn      connection resource
 *      -
 * gen :
 *	-
 * return: an array
 *         containing use_admin
 *                    use_usertype
 *                    use_theme
 *                    use_name
 *                    use_login
 */
function GetUserProperty($p_cn,$p_user)
{
 $sql="select use_login,use_first_name,use_name,use_admin,use_usertype,use_theme
     from ac_users where use_login='$p_user'";
 $Ret=ExecSql($p_cn,$sql);
 if ( pg_NumRows($Ret) == 0) 
   return array('use_first_name'=>'?',
                'use_name'=>'Unknown',
                'use_admin'=>0,
		'use_usertype'=>'user',
		'use_theme'=>'classic',
		'use_login'=>$p_user);

 $a=pg_fetch_array($Ret,0);
 return $a;
}
/* function GetModeleId
 * Purpose :  Give the mod_id from modeledef
 *        
 * parm : 
 *	- p_cn database connection (repository)
 *      - p_modname template name
 * gen :
 *	- none
 * return:
 *        template id or 0 if not found
 */
function GetModeleId($p_cn,$p_modname) {
  $Res=ExecSql($p_cn,"select mod_id from modeledef where mod_name='$p_modname'");
  if (pg_NumRows($Res) == 0) return 0;
  $name=pg_fetch_array($Res,0);
  return $name['mod_id'];
}

/* function GetArray
 * purpose return the result of a sql statment 
 * in a array
 * param : $p_cn database connection
 *         $p_sql sql query
 */
function GetArray($p_cn,$p_sql) {
  echo_debug(__FILE__,__LINE__,"GetArray");
  $r=ExecSql($p_cn,$p_sql);
  if ( ($Max=  pg_NumRows($r)) == 0 ) return null;
  $array=pg_fetch_all($r);
  echo_debug(__FILE__,__LINE__,var_export($array,true));
  return $array;
}
?>
