/*
 *   This file is part of WCOMPTA.
 *
 *   WCOMPTA is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   WCOMPTA is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with WCOMPTA; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
<!-- ONE STEP TO INSTALL BASIC CALCULATOR:

   1.  Paste the coding into the BODY of your HTML document  -->

<!--  STEP ONE: Copy this code into the BODY of your HTML document  -->
		  
<BODY>

<CENTER>
<FORM NAME="Calc">

<!-- This script and many more are available free online at -->
<!-- The JavaScript Source!! http://javascript.internet.com -->

<TABLE BORDER=4>
<TR>
<TD>
<INPUT TYPE="text"   NAME="Input" Size="16">
<br>
</TD>
</TR>
<TR>
<TD>
<INPUT TYPE="button" NAME="one"   VALUE="  1  " OnClick="Calc.Input.value += '1'">
<INPUT TYPE="button" NAME="two"   VALUE="  2  " OnCLick="Calc.Input.value += '2'">
<INPUT TYPE="button" NAME="three" VALUE="  3  " OnClick="Calc.Input.value += '3'">
<INPUT TYPE="button" NAME="plus"  VALUE="  +  " OnClick="Calc.Input.value += ' + '">
<br>
<INPUT TYPE="button" NAME="four"  VALUE="  4  " OnClick="Calc.Input.value += '4'">
<INPUT TYPE="button" NAME="five"  VALUE="  5  " OnCLick="Calc.Input.value += '5'">
<INPUT TYPE="button" NAME="six"   VALUE="  6  " OnClick="Calc.Input.value += '6'">
<INPUT TYPE="button" NAME="minus" VALUE="  -  " OnClick="Calc.Input.value += ' - '">
<br>
<INPUT TYPE="button" NAME="seven" VALUE="  7  " OnClick="Calc.Input.value += '7'">
<INPUT TYPE="button" NAME="eight" VALUE="  8  " OnCLick="Calc.Input.value += '8'">
<INPUT TYPE="button" NAME="nine"  VALUE="  9  " OnClick="Calc.Input.value += '9'">
<INPUT TYPE="button" NAME="times" VALUE="  x  " OnClick="Calc.Input.value += ' * '">
<br>
<INPUT TYPE="button" NAME="clear" VALUE="  c  " OnClick="Calc.Input.value = ''">
<INPUT TYPE="button" NAME="zero"  VALUE="  0  " OnClick="Calc.Input.value += '0'">
<INPUT TYPE="button" NAME="DoIt"  VALUE="  =  " OnClick="Calc.Input.value = eval(Calc.Input.value)">
<INPUT TYPE="button" NAME="div"   VALUE="  /  " OnClick="Calc.Input.value += ' / '">
<br>
</TD>
</TR>
</TABLE>
</FORM>
</CENTER>

<p><center>
<font face="arial, helvetica" size="-2">Free JavaScripts provided<br>
by <a href="http://javascriptsource.com">The JavaScript Source</a></font>
</center><p>

<!-- Script Size:  1.89 KB  -->
