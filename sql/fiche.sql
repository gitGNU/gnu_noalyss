-- For the fiche dev only
-- Create the sequence 
\echo recreate Sequence 
\echo try first to drop it, no worry about error messages

\echo drop SEQUENCE s_fiche
drop SEQUENCE s_fiche;
\echo CREATE SEQUENCE s_fiche
CREATE SEQUENCE s_fiche;
\echo drop sequence s_fiche_def_ref
drop sequence s_fiche_def_ref;
\echo create sequence s_fiche_def_ref
create sequence s_fiche_def_ref;
\echo drop sequence s_fdef
drop sequence s_fdef;
\echo create sequence s_fdef
create sequence s_fdef;
\echo drop  sequence s_attr_def;
drop  sequence s_attr_def;
\echo drop sequence s_attr_def;
drop sequence s_attr_def;
\echo create sequence s_attr_def
create sequence s_attr_def;
\echo drop SEQUENCE s_jnt_fic_att_value
drop SEQUENCE s_jnt_fic_att_value;
\echo CREATE SEQUENCE s_jnt_fic_att_value
CREATE SEQUENCE s_jnt_fic_att_value;

\echo Drop first the tables and after 
\echo recreate them
\echo drop table fiche_def_ref
drop table fiche_def_ref cascade;
\echo CREATE TABLE fiche_def_ref
CREATE TABLE fiche_def_ref (
    frd_id integer DEFAULT nextval('s_fiche_def_ref'::text)  primary key,
    frd_text text,
    frd_class_base integer default null
);

\echo drop TABLE fiche_def 
drop TABLE fiche_def  cascade;
\echo CREATE TABLE fiche_def
CREATE TABLE fiche_def (
    fd_id integer DEFAULT nextval('s_fdef'::text)  primary key,
    fd_class_base integer,
    fd_label text NOT NULL,
    fd_create_account bool default false,
    frd_id integer NOT NULL references fiche_def_ref(frd_id)
);

\echo drop TABLE attr_value
drop TABLE attr_value cascade;
\echo CREATE TABLE attr_value
CREATE TABLE attr_value (
    jft_id integer references jnt_fic_att_value(jft_id),
    av_text text
);

\echo drop TABLE attr_def 
drop TABLE attr_def cascade;
\echo CREATE TABLE attr_def
CREATE TABLE attr_def (
    ad_id integer DEFAULT nextval('s_attr_def'::text) primary key,
    ad_text text
);

\echo drop TABLE attr_min 
drop TABLE attr_min cascade;
\echo CREATE TABLE attr_min
CREATE TABLE attr_min (
    frd_id integer references fiche_def_ref(frd_id),
    ad_id integer references attr_def(ad_id)
);

\echo drop TABLE fiche 
drop TABLE fiche cascade;
\echo CREATE TABLE fiche
CREATE TABLE fiche (
    f_id integer DEFAULT nextval('s_fiche'::text) primary key,
    fd_id integer references fiche_def(fd_id)
);



\echo drop TABLE jnt_fic_att_value 
drop TABLE jnt_fic_att_value cascade;
\echo CREATE TABLE jnt_fic_att_value
CREATE TABLE jnt_fic_att_value (
    jft_id integer DEFAULT nextval('s_jnt_fic_att_value'::text) primary key,
    f_id integer references fiche(f_id),
    ad_id integer references attr_def(ad_id)
);
\echo drop attr_value
drop table attr_value;
\echo create table attr_value
create table attr_value (
jft_id integer references jnt_fic_att_value(jft_id),
av_text text);

\echo drop table jnt_fic_attr
drop table jnt_fic_attr
;
\echo create table jnt_fic_attr
create table jnt_fic_attr (
fd_id integer references fiche_def(fd_id),
ad_id integer references attr_def(ad_id))
;
--
-- PostgreSQL database dump
--

\connect - phpcompta

SET search_path = public, pg_catalog;

--
-- Data for TOC entry 1 (OID 21236)
-- Name: attr_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO attr_def VALUES (1, 'Nom');
INSERT INTO attr_def VALUES (2, 'Taux TVA');
INSERT INTO attr_def VALUES (3, 'Numéro de compte');
INSERT INTO attr_def VALUES (4, 'Nom de la banque');
INSERT INTO attr_def VALUES (5, 'Poste Comptable');
INSERT INTO attr_def VALUES (6, 'Prix vente');
INSERT INTO attr_def VALUES (7, 'Prix achat');
INSERT INTO attr_def VALUES (8, 'Durée Amortissement');
INSERT INTO attr_def VALUES (9, 'Description');
INSERT INTO attr_def VALUES (10, 'Date début');
INSERT INTO attr_def VALUES (11, 'Montant initial');
INSERT INTO attr_def VALUES (12, 'Personne de contact ');
INSERT INTO attr_def VALUES (13, 'numéro de tva ');
INSERT INTO attr_def VALUES (14, 'Adresse ');
INSERT INTO attr_def VALUES (15, 'code postale ');
INSERT INTO attr_def VALUES (16, 'pays ');
INSERT INTO attr_def VALUES (17, 'téléphone ');
INSERT INTO attr_def VALUES (18, 'email ');


--
-- PostgreSQL database dump
--

\connect - phpcompta

SET search_path = public, pg_catalog;

--
-- Data for TOC entry 1 (OID 21206)
-- Name: fiche_def_ref; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO fiche_def_ref VALUES (1, 'Vente Service', 700);
INSERT INTO fiche_def_ref VALUES (2, 'Marchandises achat ', 604);
-- INSERT INTO fiche_def_ref VALUES (2, 'Marchandises Vente & Achat', 604,704);
INSERT INTO fiche_def_ref VALUES (3, 'Achat Service et biens divers', 61);
INSERT INTO fiche_def_ref VALUES (4, 'Banque', 5500);
INSERT INTO fiche_def_ref VALUES (5, 'Prêt > a un an', 17);
INSERT INTO fiche_def_ref VALUES (6, 'Prêt < a un an', 430);
INSERT INTO fiche_def_ref VALUES (8, 'Fournisseurs', 440);
INSERT INTO fiche_def_ref VALUES (9, 'Clients', 400);
INSERT INTO fiche_def_ref VALUES (10, 'Salaire Administrateur', 6200);
INSERT INTO fiche_def_ref VALUES (11, 'Salaire Ouvrier', 6203);
INSERT INTO fiche_def_ref VALUES (12, 'Salaire Employé', 6202);
INSERT INTO fiche_def_ref VALUES (13, 'Dépenses non admises', 674);
INSERT INTO fiche_def_ref VALUES (7, 'Matériel à amortir', 24);

INSERT INTO fiche_def_ref VALUES (14, 'Marchandises Vente ', 704);

--
-- PostgreSQL database dump
--

\connect - phpcompta

SET search_path = public, pg_catalog;

--
-- Data for TOC entry 1 (OID 21244)
-- Name: attr_min; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO attr_min VALUES (1, 1);
INSERT INTO attr_min VALUES (1, 2);
INSERT INTO attr_min VALUES (2, 1);
INSERT INTO attr_min VALUES (2, 2);
INSERT INTO attr_min VALUES (3, 1);
INSERT INTO attr_min VALUES (3, 2);
INSERT INTO attr_min VALUES (4, 1);
INSERT INTO attr_min VALUES (4, 3);
INSERT INTO attr_min VALUES (4, 4);
INSERT INTO attr_min VALUES (4, 12);
INSERT INTO attr_min VALUES (4, 13);
INSERT INTO attr_min VALUES (4, 14);
INSERT INTO attr_min VALUES (4, 15);
INSERT INTO attr_min VALUES (4, 16);
INSERT INTO attr_min VALUES (4, 17);
INSERT INTO attr_min VALUES (4, 18);
INSERT INTO attr_min VALUES (8, 1);
INSERT INTO attr_min VALUES (8, 12);
INSERT INTO attr_min VALUES (8, 13);
INSERT INTO attr_min VALUES (8, 14);
INSERT INTO attr_min VALUES (8, 15);
INSERT INTO attr_min VALUES (8, 16);
INSERT INTO attr_min VALUES (8, 17);
INSERT INTO attr_min VALUES (8, 18);
INSERT INTO attr_min VALUES (9, 1);
INSERT INTO attr_min VALUES (9, 12);
INSERT INTO attr_min VALUES (9, 13);
INSERT INTO attr_min VALUES (9, 14);
INSERT INTO attr_min VALUES (9, 15);
INSERT INTO attr_min VALUES (9, 16);
INSERT INTO attr_min VALUES (9, 17);
INSERT INTO attr_min VALUES (9, 18);
INSERT INTO attr_min VALUES (1, 6);
INSERT INTO attr_min VALUES (1, 7);
INSERT INTO attr_min VALUES (2, 6);
INSERT INTO attr_min VALUES (2, 7);
INSERT INTO attr_min VALUES (3, 7);
INSERT INTO attr_min VALUES (14, 1);


