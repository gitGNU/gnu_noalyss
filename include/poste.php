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

/* function GetPosteLibelle
 * Purpose : Return the label of a poste
 * 
 * parm : 
 *	- p_dossier
 *      - p_id tmp_pcmn (pcm_val)
 *      - is_cn conneciton
 * gen :
 *	- none
 * return:
 *	- string 
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
/* function GetNumberLine
 * Purpose : Max of ligne definie dans le journal
 * 
 * parm : 
 *	- p_dossier
 *      - p_jrn
 * gen :
 *	- none
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
    echo "<H2 class=\"warning\"> Journal non trouv� </H2>";
    //    return (3,3);
  }
  $l_line=pg_fetch_array($Res,0);
  $l_deb=$l_line['jrn_deb_max_line'];
  $l_cred=$l_line['jrn_cred_max_line'];
  //  return ($l_deb,$l_cred);

}
/* function PosteForm
 * Purpose : Cree un form pour prendre les postes
 * 
 * parm : 
 *	-  connection
 * gen :
 *	- noen
 * return:
 *	- morceau de code d'html qui contient un multiselect
 *        pour les postes
 *
 */ 
function PosteForm($p_cn) {
  $Res=ExecSql($p_cn,"select pcm_val,pcm_lib from tmp_pcmn 
         where pcm_val = any (select j_poste from jrnx) order by pcm_val::text");
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
/* function GetSolde
 * Purpose : Cree un form pour prendre les postes
 * 
 * parm : 
 *	-  connection
 * gen :
 *	- noen
 * return:
 *	- morceau de code d'html qui contient un multiselect
 *        pour les postes
 *
 */ 
function GetSolde($p_cn,$p_account) {
  $Res=ExecSql($p_cn,"select j_poste,sum(deb) as sum_deb, sum(cred) as sum_cred from 
          ( select j_poste, 
             case when j_debit='t' then j_montant else 0 end as deb, 
             case when j_debit='f' then j_montant else 0 end as cred 
          from jrnx join tmp_pcmn on j_poste=pcm_val 
              where  
            j_poste=$p_account
          ) as m group by j_poste ");
  $Max=pg_NumRows($Res);
  if ($Max==0) return 0;
  $r=pg_fetch_array($Res,0);

  return $r['sum_deb']-$r['sum_cred'];
}

?>
