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
 * \brief answer to the ajax request for the ledger 
 * it means : 
    - detail of an operation (expert, user and analytic view)
    - removal of an operation
    - load a receipt document
    - for reconcialiation        
    - update of analytic content
*/
require_once('class_database.php');
require_once('class_user.php');
require_once('class_acc_operation.php');
require_once('class_acc_ledger.php');
require_once ('class_fiche.php');
require_once('class_acc_reconciliation.php');
require_once('class_anc_operation.php');
require_once('class_idate.php');
require_once('class_ibox.php');
/**
 *@todo Check if we receive the needed data (jr_id...)
 */
$action=$_REQUEST['act'];
$jr_id=$_REQUEST['jr_id'];
$div=$_REQUEST['div'];		/* the div source and target for javascript */
$gDossier=dossier::id();
/**
 *@todo if $_SESSION['g_user'] is not set : echo a warning
 */

$cn=new Database(dossier::id());

// check if the user is valid and can access this folder
$User=new User($cn);
$User->check();
if ( $User->check_dossier(dossier::id(),true)=='X' )
{
    ob_start();
    require_once ('template/ledger_detail_forbidden.php');
    $html=ob_get_contents();
    ob_clean();
    $html=escape_xml($html);
    header('Content-type: text/xml; charset=UTF-8');
    echo <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<data>
<ctl>$div</ctl>
<code>$html</code>
</data>
EOF;
    exit();
}


// check if the user can access the ledger where the operation is (view) and
// if he can modify it
$op=new Acc_Operation($cn);
$op->jr_id=$_REQUEST['jr_id'];
$ledger=$op->get_ledger();
$access=$User->get_ledger_access($ledger);
if ( $access == 'X' )
{
    ob_start();
    require_once ('template/ledger_detail_forbidden.php');
    $html=ob_get_contents();
    ob_clean();

    $html=escape_xml($html);
    header('Content-type: text/xml; charset=UTF-8');
    echo <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<data>
<ctl>$div</ctl>
<code>$html</code>
</data>
EOF;
    exit();
}
$html=var_export($_REQUEST,true);
switch ($action)
{
    ///////////////////////////////////////////////////////////////////////////
    //  remove op
    ///////////////////////////////////////////////////////////////////////////
case 'rmop':
        if ( $access=='W')
        {
            ob_start();
            /* get the ledger */
            try
            {
                $cn->start();
                $oLedger=new Acc_Ledger($cn,$ledger);
                $oLedger->jr_id=$_REQUEST['jr_id'];
                $oLedger->delete();
                $cn->commit();
                echo _("Opération Effacée");
            }
            catch (Exception $e)
            {
                $e->getMessage();
                $cn->rollback;
            }
            $html=ob_get_contents();
            ob_clean();
        }
    break;
    //////////////////////////////////////////////////////////////////////
    // DE Detail
    //////////////////////////////////////////////////////////////////////
case 'de':
    ob_start();

    try
    {
        $op->get();			/* get detail op (D/C) */
        $obj=$op->get_quant();	/* return an obj. ACH / FIN or VEN or null if nothing is found*/

        $oLedger=new Acc_Ledger($cn,$ledger);
        if ( $obj==null || $obj->signature == 'ODS'  )
        {
            /* only the details */
            require_once('template/ledger_detail_misc.php');
        }
        elseif ( $obj->signature=='ACH')
        {
            require_once('template/ledger_detail_ach.php');
        }
        elseif ($obj->signature=='FIN')
        {
            require_once('template/ledger_detail_fin.php');
        }
        elseif ( $obj->signature=='VEN')
        {
            require_once('template/ledger_detail_ven.php');
        }
    }
    catch (Exception $e)
    {
        echo '<A style="background-color:blue;color:white;text-decoration:none" HREF="javascript:void(0)" onclick="removeDiv(\''.$div.'\');">Fermer</A>';
        echo '<h2 class="error">Désolé il y a une erreur</h2>';
    }
    $html=ob_get_contents();
    ob_clean();

    break;
    /////////////////////////////////////////////////////////////////////////////
    // form for the file
    /////////////////////////////////////////////////////////////////////////////
case 'file':
    $op->get();
    $obj=$op->get_quant();	/* return an obj. ACH / FIN or VEN or null if nothing is found*/

    if ( $obj->det->jr_pj_name=='')
    {
        echo "<html><head>";
        $repo=new Database();
        $theme=$repo->get_value("select the_filestyle from theme where the_name=$1",array($_SESSION['g_theme']));
        echo    "   <LINK REL=\"stylesheet\" type=\"text/css\" href=\"$theme\" media=\"screen\">";
        echo "</head>";
        echo '<div class="op_detail_frame">';

        if ( $access=='W')
        {
            echo '<FORM METHOD="POST" ENCTYPE="multipart/form-data" id="form_file">';
            $sp=new ISpan('file'.$div);
            $sp->style="display:none;width:155;height:15;background-color:red;color:white;font-size:10";
            $sp->value="Chargement";
            echo $sp->input();
            echo HtmlInput::hidden('act','loadfile');
            echo dossier::hidden();
            echo HtmlInput::hidden('jr_id',$jr_id);
            echo HtmlInput::hidden('div',$div);

            echo '<INPUT TYPE="FILE" name="pj" onchange="getElementById(\'file'.$div.'\').style.display=\'inline\';submit(this);">';
            echo '</FORM>';
        }
        else
        {
            echo "<html><head>";
            $repo=new Database();
            $theme=$repo->get_value("select the_filestyle from theme where the_name=$1",array($_SESSION['g_theme']));
            echo    "   <LINK REL=\"stylesheet\" type=\"text/css\" href=\"$theme\" media=\"screen\">";
            echo "</head>";
            echo '<div class="op_detail_frame">';

            echo _('Aucun fichier');
        }
        echo '</div>';
        echo '</body></html>';
        exit();
    }
    else
    {
        echo "<html><head>";
        $repo=new Database();
        $theme=$repo->get_value("select the_filestyle from theme where the_name=$1",array($_SESSION['g_theme']));
        echo    "   <LINK REL=\"stylesheet\" type=\"text/css\" href=\"$theme\" media=\"screen\">";
        echo "</head><body>";
        echo '<div class="op_detail_frame">';

        $x='';
        if ($access=='W')
            $x=sprintf('<a class="mtitle" style="margin-right:12" href="ajax_ledger.php?gDossier=%d&div=%s&jr_id=%s&act=rmf" onclick="return confirm(\'Effacer le document ?\')">enlever</a>',
                       $gDossier,$div,$jr_id);
        echo $x;
        $filename= $obj->det->jr_pj_name;
        if ( strlen($obj->det->jr_pj_name) > 20 )
        {
            $filename=substr($obj->det->jr_pj_name,0,23);
        }
        $h=sprintf('<a class="mtitle"  href="show_pj.php?gDossier=%d&jrn=%d&jr_grpt_id=%d">%s</a>',
                   $gDossier,$ledger,$obj->det->jr_grpt_id,h( $filename));
        echo $h;
        echo '</div>';
        echo '</body></html>';
        exit();
    }
/////////////////////////////////////////////////////////////////////////////
// load a file
/////////////////////////////////////////////////////////////////////////////
case 'loadfile':
    if ( $access == 'W' && isset ($_FILES))
    {
        $cn->start();
        // remove the file
        $grpt=$cn->get_value('select jr_grpt_id from jrn where jr_id=$1',array($jr_id));
        $cn->save_upload_document($grpt);
        $cn->commit();
        // Show a link to the new file
        $op->get();
        $obj=$op->get_quant();	/* return an obj. ACH / FIN or VEN or null if nothing is found*/

        echo "<html><head>";
        $repo=new Database();
        $theme=$repo->get_value("select the_filestyle from theme where the_name=$1",array($_SESSION['g_theme']));
        echo    "   <LINK REL=\"stylesheet\" type=\"text/css\" href=\"$theme\" media=\"screen\">";
        echo "</head>";
        echo '<div class="op_detail_frame">';
        $x=sprintf('<a class="mtitle" style="margin-right:12px" href="ajax_ledger.php?gDossier=%d&div=%s&jr_id=%s&act=rmf" onclick="return confirm(\'Effacer le document ?\')">enlever</a>',
                   $gDossier,$div,$jr_id);
        echo $x;
        $filename= $obj->det->jr_pj_name;
        if ( strlen($obj->det->jr_pj_name) > 20 )
        {
            $filename=substr($obj->det->jr_pj_name,1,20);
        }
        $h=sprintf('<a class="mtitle"  href="show_pj.php?gDossier=%d&jrn=%d&jr_grpt_id=%d">%s</a>',
                   $gDossier,$ledger,$obj->det->jr_grpt_id,h($filename));
        echo $h;
        echo '</div>';

    }
    exit();
/////////////////////////////////////////////////////////////////////////////
// remove a file
/////////////////////////////////////////////////////////////////////////////
case 'rmf':
    if (   $access == 'W' )
    {
        echo "<html><head>";
        $repo=new Database();
        $theme=$repo->get_value("select the_filestyle from theme where the_name=$1",array($_SESSION['g_theme']));
        echo    "   <LINK REL=\"stylesheet\" type=\"text/css\" href=\"$theme\" media=\"screen\">";
        echo "</head>";
        echo '<div class="op_detail_frame">';

        echo '<FORM METHOD="POST" ENCTYPE="multipart/form-data" id="form_file">';
        $sp=new ISpan('file'.$div);
        $sp->style="display:none;width:155;height:15;background-color:red;color:white;font-size:10";
        $sp->value="Chargement";
        echo $sp->input();

        echo HtmlInput::hidden('act','loadfile');
        echo dossier::hidden();
        echo HtmlInput::hidden('jr_id',$jr_id);
        echo HtmlInput::hidden('div',$div);

        echo '<INPUT TYPE="FILE" name="pj" onchange="getElementById(\'file'.$div.'\').style.display=\'inline\';submit(this);">';
        echo '</FORM>';
        $ret=$cn->exec_sql("select jr_pj from jrn where jr_id=$1",array($jr_id));
        if (Database::num_row($ret) != 0)
        {
            $r=Database::fetch_array($ret,0);
            $old_oid=$r['jr_pj'];
            if (strlen($old_oid) != 0)
            {
                // check if this pj is used somewhere else
                $c=$cn->count_sql("select * from jrn where jr_pj=".$old_oid);
                if ( $c == 1 )
                    $cn->lo_unlink($old_oid);
            }
            $cn->exec_sql("update jrn set jr_pj=null, jr_pj_name=null, ".
                          "jr_pj_type=null  where jr_id=$1",array($jr_id));
        }
    }
    echo '</div>';
    exit();
/////////////////////////////////////////////////////////////////////////////
// Save operation detail
/////////////////////////////////////////////////////////////////////////////
case 'save':
    ob_start();
    try
    {
        $cn->start();
        if ( $access=="W")
        {
            $cn->exec_sql("update jrn set jr_comment=$1,jr_pj_number=$2,jr_date=to_date($4,'DD.MM.YYYY') where jr_id=$3",
                          array($_POST['lib'],$_POST['npj'],$jr_id,$_POST['p_date']));
	    $cn->exec_sql("update jrnx set j_date=to_date($1,'DD.MM.YYYY') where j_grpt in (select jr_grpt_id from jrn where jr_id=$2)",
			  array($_POST['p_date'],$jr_id));
            $rapt=$_POST['rapt'];
            if (trim($rapt) != '')
            {
                $rec=new Acc_Reconciliation ($cn);
                $rec->set_jr_id($jr_id);

                if (strpos($rapt,",") != 0 )
                {
                    $aRapt=explode(',',$rapt);
                    /* reconcialition */
                    foreach ($aRapt as $rRapt)
                    {
                        if ( isNumber($rRapt) == 1 )
                        {
                            // Add a "concerned operation to bound these op.together
                            $rec->insert($rRapt);
                        }
                    }
                }
                else
                    if ( isNumber($rapt) == 1 )
                    {
                        $rec->insert($rapt);
                    }
            }
            ////////////////////////////////////////////////////
            // CA
            //////////////////////////////////////////////////
            $owner = new Own($cn);
            if ( $owner->MY_ANALYTIC != "nu" )
            {
                // for each item, insert into operation_analytique */
                $op=new Anc_Operation($cn);
                $op->save_update_form($_POST);
            }
        }
        echo _('Opération sauvée');
        $cn->commit();
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
    }
    $html=ob_get_contents();
    ob_clean();

    break;
/////////////////////////////////////////////////////////////////////////////
    // remove a reconciliation
/////////////////////////////////////////////////////////////////////////////
case 'rmr':
    if ( $access=='W')
    {
        $rec=new Acc_Reconciliation($cn);
        $rec->set_jr_id($jr_id);
        $rec->remove($_GET['jr_id2']);
    }
    break;
    ////////////////////////////////////////////////////////////////////////////////
    // ask for a date for reversing the operation
case 'ask_extdate':
    $date=new IDate('p_date');
    $html= '<A style="background-color:blue;color:white;text-decoration:none" HREF="javascript:void(0)" onclick="removeDiv(\''.$div.'\');">Fermer</A>';
    $html.="<form id=\"form_".$div."\" onsubmit=\"return reverseOperation(this);\">";
    $html.=HtmlInput::hidden('jr_id',$_REQUEST['jr_id']).HtmlInput::hidden('div',$div).dossier::hidden().HtmlInput::hidden('act','reverseop');
    $html.='<h2 class="info">entrez une date </H2>'.$date->input();
    $html.=HtmlInput::submit('x','accepter');
    $html.='</form>';
    break;
    ////////////////////////////////////////////////////////////////////////////////
    // Reverse an operation
    ////////////////////////////////////////////////////////////////////////////////
case 'reverseop':
    if ( $access=='W')
    {
        ob_start();
        try
        {
            $cn->start();
            $oLedger=new Acc_Ledger($cn,$ledger);
            $oLedger->jr_id=$_REQUEST['jr_id'];
            $oLedger->reverse($_REQUEST['p_date']);
            $cn->commit();
            echo _("Opération extournée");
        }
        catch (Exception $e)
        {
            $e->getMessage();
            $cn->rollback();
        }
    }
    $html=ob_get_contents();
    ob_clean();
    break;
}
$html=escape_xml($html);
header('Content-type: text/xml; charset=UTF-8');
echo <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<data>
<ctl>$div</ctl>
<code>$html</code>
</data>
EOF;
