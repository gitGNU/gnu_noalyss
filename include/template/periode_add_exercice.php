<fieldset><legend>Ajout d'un exercice</legend>
Ajout d'un exercice comptable de 12 périodes, commençant le 1 janvier et terminant le 31 décembre. Si vous souhaitez une 13ième période (le 31/12)
pour faire tous les écritures de fin d'exercice: amortissements, régulations de compte... Il vous suffit de raccourcir d'allonger la période de décembre jusqu'au 30/12
et d'ajouter une période d'un jour allant du 31/12 au 31/12
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