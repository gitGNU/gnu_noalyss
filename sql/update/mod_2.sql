/* $Revision$ */
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
update version set val=3;

create sequence s_jrnaction;

create table jrn_action (
       ja_id integer not null default nextval('s_jrnaction') primary key,
       ja_name text not null,
       ja_desc text,
       ja_url text not null,
   ja_action	text not null,
       ja_lang text default 'FR',
       ja_jrn_type char(3) references jrn_type(jrn_type_id)
);
insert into jrn_action (ja_name,ja_desc,ja_url,ja_action,ja_jrn_type) VALUES (
	'Nouvelle','Création d\'une facture','user_jrn.php','action=insert_vente','VEN'
);
insert into jrn_action (ja_name,ja_desc,ja_url,ja_action,ja_jrn_type) VALUES (
	'Voir','Voir toutes les factures','user_jrn.php','action=voir_vente','VEN'
);
insert into jrn_action (ja_name,ja_desc,ja_url,ja_action,ja_jrn_type) VALUES (
	'Voir Impayés','Voir toutes les factures non payées','user_jrn.php','action=voir_vente_non_paye','VEN'
);
insert into jrn_action (ja_name,ja_desc,ja_url,ja_action,ja_jrn_type) VALUES (
	'Impression','Impression du journal','user_jrn.php','action=impress','VEN'
);
insert into jrn_action (ja_name,ja_desc,ja_url,ja_action,ja_jrn_type) VALUES (
	'Recherche','Recherche dans le journal','user_jrn.php','action=search_ven','VEN'
);
