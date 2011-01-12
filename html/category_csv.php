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
 * \brief Called by impress->category, export in CVS the history of a category
 * of card
 */
header('Pragma: public');
header('Content-type: application/csv');
header('Content-Disposition: attachment;filename="bal-fiche.csv"',FALSE);

// Security we check if user does exist and his privilege
require_once('class_user.php');
require_once('class_database.php');
require_once('class_dossier.php');
require_once('ac_common.php');

/* Security */
$gDossier=dossier::id();
$cn=new Database($gDossier);
$User=new User($cn);
$User->Check();
$User->check_dossier($gDossier);
$User->can_request(IMPFIC,0);


/* balance */
if ( $_GET['histo'] == 4 )
{
    $fd=new Fiche_Def($cn,$_REQUEST['cat']);
    if ( $fd->hasAttribute(ATTR_DEF_ACCOUNT) == false )
    {
      exit;
    }
    $aCard=$cn->get_array("select f_id,ad_value from fiche join fiche_Detail using (f_id)  where ad_id=1 and fd_id=$1 order by 2 ",array($_REQUEST['cat']));

    if ( empty($aCard))
    {
        echo "Aucune fiche trouvée";//Save PDF to file
        $fDate=date('dmy-Hi');
        exit;
    }
    printf ('"Quick code";"Nom";"debit";"credit";"solde";"D/C";');
    printf ("\n");
    for ($i=0;$i < count($aCard);$i++)
    {
        if ( isDate($_REQUEST['start']) == null || isDate ($_REQUEST['end']) == null ) 	 exit;
        $filter= " (j_date >= to_date('".$_REQUEST['start']."','DD.MM.YYYY') ".
                 " and  j_date <= to_date('".$_REQUEST['end']."','DD.MM.YYYY')) ";
        $oCard=new Fiche($cn,$aCard[$i]['f_id']);
        $solde=$oCard->get_solde_detail($filter);
        if ( $solde['debit'] == 0 && $solde['credit']==0) continue;

        printf('"%s";"%s";%s;%s;%s;"%s"',
	       $oCard->strAttribut(ATTR_DEF_QUICKCODE),
	        $oCard->strAttribut(ATTR_DEF_NAME),
	        nb($solde['debit']),
	        nb($solde['credit']),
	        nb(abs($solde['solde'])),
	        (($solde['solde']<0)?'CRED':'DEB'));
	printf("\n");
    }
}
exit;
