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

/* function FormPeriodeMult
 * Purpose :
 *         Generate the form for the periode
 * 
 * parm : 
 *	- $p_cn connexion
 * gen :
 *	- none
 * return:
 *	- string containing html code for the
 *        form
 *
 */ 
function FormPeriodeMult($p_cn)
{
  $sql="select p_id,to_char(p_start,'DD.MM.YYYY') as p_start,
                    to_char(p_end,'DD.MM.YYYY') as p_end 
        from parm_periode  
            order by p_exercice,p_start";
  $Res=ExecSql($p_cn,$sql);
  $Max=pg_NumRows($Res);
  $ret='<SELECT NAME="periode[]" SIZE="12" multiple>';
  for ( $i = 0; $i < $Max;$i++) {
    $l_line=pg_fetch_array($Res,$i);
    $ret.=sprintf('<OPTION VALUE="%s">%s - %s',$l_line['p_id']
		  ,$l_line['p_start']
		  ,$l_line['p_end']);

  }
  $ret.="</SELECT>";
  return $ret;
}

/* function FormPeriode
 * Purpose :
 *         Generate the form for the periode
 * 
 * parm : 
 *	- $p_cn connexion
 *      - $p_default default periode
 *      - $p_type the type of the periode
 *      - $p_suff the suffix of the name 
 * gen :
 *	- none
 * return:
 *	- string containing html code for the
 *        form
 *
 */ 
function FormPeriode($p_cn,$l_default=0,$p_type=OPEN,$p_suff="")
{

  if ($p_type==CLOSED) {
    $sql_closed="p_closed=true";
    $sql="select p_id,to_char(p_start,'DD.MM.YYYY') as p_start,
                    to_char(p_end,'DD.MM.YYYY') as p_end 
        from parm_periode where 
           $sql_closed order by p_exercice,p_start";

  }
  if ($p_type==OPEN) {
    $sql_closed="p_closed=false";
    $sql="select p_id,to_char(p_start,'DD.MM.YYYY') as p_start,
                    to_char(p_end,'DD.MM.YYYY') as p_end 
        from parm_periode where 
           $sql_closed order by p_exercice,p_start";

  }
  if ($p_type==NOTCENTRALIZED) {
  $sql="select p_id,to_char(p_start,'DD.MM.YYYY') as p_start,
                    to_char(p_end,'DD.MM.YYYY') as p_end 
        from parm_periode where 
           p_closed=true and p_id not in (
          select c_periode from centralized)
          order by p_exercice,p_start";
  }
  $Res=ExecSql($p_cn,$sql);
  $Max=pg_NumRows($Res);
  if ( $Max == 0 ) return null;
  $ret='<SELECT NAME="periode'.$p_suff.'">';
  for ( $i = 0; $i < $Max;$i++) {
    $l_line=pg_fetch_array($Res,$i);
    if ( $l_default == $l_line['p_id'] )
      $sel="SELECTED";
    else
      $sel="";

    $ret.=sprintf('<OPTION VALUE="%s" %s>%s - %s',$l_line['p_id']
		  ,$sel
		  ,$l_line['p_start']
		  ,$l_line['p_end']);

  }
  $ret.="</SELECT>";
  return $ret;
}
/* function SetUserPeriode
 * Purpose : Set the selected periode in the user's preferences
 * 
 * parm : 
 *	- $p_cn connexion 
 *      - $p_periode 
 *      - $p_user
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 
function SetUserPeriode($p_cn,$p_periode,$p_user) {
  $sql="update user_pref set pref_periode=$p_periode where pref_user='$p_user'";
  $Res=ExecSql($p_cn,$sql);
}
/* function GetUserPeriode
 * Purpose : Get the default periode from the user's preferences
 * 
 * parm : 
 *	- $p_cn connexion 
 *      - $p_user
 * gen :
 *	- none
 * return:
 *	- the default periode
 *
 */ 

function GetUserPeriode($p_cn,$p_user) {
  $array=GetUserPreferences($p_cn,$p_user);
  return $array['active_periode'];
}
/* function SetUserPreferences
 * Purpose : Get the default user's preferences
 * 
 * parm : 
 *	- $p_cn connexion 
 *      - $p_user
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 
function GetUserPreferences ($p_cn,$p_user)
{
  // si preference n'existe pas, les créer
  $sql="select pref_periode as active_periode from user_pref where pref_user='".$p_user."'";
  $Res=ExecSql($p_cn,$sql);
  if (pg_NumRows($Res) == 0 ) {
    $sql=sprintf("insert into user_pref (pref_periode,pref_user) values 
		 ( %d , '%s')" ,
		 1, $p_user);
    $Res=ExecSql($p_cn,$sql);

    $l_array=GetUserPreferences($p_cn,$p_user);
  } else {
    $l_array= pg_fetch_array($Res,0);
  }
  return $l_array;
}
/* function GetPeriode
 * Purpose :Give the start & end date of a periode
 * 
 * parm : 
 *	- connection
 *      - p_periode
 * gen :
 *	- none
 * return:
 *	- array containing the start date & the end date
 *
 */ 
function GetPeriode($p_cn,$p_periode) 
{
 $sql="select to_char(p_start,'DD.MM.YYYY') as p_start,
              to_char(p_end,'DD.MM.YYYY')   as p_end
       from parm_periode
         where p_id=".$p_periode;
 $Res=ExecSql($p_cn,$sql);
 if ( pg_NumRows($Res) == 0) return null;
 return pg_fetch_array($Res,0);

}
/* function  PeriodeClosed($p_cn,$p_periode) 
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	- true if closed
 *
 */ 
function PeriodeClosed($p_cn,$p_periode) 
{
 $sql="select p_closed
       from parm_periode
         where p_id=".$p_periode;
 $Res=ExecSql($p_cn,$sql);
 if ( pg_NumRows($Res) == 0) return null;
 $l_line=pg_fetch_array($Res,0);
 return $l_line['p_closed'];

}
/* function GetExercice
 * Purpose :
 * 
 * parm : 
 *	- $p_cn connection
 *      - $p_periode periode
 * gen :
 *	-
 * return:
 *	- Exercice of the periode
 *
 */ 
function GetExercice($p_cn,$p_periode)
{
  $Res=ExecSql($p_cn,"select p_exercice from parm_periode".
	       " where p_id=$p_periode");
  if ( pg_NumRows($Res) == 0 ) return "";
  $line=pg_fetch_array($Res,0);
  return $line['p_exercice'];
}
/* function ShowDevise
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
function ShowDevise($p_cn)
{
 echo "<h2 class=\"info\"> Devises </H2>";
  echo '<TABLE ALIGN="CENTER">';
  echo "<TR>";
  echo '<TH> CODE </TH>';
  echo '<TH> Valeur <BR>(par rapport à l\'euro) </TH>';
  echo "</TR>";

  $Res=ExecSql($p_cn,"select pm_id,pm_code,pm_rate  from parm_money order by pm_code");
  $Max=pg_NumRows($Res);
  
  for ($i=0;$i<$Max;$i++) {
    $l_line=pg_fetch_array($Res,$i);
    echo '<TR>'; 
    echo '<TD>'.$l_line['pm_code'].'</TD>';
    $l_rate=sprintf("% 10.6f",$l_line['pm_rate']);
    echo '<TD ALIGN="RIGHT">'.$l_rate.'</TD>';
    echo "<TD class=\"mtitle\"> <A class=\"mtitle\" HREF=\"dossier_prefs.php?p_mid=$l_line[pm_id]&p_action=change&p_code=$l_line[pm_code]&p_rate=$l_line[pm_rate]\">Change</A></TD>";
    echo "<TD class=\"mtitle\"> <A class=\"mtitle\" HREF=\"dossier_prefs.php?p_mid=$l_line[pm_id]&p_action=delete&p_code=$l_line[pm_code]\">Efface</A></TD>";
    echo '</TR>';
    
  }
  echo '<TR> <FORM ACTION="dossier_prefs.php" METHOD="POST">';
echo '<TD> <INPUT TYPE="text" NAME="p_devise"></TD>';
 echo '<TD> <INPUT TYPE="text" NAME="p_rate"></TD>';
 echo '<TD> <INPUT TYPE="SUBMIT" NAME="action" Value="Ajout"</TD>';
 echo '</FORM></TR>';
 echo '</TABLE>';
}
/* function ShowPeriode
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
function ShowPeriode($p_cn)
{
  echo "<h2 class=\"info\"> Période </H2>";
  $Res=ExecSql($p_cn,"select p_id,to_char(p_start,'DD.MM.YYYY') as date_start,to_char(p_end,'DD.MM.YYYY') as date_end,p_closed,p_exercice
  from parm_periode order by p_start");
  $Max=pg_NumRows($Res);
  echo '<TABLE ALIGN="CENTER">';
  echo "</TR>";
  echo '<TH> Date début </TH>';
  echo '<TH> Date début </TH>';
  echo '<TH> Exercice </TH>';
  echo "</TR>";
  
  for ($i=0;$i<$Max;$i++) {
    $l_line=pg_fetch_array($Res,$i);
    echo '<TR>'; 
    echo '<TD ALIGN="CENTER"> '.$l_line['date_start'].'</TD>';
    echo '<TD  ALIGN="CENTER"> '.$l_line['date_end'].'</TD>';
    echo '<TD  ALIGN="CENTER"> '.$l_line['p_exercice'].'</TD>';
    echo_debug(__FILE__,__LINE__," closed : $l_line[p_closed]");
    if ( $l_line['p_closed'] == 't' )     { 
      $closed='<TD></TD>';
      $change='<TD></TD>';
      $remove='<TD></TD>';
    } else {
      $closed='<TD class="mtitle">'; 
      $closed.='<A class="mtitle" HREF="dossier_prefs.php?p_action=closed&p_per='.$l_line['p_id'].'"> Cloturer</A>';
    $change='<TD class="mtitle">';
    $change.='<A class="mtitle" HREF=dossier_prefs.php?p_action=change_per&p_per='.
      $l_line['p_id']."&p_date_start=".$l_line['date_start'].
      "&p_date_end=".$l_line['date_end']."&p_exercice=".
      $l_line['p_exercice']."> Changer</A>";
    $remove='<TD class="mtitle">';
    $remove.='<A class="mtitle" HREF=dossier_prefs.php?p_action=delete_per&p_per='.
      $l_line['p_id']."> Efface</A>";

    }
    echo "$closed";
    echo $change;

    echo $remove;

    echo '</TR>';
    
  }
  echo '<TR> <FORM ACTION="dossier_prefs.php" METHOD="POST">';
  echo '<TD> <INPUT TYPE="text" NAME="p_date_start" SIZE="10"></TD>';
  echo '<TD> <INPUT TYPE="text" NAME="p_date_end" SIZE="10"></TD>';
  echo '<TD> <INPUT TYPE="text" NAME="p_exercice" SIZE="10"></TD>';
  echo '<TD> <INPUT TYPE="SUBMIT" NAME="add_per" Value="Ajout"</TD>';
  echo '<TD></TD>';
  echo '<TD></TD>';
  echo '</FORM></TR>';

  echo '</TABLE>';
}

?>
