<fieldset><legend>Informations générales</legend>
<table width="100%">
<TR><TD>
	<table width="50%">
	<TR>
	<TD>Date</TD><TD><?php echo $date->input(); ?></TD>
	</TR>
	<tr>
	<TD>Reference</TD><TD><?php echo $str_ag_ref; ?></TD>
	</TD></TR>
	<TD>Destinataire</TD><TD><?php echo $w->input(); ?></td>
</TD>
</table>
</TD>
<TD>
<table>
<tr>
<td>Concerne</td><td>Lien vers action concernéex</td>
</tr><tr>
<TD>Type Action</TD><TD><?php echo $str_doc_type;?></TD>
</tr><tr>
<TD>Etat</TD><td><?php echo $str_state;?><TD></TD>
</TR>
</table>
</TD>
</TR>
</table>
<?php echo $sp->input();?>
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
<A class="mtitle" id="<?php echo "doc".$aAttachedFile[$i]['d_id'];?>" href="<?php echo $aAttachedFile[$i]['link']?>"> <?php echo $aAttachedFile[$i]['d_filename'];?> </A>
<?php $rmDoc=sprintf("javascript:if ( confirm('Voulez-vous effacer le document %s')==true ) {remove_document('%s','%s','%s');}",
	$aAttachedFile[$i]['d_filename'],
	$_REQUEST['PHPSESSID'],
	dossier::id(),
	$aAttachedFile[$i]['d_id']);
?>
	
<a class="mtitle" id="<?php echo "ac".$aAttachedFile[$i]['d_id'];?>" href="<?php echo $rmDoc;?>">Effacer</a>
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