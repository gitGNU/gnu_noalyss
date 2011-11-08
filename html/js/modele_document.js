/*!\brief
 * \param p_value jrn.jr_id
 */
function modifyModeleDocument(p_value,dossier)
{
    layer++;
    id='det'+layer;
    var pos_y=posY+offsetY-20;
    var pos_x=posX+offsetX+40;
    var style="position:absolute;top:"+pos_y+";left:"+pos_x;
    var popup={'id':'mod_doc',
	       'cssclass':'inner_box',
               'html': loading(),
	       'drag':false,
	       'style':style
	      };

    querystring='gDossier='+dossier+'&op=mod_doc&id='+p_value+'&div=mod_doc';
    if ( ! $('mod_doc'))
    {
	add_div(popup);
    }
    var action=new Ajax.Request(
                   "ajax_misc.php",
                   {
                   method:'get',
                   parameters:querystring,
                   onFailure:error_box,
                   onSuccess:modify_document_success_box
                   }
               );
}
/**
 *@brief receive answer from ajax and just display it into the IBox
 * XML must contains at least 2 fields : code is the ID of the IBOX and
 * html which is the contain
 */
function modify_document_success_box(req,json)
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
        g(name_ctl).innerHTML=code_html;
        g(name_ctl).style.height='auto';
    }
    catch (e)
    {
        alert("success_box"+e.message);
    }
    try
    {
        code_html.evalScripts();
    }
    catch(e)
    {
        alert("answer_box Impossible executer script de la reponse\n"+e.message);
    }
}
