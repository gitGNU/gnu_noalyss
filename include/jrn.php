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
/* $Revision$ */

/* function RecordJrn
 * Purpose : Record an entry in the selected journal
 * 
 * parm : 
 *	- p_dossier dossier id
 *      - p_user user id
 *      - p_jrn selected journal
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 
function RecordJrn($p_dossier,$p_user,$p_jrn,$p_MaxDeb,$p_MaxCred,$p_array = null,$p_update=0)
{
  include_once("postgres.php");
  include_once("preference.php");

  echo_debug("RecordJrn($p_dossier,$p_user,$p_jrn,$p_MaxDeb,$p_MaxCred,$p_array,$p_update)");
  for ( $i = 0; $i < $p_MaxDeb; $i++) {
      ${"e_class_deb$i"}=0;
      ${"e_mont_deb$i"}=0;
  }
  for ( $i = 0; $i < $p_MaxCred; $i++) {
      ${"e_class_cred$i"}=0;
      ${"e_mont_cred$i"}=0;
  }
  $l_dossier=sprintf("dossier%d",$p_dossier);
  $cn=DbConnect($l_dossier);
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
  $l_line=GetJrnProp($p_dossier,$p_jrn);
  if ( $l_line == null ) return;
  echo '<DIV class="redcontent">';


  printf ('<H2 class="info"> %s %s </H2>',$l_line['jrn_def_name'],$l_line['jrn_def_code']);
  echo '<FORM NAME="encoding" ACTION="enc_jrn.php" METHOD="POST">';
  echo "<INPUT TYPE=HIDDEN NAME=\"MaxDeb\" VALUE=\"$p_MaxDeb\">";
  echo "<INPUT TYPE=HIDDEN NAME=\"MaxCred\" VALUE=\"$p_MaxCred\">";
  echo 'Date : <INPUT TYPE="TEXT" NAME="op_date" VALUE="'.
    $e_op_date.'" SIZE="4">'.
    $e_op_rem;

  // Chargement comptes disponibles
  if ( strlen(trim ($l_line['jrn_def_class_deb']) ) > 0 ) {
    $valid_deb=split(" ",$l_line['jrn_def_class_deb']);

    // Creation query
    $SqlDeb="select pcm_val,pcm_lib from tmp_pcmn where ";
    foreach ( $valid_deb as $item_deb) {
      if ( strlen (trim($item_deb))) {
	echo_debug("l_line[jrn_def_class_deb] $l_line[jrn_def_class_deb] item_deb $item_deb");
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
  echo_debug("SqlDeb $SqlDeb");
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
	echo_debug("l_line[jrn_def_class_cred] $l_line[jrn_def_class_cred] item_cred $item_cred");
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
  echo_debug("SqlCred $SqlCred");
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

  $search='<INPUT TYPE="BUTTON" VALUE="Cherche" OnClick="SearchJrn(\''.$sessid."')\">";
  echo_debug("search $search");
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
 
  echo '<TEXTAREA" rows="5" cols="50" NAME="comment">';
  echo $e_comment;
  echo "</TEXTAREA>";


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
/* function UpdateJrn
 * Purpose : Display the form to UPDATE account operation
 *          
 * parm : 
 *	- p_dossier
 *      - p_jrn
 *      - p_MaxDeb number of debit line
 *      - p_MaxCred "     "  credit "
 *"     - p_array array containing the others datas
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 

function UpdateJrn($p_dossier,$p_jrn,$p_MaxDeb,$p_MaxCred,$p_array)
{
  include_once("postgres.php");
  echo_debug("function UpdateJrn($p_dossier,$p_jrn,$p_MaxDeb,$p_MaxCred,$p_array)");
  foreach ( $p_array as $n=>$e) {
      ${"e_$n"}= $e;
      echo_debug("e_$n= $e");
    }
  /* Get Jrn's properties */
  $l_line=GetJrnProp($p_dossier,$p_jrn);
  if ( $l_line == null ) return;

  $l_dossier=sprintf("dossier%d",$p_dossier);
  $cn=DbConnect($l_dossier);

  echo '<DIV CLASS="redcontent">';
  printf ('<H2 class="info"> %s (%s)</H2>',$l_line['jrn_def_name'],$l_line['jrn_def_code']);
  echo '<FORM NAME="encoding" ACTION="enc_jrn.php" METHOD="POST">';
  echo "<INPUT TYPE=HIDDEN NAME=\"MaxDeb\" VALUE=\"$p_MaxDeb\">";
  echo "<INPUT TYPE=HIDDEN NAME=\"jr_id\" VALUE=\"$e_jr_id\">";
  echo "<INPUT TYPE=HIDDEN NAME=\"MaxCred\" VALUE=\"$p_MaxCred\">";
  echo 'Date : <INPUT TYPE="TEXT" NAME="op_date" VALUE="'.$e_op_date.'">';


  // Chargement comptes disponibles
  if ( strlen(trim ($l_line['jrn_def_class_deb']) ) > 0 ) {
    $valid_deb=split(" ",$l_line['jrn_def_class_deb']);

    // Creation query
    $SqlDeb="select pcm_val,pcm_lib from tmp_pcmn where ";
    foreach ( $valid_deb as $item_deb) {
      if ( strlen (trim($item_deb))) {
	echo_debug("l_line[jrn_def_class_deb] $l_line[jrn_def_class_deb] item_deb $item_deb");
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
  echo_debug("SqlDeb $SqlDeb");
  $Res=ExecSql($cn,$SqlDeb);
  $Count=pg_NumRows($Res);

  for ( $i=0;$i<$Count;$i++) {
    $l2_line=pg_fetch_array($Res,$i);
    $lib=substr($l2_line['pcm_lib'],0,35);
    $poste [$l2_line['pcm_val']]= $lib;
  }

  echo "<TABLE>";
  //echo '<TR><TD> Date : <INPUT TYPE="TEXT" NAME="op_date" VALUE="'.$e_op_date.'">';
  echo '<TR><TD><H2 class="info"> débit </H2></TD></TR>';
  for ( $i=0;$i < $p_MaxDeb;$i++) {
    echo "<tr>";

    echo "<TD>";
    printf('<INPUT TYPE="HIDDEN" name="op_deb%d" VALUE="%s">',
	   $i,
	   ${"e_op_deb$i"});

    printf ('<SELECT NAME="class_deb%d">',$i);
    foreach ( $poste as $key => $value){ 
      $selected="";
      if ( ${"e_class_deb$i"} == $key ) $selected="SELECTED";
      $a=sprintf('<OPTION VALUE="%s" %s >%s - % .40s',
	     $key,
	     $selected,
	     $key,
	     $value);
      //      echo_debug(" option = $a");
      echo $a;
    }
	
    //    echo $poste_deb;
    echo "</SELECT>";
    echo "</TD>";
    /*    printf('<TD><INPUT TYPE="TEXT" NAME="text_deb%d" VALUE="%s"></TD>',
	   $i,
	   ${"e_text_deb$i"});
    */
    printf ('<TD> Montant :<INPUT TYPE="TEXT" id="mont_deb%d" NAME="mont_deb%d" VALUE="%s" onChange="CheckTotal()"></TD>',
	    $i,$i,${"e_mont_deb$i"},$i);
    echo "</tr>";

  }

  echo '<TR><TD><H2 class="info"> crédit </H2></TD></TR>';
  // Chargement comptes disponibles
  if ( strlen(trim ($l_line['jrn_def_class_cred']) ) > 0 ) {
    $valid_cred=split(" ",$l_line['jrn_def_class_cred']);

    // Creation query
    $SqlCred="select pcm_val,pcm_lib from tmp_pcmn where ";
    foreach ( $valid_cred as $item_cred) {
      if ( strlen (trim($item_cred))) {
	echo_debug("l_line[jrn_def_class_cred] $l_line[jrn_def_class_cred] item_cred $item_cred");
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
  echo_debug("SqlCred $SqlCred");
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
    printf('<INPUT TYPE="HIDDEN" name="op_cred%d" VALUE="%s">',
	   $i,
	   ${"e_op_cred$i"});

    printf ('<SELECT NAME="class_cred%d">',$i);
    foreach ( $poste_c as $key => $value){ 
      $selected="";
      if ( ${"e_class_cred$i"} == $key ) $selected="SELECTED";
      $a=sprintf('<OPTION VALUE="%s" %s >%s - % .40s',
	     $key,
	     $selected,
	     $key,
	     $value);
      //      echo_debug(" option = $a");
      echo $a;
    }
	
    echo "</SELECT>";
    echo "</TD>";

    printf ('<TD> Montant :<INPUT TYPE="TEXT" id="mont_cred%d" NAME="mont_cred%d" VALUE="%s" onChange="CheckTotal()"></TD>',
	    $i,$i,${"e_mont_cred$i"});
    echo "</tr>";

  }
  if ( isset ($_GET["PHPSESSID"]) ) {
    $sessid=$_GET["PHPSESSID"];
  }
  else {
    $sessid=$_POST["PHPSESSID"];
  }

  $search='<INPUT TYPE="BUTTON" VALUE="Cherche" OnClick="SearchJrn(\''.$sessid."')\">";

  echo '<TR><TD colspan="2"><H2 class="info">rapprochement </H2> 
       <INPUT TYPE="TEXT" name="rapt" value="'.$e_rapt.'">'.$search.'</TD></TR>';
  echo "</TABLE>";
  echo "<H2 class=\"info\">Commentaire </H2> <BR>";
  echo '<TEXTAREA" rows="5" cols="50" NAME="comment">';
  echo $e_comment;
  echo "</TEXTAREA>";

  $e_sum_deb=0;
  $e_sum_cred=0;

  echo '<input type="submit" Name="update_record" Value="Enregistre">';
  echo '<input type="reset" Value="Efface">';
  echo '<input type="TEXT" NAME="sum_deb" VALUE="'.$e_sum_deb.'" onChange="CheckTotal()">';
  echo '<input type="TEXT" NAME="sum_cred" VALUE="'.$e_sum_cred.'" onChange="CheckTotal()">';
  echo '<SPAN ID="diff"></SPAN>';
  echo "</FORM>";
  echo '</DIV>';
  
}
/* function ViewRec
 * Purpose : debug function to be dropped
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
    echo_debug("p_array is null");
  }else {
    foreach ( $p_array as $n=>$e) {
      echo_debug("a[$n]= $e");
    }
 
  }
}
/* function CorrectRecord
 * Purpose : Call the RecordJrn. In fact does nothing
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
/* function ViewRecord
 * Purpose : View the added operation
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
  echo_debug("function ViewRecord ($p_dossier,$p_jrn,$p_id,$p_MaxCred,$p_MaxDeb,$p_array)");
  foreach ( $p_array as $key=>$element) {
    ${"e_$key"}=$element;
    echo_debug(" e_$key=$element;");

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
/* function GetJrnProp
 * Purpose : Get the properties of a journal
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
function GetJrnProp($p_dossier,$p_jrn) 
{
  $l_dossier=sprintf("dossier%d",$p_dossier);
  $cn=DbConnect($l_dossier);
  $Res=ExecSql($cn,"select jrn_Def_id,jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_def_type, 
                   jrn_deb_max_line,jrn_cred_max_line,jrn_def_ech,jrn_def_ech_lib,jrn_def_code
                   from jrn_Def 
                      where jrn_def_id=$p_jrn");
  $Count=pg_NumRows($Res);
  if ( $Count == 0 ) {
    echo '<DIV="redcontent"><H2 class="error"> Paramètres journaux non trouvés</H2> </DIV>';
    return null;
  }
  return pg_fetch_array($Res,0);
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
function GetNextId($p_cn,$p_name) {
  include_once("postgres.php");

  $Res=ExecSql($p_cn,"select max($p_name) as result from jrnx");
  echo_debug("$Res=ExecSql($p_cn,"."select max($p_name) as result from jrnx");
  if (pg_NumRows($Res) == 0 )
    return 0;
  $l_res=pg_fetch_array($Res,0);
  return $l_res['result'];
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
function GetNextJrnId($p_cn,$p_name) {
  include_once("postgres.php");
  $Res=ExecSql($p_cn,"select max($p_name) as result from jrn ");
  echo_debug("$Res=ExecSql($p_cn,"."select max($p_name) as result from jrn ");

  if (pg_NumRows($Res) == 0 )
    return 0;
  $l_res=pg_fetch_array($Res,0);
  return $l_res['result'];
}
/* function ViewJrn
 * Purpose : Vue des écritures comptables
 * 
 * parm : 
 *	- p_dossier,
 *      - p_user,
 *      - p_jrn
 *      - array
 * gen :
 *	-
 * return:
 *	-
 *
 */ 
function ViewJrn($p_dossier,$p_user,$p_jrn,$p_array=null) {
  echo_debug("function ViewJrn($p_dossier,$p_user,$p_jrn,$p_array=null) ");
  $db=sprintf("dossier%d",$p_dossier);
  $l_prop=GetJrnProp($p_dossier,$p_jrn);
  echo $l_prop['jrn_def_name']."( ".$l_prop['jrn_def_code'].")";
  $cn=DbConnect($db);
  if ( $p_array == null) {
    include_once("preference.php");
    $l_periode=GetUserPeriode($cn,$p_user);
    $Res=ExecSql($cn,"select j_id,jr_internal,to_char(j_date,'DD.MM.YYYY') as j_date,
                       j_montant,j_poste,pcm_lib,j_grpt,j_rapt,j_debit,j_centralized,j_tech_per,
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
                 pcm_lib,j_grpt,j_rapt,j_debit,j_centralized,j_tech_per,jr_internal
                   from jrnx inner join tmp_pcmn on j_poste=pcm_val
                        inner join jrn on jr_grpt_id=j_grpt
                   where 
                   j_jrn_def=$p_jrn";
    $l_and="and ";
    if ( ereg("^[0-9]+$", $l_s_montant) || ereg ("^[0-9]+\.[0-9]+$", $l_s_montant) ) {
    $sql.=" and j_montant $l_mont_sel $l_s_montant";
    }
    if ( isDate($l_date_start) != null ) {
      $sql.=$l_and." j_date >='".$l_date_start."'";
    }
    if ( isDate($l_date_end) != null ) {
      $sql.=$l_and." j_date <='".$l_date_end."'";
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
      
      
      if ( $l_line['j_centralized'] == 'f' && isClosed($cn,$l_line['j_tech_per']) == false )
	{
	  echo "<TD>";
	  if ( isset ($_GET["PHPSESSID"])  ) {
	    $sessid=$_GET["PHPSESSID"];
	  } else {
	    $sessid=$_POST["PHPSESSID"];
	  }

	  list($z_type,$z_num,$num_op)=split("-",$l_line['jr_internal']);
	  printf ('<INPUT TYPE="BUTTON" VALUE="%s" onClick="viewDetail(\'%s\',\'%s\')">', 
		  $num_op,$l_line['j_grpt'],$sessid);
	  //	  echo $num_op;
	  echo "</TD>";
	  echo '<TD class="mtitle">';
	  echo "<A class=\"mtitle\" HREF=enc_jrn.php?action=update&line=".$l_line['j_grpt'].">";
	  echo "M";
	  echo "</A></TD>";
	}else {

	  if ( isset ($_GET["PHPSESSID"])  ) {
	    $sessid=$_GET["PHPSESSID"];
	  } else {
	    $sessid=$_POST["PHPSESSID"];
	  }
	  
	  echo '<TD>';
	  list($z_type,$z_num,$num_op)=split("-",$l_line['jr_internal']);
	  printf ('<INPUT TYPE="BUTTON" VALUE="%s" onClick="viewDetail(\'%s\',\'%s\')">', 
		  $num_op,$l_line['j_grpt'],$sessid);

	  echo "</TD>";
	  echo $col_vide;

	}
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
function isClosed($p_cn,$p_period) {
  return false;
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
function GetData ($p_cn,$p_grpt) {
  $Res=ExecSql($p_cn,"select 
                        to_char(j_date,'DD.MM.YYYY') as j_date,
                        j_text,
                        j_debit,
                        j_poste,
                        j_montant,
                        j_id,
                        jr_comment,
                        to_char(jr_date,'DD.MM.YYYY') as jr_date,
                        jr_rapt, jr_id,jr_internal
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
    $l_array['ech']=$l_line['jr_date'];
    $l_array['rapt']=$l_line['jr_rapt'];
    $l_array['jr_id']=$l_line['jr_id'];
   }
  return array($l_array,$deb,$cred);
}
/* function
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-  -1 si aucune valeur de trouvée
 *
 */ 
function GetRapt($p_cn,$p_rappt) {

  $Res=ExecSql($p_cn,"select jr_id from jrn where jr_rapt='$p_rappt'");
  if ( pg_NumRows($Res) == 0 ) return -1;
  $l_line=pg_fetch_array($Res);
  return $l_line['jr_id'];
}
/* function
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
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

/* function
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-  -1 si aucune valeur de trouvée
 *
 */ 
function GetRaptDest($p_cn,$p_rappt) {

  $Res=ExecSql($p_cn,"select jr_rapt from jrn where jr_internal='$p_rappt'");
  echo_debug("select jr_rapt from jrn where jr_id=$p_rappt");
  if ( pg_NumRows($Res) == 0 ) return -1;
  $l_line=pg_fetch_array($Res);
  if ( strlen (trim ($l_line['jr_rapt'])) == 0 ) return -1;
  return $l_line['jr_rapt'];
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
function GetAmount($p_cn,$p_id) {
  $Res=ExecSql($p_cn,"select jr_montant from jrn where jr_internal='$p_id'");
  if (pg_NumRows($Res)==0) return -1;
  $l_line=pg_fetch_array($Res,0);
  return $l_line['jr_montant'];
}
/* function VerifData
 * Purpose : Verify the data before inserting or
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
      echo_debug("element $name -> $element ");
      // Sauve les données dans des variables
      ${"p_$name"}=$element;
    }
    // Verif Date
    if ( isDate($p_op_date) == null) {
      return BADDATE;
    }
    // userPref contient la periode par default
    $userPref=GetUserPeriode($p_cn,$p_user);
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
    echo_debug("Amont = 	$tot_deb $tot_cred");
    if ( round($tot_deb,2) != round($tot_cred,2) ) { 
      return DIFF_AMOUNT;
    }

    $l_dest=-1;
    // Rapprochement demandé et vérif
    if ( strlen(trim($p_rapt)) != 0 ){ 
      $l_dest=GetRapt($p_cn,$p_rapt);
      if ( $l_dest != -1 && $l_dest != $p_jr_id ) {
	return RAPPT_ALREADY_USED;
      }
      // Get Amount 

      $l_mont=GetAmount($p_cn,$p_rapt);
      echo_debug ("amount = $tot_deb $l_mont");
      if ( $l_mont == -1 ) {
	return RAPPT_NOT_EXIST;
      }
      if ( $tot_deb != $l_mont) {
	return RAPPT_NOMATCH_AMOUNT;
      }
      
    }
    return NOERROR;

}
/* function GetJrnName
 * Purpose : Return the name of the jrn
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
/* function NextJrn
 * Purpose :
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
/* function 	SetInternalCode
 * Purpose :
 * 
 * parm : 
 *	- $p_cn connection
 *      - $p_id id in jr_id
 *      - $p_jrn jrn id jrn_def_id
 *      - $p_dossier dossier id
 * gen :
 *	-
 * return:
 *	-
 *
 */ 
function SetInternalCode($p_cn,$p_id,$p_jrn,$p_dossier)
{
  $num=CountSql($p_cn,"select * from jrn where jr_def_id=$p_jrn");
  $atype=GetJrnProp($p_dossier,$p_jrn);
  $type=$atype['jrn_def_code'];
  $internal_code=sprintf("%s-%08d",$type,$num);
  $Res=ExecSql($p_cn,"update jrn set jr_internal='".$internal_code."' where ".
	       " jr_id = ".$p_id);
  return $internal_code;
}


?>
