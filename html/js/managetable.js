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

/**
 *@file
 *@brief Javascript object to manage ajax calls to 
 * save , input or delete data row. 
 *@details The callback must
 respond with a XML file , the tag status for the result
 and data the HTML code to display
 *@param p_table_name the data table on which we're working
 in javascript , to create an object to manipulate the table
 version.
 
 Example of code
 @code
 // Version will manage the table "version"
 var version=new ManageTable("version");
 
 // the file ajax_my.php will be called
 version.set_callback("ajax_my.php");
 
 // Add supplemental parameter to this file
 version.param_add ({"plugin_code":"OIC"};
 
 // Set the id of dialog box , table and tr prefix
 version.set_control("dialbox1");
 
 @endcode
 
 
 The answer from ajax must be like this template
 @verbatim
 <xml>
 <ctl> id of the control to update for diag.box, row </ctl>
 <status> OK , FAIL ...</status>
 <html> Html to display</html>
 </xml>
 @endverbatim
 
 List of function
 - set_control(p_ctl_name)
 - set_callback (p_new_callback)
 - param_add (json object)
 - parseXML (private function)
 - save
 - delete
 - input
 
 */

var ManageTable = function (p_table_name)
{
    this.callback = "ajax.php"; //!< File to call
    this.control = "dtr"; //<! Prefix Id of dialog box, table, row
    this.param = {"table": p_table_name, "ctl_id": this.control}; //<! default value to pass
    var answer = {};
    /**
     *@fn ManageTable.set_control 
     *@brief Set the id of the control name , used as 
     * prefix for dialog box , table id and row
     *@param string p_ctl_name id of dialog box
     */
    this.set_control = function (p_ctl_name) {
        this.control = p_ctl_name;
    };
    /**
     *@brief set the name of the callback file to 
     * call by default it is ajax.php
     */
    this.set_callback = function (p_new_callback) {
        this.callback = p_new_callback;
    };
    /**
     *@brief By default send the json param variable
     * you can add a json object to it in order to 
     * send it to the callback function
     */
    this.param_add = function (p_obj) {
        var result = {};
        for (var key in this.param) {
            result[key] = this.param[key];
        }
        for (var key in p_obj) {
            result[key] = p_obj[key];
        }
        this.param = result;
        return this.param;
    };
    /**
     @brief receive answer from ajax and fill up the 
     private object "answer"
     @param req Ajax answer
     */
    var parseXML = function (req) {
        var xml = req.responseXML;
        var status = getElementsByTagName("status");
        var ctl = xml.getElementsByTagName("ctl");
        var html = xml.getElementsByTagName("html");
        if (status.length == 0 || ctl.length == 0 || html.length == 0)
        {
            throw "Invalid answer " + req.responseText;

        }
        answer['status'] = getNodeText(status[0]);
        answer['ctl'] = getNodeText(ctl[0]);
        answer['html'] = getNodeText(html[0]);

    };
    /**
     *@brief call the ajax with the action save 
     *@details update or append
     * As a hidden parameter the Manage_Table:object_name must be
     * set
     */
    this.save = function (form_id) {
        waiting_box();
        var form = $F(form_id);
        this.param_add(form);
        new Ajax.Request(this.callback, {
            parameters: this.param,
            method: "post",
            onSuccess: function (req) {
                /// Display the result of the update
                /// or add , the name of the row in the table has the
                /// if p_ctl_row does not exist it means it is a new
                /// row , otherwise an update
                parseXML(req);
                if (answer ['status'] == 'OK') {
                    if ($(answer['ctl'])) {
                        $(answer['ctl']).update(answer['html']);
                    } else {
                        var new_row = new Element("tr");
                        new_row.id = answer['ctl'];
                        new_row.innerHTML = answer['html'];
                        $("tb" + this.control).appendChild(new_row);
                    }
                } else {
                    console.error("Error in save");
                    throw "error in save";
                }


            }


        })
    };
    /**
     *@brief call the ajax with action delete
     *@param id (pk) of the data row
     */
    this.delete = function (p_id, p_ctl_row) {
        this.param['p_id'] = p_id;
        this.param['action'] = 'delete';

        new Ajax.Request(this.callback, {
            parameters: this.parm,
            method: "get",
            onSuccess: function (req) {
                parseXML(req);
                if (answer['status'] == 'OK') {
                    $(answer['ctl']).hide();
                }
            }

        });
    }
    /**
     *@brief display a dialog box with the information
     * of the data row
     *@param id (pk) of the data row
     */
    this.input = function (p_id, p_ctl_row) {
        waiting_box();
        this.param['p_id'] = p_id;
        this.param['action'] = 'input';
        this.param['ctl_row'] = p_ctl_row;
        // display the form to enter data
        new Ajax.Request(this.callback, {
            parameters: this.param,
            method: "get",
            onSuccess: function (req) {
                remove_waiting_box();
                var obj = {"id": this.control, "cssclass": "inner_box", "html": loading()};
                create_div(obj);
                var pos = calcy(250);
                $(this.control).setStyle({top: pos + 'px'});
                obj.update(req.responseText);

            }
        });
    };


}

