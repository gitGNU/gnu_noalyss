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
 * \brief class for the sold, herits from acc_ledger
 */
require_once('class_acc_ledger.php');


/*!\brief Handle the ledger of sold, 
 *
 *
 */
class  Acc_Ledger_Sold extends Acc_Ledger { 
  function __construct ($p_cn,$p_init) {
    parent::__construct($p_cn,$p_init);
  }
  public function verify() {
    // Verify that the elt we want to add is correct
  }
  public function save() {
  /* please adapt */
    if (  $this->get_parameter("id") == 0 ) 
      $this->insert();
    else
      $this->update();
  }

  public function insert() {
    if ( $this->verify() != 0 ) return;
    /*  please adapt
    $sql="insert into tva_rate (tva_label,tva_rate,tva_comment,tva_poste) ".
      " values ($1,$2,$3,$4)  returning tva_id";
    $res=ExecSqlParam($this->cn,
		 $sql,
		 array($this->tva_label,
		       $this->tva_rate,
		       $this->tva_comment,
		       $this->tva_poste)
		 );
    $this->tva_id=pg_fetch_result($res,0,0);
    */
  }

  public function update() {
    if ( $this->verify() != 0 ) return;
    /*   please adapt
    $sql="update tva_rate set tva_label=$1,tva_rate=$2,tva_comment=$3,tva_poste=$4 ".
      " where tva_id = $5";
    $res=ExecSqlParam($this->cn,
		 $sql,
		 array($this->tva_label,
		       $this->tva_rate,
		       $this->tva_comment,
		       $this->tva_poste,
		       $this->tva_id)
		 );
		 */
  }

  public function load() {

   $sql="select tva_label,tva_rate, tva_comment,tva_poste from tva_rate where tva_id=$1"; 
    /* please adapt
    $res=ExecSqlParam($this->cn,
		 $sql,
		 array($this->tva_id)
		 );
		 */
    if ( pg_NumRows($res) == 0 ) return;
    $row=pg_fetch_array($res,0);
    foreach ($row as $idx=>$value) { $this->$idx=$value; }
  }
  public function delete() {
/*    $sql="delete from tva_rate where tva_id=$1"; 
    $res=ExecSqlParam($this->cn,$sql,array($this->tva_id));
*/
  }
  /*!\brief display the form for entering data for invoice
   *\param $p_array is null or you can put the predef operation or the $_POST
   *\return string
   *\note
   *\see
   */
  public function display_form($p_array=null) {
    if ( $p_array != null ) extract($p_array);

    $user = new User($this->db);

    // The first day of the periode 
    $periode=new Periode($this->db);
    list ($l_date_start,$l_date_end)=$periode->get_date_limit($user->get_periode());

    $op_date=( ! isset($e_date) ) ?$l_date_start:$e_date;
    $e_ech=(isset($e_ech))?$e_ech:"";
    $e_comm=(isset($e_comm))?$e_comm:"";
    

    $r="";

    $r.=JS_INFOBULLE;
    $r.=JS_SEARCH_CARD;
    $r.=JS_SHOW_TVA;    
    $r.=JS_TVA;
    $r.=JS_AJAX_FICHE;

    $r.="<FORM NAME=\"form_detail\" METHOD=\"POST\">";
    $r.=dossier::hidden();
    $r.=widget::hidden('phpsessid',$_REQUEST['PHPSESSID']);  
    $r.="<fieldset>";
    $r.="<legend>En-tête facture client  </legend>";
    
    $r.='<TABLE  width="100%">';
    //  Date
    //--
    $Date=new widget("js_date");
    $Date->SetReadOnly(false);
    $Date->table=1;
    $Date->tabindex=1;
    $r.="<tr>";
    $r.=$Date->IOValue("e_date",$op_date,"Date");
    // Payment limit
    //--
    $Echeance=new widget("js_date");
    $Echeance->SetReadOnly(false);
    $Echeance->table=1;
    $Echeance->tabindex=2;
    $label=widget::infobulle(4);
    $r.=$Echeance->IOValue("e_ech",$e_ech,"Echeance ".$label);

    // Periode 
    //--
    $l_user_per=$user->get_periode();
    $l_form_per=FormPeriode($this->db,$l_user_per,OPEN);
    $r.="<td class=\"input_text\">";
    $label=widget::infobulle(3);
    $r.="Période comptable $label</td><td>".$l_form_per;
    $r.="</td>";
    $r.="</tr><tr>";
    // Ledger (p_jrn)
    //--
    $ledger=new Acc_Ledger($this->db,0);
    $wLedger=$ledger->select_ledger('VEN',2);
    $wLedger->table=1;
    $wLedger->label=" Journal ".widget::infobulle(2) ;
    $r.=$wLedger->IOValue();
    // Comment
    //--
    $Commentaire=new widget("text");
    $Commentaire->table=0;
    $Commentaire->SetReadOnly(false);
    $Commentaire->size=80;
    $Commentaire->tabindex=3;
    $label=" Description ".widget::infobulle(1) ;
    $r.="<tr>";
    $r.='<td class="input_text">'.$label.'</td>'.
      '<td colspan="5">'.$Commentaire->IOValue("e_comm",$e_comm)."</td>";
    $r.="</tr>";
    include_once("fiche_inc.php");
    // Display the customer
    //--
    $fiche='deb';
    echo_debug('user_form_ven.php',__LINE__,"Client Nombre d'enregistrement ".sizeof($fiche));
    // Save old value and set a new one
    //--
    $e_client=( isset ($e_client) )?$e_client:"";
    $e_client_label="&nbsp;";//str_pad("",100,".");
  
  
    // retrieve e_client_label
    //--
    $a_client=GetFicheAttribut($this->db,$e_client);
    if ( $a_client != null)   
      $e_client_label=$a_client['vw_name']."  adresse ".$a_client['vw_addr']."  ".$a_client['vw_cp'];
    
    
    $W1=new widget("js_search_only");
    $W1->label="Client ".widget::infobulle(0) ;;
    $W1->name="e_client";
    $W1->tabindex=3;
    $W1->value=$e_client;
    $W1->table=0;
    $W1->extra=$fiche;  // list of card
    $W1->extra2="Recherche";
    $r.='<TR><td colspan="5" >'.$W1->IOValue();
    $client_label=new widget("span");
    $r.=$client_label->IOValue("e_client_label",$e_client_label)."</TD></TR>";
    
    $r.="</TABLE>";
    
    // Record the current number of article
    $Hid=new widget('hidden');
    $p_article= ( isset ($p_article))?$p_article:MAX_ARTICLE;
    $r.=$Hid->IOValue("nb_item",$p_article);
    $e_comment=(isset($e_comment))?$e_comment:"";
    $r.="</fieldset>";
    
    // Start the div for item to sell
    $r.="<DIV>";
    $r.='<fieldset><legend>D&eacute;tail articles vendus</legend>';
    $r.='<TABLE ID="sold_item">';
    $r.='<TR>';
    $r.="<th></th>";
    $label=widget::infobulle(0) ;
    $r.="<th>Code $label</th>";
    $r.="<th>D&eacute;nomination</th>";
    $r.="<th>prix</th>";
    $r.="<th>tva</th>";
    $r.="<th>quantit&eacute;</th>";

    $r.='</TR>';
    // For each article
    //--
    for ($i=0;$i< MAX_ARTICLE;$i++) {
      // Code id, price & vat code
      //--
      $march=(isset(${"e_march$i"}))?${"e_march$i"}:"";
      $march_sell=(isset(${"e_march".$i."_sell"}))?${"e_march".$i."_sell"}:"";
      $march_tva_id=(isset(${"e_march$i"."_tva_id"}))?${"e_march$i"."_tva_id"}:"";
      
      $march_tva_label="";

      $march_label="&nbsp;";
      // retrieve the tva label and name
      //--
      $a_fiche=GetFicheAttribut($this->db, $march);
      if ( $a_fiche != null ) {
	if ( $march_tva_id == "" ) {
	  $march_tva_id=$a_fiche['tva_id'];
	  $march_tva_label=$a_fiche['tva_label'];
	}
	$march_label=$a_fiche['vw_name'];
      }
      // Show input
      //--
      $W1=new widget("js_search_only");
      $W1->label="";
      $W1->name="e_march".$i;
      $W1->value=$march;
      $W1->table=1;
      $W1->extra2="Recherche";
      $W1->extra='cred';  // credits

      $W1->readonly=false;
      $r.="<TR>".$W1->IOValue();
      // For computing we need some hidden field for holding the value
      $r.=widget::hidden('tva_march'.$i,0);      
      $r.=widget::hidden('htva_march'.$i,0);      
      $r.=widget::hidden('tvac_march'.$i,0);      
      $r.="</TD>";
      $Span=new widget ("span");
      $Span->SetReadOnly(false);
      // card's name, price
      //--
      $r.='<TD style="width:60%;border-bottom:1px dotted grey;">'.$Span->IOValue("e_march".$i."_label",$march_label)."</TD>";
      // price
      $Price=new widget("text");
      $Price->SetReadOnly(false);
      $Price->table=1;
      $Price->size=9;
      $Price->javascript="onBlur='compute_sold($i)'";
      $r.=$Price->IOValue("e_march".$i."_sell",$march_sell);
      // vat label
      //--
      $select_tva=make_array($this->db,"select tva_id,tva_label from tva_rate order by tva_rate desc",0);
      $Tva=new widget("select");
      $Tva->javascript="onChange=compute_sold($i)";
      $Tva->table=1;
      $Tva->selected=$march_tva_id;
      $r.=$Tva->IOValue("e_march$i"."_tva_id",$select_tva);
      
      // quantity
      //--
      $quant=(isset(${"e_quant$i"}))?${"e_quant$i"}:"1";
      $Quantity=new widget("text");
      $Quantity->SetReadOnly(false);
      $Quantity->table=1;
      $Quantity->size=9;
      $Quantity->javascript="onChange=compute_sold($i)";
      $r.=$Quantity->IOValue("e_quant".$i,$quant);

      $r.="</tr>";
    }

    
    
    $r.="</TABLE>";

    $r.='<div style="position:float;float:left;text-align:right;padding-right:5px;color:blue">';
    $r.='<br>Total HTVA';
    $r.='<br>Total TVA';
    $r.='<br>Total TVAC';
    $r.="</div>";

    $r.='<div style="position:float;float:left;text-align:left;color:blue">';
    $r.='<br><span id="htva">0.0</span>';
    $r.='<br><span id="tva">0.0</span>';
    $r.='<br><span id="tvac">0.0</span>';
    $r.="</div>";

    $r.="</fieldset>";
    // Set correctly the REQUEST param for jrn_type 
    $r.=widget::hidden('jrn_type','VEN');

    $r.='<INPUT TYPE="BUTTON" NAME="add_item" VALUE="Ajout article" '.
      ' onClick="ledger_sold_add_row(\''.dossier::id().'\',\''.$_REQUEST['PHPSESSID'].'\')"'.     
      ' TABINDEX="32767">';

    $r.='<INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Enregistrer" TABINDEX="32767" ID="SubmitButton">';
    $r.="</DIV>";
    $r.="</FORM>";
    $r.=JS_CALC_LINE;
    return $r;
  }

  /*!\brief
   *\param
   *\return
   *\note
   *\see
   *\todo
   */	
  static function test_me() {
  }
  
}




  
