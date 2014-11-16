<?php
//This file is part of NOALYSS and is under GPL 
//see licence.txt
?><fieldset>
  <legend>
<?php echo _('Informations générales')?>
  </legend>
  <div style="float:right">
    <?echo $retour;
    ?>
  </div>
<h2 class="gest_name"><?php echo $sp->input();   ?></h2>
<div style="float:left;width: 45%">


        <table >
			<tr>
            <TD>
	    <?php echo _('N° document')?>
            </TD>
            <TD style="font-weight: bolder;" >
              <?php echo $this->ag_id;?>
            </TD>
          </TR>
			 <tr>
            <TD>
	    <?php echo _('Reference')?>
            </TD>
            <TD>
              <?php echo $str_ag_ref;
              ?>
            </TD>
          </TR>

            <TD>
   <?php echo _('Date')?>
            </TD>
            <TD>
              <?php echo $date->input();
              ?>
            </TD>
          </TR>
          <TR>
            <TD>
	    <?php echo _('Heure')?>
            </TD>
            <TD>
              <?php echo $str_ag_hour;
              ?>
            </TD>
          </TR>
          <tr>
		<TR>
            <TD>
	    <?php echo _('Date limite')?>
            </TD>
            <TD>
              <?php echo $remind_date->input();
              ?>
            </TD>
          </TR>

	<tr>
          <TD>
	    <?php echo _('Destinataire')?>
          </TD>
          <TD>
  <?php echo $w->search().$w->input();
            ?>
          </td>
          </Tr>
	<tr>
          <TD>
	  <?php echo _('Contact')?>
          </TD>
          <TD>
  <?php echo $ag_contact->search().$ag_contact->input();
            ?>
          </td>
          </Tr>
	<tr>
          <TD colspan="2">
             <?php echo $spcontact->input(); ?>
          </td>
          </Tr>
        </table>
 <?php if ($p_view != 'READ') echo $str_add_button;?>

</div>
<div style="float:left;width:45%">
        <table>

          <tr>
            <TD>
	    <?php echo _('Type')?>
            </TD>
            <TD>
              <?php echo $str_doc_type;
              ?>
            </TD>
          </tr>

          <tr>
            <TD>
	    <?php echo _('Etat')?>
            </TD>
            <td>
              <?php echo $str_state;
              ?>
            <TD>
            </TD>
          </TR>
          <tr>
            <TD>
	    <?php echo _('Priorité')?>
            </TD>
            <td>
              <?php echo $str_ag_priority;
              ?>
            <TD>
            </TD>
          </TR>
          <tr>
            <TD>
	    <?php echo _('Destinataire')?>
            </TD>
            <td>
              <?php echo $str_ag_dest;?>
          </tr>
<?php if ($this->ag_id > 0 ): ?>
          <tr>
            <TD>
                Dossier / tags
            </TD>
            
            <td id="action_tag_td">
                <?php
                   $this->tag_cell();
                ?>
            </td>
          </TR>
<?php endif; ?>          
        </table>

</div>
<div style="float:right;clear:both"></div>
	<div style="float:left;width:45%">
		<h4 style="display:inline">Opérations concernées</h4>
		<ol>

		<?php
		for ($o=0;$o<count($operation);$o++)
		{
			if ( $p_view != 'READ')
				{
                                        $js  = HtmlInput::button_action_remove_operation($operation[$o]['ago_id']);
					echo '<li id="op'.$operation[$o]['ago_id'].'">'.$operation[$o]['str_date']." ".HtmlInput::detail_op($operation[$o]['jr_id'],$operation[$o]['jr_internal'])." ".h($operation[$o]['jr_comment'])." "
						.$js.'</li>';
				}
				else
				{
					echo '<li >'.$operation[$o]['str_date']." ".HtmlInput::detail_op($operation[$o]['jr_id'],$operation[$o]['jr_internal'])." ".h($operation[$o]['jr_comment'])." "
						.'</li>';
				}
		}

		?>
		</ol>
		<?php if ($p_view != 'READ')   echo '<span class="noprint">'.$iconcerned->input().'</span>';?>
	</div>

	<div style="float:left;width:45%">
		<h4 style="display:inline"><?php echo _("Actions concernées")?></h4>
		<ol>

		<?php
		$base=HtmlInput::request_to_string(array("gDossier","ac","sa","sb","sc","f_id"));
		for ($o=0;$o<count($action);$o++)
		{
			if ( $p_view != 'READ')
				{
			$rmAction=sprintf("javascript:if ( confirm('"._('Voulez-vous effacer cette action ')."')==true ) {remove_action('%s','%s','%s');}",
					dossier::id(),
					$action[$o]['ag_id'],$_REQUEST['ag_id']);
			$showAction='<a class="line" href="'.$base."&ag_id=".$action[$o]['ag_id'].'">';
			$js= '<a class="mtitle" style="color:orange" id="acact'.$action[$o]['ag_id'].'" href="'.$rmAction.'">'._("Effacer").'</a>';
			echo '<li id="act'.$action[$o]['ag_id'].'">'.$showAction.$action[$o]['str_date']." ".$action[$o]['ag_ref']." ".
					h($action[$o]['sub_title']).'('.h($action[$o]['dt_value']).')</a>'." "
				.$js.'</li>';
			} else {
				$showAction='<a class="line" href="'.$base."&ag_id=".$action[$o]['ag_id'].'">';
				echo '<li>'.$showAction.$action[$o]['str_date']." ".$action[$o]['ag_ref']." ".
					h($action[$o]['sub_title']).'('.h($action[$o]['dt_value']).')</a>'." "
				.'</li>';
			}
		}

		?>
		</ol>
		<?php if ( $p_view != 'READ') echo '<span class="noprint">'.$iaction->input().'</span>';?>
	</div>
</fieldset>
<div class="myfieldset">
  <h1 class="legend">
	    <?php echo _('Description')?>
  </h1>
  <p>
<script language="javascript">
   function enlarge(p_id_textarea){
   $(p_id_textarea).style.height=$(p_id_textarea).style.height+250+'px';
   $('bt_enlarge').style.display="none";
   $('bt_small').style.display="inline";
 }
function small(p_id_textarea){
   $('bt_enlarge').style.display="inline";
   $('bt_small').style.display="none";

   }
</script>
<?php if  ($p_view != 'NEW') : ?>
Document créé le <?php echo $this->ag_timestamp ?> par <?php echo $this->ag_owner?>
<?php endif; ?>
   <h4 class="info"><?php echo _('Titre')?></h4>
    <p style="margin-left:100">
    <?php echo $title->input();
    ?>
</p>
<h4 class="info">   <?php echo _('Commentaire')?></h4>
    <div style="margin-left:100px">
   <?php
   $style_enl='style="display:inline"';$style_small='style="display:none"';

for( $c=0;$c<count($acomment);$c++){
	if ( $p_view != 'READ')
	{
		$rmComment=sprintf("javascript:if ( confirm('"._('Voulez-vous effacer ce commentaire')." ?')==true ) {remove_comment('%s','%s');}",
						dossier::id(),
						$acomment[$c]['agc_id']);
				$js= '<a class="mtitle" style="color:red" id="accom'.$acomment[$c]['agc_id'].'" href="'.$rmComment.'">Effacer</a>';
		echo hb('n°'.$acomment[$c]['agc_id'].'('.$acomment[$c]['tech_user']." ".$acomment[$c]['str_agc_date'].')').$js.
				'<pre style="white-space: -moz-pre-wrap;white-space: pre-wrap;border:1px solid blue;width:80%;" id="com'.$acomment[$c]['agc_id'].'"> '.
				" ".h($acomment[$c]['agc_comment']).'</pre>'
				;
	}
	else
	{
		echo hb('n°'.$acomment[$c]['agc_id'].'('.$acomment[$c]['tech_user']." ".$acomment[$c]['str_agc_date'].')').
				'<pre style="white-space: -moz-pre-wrap;white-space: pre-wrap;border:1px solid blue;width:80%;" id="com'.$acomment[$c]['agc_id'].'"> '.
				" ".h($acomment[$c]['agc_comment']).'</pre>'
				;

	}
}
echo '<span class="noprint">';
echo $desc->input();
echo '</span>';
?>
<?php if ($p_view != "READ" ): ?>
<p class="noprint">
<input type="button" id="bt_enlarge" <?php echo $style_enl?> value="+" onclick="enlarge('ag_comment');return false;">
<input type="button" id="bt_small"  <?php echo $style_small?> value="-" style="display:none" onclick="small('ag_comment');return false;">
</p>
<?php endif; ?>
  </div>
</div>
<?php if ( $p_view !='READ'  ) :?>
<input type='button' class="button" class="noprint" value='Montrer articles' id="toggleButton" onclick='toggleShowDetail()'>
<?php endif; ?>
<?php
/**
 * check if there card to show,
 */
$show_row=0;
for ($i=0;$i<count($aArticle);$i++) :
	if ( ($aCard[$i] != 0 && $p_view == 'READ') || $p_view != 'READ'){ $show_row=1;break;}
endfor;
?>
<?php
/*
 * display detail if there card or if we are in UPDATE or NEW mode
 */
if ($show_row !=0 ) :

	?>
<fieldset id="fldDetail" style='display:block'>
   <LEGEND> <?php echo _('Détail des articles')?>
</LEGEND>
<?php // hidden fields
$show_row=0;
for ($i=0;$i<count($aArticle);$i++) :
	echo $aArticle[$i]['ad_id'];
	echo $aArticle[$i]['hidden_tva'];
	echo $aArticle[$i]['hidden_htva'];
	if ( ($aCard[$i] != 0 && $p_view == 'READ') || $p_view != 'READ'){ $show_row=1;}
endfor;
?>
    <div>
<table style="width:100%" id="art" >
<tr>
  <th><?php echo _('Fiche')?></th>
  <th><?php echo _('Description')?></th>
  <th><?php echo _('prix unitaire')?></th>
<th><?php echo _('quantité')?></th>
<th><?php echo _('Code TVA')?></th>
<th><?php echo _('Montant TVA')?></th>
<th><?php echo _('Montant TVAC')?></th>

</tr>
<?php for ($i=0;$i<count($aArticle);$i++): ?>
<?php
if ( ($aCard[$i] != 0 && $p_view == 'READ') || $p_view != 'READ'):
	$show_row++;
	?>
<TR>
<TD><?php echo $aArticle[$i]['fid'] ?></TD>
<TD><?php echo $aArticle[$i]['desc'] ?></TD>
<TD class="num"><?php echo $aArticle[$i]['pu'] ?></TD>
<TD class="num"><?php echo $aArticle[$i]['quant'] ?></TD>
<TD class="num"><?php echo $aArticle[$i]['tvaid'] ?></TD>
<TD class="num"><?php echo $aArticle[$i]['tva'] ?></TD>
<TD class="num"><?php echo $aArticle[$i]['tvac'] ?></TD>
</TR>
<?php endif; ?>
<?php endfor; ?>
</table>
    </div>
<script language="JavaScript">
if ( $('e_march0') && $('e_march0').value =='') { toggleShowDetail();}
function toggleShowDetail() {
	try {var detail=g('fldDetail');
	var but=g('toggleButton');
	if (detail.style.display=='block' ) { but.value="<?php echo _("Montrer les détails")?>";detail.style.display='none';}
	else { but.value="<?php echo _("Cacher les détails")?>";detail.style.display='block';} }
	catch (error)  {alert(error);}
	}

</script>
<?php if ( $show_row != 0 ): ?>
<div style="float: right; text-align: right; padding-right: 5px; font-size: 1.2em; font-weight: bold; color: blue;">
  
    <div style="float: right; text-align: left; font-size: 1.2em; font-weight: bold; color: blue;" id="sum">
    <br><span style="text-align: right" id="htva">0.0</span>
     <br><span style="text-align: right"  id="tva">0.0</span>
    <br><span style="text-align: right" id="tvac">0.0</span>
 </div>

<div style="float: right; text-align: right; padding-right: 5px; font-size: 1.2em; font-weight: bold; color: blue;">
    <br>Total HTVA
    <br>Total TVA
    <br>Total TVAC
 </div>

 <?php if ( ! $readonly ) :  ?>
    <div  style="position:float;float:right">
    <input name="act" id="act_bt" class="smallbutton" value="<?php echo _('Actualiser')?>" onclick="compute_all_ledger();" type="button">
     <input type="button" class="smallbutton" onclick="gestion_add_row()" value="<?php echo _("Ajouter une ligne")?>">
     </div>
     
<?php endif; ?>         
</div>
<?php if ( $this->ag_id != 0 && ! $readonly) : ?>
     <div  style="position:float;float:left">
         <p>
         <?php
            $query=  http_build_query(array('gDossier'=>Dossier::id(),'ag_id'=>$this->ag_id,'create_invoice'=>1,'ac'=>$menu->get('code_invoice')));
            echo HtmlInput::button_anchor(_("Transformer en facture"),"do.php?".$query,"create_invoice", '  target="_blank" ',"button");
         ?>
         </p>
      </div>
     <?php endif; ?>
<?php endif; ?>
</fieldset>
<?php endif; ?>

<?php if ($p_view != 'READ' && $str_select_doc != '') : ?>
<fieldset class="noprint" >
  <legend>
     <?php echo _('Document à générer')?>
  </legend>
  <?php echo $str_select_doc;
 echo $str_submit_generate;
  ?>
</fieldset>
<?php endif; ?>


<fieldset>
  <legend>
     <?php echo _('Pièces attachées')?>
  </legend>
  <div class="print">
      <table>
  <?php
for ($i=0;$i<sizeof($aAttachedFile);$i++) :
  ?>

      <tr>
          <td>
              <A class="print" style="display:inline" id="<?php echo "doc".$aAttachedFile[$i]['d_id'];?>" href="<?php echo $aAttachedFile[$i]['link']?>">
          <?php echo $aAttachedFile[$i]['d_filename'];?>         </a>
          </td>
          <td>
        <label> : </label>
        <span id="print_desc<?php echo $aAttachedFile[$i]['d_id'];?>"> <?php echo h($aAttachedFile[$i]['d_description'])?>
       <?php if ($p_view != 'READ') : ?> 
        <?php 
            $js=sprintf("javascript:show_description('%s')",$aAttachedFile[$i]['d_id']);
        ?>
        <a class="line"  id="<?php echo 'desc'.$aAttachedFile[$i]['d_id'];?>" onclick="<?php echo $js?>"><?php echo _("Modifier")?></a>    
        
        </span>
        </td>
        <td>
        <span class="noprint" id="input_desc<?php echo $aAttachedFile[$i]['d_id'];?>" style="display:none" >
              <input type="input" class="input_text" id="input_desc_txt<?php echo $aAttachedFile[$i]['d_id'];?>" value="<?php echo h($aAttachedFile[$i]['d_description'])?>">
              <?php 
              $js=sprintf("update_document('%s','%s')",dossier::id(),$aAttachedFile[$i]['d_id']);
              echo HtmlInput::button('save_desc'.$aAttachedFile[$i]['d_id'], _('Sauve'), 'onclick="'.$js.'"','smallbutton');
              ?>
        </span>
        <?php else: ?>
        </span>
        <?php endif;?>
<?php $rmDoc=sprintf("javascript:if ( confirm('"._('Voulez-vous effacer le document')." %s')==true ) {remove_document('%s','%s');}",
	$aAttachedFile[$i]['d_filename'],
	dossier::id(),
	$aAttachedFile[$i]['d_id']);
    ?>
        </td>
        <td>
  <?php if ($p_view != 'READ') : ?>  <a class="line"  id="<?php echo "ac".$aAttachedFile[$i]['d_id'];?>" href="<?php echo $rmDoc;?>"><?php echo _("Effacer")?></a><?php endif;?>
        </td>
  </tr>
  <?php
endfor;
  ?>
  </table>
  </div>
  <script language="javascript">
function addFiles() {
try {
	docAdded=document.getElementById('add_file');
	new_element=document.createElement('li');
	new_element.innerHTML='<input class="inp" type="file" value="" name="file_upload[]"/><br><label>Description</label><input type="input" class="input_text" name="input_desc[]" >';
	docAdded.appendChild(new_element);
}
catch(exception) { alert('<?php echo j(_('Je ne peux pas ajouter de fichier'))?>'); alert(exception.message);}
}
</script>
<?php if ($p_view != 'READ') : ?>
  <div class="noprint">
     <h3 >Fichiers à ajouter: </h3>
    <ol id='add_file'  >
      <li>
        <?php echo $upload->input();
        ?>
        <label><?php echo _('Description')?></label>
        <input type="input" class="input_text" name="input_desc[]" >
      </li>
    </ol>
  <span   >
 <input type="button" class="smallbutton" onclick="addFiles();" value="<?php echo _("Ajouter un fichier")?>">
  </span>
  </div>
 <?php endif;?>
</fieldset>
</div>
<script>compute_all_ledger()</script>