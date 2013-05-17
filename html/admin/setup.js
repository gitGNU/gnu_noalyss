/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

content[200]="Indiquez ici le récuterpertoire où les documents temporaires peuvent être sauvés exemple c:/temp, /tmp"
content[201]="Désactiver le changement de langue (requis pour MacOSX)";
content[202]="Le chemin vers le repertoire contenant psql, pg_dump...";
content[203]="Utilisateur de la base de donnée postgresql";
content[204]="Mot de passe de l'utilisateur ";
content[205]="Port de postgresql";
content[206]="En version mono dossier, le nom de la base de données doit être mentionnée";


function show_dbname(obj) {
	try {
		if (obj.value === '1')
		{
			this.document.getElementById('div_db').style.visibility= 'hidden';
		}
		else {
			this.document.getElementById('div_db').style.visibility= 'visible';
		}
	} catch (e) {
		alert(e.getMessage);
	}
}
