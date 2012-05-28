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
 * \brief javascript scripts for the gestion
 *
 */



/**
 *@brief remove an attached document of an action
 *@param dossier
 *@param dt_id id of the document (pk document:d_id)
*/
function remove_document(p_dossier,p_id)
{
    queryString="gDossier="+p_dossier+"&a=rm&d_id="+p_id;
    var action=new Ajax.Request (
                   "show_document.php",
                   {
                   method:'get',
                   parameters:queryString,
                   onFailure:errorRemoveDoc,
                   onSuccess:successRemoveDoc
                   }

               );

}
/**
 *@brief remove the concerned operation of an action
 *@param dossier
 *@param p_id id pk action_comment_operation
*/
function remove_operation(p_dossier,p_id)
{
    queryString="gDossier="+p_dossier+"&a=rmop&id="+p_id;
    var action=new Ajax.Request (
                   "show_document.php",
                   {
                   method:'get',
                   parameters:queryString,
                   onFailure:errorRemoveDoc,
                   onSuccess:successRemoveOp
                   }

               );

}
function successRemoveOp(request,json)
{
	try{
   var answer=request.responseText.evalJSON(true);
    var action="acop"+answer.ago_id;
    $(action).innerHTML="";
    var doc="op"+answer.ago_id;
    $(doc).style.color="red";
    $(doc).href="javascript:alert('Commentaire Effacé')";
    $(doc).style.textDecoration="line-through";
	}catch(e){alert(e.message);}
}
/**
 *@brief remove comment of an action
 *@param dossier
 *@param p_id pk action_gestion_comment
*/
function remove_comment(p_dossier,p_id)
{
    queryString="gDossier="+p_dossier+"&a=rmcomment&id="+p_id;
    var action=new Ajax.Request (
                   "show_document.php",
                   {
                   method:'get',
                   parameters:queryString,
                   onFailure:errorRemoveDoc,
                   onSuccess:successRemoveComment
                   }

               );

}
function successRemoveComment(request,json)
{
   var answer=request.responseText.evalJSON(true);
    var action="accom"+answer.agc_id;
    $(action).innerHTML="";
    var doc="com"+answer.agc_id;
    $(doc).style.color="red";
    $(doc).href="javascript:alert('Commentaire Effacé')";
    $(doc).style.textDecoration="line-through";

}
/**
 *@brief error if a document if removed
 */
function errorRemoveDoc()
{
    alert('Impossible d\'effacer ce document');
}
/**
 *@brief success when removing a document
 */
function successRemoveDoc(request,json)
{
    var answer=request.responseText.evalJSON(true);
    var action="ac"+answer.d_id;
    $(action).innerHTML="";
    var doc="doc"+answer.d_id;
    $(doc).style.color="red";
    $(doc).href="javascript:alert('Document Effacé')";
    $(doc).style.textDecoration="line-through";

}
/**
* @brief check the format of the hour
* @param p_ctl is the control where the hour is encoded
*/
function check_hour(p_ctl)
{
    try
    {
        var h=document.getElementById(p_ctl);
	var re = /^\d{1,2}:\d{2}$/;
        if ( trim(h.value) !='' && ! h.value.match(re))
            alert("Format de l'heure est HH:MM ")
        }
    catch (erreur)
    {
        alert('fct : check_hour '+erreur);
    }

}
/**
 *@brief remove an attached document of an action
 *@param dossier
 *@param dt_id id of the document (pk document:d_id)
*/

function removeStock(s_id,p_dossier)
{
    if ( ! confirm("Confirmez-vous l'effacement de cette entrée dans le stock?") )
    {
	return;
    }
    queryString="gDossier="+p_dossier+"&op=rm_stock&s_id="+s_id;
    var action=new Ajax.Request (
                   "ajax_misc.php",
                   {
                   method:'get',
                   parameters:queryString,
                   onFailure:errorRemoveStock,
                   onSuccess:successRemoveStock
                   }

               );

}
/**
 *@brief error if a document if removed
 */
function errorRemoveStock()
{
    alert('Impossible d\'effacer ');
}
/**
 *@brief success when removing a document
 */
function successRemoveStock(request,json)
{
    try
    {
	var answer=request.responseText.evalJSON(true);
	var doc="stock"+answer.d_id;
	var href="href"+answer.d_id;
	$(href).innerHTML='';

	$(doc).style.color="red";
	//    $(doc).href="javascript:alert('Stock Effacé')";
	$(doc).style.textDecoration="line-through";
    } catch (e)
    {
	alert("success_box"+e.message);
    }
}
