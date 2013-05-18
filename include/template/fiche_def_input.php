<TABLE BORDER="0" CELLSPACING="0">
<TR>
<TD><?php echo ('Catégorie de fiche')?> </TD>
<TD><INPUT TYPE="INPUT" NAME="nom_mod"></TD>
</TR>
<tr>
	<td style="vertical-align: text-top">
		Description
	</td>
	<td>
		<?php echo $fd_description->input(); ?>
	</td>
</tr>
<TR>
   <TD> <?php echo _('Classe de base')?> </TD>
<TD><?php echo $f_class_base?> </TD>
<td><span id="class_base_label"></span></td>
</TR>
<TR>
<TD colspan='2'> <INPUT TYPE="CHECKBOX" NAME="create" CHECKED><?php echo _('Création automatique du poste comptable uniquement s\'il n\y a qu\'un seul poste')?></TD>
</TR>
<?php
  if ( sizeof($ref)  ) {
    foreach ($ref as $i=>$v) { ?>
<TR><TD style="width:auto" COLSPAN="2">
<?php echo $iradio->input("FICHE_REF",$v['frd_id']);
   echo $v['frd_text'];
   if ( sizeof ($v['frd_class_base']) != 0 )
	   echo "&nbsp;&nbsp<I>Class base = ".$v['frd_class_base']."</I>";
      echo "</TD></TR>";

    }

  }
?>

</TABLE>
