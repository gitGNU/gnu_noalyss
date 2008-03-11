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
 * \brief Create, view, modify and parse report
 */

/*!\brief  manipulate the form_def's child table (form) */
class  Rapport_Row
{
  /* example private $variable=array("val1"=>1,"val2"=>"Seconde valeur","val3"=>0); */
  private $variable=array("name"=>"fo_label","formula"=>"fo_formula");
  function __construct ($p_name,$p_formula) {
    $this->setParameter("name",$p_name);
    $this->setParameter("formula",$p_formula);
  }
  public function getParameter($p_string) {
    if ( array_key_exists($p_string,$this->variable) )
      return $this->$p_string;
    else 
      exit (__FILE__.":".__LINE__.'Erreur attribut inexistant');
  }
  public function setParameter($p_string,$p_value) {
    if ( array_key_exists($p_string,$this->variable) )
      $this->$p_string=$p_value;
    else 
      exit (__FILE__.":".__LINE__.'Erreur attribut inexistant');
    
    
  }
}


/*! 
 * \brief Class rapport  Create, view, modify and parse report
 */

class Rapport {

  var $db;    /*!< $db database connx */
  var $id;    /*!< $id formdef.fr_id */
  var $name;  /*!< $name report's name */
  /*!\brief  Constructor */
  function __construct($p_cn,$p_id=0) {
    $this->db=$p_cn;
    $this->id=$p_id;
    $this->name='UNDEF';
  }
  /*!\brief Return the report's name
   */
  function get_name() {
    $ret=execSql($this->db,"select fr_label from formdef where fr_id=".$this->id);
    if (pg_NumRows($ret) == 0) return $this->name;
    $a=pg_fetch_array($ret,0);
    $this->name=$a['fr_label'];
    return $this->name;
  }
  /*!\brief return all the row and parse formula
   *        from a report
   * \param $p_start start periode
   * \param $p_end end periode
   * \param $p_type_date type of the date : periode or calendar
   */
 
  function get_row($p_start,$p_end,$p_type_date) {

   $Res=ExecSql($this->db,"select fo_id ,
                     fo_fr_id,
                     fo_pos,
                     fo_label,
                     fo_formula,
                     fr_label from form
                      inner join formdef on fr_id=fo_fr_id
                     where fr_id =".$this->id.
                     "order by fo_pos");
    $Max=pg_NumRows($Res);
    if ($Max==0) {      $this->row=0;return null;}
    $col=array();
    for ($i=0;$i<$Max;$i++) {
      $l_line=pg_fetch_array($Res,$i);
	  $col[]=ParseFormula($this->db,
			      $l_line['fo_label'],
			      $l_line['fo_formula'],
			      $p_start,
			      $p_end,
			      true,
			      $p_type_date
			      );
     
    } //for ($i
    $this->row=$col;
    return $col;
  }
/*! 
 * \brief  Display a form for encoding a new report or update one
 * 
 * \param $p_line number of line
 *
 */ 
function form($p_line) {
  $search_poste=new widget('JS_SEARCH_POSTE');
  $search_poste->extra='not';

  echo "<FORM ACTION=\"form.php\" METHOD=\"POST\">";
  echo dossier::hidden();
  echo widget::hidden('line',$p_line);
  echo widget::hidden('fr_id',$this->id);

  printf ("Nom du rapport : <INPUT TYPE=\"TEXT\" NAME=\"form_nom\" VALUE=\"%s\">",
	  $form_nom);

  echo '<TABLE>';
  echo "<TR>";
  echo "<TH> Position </TH>";
  echo "<TH> Texte </TH>";
  echo "<TH> Formule</TH>";

  echo '</TR>';
  /*  for ( $i =0 ; $i < $p_line;$i++) {
    echo "<TR>";
    
    echo "<TD>";
    printf ('<input TYPE="TEXT" NAME="pos%d" size="3">',
	    $i);
    echo '</TD>';
    

    echo "<TD>";
    printf ('<input TYPE="TEXT" NAME="text%d" size="25">',
	    $i);
    echo '</TD>';

    echo "<TD>";
    printf ('<input TYPE="TEXT" NAME="form%d" SIZE="25" >',
	    $i);
    echo $search_poste->IOValue();
    echo '</TD>';

    echo "</TR>";
    }*/

  echo "</TABLE>";

  echo '<INPUT TYPE="submit" value="Enregistre" name="update">';
  /* Add a line should be a javascript see comptanalytic */
  //  echo '<INPUT TYPE="submit" value="Ajoute une ligne" name="add_line">';
  echo '<INPUT TYPE="submit" value="Efface ce rapport" name="del_form">';

  echo "</FORM>";

}

  /*!\brief fill a form thanks an array, usually it is $_POST
   *\param $array
   */
  function from_array($p_array) {
    extract ($p_array);
    $this->id=(isset($p_array['fr_id']))?$p_array['fr_id']:0;
    $this->name=(isset($p_array['form_nom']))?$p_array['form_nom']:"";
    $ix=0;
    $array=array();
    while ($ix<50) {
      $ix++;
      if ( isset(${'form'.$ix}) && isset ( ${'text'.$ix} )) {
	$obj=new Rapport_Row( ${'text'.$ix},${'form'.$ix});
	$array[]=clone $obj;
      } else   break;
    } 
    $this->aRow=$array;
  }


  /*!\brief get a list from formdef of all defined form
   *
   *\return array of object rapport
   *
   */
  function get_list()
  {
    $sql="select fr_id,fr_label from formdef order by fr_label";
    $ret=ExecSql($this->db,$sql);
    if ( pg_NumRows($ret) == 0 ) return array();
    $array=pg_fetch_all($ret);
    $obj=array();
    foreach ($array as $row) {
      $tmp=new Rapport($this->db);
      $tmp->id=$row['fr_id'];
      $tmp->name=$row['fr_label'];
      $obj[]=clone $tmp;
    }
    return $obj;
  }
  function test_me() {
    $cn=DbConnect(dossier::id());
    $a=new Rapport($cn);
    print_r($a->get_list());

  }
}
?>
