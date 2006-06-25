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
/*! \file
 **************************************************
 * \brief  
 *   Data & function about connected users
 */
/*!
 * \brief  
 *   Data & function about connected users
 */

include_once("constant.php");

class cl_user {
  var $id;
  var $pass;
  var $db;
  var $admin;
  var $valid;

  function cl_user ($p_cn,$p_id=-1){
    // if p_id is not set then check the connected user
    if ( $p_id == -1 ) {
	  echo_debug('class_user.php',__LINE__," g_user = ".$_SESSION['g_user']);
    $this->id=$_SESSION['g_user'];
    $this->pass=$_SESSION['g_pass'];
    $this->valid=(isset ($_SESSION['isValid']))?1:0;
    $this->db=$p_cn;
    if ( isset($_SESSION['g_theme']) )
      $this->theme=$_SESSION['g_theme'];
    
    $this->admin=( isset($_SESSION['use_admin']) )?$_SESSION['use_admin']:0;
    
    if ( isset($_SESSION['use_name']) )
      $this->name=$_SESSION['use_name'];
    if ( isset($_SESSION['use_first_name']) )
      $this->first_name=$_SESSION['use_first_name'];
    } 
    else // if p_id is set get data of another user
      {
      $this->id=$p_id;
      $this->db=$p_cn;
      $Sql="select use_first_name,
             use_name,
             use_login,
             use_active,
             use_admin,
                     from ac_users
             where use_id=$p_id";
      $cn=DbConnect(); 
      $Res=pg_exec($cn,$Sql);
      if (($Max=pg_NumRows($Res)) == 0 ) return -1;
      $row=pg_fetch_array($Res,0);
      $this->first_name=$row['use_first_name'];
      $this->name=$row['use_name'];
      $this->active=$row['use_active'];
      $this->login=$row['use_login'];
      $this->admin=$row['use_admin'];
    } 
  }
  /*!
   * \brief Check if user is active and exists in therepository
   * Automatically redirect
   * 
   ++*/
  function Check()
  {
	
	$res=0;
	$pass5=md5($this->pass);
      	if  ( $this->valid == 1 ) { return; }
	$cn=DbConnect();
	if ( $cn != false ) {
	  $sql="select ac_users.use_login,ac_users.use_active, ac_users.use_pass,
                    use_admin,use_first_name,use_name
				from ac_users  
				 where ac_users.use_login='$this->id' 
					and ac_users.use_active=1
					and ac_users.use_pass='$pass5'";
	    echo_debug('class_user.php',__LINE__,"Sql = $sql");
	    $ret=pg_exec($cn,$sql);
	    $res=pg_NumRows($ret);
	    echo_debug('class_user.php',__LINE__,"Number of found rows : $res");
	    if ( $res >0 ) {
	      $r=pg_fetch_array($ret,0);
	      $_SESSION['use_admin']=$r['use_admin'];
	      $_SESSION['use_name']=$r['use_name'];
	      $_SESSION['use_first_name']=$r['use_first_name'];
	      
	      $this->admin=$_SESSION['use_admin'];
	      $this->name=$_SESSION['use_name'];
	      $this->first_name=$_SESSION['use_first_name'];
	      $this->GetGlobalPref();

	    }
	  }
	  
	if ( $res == 0  ) {
	  	echo '<META HTTP-EQUIV="REFRESH" content="4;url=index.html">';
		echo "<BR><BR><BR><BR><BR><BR>";
		echo "<P ALIGN=center><BLINK>
			<FONT size=+12 COLOR=RED>
			Invalid user <BR> or<BR> Invalid password 
			</FONT></BLINK></P></BODY></HTML>";
		session_unset();
		
		exit -1;			
	} else {
	  $this->valid=1;
	}
	
	return $ret;
	
  }

  function getJrn() {
  }
/*! 
 **************************************************
 * \brief  Check if an user is an admin
 *        
 * \return 1 for yes 0 for no
 */
  function Admin() {
    $res=0;
    
    if ( $this->id != 'phpcompta') {
      $pass5=md5($this->pass);
      $sql="select use_id from ac_users where use_login='$this->id'
		and use_active=1 and use_admin=1 and use_pass='$pass5'";
      
      $cn=DbConnect();
      
      $this->admin=CountSql($cn,$sql);
    } else $this->admin=1;
    
    return $this->admin;
  }
  function AccessJrn($p_cn,$p_jrn_id) {
    $this->Admin();
    if ( $this->admin==1) return true;
    $sql=CountSql($p_cn,"select uj_id 
                             from user_sec_jrn 
                             where
                             uj_priv in ('R','W')
                             and uj_jrn_id=".$p_jrn_id.
		  "  and uj_login = '".$this->id."'");
    if ( $sql != 0 ) return true;
    return false;
        
  }
/*! 
 * \brief  Set the selected periode in the user's preferences
 * 
 * \param $p_periode periode 
 * \param     - $p_user
 *
 */ 
function SetPeriode($p_periode) {
  $sql="update user_local_pref set parameter_value='$p_periode' where user_id='$this->id' and parameter_type='PERIODE'";
  $Res=ExecSql($this->db,$sql);
}
/*! 
 * \brief  Get the default periode from the user's preferences
 * 
 * \param $p_cn connexion 
 * \param  $p_user
 * \return the default periode
 *	
 *
 */ 

function GetPeriode() {
  $array=$this->GetPreferences();
  return $array['PERIODE'];
}
/*! 
 * \brief  Get the default user's preferences
 * 
 *  
 * \param $p_cn connexion 
 * \param $p_user
 * \return array of (parameter_type => parameter_value)
 */ 
function GetPreferences ()
{
  // si preference n'existe pas, les créer
  $sql="select parameter_type,parameter_value from user_local_pref where user_id='".$this->id."'";
  $Res=ExecSql($this->db,$sql);
  if (pg_NumRows($Res) == 0 ) {
    // default periode
    $sql=sprintf("insert into user_local_pref (user_id,parameter_value,parameter_type) 
                 select '%s',min(p_id),'PERIODE' from parm_periode where p_closed=false",
		 $this->id);
    $Res=ExecSql($this->db,$sql);

    $l_array=$this->GetPreferences();
  } else {
    for ( $i =0;$i < pg_NumRows($Res);$i++) {
      $row= pg_fetch_array($Res,0);
      $type=$row['parameter_type'];
      $l_array[$type]=$row['parameter_value'];
    }
  }
  return $l_array;
}
/*! 
 * \brief  Check if an user is allowed to do an action
 * 
 * \param p_dossier dossier id
 * \param p_login   user's login
 * \param p_action_id 
 * \return
 *	- 0 no priv
 *      - 1 priv granted
 *
 */ 
 function CheckAction ( $p_cn,$p_action_id)
{

  if ( $this->admin==1 ) return 1;

  $Res=ExecSql($p_cn,"select * from user_sec_act where ua_login='".$this->id."' and ua_act_id=$p_action_id");
  $Count=pg_NumRows($Res);
  if ( $Count == 0 ) return 0;
  if ( $Count == 1 ) return 1;
  echo "<H2 class=\"error\"> Invalid action !!! $Count select * from user_sec_act where ua_login='$p_login' and ua_act_id=$p_action_id </H2>";
}
/*! 
 **************************************************
 * \brief  Get the global preferences from user_global_pref
 *        in the account_repository db
 *
 * \param set g_variable
 */


function GetGlobalPref() 
{
	echo_debug('class_user.php',__LINE__,"function GetGlobalPref");
  $cn=Dbconnect();
  // Load everything in an array
  $Res=ExecSql ($cn,"select parameter_type,parameter_value from 
                  user_global_pref
                  where user_id='".$this->id."'");
  $Max=pg_NumRows($Res);
  if (  $Max == 0 ) {
	  $this->insert_default_global_pref();
	  $this->GetGlobalPref();
	  return;
	  }
  // Load value into array
  $line=array();
  for ($i=0;$i<$Max;$i++) {
    $row=pg_fetch_array($Res,$i);
    $type=$row['parameter_type']; 
    $line[$type]=$row['parameter_value'];;
  }
  // save array into g_ variable
  $array_pref=array ('g_theme'=>'THEME','g_pagesize'=>'PAGESIZE');
  foreach ($array_pref as $name=>$parameter ) {
	  if ( ! isset ($line[$parameter]) ) {
		  echo_debug("Missing pref : ".$parameter);
		  $this->insert_default_global_pref($parameter);
		$this->GetGlobalPref();
		return;
		}	
    $_SESSION[$name]=$line[$parameter];
  }
}

/*! 
 **************************************************
 * \brief  insert default pref
 *        if no parameter are given insert all the existing 
 *        parameter otherwise only the requested
 * \param parameter's type or nothing
 *
 */
function insert_default_global_pref($p_type="",$p_value="") {
	echo_debug('class_user.php',__LINE__,"function insert_default_global_pref");
	echo_debug('class_user.php',__LINE__,"parameter p_type $p_type p_value  $p_value");

	$default_parameter= array("THEME"=>"Light",
		"PAGESIZE"=>"50");
	$cn=Dbconnect();
	$Sql="insert into user_global_pref(user_id,parameter_type,parameter_value) 
				values ('%s','%s','%s')";
	if ( $p_type == "" ) {
		foreach ( $default_parameter as $name=>$value) {
			$Insert=sprintf($Sql,$this->id,$name,$value);
			ExecSql($cn,$Insert);
		}
	}
	else {
		$value=($p_value=="")?$default_parameter[$p_type]:$p_value;
		$Insert=sprintf($Sql,$this->id,$p_type,$value);
		ExecSql($cn,$Insert);
	}


}

/*! 
 **************************************************
 * \brief  update default pref
 *           if value is not given then use the default value
 *
 * \param parameter's type 
 * \param parameter's value value of the type
 */
function update_global_pref($p_type,$p_value="") {
	$default_parameter= array("THEME"=>"Light",
		"PAGESIZE"=>"50");
	$cn=Dbconnect();
	$Sql="update user_global_pref set parameter_value='%s' 
			where parameter_type='%s' and 
				user_id='%s'";
	$value=($p_value=="")?$default_parameter[$p_type]:$p_value;
	$Update=sprintf($Sql,$value,$p_type,$this->id);
	ExecSql($cn,$Update);

 }//end function
/*!\brief Return the year of current Periode
 *        it is the parm_periode.p_exercice col
 */
function getExercice()
{
  $sql="select p_exercice from parm_periode where p_id=".$this->GetPeriode();
  $Ret=ExecSql($this->db,$sql);
  $r=pg_fetch_array($Ret,0);
  return $r['p_exercice'];
}

}
?>
