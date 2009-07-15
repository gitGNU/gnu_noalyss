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


// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
//require_once('class_database.php');
include_once("constant.php");
/*! \file
 * \brief Debug procedure
 */
/*!\brief print debug 
 *\param $file file name
 *\param what line
 *\param debugging msg
 */

function echo_debug      ($file,$line="",$msg="") {
  if ( DEBUG=='true' ) {
    $f=fopen ($_ENV['TMP'].DIRECTORY_SEPARATOR."phpcompta.log","a+");
    $a=var_export($msg,true);
    $e=basename($file);
    fwrite($f,"$e : $line $a\n");
    fclose ($f);
  }
}

?>
