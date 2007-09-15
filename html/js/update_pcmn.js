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
 * \brief Open a window to update the pcmn 
 *
 */

function PcmnUpdate(p_value,p_lib,p_parent,p_sessid,p_dossier)
	{
	var win=window.open('line_update.php?l='+p_value+'&n='+p_lib+'&p='+p_parent+'&PHPSESSID='+p_sessid+'&gDossier='+p_dossier,'Modifie','toolbar=no,width=500,height=400,scrollbars=yes,resizable=yes');
	}

function RefreshMe() {
	window.location.reload();
}
	
	