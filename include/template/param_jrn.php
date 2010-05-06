<TABLE>
<TR>

		<TD><?=_('Nom journal')?> </TD>
		<TD> <INPUT TYPE="text" NAME="p_jrn_name" VALUE="<?php	echo $name;	?>"></TD>
</TR>
<TR>
<td><?=_('Postes utilisables journal (débit/crédit)')?> 
</TD>
<td>
<?php echo $search;?>
</TD>
</TR>
<tr>
<td><INPUT TYPE="hidden" id="p_jrn_deb_max_line" NAME="p_jrn_deb_max_line" VALUE="10"></td>
</tr>
<tr>
<td><INPUT TYPE="hidden" id="p_jrn_deb_max_line" NAME="p_jrn_deb_max_line" VALUE="10"></td>
</tr>
<tr><td><INPUT TYPE="hidden" id="p_ech_lib" NAME="p_ech_lib" VALUE="echeance"></td>
</tr>
<TR>
<TD><?=_('Type de journal')?> </TD>
<TD>
<?php echo $type;?>
</TD>
</TR>
<TR>
<TD><?=_('Préfixe code interne')?> </TD><TD>
<?php echo $code?> </TD>
</TR>
<TR>
<TD><?=_('Préfixe pièce justificative')?>
</TD>
<TD>
<?php echo $pj_pref; ?>
</TD>
<TD>
<span class="notice"><?=_('Le préfixe des pièces peut être différent pour chaque journal')?></span><br>
<span class="notice"><?=_('Uniquement des chiffres')?></span>
</TD>

</TR>
<TR>
<TD>
  <?=_('Dernière pièce numérotée')?>
</TD>
<TD>
<?=$last_seq?>
</TD>
</TR>
<tr>
<TD><?=_('N° pièce justificative')?>
</TD>
<TD>
<?php echo $pj_seq; ?>
</TD>
<TD>
<span class="notice"><?=_('La numérotation est propre à chaque journal')?></span>
<span class="notice"><?=_('Laissez à 0 pour ne pas changer le numéro')?></span>
</TD>

</TR>
</TABLE>
<H2 class="info"> Fiches </H2>
<TABLE width="100%">
<TR>

<th><?=_('Fiches Débit')?></TH>
<th><?=_('Fiches Crédit')?></TH>
</TR>
<?php
// Show the fiche in deb section
$Res=$cn->exec_sql("select fd_id,fd_label from fiche_def order by fd_label");
$num=$cn->size();

for ($i=0;$i<$num;$i++) {
  $res=$cn->fetch($i);
  $CHECKED=" unchecked";
  foreach ( $rdeb as $element) {
    if ( $element == $res['fd_id'] ) {
      $CHECKED="CHECKED";
      break;
    }
  }
  echo '<TR>';
  printf ('<TD> <INPUT TYPE="CHECKBOX" VALUE="%s" NAME="FICHEDEB[]" %s>%s</TD>',
	  $res['fd_id'],$CHECKED,$res['fd_label']);
  $CHECKED=" unchecked";
  foreach ( $rcred as $element) {
    if ( $element == $res['fd_id'] ) {
      $CHECKED="CHECKED";
      break;
    }
  }

  printf ('<TD> <INPUT TYPE="CHECKBOX" VALUE="%s" NAME="FICHECRED[]" %s>%s</TD>',
	  $res['fd_id'],$CHECKED,$res['fd_label']);
  echo '</TR>';
}
?>
</TABLE>
