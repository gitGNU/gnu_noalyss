<?php
/**
 *@brief Manage the table attr_def
 *
 *
Example
@code

@endcode
 */
require_once('class_database.php');
require_once('ac_common.php');

class Fiche_Attr  
{
  /* example private $variable=array("easy_name"=>column_name,"email"=>"column_name_email","val3"=>0); */
  
  protected $variable=array("id"=>"ad_id","desc"=>"ad_text","type"=>"ad_type","size"=>"ad_size");
  function __construct ($p_cn,$p_id=0) {
        $this->cn=$p_cn;
        if ( $p_id == 0 ) {
        /* Initialize an empty object */
            foreach ($this->variable as $key=>$value) $this->$value='';
        } else {
         /* load it */
         $this->ad_id=$p_id;
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
    if ( strlen(trim($this->ad_text))==0)
      throw new Exception('La description ne peut pas être vide',1);
    if ( strlen(trim($this->ad_type))==0)
      throw new Exception('Le type ne peut pas être vide',1);
    $this->ad_type=strtolower($this->ad_type);
    if ( in_array($this->ad_type,array('date','text','numeric','zone'))==false)
      throw new Exception('Le type doit être text, numeric ou date',1);
    if ( trim($this->ad_size)=='' || isNumber($this->ad_size)==0||$this->ad_size>22) {
      switch ($this->ad_type) {
      case 'text':
	$this->ad_size=22;
	break;
      case 'numeric':
	$this->ad_size=9;
	break;
      case 'date':
	$this->ad_size=8;
	break;
      case 'zone':
	$this->ad_size=22;
	break;
      default:
	$this->ad_size=22;
      }
    }
  }
  public function save() {
  /* please adapt */
    if (  $this->ad_id == 0 ) 
      $this->insert();
    else
      $this->update();
  }
  /**
   *@brief retrieve array of object thanks a condition
   *@param $cond condition (where clause)
   *@param $p_array array for the SQL stmt
   *@see Database::get_array
   *@return an empty array if nothing is found
   */
   public function seek($cond='',$p_array=null) 
   {
     if ( $cond != '') 
       $sql="select * from attr_def where $cond order by ad_text";
     else
       $sql="select * from attr_def order by ad_text";

     $aobj=array();
     $array= $this->cn->get_array($sql,$p_array);
     // map each row in a object
     $size=$this->cn->count();
     if ( $size == 0 ) return $aobj;
     for ($i=0;$i<$size;$i++) {
         $oobj=new Fiche_Attr ($this->cn);
         foreach ($array[$i] as $idx=>$value) { $oobj->$idx=$value; }
         $aobj[]=clone $oobj;
     }
     return $aobj;
   }
  public function insert() {
    $this->verify();
    /*  please adapt */
    $sql="insert into attr_def(ad_text
,ad_type,ad_size
) values ($1
,$2,$3
) returning ad_id";
    
    $this->ad_id=$this->cn->get_value(
				      $sql,
				      array( $this->ad_text,$this->ad_type,$this->ad_size
					     )
				      );

  }

  public function update() {
    if ( $this->verify() != 0 ) return;
    if ( $this->ad_id < 9000) return;
    /*   please adapt */
    $sql=" update attr_def set ad_text = $1
,ad_type = $2,ad_size=$4
 where ad_id= $3";
    $res=$this->cn->exec_sql(
		 $sql,
		 array($this->ad_text
		       ,$this->ad_type
		       ,$this->ad_id,$this->ad_size)
		 );
		 
  }
/**
 *@brief load a object
 *@return 0 on success -1 the object is not found
 */
  public function load() {

   $sql="select ad_text
,ad_type
 from attr_def where ad_id=$1"; 
    /* please adapt */
    $res=$this->cn->get_array(
		 $sql,
		 array($this->ad_id)
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
    if ($this->ad_id < 9000)  return;
    $sql=$this->cn->exec_sql("delete from attr_value where jft_id in (select jft_id from jnt_fic_att_value where ad_id=$1 )",
			     array($this->ad_id));
    $sql="delete from jnt_fic_att_value where ad_id=$1"; 
    $res=$this->cn->exec_sql($sql,array($this->ad_id));

    $sql="delete from jnt_fic_attr where ad_id=$1"; 
    $res=$this->cn->exec_sql($sql,array($this->ad_id));

    $sql="delete from attr_def where ad_id=$1"; 
    $res=$this->cn->exec_sql($sql,array($this->ad_id));

  }
  /**
   * Unit test for the class
   */	
  static function test_me() {
      $cn=new Database(25);
$cn->start();
    echo h2info('Test object vide');
    $obj=new Fiche_Attr($cn);
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
//Fiche_Attr::test_me();

