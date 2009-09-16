<fieldset><legend>Informations générales</legend>
<table>
<TR>
<TD>Date</TD><TD><?php echo $date->input(); ?></TD>
</TR>
<tr>
<TR>
<TD>Reference</TD><TD><?php echo $str_ag_ref; ?></TD>

<td>Concerne</td><td>Lien vers action concernéex</td>
<TD>Type Action</TD><TD><?php echo $str_doc_type;?></TD>
<TD>Etat</TD><td><?php echo $str_state;?><TD></TD>
</tr>
<TR>
<TD>Destinataire</TD><TD><?php echo $w->input(); ?><?php echo $sp->input();?></TD>

</TR>
</table>


</fieldset>
<fieldset><legend>Détail</legend>
<p>
Titre <br>
<?php echo $title->input(); ?>
<br>
Commentaire 
<br>
<?php echo $desc->input(); ?>
</p>

</fieldset>
<fieldset>
<legend>Pièces attachées</legend>
<?php
print_r($aAttachedFile);
for ($i=0;$i<sizeof($aAttachedFile);$i++) :
?>

<p>
<A class="mtitle" href="<?php echo $aAttachedFile[$i]['link']?>"> <?php echo $aAttachedFile[$i]['d_filename'];?> </A>
</p>

<?php
endfor;
?>
<script language="javascript">
function addFiles() {
try {
	docAdded=document.getElementById('add_file');
	new_element=document.createElement('li');
	new_element.innerHTML='<input class="inp" type="file" value="" name="file_upload[]"/>';
	docAdded.appendChild(new_element);
}
catch(exception) { alert('Je ne peux pas ajouter de fichier'); alert(exception);}
}
</script>
<p>
Ajout de document:
<ol id='add_file'>
<li><?php echo $upload->input(); ?></li>
</ol>
</p>
<input type="button" onclick="addFiles();" value="Ajout de fichier">
</fieldset>