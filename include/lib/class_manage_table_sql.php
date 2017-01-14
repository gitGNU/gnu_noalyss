<?php

/*
 *   This file is part of NOALYSS.
 *
 *   NOALYSS is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   NOALYSS is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with NOALYSS; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */
// Copyright Author Dany De Bontridder danydb@aevalys.eu
//@file
//@brief Definition Manage_Table_SQL

/**
 * @brief Purpose is to propose a librairy to display a table content
 * and allow to update and delete row , handle also the ajax call 
 * thanks the script managetable.js
 * @see ManageTable
 */
class Manage_Table_SQL
{

    private $table; //!< Object Noalyss_SQL
    private $a_label_displaid; //!< Label of the col. of the datarow
    private $a_order; //!< order of the col
    private $a_prop; //!< property for each col.
    private $object_name; //!< Object_name is used for the javascript
    private $row_delete; //!< Flag to indicate if rows can be deleted
    private $row_update; //!< Flag to indicate if rows can be updated

    const UPDATABLE=1;
    const VISIBLE=2;

    /**
     * @brief Constructor : set the label to the column name,
     * the order of the column , set the properties and the
     * permission for updating or deleting row
     */
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
        $this->object_name=uniqid("tbl");
        $this->row_delete=TRUE;
        $this->row_update=TRUE;
    }

    /**
     * @brief set the callback function that is passed to javascript
     * @param $p_file  : callback file by default ajax.php
     */
    function set_callback($p_file)
    {
        $this->callback=$p_file;
    }

    /**
     * @brief we must create first the javascript if we want to update or delete 
     * row. It is the default script . 
     */
    function create_js_script()
    {
        echo "
		<script>
		var {$this->object_name}=new ManageTable(\"{$this->table->tablename}\");
		{$this->object_name}.set_callback(\"{$this->callback}\");
		</script>

	";
    }

    /**
     * @brief set a column of the data row updatable or not
     * @param string $p_key data column
     * @param bool $p_value Boolean False or True
     */
    function set_property_updatable($p_key, $p_value)
    {
        if (!$this->a_prop[$p_key])
            throw new Exception(__FILE__.":".__LINE__."$p_key invalid index");
        if ($p_value==False)
            $this->a_prop[$p_key]=$this->a_prop[$p_key]-self::UPDATABLE;
        elseif ($p_value==True)
            $this->a_prop[$p_key]=$this->a_prop[$p_key]|self::UPDATABLE;
        else
            throw new Exception ("set_property_updatable [ $p_value ] incorrect");
    }

    /**
     * @brief return false if the update of the row is forbidden
     */
    function can_update_row()
    {

        return $this->row_update;
    }

    /**
     * @brief Enable or disable the updating of rows
     * @param $p_value Boolean : true enable the row to be updated 
     */
    function set_update_row($p_value)
    {
        if ($p_value!==True&&$p_value!==False)
            throw new Exception("Valeur invalide set_update_row [$p_value]");
        $this->row_update=$p_value;
    }

    /**
     * @brief return false if the delete of the row is forbidden
     */
    function can_delete_row()
    {
        return $this->row_delete;
    }

    /**
     * @brief Enable or disable the deleting of rows
     * @param $p_value Boolean : true enable the row to be deleted
     */
    function set_delete_row($p_value)
    {
        if ($p_value!==True&&$p_value!==False)
            throw new Exception("Valeur invalide set_delete_row [$p_value]");
        $this->row_delete=$p_value;
    }

    /**
     * @brief return True if the column is updatable otherwise false
     * @param $p_key data column
     */
    function get_property_updatable($p_key)
    {
        if ( $this->a_prop[$p_key] & self::UPDATABLE == 1) return true;
        return false;
    }

    /**
     * @brief set a column of the data row visible  or not
     * @param string $p_key data column
     * @param bool $p_value Boolean False or True
     */
    function set_property_visible($p_key, $p_value)
    {
        if (!$this->a_prop[$p_key])
            throw new Exception(__FILE__.":".__LINE__."$p_key invalid index");
        if ($p_value==False)
            $this->a_prop[$p_key]=$this->a_prop[$p_key]-self::VISIBLE;
        elseif ($p_value==True)
            $this->a_prop[$p_key]=$this->a_prop[$p_key]|self::VISIBLE;
        else
            throw new Exception ("set_property_updatable [ $p_value ] incorrect");
    }

    /**
     * @brief return True if the column is visible otherwise false
     * @param $p_key data column
     */
    function get_property_visible($p_key)
    {
        if ( $this->a_prop[$p_key] & self::VISIBLE == 1) return true;
        return false;
    }

    /**
     * @brief set the name to display for a column
     * @param string $p_key data column
     * @param string $p_display Label to display
     *
     */
    function set_col_label($p_key, $p_display)
    {
        $this->a_label_displaid[$p_key]=$p_display;
    }

    /**
     * @brief get the position of a column
     * @param $p_key data column
     */
    function get_current_pos($p_key)
    {
        $nb_order=count($this->a_order);
        for ($i=0; $i<$nb_order; $i++)
                if ($this->a_order[$i]==$p_key)
                return $i;
        throw new Exception("COL INVAL ".$p_key);
    }

    /** 	
     * @brief if we change a column order , the order
     * of the other columns is impacted.
     *
     * With a_order[0,1,2,3]=[x,y,z,a]
     * if we move the column x (idx=0) to 2	
     * we must obtain [y,z,x,a]
     * @param string $p_key data column
     * @param integer $p_idx new location
     */
    function move($p_key, $p_idx)
    {
        // get current position of p_key
        $cur_pos=$this->get_current_pos($p_key);

        if ($cur_pos==$p_idx)
            return;

        if ($cur_pos<$p_idx)
        {
            $nb_order=count($this->a_order);
            for ($i=0; $i<$nb_order; $i++)
            {
                // if col_name is not the searched one we continue		
                if ($this->a_order[$i]!=$p_key)
                    continue;
                if ($p_idx==$i)
                    continue;
                // otherwise we swap with i+1
                $old=$this->a_order[$i+1];
                $this->a_order[$i]=$this->a_order[$i+1];
                $this->a_order[$i+1]=$p_key;
            }
        } else
        {

            $nb_order=count($this->a_order)-1;
            for ($i=$nb_order; $i>0; $i--)
            {
                // if col_name is not the searched one we continue		
                if ($this->a_order[$i]!=$p_key)
                    continue;
                if ($p_idx==$i)
                    continue;
                // otherwise we swap with i+1
                $old=$this->a_order[$i-1];
                $this->a_order[$i]=$this->a_order[$i-1];
                $this->a_order[$i-1]=$p_key;
            }
        }
    }

    /**
     * @brief display the data of the table
     */
    function display_table()
    {
        $ret=$this->table->seek();
        $nb=Database::num_count($ret);
        printf('<table id="tb%s">', $this->object_name);
        for ($i=0; $i<$nb; $i++)
        {
            if ($i==0)
            {
                $this->display_table_header();
            }
            $row=Database::fetch_array($ret, $i);
            $this->display_row($row);
        }
    }

    /**
     * @brief display the column header excepted the not visible one
     * and in the order defined with $this->a_order
     */
    function display_table_header()
    {
        $nb=count($this->a_order);
        echo "<tr>";

        for ($i=0; $i<$nb; $i++)
        {

            $key=$this->a_order[$i];
            if ($this->get_property_visible($key))
                echo th($this->a_label_displaid[$key]);
        }
        echo "</tr>";
    }

    /**
     * @brief set the id value of a data row
     */
    function set_pk($p_id)
    {
        $this->table->set_pk_value($p_id);
    }

    /**
     * @brief get the data from http request
     */
    function from_request()
    {
        $nb=count($this->a_order);
        for ($i=0; $i<$nb; $i++)
        {
            $v=HtmlInput::default_value_request($this->a_order[$i], "");
            $key=$this->a_order[$i];
            $this->table->$key=$v;
        }
    }

    /**
     * @brief display a data row in the table, with the order defined
     * in a_order and depending of the visibility of the column
     * @see display_table
     */
    private function display_row($p_row)
    {

        printf('<tr id="%s_%s">', $this->object_name,
                $p_row[$this->table->primary_key])
        ;

        $nb_order=count($this->a_order);
        for ($i=0; $i<$nb_order; $i++)
        {
            $v=$this->a_order($i);
            if ($this->get_property_visible($v))
                echo td($p_row[$v]);
        }
        echo "<td>";
        if ($this->can_update_row())
        {
            $js=printf("ManageTable.input('%s','%s');",
                    $p_row[$this->table->primary_key], $this->object_name
            );
        }
        echo "</td>";
        echo "<td>";
        if ($this->can_delete_row())
        {
            $js=printf("ManageTable.delete('%s','%s');",
                    $p_row[$this->table->primary_key], $this->object_name
            );
        }
        echo "</td>";
        echo '</tr>';
    }

    /**
     * @brief display into a dialog box the datarow in order 
     * to be appended or modified
     */
    function input()
    {
        $nb_order=count($this->a_order);
        echo "<table>";
        echo "<tr>";
        for ($i=0; $i<$nb_order; $i++)
        {
            $key=$this->a_order[$i];
            $label=$this->a_label_displaid[$key];
            $value=$this->table->get($key);

            // Label
            echo "<td> {$label} </td>";
            printf('<input type="text" label="%s" value="%s" name="%s" id="%s">',
                    $label, $value, $key, $key
            );
        }
        echo "</tr>";
        echo "</table>";
    }

    /**
     * @brief delete a datarow , the id must be have set before 
     * @see from_request
     */
    function delete()
    {
        $this->table->delete();
    }

    /**
     * @brief save the Noalyss_SQL Object
     * The noalyss_SQL is not empty
     * @see from_request
     */
    function save()
    {
        $this->table->save();
    }

    /**
     * @brief insert a new value
     * @see set_pk_value
     * @see from_request
     */
    function insert()
    {
        $this->table->insert();
    }

    /**
     * @brief
     * @see set_pk_value
     * @see from_request
     */
    function update()
    {
        $this->table->update();
    }

    /**
     * @brief
     * @see set_pk_value
     * @see from_request
     */
    function set_value($p_key, $p_value)
    {
        $this->table->set($p_key, $p_value);
    }

}
