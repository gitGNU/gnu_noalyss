<?
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
// Auteur Dany De Bontridder ddebontridder@yahoo.fr
include_once ("ac_common.php");



/* $Revision$ */

include ("postgres.php");
include_once("debug.php");

if (  isset ($_POST["p_user"] ) ) {
  echo_debug("user is set");
  $g_user=$_POST["p_user"];
  $g_pass=$_POST["p_pass"];
  session_register("g_user");
  session_register("g_pass");
  $cn=pg_connect("dbname=account_repository user='webcompta' ");

  // Verif if User and Pass match DB
  // if no, then redirect to the login page
   CheckUser();
    $g_UserProperty=GetUserProperty($cn,$g_user);
    session_register("g_UserProperty");

    html_page_start($g_UserProperty['use_theme']);

} else
{
  if ( strlen($g_user) != 0 ) {
    echo_debug("user is not null");
  html_page_start($g_UserProperty['use_theme']);    
  } else
    {
      html_page_start("classic");
    }
}

if ( isset ($db_page) ) {
  echo_debug("db_page is set ".$db_page);
} else 
{ 
  echo_debug("db_page is NOT set");
  $db_page=0;

}


echo_debug("USER=$g_user - ");
echo_debug("PAGE = $db_page");

echo_debug("theme =".$g_UserProperty['use_theme']);

include("top_menu_compta.php");
// Show the first menu
ShowMenu($g_UserProperty['use_admin']);
if ( $g_UserProperty['use_admin'] != 0 || $g_user=='webcompta' ) {
  /* Administrator */
                $l_MaxLine=0;
		$r_database=ShowDossier('all',$db_page,$db_page+15,&$l_MaxLine);
		$action="Encoder";
		$priv="Administrateur";
   } else {
  /* User */
                $l_MaxLine=0;
                $r_database=ShowDossier('lim',$db_page,$db_page+15,&$l_MaxLine);
		$priv="Utilisateur";
   }

echo '<H1 class="title"> Dossier </H1>';
echo "<TABLE>";

if ( $r_database != null ) {
  $row=0;
  foreach ( $r_database as $l_db ) {
    $row++;
    if ( $l_db['priv_priv'] == "W") {
      $action="Encoder";
    } else {
      $action="Lire";
    }
    
    if ( $row %2 == 0 ) {
      $tr='<TR class="odd">';
      $td='<TD class="odd">';
    }    else {
      $tr='<TR class="even">';
      $td='<TD class="even">';
    }
    printf("%s $td <B> %s</B></TD> $td %s</TD> <td style=\"border-width:1pt;border-style:solid\";> %s</TD> <TD class=\"mtitle\"> <A class=\"mtitle\" HREF=\"compta.php?dos=%d\">%s</A></TD></TR>",
	   $tr,
	   $l_db['dos_name'],
	   $l_db['dos_description'],
	   $priv,
	   $l_db['dos_id'],
	   $action);

  
  }

  echo "</TABLE>";
  $db_debut=15+$db_page;
  if ($db_debut < $l_MaxLine) {
  printf ("<A HREF=\"login.php?db_page=%d\">Suivant</A>",$db_debut);
  }
  if ( $db_page !=0 ) {
    $db_debut=$db_page-15;
    printf ("<A HREF=\"login.php?db_page=%d\">Précédent</A>",$db_debut);
  }

}
?>


<?

html_page_stop();
?>
