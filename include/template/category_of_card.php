<TABLE BORDER="0" CELLSPACING="0">
<TR>
<TD> Catégorie de fiche </TD>
<TD><INPUT TYPE="INPUT" NAME="nom_mod"></TD>
</TR>
<TR>
<TD> Classe de base </TD>
<TD><INPUT TYPE="TEXT" value="<?php echo $base;?>" id="class_base" NAME="class_base"> <INPUT TYPE="BUTTON" VALUE="Cherche" OnClick="<?php echo $p_js;?>"></TD>
<td><span id="class_base_label"></span></td>
</TR>
<TR>
<TD> <INPUT TYPE="CHECKBOX" NAME="create" UNCHECKED>Cr&eacute;ation automatique du poste comptable</TD>
</TR>
</TABLE>
<p class="info">
Si vous utilisez la création automatique de poste, chaque nouvelle fiche de cette catégorie aura son propre poste comptable. Ce poste comptable sera la classe de base augmenté de 1.
</p>
<p class="info">
Si vous n'utilisez pas la création automatique, toutes les nouvelles fiches auront par défaut le même poste comptable. Ce poste comptable par défaut est la classe de base.
</p>
<p class="info">
 A moins qu'en créant la fiche, vous forcez un autre poste 
comptable.  
</p>