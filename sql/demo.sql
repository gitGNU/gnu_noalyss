--
-- PostgreSQL database dump
--

\connect - phpcompta

SET search_path = public, pg_catalog;

--
-- TOC entry 46 (OID 34884)
-- Name: tmp_pcmn; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE tmp_pcmn (
    pcm_val integer NOT NULL,
    pcm_lib text,
    pcm_val_parent integer DEFAULT 0,
    pcm_country character(2) DEFAULT 'BE' NOT NULL
);


--
-- TOC entry 47 (OID 34891)
-- Name: version; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE "version" (
    val integer
);


--
-- TOC entry 2 (OID 34893)
-- Name: s_periode; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_periode
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 4 (OID 34895)
-- Name: s_currency; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_currency
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 48 (OID 34897)
-- Name: parm_money; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE parm_money (
    pm_id integer DEFAULT nextval('s_currency'::text),
    pm_code character(3) NOT NULL,
    pm_rate double precision
);


--
-- TOC entry 49 (OID 34900)
-- Name: parm_periode; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE parm_periode (
    p_id integer DEFAULT nextval('s_periode'::text) NOT NULL,
    p_start date NOT NULL,
    p_end date,
    p_exercice text DEFAULT to_char(now(), 'YYYY'::text) NOT NULL,
    p_closed boolean DEFAULT false
);


--
-- TOC entry 6 (OID 34908)
-- Name: s_jrn_def; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_def
    START 5
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 8 (OID 34910)
-- Name: s_grpt; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_grpt
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 10 (OID 34912)
-- Name: s_jrn_op; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_op
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 12 (OID 34914)
-- Name: s_jrn; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 50 (OID 34916)
-- Name: jrn_type; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jrn_type (
    jrn_type_id character(3) NOT NULL,
    jrn_desc text
);


--
-- TOC entry 51 (OID 34921)
-- Name: jrn_def; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jrn_def (
    jrn_def_id integer DEFAULT nextval('s_jrn_def'::text) NOT NULL,
    jrn_def_name text NOT NULL,
    jrn_def_class_deb text,
    jrn_def_class_cred text,
    jrn_def_fiche_deb text,
    jrn_def_fiche_cred text,
    jrn_deb_max_line integer DEFAULT 1,
    jrn_cred_max_line integer DEFAULT 1,
    jrn_def_ech boolean DEFAULT false,
    jrn_def_ech_lib text,
    jrn_def_type character(3) NOT NULL,
    jrn_def_code text NOT NULL
);


--
-- TOC entry 14 (OID 34930)
-- Name: s_jrnx; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrnx
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 52 (OID 34932)
-- Name: jrn; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jrn (
    jr_id integer DEFAULT nextval('s_jrn'::text) NOT NULL,
    jr_def_id integer NOT NULL,
    jr_montant double precision NOT NULL,
    jr_comment text,
    jr_date date,
    jr_grpt_id integer NOT NULL,
    jr_internal text,
    jr_tech_date timestamp without time zone DEFAULT now() NOT NULL,
    jr_tech_per integer NOT NULL,
    jr_ech date,
    jr_rapt text
);


--
-- TOC entry 53 (OID 34939)
-- Name: jrnx; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jrnx (
    j_id integer DEFAULT nextval('s_jrn_op'::text) NOT NULL,
    j_date date DEFAULT now(),
    j_montant double precision DEFAULT 0,
    j_poste integer NOT NULL,
    j_grpt integer NOT NULL,
    j_rapt text,
    j_jrn_def integer NOT NULL,
    j_debit boolean DEFAULT true,
    j_text text,
    j_centralized boolean DEFAULT false,
    j_internal text,
    j_tech_user text NOT NULL,
    j_tech_date timestamp without time zone DEFAULT now() NOT NULL,
    j_tech_per integer
);


--
-- TOC entry 54 (OID 34950)
-- Name: user_pref; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE user_pref (
    pref_user text NOT NULL,
    pref_periode integer NOT NULL
);


--
-- TOC entry 16 (OID 34955)
-- Name: s_formdef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_formdef
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 18 (OID 34957)
-- Name: s_form; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_form
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 55 (OID 34959)
-- Name: formdef; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE formdef (
    fr_id integer DEFAULT nextval('s_formdef'::text) NOT NULL,
    fr_label text
);


--
-- TOC entry 56 (OID 34965)
-- Name: form; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE form (
    fo_id integer DEFAULT nextval('s_form'::text) NOT NULL,
    fo_fr_id integer,
    fo_pos integer,
    fo_label text,
    fo_formula text
);


--
-- TOC entry 20 (OID 34971)
-- Name: s_isup; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_isup
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 22 (OID 34973)
-- Name: s_idef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_idef
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 57 (OID 34975)
-- Name: fichedef; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE fichedef (
    fd_id integer DEFAULT nextval('s_fdef'::text) NOT NULL,
    fd_label text NOT NULL,
    fd_class_base text
);


--
-- TOC entry 58 (OID 34981)
-- Name: isupp_def; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE isupp_def (
    isd_id integer DEFAULT nextval('s_idef'::text) NOT NULL,
    isd_label text,
    isd_fd_id integer,
    isd_form boolean
);


--
-- TOC entry 59 (OID 34987)
-- Name: isupp; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE isupp (
    is_id integer DEFAULT nextval('s_isup'::text) NOT NULL,
    is_f_id integer,
    is_isd_id integer,
    is_value text
);


--
-- TOC entry 24 (OID 34993)
-- Name: s_centralized; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_centralized
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 60 (OID 34995)
-- Name: centralized; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE centralized (
    c_id integer DEFAULT nextval('s_centralized'::text) NOT NULL,
    c_j_id integer,
    c_date date NOT NULL,
    c_internal text NOT NULL,
    c_montant double precision NOT NULL,
    c_debit boolean DEFAULT 't',
    c_jrn_def integer NOT NULL,
    c_poste integer,
    c_description text,
    c_grp integer NOT NULL,
    c_comment text,
    c_rapt text,
    c_periode integer
);


--
-- TOC entry 26 (OID 35002)
-- Name: s_user_jrn; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_user_jrn
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 28 (OID 35004)
-- Name: s_user_act; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_user_act
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 61 (OID 35006)
-- Name: user_sec_jrn; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE user_sec_jrn (
    uj_id integer DEFAULT nextval('s_user_jrn'::text) NOT NULL,
    uj_login text,
    uj_jrn_id integer,
    uj_priv text
);


--
-- TOC entry 62 (OID 35012)
-- Name: action; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE "action" (
    ac_id integer NOT NULL,
    ac_description text NOT NULL
);


--
-- TOC entry 63 (OID 35017)
-- Name: user_sec_act; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE user_sec_act (
    ua_id integer DEFAULT nextval('s_user_act'::text) NOT NULL,
    ua_login text,
    ua_act_id integer
);


--
-- TOC entry 30 (OID 35023)
-- Name: s_jrnaction; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrnaction
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 64 (OID 35025)
-- Name: jrn_action; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jrn_action (
    ja_id integer DEFAULT nextval('s_jrnaction'::text) NOT NULL,
    ja_name text NOT NULL,
    ja_desc text,
    ja_url text NOT NULL,
    ja_action text NOT NULL,
    ja_lang text DEFAULT 'FR',
    ja_jrn_type character(3)
);


--
-- TOC entry 65 (OID 35032)
-- Name: tva_rate; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE tva_rate (
    tva_id integer NOT NULL,
    tva_label text NOT NULL,
    tva_rate double precision DEFAULT 0.0 NOT NULL,
    tva_comment text,
    tva_poste text
);


--
-- TOC entry 32 (OID 35038)
-- Name: s_fiche; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fiche
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 34 (OID 35040)
-- Name: s_fiche_def_ref; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fiche_def_ref
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 36 (OID 35042)
-- Name: s_fdef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fdef
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 38 (OID 35044)
-- Name: s_attr_def; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_attr_def
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 40 (OID 35046)
-- Name: s_jnt_fic_att_value; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jnt_fic_att_value
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 66 (OID 35048)
-- Name: fiche_def_ref; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE fiche_def_ref (
    frd_id integer DEFAULT nextval('s_fiche_def_ref'::text) NOT NULL,
    frd_text text,
    frd_class_base integer
);


--
-- TOC entry 67 (OID 35054)
-- Name: fiche_def; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE fiche_def (
    fd_id integer DEFAULT nextval('s_fdef'::text) NOT NULL,
    fd_class_base integer,
    fd_label text NOT NULL,
    fd_create_account boolean DEFAULT false,
    frd_id integer NOT NULL
);


--
-- TOC entry 68 (OID 35061)
-- Name: attr_def; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_def (
    ad_id integer DEFAULT nextval('s_attr_def'::text) NOT NULL,
    ad_text text
);


--
-- TOC entry 69 (OID 35067)
-- Name: attr_min; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_min (
    frd_id integer,
    ad_id integer
);


--
-- TOC entry 70 (OID 35069)
-- Name: fiche; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE fiche (
    f_id integer DEFAULT nextval('s_fiche'::text) NOT NULL,
    fd_id integer
);


--
-- TOC entry 71 (OID 35072)
-- Name: jnt_fic_att_value; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jnt_fic_att_value (
    jft_id integer DEFAULT nextval('s_jnt_fic_att_value'::text) NOT NULL,
    f_id integer,
    ad_id integer
);


--
-- TOC entry 72 (OID 35075)
-- Name: attr_value; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_value (
    jft_id integer,
    av_text text
);


--
-- TOC entry 73 (OID 35080)
-- Name: jnt_fic_attr; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jnt_fic_attr (
    fd_id integer,
    ad_id integer
);


--
-- TOC entry 42 (OID 35082)
-- Name: s_stock_goods; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_stock_goods
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 74 (OID 35084)
-- Name: stock_goods; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE stock_goods (
    sg_id integer DEFAULT nextval('s_stock_goods'::text) NOT NULL,
    j_id integer NOT NULL,
    f_id integer NOT NULL,
    sg_quantity_deb integer DEFAULT 0,
    sg_quantity_cred integer DEFAULT 0
);


--
-- TOC entry 75 (OID 35089)
-- Name: test; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE test (
    col1 text,
    col2 text
);


--
-- TOC entry 44 (OID 35094)
-- Name: s_jrn_rapt; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_rapt
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 76 (OID 35096)
-- Name: jrn_rapt; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jrn_rapt (
    jra_id integer DEFAULT nextval('s_jrn_rapt'::text) NOT NULL,
    jr_id integer NOT NULL,
    jra_concerned integer NOT NULL
);


--
-- TOC entry 77 (OID 35101)
-- Name: vw_fiche_attr; Type: VIEW; Schema: public; Owner: phpcompta
--

CREATE VIEW vw_fiche_attr AS
    SELECT a.f_id, fd_id, a.av_text AS vw_name, b.av_text AS vw_sell, c.av_text AS vw_buy, d.av_text AS tva_code, tva_id, tva_rate, tva_label, e.av_text AS vw_addr, f.av_text AS vw_cp, frd_id FROM ((((((((SELECT f_id, fd_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 1)) a LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 6)) b ON ((a.f_id = b.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 7)) c ON ((a.f_id = c.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 2)) d ON ((a.f_id = d.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 14)) e ON ((a.f_id = e.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 15)) f ON ((a.f_id = f.f_id))) LEFT JOIN tva_rate ON ((d.av_text = (tva_rate.tva_id)::text))) JOIN fiche_def USING (fd_id));


--
-- Data for TOC entry 125 (OID 34884)
-- Name: tmp_pcmn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO tmp_pcmn VALUES (10, 'Capital ', 1, 'BE');
INSERT INTO tmp_pcmn VALUES (100, ' Capital souscrit', 10, 'BE');
INSERT INTO tmp_pcmn VALUES (11, 'Prime d''émission ', 1, 'BE');
INSERT INTO tmp_pcmn VALUES (12, 'Plus Value de réévaluation ', 1, 'BE');
INSERT INTO tmp_pcmn VALUES (13, 'Réserve ', 1, 'BE');
INSERT INTO tmp_pcmn VALUES (130, 'Réserve légale', 13, 'BE');
INSERT INTO tmp_pcmn VALUES (131, 'Réserve indisponible', 13, 'BE');
INSERT INTO tmp_pcmn VALUES (1310, 'Réserve pour actions propres', 131, 'BE');
INSERT INTO tmp_pcmn VALUES (1311, ' Autres réserves indisponibles', 131, 'BE');
INSERT INTO tmp_pcmn VALUES (132, ' Réserves immunisées', 13, 'BE');
INSERT INTO tmp_pcmn VALUES (133, 'Réserves disponibles', 13, 'BE');
INSERT INTO tmp_pcmn VALUES (14, 'Bénéfice ou perte reportée', 1, 'BE');
INSERT INTO tmp_pcmn VALUES (140, 'Bénéfice reporté', 14, 'BE');
INSERT INTO tmp_pcmn VALUES (141, 'Perte reportée', 14, 'BE');
INSERT INTO tmp_pcmn VALUES (15, 'Subside en capital', 1, 'BE');
INSERT INTO tmp_pcmn VALUES (16, 'Provisions pour risques et charges', 1, 'BE');
INSERT INTO tmp_pcmn VALUES (160, 'Provisions pour pensions et obligations similaires', 16, 'BE');
INSERT INTO tmp_pcmn VALUES (161, 'Provisions pour charges fiscales', 16, 'BE');
INSERT INTO tmp_pcmn VALUES (162, 'Provisions pour grosses réparation et gros entretien', 16, 'BE');
INSERT INTO tmp_pcmn VALUES (17, ' Dettes à plus d''un an', 1, 'BE');
INSERT INTO tmp_pcmn VALUES (170, 'Emprunts subordonnés', 17, 'BE');
INSERT INTO tmp_pcmn VALUES (1700, 'convertibles', 170, 'BE');
INSERT INTO tmp_pcmn VALUES (1701, 'non convertibles', 170, 'BE');
INSERT INTO tmp_pcmn VALUES (171, 'Emprunts subordonnés', 17, 'BE');
INSERT INTO tmp_pcmn VALUES (1710, 'convertibles', 170, 'BE');
INSERT INTO tmp_pcmn VALUES (1711, 'non convertibles', 170, 'BE');
INSERT INTO tmp_pcmn VALUES (172, ' Dettes de locations financement', 17, 'BE');
INSERT INTO tmp_pcmn VALUES (173, ' Etablissement de crédit', 17, 'BE');
INSERT INTO tmp_pcmn VALUES (1730, 'Dettes en comptes', 173, 'BE');
INSERT INTO tmp_pcmn VALUES (1731, 'Promesses', 173, 'BE');
INSERT INTO tmp_pcmn VALUES (1732, 'Crédits d''acceptation', 173, 'BE');
INSERT INTO tmp_pcmn VALUES (174, 'Autres emprunts', 17, 'BE');
INSERT INTO tmp_pcmn VALUES (175, 'Dettes commerciales', 17, 'BE');
INSERT INTO tmp_pcmn VALUES (1750, 'Fournisseurs', 175, 'BE');
INSERT INTO tmp_pcmn VALUES (1751, 'Effets à payer', 175, 'BE');
INSERT INTO tmp_pcmn VALUES (176, 'Acomptes reçus sur commandes', 17, 'BE');
INSERT INTO tmp_pcmn VALUES (178, 'Cautionnement reçus en numéraires', 17, 'BE');
INSERT INTO tmp_pcmn VALUES (179, 'Dettes diverses', 17, 'BE');
INSERT INTO tmp_pcmn VALUES (20, 'Frais d''établissement', 2, 'BE');
INSERT INTO tmp_pcmn VALUES (200, 'Frais de constitution et d''augmentation de capital', 20, 'BE');
INSERT INTO tmp_pcmn VALUES (201, ' Frais d''émission d''emprunts et primes de remboursement', 20, 'BE');
INSERT INTO tmp_pcmn VALUES (202, 'Autres frais d''établissement', 20, 'BE');
INSERT INTO tmp_pcmn VALUES (204, 'Frais de restructuration', 20, 'BE');
INSERT INTO tmp_pcmn VALUES (21, 'Immobilisations incorporelles', 2, 'BE');
INSERT INTO tmp_pcmn VALUES (210, 'Frais de recherche et de développement', 21, 'BE');
INSERT INTO tmp_pcmn VALUES (211, 'Concessions, brevet, licence savoir faire, marque et droit similaires', 21, 'BE');
INSERT INTO tmp_pcmn VALUES (212, 'Goodwill', 21, 'BE');
INSERT INTO tmp_pcmn VALUES (213, 'Acomptes versés', 21, 'BE');
INSERT INTO tmp_pcmn VALUES (22, 'Terrains et construction', 2, 'BE');
INSERT INTO tmp_pcmn VALUES (220, 'Terrains', 22, 'BE');
INSERT INTO tmp_pcmn VALUES (221, 'Construction', 22, 'BE');
INSERT INTO tmp_pcmn VALUES (222, 'Terrains bâtis', 22, 'BE');
INSERT INTO tmp_pcmn VALUES (223, 'Autres droits réels sur des immeubles', 22, 'BE');
INSERT INTO tmp_pcmn VALUES (23, ' Installations, machines et outillages', 2, 'BE');
INSERT INTO tmp_pcmn VALUES (24, 'Mobilier et Matériel roulant', 2, 'BE');
INSERT INTO tmp_pcmn VALUES (25, 'Immobilisations détenus en location-financement et droits similaires', 2, 'BE');
INSERT INTO tmp_pcmn VALUES (250, 'Terrains', 25, 'BE');
INSERT INTO tmp_pcmn VALUES (251, 'Construction', 25, 'BE');
INSERT INTO tmp_pcmn VALUES (252, 'Terrains bâtis', 25, 'BE');
INSERT INTO tmp_pcmn VALUES (253, 'Mobilier et matériels roulants', 25, 'BE');
INSERT INTO tmp_pcmn VALUES (26, 'Autres immobilisations corporelles', 2, 'BE');
INSERT INTO tmp_pcmn VALUES (27, 'Immobilisations corporelles en cours et acomptes versés', 2, 'BE');
INSERT INTO tmp_pcmn VALUES (28, 'Immobilisations financières', 2, 'BE');
INSERT INTO tmp_pcmn VALUES (280, 'Participation dans des entreprises liées', 28, 'BE');
INSERT INTO tmp_pcmn VALUES (2800, 'Valeur d''acquisition', 280, 'BE');
INSERT INTO tmp_pcmn VALUES (2801, 'Montants non-appelés(-)', 280, 'BE');
INSERT INTO tmp_pcmn VALUES (2808, 'Plus-values actées', 280, 'BE');
INSERT INTO tmp_pcmn VALUES (2809, 'Réductions de valeurs actées', 280, 'BE');
INSERT INTO tmp_pcmn VALUES (281, 'Créance sur  des entreprises liées', 28, 'BE');
INSERT INTO tmp_pcmn VALUES (2810, 'Créance en compte', 281, 'BE');
INSERT INTO tmp_pcmn VALUES (2811, 'Effets à recevoir', 281, 'BE');
INSERT INTO tmp_pcmn VALUES (2812, 'Titre à reveny fixe', 281, 'BE');
INSERT INTO tmp_pcmn VALUES (2817, 'Créances douteuses', 281, 'BE');
INSERT INTO tmp_pcmn VALUES (2819, 'Réduction de valeurs actées', 281, 'BE');
INSERT INTO tmp_pcmn VALUES (282, 'Participations dans des entreprises avec lesquelles il existe un lien de participation', 28, 'BE');
INSERT INTO tmp_pcmn VALUES (2820, 'Valeur d''acquisition', 282, 'BE');
INSERT INTO tmp_pcmn VALUES (2821, 'Montants non-appelés(-)', 282, 'BE');
INSERT INTO tmp_pcmn VALUES (2828, 'Plus-values actées', 282, 'BE');
INSERT INTO tmp_pcmn VALUES (2829, 'Réductions de valeurs actées', 282, 'BE');
INSERT INTO tmp_pcmn VALUES (283, 'Créances sur des entreprises avec lesquelles existe un lien de participation', 28, 'BE');
INSERT INTO tmp_pcmn VALUES (2830, 'Créance en compte', 283, 'BE');
INSERT INTO tmp_pcmn VALUES (2831, 'Effets à recevoir', 283, 'BE');
INSERT INTO tmp_pcmn VALUES (2832, 'Titre à revenu fixe', 283, 'BE');
INSERT INTO tmp_pcmn VALUES (2837, 'Créances douteuses', 283, 'BE');
INSERT INTO tmp_pcmn VALUES (2839, 'Réduction de valeurs actées', 283, 'BE');
INSERT INTO tmp_pcmn VALUES (284, 'Autres actions et parts', 28, 'BE');
INSERT INTO tmp_pcmn VALUES (2840, 'Valeur d''acquisition', 284, 'BE');
INSERT INTO tmp_pcmn VALUES (2841, 'Montants non-appelés(-)', 284, 'BE');
INSERT INTO tmp_pcmn VALUES (2848, 'Plus-values actées', 284, 'BE');
INSERT INTO tmp_pcmn VALUES (2849, 'Réductions de valeurs actées', 284, 'BE');
INSERT INTO tmp_pcmn VALUES (285, 'Autres créances', 28, 'BE');
INSERT INTO tmp_pcmn VALUES (2850, 'Créance en compte', 285, 'BE');
INSERT INTO tmp_pcmn VALUES (2851, 'Effets à recevoir', 285, 'BE');
INSERT INTO tmp_pcmn VALUES (2852, 'Titre à revenu fixe', 285, 'BE');
INSERT INTO tmp_pcmn VALUES (2857, 'Créances douteuses', 285, 'BE');
INSERT INTO tmp_pcmn VALUES (2859, 'Réductions de valeurs actées', 285, 'BE');
INSERT INTO tmp_pcmn VALUES (288, 'Cautionnements versés en numéraires', 28, 'BE');
INSERT INTO tmp_pcmn VALUES (29, 'Créances à plus d''un an', 2, 'BE');
INSERT INTO tmp_pcmn VALUES (290, 'Créances commerciales', 29, 'BE');
INSERT INTO tmp_pcmn VALUES (2900, 'Clients', 290, 'BE');
INSERT INTO tmp_pcmn VALUES (2901, 'Effets à recevoir', 290, 'BE');
INSERT INTO tmp_pcmn VALUES (2906, 'Acomptes versés', 290, 'BE');
INSERT INTO tmp_pcmn VALUES (2907, 'Créances douteuses', 290, 'BE');
INSERT INTO tmp_pcmn VALUES (2909, 'Réductions de valeurs actées', 290, 'BE');
INSERT INTO tmp_pcmn VALUES (291, 'Autres créances', 29, 'BE');
INSERT INTO tmp_pcmn VALUES (2910, 'Créances en comptes', 291, 'BE');
INSERT INTO tmp_pcmn VALUES (2911, 'Effets à recevoir', 291, 'BE');
INSERT INTO tmp_pcmn VALUES (2917, 'Créances douteuses', 291, 'BE');
INSERT INTO tmp_pcmn VALUES (2919, 'Réductions de valeurs actées(-)', 291, 'BE');
INSERT INTO tmp_pcmn VALUES (30, 'Approvisionements - Matières premières', 3, 'BE');
INSERT INTO tmp_pcmn VALUES (300, 'Valeur d''acquisition', 30, 'BE');
INSERT INTO tmp_pcmn VALUES (309, 'Réductions de valeur actées', 30, 'BE');
INSERT INTO tmp_pcmn VALUES (31, 'Approvisionnements - fournitures', 3, 'BE');
INSERT INTO tmp_pcmn VALUES (310, 'Valeur d''acquisition', 31, 'BE');
INSERT INTO tmp_pcmn VALUES (319, 'Réductions de valeurs actées(-)', 31, 'BE');
INSERT INTO tmp_pcmn VALUES (32, 'En-cours de fabrication', 3, 'BE');
INSERT INTO tmp_pcmn VALUES (320, 'Valeurs d''acquisition', 32, 'BE');
INSERT INTO tmp_pcmn VALUES (329, 'Réductions de valeur actées', 32, 'BE');
INSERT INTO tmp_pcmn VALUES (33, 'Produits finis', 3, 'BE');
INSERT INTO tmp_pcmn VALUES (330, 'Valeur d''acquisition', 33, 'BE');
INSERT INTO tmp_pcmn VALUES (339, 'Réductions de valeur actées', 33, 'BE');
INSERT INTO tmp_pcmn VALUES (34, 'Marchandises', 3, 'BE');
INSERT INTO tmp_pcmn VALUES (340, 'Valeur d''acquisition', 34, 'BE');
INSERT INTO tmp_pcmn VALUES (349, 'Réductions de valeur actées', 34, 'BE');
INSERT INTO tmp_pcmn VALUES (35, 'Immeubles destinés à la vente', 3, 'BE');
INSERT INTO tmp_pcmn VALUES (350, 'Valeur d''acquisition', 35, 'BE');
INSERT INTO tmp_pcmn VALUES (359, 'Réductions de valeur actées', 35, 'BE');
INSERT INTO tmp_pcmn VALUES (36, 'Acomptes versés sur achats pour stocks', 3, 'BE');
INSERT INTO tmp_pcmn VALUES (360, 'Valeur d''acquisition', 36, 'BE');
INSERT INTO tmp_pcmn VALUES (369, 'Réductions de valeur actées', 36, 'BE');
INSERT INTO tmp_pcmn VALUES (37, 'Commandes en cours éxécution', 3, 'BE');
INSERT INTO tmp_pcmn VALUES (370, 'Valeur d''acquisition', 37, 'BE');
INSERT INTO tmp_pcmn VALUES (371, 'Bénéfice pris en compte ', 37, 'BE');
INSERT INTO tmp_pcmn VALUES (379, 'Réductions de valeur actées', 37, 'BE');
INSERT INTO tmp_pcmn VALUES (40, 'Créances commerciales', 4, 'BE');
INSERT INTO tmp_pcmn VALUES (400, 'Clients', 40, 'BE');
INSERT INTO tmp_pcmn VALUES (401, 'Effets à recevoir', 40, 'BE');
INSERT INTO tmp_pcmn VALUES (404, 'Produits à recevoir', 40, 'BE');
INSERT INTO tmp_pcmn VALUES (406, 'Acomptes versés', 40, 'BE');
INSERT INTO tmp_pcmn VALUES (407, 'Créances douteuses', 40, 'BE');
INSERT INTO tmp_pcmn VALUES (409, 'Réductions de valeur actées', 40, 'BE');
INSERT INTO tmp_pcmn VALUES (41, 'Autres créances', 4, 'BE');
INSERT INTO tmp_pcmn VALUES (410, 'Capital appelé non versé', 41, 'BE');
INSERT INTO tmp_pcmn VALUES (411, 'TVA à récupérer', 41, 'BE');
INSERT INTO tmp_pcmn VALUES (412, 'Impôts et précomptes à récupérer', 41, 'BE');
INSERT INTO tmp_pcmn VALUES (4120, 'Impôt belge sur le résultat', 412, 'BE');
INSERT INTO tmp_pcmn VALUES (4121, 'Impôt belge sur le résultat', 412, 'BE');
INSERT INTO tmp_pcmn VALUES (4122, 'Impôt belge sur le résultat', 412, 'BE');
INSERT INTO tmp_pcmn VALUES (4123, 'Impôt belge sur le résultat', 412, 'BE');
INSERT INTO tmp_pcmn VALUES (4124, 'Impôt belge sur le résultat', 412, 'BE');
INSERT INTO tmp_pcmn VALUES (4125, 'Autres impôts et taxes belges', 412, 'BE');
INSERT INTO tmp_pcmn VALUES (4126, 'Autres impôts et taxes belges', 412, 'BE');
INSERT INTO tmp_pcmn VALUES (4127, 'Autres impôts et taxes belges', 412, 'BE');
INSERT INTO tmp_pcmn VALUES (4128, 'Impôts et taxes étrangers', 412, 'BE');
INSERT INTO tmp_pcmn VALUES (414, 'Produits à recevoir', 41, 'BE');
INSERT INTO tmp_pcmn VALUES (416, 'Créances diverses', 41, 'BE');
INSERT INTO tmp_pcmn VALUES (4160, 'Comptes de l''exploitant', 416, 'BE');
INSERT INTO tmp_pcmn VALUES (417, 'Créances douteuses', 41, 'BE');
INSERT INTO tmp_pcmn VALUES (418, 'Cautionnements versés en numéraires', 41, 'BE');
INSERT INTO tmp_pcmn VALUES (419, 'Réductions de valeur actées', 41, 'BE');
INSERT INTO tmp_pcmn VALUES (42, 'Dettes à plus dun an échéant dans l''année', 4, 'BE');
INSERT INTO tmp_pcmn VALUES (420, 'Emprunts subordonnés', 42, 'BE');
INSERT INTO tmp_pcmn VALUES (4200, 'convertibles', 420, 'BE');
INSERT INTO tmp_pcmn VALUES (4201, 'non convertibles', 420, 'BE');
INSERT INTO tmp_pcmn VALUES (421, 'Emprunts subordonnés', 42, 'BE');
INSERT INTO tmp_pcmn VALUES (4210, 'convertibles', 420, 'BE');
INSERT INTO tmp_pcmn VALUES (4211, 'non convertibles', 420, 'BE');
INSERT INTO tmp_pcmn VALUES (422, ' Dettes de locations financement', 42, 'BE');
INSERT INTO tmp_pcmn VALUES (423, ' Etablissement de crédit', 42, 'BE');
INSERT INTO tmp_pcmn VALUES (4230, 'Dettes en comptes', 423, 'BE');
INSERT INTO tmp_pcmn VALUES (4231, 'Promesses', 423, 'BE');
INSERT INTO tmp_pcmn VALUES (4232, 'Crédits d''acceptation', 423, 'BE');
INSERT INTO tmp_pcmn VALUES (424, 'Autres emprunts', 42, 'BE');
INSERT INTO tmp_pcmn VALUES (425, 'Dettes commerciales', 42, 'BE');
INSERT INTO tmp_pcmn VALUES (4250, 'Fournisseurs', 425, 'BE');
INSERT INTO tmp_pcmn VALUES (4251, 'Effets à payer', 425, 'BE');
INSERT INTO tmp_pcmn VALUES (426, 'Acomptes reçus sur commandes', 42, 'BE');
INSERT INTO tmp_pcmn VALUES (428, 'Cautionnement reçus en numéraires', 42, 'BE');
INSERT INTO tmp_pcmn VALUES (429, 'Dettes diverses', 42, 'BE');
INSERT INTO tmp_pcmn VALUES (43, 'Dettes financières', 4, 'BE');
INSERT INTO tmp_pcmn VALUES (430, 'Etablissements de crédit - Emprunts à compte à terme fixe', 43, 'BE');
INSERT INTO tmp_pcmn VALUES (431, 'Etablissements de crédit - Promesses', 43, 'BE');
INSERT INTO tmp_pcmn VALUES (432, ' Etablissements de crédit - Crédits d''acceptation', 43, 'BE');
INSERT INTO tmp_pcmn VALUES (433, 'Etablissements de crédit -Dettes en comptes courant', 43, 'BE');
INSERT INTO tmp_pcmn VALUES (439, 'Autres emprunts', 43, 'BE');
INSERT INTO tmp_pcmn VALUES (44, 'Dettes commerciales', 4, 'BE');
INSERT INTO tmp_pcmn VALUES (440, 'Fournisseurs', 44, 'BE');
INSERT INTO tmp_pcmn VALUES (441, 'Effets à payer', 44, 'BE');
INSERT INTO tmp_pcmn VALUES (444, 'Factures à recevoir', 44, 'BE');
INSERT INTO tmp_pcmn VALUES (45, 'Dettes fiscales, salariales et sociales', 4, 'BE');
INSERT INTO tmp_pcmn VALUES (450, 'Dettes fiscales estimées', 45, 'BE');
INSERT INTO tmp_pcmn VALUES (4500, 'Impôts belges sur le résultat', 450, 'BE');
INSERT INTO tmp_pcmn VALUES (4501, 'Impôts belges sur le résultat', 450, 'BE');
INSERT INTO tmp_pcmn VALUES (4502, 'Impôts belges sur le résultat', 450, 'BE');
INSERT INTO tmp_pcmn VALUES (4503, 'Impôts belges sur le résultat', 450, 'BE');
INSERT INTO tmp_pcmn VALUES (4504, 'Impôts belges sur le résultat', 450, 'BE');
INSERT INTO tmp_pcmn VALUES (4505, 'Autres impôts et taxes belges', 450, 'BE');
INSERT INTO tmp_pcmn VALUES (4506, 'Autres impôts et taxes belges', 450, 'BE');
INSERT INTO tmp_pcmn VALUES (4507, 'Autres impôts et taxes belges', 450, 'BE');
INSERT INTO tmp_pcmn VALUES (4508, 'Impôts et taxes étrangers', 450, 'BE');
INSERT INTO tmp_pcmn VALUES (451, 'TVA à payer', 45, 'BE');
INSERT INTO tmp_pcmn VALUES (452, 'Impôts et taxes à payer', 45, 'BE');
INSERT INTO tmp_pcmn VALUES (4520, 'Impôts belges sur le résultat', 452, 'BE');
INSERT INTO tmp_pcmn VALUES (4521, 'Impôts belges sur le résultat', 452, 'BE');
INSERT INTO tmp_pcmn VALUES (4522, 'Impôts belges sur le résultat', 452, 'BE');
INSERT INTO tmp_pcmn VALUES (4523, 'Impôts belges sur le résultat', 452, 'BE');
INSERT INTO tmp_pcmn VALUES (4524, 'Impôts belges sur le résultat', 452, 'BE');
INSERT INTO tmp_pcmn VALUES (4525, 'Autres impôts et taxes belges', 452, 'BE');
INSERT INTO tmp_pcmn VALUES (4526, 'Autres impôts et taxes belges', 452, 'BE');
INSERT INTO tmp_pcmn VALUES (4527, 'Autres impôts et taxes belges', 452, 'BE');
INSERT INTO tmp_pcmn VALUES (4528, 'Impôts et taxes étrangers', 452, 'BE');
INSERT INTO tmp_pcmn VALUES (453, 'Précomptes retenus', 45, 'BE');
INSERT INTO tmp_pcmn VALUES (454, 'Office National de la Sécurité Sociales', 45, 'BE');
INSERT INTO tmp_pcmn VALUES (455, 'Rémunérations', 45, 'BE');
INSERT INTO tmp_pcmn VALUES (456, 'Pécules de vacances', 45, 'BE');
INSERT INTO tmp_pcmn VALUES (459, 'Autres dettes sociales', 45, 'BE');
INSERT INTO tmp_pcmn VALUES (46, 'Acomptes reçus sur commandes', 4, 'BE');
INSERT INTO tmp_pcmn VALUES (47, 'Dettes découlant de l''affectation du résultat', 4, 'BE');
INSERT INTO tmp_pcmn VALUES (470, 'Dividendes et tantièmes d''exercices antérieurs', 47, 'BE');
INSERT INTO tmp_pcmn VALUES (471, 'Dividendes de l''exercice', 47, 'BE');
INSERT INTO tmp_pcmn VALUES (472, 'Tantièmes de l''exercice', 47, 'BE');
INSERT INTO tmp_pcmn VALUES (473, 'Autres allocataires', 47, 'BE');
INSERT INTO tmp_pcmn VALUES (48, 'Dettes diverses', 4, 'BE');
INSERT INTO tmp_pcmn VALUES (480, 'Obligations et coupons échus', 48, 'BE');
INSERT INTO tmp_pcmn VALUES (488, 'Cautionnements reçus en numéraires', 48, 'BE');
INSERT INTO tmp_pcmn VALUES (489, 'Autres dettes diverses', 48, 'BE');
INSERT INTO tmp_pcmn VALUES (4890, 'Compte de l''exploitant', 489, 'BE');
INSERT INTO tmp_pcmn VALUES (49, 'Comptes de régularisation', 4, 'BE');
INSERT INTO tmp_pcmn VALUES (490, 'Charges à reporter', 49, 'BE');
INSERT INTO tmp_pcmn VALUES (491, 'Produits acquis', 49, 'BE');
INSERT INTO tmp_pcmn VALUES (492, 'Charges à imputer', 49, 'BE');
INSERT INTO tmp_pcmn VALUES (493, 'Produits à reporter', 49, 'BE');
INSERT INTO tmp_pcmn VALUES (499, 'Comptes d''attentes', 49, 'BE');
INSERT INTO tmp_pcmn VALUES (50, 'Actions propres', 5, 'BE');
INSERT INTO tmp_pcmn VALUES (51, 'Actions et parts', 5, 'BE');
INSERT INTO tmp_pcmn VALUES (510, 'Valeur d''acquisition', 51, 'BE');
INSERT INTO tmp_pcmn VALUES (511, 'Montant non appelés', 51, 'BE');
INSERT INTO tmp_pcmn VALUES (519, 'Réductions de valeur actées', 51, 'BE');
INSERT INTO tmp_pcmn VALUES (52, 'Titres à revenu fixe', 5, 'BE');
INSERT INTO tmp_pcmn VALUES (520, 'Valeur d''acquisition', 52, 'BE');
INSERT INTO tmp_pcmn VALUES (529, 'Réductions de valeur actées', 52, 'BE');
INSERT INTO tmp_pcmn VALUES (53, 'Dépôts à terme', 5, 'BE');
INSERT INTO tmp_pcmn VALUES (530, 'de plus d''un an', 53, 'BE');
INSERT INTO tmp_pcmn VALUES (531, 'de plus d''un mois et d''un an au plus', 53, 'BE');
INSERT INTO tmp_pcmn VALUES (532, 'd''un mois au plus', 53, 'BE');
INSERT INTO tmp_pcmn VALUES (539, 'Réductions de valeur actées', 53, 'BE');
INSERT INTO tmp_pcmn VALUES (54, 'Valeurs échues à l''encaissement', 5, 'BE');
INSERT INTO tmp_pcmn VALUES (55, 'Etablissement de crédit', 5, 'BE');
INSERT INTO tmp_pcmn VALUES (550, 'Banque 1', 55, 'BE');
INSERT INTO tmp_pcmn VALUES (5500, 'Comptes courants', 550, 'BE');
INSERT INTO tmp_pcmn VALUES (5501, 'Chèques émis (-)', 550, 'BE');
INSERT INTO tmp_pcmn VALUES (5509, 'Réduction de valeur actée', 550, 'BE');
INSERT INTO tmp_pcmn VALUES (5510, 'Comptes courants', 551, 'BE');
INSERT INTO tmp_pcmn VALUES (5511, 'Chèques émis (-)', 551, 'BE');
INSERT INTO tmp_pcmn VALUES (5519, 'Réduction de valeur actée', 551, 'BE');
INSERT INTO tmp_pcmn VALUES (5520, 'Comptes courants', 552, 'BE');
INSERT INTO tmp_pcmn VALUES (5521, 'Chèques émis (-)', 552, 'BE');
INSERT INTO tmp_pcmn VALUES (5529, 'Réduction de valeur actée', 552, 'BE');
INSERT INTO tmp_pcmn VALUES (5530, 'Comptes courants', 553, 'BE');
INSERT INTO tmp_pcmn VALUES (5531, 'Chèques émis (-)', 553, 'BE');
INSERT INTO tmp_pcmn VALUES (5539, 'Réduction de valeur actée', 553, 'BE');
INSERT INTO tmp_pcmn VALUES (5540, 'Comptes courants', 554, 'BE');
INSERT INTO tmp_pcmn VALUES (5541, 'Chèques émis (-)', 554, 'BE');
INSERT INTO tmp_pcmn VALUES (5549, 'Réduction de valeur actée', 554, 'BE');
INSERT INTO tmp_pcmn VALUES (5550, 'Comptes courants', 555, 'BE');
INSERT INTO tmp_pcmn VALUES (5551, 'Chèques émis (-)', 555, 'BE');
INSERT INTO tmp_pcmn VALUES (5559, 'Réduction de valeur actée', 555, 'BE');
INSERT INTO tmp_pcmn VALUES (5560, 'Comptes courants', 556, 'BE');
INSERT INTO tmp_pcmn VALUES (5561, 'Chèques émis (-)', 556, 'BE');
INSERT INTO tmp_pcmn VALUES (5569, 'Réduction de valeur actée', 556, 'BE');
INSERT INTO tmp_pcmn VALUES (5570, 'Comptes courants', 557, 'BE');
INSERT INTO tmp_pcmn VALUES (5571, 'Chèques émis (-)', 557, 'BE');
INSERT INTO tmp_pcmn VALUES (5579, 'Réduction de valeur actée', 557, 'BE');
INSERT INTO tmp_pcmn VALUES (5580, 'Comptes courants', 558, 'BE');
INSERT INTO tmp_pcmn VALUES (5581, 'Chèques émis (-)', 558, 'BE');
INSERT INTO tmp_pcmn VALUES (5589, 'Réduction de valeur actée', 558, 'BE');
INSERT INTO tmp_pcmn VALUES (5590, 'Comptes courants', 559, 'BE');
INSERT INTO tmp_pcmn VALUES (5591, 'Chèques émis (-)', 559, 'BE');
INSERT INTO tmp_pcmn VALUES (5599, 'Réduction de valeur actée', 559, 'BE');
INSERT INTO tmp_pcmn VALUES (56, 'Office des chèques postaux', 5, 'BE');
INSERT INTO tmp_pcmn VALUES (560, 'Compte courant', 56, 'BE');
INSERT INTO tmp_pcmn VALUES (561, 'Chèques émis', 56, 'BE');
INSERT INTO tmp_pcmn VALUES (57, 'Caisses', 5, 'BE');
INSERT INTO tmp_pcmn VALUES (578, 'Caisse timbre', 57, 'BE');
INSERT INTO tmp_pcmn VALUES (58, 'Virement interne', 5, 'BE');
INSERT INTO tmp_pcmn VALUES (60, 'Approvisionnement et marchandises', 6, 'BE');
INSERT INTO tmp_pcmn VALUES (600, 'Achats de matières premières', 60, 'BE');
INSERT INTO tmp_pcmn VALUES (601, 'Achats de fournitures', 60, 'BE');
INSERT INTO tmp_pcmn VALUES (602, 'Achats de services, travaux et études', 60, 'BE');
INSERT INTO tmp_pcmn VALUES (603, 'Sous-traitances générales', 60, 'BE');
INSERT INTO tmp_pcmn VALUES (604, 'Achats de marchandises', 60, 'BE');
INSERT INTO tmp_pcmn VALUES (605, 'Achats d''immeubles destinés à la vente', 60, 'BE');
INSERT INTO tmp_pcmn VALUES (608, 'Remises, ristournes et rabais obtenus(-)', 60, 'BE');
INSERT INTO tmp_pcmn VALUES (609, 'Variation de stock', 60, 'BE');
INSERT INTO tmp_pcmn VALUES (6090, 'de matières premières', 609, 'BE');
INSERT INTO tmp_pcmn VALUES (6091, 'de fournitures', 609, 'BE');
INSERT INTO tmp_pcmn VALUES (6094, 'de marchandises', 609, 'BE');
INSERT INTO tmp_pcmn VALUES (6095, 'immeubles achetés destinés à la vente', 609, 'BE');
INSERT INTO tmp_pcmn VALUES (61, 'Services et biens divers', 6, 'BE');
INSERT INTO tmp_pcmn VALUES (62, 'Rémunérations, charges sociales et pensions', 6, 'BE');
INSERT INTO tmp_pcmn VALUES (620, 'Rémunérations et avantages sociaux directs', 62, 'BE');
INSERT INTO tmp_pcmn VALUES (6200, 'Administrateurs ou gérants', 620, 'BE');
INSERT INTO tmp_pcmn VALUES (6201, 'Personnel de directions', 620, 'BE');
INSERT INTO tmp_pcmn VALUES (6202, 'Employés,620', 6202, 'BE');
INSERT INTO tmp_pcmn VALUES (6203, 'Ouvriers', 620, 'BE');
INSERT INTO tmp_pcmn VALUES (6204, 'Autres membres du personnel', 620, 'BE');
INSERT INTO tmp_pcmn VALUES (621, 'Cotisations patronales d''assurances sociales', 62, 'BE');
INSERT INTO tmp_pcmn VALUES (622, 'Primes partonales pour assurances extra-légales', 62, 'BE');
INSERT INTO tmp_pcmn VALUES (623, 'Autres frais de personnel', 62, 'BE');
INSERT INTO tmp_pcmn VALUES (624, 'Pensions de retraite et de survie', 62, 'BE');
INSERT INTO tmp_pcmn VALUES (6240, 'Administrateurs ou gérants', 624, 'BE');
INSERT INTO tmp_pcmn VALUES (6241, 'Personnel', 624, 'BE');
INSERT INTO tmp_pcmn VALUES (63, 'Amortissements, réductions de valeurs et provisions pour risques et charges', 6, 'BE');
INSERT INTO tmp_pcmn VALUES (630, 'Dotations aux amortissements et réduction de valeurs sur immobilisations', 63, 'BE');
INSERT INTO tmp_pcmn VALUES (6300, ' Dotations aux amortissements sur frais d''établissement', 630, 'BE');
INSERT INTO tmp_pcmn VALUES (705, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn VALUES (6301, 'Dotations aux amortissements sur immobilisations incorporelles', 630, 'BE');
INSERT INTO tmp_pcmn VALUES (6302, 'Dotations aux amortissements sur immobilisations corporelles', 630, 'BE');
INSERT INTO tmp_pcmn VALUES (6308, 'Dotations aux réductions de valeur sur immobilisations incorporelles', 630, 'BE');
INSERT INTO tmp_pcmn VALUES (6309, 'Dotations aux réductions de valeur sur immobilisations corporelles', 630, 'BE');
INSERT INTO tmp_pcmn VALUES (631, 'Réductions de valeur sur stocks', 63, 'BE');
INSERT INTO tmp_pcmn VALUES (6310, 'Dotations', 631, 'BE');
INSERT INTO tmp_pcmn VALUES (6311, 'Reprises(-)', 631, 'BE');
INSERT INTO tmp_pcmn VALUES (632, 'Réductions de valeur sur commande en cours d''éxécution', 63, 'BE');
INSERT INTO tmp_pcmn VALUES (6320, 'Dotations', 632, 'BE');
INSERT INTO tmp_pcmn VALUES (6321, 'Reprises(-)', 632, 'BE');
INSERT INTO tmp_pcmn VALUES (633, 'Réductions de valeurs sur créances commerciales à plus d''un an', 63, 'BE');
INSERT INTO tmp_pcmn VALUES (6330, 'Dotations', 633, 'BE');
INSERT INTO tmp_pcmn VALUES (6331, 'Reprises(-)', 633, 'BE');
INSERT INTO tmp_pcmn VALUES (634, 'Réductions de valeur sur créances commerciales à un an au plus', 63, 'BE');
INSERT INTO tmp_pcmn VALUES (6340, 'Dotations', 634, 'BE');
INSERT INTO tmp_pcmn VALUES (6341, 'Reprise', 634, 'BE');
INSERT INTO tmp_pcmn VALUES (635, 'Provisions pour pensions et obligations similaires', 63, 'BE');
INSERT INTO tmp_pcmn VALUES (6350, 'Dotations', 635, 'BE');
INSERT INTO tmp_pcmn VALUES (6351, 'Utilisation et reprises', 635, 'BE');
INSERT INTO tmp_pcmn VALUES (636, 'Provisions pour grosses réparations et gros entretien', 63, 'BE');
INSERT INTO tmp_pcmn VALUES (6360, 'Dotations', 636, 'BE');
INSERT INTO tmp_pcmn VALUES (6361, 'Reprises(-)', 636, 'BE');
INSERT INTO tmp_pcmn VALUES (637, 'Provisions pour autres risques et charges', 63, 'BE');
INSERT INTO tmp_pcmn VALUES (6370, 'Dotations', 637, 'BE');
INSERT INTO tmp_pcmn VALUES (6371, 'Reprises(-)', 637, 'BE');
INSERT INTO tmp_pcmn VALUES (64, 'Autres charges d''exploitation', 6, 'BE');
INSERT INTO tmp_pcmn VALUES (640, 'Charges fiscales d''exploitation', 64, 'BE');
INSERT INTO tmp_pcmn VALUES (641, 'Moins-values sur réalisations courantes d''immobilisations corporelles', 64, 'BE');
INSERT INTO tmp_pcmn VALUES (642, 'Moins-value sur réalisation de créances commerciales', 64, 'BE');
INSERT INTO tmp_pcmn VALUES (643, 'Charges d''exploitations', 64, 'BE');
INSERT INTO tmp_pcmn VALUES (644, 'Charges d''exploitations', 64, 'BE');
INSERT INTO tmp_pcmn VALUES (645, 'Charges d''exploitations', 64, 'BE');
INSERT INTO tmp_pcmn VALUES (646, 'Charges d''exploitations', 64, 'BE');
INSERT INTO tmp_pcmn VALUES (647, 'Charges d''exploitations', 64, 'BE');
INSERT INTO tmp_pcmn VALUES (648, 'Charges d''exploitations', 64, 'BE');
INSERT INTO tmp_pcmn VALUES (649, 'Charges d''exploitation portées à l''actif au titre de frais de restructuration(-)', 64, 'BE');
INSERT INTO tmp_pcmn VALUES (65, 'Charges financières', 6, 'BE');
INSERT INTO tmp_pcmn VALUES (650, 'Charges des dettes', 65, 'BE');
INSERT INTO tmp_pcmn VALUES (6500, 'Intérêts, commmissions et frais afférents aux dettes', 650, 'BE');
INSERT INTO tmp_pcmn VALUES (6501, 'Amortissements des frais d''émissions d''emrunts et des primes de remboursement', 650, 'BE');
INSERT INTO tmp_pcmn VALUES (6502, 'Autres charges des dettes', 650, 'BE');
INSERT INTO tmp_pcmn VALUES (6503, 'Intérêts intercalaires portés à l''actif(-)', 650, 'BE');
INSERT INTO tmp_pcmn VALUES (651, 'Réductions de valeur sur actifs circulants', 65, 'BE');
INSERT INTO tmp_pcmn VALUES (6510, 'Dotations', 651, 'BE');
INSERT INTO tmp_pcmn VALUES (6511, 'Reprises(-)', 651, 'BE');
INSERT INTO tmp_pcmn VALUES (652, 'Moins-value sur réalisation d''actifs circulants', 65, 'BE');
INSERT INTO tmp_pcmn VALUES (653, 'Charges d''escompte de créances', 65, 'BE');
INSERT INTO tmp_pcmn VALUES (654, 'Différences de changes', 65, 'BE');
INSERT INTO tmp_pcmn VALUES (655, 'Ecarts de conversion des devises', 65, 'BE');
INSERT INTO tmp_pcmn VALUES (656, 'Charges financières diverses', 65, 'BE');
INSERT INTO tmp_pcmn VALUES (657, 'Charges financières diverses', 65, 'BE');
INSERT INTO tmp_pcmn VALUES (658, 'Charges financières diverses', 65, 'BE');
INSERT INTO tmp_pcmn VALUES (659, 'Charges financières diverses', 65, 'BE');
INSERT INTO tmp_pcmn VALUES (66, 'Charges exceptionnelles', 6, 'BE');
INSERT INTO tmp_pcmn VALUES (660, 'Amortissements et réductions de valeur exceptionnels (dotations)', 66, 'BE');
INSERT INTO tmp_pcmn VALUES (6600, 'sur frais d''établissement', 660, 'BE');
INSERT INTO tmp_pcmn VALUES (6601, 'sur immobilisations incorporelles', 660, 'BE');
INSERT INTO tmp_pcmn VALUES (6602, 'sur immobilisations corporelles', 660, 'BE');
INSERT INTO tmp_pcmn VALUES (661, 'Réductions de valeur sur immobilisations financières (dotations)', 66, 'BE');
INSERT INTO tmp_pcmn VALUES (662, 'Provisions pour risques et charges exceptionnels', 66, 'BE');
INSERT INTO tmp_pcmn VALUES (663, 'Moins-values sur réalisations d''actifs immobilisés', 66, 'BE');
INSERT INTO tmp_pcmn VALUES (664, 'Autres charges exceptionnelles', 66, 'BE');
INSERT INTO tmp_pcmn VALUES (665, 'Autres charges exceptionnelles', 66, 'BE');
INSERT INTO tmp_pcmn VALUES (666, 'Autres charges exceptionnelles', 66, 'BE');
INSERT INTO tmp_pcmn VALUES (667, 'Autres charges exceptionnelles', 66, 'BE');
INSERT INTO tmp_pcmn VALUES (668, 'Autres charges exceptionnelles', 66, 'BE');
INSERT INTO tmp_pcmn VALUES (669, ' Charges exceptionnelles portées à l''actif au titre de frais de restructuration', 66, 'BE');
INSERT INTO tmp_pcmn VALUES (67, 'impôts sur le résultat', 6, 'BE');
INSERT INTO tmp_pcmn VALUES (670, 'Impôts belge sur le résultat de l''exercice', 67, 'BE');
INSERT INTO tmp_pcmn VALUES (6700, 'Impôts et précomptes dus ou versés', 670, 'BE');
INSERT INTO tmp_pcmn VALUES (6701, 'Excédents de versement d''impôts et de précomptes portés à l''actifs (-)', 670, 'BE');
INSERT INTO tmp_pcmn VALUES (6702, 'Charges fiscales estimées', 670, 'BE');
INSERT INTO tmp_pcmn VALUES (671, 'Impôts belges sur le résultats d''exercices antérieures', 67, 'BE');
INSERT INTO tmp_pcmn VALUES (6710, 'Suppléments d''impôt dus ou versés', 671, 'BE');
INSERT INTO tmp_pcmn VALUES (6711, 'Suppléments d''impôts estimés', 671, 'BE');
INSERT INTO tmp_pcmn VALUES (6712, 'Provisions fiscales constituées', 671, 'BE');
INSERT INTO tmp_pcmn VALUES (672, 'Impôts étrangers sur le résultat de l''exercice', 67, 'BE');
INSERT INTO tmp_pcmn VALUES (673, 'Impôts étrangers sur le résultat d''exercice antérieures', 67, 'BE');
INSERT INTO tmp_pcmn VALUES (68, 'Transferts aux réserves immunisées', 6, 'BE');
INSERT INTO tmp_pcmn VALUES (69, 'Affectations et prélévements', 6, 'BE');
INSERT INTO tmp_pcmn VALUES (690, 'Perte reportée de l''exercice précédent', 69, 'BE');
INSERT INTO tmp_pcmn VALUES (691, 'Dotation à la réserve légale', 69, 'BE');
INSERT INTO tmp_pcmn VALUES (692, 'Dotation aux autres réserves', 69, 'BE');
INSERT INTO tmp_pcmn VALUES (693, 'Bénéfice à reporter', 69, 'BE');
INSERT INTO tmp_pcmn VALUES (694, 'Rémunération du capital', 69, 'BE');
INSERT INTO tmp_pcmn VALUES (695, 'Administrateurs ou gérants', 69, 'BE');
INSERT INTO tmp_pcmn VALUES (696, 'Autres allocataires', 69, 'BE');
INSERT INTO tmp_pcmn VALUES (70, 'Chiffre d''affaire', 7, 'BE');
INSERT INTO tmp_pcmn VALUES (700, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn VALUES (701, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn VALUES (702, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn VALUES (703, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn VALUES (704, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn VALUES (706, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn VALUES (707, 'Ventes et prestations de services', 70, 'BE');
INSERT INTO tmp_pcmn VALUES (709, 'Remises, ristournes et rabais accordés(-)', 70, 'BE');
INSERT INTO tmp_pcmn VALUES (71, 'Variations des stocks et commandes en cours d''éxécution', 7, 'BE');
INSERT INTO tmp_pcmn VALUES (712, 'des en-cours de fabrication', 71, 'BE');
INSERT INTO tmp_pcmn VALUES (713, 'des produits finis', 71, 'BE');
INSERT INTO tmp_pcmn VALUES (715, 'des immeubles construits destinés à la vente', 71, 'BE');
INSERT INTO tmp_pcmn VALUES (717, ' des commandes  en cours d''éxécution', 71, 'BE');
INSERT INTO tmp_pcmn VALUES (7170, 'Valeur d''acquisition', 717, 'BE');
INSERT INTO tmp_pcmn VALUES (7171, 'Bénéfice pris en compte', 717, 'BE');
INSERT INTO tmp_pcmn VALUES (72, 'Production immobilisée', 7, 'BE');
INSERT INTO tmp_pcmn VALUES (74, 'Autres produits d''exploitation', 7, 'BE');
INSERT INTO tmp_pcmn VALUES (740, 'Subsides d'' exploitation  et montants compensatoires', 74, 'BE');
INSERT INTO tmp_pcmn VALUES (741, 'Plus-values sur réalisation courantes d'' immobilisations corporelles', 74, 'BE');
INSERT INTO tmp_pcmn VALUES (742, 'Plus-values sur réalisations de créances commerciales', 74, 'BE');
INSERT INTO tmp_pcmn VALUES (743, 'Produits d''exploitations divers', 74, 'BE');
INSERT INTO tmp_pcmn VALUES (744, 'Produits d''exploitations divers', 74, 'BE');
INSERT INTO tmp_pcmn VALUES (745, 'Produits d''exploitations divers', 74, 'BE');
INSERT INTO tmp_pcmn VALUES (746, 'Produits d''exploitations divers', 74, 'BE');
INSERT INTO tmp_pcmn VALUES (747, 'Produits d''exploitations divers', 74, 'BE');
INSERT INTO tmp_pcmn VALUES (748, 'Produits d''exploitations divers', 74, 'BE');
INSERT INTO tmp_pcmn VALUES (749, 'Produits d''exploitations divers', 74, 'BE');
INSERT INTO tmp_pcmn VALUES (75, 'Produits financiers', 7, 'BE');
INSERT INTO tmp_pcmn VALUES (750, 'Produits sur immobilisations financières', 75, 'BE');
INSERT INTO tmp_pcmn VALUES (751, 'Produits des actifs circulants', 75, 'BE');
INSERT INTO tmp_pcmn VALUES (752, 'Plus-value sur réalisations d''actis circulants', 75, 'BE');
INSERT INTO tmp_pcmn VALUES (753, 'Subsides en capital et intérêts', 75, 'BE');
INSERT INTO tmp_pcmn VALUES (754, 'Différences de change', 75, 'BE');
INSERT INTO tmp_pcmn VALUES (755, 'Ecarts de conversion des devises', 75, 'BE');
INSERT INTO tmp_pcmn VALUES (756, 'Produits financiers divers', 75, 'BE');
INSERT INTO tmp_pcmn VALUES (757, 'Produits financiers divers', 75, 'BE');
INSERT INTO tmp_pcmn VALUES (758, 'Produits financiers divers', 75, 'BE');
INSERT INTO tmp_pcmn VALUES (759, 'Produits financiers divers', 75, 'BE');
INSERT INTO tmp_pcmn VALUES (76, 'Produits exceptionnels', 7, 'BE');
INSERT INTO tmp_pcmn VALUES (760, 'Reprise d''amortissements et de réductions de valeur', 76, 'BE');
INSERT INTO tmp_pcmn VALUES (7601, 'sur immobilisations corporelles', 760, 'BE');
INSERT INTO tmp_pcmn VALUES (7602, 'sur immobilisations incorporelles', 760, 'BE');
INSERT INTO tmp_pcmn VALUES (761, 'Reprises de réductions de valeur sur immobilisations financières', 76, 'BE');
INSERT INTO tmp_pcmn VALUES (762, 'Reprises de provisions pour risques et charges exceptionnels', 76, 'BE');
INSERT INTO tmp_pcmn VALUES (763, 'Plus-value sur réalisation d''actifs immobilisé', 76, 'BE');
INSERT INTO tmp_pcmn VALUES (764, 'Autres produits exceptionnels', 76, 'BE');
INSERT INTO tmp_pcmn VALUES (765, 'Autres produits exceptionnels', 76, 'BE');
INSERT INTO tmp_pcmn VALUES (766, 'Autres produits exceptionnels', 76, 'BE');
INSERT INTO tmp_pcmn VALUES (767, 'Autres produits exceptionnels', 76, 'BE');
INSERT INTO tmp_pcmn VALUES (768, 'Autres produits exceptionnels', 76, 'BE');
INSERT INTO tmp_pcmn VALUES (769, 'Autres produits exceptionnels', 76, 'BE');
INSERT INTO tmp_pcmn VALUES (77, 'Régularisations d''impôts et reprises de provisions fiscales', 7, 'BE');
INSERT INTO tmp_pcmn VALUES (771, 'impôts belges sur le résultat', 77, 'BE');
INSERT INTO tmp_pcmn VALUES (7710, 'Régularisations d''impôts dus ou versé', 771, 'BE');
INSERT INTO tmp_pcmn VALUES (7711, 'Régularisations d''impôts estimés', 771, 'BE');
INSERT INTO tmp_pcmn VALUES (7712, 'Reprises de provisions fiscales', 771, 'BE');
INSERT INTO tmp_pcmn VALUES (773, 'Impôts étrangers sur le résultats', 77, 'BE');
INSERT INTO tmp_pcmn VALUES (79, 'Affectations et prélévements', 7, 'BE');
INSERT INTO tmp_pcmn VALUES (790, 'Bénéfice reporté de l''exercice précédent', 79, 'BE');
INSERT INTO tmp_pcmn VALUES (791, 'Prélévement sur le capital et les primes d''émission', 79, 'BE');
INSERT INTO tmp_pcmn VALUES (792, 'Prélévement sur les réserves', 79, 'BE');
INSERT INTO tmp_pcmn VALUES (793, 'Perte à reporter', 79, 'BE');
INSERT INTO tmp_pcmn VALUES (794, 'Intervention d''associés (ou du propriétaire) dans la perte', 79, 'BE');
INSERT INTO tmp_pcmn VALUES (1, 'Fonds propres, provisions pour risques et charges à plus d''un an', 0, 'BE');
INSERT INTO tmp_pcmn VALUES (2, 'Frais d''établissement, actifs immobilisés et créances à plus d''un an', 0, 'BE');
INSERT INTO tmp_pcmn VALUES (3, 'Stocks et commandes en cours d''éxécution', 0, 'BE');
INSERT INTO tmp_pcmn VALUES (4, 'Créances et dettes à un an au plus', 0, 'BE');
INSERT INTO tmp_pcmn VALUES (5, 'Placements de trésorerie et valeurs disponibles', 0, 'BE');
INSERT INTO tmp_pcmn VALUES (6, 'Charges', 0, 'BE');
INSERT INTO tmp_pcmn VALUES (7, 'Produits', 0, 'BE');
INSERT INTO tmp_pcmn VALUES (4000001, 'Client 1', 400, 'BE');
INSERT INTO tmp_pcmn VALUES (4000002, 'Client 2', 400, 'BE');
INSERT INTO tmp_pcmn VALUES (70001, 'Marchandise A', 700, 'BE');
INSERT INTO tmp_pcmn VALUES (70002, 'Marchandise B', 700, 'BE');
INSERT INTO tmp_pcmn VALUES (70003, 'Marchandise C', 700, 'BE');
INSERT INTO tmp_pcmn VALUES (70004, 'Marchandise D', 700, 'BE');
INSERT INTO tmp_pcmn VALUES (4400001, 'Fournisseur A', 440, 'BE');
INSERT INTO tmp_pcmn VALUES (4400002, 'Fournisseur B', 440, 'BE');
INSERT INTO tmp_pcmn VALUES (610001, 'fourniture A', 61, 'BE');
INSERT INTO tmp_pcmn VALUES (55000002, 'Argenta', 5500, 'BE');
INSERT INTO tmp_pcmn VALUES (55000001, 'Banque 1', 5500, 'BE');
INSERT INTO tmp_pcmn VALUES (4000003, 'Client fiche', 400, 'BE');
INSERT INTO tmp_pcmn VALUES (4000004, 'Toto', 400, 'BE');
INSERT INTO tmp_pcmn VALUES (4000005, 'NOUVEAU CLIENT', 400, 'BE');
INSERT INTO tmp_pcmn VALUES (610002, 'Loyer', 61, 'BE');
INSERT INTO tmp_pcmn VALUES (4511, 'TVA à payer 21%', 451, 'BE');
INSERT INTO tmp_pcmn VALUES (4512, 'TVA à payer 12%', 451, 'BE');
INSERT INTO tmp_pcmn VALUES (4513, 'TVA à payer 6%', 451, 'BE');
INSERT INTO tmp_pcmn VALUES (4514, 'TVA à payer 0%', 451, 'BE');
INSERT INTO tmp_pcmn VALUES (4111, 'TVA à récupérer 21%', 411, 'BE');
INSERT INTO tmp_pcmn VALUES (4112, 'TVA à récupérer 12%', 411, 'BE');
INSERT INTO tmp_pcmn VALUES (4113, 'TVA à récupérer 6% ', 411, 'BE');
INSERT INTO tmp_pcmn VALUES (4114, 'TVA à récupérer 0%', 411, 'BE');


--
-- Data for TOC entry 126 (OID 34891)
-- Name: version; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO "version" VALUES (3);


--
-- Data for TOC entry 127 (OID 34897)
-- Name: parm_money; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO parm_money VALUES (1, 'EUR', 1);


--
-- Data for TOC entry 128 (OID 34900)
-- Name: parm_periode; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO parm_periode VALUES (1, '2003-01-01', '2003-01-31', '2003', false);
INSERT INTO parm_periode VALUES (2, '2003-02-01', '2003-02-28', '2003', false);
INSERT INTO parm_periode VALUES (3, '2003-03-01', '2003-03-31', '2003', false);
INSERT INTO parm_periode VALUES (4, '2003-04-01', '2003-04-30', '2003', false);
INSERT INTO parm_periode VALUES (5, '2003-05-01', '2003-05-31', '2003', false);
INSERT INTO parm_periode VALUES (6, '2003-06-01', '2003-06-30', '2003', false);
INSERT INTO parm_periode VALUES (7, '2003-07-01', '2003-07-31', '2003', false);
INSERT INTO parm_periode VALUES (8, '2003-08-01', '2003-08-31', '2003', false);
INSERT INTO parm_periode VALUES (9, '2003-09-01', '2003-09-30', '2003', false);
INSERT INTO parm_periode VALUES (10, '2003-10-01', '2003-10-30', '2003', false);
INSERT INTO parm_periode VALUES (11, '2003-11-01', '2003-11-30', '2003', false);
INSERT INTO parm_periode VALUES (12, '2003-12-01', '2003-12-31', '2003', false);
INSERT INTO parm_periode VALUES (13, '2003-12-31', NULL, '2003', false);
INSERT INTO parm_periode VALUES (27, '2004-01-01', '2004-01-31', '2004', false);
INSERT INTO parm_periode VALUES (28, '2004-02-01', '2004-02-28', '2004', false);
INSERT INTO parm_periode VALUES (29, '2004-03-01', '2004-03-31', '2004', false);
INSERT INTO parm_periode VALUES (30, '2004-04-01', '2004-04-30', '2004', false);
INSERT INTO parm_periode VALUES (31, '2004-05-01', '2004-05-31', '2004', false);
INSERT INTO parm_periode VALUES (32, '2004-06-01', '2004-06-30', '2004', false);
INSERT INTO parm_periode VALUES (33, '2004-07-01', '2004-07-31', '2004', false);
INSERT INTO parm_periode VALUES (34, '2004-08-01', '2004-08-31', '2004', false);
INSERT INTO parm_periode VALUES (35, '2004-09-01', '2004-09-30', '2004', false);
INSERT INTO parm_periode VALUES (36, '2004-10-01', '2004-10-30', '2004', false);
INSERT INTO parm_periode VALUES (37, '2004-11-01', '2004-11-30', '2004', false);
INSERT INTO parm_periode VALUES (38, '2004-12-01', '2004-12-31', '2004', false);
INSERT INTO parm_periode VALUES (39, '2004-12-31', NULL, '2004', false);


--
-- Data for TOC entry 129 (OID 34916)
-- Name: jrn_type; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn_type VALUES ('FIN', 'Financier');
INSERT INTO jrn_type VALUES ('VEN', 'Vente');
INSERT INTO jrn_type VALUES ('ACH', 'Achat');
INSERT INTO jrn_type VALUES ('OD ', 'Opérations Diverses');


--
-- Data for TOC entry 130 (OID 34921)
-- Name: jrn_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn_def VALUES (1, 'Financier', '5* ', '5*', '1,2,4,9', '1,2,4,9', 5, 5, false, NULL, 'FIN', 'FIN-01');
INSERT INTO jrn_def VALUES (4, 'Opération Diverses', NULL, NULL, NULL, NULL, 5, 5, false, NULL, 'OD ', 'OD-01');
INSERT INTO jrn_def VALUES (3, 'Achat', '6*', '4*', '5', '4', 1, 3, true, 'échéance', 'ACH', 'ACH-01');
INSERT INTO jrn_def VALUES (2, 'Vente', '7*', '4*', '2', '3', 1, 3, true, 'échéance', 'VEN', 'VEN-01');


--
-- Data for TOC entry 131 (OID 34932)
-- Name: jrn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn VALUES (54, 2, 1210, 'VEN-01-00054  client : NOUVEAU CLIENT', '2004-04-01', 56, 'VEN-01-00054', '2004-03-25 18:28:58.14453', 30, NULL, NULL);
INSERT INTO jrn VALUES (55, 2, 1210, 'VEN-01-00055  client : NOUVEAU CLIENT', '2004-04-01', 57, 'VEN-01-00055', '2004-03-25 18:29:15.376252', 30, NULL, NULL);
INSERT INTO jrn VALUES (1, 2, 121, 'VEN-01-00001', '2003-01-01', 3, 'VEN-01-00001', '2004-03-23 13:47:24.537294', 1, NULL, NULL);
INSERT INTO jrn VALUES (56, 2, 121, 'VEN-01-00056  client : NOUVEAU CLIENT', '2004-04-01', 58, 'VEN-01-00056', '2004-03-25 18:38:11.552351', 30, NULL, NULL);
INSERT INTO jrn VALUES (57, 2, 121, 'VEN-01-00057  client : NOUVEAU CLIENT', '2004-04-01', 59, 'VEN-01-00057', '2004-03-25 18:38:13.281737', 30, NULL, NULL);
INSERT INTO jrn VALUES (2, 2, 121, 'VEN-01-00002', '2003-01-01', 4, 'VEN-01-00002', '2004-03-23 14:05:34.460249', 1, NULL, NULL);
INSERT INTO jrn VALUES (58, 2, 121, 'VEN-01-00058  client : NOUVEAU CLIENT', '2004-04-01', 60, 'VEN-01-00058', '2004-03-25 18:38:13.356227', 30, NULL, NULL);
INSERT INTO jrn VALUES (59, 2, 121, 'VEN-01-00059  client : NOUVEAU CLIENT', '2004-04-01', 61, 'VEN-01-00059', '2004-03-25 18:38:54.279845', 30, NULL, NULL);
INSERT INTO jrn VALUES (3, 2, 121, 'VEN-01-00003', '2003-01-01', 5, 'VEN-01-00003', '2004-03-23 14:14:35.24054', 1, NULL, NULL);
INSERT INTO jrn VALUES (60, 2, 121, 'VEN-01-00060  client : NOUVEAU CLIENT', '2004-04-01', 62, 'VEN-01-00060', '2004-03-25 18:38:54.341601', 30, NULL, NULL);
INSERT INTO jrn VALUES (61, 2, 121, 'VEN-01-00061  client : NOUVEAU CLIENT', '2004-04-01', 63, 'VEN-01-00061', '2004-03-25 18:39:08.103448', 30, NULL, NULL);
INSERT INTO jrn VALUES (4, 2, 121, 'VEN-01-00004', '2003-01-01', 6, 'VEN-01-00004', '2004-03-23 14:16:23.732411', 1, NULL, NULL);
INSERT INTO jrn VALUES (62, 2, 121, 'VEN-01-00062  client : NOUVEAU CLIENT', '2004-04-01', 64, 'VEN-01-00062', '2004-03-25 18:39:08.166298', 30, NULL, NULL);
INSERT INTO jrn VALUES (63, 2, 121, 'VEN-01-00063  client : NOUVEAU CLIENT', '2004-04-01', 65, 'VEN-01-00063', '2004-03-25 18:40:13.781818', 30, NULL, NULL);
INSERT INTO jrn VALUES (5, 2, 121, 'VEN-01-00005', '2003-01-01', 7, 'VEN-01-00005', '2004-03-23 14:17:50.124512', 1, NULL, NULL);
INSERT INTO jrn VALUES (64, 2, 121, 'VEN-01-00064  client : NOUVEAU CLIENT', '2004-04-01', 66, 'VEN-01-00064', '2004-03-25 18:40:13.840801', 30, NULL, NULL);
INSERT INTO jrn VALUES (65, 2, 121, 'VEN-01-00065  client : NOUVEAU CLIENT', '2004-04-01', 67, 'VEN-01-00065', '2004-03-25 18:40:18.669369', 30, NULL, NULL);
INSERT INTO jrn VALUES (6, 2, 121, 'VEN-01-00006', '2003-01-01', 8, 'VEN-01-00006', '2004-03-23 14:18:33.81708', 1, NULL, NULL);
INSERT INTO jrn VALUES (66, 2, 121, 'VEN-01-00066  client : NOUVEAU CLIENT', '2004-04-01', 68, 'VEN-01-00066', '2004-03-25 18:40:18.73078', 30, NULL, NULL);
INSERT INTO jrn VALUES (67, 2, 121, 'VEN-01-00067  client : NOUVEAU CLIENT', '2004-04-01', 69, 'VEN-01-00067', '2004-03-25 18:41:38.636524', 30, NULL, NULL);
INSERT INTO jrn VALUES (7, 2, 605, 'VEN-01-00007', '2003-01-01', 9, 'VEN-01-00007', '2004-03-23 14:18:43.862098', 1, NULL, NULL);
INSERT INTO jrn VALUES (68, 2, 121, 'VEN-01-00068  client : NOUVEAU CLIENT', '2004-04-01', 70, 'VEN-01-00068', '2004-03-25 18:41:38.696572', 30, NULL, NULL);
INSERT INTO jrn VALUES (69, 2, 121, 'VEN-01-00069  client : NOUVEAU CLIENT', '2004-04-01', 71, 'VEN-01-00069', '2004-03-25 18:43:06.044583', 30, NULL, NULL);
INSERT INTO jrn VALUES (8, 2, 605, 'VEN-01-00008', '2003-01-01', 10, 'VEN-01-00008', '2004-03-23 14:18:55.356075', 1, NULL, NULL);
INSERT INTO jrn VALUES (70, 2, 121, 'VEN-01-00070  client : NOUVEAU CLIENT', '2004-04-01', 72, 'VEN-01-00070', '2004-03-25 18:43:06.107978', 30, NULL, NULL);
INSERT INTO jrn VALUES (71, 2, 605, 'VEN-01-00071  client : NOUVEAU CLIENT', '2004-04-01', 73, 'VEN-01-00071', '2004-03-25 18:45:40.030576', 30, NULL, NULL);
INSERT INTO jrn VALUES (9, 2, 605, 'VEN-01-00009', '2003-01-01', 11, 'VEN-01-00009', '2004-03-23 14:24:19.78429', 1, NULL, NULL);
INSERT INTO jrn VALUES (72, 2, 858.6, 'VEN-01-00072  client : Client 2', '2004-04-01', 74, 'VEN-01-00072', '2004-03-25 22:44:24.649864', 30, NULL, NULL);
INSERT INTO jrn VALUES (73, 2, 4116.5, 'VEN-01-00073  client : Toto', '2004-04-01', 75, 'VEN-01-00073', '2004-03-25 22:46:04.176429', 30, NULL, NULL);
INSERT INTO jrn VALUES (10, 2, 605, 'VEN-01-00010', '2003-01-01', 12, 'VEN-01-00010', '2004-03-23 14:27:37.592925', 1, NULL, NULL);
INSERT INTO jrn VALUES (74, 2, 4116.5, 'VEN-01-00074  client : Toto', '2004-04-01', 76, 'VEN-01-00074', '2004-03-25 22:48:07.346032', 30, NULL, NULL);
INSERT INTO jrn VALUES (75, 2, 4116.5, 'VEN-01-00075  client : Toto', '2004-04-01', 77, 'VEN-01-00075', '2004-03-25 22:49:56.985635', 30, NULL, NULL);
INSERT INTO jrn VALUES (11, 2, 605, 'VEN-01-00011', '2003-01-01', 13, 'VEN-01-00011', '2004-03-23 14:31:13.983055', 1, NULL, NULL);
INSERT INTO jrn VALUES (76, 3, 24.2, 'ACH-01-00001  client : Fournisseur B', '2004-04-01', 81, 'ACH-01-00001', '2004-03-26 00:40:57.302856', 30, NULL, NULL);
INSERT INTO jrn VALUES (77, 3, 0, 'ACH-01-00002  client : Fournisseur B', '2004-04-01', 82, 'ACH-01-00002', '2004-03-26 00:41:13.024891', 30, NULL, NULL);
INSERT INTO jrn VALUES (12, 2, 605, 'VEN-01-00012', '2003-01-01', 14, 'VEN-01-00012', '2004-03-23 14:31:24.287667', 1, NULL, NULL);
INSERT INTO jrn VALUES (78, 3, 380, 'ACH-01-00003  client : Fournisseur B', '2004-04-01', 83, 'ACH-01-00003', '2004-03-26 00:42:55.879974', 30, NULL, NULL);
INSERT INTO jrn VALUES (28, 2, 123, 'VEN-01-00028  client : Client 1', '2004-03-01', 30, 'VEN-01-00028', '2004-03-25 17:41:27.129826', 29, NULL, NULL);
INSERT INTO jrn VALUES (13, 2, 605, 'VEN-01-00013', '2003-01-01', 15, 'VEN-01-00013', '2004-03-23 14:46:30.502689', 1, NULL, NULL);
INSERT INTO jrn VALUES (29, 2, 3086.35, 'VEN-01-00029  client : Client 2', '2004-03-01', 31, 'VEN-01-00029', '2004-03-25 17:48:09.18601', 29, NULL, NULL);
INSERT INTO jrn VALUES (30, 2, 3086.35, 'VEN-01-00030  client : Client 2', '2004-03-01', 32, 'VEN-01-00030', '2004-03-25 17:48:19.358459', 29, NULL, NULL);
INSERT INTO jrn VALUES (14, 2, 605, 'VEN-01-00014client : Client 1', '2003-01-01', 16, 'VEN-01-00014', '2004-03-23 14:49:56.676495', 1, NULL, NULL);
INSERT INTO jrn VALUES (31, 2, 1113, 'VEN-01-00031  client : Client 1', '2004-03-01', 33, 'VEN-01-00031', '2004-03-25 17:51:51.498222', 29, NULL, NULL);
INSERT INTO jrn VALUES (32, 2, 484, 'VEN-01-00032  client : Client 1', '2004-03-01', 34, 'VEN-01-00032', '2004-03-25 17:52:56.208691', 29, NULL, NULL);
INSERT INTO jrn VALUES (15, 2, 1210, 'VEN-01-00015  client : Client 2', '2003-01-01', 17, 'VEN-01-00015', '2004-03-23 14:54:29.095873', 1, NULL, NULL);
INSERT INTO jrn VALUES (33, 2, 484, 'VEN-01-00033  client : Client 1', '2004-03-01', 35, 'VEN-01-00033', '2004-03-25 17:53:27.832884', 29, NULL, NULL);
INSERT INTO jrn VALUES (34, 2, 484, 'VEN-01-00034  client : Client 1', '2004-03-01', 36, 'VEN-01-00034', '2004-03-25 17:53:33.310167', 29, NULL, NULL);
INSERT INTO jrn VALUES (16, 2, 6050, 'VEN-01-00016  client : Client 1', '2003-01-01', 18, 'VEN-01-00016', '2004-03-23 15:37:53.030342', 1, '2003-03-01', NULL);
INSERT INTO jrn VALUES (35, 2, 484, 'VEN-01-00035  client : Client 1', '2004-03-01', 37, 'VEN-01-00035', '2004-03-25 17:53:46.282913', 29, NULL, NULL);
INSERT INTO jrn VALUES (36, 2, 484, 'VEN-01-00036  client : Client 1', '2004-03-01', 38, 'VEN-01-00036', '2004-03-25 17:54:05.645273', 29, NULL, NULL);
INSERT INTO jrn VALUES (17, 2, 4235, 'VEN-01-00017  client : Client 1', '2003-01-11', 19, 'VEN-01-00017', '2004-03-23 15:38:24.412245', 1, '2003-04-01', NULL);
INSERT INTO jrn VALUES (37, 2, 0, 'VEN-01-00037  client : NOUVEAU CLIENT', '2004-03-01', 39, 'VEN-01-00037', '2004-03-25 17:58:05.877515', 29, NULL, NULL);
INSERT INTO jrn VALUES (38, 2, 327.91, 'VEN-01-00038  client : Client 2', '2004-04-01', 40, 'VEN-01-00038', '2004-03-25 18:11:43.392172', 30, NULL, NULL);
INSERT INTO jrn VALUES (18, 2, 0, 'VEN-01-00018  client : Client 1', '2003-01-01', 20, 'VEN-01-00018', '2004-03-23 21:50:46.189884', 1, NULL, NULL);
INSERT INTO jrn VALUES (39, 2, 327.91, 'VEN-01-00039  client : Client 2', '2004-04-01', 41, 'VEN-01-00039', '2004-03-25 18:11:56.798965', 30, NULL, NULL);
INSERT INTO jrn VALUES (40, 2, 242, 'VEN-01-00040  client : Client 1', '2004-04-01', 42, 'VEN-01-00040', '2004-03-25 18:12:44.228286', 30, NULL, NULL);
INSERT INTO jrn VALUES (19, 2, 392, 'VEN-01-00019  client : Client 2', '2004-03-01', 21, 'VEN-01-00019', '2004-03-25 14:21:09.142765', 29, NULL, NULL);
INSERT INTO jrn VALUES (41, 2, 1027.25, 'VEN-01-00041  client : Client 1', '2004-04-01', 43, 'VEN-01-00041', '2004-03-25 18:15:48.651263', 30, NULL, NULL);
INSERT INTO jrn VALUES (20, 2, 1500, 'VEN-01-00020  client : NOUVEAU CLIENT', '2004-03-01', 22, 'VEN-01-00020', '2004-03-25 17:31:56.928938', 29, NULL, NULL);
INSERT INTO jrn VALUES (21, 2, 1012.6, 'VEN-01-00021  client : NOUVEAU CLIENT', '2004-03-01', 23, 'VEN-01-00021', '2004-03-25 17:33:44.788229', 29, NULL, NULL);
INSERT INTO jrn VALUES (22, 2, 1230, 'VEN-01-00022  client : NOUVEAU CLIENT', '2004-03-01', 24, 'VEN-01-00022', '2004-03-25 17:34:55.560705', 29, NULL, NULL);
INSERT INTO jrn VALUES (23, 2, 3085, 'VEN-01-00023  client : Client 2', '2004-03-01', 25, 'VEN-01-00023', '2004-03-25 17:35:47.968167', 29, NULL, NULL);
INSERT INTO jrn VALUES (24, 2, 3085, 'VEN-01-00024  client : Client 2', '2004-03-01', 26, 'VEN-01-00024', '2004-03-25 17:36:00.362501', 29, NULL, NULL);
INSERT INTO jrn VALUES (25, 2, 3085, 'VEN-01-00025  client : Client 2', '2004-03-01', 27, 'VEN-01-00025', '2004-03-25 17:38:58.798242', 29, NULL, NULL);
INSERT INTO jrn VALUES (26, 2, 3085, 'VEN-01-00026  client : Client 2', '2004-03-01', 28, 'VEN-01-00026', '2004-03-25 17:39:12.093874', 29, NULL, NULL);
INSERT INTO jrn VALUES (27, 2, 3085, 'VEN-01-00027  client : Client 2', '2004-03-01', 29, 'VEN-01-00027', '2004-03-25 17:40:16.587902', 29, NULL, NULL);
INSERT INTO jrn VALUES (79, 1, 0, 'FIN-01-00001  client : Unknown', '2004-04-01', 84, 'FIN-01-00001', '2004-03-26 04:32:57.385686', 30, NULL, NULL);
INSERT INTO jrn VALUES (42, 2, 1027.25, 'VEN-01-00042  client : Client 1', '2004-04-01', 44, 'VEN-01-00042', '2004-03-25 18:16:38.79963', 30, NULL, NULL);
INSERT INTO jrn VALUES (43, 2, 750, 'VEN-01-00043  client : Client 1', '2004-04-01', 45, 'VEN-01-00043', '2004-03-25 18:19:13.953508', 30, NULL, NULL);
INSERT INTO jrn VALUES (44, 2, 750, 'VEN-01-00044  client : Client 1', '2004-04-01', 46, 'VEN-01-00044', '2004-03-25 18:20:09.977942', 30, NULL, NULL);
INSERT INTO jrn VALUES (45, 2, 605, 'VEN-01-00045  client : NOUVEAU CLIENT', '2004-04-01', 47, 'VEN-01-00045', '2004-03-25 18:24:16.882297', 30, NULL, NULL);
INSERT INTO jrn VALUES (46, 2, 605, 'VEN-01-00046  client : NOUVEAU CLIENT', '2004-04-01', 48, 'VEN-01-00046', '2004-03-25 18:24:45.611603', 30, NULL, NULL);
INSERT INTO jrn VALUES (47, 2, 0, 'VEN-01-00047  client : NOUVEAU CLIENT', '2004-04-01', 49, 'VEN-01-00047', '2004-03-25 18:25:00.296715', 30, NULL, NULL);
INSERT INTO jrn VALUES (48, 2, 0, 'VEN-01-00048  client : NOUVEAU CLIENT', '2004-04-01', 50, 'VEN-01-00048', '2004-03-25 18:26:41.078754', 30, NULL, NULL);
INSERT INTO jrn VALUES (49, 2, 605, 'VEN-01-00049  client : NOUVEAU CLIENT', '2004-04-01', 51, 'VEN-01-00049', '2004-03-25 18:26:57.773547', 30, NULL, NULL);
INSERT INTO jrn VALUES (50, 2, 605, 'VEN-01-00050  client : NOUVEAU CLIENT', '2004-04-01', 52, 'VEN-01-00050', '2004-03-25 18:27:00.823312', 30, NULL, NULL);
INSERT INTO jrn VALUES (51, 2, 605, 'VEN-01-00051  client : NOUVEAU CLIENT', '2004-04-01', 53, 'VEN-01-00051', '2004-03-25 18:27:01.037609', 30, NULL, NULL);
INSERT INTO jrn VALUES (52, 2, 605, 'VEN-01-00052  client : NOUVEAU CLIENT', '2004-04-01', 54, 'VEN-01-00052', '2004-03-25 18:28:33.699175', 30, NULL, NULL);
INSERT INTO jrn VALUES (53, 2, 605, 'VEN-01-00053  client : NOUVEAU CLIENT', '2004-04-01', 55, 'VEN-01-00053', '2004-03-25 18:28:33.770934', 30, NULL, NULL);


--
-- Data for TOC entry 132 (OID 34939)
-- Name: jrnx; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrnx VALUES (1, '2003-01-01', 484, 4000002, 1, NULL, 2, true, NULL, false, NULL, 'phpcompta', '2004-03-23 13:44:08.837656', 1);
INSERT INTO jrnx VALUES (272, '2004-04-01', 20, 610001, 78, NULL, 3, true, NULL, false, NULL, 'dany', '2004-03-26 00:40:11.388474', 30);
INSERT INTO jrnx VALUES (273, '2004-04-01', 24.2, 4400002, 79, NULL, 3, false, NULL, false, NULL, 'dany', '2004-03-26 00:40:33.54117', 30);
INSERT INTO jrnx VALUES (4, '2003-01-01', 84, 451, 1, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 13:44:08.887007', 1);
INSERT INTO jrnx VALUES (5, '2003-01-01', 121, 4000001, 2, NULL, 2, true, NULL, false, NULL, 'phpcompta', '2004-03-23 13:46:16.534052', 1);
INSERT INTO jrnx VALUES (6, '2003-01-01', 100, 70001, 2, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 13:46:16.549515', 1);
INSERT INTO jrnx VALUES (7, '2003-01-01', 21, 451, 2, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 13:46:16.575457', 1);
INSERT INTO jrnx VALUES (8, '2003-01-01', 121, 4000001, 3, NULL, 2, true, NULL, false, NULL, 'phpcompta', '2004-03-23 13:47:24.505201', 1);
INSERT INTO jrnx VALUES (9, '2003-01-01', 100, 70001, 3, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 13:47:24.522298', 1);
INSERT INTO jrnx VALUES (10, '2003-01-01', 21, 451, 3, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 13:47:24.529054', 1);
INSERT INTO jrnx VALUES (11, '2003-01-01', 121, 4000001, 4, NULL, 2, true, NULL, false, NULL, 'phpcompta', '2004-03-23 14:05:34.435455', 1);
INSERT INTO jrnx VALUES (12, '2003-01-01', 100, 70001, 4, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:05:34.450893', 1);
INSERT INTO jrnx VALUES (13, '2003-01-01', 21, 451, 4, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:05:34.456476', 1);
INSERT INTO jrnx VALUES (14, '2003-01-01', 121, 4000001, 5, NULL, 2, true, NULL, false, NULL, 'phpcompta', '2004-03-23 14:14:35.206407', 1);
INSERT INTO jrnx VALUES (15, '2003-01-01', 100, 70001, 5, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:14:35.229875', 1);
INSERT INTO jrnx VALUES (16, '2003-01-01', 21, 451, 5, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:14:35.236637', 1);
INSERT INTO jrnx VALUES (17, '2003-01-01', 121, 4000001, 6, NULL, 2, true, NULL, false, NULL, 'phpcompta', '2004-03-23 14:16:23.688185', 1);
INSERT INTO jrnx VALUES (18, '2003-01-01', 100, 70001, 6, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:16:23.704737', 1);
INSERT INTO jrnx VALUES (19, '2003-01-01', 21, 451, 6, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:16:23.726303', 1);
INSERT INTO jrnx VALUES (20, '2003-01-01', 121, 4000001, 7, NULL, 2, true, NULL, false, NULL, 'phpcompta', '2004-03-23 14:17:50.035336', 1);
INSERT INTO jrnx VALUES (21, '2003-01-01', 100, 70001, 7, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:17:50.114145', 1);
INSERT INTO jrnx VALUES (22, '2003-01-01', 21, 451, 7, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:17:50.12039', 1);
INSERT INTO jrnx VALUES (23, '2003-01-01', 121, 4000001, 8, NULL, 2, true, NULL, false, NULL, 'phpcompta', '2004-03-23 14:18:33.751592', 1);
INSERT INTO jrnx VALUES (24, '2003-01-01', 100, 70001, 8, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:18:33.776141', 1);
INSERT INTO jrnx VALUES (25, '2003-01-01', 21, 451, 8, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:18:33.784781', 1);
INSERT INTO jrnx VALUES (26, '2003-01-01', 605, 4000001, 9, NULL, 2, true, NULL, false, NULL, 'phpcompta', '2004-03-23 14:18:43.833936', 1);
INSERT INTO jrnx VALUES (27, '2003-01-01', 500, 70001, 9, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:18:43.84982', 1);
INSERT INTO jrnx VALUES (28, '2003-01-01', 105, 451, 9, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:18:43.858151', 1);
INSERT INTO jrnx VALUES (29, '2003-01-01', 605, 4000001, 10, NULL, 2, true, NULL, false, NULL, 'phpcompta', '2004-03-23 14:18:55.289723', 1);
INSERT INTO jrnx VALUES (30, '2003-01-01', 500, 70001, 10, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:18:55.340145', 1);
INSERT INTO jrnx VALUES (31, '2003-01-01', 105, 451, 10, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:18:55.351839', 1);
INSERT INTO jrnx VALUES (32, '2003-01-01', 605, 4000001, 11, NULL, 2, true, NULL, false, NULL, 'phpcompta', '2004-03-23 14:24:19.718655', 1);
INSERT INTO jrnx VALUES (33, '2003-01-01', 500, 70001, 11, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:24:19.767005', 1);
INSERT INTO jrnx VALUES (34, '2003-01-01', 105, 451, 11, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:24:19.780372', 1);
INSERT INTO jrnx VALUES (35, '2003-01-01', 605, 4000001, 12, NULL, 2, true, NULL, false, NULL, 'phpcompta', '2004-03-23 14:27:37.543869', 1);
INSERT INTO jrnx VALUES (36, '2003-01-01', 500, 70001, 12, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:27:37.56078', 1);
INSERT INTO jrnx VALUES (37, '2003-01-01', 105, 451, 12, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:27:37.588805', 1);
INSERT INTO jrnx VALUES (38, '2003-01-01', 605, 4000001, 13, NULL, 2, true, NULL, false, NULL, 'phpcompta', '2004-03-23 14:31:13.958267', 1);
INSERT INTO jrnx VALUES (39, '2003-01-01', 500, 70001, 13, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:31:13.973454', 1);
INSERT INTO jrnx VALUES (40, '2003-01-01', 105, 451, 13, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:31:13.97933', 1);
INSERT INTO jrnx VALUES (41, '2003-01-01', 605, 4000001, 14, NULL, 2, true, NULL, false, NULL, 'phpcompta', '2004-03-23 14:31:24.261385', 1);
INSERT INTO jrnx VALUES (42, '2003-01-01', 500, 70001, 14, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:31:24.27848', 1);
INSERT INTO jrnx VALUES (43, '2003-01-01', 105, 451, 14, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:31:24.283905', 1);
INSERT INTO jrnx VALUES (44, '2003-01-01', 605, 4000001, 15, NULL, 2, true, NULL, false, NULL, 'phpcompta', '2004-03-23 14:46:30.458825', 1);
INSERT INTO jrnx VALUES (45, '2003-01-01', 500, 70001, 15, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:46:30.49216', 1);
INSERT INTO jrnx VALUES (46, '2003-01-01', 105, 451, 15, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:46:30.49841', 1);
INSERT INTO jrnx VALUES (47, '2003-01-01', 605, 4000001, 16, NULL, 2, true, NULL, false, NULL, 'phpcompta', '2004-03-23 14:49:56.649896', 1);
INSERT INTO jrnx VALUES (48, '2003-01-01', 500, 70001, 16, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:49:56.667083', 1);
INSERT INTO jrnx VALUES (49, '2003-01-01', 105, 451, 16, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:49:56.672634', 1);
INSERT INTO jrnx VALUES (50, '2003-01-01', 1210, 4000002, 17, NULL, 2, true, NULL, false, NULL, 'phpcompta', '2004-03-23 14:54:29.055972', 1);
INSERT INTO jrnx VALUES (51, '2003-01-01', 1000, 70001, 17, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:54:29.084167', 1);
INSERT INTO jrnx VALUES (52, '2003-01-01', 210, 451, 17, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 14:54:29.090254', 1);
INSERT INTO jrnx VALUES (53, '2003-01-01', 6050, 4000001, 18, NULL, 2, true, NULL, false, NULL, 'phpcompta', '2004-03-23 15:37:53.003304', 1);
INSERT INTO jrnx VALUES (54, '2003-01-01', 5000, 70001, 18, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 15:37:53.020759', 1);
INSERT INTO jrnx VALUES (55, '2003-01-01', 1050, 451, 18, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 15:37:53.026419', 1);
INSERT INTO jrnx VALUES (56, '2003-01-11', 4235, 4000001, 19, NULL, 2, true, NULL, false, NULL, 'phpcompta', '2004-03-23 15:38:24.349646', 1);
INSERT INTO jrnx VALUES (57, '2003-01-11', 3500, 70001, 19, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 15:38:24.385135', 1);
INSERT INTO jrnx VALUES (58, '2003-01-11', 3500, 70002, 19, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 15:38:24.403236', 1);
INSERT INTO jrnx VALUES (59, '2003-01-11', 735, 451, 19, NULL, 2, false, NULL, false, NULL, 'phpcompta', '2004-03-23 15:38:24.408578', 1);
INSERT INTO jrnx VALUES (60, '2003-01-01', 0, 4000001, 20, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-23 21:50:46.141907', 1);
INSERT INTO jrnx VALUES (61, '2003-01-01', 0, 70001, 20, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-23 21:50:46.180221', 1);
INSERT INTO jrnx VALUES (62, '2003-01-01', 0, 451, 20, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-23 21:50:46.186024', 1);
INSERT INTO jrnx VALUES (63, '2004-03-01', 392, 4000002, 21, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 14:21:08.938766', 29);
INSERT INTO jrnx VALUES (64, '2004-03-01', 350, 70004, 21, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 14:21:09.087367', 29);
INSERT INTO jrnx VALUES (65, '2004-03-01', 350, 70001, 21, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 14:21:09.093922', 29);
INSERT INTO jrnx VALUES (66, '2004-03-01', 0, 451, 21, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 14:21:09.099715', 29);
INSERT INTO jrnx VALUES (67, '2004-03-01', 42, 451, 21, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 14:21:09.139044', 29);
INSERT INTO jrnx VALUES (68, '2004-03-01', 1500, 4000005, 22, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 17:31:56.90032', 29);
INSERT INTO jrnx VALUES (69, '2004-03-01', 1500, 70004, 22, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:31:56.919483', 29);
INSERT INTO jrnx VALUES (70, '2004-03-01', 0, 451, 22, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:31:56.925079', 29);
INSERT INTO jrnx VALUES (71, '2004-03-01', 1012.6, 4000005, 23, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 17:33:44.759556', 29);
INSERT INTO jrnx VALUES (72, '2004-03-01', 1000, 70001, 23, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:33:44.775575', 29);
INSERT INTO jrnx VALUES (73, '2004-03-01', 12.6, 451, 23, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:33:44.784297', 29);
INSERT INTO jrnx VALUES (74, '2004-03-01', 1230, 4000005, 24, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 17:34:55.525884', 29);
INSERT INTO jrnx VALUES (75, '2004-03-01', 1230, 70001, 24, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:34:55.550173', 29);
INSERT INTO jrnx VALUES (76, '2004-03-01', 0, 451, 24, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:34:55.556599', 29);
INSERT INTO jrnx VALUES (77, '2004-03-01', 3085, 4000002, 25, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 17:35:47.927244', 29);
INSERT INTO jrnx VALUES (78, '2004-03-01', 3085, 70003, 25, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:35:47.943975', 29);
INSERT INTO jrnx VALUES (79, '2004-03-01', 3085, 70002, 25, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:35:47.953398', 29);
INSERT INTO jrnx VALUES (80, '2004-03-01', 0, 451, 25, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:35:47.959228', 29);
INSERT INTO jrnx VALUES (81, '2004-03-01', 0, 451, 25, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:35:47.964411', 29);
INSERT INTO jrnx VALUES (82, '2004-03-01', 3085, 4000002, 26, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 17:36:00.29272', 29);
INSERT INTO jrnx VALUES (83, '2004-03-01', 3085, 70003, 26, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:36:00.334306', 29);
INSERT INTO jrnx VALUES (84, '2004-03-01', 3085, 70002, 26, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:36:00.344229', 29);
INSERT INTO jrnx VALUES (85, '2004-03-01', 0, 451, 26, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:36:00.352923', 29);
INSERT INTO jrnx VALUES (86, '2004-03-01', 0, 451, 26, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:36:00.35823', 29);
INSERT INTO jrnx VALUES (87, '2004-03-01', 3085, 4000002, 27, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 17:38:58.749028', 29);
INSERT INTO jrnx VALUES (88, '2004-03-01', 3085, 70003, 27, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:38:58.766829', 29);
INSERT INTO jrnx VALUES (89, '2004-03-01', 3085, 70002, 27, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:38:58.77984', 29);
INSERT INTO jrnx VALUES (90, '2004-03-01', 0, 451, 27, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:38:58.789413', 29);
INSERT INTO jrnx VALUES (91, '2004-03-01', 0, 451, 27, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:38:58.794563', 29);
INSERT INTO jrnx VALUES (92, '2004-03-01', 3085, 4000002, 28, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 17:39:12.003188', 29);
INSERT INTO jrnx VALUES (93, '2004-03-01', 3085, 70003, 28, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:39:12.071989', 29);
INSERT INTO jrnx VALUES (94, '2004-03-01', 3085, 70002, 28, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:39:12.078619', 29);
INSERT INTO jrnx VALUES (95, '2004-03-01', 0, 451, 28, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:39:12.084837', 29);
INSERT INTO jrnx VALUES (96, '2004-03-01', 0, 451, 28, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:39:12.090142', 29);
INSERT INTO jrnx VALUES (97, '2004-03-01', 3085, 4000002, 29, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 17:40:16.542875', 29);
INSERT INTO jrnx VALUES (98, '2004-03-01', 3085, 70003, 29, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:40:16.56722', 29);
INSERT INTO jrnx VALUES (99, '2004-03-01', 3085, 70002, 29, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:40:16.573743', 29);
INSERT INTO jrnx VALUES (100, '2004-03-01', 0, 451, 29, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:40:16.579108', 29);
INSERT INTO jrnx VALUES (101, '2004-03-01', 0, 451, 29, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:40:16.584154', 29);
INSERT INTO jrnx VALUES (102, '2004-03-01', 123, 4000001, 30, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 17:41:27.103067', 29);
INSERT INTO jrnx VALUES (103, '2004-03-01', 123, 70004, 30, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:41:27.120473', 29);
INSERT INTO jrnx VALUES (104, '2004-03-01', 0, 451, 30, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:41:27.126042', 29);
INSERT INTO jrnx VALUES (105, '2004-03-01', 3086.35, 4000002, 31, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 17:48:09.146159', 29);
INSERT INTO jrnx VALUES (106, '2004-03-01', 3085, 70003, 31, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:48:09.164505', 29);
INSERT INTO jrnx VALUES (107, '2004-03-01', 3085, 70002, 31, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:48:09.1713', 29);
INSERT INTO jrnx VALUES (108, '2004-03-01', 0.3, 451, 31, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:48:09.176761', 29);
INSERT INTO jrnx VALUES (109, '2004-03-01', 1.05, 451, 31, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:48:09.182277', 29);
INSERT INTO jrnx VALUES (110, '2004-03-01', 3086.35, 4000002, 32, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 17:48:19.303845', 29);
INSERT INTO jrnx VALUES (111, '2004-03-01', 3085, 70003, 32, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:48:19.334183', 29);
INSERT INTO jrnx VALUES (112, '2004-03-01', 3085, 70002, 32, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:48:19.341581', 29);
INSERT INTO jrnx VALUES (113, '2004-03-01', 0.3, 451, 32, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:48:19.34731', 29);
INSERT INTO jrnx VALUES (114, '2004-03-01', 1.05, 451, 32, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:48:19.354467', 29);
INSERT INTO jrnx VALUES (115, '2004-03-01', 1113, 4000001, 33, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 17:51:51.400326', 29);
INSERT INTO jrnx VALUES (116, '2004-03-01', 1050, 70003, 33, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:51:51.479187', 29);
INSERT INTO jrnx VALUES (117, '2004-03-01', 63, 451, 33, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:51:51.485468', 29);
INSERT INTO jrnx VALUES (118, '2004-03-01', 484, 4000001, 34, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 17:52:56.175719', 29);
INSERT INTO jrnx VALUES (119, '2004-03-01', 400, 70001, 34, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:52:56.19239', 29);
INSERT INTO jrnx VALUES (120, '2004-03-01', 400, 70002, 34, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:52:56.19931', 29);
INSERT INTO jrnx VALUES (121, '2004-03-01', 84, 451, 34, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:52:56.204819', 29);
INSERT INTO jrnx VALUES (122, '2004-03-01', 484, 4000001, 35, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 17:53:27.769022', 29);
INSERT INTO jrnx VALUES (123, '2004-03-01', 400, 70001, 35, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:53:27.797199', 29);
INSERT INTO jrnx VALUES (124, '2004-03-01', 400, 70002, 35, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:53:27.822604', 29);
INSERT INTO jrnx VALUES (125, '2004-03-01', 84, 451, 35, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:53:27.828296', 29);
INSERT INTO jrnx VALUES (126, '2004-03-01', 484, 4000001, 36, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 17:53:33.275848', 29);
INSERT INTO jrnx VALUES (127, '2004-03-01', 400, 70001, 36, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:53:33.292509', 29);
INSERT INTO jrnx VALUES (128, '2004-03-01', 400, 70002, 36, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:53:33.300459', 29);
INSERT INTO jrnx VALUES (129, '2004-03-01', 84, 451, 36, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:53:33.306057', 29);
INSERT INTO jrnx VALUES (130, '2004-03-01', 484, 4000001, 37, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 17:53:46.226367', 29);
INSERT INTO jrnx VALUES (131, '2004-03-01', 400, 70001, 37, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:53:46.263388', 29);
INSERT INTO jrnx VALUES (132, '2004-03-01', 400, 70002, 37, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:53:46.271691', 29);
INSERT INTO jrnx VALUES (133, '2004-03-01', 84, 451, 37, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:53:46.27735', 29);
INSERT INTO jrnx VALUES (134, '2004-03-01', 484, 4000001, 38, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 17:54:05.612655', 29);
INSERT INTO jrnx VALUES (135, '2004-03-01', 400, 70001, 38, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:54:05.629368', 29);
INSERT INTO jrnx VALUES (136, '2004-03-01', 400, 70002, 38, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:54:05.636068', 29);
INSERT INTO jrnx VALUES (137, '2004-03-01', 84, 451, 38, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:54:05.641499', 29);
INSERT INTO jrnx VALUES (138, '2004-03-01', 0, 4000005, 39, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 17:58:05.84615', 29);
INSERT INTO jrnx VALUES (139, '2004-03-01', 0, 70003, 39, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:58:05.868033', 29);
INSERT INTO jrnx VALUES (140, '2004-03-01', 0, 451, 39, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 17:58:05.873694', 29);
INSERT INTO jrnx VALUES (141, '2004-04-01', 327.91, 4000002, 40, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:11:43.354675', 30);
INSERT INTO jrnx VALUES (142, '2004-04-01', 120, 70001, 40, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:11:43.373022', 30);
INSERT INTO jrnx VALUES (143, '2004-04-01', 151, 70002, 40, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:11:43.382633', 30);
INSERT INTO jrnx VALUES (144, '2004-04-01', 56.91, 451, 40, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:11:43.388231', 30);
INSERT INTO jrnx VALUES (145, '2004-04-01', 327.91, 4000002, 41, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:11:56.762946', 30);
INSERT INTO jrnx VALUES (146, '2004-04-01', 120, 70001, 41, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:11:56.78083', 30);
INSERT INTO jrnx VALUES (147, '2004-04-01', 151, 70002, 41, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:11:56.788277', 30);
INSERT INTO jrnx VALUES (148, '2004-04-01', 56.91, 451, 41, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:11:56.794537', 30);
INSERT INTO jrnx VALUES (149, '2004-04-01', 242, 4000001, 42, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:12:44.171695', 30);
INSERT INTO jrnx VALUES (150, '2004-04-01', 100, 70001, 42, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:12:44.212209', 30);
INSERT INTO jrnx VALUES (151, '2004-04-01', 100, 70001, 42, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:12:44.218773', 30);
INSERT INTO jrnx VALUES (152, '2004-04-01', 42, 451, 42, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:12:44.224427', 30);
INSERT INTO jrnx VALUES (153, '2004-04-01', 1027.25, 4000001, 43, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:15:48.597011', 30);
INSERT INTO jrnx VALUES (154, '2004-04-01', 150, 70004, 43, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:15:48.615691', 30);
INSERT INTO jrnx VALUES (155, '2004-04-01', 725, 70002, 43, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:15:48.622252', 30);
INSERT INTO jrnx VALUES (156, '2004-04-01', 0, 70001, 43, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:15:48.628711', 30);
INSERT INTO jrnx VALUES (157, '2004-04-01', 0, 451, 43, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:15:48.634206', 30);
INSERT INTO jrnx VALUES (158, '2004-04-01', 152.25, 451, 43, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:15:48.639264', 30);
INSERT INTO jrnx VALUES (159, '2004-04-01', 1027.25, 4000001, 44, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:16:38.72783', 30);
INSERT INTO jrnx VALUES (160, '2004-04-01', 150, 70004, 44, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:16:38.754528', 30);
INSERT INTO jrnx VALUES (161, '2004-04-01', 725, 70002, 44, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:16:38.769099', 30);
INSERT INTO jrnx VALUES (162, '2004-04-01', 0, 70001, 44, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:16:38.776759', 30);
INSERT INTO jrnx VALUES (163, '2004-04-01', 0, 451, 44, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:16:38.783246', 30);
INSERT INTO jrnx VALUES (164, '2004-04-01', 152.25, 451, 44, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:16:38.792241', 30);
INSERT INTO jrnx VALUES (165, '2004-04-01', 750, 4000001, 45, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:19:13.915227', 30);
INSERT INTO jrnx VALUES (166, '2004-04-01', 750, 70004, 45, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:19:13.943722', 30);
INSERT INTO jrnx VALUES (167, '2004-04-01', 0, 451, 45, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:19:13.949548', 30);
INSERT INTO jrnx VALUES (168, '2004-04-01', 750, 4000001, 46, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:20:09.946215', 30);
INSERT INTO jrnx VALUES (169, '2004-04-01', 750, 70004, 46, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:20:09.967112', 30);
INSERT INTO jrnx VALUES (170, '2004-04-01', 0, 451, 46, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:20:09.973592', 30);
INSERT INTO jrnx VALUES (171, '2004-04-01', 605, 4000005, 47, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:24:16.855803', 30);
INSERT INTO jrnx VALUES (172, '2004-04-01', 500, 70001, 47, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:24:16.87285', 30);
INSERT INTO jrnx VALUES (173, '2004-04-01', 105, 451, 47, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:24:16.878369', 30);
INSERT INTO jrnx VALUES (174, '2004-04-01', 605, 4000005, 48, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:24:45.582833', 30);
INSERT INTO jrnx VALUES (175, '2004-04-01', 500, 70001, 48, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:24:45.60194', 30);
INSERT INTO jrnx VALUES (176, '2004-04-01', 105, 451, 48, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:24:45.607414', 30);
INSERT INTO jrnx VALUES (177, '2004-04-01', 0, 4000005, 49, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:25:00.269585', 30);
INSERT INTO jrnx VALUES (178, '2004-04-01', 0, 70001, 49, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:25:00.287315', 30);
INSERT INTO jrnx VALUES (179, '2004-04-01', 0, 451, 49, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:25:00.292919', 30);
INSERT INTO jrnx VALUES (180, '2004-04-01', 0, 4000005, 50, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:26:41.051651', 30);
INSERT INTO jrnx VALUES (181, '2004-04-01', 0, 70001, 50, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:26:41.069233', 30);
INSERT INTO jrnx VALUES (182, '2004-04-01', 0, 451, 50, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:26:41.074665', 30);
INSERT INTO jrnx VALUES (183, '2004-04-01', 605, 4000005, 51, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:26:57.746752', 30);
INSERT INTO jrnx VALUES (184, '2004-04-01', 500, 70001, 51, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:26:57.764127', 30);
INSERT INTO jrnx VALUES (185, '2004-04-01', 105, 451, 51, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:26:57.769766', 30);
INSERT INTO jrnx VALUES (186, '2004-04-01', 605, 4000005, 52, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:27:00.794503', 30);
INSERT INTO jrnx VALUES (187, '2004-04-01', 500, 70001, 52, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:27:00.811162', 30);
INSERT INTO jrnx VALUES (188, '2004-04-01', 105, 451, 52, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:27:00.819053', 30);
INSERT INTO jrnx VALUES (189, '2004-04-01', 605, 4000005, 53, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:27:00.986789', 30);
INSERT INTO jrnx VALUES (190, '2004-04-01', 500, 70001, 53, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:27:01.016165', 30);
INSERT INTO jrnx VALUES (191, '2004-04-01', 105, 451, 53, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:27:01.03292', 30);
INSERT INTO jrnx VALUES (192, '2004-04-01', 605, 4000005, 54, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:28:33.650454', 30);
INSERT INTO jrnx VALUES (193, '2004-04-01', 500, 70001, 54, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:28:33.66699', 30);
INSERT INTO jrnx VALUES (194, '2004-04-01', 105, 451, 54, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:28:33.673293', 30);
INSERT INTO jrnx VALUES (195, '2004-04-01', 605, 4000005, 55, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:28:33.7523', 30);
INSERT INTO jrnx VALUES (196, '2004-04-01', 500, 70001, 55, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:28:33.760926', 30);
INSERT INTO jrnx VALUES (197, '2004-04-01', 105, 451, 55, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:28:33.766592', 30);
INSERT INTO jrnx VALUES (198, '2004-04-01', 1210, 4000005, 56, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:28:58.117617', 30);
INSERT INTO jrnx VALUES (199, '2004-04-01', 1000, 70001, 56, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:28:58.1349', 30);
INSERT INTO jrnx VALUES (200, '2004-04-01', 210, 451, 56, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:28:58.140719', 30);
INSERT INTO jrnx VALUES (201, '2004-04-01', 1210, 4000005, 57, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:29:15.347207', 30);
INSERT INTO jrnx VALUES (202, '2004-04-01', 1000, 70001, 57, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:29:15.365558', 30);
INSERT INTO jrnx VALUES (203, '2004-04-01', 210, 451, 57, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:29:15.371801', 30);
INSERT INTO jrnx VALUES (204, '2004-04-01', 121, 4000005, 58, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:38:11.524259', 30);
INSERT INTO jrnx VALUES (205, '2004-04-01', 100, 70001, 58, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:38:11.543024', 30);
INSERT INTO jrnx VALUES (206, '2004-04-01', 21, 451, 58, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:38:11.548557', 30);
INSERT INTO jrnx VALUES (207, '2004-04-01', 121, 4000005, 59, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:38:13.253348', 30);
INSERT INTO jrnx VALUES (208, '2004-04-01', 100, 70001, 59, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:38:13.27219', 30);
INSERT INTO jrnx VALUES (209, '2004-04-01', 21, 451, 59, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:38:13.277619', 30);
INSERT INTO jrnx VALUES (210, '2004-04-01', 121, 4000005, 60, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:38:13.334188', 30);
INSERT INTO jrnx VALUES (211, '2004-04-01', 100, 70001, 60, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:38:13.34485', 30);
INSERT INTO jrnx VALUES (212, '2004-04-01', 21, 451, 60, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:38:13.352391', 30);
INSERT INTO jrnx VALUES (213, '2004-04-01', 121, 4000005, 61, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:38:54.227969', 30);
INSERT INTO jrnx VALUES (214, '2004-04-01', 100, 70001, 61, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:38:54.26622', 30);
INSERT INTO jrnx VALUES (215, '2004-04-01', 21, 451, 61, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:38:54.274833', 30);
INSERT INTO jrnx VALUES (216, '2004-04-01', 121, 4000005, 62, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:38:54.321747', 30);
INSERT INTO jrnx VALUES (217, '2004-04-01', 100, 70001, 62, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:38:54.331093', 30);
INSERT INTO jrnx VALUES (218, '2004-04-01', 21, 451, 62, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:38:54.336902', 30);
INSERT INTO jrnx VALUES (219, '2004-04-01', 121, 4000005, 63, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:39:08.075266', 30);
INSERT INTO jrnx VALUES (220, '2004-04-01', 100, 70001, 63, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:39:08.092143', 30);
INSERT INTO jrnx VALUES (221, '2004-04-01', 21, 451, 63, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:39:08.097662', 30);
INSERT INTO jrnx VALUES (222, '2004-04-01', 121, 4000005, 64, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:39:08.146529', 30);
INSERT INTO jrnx VALUES (223, '2004-04-01', 100, 70001, 64, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:39:08.157341', 30);
INSERT INTO jrnx VALUES (224, '2004-04-01', 21, 451, 64, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:39:08.162515', 30);
INSERT INTO jrnx VALUES (225, '2004-04-01', 121, 4000005, 65, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:40:12.634532', 30);
INSERT INTO jrnx VALUES (226, '2004-04-01', 100, 70001, 65, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:40:13.770606', 30);
INSERT INTO jrnx VALUES (227, '2004-04-01', 21, 451, 65, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:40:13.776812', 30);
INSERT INTO jrnx VALUES (228, '2004-04-01', 121, 4000005, 66, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:40:13.823585', 30);
INSERT INTO jrnx VALUES (229, '2004-04-01', 100, 70001, 66, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:40:13.831875', 30);
INSERT INTO jrnx VALUES (230, '2004-04-01', 21, 451, 66, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:40:13.836918', 30);
INSERT INTO jrnx VALUES (231, '2004-04-01', 121, 4000005, 67, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:40:18.573592', 30);
INSERT INTO jrnx VALUES (232, '2004-04-01', 100, 70001, 67, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:40:18.607407', 30);
INSERT INTO jrnx VALUES (233, '2004-04-01', 21, 451, 67, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:40:18.653411', 30);
INSERT INTO jrnx VALUES (234, '2004-04-01', 121, 4000005, 68, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:40:18.712462', 30);
INSERT INTO jrnx VALUES (235, '2004-04-01', 100, 70001, 68, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:40:18.720271', 30);
INSERT INTO jrnx VALUES (236, '2004-04-01', 21, 451, 68, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:40:18.725734', 30);
INSERT INTO jrnx VALUES (237, '2004-04-01', 121, 4000005, 69, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:41:38.602398', 30);
INSERT INTO jrnx VALUES (238, '2004-04-01', 100, 70001, 69, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:41:38.625343', 30);
INSERT INTO jrnx VALUES (239, '2004-04-01', 21, 451, 69, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:41:38.632719', 30);
INSERT INTO jrnx VALUES (240, '2004-04-01', 121, 4000005, 70, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:41:38.677377', 30);
INSERT INTO jrnx VALUES (241, '2004-04-01', 100, 70001, 70, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:41:38.687785', 30);
INSERT INTO jrnx VALUES (242, '2004-04-01', 21, 451, 70, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:41:38.692839', 30);
INSERT INTO jrnx VALUES (243, '2004-04-01', 121, 4000005, 71, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:43:06.015053', 30);
INSERT INTO jrnx VALUES (244, '2004-04-01', 100, 70001, 71, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:43:06.034587', 30);
INSERT INTO jrnx VALUES (245, '2004-04-01', 21, 451, 71, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:43:06.04071', 30);
INSERT INTO jrnx VALUES (246, '2004-04-01', 121, 4000005, 72, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:43:06.089566', 30);
INSERT INTO jrnx VALUES (247, '2004-04-01', 100, 70001, 72, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:43:06.097368', 30);
INSERT INTO jrnx VALUES (248, '2004-04-01', 21, 451, 72, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:43:06.104071', 30);
INSERT INTO jrnx VALUES (249, '2004-04-01', 605, 4000005, 73, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 18:45:39.986738', 30);
INSERT INTO jrnx VALUES (250, '2004-04-01', 500, 70001, 73, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:45:40.007622', 30);
INSERT INTO jrnx VALUES (251, '2004-04-01', 105, 451, 73, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 18:45:40.02647', 30);
INSERT INTO jrnx VALUES (252, '2004-04-01', 858.6, 4000002, 74, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 22:44:24.614334', 30);
INSERT INTO jrnx VALUES (253, '2004-04-01', 400, 70003, 74, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 22:44:24.633688', 30);
INSERT INTO jrnx VALUES (254, '2004-04-01', 410, 70003, 74, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 22:44:24.640365', 30);
INSERT INTO jrnx VALUES (255, '2004-04-01', 48.6, 451, 74, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 22:44:24.645956', 30);
INSERT INTO jrnx VALUES (256, '2004-04-01', 4116.5, 4000004, 75, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 22:46:04.092868', 30);
INSERT INTO jrnx VALUES (257, '2004-04-01', 2000, 70003, 75, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 22:46:04.112674', 30);
INSERT INTO jrnx VALUES (258, '2004-04-01', 1650, 70002, 75, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 22:46:04.156596', 30);
INSERT INTO jrnx VALUES (259, '2004-04-01', 120, 451, 75, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 22:46:04.166432', 30);
INSERT INTO jrnx VALUES (260, '2004-04-01', 346.5, 451, 75, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 22:46:04.172696', 30);
INSERT INTO jrnx VALUES (261, '2004-04-01', 4116.5, 4000004, 76, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 22:48:07.277552', 30);
INSERT INTO jrnx VALUES (262, '2004-04-01', 2000, 70003, 76, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 22:48:07.297621', 30);
INSERT INTO jrnx VALUES (263, '2004-04-01', 1650, 70002, 76, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 22:48:07.317444', 30);
INSERT INTO jrnx VALUES (264, '2004-04-01', 120, 451, 76, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 22:48:07.328267', 30);
INSERT INTO jrnx VALUES (265, '2004-04-01', 346.5, 451, 76, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 22:48:07.335682', 30);
INSERT INTO jrnx VALUES (266, '2004-04-01', 4116.5, 4000004, 77, NULL, 2, true, NULL, false, NULL, 'dany', '2004-03-25 22:49:56.92508', 30);
INSERT INTO jrnx VALUES (267, '2004-04-01', 2000, 70003, 77, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 22:49:56.943611', 30);
INSERT INTO jrnx VALUES (268, '2004-04-01', 1650, 70002, 77, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 22:49:56.958568', 30);
INSERT INTO jrnx VALUES (269, '2004-04-01', 120, 451, 77, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 22:49:56.973752', 30);
INSERT INTO jrnx VALUES (270, '2004-04-01', 346.5, 451, 77, NULL, 2, false, NULL, false, NULL, 'dany', '2004-03-25 22:49:56.980578', 30);
INSERT INTO jrnx VALUES (271, '2004-04-01', 24.2, 4400002, 78, NULL, 3, false, NULL, false, NULL, 'dany', '2004-03-26 00:40:11.153843', 30);
INSERT INTO jrnx VALUES (274, '2004-04-01', 20, 610001, 79, NULL, 3, true, NULL, false, NULL, 'dany', '2004-03-26 00:40:33.564503', 30);
INSERT INTO jrnx VALUES (275, '2004-04-01', 24.2, 4400002, 80, NULL, 3, false, NULL, false, NULL, 'dany', '2004-03-26 00:40:43.323548', 30);
INSERT INTO jrnx VALUES (276, '2004-04-01', 20, 610001, 80, NULL, 3, true, NULL, false, NULL, 'dany', '2004-03-26 00:40:43.342466', 30);
INSERT INTO jrnx VALUES (277, '2004-04-01', 24.2, 4400002, 81, NULL, 3, false, NULL, false, NULL, 'dany', '2004-03-26 00:40:56.418324', 30);
INSERT INTO jrnx VALUES (278, '2004-04-01', 20, 610001, 81, NULL, 3, true, NULL, false, NULL, 'dany', '2004-03-26 00:40:57.286635', 30);
INSERT INTO jrnx VALUES (279, '2004-04-01', 4.2, 411, 81, NULL, 3, false, NULL, false, NULL, 'dany', '2004-03-26 00:40:57.297247', 30);
INSERT INTO jrnx VALUES (280, '2004-04-01', 0, 4400002, 82, NULL, 3, false, NULL, false, NULL, 'dany', '2004-03-26 00:41:12.965944', 30);
INSERT INTO jrnx VALUES (281, '2004-04-01', 0, 610001, 82, NULL, 3, true, NULL, false, NULL, 'dany', '2004-03-26 00:41:13.00877', 30);
INSERT INTO jrnx VALUES (282, '2004-04-01', 0, 411, 82, NULL, 3, false, NULL, false, NULL, 'dany', '2004-03-26 00:41:13.019858', 30);
INSERT INTO jrnx VALUES (283, '2004-04-01', 380, 4400002, 83, NULL, 3, false, NULL, false, NULL, 'dany', '2004-03-26 00:42:55.842062', 30);
INSERT INTO jrnx VALUES (284, '2004-04-01', 380, 610002, 83, NULL, 3, true, NULL, false, NULL, 'dany', '2004-03-26 00:42:55.862305', 30);
INSERT INTO jrnx VALUES (285, '2004-04-01', 0, 411, 83, NULL, 3, false, NULL, false, NULL, 'dany', '2004-03-26 00:42:55.874789', 30);


--
-- Data for TOC entry 133 (OID 34950)
-- Name: user_pref; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO user_pref VALUES ('france', 1);
INSERT INTO user_pref VALUES ('dany', 30);
INSERT INTO user_pref VALUES ('phpcompta', 1);


--
-- Data for TOC entry 134 (OID 34959)
-- Name: formdef; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 135 (OID 34965)
-- Name: form; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 136 (OID 34975)
-- Name: fichedef; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO fichedef VALUES (1, 'a', 'a');


--
-- Data for TOC entry 137 (OID 34981)
-- Name: isupp_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO isupp_def VALUES (1, 'a', 1, false);
INSERT INTO isupp_def VALUES (2, 'b', 1, false);


--
-- Data for TOC entry 138 (OID 34987)
-- Name: isupp; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 139 (OID 34995)
-- Name: centralized; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 140 (OID 35006)
-- Name: user_sec_jrn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO user_sec_jrn VALUES (1, 'phpcompta', 1, '');
INSERT INTO user_sec_jrn VALUES (2, 'phpcompta', 2, '');
INSERT INTO user_sec_jrn VALUES (3, 'phpcompta', 3, '');
INSERT INTO user_sec_jrn VALUES (4, 'phpcompta', 4, '');
INSERT INTO user_sec_jrn VALUES (5, 'dany', 4, 'NO');
INSERT INTO user_sec_jrn VALUES (6, 'dany', 2, 'NO');
INSERT INTO user_sec_jrn VALUES (7, 'dany', 1, 'NO');
INSERT INTO user_sec_jrn VALUES (8, 'dany', 3, 'NO');
INSERT INTO user_sec_jrn VALUES (9, 'france', 4, 'W');
INSERT INTO user_sec_jrn VALUES (10, 'france', 2, 'W');
INSERT INTO user_sec_jrn VALUES (11, 'france', 1, 'R');
INSERT INTO user_sec_jrn VALUES (12, 'france', 3, 'R');


--
-- Data for TOC entry 141 (OID 35012)
-- Name: action; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO "action" VALUES (1, 'Journaux');
INSERT INTO "action" VALUES (2, 'Facturation');
INSERT INTO "action" VALUES (4, 'Impression');
INSERT INTO "action" VALUES (5, 'Formulaire');
INSERT INTO "action" VALUES (6, 'Mise à jour Plan Comptable');
INSERT INTO "action" VALUES (7, 'Gestion Journaux');
INSERT INTO "action" VALUES (8, 'Paramètres');
INSERT INTO "action" VALUES (9, 'Sécurité');
INSERT INTO "action" VALUES (10, 'Centralise');
INSERT INTO "action" VALUES (3, 'Fiche Read');
INSERT INTO "action" VALUES (14, 'Fiche écriture');


--
-- Data for TOC entry 142 (OID 35017)
-- Name: user_sec_act; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO user_sec_act VALUES (1, 'france', 2);
INSERT INTO user_sec_act VALUES (2, 'france', 1);
INSERT INTO user_sec_act VALUES (3, 'france', 3);
INSERT INTO user_sec_act VALUES (4, 'demo', 10);
INSERT INTO user_sec_act VALUES (5, 'demo', 2);
INSERT INTO user_sec_act VALUES (6, 'demo', 14);
INSERT INTO user_sec_act VALUES (7, 'demo', 3);
INSERT INTO user_sec_act VALUES (8, 'demo', 5);
INSERT INTO user_sec_act VALUES (9, 'demo', 7);
INSERT INTO user_sec_act VALUES (10, 'demo', 4);
INSERT INTO user_sec_act VALUES (11, 'demo', 1);
INSERT INTO user_sec_act VALUES (12, 'demo', 6);
INSERT INTO user_sec_act VALUES (13, 'demo', 8);


--
-- Data for TOC entry 143 (OID 35025)
-- Name: jrn_action; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn_action VALUES (2, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'VEN');
INSERT INTO jrn_action VALUES (4, 'Voir Impayés', 'Voir toutes les factures non payées', 'user_jrn.php', 'action=voir_jrn_non_paye', 'FR', 'VEN');
INSERT INTO jrn_action VALUES (6, 'Recherche', 'Recherche dans le journal', 'user_jrn.php', 'action=search', 'FR', 'VEN');
INSERT INTO jrn_action VALUES (1, 'Nouvelle', 'Création d''une facture', 'user_jrn.php', 'action=insert_vente&blank', 'FR', 'VEN');
INSERT INTO jrn_action VALUES (5, 'Impression', 'Impression du journal', 'impress.php', 'filter=1&action=viewpdf&type=jrn', 'FR', 'VEN');
INSERT INTO jrn_action VALUES (10, 'Nouveau', 'Encode un nouvel achat (matériel, marchandises, services et biens divers)', 'user_jrn.php', 'action=new&blank', 'FR', 'ACH');
INSERT INTO jrn_action VALUES (12, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'ACH');
INSERT INTO jrn_action VALUES (14, 'Voir Impayés', 'Voir toutes les factures non payées', 'user_jrn.php', 'action=voir_jrn_non_paye', 'FR', 'ACH');
INSERT INTO jrn_action VALUES (16, 'Impression', 'Impression du journal', 'impress.php', 'filter=1&action=viewpdf&type=jrn', 'FR', 'ACH');
INSERT INTO jrn_action VALUES (18, 'Recherche', 'Recherche dans le journal', 'user_jrn.php', 'action=search', 'FR', 'ACH');
INSERT INTO jrn_action VALUES (20, 'Nouveau', 'Encode un nouvel achat (matériel, marchandises, services et biens divers)', 'user_jrn.php', 'action=new&blank', 'FR', 'FIN');
INSERT INTO jrn_action VALUES (22, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'FIN');
INSERT INTO jrn_action VALUES (24, 'Impression', 'Impression du journal', 'impress.php', 'filter=1&action=viewpdf&type=jrn', 'FR', 'FIN');
INSERT INTO jrn_action VALUES (26, 'Recherche', 'Recherche dans le journal', 'user_jrn.php', 'action=search', 'FR', 'FIN');
INSERT INTO jrn_action VALUES (30, 'Nouveau', NULL, 'user_jrn.php', 'action=new&blank', 'FR', 'OD ');
INSERT INTO jrn_action VALUES (32, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'OD ');
INSERT INTO jrn_action VALUES (34, 'Impression', 'Impression du journal', 'impress.php', 'filter=1&action=viewpdf&type=jrn', 'FR', 'OD ');
INSERT INTO jrn_action VALUES (36, 'Recherche', 'Recherche dans le journal', 'user_jrn.php', 'action=search', 'FR', 'OD ');


--
-- Data for TOC entry 144 (OID 35032)
-- Name: tva_rate; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO tva_rate VALUES (1, '21%', 0.21, 'Tva applicable à tout ce qui bien et service divers', '4111,4511');
INSERT INTO tva_rate VALUES (2, '12%', 0.12, 'Tva ', '4112,4512');
INSERT INTO tva_rate VALUES (3, '6%', 0.06, 'Tva applicable aux journaux et livres', '4113,4513');
INSERT INTO tva_rate VALUES (4, '0%', 0, 'Tva applicable lors de vente/achat intracommunautaire', '4114,4514');


--
-- Data for TOC entry 145 (OID 35048)
-- Name: fiche_def_ref; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO fiche_def_ref VALUES (1, 'Vente Service', 700);
INSERT INTO fiche_def_ref VALUES (2, 'Achat Marchandises', 604);
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
INSERT INTO fiche_def_ref VALUES (14, 'Administration des Finances', NULL);


--
-- Data for TOC entry 146 (OID 35054)
-- Name: fiche_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO fiche_def VALUES (1, 5500, 'Banque', true, 4);
INSERT INTO fiche_def VALUES (2, 400, 'Client', true, 9);
INSERT INTO fiche_def VALUES (3, 700, 'Vente', false, 1);
INSERT INTO fiche_def VALUES (4, 440, 'Fournisseur', true, 8);
INSERT INTO fiche_def VALUES (5, 61, 'Charges', true, 3);
INSERT INTO fiche_def VALUES (6, 604, 'Marchandise', true, 2);
INSERT INTO fiche_def VALUES (9, NULL, 'Taxes & impot', false, 14);
INSERT INTO fiche_def VALUES (7, 604, 'March Cat A', true, 2);
INSERT INTO fiche_def VALUES (8, 700, 'March Cat b', false, 1);


--
-- Data for TOC entry 147 (OID 35061)
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
INSERT INTO attr_def VALUES (19, 'Gestion stock');


--
-- Data for TOC entry 148 (OID 35067)
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
INSERT INTO attr_min VALUES (1, 19);
INSERT INTO attr_min VALUES (2, 19);
INSERT INTO attr_min VALUES (14, 1);


--
-- Data for TOC entry 149 (OID 35069)
-- Name: fiche; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO fiche VALUES (1, 1);
INSERT INTO fiche VALUES (2, 2);
INSERT INTO fiche VALUES (3, 2);
INSERT INTO fiche VALUES (4, 3);
INSERT INTO fiche VALUES (5, 3);
INSERT INTO fiche VALUES (6, 3);
INSERT INTO fiche VALUES (7, 3);
INSERT INTO fiche VALUES (8, 4);
INSERT INTO fiche VALUES (9, 4);
INSERT INTO fiche VALUES (10, 5);
INSERT INTO fiche VALUES (11, 5);
INSERT INTO fiche VALUES (12, 1);
INSERT INTO fiche VALUES (13, 2);
INSERT INTO fiche VALUES (14, 2);
INSERT INTO fiche VALUES (15, 2);


--
-- Data for TOC entry 150 (OID 35072)
-- Name: jnt_fic_att_value; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jnt_fic_att_value VALUES (1, 1, 5);
INSERT INTO jnt_fic_att_value VALUES (2, 1, 1);
INSERT INTO jnt_fic_att_value VALUES (3, 1, 3);
INSERT INTO jnt_fic_att_value VALUES (4, 1, 4);
INSERT INTO jnt_fic_att_value VALUES (5, 1, 12);
INSERT INTO jnt_fic_att_value VALUES (6, 1, 13);
INSERT INTO jnt_fic_att_value VALUES (7, 1, 14);
INSERT INTO jnt_fic_att_value VALUES (8, 1, 15);
INSERT INTO jnt_fic_att_value VALUES (9, 1, 16);
INSERT INTO jnt_fic_att_value VALUES (10, 1, 17);
INSERT INTO jnt_fic_att_value VALUES (11, 1, 18);
INSERT INTO jnt_fic_att_value VALUES (12, 2, 5);
INSERT INTO jnt_fic_att_value VALUES (13, 2, 1);
INSERT INTO jnt_fic_att_value VALUES (14, 2, 12);
INSERT INTO jnt_fic_att_value VALUES (15, 2, 13);
INSERT INTO jnt_fic_att_value VALUES (16, 2, 14);
INSERT INTO jnt_fic_att_value VALUES (17, 2, 15);
INSERT INTO jnt_fic_att_value VALUES (18, 2, 16);
INSERT INTO jnt_fic_att_value VALUES (19, 2, 17);
INSERT INTO jnt_fic_att_value VALUES (20, 2, 18);
INSERT INTO jnt_fic_att_value VALUES (21, 3, 5);
INSERT INTO jnt_fic_att_value VALUES (22, 3, 1);
INSERT INTO jnt_fic_att_value VALUES (23, 3, 12);
INSERT INTO jnt_fic_att_value VALUES (24, 3, 13);
INSERT INTO jnt_fic_att_value VALUES (25, 3, 14);
INSERT INTO jnt_fic_att_value VALUES (26, 3, 15);
INSERT INTO jnt_fic_att_value VALUES (27, 3, 16);
INSERT INTO jnt_fic_att_value VALUES (28, 3, 17);
INSERT INTO jnt_fic_att_value VALUES (29, 3, 18);
INSERT INTO jnt_fic_att_value VALUES (30, 4, 1);
INSERT INTO jnt_fic_att_value VALUES (31, 4, 2);
INSERT INTO jnt_fic_att_value VALUES (32, 4, 6);
INSERT INTO jnt_fic_att_value VALUES (33, 4, 7);
INSERT INTO jnt_fic_att_value VALUES (34, 5, 1);
INSERT INTO jnt_fic_att_value VALUES (35, 5, 2);
INSERT INTO jnt_fic_att_value VALUES (36, 5, 6);
INSERT INTO jnt_fic_att_value VALUES (37, 5, 7);
INSERT INTO jnt_fic_att_value VALUES (38, 6, 1);
INSERT INTO jnt_fic_att_value VALUES (39, 6, 2);
INSERT INTO jnt_fic_att_value VALUES (40, 6, 6);
INSERT INTO jnt_fic_att_value VALUES (41, 6, 7);
INSERT INTO jnt_fic_att_value VALUES (42, 7, 1);
INSERT INTO jnt_fic_att_value VALUES (43, 7, 2);
INSERT INTO jnt_fic_att_value VALUES (44, 7, 6);
INSERT INTO jnt_fic_att_value VALUES (45, 7, 7);
INSERT INTO jnt_fic_att_value VALUES (46, 4, 5);
INSERT INTO jnt_fic_att_value VALUES (47, 5, 5);
INSERT INTO jnt_fic_att_value VALUES (48, 6, 5);
INSERT INTO jnt_fic_att_value VALUES (49, 7, 5);
INSERT INTO jnt_fic_att_value VALUES (50, 8, 5);
INSERT INTO jnt_fic_att_value VALUES (51, 8, 1);
INSERT INTO jnt_fic_att_value VALUES (52, 8, 12);
INSERT INTO jnt_fic_att_value VALUES (53, 8, 13);
INSERT INTO jnt_fic_att_value VALUES (54, 8, 14);
INSERT INTO jnt_fic_att_value VALUES (55, 8, 15);
INSERT INTO jnt_fic_att_value VALUES (56, 8, 16);
INSERT INTO jnt_fic_att_value VALUES (57, 8, 17);
INSERT INTO jnt_fic_att_value VALUES (58, 8, 18);
INSERT INTO jnt_fic_att_value VALUES (59, 9, 5);
INSERT INTO jnt_fic_att_value VALUES (60, 9, 1);
INSERT INTO jnt_fic_att_value VALUES (61, 9, 12);
INSERT INTO jnt_fic_att_value VALUES (62, 9, 13);
INSERT INTO jnt_fic_att_value VALUES (63, 9, 14);
INSERT INTO jnt_fic_att_value VALUES (64, 9, 15);
INSERT INTO jnt_fic_att_value VALUES (65, 9, 16);
INSERT INTO jnt_fic_att_value VALUES (66, 9, 17);
INSERT INTO jnt_fic_att_value VALUES (67, 9, 18);
INSERT INTO jnt_fic_att_value VALUES (68, 10, 5);
INSERT INTO jnt_fic_att_value VALUES (69, 10, 1);
INSERT INTO jnt_fic_att_value VALUES (70, 10, 2);
INSERT INTO jnt_fic_att_value VALUES (71, 10, 7);
INSERT INTO jnt_fic_att_value VALUES (72, 11, 5);
INSERT INTO jnt_fic_att_value VALUES (73, 11, 1);
INSERT INTO jnt_fic_att_value VALUES (74, 11, 2);
INSERT INTO jnt_fic_att_value VALUES (75, 11, 7);
INSERT INTO jnt_fic_att_value VALUES (76, 12, 5);
INSERT INTO jnt_fic_att_value VALUES (77, 12, 1);
INSERT INTO jnt_fic_att_value VALUES (78, 12, 3);
INSERT INTO jnt_fic_att_value VALUES (79, 12, 4);
INSERT INTO jnt_fic_att_value VALUES (80, 12, 12);
INSERT INTO jnt_fic_att_value VALUES (81, 12, 13);
INSERT INTO jnt_fic_att_value VALUES (82, 12, 14);
INSERT INTO jnt_fic_att_value VALUES (83, 12, 15);
INSERT INTO jnt_fic_att_value VALUES (84, 12, 16);
INSERT INTO jnt_fic_att_value VALUES (85, 12, 17);
INSERT INTO jnt_fic_att_value VALUES (86, 12, 18);
INSERT INTO jnt_fic_att_value VALUES (87, 13, 5);
INSERT INTO jnt_fic_att_value VALUES (88, 14, 5);
INSERT INTO jnt_fic_att_value VALUES (89, 14, 1);
INSERT INTO jnt_fic_att_value VALUES (90, 14, 12);
INSERT INTO jnt_fic_att_value VALUES (91, 14, 13);
INSERT INTO jnt_fic_att_value VALUES (92, 14, 14);
INSERT INTO jnt_fic_att_value VALUES (93, 14, 15);
INSERT INTO jnt_fic_att_value VALUES (94, 14, 16);
INSERT INTO jnt_fic_att_value VALUES (95, 14, 17);
INSERT INTO jnt_fic_att_value VALUES (96, 14, 18);
INSERT INTO jnt_fic_att_value VALUES (97, 15, 5);
INSERT INTO jnt_fic_att_value VALUES (98, 15, 1);
INSERT INTO jnt_fic_att_value VALUES (99, 15, 12);
INSERT INTO jnt_fic_att_value VALUES (100, 15, 13);
INSERT INTO jnt_fic_att_value VALUES (101, 15, 14);
INSERT INTO jnt_fic_att_value VALUES (102, 15, 15);
INSERT INTO jnt_fic_att_value VALUES (103, 15, 16);
INSERT INTO jnt_fic_att_value VALUES (104, 15, 17);
INSERT INTO jnt_fic_att_value VALUES (105, 15, 18);


--
-- Data for TOC entry 151 (OID 35075)
-- Name: attr_value; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO attr_value VALUES (12, '4000001');
INSERT INTO attr_value VALUES (13, 'Client 1');
INSERT INTO attr_value VALUES (14, '');
INSERT INTO attr_value VALUES (15, '');
INSERT INTO attr_value VALUES (16, 'Rue du Client 1');
INSERT INTO attr_value VALUES (17, '99999');
INSERT INTO attr_value VALUES (18, 'Belgique');
INSERT INTO attr_value VALUES (19, '');
INSERT INTO attr_value VALUES (20, '');
INSERT INTO attr_value VALUES (21, '4000002');
INSERT INTO attr_value VALUES (22, 'Client 2');
INSERT INTO attr_value VALUES (23, '');
INSERT INTO attr_value VALUES (24, '');
INSERT INTO attr_value VALUES (25, 'Rue du client 2');
INSERT INTO attr_value VALUES (26, '108000');
INSERT INTO attr_value VALUES (27, 'Belgique');
INSERT INTO attr_value VALUES (28, '');
INSERT INTO attr_value VALUES (29, '');
INSERT INTO attr_value VALUES (30, 'Marchandise A');
INSERT INTO attr_value VALUES (31, '1');
INSERT INTO attr_value VALUES (46, '70001');
INSERT INTO attr_value VALUES (32, '100');
INSERT INTO attr_value VALUES (33, '120');
INSERT INTO attr_value VALUES (34, 'Marchandise B');
INSERT INTO attr_value VALUES (35, '1');
INSERT INTO attr_value VALUES (47, '70002');
INSERT INTO attr_value VALUES (36, '150');
INSERT INTO attr_value VALUES (37, '140');
INSERT INTO attr_value VALUES (38, 'Marchandise C');
INSERT INTO attr_value VALUES (39, '2');
INSERT INTO attr_value VALUES (48, '70003');
INSERT INTO attr_value VALUES (40, '200');
INSERT INTO attr_value VALUES (41, '100');
INSERT INTO attr_value VALUES (42, 'Marchandise D');
INSERT INTO attr_value VALUES (43, '3');
INSERT INTO attr_value VALUES (49, '70004');
INSERT INTO attr_value VALUES (44, '150');
INSERT INTO attr_value VALUES (45, '75');
INSERT INTO attr_value VALUES (50, '4400001');
INSERT INTO attr_value VALUES (51, 'Fournisseur A');
INSERT INTO attr_value VALUES (52, '');
INSERT INTO attr_value VALUES (53, '');
INSERT INTO attr_value VALUES (54, '');
INSERT INTO attr_value VALUES (55, '');
INSERT INTO attr_value VALUES (56, '');
INSERT INTO attr_value VALUES (57, '');
INSERT INTO attr_value VALUES (58, '');
INSERT INTO attr_value VALUES (59, '4400002');
INSERT INTO attr_value VALUES (60, 'Fournisseur B');
INSERT INTO attr_value VALUES (61, '');
INSERT INTO attr_value VALUES (62, '');
INSERT INTO attr_value VALUES (63, '');
INSERT INTO attr_value VALUES (64, '');
INSERT INTO attr_value VALUES (65, '');
INSERT INTO attr_value VALUES (66, '');
INSERT INTO attr_value VALUES (67, '');
INSERT INTO attr_value VALUES (68, '610001');
INSERT INTO attr_value VALUES (69, 'fourniture A');
INSERT INTO attr_value VALUES (70, '1');
INSERT INTO attr_value VALUES (71, '');
INSERT INTO attr_value VALUES (76, '55000002');
INSERT INTO attr_value VALUES (77, 'Argenta');
INSERT INTO attr_value VALUES (78, '');
INSERT INTO attr_value VALUES (79, '');
INSERT INTO attr_value VALUES (80, '');
INSERT INTO attr_value VALUES (81, '');
INSERT INTO attr_value VALUES (82, '');
INSERT INTO attr_value VALUES (83, '');
INSERT INTO attr_value VALUES (84, '');
INSERT INTO attr_value VALUES (85, '');
INSERT INTO attr_value VALUES (86, '');
INSERT INTO attr_value VALUES (11, '');
INSERT INTO attr_value VALUES (10, 'a');
INSERT INTO attr_value VALUES (9, '');
INSERT INTO attr_value VALUES (8, '');
INSERT INTO attr_value VALUES (7, '');
INSERT INTO attr_value VALUES (6, '');
INSERT INTO attr_value VALUES (5, '');
INSERT INTO attr_value VALUES (4, '');
INSERT INTO attr_value VALUES (3, '');
INSERT INTO attr_value VALUES (2, 'Banque 1');
INSERT INTO attr_value VALUES (1, '55000001');
INSERT INTO attr_value VALUES (87, '4000003');
INSERT INTO attr_value VALUES (88, '4000004');
INSERT INTO attr_value VALUES (89, 'Toto');
INSERT INTO attr_value VALUES (90, '');
INSERT INTO attr_value VALUES (91, '');
INSERT INTO attr_value VALUES (92, 'Maison de toto');
INSERT INTO attr_value VALUES (93, 'cp');
INSERT INTO attr_value VALUES (94, 'pays');
INSERT INTO attr_value VALUES (95, '');
INSERT INTO attr_value VALUES (96, '');
INSERT INTO attr_value VALUES (97, '4000005');
INSERT INTO attr_value VALUES (98, 'NOUVEAU CLIENT');
INSERT INTO attr_value VALUES (99, 'Toto');
INSERT INTO attr_value VALUES (100, '');
INSERT INTO attr_value VALUES (101, 'Adresse');
INSERT INTO attr_value VALUES (102, '');
INSERT INTO attr_value VALUES (103, '');
INSERT INTO attr_value VALUES (104, '');
INSERT INTO attr_value VALUES (105, '');
INSERT INTO attr_value VALUES (73, 'Loyer');
INSERT INTO attr_value VALUES (74, '3');
INSERT INTO attr_value VALUES (72, '610002');
INSERT INTO attr_value VALUES (75, '380');


--
-- Data for TOC entry 152 (OID 35080)
-- Name: jnt_fic_attr; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jnt_fic_attr VALUES (1, 5);
INSERT INTO jnt_fic_attr VALUES (1, 1);
INSERT INTO jnt_fic_attr VALUES (1, 3);
INSERT INTO jnt_fic_attr VALUES (1, 4);
INSERT INTO jnt_fic_attr VALUES (1, 12);
INSERT INTO jnt_fic_attr VALUES (1, 13);
INSERT INTO jnt_fic_attr VALUES (1, 14);
INSERT INTO jnt_fic_attr VALUES (1, 15);
INSERT INTO jnt_fic_attr VALUES (1, 16);
INSERT INTO jnt_fic_attr VALUES (1, 17);
INSERT INTO jnt_fic_attr VALUES (1, 18);
INSERT INTO jnt_fic_attr VALUES (2, 5);
INSERT INTO jnt_fic_attr VALUES (2, 1);
INSERT INTO jnt_fic_attr VALUES (2, 12);
INSERT INTO jnt_fic_attr VALUES (2, 13);
INSERT INTO jnt_fic_attr VALUES (2, 14);
INSERT INTO jnt_fic_attr VALUES (2, 15);
INSERT INTO jnt_fic_attr VALUES (2, 16);
INSERT INTO jnt_fic_attr VALUES (2, 17);
INSERT INTO jnt_fic_attr VALUES (2, 18);
INSERT INTO jnt_fic_attr VALUES (3, 1);
INSERT INTO jnt_fic_attr VALUES (3, 2);
INSERT INTO jnt_fic_attr VALUES (3, 6);
INSERT INTO jnt_fic_attr VALUES (3, 7);
INSERT INTO jnt_fic_attr VALUES (3, 5);
INSERT INTO jnt_fic_attr VALUES (4, 5);
INSERT INTO jnt_fic_attr VALUES (4, 1);
INSERT INTO jnt_fic_attr VALUES (4, 12);
INSERT INTO jnt_fic_attr VALUES (4, 13);
INSERT INTO jnt_fic_attr VALUES (4, 14);
INSERT INTO jnt_fic_attr VALUES (4, 15);
INSERT INTO jnt_fic_attr VALUES (4, 16);
INSERT INTO jnt_fic_attr VALUES (4, 17);
INSERT INTO jnt_fic_attr VALUES (4, 18);
INSERT INTO jnt_fic_attr VALUES (5, 5);
INSERT INTO jnt_fic_attr VALUES (5, 1);
INSERT INTO jnt_fic_attr VALUES (5, 2);
INSERT INTO jnt_fic_attr VALUES (5, 7);
INSERT INTO jnt_fic_attr VALUES (1, 19);
INSERT INTO jnt_fic_attr VALUES (2, 19);
INSERT INTO jnt_fic_attr VALUES (6, 5);
INSERT INTO jnt_fic_attr VALUES (6, 1);
INSERT INTO jnt_fic_attr VALUES (6, 2);
INSERT INTO jnt_fic_attr VALUES (6, 6);
INSERT INTO jnt_fic_attr VALUES (6, 7);
INSERT INTO jnt_fic_attr VALUES (6, 19);
INSERT INTO jnt_fic_attr VALUES (7, 5);
INSERT INTO jnt_fic_attr VALUES (7, 1);
INSERT INTO jnt_fic_attr VALUES (7, 2);
INSERT INTO jnt_fic_attr VALUES (7, 6);
INSERT INTO jnt_fic_attr VALUES (7, 7);
INSERT INTO jnt_fic_attr VALUES (7, 19);
INSERT INTO jnt_fic_attr VALUES (8, 1);
INSERT INTO jnt_fic_attr VALUES (8, 2);
INSERT INTO jnt_fic_attr VALUES (8, 6);
INSERT INTO jnt_fic_attr VALUES (8, 7);
INSERT INTO jnt_fic_attr VALUES (8, 19);
INSERT INTO jnt_fic_attr VALUES (9, 1);
INSERT INTO jnt_fic_attr VALUES (9, 5);
INSERT INTO jnt_fic_attr VALUES (9, 9);
INSERT INTO jnt_fic_attr VALUES (9, 12);
INSERT INTO jnt_fic_attr VALUES (9, 14);
INSERT INTO jnt_fic_attr VALUES (9, 15);
INSERT INTO jnt_fic_attr VALUES (9, 17);
INSERT INTO jnt_fic_attr VALUES (9, 18);
INSERT INTO jnt_fic_attr VALUES (9, 16);


--
-- Data for TOC entry 153 (OID 35084)
-- Name: stock_goods; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO stock_goods VALUES (3, 267, 6, 0, 10);
INSERT INTO stock_goods VALUES (4, 268, 5, 0, 11);


--
-- Data for TOC entry 154 (OID 35089)
-- Name: test; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO test VALUES ('1toto', '2');


--
-- Data for TOC entry 155 (OID 35096)
-- Name: jrn_rapt; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn_rapt VALUES (2, 60, 63);


--
-- TOC entry 106 (OID 36430)
-- Name: x_act; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE UNIQUE INDEX x_act ON "action" USING btree (ac_description);


--
-- TOC entry 104 (OID 36431)
-- Name: x_usr_jrn; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE UNIQUE INDEX x_usr_jrn ON user_sec_jrn USING btree (uj_login, uj_jrn_id);


--
-- TOC entry 100 (OID 36432)
-- Name: fk_centralized_c_jrn_def; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_centralized_c_jrn_def ON centralized USING btree (c_jrn_def);


--
-- TOC entry 101 (OID 36433)
-- Name: fk_centralized_c_poste; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_centralized_c_poste ON centralized USING btree (c_poste);


--
-- TOC entry 113 (OID 36434)
-- Name: fk_fiche_def_frd_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_fiche_def_frd_id ON fiche_def USING btree (frd_id);


--
-- TOC entry 120 (OID 36435)
-- Name: fk_attr_value_jft_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_attr_value_jft_id ON attr_value USING btree (jft_id);


--
-- TOC entry 116 (OID 36436)
-- Name: fk_attr_min_frd_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_attr_min_frd_id ON attr_min USING btree (frd_id);


--
-- TOC entry 115 (OID 36437)
-- Name: fk_attr_min_ad_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_attr_min_ad_id ON attr_min USING btree (ad_id);


--
-- TOC entry 118 (OID 36438)
-- Name: fk_fiche_fd_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_fiche_fd_id ON fiche USING btree (fd_id);


--
-- TOC entry 94 (OID 36439)
-- Name: fk_form_fo_fr_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_form_fo_fr_id ON form USING btree (fo_fr_id);


--
-- TOC entry 90 (OID 36440)
-- Name: fk_jrnx_j_poste; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_jrnx_j_poste ON jrnx USING btree (j_poste);


--
-- TOC entry 89 (OID 36441)
-- Name: fk_jrn_def; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_jrn_def ON jrnx USING btree (j_jrn_def);


--
-- TOC entry 109 (OID 36442)
-- Name: fk_jrn_action_ja_jrn_type; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_jrn_action_ja_jrn_type ON jrn_action USING btree (ja_jrn_type);


--
-- TOC entry 102 (OID 36443)
-- Name: fk_user_sec_jrn; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_user_sec_jrn ON user_sec_jrn USING btree (uj_jrn_id);


--
-- TOC entry 107 (OID 36444)
-- Name: fk_user_sec_act; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_user_sec_act ON user_sec_act USING btree (ua_act_id);


--
-- TOC entry 85 (OID 36445)
-- Name: fk_jrn_jr_def_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_jrn_jr_def_id ON jrn USING btree (jr_def_id);


--
-- TOC entry 122 (OID 36446)
-- Name: fk_stock_goods_j_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_stock_goods_j_id ON stock_goods USING btree (j_id);


--
-- TOC entry 121 (OID 36447)
-- Name: fk_stock_goods_f_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_stock_goods_f_id ON stock_goods USING btree (f_id);


--
-- TOC entry 86 (OID 36448)
-- Name: idx_jr_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE UNIQUE INDEX idx_jr_id ON jrn USING btree (jr_id);


--
-- TOC entry 78 (OID 36449)
-- Name: tmp_pcmn_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY tmp_pcmn
    ADD CONSTRAINT tmp_pcmn_pkey PRIMARY KEY (pcm_val);


--
-- TOC entry 79 (OID 36451)
-- Name: parm_money_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY parm_money
    ADD CONSTRAINT parm_money_pkey PRIMARY KEY (pm_code);


--
-- TOC entry 81 (OID 36453)
-- Name: parm_periode_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY parm_periode
    ADD CONSTRAINT parm_periode_pkey PRIMARY KEY (p_id);


--
-- TOC entry 80 (OID 36455)
-- Name: parm_periode_p_start_key; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY parm_periode
    ADD CONSTRAINT parm_periode_p_start_key UNIQUE (p_start);


--
-- TOC entry 82 (OID 36457)
-- Name: jrn_type_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_type
    ADD CONSTRAINT jrn_type_pkey PRIMARY KEY (jrn_type_id);


--
-- TOC entry 84 (OID 36459)
-- Name: jrn_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT jrn_def_pkey PRIMARY KEY (jrn_def_id);


--
-- TOC entry 83 (OID 36461)
-- Name: jrn_def_jrn_def_name_key; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT jrn_def_jrn_def_name_key UNIQUE (jrn_def_name);


--
-- TOC entry 156 (OID 36463)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT "$1" FOREIGN KEY (jrn_def_type) REFERENCES jrn_type(jrn_type_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 88 (OID 36467)
-- Name: jrn_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn
    ADD CONSTRAINT jrn_pkey PRIMARY KEY (jr_id, jr_def_id);


--
-- TOC entry 91 (OID 36469)
-- Name: jrnx_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrnx
    ADD CONSTRAINT jrnx_pkey PRIMARY KEY (j_id);


--
-- TOC entry 157 (OID 36471)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrnx
    ADD CONSTRAINT "$1" FOREIGN KEY (j_poste) REFERENCES tmp_pcmn(pcm_val) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 158 (OID 36475)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrnx
    ADD CONSTRAINT "$2" FOREIGN KEY (j_jrn_def) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 92 (OID 36479)
-- Name: user_pref_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_pref
    ADD CONSTRAINT user_pref_pkey PRIMARY KEY (pref_user);


--
-- TOC entry 93 (OID 36481)
-- Name: formdef_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY formdef
    ADD CONSTRAINT formdef_pkey PRIMARY KEY (fr_id);


--
-- TOC entry 95 (OID 36483)
-- Name: form_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY form
    ADD CONSTRAINT form_pkey PRIMARY KEY (fo_id);


--
-- TOC entry 159 (OID 36485)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY form
    ADD CONSTRAINT "$1" FOREIGN KEY (fo_fr_id) REFERENCES formdef(fr_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 96 (OID 36489)
-- Name: fichedef_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fichedef
    ADD CONSTRAINT fichedef_pkey PRIMARY KEY (fd_id);


--
-- TOC entry 97 (OID 36491)
-- Name: isupp_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY isupp_def
    ADD CONSTRAINT isupp_def_pkey PRIMARY KEY (isd_id);


--
-- TOC entry 160 (OID 36493)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY isupp_def
    ADD CONSTRAINT "$1" FOREIGN KEY (isd_fd_id) REFERENCES fichedef(fd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 98 (OID 36497)
-- Name: isupp_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY isupp
    ADD CONSTRAINT isupp_pkey PRIMARY KEY (is_id);


--
-- TOC entry 161 (OID 36499)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY isupp
    ADD CONSTRAINT "$1" FOREIGN KEY (is_f_id) REFERENCES tmp_pcmn(pcm_val) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 162 (OID 36503)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY isupp
    ADD CONSTRAINT "$2" FOREIGN KEY (is_isd_id) REFERENCES isupp_def(isd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 99 (OID 36507)
-- Name: centralized_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY centralized
    ADD CONSTRAINT centralized_pkey PRIMARY KEY (c_id);


--
-- TOC entry 163 (OID 36509)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY centralized
    ADD CONSTRAINT "$1" FOREIGN KEY (c_jrn_def) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 164 (OID 36513)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY centralized
    ADD CONSTRAINT "$2" FOREIGN KEY (c_poste) REFERENCES tmp_pcmn(pcm_val) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 103 (OID 36517)
-- Name: user_sec_jrn_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_jrn
    ADD CONSTRAINT user_sec_jrn_pkey PRIMARY KEY (uj_id);


--
-- TOC entry 165 (OID 36519)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_jrn
    ADD CONSTRAINT "$1" FOREIGN KEY (uj_jrn_id) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 105 (OID 36523)
-- Name: action_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY "action"
    ADD CONSTRAINT action_pkey PRIMARY KEY (ac_id);


--
-- TOC entry 108 (OID 36525)
-- Name: user_sec_act_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_act
    ADD CONSTRAINT user_sec_act_pkey PRIMARY KEY (ua_id);


--
-- TOC entry 166 (OID 36527)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_act
    ADD CONSTRAINT "$1" FOREIGN KEY (ua_act_id) REFERENCES "action"(ac_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 110 (OID 36531)
-- Name: jrn_action_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_action
    ADD CONSTRAINT jrn_action_pkey PRIMARY KEY (ja_id);


--
-- TOC entry 167 (OID 36533)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_action
    ADD CONSTRAINT "$1" FOREIGN KEY (ja_jrn_type) REFERENCES jrn_type(jrn_type_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 111 (OID 36537)
-- Name: fiche_def_ref_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche_def_ref
    ADD CONSTRAINT fiche_def_ref_pkey PRIMARY KEY (frd_id);


--
-- TOC entry 112 (OID 36539)
-- Name: fiche_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche_def
    ADD CONSTRAINT fiche_def_pkey PRIMARY KEY (fd_id);


--
-- TOC entry 168 (OID 36541)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche_def
    ADD CONSTRAINT "$1" FOREIGN KEY (frd_id) REFERENCES fiche_def_ref(frd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 114 (OID 36545)
-- Name: attr_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_def
    ADD CONSTRAINT attr_def_pkey PRIMARY KEY (ad_id);


--
-- TOC entry 169 (OID 36547)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_min
    ADD CONSTRAINT "$1" FOREIGN KEY (frd_id) REFERENCES fiche_def_ref(frd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 170 (OID 36551)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_min
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 117 (OID 36555)
-- Name: fiche_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche
    ADD CONSTRAINT fiche_pkey PRIMARY KEY (f_id);


--
-- TOC entry 171 (OID 36557)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche
    ADD CONSTRAINT "$1" FOREIGN KEY (fd_id) REFERENCES fiche_def(fd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 119 (OID 36561)
-- Name: jnt_fic_att_value_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT jnt_fic_att_value_pkey PRIMARY KEY (jft_id);


--
-- TOC entry 172 (OID 36563)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT "$1" FOREIGN KEY (f_id) REFERENCES fiche(f_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 173 (OID 36567)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 174 (OID 36571)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_value
    ADD CONSTRAINT "$1" FOREIGN KEY (jft_id) REFERENCES jnt_fic_att_value(jft_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 175 (OID 36575)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_attr
    ADD CONSTRAINT "$1" FOREIGN KEY (fd_id) REFERENCES fiche_def(fd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 176 (OID 36579)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_attr
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 123 (OID 36583)
-- Name: stock_goods_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY stock_goods
    ADD CONSTRAINT stock_goods_pkey PRIMARY KEY (sg_id);


--
-- TOC entry 177 (OID 36585)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY stock_goods
    ADD CONSTRAINT "$1" FOREIGN KEY (j_id) REFERENCES jrnx(j_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 178 (OID 36589)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY stock_goods
    ADD CONSTRAINT "$2" FOREIGN KEY (f_id) REFERENCES fiche(f_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 124 (OID 36593)
-- Name: jrn_rapt_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_rapt
    ADD CONSTRAINT jrn_rapt_pkey PRIMARY KEY (jra_id);


--
-- TOC entry 87 (OID 36595)
-- Name: jrn_jr_id_key; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn
    ADD CONSTRAINT jrn_jr_id_key UNIQUE (jr_id);


--
-- TOC entry 179 (OID 36597)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_rapt
    ADD CONSTRAINT "$1" FOREIGN KEY (jr_id) REFERENCES jrn(jr_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 180 (OID 36601)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_rapt
    ADD CONSTRAINT "$2" FOREIGN KEY (jra_concerned) REFERENCES jrn(jr_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 3 (OID 34893)
-- Name: s_periode; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_periode', 39, true);


--
-- TOC entry 5 (OID 34895)
-- Name: s_currency; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_currency', 1, true);


--
-- TOC entry 7 (OID 34908)
-- Name: s_jrn_def; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrn_def', 5, false);


--
-- TOC entry 9 (OID 34910)
-- Name: s_grpt; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_grpt', 1, false);


--
-- TOC entry 11 (OID 34912)
-- Name: s_jrn_op; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrn_op', 286, true);


--
-- TOC entry 13 (OID 34914)
-- Name: s_jrn; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrn', 79, true);


--
-- TOC entry 15 (OID 34930)
-- Name: s_jrnx; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrnx', 1, false);


--
-- TOC entry 17 (OID 34955)
-- Name: s_formdef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_formdef', 1, false);


--
-- TOC entry 19 (OID 34957)
-- Name: s_form; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_form', 1, false);


--
-- TOC entry 21 (OID 34971)
-- Name: s_isup; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_isup', 1, false);


--
-- TOC entry 23 (OID 34973)
-- Name: s_idef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_idef', 2, true);


--
-- TOC entry 25 (OID 34993)
-- Name: s_centralized; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_centralized', 1, false);


--
-- TOC entry 27 (OID 35002)
-- Name: s_user_jrn; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_user_jrn', 12, true);


--
-- TOC entry 29 (OID 35004)
-- Name: s_user_act; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_user_act', 13, true);


--
-- TOC entry 31 (OID 35023)
-- Name: s_jrnaction; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrnaction', 7, true);


--
-- TOC entry 33 (OID 35038)
-- Name: s_fiche; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_fiche', 15, true);


--
-- TOC entry 35 (OID 35040)
-- Name: s_fiche_def_ref; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_fiche_def_ref', 14, true);


--
-- TOC entry 37 (OID 35042)
-- Name: s_fdef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_fdef', 9, true);


--
-- TOC entry 39 (OID 35044)
-- Name: s_attr_def; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_attr_def', 19, true);


--
-- TOC entry 41 (OID 35046)
-- Name: s_jnt_fic_att_value; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jnt_fic_att_value', 105, true);


--
-- TOC entry 43 (OID 35082)
-- Name: s_stock_goods; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_stock_goods', 4, true);


--
-- TOC entry 45 (OID 35094)
-- Name: s_jrn_rapt; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrn_rapt', 2, true);


