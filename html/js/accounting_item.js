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
/* $Revision$ */

// Copyright Author Dany De Bontridder danydb@aevalys.eu

/*! \file
 * \brief
 * containing the javascript for opening a windows to search an account (poste comptable)
 */

function set_poste_parent(p_ctl,p_value)
{
    var f=g(p_ctl);
    f.value+='['+p_value+']';
}

function set_jrn_parent(p_ctl,p_value)
{
    var f=g(p_ctl);
    if ( f )
    {
        if ( trim(f.value)!="") f.value+=' ';
        f.value+=p_value;
    }
}
/**
 * Display a box with accounting detail for update, delete or add
 * @param {type} p_value pcmn_val
 * @param {type} p_lib pcmn_lib
 * @param {type} p_parent pcmn_val_parent
 * @param {type} p_type pcmn_val_type
 * @param {type} p_dossier gDossier
 * @param {type} p_top position of the box
 * @param {type} p_action = new | update | delete
 * @returns {undefined}
 */

function PcmnUpdate(p_value,p_lib,p_parent,p_type,p_dossier,p_top,p_action)
{
    $('p_valu').value=p_value;
    $('p_oldu').value=p_value;
    $('p_libu').value=p_lib;
    $('p_parentu').value=p_parent;
    $('p_typeu').value=p_type;
    var i=0;
    for (i=0;i < $('p_typeu').options.length;i++) {
        if ($('p_typeu').options[i].value== p_type) {$('p_typeu').options.selectedIndex= i; break; }
    }
    $('p_typeu').options.selectedIndex=p_type;
    $('acc_update').style.top=(posY+offsetY+p_top)+"px";
    $('acc_update').style.left="10%";
    $('acc_update').style.width="80%";
    $('acc_update_info').innerHTML="";
    $('p_action').value=p_action;
    $('delete_acc').checked=false;
    $('acc_update').show();
}
/**
 *@brief show the popup for search an accounting item
 *@param object this, it must contains some attribute as
 * - jrn if set and different to 0, will filter the accounting item for a
 *   ledger
 * - account the tag which will contains the  number
 * - label the tag which will contains the label
 * - bracket if the value must be surrounded by [ ]
 * - acc_query for the initial query
 *\see ajax_poste.php
 */
function search_poste(obj)
{
	var sx=0;
	if ( window.scrollY)
	{
            sx=window.scrollY+40;
	}
	else
	{
            sx=document.body.scrollTop+60;
	}

	var div_style="top:"+sx+"px";
	removeDiv('search_account');
	add_div({id:'search_account',cssclass:'inner_box',html:loading(),style:div_style,drag:true});

    var dossier=$('gDossier').value;

    var queryString="gDossier="+dossier;

    queryString+="&op=sf";
    try
    {
        if ( obj.jrn)
        {
            queryString+="&j="+obj.jrn;
        }
        if ( obj.account)
        {
            queryString+="&c="+obj.account;
        } 
        if ( obj.label)
        {
            queryString+="&l="+obj.label;
        }
        if ( obj.bracket)
        {
            queryString+="&b="+obj.bracket;
        }
        if( obj.noquery)
        {
            queryString+="&nq";
        }
        if( obj.no_overwrite)
        {
            queryString+="&nover";
        }
        if( obj.bracket)
        {
            queryString+="&bracket";
        }
        if ( ! obj.noquery)
        {
            if( obj.acc_query)
            {
                queryString+="&q="+obj.acc_query;
            }
            else
            {
                if ($(obj).account)
                {
                    var e=$(obj).account;
                    var str_account=$(e).value;
                    queryString+="&q="+str_account;
                }
            }
        }

        queryString+="&ctl="+'search_account';
        queryString=encodeURI(queryString);
        var action=new Ajax.Request ( 'ajax_poste.php',
                                      {
                                  method:'get',
                                  parameters:queryString,
                                  onFailure:errorPoste,
                                  onSuccess:result_poste_search
                                      }
                                    );
    }
    catch (e)
    {
        alert(e.getMessage);
    }
}
/**
 *@brief when you submit the form for searching a accounting item
 *@param obj form
 *@note the same as search_poste, except it answer to a FORM and not
 * to a click event
 */
function search_get_poste(obj)
{
    var dossier=$('gDossier').value;
    var queryString="gDossier="+dossier;

    queryString+="&op=sf";

    if ( obj.elements['jrn'] )
    {
        queryString+="&j="+$F('jrn');
    }
    if ( obj.elements['account'])
    {
        queryString+="&c="+$F('account');
    }
    if ( obj.elements['label'])
    {
        queryString+="&l="+$F('label');
    }
    if( obj.elements['acc_query'])
    {
        queryString+="&q="+$F('acc_query');
    }
    if (obj.ctl )
    {
        queryString+="&ctl="+obj.ctl;
    }
    if( obj.elements['nosearch'])
    {
        queryString+="&nq";
    }
    if( obj.elements['nover'])
    {
        queryString+="&nover";
    }
    if( obj.elements['bracket'])
    {
        queryString+="&bracket";
    }

    $('asearch').innerHTML=loading();
    var action=new Ajax.Request ( 'ajax_poste.php',
                                  {
                                  method:'get',
                                  parameters:queryString,
                                  onFailure:errorPoste,
                                  onSuccess:result_poste_search
                                  }
                                );
}

/**
 *@brief show the answer of ajax request
 *@param  answer in XML
 */
function result_poste_search(req)
{
    try
    {
        var answer=req.responseXML;
        var a=answer.getElementsByTagName('ctl');
        if ( a.length == 0 )
        {
            var rec=req.responseText;
            alert ('erreur :'+rec);
        }
        var html=answer.getElementsByTagName('code');

        var name_ctl=a[0].firstChild.nodeValue;
        var nodeXml=html[0];
        var code_html=getNodeText(nodeXml);
        code_html=unescape_xml(code_html);
        $('search_account').innerHTML=code_html;
    }
    catch (e)
    {
        alert(e.message);
    }
    try
    {
        code_html.evalScripts();
    }
    catch(e)
    {
        alert("Impossible executer script de la reponse\n"+e.message);
    }

}
/**
*@brief error for ajax
*/
function errorPoste()
{
    alert('Ajax failed');
}
function pausecomp(millis)
 {
  var date = new Date();
  var curDate = null;
  do { curDate = new Date(); }
  while(curDate-date < millis);
}
/**
 * Update an accounting with the information in the form, called frmo
 * param_pcmn.inc.php
 * @param {type} p_obj_id id of the form
 * @returns false
 */
function account_update(p_obj_id)
{
    try {
        waiting_box();
        // initialize variables
        var gDossier=0;
        var p_action="";
        var p_oldu=-1;
        var p_valu="";
        var p_libu="";
        var p_parentu="";
        var form=$('acc_update_frm_id');
        var notfound="not found:";
        var p_typeu=-1;
        // get them
        if ( form['gDossier']) { gDossier=form['gDossier'].value;}else { notfound+='gDossier';} 
        if ( form['p_action']) { action=form['p_action'].value;}else { notfound+=', p_action ';}
        if ( form['p_oldu']) { p_oldu=form['p_oldu'].value;}else { notfound+=', p_oldu';}
        if ( form['p_valu']) { p_valu=form['p_valu'].value;}else { notfound+=', p_valu';}
        if ( form['p_libu']) { p_libu=form['p_libu'].value;}else { notfound+=', p_libu ';}
        if ( form['p_parentu']) { p_parentu=form['p_parentu'].value;}else { notfound+='p_parentu';}
        if ( form['delete_acc'])  { 
                if (form['delete_acc'].checked) { acc_delete=1;} else {acc_delete=0} }
            else {
                notfound += ', delete_acc';
            }
        if ( form['p_typeu']) { p_typeu=form['p_typeu'].value;} else { notfound+=", p_typeu";}
        
        
        if ( notfound != "not found:") throw notfound;
            
        var queryString = "op=account_update" + "&gDossier=" + gDossier+ "&action=" + action + "&p_oldu=" + p_oldu+"&p_valu="+p_valu+"&p_libu="+p_libu+"&p_parentu="+p_parentu+"&acc_delete="+acc_delete+"&p_typeu="+p_typeu;
        var ajax_action = new Ajax.Request(
                "ajax_misc.php",
                {
                    method: 'get',
                    parameters: queryString,
                    onFailure: error_box,
                    onSuccess: function(req, json) {
                        try
                        {
                            var name_ctl = 'acc_update_info';
                            var answer = req.responseXML;
                            remove_waiting_box();
                            var html = answer.getElementsByTagName('code');
                            var ctl = answer.getElementsByTagName('ctl')[0].textContent;
                            if (html.length == 0) {
                                var rec = req.responseText;
                                alert('erreur :' + rec);
                            }
                            var code_html = getNodeText(html[0]); // Firefox ne prend que les 4096 car.
                            code_html = unescape_xml(code_html);
                            
                            $(name_ctl).innerHTML = code_html;
                            if ( ctl == 'ok') {
                               window.location.reload();
                            }
                        } catch (e)
                        {
                            error_message(e.message);
                            return false;
                        }
                    }
                }

        );
        
    }catch (e) {
        return false;
    }
    return false;
}
 