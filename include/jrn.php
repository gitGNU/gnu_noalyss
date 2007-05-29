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

/* $Revision$ */

/*! \file
 * \brief work with the ledger
 */
require_once('class_fiche.php');
/*! 
 * \brief  Display the form to UPDATE account operation
 *          
 * \param $p_cn database connection
 * \param $jr_id pk of jrn
 *
 * \return none
 *
 *
 */ 
 function UpdateJrn($p_cn,$p_jr_id)
{
  echo_debug('jrn.php',__LINE__,"function UpdateJrn");

  $l_array=GetDataJrnJrId($p_cn,$p_jr_id);
  if ( $l_array == null ) {
    echo_error ("Not data found for UpdateJrn p_jr_id = $p_jr_id");
    return ;
  }
  echo_debug('jrn.php',__LINE__,$l_array);
  // Javascript
  $r=JS_VIEW_JRN_MODIFY;

  // Build the form
  $col_vide="<TD></TD>";
  for ( $i =0 ; $i < sizeof($l_array); $i++) {
    $content=$l_array[$i] ;

      // for the first line
      if ( $i == 0 ) {
	$r.="<TABLE>";
	$r.="<TR>";
	// Date
	$r.="<TD>";
	$r.=$content['jr_date'];
	$r.="</TD>";
	// for upload document we need the grpt_id   
	$r.='<Input type="hidden" name="jr_grpt_id" value="'.$content['jr_grpt_id'].'">';

	// comment can be changed
	$r.="<TD>";
	$r.='<INPUT TYPE="TEXT" name="comment" value="';
	$r.=$content['jr_comment'];
	$r.='" SIZE="25">';
	$r.="</TD>";

	// Internal
	$r.="<TD>";
	$r.=$content['jr_internal'];
	$r.="</TD>";

	if ( $content['jrn_def_type'] == 'ACH' or 
	     $content['jrn_def_type'] == 'VEN' )
	  {
	    // Is Paid
	    $r.="<TD>";
	    $check=( $content['jr_rapt'] != null )?"CHECKED":"UNCHECKED";
	    $r.='<TD>Payé <INPUT TYPE="CHECKBOX" name="is_paid" '.$check.'></TD>';
	  }
	$r.="</TR>";
	$r.="</TABLE>";
	$r.="<TABLE>";
      }
      $r.="<TR>";
      if ( $content['j_debit'] == 'f' ) $r.=$col_vide;
      //      $r.="<TD>".$content['j_debit']."</td>";
      
      $r.="<TD>".$content['j_poste']."</td>";
      if ( $content['j_debit'] == 't' ) $r.=$col_vide;
      $r.="<TD>".$content['vw_name']."</td>";
      if ( $content['j_debit'] == 'f' ) $r.=$col_vide;
      $r.="<TD>".$content['j_montant']."</td>";
      $r.="</TR>";

      //    }//     foreach ($l_array[$i]  as $value=>$content) 
  }// for ( $i =0 ; $i < sizeof($l_array); $i++) 
    $file=new widget("file");
    $file->table=1;
	//document
    $r.='<TD>A effacer <INPUT TYPE="CHECKBOX" name="to_remove" ></TD>';
    $r.="<TD>".sprintf('<A class="detail" HREF="show_pj.php?jrn=%s&jr_grpt_id=%s">%s</A>',
		$content['jr_id'],
		$content['jr_grpt_id'],
		$content['jr_pj_name'])."</TD>";
    $r.="</TR></TABLE>";
    $r.="<hr>";
    $r.= "<table>"; 
    $r.="<TR>".$file->IOValue("pj","","Pièce justificative")."</TR>";
    $r.="</table>";
    $r.="<hr>";

  $r.="</table>";
  $r.="Total ".$content['jr_montant']."<br>";
  // show all the related operation
  $a=GetConcerned($p_cn,$content['jr_id']);
  
  if ( $a != null ) {
      $r.="<b>Operation concernée</b> <br>";
      if ( isset ($_GET["PHPSESSID"]) ) {
	$sessid=$_GET["PHPSESSID"];
      }
      else {
	$sessid=$_POST["PHPSESSID"];
      }

    $r.= '<div style="margin-left:30px;">';
    foreach ($a as $key => $element) {
      $r.=sprintf ('%s <INPUT TYPE="BUTTON" VALUE="Détail" onClick="modifyOperation(\'%s\',\'%s\')">', 
		   GetInternal($p_cn,$element),
		   $element,
		   $sessid);
      $r.=sprintf('<INPUT TYPE="button" value="Efface" onClick="dropLink(\'%s\',\'%s\',\'%s\')"><BR>',
		  $content['jr_id'],$element,$sessid);
    }//for
    $r.= "</div>";
  }// if ( $a != null ) {

  if ( isset ($_GET["PHPSESSID"]) ) {
    $sessid=$_GET["PHPSESSID"];
  }
  else {
    $sessid=$_POST["PHPSESSID"];
  }
  
  $search='<INPUT TYPE="BUTTON" VALUE="Cherche" OnClick="SearchJrn(\''.$sessid."','rapt','".$content['jr_montant']."')\">";

  $r.= '<H2 class="info">rapprochement </H2> 
       <INPUT TYPE="TEXT" name="rapt" value="">'.$search;
  $r.='<input type="hidden" name="jr_id" value="'.$content['jr_id'].'">';

  //  echo $r;
  return $r;
}

/*! 
 * \brief  Get the properties of a journal
 * 
 * parm : 
 *	- p_dossier the folder id
 *      - p_jrn the jrn id
 * gen :
 *	- none
 * return:
 *	- an array containing properties
 *
 */ 
function GetJrnProp($p_dossier,$p_jrn,$is_connected=0) 
{
  echo_debug("jrn.php",__LINE__,"GetJrnProp $p_dossier");
  if ( $is_connected == 0 ) 
    $cn=DbConnect($p_dossier);
  else
    $cn=$p_dossier;

  $Res=ExecSql($cn,"select jrn_Def_id,jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_def_type, 
                   jrn_deb_max_line,jrn_cred_max_line,jrn_def_ech,jrn_def_ech_lib,jrn_def_code,
                   jrn_def_fiche_deb,jrn_def_fiche_deb
                   from jrn_Def 
                      where jrn_def_id=$p_jrn");
  $Count=pg_NumRows($Res);
  if ( $Count == 0 ) {
    echo '<DIV="redcontent"><H2 class="error"> Paramètres journaux non trouvés</H2> </DIV>';
    return null;
  }
  return pg_fetch_array($Res,0);
}
/*! 
 * \brief  Vue des écritures comptables
 * 
 * parm : 
 *	- p_dossier,
 *      - p_user,
 *      - p_jrn
 *      - p_url for modif
 *      - array
 * gen :
 *	-
 * return:
 *	-
 *
 */ 
function ViewJrn($p_dossier,$p_user,$p_jrn,$p_url,$p_array=null) {
  echo_debug('jrn.php',__LINE__,"function ViewJrn($p_dossier,$p_user,$p_jrn,$p_array=null) ");

  $db=sprintf("dossier%d",$p_dossier);
  $l_prop=GetJrnProp($p_dossier,$p_jrn);
  echo "<H2 class=\"info\">".$l_prop['jrn_def_name']."( ".$l_prop['jrn_def_code'].")"."</H2>";
  $cn=DbConnect($p_dossier);
  if ( $p_array == null) {
    include_once("preference.php");
    $l_periode=GetUserPeriode($cn,$p_user);
    $Res=ExecSql($cn,"select jr_id,j_id,jr_internal,to_char(j_date,'DD.MM.YYYY') as j_date,
                       j_montant,j_poste,pcm_lib,j_grpt,j_debit,j_centralized,j_tech_per,
                       pcm_lib
                   from jrnx inner join tmp_pcmn on j_poste=pcm_val
                             inner join jrn on jr_grpt_id=j_grpt
                   where 
                   j_jrn_def=$p_jrn and j_tech_per=$l_periode
                   order by j_id,j_grpt,j_debit desc");
  } else {
    // Construction Query 
    foreach ( $p_array as $key=>$element) {
      ${"l_$key"}=$element;
      echo_debug ("l_$key $element");
    }
    $sql="select j_id,to_char(j_date,'DD.MM.YYYY') as j_date,j_montant,j_poste,
                 pcm_lib,j_grpt,jr_id,j_debit,j_centralized,j_tech_per,jr_internal
                   from jrnx inner join tmp_pcmn on j_poste=pcm_val
                        inner join jrn on jr_grpt_id=j_grpt
                   where 
                   j_jrn_def=$p_jrn";
    $l_and="and ";
    if ( ereg("^[0-9]+$", $l_s_montant) || ereg ("^[0-9]+\.[0-9]+$", $l_s_montant) ) {
    $sql.=" and jr_montant $l_mont_sel $l_s_montant";
    }
    if ( isDate($l_date_start) != null ) {
      $sql.=$l_and." j_date >= to_date('".$l_date_start."','DD.MM.YYYY')";
    }
    if ( isDate($l_date_end) != null ) {
      $sql.=$l_and." j_date <= to_date('".$l_date_end."','DD.MM.YYYY')";
    }
    $l_s_comment=FormatString($l_s_comment);
    if ( $l_s_comment != null ) {
      $sql.=$l_and." upper(jr_comment) like upper('%".$l_s_comment."%') ";
    }

    $sql.=" order by j_id,j_grpt,j_debit desc";
    echo_debug ("search query is $sql");
    $Res=ExecSql($cn,$sql);
  }
  $MaxLine=pg_NumRows($Res);
  if ( $MaxLine == 0 ) return;
  $col_vide="<TD></TD>";
  echo '<TABLE ALIGN="center">';
  $l_id=0;
  for ( $i=0; $i < $MaxLine; $i++) {
    $l_line=pg_fetch_array($Res,$i);
    if ( $l_line['j_debit'] == 't' ) {
      echo '<TR style="background-color:lightblue;">';
    }
    else {
      echo '<TR>';
    }
    if ( $l_id == $l_line['j_grpt'] ) {
      echo $col_vide.$col_vide.$col_vide;
    } else {
      echo "<TD>";
      echo $l_line['j_date'];
      echo "</TD>";
      
      
	  echo "<TD>";
	  if ( isset ($_GET["PHPSESSID"])  ) {
	    $sessid=$_GET["PHPSESSID"];
	  } else {
	    $sessid=$_POST["PHPSESSID"];
	  }

	  list($z_type,$z_num,$num_op)=split("-",$l_line['jr_internal']);
	  printf ('<INPUT TYPE="BUTTON" VALUE="%s" onClick="modifyOperation(\'%s\',\'%s\')">', 
		  $num_op,$sessid,$l_line['jr_id']);
	  //	  echo $num_op;
	  echo "</TD>";


	  // no modification only cancel of wrong op.
	  echo '<TD class="mlltitle">';
	  echo "<A class=\"mtitle\" HREF=$p_url?action=update&line=".$l_line['jr_id'].">";
	  echo "M";
	  echo "</A></TD>";
      $l_id=$l_line['j_grpt'];
    }
    if ($l_line['j_debit']=='f')
      echo $col_vide;
    
    echo '<TD>';
    echo $l_line['j_poste'];
    echo '</TD>';

    echo '<TD>';
    echo $l_line['pcm_lib'];
    echo '</TD>';
    echo $col_vide;

    echo '<TD>';
    echo $l_line['j_montant'];
    echo '</TD>';

    if ( $l_line['j_debit']=='t')
      echo $col_vide;

    echo "</TR>";


  }
  echo '</TABLE>';
}
/*! 
 * \brief  Get data from jrnx where p_grpt=jrnx(j_grpt)
 * 
 * parm : 
 *	- connection
 *      - p_grpt
 * gen :
 *	- none
 * return:
 *	- return array
 *
 */ 
function GetData ($p_cn,$p_grpt) {
  echo_debug('jrn.php',__LINE__,"GetData $p_cn $p_grpt");
  $Res=ExecSql($p_cn,"select 
                        to_char(j_date,'DD.MM.YYYY') as j_date,
                        j_text,
                        j_debit,
                        j_poste,
                        j_montant,
                        j_id,
                        jr_comment,
			to_char(jr_ech,'DD.MM.YYYY') as jr_ech,
                        to_char(jr_date,'DD.MM.YYYY') as jr_date,
                        jr_id,jr_internal,jr_def_id
                     from jrnx inner join jrn on j_grpt=jr_grpt_id where j_grpt=$p_grpt");
  $MaxLine=pg_NumRows($Res);
  if ( $MaxLine == 0 ) return null;
  $deb=0;$cred=0;
  for ( $i=0; $i < $MaxLine; $i++) {
    
    $l_line=pg_fetch_array($Res,$i);
    $l_array['op_date']=$l_line['j_date'];
    if ( $l_line['j_debit'] == 't' ) {
      $l_class=sprintf("class_deb%d",$deb);
      $l_montant=sprintf("mont_deb%d",$deb);
      $l_text=sprintf("text_deb%d",$deb);
      $l_array[$l_class]=$l_line['j_poste'];
      $l_array[$l_montant]=$l_line['j_montant'];
      $l_array[$l_text]=$l_line['j_text'];
      $l_id=sprintf("op_deb%d",$deb);
      $l_array[$l_id]=$l_line['j_id'];
      $deb++;
    }
    if ( $l_line['j_debit'] == 'f' ) {
      $l_class=sprintf("class_cred%d",$cred);
      $l_montant=sprintf("mont_cred%d",$cred);
      $l_array[$l_class]=$l_line['j_poste'];
      $l_array[$l_montant]=$l_line['j_montant'];
      $l_id=sprintf("op_cred%d",$cred);
      $l_array[$l_id]=$l_line['j_id'];
      $l_text=sprintf("text_cred%d",$deb);
      $l_array[$l_text]=$l_line['j_text'];

      $cred++;
    }
    $l_array['jr_internal']=$l_line['jr_internal'];
    $l_array['comment']=$l_line['jr_comment'];
    $l_array['ech']=$l_line['jr_ech'];
    $l_array['jr_id']=$l_line['jr_id'];
    $l_array['jr_def_id']=$l_line['jr_def_id'];
   }
  return array($l_array,$deb,$cred);
}
/*! 
 * \brief 
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-  -1 si aucune valeur de trouvée
 *
 */ 
// function GetRapt($p_cn,$p_rappt) {

//   $Res=ExecSql($p_cn,"select jr_id from jrn where jr_rapt='$p_rappt'");
//   if ( pg_NumRows($Res) == 0 ) return -1;
//   $l_line=pg_fetch_array($Res);
//   return $l_line['jr_id'];
// }
/*! 
 * \brief  Return the internal value
 * 
 * parm : 
 *	- p_cn database connection
 *      - p_id : jrn.jr_id
 * gen :
 *	- none
 * return:
 *	-  null si aucune valeur de trouvée
 *
 */ 
function GetInternal($p_cn,$p_id) {

  $Res=ExecSql($p_cn,"select jr_internal from jrn where jr_id=$p_id");
  if ( pg_NumRows($Res) == 0 ) return null;
  $l_line=pg_fetch_array($Res);
  return $l_line['jr_internal'];
}

/*! 
 * \brief  return the sum of jrn where
 *            the internal_code is the p_id
 * 
 * parm : 
 *	- $p_cn database connection
 *  - p_id = jrn.jr_internal
 * gen :
 *	-
 * return:
 *	- number
 *
 */ 
function GetAmount($p_cn,$p_id) {
  $Res=ExecSql($p_cn,"select jr_montant from jrn where jr_internal='$p_id'");
  if (pg_NumRows($Res)==0) return -1;
  $l_line=pg_fetch_array($Res,0);
  return $l_line['jr_montant'];
}
/*! 
 * \brief  Verify the data before inserting or
 *           updating
 * 
 * parm : 
 *	- p_cn connection
 *      - p_array array with all the values
 *      - p_user
 * gen :
 *	- none
 * return:
 *	- errorcode or ok
 *
 */ 
function VerifData($p_cn,$p_array,$p_user)
{
  if ( ! isset ($p_cn) ||
       ! isset ($p_array)||
       ! isset ($p_user)||
       $p_array == null ){
    echo_error("JRN.PHP VerifData missing parameter");
    return BADPARM;
  }
  // Montre ce qu'on a encodé et demande vérif
  $next="";
  foreach ( $p_array as $name=>$element ) {
      echo_debug('jrn.php',__LINE__,"element $name -> $element ");
      // Sauve les données dans des variables
      ${"p_$name"}=$element;
    }
    // Verif Date
    if ( isDate($p_op_date) == null) {
      return BADDATE;
    }
    // userPref contient la periode par default
    $userPref=$p_user->GetPeriode();
    list ($l_date_start,$l_date_end)=GetPeriode($p_cn,$userPref);

    // Date dans la periode active
    echo_debug ("date start periode $l_date_start date fin periode $l_date_end date demandée $p_op_date");
    if ( cmpDate($p_op_date,$l_date_start)<0 || 
	 cmpDate($p_op_date,$l_date_end)>0 )
      {
	return NOTPERIODE;
      }
    // Periode fermée 
    if ( PeriodeClosed ($p_cn,$userPref)=='t' )
      {
	return PERIODCLOSED;
      }
    $l_mont=0;
    if ( ! isset ($p_ech) ) $p_ech="";

    if ($p_ech!='' && isDate ( $p_ech) == null ){
      return INVALID_ECH;
    }

    $tot_deb= 0;
    $tot_cred= 0;
    for ( $i = 0; $i < $p_MaxCred; $i++) {
      if ( isset ( ${"p_mont_cred$i"} ))
	$tot_cred+=${"p_mont_cred$i"};
    }
    for ( $i = 0; $i < $p_MaxDeb; $i++) {
      if ( isset ( ${"p_mont_deb$i"} ))
	$tot_deb+=${"p_mont_deb$i"};
    }
    echo_debug('jrn.php',__LINE__,"Amont = 	$tot_deb $tot_cred");
    if ( round($tot_deb,2) != round($tot_cred,2) ) { 
      return DIFF_AMOUNT;
    }

    return NOERROR;

}
/*! 
 * \brief  Return the name of the jrn
 * 
 * parm : 
 *	- p_cn connexion resource
 *      - jrn id
 * gen :
 *	- none
 * return:
 *	- string or null if not found
 *
 */ 
function GetJrnName($p_cn,$p_id) {
  $Res=ExecSql($p_cn,"select jrn_def_name from ".
	       " jrn_def where jrn_def_id=".
	       $p_id);
  $Max=pg_NumRows($Res);
  if ($Max==0) return null;
  $ret=pg_fetch_array($Res,0);
  return $ret['jrn_def_name'];
}
/*! 
 * \brief 
 *         Get the number of the next jrn
 *         from the jrn_def.jrn_code
 * 
 * parm : 
 *	- $p_cn connection
 *      - $p_type jrn type
 * gen :
 *	- none
 * return:
 *	- string containing the next code
 *
 */ 
function NextJrn($p_cn,$p_type)
{
  $Ret=CountSql($p_cn,"select * from jrn_def where jrn_def_type='".$p_type."'");
  return $Ret+1; 
}
/*! 
 * \brief 
 * 
 * parm : 
 *	- $p_cn connection
 *      - $p_grpt id in jr_grpt_id
 *      - $p_jrn jrn id jrn_def_id
 *      - $p_dossier dossier id
 * gen :
 *	-
 * return:
 *	-
 *
 */ 
function SetInternalCode($p_cn,$p_grpt,$p_jrn)
{
  //$num=CountSql($p_cn,"select * from jrn where jr_def_id=$p_jrn and jr_internal != 'ANNULE'")+1;
  $num = NextSequence($p_cn,'s_internal');
  $num=strtoupper(hexdec($num));
  $atype=GetJrnProp($_SESSION['g_dossier'],$p_jrn);
  $type=$atype['jrn_def_code'];
  $internal_code=sprintf("%d%s-%s",$_SESSION['g_dossier'],$type,$num);
  $Res=ExecSql($p_cn,"update jrn set jr_internal='".$internal_code."' where ".
	       " jr_grpt_id = ".$p_grpt);
  echo_debug ("jrn.php",__LINE__,"internal_code = $internal_code");
  return $internal_code;
}
/*! 
 * \brief  Get data from jrn and jrnx thanks the jr_id
 * 
 *
 *     \param connection
 *     \param p_jr_id (jrn.jr_id)
 *
 *
 * \return array
 *
 */ 
function GetDataJrnJrId ($p_cn,$p_jr_id) {

  echo_debug('jrn.php',__LINE__,"GetDataJrn $p_cn $p_jr_id");
  $Res=ExecSql($p_cn,"select 
                        j_text,
                        j_debit,
                        j_poste,
                       pcm_lib,
                        j_montant,
                        jr_montant,
                        j_id,
                        jr_pj_name,
                        jr_grpt_id,
                        jr_comment,
                        to_char(jr_ech,'DD.MM.YYYY') as jr_ech,
                        to_char(jr_date,'DD.MM.YYYY') as jr_date,
                        jr_id,jr_internal, jr_rapt,jrn_def_type,
                        j_qcode
                     from 
                          jrnx 
                        inner join jrn on j_grpt=jr_grpt_id 
                        inner join jrn_def on jrn_def.jrn_def_id=jrn.jr_def_id
                        left outer join tmp_pcmn on  j_poste=pcm_val
                      where 
                         jr_id=$p_jr_id 
                      order by j_debit desc");
  $MaxLine=pg_NumRows($Res);
  echo_debug('jrn.php',__LINE__,"Found $MaxLine lines");
  if ( $MaxLine == 0 ) return null;

  for ( $i=0; $i < $MaxLine; $i++) {
    $line=pg_fetch_array($Res,$i);
    $array['j_debit']=$line['j_debit'];
    // is there a name from this j_qcode
    //
    if ( strlen( $line['j_qcode']) != 0 )
      {
	$fiche=new fiche($p_cn);
	$fiche->GetByQCode($line['j_qcode']);

	$array['vw_name']=$fiche->getName();
      }
    else
      {
	$array['vw_name']=$line['pcm_lib'];
      }
      
    $array['jr_comment']=$line['jr_comment'];
    $array['j_montant']=$line['j_montant'];
    $array['jr_id']=$line['jr_id'];
    $array['jr_date']=$line['jr_date'];
    $array['jr_internal']=$line['jr_internal'];
    $array['j_poste']=$line['j_poste'];
    $array['jr_montant']=$line['jr_montant'];
    $array['jr_rapt']=$line['jr_rapt'];
    $array['jrn_def_type']=$line['jrn_def_type'];
    $array['jr_grpt_id']=$line['jr_grpt_id'];
    $array['jr_pj_name']=$line['jr_pj_name'];
    //    $array['']=$line[''];

    $ret_array[$i]=$array;
    }
  return $ret_array;
}
?>
