
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
/*!\file
 * \brief This file permit to upgrade a version of phpcompta, it should be 
 *        used and immediately delete after an upgrade.
 *        This file is included in each release  for a new upgrade
 * 
 * \todo remove the rebuild of mod2 (drop / create) for the next version
 *        the mod2 from the version < 2.0 are full of bugs
 */
$inc_path=get_include_path();
if ( strpos($inc_path,";") != 0 ) {
  $new_path=$inc_path.';..\..\include;addon';
 } else {
  $new_path=$inc_path.':../../include:addon';
 }

set_include_path($new_path);

include_once('constant.php');
include_once('postgres.php');
include_once('debug.php');
include_once('ac_common.php');
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
    if ( ExecSql($p_cn,$sql) == false ) {
	    Rollback($p_cn);
	    if ( DEBUG=='false' ) ob_end_flush();
	    print "ERROR : $sql";
            exit();
	    }
    $sql="";
    $flag_function=false;
    print "<hr>";
  } // while (feof)
  fclose($hf);
}

// Verify some PHP parameters
// magic_quotes_gpc = Off
// magic_quotes_runtime = Off
// magic_quotes_sybase = Off
// include_path

?>
<h2>Info</h2>
Vous utilisez le domaine <? echo domaine; ?>
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
if ( ini_get("max_execution_time") < 60 )  {
	print '<h2 class="info"> max_execution_time should be set to 60 minimum</h2>';
}
if ( ini_get("session.auto_start") == false )  {
	print '<h2 class="error"> session.auto_start must be set to true </h2>';
	$flag_php++;
}
if ( ini_get("session.use_trans_sid") == false )  {
	print '<h2 class="error"> avertissement session.use_trans_sid should be set to true </h2>';
}
if ( ereg("..\/include",$inc_path) == 0 and ereg("..\\include",$inc_path) == 0)
{
  print ("<h2 class=\"error\">include_path incorrect  !!!".$inc_path."</h2>");
	$flag_php++;
}
 else
 if ( ereg("addon",$inc_path) == 0) {
  print ("<h2 class=\"error\">include_path incorrect  !!!".$inc_path."</h2>");
	$flag_php++;
 }else
   print 'include_path : ok ('.$inc_path.')<br>';

if ( $flag_php==0 ) {
	echo '<p class="info">php.ini est bien configuré</p>';
} else {
	echo '<p class="error"> php mal configuré</p>';
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
<h2>Database version </h2>
<?
 // Verify Psql version
 //--
$sql="select setting from pg_settings where name='server_version'";
$Res=ExecSql($cn,$sql);
$row=pg_fetch_array($Res,0);
$version=$row[0];

var_dump($version);

if ( $version[0]  != '8' ) {
?>
  <p> Vous devez absolument utiliser au minimum une version 8 de PostGresql, si votre distribution n'en
offre pas, installez en une en la compilant. </p><p>Lisez attentivement la notice sur postgresql.org pour migrer
vos bases de données en 8
</p>
<? exit(); //'
}

?>
<h2>Database Setting</h2> 
<?
// Language plsql is installed 
//--
$sql="select lanname from pg_language where lanname='plpgsql'";
$Res=CountSql($cn,$sql);
if ( $Res==0) { ?>
<p> Vous devez installer le langage plpgsql pour permettre aux fonctions SQL de fonctionner.</p>
<p>Pour cela, sur la ligne de commande, faites 
createlang plpgsql pour chaque base de données que vous possédez (y compris template0 et template1).
</p>
<p>Pour afficher toutes les bases de données, tapez sur la ligne de commande "psql -l"</p>
<? exit(); }

// Memory setting
//--
$sql="select name,setting 
      from pg_settings 
      where 
      name in ('effective_cache_size','shared_buffers','work_mem')";
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
  case 'work_mem':
    if ( $a['setting'] < 8192 ){
      print '<p class="warning">Attention le paramètre work_mem est de '.
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
if ( ! isset($_POST['go']) ) {
?>
<FORM action="setup.php" METHOD="post">
<input type="submit" name="go" value="Prêt à commencer la mise à jour ou l'installation?">
</form>
<?
}
if ( ! isset($_POST['go']) )
	exit();
// Check if account_repository exists
$account=CountSql($cn,
		  "select * from pg_database where datname='".domaine."account_repository'");

// Create the account_repository
if ($account == 0 ) {

  echo "Creation of ".domaine."account_repository";
  if ( DEBUG=='false') ob_start();  
  ExecSql($cn,"create database ".domaine."account_repository encoding='latin1'");
  $cn=DbConnect();
  ExecuteScript($cn,"sql/account_repository/schema.sql");
  ExecuteScript($cn,"sql/account_repository/data.sql");
 if ( DEBUG=='false') ob_end_clean();
  echo "Creation of Démo";
  if ( DEBUG=='false') ob_start();  
  ExecSql($cn,"create database ".domaine."dossier1 encoding='latin1'");
  $cn=DbConnect(1,'dossier');
  ExecuteScript($cn,'sql/dossier1/schema.sql');
  ExecuteScript($cn,'sql/dossier1/data.sql');
 if ( DEBUG=='false') ob_end_clean();

  echo "Creation of Modele1";
  if ( DEBUG=='false') ob_start();  
  ExecSql($cn,"create database ".domaine."mod1 encoding='latin1'");
  $cn=DbConnect(1,'mod');
  ExecuteScript($cn,'sql/mod1/schema.sql');
  ExecuteScript($cn,'sql/mod1/data.sql');
 if ( DEBUG=='false') ob_end_clean();
 }// end if
// Add a french accountancy model
//--
$cn=DbConnect();
$Res=CountSql($cn,"select * from modeledef where mod_id=2");
// ----------------------------------------------------------------------
// to be remove 
if ( $Res == 1 )
 {
  $cn=DbConnect();
  ExecSql($cn,"drop database ".domaine."mod2;");
  ExecSql($cn,"delete from modeledef where mod_id=2");
 }
//----------------------------------------------------------------------
$Res=CountSql($cn,"select * from modeledef where mod_id=2");
if ( $Res == 0) {
  echo "Creation of Modele2";
  ExecSql($cn,"create database ".domaine."mod2 encoding='latin1'");
  $cn=DbConnect(2,'mod');
  if ( DEBUG=='false') { ob_start();  }
  ExecuteScript($cn,'sql/mod2/schema.sql');
  ExecuteScript($cn,'sql/mod2/data.sql');
  $sql="INSERT INTO modeledef VALUES (2, '(FR) Basique', 'Comptabilité Française, tout doit être adaptée');";
  $cn=DbConnect();
  ExecSql($cn,$sql);
 if ( DEBUG=='false') ob_end_clean();
}
// 
// Test the connection
//--
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
  $db=DbConnect($db_row['dos_id'],'dossier');
  echo "Patching ".$db_row['dos_name']." from the version ".GetVersion($db)."<hr>";
if ( DEBUG=='false' ) ob_start();
  if ( GetVersion($db) <= 4 ) { 
    ExecuteScript($db,'sql/patch/upgrade4.sql');
      
    $sql="select jrn_def_id from jrn_def ";
    $Res=ExecSql($db,$sql);
    $Max=pg_NumRows($Res);
    for ($seq=0;$seq<$Max;$seq++) {
	    $row=pg_fetch_array($Res,$seq);
	    $sql=sprintf ("create sequence s_jrn_%d",$row['jrn_def_id']);
	    ExecSql($db,$sql);
    }
  } // version == 4
  //--
  // update to the version 5
  //--
  if ( GetVersion($db) == 5 ) { 
    ExecuteScript($db,'sql/patch/upgrade5.sql');
  } // version == 5


  //--
  // update to the version 7
  //--
  if ( GetVersion($db) == 6 ) { 
    ExecuteScript($db,'sql/patch/upgrade6.sql');
  } // version == 6

  //--
  // update to the version 8
  //--
  if ( GetVersion($db) == 7 ) { 
    ExecuteScript($db,'sql/patch/upgrade7.sql');
    // now we use sequence instead of computing a max
    // 
    $Res2=ExecSql($db,'select coalesce(max(jr_grpt_id),1) as l from jrn');
    $Max2= pg_NumRows($Res2) ;
    if ( $Max2 == 1) {
      $Row=pg_fetch_array($Res2,0);
      var_dump($Row);
      $M=$Row['l'];
      ExecSql($db,"select setval('s_grpt',$M,true)");
    }
  } // version == 7
  // version 8 -> 9
  if ( GetVersion($db) == 8 ) { 
    ExecuteScript($db,'sql/patch/upgrade8.sql');
  } // version == 9->10
  if ( GetVersion($db) == 9 ) { 
    ExecuteScript($db,'sql/patch/upgrade9.sql');
  } // version == 10->11
  if ( GetVersion($db) == 10 ) { 
    ExecuteScript($db,'sql/patch/upgrade10.sql');
  } // version 
  if ( GetVersion($db) == 11 ) { 
    ExecuteScript($db,'sql/patch/upgrade11.sql');
  } // version 
  if ( GetVersion($db) == 12 ) { 
    ExecuteScript($db,'sql/patch/upgrade12.sql');
  } // version 

  if ( GetVersion($db) == 13 ) { 
    ExecuteScript($db,'sql/patch/upgrade13.sql');
  } // version 


if ( DEBUG == 'false') ob_end_clean();
 }//for

$Resdossier=ExecSql($cn,"select mod_id, mod_name from modeledef");
$MaxDossier=pg_NumRows($Resdossier);
echo "Upgrading Dossier";
for ($e=0;$e < $MaxDossier;$e++) {
  $db_row=pg_fetch_array($Resdossier,$e);
  echo "Patching ".$db_row['mod_name']."<hr>";
  $db=DbConnect($db_row['mod_id'],'mod');
if (DEBUG == 'false' ) ob_start();
  if ( GetVersion($db) <= 4 ) { 
    ExecuteScript($db,'sql/patch/upgrade4.sql');
      
    $sql="select jrn_def_id from jrn_def ";
    $Res=ExecSql($db,$sql);
    $Max=pg_NumRows($Res);
    for ($seq=0;$seq<$Max;$seq++) {
	    $row=pg_fetch_array($Res,$seq);
	    $sql=sprintf ("create sequence s_jrn_%d",$row['jrn_def_id']);
	    ExecSql($db,$sql);
    }
 } // version == 4
  if ( GetVersion($db) == 5 ) { 
    ExecuteScript($db,'sql/patch/upgrade5.sql');
  } // version == 5


  //--
  // update to the version 7
  //--
  if ( GetVersion($db) == 6 ) { 
    ExecuteScript($db,'sql/patch/upgrade6.sql');
  } // version == 6

  //--
  // update to the version 8
  //--
  if ( GetVersion($db) == 7 ) { 
    ExecuteScript($db,'sql/patch/upgrade7.sql');
    // now we use sequence instead of computing a max
    // 
    $Res2=ExecSql($db,'select coalesce(max(jr_grpt_id),1) as l from jrn');
    $Max2= pg_NumRows($Res2) ;
    if ( $Max2 == 1) {
      $Row=pg_fetch_array($Res2,0);
      $M=$Row['l'];
      ExecSql($db,"select setval('s_grpt',$M,true)");
    }
  } // version == 8
  //--
  // update to the version 9
  //--
  if ( GetVersion($db) == 8 ) { 
    ExecuteScript($db,'sql/patch/upgrade8.sql');
  } // version == 9
  // update to the version 10
  //--
  if ( GetVersion($db) == 9 ) { 
    ExecuteScript($db,'sql/patch/upgrade9.sql');
  } // version == 9
  if ( GetVersion($db) == 10 ) { 
    ExecuteScript($db,'sql/patch/upgrade10.sql');
  } // version 
  if ( GetVersion($db) == 11 ) { 
    ExecuteScript($db,'sql/patch/upgrade11.sql');
  } // version 
  if ( GetVersion($db) == 12 ) { 
    ExecuteScript($db,'sql/patch/upgrade12.sql');
  } // version 
  if ( GetVersion($db) == 13 ) { 
    ExecuteScript($db,'sql/patch/upgrade13.sql');
  } // version 

if ( DEBUG == 'false') ob_end_clean();
 }

echo "Upgrading Repository";
$cn=DbConnect();
if ( DEBUG == 'false') ob_start();
if ( GetVersion($cn) <= 4 ) {
  ExecuteScript($cn,'sql/patch/ac-upgrade4.sql');
 }
if ( GetVersion($cn) == 5 ) {
  ExecuteScript($cn,'sql/patch/ac-upgrade5.sql');
 }
if ( GetVersion($cn) == 6 ) {
  ExecuteScript($cn,'sql/patch/ac-upgrade6.sql');
 }
if ( GetVersion($cn) == 7 ) {
  ExecuteScript($cn,'sql/patch/ac-upgrade7.sql');
 }

if (DEBUG=='false') ob_end_clean();
echo "<h2 class=\"info\">Voilà tout est installé ;-)</h2>";
