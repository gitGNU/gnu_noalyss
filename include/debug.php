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


// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
//include_once ("postgres.php");
include_once("constant.php");

function echo_debug      ($file,$line="",$msg="") {
  if ( DEBUG=='true' ) {
    $password=phpcompta_password;
    $l_Db="dbname=log user='phpcompta' password='$password' host=127.0.0.1";
    $cn=pg_connect($l_Db);
    $file=FormatString($file);
    $line=FormatString($line);
    $msg='domaine :'.domaine.' '.FormatString($msg);
    
    $sql= "insert into log (lg_file,lg_line,lg_msg) ".
 	  "values ('$file','$line','$msg');";
//    pg_exec ($cn,"\set encoding 'latin1'");
    pg_set_client_encoding($cn,'latin1');
    pg_exec($cn,$sql);
  }
}

?>
