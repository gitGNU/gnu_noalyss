<table class="result">
<tr>
<th>
N°
</th>
<th>
Date
</th>
<th>
N° op
</th>
<th>
n° pièce
</th>
<th>
Libellé
</th>
<th>
Montant
</th>
</tr>
<?
for ($i=0;$i<count($array);$i++) {
	$r='';
	$r.=td($i);
	$r.=td(format_date($array[$i]['first']['jr_date']));
	$detail=sprintf('<a class="detail" href="javascript:modifyOperation(\'%s\',%d)">%s</a>',
			$array[$i]['first']['jr_id'],$gDossier,$array[$i]['first']['jr_internal']);
	$r.=td($detail);
	$r.=td($array[$i]['first']['jr_pj_number']);
	$r.=td($array[$i]['first']['jr_comment']);
	$r.=td($array[$i]['first']['jr_montant']);
	echo tr($r);
	if ( isset($array[$i]['depend']) )
	{
		$limit=count($array[$i]['depend'])-1;
		for ($e=0;$e<count($array[$i]['depend']);$e++) {
			$r='';
			$r.=td($i);
			$r.=td(format_date($array[$i]['depend'][$e]['jr_date']));
			$detail=sprintf('<a class="detail" href="javascript:modifyOperation(\'%s\',%d)">%s</a>',
					$array[$i]['depend'][$e]['jr_id'],$gDossier,$array[$i]['depend'][$e]['jr_internal']);
			$r.=td($detail);
			$r.=td($array[$i]['depend'][$e]['jr_pj_number']);
			$r.=td($array[$i]['depend'][$e]['jr_comment']);
			$r.=td($array[$i]['depend'][$e]['jr_montant']);
			if ( $e==$limit)
				echo '<tr style="border-bottom: 1px solid  #4f4f7d;">'.$r.'</tr>';
			else
				echo tr($r);
			}
	}
}
?>
</table>