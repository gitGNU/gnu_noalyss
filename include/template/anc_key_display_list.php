<?php
/* * *
  Copyright (C) 2014 dany

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
 */
/* * *
 * @file
 * @brief display a table with the list of available keys for all ledgers
 * @see Anc_Key::display_list
 */

        
?>
<table class="result">
    <?php for ($i = 0; $i < Database::num_row($rkey); $i++):
    $onclick=  http_build_query(array(
        'gDossier'=>Dossier::id(),
        'ac'=>$_REQUEST['ac'],
        'op'=>'consult',
        'key'=>$a_key[$i]['kd_id']
    ));
    ?>
        <tr>
            <td>
                <a class="line" href="do.php?<?php echo $onclick; ?>" >
                <?php echo $a_key[$i]['kd_name']; ?>
                </a>
            </td>
            <td>
                <?php echo $a_key[$i]['kd_description'] ?>
            </td>

        </tr>
    <?php endfor; ?>
</table>