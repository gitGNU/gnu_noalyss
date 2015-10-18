//This file is part of NOALYSS and is under GPL 
//see licence.txt
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function show_dbname(obj) {
	try {
		if (obj.checked === true)
		{
			this.document.getElementById('div_db').style.visibility= 'visible';
		}
		else {
                        this.document.getElementById('div_db').style.visibility= 'hidden';
		}
	} catch (e) {
		alert_box(e.getMessage);
	}
}
