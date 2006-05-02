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
require_once("class_fiche_def.php");

// record change
if ( isset ($_POST['confirm_mod'])) {
  extract ($_POST);
  $update=new fiche_def_ref($cn);
  $update->frd_id=FormatString($frd_id);
  $update->frd_text=FormatString($frd_text);
  $update->frd_class_base=FormatString($frd_class_base);
  $update->Save();
}
// Load All Fiche_def
$fiche_def=new fiche_def_ref($cn);
$all=$fiche_def->LoadAll();
// Display Them
echo '<table align="left">';
for ($i=0;$i<sizeof($all);$i++) 
{
  echo '<TR>';
  echo $all[$i]->Display();
  echo "<TD>";
  echo '<form method="post">';
  $w=new widget('hidden');
  echo $w->IOValue('idx',$i);
  echo $w->Submit('mod','modifie');
  echo $w->IOValue('fiche','p_action');
  echo "</form>";
  echo "</TD>";
  echo '</TR>';
}
echo "</table>";
// modify input
if ( isset ($_POST['mod']) ) 
{
  extract ($_POST);
  echo '<div style="position:float;padding:2%">';
  echo "Voulez-vous modifier ?<br><font color=\"red\"> Attention, ne changer pas la signification de ";
  echo " ce poste, <i>par exemple ne pas changer Client par fournisseur</i>, <br>sinon le programme fonctionnera mal, utiliser uniquement des chiffres pour la classe de base ou rien</font>";
  $idx=$_POST['idx'];
  $mod=new fiche_def_ref($cn);
  $mod->frd_id=$all[$idx]->frd_id;
  $mod->frd_text=$all[$idx]->frd_text;
  $mod->frd_class_base=$all[$idx]->frd_class_base;
  echo '<form method="post">';
  echo '<ul style="list-style-type:none"';
  echo $mod->Input();
  echo "</ul>";
  $w=new widget("hidden");
  echo $w->IOValue('p_action','fiche');
  echo $w->Submit('confirm_mod' ,'Confirme');
  echo $w->Submit('no','Cancel');
  echo '</form>';
  echo '</div>';
}
