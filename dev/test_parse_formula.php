<?
include_once('impress_inc.php');

echo '<hr>';
foreach ( array('1',
		'(45+5)',
	    'round([45])',
		'$A=9',
		'$S30=($F1 >=0)?$F1:0',

	    '[45%]',
	    '[50]*[51%]',
	    '$A1=[50]*[51%]',
	    '[50]*9',
	    '[50]*9.0',
	    '[50%]*9',
	    '$C1111=[50%]*9',
	    '$C1111=[50%]*9*$D1',
	    '$C10=[10%]',
	    '[50%]*9.0',
	    '[50%]*9.0 FROM=01.2004',
	    '[50%]*9.0FROM=01.2004',
		'system',
		'unlink',
		'ls -1')
	as $a ) {
  echo "Testing :".$a;
  echo (CheckFormula($a)==false)?'Non valide ':'ok';

  echo '<br>';
  
  foreach (array('+','-','/') as $b ) {
    $ee=str_replace('*',$b,$a);
    echo "Testing :".$ee;
    echo (CheckFormula($ee)==false)?'Non valide ':'ok';
    echo '<br>';

  }
  for($e=0;$e<3;$e++) {
    $a.="*".$a;
  echo "Testing :".$a;
  echo (CheckFormula($a)==false)?'Non valide ':'ok';
  echo '<br>';

  }
 }

?>
