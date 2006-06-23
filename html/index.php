
<?
/*! \file
 * \brief default page where user access
 */
/*! \mainpage PhpCompta
 *
 * \section intro_sec Introduction
 *
 * Cette partie contient de la documentation pour les développeurs.
 *
 * \section convention_code Convention de codage
 * <p>
 * Quelques conventions de codage pour avoir un code plus ou moins
 * homogène
 * <ol>
 * <li>Tant que ce peut réutiliser ce qui existe déjà, </li>
 * <li>Documenter avec les tags doxygen votre nouveau code,</li>
 * <li>Les noms de fichiers sont *.inc.php pour les fichiers à include</li>
 * <li>Les noms de fichier sont  class_*.php pour les fichiers contenant des classes.</li>
 * <li>Faire de la doc c'est bien </li>
 * </ol>
 * 
 * </p>
 *
 */

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
</style>
<script src="scripts.js" type="text/javascript"></script>
</head>
<BODY onLoad="SetFocus(\'login\',0)">
Version  developpement 1.2.2 patch 5
<div class="remark">
 <p class="gras">Il est conseill&eacute; de ne PAS utiliser Internet Explorer.
 </p><p>
 Vous pouvez utiliser un autre browser: firefox, mozilla, netscape, Konqueror, opera...
 </p>
 <p>
 <a href="https://savannah.nongnu.org/projects/phpcompta">
 Si vous avez envie de nous rejoindre, vous êtes les bienvenus
 </a>	.
 </p>
</div>

<center>
	<IMG SRC="image/logo7.jpg" alt="Logo">
<BR>

<form action="login.php" method="post" name="loginform">
<TABLE><TR><TD>
<TABLE  BORDER=0 CELLSPACING=0>
<TR>
<TD class="cell">Login</TD>
<TD><input type="text" name="p_user" tabindex="1"></TD>
</TR>
<TR>
<TD> Password</TD>
<TD><INPUT TYPE="PASSWORD" NAME="p_pass" tabindex="2"></TD>
</TR>
<TR>
<TD COLSPAN=2 ALIGN=CENTER><INPUT TYPE=SUBMIT  NAME="login" value="Log in"></TD>
</TR>
</table>
</TD></TR></TABLE>
</form> 
</Center>
</body>
</html>';

?>
