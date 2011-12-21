<fieldset>
  <legend>
<?=_('Informations générales')?>
  </legend>
  <div style="float:right">
    <?echo $retour;
    ?>
  </div>
<div style="float:left;width:50%">
<em> <strong><?php echo $sp->input();   ?></strong></em>

        <table width="50%">
          <TR>
            <TD>
   <?=_('Date')?>
            </TD>
            <TD>
              <?php echo $date->input();
              ?>
            </TD>
          </TR>
          <TR>
            <TD>
	    <?=_('Heure')?>
            </TD>
            <TD>
              <?php echo $str_ag_hour;
              ?>
            </TD>
          </TR>
          <tr>
            <TD>
	    <?=_('Reference')?>
            </TD>
            <TD>
              <?php echo $str_ag_ref;
              ?>
            </TD>
          </TR>
	<tr>
          <TD>
	    <?=_('Destinataire')?>
          </TD>
          <TD>
  <?php echo $w->search().$w->input();
            ?>
          </td>
          </Tr>
	<tr>
          <TD>
	  <?=_('Contact')?>
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
 <?echo $str_add_button;?>

</div>
<div style="float:left;width:50%">
        <table>
          <tr>
            <td>
   <?=_('Concerne')?>
            </td>
            <td>
              <?php echo $lag_ref_ag_id;
              ?>
            </td>
          </tr>
          <tr>
            <TD>
	    <?=_('Type')?>
            </TD>
            <TD>
              <?php echo $str_doc_type;
              ?>
            </TD>
          </tr>

          <tr>
            <TD>
	    <?=_('Calendrier')?>
            </TD>
            <TD>
              <?php echo $str_ag_cal;
              ?>
            </TD>
          </tr>
          <tr>
            <TD>
	    <?=_('Etat')?>
            </TD>
            <td>
              <?php echo $str_state;
              ?>
            <TD>
            </TD>
          </TR>
          <tr>
            <TD>
	    <?=_('Priorité')?>
            </TD>
            <td>
              <?php echo $str_ag_priority;
              ?>
            <TD>
            </TD>
          </TR>
          <tr>
            <TD>
	    <?=_('Affecté')?>
            </TD>
            <td>
              <?php echo $str_ag_dest;?>
            <TD>
            </TD>
          </TR>
        </table>

</div>

</fieldset>
<fieldset>
  <legend>
	    <?=_('Description')?>
  </legend>
  <p>
<script language="javascript">
   function enlarge(p_id_textarea){
   $(p_id_textarea).rows=40; $(p_id_textarea).cols=120;
   $('bt_enlarge').style.display="none";
   $('bt_small').style.display="inline";
 }
function small(p_id_textarea){
   $(p_id_textarea).rows=5; $(p_id_textarea).cols=70;
   $('bt_enlarge').style.display="inline";
   $('bt_small').style.display="none";

   }
</script>

   <h4 class="info"><?=_('Titre')?></h4>
    <p style="margin-left:100">
    <?php echo $title->input();
    ?>
</p>
<h4 class="info">   <?=_('Commentaire')?></h4>
    <div style="margin-left:100">
    <?php echo $desc->input();
$style_enl='style="display:inline"';$style_small='style="display:none"';
if (strlen($desc->value)>300) {$style_enl='style="display:none"';$style_small='style="display:inline"';}
?>
<input type="button" id="bt_enlarge" <?=$style_enl?> value="+" onclick="enlarge('ag_comment');return false;">
<input type="button" id="bt_small"  <?=$style_small?> value="-" style="display:none" onclick="small('ag_comment');return false;">
  </div>
</fieldset>
<input type='button' class="button" value='Montrer articles' id="toggleButton" onclick='toggleShowDetail()'>
<fieldset id="fldDetail" style='display:block'>
   <LEGEND> <?=_('Détail des articles')?>
</LEGEND>
<?php // hidden fields
for ($i=0;$i<count($aArticle);$i++) :
	echo $aArticle[$i]['ad_id'];
	echo $aArticle[$i]['hidden_tva'];
	echo $aArticle[$i]['hidden_htva'];
endfor;
?>
<table id="art" >
<tr>
  <th><?=_('Fiche')?></th>
  <th><?=_('Description')?></th>
  <th><?=_('prix unitaire')?></th>
<th><?=_('quantité')?></th>
<th><?=_('Code TVA')?></th>
<th><?=_('Montant TVA')?></th>
<th><?=_('Montant TVAC')?></th>

</tr>

<?for ($i=0;$i<count($aArticle);$i++): ?>
<TR>
<TD><?php echo $aArticle[$i]['fid'] ?></TD>
<TD><?php echo $aArticle[$i]['desc'] ?></TD>
<TD class="num"><?php echo $aArticle[$i]['pu'] ?></TD>
<TD class="num"><?php echo $aArticle[$i]['quant'] ?></TD>
<TD class="num"><?php echo $aArticle[$i]['tvaid'] ?></TD>
<TD class="num"><?php echo $aArticle[$i]['tva'] ?></TD>
<TD class="num"><?php echo $aArticle[$i]['tvac'] ?></TD>

</TR>
<?php endfor; ?>
</table>
<script language="JavaScript">
if ( $('e_march0').value =='') { toggleShowDetail();}
function toggleShowDetail() {
	try {var detail=g('fldDetail');
	var but=g('toggleButton');
	if (detail.style.display=='block' ) { but.value="<?=_("Montrer les détails")?>";detail.style.display='none';}
	else { but.value="<?=_("Cacher les détails")?>";detail.style.display='block';} }
	catch (error)  {alert(error);}
	}

</script>
<div style="float: left; text-align: right; padding-right: 5px; font-size: 1.2em; font-weight: bold; color: blue;">
  <input name="act" id="act" class="button" value="<?=_('Actualiser')?>" onclick="compute_all_ledger();" type="button">

    <div style="float: right; text-align: left; font-size: 1.2em; font-weight: bold; color: blue;" id="sum">
    <br><span id="htva">0.0</span>
     <br><span id="tva">0.0</span>
    <br><span id="tvac">0.0</span>
 </div>

<div style="float: left; text-align: right; padding-right: 5px; font-size: 1.2em; font-weight: bold; color: blue;">
    <br>Total HTVA
    <br>Total TVA
    <br>Total TVAC
 </div>
</div>

</fieldset>
<fieldset >
  <legend>
     <?=_('Document à générer')?>
  </legend>
  <?php echo $str_select_doc;
echo $str_submit_generate;
  ?>
</fieldset>
<fieldset>
  <legend>
     <?=_('Pièces attachées')?>
  </legend>
  <?php
for ($i=0;$i<sizeof($aAttachedFile);$i++) :
  ?>
  <p>
    <A class="mtitle" id="<?php echo "doc".$aAttachedFile[$i]['d_id'];?>" href="<?php echo $aAttachedFile[$i]['link']?>"><?php echo $aAttachedFile[$i]['d_filename'];?>
    </A>
<?php $rmDoc=sprintf("javascript:if ( confirm('"._('Voulez-vous effacer le document')." %s')==true ) {remove_document('%s','%s');}",
	$aAttachedFile[$i]['d_filename'],
	dossier::id(),
	$aAttachedFile[$i]['d_id']);
    ?>
    <a class="mtitle" id="<?php echo "ac".$aAttachedFile[$i]['d_id'];?>" href="<?php echo $rmDoc;?>">Effacer</a>
  </p>
  <?php
endfor;
  ?>
  <script language="javascript">
function addFiles() {
try {
	docAdded=document.getElementById('add_file');
	new_element=document.createElement('li');
	new_element.innerHTML='<input class="inp" type="file" value="" name="file_upload[]"/>';
	docAdded.appendChild(new_element);
}
catch(exception) { alert('<?=j(_('Je ne peux pas ajouter de fichier'))?>'); alert(exception.message);}
}
</script>
  <p>
     <strong>Fichiers à ajouter: </strong>
    <ol id='add_file'>
      <li>
        <?php echo $upload->input();
        ?>
      </li>
    </ol>
  </p>
  <input type="button" class="button" onclick="addFiles();" value="Ajouter un fichier">
</fieldset>
