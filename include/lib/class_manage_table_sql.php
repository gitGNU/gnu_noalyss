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
///@file
///@brief Definition Manage_Table_SQL

/**
 * @brief Purpose is to propose a librairy to display a table content
 * and allow to update and delete row , handle also the ajax call 
 * thanks the script managetable.js

 * 
 * Code for ajax , here we see the ajax_input for creating a dg box 
  @code
  $objet->set_pk($p_id);
  $objet->set_object_name($objet_name);

  // Set the ajax to call
  $objet->set_callback("ajax.php");

  // Build the json object for JS
  $plugin_code=HtmlInput::default_value_request("plugin_code","");
  $ac=HtmlInput::default_value_request("ac","");
  $sa=HtmlInput::default_value_request("sa","");
  $aJson=array("gDossier"=>Dossier::id(),
  "ac"=>$ac,
  "plugin_code"=>$plugin_code,
  "sa"=>$sa,
  "sb"=>$sb
  );
  $json=json_encode($aJson);
  $objet->param_set($json);

  // Display the box
  $xml=$objet->ajax_input();
  @endcode
 * @see ManageTable.js
 */
class Manage_Table_SQL
{

    private $table; //!< Object Noalyss_SQL
    private $a_label_displaid; //!< Label of the col. of the datarow
    private $a_order; //!< order of the col
    private $a_prop; //!< property for each col.
    private $a_type; //!< Type of the column : date , select ... Only in input
    private $a_select; //!< Possible value if a_type is a SELECT
    private $object_name; //!< Object_name is used for the javascript
    private $row_delete; //!< Flag to indicate if rows can be deleted
    private $row_update; //!< Flag to indicate if rows can be updated
    private $row_append; //!< Flag to indicate if rows can be added
    private $json_parameter; //!< Default parameter to add (gDossier...)

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
            $this->a_type[$value]=$this->table->type[$value];
            $this->a_select[$value]=null;
            $order++;
        }
        $this->object_name=uniqid("tbl");
        $this->row_delete=TRUE;
        $this->row_update=TRUE;
        $this->row_append=TRUE;
        $this->callback="ajax.php";
        $this->json=json_encode(array("gDossier"=>Dossier::id(),
            "op"=>"managetable"));
    }

    /**
     * @brief set the type of a column , it will change in the input db box , the
     * select must supply an array of possible values [val=> , label=>] with
     * the variable $this->key_name->a_value
     * @param $p_key col name
     * @param $p_type is SELECT NUMERIC TEXT or DATE 
     * @param $p_array if type is  SELECT an array is expected
     */
    function set_col_type($p_key, $p_value, $p_array=NULL)
    {
        if (!isset($this->a_type[$p_key]))
            throw new Exception("invalid key $p_key");

        if (!in_array($p_value,
                        array("text", "numeric", "date", "select", "timestamp")))
            throw new Exception("invalid type $p_value");

        $this->a_type[$p_key]=$p_value;
        $this->a_select[$p_key]=$p_array;
    }
    /**
     * @brief return the type of a column 
     * @param $p_key col name
     * @see set_col_type
     */
    function get_col_type($p_key)
    {
        if (!isset($this->a_type[$p_key]))
            throw new Exception("invalid key");

        return $this->a_type[$p_key];
    }

    /**
     * Get the object name
     * @details : return the object name , it is useful it
     * the javascript will return coded without the create_js_script function
     * @see create_js_script
     */
    function get_js_variable()
    {
        return $this->object_name;
    }

    /**
     * Set the parameter of the object (gDossier, ac, plugin_code...)
     * @detail By default , only gDossier will be set . The default value
     * is given in the constructor
     * @param string with json format $p_json 
     * 
     */
    function param_set($p_json)
    {
        $this->json_parameter=$p_json;
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
     * @brief we must create first the javascript if we want to update, insert 
     * or delete  rows. It is the default script . 
     */
    function create_js_script()
    {
        echo "
		<script>
		var {$this->object_name}=new ManageTable(\"{$this->table->table}\");
		{$this->object_name}.set_callback(\"{$this->callback}\");
		{$this->object_name}.param_add({$this->json_parameter});
		</script>

	";
    }

    /**
     * Set the object_name 
     * @param string $p_object_name name of the JS var, used in ajax response
     */
    function set_object_name($p_object_name)
    {
        $this->object_name=$p_object_name;
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
            throw new Exception("set_property_updatable [ $p_value ] incorrect");
    }

    /**
     * @brief return false if the update of the row is forbidden
     */
    function can_update_row()
    {

        return $this->row_update;
    }

    /**
     * @brief return false if the append of the row is forbidden
     */
    function can_append_row()
    {

        return $this->row_append;
    }

    /**
     * @brief Enable or disable the deletion of rows
     * @param $p_value Boolean : true enable the row to be deleted
     */
    function set_delete_row($p_value)
    {
        if ($p_value!==True&&$p_value!==False)
            throw new Exception("Valeur invalide set_delete_row [$p_value]");
        $this->row_delete=$p_value;
    }

    /**
     * @brief Enable or disable the appending of rows
     * @param $p_value Boolean : true enable the row to be appended
     */
    function set_append_row($p_value)
    {
        if ($p_value!==True&&$p_value!==False)
            throw new Exception("Valeur invalide set_append_row [$p_value]");
        $this->row_append=$p_value;
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
     * @brief return True if the column is updatable otherwise false
     * @param $p_key data column
     */
    function get_property_updatable($p_key)
    {
        $val=$this->a_prop[$p_key]&self::UPDATABLE;
        if ($val==self::UPDATABLE)
            return true;
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
            throw new Exception("set_property_updatable [ $p_value ] incorrect");
    }

    /**
     * @brief return True if the column is visible otherwise false
     * @param $p_key data column
     */
    function get_property_visible($p_key)
    {
        $val=$this->a_prop[$p_key]&self::VISIBLE;
        if ($val===self::VISIBLE)
            return true;
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
        $ret=$this->table->seek("order by ".$this->table->primary_key);
        $nb=Database::num_row($ret);
        if ($this->can_append_row()==TRUE)
        {
            echo HtmlInput::button_action(_("Ajout"),
                    sprintf("%s.input('-1','%s')", $this->object_name,
                            $this->object_name));
        }
        $nb_order=count($this->a_order);
        $virg=""; $result="";
        for ($e=0; $e<$nb_order; $e++)
        {
            if ($this->get_property_visible($this->a_order[$e])==TRUE)
            {
                $result.=$virg."$e";
                $virg=",";
            }
        }
        echo HtmlInput::filter_table("tb".$this->object_name, $result, 1);
        printf('<table class="result" id="tb%s">', $this->object_name);
        for ($i=0; $i<$nb; $i++)
        {
            if ($i==0)
            {
                $this->display_table_header();
            }
            $row=Database::fetch_array($ret, $i);
            $this->display_row($row);
        }
        echo "</table>";
        if ($this->can_append_row()==TRUE)
        {
            echo HtmlInput::button_action(_("Ajout"),
                    sprintf("%s.input('-1','%s')", $this->object_name,
                            $this->object_name));
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

            if ($this->get_property_visible($key)==true)
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
        $this->table->load();
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
            $this->table->$key=strip_tags($v);
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
            $v=$this->a_order[$i];
            if ($this->get_property_visible($v))
                echo td($p_row[$v]);
        }
        echo "<td>";
        if ($this->can_update_row())
        {
            $js=sprintf("onclick=\"%s.input('%s','%s');\"", $this->object_name,
                    $p_row[$this->table->primary_key], $this->object_name
            );
            echo HtmlInput::anchor(_("Modifier"), "", $js);
        }
        echo "</td>";
        echo "<td>";
        if ($this->can_delete_row())
        {
            $js=sprintf("onclick=\"%s.delete('%s','%s');\"", $this->object_name,
                    $p_row[$this->table->primary_key], $this->object_name
            );
            echo HtmlInput::anchor(_("Effacer"), "", $js);
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
        for ($i=0; $i<$nb_order; $i++)
        {
            echo "<tr>";
            $key=$this->a_order[$i];
            $label=$this->a_label_displaid[$key];
            $value=$this->table->get($key);

            if ($this->get_property_visible($key)===TRUE)
            {
                // Label
                echo "<td> {$label} </td>";

                if ($this->get_property_updatable($key)==TRUE)
                {
                    echo "<td>";
                    if ($this->a_type[$key]=="select")
                    {
                        $select = new ISelect($key);
                        $select->value=$this->a_select[$key];
                        $select->selected=$value;
                        echo $select->input();
                    }
                    else
                    {
                        $text=new IText($key);
                        $text->value=$value;
                        $min_size=(strlen($value)<30)?30:strlen($value)+5;
                        $text->size=$min_size;
                        echo $text->input();
                    /*    printf('<input class="input_text" type="text" label="%s" value="%s" name="%s" id="%s">',
                                $label, $value, $key, $key
                        );*/
                    }
                    echo "</td>";
                }
                else
                {
                    printf('<td>%s %s</td>', h($value),
                            HtmlInput::hidden($key, $value)
                    );
                }
            }
            echo "</tr>";
        }
        echo "</table>";
    }

    /**
     * @brief Save the record from Request into the DB and returns an XML
     * to update the Html Element
     * @return \DOMDocument
     */
    function ajax_save()
    {

        $status="NOK";
        $xml=new DOMDocument('1.0', "UTF-8");
        try
        {
            // fill up object with $_REQUEST
            $this->from_request();
            // save the object
            $this->save();
            // compose the answer
            $status="OK";
            $s1=$xml->createElement("status", $status);
            $ctl=$this->object_name."_".$this->table->get_pk_value();
            $s2=$xml->createElement("ctl_row", $ctl);
            $s4=$xml->createElement("ctl", $this->object_name);
            ob_start();
            $this->table->load();
            $array=$this->table->to_array();
            $this->display_row($array);
            $html=ob_get_contents();
            ob_end_clean();
            $s3=$xml->createElement("html" );
            $t1=$xml->createTextNode($html);
            $s3->appendChild($t1);


            $root=$xml->createElement("data");
            $root->appendChild($s1);
            $root->appendChild($s2);
            $root->appendChild($s3);
            $root->appendChild($s4);
        }
        catch (Exception $ex)
        {
            $s1=$xml->createElement("status", "NOK");
            $s2=$xml->createElement("ctl_row",
            $this->object_name+"_"+$this->table->get_pk_value());
            $s4=$xml->createElement("ctl", $this->object_name);
            $s3=$xml->createElement("html", $ex->getTraceAsString());
            $root=$xml->createElement("data");
            $root->appendChild($s1);
            $root->appendChild($s2);
            $root->appendChild($s3);
            $root->appendChild($s4);
        }
        $xml->appendChild($root);
        return $xml;
    }

    /**
     * @brief send an xml with input of the object, create an xml answer.
     * XML Tag 
     *   - status  : OK , NOK 
     *   - ctl     : Dom id to update 
     *   - content : Html answer
     * @return DomDocument
     */
    function ajax_input()
    {
        $xml=new DOMDocument("1.0", "UTF-8");
        $xml->createElement("status", "OK");
        try
        {
            $status="OK";
            ob_start();
		
            echo HtmlInput::title_box("Donnée", "dtr");
            printf('<form id="frm%s_%s" method="POST" onsubmit="%s.save(\'frm%s_%s\');return false;">',
                    $this->object_name, $this->table->get_pk_value(),
                    $this->object_name, $this->object_name,
                    $this->table->get_pk_value());
            $this->input();
            // JSON param to hidden
            echo HtmlInput::json_to_hidden($this->json_parameter);
            echo HtmlInput::hidden("p_id", $this->table->get_pk_value());
            // button Submit and cancel
            $close=sprintf("\$('%s').remove()", "dtr");
            echo '<ul class="aligned-block">',
            '<li>',
            HtmlInput::submit('update', _("OK")),
            '</li>',
            '<li>',
            HtmlInput::button_action(_("Cancel"), $close,"","smallbutton"),
            '</li>',
            '</ul>';
            echo "</form>";

            $html=ob_get_contents();
            ob_end_clean();

            $s1=$xml->createElement("status", $status);
            $ctl=$this->object_name."_".$this->table->get_pk_value();
            $s2=$xml->createElement("ctl_row", $ctl);
            $s4=$xml->createElement("ctl", $this->object_name);
            $s3=$xml->createElement("html" );
            $t1=$xml->createTextNode($html);
            $s3->appendChild($t1);

            $root=$xml->createElement("data");
            $root->appendChild($s1);
            $root->appendChild($s2);
            $root->appendChild($s3);
            $root->appendChild($s4);
        }
        catch (Exception $ex)
        {
            $s1=$xml->createElement("status", "NOK");
            $s2=$xml->createElement("ctl", $this->object_name);
            $s2=$xml->createElement("ctl_row",
                    $this->object_name+"_"+$this->table->get_pk_value());
            $s3=$xml->createElement("html", $ex->getTraceAsString());
            $root=$xml->createElement("data");
            $root->appendChild($s1);
            $root->appendChild($s2);
            $root->appendChild($s3);
            $root->appendChild($s4);
        }
        $xml->appendChild($root);
        return $xml;
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
     * Delete a record and return an XML answer for ajax
     * @return \DOMDocument
     */
    function ajax_delete()
    {
        $status="NOK";
        $xml=new DOMDocument('1.0', "UTF-8");
        try
        {
            $this->table->delete();
            $status="OK";
            $s1=$xml->createElement("status", $status);
            $ctl=$this->object_name."_".$this->table->get_pk_value();
            $s2=$xml->createElement("ctl_row", $ctl);
            $s3=$xml->createElement("html", _("Effacé"));
            $s4=$xml->createElement("ctl", $this->object_name);

            $root=$xml->createElement("data");
            $root->appendChild($s1);
            $root->appendChild($s2);
            $root->appendChild($s3);
            $root->appendChild($s4);
        }
        catch (Exception $ex)
        {
            $s1=$xml->createElement("status", "NOK");
            $s2=$xml->createElement("ctl",
                    $this->object_name."_".$this->table->get_pk_value());
            $s3=$xml->createElement("html", $ex->getTraceAsString());
            $s4=$xml->createElement("ctl", $this->object_name);

            $root=$xml->createElement("data");
            $root->appendChild($s1);
            $root->appendChild($s2);
            $root->appendChild($s3);
            $root->appendChild($s4);
        }
        $xml->appendChild($root);
        return $xml;
    }

    /**
     * @brief save the Noalyss_SQL Object
     * The noalyss_SQL is not empty
     * @see from_request
     */
    function save()
    {
        if ($this->table->exist()==0)
        {
            $this->table->insert();
        }
        else
        {
            $this->table->update();
        }
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
