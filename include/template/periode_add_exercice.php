<fieldset><legend>Ajout d'un exercice</legend>
<ul>
<li>
Exercice en 12 périodes : Ajout d'un exercice comptable de 12 périodes, commençant le 1 janvier et terminant le 31 décembre. </li>
<li>   Exercice en 13 périodes: Ajout d'une période d'un jour le 31/12. Cette période est utilisée 
pour faire tous les écritures de fin d'exercice: amortissements, régulations de compte... Avec une 13ième période, cela simplifie les prévisions, les rapports...</li>
</ul>

<form method="post" onsubmit="return confirm('Confirmez vous l\'ajout d\'un exercice comptable ?')">
<?
echo HtmlInput::hidden("ac",$_REQUEST['ac']);
echo $nb_exercice->input();
echo HtmlInput::hidden("jrn_def_id","0");
echo Dossier::hidden();
echo HtmlInput::submit("add_exercice","Ajout d'un exercice comptable");
?>

</form>
</fieldset>