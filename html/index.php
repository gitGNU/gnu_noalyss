
<?
echo '<!DOCTYPE HTML PUBLIC "-//W3C/DTD HTML 3.2 FINAL//EN">
<HTML>
<head>
<TITLE> Gnu Accountancy</TITLE>
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
</style> 
</head>
<BODY onLoad="document.loginform.p_user.focus()">
<div class="remark">
 <p>A cause de probl�me en javascript, Internet Explorer ne peut absolument 
 pas �tre utilis� avec ce produit.</p>
 <p>Vous devez utilisez un autre browser: firefox, mozilla, netscape, Konqueror, opera...
 </p>
 <p>
 Si vous pensez pouvoir nous aidez � r�gler ce probl�me de compatibilit� 
 <a href="https://savannah.nongnu.org/projects/phpcompta">rejoignez-nous</a>	.
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
<TD COLSPAN=2 ALIGN=CENTER><INPUT TYPE=SUBMIT value="Log in"></TD>
</TR>
</table>
</TD></TR></TABLE>
</form> 
</Center>
</body>
</html>';

?>
