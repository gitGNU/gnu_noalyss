#!/usr/bin/python


# Command we have to replace
# @table@ by the table name
# @id@ by the primary key
# @column_noid@ by the list of column (respect order for insert)
# @class_name@ Name of the class (uppercase)
# @column_array@ fill the $variable
# @sql_update@ the sql update
# @column_comma the column for insert and update
# read the file with the name
# first line = table name
# second line = pk

import sys, getopt
def help():
    print """
    option are -h for help
               -f input file containing the structure
               -c create the code for a child class
               -v create the code for a view (so only load and seek)
    The input file contains :
    first  line class name : mother class separator : (optionnal)
    second line table name
    3rd PK type
    ... and after all the column names and column type (see create_file_table.sql)
    see the file example
    """
def main():
    try:
        opts,args=getopt.getopt(sys.argv[1:],'cf:hv',['child','file','help','view'])
    except getopt.GetOptError, err:
        print str(err)
        help()
        sys.exit(-1)
    filein='';child=False;view=False
    for option,value in opts:
        if option in ('-f','--file'):
            filein=value
        elif option in ('-h','--help'):
            help()
            sys.exit(-1)
        elif option in ('-c','--child'):
            child=True
        elif option in ('-v','--view'):
            view=True
    if filein=='' :
        help()
        sys.exit(-2)
    sParent="""<?php
/**
 *@file
 *@brief Manage the table @table@
 *
 *
Example
@code

@endcode
 */
require_once('class_database.php');
require_once('ac_common.php');


/**
 *@brief Manage the table @table@
*/
class @class_name@  @mother_class@
{
  /* example private $variable=array("easy_name"=>column_name,"email"=>"column_name_email","val3"=>0); */
  
  protected $variable=array(@column_array@);
  function __construct ( & $p_cn,$p_id=-1) {
        $this->cn=$p_cn;
        $this->@id@=$p_id;
        
        if ( $p_id == -1 ) {
        /* Initialize an empty object */
            foreach ($this->variable as $key=>$value) $this->$value='';
            $this->@id@=$p_id;
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
    @verify_data_type@
    @set_tech_user@
  }
  public function save() {
  /* please adapt */
    if (  $this->@id@ == -1 ) 
      $this->insert();
    else
      $this->update();
  }
  /**
   *@brief retrieve array of object thanks a condition
   *@param $cond condition (where clause) (optional by default all the rows are fetched)
   * you can use this parameter for the order or subselect
   *@param $p_array array for the SQL stmt
   *@see Database::exec_sql get_object
   *@return the return value of exec_sql
   */
   public function seek($cond='',$p_array=null) 
   {
     $sql="select * from @table@  $cond";
     $aobj=array();
     $ret= $this->cn->exec_sql($sql,$p_array);
     return $ret;
   }
   /**
    *get_seek return the next object
    *@param $p_ret is the return value of an exec_sql
    *@param $idx is the index
    *@see seek
    *@return object 
    */
   public function get_object($p_ret,$idx)
    {
     // map each row in a object
     $oobj=new @class_name@ ($this->cn);
     $array=Database::fetch_array($p_ret,$idx);
     foreach ($array as $idx=>$value) { $oobj->$idx=$value; }
     $aobj[]=clone $oobj;

     return $aobj;
   }
  public function insert() {
    if ( $this->verify() != 0 ) return;
      if( $this->@id@==-1 ){
    /*  please adapt */
    $sql="insert into @table@(@column_noid@) values (@column_insert@) returning @id@";
    
    $this->@id@=$this->cn->get_value(
		 $sql,
		 array( @column_this@)
		 );
          } else {
              $sql="insert into @table@(@column_noid@,@id@) values (@column_insert_id@) returning @id@";
    
    $this->@id@=$this->cn->get_value(
		 $sql,
		 array( @column_this_id@)
		 );

          }
   
  }

  public function update() {
    if ( $this->verify() != 0 ) return;
    /*   please adapt */
    $sql="@sql_update@";
    $res=$this->cn->exec_sql(
		 $sql,
		 array(@column_comma@,$this->@id@)
		 );
		 
  }
/**
 *@brief load a object
 *@return 0 on success -1 the object is not found
 */
  public function load() {

   $sql="select @column_select@ from @table@ where @id@=$1"; 
    /* please adapt */
    $res=$this->cn->get_array(
		 $sql,
		 array($this->@id@)
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
    $sql="delete from @table@ where @id@=$1"; 
    $res=$this->cn->exec_sql($sql,array($this->@id@));
  }
  /**
   * Unit test for the class
   */	
  static function test_me() {
      $cn=new Database(25);
$cn->start();
    echo h2info('Test object vide');
    $obj=new @class_name@($cn);
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
// @class_name@::test_me();
?>
"""
    sChild="""<?php
/**
 *@file
 *@brief Manage the table @table@
 *
 *
Example
@code

@endcode
 */
require_once('class_database.php');
require_once('ac_common.php');

/**
 *@brief Manage the table @table@
 *@extends @mother_class@
*/
class @class_name@  @mother_class@
{
  /* example private $variable=array("easy_name"=>column_name,"email"=>"column_name_email","val3"=>0); */
  
  protected $variable=array(@column_array@);

  public function verify() {
    // Verify that the elt we want to add is correct
    /* verify only the datatype */
    @verify_data_type@
    @set_tech_user@
  }
  public function save() {
  /* please adapt */
    if (  $this->@id@ == -1 ) 
      $this->insert();
    else
      $this->update();
  }
  public function insert() {
    if ( $this->verify() != 0 ) return;
    /*  please adapt */
    $sql="insert into @table@(@column_noid@) values (@column_insert@) returning @id@";
    
    $this->@id@=$this->cn->get_value(
		 $sql,
		 array( @column_this@)
		 );
   
  }

  public function update() {
    if ( $this->verify() != 0 ) return;
    /*   please adapt */
    $sql="@sql_update@";
    $res=$this->cn->exec_sql(
		 $sql,
		 array(@column_comma@,$this->@id@)
		 );
		 
  }
/**
 *@brief load a object
 *@return 0 on success -1 the object is not found
 */
  public function load() {

   $sql="select @column_select@ from @table@ where @id@=$1"; 
    /* please adapt */
    $res=$this->cn->get_array(
		 $sql,
		 array($this->@id@)
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
    $sql="delete from @table@ where @id@=$1"; 
    $res=$this->cn->exec_sql($sql,array($this->@id@));
  }
  /**
   * Unit test for the class
   */	
  static function test_me() {
      $cn=new Database(25);
$cn->start();
    echo h2info('Test object vide');
    $obj=new @class_name@($cn);
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
@class_name@::test_me();
?>
"""
    sView="""<?php
/**
 *@file
 *@brief Manage the view @table@
 *
 *
Example
@code

@endcode
 */
require_once('class_database.php');
require_once('ac_common.php');

/**
 *@brief Manage the view @table@
*/
class @class_name@
{
  /* example private $variable=array("easy_name"=>column_name,"email"=>"column_name_email","val3"=>0); */
  
  protected $variable=array(@column_array@);
  function __construct ( & $p_cn) {
        $this->cn=$p_cn;
  }


/**
 *@brief load a object
 *@return 0 on success -1 the object is not found
 */
  public function load($cond,$array=null) {

   $sql="select @column_select@ from @table@ $cond"; 
    /* please adapt */
    $res=$this->cn->get_array(
		 $sql,
		 $array
		 );
		 
    if ( count($res) == 0 ) {
          /* Initialize an empty object */
          foreach ($this->variable as $key=>$value) $this->$key='';

          return -1;
          }
    foreach ($res[0] as $idx=>$value) { $this->$idx=$value; }
    return 0;
  }
  /**
   *@brief retrieve array of object thanks a condition
   *@param $cond condition (where clause) (optional by default all the rows are fetched)
   * you can use this parameter for the order or subselect
   *@param $p_array array for the SQL stmt
   *@see Database::get_array
   *@return an empty array if nothing is found
   */
   public function seek($cond='',$p_array=null) 
   {
     $sql="select * from @table@  $cond";
     $aobj=array();
     $array= $this->cn->get_array($sql,$p_array);
     // map each row in a object
     $size=$this->cn->count();
     if ( $size == 0 ) return $aobj;
     for ($i=0;$i<$size;$i++) {
         $oobj=new @class_name@ ($this->cn);
         foreach ($array[$i] as $idx=>$value) { $oobj->$idx=$value; }
         $aobj[]=clone $oobj;
     }
     return $aobj;
   }
}
?>

"""

    # read the file
    try :
        file=open(filein,'r')
        line=file.readlines()
        mother_name='';mother_class=''
        if line[0].find(':') > 0 :
            class_name=(line[0].split(':'))[0].strip()
            mother_name=(line[0].split(':'))[1].strip()
            mother_class="extends "+mother_name
        else:
            class_name=line[0].strip()
            mother_name=''
            mother_class=''
        table=line[1].strip()
        (id,type_id)=line[2].strip().split('|')
        id=id.strip()
        column_noid=''
        column_this=''
        column_select=''
        column_insert=''
        fileoutput=open("class_"+class_name.lower()+".php",'w+')
        
        sep=''
        i=1
	set_tech_user=""
        for e in line[3:]:
            if e.find('|') < 0 :
                continue
            col_name=(e.split('|'))[0].strip()
            col_type=(e.split('|'))[1].strip()
	    if col_name == 'tech_date':
		print "*"*80
		print ('Warning : tech_date est un champs technique a utiliser avec un trigger')
		print "*"*80
            column_this=column_this+sep+'$this->'+col_name+"\n"
            column_noid=column_noid+sep+col_name+"\n"

	    if col_name == 'tech_user' :
		set_tech_user=" $this->tech_user=$_SESSION['g_user']; "
            if col_type == 'date':
                column_select=column_select+sep+"to_char("+col_name+",'DD.MM.YYYY') as "+col_name+"\n"
		column_insert=column_insert+sep+"to_date($"+str(i)+",'DD.MM.YYYY') \n "
            else:
                column_select=column_select+sep+col_name+"\n"
                column_insert=column_insert+sep+'$'+str(i)+"\n"
            i+=1                
            sep=','
        column_insert_id=column_insert+sep+'$'+str(i)+"\n"
        column_this_id=column_this+sep+'$this->'+id
        column_array=''
        sep=''
        for e in line [3:]:
            if e.find('|') < 0 :
                continue
            col_name=(e.split('|'))[0].strip()
            column_array+=sep+'"'+col_name+'"=>"'+col_name+'"'+"\n"
            sep=','
        column_array='"'+id+'"=>"'+id+'",'+column_array
        sql_update=" update "+table
        i=1;sep='';set=' set '
        column_comma=''
        for e in line[3:]:
            if e.find('|') < 0 :
                continue
            if  (e.split('|'))[1].strip() == 'date':
                  sql_update+=sep+set+(e.split('|'))[0].strip()+" =to_date($"+str(i)+",'DD.MM.YYYY')"+"\n"
            else:
                  sql_update+=sep+set+(e.split('|'))[0].strip()+" = $"+str(i)+"\n"
            set=''
            column_comma+=sep+"$this->"+(e.split('|'))[0].strip()+"\n"
            i+=1
            sep=','
        sql_update=sql_update+" where "+id+"= $"+str(i)
        verify_data_type=''
        # create verify data_type
        for e in line[3:]:
               if e.find('|') < 0 :
                         continue

               (col_id,col_type)=e.split('|')
               col_id=col_id.strip()
               col_type=col_type.strip()
               if col_type in ('float','integer','numeric','bigint') :
                   verify_data_type+="if ( settype($this->"+col_id+",'float') == false )\n \
            throw new Exception('DATATYPE "+col_id+" $this->"+col_id+" non numerique');\n"
                   if col_type in ('date',' timestamp without time zone','timestamp with time zone'):
                       verify_data_type+=" if (isDate($this->"+col_id+") == null )\n \
            throw new Exception('DATATYPE "+col_id+" $this->"+col_id+" date invalide');\n"
        if  child == True :
            sChild=sChild.replace('@id@',id)
            sChild=sChild.replace('@table@',table)
            sChild=sChild.replace('@class_name@',class_name)
            sChild=sChild.replace('@column_noid@',column_noid)
            sChild=sChild.replace('@column_array@',column_array)
            sChild=sChild.replace('@sql_update@',sql_update)
            sChild=sChild.replace('@column_comma@',column_comma)
            sChild=sChild.replace('@column_this@',column_this)
            sChild=sChild.replace('@column_this_id@',column_this_id)	
            sChild=sChild.replace('@verify_data_type@',verify_data_type)
            sChild=sChild.replace('@column_select@',column_select)
            sChild=sChild.replace('@column_insert@',column_insert)
            sChild=sChild.replace('@mother_name@',mother_name)
            sChild=sChild.replace('@mother_class@',mother_class)            
            sChild=sChild.replace('@column_insert_id@',column_insert_id)
            fileoutput.writelines(sChild)
        elif view == True:
            sView=sView.replace('@id@',id)
            sView=sView.replace('@table@',table)
            sView=sView.replace('@class_name@',class_name)
            sView=sView.replace('@column_noid@',column_noid)
            sView=sView.replace('@column_array@',column_array)
            sView=sView.replace('@sql_update@',sql_update)
            sView=sView.replace('@column_comma@',column_comma)
            sView=sView.replace('@column_this@',column_this)
            sView=sView.replace('@column_this_id@',column_this_id)	
            sView=sView.replace('@verify_data_type@',verify_data_type)
            sView=sView.replace('@column_select@',column_select)
            sView=sView.replace('@column_insert@',column_insert)
            sView=sView.replace('@mother_name@',mother_name)
            sView=sView.replace('@mother_class@',mother_class)            
            sView=sView.replace('@column_insert_id@',column_insert_id)
            sView=sView.replace('@set_tech_user@',set_tech_user)
            fileoutput.writelines(sView)
        else:
            sParent=sParent.replace('@id@',id)
            sParent=sParent.replace('@table@',table)
            sParent=sParent.replace('@class_name@',class_name)
            sParent=sParent.replace('@column_noid@',column_noid)
            sParent=sParent.replace('@column_array@',column_array)
            sParent=sParent.replace('@sql_update@',sql_update)
            sParent=sParent.replace('@column_comma@',column_comma)
            sParent=sParent.replace('@column_this@',column_this)
	    sParent=sParent.replace('@column_this_id@',column_this_id)	
            sParent=sParent.replace('@verify_data_type@',verify_data_type)
            sParent=sParent.replace('@column_select@',column_select)
            sParent=sParent.replace('@column_insert@',column_insert)
            sParent=sParent.replace('@column_insert_id@',column_insert_id)
            sParent=sParent.replace('@mother_class@',mother_class)
            sParent=sParent.replace('@set_tech_user@',set_tech_user)
            fileoutput.writelines(sParent)

    except :
        print "error "
        print sys.exc_info()
if __name__ == "__main__":
    main()
