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
function GetPosteLibelle($p_dossier,$p_id,$is_cn=0)
{
  include_once("postgres.php");
  if ( ! isset($is_cn) ) $is_cn=0;
  if ( $is_cn == 0) {
    $l_dossier=sprintf("dossier%d",$p_dossier);
    $cn=DbConnect($l_dossier);
  } else {
    $cn=$p_dossier;
  }
  $Res=ExecSql($cn,"select pcm_lib from tmp_pcmn where pcm_val=$p_id");
  if ( pg_NumRows($Res) == 0) { return "non existing poste";}
  $l_poste=pg_fetch_row($Res,0);
  return $l_poste[0];
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
function GetNumberLine($p_dossier,$p_jrn) 
{
  $l_dossier=sprintf("dossier%d",$p_jrn);
  $cn=DbConnect($l_dossier);
  $Res=ExecSql($cn,"select jrn_deb_max_line,jrn_cred_max_line from jrn_def where jrn_def_id=$p_jrn");
  if ( pg_NumRows($Res) == 0 ) {
    echo "<H2 class=\"warning\"> Journal non trouvé </H2>";
    //    return (3,3);
  }
  $l_line=pg_fetch_array($Res,0);
  $l_deb=$l_line['jrn_deb_max_line'];
  $l_cred=$l_line['jrn_cred_max_line'];
  //  return ($l_deb,$l_cred);

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
function PosteForm($p_cn) {
  $Res=ExecSql($p_cn,"select pcm_val,pcm_lib from tmp_pcmn order by pcm_val::text");
  $Max=pg_NumRows($Res);
  if ($Max==0) return null;
  $ret='<SELECT NAME="poste[]" SIZE="15" MULTIPLE>';
  for ( $i = 0;$i< $Max;$i++) {
    $line=pg_fetch_array($Res,$i);
    $ret.=sprintf('<OPTION VALUE="%s" > %s - %s',
		  $line['pcm_val'],
		  $line['pcm_val'],
		  $line['pcm_lib']);
  }//for
  $ret.="</SELECT>";
  return $ret;
}
?>
