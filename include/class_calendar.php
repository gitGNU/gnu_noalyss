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
 * \brief Display the calendar
 */
class Calendar
{
    var $current_date;
    private static $nb_day=array(31,28,31,30,31,30,31,31,30,31,30,31);

    function __construct()
    {
        /* get the current month */
        $this->current_date=getdate();
        $this->month=$this->current_date['mon'];
        $this->day=self::$nb_day[$this->month-1];
        $this->year=$this->current_date['year'];
        if ( $this->year % 4 == 0 && $this->month=2)
            $this->day=29;
    }

    /*!\brief fill the array given as parameter with the data from action_gestion
     *\param $p_array array of the date of the month
     */
    function fill_from_action(&$p_array)
    {
        $cn=new Database(dossier::id());
        $sql="select ag_id,substr(ag_title,0,20) as ag_title_fmt,ag_ref,
				to_char(ag_timestamp,'DD')::integer as ag_timestamp_day ".
             " from action_gestion ".
             " where ".
             " to_char(ag_timestamp,'MM')::integer=$1 ".
             " and to_char(ag_timestamp,'YYYY')::integer=$2 ".
             " and ag_cal='C' and ag_owner=$3";
        $array=$cn->get_array($sql,array($this->month,$this->year,$_SESSION['g_user']));
        for ($i=0;$i<count($array);$i++)
        {
            $ind=$array[$i]['ag_timestamp_day'];
            $p_array[$ind]=(isset($p_array[$ind]))?$p_array[$ind]:'';
            $p_array[$ind].='<A class="line" HREF="do.php?ac=FOLLOW&'.dossier::get().'&sa=detail&ag_id='.$array[$i]['ag_id'].'">';
            $p_array[$ind].="<span class=\"calfollow\">".h($array[$i]['ag_ref']." ".$array[$i]['ag_title_fmt']).'</span>';
            $p_array[$ind].="</A>";

        }
    }
    /*!\brief fill the array given as parameter with the data from todo
     *\param $p_array array of the date of the month
     */
    function fill_from_todo(&$p_array)
    {
        $cn=new Database(dossier::id());
        $sql="select tl_id,substr(tl_title,0,20) as tl_title_fmt,to_char(tl_date,'DD')::integer as tl_date_day ".
             " from todo_list ".
             " where ".
             " to_char(tl_date,'MM')::integer=$1 ".
             " and to_char(tl_date,'YYYY')::integer=$2 ".
             " and use_login=$3";
        $array=$cn->get_array($sql,array($this->month,$this->year,$_SESSION['g_user']));
        for ($i=0;$i<count($array);$i++)
        {
            $ind=$array[$i]['tl_date_day'];
            $p_array[$ind].="<span class=\"todo\">".h($array[$i]['tl_title_fmt']).'</span>';
        }
    }
    /*!\brief display a calendar after a call to Calendar::fill
     *\param none
     *\return HTML String
     */
    function display()
    {
        /* day */
        $cell=array();
        for ($i=0;$i<42;$i++)
        {
            $cell[$i]="";
        }
        $this->set_month_year();
        /* weekday */
        $week=array(_('Dimanche'),_('Lundi'),_('Mardi'),_('Mercredi'),_('Jeudi'),_('Vendredi'),_('Samedi'));

        $this->fill_from_action($cell);
        $this->fill_from_todo($cell);
        $wMonth=new ISelect('per');
        $cn=new Database(dossier::id());
        $wMonth->value=$cn->make_array("select p_id,to_char(p_start,'MM/YYYY') from parm_periode order by p_start");
        $wMonth->selected=$this->default_periode;
        $wMonth->javascript="onchange=change_month(this)";
        $wMonth->set_attribute('gDossier',dossier::id());
        $month_year=$wMonth->input().$wMonth->get_js_attr();

        ob_start();
        require_once('template/calendar.php');
        $ret=ob_get_contents();
        ob_end_clean();
        return $ret;
    }
    /**
     *@brief set correctly the month and the year with the default_periode
     */
    function set_month_year()
    {
        $cn=new Database(dossier::id());
        $array=$cn->get_array("select to_char(p_start,'MM') as month, to_char(p_start,'YYYY') as year ".
                              " from parm_periode where p_id=$1",array($this->default_periode));
        $this->month=(int)$array[0]['month'];
        $this->year=(int)$array[0]['year'];
        $this->day=self::$nb_day[$this->month-1];
        if ( $this->year % 4 == 0 && $this->month==2)
            $this->day=29;
    }
    /**
     *@brief get the periode from the preference of the current user
     * change the value of default_periode
     *@return $this->default_periode
     */
    function get_preference()
    {
        $cn=new Database(dossier::id());
        $today=date('d.m.Y');
        $p_id=$cn->get_value("
                select p_id from parm_periode
                where
                p_start <= to_date($1,'DD.MM.YYYY')
                and
                p_end >= to_date($1,'DD.MM.YYYY')",
                array($today));
        if ( $p_id == '')
        {
            $user=new User($cn);
            $p_id=$user->get_periode();
        }
		$this->default_periode=$p_id;
        return  $p_id;
    }
    /**
     *@brief set the periode to the parameter, change the value of $this->default_periode
     * there is no check on the periode
     */
    function set_periode($p_per)
    {
        $this->default_periode=$p_per;
    }

}
