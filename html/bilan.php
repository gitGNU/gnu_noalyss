<?
include_once("ac_common.php");
include_once("impress_inc.php");

header('Content-type: application/rtf');
 
 // It will be called downloaded.pdf
header('Content-Disposition: attachment; filename="bilan.rtf"');
 
include_once("postgres.php");

// Open a connection
$cn=DbConnect(4);
// Open forms
$bnb_form=fopen('document/fr_be/bnb.form','r');
if ( $bnb_form == false) {
  echo 'Cannot Open';
  exit();
 }
$start=40;
$end=52;
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