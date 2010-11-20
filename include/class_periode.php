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
require_once ('class_database.php');
class Periode
{
    var $cn;			/*!< database connection */
    var $jrn_def_id;		/*!< the jr, 0 means all the ledger*/
    var $p_id;			/*!< pk of parm_periode */
    var $status;			/*!< status is CL for closed, OP for
                                           open and CE for centralized */
    var $p_start;			/*!< start of the periode */
    var $p_end;			/*!< end of the periode */
    function __construct($p_cn,$p_id=0)
    {
        $this->p_id=$p_id;
        $this->cn=$p_cn;
    }
    function set_jrn($p_jrn)
    {
        $this->jrn_def_id=$p_jrn;
    }
    function set_periode($pp_id)
    {
        $this->p_id=$pp_id;
    }
    /*!\brief return the p_id of the start and the end of the exercice
     *into an array
     *\param $p_exercice
     *\return array [start]=>,[end]=>
     */
    function limit_year($p_exercice)
    {
        $sql_start="select p_id from parm_periode where p_exercice=$1 order by p_start  ASC limit 1";
        $start=$this->cn->get_value($sql_start,array($p_exercice));
        $sql_end="select p_id from parm_periode where p_exercice=$1 order by p_end  DESC limit 1";
        $end=$this->cn->get_value($sql_end,array($p_exercice));
        return array("start"=>$start,"end"=>$end);
    }
    /*!\brief check if a periode is closed. If jrn_def_id is set to a no zero value then check only for this ledger
     *\return 1 is the periode is closed otherwise return 0
     */
    function is_closed()
    {
        if ( $this->jrn_def_id != 0 )
            $sql="select status from jrn_periode ".
                 " where jrn_def_id=".$this->jrn_def_id.
                 " and p_id =".$this->p_id;
        else
            $sql="select p_closed as status from parm_periode ".
                 " where ".
                 " p_id =".$this->p_id;
        $res=$this->cn->exec_sql($sql);
        $status=Database::fetch_result($res,0,0);
        if ( $status == 'CL' || $status=='t' ||$status=='CE')
            return 1;
        return 0;
    }
    function is_open()
    {
        /* if jrn_Def_id == 0 then we check the global otherwise we check
           a ledger */
        if ( $this->jrn_def_id != 0 )
            $sql="select status from jrn_periode ".
                 " where jrn_def_id=".$this->jrn_def_id.
                 " and p_id =".$this->p_id;
        else
            $sql="select p_closed as status from parm_periode ".
                 " where ".
                 " p_id =".$this->p_id;
        $res=$this->cn->exec_sql($sql);
        $status=Database::fetch_result($res,0,0);
        if ( $status == 'OP' || $status=='f' )
            return 1;
        return 0;
    }
    function is_centralized()
    {
        if ( $this->jrn_def_id != 0 )
            $sql="select status from jrn_periode ".
                 " where jrn_def_id=".$this->jrn_def_id.
                 " and p_id =".$this->p_id;
        else
            $sql="select p_centralized as status from parm_periode ".
                 " where ".
                 " p_id =".$this->p_id;
        $res=$this->cn->exec_sql($sql);
        $status=Database::fetch_result($res,0,0);
        if ( $status == 'CE' || $status=='t' )
            return 1;
        return 0;
    }
    function reopen()
    {
        if ( $this->jrn_def_id == 0 )
        {
	  $this->cn->exec_sql("update parm_periode set p_closed='f',p_central='f' where p_id=$1",
			    array($_GET['p_per']));

	  $this->cn->exec_sql("update jrn_periode set status='OP' ".
                                " where p_id = ".$this->p_id);

	  return;
        }
        else
        {
            $this->cn->exec_sql("update jrn_periode set status='OP' ".
                                " where jrn_def_id=".$this->jrn_def_id." and ".
                                " p_id = ".$this->p_id);
            /* if all ledgers have this periode open then synchro with
            the table parm_periode
            */
            $nJrn=$this->cn->count_sql( "select * from jrn_periode where ".
                                        " p_id=".$this->p_id);
            $nJrnPeriode=$this->cn->count_sql( "select * from jrn_periode where ".
                                               " p_id=".$this->p_id." and status='OP'");

            if ( $nJrnPeriode==$nJrn)
                $this->cn->exec_sql("update parm_periode set p_closed=false where p_id=".$this->p_id);
            return;
        }

    }

    function close()
    {
        if ( $this->jrn_def_id == 0 )
        {
            $this->cn->exec_sql("update parm_periode set p_closed=true where p_id=".
                                $this->p_id);
            $this->cn->exec_sql("update jrn_periode set status='CL' ".
                                " where p_id = ".$this->p_id);

            return;
        }
        else
        {
            $this->cn->exec_sql("update jrn_periode set status='CL' ".
                                " where jrn_def_id=".$this->jrn_def_id." and ".
                                " p_id = ".$this->p_id);
            /* if all ledgers have this periode closed then synchro with
            the table parm_periode
            */
            $nJrn=$this->cn->count_sql( "select * from jrn_periode where ".
                                        " p_id=".$this->p_id);
            $nJrnPeriode=$this->cn->count_sql( "select * from jrn_periode where ".
                                               " p_id=".$this->p_id." and status='CL'");

            if ( $nJrnPeriode==$nJrn)
                $this->cn->exec_sql("update parm_periode set p_closed=true where p_id=".$this->p_id);
            return;
        }

    }
    function centralized()
    {
        if ( $this->jrn_def_id == 0 )
        {
            $this->cn->exec_sql("update parm_periode set p_central=true");
            return;
        }
        else
        {
            $this->cn->exec_sql("update jrn_periode set status='CE' ".
                                " where ".
                                " p_id = ".$this->p_id);
            return;
        }

    }
    /*!
     * \brief Display all the periode and their status
     *
     */

    function display_form_periode()
    {
        $str_dossier=dossier::get();

        if ( $this->jrn_def_id==0 )
        {
            $Res=$this->cn->exec_sql("select p_id,to_char(p_start,'DD.MM.YYYY') as date_start,to_char(p_end,'DD.MM.YYYY') as date_end,p_central,p_closed,p_exercice,
                                     (select count(jr_id) as count_op from jrn where jr_tech_per = p_id) as count_op
                                     from parm_periode
                                     order by p_start,p_end");
            $Max=Database::num_row($Res);
            echo '<TABLE ALIGN="CENTER">';
            echo "</TR>";
            echo '<TH> Date d&eacute;but </TH>';
            echo '<TH> Date fin </TH>';
            echo '<TH> Exercice </TH>';
            echo "</TR>";

            for ($i=0;$i<$Max;$i++)
            {
                $l_line=Database::fetch_array($Res,$i);
		$class="even";
		if ( $i % 2 == 0 )
		  $class="odd";
		$style='';
		if ( $l_line['p_closed'] == 't')
		  $style="color:red";
                echo '<TR class="'.$class.'" style="'.$style.'">';
                echo '<TD ALIGN="CENTER"> '.$l_line['date_start'].'</TD>';
                echo '<TD  ALIGN="CENTER"> '.$l_line['date_end'].'</TD>';
                echo '<TD  ALIGN="CENTER"> '.$l_line['p_exercice'].'</TD>';

                if ( $l_line['p_closed'] == 't' )
                {
                    $closed=($l_line['p_central']=='t')?'<TD>Centralis&eacute;e</TD>':'<TD>Ferm&eacute;e</TD>';
                    $change='<TD></TD>';
		    $remove=sprintf(_('Nombre opérations %d'),$l_line['count_op']);
                    $remove=td($remove,' class="mtitle" ');
                    $change=td ('<A class="mtitle" HREF="?p_action=periode&action=reopen&p_per='.$l_line['p_id'].'&'.$str_dossier.'" onclick="return confirm(\''._('Confirmez Réouverture').' ?\')"> Réouverture</A>',' class="mtitle"');

                }
                else
                {
                    $closed='<TD class="mtitle">';
                    $closed.='<A class="mtitle" HREF="?p_action=periode&action=closed&p_per='.$l_line['p_id'].'&'.$str_dossier.'" onclick="return confirm(\''._('Confirmez cloture').' ?\')"> Cloturer</A></td>';
                    $change='<TD class="mtitle">';
                    $change.='<A class="mtitle" HREF="?p_action=periode&action=change_per&p_per='.
                             $l_line['p_id']."&p_date_start=".$l_line['date_start'].
                             "&p_date_end=".$l_line['date_end']."&p_exercice=".
                             $l_line['p_exercice']."&$str_dossier\"> Changer</A></td>";

		    $reopen=td("");


                    $remove='<TD class="mtitle">';


                    if ($l_line['count_op'] == 0 )
                    {
                        $remove.='<A class="mtitle" HREF="?p_action=periode&action=delete_per&p_per='.
                                 $l_line['p_id']."&$str_dossier\" onclick=\"return confirm('"._('Confirmez effacement ?')."')\" > Efface</A>";
                    }
                    else
                    {
                        $remove.=sprintf(_('Nombre opérations %d'),$l_line['count_op']);
                    }
                    $remove.='</td>';
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

        }
        else
        {
            $Res=$this->cn->exec_sql("select p_id,to_char(p_start,'DD.MM.YYYY') as date_start,to_char(p_end,'DD.MM.YYYY') as date_end,status,p_exercice
                                     from parm_periode join jrn_periode using (p_id) where jrn_def_id=".$this->jrn_def_id."
                                     order by p_start,p_end");
            $Max=Database::num_row($Res);
            $r=$this->cn->exec_sql('select jrn_Def_name from jrn_Def where jrn_Def_id='.
                                   $this->jrn_def_id);
            $jrn_name=Database::fetch_result($r,0,0);
            echo '<h2> Journal '.$jrn_name.'</h2>';
            echo '<TABLE ALIGN="CENTER">';
            echo "</TR>";
            echo '<TH> Date d&eacute;but </TH>';
            echo '<TH> Date fin </TH>';
            echo '<TH> Exercice </TH>';
            echo "</TR>";

            for ($i=0;$i<$Max;$i++)
            {
                $l_line=Database::fetch_array($Res,$i);
                if ( $l_line['status'] != 'OP' )
		  echo '<TR style="COLOR:RED">';
		else
		  echo '<TR>';
                echo '<TD ALIGN="CENTER"> '.$l_line['date_start'].'</TD>';
                echo '<TD  ALIGN="CENTER"> '.$l_line['date_end'].'</TD>';
                echo '<TD  ALIGN="CENTER"> '.$l_line['p_exercice'].'</TD>';

                if ( $l_line['status'] != 'OP' )
                {
		  $closed=td ('<A class="mtitle" HREF="?p_action=periode&action=reopen&p_per='.$l_line['p_id'].'&'.$str_dossier.'&jrn_def_id='.$this->jrn_def_id.'" onclick="return confirm(\''._('Confirmez Réouverture').' ?\')"> Réouverture</A>',' class="mtitle"');
		  //                    $closed=($l_line['status']=='CE')?'<TD>Centralisee</TD>':'<TD>Ferm&eacute;e</TD>';
                }
                else
                {
                    $closed='<TD class="mtitle">';
                    $closed.='<A class="mtitle" HREF="?p_action=periode&action=closed&p_per='.$l_line['p_id'].'&'.$str_dossier.'&jrn_def_id='.$this->jrn_def_id.'" onclick="return confirm(\''._('Confirmez Cloture').' ?\')"> Cloturer</A>';
                    $closed.='</td>';
                }
                echo "$closed";

                echo '</TR>';

            }
            echo '</TABLE>';

        }
    }
    function insert($p_date_start,$p_date_end,$p_exercice)
    {
        if (isDate($p_date_start) == null ||
                isDate($p_date_end) == null ||
                strlen (trim($p_exercice)) == 0 ||
                (string) $p_exercice != (string)(int) $p_exercice)
        {
            return 1;
        }
        $p_id=$this->cn->get_next_seq('s_periode');
        $sql=sprintf(" insert into parm_periode(p_id,p_start,p_end,p_closed,p_exercice)".
                     "values (%d,to_date('%s','DD.MM.YYYY'),to_date('%s','DD.MM.YYYY')".
                     ",'f','%s')",
                     $p_id,
                     $p_date_start,
                     $p_date_end,
                     $p_exercice);
        try
        {
            $this->cn->start();
            $Res=$this->cn->exec_sql($sql);
            $Res=$this->cn->exec_sql("insert into jrn_periode (jrn_def_id,p_id,status) ".
                                     "select jrn_def_id,$p_id,'OP' from jrn_def");
            $this->cn->commit();
        }
        catch (Exception $e)
        {
            $this->cn->rollback();
            return 1;
        }
        return 0;
    }
    /*!\brief load data from database
     *\return 0 on success and -1 on error
     */
    function load()
    {

        $row=$this->cn->get_array("select p_start,p_end,p_exercice,p_closed,p_central from parm_periode where p_id=$1",
                                  array($this->p_id));
        if ($row == null ) return -1;

        $this->p_start=$row[0]['p_start'];
        $this->p_end=$row[0]['p_end'];
        $this->p_exercice=$row[0]['p_exercice'];
        $this->p_closed=$row[0]['p_closed'];
        $this->p_central=$row[0]['p_central'];
        return 0;
    }

    /*!\brief return the max and the min periode of the exercice given
     *in parameter
     *\param $p_exercice is the exercice
     *\return an array of Periode object
     */
    function get_limit($p_exercice)
    {

        $max=$this->cn->get_value("select p_id from parm_periode where p_exercice=$1 order by p_start asc limit 1",array($p_exercice));
        $min=$this->cn->get_value("select p_id from parm_periode where p_exercice=$1 order by p_start desc limit 1",array($p_exercice));
        $rMax=new Periode($this->cn);
        $rMax->p_id=$max;
        if ( $rMax->load() ) throw new Exception('Periode n\'existe pas');
        $rMin=new Periode($this->cn);
        $rMin->p_id=$min;
        if ( $rMin->load() ) throw new Exception('Periode n\'existe pas');
        return array($rMax,$rMin);
    }
    /*!
     * \brief Give the start & end date of a periode
     * \param $p_periode is the periode id, if omitted the value is the current object
     * \return array containing the start date & the end date, index are p_start and p_end or NULL if
     * nothing is found
    \verbatim
    $ret['p_start']=>'01.01.2009'
    $ret['p_end']=>'31.01.2009'
    \endverbatim
     */
    public function get_date_limit($p_periode = 0)
    {
        if ( $p_periode == 0 ) $p_periode=$this->p_id;
        $sql="select to_char(p_start,'DD.MM.YYYY') as p_start,
             to_char(p_end,'DD.MM.YYYY')   as p_end
             from parm_periode
             where p_id=$1";
        $Res=$this->cn->exec_sql($sql,array($p_periode));
        if ( Database::num_row($Res) == 0) return null;
        return Database::fetch_array($Res,0);

    }
    /*!\brief return the first day of periode
     *the this->p_id must be set
     *\return a string with the date (DD.MM.YYYY)
     */
    public function first_day()
    {
        list($p_start,$p_end)=$this->get_date_limit($this->p_id);
        return $p_start;
    }
    /*!\brief return the last day of periode
     *the this->p_id must be set
     *\return a string with the date (DD.MM.YYYY)
     */
    public function last_day()
    {
        list($p_start,$p_end)=$this->get_date_limit($this->p_id);
        return $p_end;
    }

    function get_exercice($p_id=0)
    {
        if ( $p_id == 0 )  $p_id=$this->p_id;
        $sql="select p_exercice from parm_periode where p_id=".$p_id;
        $Res=$this->cn->exec_sql($sql);
        if ( Database::num_row($Res) == 0) return null;
        return Database::fetch_result($Res,0,0);

    }
    /*!\brief retrieve the periode thanks the date_end
    *\param $p_date format DD.MM.YYYY
     * \return the periode id
     *\exception if not periode is found or if more than one periode is found
     */
    function find_periode($p_date)
    {
        $sql="select p_id from parm_periode where p_start <= to_date($1,'DD.MM.YYYY') and p_end >= to_date($1,'DD.MM.YYYY') ";
        $ret=$this->cn->exec_sql($sql,array($p_date));
        $nb_periode=Database::num_row($ret);
        if (  $nb_periode == 0 )
            throw  (new Exception('Aucune période trouvée',101));
        if ( $nb_periode > 1 )
            throw  (new Exception("Trop de périodes trouvées $nb_periode pour $p_date",100));
        $per=Database::fetch_result($ret,0);
        $this->p_id=$per;
        return $per;
    }
    static function test_me()
    {
        $cn=new Database(dossier::id());
        $obj=new Periode($cn);
        $obj->set_jrn(1);
        $obj->display_form_periode();
    }
}
