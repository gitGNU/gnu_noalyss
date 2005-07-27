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
//$Revision$
include_once("impress_inc.php");
/* function EncodeForm
 * Purpose : 
 *          Encoding Form
 * 
 * parm : 
 *	- array of previous changes
 *      - sessid for the search window
 *      - p_line number of line
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 
function EncodeForm($p_line,$p_sessid,$p_array=null) {
  //  echo '<SCRIPT LANGUAGE="javascript" SRC="win_search_poste.js"></SCRIPT>';
  include_once("constant.php");
  echo JS_SEARCH_POSTE;
   $search='<INPUT TYPE="BUTTON" VALUE="Cherche" OnClick="SearchPoste(\''.$p_sessid."','not')\">";
  for ( $i =0 ; $i <= $p_line; $i++) {
    ${"text$i"}="";
    ${"form$i"}="";
    ${"pos$i"}="";
  }
  if ( $p_array != null ) {
    foreach ( $p_array as $key=> $element ) {
      ${"$key"}=$element;
      echo_debug ("EncodeForm $key = $element");

    }
    
  } else {
    $form_nom="";
    $text0="";
    $form0="";
  }
  echo "<FORM ACTION=\"form.php\" METHOD=\"POST\">";
  printf ("Nom du rapport : <INPUT TYPE=\"TEXT\" NAME=\"form_nom\" VALUE=\"%s\">",
	  $form_nom);
  printf ('<INPUT TYPE="HIDDEN" NAME="line" value="%s"',
	  $p_line);
if ( isset ($fr_id))   printf ('<INPUT TYPE="HIDDEN" NAME="fr_id" value="%s"',
	  $fr_id);

  echo '<TABLE>';
  echo "<TR>";
  if ( isset($fr_id) )   echo "<TH> Position </TH>";
  echo "<TH> Texte </TH>";
  echo "<TH> Formule</TH>";

  echo '</TR>';
  for ( $i =0 ; $i < $p_line;$i++) {
    echo "<TR>";
    // si fr_id != null alors les donnees viennent de 
    // GetDataForm: ce n'est pas un nouvel enregistrement
    //
    if ( isset($fr_id)) {
      echo "<TD>";
      printf ('<input TYPE="TEXT" NAME="pos%d" size="3" VALUE="%s">',
	      $i,${"pos$i"});
      echo '</TD>';
    }

    echo "<TD>";
    printf ('<input TYPE="TEXT" NAME="text%d" size="25" VALUE="%s">',
	    $i,${"text$i"});
    echo '</TD>';

    echo "<TD>";
    printf ('<input TYPE="TEXT" NAME="form%d" SIZE="25" VALUE="%s">',
	    $i,${"form$i"});
    echo $search;
    echo '</TD>';

    echo "</TR>";
  }
  echo "</TABLE>";
  if ( isset($fr_id)){
    echo '<INPUT TYPE="submit" value="Enregistre" name="update">';
  }else {
    echo '<INPUT TYPE="submit" value="Enregistre" name="record">';
  }
  echo '<INPUT TYPE="submit" value="Ajoute une ligne" name="add_line">';
  echo '<INPUT TYPE="submit" value="Efface ce rapport" name="del_form">';

  echo "</FORM>";

}
/* function ViewForm
 * Purpose : Show the details of a form
 * 
 * parm : 
 *	- $p_cn connection
 *      - $p_id gives the formdef.fr_id
 *      - sessid for the search window
 * gen :
 *	- none
 * return: 
 *	- none
 *
 */ 
function ViewForm($p_cn,$p_sessid,$p_id) {
  $array=GetDataForm($p_cn,$p_id);
  $l_nom=GetFormName($p_cn,$p_id);
  $array['form_nom']=$l_nom;

  $l_line=GetNumberLine($p_cn,$p_id);
  EncodeForm($l_line,$p_sessid,$array);

}
/* function ViewForm
 * Purpose : Show the details of a form
 * 
 * parm : 
 *	- $p_cn connection
 *      - $p_id gives the formdef.fr_id
 *      - sessid for the search window
 * gen :
 *	- none
 * return: 
 *	- none
 *
 */ 
function DeleteForm($p_cn,$p_id) {
  ExecSql($p_cn,"delete from form where fo_fr_id=$p_id");
  ExecSql($p_cn,"delete from formdef where fr_id=$p_id");
}
/* function GetDataForm
 * Purpose :
 *         Get data from a form 
 * parm : 
 *	- $p_cn connection
 *      - $p_id gives the formdef.fr_id
 *	- 
 * gen :
 *	- none
 * return:
 *	- array
 *
 */ 
function GetDataForm($p_cn,$p_id) {
  $Res=ExecSql($p_cn,"select fo_id,fo_fr_id,fo_pos,fo_label,fo_formula from form where fo_fr_id=$p_id
                      order by fo_pos");
  $Max=pg_NumRows($Res);
  for ( $i=0;$i<$Max;$i++){
    $l_line=pg_fetch_array($Res,$i);

    // get the fo_id
    $text=sprintf("fo_id%d",$i);
    $array[$text]=$l_line['fo_id'];
    // get the fo_label
    $text=sprintf("text%d",$i);
    $array[$text]=$l_line['fo_label'];
    // get the fo_formula
    $text=sprintf("form%d",$i);
    $array[$text]=$l_line['fo_formula'];
    // get the pos
    $text=sprintf("pos%d",$i);
    $array[$text]=$l_line['fo_pos'];
  } //for
  $array["fr_id"]=$p_id;
  return $array;
}
/* function GetNumberLine
 * Purpose : Get the number of line of a form
 * 
 * parm : 
 *	- $p_cn connection
 *      - $p_id gives the formdef.fr_id
 *	- 
 * gen :
 *	- none
 * return:
 *	- integer
 *
 */ 
function GetNumberLine($p_cn,$p_id) {
  $Res=ExecSql($p_cn,"select * from form where fo_fr_id=".$p_id);
  $count=pg_NumRows($Res);
  return $count;
}
/* function GetFormName
 * Purpose : Get the name of the form
 * 
 * parm : 
 *	- $p_cn connection
 *      - $p_id formdef.fr_id
 * gen :
 *	- none
 * return:
 *	- string name
 *
 */ 
function GetFormName($p_cn,$p_id) {
  $Res=ExecSql($p_cn,"select fr_label from formdef where fr_id=".$p_id);
  if ( pg_NumRows($Res) == 0 ) return null;
  $name=pg_fetch_array($Res,0);
  return $name['fr_label'];
}
/* function UpdateForm
 * Purpose :
 * 
 * parm : 
 *	-  $p_cn connexion
 *      -  $array
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 
function UpdateForm($p_cn,$p_array) {
  foreach ($p_array as $key=>$element) {
    echo_debug ("UpdateForm $key = $element");
    ${"$key"}=$element;
  }
  $Res=ExecSql($p_cn,"update formdef set fr_label='".FormatString($form_nom)."' where fr_id=".$fr_id);
  $Res=ExecSql($p_cn,"delete from form where fo_fr_id=".$fr_id);
  // Test les positions
  for ($i =0; $i <$line;$i++) {
    echo_debug ("position =".${"pos$i"});
    if ( (string) ${"pos$i"} != (string) (int) ${"pos$i"}) {
      ${"pos$i"}=$i+1;
    }
    if ( ${"pos$i"} > $line || ${"pos$i"} < 0) { 
      ${"pos$i"}=$i+1;
      echo_debug(__FILE__,__LINE__,"position trop grand max = $line+1 ");
    }
  }
  for ($i =0; $i <$line;$i++) {
    for ( $o=$i+1; $o < $line;$o++) {
      if ( ${"pos$i"} == ${"pos$o"} ) {
	${"pos$o"}=$o+1;
      }
    }
  }
  for ( $i=0;$i<$line;$i++) {
    if ( strlen(trim(${"text$i"})) != 0) {
      ${"text$i"}=FormatString(${"text$i"});
      ${"form$i"}=FormatString(${"form$i"});
      if ( ${"text$i"} != null ) {
	if ( CheckFormula(${"form$i"}) == false ) 
	  ${"form$i"}="!!!!!!! FORMULE INVALIDE ".${"form$i"};

	${"form$i"}=(${"form$i"}==null)?"null":"'".${"form$i"}."'";
	$sql=sprintf("insert into form (fo_fr_id,
                                  fo_pos,
                                  fo_label,
                                  fo_formula) values
                                  ( %d,
                                    %d,
                                    '%s',
                                    %s)",
		     $fr_id,${"pos$i"},
		     ${"text$i"},
		     ${"form$i"}
		     );
	$Res=ExecSql($p_cn,$sql);
      }
    }//if
  }//for

}
/* function AddForm
 * Purpose :
 * 
 * parm : 
 *	-  $p_cn connexion
 *      -  $array
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 
function AddForm($p_cn,$p_array) {
  foreach ($p_array as $key=>$element) {
    echo_debug ("AddForm $key = $element");
    ${"$key"}=$element;
  }

  if ( !isset ($form_nom) ||
       ! isset ($line) ) {
    echo_error("Nom ou ligne non défini");
    return;
  }
  $form_nom=(FormatString($form_nom)==null)?"NoName":FormatString($form_nom);
  $sql="insert into formdef (fr_label) values ('".$form_nom."')";
  $Res=ExecSql($p_cn,$sql);
  $n=GetSequence($p_cn,"s_formdef");

  for ($i=0;$i < $line;$i++) {
      ${"text$i"}=FormatString(${"text$i"});
      ${"form$i"}=FormatString(${"form$i"});
      if ( ${"text$i"} != null ) {
	//	${"form$i"}=(${"form$i"}==null)?${"form$i"}:"'".${"form$i"}."'";
	${"form$i"}=(${"form$i"}==null)?"null":"'".${"form$i"}."'";
	CheckFormula(${"form$i"});
	$sql=sprintf("insert into form (fo_fr_id,
                                  fo_pos,
                                  fo_label,
                                  fo_formula) values
                                  ( %d,
                                    %d,
                                    '%s',
                                    %s)",
		   $n,$i+1,${"text$i"},${"form$i"}
		   );
	$Res=ExecSql($p_cn,$sql);
    }//if
  }//for $i
}

?>
