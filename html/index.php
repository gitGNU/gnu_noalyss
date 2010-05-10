<?php
/*! \file
 * \brief default page where user access
 */
/*! \mainpage PhpCompta
 * Documentation
 * - \subpage Francais
 * - \subpage English
 *
 *\page Francais
 * \section intro_sec Introduction
 *
 * Cette partie contient de la documentation pour les développeurs.
 *
 * \section convention_code Convention de codage
 * <p>
 * Quelques conventions de codage pour avoir un code plus ou moins
 * homogène
 * <ol>
 * <li>Tant que possible réutiliser ce qui existe déjà, </li>
 * <li>Améliorer ce qui existe déjà et vérifier que cela fonctionne toujours</li>
 * <li>Documenter avec les tags doxygen votre nouveau code,</li>
 * <li>Dans le répertoire include: Les noms de fichiers sont *.inc.php pour les fichiers à éxécuter</li>
 * <li>Dans le répertoire include: Les noms de fichiers sont *.php pour les fichiers contenant des fonctions uniquement</li>
 * <li>Dans le répertoire include: Les noms de fichier sont
 * class_*.php pour les fichiers contenant des classes.</li>
 * <li>Dans le répertoire include/template: les fichiers de
 * présentation HTML </li>
 * <li>Utiliser sql/upgrade.sql comme fichier temporaire pour modifier la base de données, en général
 *  ce fichier deviendra l'un des patch </li>
 * <li>Faire de la doc </li>
 * </ol>
 * 
 * </p>
 * \section conseil Conseils
 * <p>
 * Utiliser cette documentation, elle est générée automatiquement avec Doxygen,
 * <ul>
 * <li>Related contient tous les \\todo</li>
 * <li>Global -> function pour lire toute la doc sur les fonctions</li>
 * <li>Regarder dans dossier1.html et account_repository.html  pour la doc des base de données
 *</ul>
 *  et il ne faut connaître que ces tags
 * <ul>
 * <li> \\file en début de fichier</li>
 * <li> \\todo ajouter un todo </li>
 * <li> \\enum pour commenter une variable</li>
 * <li> \\param pour commenter le paramètre d'une fonction</li>
 * <li> \\brief Commentaire du fichier, de la fonction ou de la classe</li>
 * <li> \\note des notes, des exemples</li>
 * <li> \\throw or exception is a function can throw an exception
 * <li> \\par to create a new paragraph
 * <li> \\return ce que la fonction retourne</li>
 * <li> \\code et \\endcode si on veut donner un morceau de code comme documentation</li>
 * <li> \\verbatim et \\endverbatim si on veut donner une description d'un tableau,  comme documentation</li>
 *<li>  \\see xxxx Ajoute un lien vers un fichier, une fonction ou une classe </li>
 * </ul>
 *----------------------------------------------------------------------
 *\page English
 * \section intro_sec Introduction
 *
 * This parts contains documentation for developpers
 *
 * \section convention_code Coding convention
 * <p>
 * Some coding conventions to have a homogene code 
 * <ol>
 * <li>Reuse the existing code , </li>
 * <li>Improve and check that the function is still working</li>
 * <li>Make documentation thanks doxygen tag</li>
 * <li>In the folder include: filenames ending by  *.inc.php will be executer after being included</li>
 * <li>Dans le répertoire include: Les noms de fichiers sont *.php pour les fichiers contenant des fonctions uniquement</li>
 * <li>In the folder include: filenames end by  *.php if they contains only function</li>
 * <li>In the folder include: filenames starting with 
 * class_*.php if it is related to a class.</li>
 * <li>In the folder include/template: files for the HTML presentation
 * </li>
 * <li>Use sql/upgrade.sql as temporary file to modify the database,this file will be the base for a SQL patch
 *  </li>
 * <li>Write documentation </li>
 * </ol>
 * 
 * </p>
 * \section advice Advices
 * <p>
 * Use this document, it is generated automatically by doxygen, check the documentation your made, read it first this
 * documentation before making change
 * <ul>
 * <li>Related contains all the \\todo</li>
 * <li>Global -> all the functions</li>
 * <li>check into mod1.html and account_repository.html for the database design
 *</ul>
 *  You need to know only this tags
 * <ul>
 * <li> \\file in the beginning of a file</li>
 * <li> \\todo add a todo </li>
 * <li> \\enum comment a variable</li>
 * <li> \\param about the parameter of a function</li>
 * <li> \\brief Documentation of the file, function or class</li>
 * <li> \\note note exemple</li>
 * <li> \\throw or exception is a function can throw an exception
 * <li> \\par to create a new paragraph
 * <li> \\return what the function returns</li>
 * <li> \\code and \\endcode code snippet given as example</li>
 * <li> \\verbatim and \\endverbatim if we want to keep the formatting without transformation</li>
 *<li>  \\see xxxx create a link to the file, function or object xxxx </li>
 * </ul>
 */

if ( ! file_exists('..'.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'config.inc.php')) {
  $p_string='admin/setup.php';
  echo '<HTML><head><META HTTP-EQUIV="REFRESH" content="0;url='.$p_string.'"></head><body> Connecting... </body></html>';
  exit(0);
}

echo '<!DOCTYPE HTML PUBLIC "-//W3C/DTD HTML 3.2 FINAL//EN">

<HTML>
<head>
<TITLE> PhpCompta </TITLE>
<style>
BODY {
  background-color:white;
  font-size:12px;
  font-family:sans-serif;
  color:blue;
}
.remark {
	border: solid black 1px;
	font-family:sans-serif;
	font-size: 9px;
	color:blue;
	width:200px;
	padding:3px;
}
.gras {
  font-size:12px;
  font-family:sans-serif;
  color:red;

}

.button {
	font-size:10;
	color:black;
	text-decoration:none;
	font-family: helvetica,arial,sans-serif;
	background-color:#DFDFDF;
/*	border-style:  outset ;*/
	border-color:  blue ;
	border-width:1;
	padding:2 4 2 4;
	cursor:pointer;
	margin:1 2 1 2;
	-moz-border-radius:2 2;
	
}
.button:hover {
	cursor:pointer;
	background-color:white;
/*	border-style:  inset ;*/
	font-color:blue;
	margin:1 2 1 2;
	}

</style>
<script src="js/scripts.js" type="text/javascript"></script>
</head>
<BODY onLoad="SetFocus(\'p_user\')">';
$my_domain="";
require ('config.inc.php');
if ( strlen(domaine) > 0 ) {
  $my_domain="Domaine : ".domaine;
}

echo '
<span style="background-color:#879ed4;color:white;padding-left:4px;padding-right:4px;">
version  5.1.build SVNINFO - '.$my_domain.'
</span>
<BR>
<BR>
<BR>
<BR>
<center>
	<IMG SRC="image/logo7.jpg" alt="Logo">
<BR>
<BR>
<BR>

<form action="login.php" method="post" name="loginform">
<TABLE><TR><TD>
<TABLE  BORDER=0 CELLSPACING=0>
<TR>
<TD class="cell">utilisateur</TD>
<TD><input type="text" name="p_user" tabindex="1"></TD>
</TR>
<TR>
<TD> mot de passe </TD>
<TD><INPUT TYPE="PASSWORD" NAME="p_pass" tabindex="2"></TD>
</TR>
<TR>
<TD COLSPAN=2 ALIGN=CENTER>
<INPUT TYPE="SUBMIT"  class="button" NAME="login" value="Se connecter">
</TD>
</TR>
</table>
</TD></TR></TABLE>
</form> 
</Center>
<div  style="position:fixed;bottom:5em;border:1px solid red;text-align:right;width:20em;">
<span style="display:block">Pour une meilleure expérience web, nous vous conseillons <a href="http://www.mozilla.com/fr/">firefox</a></span>
<span style="display:block">For a better web experience, we recommend you <a href="http://www.mozilla.com/en/">firefox</a></span>
</body>
</html>';

?>
