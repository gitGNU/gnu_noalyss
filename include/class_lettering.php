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
 * \brief letter the accounting entry (row level)
 */
require_once ('class_user.php');

/**
 *@brief mother class for the lettering by account and by card
 * use the tables jnt_letter, letter_deb and letter_cred
 * - "account"=>"account",       => the accounting of the j_id (use by Lettering_Account) 
 * - "quick_code"=>"quick_code", => the quick_code of the j_id (used by Lettering_Card) 
 * - "start"=>"start",	   => date of the first day 
 * - "end"=>"end",		   => date of the last day 
 * - "sql_ledger"=>"sql_ledger"  => the sql clause to filter on the available ledgers 
*/
class Lettering
{

    protected $variable=array("account"=>"account", /* the accounting of the j_id (use by Lettering_Account) */
                              "quick_code"=>"quick_code", /* the quick_code of the j_id (used by Lettering_Card) */
                              "start"=>"start",		/* date of the first day */
                              "end"=>"end",		/* date of the last day */
                              "sql_ledger"=>"sql_ledger"	/*   the sql clause to filter on the available ledgers */
                             )
                        ;
    /**
     * constructor
     *@param $p_init resource to database
     *@note by default start and end are the 1.1.exercice to 31.12.exercice
     */
    function __construct ($p_init)
    {
        $this->db=$p_init;
        $a=new User($p_init);
        $exercice=$a->get_exercice();
        $this->start='01.01.'.$exercice;
        $this->end='31.12.'.$exercice;
        // available ledgers
        $this->sql_ledger=str_replace('jrn_def_id','jr_def_id',$a->get_ledger_sql('ALL',3));

    }
    public function get_parameter($p_string)
    {
        if ( array_key_exists($p_string,$this->variable) )
        {
            $idx=$this->variable[$p_string];
            return $this->$idx;
        }
        else
            throw new Exception (__FILE__.":".__LINE__.$p_string.'Erreur attribut inexistant');
    }
    public function set_parameter($p_string,$p_value)
    {
        if ( array_key_exists($p_string,$this->variable) )
        {
            $idx=$this->variable[$p_string];
            $this->$idx=$p_value;
        }
        else
            throw new Exception (__FILE__.":".__LINE__.$p_string.'Erreur attribut inexistant');
    }
    /**
     *Use to just insert a couple of lettered operation
     */
    function insert_couple($j_id1,$j_id2)
    {
        $jl_id=$this->db->get_next_seq("jnt_letter_jl_id_seq");
        $this->db->exec_sql('insert into jnt_letter(jl_id) values($1)',
                            array($jl_id));
        /*  take needed data */
        $first=$this->db->get_value('select j_debit from jrnx where j_id=$1',array($j_id1));
        if ( $this->db->count() == 0 ) throw new Exception ('Opération non existante');

        $second=$this->db->get_value('select j_debit from jrnx where j_id=$1',array($j_id2));
        if ( $this->db->count() == 0 ) throw new Exception ('Opération non existante');
        /* insert */
        if ( $first == 't')
        {
            // save into letter_deb
            $ld_id=$this->db->get_value('insert into letter_deb(j_id,jl_id) values($1,$2) returning ld_id',array($j_id1,$jl_id));
        }
        else
        {
            $lc_id=$this->db->get_value('insert into letter_cred(j_id,jl_id)  values($1,$2) returning lc_id',array($j_id1,$jl_id));
        }
        if ( $second == 't')
        {
            // save into letter_deb
            $ld_id=$this->db->get_value('insert into letter_deb(j_id,jl_id) values($1,$2) returning ld_id',array($j_id2,$jl_id));
        }
        else
        {
            $lc_id=$this->db->get_value('insert into letter_cred(j_id,jl_id)  values($1,$2) returning lc_id',array($j_id2,$jl_id));
        }

    }
    public function get_info()
    {
        return var_export(self::$variable,true);
    }
    public function verify()
    {
        // Verify that the elt we want to add is correct
    }
    /**
     *@brief save from array
     *@param $p_array
    @code
    'gDossier' => string '13' (length=2)
    'letter_j_id' => 
      array
        0 => string '5' (length=1)
        1 => string '23' (length=2)
        2 => string '67' (length=2)
        3 => string '136' (length=3)
        4 => string '139' (length=3)
        5 => string '145' (length=3)
        6 => string '374' (length=3)
        7 => string '148' (length=3)
        8 => string '156' (length=3)
        9 => string '254' (length=3)
        10 => string '277' (length=3)
    'ck0' => string 'on' (length=2)
    'ck1' => string 'on' (length=2)
    'ck10' => string 'on' (length=2)
    'j_id' => string '142' (length=3)
    'jnt_id' => string '-2' (length=2)
    'record' => string 'Sauver' (length=6)
    @endcode
    */
    public function save($p_array)
    {
        if ( ! isset ($p_array['letter_j_id'])) return;
        $this->db->exec_sql('delete from jnt_letter where jl_id=$1',array($p_array['jnt_id']));

        $this->db->start();
        $jl_id=$this->db->get_next_seq("jnt_letter_jl_id_seq");
        $this->db->exec_sql('insert into jnt_letter(jl_id) values($1)',
                            array($jl_id));

        // save the source
        $deb=$this->db->get_value('select j_debit,j_montant from jrnx where j_id=$1',array($p_array['j_id']));
        if ( $deb == 't')
        {
            // save into letter_deb
            $ld_id=$this->db->get_value('insert into letter_deb(j_id,jl_id) values($1,$2) returning ld_id',array($p_array['j_id'],$jl_id));
        }
        else
        {
            $lc_id=$this->db->get_value('insert into letter_cred(j_id,jl_id)  values($1,$2) returning lc_id',array($p_array['j_id'],$jl_id));
        }
        $count=0;
        // save dest
        for($i=0;$i<count($p_array['letter_j_id']);$i++)
        {
            if (isset($p_array['ck'.$i]))
            { //if 1
                // save the dest
                $deb=$this->db->get_value('select j_debit,j_montant from jrnx where j_id=$1',array($p_array['letter_j_id'][$i]));
                if ( $deb == 't')
                {
                    $count++;
                    // save into letter_deb
                    $ld_id=$this->db->get_value('insert into letter_deb(j_id,jl_id) values($1,$2) returning ld_id',array($p_array['letter_j_id'][$i],$jl_id));
                }
                else
                {
                    $count++;
                    $lc_id=$this->db->get_value('insert into letter_cred(j_id,jl_id)  values($1,$2) returning lc_id',array($p_array['letter_j_id'][$i],$jl_id));
                }
            } //end if 1
        } //end for
        // save into jnt_letter
        /* if only one row we delete the joint */
        if ( $count==0)
        {
            $this->db->rollback();
        }
        $this->db->commit();
    }
    /**
     *@brief retrieve * row thanks a condition
     */
    public function seek($cond,$p_array=null)
    {
        /*
          $sql="select * from * where $cond";
          return $this->cn->get_array($cond,$p_array)
        */
    }
    public function insert()
    {
        if ( $this->verify() != 0 ) return;

    }
    /**
     *show all the record from jrnx and their status (linked or not)
     *it fills the array $this->content
     */
    protected function show_all()
    {
        $this->get_all();
        $r="";
        ob_start();
        include('template/letter_all.php');
        $r=ob_get_contents();
        ob_clean();
        return $r;
    }
    /**
     *show only the lettered records from jrnx 
     *it fills the array $this->content
     */
    protected function show_lettered()
    {
        $this->get_letter();
        $r="";
        ob_start();
        include('template/letter_all.php');
        $r=ob_get_contents();
        ob_clean();
        return $r;
    }
    /**
     *show only the not lettered records from jrnx 
     *it fills the array $this->content
     */
    protected function show_not_lettered()
    {
        $this->get_unletter();
        $r="";
        ob_start();
        include('template/letter_all.php');
        $r=ob_get_contents();
        ob_clean();
        return $r;
    }
    /**
     *wrapper : it call show_all, show_lettered or show_not_lettered depending
     * of the parameter
     *@param $p_type poss. values are all, unletter, letter
     */
    public function show_list($p_type)
    {
        switch($p_type)
        {
        case 'all':
                return $this->show_all();
            break;
        case 'unletter':
            return $this->show_not_lettered();
            break;
        case 'letter':
            return $this->show_lettered();
            break;
        }
        throw new Exception ("[$p_type] is no unknown");
    }

    public function show_letter($p_jid)
    {
        $j_debit=$this->db->get_value('select j_Debit from jrnx where j_id=$1',array($p_jid));
        $amount_init=$this->db->get_value('select j_montant from jrnx where j_id=$1',array($p_jid));
        $this->get_filter($p_jid);
        // retrieve jnt_letter.id
        $sql="select distinct(jl_id) from jnt_letter  left outer join letter_deb using (jl_id) left outer join letter_cred using (jl_id)
             where letter_deb.j_id = $1 or letter_cred.j_id=$2";
        $jnt_id=$this->db->get_value($sql,array($p_jid,$p_jid));

        if ($this->db->count()==0 ) $jnt_id=-2;

        ob_start();
        require_once('template/letter_prop.php');
        $r=ob_get_contents();
        ob_clean();
        $r.=HtmlInput::hidden('j_id',$p_jid);
        $r.=HtmlInput::hidden('jnt_id',$jnt_id);

        return $r;
    }

    public function update()
    {
        if ( $this->verify() != 0 ) return;
    }

    public function load()
{}

    public function delete()
    {
        throw new Exception ('delete not implemented');
    }
    /**
     * Unit test for the class
     */
    static function test_me()
    {}

}
/**
 * only for operation retrieved thanks a account (jrnx.j_poste)
 * manage the accounting entries for a given account
 */

class Lettering_Account extends Lettering
{
    function __construct($p_init,$p_account=null)
    {
        parent::__construct($p_init);
        $this->account=$p_account;
        $this->object_type='account';
    }
    /**
     * fills the this->content, datas are filtered thanks
     * - fil_deb poss values t (debit), f(credit), ' ' (everything)
     * - fil_amount_max max amount
     * - fil_amount_min min amount
     * - $this->start min date
     * - $this->end max date
     * - this->account: accounting
     */
    public function get_filter($p_jid=0)
    {
        $filter_deb='';
        if (isset($this->fil_deb))
        {
            switch ($this->fil_deb)
            {
            case 0:
                $filter_deb=" and j_debit='t' ";
                break;
            case 1:
                $filter_deb=" and j_debit='f' ";
                break;
            case 2:
                $filter_deb=" ";
                break;
            }

        }
        $filter_amount="";
        if ( isset ($this->fil_amount_max ) &&
                isset ($this->fil_amount_min ) &&
                isNumber($this->fil_amount_max)==1 &&
                isNumber($this->fil_amount_min)==1 &&
                ($this->fil_amount_max != 0 || $this->fil_amount_min != 0) )
            $filter_amount=" and (j_montant >= $this->fil_amount_min and j_montant<=$this->fil_amount_max  or (coalesce(comptaproc.get_letter_jnt($p_jid),-1)= coalesce(comptaproc.get_letter_jnt(j_id),-1) and coalesce(comptaproc.get_letter_jnt($p_jid),-1) <> -1 )) ";
        $sql="
             select j_id,j_date,to_char(j_date,'DD.MM.YYYY') as j_date_fmt,
             j_montant,j_debit,jr_comment,jr_internal,jr_id,jr_def_id,
             coalesce(comptaproc.get_letter_jnt(j_id),-1) as letter
             from jrnx join jrn on (j_grpt = jr_grpt_id)
             where j_poste = $1 and j_date >= to_date($2,'DD.MM.YYYY') and j_date <= to_date ($3,'DD.MM.YYYY')
             and $this->sql_ledger
             $filter_deb
             $filter_amount
             order by j_date,j_id";

        $this->content=$this->db->get_array($sql,array($this->account,$this->start,$this->end));
    }

    /**
     * fills this->content with all the operation for the this->account(jrnx.j_poste)
     */
    public function get_all()
    {
        $sql="
             select j_id,j_date,to_char(j_date,'DD.MM.YYYY') as j_date_fmt,
             j_montant,j_debit,jr_comment,jr_internal,jr_id,jr_def_id,jr_pj_number,
             coalesce(comptaproc.get_letter_jnt(j_id),-1) as letter
             from jrnx join jrn on (j_grpt = jr_grpt_id)
             where j_poste = $1 and j_date >= to_date($2,'DD.MM.YYYY') and j_date <= to_date ($3,'DD.MM.YYYY')
             and $this->sql_ledger

             order by j_date,j_id";
        $this->content=$this->db->get_array($sql,array($this->account,$this->start,$this->end));
    }
    /**
     * same as get_all but only for lettered operation
     */
    public function get_letter()
    {
        $sql="
             select j_id,j_date,to_char(j_date,'DD.MM.YYYY') as j_date_fmt,jr_pj_number,
             j_montant,j_debit,jr_comment,jr_internal,jr_id,jr_def_id,
             coalesce(comptaproc.get_letter_jnt(j_id),-1) as letter
             from jrnx join jrn on (j_grpt = jr_grpt_id)
             where j_poste = $1 and j_date >= to_date($2,'DD.MM.YYYY') and j_date <= to_date ($3,'DD.MM.YYYY')
             and $this->sql_ledger
             and j_id in (select j_id from letter_deb join jnt_letter using (jl_id) union select j_id from letter_cred join jnt_letter using (jl_id) )
             order by j_date,j_id";
        $this->content=$this->db->get_array($sql,array($this->account,$this->start,$this->end));
    }
    /**
     * same as get_all but only for unlettered operation
     */

    public function get_unletter()
    {
        $sql="
             select j_id,j_date,to_char(j_date,'DD.MM.YYYY') as j_date_fmt,jr_pj_number,
             j_montant,j_debit,jr_comment,jr_internal,jr_id,jr_def_id,
             coalesce(comptaproc.get_letter_jnt(j_id),-1) as letter
             from jrnx join jrn on (j_grpt = jr_grpt_id)
             where j_poste = $1 and j_date >= to_date($2,'DD.MM.YYYY') and j_date <= to_date ($3,'DD.MM.YYYY')
             and $this->sql_ledger
             and j_id not in (select j_id from letter_deb join jnt_letter using (jl_id) union select j_id from letter_cred join jnt_letter using (jl_id) )
             order by j_date,j_id";
        $this->content=$this->db->get_array($sql,array($this->account,$this->start,$this->end));
    }

}
/**
 * only for operation retrieved thanks a quick_code
 * manage the accounting entries for a given card
 */
class Lettering_Card extends Lettering
{
    /**
     *constructor
     *@param $p_init db resource
     *@param $p_qcode quick_code of the jrnx.j_id
     */
    function __construct($p_init,$p_qcode=null)
    {
        parent::__construct($p_init);
        $this->quick_code=$p_qcode;
        $this->object_type='card';
    }
    /**
     * fills the this->content, datas are filtered thanks
     * - fil_deb poss values t (debit), f(credit), ' ' (everything)
     * - fil_amount_max max amount
     * - fil_amount_min min amount
     * - $this->start min date
     * - $this->end max date
     * - this->quick_code: quick_code
     */
    public function get_filter($p_jid=0)
    {
        $filter_deb='';
        if (isset($this->fil_deb))
        {
            switch ($this->fil_deb)
            {
            case 0:
                $filter_deb=" and j_debit='t' ";
                break;
            case 1:
                $filter_deb=" and j_debit='f' ";
                break;
            case 2:
                $filter_deb=" ";
                break;
            }

        }
        $filter_amount="";
        if ( isset ($this->fil_amount_max ) &&
                isset ($this->fil_amount_min ) &&
                isNumber($this->fil_amount_max)==1 &&
                isNumber($this->fil_amount_min)==1 &&
                ($this->fil_amount_max != 0 || $this->fil_amount_min != 0) )
	  $filter_amount=" and (j_montant between $this->fil_amount_min and $this->fil_amount_max or (coalesce(comptaproc.get_letter_jnt($p_jid),-1)= coalesce(comptaproc.get_letter_jnt(j_id),-1) and coalesce(comptaproc.get_letter_jnt($p_jid),-1) <> -1 )) ";
        $sql="
             select j_id,j_date,to_char(j_date,'DD.MM.YYYY') as j_date_fmt,
             j_montant,j_debit,jr_comment,jr_internal,jr_id,jr_def_id,
             coalesce(comptaproc.get_letter_jnt(j_id),-1) as letter
             from jrnx join jrn on (j_grpt = jr_grpt_id)
             where j_qcode = upper($1) and j_date >= to_date($2,'DD.MM.YYYY') and j_date <= to_date ($3,'DD.MM.YYYY')
             and $this->sql_ledger
             $filter_deb
             $filter_amount
             order by j_date,j_id";

        $this->content=$this->db->get_array($sql,array($this->quick_code,$this->start,$this->end));
    }
    /**
     * fills this->content with all the operation for the this->quick_code(j_qcode)
     */
    public function get_all()
    {
        $sql="
             select j_id,j_date,to_char(j_date,'DD.MM.YYYY') as j_date_fmt,jr_pj_number,
             j_montant,j_debit,jr_comment,jr_internal,jr_id,jr_def_id,
             coalesce(comptaproc.get_letter_jnt(j_id),-1) as letter
             from jrnx join jrn on (j_grpt = jr_grpt_id)
             where j_qcode = upper($1) and j_date >= to_date($2,'DD.MM.YYYY') and j_date <= to_date ($3,'DD.MM.YYYY')
             and $this->sql_ledger

             order by j_date,j_id";
        $this->content=$this->db->get_array($sql,array($this->quick_code,$this->start,$this->end));
    }
    /**
     * same as get_all but only for lettered operation
     */

    public function get_letter()
    {
        $sql="
             select j_id,j_date,to_char(j_date,'DD.MM.YYYY') as j_date_fmt,jr_pj_number,
             j_montant,j_debit,jr_comment,jr_internal,jr_id,jr_def_id,
             coalesce(comptaproc.get_letter_jnt(j_id),-1) as letter
             from jrnx join jrn on (j_grpt = jr_grpt_id)
             where j_qcode = upper($1) and j_date >= to_date($2,'DD.MM.YYYY') and j_date <= to_date ($3,'DD.MM.YYYY')
             and $this->sql_ledger
             and j_id in (select j_id from letter_deb join jnt_letter using (jl_id) union select j_id from letter_cred join jnt_letter using (jl_id) )
             order by j_date,j_id";
        $this->content=$this->db->get_array($sql,array($this->quick_code,$this->start,$this->end));
    }
    /**
     * same as get_all but only for unlettered operation
     */
    public function get_unletter()
    {
        $sql="
             select j_id,j_date,to_char(j_date,'DD.MM.YYYY') as j_date_fmt,jr_pj_number,
             j_montant,j_debit,jr_comment,jr_internal,jr_id,jr_def_id,
             coalesce(comptaproc.get_letter_jnt(j_id),-1) as letter
             from jrnx join jrn on (j_grpt = jr_grpt_id)
             where j_qcode = upper($1) and j_date >= to_date($2,'DD.MM.YYYY') and j_date <= to_date ($3,'DD.MM.YYYY')
             and $this->sql_ledger
             and j_id not in (select j_id from letter_deb join jnt_letter using (jl_id) union select j_id from letter_cred join jnt_letter using (jl_id) )
             order by j_date,j_id";
        $this->content=$this->db->get_array($sql,array($this->quick_code,$this->start,$this->end));
    }

}
