<TABLE BORDER="0" CELLSPACING="0">
<TR>
   <TD><?=_('Nom de la catégorie de fiche')?> </TD>
<TD><INPUT TYPE="INPUT" NAME="nom_mod"></TD>
</TR>
<TR>
   <TD> <?=_('Classe de base')?> </TD>
<TD>
<?=$str_poste?>
</TD>
<td><span id="class_base_label"></span></td>
</TR>
<TR>
   <TD> <INPUT TYPE="CHECKBOX" NAME="create" UNCHECKED><?=_('Création automatique du poste comptable')?></TD>
</TR>
</TABLE>
<p class="info">
   <?=_('Si vous utilisez la création automatique de poste, chaque nouvelle fiche de cette catégorie aura son propre poste comptable. Ce poste comptable sera la classe de base augmenté de 1.')?>
</p>
<p class="info">
   <?=_('Si vous n\'utilisez pas la création automatique, toutes les nouvelles fiches auront par défaut le même poste comptable. Ce poste comptable par défaut est la classe de base.')?>
</p>
<p class="info">
<?=_(' A moins qu\'en créant la fiche, vous forcez un autre poste comptable')?>
</p>
