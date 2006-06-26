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
/*! \file
 * \brief included file for customizing with the vat (account,rate...)
 */

  // Confirm remove
  if ( isset ($_POST['confirm_rm'])) 
  {
    ExecSql($cn,'select tva_delete('.$_POST['tva_id'].')');
  }
//-----------------------------------------------------
// Record Change
  if ( isset ($_POST['confirm_mod'])
       || isset ($_POST['confirm_add'])) 
    {
      extract($_POST);
      $tva_id=FormatString($tva_id);
      $tva_label=FormatString($tva_label);
      $tva_rate=FormatString($tva_rate);
      $tva_comment=FormatString($tva_comment);
      $tva_poste=FormatString($tva_poste);
      $err=0; // Error code
      if ( isNumber($tva_id) == 0 ) {
	$err=1;
	
     } 
      if ( isNumber($tva_rate) == 0 ) {
	$err=2;
     } 

      if ( $err == 0 ) 
	{
	  if (  isset ($_POST['confirm_add']) ) 
	    {
	  $Res=ExecSql($cn,
		       "select tva_insert($tva_id,'$tva_label',
                        '$tva_rate','$tva_comment','$tva_poste')");

	    }
	  if (  isset ($_POST['confirm_mod']) ) 
	    {
	  $Res=ExecSql($cn,
		       "select tva_modify($tva_id,'$tva_label',
                       '$tva_rate','$tva_comment','$tva_poste')");
	    }
	  $ret_sql=pg_fetch_row($Res);
	  $err=$ret_sql[0];
	}
      if ( $err != 0 ) 
	{
	  $err_code=array(1=>"Tva id n\'est pas un nombre",
			  2=>"Taux tva invalide",
			  3=>"Label ne peut être vide",
			  4=>"Poste invalide",
			  5=>"Tva id doit être unique");
	  $str_err=$err_code[$err];
	  echo "<script>alert ('$str_err'); </script>";;
	}
  }

  //-----------------------------------------------------
  // Display
  $sql="select tva_id,tva_label,tva_rate,tva_comment,tva_poste from tva_rate order by tva_id";
  $Res=ExecSql($cn,$sql);
  ?>
<TABLE>
<TR>
<th>Id</th>
<th>Label</TH>
<th>Taux</th>
<th>Commentaire</th>
<th>Poste</th>
</tr>
<?
  $val=pg_fetch_all($Res);
  echo_debug('parametre',__LINE__,$val);
  foreach ( $val as $row)
    {
      // load value into an array
      $index=$row['tva_id']     ;
      $a[$index]=array(
		       'tva_label'=> $row['tva_label'],
		       'tva_rate'=>$row['tva_rate'],
		       'tva_comment'=>$row['tva_comment'],
		       'tva_poste'=>$row['tva_poste']
		       );
      echo "<TR>";
      echo '<FORM METHOD="POST">';

      echo "<TD>";
      echo $row['tva_id'];
      echo "</TD>";

      echo "<TD>";
      echo $row['tva_label'];
      echo "</TD>";

      echo "<TD>";
      echo $row['tva_rate'];
      echo "</TD>";

      echo "<TD>";
      echo $row['tva_comment'];
      echo "</TD>";

      echo "<TD>";
      echo $row['tva_poste'];
      echo "</TD>";

      echo "<TD>";
      echo '<input type="submit" name="rm" value="Efface">';
      echo '<input type="submit" name="mod" value="Modifie">';
      $w=new widget("hidden");
      $w->name="tva_id";
      $w->value=$row['tva_id'];
      echo $w->IOValue();
      $w=new widget("hidden");
      $w->name="p_action";
      $w->value="tva";
      echo $w->IOValue();

      echo "</TD>";

      echo '</FORM>';
      echo "</TR>";
    }
?>
</TABLE>
    <? // if we add / remove or modify a vat we don't show this button
if (   ! isset ($_POST['add'])
  &&   ! isset ($_POST['mod'])
  &&   ! isset ($_POST['rm'])

) { ?>
    <form method="post">
    <input type="submit" name="add" value="Ajouter un taux de tva">
    <input type="hidden" name="p_action" value="tva">
     </form>
<?
       } 


    //-----------------------------------------------------
    // remove
    if ( isset ( $_REQUEST['rm'])) 
      {
	echo_debug("parametre",__LINE__,"efface ".$_POST['tva_id']);
	echo "Voulez-vous vraiment effacer ce taux ? ";
	$index=$_POST['tva_id'];
	
?>
<table>
   <TR>
   <th>Label</TH>
   <th>Taux</th>
   <th>Commentaire</th>
   <th>Poste</th>
   </tr>
<tr>
   <td> <? echo $a[$index]['tva_label']; ?></td>
   <td> <? echo $a[$index]['tva_rate']; ?></td>
   <td> <? echo $a[$index]['tva_comment']; ?></td>
   <td> <? echo $a[$index]['tva_poste']; ?></td>
</Tr>
</table>
<?
    echo '<FORM method="post">';
    echo '<input type="hidden" name="tva_id" value="'.$index.'">';
    echo '<input type="submit" name="confirm_rm" value="Confirme">';
    echo '<input type="submit" value="Cancel" name="no">';
    echo "</form>"; 

  }
  //-----------------------------------------------------
  // add
  if ( isset ( $_REQUEST['add'])) 
  {
    echo_debug("parametre",__LINE__,"add a line ");
    echo "Tva à ajouter, l'id doit être différent pour chaque taux";
    echo '<FORM method="post">';


?>
<table >
   <TR>
   <th>id</TH>
   <th>Label</TH>
   <th>Taux</th>
   <th>Commentaire</th>
   <th>Poste</th>
   </tr>
<tr valign="top">
   <td> <? $w=new widget("text");$w->size=5; echo $w->IOValue('tva_id','') ?></td>
   <td> <? $w=new widget("text");$w->size=20; echo $w->IOValue('tva_label','') ?></td>
   <td> <? $w=new widget("text");$w->size=5; echo $w->IOValue('tva_rate','') ?></td>
   <td> <? $w=new widget("textarea"); $w->heigh=2;$w->width=20;echo $w->IOValue('tva_comment','') ?></td>
   <td> <? $w=new widget("text"); $w->size=10;echo $w->IOValue('tva_poste','') ?></td>
</Tr>
</table>
<input type="submit" value="Confirme" name="confirm_add">
<input type="submit" value="Cancel" name="no">

 </FORM>
<?  }

  //-----------------------------------------------------
  // mod
  if ( isset ( $_REQUEST['mod'])) 
    {

      echo_debug("parametre",__LINE__,"modifie ".$_POST['tva_id']);
      echo "Tva à modifier";
      $index=$_POST['tva_id'];

      echo '<FORM method="post">';
      echo '<input type="hidden" name="tva_id" value="'.$index.'">';
?>
<table>
   <TR>
   <th>Label</TH>
   <th>Taux</th>
   <th>Commentaire</th>
   <th>Poste</th>
   </tr>
<tr valign="top">
   <td> <? $w=new widget("text");$w->size=20; echo $w->IOValue('tva_label',$a[$index]['tva_label']) ?></td>
   <td> <? $w=new widget("text");$w->size=5; echo $w->IOValue('tva_rate',$a[$index]['tva_rate']) ?></td>
   <td> <? $w=new widget("textarea"); $w->heigh=2;$w->width=20;
   echo $w->IOValue('tva_comment',$a[$index]['tva_comment']) ?></td>
   <td> <? $w=new widget("text");$w->size=5; echo $w->IOValue('tva_poste',$a[$index]['tva_poste']) ?></td>
</Tr>
</table>
<input type="submit" value="Confirme" name="confirm_mod">
<input type="submit" value="Cancel" name="no">
 </FORM>
<? 
    }

?>