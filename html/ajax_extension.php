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
 * \brief this file answer to ajax
 * parameters :
 * - action =de  details extension
 *       parameters : dossier, phpsessid and ex_id
 *
 *
 */
require_once('class_dossier.php');
require_once('constant.security.php');
require_once('class_extension.php');
require_once('function_javascript.php');
require_once('ac_common.php');
require_once('class_user.php');
require_once('class_iselect.php');
require_once ('class_user.php');

$cn=new Database(dossier::id());
/* check user */
$user=new User($cn);
set_language();
$user->check(true);$user->check_dossier(dossier::id(),true);
if ($user->check_action(EXTENSION)==0 ) exit;
$var=array('PHPSESSID','gDossier','action');
$cont=0;
/*  check if mandatory parameters are given */
foreach ($var as $v) {
  if ( ! isset ($_REQUEST [$v] ) ) {
    echo "$v is not set ";
    $cont=1;
  }
}
extract ($_REQUEST);
$r="";$extra="";$ctl="";
switch($action) {
  /*----------------------------------------------------------------------
   *detail of an extension 
   *
   *----------------------------------------------------------------------*/
 case 'de':
   $ext=new Extension($cn);
   $ext->set_parameter("id",$ex_id);
   $ext->load();
   $str_hidden=HtmlInput::hidden('ex_id',$ex_id);
   $name=new IText('name',$ext->get_parameter("name"));
   $str_name=$name->input();

   $code=new IText('code',$ext->get_parameter("code"));
   $str_code=$code->input();

   $desc=new IText('desc',$ext->get_parameter("desc"));
   $str_desc=$desc->input();

   $file=new IText('file',$ext->get_parameter("filepath"));
   $str_file=$file->input();

   $enable=new ISelect('enable');
   $array=array(
		array ('label'=>_('Oui'),'value'=>'Y'),
		array('label'=>_('Non'),'value'=>'N')
		);
   $enable->value=$array;
   $enable->selected=$ext->get_parameter('enable');
   $str_enable=$enable->input();
   $r.='<div style="overflow:auto;">';
   $r.='<form  id="extde" onsubmit="extension_save(this)">';
   $r.=dossier::hidden().HtmlInput::phpsessid();
   /* property of the extension */
   ob_start();
   require_once('template/extension-detail.php');
   $r.=ob_get_contents();
   ob_clean();
   /* security */
   $ans=array(array('value'=>'Y','label'=>_('Accès')),
	      array('value'=>'N','label'=>_('Interdit'))
	      );
   $array=User::get_list(dossier::id());
   for ($i=0;$i<sizeof($array);$i++){
     $i_select=new ISelect("is_".$array[$i]['use_login']);
     $i_select->value=$ans;
     $i_select->selected=($ext->can_request($array[$i]['use_login'])==1)?'Y':'N';
     $array[$i]['access']=$i_select->input();
   }
   require_once('template/extension-sec.php');
   $r.=ob_get_contents();
   ob_clean();

   /* submit */
   $r.=HtmlInput::submit('ex_save',_('Sauve'));
   $r.='</form>';
   $r.='</div>';
   $ctl='dtext';
   break;
   /* ---------------------------------------------------------------------- 
    * new  extension
    *----------------------------------------------------------------------*/
 case 'ne':
   $ext=new Extension($cn);
   $ext->set_parameter("id",0);
   $str_hidden=HtmlInput::hidden('ex_id',0);

   $name=new IText('name',"");
   $str_name=$name->input();

   $code=new IText('code',"");
   $str_code=$code->input();

   $desc=new IText('desc',"");
   $str_desc=$desc->input();

   $file=new IText('file',"");
   $str_file=$file->input();

   $enable=new ISelect('enable');
   $array=array(
		array ('label'=>_('Oui'),'value'=>'Y'),
		array('label'=>_('Non'),'value'=>'N')
		);
   $enable->value=$array;
   $enable->selected='Y';
   $str_enable=$enable->input();
   $r.='<div style="overflow:auto">';
   $r.='<form id="extde" onsubmit="extension_save(this);">';
   $r.=dossier::hidden().HtmlInput::phpsessid();
   ob_start();
   require_once('template/extension-detail.php');
   $r.=ob_get_contents();
   ob_clean();

   /* security */
   $ans=array(array('value'=>'Y','label'=>_('Accès')),
	      array('value'=>'N','label'=>_('Interdit'))
	      );
   $array=User::get_list(dossier::id());
   for ($i=0;$i<sizeof($array);$i++){
     $i_select=new ISelect("is_".$array[$i]['use_login']);
     $i_select->value=$ans;
     $i_select->selected=($ext->can_request($array[$i]['use_login'])==1)?'Y':'N';
     $array[$i]['access']=$i_select->input();
   }
   require_once('template/extension-sec.php');
   $r.=ob_get_contents();
   ob_clean();


   $r.=HtmlInput::submit('ex_save',_('Sauve'));
   $r.='</form>';
   $r.='</div>';
   $ctl='dtext';
   break;
   /*----------------------------------------------------------------------
    * save the extension
    *
    *----------------------------------------------------------------------*/
case 'se':
  $ext=new Extension($cn);
  $ext->fromArray($_POST);
  try {
    $ext->save();
    $ext->save_security($_POST);
  }catch (Exception $e) {
    $r=alert(j( $e->getMessage()),true);
  }
  break;
case 're':
  $ext=new Extension($cn);
  $ext->set_parameter('id',$ex_id);
  $ext->delete();
  break;
}

$html=escape_xml($r);

header('Content-type: text/xml; charset=UTF-8');
echo <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<data>
<ctl>$ctl</ctl>
<code>$html</code>
<extra>$extra</extra>
</data>
EOF;
