

/**
 * Autogenerated file 
 */
/**
 * <?php echo $this->table_name."_sql.class.php"."\n"; ?>
 *
 *@file
 *@brief abstract of the table <?php echo $this->schema_name.".".$this->table_name?>
 */
class <?php echo ucwords($this->table_name)."_SQL";?> extends SQL
{

function __construct($p_id=-1)
  {
  $this->table = "<?php echo $this->schema_name.".".$this->table_name?>";
  $this->primary_key = "<?php echo  $this->pk?>";
/*
 * List of columns
 */
  $this->name=array(
  <?php 
  $sep="";
  for ($i=0;$i < count($columns);$i++):
      print "\t". $sep.'"'.$columns[$i]['column_name'].'"'.'=>'.'"'.$columns[$i]['column_name'].'"';
      $sep=",";
      printf("\n");
  endfor;
?>
        );
/*
 * Type of columns
 */
  $this->type = array(
   <?php 
  $sep="";
  for ($i=0;$i < count($columns);$i++):
      $type=$columns[$i]['data_type'];
      if ( in_array($columns[$i]['data_type'], array('integer','numeric','bigint'))):
          $type="numeric";
          elseif (in_array($columns[$i]['data_type'], array('character','character varying','text'))):
              $type="text";
          elseif (in_array($columns[$i]['data_type'], array('timestamp without timezone','timestamp with timezone','date'))):
              $type="date";
          endif; 
      print "\t". $sep.'"'.$columns[$i]['column_name'].'"'.'=>'.'"'.$type.'"';
      printf("\n");
      $sep=",";
  endfor;
?>          );
 

  $this->default = array(
  "<?php echo $this->pk?>" => "auto",
  );

  $this->date_format = "DD.MM.YYYY";
  global $cn;

  parent::__construct($cn,$p_id);
  }
  

}