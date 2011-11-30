<fieldset><legend>Ajout d'un exercice</legend>
Ajout d'un exercice comptable de 12 périodes, commençant le 1 janvier et terminant le 31 décembre
<form method="post" onsubmit="return confirm('Confirmez vous l\'ajout d\'un exercice comptable ?')">
<?
echo HtmlInput::hidden("ac",$_REQUEST['ac']);
echo HtmlInput::hidden("jrn_def_id","0");
echo HtmlInput::hidden("jrn_def_id","0");
echo Dossier::hidden();
echo HtmlInput::submit("add_exercice","Ajout d'un exercice comptable");
?>

</form>
</fieldset>