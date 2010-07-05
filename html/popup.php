<?

require_once('ac_common.php');
require_once('function_javascript.php');
require_once('class_html_input.php');

html_page_start($_SESSION['g_theme']);
echo HtmlInput::hidden('inpopup',1);
echo js_include('scripts.js');
echo js_include('prototype.js');
echo js_include('scriptaculous.js');
echo js_include('effects.js');
echo js_include('controls.js');
echo js_include('dragdrop.js');
echo js_include('anc_script.js');
echo js_include('acc_ledger.js');
echo js_include('infobulle.js');
echo js_include('accounting_item.js');
echo js_include('card.js');


$str="?".$_SERVER['QUERY_STRING']."&div=popup";
$script="
var obj={'id':'popup','fixed':1,'class':'content',style:'width:auto','html':loading(),'qs':'$str',js_success:'success_box','js_error':null,'callback':'".$_GET['ajax']."'};
show_box(obj);
";
echo create_script($script);
?>