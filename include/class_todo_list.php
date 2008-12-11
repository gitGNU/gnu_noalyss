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

/*!\file
 * \brief the todo list is managed by this class
 */


/*!\brief 
 * This class manages the table todo_list
 * Data Member : 
 * - $cn database connx
 * - $variable
 *    - id (todo_list.tl_id)
 *    - date (todo_list.tl_Date)
 *    - title (todo_list.title)
 *    - desc (todo_list.tl_desc)
 *    - owner (todo_list.use_id)
 * 
 */
class Todo_List
{

  private static $variable=array(
				 "id"=>"tl_id",
				 "date"=>"tl_date",
				 "title"=>"tl_title",
				 "desc"=>"tl_desc",
				 "owner"=>"use_login");
  private $cn;
  private  $tl_id,$tl_date,$tl_title,$use_login;
				 
  function __construct ($p_init) {
    $this->cn=$p_init;
    $this->tl_id=0;
    $this->tl_desc="";
    $this->use_login=$_SESSION['g_user'];

  }
  public function get_parameter($p_string) {
    if ( array_key_exists($p_string,self::$variable) ) {
      $idx=self::$variable[$p_string];
      return $this->$idx;
    }
    else 
      exit (__FILE__.":".__LINE__.'Erreur attribut inexistant');
  }
  public function check($p_idx,&$p_value) {
	if ( strcmp ($p_idx, 'tl_id') == 0 ) { if ( strlen($p_value) > 6 || isNumeric ($p_value) == false) return false;}
	if ( strcmp ($p_idx, 'tl_date') == 0 ) { if ( strlen($p_value) > 12 || isDate ($p_value) == false) return false;}
	if ( strcmp ($p_idx, 'tl_title') == 0 ) { $p_value=subsrt(htmlentities($p_value),0,120) ; return true;}
	if ( strcmp ($p_idx, 'tl_desc') == 0 ) { $p_value=substr(htmlentities($p_value),0,400) ; return true;}
	return true;
  }
  public function set_parameter($p_string,$p_value) {
    if ( array_key_exists($p_string,self::$variable) ) {
      $idx=self::$variable[$p_string];
      if ($this->check($idx,$p_value) == true )      $this->$idx=$p_value;
    }
    else 
      exit (__FILE__.":".__LINE__.'Erreur attribut inexistant');
    
    
  }
  public function get_info() {    return var_export(self::$variable,true);  }
  public function verify() {
    return 0;
  }
  public function save() {
    if (  $this->get_parameter("id") == 0 ) 
      $this->insert();
    else
      $this->update();
  }

  public function insert() {
    if ( $this->verify() != 0 ) return;

    $sql="insert into todo_list (tl_date,tl_title,tl_desc,use_login) ".
      " values (to_date($1,'DD.MM.YYYY'),$2,$3,$4)  returning tl_id";
    $res=ExecSqlParam($this->cn,
		 $sql,
		 array($this->tl_date,
		       $this->tl_title,
		       $this->tl_desc,
		       $this->use_login)
		 );
    $this->tl_id=pg_fetch_result($res,0,0);
    
  }

  public function update() {
    if ( $this->verify() != 0 ) return;

    $sql="update todo_list set tl_title=$1,tl_date=to_date($2,'DD.MM.YYYY'),tl_desc=$3 ".
      " where tl_id = $4";
    $res=ExecSqlParam($this->cn,
		 $sql,
		 array($this->tl_title,
		       $this->tl_date,
		       $this->tl_desc,
		       $this->tl_id)
		 );
		 
  }
  /*!\brief load all the task
   *\return an array of the existing tasks of the current user
   */
  public function load_all() {
    $sql="select tl_id, tl_title,tl_desc,to_char( tl_date,'DD.MM.YYYY') as tl_date 
from todo_list where use_login=$1".
      " order by tl_date desc";
    $res=ExecSqlParam($this->cn,
		      $sql,
		      array($this->use_login));	
    $array=pg_fetch_all($res);
    return $array;	   
  }
  public function load() {

   $sql="select tl_id,tl_title,tl_desc,to_char( tl_date,'DD.MM.YYYY') as tl_date
from todo_list where tl_id=$1"; 

    $res=ExecSqlParam($this->cn,
		 $sql,
		 array($this->tl_id)
		 );

    if ( pg_NumRows($res) == 0 ) return;
    $row=pg_fetch_array($res,0);
    foreach ($row as $idx=>$value) { $this->$idx=$value; }
  }
  public function delete() {
    $sql="delete from todo_list where tl_id=$1"; 
    $res=ExecSqlParam($this->cn,$sql,array($this->tl_id));

  }
  public function toJSON() {
    $r='';
    $r.='{';
    $r.='"tl_id":"'.$this->tl_id.'",';
    $r.='"tl_title":"'.$this->tl_title.'",';
    $r.='"tl_desc":"'.$this->tl_desc.'",';
    $r.='"tl_date":"'.$this->tl_date.'"';
    $r.='}';
    return $r;
  }
  /*!\brief static testing function
   */
  static function test_me() {
    $cn=DbConnect(dossier::id());
    $r=new Todo_List($cn);
    $r->set_parameter('title','test');
    $r->use_login='phpcompta';
    $r->set_parameter('date','02.03.2008');
    $r->save();
    $r->set_parameter('id',3);
    $r->load();
    print_r($r);
    $r->set_parameter('title','Test UPDATE');
    $r->save();
    print_r($r);
    $r->set_parameter('id',1);
    $r->delete();
  }
  
}


