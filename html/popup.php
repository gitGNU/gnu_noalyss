<?php

require_once('../include/ac_common.php');
require_once('function_javascript.php');
require_once('class_html_input.php');
require_once('class_dossier.php');
require_once('class_database.php');
require_once('class_user.php');
require_once('class_periode.php');
echo '<div style="float:left;">'.HtmlInput::print_window();

html_page_start($_SESSION['g_theme']);
global $g_user;
if ( basename($_GET['ajax']) == 'ajax_history.php' )
  {
    $href=dossier::get();
    $cn=new Database(dossier::id());
    /* current year  */
    $g_user=new User($cn);
    $exercice=$g_user->get_exercice();

    /* get date limit */
    $periode=new Periode($cn);
    $limit=$periode->get_limit($exercice);

    $from_periode='from_periode='.format_date($limit[0]->p_start);
    $to_periode='to_periode='.format_date($limit[1]->p_end);
    if (isset($_GET['ex']))
      {
	if ( $exercice > $_GET['ex'])
	  {
	    $limit_periode=$periode->get_limit($_GET['ex']);
	    $from_periode='from_periode='.format_date($limit_periode[0]->p_start);
	  }
	else
	  {
	    $limit_periode=$periode->get_limit($_GET['ex']);
	    $to_periode='to_periode='.format_date($limit_periode[1]->p_end);

	  }
      }

    if (isset($_GET['pcm_val']) )
      {
	$href_csv="export.php?".$href.'&poste_id='.$_GET['pcm_val'].'&ople=0&type=poste&'.$from_periode.'&'.$to_periode."&act=CSV:postedetail";
	$href_pdf="export.php?".$href.'&poste_id='.$_GET['pcm_val'].'&ople=0&type=poste&'.$from_periode.'&'.$to_periode."&act=PDF:postedetail";;
      }
    else
      {
	$href_csv="export.php?".$href.'&f_id='.$_GET['f_id'].'&ople=0&type=poste&'.$from_periode.'&'.$to_periode."&act=CSV:fichedetail";
	$href_pdf="export.php?".$href.'&f_id='.$_GET['f_id'].'&ople=0&type=poste&'.$from_periode.'&'.$to_periode."&act=PDF:fichedetail";
      }

    echo '<a class="smallbutton"  href="'.$href_csv.'">'._("Export CSV").'</a>';
    echo '<a class="smallbutton"  href="'.$href_pdf.'">'._("Export PDF").'</a>';
  }
echo '</div>';
echo HtmlInput::hidden('inpopup',1);
load_all_script();

$str=$_SERVER['QUERY_STRING']."&div=popup";
$script="
        var obj={'id':'popup','fixed':1,'class':'content',style:'width:auto','html':loading(),'qs':'$str',js_success:'success_box','js_error':null,'callback':'".$_GET['ajax']."'};
        show_box(obj);
        ";
echo create_script($script);
?>
