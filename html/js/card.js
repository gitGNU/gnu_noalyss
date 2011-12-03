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
 * \brief javascript for searching a card
 */

var card_layer=1;
/**
 *@brief show the ipopup with the form to search a card
 * the properties
 *  - jrn for the ledger
 *  - fs for the action
 *  - price for the price of the card (field to update)
 *  - tvaid for the tvaid of the card (field to update)
 *  - inp input text to update with the quickcode
 *  - label field to update with the name
 *  - ctl the id to fill with the HTML answer (ending with _content)
 */
function search_card(obj)
{
    try
    {
        var gDossier=$('gDossier').value;
        var inp=obj.inp;
        var string_to_search=$(inp).value;
        var label=obj.label;
        var typecard=obj.typecard;
        var price=obj.price;
        var tvaid=obj.tvaid;
        var jrn=obj.jrn;
        if ( jrn==undefined)
        {
            if ( g('p_jrn'))   {
		jrn=$('p_jrn').value;
	    }
            else 	    {
		jrn=-1;
	    }
        }
	var query=encodeJSON({'gDossier':gDossier,
                      'inp':inp,'label':label,'price':price,'tvaid':tvaid,
                      'ctl':'search_card','op':'fs','jrn':jrn,
                      'typecard':typecard,'query':string_to_search
                             });
	if (  $('search_card') ) {
	    removeDiv('search_card');
	}
	var sx=0;
	if ( window.scrollY)
	{
            sx=window.scrollY+40;
	}
	else
	{
            sx=document.body.scrollTop+60;
	}

	var div_style="top:"+sx+";width:60%;heigth:80%";
	add_div({id:'search_card',cssclass:'inner_box',html:loading(),style:div_style,drag:true});

        var action=new Ajax.Request ( 'ajax_card.php',
                                      {
                                  method:'get',
                                  parameters:query,
                                  onFailure:errorFid,
                                  onSuccess:result_card_search
                                      }
                                    );
    }
    catch(e)
    {
        alert('search_card failed'+e.message);
    }
}
/**
 *@brief when you submit the form for searching a card
 *@param obj form
 *@note the same as search_card, except it answer to a FORM and not
 * to a click event
 */
function search_get_card(obj)
{
    var dossier=$('gDossier').value;

    var queryString="gDossier="+dossier;
    queryString+="&op=fs";

    if ( obj.elements['inp'] )
    {
        queryString+="&inp="+$F('inp');
    }
    if ( obj.elements['typecard'] )
    {
        queryString+="&typecard="+$F('typecard');
    }
    if ( obj.elements['jrn'] )
    {
        queryString+="&jrn="+$F('jrn');
    }
    if ( obj.elements['label'])
    {
        queryString+="&label="+$F('label');
    }
    if ( obj.elements['price'])
    {
        queryString+="&price="+$F('price');
    }
    if ( obj.elements['tvaid'])
    {
        queryString+="&tvaid="+$F('tvaid');
    }
    if( obj.elements['query'])
    {
        queryString+="&query="+$F('query');
    }
    if (obj.ctl )
    {
        queryString+="&ctl="+obj.ctl;
    }
    $('asearch').innerHTML=loading();
    var action=new Ajax.Request ( 'ajax_card.php',
                                  {
                                  method:'get',
                                  parameters:queryString,
                                  onFailure:errorFid,
                                  onSuccess:result_card_search
                                  }
                                );
}
/**
 *@brief show the answer of ajax request
 *@param  answer in XML
 */
function result_card_search(req)
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
        $('search_card').innerHTML=code_html;
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



/*!\brief Set the value of 2 input fields
*
* Set the quick code in the first ctrl and the label of the quickcode in the second one. This function is a variant of SetData for
* some specific need.  This function is called if the caller is searchcardCtrl
*
*\param p_ctrl the input with the name of the quick code
*\param  p_quickcode the found quick_code
*\param p_ctrlname the name of the input field with the label
*\param p_label the label of the quickcode
*/
function setCtrl(p_ctrl,p_quickcode,p_ctrlname,p_label)
{
    var ctrl=g(p_ctrl);
    if ( ctrl )
    {
        ctrl.value=p_quickcode;
    }
    var ctrl_name=g(p_ctrlname);
    if ( ctrl_name )
    {
        ctrl_name.value=p_label;
    }
}



/*!\brief clean the row (the label, price and vat)
 * \param p_ctl the calling ctrl
 */
function clean_Fid(p_ctl)
{
    nSell=p_ctl+"_price";
    nBuy=p_ctl+"_price";
    nTva_id=p_ctl+"_tva_id";
    if ( $(nSell) )
    {
        $(nSell).value="";
    }
    if ( $(nBuy) )
    {
        $(nBuy).value="";
    }
    if ( $(nTva_id) )
    {
        $(nTva_id).value="-1";
    }

}
function errorFid(request,json)
{
    alert('erreur : ajax fiche');
}
function update_value(text,li)
{
	   ajaxFid(ctl);
}
/**
 *@brief is called when something change in ICard
 *@param the input field
 *@see ICard
 */
function fill_data_onchange(ctl)
{
    ajaxFid(ctl);

}
/**
 *@brief is called when something change in ICard
 *@param the input field
 *@see ICard
 */
function fill_data(text,li)
{
    ajaxFid(text);

}
/**
 *@brief is called when something change in ICard
 *@param the input field
 *@see ICard
 */
function fill_fin_data_onchange(ctl)
{
    ajaxFid(ctl);
    ajax_saldo(ctl.id);
}
/**
 *@brief is called when something change in ICard
 *@param the input field
 *@see ICard
 */
function fill_fin_data(text,li)
{
    ajaxFid(text);
    ajax_saldo($(text.id));
}
/**
 *@brief show the ipopup window and display the details of a card,
 * to work some attribute must be set
 *@parameter obj.qcode is the qcode, obj.nohistory if you don't want to  display
 * the history button, obj.ro is the popin is readonly
 *@note you must the gDossier as hidden in the calling page
 *
 *@see ajax_card.php
 */
function fill_ipopcard(obj)
{

    card_layer++;

    var content='card_'+card_layer;
    var nTop=posY-40;
    var nLeft=posX-20;
    var str_style="top:"+nTop+";left:"+nLeft+";width:45em;height:auto";

    var popup={'id':  content,'cssclass':'inner_box','style':str_style,'html':loading(),'drag':true};

    add_div(popup);
    var dossier=$('gDossier').value;
    var qcode='';
    if ( $(obj).qcode != undefined )
    {
        qcode=obj.qcode;
    }
    else
    {
        qcode=$(obj).value;
    }
    //    ctl=$(obj).id;

    var queryString='gDossier='+dossier;
    queryString+='&qcode='+qcode;
    queryString+='&ctl='+content;
    queryString+='&op=dc'; 	// dc for detail card
    if ( obj.readonly != undefined) {
     queryString+='&ro';
    }

    if ( obj.nohistory != undefined) {
     queryString+='&nohistory';
    }

    var action=new Ajax.Request ( 'ajax_card.php',
                                  {
                                  method:'get',
                                  parameters:queryString,
                                  onFailure:errorFid,
                                  onSuccess:fill_box
                                  }
                                );
}
/**
 *@brief
 * \param request : object request
 * \param json : json answer
\code
\endcode
*/
function  successFill_ipopcard(req,json)
{
    try
    {
        var answer=req.responseXML;
        var a=answer.getElementsByTagName('ctl');
        var html=answer.getElementsByTagName('code');

        if ( a.length == 0 )
        {
            var rec=req.responseText;
            alert ('erreur :'+rec);
        }
        var name_ctl=a[0].firstChild.nodeValue;
        var code_html=getNodeText(html[0]);
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
 *@brief show the ipopup for selecting a card type, it is a needed step before adding
 * a card
 *@param input field (obj) it must have the attribute ipopup
 *       possible attribute :
 *        - filter is the filter but with a  fd_id list, -1 means there  is no filter
 *        - ref if we want to refresh the window after adding a card
 *        - type type of card (supplier, customer...)
 *@see ajax_card.php
 */
function select_card_type(obj)
{

    var dossier=$('gDossier').value;

    // give a filter, -1 if not
    var filter=$(obj).filter;
    if ( filter==undefined)
    {
        filter=-1;
    }
    var content="select_card_div";
    if ( $(content)){removeDiv(content);}
    var sx=0;
    if ( window.scrollY)
    {
            sx=window.scrollY+40;
    }
    else
    {
        sx=document.body.scrollTop+60;
    }

    var str_style="top:"+sx+";left:25%;width:50%;height:auto";

    var popup={'id':  content,'cssclass':'inner_box','style':str_style,'html':loading(),'drag':true};

    add_div(popup);

    var queryString='gDossier='+dossier;
    queryString+='&ctl='+content;
    queryString+='&op=st'; 	// st for selecting type
    if ( $(obj).win_refresh!=undefined)
    {
        queryString+='&ref';
    }
    queryString+='&fil='+filter;
    // filter on the ledger, -1 if not
    var oledger=$(obj).jrn;
    if (oledger==undefined)
    {
        ledger=-1;
    }
    else
    {
        ledger=$(obj).jrn;
    }

    queryString+='&ledger='+ledger;

    if ( obj.type_cat)
    {
        queryString+='&cat='+obj.type_cat;
    }

    var action=new Ajax.Request ( 'ajax_card.php',
                                  {
                                  method:'get',
                                  parameters:queryString,
                                  onFailure:errorFid,
                                  onSuccess:fill_box
                                  }
                                );
}
/**
 *@brief Show a blank card
 *@param Form object (obj) it must have the attribute ipopup
 *       possible attribute :
 *        - filter is the filter but with a  fd_id list, -1 means there  is no filter
 *        - ref : reload the window after adding card
 *@see ajax_card.php
 */
function dis_blank_card(obj)
{
    // first we have to take the form elt we need
    var fd_id=$F('fd_id');
    var ref="";
    if ( obj.elements['ref'] )
    {
        ref='&ref';
    }
    var content='div_new_card';
    var nTop=posY-40;
    var nLeft=posX-20;
    var str_style="top:"+nTop+";left:"+nLeft+";width:70em;height:auto";

    var popup={'id':  content,'cssclass':'inner_box','style':str_style,'html':loading(),'drag':true};
    if ( $(content)) {removeDiv(content);}
    add_div(popup);


    var dossier=$('gDossier').value;

    var queryString='gDossier='+dossier;
    queryString+='&ctl='+content;
    queryString+='&fd_id='+fd_id;
    queryString+=ref;
    queryString+='&op=bc'; 	// bc for blank card

    var action=new Ajax.Request ( 'ajax_card.php',
                                  {
                                  method:'get',
                                  parameters:queryString,
                                  onFailure:errorFid,
                                  onSuccess:successFill_ipopcard
                                  }
                                );
}
/**
 *@brief save the data contained into the form 'save_card'
 *@param input field (obj) it must have the attribute ipopup
 *       possible attribute :
 *@see ajax_card.php
 */
function save_card(obj)
{
    var content=$(obj).ipopup;
    // Data must be taken here
    data=$('save_card').serialize(false);
    $(content).innerHTML=loading();

    var dossier=$('gDossier').value;
    var queryString='gDossier='+dossier;
    queryString+='&ctl='+content;
    queryString+=data;
    queryString+='&op=sc'; 	// sc for save card

    var action=new Ajax.Request ( 'ajax_card.php',
                                  {
                                  method:'post',
                                  parameters:queryString,
                                  onFailure:errorFid,
                                  onSuccess:fill_box
                                  }
                                );
}
/**
 *@brief add a category of card,
 *@param obj with the attribute
 * - ipopup the ipopup to show
 * - type_cat the category of card we want to add
 */
function add_category(obj)
{
    var sx=0;
	if ( window.scrollY)
	{
            sx=window.scrollY+120;
	}
	else
	{
            sx=document.body.scrollTop+120;
	}

	var div_style="top:"+sx+";width:60%;heigth:80%";
    // show ipopup
	var div={id:obj.ipopup,
			cssclass:"inner_box",drag:1,style:div_style};
	if ( $(div) ) {
		removeDiv(div);
	}
	add_div(div);
	waiting_box();
    var dossier=$('gDossier').value;
    var queryString='gDossier='+dossier;
    queryString+='&op=ac';
    queryString+='&ctl='+obj.ipopup;
    if ( obj.type_cat)
    {
        queryString+='&cat='+obj.type_cat;
    }
    var action=new Ajax.Request ( 'ajax_card.php',
                                  {
                                  method:'get',
                                  parameters:queryString,
                                  onFailure:errorFid,
                                  onSuccess:fill_box
                                  }
                                );

}
/**
 * @brief save the form and add a new category of card
 * @param obj if the form object
 */
function save_card_category(obj)
{
    if ( ! $(obj).ipopup)
    {
        alert('Erreur pas d\' attribut ipopup '+obj.id);
        return;
    };
	try {
		// Data must be taken here

    data=$('newcat').serialize(false);
    var dossier=$('gDossier').value;
    queryString='ctl='+obj.ipopup+'&';
    queryString+=data;
    queryString+='&op=scc'; 	// sc for save card

    var action=new Ajax.Request ( 'ajax_card.php',
                                  {
                                  method:'get',
                                  parameters:queryString,
                                  onFailure:errorFid,
                                  onSuccess:fill_box
                                  }
                                );
	} catch(e)
	{
		alert(e.message);
		return false;
	}
	return false;
}
/**
 *@brief Remove a definition of an  attribut
 *@param attr_def.ad_id
 *@param gDossier
 *@param table_id to rm the row
 *@param special this pointer of the row
 */

function removeCardAttribut(ad_id,gDossier,table_id,row)
{
    var queryString='gDossier='+gDossier;
    queryString+='&op=rmfa';
    queryString+='&ctl=debug'; 	// debug id
    queryString+='&ad_id='+ad_id;
    var action=new Ajax.Request ( 'ajax_card.php',
                                  {
                                  method:'get',
                                  parameters:queryString,
                                  onFailure:null,
                                  onSuccess:null
                                  }
                                );
    deleteRowRec(table_id,row);


}
/**
* update a card in ajax
*/
function update_card(obj)
{
try {
    var qs=obj.serialize()+'&op=upc';
    var action=new Ajax.Request ( 'ajax_card.php',
				  {
				      method:'get',
				      parameters:qs,
				      onFailure:errorFid,
				      onSuccess:successFill_ipopcard
				  }
				);
    } catch (e) {
	alert(e.message);
	return false;
    }
}
