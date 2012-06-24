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

require_once("class_itext.php");
function is_unix()
{
    $inc_path=get_include_path();

    if ( strpos($inc_path,";") != 0 )
    {
        $os=0;			/* $os is 0 for windoz */
    }
    else
    {
        $os=1;			/* $os is 1 for unix */
    }
    return $os;
}


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
    if ( $p_array == null )
    {
        $os=is_unix();
        /* default value */
        $ctmp=($os==1)?'/tmp':'c:\tmp';
        $cpath=($os==1)?'/usr/bin':'c:\phpcompta\postgresql\bin';
        $cuser='phpcompta';
        $cpasswd='dany';
        $cport=5432;
        $cdomain='';
        $clocale=1;
    }
    else extract ($p_array);

    $text=new IText();
    $text->size=25;
    $r='';
    $r.='<div style="position:float;float:left;text-align:right;line-height:1.8em;padding:0 0.9em 0 0">';

    $r.='R&eacute;pertoire temporaire : ';
    $text->title='Indiquez ici le r&eacute;pertoire o&ugrave; les documents temporaires peuvent &ecirc;tre sauv&eacute; exemple c:\\\\temp, /tmp';
    $r.=$text->input('ctmp',$ctmp);
    $r.='<A href="#" title="'.$text->title.'" onclick="alert(\''.$text->title.'\')">(?)</a>';
    $r.='<br>';

    $r.='D&eacute;sactivation changement de langue: 1 activé, 0 désactivé ';
    $text->title='D&eacute;sactiver le changement de langue (requis pour MacOSX';
    $r.=$text->input('clocale',$clocale);
    $r.='<A href="#" title="'.$text->title.'" onclick="alert(\''.$text->title.'\')">(?)</a>';
    $r.='<br>';

    $r.='Chemin complet vers les executable de Postgresql : ';
    $text->title='Le chemin vers le repertoire contenant psql, pg_dump...';
    $r.=$text->input('cpath',$cpath);
    $r.='<A href="#" title="'.$text->title.'" onclick="alert(\''.$text->title.'\')">(?)</a>';
    $r.='<br>';
    $text->title="Utilisateur de la base de donn&eacute;e postgresql";
    $r.='Utilisateur de la base de donn&eacute;e : ';
    $r.=$text->input('cuser',$cuser);
    $r.='<A href="#" title="'.$text->title.'" onclick="alert(\''.$text->title.'\')">(?)</a>';
    $r.='<br>';
    $text->title="Mot de passe de l\' utilisateur";
    $r.='Mot de passe de l\'utilisateur : ';
    $r.=$text->input('cpasswd',$cpasswd);
    $r.='<A href="#" title="'.$text->title.'" onclick="alert(\''.$text->title.'\')">(?)</a>';
    $r.='<br>';
    $text->title="Port ";
    $r.='Port de postgresql : ';
    $r.=$text->input('cport',$cport);
    $r.='<A href="#" title="'.$text->title.'" onclick="alert(\''.$text->title.'\')">(?)</a>';
    $r.='<br>';
    $r.='</div>';
    return $r;
}
/*!\brief create the config file
 */
function config_file_create($p_array,$from_setup=1,$os=1)
{
    extract ($p_array);
    $add=($from_setup==1)?'..'.DIRECTORY_SEPARATOR:'';
    $hFile=  fopen($add.'..'.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'config.inc.php','w');
    fputs($hFile,'<?php ');
    fputs($hFile,"\r\n");
    fputs($hFile, 'date_default_timezone_set (\'Europe/Brussels\');');
    fputs($hFile,"\r\n");
    fputs($hFile, "\$_ENV['TMP']='".$ctmp."';");
    fputs($hFile,"\r\n");
    fputs($hFile, 'define("PG_PATH","'.$cpath.'");');
    fputs($hFile,"\r\n");
    if ( $os == 1 )
    {
        fputs($hFile, 'define("PG_RESTORE","'.$cpath.DIRECTORY_SEPARATOR.'pg_restore ");');
        fputs($hFile,"\r\n");
        fputs($hFile, 'define("PG_DUMP","'.$cpath.DIRECTORY_SEPARATOR.'pg_dump ");');
        fputs($hFile,"\r\n");
        fputs($hFile, 'define ("PSQL","'.$cpath.DIRECTORY_SEPARATOR.'psql");');
    }
    else
    {
        fputs($hFile, 'define("PG_RESTORE","pg_restore.exe");');
        fputs($hFile,"\r\n");
        fputs($hFile, 'define("PG_DUMP","pg_dump.exe");');
        fputs($hFile,"\r\n");
        fputs($hFile, 'define ("PSQL","psql.exe");');
    }
    fputs($hFile,"\r\n");
    fputs($hFile, 'define ("phpcompta_user","'.$cuser.'");');
    fputs($hFile,"\r\n");
    fputs($hFile, 'define ("phpcompta_password","'.$cpasswd.'");');
    fputs($hFile,"\r\n");
    fputs($hFile, 'define ("phpcompta_psql_port","'.$cport.'");');
    fputs($hFile,"\r\n");
    fputs($hFile, 'define ("phpcompta_psql_host","127.0.0.1");');
    fputs($hFile,"\r\n");

    fputs($hFile, 'define ("locale",'.$clocale.');');
    fputs($hFile,"\r\n");

    fputs($hFile, 'define ("domaine","");');
    fputs($hFile,"\r\n");
    fputs($hFile,'?>');
    fclose($hFile);
}
