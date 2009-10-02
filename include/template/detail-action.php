<fieldset>
  <legend>
     Informations générales 
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
               Date 
            </TD>
            <TD>
              <?php echo $date->input(); 
              ?>
            </TD>
          </TR>
          <TR>
            <TD>
               Heure 
            </TD>
            <TD>
              <?php echo $str_ag_hour; 
              ?>
            </TD>
          </TR>
          <tr>
            <TD>
               Reference 
            </TD>
            <TD>
              <?php echo $str_ag_ref; 
              ?>
            </TD>
            </TD>
          </TR>
	<tr>
          <TD>
             Destinataire 
          </TD>
          <TD>
            <?php echo $w->input(); 
            ?>
          </td>
          </Tr>
	<tr>
          <TD>
             Contact
          </TD>
          <TD>
            <?php echo $ag_contact->input(); 
            ?>
          </td>
          </Tr>
	<tr>
          <TD colspan="2">
             <?php echo $spcontact->input(); ?>
          </td>
          </Tr>
        </table>
</div>
<div style="float:left;width:50%">
        <table>
          <tr>
            <td>
               Concerne 
            </td>
            <td>
              <?php echo $lag_ref_ag_id; 
              ?>
            </td>
          </tr>
          <tr>
            <TD>
               Type  
            </TD>
            <TD>
              <?php echo $str_doc_type; 
              ?>
            </TD>
          </tr>

          <tr>
            <TD>
               Calendrier
            </TD>
            <TD>
              <?php echo $str_ag_cal; 
              ?>
            </TD>
          </tr>
          <tr>
            <TD>
               Etat 
            </TD>
            <td>
              <?php echo $str_state; 
              ?>
            <TD>
            </TD>
          </TR>
          <tr>
            <TD>
               Priorité 
            </TD>
            <td>
              <?php echo $str_ag_priority; 
              ?>
            <TD>
            </TD>
          </TR>
          <tr>
            <TD>
               Affecté 
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
     Description 
  </legend>
  <p>
     Titre 
    <br>
    <?php echo $title->input(); 
    ?>
    <br>
     Commentaire 
    <br>
    <?php echo $desc->input(); 
    ?>
  </p>
</fieldset>
<input type='button' value='Montrer articles' id="toggleButton" onclick='toggleShowDetail()'>
<fieldset id="fldDetail" style='display:block'>
<LEGEND> Détail des articles
</LEGEND>
<?php // hidden fields
for ($i=0;$i<count($aArticle);$i++) :
	echo $aArticle[$i]['ad_id'];
	echo $aArticle[$i]['hidden_tva'];
	echo $aArticle[$i]['hidden_htva'];
endfor;
?>
<table id="art" >
<h>
<th>Fiche</th>
<th>Description</th>
<th>prix unitaire</th>
<th>quantité</th>
<th>Code TVA</th>
<th>Montant TVA</th>
<th>Montant TVAC</th>
<!-- <th>Montant TVAC</th> -->
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

<!-- <TD class="num"><?php echo $aArticle[$i]['ctva'] ?></TD>
<TD class="num"><?php echo $aArticle[$i]['htva'] ?></TD>
<TD class="num"><?php echo $aArticle[$i]['atva'] ?></TD>
<TD class="num"><?php echo $aArticle[$i]['totaltvac'] ?></TD>
-->
</TR>
<?php endfor; ?> 
</table>
<script language="JavaScript">
toggleShowDetail();
function toggleShowDetail() {
	try {var detail=g('fldDetail');
	var but=g('toggleButton');
	if (detail.style.display=='block' ) { but.value="Montrer les détails";detail.style.display='none';}
	else { but.value="Cacher les détails";detail.style.display='block';} }
	catch (error)  {alert(error);}
	}
	
</script>
<div style="float: left; text-align: right; padding-right: 5px; font-size: 1.2em; font-weight: bold; color: blue;">
  <input name="act" id="act" value="Actualiser" onclick="compute_all_purchase();" type="button">

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

</fieldset>
<fieldset >
  <legend>
     Document à générer 
  </legend>
  <?php echo $str_select_doc; 
  ?>
</fieldset>
<fieldset>
  <legend>
     Pièces attachées 
  </legend>
  <?php
for ($i=0;$i<sizeof($aAttachedFile);$i++) : 
  ?>
  <p>
    <A class="mtitle" id="<?php echo "doc".$aAttachedFile[$i]['d_id'];?>" href="<?php echo $aAttachedFile[$i]['link']?>"><?php echo $aAttachedFile[$i]['d_filename'];?>
    </A>
<?php $rmDoc=sprintf("javascript:if ( confirm('Voulez-vous effacer le document %s')==true ) {remove_document('%s','%s','%s');}",
	$aAttachedFile[$i]['d_filename'],
	$_REQUEST['PHPSESSID'],
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
catch(exception) { alert('Je ne peux pas ajouter de fichier'); alert(exception);}
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
  <input type="button" onclick="addFiles();" value="Ajouter un fichier">
</fieldset>
