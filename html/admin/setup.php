
<style type="text/css">
<!--
body {
   	font-family:sans-serif;
	font-size:12px;
	color:blue;
 }
h2.info {
	color:green;
	font-size:20px;
	font-family:sans-serif;
}
h2.error {
	color:red;
	font-size:20px;
	font-family:sans-serif;
}
.warning  {
   	font-family:sans-serif;
	font-size:12px;
	color:red;
 }
.info {
	color:green;
	font-size:12px;
	font-family:sans-serif;
}

-->
</style>
<p align="center">
  <IMG SRC="../image/logo7.jpg" alt="Logo">
</p>
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
/* $Revision*/
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

$inc_path=get_include_path();
$inc_path.=':../../include';
set_include_path($inc_path);

include_once('constant.php');
include_once('postgres.php');
include_once('debug.php');
include_once('ac_common.php');
include_once('variable.php');
/* function GetVersion
 **************************************************
 * Purpose : Get version of a database
 *        
 * parm : 
 *	- $p_cn database connection
 * gen :
 *	- none
 * return:
 *        none
 */
function GetVersion($p_cn) {
	$Res=ExecSql($p_cn,"select val from version");
	$a=pg_fetch_array($Res,0);
	return $a['val'];
}
/* function ExecuteScript
 **************************************************
 * Purpose : Execute a sql script
 *        
 * parm : 
 *	- $p_cn database connection
 *      - $script script name
 * gen :
 *	- none
 * return:
 *        none
 */
function ExecuteScript($p_cn,$script) {
  $hf=fopen($script,'r');
  $sql="";
  while (!feof($hf)) {
    $buffer=fgets($hf);
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

    // No semi colon -> multiline command
    if ( strpos($buffer,';') == false ) {
      $sql.=$buffer;
      echo "<color=red> $buffer</color>";
      continue;
    } 
    // cut the semi colon
    $buffer=str_replace (';','',$buffer);
    $sql.=$buffer;
    print "Execute $sql <hr>";
    ExecSql($p_cn,$sql);
    $sql="";
  } // while (feof)
  fclose($hf);
}

// Verify some PHP parameters
// magic_quotes_gpc = Off
// magic_quotes_runtime = Off
// magic_quotes_sybase = Off
// include_path

?>
<h2>Php setting</h2>
<?
$flag_php=0;
foreach (array('magic_quotes_gpc','magic_quotes_runtime') as $a) {

  if ( ini_get($a) == false ) print $a.': Ok  <br>';
  else {
	print ("<h2 class=\"error\">$a has a bad value  !!!</h2>");
	$flag_php++;
  }

}
if ( ini_get("session.use_trans_sid") == false )  {
	print '<h2 class="error"> avertissement session.use_trans_sid should be set to true </h2>';
}
if ( ereg("\.\.\/include",ini_get('include_path')) == false )
{
  print ("<h2 class=\"error\">include_path incorrect  !!!".ini_get('include_path')."</h2>");
	$flag_php++;
}
 else
   print 'include_path : ok ('.ini_get('include_path').')<br>';

if ( $flag_php==0 ) {
	echo '<p class="info">php.ini est bien configuré</p>';
} else {
	echo '<p class="error"> php mal configurée</p>';
	exit -1;
}
$cn=DbConnect(-2,'phpcompta');

if ($cn == false ) {
  print "<p> Vous devez absolument taper dans une console la commande 'createuser -A -d -P  phpcompta et vous donnez dany comme mot de passe (voir la documentation)'
  puis  la commande 'createdb -O phpcompta phpcompta'. </p>
<p>Ces commandes créeront l'utilisateur phpcompta
puis la base de données par défaut de phpcompta.</p>";
  exit();
 }
?>
<h2>Database Setting</h2>
<?
$sql="select name,setting 
      from pg_settings 
      where 
      name in ('effective_cache_size','shared_buffers','sort_mem')";
$Res=ExecSql($cn,$sql);
$flag=0;
for ($e=0;$e<pg_NumRows($Res);$e++) {
  $a=pg_fetch_array($Res,$e);
  switch ($a['name']){
  case 'effective_cache_size':
    if ( $a['setting'] < 1000 ){
      print '<p class="warning">Attention le paramètre effective_cache_size est de '.
	$a['setting']." au lieu de 1000 </p>";
      $flag++;
    }
    break;
  case 'shared_buffers':
    if ( $a['setting'] < 640 ){
      print '<p class="warning">Attention le paramètre shared_buffer est de '.
	$a['setting']."au lieu de 640</p>";
      $flag++;
    }
    break;
  case 'sort_mem':
    if ( $a['setting'] < 8192 ){
      print '<p class="warning">Attention le paramètre sort_mem est de '.
	$a['setting']." au lieu de 8192 </p>";
    $flag++;
    }
    break;

  }
 }
if ( $flag == 0 ) {
  echo '<p class="info">La base de données est bien configurée</p>';
 } else {
  echo '<p class="warning">Il y a '.$flag.' paramètre qui sont trop bas</p>';
 }
?>
<FORM action="setup.php" METHOD="post">
<input type="submit" name="go" value="Prêt à commencer la mise à jour ou l'installation?">
</form>
<?
if ( ! isset($_POST['go']) )
	exit();
// Check if account_repository exists
$account=CountSql($cn,
		  "select * from pg_database where datname='account_repository'");

// Create the account_repository
if ($account == 0 ) {
//  ob_start();
  echo "Creation of account_repository";
  
  ExecSql($cn,"create database account_repository encoding='latin1'");
  $cn=DbConnect();
  ExecuteScript($cn,"sql/account_repository/schema.sql");
  ExecuteScript($cn,"sql/account_repository/data.sql");
  echo "Creation of Démo";
  ExecSql($cn,"create database dossier1 encoding='latin1'");
  $cn=DbConnect(1,'dossier');
  ExecuteScript($cn,'sql/dossier1/schema.sql');
  ExecuteScript($cn,'sql/dossier1/data.sql');

  echo "Creation of Modele1";
  ExecSql($cn,"create database mod1 encoding='latin1'");
  $cn=DbConnect(1,'mod');
  ExecuteScript($cn,'sql/mod1/schema.sql');
  ExecuteScript($cn,'sql/mod1/data.sql');

//   $r=system("$psql -U phpcompta mod1 -f sql/mod1/schema.sql",$r);
//   $r=system("$psql -U phpcompta mod1 -f sql/mod1/data.sql",$r);
  //ob_end_clean();

}
// _SERVER["DOCUMENT_ROOT"]
// Test the connection
$a=DbConnect();
if ( $a==false) {
   exit ("<h2 class=\"error\">".__LINE__." test has failed !!!</h2>");

}
if ( ($Res=ExecSql($a,"select  * from ac_users") ) == false ) {
	exit ("<h2 class=\"error\">".__LINE__." test has failed !!!</h2>");
} else 
	print "Connect to database success <br>";
echo "<h2 class=\"info\"> Congratulation : Test successfull</h2>";

echo "<h2 class=\"info\"> Patching databases</h2>";

$cn=DbConnect();
$Resdossier=ExecSql($cn,"select dos_id, dos_name from ac_dossier");
$MaxDossier=pg_NumRows($Resdossier);

for ($e=0;$e < $MaxDossier;$e++) {
  $db_row=pg_fetch_array($Resdossier,$e);
  echo "Patching ".$db_row['dos_name']."<hr>";
  $db=DbConnect($db_row['dos_id'],'dossier');
  if ( GetVersion($db) == 3 ) { 
    ExecuteScript($db,'sql/patch/upgrade4.sql');
      
    $sql="select jrn_def_id from jrn_def ";
    $Res=ExecSql($db,$sql);
    $Max=pg_NumRows($Res);
    for ($seq=0;$seq<$Max;$seq++) {
	    $row=pg_fetch_array($Res,$seq);
	    $sql=sprintf ("create sequence s_jrn_%d",$row['jrn_def_id']);
	    ExecSql($db,$sql);
    }
 } // version == 3
 }

$Resdossier=ExecSql($cn,"select mod_id, mod_name from modeledef");
$MaxDossier=pg_NumRows($Resdossier);

for ($e=0;$e < $MaxDossier;$e++) {
  $db_row=pg_fetch_array($Resdossier,$e);
  echo "Patching ".$db_row['mod_name']."<hr>";
  $db=DbConnect($db_row['mod_id'],'mod');
  if ( GetVersion($db) == 3 ) { 
    ExecuteScript($db,'sql/patch/upgrade4.sql');
      
    $sql="select jrn_def_id from jrn_def ";
    $Res=ExecSql($db,$sql);
    $Max=pg_NumRows($Res);
    for ($seq=0;$seq<$Max;$seq++) {
	    $row=pg_fetch_array($Res,$seq);
	    $sql=sprintf ("create sequence s_jrn_%d",$row['jrn_def_id']);
	    ExecSql($db,$sql);
    }
 } // version == 3
 }
$cn=DbConnect();
if ( GetVersion($cn) == 3 ) {
  ExecuteScript($cn,'sql/patch/ac-upgrade4.sql');
 }
