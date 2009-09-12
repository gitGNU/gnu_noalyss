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

/*!\file
 * \brief this file is used for the follow up of the customer (mail, meeting...)
 *  - p_action = client
 *  - sb = detail 
 *  - sc = sv
 *  - sd = this parameter is used here
 * 
 */

  /* By default we should a list of all the actions for this customer */
$sdAction=(isset($_REQUEST['sd']))?$_REQUEST['sd']:'ls';

/* List of all the possible action */
if ( $sdAction == 'ls' ) {
  require_once('template/list-of-operation.php');
}