
/*
 *   This file is part of WCOMPTA.
 *
 *   WCOMPTA is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   WCOMPTA is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with WCOMPTA; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
create sequence s_formdef;

create sequence s_form;

create table formdef (
	fr_id	integer not null default nextval('s_formdef') primary key,
	fr_label	text

);


create table form (
	fo_id integer not null default nextval ('s_form') primary key,
	fo_fr_id	integer references formdef(fr_id),
	fo_pos	integer,
	fo_label	text,
	fo_formula	text
);

