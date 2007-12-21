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
 * \brief include file for the periode form, currency,...
 *
 */
/*!   FormPeriodeMult
 * \brief         Generate the form for the periode
 * 
 * 
 * \param $p_cn connexion
 *
 * \return string containing html code for the
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

/*!   
 * \brief          Generate the form for the periode
 *
 * 
 * \param $p_cn connexion 
 * \param $p_default default periode
 * \param $p_type the type of the periode OPEN CLOSE NOTCENTRALIZED or ALL
 * \param $p_suff the suffix of the name 
 *
 * \return string containing html code for the HTML
 *
 *       
 *
 */ 
function FormPeriode($p_cn,$l_default=0,$p_type=OPEN,$p_suff="")
{
  switch ($p_type) {
  case CLOSED:
    $sql_closed="where p_closed=true and p_central = false ";
    break;
  case OPEN:
    $sql_closed="where p_closed=false";
    break;
  case NOTCENTRALIZED:
    $sql_closed="where p_closed=true and p_central = false ";
    break;
  case ALL:
    $sql_closed="";
    break;
  default:
    error("invalide p_type in 'preference.php'#__LINE__");
  }
  $sql="select p_id,to_char(p_start,'DD.MM.YYYY') as p_start_string,
                    to_char(p_end,'DD.MM.YYYY') as p_end_string 
        from parm_periode  
         $sql_closed 
          order by p_start,p_end";
          
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
		  ,$l_line['p_start_string']
		  ,$l_line['p_end_string']);

  }
  $ret.="</SELECT>";
  return $ret;
}
/*!   get_periode
 * \brief Give the start & end date of a periode
 * 
 * \param  p_connection
 * \param  p_periode
 *
 * \return array containing the start date & the end date
 *     
 *
 */ 
function get_periode($p_cn,$p_periode) 
{
 $sql="select to_char(p_start,'DD.MM.YYYY') as p_start,
              to_char(p_end,'DD.MM.YYYY')   as p_end
       from parm_periode
         where p_id=".$p_periode;
 $Res=ExecSql($p_cn,$sql);
 if ( pg_NumRows($Res) == 0) return null;
 return pg_fetch_array($Res,0);

}
/*!   
 * \brief get the status of a periode
 * 
 * \param $p_cn database connex
 * \param periode id
 *	
 * \return 't' if closed otherwise 'f'
 *      
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
/*!   
 * \brief get the exercice of a periode
 * 
 * 
 * \param $p_cn connection
 * \param $p_periode periode
 *
 * \return Exercice of the periode
 *	
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
/*!
 * \brief Show all the currency encoded
 * 
 * \param  $p_cn database connextion
 *
 * \return nothing
 *	
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
    echo "<TD class=\"mtitle\"> <A class=\"mtitle\" HREF=\"parametre.php?p_mid=$l_line[pm_id]&p_action=change&p_code=$l_line[pm_code]&p_rate=$l_line[pm_rate]\">Change</A></TD>";
    echo "<TD class=\"mtitle\"> <A class=\"mtitle\" HREF=\"parametre.php?p_mid=$l_line[pm_id]&p_action=delete&p_code=$l_line[pm_code]\">Efface</A></TD>";
    echo '</TR>';
    
  }
  echo '<TR> <FORM ACTION="parametre.php" METHOD="POST">';
echo '<TD> <INPUT TYPE="text" NAME="p_devise"></TD>';
 echo '<TD> <INPUT TYPE="text" NAME="p_rate"></TD>';
 echo '<TD> <INPUT TYPE="SUBMIT" NAME="action" Value="Ajout"</TD>';
 echo '</FORM></TR>';
 echo '</TABLE>';
}
/*!   
 * \brief Show all the periode and their status
 * 
 * \param $p_cn database connection
 *
 * \return nothing
 *     
 *
 */ 
function ShowPeriode($p_cn)
{
  $str_dossier=dossier::get();
  //  echo "<h2 class=\"info\"> Période </H2>";
  $Res=ExecSql($p_cn,"select p_id,to_char(p_start,'DD.MM.YYYY') as date_start,to_char(p_end,'DD.MM.YYYY') as date_end,p_central,p_closed,p_exercice
  from parm_periode order by p_start,p_end");
  $Max=pg_NumRows($Res);
  echo '<TABLE ALIGN="CENTER">';
  echo "</TR>";
  echo '<TH> Date début </TH>';
  echo '<TH> Date fin </TH>';
  echo '<TH> Exercice </TH>';
  echo "</TR>";
  
  for ($i=0;$i<$Max;$i++) {
    $l_line=pg_fetch_array($Res,$i);
    echo '<TR>'; 
    echo '<TD ALIGN="CENTER"> '.$l_line['date_start'].'</TD>';
    echo '<TD  ALIGN="CENTER"> '.$l_line['date_end'].'</TD>';
    echo '<TD  ALIGN="CENTER"> '.$l_line['p_exercice'].'</TD>';
    echo_debug('preference.php',__LINE__," closed : $l_line[p_closed]");
    if ( $l_line['p_closed'] == 't' )     { 
      $closed=($l_line['p_central']=='t')?'<TD>Centralisée</TD>':'<TD>Fermée</TD>';
      $change='<TD></TD>';
      $remove='<TD></TD>';
    } else {
      $closed='<TD class="mtitle">'; 
      $closed.='<A class="mtitle" HREF="user_advanced.php?p_action=periode&action=closed&p_per='.$l_line['p_id'].'&'.$str_dossier.'"> Cloturer</A>';
    $change='<TD class="mtitle">';
    $change.='<A class="mtitle" HREF="user_advanced.php?p_action=periode&action=change_per&p_per='.
      $l_line['p_id']."&p_date_start=".$l_line['date_start'].
      "&p_date_end=".$l_line['date_end']."&p_exercice=".
      $l_line['p_exercice']."&$str_dossier\"> Changer</A>";
    $remove='<TD class="mtitle">';
    $remove.='<A class="mtitle" HREF="user_advanced.php?p_action=periode&action=delete_per&p_per='.
      $l_line['p_id']."&$str_dossier\"> Efface</A>";

    }
    echo "$closed";
    echo $change;

    echo $remove;

    echo '</TR>';
    
  }
  echo '<TR> <FORM ACTION="user_advanced.php?p_action=periode" METHOD="POST">';
  echo dossier::hidden();
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
