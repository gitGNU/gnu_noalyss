
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
include_once("ac_common.php");
include_once("postgres.php");
// Test the connection
$a=DbConnect();
if ( $a==false) {
   exit ("<h2 class=\"error\">__LINE__ test has failed !!!</h2>");

}
if ( ($Res=ExecSql($a,"select  * from ac_users") ) == false ) {
	exit ("<h2 class=\"error\">__LINE__ test has failed !!!</h2>");
} else 
	print "Connect to database success <br>";

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

echo "<h2 class=\"info\"> Congratulation : Test successfull</h2>";
?>
<script type="text/javascript" language="javascript"  src="js/jrn_concerned.js">
alert("Include me");
SearchJrn('a','b');
</script>


