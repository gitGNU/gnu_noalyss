<TABLE class="result">
    <tr>
        <th>Pièce</th>
        <th>Date</th>
        <th>Ref</th>
        <th>Client / Fournisseur</th>
        <th>Description</th>
        <th style="text-align:right">HTVA</th>
        <th style="text-align:right">Privé</th>
        <th style="text-align:right">DNA</th>
        <th style="text-align:right">TVA ND</th>
        
<?php
$col_tva="";

 if ( $own->MY_TVA_USE=='Y')
        {
            $a_Tva=$cn->get_array("select tva_id,tva_label from tva_rate where tva_rate != 0.0000 order by tva_rate");
            foreach($a_Tva as $line_tva)
            {
                $col_tva.='<th style="text-align:right">Tva '.$line_tva['tva_label'].'</th>';
            }
        }
echo $col_tva;      
?>
        <th style="text-align:right">TVAC</th>
        <th>Opérations rapprochées</th>
    </tr>
<?php
$i = 0;
$cn->prepare('reconcile_date','select * from jrn where jr_id in (select jra_concerned from jrn_rapt where jr_id = $1 union all select jr_id from jrn_rapt where jra_concerned=$1)');
foreach ($Row as $line) {
    $i++;
    /*
     * Get date of reconcile operation
     */
    $ret_reconcile=$cn->execute('reconcile_date',array($line['jr_id']));
   
    $class = ($i % 2 == 0) ? ' class="even" ' : ' class="odd" ';
    echo "<tr $class>";
    echo "<TD>" . smaller_date($line['date']) . "</TD>";
    echo "<TD>" . h($line['jr_pj_number']) . "</TD>";
    echo "<TD>" . HtmlInput::detail_op($line['jr_id'], $line['jr_internal']) . "</TD>";
    $tiers = $Jrn->get_tiers($line['jrn_def_type'], $line['jr_id']);
    echo td($tiers);
    echo "<TD>" . h($line['comment']) . "</TD>";
    $dep_priv=($line['dep_priv']==0)?"":nbm($line['dep_priv']);
    $dna=($line['dna']==0)?"":nbm($line['dna']);
    $tva_dna=($line['tva_dna']==0)?"":nbm($line['tva_dna']);
    echo "<TD class=\"num\">" . nbm($line['HTVA']) . "</TD>";
    echo "<TD class=\"num\">" .$dep_priv . "</TD>";
    echo "<TD class=\"num\">" . $dna . "</TD>";
    echo "<TD class=\"num\">" . $tva_dna. "</TD>";
    if ($own->MY_TVA_USE == 'Y' )
    {
        $a_tva_amount=array();
        foreach ($line['TVA'] as $lineTVA)
                {
                    foreach ($a_Tva as $idx=>$line_tva)
                    {

                        if ($line_tva['tva_id'] == $lineTVA[1][0])
                        {
                            $a=$line_tva['tva_id'];
                            $a_tva_amount[$a]=$lineTVA[1][2];
                        }
                    }
                }
                    foreach ($a_Tva as $line_tva)
                    {
                        $a=$line_tva['tva_id'];
                        if ( isset($a_tva_amount[$a]))
                            echo '<td class="num">'.nb($a_tva_amount[$a]).'</td>';
                        else
                            printf("<td class=\"num\"></td>");
                    }
    }
    echo '<td class="num">'.$line['TVAC'].'</td>';
    /*
     * If reconcile print them
     */
    echo '<td>';
    $max=Database::num_row($ret_reconcile);
    if ($max > 0) {
        $sep="";
        for ($e=0;$e<$max;$e++) {
            $row=Database::fetch_array($ret_reconcile, $e);
            echo $sep.HtmlInput::detail_op($row['jr_id'],$row['jr_date'].' '. $row['jr_internal']);
            $sep=' ,';
        }
    }
    echo '</td>';
    echo "</tr>";
}
?>
</table>