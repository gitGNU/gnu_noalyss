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
    </tr>
<?php
$i = 0;
foreach ($Row as $line) {
    $i++;
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
    echo "</tr>";
}
?>
</table>