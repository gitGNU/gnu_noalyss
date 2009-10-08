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

/*!\file 
 * \brief javascript script, always added to every page
 *
 */

/*!\brief remove trailing and heading space
 * \param the string to modify
 * \return string without heading and trailing space
 */
function trim(s) {
    return s.replace(/^\s+/, '').replace(/\s+$/, '');
}

/**
 * @brief retrieve an element thanks its ID
 * @param ID is a string
 * @return the found object of undefined if not found
 */
function g(ID) {
  if (document.getElementById) {
    return document.getElementById(ID);
  } else   if (document.all) {
    return document.all[ID];
  }  else {
    return undefined;
  }
}
/**
 *@brief enable the type of periode
 */
function enable_type_periode() {
	if ( g('type_periode').value == 1 ) {
		g('from_periode').disabled=true;
		g('to_periode').disabled=true;
		g('from_date').disabled=false;
		g('to_date').disabled=false;
		g('p_step').disabled=true;
	} else {
		g('from_periode').disabled=false;
		g('to_periode').disabled=false;
		g('from_date').disabled=true;
		g('to_date').disabled=true;
		g('p_step').disabled=false;
	}
}
 /** 
 * @brief set a DOM id with a value in the parent window (the caller),
  @param p_ctl is the name of the control
  @param p_value is the value to set in
 */
 function set_inparent(p_ctl,p_value) {
   self.opener.set_value(p_ctl,p_value);
 }

 /** 
 * @brief set a DOM id with a value, it will consider if it the attribute
 	value or innerHTML has be used
  @param p_ctl is the name of the control
  @param p_value is the value to set in
 */
 function set_value(p_ctl,p_value) {       
	if ( g(p_ctl)) {
		var g_ctrl=g(p_ctl);
		if ( g_ctrl.value ) { g(p_ctl).value=p_value;}
		if ( g_ctrl.innerHTML ) { g(p_ctl).innerHTML=p_value;}
	}
}
/**
 *@brief will reload the window but it is dangerous if we have submitted
 * a form 
 */
function refresh_window() {
	window.location.reload();
}
/**
 * @brief object ajax
 */ 
﻿var Ajax=function() {
	var xhr;
	var synchrone=false;
	var page,param;
	this.setSynchronous = function() { this.synchrone=true;}
	this.setAsynchronous = function() { this.synchrone=false;}
	/**
	 * @brief create an ajax object and set the page and the parameter
	 * @param page : the page to call (or url)
	 * @param param ()optional) is the parameter (it is a url string ex: a=1&b=2 )
	 */
	 this.createAjax = function (page,param) {
		if (window.XMLHttpRequest){
			this.xhr=new XMLHttpRequest(); }
		else if (window.ActiveXObject) {
			this.xhr=new ActiveXObject("Microsoft.XMLHTTP"); }
		else   {alert("Your browser does not support XMLHTTP!");}
		this.xhr.onreadystatechange=this.onSuccess;
		this.page=page;

		if ( param == undefined ) {
			this.param='';
		} else {
			this.param=param;	
		}
	}
	/*
	* readyState	Description
	* 0	The request is not initialized
	* 1	The request has been set up
	* 2	The request has been sent
	* 3	The request is in process
	* 4	The request is complete <== we receive an answer
	*/
	
	/* xmlhttp.status
	* 200 ok
	* 404 page not found
	* 500 internal error
	* 403 forbidden 
	* ...
	*/
	/**
	 *@brief do a get in ajax
	 */
	this.getPage = function () {
		try {
		var uri='';
		if ( trim(this.param)!='') 
			{uri=this.page+'?'+this.param;}
		else 
			{ uri=this.page;}
		this.xhr.open('GET',uri,false);
		this.xhr.send(null);
		
		} catch (e) { alert('Error Ajax.getPage '+e.message); exit();}
	}
	/**
	 *@brief do a get in ajax
	 */
	this.postPage = function () {
		this.xhr.open('POST',this.page,this.synchrone);
		this.xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		this.xhr.setRequestHeader("Content-length", this.param.length);
		this.xhr.setRequestHeader("Connection", "close");	
		this.xhr.send(this.param);
	}
	this.onSuccess = function(pFunction) {
		if ( this.xhr.readyState == 4 ) {
			if (this.xhr.status==200) {pFunction(this.xhr);}
			if (this.xhr.status==404) {alert('Page not found'); }
			}
		}
}
/**
 *@brief we receive a json object as parameter and the function returns the string
 *       with the format variable=value&var2=val2... 
 */
encodeJSON=function(obj) {
	if (typeof obj != 'object') {alert('encodeParameter  obj n\'est pas  un objet');}
	try{
		var str='';var e=0;
		for (i in obj){
			if (e != 0 ) {str+='&';} else {e=1;}
			str+=i;
			str+='='+encodeURI(obj[i]);
		}
		return str;
	} catch(e){alert('encodeParameter '+e.message);}
}
