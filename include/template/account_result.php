<fieldset id="asearch" style="height:88%">
<legend><?php echo _('Résultats')?></legend>
<div style="height:88%;overflow:auto;">
	<?php
		$limite=5;
		?>
<table
	<tr>
		<th>Poste comptable</th>
		<th>Libellé</th>
		<th>Fiche (limite:<?php echo $limite; ?>)</th>

	</tr>
<?php for ($i=0;$i<sizeof($array);$i++) : ?>
<tr>
<td>
<a href="javascript:void(0)" onclick="<?php echo $array[$i]['javascript']?>">
<span  id="val<?php echo $i?>">
<?php echo $array[$i]['pcm_val']?>
</span>
</a>
</td>
<td>
<span id="lib<?php echo $i?>">
<?php echo $array[$i]['pcm_lib']?>
</span>
</td>
<td>
	<?php
	if ( strlen($array[$i]['acode']) >0 ) {
		if (strpos($array[$i]['acode'], ",") >0 ) {

			$det_qcode=  explode(",", $array[$i]['acode']);
			$sep="";
			$max=(count($det_qcode)>$limite)?$limite:count($det_qcode);
			for ($e=0;$e<$max;$e++) {
				echo $sep.HtmlInput::card_detail($det_qcode[$e]);
				$sep=" , ";
			}
			if ($max < count($det_qcode)) {
				echo "...";
			}
		} else {
			echo HtmlInput::card_detail($array[$i]['acode']);
		}
	}
	?>
</td>
</tr>


<?php endfor; ?>
</table>
<span style="font-style:italic">
<?php echo _("Nombre d'enregistrements")." ".$i;?>
</span>

</div>
</fieldset>