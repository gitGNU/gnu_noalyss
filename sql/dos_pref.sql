/* $Revision$ */
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

create sequence s_periode;
create sequence s_currency;

create table parm_money (
	pm_id	integer default nextval('s_currency'),
       pm_code		  char(3) primary key,
       pm_rate		  double precision
);

create table parm_periode (
       p_id  integer default nextval('s_periode') primary key,
       p_start		  date not null unique,
       p_end		  date,
       p_exercice	 text not null default to_char(now(),'YYYY'),
       p_closed		  boolean default false
);

insert into parm_money (pm_code,pm_rate) values ('EUR ',1);
set datestyle='european';

insert into parm_periode  (p_start,p_end,p_exercice) values ('01-01-2003','31-01-2003','2003');
insert into parm_periode  (p_start,p_end,p_exercice) values ('01-02-2003','28-02-2003','2003');
insert into parm_periode  (p_start,p_end,p_exercice) values ('01-03-2003','31-03-2003','2003');
insert into parm_periode  (p_start,p_end,p_exercice) values ('01-04-2003','30-04-2003','2003');
insert into parm_periode  (p_start,p_end,p_exercice) values ('01-05-2003','31-05-2003','2003');
insert into parm_periode  (p_start,p_end,p_exercice) values ('01-06-2003','30-06-2003','2003');
insert into parm_periode  (p_start,p_end,p_exercice) values ('01-07-2003','31-07-2003','2003');
insert into parm_periode  (p_start,p_end,p_exercice) values ('01-08-2003','31-08-2003','2003');
insert into parm_periode  (p_start,p_end,p_exercice) values ('01-09-2003','30-09-2003','2003');
insert into parm_periode  (p_start,p_end,p_exercice) values ('01-10-2003','30-10-2003','2003');
insert into parm_periode  (p_start,p_end,p_exercice) values ('01-11-2003','30-11-2003','2003');
insert into parm_periode  (p_start,p_end,p_exercice) values ('01-12-2003','31-12-2003','2003');
insert into parm_periode  (p_start,p_exercice) values ('31-12-2003','2003');
