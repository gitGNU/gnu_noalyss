
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

create table theme (
	the_name	text not null,
	the_filestyle 	text ,
	the_filebutton 	text
);
 insert into theme (the_name,the_filestyle,the_filebutton)
	values ('classic','style.css',null);
 insert into theme (the_name,the_filestyle,the_filebutton)
	values ('Aqua','style-aqua.css',null);
 insert into theme (the_name,the_filestyle,the_filebutton)
	values ('Elegant','style-elegant.css',null);
 insert into theme (the_name,the_filestyle,the_filebutton)
	values ('Light','style-light.css',null);
