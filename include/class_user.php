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
  /* $Revision$ */
  // Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
  /*! \file
   * \brief  
   *   Data & function about connected users
   */
  /*!
   * \brief  
   *   Data & function about connected users
   */

include_once("constant.php");
require_once('class_dossier.php');

class User {
  var $id;
  var $pass;
  var $db;
  var $admin;
  var $valid;

  function User ($p_cn,$p_id=-1){
    // if p_id is not set then check the connected user
    if ( $p_id == -1 ) {
      if ( ! isset ($_SESSION['g_user'])) 
	exit('<h2 class="error"> Utilisateur déconnecté</h2>');

      $this->login=$_SESSION['g_user'];
      $this->pass=$_SESSION['g_pass'];
      $this->valid=(isset ($_SESSION['isValid']))?1:0;
      $this->db=$p_cn;
      $this->id=-1;
      if ( isset($_SESSION['g_theme']) )
	$this->theme=$_SESSION['g_theme'];
    
      $this->admin=( isset($_SESSION['use_admin']) )?$_SESSION['use_admin']:0;
    
      if ( isset($_SESSION['use_name']) )
	$this->name=$_SESSION['use_name'];
      if ( isset($_SESSION['use_first_name']) )
	$this->first_name=$_SESSION['use_first_name'];
      $this->load();
    } 
    else // if p_id is set get data of another user
      {
	$this->id=$p_id;
	$this->db=$p_cn;
	$this->load();
      } 
  }
  /*!\brief load data from database.
   * if this->id == -1, it is unknown so we have to retrieve it
   from the database by the login
   * return -1 if nothing is found
   */
  function load() {
    /* if this->id == -1, it is unknown so we have to retrieve it from
       the database thanks it login */
    if ( $this->id < 0 ) {
      $sql_cond="   where use_login=$1";
      $sql_array=array($this->login);
    } else {
      $sql_cond="   where use_id=$1";
      $sql_array=array($this->id);
    }
    $sql="select use_id,
              use_first_name,
             use_name,
             use_login,
             use_active,
             use_admin
                     from ac_users ";
    $cn=DbConnect(); 
    $Res=ExecSqlParam($cn,$sql.$sql_cond,$sql_array);
    if (($Max=pg_NumRows($Res)) == 0 ) return -1;
    $row=pg_fetch_array($Res,0);
    $this->id=$row['use_id'];
    $this->first_name=$row['use_first_name'];
    $this->name=$row['use_name'];
    $this->active=$row['use_active'];
    $this->login=$row['use_login'];
    $this->admin=$row['use_admin'];

  }
  /*!
   * \brief Check if user is active and exists in therepository
   * Automatically redirect, it doesn't check if a user can access a folder
   * 
   ++*/
  function Check()
  {
	
    $res=0;
    $pass5=md5($this->pass);

    $cn=DbConnect();
    if ( $cn != false ) {
      $sql="select ac_users.use_login,ac_users.use_active, ac_users.use_pass,
                    use_admin,use_first_name,use_name
				from ac_users  
				 where ac_users.use_id='$this->id' 
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
	$_SESSION['isValid']=1;
	      
	$this->admin=$_SESSION['use_admin'];
	$this->name=$_SESSION['use_name'];
	$this->first_name=$_SESSION['use_first_name'];
	$this->load_global_pref();


      }
    }
	  
    if ( $res == 0  ) {
      echo '<META HTTP-EQUIV="REFRESH" content="4;url=index.html">';
      echo "<BR><BR><BR><BR><BR><BR>";
      echo "<P ALIGN=center><BLINK>
			<FONT size=+12 COLOR=RED>
			utilisateur ou mot de passe incorrect 
			</FONT></BLINK></P></BODY></HTML>";
      session_unset();
		
      exit -1;			
    } else {
      $this->valid=1;
    }
	
    return $ret;
	
  }
  /*!\brief return  the access to a folder, 
   * \param $p_dossier id if it is == 0 then we take the value from $_SESSION
   *\return the priv_priv 
   *          - X no access
   *          - R has access (normal user)
   *          - L Local Admin
   *
   */
  function get_folder_access($p_dossier = 0) {
    if ($p_dossier==0)
      $p_dossier=dossier::id();

    $cn=DbConnect();
    $sql="select priv_priv from priv_user join jnt_use_dos on (jnt_id=priv_jnt) join ac_users using (use_id)
where use_id=$1 and dos_id=$2";
    $res=getDbValue($cn,$sql,array($this->id,$p_dossier));
    return $res;
  }
  /*!\brief check that a user is valid and the access to the folder
   * \param $p_ledger the ledger to check
   *\return the priv_priv 
   * - O only predefined operation
   * - W write
   * - R read only
   * - X no access
   *

   *
   */
  function get_ledger_access($p_ledger) {
    if ( $this->admin == 1 ||
	 $this->is_local_admin(dossier::id()) ==1 )
      return 'W';

    $sql="select uj_priv from user_sec_jrn where uj_login=$1 and uj_jrn_id=$2";
    $res=getDbValue($this->db,$sql,array($this->login,$p_ledger));
    if ( $res=='' ) $res='X';
    return $res;
  }

  /*! 
   * \brief get all the available ledgers for the current user
   * \param $p_type = ALL or the type of the ledger (ACH,VEN,FIN,ODS)
   * \param $p_access =3 for Read or WRITE, 2  write, 1 for readonly
   *
   * \return an array
   */
  function get_ledger($p_type='ALL',$p_access=3) {


    if ( $this->admin != 1 && $this->is_local_admin() != 1) {
      $sql_type=($p_type=='ALL')?'':"and jrn_def_type=upper('".FormatString($p_type)."')";
      switch($p_access) {
      case 3:
	$sql_access=" and uj_priv!= 'X'";
	break;
      case 2:
	$sql_access=" and uj_priv = 'W'";
	break;

      case 1:
	$sql_access=" and uj_priv = 'R'";
	break;

      }
      $sql="select jrn_def_id,jrn_def_type,
jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_type_id,jrn_desc,uj_priv,
                               jrn_deb_max_line,jrn_cred_max_line
                             from jrn_def join jrn_type on jrn_def_type=jrn_type_id
                             join user_sec_jrn on uj_jrn_id=jrn_def_id 
                             where
                             uj_login='".$this->login."'".
	$sql_type.$sql_access.
	" order by jrn_Def_id";
    }else {
      $sql_type=($p_type=='ALL')?'':"where jrn_def_type=upper('".FormatString($p_type)."')";
      $sql="select jrn_def_id,jrn_def_type,jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_deb_max_line,jrn_cred_max_line,
                            jrn_type_id,jrn_desc,'W' as uj_priv
                             from jrn_def join jrn_type on jrn_def_type=jrn_type_id 
                            $sql_type
                             order by jrn_Def_id";
    }

    $res=ExecSql($this->db,$sql);
    if ( pg_NumRows($res) == 0 ) return null;
    $array=pg_fetch_all($res);
    return $array;
  }

  /*!\brief return an sql condition for filtering the permitted ledger
   * \param $p_type = ALL or the type of the ledger (ACH,VEN,FIN,ODS)
   * \param $p_access =3 for READ or WRITE, 2 READ and write, 1 for readonly
   *
   *\return string
   */
  function get_ledger_sql($p_type='ALL',$p_access=3) {
    /* for admin or local admin there is no restriction on the  ledger */
    if ($this->admin == 1 || $this->is_local_admin(dossier::id()) == 1) return ' jrn_def_id > 0 ';
    $aLedger=$this->get_ledger($p_type,$p_access);
    if ( empty ($aLedger)) return ' jrn_def_id < 0 ';
    $sql=" jrn_def_id in (";
    foreach ($aLedger as $row) {
      $sql.=$row['jrn_def_id'].',';
    }
    $sql.='-1)';
    return $sql;
  }
  /*! 
   * \brief  Check if an user is an admin
   *        
   * \return 1 for yes 0 for no
   */
  function Admin() {
    if ( $this->login != 'phpcompta') {
      $pass5=md5($this->pass);
      $sql="select use_admin from ac_users where use_login=$1
		and use_active=1  ";
      
      $cn=DbConnect();
      $res=ExecSqlParam($cn,$sql,array($this->login));
      if ( pg_NumRows($res)==0) exit(__FILE__." ".__LINE__." aucun resultat");
      $this->admin=pg_fetch_result($res,0);
    } 
    else $this->admin=1;
    
    return $this->admin;
  }
  /*! 
   * \brief  Set the selected periode in the user's preferences
   * 
   * \param $p_periode periode 
   * \param     - $p_user
   *
   */ 
  function set_periode($p_periode) {
    $sql="update user_local_pref set parameter_value='$p_periode' where user_id='$this->id' and parameter_type='PERIODE'";
    $Res=ExecSql($this->db,$sql);
  }

  private function set_default_periode() {

    /* get the first periode */
    $sql='select min(p_id) as pid from parm_periode where p_closed = false and p_start = (select min(p_start) from parm_periode)';
    $Res=ExecSql($this->db,$sql);

    $pid=pg_fetch_result($Res,0,0);
    /* if all the periode are closed, then we use the last closed period */
    if ( $pid == null ) {
      $sql='select min(p_id) as pid from parm_periode where p_start = (select max(p_start) from parm_periode)';
      $Res2=ExecSql($this->db,$sql);
      $pid=pg_fetch_result($Res2,0,0);
      if ( $pid == null )  {
	echo "Aucune periode trouvable !!!";
	exit(1);
      }

      $pid=pg_fetch_result($Res2,0,0);
    }

    $sql=sprintf("insert into user_local_pref (user_id,parameter_value,parameter_type) 
                 values ('%s','%d','PERIODE')",
		 $this->id,$pid);
    $Res=ExecSql($this->db,$sql);
  }

  /*! 
   * \brief  Get the default periode from the user's preferences
   * 
   * \return the default periode
   *	
   *
   */ 

  function get_periode() {

    $array=$this->get_preference();
    if ( ! isset ($array['PERIODE'])) { 
      $this->set_default_periode();
      $array=$this->get_preference();
    }
    return $array['PERIODE'];
  }
  /*!\brief return the mini rapport to display on the welcome page
   *\return 0 if nothing if found or the report to display (formdef.fr_id)
   */
  function get_mini_report() {
    $array=$this->get_preference();
    $fr_id=(isset($array['MINIREPORT']))?$array['MINIREPORT']:0;
    return $fr_id;
    
  }

  /*!\brief set the mini rapport to display on the welcome page
   */
  function set_mini_report($p_id) {
    $count=getDbValue($this->db,"select count(*) from user_local_pref where user_id=$1 and parameter_type=$2",
		      array($this->id,'MINIREPORT'));
    if ( $count == 1 ) {
      $sql="update user_local_pref set parameter_value=$1 where user_id=$2 and parameter_type='MINIREPORT'";
      $Res=ExecSqlParam($this->db,$sql,array($p_id,$this->id));
    } else {
      $sql="insert into user_local_pref (user_id,parameter_type,parameter_value)".
	"values($1,'MINIREPORT',$2)";
      $Res=ExecSqlParam($this->db,$sql,array($this->id,$p_id));
    }


  }


  /*! 
   * \brief  Get the default user's preferences
   * \return array of (parameter_type => parameter_value)
   */ 
  function get_preference ()
  {
    $sql="select parameter_type,parameter_value from user_local_pref where user_id='".$this->id."'";
    $Res=ExecSql($this->db,$sql);
    $l_array=array();
    for ( $i =0;$i < pg_NumRows($Res);$i++) {
      $row= pg_fetch_array($Res,$i);
      $type=$row['parameter_type'];
      $l_array[$type]=$row['parameter_value'];
    }


    return $l_array;
  }
  /*! 
   * \brief  Check if an user is allowed to do an action
   * \param p_action_id 
   * \return
   *	- 0 no priv
   *      - 1 priv granted
   *
   */ 
  function check_action ( $p_action_id)
  {

    if ( $this->Admin()==1 ) return 1;
    if ( $this->is_local_admin(dossier::id()) == 1 ) return 1;
    
    $Res=ExecSqlParam($this->db,
		      "select * from user_sec_act where ua_login=$1 and ua_act_id=$2",
		      array($this->login,$p_action_id));
    $Count=pg_NumRows($Res);
    if ( $Count == 0 ) return 0;
    if ( $Count == 1 ) return 1;
    echo "<H2 class=\"error\"> Action Invalide !!! $Count select * from user_sec_act where ua_login='$p_login' and ua_act_id=$p_action_id </H2>";
    exit();
  }
  /*! 
   * \brief  Get the global preferences from user_global_pref
   *        in the account_repository db
   *
   * \note set $SESSION[g_variable]
   */
  function load_global_pref() 
  {
    echo_debug('class_user.php',__LINE__,"function load_global_pref");
    $cn=Dbconnect();
    // Load everything in an array
    $Res=ExecSql ($cn,"select parameter_type,parameter_value from 
                  user_global_pref
                  where user_id='".$this->login."'");
    $Max=pg_NumRows($Res);
    if (  $Max == 0 ) {
      $this->insert_default_global_pref();
      $this->load_global_pref();
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
    $array_pref=array ('g_theme'=>'THEME','g_pagesize'=>'PAGESIZE','g_topmenu'=>'TOPMENU');
    foreach ($array_pref as $name=>$parameter ) {
      if ( ! isset ($line[$parameter]) ) {
	echo_debug("Missing pref : ".$parameter);
	$this->insert_default_global_pref($parameter);
	$this->load_global_pref();
	return;
      }	
      $_SESSION[$name]=$line[$parameter];
    }
  }

  /*! 
   * \brief  insert default pref
   *        if no parameter are given insert all the existing 
   *        parameter otherwise only the requested
   * \param $p_type parameter's type or nothing
   * \param $p_value parameter value
   *
   */
  function insert_default_global_pref($p_type="",$p_value="") {
    echo_debug('class_user.php',__LINE__,"function insert_default_global_pref");
    echo_debug('class_user.php',__LINE__,"parameter p_type $p_type p_value  $p_value");

    $default_parameter= array("THEME"=>"Light",
			      "PAGESIZE"=>"50",'TOPMENU'=>'TEXT');
    $cn=Dbconnect();
    $Sql="insert into user_global_pref(user_id,parameter_type,parameter_value) 
				values ('%s','%s','%s')";
    if ( $p_type == "" ) {
      foreach ( $default_parameter as $name=>$value) {
	$Insert=sprintf($Sql,$this->login,$name,$value);
	ExecSql($cn,$Insert);
      }
    }
    else {
      $value=($p_value=="")?$default_parameter[$p_type]:$p_value;
      $Insert=sprintf($Sql,$this->login,$p_type,$value);
      ExecSql($cn,$Insert);
    }


  }

  /*! 
   * \brief  update default pref
   *           if value is not given then use the default value
   *
   * \param $p_type parameter's type 
   * \param $p_value parameter's value value of the type
   */
  function update_global_pref($p_type,$p_value="") {
    $default_parameter= array("THEME"=>"Light",
			      "PAGESIZE"=>"50",'TOPMENU'=>'SELECT');
    $cn=Dbconnect();
    $Sql="update user_global_pref set parameter_value='%s' 
			where parameter_type='%s' and 
				user_id='%s'";
    $value=($p_value=="")?$default_parameter[$p_type]:$p_value;
    $Update=sprintf($Sql,$value,$p_type,$this->login);
    ExecSql($cn,$Update);

  }//end function
  /*!\brief Return the year of current Periode
   *        it is the parm_periode.p_exercice col
   *        if an error occurs return 0
   */
  function get_exercice()
  {
    $sql="select p_exercice from parm_periode where p_id=".$this->get_periode();
    $Ret=ExecSql($this->db,$sql);
    if (pg_NumRows($Ret) == 1) 
      {
	$r=pg_fetch_array($Ret,0);
	return $r['p_exercice'];
      }
    else 
      return 0;

  }

  /*!\brief Check if the user can access 
   * otherwise warn and exit
   * \param $p_action requested action
   * \param $p_js = 1 javascript, or 0 just a text
   * \return nothing the program exits automatically
   */
  function can_request($p_action,$p_js=0)
  {
    if ( $this->check_action($p_action)==0 )
      {
	if ( $p_js == 1 )
	  {
	    echo "<script>";
	    echo "alert ('Cette action ne vous est pas autorisée. Contactez votre responsable');";
	    echo "</script>";
	 
	  }
	else
	  {
	    echo '<div class="u_redcontent">';
	    echo '<h2 class="error"> Cette action ne vous est pas autorisée Contactez votre responsable</h2>';
	    echo '</div>';

	  }
	exit(-1);

      }
  }
  /*!   
   * \brief Get the privilege of an user on a folder
   */
  function get_privilege($p_dossier)
  {
    /* you must be connected to the repository */
    $sql="select priv_priv 
       from priv_user  left join jnt_use_dos on jnt_id=priv_jnt
	inner join ac_users on ac_users.use_id=jnt_use_dos.use_id
       where ac_users.use_id=$1 and dos_id=$2";


    $Res=ExecSqlParam($this->db,$sql,array($this->id,$p_dossier));
    
    $Num=pg_NumRows($Res);
	
    /* If nothing is found there is no access */
    if ( $Num==0)       return 'X';
    
    $priv=pg_fetch_row($Res,0);
    return $priv[0];
  }
  /*! 
   * \brief  Check if an user is an local administrator
   * 
   * 
   * \param $p_dossier : dossier_id
   * 
   * \return
   *	- 0 if no
   *      - 1 if yes
   *
   */ 
  function is_local_admin($p_dossier=-1) 
  {
    if ($p_dossier==-1) {
      $p_dossier=dossier::id();
    }

    if ( $this->login == 'phpcompta') return 1;
    $sql='select priv_priv from ac_users join jnt_use_dos using (use_id) join priv_user '.
      ' on ( jnt_use_dos.jnt_id = priv_user.priv_jnt) '.
      " where priv_priv='L' and use_login='".$this->login."' and dos_id=$p_dossier";

    $cn=DbConnect();
  
    $isAdmin=CountSql($cn,$sql);


    return $isAdmin;

  }
  /*! 
   * \brief check the access of an user on a ledger
   * 
   * \param $p_jrn the ledger id
   * \return
   * - O only predefined operation
   * - W write
   * - R read only
   * - X no access
   *
   */ 

  function check_jrn($p_jrn) 
  {
    return $this->get_ledger_access($p_jrn);
  }
  function check_dossier($p_dossier_id) 
  {
    $this->Admin();
    if ( $this->admin==1) return 'L';
    $cn=DbConnect();

    $dossier=getDbValue($cn,"select priv_priv from jnt_use_dos join priv_user on (priv_jnt=jnt_id) where dos_id=$1 and use_id=$2",
			array($p_dossier_id,$this->id));
    if ( $dossier=='X' || $dossier=='') {
      alert('Dossier non accessible');
      exit();
    }
  }
  
}
  ?>
