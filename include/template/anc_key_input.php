<?php
/*
  Copyright (C) 2014 danydb@aevalys.eu

  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 * 
 */

/*
 * all the pa_id and analytic plan
 */
?>
<form method="post">
    <?php
    echo HtmlInput::request_to_hidden(array('gDossier', 'ac'));
    echo HtmlInput::hidden('key_id', $this->key->getp('id'));
    ?>
    <div class="content">
        <div style="width:30%;display:inline-block;min-height: 75px">
            <h1 class="legend">
                <?php echo $this->key->getp('name'); ?>
            </h1>
        </div>
        <div style="width: 65%;display:inline-block;min-height: 75px">
            <p>
                <?php echo $this->key->getp('description'); ?>
            </p>

        </div>
        <h2>
            <?php echo _('Répartition'); ?>
        </h2>
        <table class="result" style="margin-left: 8%;width:84%;margin-right:8%">
            <tr>
                <th><?php echo _('n°'); ?></th>
                <?php
                // Show all the possible analytic plan
                for ($i=0; $i<count($plan); $i++):
                    ?>
                    <th>
                        <?php echo $plan[$i]['pa_name']; ?>
                    </th>
                    <?php
                endfor;
                ?>
                <th>
                    <?php echo _('Pourcentage'); ?>
                    <?php echo HtmlInput::infobulle(41); ?>
                </th>
            </tr>
            <?php
            for ($j=0; $j<count($a_row); $j++):
                ?>
                <tr>
                    <td>
                    <?php echo $a_row[$j]['ke_row']; ?>
                        <?php echo HtmlInput::hidden('row[]', $a_row[$j]['ke_id']); ?>
                    </td>
                        <?php
                         $percent=$a_row[$j]['ke_percent'];
                        // For each plan
                        for ($i=0; $i<count($plan); $i++):
                            if ( $j == 0 ) {
                                echo HtmlInput::hidden('pa[]',$plan[$i]['pa_id']);
                            }
                            $a_poste=$cn->make_array("select po_id,po_name from poste_analytique where pa_id=$1", 1, array($plan[$i]['pa_id']));
                            $select=new ISelect('po_id['.$j.'][]');
                            $select->value=$a_poste;
                            $value=$cn->get_array('select po_id,ke_percent 
                                from key_distribution_activity as ka
                                join key_distribution_detail using (ke_id) 
                                join key_distribution using (kd_id) 
                                left join poste_analytique using(po_id)
                                
                        where ke_id=$1 and ka.pa_id=$2 ', array($a_row[$j]['ke_row'],$plan[$i]['pa_id']));
                            $selected=-1;
                            if (sizeof($value)==1)
                            {
                                $selected=$value[0]['po_id'];
                               
                            }
                            if (isset($_POST['po_id']))
                            {
                                $a_po_id=HtmlInput::default_value_post('po_id', array());
                                $selected=$a_po_id[$j][$i];
                                $a_percent=HtmlInput::default_value_post('percent', array());
                                $percent=$a_percent[$j];
                            }
                            $select->selected=$selected;
                            ?>
                        <td>
                            <?php
                            echo $select->input();
                            ?>                
                        </td>
                        <?php
                    endfor;
                    ?>
                    <td>
                        <?php
                        $inum_percent=new INum('percent[]');
                        $inum_percent->value=$percent;
                        echo $inum_percent->input();
                        ?>
                    </td>
                </tr>
                <?php
            endfor;
            ?>
            <tr>

            </tr>
        </table>


        <div>
            <div>
                <h2>
                    <?php echo _("Disponible dans les journaux "); ?>
                </h2>
            </div>
            <div style="margin-left: 8%;width:84%;margin-right:8%">

                <?php $jrn=$cn->get_array('select kl_id,jrn_def_id,jrn_def_name,jrn_def_description from jrn_def 
                                            left join key_distribution_ledger using (jrn_def_id)
                                            where kd_id=$1 or kd_id is null
                                            order by jrn_def_name ', array($this->key->getp('id')));
                ?>
                <table id="jrn_def_tb" class="result">
                    <?php for ($i=0; $i<count($jrn); $i++): ?>
                        <tr>
                            <td>
                                <?php $checkbox=new ICheckBox("jrn[]"); ?>
                                <?php $checkbox->value=$jrn[$i]['jrn_def_id']; ?>
                                <?php $checkbox->selected=($jrn[$i]['kl_id']=="")?false:true; ?>
                                <?php echo $checkbox->input(); ?>
                            </td>
                            <td>
                                <?php echo h($jrn[$i]['jrn_def_name']); ?>
                            </td>
                            <td>
                                <?php echo h($jrn[$i]['jrn_def_description']); ?>
                            </td>
                        </tr>
                    <?php endfor; ?>
                </table>
            </div>
        </div>

        <!-- end -->
    </div>
    <?php echo HtmlInput::submit('save_key', _('Sauver')); ?>
</form>