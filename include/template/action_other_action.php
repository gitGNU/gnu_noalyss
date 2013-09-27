
    
<?php                
    echo HtmlInput::button("other_bt", "Autres actions", 'onclick="$(\'other_div\').style.display=\'block\';action_show_checkbox();"', "button"); 
    $radio=new IRadio("othact");
   
/*
 * Hidden values for a previous search
 */
echo HtmlInput::request_to_hidden(array("closed_action","remind_date_end","remind_date","sag_ref", "remind_date","only_internal", "state", "gDossier", "qcode", "start_date", "end_date", "ag_id", "ag_dest_query",
		"tdoc",   "query","date_start","date_end","hsstate","searchtag"));
?>
<div id="other_div" class="inner_box" style="width:40%;display: none">
    <?php echo HtmlInput::title_box('Actions sur plusieurs documents','other_div', 'hide') ?>
    Sélectionner les documents et l' action :
    <ul style='list-style-type: none;padding-left:30px;margin: 0px' >
        <li >
            <?php $radio->value="IMP"; $radio->selected=true;echo $radio->input(); ?>
            Impression 
        </li>
        <li>
            <?php $radio->value="ST";$radio->selected=false;echo $radio->input(); ?>
            Changement des états
            <?php
                $etat=new ISelect('ag_state');
                $etat->value=$cn->make_array('select s_id,s_value from document_state order by s_value');
                echo $etat->input();
            ?>
        </li>
        <li>
            <?php $radio->value="ETIADD";echo $radio->input(); ?>
            Ajout d'étiquettes
            <?php echo Tag::button_search('add'); ?>
            <?php echo Tag::add_clear_button('add'); ?>
				<span id="addtag_choose_td">
                                </span>
        </li>
        <li>
            <?php $radio->value="ETIREM";echo $radio->input(); ?>
            Enlever des étiquettes
            <?php echo Tag::button_search('rem'); ?>
            <?php echo Tag::add_clear_button('rem'); ?>
				<span id="remtag_choose_td">
                                </span>
        </li>
        <li>
            <?php $radio->value="ETICLEAR";echo $radio->input(); ?>
            Enlever toutes les étiquettes des documents choisis
        </li>
         <li>
            <?php $radio->value="DOCREM";echo $radio->input(); ?>
            Effacer les documents choisis
        </li>
    </ul>
        
<?php
    echo HtmlInput::submit("other_action_bt", "Valider");
?>
</div>
<script>
    new Draggable('other_div',{starteffect:function(){
                                    new Effect.Highlight(obj.id,{scroll:window,queue:'end'});
                                  }
                              }
                         );
</script>
