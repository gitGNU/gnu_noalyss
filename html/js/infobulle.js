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
 * \example echo JS_INFOBULLE; echo HtmlInput::infobulle(x)
 */

var posX=0,posy=0,offsetX=10,offsetY=10;
document.onmousemove=getPosition;
content[0]="Donnez le quickcode ou tapez une partie du nom de la fiche puis cliquer sur rechercher, si la recherche ne donne rien, il vous sera proposé de créer une nouvelle fiche";
content[1]="(optionnel) La description est un commentaire libre qui sert à identifier cette opération";
content[2]="Selectionnez le journal où l'opération doit être sauvée";
content[3]="Les périodes comptables servent comme un second contrôle pour la date de l'opération. Modifiez dans vos préférence pour avoir une autre période par défaut";
content[4]="(optionnel) L'échéance est la date limite de paiement";
content[5]="(optionnel)Le numéro d'extrait permet de retrouver plus facilement l'extrait de banque";
content[6]="Indiquez ici le prix hors tva si vous êtes affilié à la tva et que vous  pouvez la déduire , sinon indiquez ici le total tva incluse et utilisez un taux tva de 0%";
content[7]="(optionnel) Ces champs servent à contrôler que les montants correspondent à l'extrait";
content[8]="(optionnel) Ce montant correspond au total tva, si vous le laissez à vide, il sera calculé automatiquement en fonction du taux";
content[9]="Tapez le numéro de poste ou une partie du poste ou du libellé puis sur recherche, Si vous avez donné un quickcode, le poste comptable ne sera pas utilisé";
content[10]="ATTENTION changer le poste comptable d'une fiche modifiera <b>toutes les opérations</b> où cette fiche est utilisée";
function showBulle(p_ctl) {
	d=document.getElementById('bulle');
	d.innerHTML=content[p_ctl];
	d.style.top=posY+offsetY;
	d.style.left=posX+offsetX;
	d.style.visibility="visible";
}
function getPosition(e) {
 if (document.all) {
  posX=event.x+document.body.scrollLeft;
  posY=event.y+document.body.scrollTop;
  }
  else {
  posX=e.pageX;
  posY=e.pageY;
  }
}
function hideBulle(p_ctl) {
	d=document.getElementById('bulle');
	d.style.visibility="hidden";
}	