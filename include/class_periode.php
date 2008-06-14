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
/* $Revision$ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/*!\file 
 * \brief definition of the class periode
 */
/*! 
 * \brief For the periode tables parm_periode and jrn_periode
 */
require_once ('ac_common.php');
require_once ('debug.php');
require_once ('postgres.php');
class Periode {
  var $cn;			/*!< database connection */
  var $jrn_def_id;		/*!< the jr, 0 means all the ledger*/
  var $p_id;			/*!< pk of parm_periode */
  var $status;			/*!< status is CL for closed, OP for
                                   open and CE for centralized */
  var $p_start;			/*!< start of the periode */
  var $p_end;			/*!< end of the periode */
  function __construct($p_cn) {
    $this->cn=$p_cn;
  }
  function set_jrn($p_jrn) {
    $this->jrn_def_id=$p_jrn;
  }
  function set_periode($pp_id){
    $this->p_id=$pp_id;
  }
  /*!\brief return the p_id of the start and the end of the exercice
   *into an array
   *\param $p_exercice 
   *\return array [start]=>,[end]=>
   */
  function limit_year($p_exercice) {
    $sql_start="select p_id from parm_periode where p_exercice=$1 order by p_start  ASC";
    $start=getDbValue($this->cn,$sql_start,array($p_exercice));
    $sql_end="select p_id from parm_periode where p_exercice=$1 order by p_start  ASC";
    $end=getDbValue($this->cn,$sql_end,array($p_exercice));
    return array("start"=>$start,"end"=>$end);
  }
  function is_closed() {
    if ( $this->jrn_def_id != 0 )
    $sql="select status from jrn_periode ".
      " where jrn_def_id=".$this->jrn_def_id.
      " and p_id =".$this->p_id;
    else 
    $sql="select p_closed as status from parm_periode ".
      " where ".
      " p_id =".$this->p_id;
    $res=ExecSql($this->cn,$sql);
    $status=pg_fetch_result($res,0,0);
    echo_debug(__FILE__.':'.__LINE__.'- is_closed','return ',$status);
    if ( $status == 'CL' || $status=='t' ||$status=='CE') 
      return 1;
    return 0;
  }
  function is_open() {
    if ( $this->jrn_def_id != 0 )
      $sql="select status from jrn_periode ".
	" where jrn_def_id=".$this->jrn_def_id.
	" and p_id =".$this->p_id;
    else 
    $sql="select p_closed as status from parm_periode ".
      " where ".
      " p_id =".$this->p_id;
    $res=ExecSql($this->cn,$sql);
    $status=pg_fetch_result($res,0,0);
    if ( $status == 'OP' || $status=='f' ) 
      return 1;
    return 0;
  }
  function is_centralized() {
    if ( $this->jrn_def_id != 0 )
    $sql="select status from jrn_periode ".
      " where jrn_def_id=".$this->jrn_def_id.
      " and p_id =".$this->p_id;
    else 
    $sql="select p_centralized as status from parm_periode ".
      " where ".
      " p_id =".$this->p_id;
    $res=ExecSql($this->cn,$sql);
    $status=pg_fetch_result($res,0,0);
    if ( $status == 'CE' || $status=='t' ) 
      return 1;
    return 0;
  }
  function close() {
    if ( $this->jrn_def_id == 0 ) {
      ExecSql($this->cn,"update parm_periode set p_closed=true where p_id=".
	      $this->p_id);
      ExecSql($this->cn,"update jrn_periode set status='CL' ".
	      " where p_id = ".$this->p_id);

      return;
    }else {
      ExecSql($this->cn,"update jrn_periode set status='CL' ".
	      " where jrn_def_id=".$this->jrn_def_id." and ".
	      " p_id = ".$this->p_id);
      /* if all ledgers have this periode closed then synchro with
	 the table parm_periode
      */
      $nJrn=CountSql( $this->cn,"select * from jrn_periode where ".
			 " p_id=".$this->p_id);
      $nJrnPeriode=CountSql( $this->cn,"select * from jrn_periode where ".
			 " p_id=".$this->p_id." and status='CL'");

      if ( $nJrnPeriode==$nJrn) 
	ExecSql($this->cn,"update parm_periode set p_closed=true where p_id=".$this->p_id);
      return;
    }
  
  }
  function centralized() {
    if ( $this->jrn_def_id == 0 ) {
      ExecSql($this->cn,"update parm_periode set p_central=true");
      return;
    }else {
      ExecSql($this->cn,"update jrn_periode set status='CE' ".
	      " where ".
	      " p_id = ".$this->p_id);
      return;
    }
  
  }
/*!   
 * \brief Display all the periode and their status
 *
 */ 

  function display_form_periode() {
    $str_dossier=dossier::get();

    if ( $this->jrn_def_id==0 ) {
      $Res=ExecSql($this->cn,"select p_id,to_char(p_start,'DD.MM.YYYY') as date_start,to_char(p_end,'DD.MM.YYYY') as date_end,p_central,p_closed,p_exercice
  from parm_periode order by p_start,p_end");
      $Max=pg_NumRows($Res);
      echo '<TABLE ALIGN="CENTER">';
      echo "</TR>";
      echo '<TH> Date d&eacute;but </TH>';
      echo '<TH> Date fin </TH>';
      echo '<TH> Exercice </TH>';
      echo "</TR>";
      
      for ($i=0;$i<$Max;$i++) {
	$l_line=pg_fetch_array($Res,$i);
	echo '<TR>'; 
	echo '<TD ALIGN="CENTER"> '.$l_line['date_start'].'</TD>';
	echo '<TD  ALIGN="CENTER"> '.$l_line['date_end'].'</TD>';
	echo '<TD  ALIGN="CENTER"> '.$l_line['p_exercice'].'</TD>';
	
	if ( $l_line['p_closed'] == 't' )     { 
	  $closed=($l_line['p_central']=='t')?'<TD>Centralis&eacute;e</TD>':'<TD>Ferm&eacute;e</TD>';
	  $change='<TD></TD>';
	  $remove='<TD></TD>';
	} else {
	  $closed='<TD class="mtitle">'; 
	  $closed.='<A class="mtitle" HREF="?p_action=periode&action=closed&p_per='.$l_line['p_id'].'&'.$str_dossier.'"> Cloturer</A>';
	  $change='<TD class="mtitle">';
	  $change.='<A class="mtitle" HREF="?p_action=periode&action=change_per&p_per='.
	    $l_line['p_id']."&p_date_start=".$l_line['date_start'].
	    "&p_date_end=".$l_line['date_end']."&p_exercice=".
	    $l_line['p_exercice']."&$str_dossier\"> Changer</A>";
	  $remove='<TD class="mtitle">';
	  $remove.='<A class="mtitle" HREF="?p_action=periode&action=delete_per&p_per='.
	    $l_line['p_id']."&$str_dossier\"> Efface</A>";
	  
    }
	echo "$closed";
	echo $change;
	
	echo $remove;
	
	echo '</TR>';
	
      }
      echo '<TR> <FORM ACTION="?p_action=periode" METHOD="POST">';
      echo dossier::hidden();
      echo '<TD> <INPUT TYPE="text" NAME="p_date_start" SIZE="10"></TD>';
      echo '<TD> <INPUT TYPE="text" NAME="p_date_end" SIZE="10"></TD>';
      echo '<TD> <INPUT TYPE="text" NAME="p_exercice" SIZE="10"></TD>';
      echo '<TD> <INPUT TYPE="SUBMIT" NAME="add_per" Value="Ajout"</TD>';
      echo '<TD></TD>';
      echo '<TD></TD>';
      echo '</FORM></TR>';
      
      echo '</TABLE>';
      
    } else {
      $Res=ExecSql($this->cn,"select p_id,to_char(p_start,'DD.MM.YYYY') as date_start,to_char(p_end,'DD.MM.YYYY') as date_end,status,p_exercice
  from parm_periode join jrn_periode using (p_id) where jrn_def_id=".$this->jrn_def_id."
 order by p_start,p_end");
      $Max=pg_NumRows($Res);
      $r=ExecSql($this->cn,'select jrn_Def_name from jrn_Def where jrn_Def_id='.
		 $this->jrn_def_id);
      $jrn_name=pg_fetch_result($r,0,0);
      echo '<h2> Journal '.$jrn_name.'</h2>';
      echo '<TABLE ALIGN="CENTER">';
      echo "</TR>";
      echo '<TH> Date d&eacute;but </TH>';
      echo '<TH> Date fin </TH>';
      echo '<TH> Exercice </TH>';
      echo "</TR>";
      
      for ($i=0;$i<$Max;$i++) {
	$l_line=pg_fetch_array($Res,$i);
	echo '<TR>'; 
	echo '<TD ALIGN="CENTER"> '.$l_line['date_start'].'</TD>';
	echo '<TD  ALIGN="CENTER"> '.$l_line['date_end'].'</TD>';
	echo '<TD  ALIGN="CENTER"> '.$l_line['p_exercice'].'</TD>';
	
	if ( $l_line['status'] != 'OP' )     { 
	  $closed=($l_line['status']=='CE')?'<TD>Centralisee</TD>':'<TD>Ferm&eacute;e</TD>';
	} else {
	  $closed='<TD class="mtitle">'; 
	  $closed.='<A class="mtitle" HREF="?p_action=periode&action=closed&p_per='.$l_line['p_id'].'&'.$str_dossier.'&jrn_def_id='.$this->jrn_def_id.'"> Cloturer</A>';
	  $closed.='</td>';	  	  
    }
	echo "$closed";
	
	echo '</TR>';
	
      }
      echo '</TABLE>';

    }
  }
  function insert($p_date_start,$p_date_end,$p_exercice) {
    if (isDate($p_date_start) == null ||
	isDate($p_date_end) == null ||
	strlen (trim($p_exercice)) == 0 ||
	(string) $p_exercice != (string)(int) $p_exercice)
      { 
	return 1;
      }
    $p_id=NextSequence($this->cn,'s_periode');
    $sql=sprintf(" insert into parm_periode(p_id,p_start,p_end,p_closed,p_exercice)".
		 "values (%d,to_date('%s','DD.MM.YYYY'),to_date('%s','DD.MM.YYYY')".
		 ",'f','%s')",   
		 $p_id,
		 $p_date_start,
		 $p_date_end,
		 $p_exercice);
    try {
      StartSql($this->cn);
      $Res=ExecSql($this->cn,$sql);
      $Res=ExecSql($this->cn,"insert into jrn_periode (jrn_def_id,p_id,status) ".
		   "select jrn_def_id,$p_id,'OP' from jrn_def");
      Commit($this->cn);
    } catch (Exception $e) {
      Rollback($this->cn);
      echo_debug(__FILE__.':'.__LINE__.'- Periode insert','Exception ',$e);
      echo_debug(__FILE__.':'.__LINE__.'- Periode insert','Exception ',$e->getMessage());
      return 1;
    }
    return 0;
  }
  /*!\brief load data from database 
   */
  function load() {

    $row=get_array($this->cn,"select p_start,p_end,p_exercice,p_closed,p_central from parm_periode where p_id=$1",
		 array($this->p_id));
    if ($row == null ) return;
    
    $this->p_start=$row[0]['p_start'];
    $this->p_end=$row[0]['p_end'];
    $this->p_exercice=$row[0]['p_exercice'];
    $this->p_closed=$row[0]['p_closed'];
    $this->p_central=$row[0]['p_central'];
  }

  /*!\brief return the max and the min periode of the exercice given
   *in parameter
   *\param $p_exercice is the exercice
   *\return an array of Periode object
   */
  function get_limit($p_exercice)  {

    $max=getDbValue($this->cn,"select p_id from parm_periode where p_exercice=$1 order by p_start asc",array($p_exercice));
    $min=getDbValue($this->cn,"select p_id from parm_periode where p_exercice=$1 order by p_start desc",array($p_exercice));
    $rMax=new Periode($this->cn);
    $rMax->p_id=$max;
    $rMax->load();
    $rMin=new Periode($this->cn);
    $rMin->p_id=$min;
    $rMin->load();
    return array($rMax,$rMin);
  }
/*! 
 * \brief Give the start & end date of a periode
 * 
 * \return array containing the start date & the end date
 *     
 *
 */ 
  public function get_date_limit($p_periode) {
    $sql="select to_char(p_start,'DD.MM.YYYY') as p_start,
              to_char(p_end,'DD.MM.YYYY')   as p_end
       from parm_periode
         where p_id=".$p_periode;
    $Res=ExecSql($this->cn,$sql);
    if ( pg_NumRows($Res) == 0) return null;
    return pg_fetch_array($Res,0);
    
  }

  static function test_me() {
    $cn=DbConnect(dossier::id());
    $obj=new Periode($cn);
    $obj->set_jrn(1);
    $obj->display_form_periode();
  }
}
