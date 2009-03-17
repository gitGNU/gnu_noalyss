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
  $os=0;			/* $os is 0 for windoz */
} else {
  $new_path=$inc_path.':../../include:addon';
  $os=1;			/* $os is 1 for unix */
}
set_include_path($new_path);

require_once('config_file.php');
/* The config file is created here */
if (isset($_POST['save_config'])) {
  $url=config_file_create($_POST,1,$os);
 }


if ( ! file_exists('..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'config.inc.php')) {
  /* if the config file is not found we propose to create one */
  if ( is_writable ('..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'constant.php') == false ) {
    echo '<h2 class="error"> On ne peut pas écrire dans le répertoire de phpcompta, changez-en les droits </h2>';
    exit();
  }

  echo '<form method="post">';
  echo '<h1 class="info">Entrez les informations n&eacute;cessaires &agrave; phpcompta</h1>';
  echo config_file_form();
  echo HtmlInput::submit('save_config','Sauver la configuration');
  echo '</form>';
  exit();
  }
include_once('constant.php');
include_once('postgres.php');
include_once('debug.php');
include_once('ac_common.php');
/* If htaccess file doesn't exists we create them here
 * if os == 1 then windows, 0 means Unix 
 */
$file='..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'.htaccess';
if ( ! file_exists ( $file) ) {
  $hFile=@fopen($file,'a+');
  if ( ! $hFile )     exit('Impossible d\'&eacute;crire dans le r&eacute;pertoire include');
  fwrite($hFile,'order deny,allow'."\n");
  fwrite($hFile,'deny from all'."\n");
  fclose($hFile);
}

$file='..'.DIRECTORY_SEPARATOR.'.htaccess';
if ( ! file_exists ( $file) ) {
  $hFile=@fopen($file,'a+');
  if ( ! $hFile )     exit('Impossible d\'&eacute;crire dans le r&eacute;pertoire html');
  $array=array("php_flag  magic_quotes_gpc off",
	       "php_flag session.auto_start on",
	       "php_value max_execution_time 240",
	       "php_value memory_limit 12M",
	       "AddDefaultCharset utf-8",
	       "php_value error_reporting 10239",
		  "php_flag short_open_tag on",
	       "php_value upload_max_filesize 10M",
		"php_value session.use_trans_sid 1");

  if ( $os == 0 )
    fwrite($hFile,'php_value include_path .;..\..\include;..\include;addon'."\n");
  else
    fwrite($hFile,'php_value include_path .:../../include:../include:addon'."\n");
  foreach ($array as $value ) fwrite($hFile,$value."\n");
  fclose($hFile);
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

if ( in_array('bcmath',$module) == false ) 
{
  print '<h2 class="error">D&eacute;sol&eacute; mais soit vous n\'avez pas install&eacute; le package  pour bcmath soit php n\'a pas pas &eacute;t&eacute; compil&eacute; avec les bonnes options </h2>';
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
  <p> Vous devez absolument utiliser au minimum une version 8.2 de PostGresql, si votre distribution n'en
offre pas, installez en une en la compilant. </p><p>Lisez attentivement la notice sur postgresql.org pour migrer
vos bases de donn&eacute;es 
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
      name in ('effective_cache_size','shared_buffers')";
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
  ExecSql($cn,"create database ".domaine."account_repository encoding='utf8'");
  $cn=DbConnect();
  StartSql($cn);
  execute_script($cn,"sql/account_repository/schema.sql");
  execute_script($cn,"sql/account_repository/data.sql");
  execute_script($cn,"sql/account_repository/constraint.sql");
  Commit($cn);

 if ( DEBUG=='false') ob_end_clean();

  echo "Creation of Modele1";
  if ( DEBUG=='false') ob_start();  
  ExecSql($cn,"create database ".domaine."mod1 encoding='utf8'");
  $cn=DbConnect(1,'mod');
  StartSql($cn);
  execute_script($cn,'sql/mod1/schema.sql');
  execute_script($cn,'sql/mod1/data.sql');
  execute_script($cn,'sql/mod1/constraint.sql');
  Commit($cn);
  if ( DEBUG=='false') ob_end_clean();

  echo "Creation of Modele2";
  ExecSql($cn,"create database ".domaine."mod2 encoding='utf8'");
  $cn=DbConnect(2,'mod');
  StartSql($cn);
  if ( DEBUG=='false') { ob_start();  }
  execute_script($cn,'sql/mod1/schema.sql');
  execute_script($cn,'sql/mod2/data.sql');
  execute_script($cn,'sql/mod1/constraint.sql');
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
echo "<h2 class=\"info\"> F&eacute;licitation : Installation r&eacute;ussie</h2>";

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
 $MaxVersion=9; 
 for ($i=4;$i<= $MaxVersion;$i++) 
   { 
 	if ( get_version($cn) <= $i ) { 
 	  execute_script($cn,'sql/patch/ac-upgrade'.$i.'.sql'); 
 	} 
   } 

 if (DEBUG=='false') ob_end_clean(); 
 echo "<h2 class=\"info\">Voil&agrave; tout est install&eacute; ;-) </H2>"; 
?>
