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
 * \brief javascript script for the report (in accountancy)
 *
 */
/**
 * @brief add a line in the form for the report 
 * @param p_dossier dossier id to connect
 */
function rapport_add_row(p_dossier){
   style='style="border: 1px solid blue;"';
   var table=$("rap1");
   var line=table.rows.length;

   var row=table.insertRow(line);
   // left cell
  var cellPos = row.insertCell(0);
  cellPos.innerHTML='<input type="text" '+style+' size="3" id="pos'+line+'" name="pos'+line+'" value="'+line+'">';

  // right cell
  var cellName = row.insertCell(1);
  cellName.innerHTML='<input type="text" '+style+' size="40" id="text'+line+'" name="text'+line+'">';

  // button + formula
    var cellbutton = row.insertCell(2);
    var but_html=table.rows[1].cells[2].innerHTML;
    but_html=but_html.replace(/form0/g,"form"+line);
    cellbutton.innerHTML=but_html;
    but_html.evalScripts();

    g('form'+line).value='';
}

/**
 * @brief create a file to export a report
 * @param p_dossier the dossier id
 */
function report_export(p_dossier,p_fr_id) {
  var queryString="?&gDossier="+p_dossier+"&f="+p_fr_id;
  var action=new Ajax.Request(
			      "ajax_report.php",
			      {
			      method:'get',
			      parameters:queryString,
			      onSuccess:report_export_success
			      }
			      );
  
}
/**
 * @brief callback function for exporting a report
 * @param request object request
 * @param json json answer
 */
function report_export_success(request,json) {
  var answer = request.responseText.evalJSON(true);
  var ok=answer.answer;
  var link=answer.link;
  $('export').hide();
  $('export_link').innerHTML='<a class="mtitle" href="'+link+'"> Cliquez ici pour télécharger le rapport</a>';
}