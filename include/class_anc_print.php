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
 *  \brief this class is the mother class for the CA printing
 */

/*! \brief this class is the mother class for the CA printing
 *
 *
 */
require_once("class_idate.php");
require_once("class_itext.php");
require_once("class_ihidden.php");
require_once("class_iselect.php");
require_once("class_ibutton.php");
require_once('class_database.php');
require_once('constant.php');
require_once('class_dossier.php');
require_once ('class_anc_plan.php');

class Anc_Print
{
    var $db;						/*!< $db database connection */
    var $to;						/*!< $to start date */
    var $from; 					/*!< $from end date */
    var $from_poste;				/*!< $from_poste from poste  */
    var $to_poste;				/*!< $to_poste to the poste */

    function Anc_Print($p_cn)
    {
        $this->db=$p_cn;
        $this->from="";
        $this->to="";
        $this->from_poste="";
        $this->to_poste="";

    }
    /*!
     * \brief complete the object with the data in $_REQUEST
     */
    function get_request()
    {
        if ( isset($_REQUEST['from']))
            $this->from=$_REQUEST['from'];

        if ( isset($_REQUEST['to']))
            $this->to=$_REQUEST['to'];

        if ( isset($_REQUEST['from_poste']))
            $this->from_poste=$_REQUEST['from_poste'];

        if ( isset($_REQUEST['to_poste']))
            $this->to_poste=$_REQUEST['to_poste'];
        if ( isset($_REQUEST['pa_id']))
            $this->pa_id=$_REQUEST['pa_id'];
        else
            $this->pa_id="";

    }
    /*!
     * \brief Compute  the form to display
     * \param $p_hidden hidden tag to be included (gDossier,...)
     *
     *
     * \return string containing the data
     */
    function display_form($p_hidden="")
    {
        /* if there is no analytic plan return */
        $pa=new Anc_Plan($this->db);
        if ( $pa->count() == 0 )
        {
            echo '<h2 class="error">'._('Aucun plan défini').'</h2>';
            exit();
        }

        $from=new IDate('from','from');
        $from->size=10;
        $from->value=$this->from;

        $to=new IDate('to','to');
        $to->value=$this->to;
        $to->size=10;

        $from_poste=new IText('from_poste','from_poste');
        $from_poste->size=10;
        $from_poste->value=$this->from_poste;

        $to_poste=new IText('to_poste','to_poste');
        $to_poste->value=$this->to_poste;
        $to_poste->size=10;

        $hidden=new IHidden();
        $r=dossier::hidden();
        $r.=$hidden->input("result","1");
        $r.="Depuis : ".$from->input();
        $r.= "jusque : ".$to->input();
        $r.= '<span class="notice">'._('Les dates sont en format DD.MM.YYYY').'</span>';

        $r.=$p_hidden;
	$r.=HtmlInput::hidden('ac',$_REQUEST['ac']);
        $r.='<span style="padding:5px;margin:5px;display:block;">';
        $plan=new Anc_Plan($this->db);
        $plan_id=new ISelect("pa_id");
        $plan_id->value=$this->db->make_array("select pa_id, pa_name from plan_analytique order by pa_name");
        $plan_id->selected=$this->pa_id;
        $r.=_( "Plan Analytique :").$plan_id->input();

        $poste=new IText();
        $poste->size=10;
        $r.=_("Entre l'activité ").$poste->input("from_poste",$this->from_poste);
        $choose=new IButton();
        $choose->name=_("Choix Poste");
        $choose->label=_("Recherche");
        $choose->javascript="onClick=search_ca(".dossier::id().",'from_poste','pa_id')";
        $r.=$choose->input();

        $r.=_(" et l'activité ").$poste->input("to_poste",$this->to_poste);
        $choose->javascript="onClick=search_ca(".dossier::id().",'to_poste','pa_id')";
        $r.=$choose->input();
        $r.='<span class="notice" style="display:block">'._('Selectionnez le plan qui vous intéresse avant de cliquer sur Recherche').'</span>';

        $r.='</span>';
        return $r;
    }
    /*!
     * \brief Set the filter (account_date)
     *
     * \return return the string to add to load
     */

    function set_sql_filter()
    {
        $sql="";
        $and=" and ";
        if ( $this->from != "" )
        {
            $sql.="$and a.oa_date >= to_date('".$this->from."','DD.MM.YYYY')";
        }
        if ( $this->to != "" )
        {
            $sql.=" $and a.oa_date <= to_date('".$this->to."','DD.MM.YYYY')";
        }

        return $sql;

    }
  function check()
  {

    /*
     * check date
     */
    if (($this->from != '' && isDate ($this->from) == 0)
	||
	($this->to != '' && isDate ($this->to) == 0))
      return -1;

    return 0;
  }


}
