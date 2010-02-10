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
/*! \file
 * \brief Manage the account
 */
/*!
 * \brief Manage the account from the table jrn, jrnx or tmp_pcmn
 */
require_once("class_ihidden.php");
require_once ('class_database.php');
require_once ('class_dossier.php');

class Acc_Account_Ledger {
  var $db;          /*!< $db database connection */
  var $id;          /*!< $id poste_id (pcm_val)*/
  var $label;       /*!< $label label of the poste */
  var $parent;      /*!< $parent parent account */
  var $row;         /*!< $row double array see get_row */
  var $tot_deb;    /*!< value set by  get_row */
  var $tot_cred;    /*!< value by  get_row */
  function __construct ($p_cn,$p_id) {
    $this->db=$p_cn;
    $this->id=$p_id;
  }
  /**
   *@brief get the row thanks the resource
   *@return double array (j_date,deb_montant,cred_montant,description,jrn_name,j_debit,jr_internal)
   *         (tot_deb,tot_credit)
   */
  private function get_row_sql($Res) {
      $array=array();
      $tot_cred=0.0;
      $tot_deb=0.0;
      $Max=Database::num_row($Res);
      if ( $Max == 0 ) return null;
      for ($i=0;$i<$Max;$i++) {
	$array[]=Database::fetch_array($Res,$i);
	if ($array[$i]['j_debit']=='t') {
	  $tot_deb+=$array[$i]['deb_montant'] ;
	} else {
	  $tot_cred+=$array[$i]['cred_montant'] ;
	}
      }
      $this->row=$array;
      $this->tot_deb=$tot_deb;
      $this->tot_cred=$tot_cred;
  return array($array,$tot_deb,$tot_cred);

  }
  /*!
   * \brief  Get data for accounting entry between 2 periode
   *
   * \param  $p_from periode from
   * \param  $p_to   end periode
   * \return double array (j_date,deb_montant,cred_montant,description,jrn_name,j_debit,jr_internal)
   *         (tot_deb,tot_credit
   *
   */
  function get_row($p_from,$p_to)
    {
      $periode=sql_filter_per($this->db,$p_from,$p_to,'p_id','jr_tech_per');

      $Res=$this->db->exec_sql("select distinct jr_id,to_char(j_date,'DD.MM.YYYY') as j_date_fmt,j_date,".
	       "case when j_debit='t' then j_montant else 0 end as deb_montant,".
	       "case when j_debit='f' then j_montant else 0 end as cred_montant,".
	       " jr_comment as description,jrn_def_name as jrn_name,".
	       "j_debit, jr_internal,jr_pj_number ".
	       " from jrnx left join jrn_def on jrn_def_id=j_jrn_def ".
	       " left join jrn on jr_grpt_id=j_grpt".
	       " where j_poste=".$this->id." and ".$periode.
	       " order by j_date");
      return $this->get_row_sql($Res);
    }
  /*!
   * \brief  Get data for accounting entry between 2 date
   *
   * \param  $p_from date from
   * \param  $p_to   end date
   * \return double array (j_date,deb_montant,cred_montant,description,jrn_name,j_debit,jr_internal)
   *         (tot_deb,tot_credit
   *
   */
  function get_row_date($p_from,$p_to)
    {
      $Res=$this->db->exec_sql("select distinct jr_id,to_char(j_date,'DD.MM.YYYY') as j_date_fmt,j_date,".
	       "case when j_debit='t' then j_montant else 0 end as deb_montant,".
	       "case when j_debit='f' then j_montant else 0 end as cred_montant,".
	       " jr_comment as description,jrn_def_name as jrn_name,".
	       "j_debit, jr_internal,jr_pj_number ".
	       " from jrnx left join jrn_def on jrn_def_id=j_jrn_def ".
	       " left join jrn on jr_grpt_id=j_grpt".
	       " where j_poste=$1 and ".
	       " ( to_date($2,'DD.MM.YYYY') <= j_date and ".
			       "   to_date($3,'DD.MM.YYYY') >= j_date )".
	       " order by j_date",array($this->id,$p_from,$p_to));
      return $this->get_row_sql($Res);
    }


  /*!\brief Return the name of a account
   *        it doesn't change any data member
   * \return string with the pcm_lib
   */
  function get_name() {
    $ret=$this->db->exec_sql(
		 "select pcm_lib from tmp_pcmn where
                  pcm_val=$1",array($this->id));
      if ( Database::num_row($ret) != 0) {
	$r=Database::fetch_array($ret);
	$this->name=$r['pcm_lib'];
      } else {
	$this->name="Poste inconnu";
      }
    return $this->name;
  }
  /*!\brief check if the poste exist in the tmp_pcmn
   *\return the number of line (normally 1 or 0)
   */
  function do_exist() {
    $sql="select pcm_val from tmp_pcmn where pcm_val= $1";
    $ret=$this->db->exec_sql($sql,array($this->id));
    return Database::num_row($ret) ;
  }
  /*!\brief Get all the value for this object from the database
   *        the data member are set
   * \return false if this account doesn't exist otherwise true
   */
  function load()
  {
    $ret=$this->db->exec_sql("select pcm_lib,pcm_val_parent from
                              tmp_pcmn where pcm_val=$1",array($this->id));
    $r=Database::fetch_all($ret);

    if ( ! $r ) return false;
    $this->label=$r[0]['pcm_lib'];
    $this->parent=$r[0]['pcm_val_parent'];
    return true;

  }
  /*!\brief Get all the value for this object from the database
   *        the data member are set
   * \return false if this account doesn't exist otherwise true
   */
  function get()
    {
      echo "OBSOLETE Acc_Account_Ledger->get(), a remplacer par Acc_Account_Ledger->load()";
      return $this->load();
    }

  /*!
   * \brief  give the balance of an account
   *
   * \return
   *      balance of the account
   *
   */
  function get_solde($p_cond=" true ") {
  $Res=$this->db->exec_sql("select sum(deb) as sum_deb, sum(cred) as sum_cred from
          ( select j_poste,
             case when j_debit='t' then j_montant else 0 end as deb,
             case when j_debit='f' then j_montant else 0 end as cred
          from jrnx join tmp_pcmn on j_poste=pcm_val
              where
            j_poste::text like ('$this->id'::text) and
            $p_cond
          ) as m  ");
  $Max=Database::num_row($Res);
  if ($Max==0) return 0;
  $r=Database::fetch_array($Res,0);

  return abs($r['sum_deb']-$r['sum_cred']);
}
  /*!
   * \brief   give the balance of an account
   * \return
   *      balance of the account
   *
   */
function get_solde_detail($p_cond="") {

  if ( $p_cond != "") $p_cond=" and ".$p_cond;
  $sql="select sum(deb) as sum_deb, sum(cred) as sum_cred from
          ( select j_poste,
             case when j_debit='t' then j_montant else 0 end as deb,
             case when j_debit='f' then j_montant else 0 end as cred
          from jrnx
              where
            j_poste::text like ('$this->id'::text)
            $p_cond
          ) as m  ";

 $Res=$this->db->exec_sql($sql);
 $Max=Database::num_row($Res);

 if ($Max==0) {
   return array('debit'=>0,
	       'credit'=>0,
	       'solde'=>0)     ;
 }
 $r=Database::fetch_array($Res,0);
 // if p_start is < p_end the query returns null to avoid any problem
 // we set it to 0
 if ($r['sum_deb']=='')
   $r['sum_deb']=0.0;
 if ($r['sum_cred']=='')
   $r['sum_cred']=0.0;

  return array('debit'=>$r['sum_deb'],
	       'credit'=>$r['sum_cred'],
	       'solde'=>abs($r['sum_deb']-$r['sum_cred']));
}
/*!
 * \brief isTva tell is a poste is used for VAT
 * \param none
 *
 *
 * \return 1 is Yes otherwise 0
 */
 function isTVA()
   {
      // Load TVA array
     $a_TVA=$this->db->get_array('select tva_poste
                                from tva_rate');
     foreach ( $a_TVA as $line_tva)
       {
	 if ( $line_tva['tva_poste']  == '' )
	   continue;
	 list($tva_deb,$tva_cred)=explode(',',$line_tva['tva_poste']);
	 if ( $this->id == $tva_deb ||
	      $this->id == $tva_cred )
	   {
	     return 1;
	   }
       }
     return 0;

   }
/*!
 * \brief HtmlTable, display a HTML of a poste for the asked period
 * \param none
 *
 * \return none
 */


 function HtmlTable()
   {
     $this->get_name();
     list($array,$tot_deb,$tot_cred)=$this->get_row_date( $_REQUEST['from_periode'],
						     $_REQUEST['to_periode']
						     );

     if ( count($this->row ) == 0 )
       return;

     $rep="";

     echo '<h2 class="info">'.$this->id." ".$this->name.'</h2>';
     echo "<TABLE class=\"result\" width=\"100%\">";
     echo "<TR>".
       "<TH> Code interne </TH>".
       "<TH> Date</TH>".
       "<TH> Description </TH>".
       "<TH> D&eacute;bit  </TH>".
	"<TH> Cr&eacute;dit </TH>".
       "</TR>";

     foreach ( $this->row as $op ) {
       echo "<TR>".
	 "<TD>".$op['jr_internal']."</TD>".
	 "<TD>".$op['j_date']."</TD>".
	 "<TD>".h($op['description']).' '.h($op['jr_pj_number'])."</TD>".
	 "<TD>".$op['deb_montant']."</TD>".
	 "<TD>".$op['cred_montant']."</TD>".
	 "</TR>";

     }
     $solde_type=($tot_deb>$tot_cred)?"solde débiteur":"solde créditeur";
     $diff=round(abs($tot_deb-$tot_cred),2);
     echo "<TR>".
       "<TD>$solde_type</TD>".
       "<TD>$diff</TD>".
       "<TD></TD>".
       "<TD>$tot_deb</TD>".
       "<TD>$tot_cred</TD>".
       "</TR>";

     echo "</table>";

     return;
   }

 /*!
  * \brief Display HTML Table Header (button)
  *
  * \return none
  */
 static function HtmlTableHeader()
   {
     $hid=new IHidden();
     echo '<div class="noprint">';
     echo "<table >";
     echo '<TR>';

     echo '<TD><form method="GET" ACTION="">'.
	   dossier::hidden().
       HtmlInput::submit('bt_other',"Autre poste").
       $hid->input("type","poste").$hid->input('p_action','impress')."</form></TD>";

     echo '<TD><form method="POST" ACTION="poste_pdf.php">'.
	   dossier::hidden().
       HtmlInput::submit('bt_pdf',"Export PDF").
       $hid->input("type","poste").
       $hid->input('p_action','impress').
       $hid->input("poste_id",$_REQUEST['poste_id']).
       $hid->input("from_periode",$_REQUEST['from_periode']).
       $hid->input("to_periode",$_REQUEST['to_periode']);
     if (isset($_REQUEST['poste_fille']))
       echo $hid->input('poste_fille','on');
     if (isset($_REQUEST['oper_detail']))
       echo $hid->input('oper_detail','on');

     echo "</form></TD>";

     echo '<TD><form method="POST" ACTION="poste_csv.php">'.
	   dossier::hidden().
       HtmlInput::submit('bt_csv',"Export CSV").
       $hid->input("type","poste").
       $hid->input('p_action','impress').
       $hid->input("poste_id",$_REQUEST['poste_id']).
       $hid->input("from_periode",$_REQUEST['from_periode']).
       $hid->input("to_periode",$_REQUEST['to_periode']);
     if (isset($_REQUEST['poste_fille']))
       echo $hid->input('poste_fille','on');
     if (isset($_REQUEST['oper_detail']))
       echo $hid->input('oper_detail','on');

     echo "</form></TD>";
     echo "</table>";
     echo '</div>';

   }
/*!
 * \brief verify that the poste belong to a ledger
 *
 * \return 0 ok,  -1 no
 */
 function belong_ledger($p_jrn) {
   $filter=$this->db->get_value("select jrn_def_class_cred from jrn_def where jrn_def_id=$p_jrn");
   if ( trim ($filter) == '')
     return 0;

    $valid_cred=explode(" ",$filter);
    $sql="select count(*) as poste from tmp_pcmn where ";
    // Creation query
    $or="";
    $SqlFilter="";
    foreach ( $valid_cred as $item_cred) {
      if ( strlen (trim($item_cred))) {
	if ( strstr($item_cred,"*") == true ) {
	  $item_cred=strtr($item_cred,"*","%");
	  $SqlItem="$or pcm_val::text like '$item_cred'";
	  $or="  or ";
	} else {
	  $SqlItem="$or pcm_val::text = '$item_cred' ";
	  $or="  or ";
	}
	$SqlFilter=$SqlFilter.$SqlItem;
      }
    }//foreach
    $sql.=$SqlFilter.' and pcm_val='.$this->id;
    $max=$this->db->get_value($sql);
    if ($max > 0 )
      return 0;
    else
      return -1;
 }
  /*!\brief With the id of the ledger, get the col jrn_def_class_deb
   *\param $p_jrn jrn_id
   *\return array of value, or an empty array if nothing is found
   *\note
   *\see
   */
 function get_account_ledger($p_jrn) {
   $l=new Acc_Ledger($this->db,$p_jrn);
   $row=$l->get_propertie();
   if ( strlen(trim($row['jrn_def_class_deb'])) == 0 ) return array();
   $valid_account=explode(" ",$row['jrn_def_class_deb']);
   return $valid_account;
 }
  /*!\brief build a sql statement thanks a array found with get_account_ledger
   *
   *\param $p_jrn jrn_id
   *\return an emty string if nothing is found or a valid SQL statement like
\code
pcm_val like ... or pcm_val like ...
\endcode
   *\note
   *\see get_account_ledger
   */
 function build_sql_account($p_jrn) {
   $array=$this->get_account_ledger($p_jrn);
   if ( empty($array) ) return "";
   $sql="";
   foreach ( $array as $item_cred) {
      if ( strlen (trim($item_cred))>0 ) {
	if ( strstr($item_cred,"*") == true ) {
	  $item_cred=strtr($item_cred,"*","%");
	  $sql_tmp=" pcm_val::text like '$item_cred' or";
	} else {
	  $sql_tmp=" pcm_val::text = '$item_cred' or";
	}
	$sql.=$sql_tmp;
      }
    }//foreach
   /* remove the last or */
   $sql=substr($sql,0,strlen($sql)-2);
   return $sql;
 }
 static function test_me() {
     $cn=new Database(dossier::id());
     $a=new Acc_Account_Ledger($cn,550);
     echo ' Journal 4 '.$a->belong_ledger(4);

 }
}
