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

/* function ViewImp
 * Purpose : Create the form where the period
 *           is asked
 * 
 * parm : 
 *	- array (type,action,central,filter...)
 *      - connection
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 
function ViewImp($p_array,$p_cn) {
  include_once("preference.php");
  $periode=FormPeriodeMult($p_cn);
  foreach ( $p_array as $key=>$element) {
    echo_debug("VIEWIMP $key $element");
    ${"$key"}=$element;
  }
  if ( ! isset($type) ) return;
  $centr="";
  if ( $action=="viewhtml")  {
    echo '<FORM ACTION=impress.php METHOD="POST">';
    if ( $type=="jrn")
          $centr='<BR>Centralisé : 
             <INPUT TYPE="CHECKBOX" NAME="central" unchecked><BR>'; 
  }
  else {
    if ( $type=="jrn") {
      echo '<FORM ACTION=send_jrn_pdf.php METHOD="POST">';
          $centr='<BR>Centralisé : 
             <INPUT TYPE="CHECKBOX" NAME="central" unchecked><BR>'; 
    }
    if ( $type=="poste")
      echo '<FORM ACTION=send_poste_pdf.php METHOD="POST">';
  }
  echo $periode;
  echo '<INPUT TYPE="HIDDEN" NAME="type" value="'.$type.'">';
  echo '<INPUT TYPE="HIDDEN" NAME="action" value="'.$action.'">';

  echo $centr;

  if ( isset($filter))
    echo '<INPUT TYPE="HIDDEN" NAME="filter" value="'.$filter.'">';
  if ( isset ($p_id)) {
    echo '<INPUT TYPE="HIDDEN" NAME="p_id" value="'.$p_id.'">';
  } else {
    include_once("poste.php");
    echo "<BR>";
    echo PosteForm($p_cn);
    echo ' <BR>Tous les postes ';
    echo '<INPUT TYPE="checkbox" NAME="all_poste"><BR>';
  }
  echo '<INPUT TYPE="SUBMIT" name="print" Value="Executer">';

  echo '</FORM>';
  echo "</DIV>";
}
/* function Imp
 * Purpose : Show the html printing result 
 *           
 * 
 * parm : 
 *	-  array
 *      -  db connection
 * gen :
 *	- none
 * return:
 *	- ImpHtml
 *
 */ 
function Imp($p_array,$p_cn) {
  if ( ! isset($p_array['action'])) {
    echo_error ("IMP no action specified"); return;
  }
  if ( $p_array['action']=="viewhtml") {
    return ImpHtml($p_array,$p_cn);
  }
  echo_error ("IMP no action specified"); return;
}
/* function ImpHtml
 * Purpose : Show the html result
 * 
 * parm : 
 *	- array (type,periode,
 *      - connection
 * gen :
 *	- none
 * return: 
 *	- error if something goes wrong or
 *        the page result
 *
 */ 
function ImpHtml($p_array,$p_cn) 
{
  foreach($p_array as $key=>$element) {
    ${"$key"}=$element;
    echo_debug("ImpHtml $key => $element");
  }


  $colvide="<TD></TD>";
  // formulaire
  if ( $type == "form" ) {
    if ( !isset ($periode)) return NO_PERIOD_SELECTED;
    $cond=CreatePeriodeCond($periode);
    $Res=ExecSql($p_cn,"select fo_id , 
                     fo_fr_id, 
                     fo_pos, 
                     fo_label, 
                     fo_formula,
                     fr_label from form 
                      inner join formdef on fr_id=fo_fr_id
                     where fo_fr_id=$p_id
                     order by fo_pos");
    $Max=pg_NumRows($Res);
    if ($Max==0) return $ret="";
    for ($i=0;$i<$Max;$i++) {
      $l_line=pg_fetch_array($Res,$i);
      $col=ParseFormula($p_cn,
		   $l_line['fo_label'],
		   $l_line['fo_formula'],$cond);
      echo "<div>";
      foreach ($col as $key=> $element) {
	echo "$element ";
      }
      echo "</div>";
    } //for ($i

  }//form
  if ($type=="poste") { 
    if ( ! isset ( $all_poste) && ! isset ( $poste )) return NO_POST_SELECTED;
    if ( !isset ($periode)) return NO_PERIOD_SELECTED;
    include_once("poste.php");
    $cond=CreatePeriodeCond($periode);
    $ret="" ;
    if ( isset ( $all_poste) ){ //choisit de voir tous les postes
      $r_poste=ExecSql($p_cn,"select pcm_val from tmp_pcmn");
      $nPoste=pg_numRows($r_poste);
      for ( $i=0;$i<$nPoste;$i++) {
	$t_poste=pg_fetch_array($r_poste,$i);
	$poste[]=$t_poste['pcm_val'];
      } 
    }      
    for ( $i =0;$i<count($poste);$i++) {
      list ($array,$tot_deb,$tot_cred)=GetDataPoste($p_cn,$poste[$i],$cond);
      if ( count($array) == 0) continue;
      $ret.=sprintf("<H2 class=\"info\">%d %s</H2>",
		    $poste[$i],GetPosteLibelle($p_cn,$poste[$i],1));
      $ret.="<TABLE style=\"border-bottom-style:solid; border-width:2px\" >";
      $i=0;
      foreach ($array as $col=>$element) {
	$i++;
	if ( $i %2 == 0) 
	  $ret.="<tr class=\"even\">";
	else
	  $ret.="<TR class=\"odd\">";
	$ret.=sprintf("<TD>%s</TD>",$element['j_date']);
	$ret.=sprintf("<TD>%s</TD>",$element['jr_internal']);
	//	$ret.=sprintf("<TD>jrn:%s</TD>",$element['jrn_name']);
	$ret.=sprintf("<TD>%s</TD>",$element['description']);
	if ( $element['j_debit']=='t') {
	  $ret.=sprintf("<TD> debit</TD><TD ALIGN=\"right\">   % 8.2f</TD> $colvide",
			$element['deb_montant']);
	} else {
	  $ret.=sprintf("<TD>credit</TD> $colvide <TD ALIGN=\"right\">  % 8.2f</TD>",
			$element['cred_montant']);
	  
	}
	$ret.="</TR>";
      }//foreach
      
      $ret.=sprintf("$colvide $colvide $colvide $colvide ".
		    "<TD ALIGN=\"right\">% 8.2f</TD>".
		    "<TD ALIGN=\"right\">% 8.2f</TD>",
		    $tot_deb,
		    $tot_cred);
      $ret.="</TABLE>";
      $ret.="<p>Total débit :".$tot_deb."   Total Crédit:".$tot_cred."</p>";     
      if ( $tot_deb > $tot_cred ) {
      	$solde_t="D"; 
	$solde=$tot_deb-$tot_cred;
	}else {
      	$solde_t="C";
	$solde=$tot_cred-$tot_deb;
	}
      $ret.=" <p><B> Solde  $solde_t = ".$solde."</B></p>";
    }// for i
    return $ret;
  }//poste
  if ($type=="jrn") {
    if ( !isset ($periode)) return NO_PERIOD_SELECTED;

    echo_debug("imp html journaux");
    $ret="";
    if (isset($filter)) {
      $array=GetDataJrn($p_cn,$p_array,$filter);
    }
    $cass="";
    $c=0;

    foreach ($array as $a=>$e2) {
      //      echo_debug($ret);
      
      //cassure entre op
      if ( $cass!=$e2['grp'] ) {
	$cass=$e2['grp'];
	$ret.='<TR style="background-color:#89BEFF"><TD>'.$e2['j_date']."</TD>";
	$ret.="<TD>".$e2['jr_internal']."</TD><TD COLSPAN=4> ".$e2['comment']."</TD></TR>";	
      }
      $ret.="<TR>";
      $ret.=$colvide;
      
      if ($e2['debit']=='f') $ret.=$colvide;
      $ret.="<TD>".$e2['poste']."</TD>";
      if ($e2['debit']=='t') $ret.=$colvide;
      $ret.="<TD>".$e2['description']."</TD>";
      if ($e2['debit']=='f') $ret.=$colvide;
      $ret.="<TD>".$e2['montant']."</TD>";
      if ($e2['debit']=='t') $ret.=$colvide;
      $ret.="</TR>";
    }
    echo_debug($ret);

    return $ret ;
  }//jrn

}
/* function GetDataPoste
 * Purpose : Get dat for poste 
 * 
 * parm : 
 *      - connection
 *	- condition
 *      -. position
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 
function GetDataPoste($p_cn,$p_poste,$p_condition)
{
  $Res=ExecSql($p_cn,"select to_char(j_date,'DD.MM.YYYY') as j_date,".
	       "case when j_debit='t' then to_char(j_montant,'999999999.99') else ' ' end as deb_montant,".
	       "case when j_debit='f' then to_char(j_montant,'999999999.99') else ' ' end as cred_montant,".
	       " jr_comment as description,jrn_def_name as jrn_name,".
	       "j_debit, jr_internal ".
// 	       " case when j_debit='t' then 'debit' else 'credit' end as debit".
	       " from jrnx left join jrn_def on jrn_def_id=j_jrn_def ".
	       " left join jrn on jr_grpt_id=j_grpt".
	       " where j_poste=".$p_poste." and ".$p_condition.
	       " order by j_date::date");
  $array=array();
  $tot_cred=0;
  $tot_deb=0;
  $Max=pg_NumRows($Res);
  if ( $Max == 0 ) return null;
  for ($i=0;$i<$Max;$i++) {
    $array[]=pg_fetch_array($Res,$i);
    if ($array[$i]['j_debit']=='t') {
      $tot_deb+=$array[$i]['deb_montant'] ;
    } else {
      $tot_cred+=$array[$i]['cred_montant'] ;
    }
  }
  return array($array,$tot_deb,$tot_cred);
}
/* function GetDataJrn
 * Purpose : Get data from the jrn table
 * 
 * parm : 
 *	- connection
 *      - array periode
 *      - filter (default = YES)
 * gen :
 *	- none
 * return:
 *	- error code if something code wrong
 *       otherwise the result
 *
 */ 
function GetDataJrn($p_cn,$p_array,$filter=YES)
{
  if ( !isset ($p_array['periode']) ) return NO_PERIOD_SELECTED;

  if ( $filter==YES) {
    if ( ! isset ( $p_array['central'])){
      $cond=CreatePeriodeCond($p_array['periode']);
      $Res=ExecSql($p_cn,"select to_char(j_date,'DD.MM.YYYY') as j_date,
                j_montant as montant,j_debit as debit,j_poste as poste,".
	       "j_text as description,j_grpt as grp,jr_comment as comment,
                j_rapt as oc,jr_internal from jrnx left join jrn on ".
	       "jr_grpt_id=j_grpt where j_jrn_def=".$p_array['p_id'].
	       " and ".$cond." order by j_date::date,j_grpt,j_debit desc");
    } else {
      // create 
      $cond=CreatePeriodeCond($p_array['periode'],"c_periode");

      $Res=ExecSql($p_cn,"select to_char(c_date,'DD.MM.YYYY') as j_date,
                c_montant as montant,c_debit as debit,c_poste as poste,".
		   "c_description as description,c_grp as grp,c_comment as comment,
                c_rapt as oc,c_internal as jr_internal from centralized left join jrn on ".
		   "jr_grpt_id=c_grp where c_jrn_def=".$p_array['p_id']." and ".
		   $cond." order by c_id,c_date,c_grp,c_debit desc");
    }
    
  } // if filter == YES
  if ( $filter == NO) {
    if ( ! isset ($p_array['central']) ) {
      $cond=CreatePeriodeCond($p_array['periode']);
      $Res=ExecSql($p_cn,"select to_char(j_date,'DD.MM.YYYY') as j_date,
                j_montant as montant,j_debit as debit,j_poste as poste,".
		   "j_text as description,j_grpt as grp,jr_comment as comment,
                j_rapt as oc,jr_internal from jrnx left join jrn on ".
		   "jr_grpt_id=j_grpt where ".
		   $cond." order by j_date::date,j_grpt,j_debit desc");
    } else {
      $cond=CreatePeriodeCond($p_array['periode'],"c_periode");

      $Res=ExecSql($p_cn,"select to_char(c_date,'DD.MM.YYYY') as j_date,
                c_montant as montant,c_debit as debit,c_poste as poste,".
		   "c_description as description,c_grp as grp,c_comment as comment,
                c_rapt as oc,c_internal as jr_internal from centralized left join jrn on ".
		   "jr_grpt_id=c_grp where ".
		   $cond." order by c_id,c_date,c_grp,c_debit desc");
    
    } 
  }// filter == no
  $array=array();
  $Max=pg_NumRows($Res);
  for ($i=0;$i<$Max;$i++) {
    $array[]=pg_fetch_array($Res,$i);
  }
  return $array;
}
/* function CreatePeriodeCond
 * Purpose : Create the sql query for the periode
 * 
 * parm : 
 *	- p_periode
 *      - p_field (default = j_tech_per)
 * gen :
 *	- none
 * return:
 *	- a string containing the query
 *
 */ 
function CreatePeriodeCond($p_periode,$p_field=" j_tech_per") {
  if ( count($p_periode) == 1) {
    return $p_field."=".$p_periode[0];
  }

  $cond_periode=" $p_field in (";
  // condition periode
  $old=0;
  $follow=0;
  foreach ( $p_periode as $per) {
    if ( $old == 0) { 
      $old=$per;
      $follow=1;
      continue;
    }
    if ( $per == $old+1 ) { 
      $old=$per;
      $follow++;
    }
    
  }// foreach
  if ( count($p_periode) == $follow) {
    $cond=$p_field." >= ".$p_periode[0].' and '.$p_field.' <= '.$p_periode[count($p_periode)-1];
    return $cond;
  }

  // condition periode
  foreach ( $p_periode as $per) {
    $cond_periode.=$per.",";
  }
  $cond_periode=substr($cond_periode,0,strlen($cond_periode)-1);
  $cond_periode.=")";
  return $cond_periode;
}
/* function GetDataJrnPdf
 * Purpose : Get The data for the pdf printing
 * 
 * parm : 
 *	- connection
 *      - array
 *      - p_limit starting line
 *      - p_offset number of lines
 *
 * gen :
 *	- none
 * return:
 *	- Array with the asked data
 *
 */ 
function GetDataJrnPdf($p_cn,$p_array,$p_limit,$p_offset)
{
  echo_debug("GetDataJrnPdf");

  if ( !isset ($p_array['periode']) ) return NO_PERIOD_SELECTED;

  if ( $p_array['filter']==YES) {
    $cond=CreatePeriodeCond($p_array['periode']);
    if ( ! isset ($p_array['central']) ) {
      // Journaux non centralisés
    $Res=ExecSql($p_cn,"select j_id,to_char(j_date,'DD.MM.YYYY') as j_date,
                      jr_internal,
                case j_debit when 't' then j_montant::text else '   ' end as deb_montant,
                case j_debit when 'f' then j_montant::text else '   ' end as cred_montant,
                j_debit as debit,j_poste as poste,jr_montant , ".
	       "pcm_lib as description,j_grpt as grp,jr_comment ,
                jr_rapt as oc, j_tech_per as periode from jrnx left join jrn on ".
		 "jr_grpt_id=j_grpt ".
		 " left join tmp_pcmn on pcm_val=j_poste ".
                " where j_jrn_def=".$p_array['p_id'].
	       " and ".$cond." order by j_date::date asc,jr_internal,j_debit desc".
	       " limit ".$p_limit." offset ".$p_offset);
    }else {
      // Journaux centralisés
      $cond=CreatePeriodeCond($p_array['periode'],"c_periode");
      $Sql="select c_id as j_id,
            c_j_id,
            to_char (c_date,'DD.MM.YYYY') as j_date ,
            c_internal as jr_internal,
            case c_debit when 't' then c_montant::text else '   ' end as deb_montant,
            case c_debit when 'f' then c_montant::text else '   ' end as cred_montant,
            c_debit as j_debit,
            c_poste as poste,
            pcm_lib as description,
            jr_comment,
            jr_montant,
            c_grp as grp,
            c_comment as comment,
            c_rapt as oc,
            c_periode as periode 
            from centralized left join jrn on ".
		"jr_grpt_id=c_grp left join tmp_pcmn on pcm_val=c_poste where ".
          	" c_jrn_def=".$p_array['p_id']." and ".
                $cond." order by c_id ";
    $Res=ExecSql($p_cn,$Sql." limit ".$p_limit." offset ".$p_offset);

    }
  } else {
    // Grand Livre
    if (! isset($p_array['central'])) {
      // Non centralisé
      $cond=CreatePeriodeCond($p_array['periode']);
      $Res=ExecSql($p_cn,"select j_id,to_char(j_date,'DD.MM.YYYY') as j_date,
                      jr_internal,
                case j_debit when 't' then j_montant::text else '   ' end as deb_montant,
                case j_debit when 'f' then j_montant::text else '   ' end as cred_montant,
                j_debit as debit,j_poste as poste,".
	       "pcm_lib as description,j_grpt as grp,jr_comment as jr_comment,
                jr_montant,
                jr_rapt as oc, j_tech_per as periode from jrnx left join jrn on ".
		 "jr_grpt_id=j_grpt left join tmp_pcmn on pcm_val=j_poste where ".
	       "  ".$cond." order by j_date::date,j_grpt,j_debit desc".
	       " limit ".$p_limit." offset ".$p_offset);

    } else {
      // Centralisé
      $cond=CreatePeriodeCond($p_array['periode'],"c_periode");
      $Sql="select c_id as j_id,
            c_j_id,
            to_char (c_date,'DD.MM.YYYY') as j_date ,
            c_internal as jr_internal,
            case c_debit when 't' then c_montant::text else '   ' end as deb_montant,
            case c_debit when 'f' then c_montant::text else '   ' end as cred_montant,
            c_debit as j_debit,
            c_poste as poste,
            pcm_lib as description,
            jr_comment,
            jr_montant,
            c_grp as grp,
            c_comment as comment,
            c_rapt as oc,
            c_periode as periode 
            from centralized left join jrn on ".
		"jr_grpt_id=c_grp left join tmp_pcmn on pcm_val=c_poste where ".
                $cond." order by c_id ";
    $Res=ExecSql($p_cn,$Sql." limit ".$p_limit." offset ".$p_offset);
    } // Grand Livre
  }


  $array=array();
  $Max=pg_NumRows($Res);
  if ($Max==0) return null;
  $case="";
  $tot_deb=0;
  $tot_cred=0;
  for ($i=0;$i<$Max;$i++) {
    $line=pg_fetch_array($Res,$i);
    $mont_deb=($line['deb_montant']!=0)?sprintf("% 8.2f",$line['deb_montant']):"";
    $mont_cred=($line['cred_montant']!=0)?sprintf("% 8.2f",$line['cred_montant']):"";
    $jr_montant=($line['jr_montant']!=0)?sprintf("% 8.2f",$line['jr_montant']):"";
    $tot_deb+=$line['deb_montant'];
    $tot_cred+=$line['cred_montant'];
    echo_debug(" GetJrnDataPdf : mont_Deb ".$mont_deb);
    echo_debug(" GetJrnDataPdf : mont_cred ".$mont_cred);

    if ( $case != $line['grp'] ) {
      $case=$line['grp'];
      $array[]=array (
		      'j_id'=>$line['j_id'],
		      'j_date' => $line['j_date'],
		      'internal'=>$line['jr_internal'],
		      'deb_montant'=>'',
		      'cred_montant'=>'<b><i>'.$jr_montant.'</i></b>',
		      'description'=>'<b><i>'.$line['jr_comment'].'</i></b>',
		      'poste' => $line['oc'],
		      'periode' =>$line['periode'] );

      $array[]=array (
		      'j_id'=>$line['j_id'], 
		      'j_date' => '',
		      'internal'=>'',
		      'deb_montant'=>$mont_deb,
		      'cred_montant'=>$mont_cred,
		      'description'=>$line['description'],
		      'poste' => $line['poste'],
		      'periode' => $line['periode']
		      );
    
    }else {
      $array[]=array (
		      'j_id'=>$line['j_id'],
		      'j_date' => '',
		      'internal'=>'',
		      'deb_montant'=>$mont_deb,
		      'cred_montant'=>$mont_cred,
		      'description'=>$line['description'],
		      'poste' => $line['poste'],
		      'periode' => $line['periode']);

    }
      

  }
  echo_debug("Total debit $tot_deb,credit $tot_cred");
  $a=array($array,$tot_deb,$tot_cred);
 return $a;
}
/* function 
 * Purpose : 
 *        
 * parm : 
 *	-
 * gen :
 *	-
 * return:
 */


function GetDataGrpt($p_cn,$p_array)
{
  if ( !isset ($p_array['periode']) ) return NO_PERIOD_SELECTED;
  $cond=CreatePeriodeCond($p_array['periode']);
  $Res=ExecSql($p_cn,"select distinct ".
	       " j_grpt as grp".
               " from jrnx ".
	       " where j_jrn_def=".$p_array['p_id'].
	       " and ".$cond." order by j_grpt");
  $array=array();
  $Max=pg_NumRows($Res);
  $case="";
  for ($i=0;$i<$Max;$i++) {
    $array[]=pg_fetch_array($Res,$i);
  }
  return $array;
}

/* function GetRappel
 * Purpose : Get the amount on each page
 * 
 * parm : 
 *	- $p_cn
 *      - $p_jrnx_id jrnx.j_id
 *      - $p_jrn_id jrn.jr_id
 *      - $which LAST or FIRST
 *      - $p_type valeur JRN GL-CENTRAL GL-NOCENTRAL
 * gen :
 *	-
 * return:
 *	- array sum (deb) sum(cred)
 *
 */ 

function GetRappel($p_cn,$p_jrnx_id,$p_jrn_id,$p_exercice,$which,$p_type,$p_central) 
{
  include_once("preference.php");

  if ( $which == LAST) 
    $cmp="<="; 
  else
    $cmp="<";

  if ( $p_type == 1 ) {
    // Vue filtree => Journaux
    if ( $p_central == 0 ) { // Vue non centralisée
	  return array(0,0);
    } // p_central == 0

    //     Vue filtree => Journaux & Jrn centralisé 
    if ( $p_central == 1 ) {
      $c_line=CountSql($p_cn,"select * from centralized left join parm_periode on c_periode=p_id ".
		       " where c_jrn_def=$p_jrn_id and  p_exercice='".$p_exercice."'".
		       " and c_id $cmp $p_jrnx_id ");
      
      if ($c_line == 0 ) { return array (0,0); }
      $sql="select sum(c_montant) as tot_amount ".
	" from centralized ".
	" left join parm_periode on c_periode=p_id ".
	" where c_jrn_def=$p_jrn_id and ".
	" p_exercice='".$p_exercice."'".
	" and c_id $cmp $p_jrnx_id " ;
      $Res=ExecSql($p_cn,$sql." and c_debit='t' ");
      if ( pg_NumRows($Res) == 0 ) 
	$deb=0;
      else {
	$line=pg_fetch_array($Res,0);
	$deb=$line['tot_amount'];
      }
      
      $Res=ExecSql($p_cn,$sql." and c_debit='f' ");
      if ( pg_NumRows($Res) == 0 ) 
	$cred=0;
      else {
	
	$line=pg_fetch_array($Res,0);
	$cred=$line['tot_amount'];
      }
      echo_debug("MONTANT $deb,$cred");
      $a=array($deb,$cred);
      return $a;

    }
  } // Type = jrn
  if ($p_type==0 ) { // Si Grand Livre, prendre donnée centralisée{
    if ( $p_central == 1) {
      $c_line=CountSql($p_cn,"select * from centralized left join parm_periode on c_periode=p_id ".
		       "where p_exercice='".$p_exercice."'".
		       " and c_id $cmp $p_jrnx_id ");
      
      if ($c_line == 0 ) { return array (0,0); }
      $sql="select sum(c_montant) as tot_amount ".
	" from centralized ".
	" left join parm_periode on c_periode=p_id ".
	" where ".
	" p_exercice='".$p_exercice."'".
	" and c_id $cmp $p_jrnx_id " ;
      $Res=ExecSql($p_cn,$sql." and c_debit='t' ");
      if ( pg_NumRows($Res) == 0 ) 
	$deb=0;
      else {
	$line=pg_fetch_array($Res,0);
	$deb=$line['tot_amount'];
      }
      
      $Res=ExecSql($p_cn,$sql." and c_debit='f' ");
      if ( pg_NumRows($Res) == 0 ) 
	$cred=0;
      else {
	
	$line=pg_fetch_array($Res,0);
	$cred=$line['tot_amount'];
      }
      echo_debug("MONTANT $deb,$cred");
      $a=array($deb,$cred);
      return $a;
    } // central == 1
    else // Donnée non centralisée => pas de rappel
      {
	if ($p_central== 0) { // Si Grand Livre, prendre donnée non centralisée{
	  return array(0,0);
	}
      }//else
  } // if type==0
  
}
/* function ParseFormula
 * Purpose : Parse the formula contained in the fo_formula 
 *           field and return a array containing all the columns
 * 
 * parm : 
 *	- $p_cn connexion
 *      - $p_label
 *      - $p_formula
 * gen :
 *	- none
 * return:
 *	- array
 *
 */ 
function ParseFormula($p_cn,$p_label,$p_formula,$p_cond) 
{
  $aret=array();
  $l_debit=0;
  $l_credit=0;
    // somme debit
  $Res=ExecSql($p_cn,"select sum (j_montant) as montant from
                      jrnx where $p_cond and j_debit='t' and j_poste like '$p_formula'");
  if (pg_NumRows($Res)==0){
    $l_debit=0;                   
  } else {
    $l=pg_fetch_array($Res,0);
    $l_debit=$l['montant'];
      }
  // somme credit
  $Res=ExecSql($p_cn,"select sum (j_montant) as montant from
                      jrnx where $p_cond and j_debit='f' and j_poste like '$p_formula'");
  if (pg_NumRows($Res)==0) {
    $l_credit=0;                   
  } else {
    $l=pg_fetch_array($Res,0);
    $l_credit=$l['montant'];
  }

  if ( $l_credit==$l_debit) {
    $aret=array('desc' => $p_label,
		'montant' => '0');
  }
  if ( $l_credit < $l_debit) {
    $l2=sprintf("% .2f",$l_debit-$l_credit);
    $aret=array('desc' => $p_label,
		'montant' => $l2);
  }
  if ( $l_credit>$l_debit) {
    $l2=sprintf("(% .2f)",$l_credit-$l_debit);
    $aret=array('desc' => $p_label,
		'montant' => $l2);

  }
  return $aret;
}
?>
