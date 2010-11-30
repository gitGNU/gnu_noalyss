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
 * \brief Function for managing the stock
 */
require_once('class_fiche.php');

/*!
 **************************************************
 * \brief  show the listing of all goods 
 *        
 *
 *\param  $cn  database connection
 * \param $p_year exercice
 *	-
 * \return string containing the table
 */
function ViewStock($p_cn,$p_year)
{
// build sql -- get the different merchandise sold or bought
    // during the p_year
    /* $sql=" select distinct sg_code
        from stock_goods 
         left outer join  jrnx on (stock_goods.j_id=jrnx.j_id)
        right outer join parm_periode on (parm_periode.p_id=jrnx.j_tech_per)
      where
        p_exercice= '$p_year'
        and sg_code is not null and sg_code != '' and sg_code!='null'";
    */
    $sql=" select distinct sg_code
         from stock_goods
         where
         sg_code is not null and sg_code not in ('','null','--not found--','- ERROR -')";



    // send the sql
    $Res=$p_cn->exec_sql($sql);


    if ( ( $M = Database::num_row($Res)) == 0 ) return null;
    // store it in a HTLM table
    $result='<table style="width:100%;border:solid blue 2px ;border-style:outset;">';
    $result.="<tr>";
    $result.='<th style="text-align:left">Code</th>';
    $result.='<th style="text-align:left">Noms</th>';
    $result.='<th style="text-align:right">Entrée</th>';
    $result.='<th style="text-align:right">Sortie</th>';
    $result.='<th style="text-align:right">Solde</th>';
    $result.="</tr>";

    // Sql result => table
    for ($i = 0; $i < $M ; $i++ )
    {
        $r=Database::fetch_array($Res,$i);
        $result.="<TR>";

        // sg_code  and link to details
        $result.="<td>".'<a class="one"
                 HREF="?p_action=stock&action=detail&sg_code='.$r['sg_code'].'&year='.$p_year.
                 '&'.dossier::get().'">'.
                     $r['sg_code']."</A></td>";

        // name
        $a_name=getFicheNameCode($p_cn,$r['sg_code']);
        $name="";
        if ( $a_name != null )
        {
            foreach ($a_name as $key=>$element)
            {
                $name.=$element['av_text'].",";
            }
        }// if ( $a_name
        $result.="<td> $name </td>";

        // Debit (in)
        $deb=GetQuantity($p_cn,$r['sg_code'],$p_year,'d');
        $deb=($deb=='')?"0.0":$cred;
        $result.='<td style="text-align:right">'.$deb."</td>";

        // Credit (out)
        $cred=GetQuantity($p_cn,$r['sg_code'],$p_year,'c');
        $cred=($cred=='')?"0.0":$cred;
        $result.='<td style="text-align:right">'.$cred."</td>";


        // diff
        $diff=$deb-$cred;
        $msg=($diff < 0)?_('Sortie /Vente '):_('Entrée/Achat ');
        $result.='<td style="text-align:right">'. $msg.(abs($diff))."</td>";
        $result.="</tr>";

    }
    $result.="</table>";

    return $result;
}
/*!
 ************************************************************
 * \brief  return an array of f_id and f_name
 *        
 * 
 * \param $p_cn database connection
 * \param _sg_code stock_goods.sg_code
 * \return
 *      - array (f_id, f_label) or null if nothing is found 
 */
function getFicheNameCode ($p_cn,$p_sg_code)
{
    // Sql stmt
    $sql="select distinct f_id,ad_value
         from stock_goods
         join fiche_detail using(f_id)
         where
         ad_id=".ATTR_DEF_STOCK."
         and sg_code='$p_sg_code'
         ";
// Execute
    $Res=$p_cn->exec_sql($sql);
    if ( ( $M=Database::num_row($Res)) == 0 ) return null;

// Store in an array
    for ( $i=0; $i<$M;$i++)
    {
        $r=Database::fetch_array($Res,$i);
        $a['f_id']=$r['f_id'];
        $fiche=new Fiche($p_cn,$r['f_id']);
        $a['av_text']=$fiche->getName();
        $result[$i]=$a;

    }

    return $result;

}
/*!
 **************************************************
 * \brief View the details of a stock 
 *        
 * 
 *\param $p_cn database connection
 * \param$p_sg_code
 * \param $p_year
 * \return HTML code
 */
function ViewDetailStock($p_cn,$p_sg_code,$p_year)
{
    $sql="select sg_id,
              sg_code,
         j_montant,
         coalesce(j_date,sg_date) as j_date,
         sg_quantity,
         sg_type,
         jr_id,
         coalesce(jr_comment,sg_comment) as comment,
         coalesce(jr_internal,'Changement manuel') as jr_internal,
         jr_id,
         case when sg_date is not null then sg_date else j_date end as stock_date
         from stock_goods
         left outer join jrnx using (j_id)
         left outer join jrn on jr_grpt_id=j_grpt
         where
         sg_code='$p_sg_code' and (
         sg_exercice = '$p_year'
         or j_tech_per in (select p_id from parm_periode where p_exercice='$p_year')
         )
         order by stock_date
         " ;
    // name

    $in_quantity=0;
    $out_quantity=0;
    $in_amount=0;
    $out_amount=0;
    $r="";
    $a_name=getFicheNameCode($p_cn,$p_sg_code);
    $name="";
    if ( $a_name != null )
    {
        foreach ($a_name as $key=>$element)
        {
            $name.=$element['av_text'].",";
        }
    }// if ( $a_name
    // Add java script for detail


    $r.='<H2 class="info">'.$p_sg_code."  Noms : ".$name.'</H2>';

    $Res=$p_cn->exec_sql($sql);
    if ( ($M=Database::num_row($Res)) == 0 ) return "no rows";
    $r.='<table class="result" >';
    $r.="<TR >";
    $r.="<th>Date </th>";
    $r.="<th>Entrée / Sortie </th>";
    $r.="<th>Description</th>";
    $r.="<th>Op&eacute;ration</th>";
    $r.="<th style=\"text-align:right\">Montant</th>";
    $r.="<th style=\"text-align:right\">Quantité</th>";
    $r.="<th style=\"text-align:right\">Prix/Cout Unitaire</th>";
    $r.="</TR>";
    $tot_quantity=0;
    for ( $i=0; $i < $M;$i++)
    {
        $l=Database::fetch_array($Res,$i);
        $r.="<tR id=\"stock".$l['sg_id']."\" >";

        // date
        $r.="<TD>";
        $r.=format_date($l['j_date']);
        $r.="</TD>";

        //type (deb = out cred=in)
        $r.="<TD>";
        $r.=($l['sg_type']=='c')?'OUT':'IN';
        $r.="</TD>";

        if ( $l['sg_type']=='c')
        {
            $quantity=(-1)*$l['sg_quantity'];
            $out_quantity+=$l['sg_quantity'];
            $out_amount+=$l['j_montant'];
        }
        else
        {
            $quantity=$l['sg_quantity'];
            $in_quantity+=$l['sg_quantity'];
            $in_amount+=$l['j_montant'];
        }
        $tot_quantity+=$quantity;
        // comment
        $r.="<TD>";
        $r.=h($l['comment']);
        $r.="</TD>";

        // jr_internal
        $r.="<TD>";
        if ( $l['jr_id'] != "")
            $r.= "<A class=\"detail\" style=\"text-decoration:underline\" HREF=\"javascript:modifyOperation('".$l['jr_id']."',".dossier::id().",0,'S')\" > ".$l['jr_internal']."</A>";
        else
            $r.=$l['jr_internal'];

        $r.="</TD>";



        //amount
        $r.='<TD align="right">';
        $r.=nbm($l['j_montant']);
        $r.="</TD>";
        //quantity
        $r.='<TD align="right">';
        $r.=abs($quantity);
        $r.="</TD>";

        // Unit Price
        $r.='<TD align="right">';
        $up="";
        if ( $l['sg_quantity'] != 0 )
            $up=round($l['j_montant']/$l['sg_quantity'],4);
        $r.=nbm($up);
        $r.="</TD>";
	$r.=td(HtmlInput::remove_stock($l['sg_id'],'Effacer'),'id="href'.$l['sg_id'].'"');
        $r.="</TR>";
    }// for ($i
    // write the total
    $msg=($tot_quantity<0)?'Sortie / Vente ':'Entrée / Achat ';
    $row=td('Total '.$msg,' colspan="4" style="width:auto;text-align:right"');
    $row.=td(abs($tot_quantity), 'style="text-align:right"');
    $row.=td();
    $r.=td($row);
    $r.="</table>";
    $r.='<div style="float:left">';
    $r.='<table>';
    $row=td('Quantité IN/Achetée').td($in_quantity,'style="text-align:right"');
    $r.=tr($row);
    $row=td('Quantité OUT/Vendue').td($out_quantity,'style="text-align:right"');
    $r.=tr($row);
    $row=td('Quantité ').td(abs($tot_quantity),'style="text-align:right"');
    $r.=tr($row);
    $r.='</table>';
    $r.='</div>';

    $r.='<div style="float:left;clear:right;margin-left:10%">';
    $r.='<table>';
    $row=td('Montant IN/Acheté').td($in_amount,'style="text-align:right"');
    $r.=tr($row);
    $row=td('Montant OUT/Vendu').td($out_amount,'style="text-align:right"');
    $r.=tr($row);
    $row=td('Montant ').td($out_amount-$in_amount,'style="text-align:right"');
    $r.=tr($row);
    $r.='</table>';
    $r.='</div>';

    return $r;

}
/*!
 **************************************************
 * \brief  
 *        
 * parm : 
 *	-
 * gen :
 *	-
 * return:
 */
function ChangeStock($p_sg_code,$p_year)
{
    $sg_date=date("d.m.Y");
    $r='<table>';
    $row=td('Description');
    $row.=td(' <input type="text" class="input_text"  name="comment" value="">');
    $r.=tr($row);
    $row=td('Valeur(positive pour IN ou négative comme OUT)');
    $row.=td(' <input type="text" class="input_text" name="stock_change" value="0">');
    $r.=tr($row);
    $row=td('Date');
    $row.=td(' <input type="text" class="input_text"  name="sg_date" value="'.$sg_date.'">');
    $r.=tr($row);
    $r.='</table>';
    $r.='<input type="hidden" name="sg_code" value="'.$p_sg_code.'">
        <input type="hidden" name="year" value="'.$p_year.'">';

    return $r;

}
/*!
 * \brief return the quantity of a sg_code for the period 
 *
 * \return number or NULL
 */
function GetQuantity($p_cn,$p_sg_code,$p_year,$p_type)
{
    $sql="select sum(sg_quantity) as result
         from stock_goods
         left join jrnx on (stock_goods.j_id=jrnx.j_id)
         left join parm_periode on (parm_periode.p_id=jrnx.j_tech_per)
         where
         sg_code='$p_sg_code' and
         (p_exercice = '$p_year' or to_char(sg_date::timestamp,'YYYY')='$p_year') and
         sg_type='$p_type'";
    $Res=$p_cn->exec_sql($sql);
    if ( Database::num_row($Res)== 0) return null;
    $value=Database::fetch_array($Res,0);
    return $value['result'];

}
