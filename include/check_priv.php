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

include_once("postgres.php");
/* function CheckJrn
 * Purpose :
 * Vérifie les acces d'un utilisateur
 * sur un journal
 * 
 * parm : 
 *	- $p_dossier le dossier
 *      - $p_user    le login user
 *      - $p_jrn     le journal
 * gen :
 *	- rien
 * return:
 *	- 0 pas d'acces
 *      - 1 Lecture
 *      - 2 écriture
 *
 */ 

function CheckJrn($p_dossier,$p_user,$p_jrn) 
{
  if ( CheckIsAdmin( $p_user) == 1 ) return 2;
  $l_Db=sprintf("dossier%d",$p_dossier);
  $cn=DbConnect($l_Db);
  // Special
  // p_jrn = 0 ==> grand livre access if there is no uj_prix=X
  if ( $p_jrn == 0 ) {
    //    $n_jrn=CountSql($cn,"select jrn_def_id from jrn_def");
    $n_for=CountSql($cn,"select jrn_def_id,uj_priv 
                 from jrn_def left join user_sec_jrn on uj_jrn_id=jrn_def_id
                    where uj_login='$p_user' and uj_priv='X'");
    if ( $n_for == 0 ) 
      return 2;
    else 
      return 0;
    

  }

  // droit spécifique
  $Res2=ExecSql($cn,"select jrn_def_id,uj_priv 
                 from jrn_def left join user_sec_jrn on uj_jrn_id=jrn_def_id
                    where uj_login='$p_user' and jrn_def_id=$p_jrn");

  $PrivJrn=pg_NumRows($Res2);
  $cn=DbConnect();
  // droit par défaut
  $Res=ExecSql($cn,"  select * 
                       from ac_users left join jnt_use_dos using (use_id) 
                       left join priv_user on (priv_jnt=jnt_id) 
                      where use_login='$p_user' and
                       dos_id=$p_dossier");

  $DefRight=pg_NumRows($Res);
  echo_debug ("PrivJrn = $PrivJrn DefRight $DefRight");
  // Si les droits par défaut == 0, alors user n'a pas accès au dossier
  if ( $DefRight == 0 ) return 0;
  $Def=pg_fetch_array($Res,0);

  // Si les droits par défaut == NO, alors user n'a pas accès au dossier
  if ( $Def['priv_priv'] == "NO" ) return 0;

  if ( $Def['priv_priv'] == "W") {
  // Si droit pas défaut == écriture      
    if ( $PrivJrn == 0 ) {
      // Pas de droit spécifique sur jrn => droit par défaut = W
      return 2;
    }
    $Priv=pg_fetch_array($Res2,0);
    
    if ( $Priv['uj_priv'] == "X" ) return 0;
    if ( $Priv['uj_priv'] == "R" ) return 1;
    if ( $Priv['uj_priv'] == "W" ) return 2;
    echo '<H2 class="error"> Undefined right</H2>';
    echo_debug ("Droit Journal $Priv[uj_priv]");
    return 0;
  }
  if ( $Def['priv_priv'] == "R") {
  // Si droit pas défaut == Lire
    if ( $PrivJrn == 0 ) {
      // Pas de droit spécifique sur jrn => droit par défaut = Lire
      return 1;
    }
    $Priv=pg_fetch_array($Res2,0);
    
    if ( $Priv['uj_priv'] == "X" ) return 0;
    if ( $Priv['uj_priv'] == "R" ) return 1;
    if ( $Priv['uj_priv'] == "W" ) return 2;
    echo_debug ("Droit Journal $Priv[uj_priv]");
    echo '<H2 class="error"> Undefined right</H2>';
    return 0;
  }
  echo '<H2 class="error"> Undefined default right</H2>';
  return 0;

}
/* function CheckAction
 * Purpose : Check if an user is allowed to do an action
 * 
 * parm : 
 *	- p_dossier dossier id
 *      - p_login   user's login
 *      - p_action_id 
 * gen :
 *	-
 * return:
 *	- 0 no priv
 *      - 1 priv granted
 *
 */ 
function CheckAction ( $p_dossier,$p_login,$p_action_id)
{
  if ( CheckIsAdmin ($p_login) ) return 1;
  $l_Db=sprintf("dossier%d",$p_dossier);
  $cn=DbConnect($l_Db);
  $Res=ExecSql($cn,"select * from user_sec_act where ua_login='$p_login' and ua_act_id=$p_action_id");
  $Count=pg_NumRows($Res);
  if ( $Count == 0 ) return 0;
  if ( $Count == 1 ) return 1;
  echo "<H2 class=\"error\"> Invalid action !!! $Count select * from user_sec_act where ua_login='$p_login' and ua_act_id=$p_action_id </H2>";
}
/* function CheckIsAdmin
 * Purpose : Check if an user is an administrator
 * 
 * parm : 
 *	- $p_user  user login
 * gen :
 *	-
 * return:
 *	- 0 if no
 *      - 1 if yes
 *
 */ 
function CheckIsAdmin($p_user) 
{
  if ( $p_user == 'phpcompta') return 1;
  $sql="select use_id from ac_users where use_login='$p_user'
		and use_active=1 and use_admin=1 ";
  $cn=DbConnect();
  
  $isAdmin=CountSql($cn,$sql);


  return $isAdmin;

}
/* function CheckDossier
 **************************************************
 * Purpose : Check if an user has acces to a folder
 *        
 * parm : 
 *	- user
 *      - p_dossier concerned folder
 * gen :
 *	- none
 * return: 
 *     1 if yes
 *     0 if no
 */
function CheckDossier($p_user,$p_dossier) 
{
  if ( CheckIsAdmin ($p_user) ) return 1;
  $cn=DbConnect();
  $sql="select  dos_id from ac_users 
                  natural join jnt_use_dos 
                  natural join  ac_dossier 
                  join  priv_user on ( priv_jnt=jnt_id)
          where use_active=1 
         and use_login='$p_user' 
         and dos_id='$p_dossier' 
         and priv_priv != 'NO'";
  return CountSql($cn,$sql);

}

?>
