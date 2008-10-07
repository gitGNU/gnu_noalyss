<?php
/*
 *   This file is part of PhpCompta.
 *
 *   PhpCompta is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   PhpCompta is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with PhpCompta; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
/* $Revision$ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/*!\file
 * \brief functions concerning the config file config.inc.php. The domain is not set into the form for security issues
 */

require_once('class_widget.php');


/*!\brief
 *\param array with the index
 *  - ctmp temporary folder 
 *  - cpath path to postgresql
 *  - cuser postgresql user
 *  - cpasswd password of cuser
 *  - cport port for postgres
 *\return string with html code
 */
function config_file_form($p_array=null)
{
  if ( $p_array == null ) {
    /* default value */
    $ctmp='/tmp';
    $cpath='/usr/bin';
    $cuser='phpcompta';
    $cpasswd='dany';
    $cport=5432;
    $cdomain='';
  } else extract ($p_array);

  $text=new widget('text');
  $r='';
  $r.='<div style="position:float;float:left;text-align:right;line-height:1.8em;padding:0 0.9em 0 0">';

  $r.='R&eacute;pertoire temporaire : ';
  $text->title='Indiquez ici le r&eacute;pertoire o&ugrave; les documents temporaires peuvent &ecirc;tre sauv&eacute; exemple c:\\\\temp, /tmp';
  $r.=$text->IOValue('ctmp',$ctmp);
  $r.='<A href="#" title="'.$text->title.'" onclick="alert(\''.$text->title.'\')">(?)</a>';
  $r.='<br>';

  $r.='Chemin complet vers les executable de Postgresql : ';
  $text->title='Le chemin vers le repertoire contenant psql, pg_dump...';
  $r.=$text->IOValue('cpath',$cpath);
  $r.='<A href="#" title="'.$text->title.'" onclick="alert(\''.$text->title.'\')">(?)</a>';
  $r.='<br>';
  $text->title="Utilisateur de la base de donn&eacute;e postgresql";
  $r.='Utilisateur de la base de donn&eacute;e : ';
  $r.=$text->IOValue('cuser',$cuser);
  $r.='<A href="#" title="'.$text->title.'" onclick="alert(\''.$text->title.'\')">(?)</a>';
  $r.='<br>';
  $text->title="Mot de passe de l\' utilisateur";
  $r.='Mot de passe de l\'utilisateur : ';
  $r.=$text->IOValue('cpasswd',$cpasswd);
  $r.='<A href="#" title="'.$text->title.'" onclick="alert(\''.$text->title.'\')">(?)</a>';
  $r.='<br>';
  $text->title="Port ";
  $r.='Port de postgresql : ';
  $r.=$text->IOValue('cport',$cport);
  $r.='<A href="#" title="'.$text->title.'" onclick="alert(\''.$text->title.'\')">(?)</a>';
  $r.='<br>';
  $r.='</div>';
  return $r;
}
/*!\brief
 *\param
 *\return
 *\note
 *\see
 *\todo
 */
function config_file_create($p_array,$from_setup=1) {
  extract ($p_array);
  $add=($from_setup==1)?'..'.DIRECTORY_SEPARATOR:'';
  $hFile=  fopen($add.'..'.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'config.inc.php','w');
  fputs($hFile,'<?php ');
  fputs($hFile,"\n\r");
  fputs($hFile, 'date_default_timezone_set (\'Europe/Brussels\');');
  fputs($hFile,"\n\r");
  fputs($hFile, "\$_ENV['TMP']='".$ctmp."';");
  fputs($hFile,"\n\r");
  fputs($hFile, 'define("PG_PATH","'.$cpath'");');
  fputs($hFile,"\n\r");
  fputs($hFile, 'define("PG_RESTORE","'.$cpath.DIRECTORY_SEPARATOR.'pg_restore ");');
  fputs($hFile,"\n\r");
  fputs($hFile, 'define("PG_DUMP","'.$cpath.DIRECTORY_SEPARATOR.'pg_dump ");');
  fputs($hFile,"\n\r");
  fputs($hFile, 'define ("PSQL","'.$cpath.DIRECTORY_SEPARATOR.'psql");');
  fputs($hFile,"\n\r");
  fputs($hFile, 'define ("phpcompta_user","'.$cuser.'");');
  fputs($hFile,"\n\r");
  fputs($hFile, 'define ("phpcompta_password","'.$cpasswd.'");');
  fputs($hFile,"\n\r");
  fputs($hFile, 'define ("phpcompta_psql_port","'.$cport.'");');
  fputs($hFile,"\n\r");
  fputs($hFile, 'define ("domaine","");');
  fputs($hFile,"\n\r");
  fputs($hFile,'?>');
  fclose($hFile);
}
