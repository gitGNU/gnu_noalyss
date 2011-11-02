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
/**
  * @brief javascript for the analytic accountancy
  */

/*!\brief add a row for the CA
 * \param p_table_id
 * \param p_amount amount to reach
 */
function add_row(p_table,p_seq)
{
    var mytable=g(p_table).tBodies[0];
	var max=parseFloat(g('amount_t'+p_seq).value);
    if ( ! mytable )
    {
        return;
    }
    var new_value=mytable.rows.length+1;


    if ( mytable.rows.length > 5 )
    {
        alert("Maximum 5 lignes ");
        return;
    }
	amount=compute_total_table(p_table,p_seq);
	if ( max < amount )
	{
		alert('Montant incorrect : max = '+max+" calculé="+amount);
		return;
	}
    // For the detail view (modify_op) there is several form and then several time the
    // element
    var rowToCopy=mytable.rows[1];
    var row=mytable.insertRow(mytable.rows.length);

    for ( var i=0;i< rowToCopy.cells.length;i++)
    {
        var cell=row.insertCell(i);
        var txt=rowToCopy.cells[i].innerHTML;
//	txt=txt.replace(/row_1/g,"row_"+new_value);
        cell.innerHTML=txt;
    }
	var col=document.getElementsByName("val["+p_seq+"][]");
	col[col.length-1].value=max-amount;
	anc_refresh_remain(p_table,p_seq);
}
/**
 *Compute total of a form from Anc_Operation::display_form_plan
 *@param p_table table id
 *@param seq sequence of the line
 *@see Anc_Operation::display_form_plan
 */
function compute_total_table(p_table,seq)
{
	try{

	var i=0;var tot=0;
	var col=document.getElementsByName("val["+seq+"][]");
	for (i=0;i<col.length;i++)
		{
			// here is the problem
			tot+=parseFloat(col[i].value);

		}
		return tot;
	}
	catch(e)
	{
		alert(e.message);
	}
}
/**
 * Refresh remain of account. analytic
 *@param p_table table id
 *@param p_seq sequence of the line
 *@see Anc_Operation::display_form_plan
 */
function anc_refresh_remain(p_table,p_seq)
{
	try
	{
		var tot_line=parseFloat(g('amount_t'+p_seq).value);
		var tot_table=compute_total_table(p_table,p_seq);
		var remain = tot_line-tot_table;
		remain=Math.round(remain *100)/100;
		$('remain'+p_table).innerHTML=remain;
		if (remain == 0)
			{$('remain'+p_table).style.color="green"}
			else
			{$('remain'+p_table).style.color="red"}
	}catch (a)
	{
		alert(a.message);
	}
}
/*!
 * \brief Check the amount of the CA
 * \param p_style : error or ok, if ok show a ok box if the amount are equal
 *
 *
 * \return true if the amounts are equal
 */
function verify_ca(div)
{
    var idx=0;
    var amount_error=0;
    // put a maximum
    while (idx < 50 )
    {
        var table=div+'t'+idx;
        if ( g(table) )
        {
            var total_amount=0;
            // table is found compute the different val[]
            var array_value=document.getElementsByName('val['+idx+'][]');

            for (var i=0;i < array_value.length;i++ )
            {
                if ( isNaN(array_value[i].value))
                {
                    array_value[i].value=0;
                }

                total_amount+=parseFloat(array_value[i].value);
            }
            var amount=parseFloat(g('amount_t'+idx).value);
            var diff=amount-total_amount;

            if (  Math.round(diff,2)!= 0.0)
            {
                g(table).style.backgroundColor='red';
                amount_error++;
            }
            else
            {
                g(table).style.backgroundColor='lightgreen';

            }
            idx++;
        }
        else	break;
    }
    if ( amount_error != 0 )
    {
        alert('Désolé, les montants pour la comptabilité analytique sont incorrects');
        return false;
    }
    return true;
}
/*!
 * \brief open a window for searching a CA account,
 * \param p_dossier dossier id
 * \param p_target ctrl to update
 * \param p_source ctrl containing the pa_id
 *
 *
 * \return
 */
function search_ca (p_dossier,p_target,p_source)
{
    var pa_id=g(p_source).value;
    var url="gDossier="+p_dossier+"&c1="+p_target+"&c2="+pa_id;
    var a=window.open("search_ca.php"+url,"CA recherche",'statusbar=no,scrollbars=yes,toolbar=no');
    a.focus();
}

function caod_checkTotal()
{
    var ie4=false;
    if ( document.all )
    {
        ie4=true;
    }// Ajouter getElementById par document.all[str]
    var total_deb=0.0;
    var total_cred=0.0;
    var nb_item=g('nbrow').value;

    for (var i=0;i <nb_item ;i++)
    {
        var doc_amount=g("pamount"+i);
        if ( ! doc_amount )
        {
            return;
        }
        var side=g("pdeb"+i);
        if ( ! side )
        {
            return;
        }
        var amount=parseFloat(doc_amount.value);

        if ( isNaN(amount) == true)
        {
            amount=0.0;
        }
        if ( side.checked == false )
        {
            total_cred+=amount;
        }
        if ( side.checked == true )
        {
            total_deb+=amount;
        }
    }



    r_total_cred=Math.round(total_cred*100)/100;
    r_total_deb=Math.round(total_deb*100)/100;
    g('totalDeb').innerHTML=r_total_deb;
    g('totalCred').innerHTML=r_total_cred;

    if ( r_total_deb != r_total_cred )
    {
        g("totalDiff").style.color="red";
        g("totalDiff").style.fontWeight="bold";
        g("totalDiff").innerHTML="Différence";
        diff=total_deb-total_cred;
        diff=Math.round(diff*100)/100;
        g("totalDiff").innerHTML=diff;

    }
    else
    {
        g("totalDiff").innerHTML="0.0";
    }
}

/**
 *@brief remove an operation
 *@param p_dossier is the folder
 *@param p_oa_group is the group of the analytic operation
 */
function op_remove(p_dossier,p_oa_group)
{
    var a=confirm("Etes-vous sur de vouloir effacer cette operation ?\n");
    if ( a == false ) return;
var obj={"oa":
         p_oa_group,"gDossier":
             p_dossier};
    queryString=encodeJSON(obj);
    g(p_oa_group).style.display='none';
    var e=new Ajax.Request("remove_op.php",
                       {method:'get',parameters:queryString});

}
/**
* add a row in misc operation for ANC
* the code must be adapted for that
*/
function anc_add_row(tableid)
{
    style='class="input_text"';
    var mytable=g(tableid).tBodies[0];
    var nNumberRow=mytable.rows.length;
    var oRow=mytable.insertRow(nNumberRow);
    var rowToCopy=mytable.rows[1];
    var nNumberCell=rowToCopy.cells.length;
    var nb=g("nbrow");
    var oNewRow = mytable.insertRow(nNumberRow);
    for ( var e=0;e < nNumberCell;e++)
    {
        var newCell=oRow.insertCell(e);
        var tt=rowToCopy.cells[e].innerHTML;
        new_tt=tt.replace(/pop0/g,"pop"+nb.value);
        new_tt=new_tt.replace(/pamount0/g,"pamount"+nb.value);
        new_tt=new_tt.replace(/pdeb0/g,"pdeb"+nb.value);
        newCell.innerHTML=new_tt;
        new_tt.evalScripts();
    }
    $("pamount"+nb.value).value="0";
    nb.value++;
}
