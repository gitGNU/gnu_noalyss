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
/* $Revision*/
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/*!\file
*\brief this file let you debug and test the different functionnalities, there are 2 important things to do
*  - first do not forget to create the authorized_debug file in the html folder
*  - secund the test must adapted to this page : if you do a post (or get) from a test, you won't get any result
* if the $_REQUEST[test_select] is not set, so set it . 
*/
include_once("ac_common.php");
require_once('class_database.php');
require_once ('class_dossier.php');
require_once('class_html_input.php');

if ( ! file_exists('authorized_debug') )
{
    echo "Pour pouvoir utiliser ce fichier vous devez creer un fichier nomme authorized_debug
    dans le repertoire html du server";
    exit();

}
html_page_start();
// Test the connection
echo __FILE__.":".__LINE__;
print_r($_REQUEST);
if ( ! isset($_REQUEST['gDossier']))
{
    echo "Vous avez oublie de specifier le gDossier ;)";
    echo "L'url aurait du etre test.php?gDossier=xx";
    exit();
}
$array=array(
           array(1,'Plan Analytic'),
           array(2,'Poste Analytic'),
           array(3,'Opération Analytic'),
           array(11,'Periode'),
           array(12,'verif.inc.php'),
           array(13,'Rapport(class_acc_report'),
           array(14,'Tva(class_acc_tav'),
           array(15,'Compute(class_acc_compute'),
           array(16,'widget'),
           array(17,'Ledger info(class_acc_ledger_info'),
           array(18,'Toddo list(class_todo'),
           array(19,'Payment (class_acc_payment'),
           array(20,'Form Acc_Ledger_Sold'),
           array(21,'Javascript') ,
           array(22,'DIV IPopup'),
           array(23,'Détection Navigateur')	,
           array(24,'Search Card'),
           array(25,'Search Ledger'),
           array(26,'Gettext'),
           array(27,'Card (class_fiche)'),
           array(28,'Extension'),
           array(29,'ITVA popup'),
           array(30,'Ereg and preg_match'),
           array(31,'IBOX'),
           array(32,'Acc_Operation & children'),
           array(33,'Ledger : reverse op'),
           array(34,'Reconciliation')
       );
$r='<form method="get">';
$r.='<select name="test_select" >';
foreach ($array as $value)
{
    $sel="";
    if ( isset($_REQUEST['test_select']) && $_REQUEST['test_select']==$value[0])
        $sel=' selected ';
    $r.='<option value="'.$value[0].'" '.$sel.'>'.$value[1];
}
$r.='</select>';
$r.= Dossier::hidden();
$r.='<input type="submit" value="Testons">';
$r.='</form>';

echo $r;

if ( ! isset($_REQUEST['test_select']))
    exit();

$act=$_REQUEST['test_select'];

switch ($act)
{
case 1:
        require_once('class_anc_plan.php');
    Anc_Plan::test_me();
    break;

case 2:
    require_once("class_anc_account.php");
    Anc_Account::test_me();
    break;
case 3:
    require_once ('class_anc_operation.php');
    Anc_Operation::test_me();
    break;
case 11:
    require_once ('class_periode.php');
    Periode::test_me();
    break;
case 13:
    require_once('class_acc_report.php');
    Acc_Report::test_me();
    require_once('class_acc_report_row.php');
    Acc_Report_Row::test_me();
    break;
case 14:
    require_once('class_acc_tva.php');
    Acc_Tva::test_me();
    break;
case 15:
    require('class_acc_compute.php');
    Acc_Compute::test_me();
    break;
case 16:
    echo HtmlInput::button_anchor("On y va",'login.php');
    require_once('constant.php');
    require_once('class_icard.php');
    ICard::test_me();
    break;
case 17:
    require_once('class_acc_ledger_info.php');
    Acc_Ledger_Info::test_me();
    break;
case 18:
    require_once('class_todo_list.php');
    require_once ('constant.php');
    echo JS_PROTOTYPE;
    Todo_List::test_me();
    echo '<script src="js/todo_list.js"></script>';
    echo '<form method="get">';
    echo dossier::hidden();
    echo 'title : <input type="text" id="p_title"><br>';
    echo 'desc : <input type="text" id="p_desc"><br>';
    echo 'date : <input type="text" id="p_date"><br>';
    echo '<input type="button" onClick="todo_list_add();return false;">';
    echo '</form>';
    break;
case 19:
    require_once('class_acc_payment.php');
    Acc_Payment::test_me();
    break;
case 20:
    require_once('class_acc_ledger_sold.php');
    Acc_Ledger_Sold::test_me();
    break;
case 21:
    test_javascript();
    break;
case 22:
    require_once('class_ipopup.php');
    IPopup::test_me();
    break;
case 23:
    $browser= $_SERVER['HTTP_USER_AGENT'];
    echo $browser;
    if ( strpos ($browser,'MSIE 6.0') != 0 )
    {
        echo "Vous ne pouvez pas utiliser Internet Explorer 6";
    }
    break;
case 24:
    require_once('class_icard.php');
    require_once ('constant.php');
    echo JS_PROTOTYPE;
    echo JS_AJAX_FICHE;
    echo JS_CARD;
    ICard::test_me();
    break;
case 25:
    require_once('class_acc_ledger.php');
    echo JS_PROTOTYPE;

    Acc_Ledger::test_me('search');
    break;
case 26:
    /* gettext */
    $dir="";
    if ( isset($_REQUEST['l']))
    {
        $dir=setlocale(LC_MESSAGES,$_REQUEST['l']);
    }
    else
    {
        $dir=setlocale(LC_MESSAGES,"fr_FR.utf8");
    }
    print_r("dir =[$dir]");
    if ( $dir == "")
    {
        echo '<h2 class="error">Désolé mais ce domaine n\'est pas supporté</h2>';
    }
    echo '<br>';
    bindtextdomain('messages','./lang');
    textdomain('messages');
    bind_textdomain_codeset('messages','UTF8');
    echo _('Bienvenu sur mon site');
    echo _('Essayer de vous y amuser');
    echo '<form method="GET">';
    echo dossier::hidden();
    echo HtmlInput::hidden('test_select',$act);
    echo _('Choississez votre langue');
    $aLang=array(array(_('Français'),'fr_FR.utf8'),
                 array(_('Anglais'),'en_US.utf8'),
                 array(_('Néerlandais'),'nl_NL.utf8'),
                );
    echo '<select name="l" id="l">';
    for ($i =0;$i < count($aLang);$i++)
    {
        printf('<option value="%s">%s</option>',
               $aLang[$i][1],$aLang[$i][0]);

    }
    echo '</select>';
    echo '<input type="submit" value="'._('Change de langue').'">';
    echo '</form>';
    echo "Div 5/10 = ";
    echo 5/10;
    echo '<br>';
    $test_num=10000/3;
    echo 'Div num '.$test_num;
    echo '<br>';
    echo _('Fin de programme');
    break;
case 27:
    require_once('class_fiche.php');
    Fiche::test_me();
    break;
case 28:
    require_once('class_extension.php');
    Extension::test_me();
    break;
case 29:
    require_once('class_itva_popup.php');
    echo JS_PROTOTYPE;
    echo JS_AJAX_FICHE;
    echo JS_CARD;
    echo js_include('scripts.js');
    echo js_include('scriptaculous.js');
    echo js_include('effects.js');
    echo js_include("controls.js");
    echo js_include('dragdrop.js');
    ITva_Popup::test_me();
    break;
case 30:
    // Test on ereg and preg_match
    $aString=array('[7%]','[67%]','[7%]-[6%]+[67%]','[7%]-[6%]','[55%] FROM=00.000');
    echo '<h2> EREG </h2>';
    foreach ($aString as $str)
    {
        echo $str." ====>";
        @ereg ("^(\\$[a-zA-Z]*[0-9]*=){0,1}((\[{0,1}[0-9]+\.*[0-9]*%{0,1}\]{0,1})+*([+-\*/]){0,1} *(\[{0,1}[0-9]+\.*[0-9]*%{0,1}\]{0,1})*)*(([+-\*/])*\\$([a-zA-Z])+[0-9]*([+-\*/])*)* *( *FROM=[0-9][0-0].20[0-9][0-9]){0,1}$",$str,$p_result);
        var_dump($p_result);
    }
    echo '<h2> EREG </h2>';
    foreach ($aString as $str)
    {
        echo $str." ====>";
        /// $a=70%+60%+... FROM=00.000
        preg_match("/^(\\$[a-zA-Z]+[0-9]*=){0,1}(\[{0,1}[0-9]+\.*[0-9]*%{0,1}\]{0,1})(+|-|\*|\/)*/",$str,$p_result);
        //    preg_match ("/^(\\$[a-zA-Z]+[0-9]*=){0,1}((\[{0,1}[0-9]+\.*[0-9]*%{0,1}\]{0,1})+ *([+-\*/])* *(\[{0,1}[0-9]+\.*[0-9]*%{0,1}\]{0,1})*)*(([+-\*/])*\\$([a-zA-Z])+[0-9]*([+-\*/])*)* *( *FROM=[0-9][0-0].20[0-9][0-9]){0,1}\$/",$str,$p_result);
        var_dump($p_result);
    }
case 31:
    require_once('class_ibox.php');
    IBox::test_me();
    break;
case 32:
    require_once('class_acc_operation.php');
    Acc_Operation::test_me();
    break;
case 33:
    require_once('class_acc_ledger.php');
    Acc_Ledger::test_me('reverse');
    break;
case 34:
    require_once('class_acc_reconciliation.php');
    Acc_Reconciliation::test_me();
    break;
}


function test_javascript()
{
  echo <<<JS
    <script language="Javascript" src="js/scripts.js"></script>
    <script language="javascript">
    function ajax_post()
    {
      a=new Ajax();
      a.createAjax('test.php','a=1&b=2');
      a.postPage();
      a.onSuccess(show);
    }
  function ajax_get()
  {
    a=new Ajax();
    a.createAjax('test.php','a=1&b=2');
    a.getPage();
    a.onSuccess(show);
  }
  function ajax_get_obj()
  {
    a=new Ajax();
    var param={"reponse":"oui"
	       ,"valeur":"pas des masses"
	       ,"gDossier":"13"
    };
    str_param=encodeJSON(param);
    a.createAjax('test.php',str_param);
    a.getPage();
    a.onSuccess(show);
  }
  function ajax_post_obj()
  {
    var a=new Ajax();
    var param={"reponse":"oui"
	       ,"valeur":"pas des masses"
	       ,"gDossier":"13"
    };
    str_param=encodeJSON(param);
    a.createAjax('test.php',str_param);
    a.postPage();
  }
  function show(p)
  {
    alert('return ok');
  }

  </script>
      <h1> Ajax get - post </h1>
      <div>
      <input type="button" onclick="ajax_post()" value="Ajax POST">
      <input type="button" onclick="ajax_get()" value="Ajax GET">
      </div>
      <h1> encodeParameter</h1>
      <div>
      <p>
      obj =
      { "a":"1000"
	,"b":"seconde valeur"
      }
  </p>
      <p>
      <input type="button" onclick='x=120;obj =  { "a":"1000","b":"seconde valeur","c":x} ;a=encodeJSON(obj);alert(a);' value="encodeParameter">
      <input type="button" onclick="ajax_get_obj()" value="Ajax GET + object">
      <input type="button" onclick="ajax_post_obj()" value="Ajax POST + object">
      </p>
      </div>

      <h1>set_value</h1>
      <div>
      <p id="p_id">p_id</p>
      <span id="p_span">p_span</span>
      <textarea id="p_txtarea">
      p_txtarea
      </textarea>
      <input type="text" value="toto" id="p_input_text">
      <input type="button" onclick="set_all()" value="set_all()">

      <script>
      function set_all()
      {
	set_value('p_id',"Ceci est un paragraphe");
	set_value('p_span',"Ceci est un SPAN");
	set_value('p_txtarea',"Ceci est un très grand textarea vraiemnt");
	set_value('p_input_text','normalement nous sommes dans l\'input');
      }


  </script>
      </div>
JS;
}
