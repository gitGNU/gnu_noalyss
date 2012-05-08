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
/* $Revision: 4301 $ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/*!\file
 * \brief show all the operation for a customer
 * the variable inherited are
 * - $cn database connexion
 * - $_REQUEST['f_id'] the f_id of the card
 * - $p_action
 * - $sub_action
 * - $ss_action
 */
require_once('class_acc_ledger.php');
$f=new Fiche($cn,$_REQUEST['f_id']);
$qcode=$f->get_quick_code();
$_GET['qcode']=$qcode;
$_REQUEST['qcode']=$qcode;
$var_array=compute_variable('ledger_type=ALL');
put_global($var_array);
require_once ('history_operation.inc.php');

