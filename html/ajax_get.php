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
 * - PHPSESSID
 * - gDossier
 * - action 
      - dc Detail of a card
      specific parameter qcode
 * - ctl (to return)
 */
require_once('class_database.php');
require_once ('class_fiche.php');
require_once('class_iradio.php');
require_once('function_javascript.php');

/**
 *\todo add the security to avoid that everyone can see or modify all card
 */
$var=array('PHPSESSID','gDossier','op','ctl');
$cont=0;
/*  check if mandatory parameters are given */
foreach ($var as $v) {
  if ( ! isset ($_REQUEST [$v] ) ) {
    echo "$v is not set ";
    $cont=1;
  }
}
if ( $cont != 0 ) exit();
extract($_REQUEST );
$cn=new Database($gDossier);
switch($op) {
  /* ------------------------------------------------------------ */
  /* Display card detail */
  /* ------------------------------------------------------------ */

case 'dc':
   $f=new Fiche($cn);
   $f->get_by_qcode($qcode);
   $html=$f->Display(true); 
  break;
  /* ------------------------------------------------------------ */
  /* Blank card */
  /* ------------------------------------------------------------ */
case 'bc':
  /**
   *\todo check if the user can add a card
   */
  $r='';
  $f=new Fiche($cn);
  $popup=str_replace('_content','',$ctl);
  $r.='<form id="save_card" method="POST" onsubmit="this.ipopup=\''.$popup.'\';save_card(this);return false;" >';
  $r.=dossier::hidden();$r.=HtmlInput::phpsessid();
  $r.=HtmlInput::hidden('fd_id',$fd_id);
  $r.=HtmlInput::hidden('ctl',$ctl);
  $r.=$f->blank($fd_id);
  $r.=HtmlInput::submit('sc','Sauve');
  $r.='</form>';
  $html=$r;

  break;
  /* ------------------------------------------------------------ */
  /* Show Type */
  /* Before inserting a new card, the type must be selected */
  /* ------------------------------------------------------------ */
case 'st':
  $sql="select fd_id,fd_label from fiche_def";
  if ( strlen(trim($fil)) > 0 ){ 
    $sql=$sql.sprintf(" where fd_id in (%s)",
		      FormatString($fil));
  } 
  $array=$cn->make_array($sql);
  /**
   *\todo if empty returns a error message : there is no type card to select
   * 
   */
  if ( empty($array)) {
    $html="Aucune catégorie de fiche ne correspondant a".
      " votre demande";
  } else {
    $r='';
    $isel=new ISelect('fd_id');
    $isel->value=$array;
    $popup=str_replace('_content','',$ctl);
    $r.='<form id="sel_type" method="GET" onsubmit="this.ipopup=\''.$popup.'\';dis_blank_card(this);return false;" >';
    $r.=dossier::hidden();$r.=HtmlInput::phpsessid();
    $r.='<p>choisissez le type de fiche à ajouter</p>';
    $r.=$isel->input();
    $r.=HtmlInput::submit('st','choix');
    $r.='</form>';
    $html=$r;
  }
  break;
  /*----------------------------------------------------------------------
   * SC save card
   * save the new card (insert)
   *
   ----------------------------------------------------------------------*/
case 'sc':
  $f=new Fiche($cn);
  $f->insert($fd_id,$_POST);
  $html='<h2 class="info">Fiche sauvée</h2>';
  $html.=$f->Display(true);

  break;
}
$html=str_replace('&','&amp;',$html);
$html=str_replace('<','&lt;',$html);
$html=str_replace('>','&gt;',$html);
$html=str_replace("'",'&apos;',$html);
$html=str_replace('"','&quot;',$html);

header('Content-type: text/xml; charset=UTF-8');
echo <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<data>
<ctl>$ctl</ctl>
<code>$html</code>
</data>




EOF;
