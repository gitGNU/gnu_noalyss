  #!/usr/bin/python


# Command we have to replace
# @table@ by the table name
# @id@ by the primary key
# @column_noid@ by the list of column (respect order for insert)
# @class_name@ Name of the class (uppercase)
# @column_array@ fill the $variable
# @sql_update@ the sql update
# read the file with the name
# @column_comma the column for insert and update
# first line = table name
# second line = pk

import sys, getopt

def help():
    print """
    option are -h for help
               -f input file containing the structure
               -c create the code for a child class
               -v create the code for a view (so only load and seek)
               -n the PK is not serial
    The input file contains :
    first  line class name : mother class separator : (optionnal)
    second line table name
    3rd PK type
    ... and after all the column names and column type (see create_file_table.sql)
    see the file example
    """
def main():
    try:
        opts,args=getopt.getopt(sys.argv[1:],'cf:hv',['child','file','help','view','pk-not-serial'])
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
        elif option in ('-n','--pk-not-serial'):
	    nopk=True
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
require_once('class_phpcompta_sql.php');


/**
 *@brief Manage the table @table@
*/
class @class_name@ extends Phpcompta_SQL
{
  /* example private $variable=array("easy_name"=>column_name,"email"=>"column_name_email","val3"=>0); */
  function __construct($p_id=-1)
	{


		$this->table = "@table@";
		$this->primary_key = "@id@";

		$this->name = array(
			@column_array@
		);

		$this->type = array(
			@column_type_array@
		);

		$this->default = array(
			"@id@" => "auto"
		);
		global $cn;

		parent::__construct($cn, $p_id);
	}
  public function verify() {
    parent::verify();
    
  }
  
}
  
?>
"""
  

    # read the file
    try :
        file=open(filein,'r')
        line=file.readlines()
        class_name=line[0].strip()
                  
            
        table=line[1].strip()
        (id,type_id,default)=line[2].strip().split('|')
        id=id.strip()
        column_noid=''
        column_this=''
        column_select=''
        column_insert=''
        fileoutput=open("class_"+class_name.lower()+".php",'w+')
	print "Create the file "+fileoutput.name
        
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
        column_type_array=''
        sep=''
        for e in line [3:]:
            if e.find('|') < 0 :
                continue
            col_name=(e.split('|'))[0].strip()
            col_type=(e.split('|'))[1].strip()
            if col_type == 'integer' or col_type == 'numeric' or col_type=='bigint':
		col_type="numeric"
	    elif col_type=='text' or col_type=='character varying':
		col_type="text"
	    elif col_type=='date':
		col_type='date'
	    else :
		col_type='set_me'
            column_array+=sep+'"'+col_name+'"=>"'+col_name+'"'+"\n"
            column_type_array+=sep+'"'+col_name+'"=>"'+col_type+'"'+"\n"
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

               (col_id,col_type,default)=e.split('|')
               col_id=col_id.strip()
               col_type=col_type.strip()
               verify_data_type+=" if ( trim($this->"+col_id+") == '') $this->"+col_id+"=null;\n"
               if col_type in ('float','integer','numeric','bigint') :
                   verify_data_type+="if ( $this->"+col_id+"!== null && settype($this->"+col_id+",'float') == false )\n \
            throw new Exception('DATATYPE "+col_id+" $this->"+col_id+" non numerique');\n"
                   if col_type in ('date',' timestamp without time zone','timestamp with time zone'):
                       verify_data_type+=" if (isDate($this->"+col_id+") == null )\n \
            throw new Exception('DATATYPE "+col_id+" $this->"+col_id+" date invalide');\n"
       
	sParent=sParent.replace('@id@',id)
	sParent=sParent.replace('@table@',table)
	sParent=sParent.replace('@class_name@',class_name)
	sParent=sParent.replace('@column_noid@',column_noid)
	sParent=sParent.replace('@column_array@',column_array)
	sParent=sParent.replace('@column_type_array@',column_type_array)
	sParent=sParent.replace('@sql_update@',sql_update)
	sParent=sParent.replace('@column_comma@',column_comma)
	sParent=sParent.replace('@column_this@',column_this)
	sParent=sParent.replace('@column_this_id@',column_this_id)	
	sParent=sParent.replace('@verify_data_type@',verify_data_type)
	sParent=sParent.replace('@column_select@',column_select)
	sParent=sParent.replace('@column_insert@',column_insert)
	sParent=sParent.replace('@column_insert_id@',column_insert_id)
	sParent=sParent.replace('@set_tech_user@',set_tech_user)
	fileoutput.writelines(sParent)

    except :
        print "error "
        print sys.exc_info()
if __name__ == "__main__":
    main()
