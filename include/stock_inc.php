<?
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


/* function ViewStock ($p_cn)
 **************************************************
 * Purpose : show the listing of all goods 
 *        
 * parm : 
 *	- database connection
 * gen :
 *	-
 * return: string containing the table
 */
function ViewStock($p_cn,$p_year) {

  // build sql
  $sql= 'select C.sg_code,sum(deb) as deb_sum,sum(cred) as cred_sum
      from jrnx 
    join stock_goods using (j_id)
    join
    ( select sg_code,
                case when sg_type=\'d\' then sg_quantity else 0 end as deb,
                case when sg_type=\'c\' then sg_quantity else 0 end as cred
               from stock_goods 
       where sg_code is not null
     ) as C on (stock_goods.sg_code=C.sg_code)
 left outer join (select f_id,av_text as name 
         from jnt_fic_att_value join attr_value using (jft_id) where ad_id='.ATTR_DEF_NAME.'
     ) as B on stock_goods.f_id=B.f_id


    where 

    to_char(j_date,\'YYYY\') = \''.$p_year.'\'
group by c.sg_code
';

  // send the sql
  $Res=ExecSql($p_cn,$sql);

  if ( ( $M = pg_NumRows($Res)) == 0 ) return null;
  // store it in a HTLM table
  $result="<table>";
  $result.="<tr>";
  $result.='<th>Nom</th>';
  $result.='<th>Entrée</th>';
  $result.='<th>Sortie</th>';
  $result.='<th>Solde</th>';
  $result.="</tr>";

  // Sql result => table
  for ($i = 0; $i < $M ; $i++ ) {
    $r=pg_fetch_array($Res,$i);
    $result.="<TR>";
    // name and link to details
/*    $result.="<td>".'<a class="one" 
              HREF="stock.php?action=detail&f_id='.$r['f_id'].'&year='.$p_year.'">'.
      $r['name']."</A></td>";
*/
    $result.="<td>".$r['deb_sum']."</td>";
    $result.="<td>".$r['cred_sum']."</td>";
    $result.="<td>".$r['deb_sum']-$r['cred_sum']."</td>";
    $result.="</tr>";

  }
  $result.="</table>";

  return $result;
}
/* function ViewDetailStock ($p_cn,$p_f_id,$p_year) 
 **************************************************
 * Purpose : return all the stock movement
 *        
 * parm : 
 *	- p_cn database connection
 *      - p_f_id f_id concerned card
 *      - year
 * gen :
 *	- none
 * return: table with the html code (string)
 */
function ViewDetailStock($p_cn,$p_f_id,$p_year) {


}

