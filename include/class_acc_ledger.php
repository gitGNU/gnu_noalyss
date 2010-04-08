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
require_once("class_iselect.php");
require_once("class_icard.php");
require_once("class_ispan.php");
require_once("class_ihidden.php");
require_once("class_idate.php");
require_once("class_itext.php");
require_once("class_icheckbox.php");
require_once('class_iperiod.php');
require_once('class_fiche.php');
require_once('class_user.php');
require_once ('class_dossier.php');
require_once ('class_own.php');
require_once ('class_anc_operation.php');
require_once ('class_acc_operation.php');
require_once ('class_acc_account_ledger.php');
require_once ('class_pre_op_advanced.php');
require_once ('jrn.php');
require_once ('class_acc_reconciliation.php');
require_once ('class_periode.php');
require_once ('class_gestion_purchase.php');
require_once ('class_acc_account.php');
require_once('ac_common.php');
require_once('class_inum.php');
/*!\file
* \brief Class for jrn,  class acc_ledger for manipulating the ledger
*/

/*!\brief Class for jrn,  class acc_ledger for manipulating the ledger
*
*/
class Acc_Ledger {
  var $id;			/*!< jrn_def.jrn_def_id */
  var $name;			/*!< jrn_def.jrn_def_name */
  var $db;			/*!< database connextion */
  var $row;			/*!< row of the ledger */
  var $type;			/*!< type of the ledger ACH ODS FIN
				  VEN or GL */
  var $nb;			/*!< default number of rows by
				  default 10 */

  function Acc_Ledger ($p_cn,$p_id){
    $this->id=$p_id;
    $this->db=$p_cn;
    $this->row=null;
    $this->nb=10;
  }
  function get_last_pj() {
    if ( $this->db->exist_sequence("s_jrn_pj".$this->id) ) {
      $ret= $this->db->get_array("select last_value,is_called from s_jrn_pj".$this->id);
	  $last=$ret[0]['last_value'];
	  /*!
	       *\note  With PSQL sequence , the last_value column is 1 when before   AND after the first call, to make the difference between them
	       * I have to check whether the sequence has been already called or not */
	  if ($ret[0]['is_called']=='f' ) $last--;
	  return $last;
	  }
    else
      $this->db->create_sequence("s_jrn_pj".$this->id);
    return 0;
  }
  /*!
   * \brief Return the type of a ledger (ACH,VEN,ODS or FIN) or GL
   *
   */
  function get_type() {
    if ( $this->id==0 ) {
      $this->name=" Grand Livre ";
      $this->type="GL";
      return "GL";
    }

    $Res=$this->db->exec_sql("select jrn_def_type from ".
		 " jrn_def where jrn_def_id=".
		 $this->id);
    $Max=Database::num_row($Res);
    if ($Max==0) return null;
    $ret=Database::fetch_array($Res,0);
    $this->type=$ret['jrn_def_type'];
    return $ret['jrn_def_type'];
  }
  /*!
   * \brief Return the name of a ledger
   *
   */
  function get_name() {
    if ( $this->id==0 ) {
      $this->name=" Grand Livre ";
      return $this->name;
    }

    $Res=$this->db->exec_sql("select jrn_def_name from ".
		 " jrn_def where jrn_def_id=".
		 $this->id);
    $Max=Database::num_row($Res);
    if ($Max==0) return null;
    $ret=Database::fetch_array($Res,0);
    $this->name=$ret['jrn_def_name'];
    return $ret['jrn_def_name'];
  }


  /*! \function  get_row
   * \brief  Get The data
   *
   *
   * \param p_from from periode
   * \param p_to to periode
   * \param $p_cent (on or off)
   * \param p_limit starting line
   * \param p_offset number of lines
   * \return Array with the asked data
   *
   */
  function get_row($p_from,$p_to,$cent='off',$p_limit=-1,$p_offset=-1) {

    echo_debug('class_acc_ledger.php',__LINE__,"get_row ( $p_from,$p_to,$cent,$p_limit,$p_offset)");

    $periode=sql_filter_per($this->db,$p_from,$p_to,'p_id','jr_tech_per');

    $cond_limite=($p_limit!=-1)?" limit ".$p_limit." offset ".$p_offset:"";
    // retrieve the type
    $this->get_type();
    // Grand livre == 0
    if ( $this->id != 0 ) {

      if ( $cent=='off' ) {
	echo_debug('class_acc_ledger.php',__LINE__,"journaux non  centralise");
	// Journaux non centralises
	$Res=$this->db->exec_sql("select j_id,j_id as int_j_id,to_char(j_date,'DD.MM.YYYY') as j_date,
	      jr_internal,
	case j_debit when 't' then j_montant::text else '   ' end as deb_montant,
	case j_debit when 'f' then j_montant::text else '   ' end as cred_montant,
	j_debit as debit,j_poste as poste,jr_montant , ".
		     "coalesce(j_text,pcm_lib) as description,j_grpt as grp,
jr_comment||' ('||jr_internal||')'||case when jr_pj_number is not null and jr_pj_number  !='' then jr_pj_number else '' end  as jr_comment,
       j_qcode,
	jr_rapt as oc, j_tech_per as periode
      from jrnx left join jrn on ".
		     "jr_grpt_id=j_grpt ".
		     " left join tmp_pcmn on pcm_val=j_poste ".
		     " where j_jrn_def=".$this->id.
		     " and ".$periode." order by j_date::date asc,jr_internal,j_debit desc ".
		     $cond_limite);
      }else {
	// Journaux centralises
	//      echo'class_acc_ledger.php',__LINE__,"journaux centralise";
	$Sql="select jr_opid as j_id,
	    c_order as int_j_id,
    to_char (c_date,'DD.MM.YYYY') as j_date ,
    c_internal as jr_internal,
    case c_debit when 't' then c_montant::text else '   ' end as deb_montant,
    case c_debit when 'f' then c_montant::text else '   ' end as cred_montant,
    c_debit as j_debit,
    c_poste as poste,
    coalesce(j_text,pcm_lib) as description,
    j_qcode,
jr_comment||' ('||c_internal||')'||case when jr_pj_number is not null and jr_pj_number  !='' then 'pj:'||coalesce(jr_pj_number,'-') else '' end  as jr_comment,
    jr_montant,
    c_grp as grp,
    c_comment as comment,
    c_rapt as oc,
    c_periode as periode
    from

  centralized left join jrnx on j_id=c_j_id
 left join tmp_pcmn on pcm_val=c_poste
 left join jrn on jr_grpt_id=c_grp
   where ".
	  " c_jrn_def=".$this->id." and ".
	  $periode." order by c_order ";
	$Res=$this->db->exec_sql($Sql.$cond_limite);

      }
    } else {
      // Grand Livre
      if ( $cent == 'off') {
	echo_debug('class_acc_ledger.php',__LINE__,"Grand livre non centralise");
	// Non centralise
	$Res=$this->db->exec_sql("select j_id,j_id as int_j_id,to_char(j_date,'DD.MM.YYYY') as j_date,
	      jr_internal,
	case j_debit when 't' then j_montant::text else '   ' end as deb_montant,
	case j_debit when 'f' then j_montant::text else '   ' end as cred_montant,
	j_debit as debit,j_poste as poste,".
		     "coalesce(j_text,pcm_lib) as description,j_grpt as grp,
jr_comment||' ('||jr_internal||')'||case when jr_pj_number is not null and jr_pj_number  !='' then 'pj:'||coalesce(jr_pj_number,'-') else '' end as jr_comment,
	jr_montant,
	j_qcode,
	jr_rapt as oc, j_tech_per as periode from jrnx left join jrn on ".
		     "jr_grpt_id=j_grpt left join tmp_pcmn on pcm_val=j_poste where ".
		     "  ".$periode." order by j_date::date,j_grpt,j_debit desc   ".
		     $cond_limite);

      } else {
	echo_debug('class_acc_ledger.php',__LINE__,"Grand livre  centralise");
	// Centralise
	$Sql="select jr_c_opid as j_id,
	   c_order as int_j_id,
    c_j_id,
    to_char (c_date,'DD.MM.YYYY') as j_date ,
    c_internal as jr_internal,
    case c_debit when 't' then c_montant::text else '   ' end as deb_montant,
    case c_debit when 'f' then c_montant::text else '   ' end as cred_montant,
    c_debit as j_debit,
    c_poste as poste,
    coalesce(j_text,pcm_lib) as description,
jr_comment||' ('||c_internal||')'||case when jr_pj_number is not null and jr_pj_number  !='' then 'pj:'||coalesce(jr_pj_number,'-') else '' end as jr_comment,
    jr_montant,
    c_grp as grp,
    c_comment||' ('||c_internal||' '||jr_opid||')'||'pj:'||coalesce(jr_pj_number,'-') as comment,
    c_rapt as oc,
    j_qcode,
    c_periode as periode
    from centralized left join jrn on ".
	  "jr_grpt_id=c_grp left join tmp_pcmn ".
	  " on (pcm_val=c_poste)  ".
	  "            join jrnx on (j_id=c_j_id)".
	  " where ".
	  $periode." order by c_order ";
	$Res=$this->db->exec_sql($Sql.$cond_limite);
      } // Grand Livre
    }


    $array=array();
    $Max=Database::num_row($Res);
    if ($Max==0) return null;
    $case="";
    $tot_deb=0;
    $tot_cred=0;
    $row=Database::fetch_all($Res);
    for ($i=0;$i<$Max;$i++) {
      $fiche=new fiche($this->db);
      $line=$row[$i];
      $mont_deb=($line['deb_montant']!=0)?sprintf("% 8.2f",$line['deb_montant']):"";
      $mont_cred=($line['cred_montant']!=0)?sprintf("% 8.2f",$line['cred_montant']):"";
      $jr_montant=($line['jr_montant']!=0)?sprintf("% 8.2f",$line['jr_montant']):"";
      $tot_deb+=$line['deb_montant'];
      $tot_cred+=$line['cred_montant'];
      $tot_op=$line['jr_montant'];
      echo_debug('class_acc_ledger.php',__LINE__," get_row : mont_Deb ".$mont_deb);
      echo_debug('class_acc_ledger.php',__LINE__," get_row : mont_cred ".$mont_cred);

      /* Check first if there is a quickcode */
      if ( strlen(trim($line['j_qcode'])) != 0 )
	{
	  if ( $fiche->get_by_qcode($line['j_qcode'],false) == 0 )
	    {
	      $line['description']=$fiche->strAttribut(ATTR_DEF_NAME);
	    }
	}
      if ( $case != $line['grp'] ) {
	$case=$line['grp'];
	// for financial, we show if the amount is or not in negative
	if ( $this->type=='FIN') {
	  echo_debug(__FILE__,__LINE__,"Journal FIN");
	  $eMax=(($i+20) < $Max)?$i+20:$Max;
	  // check in $row if the BQE is in deb or cred
	  for ($e=$i;$e<$Max;$e++) {
	    echo_debug(__FILE__,__LINE__,$row[$e]);
	    if ( $row[$e]['grp'] != $case ) continue;
	    if ( strlen(trim($row[$e]['j_qcode'])) == 0 ) continue;

	    $f=new fiche($this->db);
	    $f->get_by_qcode($row[$e]['j_qcode'],false);
	    echo_debug(__FILE__,__LINE__,$f);
	    if ( $f->get_fiche_def_ref_id() == FICHE_TYPE_FIN ) {
	      $tot_op=($row[$e]['debit'] == 't')?$jr_montant:" - ".$jr_montant;
	      break;
	    }
	  }
	}
	$array[]=array (
			'int_j_id' => $line['int_j_id'],
			'j_id'=>$line['j_id'],
			'j_date' => $line['j_date'],
			'internal'=>$line['jr_internal'],
			'deb_montant'=>'',
			'cred_montant'=>' ',
			'description'=>'<b><i>'.h($line['jr_comment']).' ['.$tot_op.'] </i></b>',
			'poste' => $line['oc'],
			'qcode' => $line['j_qcode'],
			'periode' =>$line['periode'] );

	$array[]=array (
			'int_j_id' => $line['int_j_id'],
			'j_id'=>'',
			'j_date' => '',
			'internal'=>'',
			'deb_montant'=>$mont_deb,
			'cred_montant'=>$mont_cred,
			'description'=>$line['description'],
			'poste' => $line['poste'],
			'qcode' => $line['j_qcode'],
			'periode' => $line['periode']
			);

      }else {
	$array[]=array (
			'int_j_id' => $line['int_j_id'],
			'j_id'=>'',
			'j_date' => '',
			'internal'=>'',
			'deb_montant'=>$mont_deb,
			'cred_montant'=>$mont_cred,
			'description'=>$line['description'],
			'poste' => $line['poste'],
			'qcode' => $line['j_qcode'],
			'periode' => $line['periode']);

      }


    }
    echo_debug('class_acc_ledger.php',__LINE__,"Total debit $tot_deb,credit $tot_cred");
    $this->row=$array;
    $a=array($array,$tot_deb,$tot_cred);
    return $a;
  }
  /*! \brief  Get simplified row from ledger
   *
   * \param from periode
   * \param to periode
   * \param centralized (on or off)
   * \param p_limit starting line
   * \param p_offset number of lines
   * \param trunc if data must be truncated (pdf export)
   *
   * \return an Array with the asked data
   */
  function get_rowSimple($p_from,$p_to,$cent='off',$trunc=0,$p_limit=-1,$p_offset=-1)
  {
    // Grand-livre : id= 0
    //---
    $jrn=($this->id == 0 )?"":"and jrn_def_id = ".$this->id;
    // Non Centralise si cent=off
    //--
    if ($cent=='off')
      {// Non centralise

	$periode=sql_filter_per($this->db,$p_from,$p_to,'p_id','jr_tech_per');

	$cond_limite=($p_limit!=-1)?" limit ".$p_limit." offset ".$p_offset:"";
	//---
	$sql="
	  SELECT jrn.jr_id as jr_id ,
	      jrn.jr_id as num ,
	      jrn.jr_def_id as jr_def_id,
	      jrn.jr_montant as montant,
	      substr(jrn.jr_comment,1,30)|| case when jr_pj_number is not null and jr_pj_number  !='' then 'pj:'||coalesce(jr_pj_number,'-') else '' end as comment,
	      to_char(jrn.jr_date,'DD-MM-YYYY') as date,
	      jr_internal,
	      jrn.jr_grpt_id as grpt_id,
	      jrn.jr_pj_name as pj,
	      jrn_def_type,
	       jrn.jr_tech_per
	  FROM jrn join jrn_def on (jrn_def_id=jr_def_id)
	  WHERE $periode $jrn order by jr_date $cond_limite";
      }
    else
      {

	$periode=sql_filter_per($this->db,$p_from,$p_to,'p_id','jr_tech_per');

	$cond_limite=($p_limit!=-1)?" limit ".$p_limit." offset ".$p_offset:"";
	//Centralise
	//---
	$id=($this->id == 0 ) ?"jr_c_opid as num":"jr_opid as num";

	$sql="
    SELECT jrn.jr_id as jr_id ,
	   $id ,
	   jrn.jr_def_id as jr_def_id,
	   jrn.jr_montant as montant,
	      substr(jrn.jr_comment,1,30)|| case when jr_pj_number is not null and jr_pj_number  !='' then 'pj:'||coalesce(jr_pj_number,'-') else '' end as comment,
	   to_char(jrn.jr_date,'DD-MM-YYYY') as date,
	   jr_internal,
	   jrn.jr_grpt_id as grpt_id,
	   jrn.jr_pj_name as pj,
	   jrn_def_type,
	   jrn.jr_tech_per
   FROM jrn join jrn_def on (jrn_def_id=jr_def_id)
       where
	 $periode $jrn and
	 jr_opid is not null
	 order by num  $cond_limite";
      }// end else $cent=='off'
    //load all data into an array
    //---

    $Res=$this->db->exec_sql($sql);
    $Max=Database::num_row($Res);
    if ( $Max == 0 )
      {
	return null;
      }
    $type=$this->get_type();
    // for type ACH and Ven we take more info
    if (  $type == 'ACH' ||  	  $type == 'VEN')
      {
	$a_ParmCode=$this->db->get_array('select p_code,p_value from parm_code');
	$a_TVA=$this->db->get_array('select tva_id,tva_label,tva_poste
			 from tva_rate where tva_rate != 0 order by tva_id');
	for ( $i=0;$i<$Max;$i++)
	  {
	    $array[$i]=Database::fetch_array($Res,$i);
	    $p=$this->get_detail($array[$i],$type,$trunc,$a_TVA,$a_ParmCode);
	    if ( $array[$i]['dep_priv'] != 0.0) {
	      $array[$i]['comment'].="(priv. ".$array[$i]['dep_priv'].")";
	    }
	  }

      }
    else
      {
	$array=Database::fetch_all($Res);

      }

    return $array;
  }// end function get_rowSimple

  /*!\brief guess what  the next pj should be
   */
  function guess_pj() {
    $prop=$this->get_propertie();
    $pj_pref=$prop["jrn_def_pj_pref"];
    $pj_seq=$this->get_last_pj()+1;
    return $pj_pref.$pj_seq;
  }

  /*!\brief Show all the operation
   *\param $sql is the sql stmt, normally created by build_search_sql
   *\param $offset the offset
   *\param $p_paid if we want to see info about payment
\code
// Example
// Build the sql
 list($sql,$where)=$Ledger->build_search_sql($_GET);
// Count nb of line
  $max_line=$cn->count_sql($sql);

  $step=$_SESSION['g_pagesize'];
  $page=(isset($_GET['offset']))?$_GET['page']:1;
  $offset=(isset($_GET['offset']))?$_GET['offset']:0;
// create the nav. bar
  $bar=jrn_navigation_bar($offset,$max_line,$step,$page);
// show a part
  list($count,$html)= $Ledger->list_operation($sql,$offset,0);
  echo $html;
// show nav bar
  echo $bar;

\endcode
   *\see build_search_sql
   *\see display_search_form
   *\see search_form

   *\return HTML string
   */
  public function list_operation($sql,$offset,$p_paid=0) {
    $user=new User($this->db);
    $gDossier=dossier::id();
    $amount_paid=0.0;
    $amount_unpaid=0.0;
    include_once("central_inc.php");
    $limit=($_SESSION['g_pagesize']!=-1)?" LIMIT ".$_SESSION['g_pagesize']:"";
    $offset=($_SESSION['g_pagesize']!=-1)?" OFFSET ".Database::escape_string($offset):"";
    $order="  order by jr_date_order asc,jr_internal asc";
    // Sort
    $url=CleanUrl();
    $str_dossier=dossier::get();
    $image_asc='<IMAGE SRC="image/down.gif" border="0" >';
    $image_desc='<IMAGE SRC="image/up.gif" border="0">';
    $image_sel_desc='<IMAGE SRC="image/select1.gif">';
    $image_sel_asc='<IMAGE SRC="image/select2.gif">';

    $sort_date="<th>  <A class=\"mtitle\" HREF=\"?$url&o=da\">$image_asc</A>"._('Date')."<A class=\"mtitle\" HREF=\"?$url&o=dd\">$image_desc</A></th>";
    $sort_description="<th>  <A class=\"mtitle\" HREF=\"?$url&o=ca\">$image_asc</A>"._('Description')."<A class=\"mtitle\" HREF=\"?$url&o=cd\">$image_desc</A></th>";
    $sort_amount="<th>  <A class=\"mtitle\" HREF=\"?$url&o=ma\">$image_asc</A>"._('Montant')." <A class=\"mtitle\" HREF=\"?$url&o=md\">$image_desc</A></th>";
    $sort_pj="<th>  <A class=\"mtitle\" HREF=\"?$url&o=pja\">$image_asc</A>"._('PJ')."<A class=\"mtitle\" HREF=\"?$url&o=pjd\">$image_desc</A></th>";
    $sort_echeance="<th>  <A class=\"mtitle\" HREF=\"?$url&o=ea\">$image_asc</A>"._('Ech')." <A class=\"mtitle\" HREF=\"?$url&o=ed\">$image_desc</A> </th>";

    $own=new Own($this->db);
    // if an order is asked
    if ( isset ($_GET['o']) )
      {
	switch ($_GET['o'])
	  {
	  case 'pja':
	    // pj asc
	  $sort_pj="<th>$image_sel_asc PJ <A class=\"mtitle\" HREF=\"?$url&o=pjd\">$image_desc</A></th>";
	  $order=" order by jr_pj_number asc ";
	  break;
	case 'pjd':
	  $sort_pj="<th> <A class=\"mtitle\" HREF=\"?$url&o=pja\">$image_asc</A> PJ $image_sel_desc</th>";
	  // pj desc
	  $order=" order by jr_pj_number desc ";
	  break;

	case 'da':
	  // date asc
	  $sort_date="<th>$image_sel_asc Date <A class=\"mtitle\" HREF=\"?$url&o=dd\">$image_desc</A></th>";
	  $order=" order by jr_date_order asc ";
	  break;
	case 'dd':
	  $sort_date="<th> <A class=\"mtitle\" HREF=\"?$url&o=da\">$image_asc</A> Date $image_sel_desc</th>";
	  // date desc
	  $order=" order by jr_date_order desc ";
	  break;
	case 'ma':
	  // montant asc
	  $sort_amount="<th> $image_sel_asc Montant <A class=\"mtitle\" HREF=\"?$url&o=md\">$image_desc</A></th>";
	  $order=" order by jr_montant asc ";
	  break;
	case 'md':
	  // montant desc
	  $sort_amount="<th>  <A class=\"mtitle\" HREF=\"?$url&o=ma\">$image_asc</A>Montant $image_sel_desc</th>";
	  $order=" order by jr_montant desc ";
	  break;
	case 'ca':
	  // jr_comment asc
	  $sort_description="<th> $image_sel_asc Description <A class=\"mtitle\" HREF=\"?$url&o=cd\">$image_desc</A></th>";
	  $order=" order by jr_comment asc ";
	  break;
	case 'cd':
	  // jr_comment desc
	  $sort_description="<th>  <A class=\"mtitle\" HREF=\"?$url&o=ca\">$image_asc</A>Description $image_sel_desc</th>";
	  $order=" order by jr_comment desc ";
	  break;
	case 'ea':
	  // jr_comment asc
	  $sort_echeance="<th> $image_sel_asc Ech. <A class=\"mtitle\" HREF=\"?$url&o=ed\">$image_desc</A></th>";
	  $order=" order by jr_ech asc ";
	  break;
	case 'ed':
	  // jr_comment desc
	  $sort_echeance="<th>  <A class=\"mtitle\" HREF=\"?$url&o=ea\">$image_asc</A> Ech. $image_sel_desc</th>";
	  $order=" order by jr_ech desc ";
	  break;

	}
      } else {
      // date asc
      $sort_date="<th>$image_sel_asc Date <A class=\"mtitle\" HREF=\"?$url&o=dd\">$image_desc</A></th>";
      $order=" order by jr_date_order asc ";
    }
    // set a filter for the FIN
    $a_parm_code=$this->db->get_array("select p_value from parm_code where p_code in ('BANQUE','COMPTE_COURANT','CAISSE')");
    $sql_fin="(";
    $or="";
    foreach ($a_parm_code as $code) {
      $sql_fin.="$or j_poste::text like '".$code['p_value']."%'";
      $or=" or ";
    }
    $sql_fin.=")";

    // Count
    $count=$this->db->count_sql($sql);
  // Add the limit
  $sql.=$order.$limit.$offset;

  // Execute SQL stmt
  $Res=$this->db->exec_sql($sql);

  //starting from here we can refactor, so that instead of returning the generated HTML,
  //this function returns a tree structure.

  $r="";

  $r.=JS_LEDGER;

  $Max=Database::num_row($Res);

  if ($Max==0) return array(0,_("Aucun enregistrement trouvé"));

  $r.='<table class="result">';
  $l_sessid=$_REQUEST['PHPSESSID'];

  $r.="<tr >";
  $r.="<th>Internal</th>";
  if ( $this->type=='') {
    $r.=th('Journal');
  }
  $r.=$sort_date;
  $r.=$sort_echeance;
  $r.=$sort_pj;
  $r.=$sort_description;
  $r.=$sort_amount;
  // if $p_paid is not equal to 0 then we have a paid column
  if ( $p_paid != 0 )
    {
      $r.="<th> "._('Payé')."</th>";
    }
  $r.="<th>"._('Op. Concernée')."</th>";
  if ($own->MY_STRICT=='N' &&  $user->check_action(GEOP)==1)
    $r.='<th>'._('Action').'</th>';
  $r.="<th>"._('Document')."</th>";
  $r.="</tr>";
  // Total Amount
  $tot=0.0;
  $gDossier=dossier::id();
  for ($i=0; $i < $Max;$i++) {


    $row=Database::fetch_array($Res,$i);

    if ( $i % 2 == 0 ) $tr='<TR class="odd">';
		else $tr='<TR class="even">';
    $r.=$tr;
    //internal code
	// button  modify
    $r.="<TD>";
    // If url contains
    //

    $href=basename($_SERVER['PHP_SELF']);
	echo_debug(__FILE__,__LINE__,"href = $href");
    switch ($href)
      {
		// user_jrn.php
      case 'compta.php':
		$vue="S"; //Expert View
		break;
      case 'commercial.php':
		$vue="S"; //Simple View
		break;
	  case 'recherche.php':
		$vue=(isset($_GET['expert']))?'E':'S';
		break;
      default:
		echo_error('user_form_ach.php',__LINE__,'Erreur invalid request uri');
		exit (-1);
      }
    //DEBUG
    //    $r.=$l_sessid;
    $r.=sprintf('<A class="detail" HREF="javascript:modifyOperation(\'%s\',\'%s\',\'%s\',\'%s\',\'%s\')" >%s</A>',
				$row['jr_id'], $l_sessid,$gDossier, $row['jrn_def_id'],$vue, $row['jr_internal']);
    $r.="</TD>";
    if ( $this->type=='') $r.=td($row['jrn_def_name']);
    // date
    $r.="<TD>";
    $r.=$row['jr_date'];
    $r.="</TD>";
    // echeance
    $r.="<TD>";
    $r.=$row['jr_ech'];
    $r.="</TD>";

    // pj
    $r.="<TD>";
    $r.=$row['jr_pj_number'];
    $r.="</TD>";

    // comment
    $r.="<TD>";
    $tmp_jr_comment=h($row['jr_comment']);
    $r.=$tmp_jr_comment;
    $r.="</TD>";

    // Amount
    // If the ledger is financial :
    // the credit must be negative and written in red
    $positive=0;

    // Check ledger type :
     if (  $row['jrn_def_type'] == 'FIN' )
     {
       $positive = $this->db->count_sql("select * from jrn inner join jrnx on jr_grpt_id=j_grpt ".
 			   " where jr_id=".$row['jr_id']." and $sql_fin ".
 			   " and j_debit='f'");
     }
    $r.="<TD align=\"right\">";

    $tot=($positive != 0)?$tot-$row['jr_montant']:$tot+$row['jr_montant'];
    //STAN $positive always == 0
     $r.=( $positive != 0 )?"<font color=\"red\">  - ".sprintf("%8.2f",$row['jr_montant'])."</font>":sprintf("%8.2f",$row['jr_montant']);
    $r.="</TD>";


    // Show the paid column if p_paid is not null
    if ( $p_paid !=0 )
      {
		$w=new ICheckBox();
		$w->name="rd_paid".$row['jr_id'];
		$w->selected=($row['jr_rapt']=='paid')?true:false;
		// if p_paid == 2 then readonly
		$w->readonly=( $p_paid == 2)?true:false;
		$h=new IHidden();
		$h->name="set_jr_id".$row['jr_id'];
		$r.='<TD>'.$w->input().$h->input().'</TD>';
		if ( $row['jr_rapt']=='paid')
		  $amount_paid+=$row['jr_montant'];
		else
		  $amount_unpaid+=$row['jr_montant'];
      }

    // Rapprochement
    $rec=new Acc_Reconciliation($this->db);
    $rec->set_jr_id($row['jr_id']);
    $a=$rec->get();
    $r.="<TD>";
    if ( $a != null ) {

      foreach ($a as $key => $element)
      {
	$operation=new Acc_Operation($this->db);
	$operation->jr_id=$element;
	$l_amount=$this->db->get_value("select jr_montant from jrn ".
					 " where jr_id=$element");
	$r.= "<A class=\"detail\" HREF=\"javascript:modifyOperation('".$element."','".$l_sessid."',".$gDossier.")\" > ".$operation->get_internal()." [ $l_amount &euro; ]</A>";
      }//for
    }// if ( $a != null ) {
    $r.="</TD>";

    if ( $row['jr_valid'] == 'f'  ) {
      $r.="<TD> Op&eacute;ration annul&eacute;e</TD>";
    }    else {
      // all operations can be removed either by setting to 0 the amount
      // or by writing the opposite operation if the period is closed
      $r.="<TD>";
      // cancel operation
      if ( $user->check_action(GEOP)==1)
	$r.=sprintf('<input TYPE="BUTTON" VALUE="%s" onClick="cancelOperation(\'%s\',\'%s\',%d,\'%s\')">',
		    _("Effacer"),$row['jr_grpt_id'],$l_sessid,$gDossier,$row['jrn_def_id']);
      $r.="</TD>";
    } // else
    //document
    if ( $row['jr_pj_name'] != "")
      {
	$image='<IMG SRC="image/insert_table.gif" title="'.$row['jr_pj_name'].'" border="0">';
	$r.="<TD>".sprintf('<A class="detail" HREF="show_pj.php?jrn=%s&jr_grpt_id=%s&%s&PHPSESSID=%s">%s</A>',
			   $row['jrn_def_id'],
			   $row['jr_grpt_id'],
			   $str_dossier,
			   $_REQUEST['PHPSESSID'],
			   $image)
			   ."</TD>";
      }
    else
      $r.="<TD></TD>";

    // end row
    $r.="</tr>";

  }
  $amount_paid=round($amount_paid,4);
  $amount_unpaid=round($amount_unpaid,4);
  $tot=round($tot,4);
  $r.="<TR>";
  $r.='<TD COLSPAN="5">Total</TD>';
  $r.='<TD ALIGN="RIGHT">'.$tot."</TD>";
  $r.="</tr>";
  if ( $p_paid != 0 ) {
	$r.="<TR>";
	$r.='<TD COLSPAN="5">Pay&eacute;</TD>';
	$r.='<TD ALIGN="RIGHT">'.$amount_paid."</TD>";
	$r.="</tr>";
	$r.="<TR>";
	$r.='<TD COLSPAN="5">Non pay&eacute;</TD>';
	$r.='<TD ALIGN="RIGHT">'.$amount_unpaid."</TD>";
	$r.="</tr>";
  }
  $r.="</table>";

  return array ($count,$r);
}

  /*!
   * \brief get_detail gives the detail of row
   * this array must contains at least the field
   *       <ul>
   *       <li> montant</li>
   *       <li> grpt_id
   *       </ul>
   * the following field will be added
   *       <ul>
   *       <li> HTVA
   *       <li> TVAC
   *       <li> TVA array with
   *          <ul>
   *          <li> field 0 idx
   *          <li> array containing tva_id,tva_label and tva_amount
   *          </ul>
   *       </ul>
   *
   * \param p_array the structure is set in get_rowSimple, this array is
   *        modified,
   * \param $trunc if the data must be truncated, usefull for pdf export
   * \param p_jrn_type is the type of the ledger (ACH or VEN)
   * \param $a_TVA TVA Array (default null)
   * \param $a_ParmCode Array (default null)
   * \return p_array
   */
  function get_detail(&$p_array,$p_jrn_type,$trunc=0,$a_TVA=null,$a_ParmCode=null)
  {
    echo_debug(__FILE__.':'.__LINE__.'- get_detail','$p_array',$p_array);
    if ( $a_TVA == null )
      {
	//Load TVA array
	$a_TVA=$this->db->get_array('select tva_id,tva_label,tva_poste
			 from tva_rate where tva_rate != 0 order by tva_id');
      }
    if ( $a_ParmCode == null )
      {
	//Load Parm_code
	$a_ParmCode=$this->db->get_array('select p_code,p_value from parm_code');
      }
    // init
    $p_array['client']="";
    $p_array['TVAC']=0;
    $p_array['TVA']=array();
    $p_array['AMOUNT_TVA']=0.0;
    $p_array['dep_priv']=0;
    $dep_priv=0.0;
    //
    // Retrieve data from jrnx
    $sql="select j_id,j_poste,j_montant, j_debit,j_qcode from jrnx where ".
      " j_grpt=".$p_array['grpt_id'];
    $Res2=$this->db->exec_sql($sql);
    $data_jrnx=Database::fetch_all($Res2);
    $c=0;

    // Parse data from jrnx and fill diff. field
    foreach ( $data_jrnx as $code ) {
      $idx_tva=0;
      echo_debug('class_acc_ledger',__LINE__,'Code is');
      echo_debug('class_acc_ledger',__LINE__,$code);
      $poste=new Acc_Account_Ledger($this->db,$code['j_poste']);

      // if card retrieve name if the account is not a VAT account
      if ( strlen(trim($code['j_qcode'] )) != 0 && $poste->isTva() == 0 )
	{
	  echo_debug('class_acc_ledger',__LINE__,'fiche_def = '.$code['j_qcode']);
	  $fiche=new fiche($this->db);
	  $fiche->get_by_qcode(trim($code['j_qcode']),false);
	  $fiche_def_id=$fiche->get_fiche_def_ref_id();
	  // Customer or supplier
	  if ( $fiche_def_id == FICHE_TYPE_CLIENT ||
	       $fiche_def_id == FICHE_TYPE_FOURNISSEUR )
	    {
	      echo_debug('class_acc_ledger',__LINE__,$code['j_qcode'].'est F ou C');
	      $p_array['TVAC']=$code['j_montant'];

	      $p_array['client']=($trunc==0)?$fiche->getName():substr($fiche->getName(),0,20);
	      $p_array['reversed']=false;
	      if (	$fiche_def_id == FICHE_TYPE_CLIENT && $code['j_debit']=='f')
		{
		  $p_array['reversed']=true;
		  $p_array['TVAC']*=-1;

		}
	      if (	$fiche_def_id == FICHE_TYPE_FOURNISSEUR && $code['j_debit']=='t')
		{
		  $p_array['reversed']=true;
		  $p_array['TVAC']*=-1;
		}


	    } else {
	    // if we use the ledger ven / ach for others card than supplier and customer
	    if ( $fiche_def_id != FICHE_TYPE_VENTE &&
		 $fiche_def_id != FICHE_TYPE_ACH_MAR &&
		 $fiche_def_id != FICHE_TYPE_ACH_SER ) {
	      echo_debug('class_acc_ledger',__LINE__,$code['j_qcode']."n 'est PAS F ou C");
	      $p_array['TVAC']=$code['j_montant'];

	      $p_array['client']=	($trunc==0)?$fiche->getName():substr($fiche->getName(),0,20);
	      $p_array['reversed']=false;
	      if ($p_jrn_type == 'ACH' && $code['j_debit']=='t')
		{
		  $p_array['reversed']=true;
		  $p_array['TVAC']*=-1;

		}
	      if ($p_jrn_type == 'VEN'  && $code['j_debit']=='f')
		{
		  $p_array['reversed']=true;
		  $p_array['TVAC']*=-1;
		}




	    }
	  }
	}
      echo_debug('class_acc_ledger',__LINE__,$a_TVA);
      // if TVA, load amount, tva id and rate in array
      foreach ( $a_TVA as $line_tva)
	{
	  echo_debug('class_acc_ledger',__LINE__,'ICI');
	  echo_debug('class_acc_ledger',__LINE__,'Montant TVA = '.$p_array['AMOUNT_TVA']);
	  list($tva_deb,$tva_cred)=explode(',',$line_tva['tva_poste']);
	  if ( $code['j_poste'] == $tva_deb ||
	       $code['j_poste'] == $tva_cred )
	    {

	      // For the reversed operation
	      if ( $p_jrn_type == 'ACH' && $code['j_debit'] == 'f')
		{
		  $code['j_montant']=-1*$code['j_montant'];
		}
	      if ( $p_jrn_type == 'VEN' && $code['j_debit'] == 't')
		{
		  $code['j_montant']=-1*$code['j_montant'];
		}

	      $p_array['AMOUNT_TVA']+=$code['j_montant'];

	      $p_array['TVA'][$c]=array($idx_tva,array($line_tva['tva_id'],$line_tva['tva_label'],$code['j_montant']));
	      echo_debug('class_acc_ledger',__LINE__,'Montant TVA = '.$p_array['AMOUNT_TVA']);
	      $c++;

	      $idx_tva++;
	    }
	}

      // isDNA
      // If operation is reversed then  amount are negatif
    /* if ND */
      if ( $p_array['jrn_def_type'] == 'ACH') {
	$purchase=new Gestion_Purchase($this->db);
	$purchase->search_by_jid($code['j_id']);
	$purchase->load();
	$dep_priv+=$purchase->qp_dep_priv;
	echo_debug(__FILE__.':'.__LINE__.'- get_detail','$dep_priv',$dep_priv);
	$p_array['dep_priv']=$dep_priv;
      }

    }
    $p_array['TVAC']=sprintf('% 10.2f',$p_array['TVAC']-$dep_priv);
    $p_array['HTVA']=sprintf('% 10.2f',$p_array['TVAC']-$p_array['AMOUNT_TVA']);
    $r="";
    $a_tva_amount=array();
    // inline TVA (used for the PDF)
    foreach ($p_array['TVA'] as $linetva)
      {
	foreach ($a_TVA as $tva)
	  {
	    if ( $tva['tva_id'] == $linetva[1][0] )
	      {
		$a=$tva['tva_id'];
		$a_tva_amount[$a]=$linetva[1][2];
	      }
	  }
      }
    foreach ($a_TVA as $line_tva)
      {
	$a=$line_tva['tva_id'];
	if ( isset($a_tva_amount[$a]))
	  {
	    $tmp=sprintf("% 10.2f",$a_tva_amount[$a]);
	    $r.="$tmp";
	  }
	else
	  $r.=sprintf("% 10.2f",0);
      }
    $p_array['TVA_INLINE']=$r;

    return $p_array;
  }  // retrieve data from jrnx
  /*!
   * \brief  Get the properties of a journal
   *
   * \return an array containing properties
   *
   */
  function get_propertie()
  {
    if ( $this->id == 0 ) return;

    $Res=$this->db->exec_sql("select jrn_Def_id,jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_def_type,
	   jrn_deb_max_line,jrn_cred_max_line,jrn_def_ech,jrn_def_ech_lib,jrn_def_code,
	   jrn_def_fiche_deb,jrn_def_fiche_deb,jrn_def_pj_pref
	   from jrn_Def
	      where jrn_def_id=$1",array($this->id));
    $Count=Database::num_row($Res);
    if ( $Count == 0 ) {
      echo '<DIV="redcontent"><H2 class="error">'._('Parametres journaux non trouves').'</H2> </DIV>';
      return null;
    }
    return Database::fetch_array($Res,0);
  }

  /*! \function GetDefLine
   * \brief Get the number of lines of a journal
   * \param $p_cred deb or cred
   *
   * \return an integer
   */
  function GetDefLine()
  {
    $sql_cred='jrn_deb_max_line';
    $sql="select jrn_deb_max_line as value from jrn_def where jrn_def_id=$1";
    $r=$this->db->exec_sql($sql,array($this->id));
    $Res=Database::fetch_all($r);
    echo_debug('class_acc_ledger',__LINE__,$Res);
    if ( sizeof($Res) == 0 ) return 1;
    return $Res[0]['value'];
  }
  /*!\brief get the saldo of a ledger for a specific period
   * \param $p_from start period
   * \param $p_to end period
   * \param $p_cent 1 for a centralized period otherwise 0
   */
  function get_solde($p_from,$p_to,$p_cent) {
    $ledger="";
    if ( $this->id != 0 && $p_cent=='off') {
      $ledger=" and j_jrn_def = ".$this->id;
    }

    if ( $this->id != 0 && $p_cent=='on') {
      $ledger=" and c_jrn_def = ".$this->id;
    }

    // we ask for a specific ledger
    if ( $p_cent == 'off') {
      $periode=sql_filter_per($this->db,$p_from,$p_to,'p_id','j_tech_per');
      $sql='select j_montant as montant,j_debit as deb from jrnx where '
	.$periode.$ledger;
    }else {
      $periode=sql_filter_per($this->db,$p_from,$p_to,'p_id','c_periode');
      $sql='select c_montant as montant,c_debit as deb from centralized where '
	.$periode.$ledger;
    }
    $ret=$this->db->exec_sql($sql);
    $array=Database::fetch_all($ret);
    $deb=0.0;
    $cred=0.0;
    foreach ($array as $line) {

      if ( $line['deb']=='t' )
	$deb+=$line['montant'];
      else
	$cred+=$line['montant'];
    }
    $response=array($deb,$cred);
    return $response;
  }
  /*!
   * \brief Show a select list of the ledger you can access in
   * writing, the security is taken in care but show the readable AND
   * writable ledger.
   * \param $p_type = ALL or the type of the ledger (ACH,VEN,FIN,ODS)
   * \param $p_access =3 for READ and WRITE, 2 for write and 1 for readonly
   * \return object HtmlInput select
   */
  function select_ledger($p_type="ALL",$p_access=3) {
    $user=new User($this->db);
    $array=$user->get_ledger($p_type,$p_access);

    if ( $array == null ) return null;
    $idx=0;
    $ret=array();

    foreach ( $array as $value) {
      $ret[$idx]['value']=$value['jrn_def_id'];
      $ret[$idx]['label']=h($value['jrn_def_name']);
      $idx++;
    }

    $select=new ISelect();
    $select->name='p_jrn';
    $select->value=$ret;
    $select->selected=$this->id;
    return $select;
  }
  /*!
   * \brief retrieve the jrn_def_fiche and return them into a array
   *        index deb, cred
   * \param
   * \param
   * \param
   *
   *
   * \return return an array ('deb'=> ,'cred'=>)
   */
  function get_fiche_def() {
    $sql="select jrn_def_fiche_deb as deb,jrn_def_fiche_cred as cred ".
      " from jrn_def where ".
      " jrn_def_id = $1 ";

    $r=$this->db->exec_sql($sql,array($this->id));

    $res=Database::fetch_all($r);
    if ( empty($res) ) return null;

    return $res[0];
  }
  /*!
   * \brief retrieve the jrn_def_class_deb and return it
   *
   *
   * \return return an string
   */
  function get_class_def() {
    $sql="select jrn_def_class_deb  ".
      " from jrn_def where ".
      " jrn_def_id = $1";

    $r=$this->db->exec_sql($sql,array($this->id));

    $res=Database::fetch_all($r);

    if ( empty($res) ) return null;

    return $res[0];
  }

  /*!
   * \brief show the result of the array
   * \param $p_array array from the form
   * \return string
   */
  function show_summary($p_array) {
    $this->id=$p_array['p_jrn'];
    if ( empty($p_array)) return 'Aucun r&eacute;sultat';
    extract($p_array);
    $ret="";
    $ret.="<table>";
    $ret.="<tr><td>"._('Date')." : </td><td>$date</td></tr>";
    $ret.="<tr><td>"._('Description')." </td><td>".h($desc)."</td></tr>";
    $ret.="<tr><td>"._('PJ Num')." </td><td>".h($e_pj)."</td></tr>";
    $ret.='</table>';
    $ret.="<table>";
    $ret.="<tr>";
    $ret.="<th>"._('Quick Code ou');
    $ret.=_("Poste")." </th>";
    $ret.="<th> "._("Montant")." </th>";
    $ret.="<th>"._("Débit")."</th>";
    $ret.="</tr>";
    $own=new own($this->db);

    $ret.=HtmlInput::hidden('date',$date);
    $ret.=HtmlInput::hidden('desc',$desc);
    $ret.=HtmlInput::hidden('e_pj',$e_pj);
    $ret.=HtmlInput::hidden('e_pj_suggest',$e_pj_suggest);
    $mt=microtime(true);
    $ret.=HtmlInput::hidden('mt',$mt);
    // For predefined operation
    $ret.=HtmlInput::hidden('e_comm',$desc);
    $ret.=HtmlInput::hidden('jrn_type',$this->get_type());
    $ret.=HtmlInput::hidden('p_jrn',$this->id);
    $ret.=HtmlInput::hidden('nb_item',$nb_item);
    if ( $this->with_concerned==true) {
      $ret.=HtmlInput::hidden('jrn_concerned',$jrn_concerned);
    }
    $ret.=dossier::hidden();
    $count=0;
    for ($i=0;$i<$nb_item;$i++) {
      $ret.="<tr>";
      if ( trim(${'qc_'.$i})!="") {
	$oqc=new fiche($this->db);
	$oqc->get_by_qcode(${'qc_'.$i},false);
	$strPoste=$oqc->strAttribut(ATTR_DEF_ACCOUNT);
	$ret.="<td>".
	  ${'qc_'.$i}.' - '.
	  $oqc->strAttribut(ATTR_DEF_NAME).HtmlInput::hidden('qc_'.$i,${'qc_'.$i}).
			'</td>';
      }

      if ( trim(${'qc_'.$i})=="" && trim(${'poste'.$i}) != "") {
	$oposte=new Acc_Account_Ledger($this->db,${'poste'.$i});
	$strPoste=$oposte->id;
	$ret.="<td>".h(${"poste".$i}." - ".
	      $oposte->get_name()).HtmlInput::hidden('poste'.$i,${'poste'.$i}).
             '</td>';
      }

      if ( trim(${'qc_'.$i})=="" && trim(${'poste'.$i}) == "")
	continue;
      $ret.="<td>".h(${"ld".$i}).HtmlInput::hidden('ld'.$i,${'ld'.$i})."</td>";
      $ret.="<td>".${"amount".$i}.HtmlInput::hidden('amount'.$i,${'amount'.$i})."</td>";
      $ret.="<td>";
      $ret.=(isset(${"ck$i"}))?"D":"C";
      $ret.=(isset(${"ck$i"}))?HtmlInput::hidden('ck'.$i,${'ck'.$i}):"";
      $ret.="</td>";
      // CA

      if (  $own->MY_ANALYTIC!='nu') // use of AA
	{
	  if ( ereg("^[6,7]+",$strPoste)) {
	    // show form
	    $op=new Anc_Operation($this->db);
	    $null=($own->MY_ANALYTIC=='op')?1:0;
	    $ret.='<td>';
	    $ret.=$op->display_form_plan(null,$null,1,$count,round(${'amount'.$i},2));
	    $ret.='</td>';
	    $count++;
	  }

	}



      $ret.="</tr>";
    }
    $ret.="</table>";
    return $ret;
  }

  /*!
   * \brief Show the form to encode your operation
   * \param $p_array if you correct or use a predef operation
   * \param $p_readonly 1 for readonly 0 for writable
   *
   * \return a string containing the form
   */
  function show_form($p_array=null,$p_readonly=0)
  {
    if ( $p_readonly == 1 )
      return $this->show_summary($p_array);

    if ( $p_array != null )
      extract($p_array);

    $ret="";
    /* Add button */
    $f_add_button=new IButton('add_card');
    $f_add_button->label=_('Créer une nouvelle fiche');
    $f_add_button->set_attribute('ipopup','ipop_newcard');
    $f_add_button->set_attribute('filter',$this->get_all_fiche_def ());
    //    $f_add_button->set_attribute('jrn',$this->id);
    $f_add_button->javascript=" select_card_type(this);";
    $ret.=$f_add_button->input();


    // Load the javascript
    //
    $ret.="<table>";
    $ret.= '<tr><td>';
    $wDate=new IDate('date');
    $wDate->readonly=$p_readonly;
    $date=(isset($date)&&trim($date)!='')?$date:'';
    if (trim($date)=='') {
      $user=new User($this->db);
      $periode=new Periode($this->db);
      list ($l_date_start,$l_date_end)=$periode->get_date_limit($user->get_periode());
      $date=$l_date_start;
    }
    $wDate->value=$date;

    $ret.=_("Date").' : '.$wDate->input();
    $ret.= '</td></tr>';

    $ret.= '<tr><td>'._('Description');
    $wDescription=new IText('desc');
    $wDescription->readonly=$p_readonly;
    $wDescription->size=100;
    $wDescription->value=(isset($desc))?$desc:'';
    $ret.=$wDescription->input();
    $ret.= '</td>';

    $wPJ=new IText('e_pj');
    $wPJ->readonly=false;
    $wPJ->size=10;

    /* suggest PJ ? */
    $default_pj='';
    $own=new Own($this->db);
    if ( $own->MY_PJ_SUGGEST=='Y') {
      $default_pj=$this->guess_pj();
    }
    $wPJ->value=(isset($e_pj))?$e_pj:$default_pj;

    $ret.='<td> '._('Pièce').' : '.$wPJ->input();
    $ret.=HtmlInput::hidden('e_pj_suggest',$default_pj);
    $ret.= '</td></tr>';

    $ret.= '</table>';
    $nb_row=(isset($nb_item) )?$nb_item:$this->nb;

    $ret.=HtmlInput::hidden('nb_item',$nb_row);
    $ret.=dossier::hidden();

    $ret.=HtmlInput::hidden('p_jrn',$this->id);
    $ret.=dossier::hidden();
    $ret.=HtmlInput::hidden('phpsessid',$_REQUEST['PHPSESSID']);

    $ret.=HtmlInput::hidden('jrn_type',$this->get_type());
    $info= HtmlInput::infobulle(0);
    $info_poste=HtmlInput::infobulle(9);
    $ret.='<table id="quick_item" style="width:100%">';
    $ret.='<tr>'.
      '<th >Quickcode'.$info.'</th>'.
      '<th >'._('Poste').$info_poste.'</th>'.
      '<th >'._('Libellé').'</th>'.
      '<th>'._('Montant').'</th>'.
      '<th>'._('Débit').'</th>'.
      '</tr>';
    $l_sessid=$_REQUEST['PHPSESSID'];

    for ($i = 0 ;$i<$nb_row;$i++){
      // Quick Code
      $quick_code=new ICard('qc_'.$i);
      $quick_code->set_dblclick("fill_ipopcard(this);");
      $quick_code->set_attribute('ipopup','ipopcard');

      // name of the field to update with the name of the card
      $quick_code->set_attribute('label',"ld".$i);
      $quick_code->set_attribute('jrn',$this->id);

      // name of the field to update with the name of the card
      $quick_code->set_attribute('typecard','filter');
      $quick_code->extra='filter';
      // Add the callback function to filter the card on the jrn
      $quick_code->set_callback('filter_card');
      $quick_code->set_function('fill_data');
      $quick_code->javascript=sprintf(' onchange="fill_data_onchange(\'%s\');" ',
			      $quick_code->name);

      $quick_code->jrn=$this->id;
      $quick_code->value=(isset(${'qc_'.$i}))?${'qc_'.$i}:"";
      $quick_code->readonly=$p_readonly;

      $label='';
      if ( $quick_code->value != '' ) {
	$Fiche=new fiche($this->db);
	$Fiche->get_by_qcode($quick_code->value);
	$label=$Fiche->strAttribut(ATTR_DEF_NAME);
      }


      // Account
      $poste=new IPoste();
      $poste->name='poste'.$i;
      $poste->set_attribute('jrn',$this->id);
      $poste->set_attribute('ipopup','ipop_account');
      $poste->set_attribute('label','ld'.$i);
      $poste->set_attribute('account','poste'.$i);

      $poste->value=(isset(${'poste'.$i}))?${"poste".$i}:'';
      $poste->readonly=$p_readonly;

      if ( $poste->value != '' ) {
	$Poste=new Acc_Account($this->db);
	$Poste->set_parameter('value',$poste->value);
	$label=$Poste->get_lib();
      }

      // Description of the line
      $line_desc=new IText();
      $line_desc->name='ld'.$i;
      $line_desc->size=30;
      $line_desc->value=(isset(${"ld".$i}))?${"ld".$i}:$label;

      // Amount
      $amount=new INum();
      $amount->size=10;
      $amount->name='amount'.$i;
      $amount->value=(isset(${'amount'.$i}))?${"amount".$i}:'';
      $amount->readonly=$p_readonly;
      $amount->javascript=' onChange="format_number(this);checkTotalDirect()"';
      // D/C
      $deb=new ICheckBox();
      $deb->name='ck'.$i;
      $deb->selected=(isset(${'ck'.$i}))?true:false;
      $deb->readonly=$p_readonly;
      $deb->javascript=' onChange="checkTotalDirect()"';

      $ret.='<tr>';
      $ret.='<td>'.$quick_code->search().$quick_code->input().'</td>';
      $ret.='<td>'.$poste->input().
	'<script> document.getElementById(\'poste'.$i.'\').onblur=function(){ if (trim(this.value) !=\'\') {document.getElementById(\'qc_'.$i.'\').value="";}}</script>'.
	'</td>';
      $ret.='<td>'.$line_desc->input().'</td>';
      $ret.='<td>'.$amount->input().'</td>';
      $ret.='<td>'.$deb->input().'</td>';
      $ret.='</tr>';
      // If readonly == 1 then show CA
    }
    $ret.='</table>';
    if ( isset ($this->with_concerned) && $this->with_concerned==true) {
      $oRapt=new Acc_Reconciliation($this->db);
      $w=$oRapt->widget();
      $w->name='jrn_concerned';
      $w->value=(isset($jrn_concerned))?$jrn_concerned:"";
      $ret.="R&eacute;conciliation/rapprochements : ".$w->input();
    }
    return $ret;
  }

  /*!\brief
   * check if the current ledger is closed
   *\return 1 for yes, otherwise 0
   *\see Periode::is_closed
   */
  function is_closed($p_periode) {
    $per=new Periode($this->db);
    $per->set_jrn($this->id);
    $per->set_periode($p_periode);
    $ret=$per->is_closed();
    echo_debug(__FILE__.':'.__LINE__.'- is_closed','return',$ret);
    return $ret;

  }
  /*!
   * \brief verify that the operation can be saved
   * \param $p_array array of data same layout that the $_POST from show_form
   *
   *
   * \throw  the getcode  value is 1 incorrect balance,  2 date
   * invalid, 3 invalid amount,  4 the card is not in the range of
   * permitted card, 5 not in the user's period, 6 closed period
   *
   */
  function verify($p_array)
  {
    extract ($p_array);
    $user=new User($this->db);
    $tot_cred=0;$tot_deb=0;

    /* check if we can write into this ledger */
    $user=new User($this->db);
    if ( $user->check_jrn($p_jrn) != 'W' )
      throw new Exception (_('Accès interdit'),20);

    /* check for a double reload */
    if ( isset($mt) && $this->db->count_sql('select jr_mt from jrn where jr_mt=$1',array($mt)) != 0 )
      throw new Exception ('Double Encodage',5);

    // Check the periode and the date
    if ( isDate($date) == null ) {
      throw new Exception('Date invalide', 2);
    }
    $periode=new Periode($this->db);
    /* find the periode  if we have enabled the check_periode*/
    if ($this->check_periode()==false) {
      $periode->find_periode($date);
    } else {
      $periode->p_id=$user->get_periode();
      list ($l_date_start,$l_date_end)=$periode->get_date_limit();
      // Date dans la periode active
      if ( cmpDate($date,$l_date_start)<0 ||
	   cmpDate($date,$l_date_end)>0 )
	{
	  throw new Exception(_('Pas dans la periode active'),5);
	}

    }



    // Periode ferme
    if ( $this->is_closed($periode->p_id)==1 )
      {
	echo_debug(__FILE__.':'.__LINE__.'- verify',' the periode is closed ');
	throw new Exception('Periode fermee',6);
      }
    /* check if we are using the strict mode */
    if( $this->check_strict() == true) {
      /* if we use the strict mode, we get the date of the last
	 operation */
      $last_date=$this->get_last_date();
      if ( $last_date !=null && cmpDate($date,$last_date) < 0 )
	throw new Exception(_('Vous utilisez le mode strict la dernière operation est la date du ')
			    .$last_date.' '._('vous ne pouvez pas encoder à une date antérieure'),15);

    }

    for ($i=0;$i<$nb_item;$i++)
      {
	$err=0;

	// Check the balance
	if ( ! isset (${'amount'.$i}))
	  continue;

	$amount=round(${'amount'.$i},2);
	$tot_deb+=(isset(${'ck'.$i}))?$amount:0;
	$tot_cred+=(! isset(${'ck'.$i}))?$amount:0;

	// Check if the card is permitted
	if ( isset (${'qc_'.$i}) && trim(${'qc_'.$i}) !="") {
	  $f=new fiche($this->db);
	  $f->quick_code=${'qc_'.$i};
	  if ( $f->belong_ledger($p_jrn) < 0 )
	    throw new Exception("La fiche quick_code = ".
				  $f->quick_code." n\'est pas dans ce journal",4);
	  if ( strlen(trim(${'qc_'.$i}))!=0 &&  isNumber(${'amount'.$i} ) == 0 )
	    throw new Exception('Montant invalide',3);

	}

	// Check if the account is permitted
	if ( isset (${'poste'.$i}) && strlen (trim(${'poste'.$i})) != 0 ) {
	  $p=new Acc_Account_Ledger($this->db,${'poste'.$i});
	  if ( $p->belong_ledger ($p_jrn) < 0 )
	    throw new Exception(_("Le poste")." ".$p->id." "._("n'est pas dans ce journal"),5);
	  if ( strlen(trim(${'poste'.$i}))!=0 &&  isNumber(${'amount'.$i} ) == 0 )
	    throw new Exception(_('Poste invalide'),3);
	  if ( $p->do_exist() == 0 )
	    throw new Exception(_('Poste Inexistant'),4);
	}


      }
    $tot_deb=round($tot_deb,4);
    $tot_cred=round($tot_cred,4);
    if ( $tot_deb != $tot_cred ) {
      throw new Exception(_("Balance incorrecte ")." debit = $tot_deb credit=$tot_cred ",1);
    }

  }
  /*!
   * \brief compute the internal code of the saved operation and set the $this->jr_internal to
   *  the computed value
   *
   * \param $p_grpt id in jr_grpt_
   *
   * \return string internal_code
   *      -
   *
   */
  function compute_internal_code($p_grpt)
  {
    if ( $this->id==0) return;
    $num = $this->db->get_next_seq('s_internal');
    $atype=$this->get_propertie();
    $type=$atype['jrn_def_code'];
    $internal_code=sprintf("%d%s-%s",dossier::id(),$type,$num);
    echo_debug (__FILE__,__LINE__,"internal_code = $internal_code");
    $this->jr_internal=$internal_code;
    return $internal_code;
  }

  /*!
   * \brief save the operation into the jrnx,jrn, ,
   *  CA and pre_def
   * \param $p_array
   *
   * \return array with [0] = false if failed otherwise true, [1] error
   * code
   */
  function save ($p_array) {
    extract ($p_array);
    try {
      $this->verify($p_array);

      $this->db->start() ;

      $seq=$this->db->get_next_seq('s_grpt');
      $internal=$this->compute_internal_code($seq);

      $group=$this->db->get_next_seq("s_oa_group");
      $own=new own($this->db);
      $tot_amount=0;
      $tot_deb=0;
      $tot_cred=0;
	  $oPeriode=new Periode($this->db);
	  $check_periode=$this->check_periode();
	  if ( $check_periode == false) {
		$oPeriode->find_periode($date);
	  }

      $count=0;
      for ($i=0;$i<$nb_item;$i++)
	{
	  if ( ! isset (${'qc_'.$i}) && ! isset(${'poste'.$i}))
	    continue;
	  $acc_op=new Acc_Operation($this->db);
	  $quick_code="";
	  // First we save the jrnx
	  if ( isset(${'qc_'.$i})) {
	    $qc=new fiche($this->db);
	    $qc->get_by_qcode(${'qc_'.$i},false);
	    $sposte=$qc->strAttribut(ATTR_DEF_ACCOUNT);
	    /*  if there are 2 accounts take following the deb or cred */
	    if (strpos($sposte,',') != 0 ){
	      $array=explode(",",$sposte);
	      $poste=(isset(${'ck'.$i}))?$array[0]:$array[1];
	    } else {
	      $poste=$sposte;
	    }
	    $quick_code=${'qc_'.$i};
	  }
	  else {
	    $poste=${'poste'.$i};
	  }
	  $acc_op->date=$date;
	  // compute the periode is do not check it
	  if ($check_periode == false ) $acc_op->periode=$oPeriode->p_id;
	  $acc_op->desc=$desc;
	  if ( strlen(trim(${'ld'.$i})) != 0 )
	    $acc_op->desc=${'ld'.$i};
	  $acc_op->amount=round(${'amount'.$i},2);
	  $acc_op->grpt=$seq;
	  $acc_op->poste=$poste;
	  $acc_op->jrn=$this->id;
	  $acc_op->type=(isset (${'ck'.$i}))?'d':'c';
	  $acc_op->qcode=$quick_code;
	  $j_id=$acc_op->insert_jrnx();
	  $tot_amount+=round($acc_op->amount,2);
	  $tot_deb+=($acc_op->type=='d')?$acc_op->amount:0;
	  $tot_cred+=($acc_op->type=='c')?$acc_op->amount:0;
	  if ( $own->MY_ANALYTIC != "nu" )
	    {
	      if ( ereg("^[6,7]+",$poste)) {

		// for each item, insert into operation_analytique */
		$op=new Anc_Operation($this->db);
		$op->oa_group=$group;
		$op->j_id=$j_id;
		$op->oa_date=$date;
		$op->oa_debit=($acc_op->type=='d' )?'t':'f';
		$op->oa_description=$desc;
		$op->save_form_plan($p_array,$count);
		$count++;
	      }
	    }
	}// loop for each item
      $acc_end=new Acc_Operation($this->db);
      $acc_end->amount=$tot_deb;
	  if ($check_periode == false ) $acc_end->periode=$oPeriode->p_id;
      $acc_end->date=$date;
      $acc_end->desc=$desc;
      $acc_end->grpt=$seq;
      $acc_end->jrn=$this->id;
      $acc_end->mt=$mt;
      $jr_id= $acc_end->insert_jrn();
      if ($jr_id == false )
	throw new Exception('Balance incorrecte');
      $acc_end->pj=$e_pj;

      /* if e_suggest != e_pj then do not increment sequence */
      if ( strcmp($e_pj,$e_pj_suggest) == 0 && strlen(trim($e_pj)) !=0) {
      	$this->inc_seq_pj();
      }

      $this->pj=$acc_end->set_pj();

      $this->db->exec_sql("update jrn set jr_internal='".$internal."' where ".
	      " jr_grpt_id = ".$seq);

      // Save now the predef op
      //------------------------
      if ( isset($save_opd)) {
	$opd=new Pre_Op_Advanced($this->db);
	$opd->name=(trim($desc)=='')?$internal:$desc;
	$opd->get_post();
	$opd->save();
      }

      if ( isset($this->with_concerned) && $this->with_concerned==true) {
	$orap=new acc_reconciliation($this->db);
	$orap->jr_id=$jr_id;

	$orap->insert($jrn_concerned);
      }

    }

    catch (Exception $a) {
      throw $a;
    }
    catch (Exception $e) {
      $this->db->rollback();
      echo 'OPERATION ANNULEE ';
      echo '<hr>';
      echo __FILE__.__LINE__.$e->getMessage();
      exit();
    }
    $this->db->commit();
    return true;
  }

  /*!
   * \brief get all the data from request and build the object
   */
  function get_request()
  {
    $this->id=$_REQUEST['p_jrn'];

  }

  /*!
   * \brief retrieve the next number for this type of ledger
   * \param p_cn connx
   * \param p_type ledger type
   *
   * \return the number
   *
   *
   */
  static function next_number($p_cn,$p_type)
  {

    $Ret=$p_cn->count_sql("select * from jrn_def where jrn_def_type='".$p_type."'");
    return $Ret+1;
  }
  /*!\brief get the first ledger
   *\param the type
   *\return the j_id
   */
  public function get_first($p_type) {
    $user=new User($this->db);
    $all=$user->get_ledger($p_type);
    return $all[0];
  }


  /*!\brief Update the paiment  in the list of operation
   *\param $p_array is normally $_GET
   */
  function update_paid($p_array) {
	// reset all the paid flag because the checkbox is post only
	// when checked
	foreach ($p_array as $name=>$paid)
	  {
	    list($ad) = sscanf($name,"set_jr_id%d");
 	    if ( $ad == null ) continue;
 	    $sql="update jrn set jr_rapt='' where jr_id=$ad";
 	    $Res=$this->db->exec_sql($sql);

	  }
	// set a paid flag for the checked box
	foreach ($p_array as $name=>$paid)
	  {
	    list ($id) = sscanf ($name,"rd_paid%d");

	    if ( $id == null ) continue;
	    $paid=($paid=='on')?'paid':'';
	    $sql="update jrn set jr_rapt='$paid' where jr_id=$id";
	    $Res=$this->db->exec_sql($sql);
	  }

  }
  function update_internal_code($p_internal) {
    if ( ! isset($this->grpt_id) )
      exit( 'ERREUR '.__FILE__.":".__LINE__);
    $Res=$this->db->exec_sql("update jrn set jr_internal='".$p_internal."' where ".
		 " jr_grpt_id = ".$this->grpt_id);

  }
  /*!\brief retrieve all the card for this type of ledger, make them
   *into a string separated by comma
   *\param none
   *\return all the card or null is nothing is found
   */
  function get_all_fiche_def() {
    $sql="select jrn_def_fiche_deb as deb,jrn_def_fiche_cred as cred ".
      " from jrn_def where ".
      " jrn_def_type = $1 ";

    $r=$this->db->exec_sql($sql,array($this->type));

    $res=Database::fetch_all($r);
    if ( empty($res) ) return null;
    $card="";
    $comma='';
    foreach ($res as $item ) {
      if ( strlen(trim($item['deb'])) != 0 ) {
	$card.=$comma.$item['deb'];
	$comma=',';
      }
      if ( strlen(trim($item['cred'])) != '') {
	$card.=$comma.$item['cred'];
	$comma=',';
      }

    }

    return $card;
  }
  /*!\brief get the saldo of an exercice, used for the opening of a folder
   *\param $p_exercice is the exercice we want
   *\return an array
   * index =
   * - solde (debit > 0 ; credit < 0)
   * - j_poste
   * - j_qcode
   */
  function get_saldo_exercice($p_exercice) {
    $sql="select sum(a.montant) as solde, j_poste, j_qcode
          from
             (select j_id, case when j_debit='t' then j_montant
                                                 else j_montant * (-1) end  as montant
               from jrnx) as a
          join jrnx using (j_id)
          join parm_periode on (j_tech_per = p_id )
          where
          p_exercice=$1
          and j_poste::text not like '7%'
          and j_poste::text not like '6%'
          group by j_poste,j_qcode
          having (sum(a.montant) != 0 )";
    $res=$this->db->get_array($sql,array($p_exercice));
    return $res;
  }
/*!
 *\brief Check if a Dossier is using the strict mode or not
 * \return true if we are using the strict_mode
 */
function check_strict() {
	$own=new Own($this->db);
	if ( $own->MY_STRICT=='Y') return true;
	if ( $own->MY_STRICT=='N') return false;
	exit("Valeur invalid ".__FILE__.':'.__LINE__);
}
/*!
 *\brief Check if a Dossier is using the check on the periode, if true than the user has to enter the date
 * and the periode, it is a security check
 * \return true if we are using the double encoding (date+periode)
 */
function check_periode() {
	$own=new Own($this->db);
	if ( $own->MY_CHECK_PERIODE=='Y') return true;
	if ( $own->MY_CHECK_PERIODE=='N') return false;
	exit("Valeur invalid ".__FILE__.':'.__LINE__);
}

/*!\brief get the date of the last operation
*/
function get_last_date()
{
	if ( $this->id==0) throw new Exception (__FILE__.":".__LINE__."Journal incorrect ");
	$sql="select to_char(max(jr_date),'DD.MM.YYYY') from jrn where jr_def_id=$1";
	$date=$this->db->get_value($sql,array($this->id));
	return $date;
}
  /*!\brief retrieve the jr_id thanks the internal code, do not change
   *anything to the current object
   *\param the internal code
   *\return the jr_id or 0 if not found
   */
  function get_id($p_internal) {
    $sql='select jr_id from jrn where jr_internal=$1';
    $value=$this->db->get_value($sql,array($p_internal));
    if ($value=='') $value=0;
    return $value;
  }
  /*!\brief create the invoice and saved it as attachment to the
   *operation,
   *\param $internal is the internal code
   *\param $p_array is normally the $_POST
   *\return a string
   */
  function create_document($internal,$p_array) {
    extract ($p_array);
    $doc=new Document($this->db);
    $doc->f_id=$e_client;
    $doc->md_id=$gen_doc;
    $doc->ag_id=0;
    $str_file=$doc->Generate();
    // Move the document to the jrn
    $doc->MoveDocumentPj($internal);
    // Update the comment with invoice number, if the comment is empty
    if ( ! isset ($e_comm) || strlen(trim($e_comm))== 0 ) {
      $sql="update jrn set jr_comment=' document ".$doc->d_number."' where jr_internal='$internal'";
      $this->db->exec_sql($sql);
    }
    return '<h2 class="info">'.$str_file.'</h2>';

  }
  /*!\brief check if the payment method is valid
   *\param $e_mp is the value and $e_mp_qcode is the quickcode
   *\return nothing throw an Exception
   */
  public function check_payment($e_mp,$e_mp_qcode) {
    /*   Check if the "paid by" is empty, */
    if (  $e_mp != 0) {
    /* the paid by is not empty then check if valid */
      $empl=new fiche($this->db);
      $empl->get_by_qcode($e_mp_qcode);
      if ( $empl->empty_attribute(ATTR_DEF_ACCOUNT)== true) {
	throw new Exception('Celui qui paie n\' a pas de poste comptable',20);
      }
      /* get the account and explode if necessary */
      $sposte=$empl->strAttribut(ATTR_DEF_ACCOUNT);
      // if 2 accounts, take only the debit one for customer
      if ( strpos($sposte,',') != 0 ) {
	$array=explode(',',$sposte);
	$poste_val=$array[0];
      } else {
	$poste_val=$sposte;
      }
      $poste=new Acc_Account_Ledger($this->db,$poste_val);
      if ( $poste->load() == false ){
	throw new Exception('Pour la fiche'.$empl->quick_code.'  le poste comptable ['.$poste->id.'n\'existe pas',9);

      }
    }
  }


  /*!\brief increment the sequence for the pj */
  function inc_seq_pj() {
    $sql="select nextval('s_jrn_pj".$this->id."')";
    $this->db->exec_sql($sql);
  }
  /*!\brief return a HTML string with the form for the search
   *\param $p_type if the type of ledger possible values=ALL,VEN,ACH,ODS,FIN
   *\param $all_type_ledger
   *       values :
   *         - 1 means all the ledger of this type
   *         - 0 No have the "Tous les journaux" availables
   *\return a HTML String without the tag FORM or DIV
   *\see build_search_sql
   *\see display_search_form
   *\see list_operation
   */
  function  search_form($p_type,$all_type_ledger=1) {
    $user=new User($this->db);
    $r='';
    /* security : filter ledger on user */
    $filter_ledger=$user->get_ledger_sql($p_type,3);

    $f_ledger=new ISelect('p_jrn');
    $aLedger=$this->db->make_array('select jrn_def_id,jrn_def_name from jrn_def where '.$filter_ledger);
    if ( $all_type_ledger==1)
      $aLedger[]=array('value'=>-1,'label'=>'Tous les journaux');

    /* if not p_jrn then all */
    if( ! isset($_REQUEST['p_jrn'])) {
      /* By default all ledger of this type */
      $this->id=-1;
    } else {
      $this->id=$_REQUEST['p_jrn'];
    }
    $f_ledger->selected=$this->id;
    $f_ledger->value=$aLedger;

    /* widget for date_start */
    $f_date_start=new IDate('date_start');
    /* all periode or only the selected one */
    if ( isset($_REQUEST['date_start'])) {
      $f_date_start->value=$_REQUEST['date_start'];
    } else {
      $period=$user->get_periode();
      $per=new Periode($this->db,$period);
      list($date_start,$date_end)=$per->get_date_limit();
      $f_date_start->value=$date_start;
    }

    /* widget for date_end */
    $f_date_end=new IDate('date_end');
    /* all date or only the selected one */
    if ( isset($_REQUEST['date_end'])) {
      $f_date_end->value=$_REQUEST['date_end'];
    } else {
      $f_date_end->value=$date_end;
    }

    /* widget for desc */
    $f_descript=new IText('desc');
    $f_descript->size=40;
    if ( isset($_REQUEST['desc'])) {
      $f_descript->value=$_REQUEST['desc'];
    }

    /* widget for amount */
    $f_amount_min=new INum('amount_min');
    $f_amount_min->value=(isset($_REQUEST['amount_min']))?$_REQUEST['amount_min']:0;
    $f_amount_max=new INum('amount_max');
    $f_amount_max->value=(isset($_REQUEST['amount_max']))?$_REQUEST['amount_max']:0;

    /* input quick code */
    $f_qcode=new ICard('qcode');
    $f_qcode->extra='filter';
    $f_qcode->set_attribute('typecard','all');
    $f_qcode->set_callback('filter_card');
    $f_txt_qcode=new IText('qcode');
    $f_txt_qcode->value=(isset($_REQUEST['qcode']))?$_REQUEST['qcode']:'';

    /* input poste comptable */
    $f_accounting=new IPoste('accounting');
    $f_accounting->value=(isset($_REQUEST['accounting']))?$_REQUEST['accounting']:'';
    if ( $this->id=-1) $jrn=0;else $jrn=$this->id;
    $f_accounting->set_attribute('jrn',$jrn);
    $f_accounting->set_attribute('ipopup','ipop_account');
    $f_accounting->set_attribute('label','ld');
    $f_accounting->set_attribute('account','accounting');
    $info=HtmlInput::infobulle(13);

    $f_paid=new ICheckbox('unpaid');
    $f_paid->selected=(isset($_REQUEST['unpaid']))?true:false;

    $r.=HtmlInput::hidden('phpsessid',$_REQUEST['PHPSESSID']);
    $r.=dossier::hidden();
    $r.=HtmlInput::hidden('ledger_type',$this->type);
    ob_start();
    require_once('template/ledger_search.php');
    $r.=ob_get_contents();
    ob_clean();
    return $r;

  }
  /*!\brief this function will create a sql stmt to use to create the list for
   * the ledger,
   *\param $p_array is usually the $_GET,
   *\param $p_order the order of the row
   *\param $p_where is the sql condition if not null then the $p_array will not be used
   *\note the p_action will be used to filter the ledger but gl means ALL
   * struct array $p_array
\verbatim
(
    [phpsessid] => 016710a766b3c7b137ce6ee5bfbacc00
    [gDossier] => 13
    [p_jrn] => -1
    [date_start] =>
    [date_end] =>
    [amount_min] => 0
    [amount_max] => 0
    [desc] =>
    [search] => Rechercher
    [p_action] => ven
    [sa] => l
    [PHPSESSID] => 016710a766b3c7b137ce6ee5bfbacc00
)
\endverbatim
   *\return an array with a valid sql statement, an the where clause => array[sql] array[where]
   *\see list_operation
   *\see display_search_form
   *\see search_form
   */
  public function build_search_sql($p_array,$p_order="",$p_where="") {
    $sql="select jr_id	,
			jr_montant,
                        substr(jr_comment,1,60) as jr_comment,
			to_char(jr_ech,'DD.MM.YYYY') as jr_ech,
			to_char(jr_date,'DD.MM.YYYY') as jr_date,
                        jr_date as jr_date_order,
			jr_grpt_id,
			jr_rapt,
			jr_internal,
			jrn_def_id,
			jrn_def_name,
			jrn_def_ech,
			jrn_def_type,
                        jr_valid,
                        jr_tech_per,
                        jr_pj_name,
                        p_closed,
                        jr_pj_number
		       from
			jrn
                            join jrn_def on jrn_def_id=jr_def_id
                            join parm_periode on p_id=jr_tech_per";

    if ( $p_array != null )
      extract($p_array);

    /* if no variable are set then give them a default
     * value */
    if ( $p_array == null || empty($p_array) || ! isset($amount_min) ) {
      $amount_min=0;
      $amount_max=0;

      if ( ! isset ($date_start)) {
	$user=new User($this->db);
	$period=$user->get_periode();
	$per=new Periode($this->db);
	list($date_start,$date_end)=$per->get_date_limit();
      }
      $desc='';
      $p_jrn=(isset($p_jrn))?$p_jrn:-1;
      $qcode=(isset($qcode))?$qcode:"";
      $accounting=(isset($accounting))?$accounting:"";

    }

    /* if p_jrn : 0 if means all ledgers, if -1 means all ledger of this
     *  type otherwise only one ledger*/
    $fil_ledger='';
    $fil_amount='';
    $fil_date='';
    $fil_desc='';
    $fil_sec='';
    $fil_qcode='';
    $fil_account='';
    $fil_paid='';

    $and='';
    if ( $p_jrn == -1 ) {
      $user=new User($this->db);
      /* Specific action allow to see all the ledgers in once */
      if ( $p_action == 'gl') $p_action='ALL';
      /* actions from commercial.php  */
      if ( $p_action == 'client') $p_action='ALL';
      if ( $p_action == 'supplier') $p_action='ALL';
      if ( $p_action == 'adm') $p_action='ALL';
      if ( $p_action == 'quick_writing') $p_action='ALL';


      $fil_ledger=$user->get_ledger_sql($p_action,3);
      $and=' and ';
    } else if ( $p_jrn != 0 ){
      $fil_ledger = ' jrn_def_id = '.$p_jrn;
      $and=' and ';
    }

    /* format the number */
    $amount_min=toNumber($amount_min);
    $amount_max=toNumber($amount_max);
    if ( $amount_min > 0 && isNumber($amount_min) ) {
      $fil_amount=$and.' jr_montant >=' .$amount_min; $and=' and ';
    }
    if ( $amount_max > 0 && isNumber($amount_max)  ) {
      $fil_amount.=$and.' jr_montant <=' .$amount_max; $and=' and ';
    }
    /* -------------------------------------------------------------------------- *
     * if both amount are the same then we need to search into the detail
     * and we reset the fil_amount
     * -------------------------------------------------------------------------- */
    if ( isNumber($amount_min) &&
	 isNumber($amount_max) &&
	 $amount_min > 0 &&
	 bccomp($amount_min, $amount_max,2)==0 )
      {
	  $fil_amount= $and. 'jr_grpt_id in  ( select distinct j_grpt from jrnx where j_montant = '.$amount_min.')';
	  $and=" and ";
      }
    // date
    if ( isDate($date_start) != null )
      {
	$fil_date=$and." jr_date >= to_date('".$date_start."','DD.MM.YYYY')";
	$and=" and ";
      }
    if ( isDate($date_end) != null ) {
      $fil_date.=$and." jr_date <= to_date('".$date_end."','DD.MM.YYYY')";
      $and=" and ";
    }
    // comment
    $desc=FormatString($desc);
    if ( $desc != null )
      {
	$fil_desc=$and." ( upper(jr_comment) like upper('%".$desc."%') or upper(jr_pj_number) like upper('%".$desc."%') ".
	" or upper(jr_internal)  like upper('%".$desc."%') )";
	$and=" and ";
      }
    //    Poste
    if ( $accounting != null ) {
      $fil_account=$and."  jr_grpt_id in (select j_grpt
             from jrnx where j_poste::text like '$accounting%' )  ";
      $and=" and ";
    }
    // Quick Code
    if ( $qcode != null )
      {
    	$fil_qcode=$and."  jr_grpt_id in ( select j_grpt from
             jrnx where trim(j_qcode) = upper(trim('$qcode')))";
    	$and=" and ";
      }

    // Only the unpaid
    if ( isset($unpaid) ) {
      $fil_paid=$and.SQL_LIST_UNPAID_INVOICE;
      $and =" and ";
    }

    $User=new User(new Database());
    $User->Check();
    $User->check_dossier(dossier::id());

    if ( $User->admin == 0 && $User->is_local_admin()==0 )
      {
	$fil_sec=$and." jr_def_id in ( select uj_jrn_id ".
	  " from user_sec_jrn where ".
	  " uj_login='".$_SESSION['g_user']."'".
	  " and uj_priv in ('R','W'))";
      }
    $where=$fil_ledger.$fil_amount.$fil_date.$fil_desc.$fil_sec.$fil_amount.$fil_qcode.$fil_paid.$fil_account;
    $sql.=" where ".$where;
    return array($sql,$where);
  }
  /*!\brief return a html string with the search_form
   *\return a HTML string with the FORM
   *\see build_search_sql
   *\see search_form
   *\see list_operation
   */
  function display_search_form() {
    $r='';
    $type=$this->type;

    if ( $type=="" || $this->id==0) $type='ALL';
    if ( isset($_GET['amount_min']) ) { $display='block';} else {$display='none';}
    $r.='<div id="search_form" style="display:'.$display.'">';
    $r.='<FORM METHOD="GET">';
    $r.=$this->search_form($type);
    $r.=HtmlInput::submit('search',_('Rechercher'));
    $r.=HtmlInput::hidden('p_action',$_REQUEST['p_action']);

    /*  when called from commercial.php some hidden values are needed */
    if (isset($_REQUEST['sa'])) $r.= HtmlInput::hidden("sa",$_REQUEST['sa']);
    if (isset($_REQUEST['sb'])) $r.= HtmlInput::hidden("sb",$_REQUEST['sb']);
    if (isset($_REQUEST['sc'])) $r.= HtmlInput::hidden("sc",$_REQUEST['sc']);
    if (isset($_REQUEST['f_id'])) $r.=HtmlInput::hidden("f_id",$_REQUEST['f_id']);

    $r.='</FORM>';
    $button=new IButton('tfs');
    $button->label=_("Afficher recherche");
    $button->javascript="toggleHideShow('search_form','tfs');";
    $r.='</div>';
    $r.=$button->input();

    return $r;
  }
  /*!
   * \brief this function is intended to test this class
   */
  static function test_me($pCase='')
  {
    if ( $pCase=='') {
    echo Acc_Reconciliation::$javascript;
    html_page_start();
    $cn=new Database(dossier::id());
    $_SESSION['g_user']='phpcompta';
    $_SESSION['g_pass']='phpcompta';

    $id=(isset ($_REQUEST['p_jrn']))?$_REQUEST['p_jrn']:-1;
    $a=new Acc_Ledger($cn,$id);
    $a->with_concerned=true;
    // Vide
    echo '<FORM method="post">';
    echo $a->select_ledger()->input();
    echo HtmlInput::submit('go','Test it');
    echo '</form>';
    if ( isset($_POST['go'])) {
      echo "Ok ";
      echo '<form method="post">';
      echo $a->show_form();
      echo HtmlInput::submit('post_id','Try me');
      echo '</form>';
      // Show the predef operation
      // Don't forget the p_jrn
      echo '<form>';
      echo dossier::hidden();
      echo '<input type="hidden" value="'.$id.'" name="p_jrn">';
      $op=new Pre_operation($cn);
      $op->p_jrn=$id;
      $op->od_direct='t';
      if ($op->count() != 0 ) {
	echo HtmlInput::submit('use_opd','Utilisez une op.pr&eacute;d&eacute;finie');
	echo $op->show_button();
      }
      echo '</form>';
      exit();
    }

    if ( isset($_POST['post_id' ])) {

      echo '<form method="post">';
      echo $a->show_form($_POST,1);
      echo HtmlInput::button('add','Ajout d\'une ligne','onClick="quick_writing_add_row()"');
      echo HtmlInput::submit('save_it',"Sauver");
      echo '</form>';
      exit();
    }
    if ( isset($_POST['save_it' ])) {
      print 'saving';
      $array=$_POST;
      $array['save_opd']=1;
      try {
	$a->save($array);
      } catch (Exception $e) {
	alert($e->getMessage());
	echo '<form method="post">';

	echo $a->show_form($_POST);
	echo HtmlInput::submit('post_id','Try me');
	echo '</form>';

      }
      exit();
    }
    // The GET at the end because automatically repost when you don't
    // specify the url in the METHOD field
    if ( isset ($_GET['use_opd'])) {
      $op=new Pre_op_advanced($cn);
      $op->set_od_id($_REQUEST['pre_def']);
      //$op->p_jrn=$id;

      $p_post=$op->compute_array();

      echo '<FORM method="post">';

      echo $a->show_form($p_post);
      echo HtmlInput::submit('post_id','Use predefined operation');
      echo '</form>';
      exit();

    }
    }// if case = ''
    if ( $pCase  == 'search') {
      html_page_start();
      $cn=new Database(dossier::id());
      $ledger=new Acc_Ledger($cn,0);
      $_SESSION['g_user']='phpcompta';
      $_SESSION['g_pass']='phpcompta';
      echo $ledger->search_form('ALL');
    }
  }
  /*!\brief return the last p_limit operation into an array
   *\param $p_limit is the max of operation to return
   *\return $p_array of Action object
   */
  function get_last($p_limit) {
    $user=new User($this->db);
    $filter_ledger=$user->get_ledger_sql('ALL',3);
    $filter_ledger=str_replace('jrn_def_id','jr_def_id',$filter_ledger);
    $sql="select jr_date,to_char(jr_date,'DD.MM.YYYY') as jr_date_fmt,jr_montant, jr_comment,jr_internal from jrn ".
      " where $filter_ledger ".
      " order by jr_date desc limit $p_limit";
    $array=$this->db->get_array($sql);
    return $array;
  }
  /**
   *@brief retreive the jr_grpt_id from a ledger
   *@param $p_what the column to seek
   *    possible values are
   *   - internal
   *@param $p_value the value of the col.
   */
  function search_group($p_what,$p_value) {
    switch($p_what) {
    case 'internal':
      return $this->db->get_value('select jr_grpt_id from jrn where jr_internal=$1',
				  array($p_value));

    }
  }
}
