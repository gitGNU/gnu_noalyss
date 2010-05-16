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
 *      it requires prototype.js. The calling page must have 
 *      the gDossier
 * 
 */
function todo_list_show(p_id) {
    var gDossier=$('gDossier').value;
   $('add_todo_list').style.top=posY+offsetY;
    $('add_todo_list').style.left=posX+offsetX;

    try {
    var action=new Ajax.Request(
	'todo_list.php',
	{
	    method:'get',
	    parameters:{'show':1,'id':p_id,'gDossier':gDossier},
	    onFailure:todo_list_show_error,
	    onSuccess:todo_list_show_success
	}
    );
    } catch (e) { 
	alert(" Envoi ajax non possible" + e.message);
    }
    return false;
}
function todo_list_show_success(req) {
    try {
	var answer=req.responseXML;
	var tl_id=answer.getElementsByTagName('tl_id');
	var tl_title=answer.getElementsByTagName('tl_title');
	var tl_desc=answer.getElementsByTagName('tl_desc');
	var tl_date=answer.getElementsByTagName('tl_date');
	
	if ( tl_id.length == 0 ) { var rec=req.responseText;alert ('erreur :'+rec);}

	$('p_title').value=getNodeText(tl_title[0]);
	$('p_date').value=getNodeText(tl_date[0]);
	$('p_desc').value=getNodeText(tl_desc[0]);
	$('tl_id').value=getNodeText(tl_id[0]);
	$('add_todo_list').show();
    } catch (e)  { alert(e.message);}
}
function todo_list_show_error(request_json) {
    alert ('failure');
}
function add_todo() {
    $('add_todo_list').style.top=posY+offsetY;
    $('add_todo_list').style.left=posX+offsetX;

    $('add_todo_list').show()
    $('p_title').value='';

    $('p_date').value='';
    $('p_desc').value='';
    $('tl_id').value=0;
}
function todo_list_remove(p_ctl) {
    if ( confirm('Effacer ?') == false ) {return;}
    $("tr"+p_ctl).hide();
    var gDossier=$('gDossier').value;

    var action=new Ajax.Request(
	'todo_list.php',
	{
	    method:'get',
	    parameters:{'del':1,'id':p_ctl,'gDossier':gDossier}
	}
    );
    return false;

}
