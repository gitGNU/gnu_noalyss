<?php  
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
 *        but make sometimes the code more easy
 */
require_once('class_fiche.php');

/*!  
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
  echo_debug('fiche_inc.php',__LINE__,"CreateCategory ($p_array,$p_ligne=1) ");

  // Creating form
  $display='<FORM ACTION="?p_action=fiche" METHOD="POST">';
  $display.=dossier::hidden();
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
/*!   getFicheName
 ***************************************************
 * \brief  retourne le nom de la fiche
 *           en fournissant le quick_code
 *        
 *  
 * \param  p_cn connexion
 * \param p_id quick_code
 *
 */
function getFicheName($p_cn,$p_id) {
  // Retrieve the attribute with the ad_id 1
  // 1 is always the name
  $p_id=FormatString($p_id);
  $fiche=new fiche($p_cn);
  $fiche->get_by_qcode($p_id);
  return $fiche->strAttribut(ATTR_DEF_NAME);
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
 * \param $p_cn  database connexion 
 * \param $p_fiche_def_ref 
 * \return array of ad_id  (attr_min.ad_id) and  labels (attr_def.ad_text)
 *      
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
 *\todo fiche_inc.php function isFicheOfJrn this function is now in
 * class_fiche with the name belong_ledger, it should be replace
 * everywhere in the code 
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
