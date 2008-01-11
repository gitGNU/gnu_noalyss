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

/* !\file 
 */

/* \brief Plan Analytique
 *
 */
require_once("class_anc_plan.php");
require_once("class_anc_account.php");
$ret="";

//---------------------------------------------------------------------------
// action 
// Compute the u_redcontent div
//---------------------------------------------------------------------------
if ( isset($_REQUEST['sa']))
  {
	$sa=$_REQUEST['sa'];

	// show the form for adding a pa
	if ( $sa == "add_pa")
	  {
		$wAction=new widget("hidden","","p_action","ca_pa");
		$new=new Anc_Plan($cn);
		if ( $new->isAppend() == true)
		  {
			$ret.= '<div class="u_redcontent">';
			$ret.= '<h2 class="info">Nouveau plan</h2>';
			$ret.= '<form method="post">';
			$ret.=dossier::hidden();
			$ret.= $new->form();
			$wSa=new widget("HIDDEN","","sa","pa_write");
			$wSa->value="pa_write";
			$ret.= $wSa->IOValue();
			$ret.= $wAction->IOValue();
			$ret.= $wSa->Submit("submit","Enregistre");
			$ret.= '</form>';
			$ret.= '</div>';
		  }
		else
		  {
			$ret.= '<div class="u_redcontent">'.
			  '<h2 class="info">'.
			  "Maximum de plan analytique est atteint".
			  "</h2></div>";
		  }
	  }
	// Add 
	if ( $sa == "pa_write")
	  {
		$new=new Anc_Plan($cn);


		if ( $new->isAppend() == false)
		  {
			$ret.= '<h2 class="info">'.
			  "Maximum de plan analytique est atteint".
			  "</h2>";			
		  }
		else 
		  {
			$new=new Anc_Plan($cn);
			$new->name=$_POST['pa_name'];
			$new->description=$_POST['pa_description'];
			$new->add();
		  }
	  }
	// show the detail
	if ( $sa == "pa_detail" )
	  {
		$new=new Anc_Plan($cn,$_GET['pa_id']);
		$wAction=new widget("hidden","","p_action","ca_pa");
		$wSa=new widget("HIDDEN","","sa","pa_update");

		$new->get();
   
		$ret.= '<div class="u_redcontent">';
		$ret.= '<h2 class="info">Mise &agrave; jour</h2>';
		$ret.= '<form method="post">';
		$ret.=dossier::hidden();

		$ret.= $new->form();
		$ret.= $wSa->IOValue();
		$ret.= $wAction->IOValue();
		$ret.= $wSa->Submit("submit","Enregistre");
		$ret.=sprintf('<A class="mtitle" HREF="%s">'.
					  '<input type="button" value="Efface"></A>',
					  "?p_action=ca_pa&pa_id=".$_GET['pa_id']."&sa=pa_delete&$str_dossier");

		$ret.= '</form>';
		$ret.= '</div>';

	  }
	// Update the PA
	if ( $sa == "pa_update" )
	  {
		$new=new Anc_Plan($cn,$_GET['pa_id']);
		$new->name=$_POST['pa_name'];
		$new->description=$_POST['pa_description'];
		$new->update();
		$ret='<div class="u_redcontent">';
		$ret.='<h2 class="info">Mis &agrave; jour</h2>';
		$ret.="</div>";
	  }
	// show the form for add a poste
	if ( $sa=='po_add')
	  {
		$po=new Anc_Account($cn);
		$po->pa_id=$_REQUEST['pa_id'];
		$wSa=new widget("hidden","sa","sa","po_write");
		$ret.='<div class="u_redcontent">';
		$ret.='<form method="post">';
		$ret.=dossier::hidden();
		$ret.=$po->form();
		$ret.=$wSa->IOValue();
		$ret.=$wSa->Submit("add","Ajout");
		$ret.="</div>";
	  }
	// record the poste
	if ( $sa=="po_write")
	  {
		//		var_dump($_POST);
		$po=new Anc_Account($cn);
		$po->get_from_array($_POST);
		$po->add();
		$sa="list";

	  }
	/* delete pa */
	if ( $sa == "pa_delete")
	  {
		$delete=new Anc_Plan($cn,$_GET['pa_id']);
		$delete->delete();
	  }
	/* po detail
	 *
	 */
	if ( $sa=="po_detail")
	  {
		$wHidden=new widget('hidden','','sa','po_update');
		$po=new Anc_Account($cn,$_GET['po_id']);
		$po->get_by_id();
		$ret.='<div class="u_redcontent">';
		$ret.='<form method="post">';
		$ret.=dossier::hidden();

		$ret.=$po->form();
		$ret.=$wHidden->IOValue();
		$ret.='<input type="submit" value="Correction">';
		$ret.=sprintf('<A class="mtitle" HREF="?p_action=ca_pa&sa=po_delete&po_id=%s&pa_id=%s&'.$str_dossier.'">'.
					  '<input type="button" value="Efface" onClick="return confirm(\' Voulez-vous vraiment effacer ce poste\');"></A>',
					  $po->id,
					  $_REQUEST['pa_id']
					 );

		$ret.='</form>';
		$ret.='</div>';
	  }
	if ( $sa=="po_update")
	  {
		$po=new Anc_Account($cn);
		$po->get_from_array($_POST);
		$po->update();
		$sa="list";
	  }
	if ( $sa=="po_delete")
	  {
		$po=new Anc_Account($cn,$_REQUEST['po_id']);
		$po->delete();
		$sa="list";
	  }
	/* List poste */
	if ( $sa == "list" )
	  {
		$count=0;

		$new=new Anc_Plan($cn,$_REQUEST['pa_id']);
		$new->get();
		$array=$new->get_poste_analytique();
		$ret.='<div class="u_redcontent">';
		$ret.='<table style="width:100%;border:solid blue 2px ;border-style:outset;">';
		$ret.="<tr>";
		$ret.="<th> Nom </td>";
		$ret.="<th> Montant </td>";
		$ret.="<th> Description </td>";
		$ret.="<th>Groupe</th>";
		$ret.="<th> Plan A </td>";
		$ret.="</tr>";
		$class="";
		foreach ($array as $obj) 
		  {
			$count++;
			if ( $count %2 == 0 )
			  $class="even";
			else
			  $class="odd";

			$ret.="<TR class=\"$class\">";
			$ret.="<TD>".
			  '<a class="mtitle" href="?p_action=ca_pa&sa=po_detail&po_id='.$obj->id.'&pa_id='.$_REQUEST['pa_id'].'&'.
			  $str_dossier.'">'.
			  $obj->name.
			  '</a>';
			  "</td>";
			$ret.="<TD align=\"right\">".$obj->amount."</td>";
			$ret.="<TD>".$obj->description."</td>";
			$ret.="<td>".$obj->ga_id."</td>";
			$ret.="<TD>".$new->name."</td>";
			$ret.="</tr>";

		  }
		$ret.="</table>";
		$ret.=sprintf('<A class="mtitle" HREF="?p_action=ca_pa&sa=po_add&pa_id=%s&'.$str_dossier.'">'.
					 '<input type="button" value="Ajout"></A>',
					 $_GET['pa_id']
					 );
		$ret.='</div>';

	  }

  }


//---------------------------------------------------------------------------
// Show lmenu
//
//---------------------------------------------------------------------------
$obj=new Anc_Plan($cn);
$list=$obj->get_list();




if ( empty($list)  )
  {
	echo '<div class="lmenu">';
	echo '<TABLE>';
	echo '<TR><TD class="mtitle">';
	echo '<a class="mtitle" href="?p_action=ca_pa&sa=add_pa&'.$str_dossier.'">Ajout d\'un plan comptable</a>';
	echo '</TD></TR>';
	echo '</TABLE>';
	
	echo '</div>';
	if ( ! isset ( $_REQUEST['sa']))
	  echo '<div class="u_redcontent">'.
		"Aucun plan analytique n'est d&eacute;fini".
		'</div>';

  }
 else
   {
	 echo '<div class="lmenu">';

	 echo '<table>';
	 foreach ($list as $line)
	   {
		 echo '<TR>';
		 echo '<TD >'.
		   '<a class="mtitle" href="?p_action=ca_pa&sa=pa_detail&pa_id='.$line['id'].'&'.$str_dossier.'">'.
		   $line['name'].
		   '</TD>';
		 echo '<td class="mtitle">'.
		   '<a class="mtitle" href="?p_action=ca_pa&sa=list&pa_id='.$line['id'].'&'.$str_dossier.'">'.
		   "Postes".
		   "</a>";

		 echo '</TR>';
	   }
	 echo '</table>';
	 if ($obj->isAppend()==true )
	   {
		 echo '<TABLE>';
		 echo '<TR><TD class="mtitle">';
		 echo '<a class="mtitle" href="?p_action=ca_pa&sa=add_pa&'.$str_dossier.'">Ajout d\'un plan comptable</a>';
		 echo '</TD></TR>';
		 echo '</TABLE>';
	   }


	 echo '</div>';
   }
//---------------------------------------------------------------------------
// show the u_redcontent part
//
//
//---------------------------------------------------------------------------

echo $ret;
