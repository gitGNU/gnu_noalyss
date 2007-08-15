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
 * open a windows for modifying a operation
 * \param p_value jrn.jr_id
 * \param p_sessid PHPSESSID
 * \param p_jrn ledger number
 * \param p_vue easy or expert view of the operation
 */

function modifyOperation(p_value,p_sessid,p_jrn,p_vue)
		{
			var win=window.open('modify_op.php?action=update&p_jrn='+p_jrn+'&line='+p_value+'&PHPSESSID='+p_sessid+'&p_view='+p_vue,'','toolbar=no,width=690,height=410,scrollbars=yes,resizable=yes');
			win.focus();
		}
function RefreshMe() {
window.location.reload();
}
	function dropLink(p_value,p_value2,p_sessid) {
	var win=window.open('modify_op.php?action=delete&line='+p_value+'&line2='+p_value2+'&PHPSESSID='+p_sessid,'Liaison','toolbar=no,width=500,height=400,scrollbars=yes,resizable=yes');
		}
