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
/* $Revision$ */
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

include_once("ac_common.php");
include_once("impress_inc.php");
include_once("postgres.php");


include ('class_user.php');
/* Admin. Dossier */
$cn=DbConnect($_SESSION['g_dossier']);

$User=new cl_user($cn);
$User->Check();

// TODO a specific level of security for the "bilan" ???
// Change must be done here
if ( $User->admin == 0 ) {
  if ($User->CheckAction($cn,IMP) 
								  ==0
     )
  {
    /* Cannot Access */
    NoAccess();
  }

}

// A rtf file is generated
header('Content-type: application/rtf');
 
 // It will be called downloaded.pdf
header('Content-Disposition: attachment; filename="bilan.rtf"');
 

// Variable
// Start periode 
$start=( isset ($_POST['from_periode']))?$_POST['from_periode']:-1;
$end=( isset ($_POST['to_periode']))?$_POST['to_periode']:-1;

// Variable if ok ?
if ( $start*$end < 0 ) {
  echo_error("Missing Variable start = $start end=$end");
  exit (-1);
 }
// Open forms
$bnb_form=fopen('document/fr_be/bnb.form','r');
if ( $bnb_form == false) {
  echo 'Cannot Open';
  exit();
 }
$col=array();

// read forms line per line
while (! feof ($bnb_form)) {
  $buffer=trim(fgets($bnb_form));
  // $a=(CheckFormula($buffer)  == true)?"$buffer ok<br>":'<font color="red">'.'Pas ok '.$buffer."</font><br>";
  // echo $a;
  // blank line are skipped
  if (strlen(trim($buffer))==0) 
    continue;

  // buffer contains a formula A$=....
  // We need to eval it 
  //ereg("\\$[a-zA-Z]+[0-9]*=",$buffer,$e);
  //  echo $e[0];
  //echo "<br>".$form;
  $a=ParseFormula($cn,"$buffer",$buffer,$start,$end,false);
  $b=str_replace("$","\$",$a);

  // echo "Formule to eval".$b.'<hr>';
  eval("$b;");
  // var_dump($b);
  
 }// end read form line per line
// echo "<hr>";
fclose ($bnb_form);
//////////////////////////////////////////////////////////////////////
// Open the rtf document in order to read it and to 
// replace the <<>> by the appropriate values
//
// Open the rtf template
$bnb_rtf=fopen('document/fr_be/bnb.rtf','r');
// $out=fopen('tmp/a.rtf','w+');
// if ( $bnb_rtf == false) {
//   echo 'Cannot Open rtf';
//   exit();
//  }
// Read it until the end
while ( !feof($bnb_rtf) ) {
  $line_rtf=fgets($bnb_rtf);
  // the line contains the magic <<
  if (ereg("<<\\$[a-zA-Z]*[0-9]*>>",$line_rtf,$f2) == true) {
    // DEBUG
    //    echo $line_rtf.'<br>';

    // the f2 array contains all the magic << in the line
    foreach ($f2 as $f2_str) {
      // DEBUG
      // echo "single_f2 = $f2_str <br>";
      // replace single_f2 by its value
      $f2_value=str_replace("<<","",$f2_str);
      $f2_value=str_replace(">>","",$f2_value);
      $f2_value=str_replace("$","",$f2_value);
      // DEBUG
      //echo "f2_value=$f2_value";
      $a=${"$f2_value"};
      // DEBUG      echo " a = $a";
      $line_rtf=str_replace($f2_str,$a,$line_rtf);

    }// foreach end
  }
  // DEBUG
  //  fwrite($out,$line_rtf);
  echo $line_rtf;


 }// rtf file is read
?>
