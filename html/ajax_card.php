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
 * \brief this file respond to an ajax request and return an object with the ctl and the html string
 * at minimum
 \verbatim
 {'ctl':'','html':''}
\endverbatim
 * The parameters are
 * - gDossier
 * - op
      - dc Detail of a card
      parameter : $qcode , optional ro for readonly and nohistory without the history button
      - bc Blank Card : display form for adding a card
      parameter fd_id (fiche_def:fd_id)
      - st Show Type : select type of card
      parameter fil : possible values of fd_id if empty it means all the fiche cat.
      - sc Save Card : insert a new card (call first bc)
      - upc  update a card
      specific parameter qcode
      - fs  Form to search card
          parameter like
	  - inp : the input text field to update
	  - str : current content of the input text field (inp)
	  - label : the element to put the name of the card
	  - price : the element to put the price of the card (if exists)
	  - tvaid : the element to put the tvaid of the card (if exists)
	  - jrn : the concerned ledger (or all)
	  - typecard : possible values are cred, deb, filter (list of fd_id)
      - ac Add Category
          - cat type of cat to add (FICHE_TYPE_CLIENT,...)
 * - ctl (to return)
 * - popup
 * - ref if we want to refresh the window
 *\see fiche fiche::Save constant.php
 */
require_once('class_database.php');
require_once ('class_fiche.php');
require_once('class_iradio.php');
require_once('function_javascript.php');
require_once('ac_common.php');
require_once ('class_user.php');
require_once ('class_fiche_attr.php');

$var=array('gDossier','op','ctl');
$cont=0;
/*  check if mandatory parameters are given */
foreach ($var as $v)
{
    if ( ! isset ($_REQUEST [$v] ) )
    {
        echo "$v is not set ";
        $cont=1;
    }
}
extract($_REQUEST );

ajax_disconnected($ctl);

if ( $cont != 0 ) exit();

set_language();
/*
 *echo a warning if disconnected
 */
ajax_disconnected($_REQUEST['ctl']);

$cn=new Database($gDossier);
$user=new User($cn);
$user->check(true);
$user->check_dossier($gDossier,true);
$html=var_export($_REQUEST,true);
switch($op)
{
    /* ------------------------------------------------------------ */
    /* Remove a attribut */
    /* ------------------------------------------------------------ */
case 'rmfa':
        ob_start();
    if( ! isset($_GET['ad_id']) || isNumber($_GET['ad_id']) ==0)
        throw new Exception ( "Parametre ad_id est invalide",11);
    $ad_id=  $_GET['ad_id'];
    try
    {
        $cn->start();
        $fa=new Fiche_Attr($cn,$ad_id);
        $fa->delete();
        $cn->commit();
    }
    catch (Exception $e)
    {
        $cn->rollback();
        echo $e->getMessage();
    }
    $html=ob_get_contents();
    ob_clean();
    break;
    /* ------------------------------------------------------------ */
    /* Display card detail */
    /* ------------------------------------------------------------ */

case 'dc':
    $f=new Fiche($cn);
    /* add title + close */
    $html="";
    $html=HtmlInput::anchor_close($ctl);
    $html.=h2info('Détail fiche');
    if ( $qcode != '')
    {
        $f->get_by_qcode($qcode);
	$can_modify=$user->check_action(FIC);
	if ( isset($ro) )
	  {
	    $can_modify=0;
	  }
	if ( $can_modify==1)
	  $card=$f->Display(false);
	else
	  $card=$f->Display(true);
	if ( $card == 'FNT' ) 
	  {
	    $html.='<h2 class="error">'._('Fiche non trouvée').'</h2>';
	  }
	else
	  {
	    
	    if ($can_modify==1)
	      {
		$html.='<form method="get" onsubmit="update_card(this);return false;">';
		$html.=dossier::hidden();
		$html.=HtmlInput::hidden('f_id',$f->id);
		$html.=HtmlInput::hidden('ctl',$ctl);
	      }
	    $html.=$card;
	    if ( $can_modify==1)
	      {
		$html.=HtmlInput::submit('save','Sauver');
	      }
	    $html.=HtmlInput::button('close_'.$ctl,'Fermer',"onclick=\"removeDiv('$ctl')\"");
	    if ( ! isset ($nohistory))$html.=HtmlInput::history_card_button($f->id,_('Historique'));
	    if ($can_modify==1)
	      {
		$html.='</form>';
	      }
	  }
    }
    else 
      {
      $html.='<h2 class="error">'._('Aucune fiche demandée').'</h2>';
      $html.=HtmlInput::button('close_'.$ctl,'Fermer',"onclick=\"removeDiv('$ctl')\"");
      }
    break;
    /* ------------------------------------------------------------ */
    /* Blank card */
    /* ------------------------------------------------------------ */
case 'bc':
    if ( $user->check_action(FICADD)==1 )
    {
        $r='';
	$r=HtmlInput::anchor_close($ctl);
	/* get cat. name */
	$cat_name=$cn->get_value('select fd_label from fiche_def where fd_id=$1',
				 array($fd_id));
	$r.=h2info(_('Nouvelle fiche ').$cat_name);
        $f=new Fiche($cn);
        $r.='<form id="save_card" method="POST" onsubmit="this.ipopup=\''.$ctl.'\';save_card(this);return false;" >';
        $r.=dossier::hidden();
        $r.=(isset($ref))?HtmlInput::hidden('ref',1):'';
        $r.=HtmlInput::hidden('fd_id',$fd_id);
        $r.=HtmlInput::hidden('ctl',$ctl);
        $r.=$f->blank($fd_id);
        $r.=HtmlInput::submit('sc','Sauve');
        $r.='</form>';
        $html=$r;
    }
    else
    {
        $html=alert(_('Action interdite'),true);
    }
    break;
    /* ------------------------------------------------------------ */
    /* Show Type */
    /* Before inserting a new card, the type must be selected */
    /* ------------------------------------------------------------ */
case 'st':
    $sql="select fd_id,fd_label from fiche_def";
    /*  if we filter  thanks the ledger*/
    if ( $ledger != -1 )
    {
        /* we want the card for deb or cred or both of this ledger */
        switch( $fil  )
        {
        case -1:
            $l=new Acc_Ledger($cn,$ledger);
            $sql.='  where fd_id in ('.$l->get_all_fiche_def().')';
            break;
        case 'cred':
            $l=new Acc_Ledger($cn,$ledger);
            $prop=$l->get_propertie();
            if ( $prop['jrn_def_fiche_cred']=='')$prop=-1;
            $sql.='  where fd_id in ('.$prop['jrn_def_fiche_cred'].')';
            break;
        case 'deb':
            $l=new Acc_Ledger($cn,$ledger);
            $prop=$l->get_propertie();
            if ( $prop=='')$prop=-1;
            $sql.='  where fd_id in ('.$prop['jrn_def_fiche_deb'].')';
            break;
        }
    }
    else
    {
        /* we filter thanks a given model of card */
        if ( isset($cat))
        {
            $sql=$sql.sprintf(' where frd_id = '.sql_string ($cat));
        }
        else
            /* we filter thanks a given list of category of card
             */
            if ( isset($fil) && strlen(trim($fil)) > 0 )
            {
                $sql=$sql.sprintf(" where fd_id in (%s)",
                                  sql_string($fil));
            }
    }
    $array=$cn->make_array($sql);
    $html=HtmlInput::anchor_close('select_card_div');
    $html.=h2info('Choix de la catégorie');
    if ( empty($array))
    {
        $html.=_("Aucune catégorie de fiche ne correspondant à".
                " votre demande");
        $html.=$sql;
    }
    else
    {
        $r='';
	$html.='<p class="notice"> Choississez la catégorie de fiche à laquelle vous aimeriez ajouter une fiche</p>';
        $isel=new ISelect('fd_id');
        $isel->value=$array;
        $r.='<form id="sel_type" method="GET" onsubmit="this.ipopup='.$ctl.';dis_blank_card(this);return false;" >';
        $r.=dossier::hidden();
        $r.=(isset($ref))?HtmlInput::hidden('ref',1):'';

        $r.=$isel->input();
	$r.='<p>';
        $r.=HtmlInput::submit('st','choix');
	$r.=HtmlInput::button('Annuler','Annuler'," onclick=\"removeDiv('$ctl')\" ");
	$r.='</p>';
        $r.='</form>';
        $html.=$r;

    }
    break;
    /*----------------------------------------------------------------------
     * SC save card
     * save the new card (insert)
     *
     ----------------------------------------------------------------------*/
case 'sc':
  $html=HtmlInput::anchor_close($ctl);
  $html.=h2info('Nouvelle fiche');
    if ( $user->check_action(FICADD)==1 )
    {
        $f=new Fiche($cn);
        $f->insert($fd_id,$_POST);
        $html.='<h2 class="notice">Fiche sauvée</h2>';
        $html.=$f->Display(true);
        $js="";
        if ( isset( $_POST['ref'])) $js=create_script(' window.location.reload()');
        $html.=$js;
    }
    else
    {
        $html.=alert(_('Action interdite'),true);
    }
    $html.=HtmlInput::button('fermer','Fermer'," onclick=\"removeDiv('$ctl')\";");
    break;
    /*----------------------------------------------------------------------
     * Search a card
     *
     *----------------------------------------------------------------------*/
case 'fs':
    require_once('class_acc_ledger.php');
    $r='';
    $r.='<div style="float:right;height:10px;display:block;margin-top:2px;margin-right:2px">';
    $r.= '<A style="background-color:blue;color:white;text-decoration:none" HREF="javascript:void(0)" onclick="removeDiv(\'search_card\');">Fermer</A>'; 
    $r.='</div>';
    $r.='<div> '.h2info(_('Recherche de fiche')).'</div>';

    $r.='<form method="GET" onsubmit="this.ctl=\'ipop_card\';search_get_card(this);return false;">';
    $q=new IText('query');
    $q->value=(isset($query))?$query:'';
    $r.=_('Fiche contenant');
    $r.=$q->input();
    $r.=HtmlInput::submit('fs',_('Recherche'));
    $r.=dossier::hidden().HtmlInput::hidden('op','fs');
    $array=array();
    foreach (array('query','inp','jrn','label','typecard','price','tvaid') as $i)
    {
        if  (isset(${$i}) )
        {
            $r.=HtmlInput::hidden($i,${$i});
            $sql_array[$i]=${$i};
        }
    }
    /* what is the type of the ledger */
    $type="GL";
    if (isset($jrn) && $jrn > 1)
    {
        $ledger=new Acc_Ledger($cn,$jrn);
        $type=$ledger->get_type();
    }
    $fiche=new Fiche($cn);
    /* Build the SQL and show result */
    $sql=$fiche->build_sql($sql_array);

    /* We limit the search to 20 records */
    $sql=$sql.' order by vw_name limit 20';
    $a=$cn->get_array($sql);

    for($i=0;$i<count($a);$i++)
    {
        $array[$i]['quick_code']=$a[$i]['quick_code'];
        $array[$i]['name']=h($a[$i]['vw_name']);
        $array[$i]['first_name']=h($a[$i]['vw_first_name']);
        $array[$i]['description']=h($a[$i]['vw_description']);
        $array[$i]['javascript']=sprintf("set_value('%s','%s');",
                                         $inp,$array[$i]['quick_code']);
        $array[$i]['javascript'].=sprintf("set_value('%s','%s');",
                                          $label,j(h(strip_tags($a[$i]['vw_name']))));

        /* if it is a ledger of sales we use vw_buy
           if it is a ledger of purchase we use vw_sell*/
        if ( $type=="ACH" )
            $array[$i]['javascript'].=sprintf("set_value('%s','%s');",
                                              $price,$a[$i]['vw_buy']);
        if ( $type=="VEN" )
            $array[$i]['javascript'].=sprintf("set_value('%s','%s');",
                                              $price,$a[$i]['vw_sell']);
        $array[$i]['javascript'].=sprintf("set_value('%s','%s');",
                                          $tvaid,$a[$i]['tva_id']);
        $array[$i]['javascript'].="removeDiv('search_card');";

    }//foreach

    ob_start();
    require_once('template/card_result.php');
    $r.=ob_get_contents();
    ob_clean();
    $ctl=$ctl.'_content';
    $html=$r;
    break;
case 'ac':
    if ( $user->check_action(FICCAT)==1 )
    {

        /*----------------------------------------------------------------------
         * Add a category, display first the form
         *
         *----------------------------------------------------------------------*/
        $ipopup=str_replace('_content','',$ctl);
        switch($cat)
        {
        case FICHE_TYPE_CLIENT:
            $msg=_(' de clients');
            $base=$cn->get_value("select p_value from parm_code where p_code='CUSTOMER'");
            break;
        case FICHE_TYPE_FOURNISSEUR:
            $msg=_(' de fournisseurs');
            $base=$cn->get_value("select p_value from parm_code where p_code='SUPPLIER'");
            break;
        case FICHE_TYPE_ADM_TAX:
            $msg=_(' d\'administration');
            $base='';
            break;
        }

        $html='';
        $html.="<h2>"._("Ajout d'une catégorie")."  ".$msg."</h2>";
        /*  show the form */
        $html.= '<div class="u_content">';
        $html.=$ctl;
        $html.= '<form id="newcat" name="newcat" method="post" onsubmit="this.ipopup=\''.$ipopup.'\';save_card_category(this);return false;">';
        $html.= dossier::hidden();
        $html.=HtmlInput::hidden('cat',$cat);
        $search=new IPoste("class_base");
        $search->size=40;
        $search->value=$base;
        $search->label=_("Recherche poste");
        $search->set_attribute('gDossier',dossier::id());
        $search->set_attribute('account',$search->name);
        $search->set_attribute('ipopup','ipop_account');

        $str_poste=$search->input();
        ob_start();
        require('template/category_of_card.php');
        $html.=ob_get_contents();
        ob_clean();
        $submit=HtmlInput::submit('save',_('Sauve'));
        $html.= '<p>';
        $html.= $submit;
        $html.= '</p>';
        $html.= '</form>';
        $html.= '</div>';
    }
    else
    {
        $html=alert(_('Action interdite'),true);
    }
    break;
case 'scc':
    /*----------------------------------------------------------------------
     * Save card Category into the database and return a ok message
     *
     *----------------------------------------------------------------------*/
    $html='';
    if ( $user->check_action(FICCAT) == 1 )
    {
        if ( strlen(trim($_POST['nom_mod'])) != 0 &&
                strlen(trim($_POST['class_base'])) != 0 )
        {
            $array=array("FICHE_REF"=>$cat,
                         "nom_mod"=>$_POST['nom_mod'],
                         "class_base"=>$_POST['class_base']);
            if ( isset ($_POST['create'])) $array['create']=1;
            $catcard=new Fiche_Def($cn);
            if ( $catcard->Add($array) == -1)
                $html.="alert('"._('Catégorie existe déjà')."')";
            else
                $html.="alert('"._('Catégorie sauvée')."')";
            $html.=create_script($html);
        }
        else
        {
            $html.="alert('"._("Le nom et la classe base ne peuvent être vide")."')";
            $html.=create_script($html);

            $invalid=1;
        }
        $ipop=str_replace('_content','',$ctl);
        $html.=create_script('hideIPopup(\''.$ipop.'\');');
    }
    else
    {
        $html=alert(_('Action interdite'),true);
    }
    break;
case 'upc':
  $html=HtmlInput::button_close($ctl);
  $html.=h2info('Détail fiche');
  
  if ( $user->check_action(FICADD)==0 )
    {
      $html.=alert(_('Action interdite'),true);
    }  
  else
    {
      if ($cn->get_value('select count(*) from fiche where f_id=$1',array($_GET['f_id'])) == '0' )
	{
	  $html.=alert(_('Fiche non valide'),true);
	  }

      else
	{
	  $html=HtmlInput::button_close($ctl);
	  $html.=h2info('Détail fiche (sauvée)');

	  $f=new Fiche($cn,$_GET['f_id']);
	  ob_start();
	  $f->update($_GET);
	  $html.=ob_get_contents();
	  ob_clean();
	  $html.=$f->Display(true);
	  $html.=HtmlInput::button('close_'.$ctl,'Fermer',"onclick=\"removeDiv('$ctl')\"");
	}
      }
} // switch
$html=escape_xml($html);

header('Content-type: text/xml; charset=UTF-8');
echo <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<data>
<ctl>$ctl</ctl>
<code>$html</code>
</data>
EOF;
