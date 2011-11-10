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
    me_description text,
    me_parameter text,
    me_javascript text,
    me_type character varying(2)
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
-- Name: COLUMN menu_ref.me_type; Type: COMMENT; Schema: public; Owner: dany
--

COMMENT ON COLUMN menu_ref.me_type IS 'ME for menu
PR for Printing
SP for special meaning (ex: return to line)
PL for plugin';


--
-- Name: profile; Type: TABLE; Schema: public; Owner: dany; Tablespace: 
--

CREATE TABLE profile (
    p_name text NOT NULL,
    p_id integer NOT NULL,
    p_desc text,
    with_calc boolean DEFAULT true,
    with_direct_form boolean DEFAULT true
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
-- Name: COLUMN profile.with_calc; Type: COMMENT; Schema: public; Owner: dany
--

COMMENT ON COLUMN profile.with_calc IS 'show the calculator';


--
-- Name: COLUMN profile.with_direct_form; Type: COMMENT; Schema: public; Owner: dany
--

COMMENT ON COLUMN profile.with_direct_form IS 'show the direct form';


--
-- Name: profile_menu; Type: TABLE; Schema: public; Owner: dany; Tablespace: 
--

CREATE TABLE profile_menu (
    pm_id integer NOT NULL,
    me_code text,
    me_code_dep text,
    p_id integer,
    p_order integer,
    p_type_display text NOT NULL,
    pm_default integer
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
E is a menu
S is a select (for plugin)';


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

SELECT pg_catalog.setval('profile_menu_pm_id_seq', 302, true);


--
-- Name: profile_menu_type; Type: TABLE; Schema: public; Owner: dany; Tablespace: 
--

CREATE TABLE profile_menu_type (
    pm_type text NOT NULL,
    pm_desc text
);


ALTER TABLE public.profile_menu_type OWNER TO dany;

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

SELECT pg_catalog.setval('profile_p_id_seq', 7, true);


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
    SELECT pm.me_code, pm.pm_id, pm.me_code_dep, pm.p_order, pm.p_type_display, pu.user_name, pu.pu_id, p.p_name, p.p_desc, mr.me_menu, mr.me_file, mr.me_url, mr.me_parameter, mr.me_javascript, mr.me_type FROM (((profile_menu pm JOIN profile_user pu ON ((pu.p_id = pm.p_id))) JOIN profile p ON ((p.p_id = pm.p_id))) JOIN menu_ref mr USING (me_code)) ORDER BY pm.p_order;


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

INSERT INTO menu_ref VALUES ('IMPCARD', 'Import Fiche', 'import_card/index.php', NULL, 'Importation de fiches', 'plugin_code=IMPCARD', NULL, 'PL');
INSERT INTO menu_ref VALUES ('AMORTIS', 'Amortissement', 'amortis/index.php', NULL, 'Amortissement', 'plugin_code=AMORTIS', NULL, 'PL');
INSERT INTO menu_ref VALUES ('TOOLPLAN', 'Import/export plan', 'tool_pcmn/index.php', NULL, 'Importation /export de plan comptable', 'plugin_code=TOOLPLAN', NULL, 'PL');
INSERT INTO menu_ref VALUES ('IMPORTBANK', 'Importation banque', 'importbank/index.php', NULL, 'Import. fichier CVS de la banque', 'plugin_code=IMPORTBANK', NULL, 'PL');
INSERT INTO menu_ref VALUES ('TOOL', 'Outil comptable', 'tools/index.php', NULL, 'Outil comptable', 'plugin_code=TOOL', NULL, 'PL');
INSERT INTO menu_ref VALUES ('TVA', 'Module de TVA', 'tva/index.php', NULL, 'Cette extension permet de faire les listings et declarations TVA', 'plugin_code=TVA', NULL, 'PL');
INSERT INTO menu_ref VALUES ('TEST', 'Cela va marcher', 'amortis/index.php', NULL, 'Test description', '0', NULL, 'PL');
INSERT INTO menu_ref VALUES ('ACH', 'Achat', 'compta_ach.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('CSV:AncBalGroup', 'Export Balance groupe analytique', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('OTH:Bilan', 'Export Bilan', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('PDF:ledger', 'Export Journaux', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('CSV:postedetail', 'Export Poste détail', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('PDF:postedetail', 'Export Poste détail', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('CSV:fichedetail', 'Export Fiche détail', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('CFGMENU', 'Config. Menu', 'menu.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('MODULARITY', 'Menu et profile', NULL, NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('CFGPROFILE', 'Profile', 'profile.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('TESTTVA', 'Test TVA', 'tva/index.php', NULL, 'Test description', 'plugin_code=TESTTVA', NULL, 'PL');
INSERT INTO menu_ref VALUES ('SEARCH', 'Recherche', NULL, NULL, NULL, NULL, 'popup_recherche()', 'ME');
INSERT INTO menu_ref VALUES ('COMPANY', 'Sociétés', 'company.inc.php', NULL, 'Parametre societe', NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('PERIODE', 'Période', 'periode.inc.php', NULL, 'Gestion des périodes', NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('DIVPARM', 'Divers', NULL, NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('CFGPAYMENT', 'Moyen de paiement', 'payment_middle.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('CFGTVA', 'TVA', 'tva.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('CFGACCOUNT', 'Poste', 'poste.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('CARD', 'Fiche', 'fiche.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('STOCK', 'Stock', 'stock.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('VEN', 'Vente', 'compta_ven.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('PDF:fichedetail', 'Export Fiche détail', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('CSV:fiche_balance', 'Export Fiche balance', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('ODSIMP', 'Historique opérations diverses', 'history_operation.inc.php', NULL, NULL, 'ledger_type=ODS', NULL, 'ME');
INSERT INTO menu_ref VALUES ('LET', 'Lettrage', 'letter.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('PREOD', 'Opérations prédéfinies', 'preod.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('VERIFBIL', 'Vérification ', 'verif_bilan.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('REPORT', 'Création de rapport', 'report.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('OPEN', 'Ecriture Ouverture', 'opening.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('ACHIMP', 'Historique achat', 'history_operation.inc.php', NULL, NULL, 'ledger_type=ACH', NULL, 'ME');
INSERT INTO menu_ref VALUES ('SUPPL', 'Fournisseur', 'supplier.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('FOLLOW', 'Courrier', 'action.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('FORECAST', 'Prévision', 'forecast.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('LETTER', 'Lettrage', NULL, NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('CFGSECURITY', 'Sécurité', 'param_sec.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('LETTERCARD', 'Let. Fiche', 'lettering.card.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('LETTERACC', 'Let. Poste', 'lettering.account.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('PREDOP', 'Ecriture prédefinie', 'preod.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('ADV', 'Avancé', NULL, NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('ANC', 'Compta Analytique', NULL, NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('CFGDOCUMENT', 'Document', 'document_modele.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('DOCUMENT', 'Document', 'document_modele.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('PLANANC', 'Plan Compt. analytique', 'anc_pa.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('ANCODS', 'Opérations diverses', 'anc_od.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('ANCGROUP', 'Groupe', 'anc_group.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('ANCIMP', 'Impression', 'anc_imp.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('VENMENU', 'Vente / Recette', NULL, NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('PREFERENCE', 'Préférence', 'pref.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('HIST', 'Historique', 'history_operation.inc.php', NULL, NULL, 'ledger_type=ALL', NULL, 'ME');
INSERT INTO menu_ref VALUES ('MENUFIN', 'Financier', NULL, NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('EXTENSION', 'Extension', 'extension_choice.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('MENUACH', 'Achat', NULL, NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('MENUODS', 'Opérations diverses', NULL, NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('ODS', 'Opérations Diverses', 'compta_ods.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('FINIMP', 'Historique financier', 'history_operation.inc.php', NULL, NULL, 'ledger_type=FIN', NULL, 'ME');
INSERT INTO menu_ref VALUES ('ADM', 'Administration', 'adm.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('FIN', 'Nouvel extrait', 'compta_fin.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('FINSALDO', 'Soldes', 'compta_fin_saldo.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('FINREC', 'Rapprochement', 'compta_fin_rec.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('ACCESS', 'Dossier', NULL, 'user_login.php', NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('JSSEARCH', 'Recherche', NULL, NULL, NULL, NULL, 'search_reconcile()', 'ME');
INSERT INTO menu_ref VALUES ('CFGLEDGER', 'journaux', 'cfgledger.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('CUSTDET', 'Fiche', 'detail_client.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('CUSTFOLLOW', 'Suivi', 'suivi_client.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('CUSTOP', 'Opération', 'operation_client.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('CFGCARDCAT', 'Catégorie de fiche', 'fiche_def.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('CARDBAL', 'Balance', 'balance_card.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('CUST', 'Client', 'client.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('CFGCATDOC', 'Catégorie de documents', 'cat_document.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('VENIMP', 'Historique vente', 'history_operation.inc.php', NULL, NULL, 'ledger_type=VEN', NULL, 'ME');
INSERT INTO menu_ref VALUES ('CFGATTRIBCARD', 'Attribut de fiche', 'card_attr.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('CFGPCMN', 'Plan Comptable', 'param_pcmn.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('PARAM', 'Paramètre', NULL, NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('GESTION', 'Gestion', NULL, NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('CUSTCONTACT', 'Contact (à faire)', NULL, NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('CFGEXTENSION', 'Extension', 'extension.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('COMPTA', 'Comptabilité', NULL, NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('LOGOUT', 'Sortie', NULL, 'logout.php', NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('DASHBOARD', 'Tableau de bord', 'dashboard.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('PDF:fiche_balance', 'Export Fiche balance', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('CSV:report', 'Export report', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('PDF:report', 'Export report', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('CSV:fiche', 'Export Fiche', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('PDF:fiche', 'Export Fiche', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('CSV:glcompte', 'Export Grand Livre', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('PDF:glcompte', 'Export Grand Livre', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('PDF:sec', 'Export Sécurité', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('CSV:AncList', 'Export Comptabilité analytique', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('CSV:AncBalSimple', 'Export Comptabilité analytique balance simple', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('PDF:AncBalSimple', 'Export Comptabilité analytique', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('CSV:AncBalDouble', 'Export Comptabilité analytique balance double', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('PDF:AncBalDouble', 'Export Comptabilité analytique balance double', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('CSV:balance', 'Export Balance comptable', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('PDF:balance', 'Export Balance comptable', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('CSV:histo', 'Export Historique', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('CSV:ledger', 'Export Journaux', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('CSV:AncTable', 'Export Tableau Analytique', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('CSV:AncAccList', 'Export Historique Compt. Analytique', NULL, NULL, NULL, NULL, NULL, 'PR');
INSERT INTO menu_ref VALUES ('new_line', NULL, NULL, NULL, 'Saut de ligne', NULL, NULL, 'SP');
INSERT INTO menu_ref VALUES ('PRINTJRN', 'Historique', 'impress_jrn.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('PRINTREC', 'Rapprochement', 'impress_rec.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('PRINTPOSTE', 'Poste', 'impress_poste.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('PRINTREPORT', 'Rapport', 'impress_rapport.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('PRINTBILAN', 'Bilan', 'impress_bilan.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('PRINTGL', 'Grand Livre', 'impress_gl_comptes.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('PRINTBAL', 'Balance', 'balance.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('PRINTCARD', 'Catégorie de Fiches', 'impress_fiche.inc.php', NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('PRINT', 'Impression', NULL, NULL, NULL, NULL, NULL, 'ME');
INSERT INTO menu_ref VALUES ('MODOP', 'Modification d''opérations', 'modop/index.php', NULL, 'Modification opérations', 'plugin_code=MODOP', NULL, 'PL');


--
-- Data for Name: profile; Type: TABLE DATA; Schema: public; Owner: dany
--

INSERT INTO profile VALUES ('Administrateur', 1, 'Profil par défaut pour les adminstrateurs', true, true);
INSERT INTO profile VALUES ('Utilisateur', 6, 'Profil par défaut pour les Utilisateurs', true, true);


--
-- Data for Name: profile_menu; Type: TABLE DATA; Schema: public; Owner: dany
--

INSERT INTO profile_menu VALUES (54, 'COMPANY', 'PARAM', 1, 1, 'E', 0);
INSERT INTO profile_menu VALUES (173, 'COMPTA', NULL, 1, 40, 'M', NULL);
INSERT INTO profile_menu VALUES (55, 'PERIODE', 'PARAM', 1, 2, 'E', 0);
INSERT INTO profile_menu VALUES (56, 'DIVPARM', 'PARAM', 1, 3, 'E', 0);
INSERT INTO profile_menu VALUES (59, 'CFGPAYMENT', 'DIVPARM', 1, 4, 'E', 0);
INSERT INTO profile_menu VALUES (60, 'CFGTVA', 'DIVPARM', 1, 5, 'E', 0);
INSERT INTO profile_menu VALUES (61, 'CFGACCOUNT', 'DIVPARM', 1, 6, 'E', 0);
INSERT INTO profile_menu VALUES (67, 'CFGCATDOC', 'DIVPARM', 1, 8, 'E', 0);
INSERT INTO profile_menu VALUES (175, 'COMPTA', NULL, 6, 40, 'M', NULL);
INSERT INTO profile_menu VALUES (68, 'CFGATTRIBCARD', 'DIVPARM', 1, 9, 'E', 0);
INSERT INTO profile_menu VALUES (69, 'CFGPCMN', 'PARAM', 1, 4, 'E', 0);
INSERT INTO profile_menu VALUES (70, 'CFGEXTENSION', 'PARAM', 1, 5, 'E', 0);
INSERT INTO profile_menu VALUES (178, 'CFGPAYMENT', 'DIVPARM', 6, 4, 'E', 0);
INSERT INTO profile_menu VALUES (179, 'CFGTVA', 'DIVPARM', 6, 5, 'E', 0);
INSERT INTO profile_menu VALUES (180, 'CFGACCOUNT', 'DIVPARM', 6, 6, 'E', 0);
INSERT INTO profile_menu VALUES (181, 'CFGCATDOC', 'DIVPARM', 6, 8, 'E', 0);
INSERT INTO profile_menu VALUES (71, 'CFGSECURITY', 'PARAM', 1, 6, 'E', 0);
INSERT INTO profile_menu VALUES (182, 'CFGATTRIBCARD', 'DIVPARM', 6, 9, 'E', 0);
INSERT INTO profile_menu VALUES (189, 'PLANANC', 'ANC', 6, 1, 'E', 0);
INSERT INTO profile_menu VALUES (190, 'CFGCARDCAT', 'DIVPARM', 6, 7, 'E', 0);
INSERT INTO profile_menu VALUES (191, 'ANCODS', 'ANC', 6, 2, 'E', 0);
INSERT INTO profile_menu VALUES (192, 'ANCGROUP', 'ANC', 6, 3, 'E', 0);
INSERT INTO profile_menu VALUES (193, 'ANCIMP', 'ANC', 6, 4, 'E', 0);
INSERT INTO profile_menu VALUES (194, 'LETTER', 'COMPTA', 6, 8, 'E', 0);
INSERT INTO profile_menu VALUES (196, 'PREFERENCE', NULL, 6, 15, 'M', 1);
INSERT INTO profile_menu VALUES (197, 'LETTERCARD', 'LETTER', 6, 1, 'E', 0);
INSERT INTO profile_menu VALUES (72, 'PREDOP', 'PARAM', 1, 7, 'E', 0);
INSERT INTO profile_menu VALUES (198, 'LETTERACC', 'LETTER', 6, 2, 'E', 0);
INSERT INTO profile_menu VALUES (199, 'CUST', 'GESTION', 6, 1, 'E', 0);
INSERT INTO profile_menu VALUES (200, 'SUPPL', 'GESTION', 6, 2, 'E', 0);
INSERT INTO profile_menu VALUES (201, 'ADM', 'GESTION', 6, 3, 'E', 0);
INSERT INTO profile_menu VALUES (202, 'CARD', 'GESTION', 6, 6, 'E', 0);
INSERT INTO profile_menu VALUES (73, 'CFGDOCUMENT', 'PARAM', 1, 8, 'E', 0);
INSERT INTO profile_menu VALUES (203, 'STOCK', 'GESTION', 6, 5, 'E', 0);
INSERT INTO profile_menu VALUES (204, 'FORECAST', 'GESTION', 6, 7, 'E', 0);
INSERT INTO profile_menu VALUES (205, 'FOLLOW', 'GESTION', 6, 8, 'E', 0);
INSERT INTO profile_menu VALUES (74, 'CFGLEDGER', 'PARAM', 1, 9, 'E', 0);
INSERT INTO profile_menu VALUES (206, 'VERIFBIL', 'ADV', 6, 21, 'E', 0);
INSERT INTO profile_menu VALUES (207, 'STOCK', 'ADV', 6, 22, 'E', 0);
INSERT INTO profile_menu VALUES (208, 'PREDOP', 'ADV', 6, 23, 'E', 0);
INSERT INTO profile_menu VALUES (75, 'PLANANC', 'ANC', 1, 1, 'E', 0);
INSERT INTO profile_menu VALUES (209, 'OPEN', 'ADV', 6, 24, 'E', 0);
INSERT INTO profile_menu VALUES (65, 'CFGCARDCAT', 'DIVPARM', 1, 7, 'E', 0);
INSERT INTO profile_menu VALUES (76, 'ANCODS', 'ANC', 1, 2, 'E', 0);
INSERT INTO profile_menu VALUES (210, 'REPORT', 'ADV', 6, 25, 'E', 0);
INSERT INTO profile_menu VALUES (211, 'CARD', 'COMPTA', 6, 7, 'E', 0);
INSERT INTO profile_menu VALUES (77, 'ANCGROUP', 'ANC', 1, 3, 'E', 0);
INSERT INTO profile_menu VALUES (212, 'HIST', 'COMPTA', 6, 1, 'E', 0);
INSERT INTO profile_menu VALUES (213, 'FINSALDO', 'MENUFIN', 6, 3, 'E', 0);
INSERT INTO profile_menu VALUES (214, 'FINREC', 'MENUFIN', 6, 4, 'E', 0);
INSERT INTO profile_menu VALUES (215, 'ADV', 'COMPTA', 6, 20, 'E', 0);
INSERT INTO profile_menu VALUES (216, 'ACCESS', NULL, 6, 25, 'M', 0);
INSERT INTO profile_menu VALUES (217, 'CSV:histo', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (78, 'ANCIMP', 'ANC', 1, 4, 'E', 0);
INSERT INTO profile_menu VALUES (218, 'LOGOUT', NULL, 6, 30, 'M', 0);
INSERT INTO profile_menu VALUES (219, 'PRINT', 'GESTION', 6, 4, 'E', 0);
INSERT INTO profile_menu VALUES (220, 'new_line', NULL, 6, 35, 'M', 0);
INSERT INTO profile_menu VALUES (221, 'CSV:ledger', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (222, 'PDF:ledger', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (223, 'PRINT', 'COMPTA', 6, 6, 'E', 0);
INSERT INTO profile_menu VALUES (224, 'CSV:postedetail', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (225, 'TVA', 'EXTENSION', 6, NULL, 'S', 0);
INSERT INTO profile_menu VALUES (226, 'MENUACH', 'COMPTA', 6, 3, 'E', 0);
INSERT INTO profile_menu VALUES (227, 'ACHIMP', 'MENUACH', 6, 2, 'E', 0);
INSERT INTO profile_menu VALUES (228, 'GESTION', NULL, 6, 45, 'M', 0);
INSERT INTO profile_menu VALUES (229, 'MENUODS', 'COMPTA', 6, 5, 'E', 0);
INSERT INTO profile_menu VALUES (23, 'LETTER', 'COMPTA', 1, 8, 'E', 0);
INSERT INTO profile_menu VALUES (45, 'PARAM', NULL, 1, 20, 'M', 0);
INSERT INTO profile_menu VALUES (230, 'ODS', 'MENUODS', 6, 1, 'E', 0);
INSERT INTO profile_menu VALUES (231, 'ODSIMP', 'MENUODS', 6, 2, 'E', 0);
INSERT INTO profile_menu VALUES (232, 'ANC', NULL, 6, 50, 'M', 0);
INSERT INTO profile_menu VALUES (234, 'VENMENU', 'COMPTA', 6, 2, 'E', 0);
INSERT INTO profile_menu VALUES (235, 'VEN', 'VENMENU', 6, 1, 'E', 0);
INSERT INTO profile_menu VALUES (236, 'VENIMP', 'VENMENU', 6, 2, 'E', 0);
INSERT INTO profile_menu VALUES (237, 'EXTENSION', NULL, 6, 55, 'M', 0);
INSERT INTO profile_menu VALUES (238, 'FIN', 'MENUFIN', 6, 1, 'E', 0);
INSERT INTO profile_menu VALUES (239, 'MENUFIN', 'COMPTA', 6, 4, 'E', 0);
INSERT INTO profile_menu VALUES (240, 'FINIMP', 'MENUFIN', 6, 2, 'E', 0);
INSERT INTO profile_menu VALUES (241, 'IMPCARD', 'EXTENSION', 6, NULL, 'S', 0);
INSERT INTO profile_menu VALUES (242, 'AMORTIS', 'EXTENSION', 6, NULL, 'S', 0);
INSERT INTO profile_menu VALUES (243, 'TOOLPLAN', 'EXTENSION', 6, NULL, 'S', 0);
INSERT INTO profile_menu VALUES (244, 'SEARCH', NULL, 6, 60, 'M', 0);
INSERT INTO profile_menu VALUES (245, 'ACH', 'MENUACH', 6, 1, 'E', 0);
INSERT INTO profile_menu VALUES (246, 'PRINTJRN', 'IMP', 6, 14, 'E', 0);
INSERT INTO profile_menu VALUES (247, 'PRINTREC', 'IMP', 6, 9, 'E', 0);
INSERT INTO profile_menu VALUES (248, 'PRINTPOSTE', 'IMP', 6, 8, 'E', 0);
INSERT INTO profile_menu VALUES (249, 'PRINTREPORT', 'IMP', 6, 10, 'E', 0);
INSERT INTO profile_menu VALUES (250, 'PRINTBILAN', 'IMP', 6, 11, 'E', 0);
INSERT INTO profile_menu VALUES (251, 'PRINTGL', 'IMP', 6, 13, 'E', 0);
INSERT INTO profile_menu VALUES (252, 'PRINTBAL', 'IMP', 6, 15, 'E', 0);
INSERT INTO profile_menu VALUES (253, 'PRINTCARD', 'IMP', 6, 12, 'E', 0);
INSERT INTO profile_menu VALUES (254, 'IMPORTBANK', 'EXTENSION', 6, NULL, 'S', 0);
INSERT INTO profile_menu VALUES (255, 'TOOL', 'EXTENSION', 6, NULL, 'S', 0);
INSERT INTO profile_menu VALUES (256, 'AMORTIS', 'MENUACH', 6, 10, 'E', 0);
INSERT INTO profile_menu VALUES (258, 'CFGMENU', 'MODULARITY', 6, NULL, 'E', 0);
INSERT INTO profile_menu VALUES (259, 'CFGPROFILE', 'MODULARITY', 6, NULL, 'E', 0);
INSERT INTO profile_menu VALUES (260, 'PDF:postedetail', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (261, 'CSV:fichedetail', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (262, 'PDF:fichedetail', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (263, 'CSV:fiche_balance', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (264, 'PDF:fiche_balance', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (265, 'CSV:report', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (266, 'PDF:report', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (267, 'CSV:fiche', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (268, 'PDF:fiche', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (269, 'CSV:glcompte', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (270, 'PDF:glcompte', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (271, 'PDF:sec', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (272, 'CSV:AncList', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (273, 'CSV:AncBalSimple', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (274, 'PDF:AncBalSimple', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (275, 'CSV:AncBalDouble', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (276, 'PDF:AncBalDouble', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (277, 'CSV:balance', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (278, 'PDF:balance', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (279, 'CSV:AncTable', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (280, 'CSV:AncAccList', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (281, 'CSV:AncBalGroup', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (282, 'OTH:Bilan', NULL, 6, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (79, 'PREFERENCE', NULL, 1, 15, 'M', 1);
INSERT INTO profile_menu VALUES (24, 'LETTERCARD', 'LETTER', 1, 1, 'E', 0);
INSERT INTO profile_menu VALUES (27, 'LETTERACC', 'LETTER', 1, 2, 'E', 0);
INSERT INTO profile_menu VALUES (37, 'CUST', 'GESTION', 1, 1, 'E', 0);
INSERT INTO profile_menu VALUES (38, 'SUPPL', 'GESTION', 1, 2, 'E', 0);
INSERT INTO profile_menu VALUES (39, 'ADM', 'GESTION', 1, 3, 'E', 0);
INSERT INTO profile_menu VALUES (36, 'CARD', 'GESTION', 1, 6, 'E', 0);
INSERT INTO profile_menu VALUES (40, 'STOCK', 'GESTION', 1, 5, 'E', 0);
INSERT INTO profile_menu VALUES (41, 'FORECAST', 'GESTION', 1, 7, 'E', 0);
INSERT INTO profile_menu VALUES (42, 'FOLLOW', 'GESTION', 1, 8, 'E', 0);
INSERT INTO profile_menu VALUES (29, 'VERIFBIL', 'ADV', 1, 21, 'E', 0);
INSERT INTO profile_menu VALUES (30, 'STOCK', 'ADV', 1, 22, 'E', 0);
INSERT INTO profile_menu VALUES (31, 'PREDOP', 'ADV', 1, 23, 'E', 0);
INSERT INTO profile_menu VALUES (32, 'OPEN', 'ADV', 1, 24, 'E', 0);
INSERT INTO profile_menu VALUES (33, 'REPORT', 'ADV', 1, 25, 'E', 0);
INSERT INTO profile_menu VALUES (5, 'CARD', 'COMPTA', 1, 7, 'E', 0);
INSERT INTO profile_menu VALUES (43, 'HIST', 'COMPTA', 1, 1, 'E', 0);
INSERT INTO profile_menu VALUES (94, 'FINSALDO', 'MENUFIN', 1, 3, 'E', 0);
INSERT INTO profile_menu VALUES (95, 'FINREC', 'MENUFIN', 1, 4, 'E', 0);
INSERT INTO profile_menu VALUES (28, 'ADV', 'COMPTA', 1, 20, 'E', 0);
INSERT INTO profile_menu VALUES (53, 'ACCESS', NULL, 1, 25, 'M', 0);
INSERT INTO profile_menu VALUES (123, 'CSV:histo', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (20, 'LOGOUT', NULL, 1, 30, 'M', 0);
INSERT INTO profile_menu VALUES (35, 'PRINT', 'GESTION', 1, 4, 'E', 0);
INSERT INTO profile_menu VALUES (156, 'new_line', NULL, 1, 35, 'M', 0);
INSERT INTO profile_menu VALUES (124, 'CSV:ledger', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (125, 'PDF:ledger', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (6, 'PRINT', 'COMPTA', 1, 6, 'E', 0);
INSERT INTO profile_menu VALUES (126, 'CSV:postedetail', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (164, 'TVA', 'EXTENSION', 1, NULL, 'S', 0);
INSERT INTO profile_menu VALUES (3, 'MENUACH', 'COMPTA', 1, 3, 'E', 0);
INSERT INTO profile_menu VALUES (86, 'ACHIMP', 'MENUACH', 1, 2, 'E', 0);
INSERT INTO profile_menu VALUES (34, 'GESTION', NULL, 1, 45, 'M', 0);
INSERT INTO profile_menu VALUES (18, 'MENUODS', 'COMPTA', 1, 5, 'E', 0);
INSERT INTO profile_menu VALUES (88, 'ODS', 'MENUODS', 1, 1, 'E', 0);
INSERT INTO profile_menu VALUES (89, 'ODSIMP', 'MENUODS', 1, 2, 'E', 0);
INSERT INTO profile_menu VALUES (2, 'ANC', NULL, 1, 50, 'M', 0);
INSERT INTO profile_menu VALUES (4, 'VENMENU', 'COMPTA', 1, 2, 'E', 0);
INSERT INTO profile_menu VALUES (90, 'VEN', 'VENMENU', 1, 1, 'E', 0);
INSERT INTO profile_menu VALUES (91, 'VENIMP', 'VENMENU', 1, 2, 'E', 0);
INSERT INTO profile_menu VALUES (82, 'EXTENSION', NULL, 1, 55, 'M', 0);
INSERT INTO profile_menu VALUES (19, 'FIN', 'MENUFIN', 1, 1, 'E', 0);
INSERT INTO profile_menu VALUES (92, 'MENUFIN', 'COMPTA', 1, 4, 'E', 0);
INSERT INTO profile_menu VALUES (93, 'FINIMP', 'MENUFIN', 1, 2, 'E', 0);
INSERT INTO profile_menu VALUES (159, 'IMPCARD', 'EXTENSION', 1, NULL, 'S', 0);
INSERT INTO profile_menu VALUES (160, 'AMORTIS', 'EXTENSION', 1, NULL, 'S', 0);
INSERT INTO profile_menu VALUES (161, 'TOOLPLAN', 'EXTENSION', 1, NULL, 'S', 0);
INSERT INTO profile_menu VALUES (151, 'SEARCH', NULL, 1, 60, 'M', 0);
INSERT INTO profile_menu VALUES (85, 'ACH', 'MENUACH', 1, 1, 'E', 0);
INSERT INTO profile_menu VALUES (16, 'PRINTJRN', 'IMP', 1, 14, 'E', 0);
INSERT INTO profile_menu VALUES (10, 'PRINTREC', 'IMP', 1, 9, 'E', 0);
INSERT INTO profile_menu VALUES (9, 'PRINTPOSTE', 'IMP', 1, 8, 'E', 0);
INSERT INTO profile_menu VALUES (11, 'PRINTREPORT', 'IMP', 1, 10, 'E', 0);
INSERT INTO profile_menu VALUES (13, 'PRINTBILAN', 'IMP', 1, 11, 'E', 0);
INSERT INTO profile_menu VALUES (15, 'PRINTGL', 'IMP', 1, 13, 'E', 0);
INSERT INTO profile_menu VALUES (17, 'PRINTBAL', 'IMP', 1, 15, 'E', 0);
INSERT INTO profile_menu VALUES (14, 'PRINTCARD', 'IMP', 1, 12, 'E', 0);
INSERT INTO profile_menu VALUES (162, 'IMPORTBANK', 'EXTENSION', 1, NULL, 'S', 0);
INSERT INTO profile_menu VALUES (163, 'TOOL', 'EXTENSION', 1, NULL, 'S', 0);
INSERT INTO profile_menu VALUES (157, 'AMORTIS', 'MENUACH', 1, 10, 'E', 0);
INSERT INTO profile_menu VALUES (167, 'MODULARITY', 'PARAM', 1, 1, 'E', 0);
INSERT INTO profile_menu VALUES (171, 'CFGMENU', 'MODULARITY', 1, NULL, 'E', 0);
INSERT INTO profile_menu VALUES (172, 'CFGPROFILE', 'MODULARITY', 1, NULL, 'E', 0);
INSERT INTO profile_menu VALUES (127, 'PDF:postedetail', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (128, 'CSV:fichedetail', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (129, 'PDF:fichedetail', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (130, 'CSV:fiche_balance', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (131, 'PDF:fiche_balance', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (132, 'CSV:report', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (133, 'PDF:report', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (134, 'CSV:fiche', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (135, 'PDF:fiche', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (136, 'CSV:glcompte', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (137, 'PDF:glcompte', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (138, 'PDF:sec', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (139, 'CSV:AncList', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (140, 'CSV:AncBalSimple', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (141, 'PDF:AncBalSimple', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (142, 'CSV:AncBalDouble', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (143, 'PDF:AncBalDouble', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (144, 'CSV:balance', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (145, 'PDF:balance', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (146, 'CSV:AncTable', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (147, 'CSV:AncAccList', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (148, 'CSV:AncBalGroup', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (149, 'OTH:Bilan', NULL, 1, NULL, 'P', 0);
INSERT INTO profile_menu VALUES (1, 'DASHBOARD', NULL, 1, 10, 'M', 0);
INSERT INTO profile_menu VALUES (302, 'TESTTVA', 'EXTENSION', 1, NULL, 'S', NULL);


--
-- Data for Name: profile_menu_type; Type: TABLE DATA; Schema: public; Owner: dany
--

INSERT INTO profile_menu_type VALUES ('P', 'Impression');
INSERT INTO profile_menu_type VALUES ('S', 'Extension');
INSERT INTO profile_menu_type VALUES ('E', 'Menu');
INSERT INTO profile_menu_type VALUES ('M', 'Module');


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
-- Name: profile_menu_type_pkey; Type: CONSTRAINT; Schema: public; Owner: dany; Tablespace: 
--

ALTER TABLE ONLY profile_menu_type
    ADD CONSTRAINT profile_menu_type_pkey PRIMARY KEY (pm_type);


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
-- Name: fki_profile_menu_me_code; Type: INDEX; Schema: public; Owner: dany; Tablespace: 
--

CREATE INDEX fki_profile_menu_me_code ON profile_menu USING btree (me_code);


--
-- Name: fki_profile_menu_profile; Type: INDEX; Schema: public; Owner: dany; Tablespace: 
--

CREATE INDEX fki_profile_menu_profile ON profile_menu USING btree (p_id);


--
-- Name: fki_profile_menu_type_fkey; Type: INDEX; Schema: public; Owner: dany; Tablespace: 
--

CREATE INDEX fki_profile_menu_type_fkey ON profile_menu USING btree (p_type_display);


--
-- Name: profile_menu_me_code_fkey; Type: FK CONSTRAINT; Schema: public; Owner: dany
--

ALTER TABLE ONLY profile_menu
    ADD CONSTRAINT profile_menu_me_code_fkey FOREIGN KEY (me_code) REFERENCES menu_ref(me_code) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: profile_menu_p_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: dany
--

ALTER TABLE ONLY profile_menu
    ADD CONSTRAINT profile_menu_p_id_fkey FOREIGN KEY (p_id) REFERENCES profile(p_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: profile_menu_type_fkey; Type: FK CONSTRAINT; Schema: public; Owner: dany
--

ALTER TABLE ONLY profile_menu
    ADD CONSTRAINT profile_menu_type_fkey FOREIGN KEY (p_type_display) REFERENCES profile_menu_type(pm_type);


--
-- Name: profile_user_p_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: dany
--

ALTER TABLE ONLY profile_user
    ADD CONSTRAINT profile_user_p_id_fkey FOREIGN KEY (p_id) REFERENCES profile(p_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

