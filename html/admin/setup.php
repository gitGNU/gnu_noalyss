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
/* $Revision*/
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/*!\file
 * \brief This file permit to upgrade a version of phpcompta, it should be 
 *        used and immediately delete after an upgrade.
 *        This file is included in each release  for a new upgrade
 * 
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
function GetVersion($p_cn) {
	$Res=ExecSql($p_cn,"select val from version");
	$a=pg_fetch_array($Res,0);
	return $a['val'];
}
/*!  ExecuteScript
 **************************************************
 * \brief Execute a sql script
 *        
 * \param $p_cn database 
 * \param $script script name
 */
function ExecuteScript($p_cn,$script) {

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
    if ( ExecSql($p_cn,$sql,false) == false ) {
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
/*! \brief loop to apply all the path to a folder or 
 *         a template
 * \param $p_cn database connexion
 * \param $p_name database name
 *
 */
function apply_patch($p_cn,$p_name)
{
  $MaxVersion=40;
  echo '<ul>';
  for ( $i = 4;$i <= $MaxVersion;$i++)
	{
	$to=$i+1;
	  if ( GetVersion($p_cn) <= $i ) { 
	  echo "<li>Patching ".$p_name.
		" from the version ".GetVersion($p_cn)." to $to</h3> </li>";

		ExecuteScript($p_cn,'sql/patch/upgrade'.$i.'.sql');
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
			ExecuteScript($p_cn,'sql/patch/upgrade17.sql');
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
		  ExecuteScript($p_cn,"sql/patch/upgrade36.".$country.".sql");
		  ExecSql($p_cn,'update tmp_pcmn set pcm_type=find_pcm_type(pcm_val)');
		}
	  if ( DEBUG == 'false') ob_end_clean();
	}
	}
  echo '</ul>';
}
//----------------------------------------------------------------------
// End functions
//
//----------------------------------------------------------------------

// Verify some PHP parameters
// magic_quotes_gpc = Off
// magic_quotes_runtime = Off
// magic_quotes_sybase = Off
// include_path

?>
<h2>Info</h2>
Vous utilisez le domaine <?php echo domaine; ?>
<h2>Php setting</h2>
<?php

$flag_php=0;

//ini_set("memory_limit","200M");
foreach (array('magic_quotes_gpc','magic_quotes_runtime') as $a) {

  if ( ini_get($a) == false ) print $a.': Ok  <br>';
  else {
	print ("<h2 class=\"error\">$a has a bad value  !!!</h2>");
	$flag_php++;
  }

}
$module=get_loaded_extensions();
if ( in_array('pgsql',$module) == false ) 
{
  print '<h2 class="error">D&eacute;sol&eacute; mais soit vous n\'avez pas install&eacute; le package  pour postgresql soit php n\'a pas pas &eacute;t&eacute; compil&eacute; avec les bonnes options </h2>';
  $flag_php++;
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
	echo '<p class="info">php.ini est bien configur&eacute;</p>';
} else {
	echo '<p class="error"> php mal configur&eacute;</p>';
	exit -1;
}
$cn=DbConnect(-2,'template1');

if ($cn == false ) {
  print "<p> Vous devez absolument taper dans une console la commande 'createuser -A -d -P  phpcompta et vous donnez dany comme mot de passe (voir la documentation)' </p>
<p>Ces commandes cr&eacute;eront l'utilisateur phpcompta avec le droit de cr&eacute;er des bases de donn&eacute;.</p>";
  exit();
 }
?>
<h2>Database version </h2>
<?php
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
vos bases de donn&eacute;es en 8
</p>
<?php exit(); //'
}

?>
<h2>Database Setting</h2> 
<?php
// Language plsql is installed 
//--
$sql="select lanname from pg_language where lanname='plpgsql'";
$Res=CountSql($cn,$sql);
if ( $Res==0) { ?>
<p> Vous devez installer le langage plpgsql pour permettre aux fonctions SQL de fonctionner.</p>
<p>Pour cela, sur la ligne de commande, faites createlang plpgsql template1
</p>

<?php exit(); }

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
      print '<p class="warning">Attention le param&egrave;tre effective_cache_size est de '.
	$a['setting']." au lieu de 1000 </p>";
      $flag++;
    }
    break;
  case 'shared_buffers':
    if ( $a['setting'] < 640 ){
      print '<p class="warning">Attention le param&egrave;tre shared_buffer est de '.
	$a['setting']."au lieu de 640</p>";
      $flag++;
    }
    break;
  case 'work_mem':
    if ( $a['setting'] < 8192 ){
      print '<p class="warning">Attention le param&egrave;tre work_mem est de '.
	$a['setting']." au lieu de 8192 </p>";
    $flag++;
    }
    break;

  }
 }
if ( $flag == 0 ) {
  echo '<p class="info">La base de donn&eacute;es est bien configur&eacute;e</p>';
 } else {
  echo '<p class="warning">Il y a '.$flag.' param&egrave;tre qui sont trop bas</p>';
 }
if ( ! isset($_POST['go']) ) {
?>
<FORM action="setup.php" METHOD="post">
<input type="submit" name="go" value="Pr&ecirc;t &agrave; commencer la mise &agrave; jour ou l'installation?">
</form>
<?php
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
  StartSql($cn);
  ExecuteScript($cn,"sql/account_repository/schema.sql");
  ExecuteScript($cn,"sql/account_repository/data.sql");
  ExecuteScript($cn,"sql/account_repository/constraint.sql");
  Commit($cn);

 if ( DEBUG=='false') ob_end_clean();

  echo "Creation of Modele1";
  if ( DEBUG=='false') ob_start();  
  ExecSql($cn,"create database ".domaine."mod1 encoding='latin1'");
  $cn=DbConnect(1,'mod');
  StartSql($cn);
  ExecuteScript($cn,'sql/mod1/schema.sql');
  ExecuteScript($cn,'sql/mod1/data.sql');
  ExecuteScript($cn,'sql/mod1/constraint.sql');
  Commit($cn);
  if ( DEBUG=='false') ob_end_clean();

  echo "Creation of Modele2";
  ExecSql($cn,"create database ".domaine."mod2 encoding='latin1'");
  $cn=DbConnect(2,'mod');
  StartSql($cn);
  if ( DEBUG=='false') { ob_start();  }
  ExecuteScript($cn,'sql/mod1/schema.sql');
  ExecuteScript($cn,'sql/mod2/data.sql');
  ExecuteScript($cn,'sql/mod1/constraint.sql');
  Commit($cn);

 if ( DEBUG=='false') ob_end_clean();

 }// end if
// Add a french accountancy model
//--
$cn=DbConnect();

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
echo "<h2 class=\"info\"> Congratulation : Installation r&eacute;ssie</h2>";

echo '<hr>';
echo "<h1>Mise a jour du systeme</h1>";
echo "<h2 > Mise &agrave; jour dossier</h2>";

$cn=DbConnect();
$Resdossier=ExecSql($cn,"select dos_id, dos_name from ac_dossier");
$MaxDossier=pg_NumRows($Resdossier);

//----------------------------------------------------------------------
// Upgrade the folders
//----------------------------------------------------------------------

for ($e=0;$e < $MaxDossier;$e++) {
  $db_row=pg_fetch_array($Resdossier,$e);
  echo "<h3>Patching ".$db_row['dos_name'].'</h3>';
  $db=DbConnect($db_row['dos_id'],'dossier');
  apply_patch($db,$db_row['dos_name']);
 }

//----------------------------------------------------------------------
// Upgrade the template
//----------------------------------------------------------------------
$Resdossier=ExecSql($cn,"select mod_id, mod_name from modeledef");
$MaxDossier=pg_NumRows($Resdossier);
echo '<hr>';
echo "<h2>Mise &agrave; jour mod&egrave;le</h2>";

for ($e=0;$e < $MaxDossier;$e++) {
  $db_row=pg_fetch_array($Resdossier,$e);
  echo "<h3>Patching ".$db_row['mod_name']."</h3>";
  $db=DbConnect($db_row['mod_id'],'mod');
  apply_patch($db,$db_row['mod_name']);
 }
echo '</ul>';
//----------------------------------------------------------------------
// Upgrade the account_repository
//----------------------------------------------------------------------
echo '<hr>';
 echo "<h2>Mise &agrave; jour Repository</h2>"; 
 $cn=DbConnect(); 
 if ( DEBUG == 'false') ob_start(); 
 $MaxVersion=7; 
 for ($i=4;$i<= $MaxVersion;$i++) 
   { 
 	if ( GetVersion($cn) <= $i ) { 
 	  ExecuteScript($cn,'sql/patch/ac-upgrade'.$i.'.sql'); 
 	} 
   } 

 if (DEBUG=='false') ob_end_clean(); 
 echo "<h2 class=\"info\">Voil&agrave; tout est install&eacute; ;-)</h2>"; 
?>
