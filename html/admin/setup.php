<style type="text/css">
<!--
h2.info {
	color:green;
	font-size:20px;
}
h2.error {
	color:red;
	font-size:20px;
}
-->
</style>

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


// Verify some PHP parameters
// magic_quotes_gpc = Off
// magic_quotes_runtime = Off
// magic_quotes_sybase = Off
// include_path
// register_global
foreach (array('magic_quotes_gpc','magic_quotes_runtime') as $a) {

  if ( ini_get($a) == false ) print $a.': Ok  <br>';
  else {
	print ("<h2 class=\"error\">$a has a bad value  !!!</h2>");
  }

}
if ( ereg("\.\.\/include",ini_get('include_path')) == false )
  print ("<h2 class=\"error\">include_path incorrect  !!!".ini_get('include_path')."</h2>");
 else
   print 'include_path : ok ('.ini_get('include_path').')<br>';



$cn=DbConnect(-2,'phpcompta');

if ($cn == false ) {
  print "<p> Vous devez absolument taper dans une console la commande 'createuser -d phpcompta'
  puis  la commande 'createdb -O phpcompta phpcompta'. </p>
<p>Ces commandes créeront l'utilisateur phpcompta
puis la base de données par défaut de phpcompta.</p>";
  exit();
 }

// Check if account_repository exists
$account=CountSql($cn,
		  "select * from pg_database where datname='account_repository'");

// Create the account_repository
if ($account == 0 ) {
  ob_start();
  echo "Creation of account_repository";
  ExecSql($cn,"create database account_repository encoding='latin1'");
  $r=system("$psql -U phpcompta account_repository -f sql/account_repository/schema.sql",$r);
  $r=system("$psql -U phpcompta account_repository -f sql/account_repository/data.sql",$r);
  echo "Creation of Démo";
  ExecSql($cn,"create database dossier1 encoding='latin1'");
  $r=system("$psql -U phpcompta dossier1 -f sql/dossier1/schema.sql",$r);
  $r=system("$psql -U phpcompta dossier1 -f sql/dossier1/data.sql",$r);
  echo "Creation of Modele1";
  ExecSql($cn,"create database mod1 encoding='latin1'");
  $r=system("$psql -U phpcompta mod1 -f sql/mod1/schema.sql",$r);
  $r=system("$psql -U phpcompta mod1 -f sql/mod1/data.sql",$r);
  ob_end_clean();

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