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
 * \brief this file contains all the javascript needed by the todo_list. 
 *      it requires prototype.js. The calling page must have the phpsessid and
 *      the gDossier
 * 
 */
function todo_list_show(p_id) {
    var gDossier=$('gDossier').value;
    var phpsessid=$('phpsessid').value;

    var action=new Ajax.Request(
	'todo_list.php',
	{
	    method:'get',
	    parameters:{'show':1,'id':p_id,'PHPSESSID':phpsessid,'gDossier':gDossier},
	    onFailure:todo_list_show_error,
	    onSuccess:todo_list_show_success
	}
    );
    return false;
}
function todo_list_show_success(request,json) {
    var answer = request.responseText.evalJSON(true);

    $('p_title').value=answer.tl_title;

    $('p_date').value=answer.tl_date;
    $('p_desc').value=answer.tl_desc;
    $('tl_id').value=answer.tl_id;
    $('add_todo_list').show();
    $('add').hide();
}
function todo_list_show_error(request_json) {
    alert ('failure');
}
function add_todo() {
    $('add_todo_list').show()
    $('add').hide();
    $('p_title').value='';

    $('p_date').value='';
    $('p_desc').value='';
    $('tl_id').value=0;
}
function todo_list_remove(p_ctl) {
    $("tr"+p_ctl).hide();
    var gDossier=$('gDossier').value;
    var phpsessid=$('phpsessid').value;

    var action=new Ajax.Request(
	'todo_list.php',
	{
	    method:'get',
	    parameters:{'del':1,'id':p_ctl,'PHPSESSID':phpsessid,'gDossier':gDossier}
	}
    );
    return false;

}