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
create index fk_jrn_action_ja_jrn_type on jrn_action(ja_jrn_type);
--
-- PostgreSQL database dump
--

\connect - phpcompta

SET search_path = public, pg_catalog;

--
-- Data for TOC entry 1 (OID 17800)
-- Name: jrn_action; Type: TABLE DATA; Schema: public; Owner: phpcompta
--
-- menu vente
INSERT INTO jrn_action VALUES (2, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'VEN');
INSERT INTO jrn_action VALUES (4, 'Voir Impayés', 'Voir toutes les factures non payées', 'user_jrn.php', 'action=voir_jrn_non_paye', 'FR', 'VEN');
INSERT INTO jrn_action VALUES (6, 'Recherche', 'Recherche dans le journal', 'user_jrn.php', 'action=search', 'FR', 'VEN');
INSERT INTO jrn_action VALUES (1, 'Nouvelle', 'Création d''une facture', 'user_jrn.php', 'action=insert_vente&blank', 'FR', 'VEN');
INSERT INTO jrn_action VALUES (5, 'Impression', 'Impression du journal', 'impress.php', 'filter=1&action=viewpdf&type=jrn', 'FR', 'VEN');

-- menu achat
INSERT INTO jrn_action VALUES (10, 'Nouveau', 'Encode un nouvel achat (matériel, marchandises, services et biens divers)', 'user_jrn.php', 'action=new&blank', 'FR', 'ACH');
INSERT INTO jrn_action VALUES (12, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'ACH');
INSERT INTO jrn_action VALUES (14, 'Voir Impayés', 'Voir toutes les factures non payées', 'user_jrn.php', 'action=voir_jrn_non_paye', 'FR', 'ACH');
INSERT INTO jrn_action VALUES (16, 'Impression', 'Impression du journal', 'impress.php', 'filter=1&action=viewpdf&type=jrn', 'FR', 'ACH');
INSERT INTO jrn_action VALUES (18, 'Recherche', 'Recherche dans le journal', 'user_jrn.php', 'action=search', 'FR', 'ACH');

-- menu banque
INSERT INTO jrn_action VALUES (20, 'Nouveau', 'Encode un nouvel achat (matériel, marchandises, services et biens divers)', 'user_jrn.php', 'action=new&blank', 'FR', 'FIN');
INSERT INTO jrn_action VALUES (22, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'FIN');
INSERT INTO jrn_action VALUES (24, 'Impression', 'Impression du journal', 'impress.php', 'filter=1&action=viewpdf&type=jrn', 'FR', 'FIN');
INSERT INTO jrn_action VALUES (26, 'Recherche', 'Recherche dans le journal', 'user_jrn.php', 'action=search', 'FR', 'FIN');

-- Menu opérations diverses
INSERT INTO jrn_action VALUES (30, 'Nouveau', null, 'user_jrn.php', 'action=new&blank', 'FR', 'OD');
INSERT INTO jrn_action VALUES (32, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'OD');
INSERT INTO jrn_action VALUES (34, 'Impression', 'Impression du journal', 'impress.php', 'filter=1&action=viewpdf&type=jrn', 'FR', 'OD');
INSERT INTO jrn_action VALUES (36, 'Recherche', 'Recherche dans le journal', 'user_jrn.php', 'action=search', 'FR', 'OD');

