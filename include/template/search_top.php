<?php

/*
 *   This file is part of NOALYSS.
 *
 *   NOALYSS is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   NOALYSS is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with NOALYSS; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */
/* $Revision$ */

// Copyright Author Dany De Bontridder danydb@aevalys.eu

/**\file
 *
 *
 * \brief
 *
 */
?>
<div style="float:right;height:10px;display:block;margin-top:2px;margin-right:2px">
<?php 
   if ($div == "search_op") {
     $callback=$_SERVER['PHP_SELF'];
     $str="recherche.php?".Dossier::get().'&ac=SEARCH';
     echo '<A id="close_div" HREF="javascript:void(0)" onclick="removeDiv(\''.$div.'\');">Fermer</A>';
   }
?>
</div>
<div>
	<?php echo h2info('Recherche')?>
</div>