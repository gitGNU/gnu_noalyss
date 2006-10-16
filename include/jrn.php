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
/* $Revision$ */
/*! \file
 * \brief work with the ledger
 */
require_once('class_fiche.php');
/*! 
 * \brief  Record an entry in the selected journal
 * 
 * \param  p_dossier dossier id 
 * \param p_user user id
 * \param p_jrn selected journal
 *
 * \return none
 *     
 *
 */ 
function RecordJrn($p_dossier,$p_user,$p_jrn,$p_MaxDeb,$p_MaxCred,$p_array = null,$p_update=0)
{
  include_once("postgres.php");
  include_once("preference.php");

  echo_debug('jrn.php',__LINE__,"RecordJrn($p_dossier,$p_user,$p_jrn,$p_MaxDeb,$p_MaxCred,$p_array,$p_update)");
  for ( $i = 0; $i < $p_MaxDeb; $i++) {
      ${"e_class_deb$i"}=0;
      ${"e_mont_deb$i"}=0;
  }
  for ( $i = 0; $i < $p_MaxCred; $i++) {
      ${"e_class_cred$i"}=0;
      ${"e_mont_cred$i"}=0;
  }
  $cn=DbConnect($p_dossier);
    // userPref contient la periode par default
    $userPref=GetUserPeriode($cn,$p_user);
    list ($l_date_start,$l_date_end)=GetPeriode($cn,$userPref);
    $e_op_rem=substr($l_date_start,2,8);

  if ( $p_array == null ) {
    $e_op_date="01";
    $e_comment="";
    $e_rapt="";
    $e_ech="";
    $e_sum_deb=0;
    $e_sum_cred=0;
  } else {
    foreach ( $p_array as $n=>$e) {
      ${"e_$n"}= $e;
  }
  }
  /* Get Jrn's properties */
  $l_line=GetJrnProperty($cn,$p_jrn);
  if ( $l_line == null ) return;
  echo '<DIV class="redcontent">';
  echo '<FORM NAME="encoding" ACTION="enc_jrn.php" METHOD="POST">';

  printf ('<H2 class="info"> %s %s </H2>',$l_line['jrn_def_name'],$l_line['jrn_def_code']);

  echo "<INPUT TYPE=HIDDEN NAME=\"MaxDeb\" VALUE=\"$p_MaxDeb\">";
  echo "<INPUT TYPE=HIDDEN NAME=\"MaxCred\" VALUE=\"$p_MaxCred\">";
  echo 'Date : <INPUT TYPE="TEXT" NAME="op_date" VALUE="'.
    $e_op_date.'" SIZE="4">'.
    $e_op_rem;

  // Comment
  echo '<br><SPAN>Comment  = <INPUT TYPE="TEXT" SIZE="70" NAME="comment" VALUE="'.$e_comment.'"></span>';

  // Chargement comptes disponibles
  if ( strlen(trim ($l_line['jrn_def_class_deb']) ) > 0 ) {
    $valid_deb=split(" ",$l_line['jrn_def_class_deb']);

    // Creation query
    $SqlDeb="select pcm_val,pcm_lib from tmp_pcmn where ";
    foreach ( $valid_deb as $item_deb) {
      if ( strlen (trim($item_deb))) {
	echo_debug('jrn.php',__LINE__,"l_line[jrn_def_class_deb] $l_line[jrn_def_class_deb] item_deb $item_deb");
	if ( strstr($item_deb,"*") == true ) {
	  $item_deb=strtr($item_deb,"*","%");
	  $Sql=" pcm_val like '$item_deb' or";
	} else {
	  $Sql=" pcm_val = '$item_deb' or";
	}
	$SqlDeb=$SqlDeb.$Sql;
      }
    }
    $SqlDeb = substr($SqlDeb,0,strlen($SqlDeb)-2)." order by pcm_val::text";
  } else
    {
      $SqlDeb="select pcm_val,pcm_lib from tmp_pcmn  order by pcm_val::text";
    }
  echo_debug('jrn.php',__LINE__,"SqlDeb $SqlDeb");
  $Res=ExecSql($cn,$SqlDeb);
  $Count=pg_NumRows($Res);

  for ( $i=0;$i<$Count;$i++) {
    $l2_line=pg_fetch_array($Res,$i);
    $lib=substr($l2_line['pcm_lib'],0,35);
    $poste [$l2_line['pcm_val']]= $lib;
  }

  echo "<TABLE>";
  echo '<TR><TD><H2 class="info"> débit </H2></TD></TR>';
  for ( $i=0;$i < $p_MaxDeb;$i++) {
    echo "<tr>";
    echo "<TD>";
    printf ('<SELECT NAME="class_deb%d">',$i);
    foreach ( $poste as $key => $value){ 
      $selected="";
      if ( ${"e_class_deb$i"} == $key ) $selected="SELECTED";
      $a=sprintf('<OPTION VALUE="%s" %s >%s - % .40s',
	     $key,
	     $selected,
	     $key,
	     $value);
      echo $a;
    }
    echo "</SELECT>";
    printf ('</TD>');

    printf('<TD> Montant :<INPUT TYPE="TEXT" id="mont_deb%d" NAME="mont_deb%d" VALUE="%s" onChange="CheckTotal()"></TD>',
	    $i,$i,${"e_mont_deb$i"},$i);
    echo "</tr>";

  }
  // Total debit
   echo '<TR><TD>';
   echo 'Total ';
   echo '</TD><TD>';
   echo '<input type="TEXT" NAME="sum_deb" VALUE="'.$e_sum_deb.'" onChange="CheckTotal()">';
   echo '</TD></TR>';
if ( $p_update == 0 )  echo "<TR><TD> <INPUT TYPE=\"SUBMIT\" VALUE=\"+ de line\" NAME=\"add_line_deb\"></TD></TR>";




  echo '<TR><TD><H2 class="info"> crédit </H2> </TD></TR>';
  // Chargement comptes disponibles
  if ( strlen(trim ($l_line['jrn_def_class_cred']) ) > 0 ) {
    $valid_cred=split(" ",$l_line['jrn_def_class_cred']);

    // Creation query
    $SqlCred="select pcm_val,pcm_lib from tmp_pcmn where ";
    foreach ( $valid_cred as $item_cred) {
      if ( strlen (trim($item_cred))) {
	echo_debug('jrn.php',__LINE__,"l_line[jrn_def_class_cred] $l_line[jrn_def_class_cred] item_cred $item_cred");
	if ( strstr($item_cred,"*") == true ) {
	  $item_cred=strtr($item_cred,"*","%");
	  $Sql=" pcm_val like '$item_cred' or";
	} else {
	  $Sql=" pcm_val = '$item_cred' or";
	}
	$SqlCred=$SqlCred.$Sql;
      }
    }
    $SqlCred = substr($SqlCred,0,strlen($SqlCred)-2)." order by pcm_val::text" ;
  } else
    {
      $SqlCred="select pcm_val,pcm_lib from tmp_pcmn  order by pcm_val::text";
    }
  echo_debug('jrn.php',__LINE__,"SqlCred $SqlCred");
  $Res=ExecSql($cn,$SqlCred);
  $Count=pg_NumRows($Res);


  for ( $i=0;$i<$Count;$i++) {
    $l2_line=pg_fetch_array($Res,$i);
    $lib=substr($l2_line['pcm_lib'],0,35);
    $poste_c[$l2_line['pcm_val']]=$lib;
  }
  for ( $i=0;$i < $p_MaxCred;$i++) {
    echo "<tr>";
    echo "<TD>";
    printf ('<SELECT NAME="class_cred%d">',$i);
    foreach ( $poste_c as $key => $value){ 
      $selected="";
      if ( ${"e_class_cred$i"} == $key ) $selected="SELECTED";
      $a=sprintf('<OPTION VALUE="%s" %s >%s - % .40s',
	     $key,
	     $selected,
	     $key,
	     $value);
      echo $a;
    }
	
    echo "</SELECT>";
    echo "</TD>";

    printf ('<TD> Montant :<INPUT TYPE="TEXT" id="mont_cred%d" NAME="mont_cred%d" VALUE="%s" onChange="CheckTotal()"></TD>',
	    $i,$i,${"e_mont_cred$i"});
    echo "</tr>";

  }
  // Total Credit
  echo '<TR><TD>';
  echo 'Total';
  echo '</TD><TD>';
  echo '<input type="TEXT" NAME="sum_cred" VALUE="'.$e_sum_cred.'" onChange="CheckTotal()">';
  echo '</TD></TR>';
  echo "<TR><TD> <INPUT TYPE=\"SUBMIT\" VALUE=\"+ de line\" NAME=\"add_line_cred\"></TD></TR>";
  if ( isset ($_GET["PHPSESSID"]) ) {
    $sessid=$_GET["PHPSESSID"];
  }
  else {
    $sessid=$_POST["PHPSESSID"];
  }

  $search='<INPUT TYPE="BUTTON" VALUE="Cherche" OnClick="SearchJrn(\''.$sessid."','rapt','$e_sum_cred')\">";
  echo_debug('jrn.php',__LINE__,"search $search");
  // To avoid problem with unknown variable
  if ( ! isset ($e_rapt) ) {
  	$e_rapt="";
  }

  echo '<TR><TD colspan="2">
         rapprochement : <INPUT TYPE="TEXT" name="rapt" value="'.$e_rapt.'">'.$search.'</TD></TR>';
  echo "</TABLE>";

  // To avoid problem with unknown variable
  if ( ! isset ($e_comment) ) {
  	$e_comment="";
  }
 


  if ( $p_update==0) {
    echo '<input type="submit" Name="add_record" Value="Enregistre">';
  } else {
    echo '<input type="submit" Name="update_record" Value="Enregistre">';
  }
   echo '<input type="reset" Value="Efface">';

   // To avoid problem with unknown variable
    if ( ! isset ($e_sum_deb) ) {
                 $e_sum_deb=0;
    }

   // To avoid problem with unknown variable
     if ( ! isset ($e_sum_cred) ) {
                 $e_sum_cred=0;
      }


   echo '<SPAN ID="diff"></SPAN>';
  echo "</FORM>";
  echo '</DIV>';
  
}
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
 * \brief  debug function to be dropped
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-
 *
 */ 

function ViewRec($p_array = null) {
  if ($p_array == null) { 
    echo_debug('jrn.php',__LINE__,"p_array is null");
  }else {
    foreach ( $p_array as $n=>$e) {
      echo_debug('jrn.php',__LINE__,"a[$n]= $e");
    }
 
  }
}
/*! 
 * \brief  Call the RecordJrn. In fact does nothing
 * 
 * parm : 
 *	- the same as RecordJrn
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 
function CorrectRecord($p_dossier,$p_user,$p_jrn,$p_MaxDeb,$p_MaxCred,$p_array)
{

   RecordJrn($p_dossier,$p_user,$p_jrn,$p_MaxDeb,$p_MaxCred,$p_array);
}
/*! 
 * \brief  View the added operation
 * 
 * parm : 
 *	- p_dossier
 *      - p_jrn
 *      - p_id
 *      - p_MaxDeb
 *      - p_MaxCred
 *      - p_array
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 
function ViewRecord ($p_dossier,$p_jrn,$p_id,$p_MaxDeb,$p_MaxCred,$p_array)
{
  echo_debug ("ViewRecord : $p_dossier");
  echo_debug('jrn.php',__LINE__,"function ViewRecord ($p_dossier,$p_jrn,$p_id,$p_MaxCred,$p_MaxDeb,$p_array)");
  foreach ( $p_array as $key=>$element) {
    ${"e_$key"}=$element;
    echo_debug('jrn.php',__LINE__," e_$key=$element;");

  }
  // Get Jrn's Prop
  $l_prop=GetJrnProp($p_dossier,$p_jrn);

  include_once("poste.php");
  if ( $l_prop == null ) return;
  $col_vide="<TD></TD>";
  echo '<TABLE ALIGN=CENTER BORDER=1 style="border-style:groove">';
  echo '<TR>';
  echo "<TD>". $l_prop['jrn_def_name']."(".$l_prop['jrn_def_code'].") </TD>";
  echo "<TD> Date : $e_op_date</TD>";
  echo "</TR>";
  echo "</TABLE>";


  echo "<table width=600 border=0>";
  echo "<TR><TD>operation $p_id</TD></TR>";
  for ($i = 0; $i < $p_MaxDeb;$i++) {
    //Deb
    if ( strlen(trim(${"e_mont_deb$i"})) > 0 && ${"e_mont_deb$i"} > 0 ) {
      $class=GetPosteLibelle($p_dossier,${"e_class_deb$i"});

      echo '<TR style="background-color:lightblue"><TD>'.${"e_class_deb$i"}."</TD>$col_vide<TD> $class </TD> <TD>".${"e_mont_deb$i"}."</TD>$col_vide</TR>";
    }
  }

  // Cred
  for ($i = 0; $i < $p_MaxCred;$i++) {
    if ( strlen(trim(${"e_mont_cred$i"})) > 0 && ${"e_mont_cred$i"} > 0 ) {
      $class=GetPosteLibelle($p_dossier,${"e_class_cred$i"});
      
      echo '<TR style="background-color:lightgreen;">'.$col_vide.'<TD>'.${"e_class_cred$i"}."</TD><TD> $class </TD>$col_vide <TD>".${"e_mont_cred$i"}."</TD></TR>";
    }
  }
  echo "<TR style=\"background-color:lightgray\">";
  echo $col_vide;
  echo $col_vide;
  echo "<TD align=\"center\"> Total</TD>";
  echo "<TD> $e_sum_deb</TD><TD> $e_sum_cred</TD>";
  echo "</TABLE>";
  // Bouton again
  echo '<table align="center">';
  echo '<TR><TD class="mtitle">';
  echo ' <A class="mtitle" HREF="enc_jrn.php?action=record&max_deb='.$l_prop['jrn_deb_max_line'].'&max_cred='.$l_prop['jrn_cred_max_line'].'&p_jrn='.$p_jrn.'"> Ajouter</A>';
  echo '</TD></TR></TABLE>';
}
/*! 
 * \brief  Get the properties of a journal
 * 
 * parm : 
 *	- p_cn database connection
 *      - p_jrn the jrn id
 * gen :
 *	- none
 * return:
 *	- an array containing properties
 *
 */ 
function GetJrnProperty($p_cn,$p_jrn) 
{
  $Res=ExecSql($p_cn,"select jrn_Def_id,jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_def_type, 
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
  $atype=GetJrnProperty($p_cn,$p_jrn);
  $type=$atype['jrn_def_code'];
  $internal_code=sprintf("%d%s-%s",$_SESSION['g_dossier'],$type,$num);
  $Res=ExecSql($p_cn,"update jrn set jr_internal='".$internal_code."' where ".
	       " jr_grpt_id = ".$p_grpt);
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
