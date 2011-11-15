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
 * \brief user setting
 */
date_default_timezone_set ('Europe/Brussels');
$_ENV['TMP']="/tmp";
# Complete path to pg_restore
define("PG_RESTORE","/usr/bin/pg_restore ");
define("PG_DUMP","/usr/bin/pg_dump ");
define ("PSQL",'/usr/bin/psql');
define ("phpcompta_user","phpcompta");
define ("phpcompta_password","dany");
define ("phpcompta_psql_port","5432");

//
// the domain can't start by a number !!!
define ("domaine","");
define ("LOCALE",1);