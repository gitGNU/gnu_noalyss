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
// $Revision$
/* fucntion GetSqlFiche 
 ***************************************************
 * purpose : return the sql string which match the p_type 
 *           permit to have all the sql here
 * param : $p_type what sql select command u want
 * return : string
 */
function GetSqlFiche($p_type) {
  switch ($p_type ) {

    // return all the existing fiche_def_ref
  case ALL_FICHE_DEF_REF:
    return "select frd_id, frd text from fiche_def_ref";

  default:
    echo_error("undefined type cannot return the corresponding select");
    return null;
  }

}

/* function addFiche
 ***************************************************
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
  // Push the array in element starting with p
  //
  foreach ($p_array as $key=>$element){
    ${"p_$key"}=$element;
    echo_debug("p_$key=$element;");
  }
  // First Get the attr of the fiche
  $field=Get_attr_def($p_cn,$p_fd_id);


  // Create the Fiche
  $Sql="insert into fiche (fd_id) values (".$p_fd_id.")";
  $Res=ExecSql($p_cn,$Sql);

  // Get is f_id (sequence)
  $l_f_id=GetSequence($p_cn,'s_fiche');
  // TODO If a class base if given, we should not create automatically
  // The class 

  // Should we Create accounts for each cards
  $create=GetCreateAccount($p_cn,$p_fd_id);

  echo_debug ( " create = $create ");
  if ( $create == 1) {
    // We create an account for each
    // Get The Class Base
    $base=GetBaseFiche($p_cn,$p_type);
    
    // If a specific post account is needed created it
    
    if ( $base !=null  ) {
      // if the class base is not null, create it in the tmp_pcmn
      $num=GetNextFiche($p_cn,$base);
      $sql=sprintf("insert into tmp_pcmn (pcm_val,pcm_lib,pcm_val_parent) 
                values (%s,'%s',%d)",
		   $num,
		   $p_av_text0,
		   $base);
      echo_debug($sql);
      $Res=ExecSql($p_cn,$sql);
      // Add it the jnt table
      // 5 is always for the account post
      $Sql=sprintf("insert into jnt_fic_att_value (f_id,ad_id) values (%d,%d)",
		   $l_f_id,ATTR_DEF_ACCOUNT);
      $Res=ExecSql($p_cn,$Sql);
      
      // Get the jft_id sequence (s_jnt_fic_att_value)
      $l_jft_id=GetSequence($p_cn,'s_jnt_fic_att_value');
      
      
      // Add it the attr_value table
      $Sql=sprintf("insert into attr_value(jft_id,av_text) values (%d,'%s')",
		   $l_jft_id,$num);
      $Res=ExecSql($p_cn,$Sql);
    }
  }// end create accounts for each cards


  // Add the others attribut in jnt_fic_att_value and after in attr_value
  for ( $i = 0; $i < $p_inc;$i++) {
    //Except for the class base
       if ( ${"p_ad_id$i"} == ATTR_DEF_ACCOUNT ) continue;

    // Add it the jnt table
    $Sql=sprintf("insert into jnt_fic_att_value (f_id,ad_id) values (%d,%d)",
		 $l_f_id,${"p_ad_id$i"});
    $Res=ExecSql($p_cn,$Sql);

    // Get the jft_id sequence (s_jnt_fic_att_value)
    $l_jft_id=GetSequence($p_cn,'s_jnt_fic_att_value');


    // Add it the attr_value table
    $Sql=sprintf("insert into attr_value(jft_id,av_text) values (%d,'%s')",
		 $l_jft_id,${"p_av_text$i"});
    $Res=ExecSql($p_cn,$Sql);

  }

}
/* function EncodeFiche
 ***************************************************
 * Purpose : Affiche les détails d'une fiche et propose
 *           de mettre à jour
 *           ou si array est a null, permet d'ajouter une
 *           fiche, to fill the attribute
 * 
 * parm : 
 *	-  p_cn connexion
 *      -  p_type id du modele fiche_def(fd_id) de la fiche SI array est null
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
    // Array is null so we display a blank form
    //    echo '<H2 class="info">'.getFicheName($p_cn,$p_type).'</H2>';
    echo '<H2 class="info">New </H2>';
    $p_f_id="";
    echo_debug("Array is null");
    // Find all the attribute of the existing cards
    // --> Get_attr_def 
    $sql="select frd_id,ad_id,ad_text from  fiche_def join jnt_fic_attr using (fd_id)
           join attr_def using (ad_id) where fd_id=".$p_type." order by ad_id";

    $Res=ExecSql($p_cn,$sql);
    $Max=pg_NumRows($Res);
    // Put the card modele id (fiche_def.fd_id)
    echo '<INPUT TYPE="HIDDEN" name="fd_id" value="'.$p_type.'">';
    for ($i=0;$i < $Max;$i++) {
      $l_line=pg_fetch_array($Res,$i);

      // The number of the attribute
      $Hid=sprintf('<INPUT TYPE="HIDDEN" name="ad_id%d" value="%s">',
		   $i,$l_line['ad_id']);
      // content of the attribute
      printf ('<TR><TD> %s </TD><TD><INPUT TYPE="TEXT" NAME="av_text%d">%s</TD></TR>',
	      $l_line['ad_text'], $i,$Hid);
    }
    echo '</TR>';
    echo '</TABLE>';
    echo '<INPUT TYPE="HIDDEN" name="inc" value="'.$Max.'">';
    echo '<INPUT TYPE="SUBMIT" name="add_fiche" value="ajoute">';
    echo '</FORM>';
  }else {
    // Array is not null so we have to find the card's data from the database
    // and display them

    // Display the card name
    $label=getFicheName($p_cn,$p_type);
    echo '<H2 class="info">'.$label.'</H2>';

    // Find all the data related to the card
    $sql="select av_text,ad_id,ad_text,jft_id from attr_value
        natural join attr_def 
        natural join jnt_fic_att_value 
        natural join fiche where f_id=$p_type";

    $Res=ExecSql($p_cn,$sql);
    $Max=pg_NumRows($Res);
    echo_debug("Max ==== $Max");    

    echo '<INPUT TYPE="HIDDEN" name="f_id" value="'.$p_type.'">';
    echo '<INPUT TYPE="HIDDEN" name="f_label" value="'.$label.'">';
    echo '<INPUT TYPE="HIDDEN" name="max" value="'.$Max.'">';

    for ($i=0;$i < $Max;$i++) {
      // fetch the data
      $l_line=pg_fetch_array($Res,$i);

      // Put also the class in a special variable
      // useful when we want to update the PCMN
      // TODO permit the update of TMP_PCMN
      if ( $l_line['ad_id'] == ATTR_DEF_ACCOUNT ) {
	printf('<INPUT TYPE="HIDDEN" name="class" value="%s">',
	       $l_line['av_text']);
      
      } 
	// in hidden we put the jft_id
	$Hid=sprintf('<INPUT TYPE="HIDDEN" name="jft_id%d" value="%s">',
		     $i,$l_line['jft_id']);
	printf ('<TR><TD> %s </TD><TD><INPUT TYPE="TEXT" NAME="av_text%d" VALUE="%s">%s</TD></TR>',
		$l_line['ad_text'], $i, $l_line['av_text'],$Hid);
      
    }
    echo '</TR>';
    echo '</TABLE>';
    echo '<INPUT TYPE="SUBMIT" name="update_fiche" value="Mis à jour">';
    echo '</FORM>';
  }
}

/* function GetBaseFiche
 ***************************************************
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
  $Res=ExecSql($p_cn,"select fd_class_base from fiche_def where fd_id=".$p_type);
  if ( pg_NumRows($Res) == 0 ) return null;
  $base=pg_fetch_array($Res,0);
  return $base['fd_class_base'];
}

/* function GetBaseFicheDefault
 ***************************************************
 * Purpose : Give the default accounts of fiche_def
 *
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
function GetBaseFicheDefault($p_cn,$p_type) {
  $base=null;
  $Res=ExecSql($p_cn,"select frd_class_base from fiche_def_ref where frd_id=".$p_type);
  if ( pg_NumRows($Res) == 0 ) return null;
  $base=pg_fetch_array($Res,0);
  return $base['frd_class_base'];
}
/* function ViewFiche
 ***************************************************
 * Purpose : Montre les fiches d'une rubrique
 * 
 * parm : 
 *	-  p_cn connexion
 *      - $p_type fiche_def.fd_id catg of card
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 
function ViewFiche($p_cn,$p_type) {
  echo '<H2 class="info">'.getFicheDefName($p_cn,$p_type).'</H2>';
  // Get all name the cards of the select category
  // 1 for attr_def.ad_id is always the name
    $Res=ExecSql($p_cn,"select f_id,av_text  from 
                          fiche join jnt_fic_att_value using (f_id) 
                                join attr_value using (jft_id)
                       where fd_id='".$p_type."' and ad_id=".ATTR_DEF_NAME." order by f_id");
    $Max=pg_NumRows($Res);

    for ( $i = 0; $i < $Max; $i++) {
      $l_line=pg_fetch_array($Res,$i);
      $div="<DIV>";
      $span_mod='<span class="mtitle"><A class="mtitle2" href="fiche.php?action=detail&fiche_id='.$l_line['f_id'].'"> Modifie</A></SPAN>';
      $span_del='<span class="mtitle2" ALIGN="left">'.
	'<A class="mtitle2" href="fiche.php?f_fd_id='.$p_type.'&action=delete&fiche_id='.$l_line['f_id'].
	'"> delete</A></SPAN>';
      $span_id='<SPAN style="background-color:lightgrey;">'.$l_line['f_id']."</SPAN>";
      if ( $i %2 == 0 ) 
	$div='<DIV style="background-color:#DDE6FF;">';
        echo $div.$span_del.'&nbsp;'.'&nbsp;'.'&nbsp;'.'&nbsp;'.'&nbsp;'.'&nbsp;'.
	  $span_mod."&nbsp;"."&nbsp;"."&nbsp;".$span_id."&nbsp;"."&nbsp;"."&nbsp;".$l_line['av_text']."</DIV>";
    }
    echo '<FORM METHOD="POST" action="fiche.php">';
    echo '<INPUT TYPE="HIDDEN" name="fiche" value="'.$p_type.'">';
    echo '<INPUT TYPE="SUBMIT" name="add" Value="Ajout fiche">';
    echo '</FORM>';

}
/* function GetNextFiche
 ***************************************************
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
/* function ViewFicheDetail
 ***************************************************
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
  
  // Retrieve the fiche_def.fd_id of the card
  $fd_id=GetFicheDef($p_cn,$p_id);

  //Update the default attribute of a card if is model has changed
  $sql="insert into jnt_fic_att_value (f_id,ad_id) 
        select $p_id, ad_id 
              from jnt_fic_attr 
        where fd_id=$fd_id and 
        ad_id not in (select ad_id from jnt_fic_att_value where
        f_id=$p_id)";

 $Res=ExecSql($p_cn,$sql);

 // add a empty string to attr_value
 $sql="insert into attr_value select jft_id,'' from jnt_fic_att_value
         where f_id=$p_id and jft_id not in (select jft_id from attr_value natural join jnt_fic_att_value where 
         f_id = $p_id)"; 
 $Res=ExecSql($p_cn,$sql);

  EncodeFiche ($p_cn,$p_id,1);
}
/* function UpdateFiche
 ***************************************************
 * Purpose : Met a jour une fiche
 *          change dans le plan comptable, fiche,et isupp
 *          
 * parm : 
 *	- p_cn
 *      - p_array
 * gen :
 *	- none
 * return:
 *	- nothing
 *
 */ 
function UpdateFiche($p_cn,$p_array) {
  foreach ( $p_array as $key=> $element) {
    echo_debug("$key => $element");
    ${"$key"}=$element;
    // Get the name
    if ( $key == "ad_id".ATTR_DEF_NAME ) {
      $label=$element;
    }
    // Get the class base
    if ( $key=="ad_id".ATTR_DEF_ACCOUNT) {
      $class=$element;
    }
  }
  // If each card has it own account must also update the tmp_pcm table
  //
  $fd_ref=GetFicheDefRef($p_cn,$fiche);
  // Update all the others data
  for ( $i =0 ; $i < $max ; $i++) {
    $sql=sprintf("update attr_value set av_text='%s' where jft_id=%d",
		 ${"av_text$i"},
		 ${"jft_id$i"});
    echo_debug($sql);
    $Res=ExecSql($p_cn,$sql);

  }
  // Update the PCMN if needed
  $sql="select av_text from attr_value natural join jnt_fic_att_value
        where f_id=$fiche and ad_id=5";
  $Res=ExecSql($p_cn,$sql);

  if ( pg_NumRows($Res) != 0 ) {
    // it means that a account exists
    // Retrieve the name and the ad_id 5
    $f=pg_fetch_array($Res,0);
    $class_old=$f['av_text'];

    // if the class changed
    if ( $class != $class_old and $class != "" ) {
      if ( CountSql($p_cn,"select * from jrnx where j_poste=$class") or 
           CountSql($p_cn,"select * from jrnx where j_poste=$class_old" ))
      {
	// No change if the account is already used 
	echo_error("Not possible to change the account, already used");
      } else {
	if ( CountSql($p_cn,"select * from tmp_pcmn where pcm_val=".$class) ==1) {
	  $Res=ExecSql($p_cn,"update tmp_pcmn set pcm_val=$class_old where pcm_val=$class");
	} else // we have to insert 
	  {
	    // First we must use a parent
	    $parent=GetParent($p_cn,$class);
	    $Res=ExecSql($p_cn,
			 "insert into tmp_pcmn (pcm_val,pcm_lib,pcm_val_parent) 
                         values ($class,'$f_label',$parent)");
	  }
      }
	$class=$class_old;
    } else // $class=""
      {
	$class=$class_old;
	// First we must use a parent
	$parent=GetParent($p_cn,$class);
	$Res=ExecSql($p_cn,
		     "insert into tmp_pcmn (pcm_val,pcm_lib,pcm_val_parent) 
                         values ($class,'$f_label',$parent)");
      }
    

    // Change the name in TMP_PCMN
    // Get the new name
    ExecSql($p_cn,"update tmp_pcmn set pcm_lib='".$f_label."' where pcm_val=".$class);


  }
  // Update of TMP_PCMN if a class base is given (ad_id=5)



}
/* function EncodeModele
 ***************************************************
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
/* function DefModele
 ***************************************************
 * Purpose : Creation of a model of card or correction
 * 
 * parm : 
 *      - $p_cn  database connection 
 *	- $p_ligne number of lines
 *      - $p_array  array
 *      - $p_js class base (javascript code)
 * gen :
 *	- none
 * return:
 *	- nothing
 *
 */ 
function DefModele ($p_cn,$p_js,$p_array=null,$p_ligne=1) 
{
  echo_debug("DefModele ($p_array,$p_ligne=1) ");

  // Creating form
  $display='<FORM ACTION="fiche.php" METHOD="POST">';

  // Number of line of the card
  $display.='<INPUT TYPE="HIDDEN" NAME="INC" VALUE="'.$p_ligne.'">';

  // Table of input
  $display.='<TABLE BORDER="0" CELLSPACING="0">';
  
  
  //Name of the CARD (fiche_def.fd_label)
  $display.='<TR><TD> Catégorie de fiche </TD>';
  $display.='<TD><INPUT TYPE="INPUT" NAME="nom_mod">';
  $display.='</TD></TR>';

  // Class base fiche_def.fd_class_base (optional)
  $display.='<TR><TD> Classe de base </TD>';
  $display.='<TD><INPUT TYPE="INPUT" NAME="class_base"> '.$p_js;
  $display.='</TD></TR>';
  // Checkbox for the creation of a post
  $display.='<TR><TD> <INPUT TYPE="CHECKBOX" NAME="create"  UNCHECKED>Create accounts for each</TD></TR>';

  //display the different template
  $ref=Get_fiche_def_ref($p_cn);

  // check if $ref is an array 
  // and display the choice

  // select by default the first choice
  $check="CHECKED";
  if ( sizeof($ref)  ) {
    foreach ($ref as $i=>$v) {
      $display.='<TR><TD COLSPAN="2">';

      $display.='<INPUT TYPE="RADIO" NAME="FICHE_REF" VALUE="'.$v['id'].'"'.$check.'>';
      $display.=$v['text'];
      // if a class base exist in fiche_def_ref, we display it
      if ( sizeof ($v['class']) != 0 ) 
	   $display.="&nbsp;&nbsp<I>Class base = ".$v['class']."</I>";
      $display.="</TD></TR>";
      $check="";
    }
 
  }
  // closing the form
  $display.='</TABLE>';
  $display.='<INPUT TYPE="SUBMIT" NAME="add_modele" VALUE="Sauve">';
  $display.='</FORM>';

  // display it
  echo $display;

}
/* function AddModele
 ***************************************************
 * Purpose : Add a modele of card into the database
 *           
 * parm : 
 *	- connection
 *      - array
 * gen :
 *	- none
 * table : insert into fiche_def
 *         insert into attr_def
 * return:
 *	- none
 *
 */ 
function AddModele($p_cn,$p_array) {
  echo_debug("AddModele");
  // Show what we receive for debug purpose only
  //
  foreach ( $p_array as $key=>$element ) {
    echo_debug("p_$key $element");
    ${"p_$key"}=$element;
  }
  // Format correctly the name of the cat. of card
  $p_nom_mod=FormatString($p_nom_mod);
  echo_debug("Adding $p_nom_mod");
   // Format the p_class_base 
  // must be an integer
  if ( isNumber($p_class_base) == 0 && FormatString($p_class_base) != null ) {
    echo_error ('p_class_base is NOT a number');
  }

  // $p_FICHE_REF cannot be null !!! (== fiche_def_ref.frd_id
  if (! isset ($p_FICHE_REF) or strlen($p_FICHE_REF) == 0 ) {
    echo_error ("AddModele : fiche_ref MUST NOT be null or empty");
    return;
  }
  // build the sql request for fiche_def
  // and insert into fiche_def
  // if p_class_base is null get the default class base from
  // fiche_def_ref
  if ( FormatString($p_class_base) == null )
    { // p_class is null
      // So we take the default one
      $p_class_base=GetBaseFicheDefault($p_cn,$p_FICHE_REF);
    }
  // Set the value of fiche_def.fd_create_account
  if ( isset($p_create)) 
    $p_create='true';
  else
    $p_create='false';

  // Class is valid ?
  if ( FormatString($p_class_base) != null) {
    // TODO verify that the class base exists
    // p_class is a valid number
    $sql=sprintf("insert into fiche_def(fd_label,fd_class_base,frd_id,fd_create_account) 
                values ('%s',%s,%d,'%s')",
		 $p_nom_mod,$p_class_base,$p_FICHE_REF,$p_create);

    $Res=ExecSql($p_cn,$sql);

    // Get the fd_id
    $fd_id=GetSequence($p_cn,'s_fdef');

    // Add the class_base if needed
    // TODO replace 5 by the definition of class_base
    if ( $p_create=='true' ) {
      $sql=sprintf("insert into jnt_fic_attr(fd_id,ad_id) 
                     values (%d,%d)",$fd_id,ATTR_DEF_ACCOUNT);
      $Res=ExecSql($p_cn,$sql);
    }
  } else {
    //There is no class base not even as default
    $sql=sprintf("insert into fiche_def(fd_label,frd_id,fd_create_account) values ('%s',%d,'%s')",
		 $p_nom_mod,$p_FICHE_REF,$p_create);

  $Res=ExecSql($p_cn,$sql);

  // Get the fd_id
  $fd_id=GetSequence($p_cn,'s_fdef');

  }

  // Get the default attr_def from attr_min
  $def_attr=Get_attr_min($p_cn,$p_FICHE_REF);

   //if defaut attr not null 
  // build the sql insert for the table attr_def
  if (sizeof($def_attr) != 0 ) {
    // insert all the mandatory fields into jnt_fiche_attr
    foreach ( $def_attr as $i=>$v) {
      $sql=sprintf("insert into jnt_fic_Attr(fd_id,ad_id)
                   values (%d,%s)",
		   $fd_id,$v['ad_id']);
      ExecSql($p_cn,$sql);
    }
  }
  
  // TODO show the details of the created catg of card
}
/* function UpdateModele
 ***************************************************
 * Purpose : Modify the model of card
 *           change some attribute (fd_label) and permit
 *           to add line
 * 
 * parm : 
 *	- p_cn connexion
 *      - p_fiche fichedef(f_id)
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 
function UpdateModele($p_cn,$p_fiche) {

  $array=GetDataModele($p_cn,$p_fiche);
  if ($array==null) {
    echo_error ("fiche_inc:UpdateModele Fiche non trouvée");
    return;
  }
  echo '<H2 class="info">'.getFicheDefName($p_cn,$p_fiche).'</H2>';
  foreach ( $array as $key=>$element) echo_debug("$key => $element");
  DisplayDetailModele($p_cn,$array,$array['ligne']);

}
/* function GetDataModele
 ***************************************************
 * Purpose : Return the info of an fiche identified by
 *           p_fiche
 * 
 * parm : 
 *	- connection
 *      - fiche id
 * gen :
 *	- none
 * return:
 *	- array with all the data
 *
 */ 
function GetDataModele($p_cn,$p_fiche) {
  $Res=ExecSql($p_cn,"select fd_class_base,ad_id,ad_text from 
                          fiche_def
                          natural join jnt_fic_attr
                          natural join attr_def
                          where
                          fd_id=$p_fiche");
  $Max=pg_NumRows($Res) ;
  if ($Max==0) return null;

  // store the data in an array
  for ($i=0; $i < $Max;$i++) {
    $line=pg_fetch_array($Res,$i);
    if ($i == 0 ) {
      $array['label']=GetFicheDefName($p_cn,$p_fiche);
      $array['class_base']=$line['fd_class_base'];
      $array['fd_id']=$p_fiche;
    }//if $i == 0

    //Label
    $text=sprintf("ad_id%d",$i);
    $array[$text]=$line['ad_id'];
    //isd_id
    $text=sprintf("ad_text%d",$i);
    $array[$text]=$line['ad_text'];

  }//for
  $array['ligne']=$Max;
  return $array;
}
/* function Remove
 ***************************************************
 * Purpose : enleve une fiche dans attr_value, jnt_fic_att_value  et fiche
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
  // if the card has its own account in PCMN
  // Get the fiche_def.fd_id from fiche.f_id
  $fd_id=GetFicheDef($p_cn,$p_fid);
  if ( GetCreateAccount($p_cn,$fd_id) == 1 ) {
    // Retrieve the class 
    $class=GetClass($p_cn,$p_fid);

    // if class is not NULL and if we use it before, we can't remove it
    if (FormatString($class) != null && 
           CountSql($p_cn,"select * from jrnx where j_poste=$class") != 0 ) {
      echo "<SCRIPT> alert('Impossible ce poste est utilisé dans un journal'); </SCRIPT>";
      return;
    } else {
      // Remove in PCMN
      ExecSql($p_cn,"delete from tmp_pcmn where pcm_val=".$class);
    }

  }
  // Remove from attr_value
  $Res=ExecSql($p_cn,"delete from attr_value 
                        where jft_id in (select jft_id 
                                          from jnt_fic_att_value 
                                                natural join fiche where f_id=$p_fid)");
  // Remove from jnt_fic_att_value
  $Res=ExecSql($p_cn,"delete from jnt_fic_att_value where f_id=$p_fid");
  
  // Remove from fiche
  $Res=ExecSql($p_cn,"delete from fiche where f_id=$p_fid");
    
}
/* function getFicheName
 ***************************************************
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
  // Retrieve the attribute with the ad_id 1
  // 1 is always the name
  // TODO replace absolute value by defined value
  $Res=ExecSql($p_cn,"select av_text from 
                    attr_value
                    natural join jnt_fic_att_value 
                    natural join fiche 
                    where
                    f_id=$p_id and
                    ad_id=".ATTR_DEF_NAME);
  if ( pg_NumRows($Res) == 0 ) return "Unknown";
  $st=pg_fetch_array($Res,0);
  return $st['av_text'];
}
/* function getFicheDefName
 ***************************************************
 * Purpose : retourne le nom de la cat. de  fiches
 *        
 * parm : 
 *	- p_cn connexion
 *      - p_id fiche id fiche_def.fd_id
 * gen :
 *	- none
 * return:
 *     - string fiche_def.fd_label
 */
function getFicheDefName($p_cn,$p_id) {
  $Res=ExecSql($p_cn,"select fd_label from fiche_def where fd_id=$p_id");
  if ( pg_NumRows($Res) == 0 ) return "Unknown";
  $st=pg_fetch_array($Res,0);
  return $st['fd_label'];
}

/* function GetFicheJrn
 ***************************************************
 * Purpose : Get all the fiche related to a "journal"
 *        
 * parm : 
 *	- p_cn connextion
 *      - j_jrn journal_id
 *      - $p_type : deb or cred
 *      - $p_fiche_type : fiche_def_ref.frd_id to know what kind of card is asked
 *                        -> Sell, Customer...
 * gen :
 *	- none
 * return: array containing the fiche(f_id),fiche(f_label)
 */
function GetFicheJrn($p_cn,$p_jrn,$p_type,$p_fiche_type)
{
  $get="";
  if ( $p_type == 'deb' ) {
    $get='jrn_def_fiche_deb';
  }
  if ( $p_type == 'cred' ) {
    $get='jrn_def_fiche_cred';
  }
  if ( $get == "" ) {
    echo_error("Invalid p_type function GetFicheJrn($p_cn,$p_jrn,$p_type)");
    exit -1;
  }
  $Res=ExecSql($p_cn,"select $get as fiche from jrn_def where jrn_def_id=$p_jrn");
  $Max=pg_NumRows($Res);
  if ( $Max==0) {
    echo_warning("No rows");
    return null;
  }
  // Normally Max must be == 1
  $list=pg_fetch_array($Res,0);
  if ( $list['fiche']=="") {
    echo_warning("No fiche");
    return null;
  }
 $sql="select f_id,av_text as f_label 
        from fiche natural join jnt_fic_att_value
        natural join attr_def
        natural join attr_value
        natural join fiche_def_ref
        where ad_id=1 and
           fd_id in (".$list['fiche'].") and frd_id=$p_fiche_type order by f_label";

  $Res=ExecSql($p_cn,$sql);
  $Max=pg_NumRows($Res);
  if ($Max==0 ) return null;
  // Get The result and put it into an array
  for ($i=0;$i<$Max;$i++) {
    $line=pg_fetch_array($Res,$i);
    $f_id=$line['f_id'];
    $f_label=$line['f_label'];
    $a[$i]=array($f_id,$f_label);
  }
  return $a;
}
/* function Get_fiche_def_ref
 ***************************************************
 * Purpose : 
 *        return an array containing all the
 *        data contained in the table fiche_def_ref
 * parm : 
 *	- $p_cn  connection to the database
 * gen :
 *	- none
 * return:
 *       array if ok null otherwize
 */
function Get_fiche_def_ref($p_cn)
{
  // get all the data from fiche_def_ref
  $Res=ExecSql($p_cn, "select frd_id,frd_text,frd_class_base 
                          from fiche_def_ref
                       order by frd_text");

  // check if the result is valid
  $Max=pg_NumRows($Res);
  if ( $Max == 0) return null;

  // store the result in a array
  for ($i=0;$i<$Max;$i++) {
    $f=pg_fetch_array($Res,$i);
    $array[$i]['id']=$f['frd_id'];
    $array[$i]['text']=$f['frd_text'];
    $array[$i]['class']=$f['frd_class_base'];
  }

  // return result
  return $array;
}
/* function Get_attr_min
 ***************************************************
 * Purpose : retrieve the mandatory field of the card model
 *        
 * parm : 
 *	- $p_cn  database connexion
 *      - $p_fiche_def_ref 
 * gen :
 *	-
 * return:
 *      array of ad_id  (attr_min.ad_id) and  labels (attr_def.ad_text)
 */
function Get_attr_min($p_cn,$p_fiche_def_ref) {
  // find the min attr for the fiche_def_ref
  $Sql="select ad_id,ad_text from attr_min natural join attr_def 
         natural join fiche_def_ref
      where
      frd_id= $p_fiche_def_ref";
  $Res=ExecSql($p_cn,$Sql);
  $Num=pg_NumRows($Res);

  // test the number of returned rows
  if ($Num == 0 ) return null;

  // Get Results & Store them in a array
  for ($i=0;$i<$Num;$i++) {
    $f=pg_fetch_array($Res,$i);
    $array[$i]['ad_id']=$f['ad_id'];
    $array[$i]['ad_text']=$f['ad_text'];
  }
  return $array;
}
/* function Get_attr_def
 ***************************************************
 * Purpose : retrieve the  fields of the card model
 *        
 * parm : 
 *	- $p_cn  database connexion
 *      - $p_fiche_def_ref fiche_def.fd_id
 * gen :
 *	-
 * return:
 *      array of ad_id  (attr_def.ad_id) and  labels (attr_def.ad_text)
 */
function Get_attr_def($p_cn,$p_fiche_def) {
  // find the min attr for the fiche_def_ref
  $Sql="select ad_id,ad_text from attr_def 
         natural join jnt_fic_attr
         natural join fiche_def
      where
      fd_id= $p_fiche_def order by ad_id";
  $Res=ExecSql($p_cn,$Sql);
  $Num=pg_NumRows($Res);

  // test the number of returned rows
  if ($Num == 0 ) return null;

  // Get Results & Store them in a array
  for ($i=0;$i<$Num;$i++) {
    $f=pg_fetch_array($Res,$i);
    $array[$i]['ad_id']=$f['ad_id'];
    $array[$i]['ad_text']=$f['ad_text'];
  }
  return $array;
}
/* function GetCreateAccount
 ***************************************************
 * Purpose : retrieve the  fields of the card model
 *        which indicate if each cards needs its own account
 * parm : 
 *	- $p_cn  database connexion
 *      - $p_fiche_def_ref fiche_def.fd_id
 * gen :
 *	-
 * return:
 *      not null if yes, each cards has its own account
 */
function GetCreateAccount($p_cn,$p_fiche_def) {
  // find the min attr for the fiche_def_ref
  $Sql="select fd_create_account
      from fiche_def
        where
      fd_id= $p_fiche_def";
  $Res=ExecSql($p_cn,$Sql);
  $Num=pg_NumRows($Res);

  // test the number of returned rows
  if ($Num == 0 ) return 0;


  for ($i=0;$i<$Num;$i++) {
    $f=pg_fetch_array($Res,$i);
    echo_debug ("fd_create_account == ".$f['fd_create_account']);
    if ( $f['fd_create_account']=='t') {
      echo_debug("fd_create_account return 1");
      return 1;
    }
  }
  echo_debug("fd_create_account return 0");
  return 0;
}
/* function GetFicheDefRef
 ***************************************************
 * Purpose : retrieve the fiche_def.frd_id thanks the f_id 
 *           of a card
 *        
 * parm : 
 *	- $p_cn connection
 *      - $p_f_id  fiche.f_id
 * gen :
 *	- none
 * return:
 *     - the fiche_def.frd_id or null if nothing has been found
 */
function GetFicheDefRef($p_cn,$p_f_id)
{
  // Sql stmt
  $sql="select frd_id from fiche_def join fiche using (fd_id) 
      where f_id=$p_f_id";
  // Execute it
  $Res=ExecSql($p_cn,$sql);

  // nothing is found
  if ( pg_NumRows($Res) == 0 ) return null;

  // Fetch the data
  $f=pg_fetch_array($Res,0);

  // return the value
  return $f['frd_id'];

}
/* function GetFicheRef
 ***************************************************
 * Purpose : retrieve the fiche_def.fd_id thanks the f_id 
 *           of a card
 *        
 * parm : 
 *	- $p_cn connection
 *      - $p_f_id  fiche.f_id
 * gen :
 *	- none
 * return:
 *     - the fiche_def.fd_id or null if nothing has been found
 */
function GetFicheDef($p_cn,$p_f_id)
{
  // Sql stmt
  $sql="select fd_id from fiche_def join fiche using (fd_id) 
      where f_id=$p_f_id";
  // Execute it
  $Res=ExecSql($p_cn,$sql);

  // nothing is found
  if ( pg_NumRows($Res) == 0 ) return null;

  // Fetch the data
  $f=pg_fetch_array($Res,0);

  // return the value
  return $f['fd_id'];

}

/* function  GetClass
 ***************************************************
 * Purpose : Retrieve the account of a card
 *        
 * parm : 
 *	- connection 
 *      - card id (fiche.f_id)
 * gen :
 *	- none
 * return: 
 *      string if a class if found or null otherwise
 */
function GetClass($p_cn,$p_fid) {
  echo_debug("GetClass($p_fid)");
  
  // the account class is in the av_text with the ad_id=5 in 
  // attr_Def
  $sql="select av_text from attr_value 
                     natural join jnt_fic_att_value
                      natural join attr_def 
                    where
                      ad_id=".ATTR_DEF_ACCOUNT." and f_id=$p_fid";
  // Exec
  $Res=ExecSql($p_cn,$sql);
  if (pg_NumRows($Res) == 0 ) return null;

  // Fetch the data and return it
  $f=pg_fetch_array($Res,0);
  return $f['av_text'];
}
/* function InsertModeleLine
 **************************************************
 * Purpose : Insert a new row into jnt_fic_attr
 *        for adding a new attribute to the card model
 * parm : 
 *	- p_cn   connection
 *      - the fiche_def.fd_id, id of the cat of the model
 *      - the ad_id is the attr_def.ad_id
 * gen :
 *	- none
 * return: none
 */
function InsertModeleLine($p_cn,$p_fid,$p_adid) 
{
  // Insert a new attribute for the model
  // it means insert a row in jnt_fic_attr
  $sql=sprintf("insert into jnt_fic_attr (fd_id,ad_id) values (%d,%d)", 
	       $p_fid,$p_adid);
  $Res=ExecSql($p_cn,$sql);
                       
}
/* function SaveModeleName
 **************************************************
 * Purpose : Update the model's name
 *
 * parm : 
 *	- p_cn   connection
 *      - the fiche_def.fd_id, id of the cat of the model
 *      - the label
 * gen :
 *	- none
 * return: none
 */
function SaveModeleName($p_cn,$p_fid,$p_label) 
{
  // Insert a new attribute for the model
  // it means insert a row in jnt_fic_attr
  $sql=sprintf("update   fiche_def set fd_label='%s' where 
                  fd_id=%d", 
	       $p_label,$p_fid);
  $Res=ExecSql($p_cn,$sql);
                       
}

/* function DisplayDetailModele
 **************************************************
 * Purpose : Show the data contained in an array of
 *           a model of card (fiche_def)
 *        
 * parm : 
 *      - p_cn database connection
 *	- array containing 
 *                 label,class_base,fd_id,ad_textX,ad_idX
 *      - number of line
 * gen :
 *	- none
 * return:
 *      none
 */
function DisplayDetailModele($p_cn,$p_array,$MaxLine)
{
  echo_debug("DisplayDetailModele");

  foreach ($p_array as $v=>$i) { 
    echo_debug("v == $v i==$i");
    ${"$v"}=$i;
  }
  echo '<FORM action="fiche.php" method="get">';
  echo '<INPUT TYPE="HIDDEN" NAME="fd_id" VALUE="'.$fd_id.'">';

  printf("<TABLE>");
  // Display each attribute
  for ($i=0;$i<$MaxLine;$i++) {
    echo '<TR><td>';
    // Can change the name
    if ( ${"ad_id$i"} == ATTR_DEF_NAME ) {
      printf('Label</TD><TD><INPUT TYPE="TEXT" NAME="label" VALUE="%s">',
	     $label);
      printf('</td><TD><input type="submit" NAME="change_name" value="Change Nom">');
    } else {
      // The attr.
      printf('%s ',
	     ${"ad_text$i"});
    }
    echo '</td></tr>';
  }

  // Show the possible attribute which are not already attribute of the model
  // of card
  $Res=ExecSql($p_cn,"select ad_id,ad_text from attr_def 
                       where ad_id not in (select ad_id from fiche_def natural join jnt_fic_attr
                           where fd_id=$fd_id)");
  $M=pg_NumRows($Res);

  // Show the unused attribute
  echo '<TR> <TD>';
  echo '<SELECT NAME="ad_id">';
  for ($i=0;$i<$M;$i++) {
    $l=pg_fetch_array($Res,$i);
    printf('<OPTION VALUE="%s"> %s',
	   $l['ad_id'],$l['ad_text']);
  }
  echo '</SELECT>';
  echo '</TD><TD> <INPUT TYPE="SUBMIT" Value="Add that line" NAME="add_ligne"></TD></TR>';
  printf("</TABLE>");
  echo '</FORM>';
}
/* function GetParent
 **************************************************
 * Purpose : Get the parent in tmp_pcmn
 *        
 * parm : 
 *	- cn database connextion
 *      - the base we want insert
 * gen :
 *	-
 * return: the parent
 *
 */
function GetParent($p_cn,$p_val) 
{
  $len=strlen($p_val)-1;
  for ($i=$len-1;$i>0;$i--) {
    $a=substr($p_val,0,$i);
    echo_debug ("parent == $a len =1");
    if (CountSql($p_cn,"select pcm_val from tmp_pcmn where pcm_val=$a") == 1)
      return $a;
  }
}

?>
