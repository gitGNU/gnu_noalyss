<?php
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
/* $Revision: 1615 $ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/*!\file 
 * \brief Html Input 
 */
 /* \brief          Generate the form for the periode
 * Data Members
  *   - $cn connexion 
  *   - $type the type of the periode OPEN CLOSE NOTCENTRALIZED or ALL
 *   -  $filter_year make a filter on the default exercice by default yes
 *   -  $user if a filter is required then we need who is the user (object User)
 */
require_once('class_html_input.php');
 class IPeriod extends HtmlInput
{
	var $type; /*!< $type the type of the periode OPEN CLOSE NOTCENTRALIZED or ALL */
	var $cn;  /*!< $cn is the database connection */
	var $show_end_date; /*!< $show_end_date is not set or false, do not show the end date */
	var $filter_year; /*!< $filter_year make a filter on the default exercice by default yes */
	var $user;  /*! $user if a filter is required then we need who is the user (object User)*/
	
	/*!   
	 * \brief show the input html for a periode
	 * \return string containing html code for the HTML
	 *       
	 *
	 */ 
	public function input($p_name=null,$p_value=null)
	{
		foreach ($a => array('type','cn') ){
			if ( ! isset ($this->$a) ) throw ('Variable non définie '.$a);
		}
		$this->name=($p_name==null)?$this->name:$p_name;
		$this->value=($p_value==null)?$this->value:$p_value;
		if ( $this->readOnly==true) return $this->display();

		  switch ($this->type) {
		  case CLOSED:
		    $sql_closed="where p_closed=true and p_central = false ";
		    break;
		  case OPEN:
		    $sql_closed="where p_closed=false";
		    break;
		  case NOTCENTRALIZED:
		    $sql_closed="where p_closed=true and p_central = false ";
		    break;
		  case ALL:
		    $sql_closed="";
		    break;
		  default:
		    throw("invalide p_type in "__FILE__.':'__LINE__);
		  }
	  $sql="select p_id,to_char(p_start,'DD.MM.YYYY') as p_start_string,
						to_char(p_end,'DD.MM.YYYY') as p_end_string 
			from parm_periode  
			 $sql_closed ";
		/* Create a filter on the current exercice */
	   if ( ! isset($this->filter_year) || (isset($this->filter_year) && $this->filter_year==true)) 
	   {
		if (! isset($this->user) ) throw (__FILE__.':'.__LINE__.' user is not set');	
		$cond='';
		if ($this->type == 'all' ) $cond='  where true ';
		$cond.=" and p_exercice='".$this->user->get_exercice()."'";
	   }
	   $sql.="  order by p_start,p_end";
	  $Res=ExecSql($p_cn,$sql);
	  $Max=pg_NumRows($Res);
	  if ( $Max == 0 ) return throw ('no row found',2);
	  $ret='<SELECT NAME="'.$this->name.'">';
	  for ( $i = 0; $i < $Max;$i++) {
	    $l_line=pg_fetch_array($Res,$i);
	    if ( $this->value==$l_line['p_id'] )
	      $sel="SELECTED";
	    else
	      $sel="";

	   if ( ! isset($this->show_end_date) || (isset($this->show_end_date) && $this->show_end_date==false)) 
	   {
		$ret.=sprintf('<OPTION VALUE="%s" %s>%s - %s',$l_line['p_id']
			  ,$sel
			  ,$l_line['p_start_string']
			  ,$l_line['p_end_string']);
		} else {
		$ret.=sprintf('<OPTION VALUE="%s" %s>%s ',$l_line['p_id']
			  ,$sel
			  ,$l_line['p_start_string']
			  );
		}

	  }
	  $ret.="</SELECT>";
	  return $ret;
	

	}
	/*!\brief print in html the readonly value of the widget*/
	public function display()
 	{
		$r="not implemented ".__FILE__.":"__LINE__;
		return $r;

	}
	static public function test_me()
 	{

	}
}
