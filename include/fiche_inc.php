<?
/*
 *   This file is part of WCOMPTA.
 *
 *   WCOMPTA is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   WCOMPTA is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with WCOMPTA; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Auteur Dany De Bontridder ddebontridder@yahoo.fr
// $Revision$

/* function addFiche
 * Purpose : ajoute une nouvelle fiche
 * 
 * parm : 
 *	- connexion
 *      - type de la fiche
 *      - tableau
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 
function AddFiche($p_cn,$p_type,$p_array) {
  echo_debug(" AddFiche($p_cn,$p_type,$p_array) ");

  foreach ($p_array as $key=>$element){
    ${"p_$key"}=$element;
    echo_debug("p_$key=$element;");
  }
  $base=GetBaseFiche($p_cn,$p_type);
  $num=GetNextFiche($p_cn,$base);
  $sql=sprintf("insert into tmp_pcmn (pcm_val,pcm_lib,pcm_val_parent) 
                values (%s,'%s',%d)",
	       $num,
	       $p_nom0,
	       $base);
  echo_debug($sql);
  $Res=ExecSql($p_cn,$sql);
  $sql=sprintf("insert into fiche ( f_id,f_label,f_fd_id) values (%d,'%s',%d)",
	       $num,$p_nom0,$p_type);
  $Res=ExecSql($p_cn,$sql);
  for ( $i = 1; $i < $p_inc;$i++) {
    $sql=sprintf("insert into isupp (is_f_id,is_isd_id,is_value) values (
                  %d,%d,'%s')",
		 $num, ${"p_isd_id$i"},${"p_nom$i"});
    echo_debug($sql);
    $Res=ExecSql($p_cn,$sql);
  }

}
/* function EncodeFiche
 * Purpose : Affiche les détails d'une fiche et propose
 *           de mettre à jour
 *           ou si array est a null, permet d'ajouter une
 *           fiche
 * 
 * parm : 
 *	-  p_cn connexion
 *      -  p_type id du modele fichedef(fd_id) de la fiche SI array est null
 *         sinon correspond au id d'une fiche fiche(f_id)
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 
function EncodeFiche($p_cn,$p_type,$p_array=null) {
  echo_debug("function EncodeFiche($p_cn,$p_type) ");

  $ch_col="</TD><TD>";
  $ch_li='</TR><TR>';
  echo '<FORM action="fiche.php" method="post">';
  echo '<INPUT TYPE="HIDDEN" name="fiche" value="'.$p_type.'">';

  echo "<TABLE>";
  if ($p_array == null) {
    echo '<H2 class="info">'.getFicheName($p_cn,$p_type).'</H2>';
    $p_f_id="";
    echo_debug("Array is null");
    $sql="select isd_id,isd_label from isupp_def  where isd_fd_id=".$p_type;

    $Res=ExecSql($p_cn,$sql);
    $Max=pg_NumRows($Res);
    
    echo '<INPUT TYPE="HIDDEN" name="f_id" value="'.$p_f_id.'">';
    for ($i=0;$i < $Max;$i++) {
      $l_line=pg_fetch_array($Res,$i);
      $Hid=sprintf('<INPUT TYPE="HIDDEN" name="isd_id%d" value="%s">',
		   $i,$l_line['isd_id']);
      printf ('<TR><TD> %s </TD><TD><INPUT TYPE="TEXT" NAME="nom%d">%s</TD></TR>',
	      $l_line['isd_label'], $i,$Hid);
    }
    echo '</TR>';
    echo '</TABLE>';
    echo '<INPUT TYPE="HIDDEN" name="inc" value="'.$Max.'">';
    echo '<INPUT TYPE="SUBMIT" name="add_fiche" value="ajoute">';
    echo '</FORM>';
  }else {

    $sql="select f_label,f_fd_id  from fiche where f_id=".$p_type;
    $Res=ExecSql($p_cn,$sql);
    $Max=pg_NumRows($Res);
    if ( $Max == 0 ) return;
    $l_line=pg_fetch_array($Res,0);
    echo '<H2 class="info">'.getFicheName($p_cn,$l_line['f_fd_id']).'</H2>';
    printf('<input TYPE="TEXT" name="f_label" value="%s"><BR>',
	   $l_line['f_label']);
    echo '<INPUT TYPE="HIDDEN" name="f_fd_id" value="'.$l_line['f_fd_id'].'">';    

    $sql="select isd_fd_id,isd_id,isd_label,is_value,is_id,is_f_id from isupp left join isupp_def on isd_id=is_isd_id where is_f_id=".$p_type;
    
    $Res=ExecSql($p_cn,$sql);
    $Max=pg_NumRows($Res);
    echo_debug("Max ==== $Max");    
    echo '<INPUT TYPE="HIDDEN" name="f_id" value="'.$p_type.'">';

    echo '<INPUT TYPE="HIDDEN" name="max" value="'.$Max.'">';
    for ($i=0;$i < $Max;$i++) {
      $l_line=pg_fetch_array($Res,$i);


      $Hid=sprintf('<INPUT TYPE="HIDDEN" name="is_id%d" value="%s">',
		   $i,$l_line['is_id']);
      printf ('<TR><TD> %s </TD><TD><INPUT TYPE="TEXT" NAME="nom%d" VALUE="%s">%s</TD></TR>',
	      $l_line['isd_label'], $i, $l_line['is_value'],$Hid);
    }
    echo '</TR>';
    echo '</TABLE>';
    echo '<INPUT TYPE="SUBMIT" name="update_fiche" value="Mis à jour">';
    echo '</FORM>';
  }
}

/* function GetBaseFiche
 * Purpose : donne la classe comptable de base d'une fiche
 *  
 * parm : 
 *	- p_cn connexion
 *      - p_type fiche id
 * gen :
 *	- none
 * return:
 *	- return la classe id ou rien
 *
 */ 
function GetBaseFiche($p_cn,$p_type) {
  $base=null;
  $Res=ExecSql($p_cn,"select fd_class_base from fichedef where fd_id=".$p_type);
  if ( pg_NumRows($Res) == 0 ) return;
  $base=pg_fetch_array($Res,0);
  return $base['fd_class_base'];
}
/* function ViewFiche
 * Purpose : Montre les fiches d'une rubrique
 * 
 * parm : 
 *	-  p_cn connexion
 *      - $p_type f_id
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 
function ViewFiche($p_cn,$p_type) {
  echo '<H2 class="info">'.getFicheName($p_cn,$p_type).'</H2>';
    $Res=ExecSql($p_cn,"select f_id,f_label from fiche where f_fd_id='".$p_type."' order by f_id");
    $Max=pg_NumRows($Res);

    for ( $i = 0; $i < $Max; $i++) {
      $l_line=pg_fetch_array($Res,$i);
      $div="<DIV>";
      $span_mod='<span class="mtitle"><A class="mtitle" href="fiche.php?action=detail&fiche_id='.$l_line['f_id'].'"> Modifie</A></SPAN>';
      $span_del='<span class="mtitle2" ALIGN="left">'.
	'<A class="mtitle" href="fiche.php?f_fd_id='.$p_type.'&action=delete&fiche_id='.$l_line['f_id'].
	'"> delete</A></SPAN>';
      $span_id='<SPAN style="background-color:lightgrey;">'.$l_line['f_id']."</SPAN>";
      if ( $i %2 == 0 ) 
	$div='<DIV style="background-color:#DDE6FF;">';
        echo $div.$span_del.'&nbsp;'.'&nbsp;'.'&nbsp;'.'&nbsp;'.'&nbsp;'.'&nbsp;'.
	  $span_mod."&nbsp;"."&nbsp;"."&nbsp;".$span_id."&nbsp;"."&nbsp;"."&nbsp;".$l_line['f_label']."</DIV>";
    }
    echo '<FORM METHOD="POST" action="fiche.php">';
    echo '<INPUT TYPE="HIDDEN" name="fiche" value="'.$p_type.'">';
    echo '<INPUT TYPE="SUBMIT" name="add" Value="Ajout fiche">';
    echo '</FORM>';

}
/* function GetNextFiche
 * Purpose : Crée le poste suivant pour une fiche en fonction
 *           de la classe de base (fichedef(fd_class_base))
 * 
 * parm : 
 *	- p_cn connexion
 *      - p_base 
 * gen :
 *	-
 * return:
 *	-
 *
 */ 
function GetNextFiche($p_cn,$p_base) {
  $Res=ExecSql($p_cn,"select max(pcm_val) as maxcode from tmp_pcmn where pcm_val_parent = $p_base");
 $Max=pg_NumRows($Res);
 echo_debug("$Max=pg_NumRows");
  $l_line=pg_fetch_array($Res,0);
  $ret=$l_line['maxcode'];
 if ( $ret == "" ) {
   $ret=sprintf("%d%04d",$p_base,0);
   return $ret+1;

 }

  echo_debug("ret $ret");
  return $ret+1;
}
/* function
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-
 *
 */ 
function GetDataFiche($p_cn,$p_fiche_id) {

}

/* function ViewFicheDetail
 * Purpose : Montre  le detail d'une fiche
 *           et ajoute les lignes manquantes
 *           dans le cas où le modèle à changer
 * 
 * parm : 
 *	-  p_cn
 *      - id de la fiche fiche(f_id)
 * gen :
 *	-
 * return:
 *	-
 *
 */ 
function ViewFicheDetail($p_cn,$p_id) {
$sql="insert into isupp (is_f_id,is_isd_id) select".
  " '".$p_id."',isd_id from isupp_def ".
  " where isd_id not in ".
  "(select is_isd_id from isupp where is_f_id=".$p_id.") ".
  " and isd_fd_id=(select distinct f_fd_id from fiche ".
  " where f_id=".$p_id.")";
 $Res=ExecSql($p_cn,$sql);
  EncodeFiche ($p_cn,$p_id,1);
}
/* function UpdateFiche
 * Purpose : Met a jour une fiche
 *          change dans le plan comptable, fiche,et isupp
 *          
 * parm : 
 *	- p_cn
 *      - p_array
 * gen :
 *	-
 * return:
 *	-
 *
 */ 
function UpdateFiche($p_cn,$p_array) {
  foreach ( $p_array as $key=> $element) {
    echo_debug("$key => $element");
    ${"$key"}=$element;
  }
  $sql=sprintf("update tmp_pcmn set pcm_lib='%s' where pcm_val=%s",
	       $f_label,
	       $f_id);
  echo_debug($sql);
  $Res=ExecSql($p_cn,$sql);
  $sql=sprintf("update fiche set f_label='%s' where f_id=%s",
	       $f_label,
	       $f_id);
  echo_debug($sql);
  $Res=ExecSql($p_cn,$sql);
  for ( $i =0 ; $i < $max ; $i++) {
    $sql=sprintf("update isupp set is_value='%s' where is_id=%d",
		 ${"nom$i"},
		 ${"is_id$i"});
    echo_debug($sql);
    $Res=ExecSql($p_cn,$sql);
  }
}
/* function EncodeModele
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-
 *
 */ 
function EncodeModele($p_js)
{
  echo '<H2 CLASS="info"> Ajout d\'un modèle</H2>';
  echo '<FORM ACTION="fiche.php" METHOD="post">';
  echo '<BR>Libellé <INPUT TYPE="TEXT" NAME="nom_mod">';
  echo '<BR>Classe de base <INPUT TYPE="TEXT" NAME="class_base">';
  echo $p_js;
  echo '<INPUT TYPE="HIDDEN" NAME="inc" VALUE="1">';
  echo '<BR><INPUT TYPE="SUBMIT" name="record_model" VALUE="Ajoute modèle">';
  echo '</FORM>';
}
/* function
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-
 *
 */ 
function DefModele ($p_js,$p_array=null,$p_ligne=1) 
{
  echo_debug("DefModele ($p_array,$p_ligne=1) ");
  for ($i=0; $i < $p_ligne; $i++) {
    ${"p_LABEL$i"}="";
    ${"p_inform$i"}="";
    ${"p_isd_id$i"}="";
  }
  if ($p_array != null) {
    foreach ( $p_array as $key => $element){
    ${"p_$key"}=$element;
    echo_debug ("p_$key $element");
    }//foreach
  }else {
    $p_nom_mod="";
    $p_class_base="";
  }

  echo '<FORM ACTION="fiche.php" METHOD="POST">';
  echo '<INPUT TYPE="HIDDEN" NAME="inc" VALUE="'.$p_ligne.'">';
  //  echo '<INPUT TYPE="HIDDEN" NAME="ligne" VALUE="'.$p_ligne.'">';
  echo '<BR>Label<INPUT TYPE="TEXT" NAME="nom_mod" VALUE="'.$p_nom_mod.'">';
  echo '<BR>classe<INPUT TYPE="TEXT" NAME="class_base" VALUE="'.$p_class_base.'">'.$p_js;

  if ( isset($p_fd_id) ) {
    echo '<INPUT TYPE="HIDDEN" NAME="fd_id" VALUE="'.$p_fd_id.'">';
  }
  for ( $i=0; $i < $p_ligne ; $i++ ) {
    if ( isset($p_fd_id) ) {
      echo '<INPUT TYPE="HIDDEN" NAME="isd_id'.$i.'" VALUE="'.${"p_isd_id$i"}.'">';
    }
    printf( '<BR>Libellé <INPUT TYPE="TEXT" NAME="LABEL%d" VALUE="%s">',
	    $i,
	    ${"p_LABEL$i"}
	    );
    if ( ${"p_inform$i"} == "on" || ${"p_inform$i"}=='t' ) {
      $val="checked";
    } else {
      $val="";
    }
    printf('Dans les formulaires <INPUT TYPE="CHECKBOX" name="inform%d" %s > ',
	   $i,
	   $val
	   );
  }
  echo '<BR><INPUT TYPE="submit" name="add_ligne" VALUE="Ajoute un champs">';
  if ( isset($p_fd_id) ){ 
    echo '<BR><INPUT TYPE="SUBMIT" NAME="update_modele" VALUE="Enregistrement">';
  }else {
    echo '<BR><INPUT TYPE="SUBMIT" NAME="add_modele" VALUE="Enregistrement">';
  }//if isset p_fd_id else

  echo '<FORM>';
}
/* function
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-
 *
 */ 
function AddModele($p_cn,$p_array) {
  echo_debug("AddModele");
  $p_ligne=$p_array['inc'];
  for ($i=0; $i < $p_ligne; $i++) {
    ${"p_inform$i"}="";
  }

  foreach ( $p_array as $key=>$element ) {
    echo_debug("p_$key $element");
    ${"p_$key"}=$element;
  }
  $p_nom_mod=FormatString($p_nom_mod);
  $p_class_base=FormatString($p_class_base);
  $sql=sprintf("insert into fichedef(fd_label,fd_class_base) values ('%s','%s')",
	       $p_nom_mod,$p_class_base);
  $Res=ExecSql($p_cn,$sql);

  $f_id=GetSequence($p_cn,"s_fdef");

  for ( $i=0; $i < $p_inc; $i++) {
    if ( ${"p_inform$i"} == "on" ) {
      $valform="true";
    }else {
      $valform="false";
    }
    ${"p_LABEL$i"}=FormatString(${"p_LABEL$i"});
    if ( ${"p_LABEL$i"}  != null  ) {
      $sql=sprintf("insert into isupp_def(isd_label,isd_fd_id,isd_form) values ('%s',%d,%s)",
		   ${"p_LABEL$i"},$f_id,$valform);
      $Res=ExecSql($p_cn,$sql);
    }
  }
}
/* function UpdateModele
 * Purpose :
 * 
 * parm : 
 *	- p_cn connexion
 *      - p_fiche fichedef(f_id)
 *      - p_js
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 
function UpdateModele($p_cn,$p_fiche,$p_js) {

  $array=GetDataModele($p_cn,$p_fiche);
  if ($array==null) {
    echo_error ("fiche_inc:UpdateModele Fiche non trouvée");
    return;
  }
  echo '<H2 class="info">'.getFicheName($p_cn,$p_fiche).'</H2>';
  foreach ( $array as $key=>$element) echo_debug("$key => $element");
  DefModele($p_js,$array,$array['ligne']);

}
/* function
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-
 *
 */ 
function GetDataModele($p_cn,$p_fiche) {
  $Res=ExecSql($p_cn,"select fd_id,fd_label,fd_class_base,
                             isd_id,isd_label,isd_form 
                      from fichedef inner join isupp_def on isd_fd_id=fd_id
                      where fd_id=$p_fiche");
  $Max=pg_NumRows($Res);
  if ($Max==0) return null;
  for ($i=0; $i < $Max;$i++) {
    $line=pg_fetch_array($Res,$i);
    if ($i == 0 ) {
      $array["nom_mod"]=$line['fd_label'];
      $array["class_base"]=$line['fd_class_base'];
      $array["fd_id"]=$line['fd_id'];
    }//if $i == 0
    //inform
    $text=sprintf("inform%d",$i);
    $array[$text]=$line['isd_form'];
    //Label
    $text=sprintf("LABEL%d",$i);
    $array[$text]=$line['isd_label'];
    //isd_id
    $text=sprintf("isd_id%d",$i);
    $array[$text]=$line['isd_id'];

  }//for
  $array['ligne']=$Max;
  return $array;
}

/* function SaveModele
 * Purpose : Sauve un modele de fiche
 * 
 * parm : 
 *	- connexion
 *      - tableau de valeurs
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 
function SaveModele($p_cn,$p_array) {
  for ($i=0; $i < $p_array['inc']; $i++) {
    ${"inform$i"}="";
  }

  foreach ($p_array as $key=>$element){
    echo_debug("$key => $element");
    ${"$key"}=$element;
  }//foreach
  $nom_mod=FormatString($nom_mod);
  $class_base=FormatString($class_base);
  if ( $nom_mod != null ) {
    $sql=sprintf("update fichedef set fd_label='%s', fd_class_base='%s' where fd_id=%d",
		 $nom_mod,$class_base,$fd_id);
    $Res=ExecSql($p_cn,$sql);
  }
  for ($i=1;$i<$inc;$i++){
    if ( ${"inform$i"} != null) { 
      $valform='true';
    }else {
      $valform='false';
    } //if ( ${"inform$d") != null) 
    
    if ( ${"isd_id$i"} != null )    {
      ${"LABEL$i"}=FormatString(${"LABEL$i"});
      if ( ${"LABEL$i"} != null ) {
	$sql=sprintf("update isupp_def set isd_label='%s',isd_form=%s where isd_id=%d",
		     ${"LABEL$i"},$valform,${"isd_id$i"});
      }
    } //   if ( ${"p_isd_id$i"} != null )    
    else {
      ${"LABEL$i"}=FormatString(${"LABEL$i"});
      if ( ${"LABEL$i"} != null ) {
	$sql=sprintf("insert into  isupp_def( isd_label,isd_fd_id,isd_form)
                    values ('%s',%d,%s)",
		     ${"LABEL$i"},$fd_id,$valform);
      }//       if ( ${"LABEL$i"} != null )
    }//else if ( ${"p_isd_id$i"} != null ) 
    $Res=ExecSql($p_cn,$sql);
  }//for
}
/* function Remove
 * Purpose : enleve une fiche dans isupp et fiche
 *           a la condition que ce poste n'aie jamais
 *           été utilisé
 * parm : 
 *	- p_cn
 *      - p_fid fiche(f_id)
 * gen :
 *	- none
 * return:
 *      - none
 */
function Remove ($p_cn, $p_fid) {
  if ( ! isset ($p_cn) ||
       ! isset ($p_fid) ) {
    echo_error ("Remove Missing Parameter p_cn = $p_cn p_fid=$p_fid");
    return;
  }
  if ( CountSql($p_cn,"select * from jrnx where j_poste=$p_fid") == 0 ) {
    $Res=ExecSql($p_cn,"delete from isupp where is_f_id=$p_fid");
    $Res=ExecSql($p_cn,"delete from fiche where f_id=$p_fid");
  } else {
         echo "<SCRIPT> alert('Impossible ce poste est utilisé dans un journal'); </SCRIPT>";
  }
}
/* function getFicheName
 * Purpose : retourne le nom de la fiche
 *        
 * parm : 
 *	- p_cn connexion
 *      - p_id fiche id
 * gen :
 *	- none
 * return:
 *     - string avec nom fiche
 */
function getFicheName($p_cn,$p_id) {

  $Res=ExecSql($p_cn,"select fd_label from fichedef where fd_id='".$p_id."'");
  if ( pg_NumRows($Res) == 0 ) return "Unknown";
  $st=pg_fetch_array($Res,0);
  return $st['fd_label'];
}

?>
