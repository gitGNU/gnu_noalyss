<style type="text/css">
<!--
body {
   	font-family:Verdana,arial,sans-serif;
	font-size:12px;
	color:blue;
        background-color:#EDF3FF;
 }
h2.info {
	color:green;
	font-size:14px;
	font-family:Verdana,arial,sans-serif;
}
h2.error {
	color:red;
	font-size:14px;
	font-family:Verdana,arial,sans-serif;
}

p.warning  {
   	font-family:Verdana,arial,sans-serif;
	font-size:12px;
	color:red;
        border: 1px solid red;
        padding:30px;
 }
.info {
	color:blue;
	font-size:12px;
	font-family:Verdana,arial,sans-serif;
}
a:hover{
color:blue;
	background-color:blue;
	color:lightgrey;
	}
a { 
color:blue;
padding:5px;
  font-size:15px;
border:groove 2px blue;
  text-decoration:none;
  background-color:lightgrey;
}
</style>
<p align="center">
  <IMG SRC="../image/logo7.gif" alt="Logo PhpCompta">
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
$failed="<span style=\"font-size:18px;color;red\">&#x2716;</span>";
$succeed="<span style=\"font-size:18px;color;green\">&#x2713;</span>";

$inc_path=get_include_path();
/**
 *@brief create correctly the htaccess file
 *@param
 *@param
 *@return
 *@see
 */
function create_htaccess() {
$inc_path=get_include_path();

if ( strpos($inc_path,";") != 0 ) {
  $new_path=$inc_path.';..\..\include;addon';
  $os=0;			/* $os is 0 for windoz */
} else {
  $new_path=$inc_path.':../../include:addon';
  $os=1;			/* $os is 1 for unix */
}

/* If htaccess file doesn't exists we create them here
 * if os == 1 then windows, 0 means Unix
 */
/**
 *@todo remove this test for production
 */
if ( ! DEBUG )
{
$file='..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'.htaccess';
$hFile=@fopen($file,'w+');
if ( ! $hFile )     exit('Impossible d\'&eacute;crire dans le r&eacute;pertoire include');
fwrite($hFile,'order deny,allow'."\n");
fwrite($hFile,'deny from all'."\n");
fclose($hFile);

$file='..'.DIRECTORY_SEPARATOR.'.htaccess';
  $hFile=@fopen($file,'w+');
  if ( ! $hFile )     exit('Impossible d\'&eacute;crire dans le r&eacute;pertoire html');
  $array=array("php_flag  magic_quotes_gpc off",
	       "php_flag session.auto_start on",
	       "php_value max_execution_time 240",
	       "php_value memory_limit 20M",
	       "AddDefaultCharset utf-8",
	       "php_value error_reporting 10239",
	       "php_value post_max_size 20M",
	       "php_flag short_open_tag on",
	       "php_value upload_max_filesize 20M",
	       "php_value session.use_trans_sid 1",
	       "php_value session.use_cookies 1",
	       "php_flag session.use_only_cookies on");

  if ( $os == 0 )
    fwrite($hFile,'php_value include_path .;..\..\include;..\include;addon'."\n");
  else
    fwrite($hFile,'php_value include_path .:../../include:../include:addon'."\n");
  foreach ($array as $value ) fwrite($hFile,$value."\n");
  fclose($hFile);
}

if ( strpos($inc_path,";") != 0 ) {
  $new_path=$inc_path.';..\..\include;addon';
  $os=0;			/* $os is 0 for windoz */
} else {
  $new_path=$inc_path.':../../include:addon';
  $os=1;			/* $os is 1 for unix */
}
set_include_path($new_path);
/* The config file is created here */
if (isset($_POST['save_config'])) {
  require_once('config_file.php');
  $url=config_file_create($_POST,1,$os);
echo '
<form method="post" >
    Les informations sont sauv&eacute;es vous pouvez continuer
<input type="submit" value="Continuer">
</form>';
create_htaccess();
 exit();
 }

if ( ! file_exists('..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'config.inc.php')) {
  /* if the config file is not found we propose to create one */
  if ( is_writable ('..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'constant.php') == false ) {
    echo '<h2 class="error"> Ecriture non possible </h2><p class="warning"> On ne peut pas &eacute;crire dans le r&eacute;pertoire de phpcompta, changez-en les droits </p>';
    exit();
  }

  echo '<form method="post">';
  echo '<h1 class="info">Entrez les informations n&eacute;cessaires &agrave; phpcompta</h1>';
  require_once('config_file.php');
  
  echo config_file_form();
  echo HtmlInput::submit('save_config','Sauver la configuration');
  echo '</form>';
  exit();
  }
create_htaccess();

//----------------------------------------------------------------------
// End functions
//
//----------------------------------------------------------------------

// Verify some PHP parameters
// magic_quotes_gpc = Off
// magic_quotes_runtime = Off
// magic_quotes_sybase = Off
// include_path
}
?>
<?
require_once('config_file.php');
include_once('constant.php');
require_once('class_database.php');

?>
<h2>Info</h2>
Vous utilisez le domaine <?php echo domaine; ?> 
<h2>Php setting</h2>
<?php

$flag_php=0;

//ini_set("memory_limit","200M");
echo "<ul style=\"list-style-type: square;\">";
foreach (array('magic_quotes_gpc','magic_quotes_runtime') as $a) {
echo "<li>";
  if ( ini_get($a) == false ) print $a.': '.$succeed;
  else {
        print $a.': '.$failed;
	print ("<h2 class=\"error\">$a a une mauvaise valeur !</h2>");
	$flag_php++;
  }

echo "</li>";
}
$module=get_loaded_extensions();

echo "<li>";
if ( in_array('pgsql',$module) == false )
{
  echo 'module PGSQL '.$failed;
  print '<span class="warning">D&eacute;sol&eacute; mais soit vous n\'avez pas install&eacute; ou activé l\'extension(pgsql)  pour postgresql soit php n\'a pas pas &eacute;t&eacute; compil&eacute; avec les bonnes options </span>';
  $flag_php++;
} else echo 'module PGSQL '.$succeed;
echo "</li>";

echo "<li>";
if ( in_array('bcmath',$module) == false )
{
  echo 'module BCMATH ok '.$failed;
  print '<span class="warning">D&eacute;sol&eacute; mais soit vous n\'avez pas install&eacute; ou activé l\'extension (bcmath)  pour bcmath soit php n\'a pas pas &eacute;t&eacute; compil&eacute; avec les bonnes options </span>';
  $flag_php++;
} else echo 'module BCMATH '.$succeed;
echo "</li>";

echo "<li>";
if ( in_array('gettext',$module) == false )
{
  echo 'module GETTEXT '.$failed;
  print '<span class="warning">D&eacute;sol&eacute; mais soit vous n\'avez pas install&eacute; ou activé l\'extension  (gettext) pour gettext soit php n\'a pas pas &eacute;t&eacute; compil&eacute; avec les bonnes options </span>';
  $flag_php++;
} else echo 'module GETTEXT '.$succeed;
echo "</li>";

echo "<li>";
if ( in_array('zip',$module) == false )
{
  echo 'module ZIP '.$failed;
  print '<span class="warning">D&eacute;sol&eacute; mais soit vous n\'avez pas install&eacute; ou activé l\'extension (zip) pour zip soit php n\'a pas pas &eacute;t&eacute; compil&eacute; avec les bonnes options </span>';
  $flag_php++;
} else echo 'module ZIP '.$succeed;
echo "</li>";

if ( ini_get("max_execution_time") < 60 )  {
        echo "<li>";
        echo 'Avertissement : '.$failed;
	print '<span class="info"> max_execution_time devrait être de 60 minimum</span>';
        echo "</li>";
}

if ( ini_get("session.auto_start") == false )  {
        echo "<li>";
        echo 'Avertissement : '.$failed;
	print '<span class="warning"> session.auto_start doit être mis à vrai</span>';
        echo "</li>";
	$flag_php++;
}

if ( ini_get("session.use_trans_sid") == false )  {
        echo "<li>";
        echo 'Avertissement : '.$failed;
	print '<span class="warning"> avertissement session.use_trans_sid should be set to true </span>';
        echo "</li>";
}

echo "<li>";
if ( strpos($inc_path,"../include") == 0 && strpos ($inc_path,'..\\include') == 0)
{
    echo 'variable include_path: '.$failed;
  print ("<span class=\"warning\"> include_path incorrect  !!!".$inc_path."</span>");
	$flag_php++;
}
 else
   if ( strpos($inc_path,"addon") == 0) {
       echo 'variable include_path: '.$failed;
    print ("<span class=\"warning\">2 include_path incorrect  !!!".$inc_path."</span>");
	$flag_php++;
 }else
   echo 'variable include_path: '.$succeed;
echo "</li>";

 echo "</ul>";
if ( $flag_php==0 ) {
	echo '<p class="info"> php.ini est bien configur&eacute; '.$succeed.'</p>';
} else {
	echo '<p class="warning"> php mal configur&eacute; '.$failed.'</p>';
	exit -1;
}
/* check user */
$cn=new Database(-1,'template');

?>
<h2>Database version </h2>
<?php
 // Verify Psql version
 //--
$sql="select setting from pg_settings where name='server_version'";
$version=$cn->get_value($sql);

var_dump($version);

if ( $version[0] < 8 ||
     ($version[0]=='8' && $version[2]<4)
     ) 
  {
?>
  <p><?=$failed?> Vous devez absolument utiliser au minimum une version 8.4 de PostGresql, si votre distribution n'en
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
$Res=$cn->count_sql($sql);
if ( $Res==0) { ?>
<p><?=$failed?> Vous devez installer le langage plpgsql pour permettre aux fonctions SQL de fonctionner.</p>
<p>Pour cela, sur la ligne de commande en tant qu\'utilisateur postgres, faites createlang plpgsql template1
</p>

<?php exit(); }

include_once('ac_common.php');

// Memory setting
//--
$sql="select name,setting
      from pg_settings
      where
      name in ('effective_cache_size','shared_buffers')";
$cn->exec_sql($sql);
$flag=0;
for ($e=0;$e<$cn->size();$e++) {
  $a=$cn->fetch($e);
  switch ($a['name']){
  case 'effective_cache_size':
    if ( $a['setting'] < 1000 ){
      
      print '<p class="warning">'.$failed.'Attention le param&egrave;tre effective_cache_size est de '.
	$a['setting']." au lieu de 1000 </p>";
      $flag++;
    }
    break;
  case 'shared_buffers':
    if ( $a['setting'] < 640 ){
      print '<p class="warning">'.$failed.'Attention le param&egrave;tre shared_buffer est de '.
	$a['setting']."au lieu de 640</p>";
      $flag++;
    }
    break;
  }
 }
if ( $flag == 0 ) {
  echo '<p class="info"> La base de donn&eacute;es est bien configur&eacute;e '.$succeed.'</p>';
 } else {
  echo '<p class="warning">'.$failed.'Il y a '.$flag.' param&egrave;tre qui sont trop bas</p>';
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
$account=$cn->count_sql("select * from pg_database where datname=lower('".domaine."account_repository')");

// Create the account_repository
if ($account == 0 ) {

  echo "Creation of ".domaine."account_repository";
  if ( ! DEBUG) ob_start();
  $cn->exec_sql("create database ".domaine."account_repository encoding='utf8'");
  $cn=new Database();
  $cn->start();
  $cn->execute_script("sql/account_repository/schema.sql");
  $cn->execute_script("sql/account_repository/data.sql");
  $cn->execute_script("sql/account_repository/constraint.sql");
  $cn->commit($cn);

 if ( ! DEBUG) ob_end_clean();

  echo "Creation of Modele1";
  if ( ! DEBUG) ob_start();
  $cn->exec_sql("create database ".domaine."mod1 encoding='utf8'");

  $cn=new Database(1,'mod');
  $cn->start();
  $cn->execute_script('sql/mod1/schema.sql');
  $cn->execute_script('sql/mod1/data.sql');
  $cn->execute_script('sql/mod1/constraint.sql');
  $cn->commit();

  if ( ! DEBUG) ob_end_clean();

  echo "Creation of Modele2";
  $cn->exec_sql("create database ".domaine."mod2 encoding='utf8'");
  $cn=new Database(2,'mod');
  $cn->start();
  if ( ! DEBUG) { ob_start();  }
  $cn->execute_script('sql/mod1/schema.sql');
  $cn->execute_script('sql/mod2/data.sql');
  $cn->execute_script('sql/mod1/constraint.sql');
  $cn->commit();

 if ( ! DEBUG) ob_end_clean();

 }// end if
// Add a french accountancy model
//--
$cn=new Database();

echo "<h2 class=\"info\"> F&eacute;licitation : Installation r&eacute;ussie</h2>";

echo '<hr>';
echo "<h1>Mise a jour du systeme</h1>";
echo "<h2 > Mise &agrave; jour dossier</h2>";

$Resdossier=$cn->exec_sql("select dos_id, dos_name from ac_dossier");
$MaxDossier=$cn->size($Resdossier);

//----------------------------------------------------------------------
// Upgrade the folders
//----------------------------------------------------------------------

for ($e=0;$e < $MaxDossier;$e++) {
  $db_row=$cn->fetch($e);
  echo "<h3>Patching ".$db_row['dos_name'].'</h3>';
  $db=new Database($db_row['dos_id'],'dos');
  $db->apply_patch($db_row['dos_name']);
 }

//----------------------------------------------------------------------
// Upgrade the template
//----------------------------------------------------------------------
$Resdossier=$cn->exec_sql("select mod_id, mod_name from modeledef");
$MaxDossier=$cn->size();
echo '<hr>';
echo "<h2>Mise &agrave; jour mod&egrave;le</h2>";

for ($e=0;$e < $MaxDossier;$e++) {
  $db_row=$cn->fetch($e);
  echo "<h3>Patching ".$db_row['mod_name']."</h3>";
  $db=new Database($db_row['mod_id'],'mod');
  $db->apply_patch($db_row['mod_name']);
 }

//----------------------------------------------------------------------
// Upgrade the account_repository
//----------------------------------------------------------------------
echo '<hr>';
 echo "<h2>Mise &agrave; jour Repository</h2>";
 $cn=new Database();
 if ( DEBUG == false ) ob_start();
 $MaxVersion=DBVERSIONREPO-1;
 for ($i=4;$i<= $MaxVersion;$i++)
   {
 	if ( $cn->get_version() <= $i ) {
 	  $cn->execute_script('sql/patch/ac-upgrade'.$i.'.sql');
 	}
   }

 if (! DEBUG) ob_end_clean();
 echo "<p class=\"warning\">Tout est install&eacute;</p>";
?>
<A HREF="../index.php">Connectez-vous à PhpCompta</A>
