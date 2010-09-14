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
/* $Revision: 2546 $ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/*!\file
 * \brief javascript script for the ledger in accountancy,
 * compute the sum, add a row at the table..
 *
 */
var layer=1;
/**
* @brief update the list of available predefined operation when we change the ledger.
*/
function update_predef(p_type,p_direct)
{
    var jrn=g("p_jrn").value;
    var dossier=g("gDossier").value;
    var querystring='?&gDossier='+dossier+'&l='+jrn+'&t='+p_type+'&d='+p_direct;
    g("p_jrn_predef").value=jrn;
    var action=new Ajax.Request(
                   "get_predef.php",
                   {
                   method:'get',
                   parameters:querystring,
                   onFailure:error_get_predef,
                   onSuccess:success_get_predef
                   }
               );
}
/**
 * @brief update the field predef
 */
function success_get_predef(request,json)
{
    var i=0;
    var answer=request.responseText.evalJSON(true);
    obj=g("pre_def");
    obj.innerHTML='';
    if ( answer.count == 0 ) return;

    for ( i = 0 ; i < answer.count;i++)
    {
        value=answer['value'+i];
        label=answer['label'+i];
        obj.options[obj.options.length]=new Option(label,value);
    }

}
/**
 * @brief update the field predef
 */
function error_get_predef(request,json)
{
    alert ("Erreur mise à jour champs non possible");

}
/**
* @brief update the list of available predefined operation when we change the ledger.
*/
function update_pj()
{
    var jrn=g("p_jrn").value;
    var dossier=g("gDossier").value;
    var querystring='?&gDossier='+dossier+'&l='+jrn;
    var action=new Ajax.Request(
                   "get_pj.php",
                   {
                   method:'get',
                   parameters:querystring,
                   onFailure:error_get_pj,
                   onSuccess:success_get_pj
                   }
               );
}
/**
 *ask the name, quick_code of the bank for the ledger
 */
function update_bank()
{
    var jrn=g('p_jrn').value;
    var dossier=g('gDossier').value;
    var qs='?&gDossier='+dossier+'&op=bkname&p_jrn='+jrn;
    var action=new Ajax.Request(
                   "ajax_misc.php",
                   {
                   method:'get',
                   parameters:qs,
                   onFailure:error_get_pj,
                   onSuccess:success_update_bank
                   }
               );

}
/**
 * Put into the span, the name of the bank, the bank account
 * and the quick_code
 */
function success_update_bank(req)
{
    try
    {
        var answer=req.responseXML;
        var a=answer.getElementsByTagName('code');
        var html=answer.getElementsByTagName('value');
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
        alert("success_update_bank".e.message);
    }
}
/**
 * call ajax, ask what is the last date for the current ledger
 */
function get_last_date()
{
    var jrn=g('p_jrn').value;
    var dossier=g('gDossier').value;
    var qs='?&gDossier='+dossier+'&op=lastdate&p_jrn='+jrn;
    var action=new Ajax.Request(
                   "ajax_misc.php",
                   {
                   method:'get',
                   parameters:qs,
                   onFailure:error_get_pj,
                   onSuccess:success_get_last_date
                   }
               );
}
/**
 * callback ajax, set the ctl with the last date from the ledger
 */
function success_get_last_date(req)
{
    try
    {
        var answer=req.responseXML;
        var a=answer.getElementsByTagName('code');
        var html=answer.getElementsByTagName('value');
        if ( a.length == 0 )
        {
            var rec=req.responseText;
            alert ('erreur :'+rec);
        }
        var name_ctl=a[0].firstChild.nodeValue;
        var code_html=getNodeText(html[0]);
        code_html=unescape_xml(code_html);
        $(name_ctl).value=code_html;
    }
    catch (e)
    {
        alert(e.message);
    }
}
/**
 * @brief update the field predef
 */
function success_get_pj(request,json)
{

    var answer=request.responseText.evalJSON(true);
    obj=g("e_pj");
    obj.value='';
    if ( answer.count == 0 ) return;
    obj.value=answer.pj;
    g("e_pj_suggest").value=answer.pj;
}
/**
 * @brief update the field predef
 */
function error_get_pj(request,json)
{
    alert("Ajax a echoue");
}

/**
 * @brief add a line in the form for the ledger fin
 */
function ledger_fin_add_row()
{
    var style='class="input_text"';
    var mytable=g("fin_item").tBodies[0];
    var line=mytable.rows.length;
    var row=mytable.insertRow(line);
    var nb=g("nb_item");
    var rowToCopy=mytable.rows[1];
    var nNumberCell=rowToCopy.cells.length;
    for ( var e=0;e < nNumberCell;e++)
    {
        var newCell=row.insertCell(e);
        var tt=rowToCopy.cells[e].innerHTML;
        var new_tt=tt.replace(/e_other0/g,"e_other"+nb.value);
        new_tt=new_tt.replace(/e_other0_comment/g,"e_other"+nb.value+'_comment');
        new_tt=new_tt.replace(/e_other0_amount/g,"e_other"+nb.value+'_amount');
        new_tt=new_tt.replace(/e_concerned0/g,"e_concerned"+nb.value);
        new_tt=new_tt.replace(/e_other0_label/g,"e_other"+nb.value+'_label');
        newCell.innerHTML=new_tt;
        new_tt.evalScripts();
    }
    g("e_other"+nb.value).value="";
    g("e_other"+nb.value+'_amount').value="0";
    g("e_other"+nb.value+'_comment').value="";
    nb.value++;
}
/**
 * @brief add a line in the form for the purchase ledger
 */
function ledger_add_row()
{
    style='class="input_text"';
    var mytable=g("sold_item").tBodies[0];
    var ofirstRow=mytable.rows[1];
    var line=mytable.rows.length;
    var nCell=9;
    var row=mytable.insertRow(line);
    var nb=g("nb_item");
    for (var e=0;e<nCell;e++)
    {
        var newCell=row.insertCell(e);
        var tt=ofirstRow.cells[e].innerHTML;
        var new_tt=tt.replace(/march0/g,"march"+nb.value);
        new_tt=new_tt.replace(/quant0/g,"quant"+nb.value);
        new_tt=new_tt.replace(/sold\(0\)/g,"sold("+nb.value+")");
        new_tt=new_tt.replace(/compute_ledger\(0\)/g,"compute_ledger("+nb.value+")");
        new_tt=new_tt.replace(/clean_tva\(0\)/g,"clean_tva("+nb.value+")");
        newCell.innerHTML=new_tt;
        new_tt.evalScripts();
    }

    g("e_march"+nb.value+"_label").innerHTML='&nbsp;';
    g("e_march"+nb.value+"_price").value='0';
    g("e_march"+nb.value).value="";
    g("e_quant"+nb.value).value="1";

    nb.value++;

    new_tt.evalScripts();

}
/**
 * @brief compute the sum of a purchase, update the span tvac, htva and tva
 * all the needed data are taken from the document (hidden field :  gdossier)
 * @param the number of the changed ctrl
 */
function compute_ledger(p_ctl_nb)
{
    var dossier=g("gDossier").value;
    var a=-1;
    if ( document.getElementById("e_march"+p_ctl_nb+'_tva_amount'))
    {
        a=trim(g("e_march"+p_ctl_nb+'_tva_amount').value);
        g("e_march"+p_ctl_nb+'_tva_amount').value=a;
    }
    g("e_march"+p_ctl_nb).value=trim(g("e_march"+p_ctl_nb).value);
    var qcode=g("e_march"+p_ctl_nb).value;

    if ( qcode.length == 0 )
    {
        clean_ledger(p_ctl_nb);
        refresh_ledger();
        return;
    }
    /*
     * if tva_id is empty send a value of -1
     */
    var tva_id=-1;
    if ( g('e_march'+p_ctl_nb+'_tva_id') )
    {
        tva_id=g('e_march'+p_ctl_nb+'_tva_id').value;
        if ( trim(tva_id) == '')
        {
            tva_id=-1;
        }
    }

    g('e_march'+p_ctl_nb+'_price').value=trim(g('e_march'+p_ctl_nb+'_price').value);
    var price=g('e_march'+p_ctl_nb+'_price').value;

    g('e_quant'+p_ctl_nb).value=trim(g('e_quant'+p_ctl_nb).value);
    var quantity=g('e_quant'+p_ctl_nb).value;
    var querystring='?&gDossier='+dossier+'&c='+qcode+'&t='+tva_id+'&p='+price+'&q='+quantity+'&n='+p_ctl_nb;
    $('sum').hide();
    var action=new Ajax.Request(
                   "compute.php",
                   {
                   method:'get',
                   parameters:querystring,
                   onFailure:error_compute_ledger,
                   onSuccess:success_compute_ledger
                   }
               );
}
/**
*@brief refresh the purchase screen, recompute vat, total...
*/
function refresh_ledger()
{
    var tva=0;
    var htva=0;
    var tvac=0;

    for (var i=0;i<g("nb_item").value;i++)
    {
        if( g('tva_march'+i))  tva+=g('tva_march'+i).value*1;
        htva+=g('htva_march'+i).value*1;
        tvac+=g('tvac_march'+i).value*1;
    }

    if ( g('tva') ) g('tva').innerHTML=Math.round(tva*100)/100;
    g('htva').innerHTML=Math.round(htva*100)/100;
    if (g('tvac'))    g('tvac').innerHTML=Math.round(tvac*100)/100;
}
/**
 *@brief update the field htva, tva_id and tvac, callback function for  compute_sold
 * it the field TVA in the answer contains NA it means that VAT is appliable and then do not
 * update the VAT field except htva_martc
 */
function success_compute_ledger(request,json)
{
    /**
     *@todo add a control 
     * - 0 everything is ok
     * - 1 tva id  is invalid
     */
    var answer=request.responseText.evalJSON(true);
    var ctl=answer.ctl;
    var rtva=answer.tva;
    var rhtva=answer.htva;
    var rtvac=answer.tvac;

    if ( rtva == 'NA' )
    {
        var rhtva=answer.htva*1;
        g('htva_march'+ctl).value=rhtva;
        g('tvac_march'+ctl).value=rtvac;
        g('sum').show();
        refresh_ledger();

        return;
    }
    rtva=answer.tva*1;



    g('sum').show();
    if ( g('e_march'+ctl+'_tva_amount').value=="" ||  g('e_march'+ctl+'_tva_amount').value==0 )
    {
        g('tva_march'+ctl).value=rtva;
        g('e_march'+ctl+'_tva_amount').value=rtva;
    }
    else
    {
        g('tva_march'+ctl).value=g('e_march'+ctl+'_tva_amount').value;
    }
    g('htva_march'+ctl).value=Math.round(parseFloat(rhtva)*100)/100;
    var tmp1=Math.round(parseFloat(g('htva_march'+ctl).value)*100)/100;
    var tmp2=Math.round(parseFloat(g('tva_march'+ctl).value)*100)/100;
    g('tvac_march'+ctl).value=Math.round((tmp1+tmp2)*100)/100;

    refresh_ledger();
}

/**
 * @brief callback error function for  compute_sold
 */
function error_compute_ledger(request,json)
{
    alert('Ajax does not work');
}
function compute_all_ledger()
{
    var loop=0;
    for (loop=0;loop<g("nb_item").value;loop++)
    {
        compute_ledger(loop);
    }
    var tva=0;
    var htva=0;
    var tvac=0;

    for (var i=0;i<g("nb_item").value;i++)
    {
        if ( g('tva_march') ) tva+=g('tva_march'+i).value*1;
        htva+=g('htva_march'+i).value*1;
        tvac+=g('tvac_march'+i).value*1;
    }

    if ( g('tva') ) g('tva').innerHTML=Math.round(tva*100)/100;
    g('htva').innerHTML=Math.round(htva*100)/100;
    if (g('tvac'))g('tvac').innerHTML=Math.round(tvac*100)/100;


}

function clean_tva(p_ctl)
{
    if ( g('e_march'+p_ctl+'_tva_amount') )g('e_march'+p_ctl+'_tva_amount').value=0;
}

function clean_ledger( p_ctl_nb)
{
    if ( g("e_march"+p_ctl_nb) )
    {
        g("e_march"+p_ctl_nb).value=trim(g("e_march"+p_ctl_nb).value);
    }
    if (g('e_march'+p_ctl_nb+'_price'))
    {
        g('e_march'+p_ctl_nb+'_price').value='';
    }
    if ( g('e_quant'+p_ctl_nb))
    {
        g('e_quant'+p_ctl_nb).value='1';
    }
    if ( g('tva_march'+p_ctl_nb+'_show') )
    {
        g('tva_march'+p_ctl_nb+'_show').value='0';
    }
    if (g('tva_march'+p_ctl_nb))
    {
        g('tva_march'+p_ctl_nb).value=0;
    }
    if ( g('htva_march'+p_ctl_nb))
    {
        g('htva_march'+p_ctl_nb).value=0;
    }
    if ( g('tvac_march'+p_ctl_nb))
    {
        g('tvac_march'+p_ctl_nb).value=0;
    }

}
/**
 * @brief add a line in the form for the quick_writing
 */
function quick_writing_add_row()
{
    style='class="input_text"';
    var mytable=g("quick_item").tBodies[0];
    var nNumberRow=mytable.rows.length;
    var oRow=mytable.insertRow(nNumberRow);
    var rowToCopy=mytable.rows[1];
    var nNumberCell=rowToCopy.cells.length;
    var nb=g("nb_item");

    var oNewRow = mytable.insertRow(nNumberRow);
    for ( var e=0;e < nNumberCell;e++)
    {
        var newCell=oRow.insertCell(e);
        var tt=rowToCopy.cells[e].innerHTML;
        new_tt=tt.replace(/qc_0/g,"qc_"+nb.value);
        new_tt=new_tt.replace(/amount0/g,"amount"+nb.value);
        new_tt=new_tt.replace(/poste0/g,"poste"+nb.value);
        new_tt=new_tt.replace(/ck0/g,"ck"+nb.value);
        new_tt=new_tt.replace(/ld0/g,"ld"+nb.value);
        newCell.innerHTML=new_tt;
        new_tt.evalScripts();
    }
    $("qc_"+nb.value).value="";
    $("amount"+nb.value).value="";
    $("poste"+nb.value).value="";
    $("ld"+nb.value).value="";



    nb.value++;

}
function RefreshMe()
{
    window.location.reload();
}
/*! \brief this function search into the ledger
 *  \param p_ctl ctl name
 *  \param p_montant amount to search (if 0 get it from the e_other_amount
 */
function SearchJrn(p_dossier,p_ctl,p_montant,p_paid)
{
    var url='jrn_search.php?p_ctl='+p_ctl+'&gDossier='+p_dossier+'&'+p_paid;


    if ( p_montant == 0 )
    {
        // compute amount name replace the number
        num=p_ctl.replace("e_concerned","");

        /* Get the amount */
        var ctl_montant_name="e_other"+num+"_amount";

        if ( document.forms[0])
        {

            for ( i=0; i < document.forms[0].length; i++)
            {
                var e=document.forms[0].elements[i];
                if ( e.name == ctl_montant_name )
                {
                    p_montant=e.value;
                    break;
                }
            }
        }
        if ( p_montant == 0 && document.forms[1])
        {

            for ( i=0; i < document.forms[1].length; i++)
            {
                var e=document.forms[1].elements[i];
                if ( e.name == ctl_montant_name )
                {
                    p_montant=e.value;
                    break;
                }
            }
        }

    }


    if ( p_montant == 0 )
    {
        var win=window.open(url,'Cherche','toolbar=no,width=600,height=600,scrollbars=yes');

    }
    else
    {
        var win=window.open(url+'&search&p_montant='+p_montant+'&p_montant_sel=%3D','Cherche','toolbar=no,width=600,height=600,scrollbars=yes');
    }
}

function updateJrn(p_ctl)
{
    var form=document.forms[1];

    for (var e=0;e<form.elements.length;e++)
    {
        var elmt=form.elements[e];
        if ( elmt.type == "checkbox")
        {
            if (elmt.checked==true )
            {
                var str_name=elmt.name;
                var nValue=str_name.replace("jr_concerned","");

                set_inparent(p_ctl,nValue,1);
            }
        }
    }
    window.close();
}

function go_next_concerned()
{
    var form=document.forms[1];

    for (var e=0;e<form.elements.length;e++)
    {
        var elmt=form.elements[e];
        if ( elmt.type == "checkbox")
        {
            if (elmt.checked==true )
            {
                return confirm("Si vous changez de page vous perdez les reconciliations, continuez ?");
            }
        }
    }
    return true;
}
function view_history_account(p_value,dossier)
{
    layer++;
    id='det'+layer;
var popup={'id':
           id,'cssclass':'op_detail'
           ,'html':
           loading(),'drag':
               true};
    querystring='?gDossier='+dossier+'&act=de&pcm_val='+p_value+'&div='+id;
    add_div(popup);
    var action=new Ajax.Request(
                   "ajax_history.php",
                   {
                   method:'get',
                   parameters:querystring,
                   onFailure:error_box,
                   onSuccess:success_box
                   }
               );
    g(id).style.top=posY-40;
    g(id).style.left=posX-10;

}
/*!\brief
 * \param p_value f_id of the card
 */

function view_history_card(p_value,dossier)
{
    layer++;
    id='det'+layer;
var popup={'id':
           id,'cssclass':'op_detail'
           ,'html':
           loading(),'drag':
               true};
    querystring='?gDossier='+dossier+'&act=de&f_id='+p_value+'&div='+id;
    add_div(popup);
    var action=new Ajax.Request(
                   "ajax_history.php",
                   {
                   method:'get',
                   parameters:querystring,
                   onFailure:error_box,
                   onSuccess:success_box
                   }
               );
    g(id).style.top=posY-40;
    g(id).style.left=posX-10;

}
/**
* remove an Operation
*@param p_jr_id is the jrn.jr_id
*@param dossier
*@param the div
*/
function removeOperation(p_jr_id,dossier,div)
{
    var qs="?gDossier="+dossier+"&act=rmop&div="+div+"&jr_id="+p_jr_id;
    var action=new Ajax.Request(
                   "ajax_ledger.php",
                   {
                   method:'get',
                   parameters:qs,
                   onFailure:error_box,
                   onSuccess:infodiv
                   }
               );

}

/**
* reverse an Operation
*@param pointer to the FORM
*/
function reverseOperation(obj)
{
    var qs="?"+$(obj).serialize();
    g('ext'+obj.divname).style.display='none';
    g('bext'+obj.divname).style.display='none';

    var action=new Ajax.Request(
                   "ajax_ledger.php",
                   {
                   method:'get',
                   parameters:qs,
                   onFailure:error_box,
                   onSuccess:infodiv
                   }
               );

    return false;
}

/*!\brief
 * \param p_value jrn.jr_id
 */
function modifyOperation(p_value,dossier)
{
    layer++;
    id='det'+layer;
var popup={'id':
           id,'cssclass':'op_detail'
           ,'html':
           loading(),'drag':
               true};
    querystring='?gDossier='+dossier+'&act=de&jr_id='+p_value+'&div='+id;
    add_div(popup);
    var action=new Ajax.Request(
                   "ajax_ledger.php",
                   {
                   method:'get',
                   parameters:querystring,
                   onFailure:error_box,
                   onSuccess:success_box
                   }
               );
    g(id).style.top=posY-40;
    g(id).style.left=posX-10;

}

/*!\brief
 * \param p_value jrn.jr_id
 */

function viewOperation(p_value,p_dossier)
{
    modifyOperation(p_value,p_dossier)
}
function dropLink(p_dossier,p_div,p_jr_id,p_jr_id2)
{
    var querystring='?gDossier='+p_dossier;
    querystring+='&div='+p_div;
    querystring+='&jr_id='+p_jr_id;
    querystring+='&act=rmr';
    querystring+='&jr_id2='+p_jr_id2;
    var action=new Ajax.Request ( 'ajax_ledger.php',
                                  {
                                  method:'get',
                                  parameters:querystring,
                                  onFailure:null,
                                  onSuccess:null
                                  }
                                );
}
/**
 *@brief this function is called before the querystring is send to the
 * fid2.php, add a filter based on the ledger 'p_jrn'
 *@param obj is the input field
 *@param queryString is the queryString to modify
 *@see ICard::input
 */
function filter_card(obj,queryString)
{
    jrn=$('p_jrn').value;
    if ( jrn == -1 )
    {
        type=$('ledger_type').value;
        queryString=queryString+'&type='+type;
    }
    else
    {
        queryString=queryString+'&j='+jrn;
    }
    return queryString;
}
/**
 *@brief to display the lettering for the operation, call
 * ajax function
 *@param obj object attribut :  gDossier,j_id,obj_type
 */
function dsp_letter(obj)
{
    try
    {
        var queryString='?gDossier='+obj.gDossier+'&j_id='+obj.j_id+'&op=dl'+'&ot='+this.obj_type;
        var action=new Ajax.Request(
                       "ajax_misc.php",
                       {
                   method:'get',
                   parameters:queryString,
                   onFailure:error_dsp_letter,
                   onSuccess:success_dsp_letter
                       }
                   );
        g('search').style.display='none';
        g('list').style.display='none';
        $('detail').innerHTML=loading();
        g('detail').style.display='block';
    }
    catch(e)
    {
        alert('dsp_letter failed  '+e.message);
    }
}

function success_dsp_letter(req)
{
    try
    {
        var answer=req.responseXML;
        var a=answer.getElementsByTagName('code');
        var html=answer.getElementsByTagName('value');
        if ( a.length == 0 )
        {
            var rec=req.responseText;
            alert ('erreur :'+rec);
        }
        var name_ctl=a[0].firstChild.nodeValue;
        var code_html=getNodeText(html[0]);
        code_html=unescape_xml(code_html);
        $('detail').innerHTML=code_html;
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
function error_dsp_letter(req)
{
    alert('Erreur AJAX DSP_LETTER');
}

function search_letter(obj)
{
    try
    {
        var str_query='';
        if (obj.elements['gDossier'] ) str_query='gDossier='+obj.elements['gDossier'].value;
        if (obj.elements['j_id'] ) str_query+='&j_id='+obj.elements['j_id'].value;
        if (obj.elements['ot'] ) str_query+='&ot='+obj.elements['ot'].value;
        if (obj.elements['op'] ) str_query+='&op='+obj.elements['op'].value;
        if (obj.elements['min_amount'] ) str_query+='&min_amount='+obj.elements['min_amount'].value;
        if (obj.elements['max_amount'] ) str_query+='&max_amount='+obj.elements['max_amount'].value;
        if (obj.elements['search_start'] ) str_query+='&search_start='+obj.elements['search_start'].value;
        if (obj.elements['search_end'] ) str_query+='&search_end='+obj.elements['search_end'].value;
        if (obj.elements['side'] ) str_query+='&side='+obj.elements['side'].value;


        var action=new Ajax.Request(
                       "ajax_misc.php",
                       {
                   method:'get',
                   parameters:str_query,
                   onFailure:error_dsp_letter,
                   onSuccess:success_dsp_letter
                       }
                   );
        $('list').hide();
        $('search').hide();
        $('detail').innerHTML=loading();
        $('detail').show();
    }
    catch(e)
    {
        alert('search_letter  '+e.message);
    }
}
/**
*@brief save an operation in ajax, it concerns only the 
* comment, the pj and the rapt
* the form elements are access by their name
*@param form
*/
function op_save(obj)
{
    var queryString='?'+$(obj).serialize();
    queryString+="&lib="+obj.lib.value;
    queryString+="&gDossier="+obj.gDossier.value;
    var rapt2="rapt"+obj.whatdiv.value;
    queryString+="&rapt="+g(rapt2).value;
    queryString+="&npj="+obj.npj.value;
    queryString+='&jr_id='+obj.jr_id.value;
    queryString+='&div='+obj.whatdiv.value;
    queryString+='&act=save';
    if ( g('inpopup'))
    {
        var action=new Ajax.Request ( 'ajax_ledger.php',
                                      {
                                  method:'post',
                                  parameters:queryString,
                                  onFailure:null,
                                  onSuccess:infodiv
                                      }
                                    );
        window.close();
    }
    else
    {
        var action=new Ajax.Request ( 'ajax_ledger.php',
                                      {
                                  method:'post',
                                  parameters:queryString,
                                  onFailure:null,
                                  onSuccess:infodiv
                                      }
                                    );
    }
    return false;
}
