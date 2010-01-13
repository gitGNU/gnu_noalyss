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
/**
 *@brief regroup the function for the import of bank csv
 */

/*! \brief import : update a record (in ajax)
 *  \param $p_sessid : PHPSESSID
 * \param $p_dossier : the dossier id
 * \param $p_count : the item number
 */
function import_update(p_sessid,p_dossier,p_count) {
  var query_string="PHPSESSID="+p_sessid+"&count="+p_count+"&gDossier="+p_dossier;
  var code=$("code"+p_count);
  var poste=$("poste"+p_count);
  var concerned=$("e_concerned"+p_count);
  var url="ajax_import.php";
  query_string+="&code="+code.value;
  query_string+="&poste="+poste.value;
  query_string+="&concerned="+concerned.value;
  query_string+="&action=update";


  /* call the script which handle the query */
  var update= new Ajax.Request (
				url, 
				{
				method:'get',
				parameters:query_string,
				}
				);
  var form=$("form_"+p_count);
  form.hide();
}
/*! \brief remove : remove a record (in ajax)
 *  \param $p_sessid : PHPSESSID
 * \param $p_dossier : the dossier id
 * \param $p_count : the item number
 */

function import_remove(p_sessid,p_dossier,p_count) {
  var query_string="PHPSESSID="+p_sessid+"&count="+p_count+"&gDossier="+p_dossier;
  var code=$("code"+p_count);
  var url="ajax_import.php";
  query_string+="&code="+code.value;
  query_string+="&action=delete";
  var a = confirm("Etes-vous certain d'effacer cette operation ?");
  if ( a == false ) { return;}

  /* call the script which handle the query */
  var update= new Ajax.Request (
				url, 
				{
				method:'get',
				parameters:query_string,
				}
				);
  var form=$("form_"+p_count);
  form.hide();
}
/*! \brief remove : remove a record (in ajax)
 *  \param $p_sessid : PHPSESSID
 * \param $p_dossier : the dossier id
 * \param $p_count : the item number
 */

function import_not_confirmed(p_sessid,p_dossier,p_count) {
  var query_string="PHPSESSID="+p_sessid+"&count="+p_count+"&gDossier="+p_dossier;
  var code=$("code"+p_count);
  var url="ajax_import.php";
  query_string+="&code="+code.value;
  query_string+="&action=not_confirmed";

  /* call the script which handle the query */
  var update= new Ajax.Request (
				url, 
				{
				method:'get',
				parameters:query_string,
				}
				);
  var form=$("form_"+p_count);
  form.hide();
}