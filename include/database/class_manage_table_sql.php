<script>
var ManageTable={
	add:function(p_table) {
		// display the form to enter data
	},
	save:function(form_id) {

	},
	delete:function (p_table,p_id) {

	},
    input:function (p_table,p_id) {
    }
    

}
</script>

<?php
class Manage_Table_SQL 
{
	private $table ; 
	private $a_label_displaid;
    private $a_order; //!< order
    private $a_prop ; //!< property 
    const UPDATABLE=1;
    const VISIBLE=2;
	function __construct(Noalyss_SQL $p_table)
	{
		$this->table=$p_table;
		$order=0;
		foreach ($this->table->name as $key=> $value)
	        {
		
			$this->a_label_displaid[$value]=$value;
			$this->a_order[$order]=$value;
            $this->a_prop[$value]=self::UPDATABLE|self::VISIBLE;
			$order++;
		}
		
	}
	function set_col_label($p_col_name,$p_display)
	{
		$this->a_label_displaid[$p_col_name]=$p_display;
	}
	function get_current_pos($p_col_name)
	{
		$nb_order=count($this->a_order);
		for ($i=0;$i<$nb_order;$i++) 
			if ( $this->a_order[$i]==$p_col_name) return $i;
		throw new Exception ("COL INVAL ".$p_col_name);
		
	}
	/**	
	 *@brief if we change a column order , the order
 	 * of the other columns is impacted.
	 *
	 * With a_order[0,1,2,3]=[x,y,z,a]
  	 * if we move the column x (idx=0) to 2	
	 * we must obtain [y,z,x,a]
	*/
	function move($p_col_name,$p_idx)
	{
		// get current position of p_col_name
		$cur_pos=$this->get_current_pos($p_col_name);
	
		if ( $cur_pos == $p_idx ) return ;

		if ( $cur_pos < $p_idx ) 
		{
			$nb_order=count($this->a_order);
			for ($i=0;$i<$nb_order;$i++) {
				// if col_name is not the searched one we continue		
				if ( $this->a_order[$i] != $p_col_name ) continue;
				if ( $p_idx == $i ) continue;
				// otherwise we swap with i+1
				$old=$this->a_order[$i+1];	
				$this->a_order[$i]=$this->a_order[$i+1];
				$this->a_order[$i+1]=$p_col_name;
			}
		} else {

			$nb_order=count($this->a_order)-1;
			for ($i=$nb_order;$i>0;$i--) {
				// if col_name is not the searched one we continue		
				if ( $this->a_order[$i] != $p_col_name ) continue;
				if ( $p_idx == $i ) continue;
				// otherwise we swap with i+1
				$old=$this->a_order[$i-1];	
				$this->a_order[$i]=$this->a_order[$i-1];
				$this->a_order[$i-1]=$p_col_name;
			}

		}
		
	}
	
	function display_table()
	{
		$ret=$this->table->seek();
		$nb=Database::num_count($ret);
		for ($i=0;$i< $nb ; $i++ )
		{
			if ( $i == 0 ) {
				$this->display_table_header();
			}
			$row=Database::fetch_array($ret,$i);
			$this->display_row($row);
		}
	}
	
	function display_table_header()
	{
		$nb=count($this->a_order);
        echo "<tr>";

		for ($i=0;$i < $nb ; $i++ ) {
			
			$key=$this->a_order[$i];
			echo th($this->a_label_displaid[$key]);	
		}
		echo "</tr>";

	}
	function set_pk($p_id)
	{
		$this->table->set_pk_value($p_id);
	}
	function from_request()
	{
		$nb=count($this->a_order);
		for ($i=0;$i < $nb ; $i++ ) {
			$v=HtmlInput::default_value_request($this->a_order[$i],"");
			$key=$this->a_order[$i];
			$this->table->$key=$v;
		}
		
	}
	private function display_row($p_row) 
	{

        printf ('<tr id="%s_%s">',
                $this->table->table,
                $p_row[$this->table->primary_key])
        ;

		$nb_order=count($this->a_order);
		for ($i=0;$i < $nb_order ; $i++)
	        {
                $v=$this->a_order($i);
                echo td($p_row[$v]);		
            }
        $js=printf ("ManageTable.input('%s','%s');",
                       $this->table->table,
                       $p_row[$this->table->primary_key]
        );
        $js=printf ("ManageTable.delete('%s','%s');",
                     $this->table->table,
                     $p_row[$this->table->primary_key]
        );

        echo "<td>";
		echo '</tr>';
	}
	function input()
	{
		$nb_order=count($this->a_order);
		echo "<table>";
		echo "<tr>";
		for ( $i=0; $i <$nb_order ; $i++ )
		{
			$key=$this->a_order[$i];
			$label=$this->a_label_displaid[$key];
			$value=$this->table->get($key);
			
			// Label
			echo "<td> {$label} </td>";
			printf('<input type="text" label="%s" value="%s" name="%s" id="%s">',
				$label,
				$value,
				$key,
				$key
				);
		}	
		echo "</tr>";
		echo "</table>";
	}
	function delete() {
		$this->table->delete();
	}
	function save() {
		$this->table->save();
	}
	function insert() {
		$this->table->insert();
	}
	function update() {
		$this->table->update();
	}
	function set_value($p_col_name,$p_value)
	{
		$this->table->set($p_col_name,$p_value);
	}
		
}

