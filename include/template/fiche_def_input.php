<TABLE BORDER="0" CELLSPACING="0">
<TR>
<TD><?=('Catégorie de fiche')?> </TD>
<TD><INPUT TYPE="INPUT" NAME="nom_mod"></TD>
</TR>
<TR>
   <TD> <?=_('Classe de base')?> </TD>
<TD><INPUT TYPE="INPUT" id="class_base" NAME="class_base"> <INPUT TYPE="BUTTON" VALUE="<?=_('Cherche')?>" OnClick="<?php echo $p_js;?>"></TD>
<td><span id="class_base_label"></span></td>
</TR>
<TR>
<TD> <INPUT TYPE="CHECKBOX" NAME="create" CHECKED><?=_('Création automatique du poste comptable')?></TD>
</TR>
<?php
  if ( sizeof($ref)  ) {
    foreach ($ref as $i=>$v) { ?>
<TR><TD COLSPAN="2">
<? echo $iradio->input("FICHE_REF",$v['frd_id']);
   echo $v['frd_text'];
   if ( sizeof ($v['frd_class_base']) != 0 )
	   echo "&nbsp;&nbsp<I>Class base = ".$v['frd_class_base']."</I>";
      echo "</TD></TR>";

    }

  }
?>

</TABLE>
