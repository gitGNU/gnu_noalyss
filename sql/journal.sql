

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
create sequence s_jrn_def start 5;

create sequence s_grpt;
create sequence s_jrn_op;
create sequence s_jrn;

create table jrn_type
(
	jrn_type_id	char(3) not null primary key,
	jrn_desc	text
);

create table jrn_def
(
	jrn_def_id	integer  default nextval('s_jrn_def') primary key,
	jrn_def_name	text not null unique,
	jrn_def_class_deb	text,
	jrn_def_class_cred	text,
	 jrn_def_fiche_deb text ,
	jrn_def_fiche_cred text ,
	jrn_deb_max_line	integer default 1,
	jrn_cred_max_line	integer default 1,
	jrn_def_ech		bool default false,
	jrn_def_ech_lib		text default null,
	jrn_def_type	char(3) not null references jrn_type(jrn_type_id),
	jrn_def_code	text not null

);

insert into jrn_type values ('FIN','Financier');
insert into jrn_type values ('VEN','Vente');
insert into jrn_type values ('ACH','Achat');
insert into jrn_type values ('OD','Opérations Diverses');

insert into jrn_def values (1,'Financier','5* ','5*',null,null,5,5,false,null,'FIN','FIN-01');
insert into jrn_def values (2,'Vente','7*','4*',null,null,1,3,true,'échéance','VEN','VEN-01');
insert into jrn_def values (3,'Achat','6*','4*',1,3,true,'échéance','ACH','ACH-01');
insert into jrn_def values (4,'Opération Diverses',null,null,null,null,5,5,false,null,'OD','OD-01');

create sequence s_jrnx;

create table jrn (
       jr_id  integer default nextval('s_jrn') not null ,
       jr_def_id integer not null references jrn_def(jrn_def_id),
       jr_montant float not null,
       jr_comment text ,
       jr_date date ,
       jr_grpt_id integer not null,
       jr_rapt	  integer,
       jr_internal text,
       jr_tech_date timestamp not null default now(),
       jr_tech_per integer not null

);
alter table jrn add primary key (jr_id,jr_def_id);

create table jrnx (
       j_id  int4 default nextval('s_jrn_op') primary key,
       j_date	  date default now(),
       j_montant float default 0,
       j_poste int not null references tmp_pcmn(pcm_val),
       j_grpt integer not null,
      j_rapt text,
       j_jrn_def int4 not null references jrn_def(jrn_def_id),
       j_debit bool default true,
       j_text	text,
       j_centralized bool default false,
	j_internal	text ,
       j_tech_user text not null,
       j_tech_date timestamp not null default now(),
       j_tech_per integer
);
