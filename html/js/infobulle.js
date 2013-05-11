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
/*!\file
 * \brief create the infobulle, the internalization is not yet implemented
 * \code
 // Example
  echo JS_INFOBULLE;
  echo HtmlInput::infobulle(x);
 \endcode
 */

var posX=0,posy=0,offsetX=10,offsetY=10;
document.onmousemove=getPosition;
var content=new Array();
content[0]="Donnez le quickcode ou tapez une partie du nom de la fiche, en appuyant sur le bouton, vous pourrez la chercher";
content[1]="(optionnel) La description est un commentaire libre qui sert à identifier cette opération";
content[2]="Selectionnez le journal où l'opération doit être sauvée";
content[3]="Les périodes comptables servent comme un second contrôle pour la date de l'opération. Modifiez dans vos préférence pour avoir une autre période par défaut. Pour ne plus avoir à changer la période aller dans COMPANY, et mettez \"Afficher la période comptable\" à non";
content[4]="(optionnel) L'échéance est la date limite de paiement";
content[5]="(optionnel)Le numéro d'extrait permet de retrouver plus facilement l'extrait de banque";
content[6]="Indiquez ici le prix hors tva si vous êtes affilié à la tva et que vous  pouvez la déduire , sinon indiquez ici le total tva incluse et utilisez un taux tva de 0%";
content[7]="(optionnel) Ces champs servent à contrôler que les montants correspondent à l'extrait";
content[8]="(optionnel) Ce montant correspond au total tva, si vous le laissez à vide, il sera calculé automatiquement en fonction du taux";
content[9]="Tapez le numéro de poste ou une partie du poste ou du libellé puis sur recherche, Si vous avez donné un quickcode, le poste comptable ne sera pas utilisé";
content[10]="ATTENTION changer le poste comptable d'une fiche <b>ne modifiera pas toutes les opérations</b> où cette fiche est utilisée";
content[11]="ATTENTION si le poste comptable est  vide, il sera créé automatiquement";
content[12]="Document généré uniquement si le mode de paiement est utilisé";
content[13]="Vous pouvez utiliser le % pour indiquer le poste parent";
content[14]="Attention, le poste comptable doit exister, il ne sera pas vérifié";
content[15]="Laissez à 0 pour ne rien changer";
content[16]="Vous devez donner la date par opération";
content[17]="Cliquez sur le titre d'une colonne pour trier";
content[18]="Donnez une partie du nom, prénom, de la description, du poste comptable, du n° de TVA ou quick code";
content[19]="Donnez une partie du nom, de la description,  du n° de TVA ou quick code";
content[20]="Les menus ne peuvent dépendre que dans d'un menu principal ou d'un menu, si cette liste est vide, ajouter des modules ou menu principal sans donner de dépendance";
content[21]="Donnez un nombre entre 0 & 100";
content[22]="Donnez une partie du nom du dossier,du nom, du prénom ou du login pour filtrer";
content[23]="Donnez une partie du nom du dossier ou de la description pour filtrer";
content[24]="Donnez une partie du poste comptable ou du libellé pour filtrer";
content[25]="Donnez une partie du libellé, la date, le montant ou le numéro d'opération pour filtrer, cela n'efface pas ce qui a déjà été sélectionné";
content[26]="Donnez une partie du quickcode, nom, description... pour filtrer";

function showBulle(p_ctl)
{
    d=document.getElementById('bulle');
    d.innerHTML=content[p_ctl];
    d.style.top=posY+offsetY;
    d.style.left=posX+offsetX;
    d.style.visibility="visible";
}
function getPosition(e)
{
    if (document.all)
    {
        posX=event.x+document.body.scrollLeft;
        posY=event.y+document.body.scrollTop;
    }
    else
    {
        posX=e.pageX;
        posY=e.pageY;
    }
}
function hideBulle(p_ctl)
{
    d=document.getElementById('bulle');
    d.style.visibility="hidden";
}
