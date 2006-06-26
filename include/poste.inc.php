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
 * \brief p_action contains the main action (always poste here)
 *  action contains the sub action 
 */
require_once("class_parm_code.php");

//-----------------------------------------------------
// confirm mod
if ( isset( $_POST['confirm_mod'] ) )
{
  extract($_POST);
  $update=new parm_code($cn,$p_code);
  $update->p_comment=$p_comment;
  $update->p_value=$p_value;
  $update->Save();
}
$object=new parm_code($cn);

$all=$object->LoadAll();
echo '<div style="position:float;align:right; ">';
echo '<table align="left">';
for ($i=0;$i<sizeof($all);$i++)  {
  echo '<TR>';
  echo $all[$i]->Display();
  echo '<TD><FORM method="POST">';
  $w=new widget('hidden');
  $w->name='id';
  $w->value=$i;
  echo $w->IOValue();
  echo $w->Submit('mod','modifie');
  echo '</FORM>';
  echo '</TD>';
  echo "</TR>";
}
echo "</table>";
echo "</div>";
//-----------------------------------------------------
// modifie
if ( isset ($_POST['mod'] ))
{
  echo '<div style="position:float;align:right;">';
  echo "Voulez-vous vraiment modifier ?";
  echo '<FORM METHOD="POST">';

  echo "<TABLE>";
  $id=$_POST['id'];
  echo $all[$id]->Input();
  echo "</TABLE>";
  $h=new widget('hidden');
  $h->name='p_action';
  $h->value='poste';
  echo $h->IOValue();
  echo $w->Submit('confirm_mod','Confirme');
  echo $w->Submit('no','Cancel');
  echo "</FORM>";
  echo "</div>";

} 