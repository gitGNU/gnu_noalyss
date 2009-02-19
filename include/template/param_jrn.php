<TABLE>
<TR>
		
		<TD> Nom journal </TD>
		<TD> <INPUT TYPE="text" NAME="p_jrn_name" VALUE="<?php	echo $name;	?>"></TD>
</TR>
<TR>
<TD> Postes utilisables journal (d&eacute;bit/cr&eacute;dit) </TD>
<TD> 
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
<TD> Type de journal </TD>
<TD>
<?php echo $type;?>
</TD>
</TR>
<TR>
<TD> Préfixe code interne </TD><TD>
<?php echo $code?> </TD>
</TR>
<TR>
<TD>Préfixe pièce justificative
</TD>
<TD>
<?php echo $pj_pref; ?>
<span class="notice">Le préfixe des pièces peut être différent pour chaque journal</span>
</TD>
</TR>
<tr>
<TD>N° pièce justificative
</TD>
<TD>
<?php echo $pj_seq; ?>
<span class="notice">La numérotation est propre à chaque journal</span>
</TD>

</TR>
</TABLE>
<H2 class="info"> Fiches </H2>
<TABLE width="100%">
<TR>

<th> Fiches Débit</TH>
<th> Fiches Crédit</TH>
</TR>
<?php
// Show the fiche in deb section
$Res=ExecSql($cn,"select fd_id,fd_label from fiche_def order by fd_label");
$num=pg_NumRows($Res);

for ($i=0;$i<$num;$i++) {
  $res=pg_fetch_array($Res,$i);
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
