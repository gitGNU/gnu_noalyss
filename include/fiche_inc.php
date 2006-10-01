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
/*! \file
 * \brief Function for managing card, redundant with the class fiche
 * \todo remove this file but adapt first the pages which needs those 
 *       function and complete the class_fiche.php
 */

/*!
 ***************************************************
 * \brief  ajoute une nouvelle fiche
 * 
 * 
 * \param $p_cn connexion
 * \param $p_type type de la fiche
 * \param $p_array tableau
 *
 */ 
function AddFiche($p_cn,$p_type,$p_array) {
  echo '<h1>'.__FILE__.'#'.__line__.'AddFiche obsolete</h1>';
}
/*!   EncodeFiche
 ***************************************************
 * \brief  Affiche les détails d'une fiche et propose
 *           de mettre à jour
 *           ou si array est a null, permet d'ajouter une
 *           fiche, to fill the attribute
 * 
 * \param  $p_cn connexion
 * \param  $p_type id du modele fiche_def(fd_id) de la fiche SI array est null
 *         sinon correspond au id d'une fiche fiche(f_id)
 *
 * \note STAN: je ne trouve pas le nom "EncodeFiche" super claire pour une fonction qui affiche les détails d'une fiche...
 * on ne changerait pas en "DisplayFiche" ?
 * \note DANY: Oui je suis assez d'accord avec toi
 *
 */ 
function EncodeFiche($p_cn,$p_type,$p_array=null) {
  echo '<h1>'.__FILE__.'#'.__line__.'EncodeFiche obsolete</h1>';
}

/*!   GetBaseFiche
 ***************************************************
 * \brief  donne la classe comptable de base d'une fiche
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

/*!   GetBaseFicheDefault
 ***************************************************
 * \brief  Give the default accounts of fiche_def
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
/*!   ViewFiche
 ***************************************************
 * \brief  Montre les fiches d'une rubrique
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
  echo "<h1>".__FILE__." # ".__LINE__." ViewFiche Obsolete please update</h1>";
}
/*!   GetNextFiche
 ***************************************************
 * \brief  Crée le poste suivant pour une fiche en fonction
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
 echo_debug('fiche_inc.php',__LINE__,"$Max=pg_NumRows");
  $l_line=pg_fetch_array($Res,0);
  $ret=$l_line['maxcode'];
 if ( $ret == "" ) {
   $ret=sprintf("%d%04d",$p_base,0);
   return $ret+1;

 }

  echo_debug('fiche_inc.php',__LINE__,"ret $ret");
  return $ret+1;
}
/*!   ViewFicheDetail
 ***************************************************
 * \brief  Montre  le detail d'une fiche
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
    echo "<h1>".__FILE__." # ".__LINE__." ViewFicheDetail Obsolete please update</h1>";
}
/*!   UpdateFiche
 ***************************************************
 * \brief  Met a jour une fiche
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
  echo "<h1>".__FILE__." # ".__LINE__." UpdateFiche Obsolete please update</h1>";

}
/*!   EncodeModele
 ***************************************************
 * \brief 
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
  echo "<h1>".__FILE__." # ".__LINE__." EncodeModele Obsolete please update</h1>";
}
/*!   DefModele
 ***************************************************
 * \brief  Creation of a Category of card or correction
 * 
 * 
 * \param $p_cn  database connection 
 * \param $p_ligne number of lines
 * \param $p_array  array
 * \param $p_js class base (javascript code)
 *
 * \return nothing
 *	
 *
 */ 
function CreateCategory ($p_cn,$p_js,$p_array=null,$p_ligne=1) 
{
  echo_debug('fiche_inc.php',__LINE__,"DefModele ($p_array,$p_ligne=1) ");

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
  $display.='<TR><TD> <INPUT TYPE="CHECKBOX" NAME="create" CHECKED>Cr&eacute;ation automatique du poste comptable</TD></TR>';

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
/*!   AddModele
 ***************************************************
 * \brief  Add a modele of card into the database
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
  echo "<h1> AddModele Obsolete upgrade please </h1>";
}
/*!   UpdateModele
 ***************************************************
 * \brief  Modify the model of card
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
  echo '<h1>'.__FILE__.'#'.__line__.'UpdateModele obsolete</h1>';
}
/*!   GetDataModele
 ***************************************************
 * \brief  Return the info of an fiche identified by
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
  echo '<h1>'.__FILE__.'#'.__line__.'GetDataModele obsolete</h1>';
}
/*!   Remove
 ***************************************************
 * \brief  enleve une fiche dans attr_value, jnt_fic_att_value  et fiche
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
  echo '<h1>'.__FILE__." # ".__LINE__."Remove obsolete</h1>";    
}
/*!   getFicheName
 ***************************************************
 * \brief  retourne le nom de la fiche
 *           en fournissant le quick_code
 *        
 * parm : 
 *	- p_cn connexion
 *      - p_id quick_code
 * gen :
 *	- none
 * return:
 *     - string avec nom fiche
 */
function getFicheName($p_cn,$p_id) {
  // Retrieve the attribute with the ad_id 1
  // 1 is always the name
  $p_id=FormatString($p_id);
  $Res=ExecSql($p_cn,"select vw_name from 
                        vw_fiche_attr where quick_code=upper('$p_id')");
  if ( pg_NumRows($Res) == 0 ) return "Unknown";
  $st=pg_fetch_array($Res,0);
  return $st['vw_name'];
}
/*!   getFicheNameById
 ***************************************************
 * \brief  retourne le nom de la fiche en fournissant le f_id
 *        
 * parm : 
 *	- p_cn connexion
 *      - p_id quick_code
 * gen :
 *	- none
 * return:
 *     - string avec nom fiche
 */
function getFicheNameById($p_cn,$p_id) {
  // Retrieve the attribute with the ad_id 1
  // 1 is always the name
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

/*!   getFicheDefName
 ***************************************************
 * \brief  retourne le nom de la cat. de  fiches
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

/*!   GetFicheJrn
 ***************************************************
 * \brief  Get all the fiche related to a "journal"
 *        
 * parm : 
 *	- p_cn connextion
 *      - j_jrn journal_id
 *      - $p_type : deb or cred
 * gen :
 *	- none
 * return: array containing the fiche(f_id),fiche(f_label)
 */
function GetFicheJrn($p_cn,$p_jrn,$p_type)
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
        natural join fiche_def
        where ad_id=1 and
           fd_id in (".$list['fiche'].")  order by f_label";

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
/*!   Get_fiche_def_ref
 ***************************************************
 * \brief  
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
/*!   Get_attr_min
 ***************************************************
 * \brief  retrieve the mandatory field of the card model
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
/*!   Get_attr_def
 ***************************************************
 * \brief  retrieve the  fields of the card model
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
/*!   GetCreateAccount
 ***************************************************
 * \brief  retrieve the  fields of the card model
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
      echo_debug('fiche_inc.php',__LINE__,"fd_create_account return 1");
      return 1;
    }
  }
  echo_debug('fiche_inc.php',__LINE__,"fd_create_account return 0");
  return 0;
}
/*!   GetFicheDefRef
 ***************************************************
 * \brief  retrieve the fiche_def.frd_id thanks the f_id 
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
/*!   GetFicheRef
 ***************************************************
 * \brief  retrieve the fiche_def.fd_id thanks the f_id 
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

/*!    GetClass
 ***************************************************
 * \brief  Retrieve the account of a card
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
  echo_debug('fiche_inc.php',__LINE__,"GetClass($p_fid)");
  
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
/*!   InsertModeleLine
 **************************************************
 * \brief  Insert a new row into jnt_fic_attr
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
  echo '<h1>'.__FILE__.'#'.__line__.'InsertModeleLine obsolete</h1>';
}
/*!   SaveModeleName
 **************************************************
 * \brief  Update the model's name
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
  echo '<h1>'.__FILE__.'#'.__line__.'SaveModeleName obsolete</h1>';
}

/*!   DisplayDetailModele
 **************************************************
 * \brief  Show the data contained in an array of
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
  echo '<h1>'.__FILE__."#".__LINE__."DisplayDetailModele obsolete</h1>";
}
/*!   GetParent
 **************************************************
 * \brief  Get the parent in tmp_pcmn
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
/*!   getFicheAttribut
 ***************************************************
 * \brief  retrun array of attribut or string
 *        
 * \param p_cn connexion
 * \param p_id quick_code
 * \param attribut if attribut == "" then return a array
 *        taken from vw_fiche_attr
 * \return string avec nom fiche is attr is not null 
 *         or array is attr is empty
 *   
 */
function getFicheAttribut($p_cn,$p_id,$p_attr="") {
  // Retrieve the attribute with the ad_id 1
  // 1 is always the name
  $p_id=FormatString($p_id);
  if ( $p_attr != "") {
    $Res=ExecSql($p_cn,"select av_text from 
                    attr_value
                    natural join jnt_fic_att_value 
                    natural join fiche 
                    where
                    ad_id=$p_attr 
                    and f_id=(select f_id from vw_poste_qcode where j_qcode=upper('$p_id')) ");
    if ( pg_NumRows($Res) == 0 ) return NULL;
    $st=pg_fetch_array($Res,0);
    return $st['av_text'];
  } else {
    // Get attribut from the view
    $Res=ExecSql($p_cn,"select * from vw_fiche_attr where quick_code=upper('$p_id')");
    if ( pg_NumRows($Res) == 0 ) return null;
    $st=pg_fetch_array($Res,0);
    return $st;
  }
}

/*!   IsFicheOfJrn
 ***************************************************
 * \brief   Check if a fiche is used by a jrn
 *  return 1 if the  fiche is in the range otherwise 0
 *        
 * 
 * \param  $p_cn connextion
 * \param   $j_jrn journal_id
 * \param   $p_fiche : quick_code
 * \param   $p_type : deb or cred default empty
 * 
 *
 * \return 1 if the fiche is in the range otherwise 0
 * 
 */
function IsFicheOfJrn($p_cn,$p_jrn,$p_fiche,$p_type="")
{
  $get="";
  if ( $p_type == 'deb' ) {
    $get='jrn_def_fiche_deb';
  }
  if ( $p_type == 'cred' ) {
    $get='jrn_def_fiche_cred';
  }
  if ( $get != "" ) {
    $Res=ExecSql($p_cn,"select $get as fiche from jrn_def where jrn_def_id=$p_jrn");
  } else {
    // Get all the fiche type (deb and cred)
    $Res=ExecSql($p_cn," select jrn_def_fiche_cred as fiche  
                         from jrn_def where jrn_def_id=$p_jrn
                        union
                         select jrn_def_fiche_deb 
                         from jrn_def where jrn_def_id=$p_jrn"
		 );
  }
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
  $p_fiche=FormatString($p_fiche);  
  $sql="select *
        from vw_fiche_attr
        where  
           fd_id in (".$list['fiche'].") and quick_code=upper('$p_fiche')"; 

  $Res=ExecSql($p_cn,$sql);
  $Max=pg_NumRows($Res);
  if ($Max==0 ) 
    return 0;
  else
    return 1;
}
?>
