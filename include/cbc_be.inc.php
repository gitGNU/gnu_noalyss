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

/////////////////////////////////////////////////////////////////////////////////////////////////////
// Bank CBC 
/////////////////////////////////////////////////////////////////////////////////////////////////////
$row=1;
while (($data = fgetcsv($handle, 2000,'!@')) !== FALSE) {
	$num = count($data);
	for ($c=0; $c < $num; $c++) {
// Parsing CSV comes here

// insert into import_tmp
			$Sql="insert into import_tmp (code,
				date_exec ,
				date_valeur,
				montant,
				devise,
				compte_ordre,
				detail,
				bq_account ,
				jrn,
				ok)
			values ('$code',
				'$date_exec',
				'$date_exec',
				$montant,
				'$devise',
				'".addslashes($compte_ordre)."',	
				'".addslashes($detail).$num_compte."	',
				'$p_bq_account',
				$p_jrn,
				false)";
		} // for ($c=0;$c<$num;$c++)
		$row++;
} // file is read
fclose($handle);
echo "Encore rien désolé";
?>
