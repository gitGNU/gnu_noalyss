
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

create sequence users_id;
create table ac_users (
	use_id	int not null default nextval('users_id'),
	use_first_name	text null, 
	use_name 	text null ,
	use_login text not null unique,
	use_active 	integer default 0,
	use_pass	text,
	use_admin integer default 0,
	use_theme text default 'Light',
	use_usertype	text not null,
	check ( use_active=0 or use_active=1 ),
	primary key (use_id)
);

insert into ac_users (use_login, use_active,use_pass,use_usertype,use_admin) values (user,1,
'b1cc88e1907cde80cb2595fa793b3da9','compta',1);



