
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
create sequence s_user_jrn;
create sequence s_user_act;

create table user_sec_jrn (
	uj_id	integer default nextval('s_user_jrn') primary key,
	uj_login text,
	uj_jrn_id  integer references jrn_def(jrn_def_id),
	uj_priv	text

);
create table action (
	ac_id integer not null primary key,
	ac_description text not null
);
create unique index x_act on action (ac_description) ;
create table user_sec_act (
	ua_id	integer default nextval('s_user_act') primary key,
	ua_login text,
	ua_act_id	integer references action(ac_id)	
);
create unique index x_usr_jrn on user_sec_jrn (uj_login,uj_jrn_id);
insert into action (ac_id,ac_description) values (1,'Journaux');
insert into action (ac_id,ac_description) values (2,'Facturation');
insert into action (ac_id,ac_description) values (3,'Fiche');
insert into action (ac_id,ac_description) values (4,'Impression');
insert into action (ac_id,ac_description) values (5,'Formulaire');
insert into action (ac_id,ac_description) values (6,'Mise à jour Plan Comptable');
insert into action (ac_id,ac_description) values (7,'Gestion Journaux');
insert into action (ac_id,ac_description) values (8,'Paramètres');
insert into action (ac_id,ac_description) values (9,'Sécurité');
insert into action (ac_id,ac_description) values (10,'Centralise');
