<?php
/**
 *@file
 *@brief Manage the table public.profile_menu
 *
 *
Example
@code

@endcode
 */
require_once('class_database.php');
require_once('ac_common.php');


/**
 *@brief Manage the table public.profile_menu
*/
class Profile_Menu_sql  
{
  /* example private $variable=array("easy_name"=>column_name,"email"=>"column_name_email","val3"=>0); */
  
  protected $variable=array("pm_id"=>"pm_id","me_code"=>"me_code"
,"me_code_dep"=>"me_code_dep"
,"p_id"=>"p_id"
,"p_order"=>"p_order"
,"p_type_display"=>"p_type_display"
,"pm_default"=>"pm_default"
);
  function __construct ( & $p_cn,$p_id=-1) {
        $this->cn=$p_cn;
        $this->pm_id=$p_id;
        
        if ( $p_id == -1 ) {
        /* Initialize an empty object */
            foreach ($this->variable as $key=>$value) $this->$value=null;
            $this->pm_id=$p_id;
        } else {
         /* load it */

         $this->load();
      }
  }
  public function get_parameter($p_string) {
    if ( array_key_exists($p_string,$this->variable) ) {
      $idx=$this->variable[$p_string];
      return $this->$idx;
    }
    else 
      throw new Exception (__FILE__.":".__LINE__.$p_string.'Erreur attribut inexistant');
  }
  public function set_parameter($p_string,$p_value) {
    if ( array_key_exists($p_string,$this->variable) ) {
      $idx=$this->variable[$p_string];
      $this->$idx=$p_value;
    }
    else 
      throw new Exception (__FILE__.":".__LINE__.$p_string.'Erreur attribut inexistant');
  }
  public function get_info() {    return var_export($this,true);  }
  public function verify() {
    // Verify that the elt we want to add is correct
    /* verify only the datatype */
     if ( trim($this->me_code) == '') $this->me_code=null;
 if ( trim($this->me_code_dep) == '') $this->me_code_dep=null;
 if ( trim($this->p_id) == '') $this->p_id=null;
if ( $this->p_id!== null && settype($this->p_id,'float') == false )
             throw new Exception('DATATYPE p_id $this->p_id non numerique');
 if ( trim($this->p_order) == '') $this->p_order=null;
if ( $this->p_order!== null && settype($this->p_order,'float') == false )
             throw new Exception('DATATYPE p_order $this->p_order non numerique');
 if ( trim($this->p_type_display) == '') $this->p_type_display=null;
 if ( trim($this->pm_default) == '') $this->pm_default=null;
if ( $this->pm_default!== null && settype($this->pm_default,'float') == false )
             throw new Exception('DATATYPE pm_default $this->pm_default non numerique');

    
  }
  public function save() {
  /* please adapt */
    if (  $this->pm_id == -1 ) 
      $this->insert();
    else
      $this->update();
  }
  /**
   *@brief retrieve array of object thanks a condition
   *@param $cond condition (where clause) (optional by default all the rows are fetched)
   * you can use this parameter for the order or subselect
   *@param $p_array array for the SQL stmt
   *@see Database::exec_sql get_object  Database::num_row
   *@return the return value of exec_sql
   */
   public function seek($cond='',$p_array=null) 
   {
     $sql="select * from public.profile_menu  $cond";
     $aobj=array();
     $ret= $this->cn->exec_sql($sql,$p_array);
     return $ret;
   }
   /**
    *get_seek return the next object, the return of the query must have all the column
    * of the object
    *@param $p_ret is the return value of an exec_sql
    *@param $idx is the index
    *@see seek
    *@return object 
    */
   public function get_object($p_ret,$idx)
    {
     // map each row in a object
     $oobj=new Profile_Menu_sql ($this->cn);
     $array=Database::fetch_array($p_ret,$idx);
     foreach ($array as $idx=>$value) { $oobj->$idx=$value; }
     return $oobj;
   }
  public function insert() {
    if ( $this->verify() != 0 ) return;
      if( $this->pm_id==-1 ){
    /*  please adapt */
    $sql="insert into public.profile_menu(me_code
,me_code_dep
,p_id
,p_order
,p_type_display
,pm_default
) values ($1
,$2
,$3
,$4
,$5
,$6
) returning pm_id";
    
    $this->pm_id=$this->cn->get_value(
		 $sql,
		 array( $this->me_code
,$this->me_code_dep
,$this->p_id
,$this->p_order
,$this->p_type_display
,$this->pm_default
)
		 );
          } else {
              $sql="insert into public.profile_menu(me_code
,me_code_dep
,p_id
,p_order
,p_type_display
,pm_default
,pm_id) values ($1
,$2
,$3
,$4
,$5
,$6
,$7
) returning pm_id";
    
    $this->pm_id=$this->cn->get_value(
		 $sql,
		 array( $this->me_code
,$this->me_code_dep
,$this->p_id
,$this->p_order
,$this->p_type_display
,$this->pm_default
,$this->pm_id)
		 );

          }
   
  }

  public function update() {
    if ( $this->verify() != 0 ) return;
    /*   please adapt */
    $sql=" update public.profile_menu set me_code = $1
,me_code_dep = $2
,p_id = $3
,p_order = $4
,p_type_display = $5
,pm_default = $6
 where pm_id= $7";
    $res=$this->cn->exec_sql(
		 $sql,
		 array($this->me_code
,$this->me_code_dep
,$this->p_id
,$this->p_order
,$this->p_type_display
,$this->pm_default
,$this->pm_id)
		 );
		 
  }
/**
 *@brief load a object
 *@return 0 on success -1 the object is not found
 */
  public function load() {

   $sql="select me_code
,me_code_dep
,p_id
,p_order
,p_type_display
,pm_default
 from public.profile_menu where pm_id=$1"; 
    /* please adapt */
    $res=$this->cn->get_array(
		 $sql,
		 array($this->pm_id)
		 );
		 
    if ( count($res) == 0 ) {
          /* Initialize an empty object */
          foreach ($this->variable as $key=>$value) $this->$key='';

          return -1;
          }
    foreach ($res[0] as $idx=>$value) { $this->$idx=$value; }
    return 0;
  }

  public function delete() {
    $sql="delete from public.profile_menu where pm_id=$1"; 
    $res=$this->cn->exec_sql($sql,array($this->pm_id));
  }
  /**
   * Unit test for the class
   */	
  static function test_me() {
      $cn=new Database(25);
$cn->start();
    echo h2info('Test object vide');
    $obj=new Profile_Menu_sql($cn);
    var_dump($obj);

    echo h2info('Test object NON vide');
    $obj->set_parameter('j_id',3);
    $obj->load();
    var_dump($obj);

    echo h2info('Update');
    $obj->set_parameter('j_qcode','NOUVEAU CODE');
    $obj->save();
    $obj->load();
    var_dump($obj);

    echo h2info('Insert');
    $obj->set_parameter('j_id',0);
    $obj->save();
    $obj->load();
    var_dump($obj);

    echo h2info('Delete');
    $obj->delete();
    echo (($obj->load()==0)?'Trouve':'non trouve');
    var_dump($obj);
$cn->rollback();

  }
  
}
// Profile_Menu_sql::test_me();
?>
