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


function PcmnUpdate(p_value,p_lib,p_parent,p_type,p_dossier)
{
    $('p_valu').value=p_value;
    $('p_oldu').value=p_value;
    $('p_libu').value=p_lib;
    $('p_parentu').value=p_parent;
    $('p_typeu').value=p_type;
    $('acc_update').style.top=posY+offsetY;
    $('acc_update').style.left=posX+offsetX;
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
    showIPopup(obj.ipopup);
    var dossier=$('gDossier').value;

    var queryString="?gDossier="+dossier;

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

        queryString+="&ctl="+obj.ipopup;
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
        alert(e.getMessage());
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
    var queryString="?gDossier="+dossier;

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
        $(name_ctl).innerHTML=code_html;
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
