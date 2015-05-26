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

/*!\file
 * \brief this file contains all the javascript needed by the todo_list. 
 *      it requires prototype.js. The calling page must have 
 *      the gDossier
 * 
 */
function todo_list_show(p_id)
{
    waiting_node();
    /*
     * create a div id based on p_id
     */
    

    try
    {
         var gDossier = $('gDossier').value;
        var action = new Ajax.Request(
                'ajax_todo_list.php',
                {
                    method: 'get',
                    parameters:
                            {'show':
                                        1, 'id':
                                        p_id, 'gDossier':
                                        gDossier
                            },
                    onFailure: todo_list_show_error,
                    onSuccess: function (req)
                    {
                        try
                        {
                            var todo_div=create_div({id:'todo_list_div'+p_id,cssclass:'add_todo_list',drag:1});
                           


                            todo_div.style.top = (posY + offsetY) + 'px';
                            todo_div.style.left = (posX + offsetX - 200) + 'px';

                            var answer = req.responseXML;
                            var tl_id = answer.getElementsByTagName('tl_id');
                            var tl_content = answer.getElementsByTagName('tl_content');

                            if (tl_id.length == 0)
                            {
                                var rec = req.responseText;
                                alert('erreur :' + rec);
                            }
                            var content = unescape_xml(getNodeText(tl_content[0]));
                            todo_div.innerHTML=content;
                            
                            remove_waiting_node();
                            content.evalScripts();
                            Effect.SlideDown(todo_div, {duration: 0.1, direction: 'top-left'})
                        }
                        catch (e)
                        {
                            alert(e.message);
                        }
                    }
                }
        );
    }
    catch (e)
    {
        alert(" Envoi ajax non possible" + e.message);
    }
    return false;
}
function todo_list_show_error(request_json)
{
    alert('failure');
}
function add_todo()
{
    todo_list_show(0);
    /*$('add_todo_list').style.top = posY + offsetY + "px";
    $('add_todo_list').style.left = posX + offsetX + "px";

    $('p_title').value = '';

    $('p_date_todo').value = '';
    $('p_desc').value = '';
    $('tl_id').value = 0;
    $('add_todo_list').style.display = 'block';*/
}
function todo_list_remove(p_ctl)
{
    if (confirm('Effacer ?') == false)
    {
        return;
    }
    $("tr" + p_ctl).hide();
    var gDossier = $('gDossier').value;

    var action = new Ajax.Request(
            'ajax_todo_list.php',
            {
                method: 'get',
                parameters:
                        {'del':
                                    1, 'id':
                                    p_ctl, 'gDossier':
                                    gDossier
                        }
            }
    );
    return false;

}
function todo_list_save(p_form)
{
    try {
    console.log(p_form);
    var form=$('todo_form_'+p_form);
    var json=form.serialize(true);
    new Ajax.Request('ajax_todo_list.php',
                    {
                        method:'get',
                       parameters:json,
                       onSuccess:function (req) {
                           // On success : reload the correct row and close 
                           // the box
                           var answer = req.responseXML;
                            var tl_id = answer.getElementsByTagName('tl_id');
                            var content = answer.getElementsByTagName('row');
                            var style  =  answer.getElementsByTagName('style');

                            if (tl_id.length == 0)
                            {
                                var rec = req.responseText;
                                alert('erreur :' + rec);
                            }
                            var tr = $('tr'+p_form);
                            if ( p_form == 0) 
                            {
                                tr=document.createElement('tr');
                                tr.id='tr'+getNodeText(tl_id[0]);
                                $('table_todo').appendChild(tr);
                            }
                            var html=getNodeText(content[0]);
                            tr.innerHTML=unescape_xml(html);
                            $w(tr.className).each ( function(p_class) { tr.removeClassName(p_class); } );
                            tr.addClassName(getNodeText(style[0]));
                           Effect.Fold('todo_list_div'+p_form,{duration:0.1});
                       }
                    }
                    );
        }
        catch (e) {
            console.log(e.message);
            return false;
        }
        return false;
}

/**
 * @brief maximize or minimize the todo  list from the
 * dashboard.
 */
var todo_maximize=false;

function zoom_todo ()
{
    if ( ! todo_maximize)
    {
        
        $('todo_listg_div').setStyle({'z-index':1,'position':'absolute'});
        new Effect.Scale('todo_listg_div',200,{scaleContent:false,scaleMode:'contents'});
        todo_maximize=true;
    } else
    {
        todo_maximize=false;
        $('todo_listg_div').setAttribute('style',"");
        /* IE Bug */
         if ($('todo_listg_div').style.setAttribute) { $('todo_listg_div').style.setAttribute('cssText', "") ;}
        new Effect.Scale('todo_listg_div',100,{scaleContent:false,scaleMode:'contents'});
    }
   
}