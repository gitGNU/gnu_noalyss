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
/* $Revision$ */
include_once ("ac_common.php");
include_once("jrn.php");
include_once("preference.php");
include_once("poste.php");
include_once("error.php");
html_page_start($g_UserProperty['use_theme'],"onLoad=\"CheckTotal();\"");
if ( ! isset ( $g_dossier ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}
include_once ("postgres.php");
include_once ("check_priv.php");
/* Admin. Dossier */
CheckUser();
if ( isset( $p_jrn )) {
  session_register("g_jrn");
  $g_jrn=$p_jrn;
}
    $l_Db=sprintf("dossier%d",$g_dossier);
    $cn=DbConnect($l_Db);

echo '<SCRIPT LANGUAGE="javascript" SRC="compute.js"></SCRIPT>';
echo '<SCRIPT LANGUAGE="javascript" SRC="win_search_jrn.js"></SCRIPT>';
echo '<SCRIPT LANGUAGE="javascript" SRC="win_detail_jrn.js"></SCRIPT>';
/* Get MaxLine */
include_once("poste.php");
include_once ("top_menu_compta.php");
ShowMenuCompta($g_dossier,$g_UserProperty);
ShowMenuComptaRight($g_dossier,$g_UserProperty);
if ( $g_UserProperty['use_admin'] == 0 ) {
  $r=CheckAction($g_dossier,$g_user,ENCJRN);
  if ($r == 0 ){
    /* Cannot Access */
    NoAccess();
  }
  if ( isset ($g_jrn)) {
  	$right=CheckJrn($g_dossier,$g_user,$g_jrn);
	  if ($right == 0 ){
	    /* Cannot Access */
	    NoAccess();
	    exit -1;
	  }
    } // if isset g_jrn

}

ShowMenuJrnUser($g_dossier,$g_UserProperty);

if ( isset ( $_GET["action"] )) {
  if ( $_GET["action"] == "view" ) {
    ShowMenuRecherche($g_dossier,$g_jrn);

    echo '<DIV class="redcontent">';
    ViewJrn($g_dossier,$g_user,$g_jrn);
    echo '</DIV>';
  }
  if ( $_GET["action"] == "record" ) {
    if ( CheckJrn($g_dossier,$g_user,$g_jrn) < 2 ) {
      NoAccess();
      exit -1;

    }
    echo_debug("record");
    $max_deb=$_GET["max_deb"];
    $max_cred=$_GET["max_cred"];
    RecordJrn($g_dossier,$g_user,$g_jrn,$max_deb,$max_cred);
  }
  if ($_GET["action"]=="update" ) {
    if ( CheckJrn($g_dossier,$g_user,$g_jrn) < 2 ) {
      NoAccess();
      exit -1;
    
    }

    $p_id=$_GET["line"];
    list($l_array,$max_deb,$max_cred)=GetData($cn,$p_id);
    foreach ($l_array as $key => $element) {
      echo_debug("update $key $element");
    }

    UpdateJrn($g_dossier,$g_jrn,$max_deb,$max_cred,$l_array);
  }    

}
?>

<?
  if ( isset ($_POST['add_line_deb'])	) {
    echo '<DIV class="redcontent">';
    foreach ( $HTTP_POST_VARS as $key=>$element) {
      ${"p_$key"}=$element;
    }
    $p_MaxDeb+=2;
    echo "</DIV>";
    CorrectRecord($g_dossier,$g_user,$g_jrn,$p_MaxDeb,$p_MaxCred,$HTTP_POST_VARS);
    echo_debug("CorrectRecord($g_dossier,$g_user,$g_jrn,$p_MaxDeb,$p_MaxCred,$HTTP_POST_VARS);");
  }

  if ( isset ($_POST['add_line_cred'])	) {
    echo '<DIV class="redcontent">';
    foreach ( $HTTP_POST_VARS as $key=>$element) {
      ${"p_$key"}=$element;
    }
    $p_MaxCred+=2;
    echo "</DIV>";
    CorrectRecord($g_dossier,$g_user,$g_jrn,$p_MaxDeb,$p_MaxCred,$HTTP_POST_VARS);
    echo_debug("CorrectRecord($g_dossier,$g_user,$g_jrn,$p_MaxDeb,$p_MaxCred+10,$HTTP_POST_VARS);");
  }
 if ( isset ($_POST['viewsearch']) ) {
   reset($HTTP_POST_VARS);
   ShowMenuRecherche($g_dossier,$g_jrn,$HTTP_POST_VARS);
 
   echo '<DIV class="redcontent">';
   ViewJrn($g_dossier,$g_user,$g_jrn,$HTTP_POST_VARS);
   echo '</DIV>';
 
 }

if ( isset($_POST['add_record']) ) {
    echo '<DIV class="redcontent">';
    foreach ( $HTTP_POST_VARS as $name=>$element ) {
      echo_debug("element $name -> $element ");
      // Sauve les données dans des variables
      ${"p_$name"}=$element;
    }
    $userPref=GetUserPeriode($cn,$g_user);
    list ($l_date_start,$l_date_end)=GetPeriode($cn,$userPref);

    $p_op_date=$p_op_date.substr($l_date_start,2,8);
    echo_debug("p_op_date is $p_op_date");
    $aHttp=$HTTP_POST_VARS;
    $aHttp['op_date']=$p_op_date;
    // Verifie data
    //    $result=VerifData($cn,$HTTP_POST_VARS,$g_user);
    $result=VerifData($cn,$aHttp,$g_user);
    if ($result != NOERROR) {
      // Parse result
      AnalyzeError($result);

      echo "</DIV>";
      CorrectRecord($g_dossier,$g_user,$g_jrn,$p_MaxDeb,$p_MaxCred,$HTTP_POST_VARS);
      return;
    }

    $Res=StartSql($cn);
    $seq=GetNextId($cn,'j_grpt')+1;
    $s_op=GetNextId($cn,'j_id')+1;
	
	// For the user profile we need the correct sequence id
	AlterSequence($cn,'s_jrn_op',$s_op);
	
    $tot_cred=0;
    $tot_deb=0;

    //debit
    for ( $i = 0; $i < $p_MaxDeb; $i++) {
      $montant=${"p_mont_deb$i"};
      $l_class=${"p_class_deb$i"};
      if ( strlen(trim($montant)) != 0 && $montant != 0) {
	${"p_text_deb$i"}=FormatString(GetPosteLibelle($g_dossier,$l_class));
	//	$p_text=(FormatString(${"p_text_deb$i"})==null)?:FormatString(${"p_text_deb$i"});
	$Sql="insert into jrnx(j_id,j_date,j_montant,j_poste,j_grpt,
                j_jrn_def,j_debit,j_tech_user,j_tech_per) 
                values ( $s_op,to_date('$p_op_date','DD.MM.YYYY'), "
                .$montant.",
                $l_class,$seq,
                $g_jrn,true,'$g_user',$userPref)";
	echo_debug("sql $Sql");
	$s_op++;
	$tot_deb+=$montant;
	$Res=ExecSql($cn,$Sql);
  	if ( $Res == false ) { Rollback($cn); EndSql($cn); return;}
      }
    }
    for ( $i = 0; $i < $p_MaxCred; $i++) {
      $montant=${"p_mont_cred$i"};
      $l_class=${"p_class_cred$i"};
      if ( strlen(trim($montant)) != 0 && $montant != 0) {
	//$p_text=(FormatString(${"p_text_cred$i"})==null)?FormatString(GetPosteLibelle($g_dossier,$l_class)):FormatString(${"p_text_cred$i"});
	${"p_text_cred$i"}=FormatString(GetPosteLibelle($g_dossier,$l_class));
	$Sql="insert into jrnx(j_id,j_date,j_montant,j_poste,j_grpt,
                j_jrn_def,j_debit,j_tech_user,j_tech_per) 
                values ( $s_op,to_date('$p_op_date','DD.MM.YYYY'), $montant,
                $l_class,$seq,
                $g_jrn,false,'$g_user',$userPref)";
	echo_debug("sql $Sql");
	$s_op++;
	$tot_cred+=$montant;
	$Res=ExecSql($cn,$Sql);
	if ( $Res == false ) { Rollback($cn); EndSql($cn); break;}

      }
    }

    
    // si rappt
    if ( ! isset ($p_rapt) ) 
    	$p_rapt="";
    if ( trim($p_rapt) != '' ) {
      $jrn_id=GetNextJrnId($cn,'jr_id')+1;
	  
    if ( ! isset ($p_ech) ) 
    	$p_ech="";
      $l_date=isDate($p_ech);
      if ( $l_date == null) {
	$p_ech='null';
      } else {
	$p_ech="to_date('".$p_ech."','DD.MM.YYYY')";
      }
      $comment=FormatString($p_comment);
      $Sql=sprintf("insert into jrn(jr_id,jr_def_id,jr_comment,jr_date,jr_grpt_id,
                          jr_rapt,jr_montant,jr_tech_per,jr_ech) values(%s,%s,'%s',to_date('%s','DD.MM.YYYY'),%d,'%s',%f,%d,'%s')",
		   $jrn_id,
		   $g_jrn,
		   $comment,
		   $p_op_date,
		   $seq,
		   $p_rapt, $tot_deb,
		   $userPref,
		   $p_ech);
      echo_debug($Sql);
      $Res=ExecSql($cn,$Sql);
      //      $l_dest=GetRaptDest($cn,$p_rapt);
      if ($Res) 
	$internal=SetInternalCode($cn,$seq,$g_jrn);
	
	// For the user profile we need the correct sequence id
	AlterSequence($cn,'s_jrn',$jrn_id);

	// TODO multiple rapt should be added
      $sql=sprintf("update jrn set jr_rapt='%s' where jr_internal='%s'",$internal,$p_rapt);
      echo_debug("add $sql");
      $Res=ExecSql($cn,$sql);
      
    } else {
      $jrn_id=GetNextJrnId($cn,'jr_id')+1;
      if ( ! isset ($p_ech) ) $p_ech="";
      $l_date=isDate($p_ech);
      if ( $l_date == null) {
	$p_ech='null';
      } else {
	$p_ech="to_char('".$l_date."','DD.MM.YYYY')";
	    }
      $comment=FormatString($p_comment);
      
      $Sql=sprintf("insert into jrn(jr_id,jr_def_id,jr_comment,jr_date,jr_grpt_id,
                            jr_montant,jr_tech_per) values(%s,%s,'%s',%s,%d,%f,%d)",
		   $jrn_id,
		   $g_jrn,
		   $comment,
		   $p_ech,
		   $seq,$tot_deb,$userPref);
      echo_debug("Sql $Sql");
      $Res=ExecSql($cn,$Sql);
      if ($Res) 
	SetInternalCode($cn,$seq,$g_jrn);

    }
    
    if ( $Res) {
      Commit($cn); 
      EndSql($cn);
      
      //	AlterSequence($cn,"s_grpt",$seq+1);
      //AlterSequence($cn,"s_jrn_op",$s_op);
      // Add the p_text to the array

      ViewRecord($g_dossier,$g_jrn,$seq,$p_MaxDeb,$p_MaxCred,$aHttp);
      //      echo_debug("ViewRecord($g_dossier,$g_jrn,$seq,$p_MaxDeb,$p_MaxCred,$HTTP_POST_VARS);");
            echo_debug("ViewRecord($g_dossier,$g_jrn,$seq,$p_MaxDeb,$p_MaxCred,$aHttp);");
    } else
      {
	Rollback($cn); 
	EndSql($cn);
      }
} // _POST['add_record']

if ( isset($_POST['update_record']) ) {
  ShowMenuRecherche($g_dossier,$g_jrn);

  // Montre ce qu'on a encodé et demande vérif
  echo_debug ("######## UPDATE ##############");
  $l_Db=sprintf("dossier%d",$g_dossier);
  $cn=DbConnect($l_Db);
  echo '<DIV class="redcontent">';
  foreach ( $HTTP_POST_VARS as $name=>$element ) {
    echo_debug("element $name -> $element ");
    // Sauve les données dans des variables
    ${"p_$name"}=$element;
  }
  reset($HTTP_POST_VARS);
  $result=VerifData($cn,$HTTP_POST_VARS,$g_user);

        // Parse result
  AnalyzeError($result);
  foreach ( $HTTP_POST_VARS as $name=>$element ) {
    echo_debug("element $name -> $element ");
    // Sauve les données dans des variables
    ${"p_$name"}=$element;
  }
  
  if ($result != NOERROR) {
    echo "</DIV>";
    UpdateJrn($g_dossier,$g_jrn,$p_MaxDeb,$p_MaxCred,$HTTP_POST_VARS);
    return;
  }


  $Res=StartSql($cn);

  $userPref=GetUserPeriode($cn,$g_user);
  $tot_montant=0;  
  for ( $i = 0; $i < $p_MaxDeb; $i++) {
    $j_id=${"p_op_deb$i"};
    $montant=${"p_mont_deb$i"};
    // Compute the sum (for jrn table)
    $tot_montant+=$montant;
    $l_class=${"p_class_deb$i"};
    if ( strlen(trim($montant)) == 0) $montant=0;
    
    $Sql=sprintf("update jrnx set j_montant=%f, j_poste=%d,j_tech_user='%s',j_date=to_date('%s','DD.MM.YYYY'),j_tech_per=%d where j_id=%d",
		 $montant,$l_class,$g_user,$p_op_date,
		 $userPref,
		 $j_id);
    echo_debug("sql $Sql");
    $Res=ExecSql($cn,$Sql);
    if ( $Res == false ) { Rollback($cn); EndSql($cn); return;}

  }
  
  
  for ( $i = 0; $i < $p_MaxCred; $i++) {
    $j_id=${"p_op_cred$i"};
    $montant=${"p_mont_cred$i"};
    $l_class=${"p_class_cred$i"};
    if ( strlen(trim($montant)) == 0 ) $montant=0;

    $Sql=sprintf("update jrnx set j_montant=%f,j_poste=%d,j_tech_user='%s', j_date=to_date('%s','DD.MM.YYYY'),j_tech_per=%d where j_id=%d",
		 $montant,$l_class,$g_user,$p_op_date,
		 $userPref,$j_id);
    echo_debug("sql $Sql");

    $Res=ExecSql($cn,$Sql);
    if ( $Res == false ) { Rollback($cn); EndSql($cn); return;}

  }
  
  
  $l_src=-1;
  $l_dest=-1;
  if ( strlen(trim($p_rapt)) != 0 ) {
    $l_src=GetRapt($cn,$p_rapt);
    $l_dest=GetRaptDest($cn,$p_rapt);
    echo_debug("l_src = $l_src l_dest = $l_dest");
    // if the operation match already => set rappt to null
    if ( $l_src != -1 ) {
      $Sql=sprintf("update jrn set jr_rapt=null where jr_internal='%s'",$l_src);
      $Res=ExecSql($cn,$Sql);
    }
    }// strlen (trim (p_rapt
    if ( !isset ($p_ech) ) $p_ech="";
    $l_date=isDate($p_ech);
    if ( $l_date == null ) {
      $l_date="null";
    }else {
      $l_date="to_date('".$_POST["ech"]."','DD.MM.YYYY')";
      }
      if ( FormatString( $p_rapt ) != null ) {
	$comment=FormatString($_POST["comment"]);
	
	$Sql=sprintf("update  jrn set jr_comment='%s',jr_date=%s,jr_rapt='%s',".
		     "jr_montant=%f j_tech_per=%d where
                      jr_id=%d",
		     $comment,
		     $l_date,
		     $p_rapt,
		     $tot_montant,
		     $userPref,
		     $p_jr_id
		     );
	
	$Res=ExecSql($cn,$Sql);
	$current_internal=GetInternal($cn,$p_jr_id);
	$Sql=sprintf("update jrn set jr_rapt='%s' where jr_internal='%s'",
		     $current_internal,
		     $p_rapt);
	$Res=ExecSql($cn,$Sql);
	
	
      } else {
	$comment=FormatString($_POST["comment"]);
	$Sql=sprintf("update  jrn set jr_comment='%s',
                       jr_date=%s,jr_rapt=null,jr_montant=%f,jr_tech_per=%d where jr_id=%d",
		     $comment,
		     $l_date,$tot_montant,$userPref,
		     $p_jr_id);
	$Res=ExecSql($cn,$Sql);
	
	
}
  if ($Res) {
    Commit($cn); 
    EndSql($cn);
    
  } else
    {
      Rollback($cn); 
      EndSql($cn);
    }
    ViewJrn($g_dossier,$g_user,$g_jrn);

} // if update_record

$l_Db=sprintf("dossier%d",$g_dossier);
$cn=DbConnect($l_Db);
?>
</DIV>
<?
html_page_stop();
?>
