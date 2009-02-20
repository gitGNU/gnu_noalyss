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
 * \brief Tools for checking the security
 * \todo some of those tools are redundant with the class user and should be cleaned
 */

include_once("postgres.php");
/*! 
 * \brief  Check if an user is allowed to do an action, 
 *         this function is only used by the user_sec.php, 
 *         otherwise you have to use User->check_action 
 *         or $User->can_request
 * 
 * \param p_dossier dossier id
 * \param p_login   user's login
 * \param p_action_id 
 *	
 * \return
 *	- 0 no priv
 *      - 1 priv granted
 *
 */ 
function check_action ( $p_dossier,$p_login,$p_action_id)
{
  if ( CheckIsAdmin ($p_login) ) return 1;
  //  if( is_local_admin($p_login,$p_dossier)) return 1;


  $cn=DbConnect($p_dossier);

  /* check if user is a local admin */
  $a="select * from user_sec_act where ua_login='$p_login' and ua_act_id=$p_action_id";
  $Res=ExecSql($cn,$a);
  $Count=pg_NumRows($Res);
  if ( $Count == 0 ) return 0;
  if ( $Count == 1 ) return 1;
  echo "<H2 class=\"error\"> Invalid action !!! $Count select * from user_sec_act where ua_login='$p_login' and ua_act_id=$p_action_id </H2>";
}

/*! 
 * \brief  Check if an user is an administrator
 * 
 * 
 * \param $p_user  user login
 * 
 * \return
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
/*! 
 **************************************************
 * \brief  Check if an user has acces to a folder
 *        
 *  
 * \param p_user
 * \param p_dossier concerned folder
 * 
 * \return
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
