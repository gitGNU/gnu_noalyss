--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: menu_ref; Type: TABLE; Schema: public; Owner: dany; Tablespace: 
--

CREATE TABLE menu_ref (
    me_code text NOT NULL,
    me_menu text,
    me_file text,
    me_url text,
    me_description text
);


ALTER TABLE public.menu_ref OWNER TO dany;

--
-- Name: COLUMN menu_ref.me_code; Type: COMMENT; Schema: public; Owner: dany
--

COMMENT ON COLUMN menu_ref.me_code IS 'Menu Code ';


--
-- Name: COLUMN menu_ref.me_menu; Type: COMMENT; Schema: public; Owner: dany
--

COMMENT ON COLUMN menu_ref.me_menu IS 'Label to display';


--
-- Name: COLUMN menu_ref.me_file; Type: COMMENT; Schema: public; Owner: dany
--

COMMENT ON COLUMN menu_ref.me_file IS 'if not empty file to include';


--
-- Name: COLUMN menu_ref.me_url; Type: COMMENT; Schema: public; Owner: dany
--

COMMENT ON COLUMN menu_ref.me_url IS 'url ';


--
-- Name: profile; Type: TABLE; Schema: public; Owner: dany; Tablespace: 
--

CREATE TABLE profile (
    p_name text NOT NULL,
    p_id integer NOT NULL,
    p_desc text
);


ALTER TABLE public.profile OWNER TO dany;

--
-- Name: TABLE profile; Type: COMMENT; Schema: public; Owner: dany
--

COMMENT ON TABLE profile IS 'Available profile ';


--
-- Name: COLUMN profile.p_name; Type: COMMENT; Schema: public; Owner: dany
--

COMMENT ON COLUMN profile.p_name IS 'Name of the profile';


--
-- Name: COLUMN profile.p_desc; Type: COMMENT; Schema: public; Owner: dany
--

COMMENT ON COLUMN profile.p_desc IS 'description of the profile';


--
-- Name: profile_menu; Type: TABLE; Schema: public; Owner: dany; Tablespace: 
--

CREATE TABLE profile_menu (
    pm_id integer NOT NULL,
    me_code text,
    me_code_dep text,
    p_id integer,
    p_order integer,
    p_type_display text NOT NULL
);


ALTER TABLE public.profile_menu OWNER TO dany;

--
-- Name: TABLE profile_menu; Type: COMMENT; Schema: public; Owner: dany
--

COMMENT ON TABLE profile_menu IS 'Join  between the profile and the menu ';


--
-- Name: COLUMN profile_menu.me_code_dep; Type: COMMENT; Schema: public; Owner: dany
--

COMMENT ON COLUMN profile_menu.me_code_dep IS 'menu code dependency';


--
-- Name: COLUMN profile_menu.p_id; Type: COMMENT; Schema: public; Owner: dany
--

COMMENT ON COLUMN profile_menu.p_id IS 'link to profile';


--
-- Name: COLUMN profile_menu.p_order; Type: COMMENT; Schema: public; Owner: dany
--

COMMENT ON COLUMN profile_menu.p_order IS 'order of displaying menu';


--
-- Name: COLUMN profile_menu.p_type_display; Type: COMMENT; Schema: public; Owner: dany
--

COMMENT ON COLUMN profile_menu.p_type_display IS 'M is a module
E is a menu';


--
-- Name: profile_menu_pm_id_seq; Type: SEQUENCE; Schema: public; Owner: dany
--

CREATE SEQUENCE profile_menu_pm_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.profile_menu_pm_id_seq OWNER TO dany;

--
-- Name: profile_menu_pm_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dany
--

ALTER SEQUENCE profile_menu_pm_id_seq OWNED BY profile_menu.pm_id;


--
-- Name: profile_menu_pm_id_seq; Type: SEQUENCE SET; Schema: public; Owner: dany
--

SELECT pg_catalog.setval('profile_menu_pm_id_seq', 79, true);


--
-- Name: profile_p_id_seq; Type: SEQUENCE; Schema: public; Owner: dany
--

CREATE SEQUENCE profile_p_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.profile_p_id_seq OWNER TO dany;

--
-- Name: profile_p_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dany
--

ALTER SEQUENCE profile_p_id_seq OWNED BY profile.p_id;


--
-- Name: profile_p_id_seq; Type: SEQUENCE SET; Schema: public; Owner: dany
--

SELECT pg_catalog.setval('profile_p_id_seq', 1, true);


--
-- Name: profile_user; Type: TABLE; Schema: public; Owner: dany; Tablespace: 
--

CREATE TABLE profile_user (
    user_name text NOT NULL,
    pu_id integer NOT NULL,
    p_id integer
);


ALTER TABLE public.profile_user OWNER TO dany;

--
-- Name: TABLE profile_user; Type: COMMENT; Schema: public; Owner: dany
--

COMMENT ON TABLE profile_user IS 'Contains the available profile for users';


--
-- Name: COLUMN profile_user.user_name; Type: COMMENT; Schema: public; Owner: dany
--

COMMENT ON COLUMN profile_user.user_name IS 'fk to available_user : login';


--
-- Name: COLUMN profile_user.p_id; Type: COMMENT; Schema: public; Owner: dany
--

COMMENT ON COLUMN profile_user.p_id IS 'fk to profile';


--
-- Name: profile_user_pu_id_seq; Type: SEQUENCE; Schema: public; Owner: dany
--

CREATE SEQUENCE profile_user_pu_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.profile_user_pu_id_seq OWNER TO dany;

--
-- Name: profile_user_pu_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dany
--

ALTER SEQUENCE profile_user_pu_id_seq OWNED BY profile_user.pu_id;


--
-- Name: profile_user_pu_id_seq; Type: SEQUENCE SET; Schema: public; Owner: dany
--

SELECT pg_catalog.setval('profile_user_pu_id_seq', 1, true);


--
-- Name: v_all_menu; Type: VIEW; Schema: public; Owner: dany
--

CREATE VIEW v_all_menu AS
    SELECT pm.me_code, pm.pm_id, pm.me_code_dep, pm.p_order, pm.p_type_display, pu.user_name, pu.pu_id, p.p_name, p.p_desc, mr.me_menu, mr.me_file, mr.me_url FROM (((profile_menu pm JOIN profile_user pu ON ((pu.p_id = pm.p_id))) JOIN profile p ON ((p.p_id = pm.p_id))) JOIN menu_ref mr USING (me_code)) ORDER BY pm.p_order;


ALTER TABLE public.v_all_menu OWNER TO dany;

--
-- Name: p_id; Type: DEFAULT; Schema: public; Owner: dany
--

ALTER TABLE profile ALTER COLUMN p_id SET DEFAULT nextval('profile_p_id_seq'::regclass);


--
-- Name: pm_id; Type: DEFAULT; Schema: public; Owner: dany
--

ALTER TABLE profile_menu ALTER COLUMN pm_id SET DEFAULT nextval('profile_menu_pm_id_seq'::regclass);


--
-- Name: pu_id; Type: DEFAULT; Schema: public; Owner: dany
--

ALTER TABLE profile_user ALTER COLUMN pu_id SET DEFAULT nextval('profile_user_pu_id_seq'::regclass);


--
-- Data for Name: menu_ref; Type: TABLE DATA; Schema: public; Owner: dany
--

INSERT INTO menu_ref VALUES ('COMPANY', 'Sociétés', 'company.inc.php', NULL, 'Parametre societe');
INSERT INTO menu_ref VALUES ('PERIODE', 'Période', 'periode.inc.php', NULL, 'Gestion des périodes');
INSERT INTO menu_ref VALUES ('DIVPARM', 'Divers', NULL, NULL, NULL);
INSERT INTO menu_ref VALUES ('CFGPAYMENT', 'Moyen de paiement', 'payment_middle.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('CFGTVA', 'TVA', 'tva.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('CFGACCOUNT', 'Poste', 'poste.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('CARD', 'Fiche', 'fiche.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('STOCK', 'Stock', 'stock.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('MISC', 'Opérations Diverses', 'compta_ods.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('HIST', 'Historique', 'user_action_gl.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('VEN', 'Vente', 'compta_ven.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('ACH', 'Achat', 'compta_ach.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('FIN', 'Financier', 'compta_fin.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('LET', 'Lettrage', 'letter.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('PREOD', 'Opérations prédéfinies', 'preod.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('VERIFBIL', 'Vérification ', 'verif_bilan.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('REPORT', 'Création de rapport', 'report.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('OPEN', 'Ecriture Ouverture', 'opening.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('ADM', 'Administration', 'adm.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('SUPPL', 'Fournisseur', 'supplier.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('FOLLOW', 'Courrier', 'action.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('FORECAST', 'Prévision', 'forecast.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('IMPJRN', 'Historique', 'impress_jrn.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('IMPREC', 'Rapprochement', 'impress_rec.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('IMPPOSTE', 'Poste', 'impress_poste.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('IMPREPORT', 'Rapport', 'impress_rapport.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('IMPBILAN', 'Bilan', 'impress_bilan.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('IMPGL', 'Grand Livre', 'impress_gl_comptes.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('IMPBAL', 'Balance', 'balance.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('IMPCARD', 'Catégorie de Fiches', 'impress_fiche.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('CUSTDET', 'Fiche', 'detail_client.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('CUSTFOLLOW', 'Suivi', 'suivi_client.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('CUSTOP', 'Opération', 'operation_client.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('CFGCARDCAT', 'Catégorie de fiche', 'fiche_def.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('CARDBAL', 'Balance', 'balance_card.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('CUST', 'Client', 'client.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('CFGCATDOC', 'Catégorie de documents', 'cat_document.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('CFGATTRIBCARD', 'Attribut de fiche', 'card_attr.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('CFGPCMN', 'Plan Comptable', 'param_pcmn.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('PARAM', 'Paramètre', NULL, NULL, NULL);
INSERT INTO menu_ref VALUES ('GESTION', 'Gestion', NULL, NULL, NULL);
INSERT INTO menu_ref VALUES ('CUSTCONTACT', 'Contact (à faire)', NULL, NULL, NULL);
INSERT INTO menu_ref VALUES ('CFGEXTENSION', 'Extension', 'extension.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('COMPTA', 'Comptabilité', NULL, NULL, NULL);
INSERT INTO menu_ref VALUES ('LOGOUT', 'Sortie', NULL, 'logout.php', NULL);
INSERT INTO menu_ref VALUES ('DASHBOARD', 'Tableau de bord', 'dashboard.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('IMP', 'Impression', NULL, NULL, NULL);
INSERT INTO menu_ref VALUES ('LETTER', 'Lettrage', NULL, NULL, NULL);
INSERT INTO menu_ref VALUES ('CFGSECURITY', 'Sécurité', 'param_sec.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('LETTERCARD', 'Let. Fiche', 'lettering.card.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('LETTERACC', 'Let. Poste', 'lettering.account.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('PREDOP', 'Ecriture prédefinie', 'preod.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('ADV', 'Avancé', NULL, NULL, NULL);
INSERT INTO menu_ref VALUES ('ANC', 'Compta Analytique', NULL, NULL, NULL);
INSERT INTO menu_ref VALUES ('CFGDOCUMENT', 'Document', 'document_modele.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('ACCESS', 'Dossier', NULL, 'user_login.php', NULL);
INSERT INTO menu_ref VALUES ('DOCUMENT', 'Document', 'document_modele.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('CFGLEDGER', 'journaux', NULL, NULL, NULL);
INSERT INTO menu_ref VALUES ('PLANANC', 'Plan Compt. analytique', 'anc_pa.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('ANCODS', 'Opérations diverses', 'anc_od.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('ANCGROUP', 'Groupe', 'anc_group.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('ANCIMP', 'Impression', 'anc_imp.inc.php', NULL, NULL);
INSERT INTO menu_ref VALUES ('PREFERENCE', 'Préférence', 'pref.inc.php', NULL, NULL);


--
-- Data for Name: profile; Type: TABLE DATA; Schema: public; Owner: dany
--

INSERT INTO profile VALUES ('default', 1, 'Profil par défaut');
INSERT INTO profile VALUES ('Analytique', 2, 'Uniquement comptabilité analytique');
INSERT INTO profile VALUES ('Vente', 3, 'Uniquement vente');


--
-- Data for Name: profile_menu; Type: TABLE DATA; Schema: public; Owner: dany
--

INSERT INTO profile_menu VALUES (1, 'COMPTA', NULL, 1, 5, 'M');
INSERT INTO profile_menu VALUES (2, 'ANC', NULL, 1, 7, 'M');
INSERT INTO profile_menu VALUES (45, 'PARAM', NULL, 1, 2, 'M');
INSERT INTO profile_menu VALUES (44, 'DASHBOARD', NULL, 1, 1, 'M');
INSERT INTO profile_menu VALUES (50, 'ANC', NULL, 2, 1, 'M');
INSERT INTO profile_menu VALUES (51, 'COMPTA', NULL, 3, 1, 'M');
INSERT INTO profile_menu VALUES (52, 'VEN', 'COMPTA', 3, 1, 'E');
INSERT INTO profile_menu VALUES (53, 'ACCESS', NULL, 1, 18, 'M');
INSERT INTO profile_menu VALUES (54, 'COMPANY', 'PARAM', 1, 1, 'E');
INSERT INTO profile_menu VALUES (55, 'PERIODE', 'PARAM', 1, 2, 'E');
INSERT INTO profile_menu VALUES (56, 'DIVPARM', 'PARAM', 1, 3, 'E');
INSERT INTO profile_menu VALUES (59, 'CFGPAYMENT', 'DIVPARM', 1, 4, 'E');
INSERT INTO profile_menu VALUES (60, 'CFGTVA', 'DIVPARM', 1, 5, 'E');
INSERT INTO profile_menu VALUES (61, 'CFGACCOUNT', 'DIVPARM', 1, 6, 'E');
INSERT INTO profile_menu VALUES (67, 'CFGCATDOC', 'DIVPARM', 1, 8, 'E');
INSERT INTO profile_menu VALUES (13, 'IMPBILAN', 'IMP', 1, 11, 'E');
INSERT INTO profile_menu VALUES (14, 'IMPCARD', 'IMP', 1, 12, 'E');
INSERT INTO profile_menu VALUES (15, 'IMPGL', 'IMP', 1, 13, 'E');
INSERT INTO profile_menu VALUES (16, 'IMPJRN', 'IMP', 1, 14, 'E');
INSERT INTO profile_menu VALUES (17, 'IMPBAL', 'IMP', 1, 15, 'E');
INSERT INTO profile_menu VALUES (68, 'CFGATTRIBCARD', 'DIVPARM', 1, 9, 'E');
INSERT INTO profile_menu VALUES (69, 'CFGPCMN', 'PARAM', 1, 4, 'E');
INSERT INTO profile_menu VALUES (70, 'CFGEXTENSION', 'PARAM', 1, 5, 'E');
INSERT INTO profile_menu VALUES (20, 'LOGOUT', NULL, 1, 19, 'M');
INSERT INTO profile_menu VALUES (71, 'CFGSECURITY', 'PARAM', 1, 6, 'E');
INSERT INTO profile_menu VALUES (72, 'PREDOP', 'PARAM', 1, 7, 'E');
INSERT INTO profile_menu VALUES (73, 'CFGDOCUMENT', 'PARAM', 1, 8, 'E');
INSERT INTO profile_menu VALUES (74, 'CFGLEDGER', 'PARAM', 1, 9, 'E');
INSERT INTO profile_menu VALUES (75, 'PLANANC', 'ANC', 1, 1, 'E');
INSERT INTO profile_menu VALUES (65, 'CFGCARDCAT', 'DIVPARM', 1, 7, 'E');
INSERT INTO profile_menu VALUES (9, 'IMPPOSTE', 'IMP', 1, 8, 'E');
INSERT INTO profile_menu VALUES (10, 'IMPREC', 'IMP', 1, 9, 'E');
INSERT INTO profile_menu VALUES (11, 'IMPREPORT', 'IMP', 1, 10, 'E');
INSERT INTO profile_menu VALUES (76, 'ANCODS', 'ANC', 1, 2, 'E');
INSERT INTO profile_menu VALUES (77, 'ANCGROUP', 'ANC', 1, 3, 'E');
INSERT INTO profile_menu VALUES (78, 'ANCIMP', 'ANC', 1, 4, 'E');
INSERT INTO profile_menu VALUES (79, 'PREFERENCE', NULL, 1, 2, 'M');
INSERT INTO profile_menu VALUES (23, 'LETTER', 'COMPTA', 1, 8, 'E');
INSERT INTO profile_menu VALUES (24, 'LETTERCARD', 'LETTER', 1, 1, 'E');
INSERT INTO profile_menu VALUES (27, 'LETTERACC', 'LETTER', 1, 2, 'E');
INSERT INTO profile_menu VALUES (37, 'CUST', 'GESTION', 1, 1, 'E');
INSERT INTO profile_menu VALUES (38, 'SUPPL', 'GESTION', 1, 2, 'E');
INSERT INTO profile_menu VALUES (39, 'ADM', 'GESTION', 1, 3, 'E');
INSERT INTO profile_menu VALUES (35, 'IMP', 'GESTION', 1, 4, 'E');
INSERT INTO profile_menu VALUES (36, 'CARD', 'GESTION', 1, 6, 'E');
INSERT INTO profile_menu VALUES (40, 'STOCK', 'GESTION', 1, 5, 'E');
INSERT INTO profile_menu VALUES (41, 'FORECAST', 'GESTION', 1, 7, 'E');
INSERT INTO profile_menu VALUES (42, 'FOLLOW', 'GESTION', 1, 8, 'E');
INSERT INTO profile_menu VALUES (29, 'VERIFBIL', 'ADV', 1, 21, 'E');
INSERT INTO profile_menu VALUES (30, 'STOCK', 'ADV', 1, 22, 'E');
INSERT INTO profile_menu VALUES (31, 'PREDOP', 'ADV', 1, 23, 'E');
INSERT INTO profile_menu VALUES (32, 'OPEN', 'ADV', 1, 24, 'E');
INSERT INTO profile_menu VALUES (33, 'REPORT', 'ADV', 1, 25, 'E');
INSERT INTO profile_menu VALUES (5, 'CARD', 'COMPTA', 1, 7, 'E');
INSERT INTO profile_menu VALUES (43, 'HIST', 'COMPTA', 1, 1, 'E');
INSERT INTO profile_menu VALUES (34, 'GESTION', NULL, 1, 6, 'M');
INSERT INTO profile_menu VALUES (4, 'VEN', 'COMPTA', 1, 2, 'E');
INSERT INTO profile_menu VALUES (3, 'ACH', 'COMPTA', 1, 3, 'E');
INSERT INTO profile_menu VALUES (19, 'FIN', 'COMPTA', 1, 4, 'E');
INSERT INTO profile_menu VALUES (18, 'MISC', 'COMPTA', 1, 5, 'E');
INSERT INTO profile_menu VALUES (6, 'IMP', 'COMPTA', 1, 6, 'E');
INSERT INTO profile_menu VALUES (28, 'ADV', 'COMPTA', 1, 20, 'E');


--
-- Data for Name: profile_user; Type: TABLE DATA; Schema: public; Owner: dany
--

INSERT INTO profile_user VALUES ('phpcompta', 1, 1);


--
-- Name: menu_ref_pkey; Type: CONSTRAINT; Schema: public; Owner: dany; Tablespace: 
--

ALTER TABLE ONLY menu_ref
    ADD CONSTRAINT menu_ref_pkey PRIMARY KEY (me_code);


--
-- Name: profile_menu_pkey; Type: CONSTRAINT; Schema: public; Owner: dany; Tablespace: 
--

ALTER TABLE ONLY profile_menu
    ADD CONSTRAINT profile_menu_pkey PRIMARY KEY (pm_id);


--
-- Name: profile_pkey; Type: CONSTRAINT; Schema: public; Owner: dany; Tablespace: 
--

ALTER TABLE ONLY profile
    ADD CONSTRAINT profile_pkey PRIMARY KEY (p_id);


--
-- Name: profile_user_pkey; Type: CONSTRAINT; Schema: public; Owner: dany; Tablespace: 
--

ALTER TABLE ONLY profile_user
    ADD CONSTRAINT profile_user_pkey PRIMARY KEY (pu_id);


--
-- Name: profile_user_user_name_key; Type: CONSTRAINT; Schema: public; Owner: dany; Tablespace: 
--

ALTER TABLE ONLY profile_user
    ADD CONSTRAINT profile_user_user_name_key UNIQUE (user_name, p_id);


--
-- Name: fki_profile_menu_menu_ref; Type: INDEX; Schema: public; Owner: dany; Tablespace: 
--

CREATE INDEX fki_profile_menu_menu_ref ON profile_menu USING btree (me_code);


--
-- Name: fki_profile_menu_profile; Type: INDEX; Schema: public; Owner: dany; Tablespace: 
--

CREATE INDEX fki_profile_menu_profile ON profile_menu USING btree (p_id);


--
-- Name: profile_menu_me_code_fkey; Type: FK CONSTRAINT; Schema: public; Owner: dany
--

ALTER TABLE ONLY profile_menu
    ADD CONSTRAINT profile_menu_me_code_fkey FOREIGN KEY (me_code) REFERENCES menu_ref(me_code);


--
-- Name: profile_menu_p_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: dany
--

ALTER TABLE ONLY profile_menu
    ADD CONSTRAINT profile_menu_p_id_fkey FOREIGN KEY (p_id) REFERENCES profile(p_id);


--
-- Name: profile_user_p_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: dany
--

ALTER TABLE ONLY profile_user
    ADD CONSTRAINT profile_user_p_id_fkey FOREIGN KEY (p_id) REFERENCES profile(p_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--
