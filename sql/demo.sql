--
-- PostgreSQL database dump
--

\connect - phpcompta

SET search_path = public, pg_catalog;

--
-- TOC entry 44 (OID 34884)
-- Name: tmp_pcmn; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE tmp_pcmn (
    pcm_val integer NOT NULL,
    pcm_lib text,
    pcm_val_parent integer DEFAULT 0,
    pcm_country character(2) DEFAULT 'BE' NOT NULL
);


--
-- TOC entry 45 (OID 34891)
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
-- TOC entry 46 (OID 34897)
-- Name: parm_money; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE parm_money (
    pm_id integer DEFAULT nextval('s_currency'::text),
    pm_code character(3) NOT NULL,
    pm_rate double precision
);


--
-- TOC entry 47 (OID 34900)
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
-- TOC entry 48 (OID 34916)
-- Name: jrn_type; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jrn_type (
    jrn_type_id character(3) NOT NULL,
    jrn_desc text
);


--
-- TOC entry 49 (OID 34921)
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
-- TOC entry 50 (OID 34932)
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
-- TOC entry 51 (OID 34939)
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
-- TOC entry 52 (OID 34950)
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
-- TOC entry 53 (OID 34959)
-- Name: formdef; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE formdef (
    fr_id integer DEFAULT nextval('s_formdef'::text) NOT NULL,
    fr_label text
);


--
-- TOC entry 54 (OID 34965)
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
-- TOC entry 20 (OID 34973)
-- Name: s_idef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_idef
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 22 (OID 34993)
-- Name: s_centralized; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_centralized
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 55 (OID 34995)
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
-- TOC entry 24 (OID 35002)
-- Name: s_user_jrn; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_user_jrn
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 26 (OID 35004)
-- Name: s_user_act; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_user_act
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 56 (OID 35006)
-- Name: user_sec_jrn; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE user_sec_jrn (
    uj_id integer DEFAULT nextval('s_user_jrn'::text) NOT NULL,
    uj_login text,
    uj_jrn_id integer,
    uj_priv text
);


--
-- TOC entry 57 (OID 35012)
-- Name: action; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE "action" (
    ac_id integer NOT NULL,
    ac_description text NOT NULL
);


--
-- TOC entry 58 (OID 35017)
-- Name: user_sec_act; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE user_sec_act (
    ua_id integer DEFAULT nextval('s_user_act'::text) NOT NULL,
    ua_login text,
    ua_act_id integer
);


--
-- TOC entry 28 (OID 35023)
-- Name: s_jrnaction; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrnaction
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 59 (OID 35025)
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
-- TOC entry 60 (OID 35032)
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
-- TOC entry 30 (OID 35038)
-- Name: s_fiche; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fiche
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 32 (OID 35040)
-- Name: s_fiche_def_ref; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fiche_def_ref
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 34 (OID 35042)
-- Name: s_fdef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fdef
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 36 (OID 35044)
-- Name: s_attr_def; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_attr_def
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 38 (OID 35046)
-- Name: s_jnt_fic_att_value; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jnt_fic_att_value
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 61 (OID 35048)
-- Name: fiche_def_ref; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE fiche_def_ref (
    frd_id integer DEFAULT nextval('s_fiche_def_ref'::text) NOT NULL,
    frd_text text,
    frd_class_base integer
);


--
-- TOC entry 62 (OID 35054)
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
-- TOC entry 63 (OID 35061)
-- Name: attr_def; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_def (
    ad_id integer DEFAULT nextval('s_attr_def'::text) NOT NULL,
    ad_text text
);


--
-- TOC entry 64 (OID 35067)
-- Name: attr_min; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_min (
    frd_id integer,
    ad_id integer
);


--
-- TOC entry 65 (OID 35069)
-- Name: fiche; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE fiche (
    f_id integer DEFAULT nextval('s_fiche'::text) NOT NULL,
    fd_id integer
);


--
-- TOC entry 66 (OID 35072)
-- Name: jnt_fic_att_value; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jnt_fic_att_value (
    jft_id integer DEFAULT nextval('s_jnt_fic_att_value'::text) NOT NULL,
    f_id integer,
    ad_id integer
);


--
-- TOC entry 67 (OID 35075)
-- Name: attr_value; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_value (
    jft_id integer,
    av_text text
);


--
-- TOC entry 68 (OID 35080)
-- Name: jnt_fic_attr; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jnt_fic_attr (
    fd_id integer,
    ad_id integer
);


--
-- TOC entry 40 (OID 35094)
-- Name: s_jrn_rapt; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_rapt
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 69 (OID 35096)
-- Name: jrn_rapt; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jrn_rapt (
    jra_id integer DEFAULT nextval('s_jrn_rapt'::text) NOT NULL,
    jr_id integer NOT NULL,
    jra_concerned integer NOT NULL
);


--
-- TOC entry 70 (OID 35101)
-- Name: vw_fiche_attr; Type: VIEW; Schema: public; Owner: phpcompta
--

CREATE VIEW vw_fiche_attr AS
    SELECT a.f_id, fd_id, a.av_text AS vw_name, b.av_text AS vw_sell, c.av_text AS vw_buy, d.av_text AS tva_code, tva_id, tva_rate, tva_label, e.av_text AS vw_addr, f.av_text AS vw_cp, frd_id FROM ((((((((SELECT f_id, fd_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 1)) a LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 6)) b ON ((a.f_id = b.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 7)) c ON ((a.f_id = c.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 2)) d ON ((a.f_id = d.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 14)) e ON ((a.f_id = e.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 15)) f ON ((a.f_id = f.f_id))) LEFT JOIN tva_rate ON ((d.av_text = (tva_rate.tva_id)::text))) JOIN fiche_def USING (fd_id));


--
-- TOC entry 42 (OID 37964)
-- Name: s_stock_goods; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_stock_goods
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 71 (OID 37966)
-- Name: stock_goods; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE stock_goods (
    sg_id integer DEFAULT nextval('s_stock_goods'::text) NOT NULL,
    j_id integer NOT NULL,
    f_id integer NOT NULL,
    sg_quantity integer DEFAULT 0,
    sg_type character(1) DEFAULT 'c' NOT NULL,
    CONSTRAINT stock_goods_sg_type CHECK (((sg_type = 'c'::bpchar) OR (sg_type = 'd'::bpchar)))
);


--
-- Data for TOC entry 116 (OID 34884)
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
INSERT INTO tmp_pcmn VALUES (70002, 'Marchandise B', 700, 'BE');
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
INSERT INTO tmp_pcmn VALUES (70004, 'Marchandise D', 700, 'BE');
INSERT INTO tmp_pcmn VALUES (70003, 'Marchandise C', 700, 'BE');
INSERT INTO tmp_pcmn VALUES (70001, 'Marchandise A', 700, 'BE');
INSERT INTO tmp_pcmn VALUES (4400003, 'Fournisseur E', 440, 'BE');


--
-- Data for TOC entry 117 (OID 34891)
-- Name: version; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO "version" VALUES (3);


--
-- Data for TOC entry 118 (OID 34897)
-- Name: parm_money; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO parm_money VALUES (1, 'EUR', 1);


--
-- Data for TOC entry 119 (OID 34900)
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
-- Data for TOC entry 120 (OID 34916)
-- Name: jrn_type; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn_type VALUES ('FIN', 'Financier');
INSERT INTO jrn_type VALUES ('VEN', 'Vente');
INSERT INTO jrn_type VALUES ('ACH', 'Achat');
INSERT INTO jrn_type VALUES ('OD ', 'Opérations Diverses');


--
-- Data for TOC entry 121 (OID 34921)
-- Name: jrn_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn_def VALUES (4, 'Opération Diverses', NULL, NULL, NULL, NULL, 5, 5, false, NULL, 'OD ', 'OD-01');
INSERT INTO jrn_def VALUES (3, 'Achat', '6*', '4*', '5', '4', 1, 3, true, 'échéance', 'ACH', 'ACH-01');
INSERT INTO jrn_def VALUES (2, 'Vente', '7*', '4*', '2', '3', 1, 3, true, 'échéance', 'VEN', 'VEN-01');
INSERT INTO jrn_def VALUES (1, 'Financier', '5* ', '5*', '1,2,4,9', '1,2,4,9', 5, 5, false, NULL, 'FIN', 'FIN-01');


--
-- Data for TOC entry 122 (OID 34932)
-- Name: jrn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn VALUES (114, 4, 100, 'Annulation OD-01-00001', '2003-01-01', 2, 'Annulation :OD-01-00001', '2004-03-31 23:53:53.587346', 1, NULL, NULL);
INSERT INTO jrn VALUES (113, 4, 100, 'OD-01-00001', '2003-01-01', 1, 'OD-01-00001', '2004-03-31 23:50:06.502246', 1, NULL, NULL);
INSERT INTO jrn VALUES (115, 4, 20004, 'OD-01-00003', '2003-01-01', 3, 'OD-01-00003', '2004-04-01 00:18:53.002503', 1, NULL, NULL);
INSERT INTO jrn VALUES (116, 4, 20004, 'Annulation OD-01-00003', '2003-01-01', 10, 'OD-01-00003', '2004-04-01 00:31:24.541969', 1, NULL, NULL);
INSERT INTO jrn VALUES (117, 1, 100, 'FIN-01-00001  client : Banque 1', '2003-01-01', 11, 'FIN-01-00001', '2004-04-01 02:48:19.63074', 1, NULL, NULL);
INSERT INTO jrn VALUES (118, 1, 200, 'FIN-01-00002  client : Banque 1', '2003-01-01', 12, 'FIN-01-00002', '2004-04-01 02:50:34.708511', 1, NULL, NULL);
INSERT INTO jrn VALUES (119, 1, 300, 'FIN-01-00003  client : Banque 1', '2003-01-01', 13, 'FIN-01-00003', '2004-04-01 03:00:02.520401', 1, NULL, NULL);


--
-- Data for TOC entry 123 (OID 34939)
-- Name: jrnx; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrnx VALUES (377, '2003-01-01', 100, 4111, 1, NULL, 4, true, NULL, false, NULL, 'phpcompta', '2004-03-31 23:50:06.439088', 1);
INSERT INTO jrnx VALUES (378, '2003-01-01', 100, 4512, 1, NULL, 4, false, NULL, false, NULL, 'phpcompta', '2004-03-31 23:50:06.470302', 1);
INSERT INTO jrnx VALUES (379, '2004-03-31', 100, 4111, 2, NULL, 4, false, NULL, false, NULL, 'phpcompta', '2004-03-31 23:53:52.666875', 1);
INSERT INTO jrnx VALUES (380, '2004-03-31', 100, 4512, 2, NULL, 4, true, NULL, false, NULL, 'phpcompta', '2004-03-31 23:53:52.666875', 1);
INSERT INTO jrnx VALUES (381, '2003-01-01', 20004, 10, 3, NULL, 4, false, NULL, false, NULL, 'phpcompta', '2004-04-01 00:18:52.913242', 1);
INSERT INTO jrnx VALUES (382, '2003-01-01', 20004, 55000001, 3, NULL, 4, true, NULL, false, NULL, 'phpcompta', '2004-04-01 00:18:52.955688', 1);
INSERT INTO jrnx VALUES (383, '2004-04-01', 20004, 10, 4, NULL, 4, true, NULL, false, NULL, 'phpcompta', '2004-04-01 00:24:08.281527', 1);
INSERT INTO jrnx VALUES (384, '2004-04-01', 20004, 55000001, 4, NULL, 4, false, NULL, false, NULL, 'phpcompta', '2004-04-01 00:24:08.281527', 1);
INSERT INTO jrnx VALUES (385, '2004-04-01', 20004, 10, 5, NULL, 4, true, NULL, false, NULL, 'phpcompta', '2004-04-01 00:25:23.554034', 1);
INSERT INTO jrnx VALUES (386, '2004-04-01', 20004, 55000001, 5, NULL, 4, false, NULL, false, NULL, 'phpcompta', '2004-04-01 00:25:23.554034', 1);
INSERT INTO jrnx VALUES (387, '2004-04-01', 20004, 10, 6, NULL, 4, true, NULL, false, NULL, 'phpcompta', '2004-04-01 00:26:45.800251', 1);
INSERT INTO jrnx VALUES (388, '2004-04-01', 20004, 55000001, 6, NULL, 4, false, NULL, false, NULL, 'phpcompta', '2004-04-01 00:26:45.800251', 1);
INSERT INTO jrnx VALUES (389, '2004-04-01', 20004, 10, 7, NULL, 4, true, NULL, false, NULL, 'phpcompta', '2004-04-01 00:29:48.737261', 1);
INSERT INTO jrnx VALUES (390, '2004-04-01', 20004, 55000001, 7, NULL, 4, false, NULL, false, NULL, 'phpcompta', '2004-04-01 00:29:48.737261', 1);
INSERT INTO jrnx VALUES (391, '2004-04-01', 20004, 10, 8, NULL, 4, true, NULL, false, NULL, 'phpcompta', '2004-04-01 00:30:22.855355', 1);
INSERT INTO jrnx VALUES (392, '2004-04-01', 20004, 55000001, 8, NULL, 4, false, NULL, false, NULL, 'phpcompta', '2004-04-01 00:30:22.855355', 1);
INSERT INTO jrnx VALUES (393, '2004-04-01', 20004, 10, 9, NULL, 4, true, NULL, false, NULL, 'phpcompta', '2004-04-01 00:30:49.403648', 1);
INSERT INTO jrnx VALUES (394, '2004-04-01', 20004, 55000001, 9, NULL, 4, false, NULL, false, NULL, 'phpcompta', '2004-04-01 00:30:49.403648', 1);
INSERT INTO jrnx VALUES (395, '2004-04-01', 20004, 10, 10, NULL, 4, true, NULL, false, NULL, 'phpcompta', '2004-04-01 00:31:24.116844', 1);
INSERT INTO jrnx VALUES (396, '2004-04-01', 20004, 55000001, 10, NULL, 4, false, NULL, false, NULL, 'phpcompta', '2004-04-01 00:31:24.116844', 1);
INSERT INTO jrnx VALUES (397, '2003-01-01', 100, 55000001, 11, NULL, 1, true, NULL, false, NULL, 'phpcompta', '2004-04-01 02:48:19.589364', 1);
INSERT INTO jrnx VALUES (398, '2003-01-01', 100, 4400003, 11, NULL, 1, false, NULL, false, NULL, 'phpcompta', '2004-04-01 02:48:19.616592', 1);
INSERT INTO jrnx VALUES (399, '2003-01-01', 200, 55000001, 12, NULL, 1, false, NULL, false, NULL, 'phpcompta', '2004-04-01 02:50:34.660866', 1);
INSERT INTO jrnx VALUES (400, '2003-01-01', 200, 4400003, 12, NULL, 1, true, NULL, false, NULL, 'phpcompta', '2004-04-01 02:50:34.6831', 1);
INSERT INTO jrnx VALUES (401, '2003-01-01', 300, 55000001, 13, NULL, 1, true, NULL, false, NULL, 'phpcompta', '2004-04-01 03:00:02.490325', 1);
INSERT INTO jrnx VALUES (402, '2003-01-01', 300, 55000002, 13, NULL, 1, false, NULL, false, NULL, 'phpcompta', '2004-04-01 03:00:02.510827', 1);


--
-- Data for TOC entry 124 (OID 34950)
-- Name: user_pref; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO user_pref VALUES ('france', 1);
INSERT INTO user_pref VALUES ('dany', 30);
INSERT INTO user_pref VALUES ('phpcompta', 1);


--
-- Data for TOC entry 125 (OID 34959)
-- Name: formdef; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 126 (OID 34965)
-- Name: form; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 127 (OID 34995)
-- Name: centralized; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 128 (OID 35006)
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
-- Data for TOC entry 129 (OID 35012)
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
-- Data for TOC entry 130 (OID 35017)
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
-- Data for TOC entry 131 (OID 35025)
-- Name: jrn_action; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn_action VALUES (2, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'VEN');
INSERT INTO jrn_action VALUES (4, 'Voir Impayés', 'Voir toutes les factures non payées', 'user_jrn.php', 'action=voir_jrn_non_paye', 'FR', 'VEN');
INSERT INTO jrn_action VALUES (6, 'Recherche', 'Recherche dans le journal', 'user_jrn.php', 'action=search', 'FR', 'VEN');
INSERT INTO jrn_action VALUES (1, 'Nouvelle', 'Création d''une facture', 'user_jrn.php', 'action=insert_vente&blank', 'FR', 'VEN');
INSERT INTO jrn_action VALUES (10, 'Nouveau', 'Encode un nouvel achat (matériel, marchandises, services et biens divers)', 'user_jrn.php', 'action=new&blank', 'FR', 'ACH');
INSERT INTO jrn_action VALUES (12, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'ACH');
INSERT INTO jrn_action VALUES (14, 'Voir Impayés', 'Voir toutes les factures non payées', 'user_jrn.php', 'action=voir_jrn_non_paye', 'FR', 'ACH');
INSERT INTO jrn_action VALUES (18, 'Recherche', 'Recherche dans le journal', 'user_jrn.php', 'action=search', 'FR', 'ACH');
INSERT INTO jrn_action VALUES (20, 'Nouveau', 'Encode un nouvel achat (matériel, marchandises, services et biens divers)', 'user_jrn.php', 'action=new&blank', 'FR', 'FIN');
INSERT INTO jrn_action VALUES (22, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'FIN');
INSERT INTO jrn_action VALUES (26, 'Recherche', 'Recherche dans le journal', 'user_jrn.php', 'action=search', 'FR', 'FIN');
INSERT INTO jrn_action VALUES (30, 'Nouveau', NULL, 'user_jrn.php', 'action=new&blank', 'FR', 'OD ');
INSERT INTO jrn_action VALUES (32, 'Voir', 'Voir toutes les factures', 'user_jrn.php', 'action=voir_jrn', 'FR', 'OD ');
INSERT INTO jrn_action VALUES (36, 'Recherche', 'Recherche dans le journal', 'user_jrn.php', 'action=search', 'FR', 'OD ');
INSERT INTO jrn_action VALUES (5, 'Impression', 'Impression du journal', 'impress.php', 'filter=1&action=viewpdf&type=jrn&direct=1', 'FR', 'VEN');
INSERT INTO jrn_action VALUES (16, 'Impression', 'Impression du journal', 'impress.php', 'filter=1&action=viewpdf&type=jrn&direct=1', 'FR', 'ACH');
INSERT INTO jrn_action VALUES (24, 'Impression', 'Impression du journal', 'impress.php', 'filter=1&action=viewpdf&type=jrn&direct=1', 'FR', 'FIN');
INSERT INTO jrn_action VALUES (34, 'Impression', 'Impression du journal', 'impress.php', 'filter=1&action=viewpdf&type=jrn&direct=1', 'FR', 'OD ');


--
-- Data for TOC entry 132 (OID 35032)
-- Name: tva_rate; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO tva_rate VALUES (1, '21%', 0.21, 'Tva applicable à tout ce qui bien et service divers', '4111,4511');
INSERT INTO tva_rate VALUES (2, '12%', 0.12, 'Tva ', '4112,4512');
INSERT INTO tva_rate VALUES (3, '6%', 0.06, 'Tva applicable aux journaux et livres', '4113,4513');
INSERT INTO tva_rate VALUES (4, '0%', 0, 'Tva applicable lors de vente/achat intracommunautaire', '4114,4514');


--
-- Data for TOC entry 133 (OID 35048)
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
-- Data for TOC entry 134 (OID 35054)
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
-- Data for TOC entry 135 (OID 35061)
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
-- Data for TOC entry 136 (OID 35067)
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
-- Data for TOC entry 137 (OID 35069)
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
INSERT INTO fiche VALUES (16, 3);
INSERT INTO fiche VALUES (17, 4);


--
-- Data for TOC entry 138 (OID 35072)
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
INSERT INTO jnt_fic_att_value VALUES (106, 1, 19);
INSERT INTO jnt_fic_att_value VALUES (107, 16, 1);
INSERT INTO jnt_fic_att_value VALUES (108, 16, 2);
INSERT INTO jnt_fic_att_value VALUES (109, 16, 6);
INSERT INTO jnt_fic_att_value VALUES (110, 16, 7);
INSERT INTO jnt_fic_att_value VALUES (111, 16, 5);
INSERT INTO jnt_fic_att_value VALUES (112, 17, 5);
INSERT INTO jnt_fic_att_value VALUES (113, 17, 1);
INSERT INTO jnt_fic_att_value VALUES (114, 17, 12);
INSERT INTO jnt_fic_att_value VALUES (115, 17, 13);
INSERT INTO jnt_fic_att_value VALUES (116, 17, 14);
INSERT INTO jnt_fic_att_value VALUES (117, 17, 15);
INSERT INTO jnt_fic_att_value VALUES (118, 17, 16);
INSERT INTO jnt_fic_att_value VALUES (119, 17, 17);
INSERT INTO jnt_fic_att_value VALUES (120, 17, 18);


--
-- Data for TOC entry 139 (OID 35075)
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
INSERT INTO attr_value VALUES (34, 'Marchandise B');
INSERT INTO attr_value VALUES (35, '1');
INSERT INTO attr_value VALUES (47, '70002');
INSERT INTO attr_value VALUES (36, '150');
INSERT INTO attr_value VALUES (37, '140');
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
INSERT INTO attr_value VALUES (106, '');
INSERT INTO attr_value VALUES (42, 'Marchandise D');
INSERT INTO attr_value VALUES (43, '15');
INSERT INTO attr_value VALUES (49, '70004');
INSERT INTO attr_value VALUES (44, '150');
INSERT INTO attr_value VALUES (45, '75');
INSERT INTO attr_value VALUES (38, 'Marchandise C');
INSERT INTO attr_value VALUES (39, '20');
INSERT INTO attr_value VALUES (48, '70003');
INSERT INTO attr_value VALUES (40, '200');
INSERT INTO attr_value VALUES (41, '100');
INSERT INTO attr_value VALUES (107, 'Marchandise Ex');
INSERT INTO attr_value VALUES (108, '10');
INSERT INTO attr_value VALUES (111, '');
INSERT INTO attr_value VALUES (109, '');
INSERT INTO attr_value VALUES (110, '');
INSERT INTO attr_value VALUES (30, 'Marchandise A');
INSERT INTO attr_value VALUES (46, '70001');
INSERT INTO attr_value VALUES (32, '100');
INSERT INTO attr_value VALUES (33, '120');
INSERT INTO attr_value VALUES (31, '1');
INSERT INTO attr_value VALUES (112, '4400003');
INSERT INTO attr_value VALUES (113, 'Fournisseur E');
INSERT INTO attr_value VALUES (114, '');
INSERT INTO attr_value VALUES (115, '');
INSERT INTO attr_value VALUES (116, '');
INSERT INTO attr_value VALUES (117, '');
INSERT INTO attr_value VALUES (118, '');
INSERT INTO attr_value VALUES (119, '');
INSERT INTO attr_value VALUES (120, '');


--
-- Data for TOC entry 140 (OID 35080)
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
-- Data for TOC entry 141 (OID 35096)
-- Name: jrn_rapt; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn_rapt VALUES (10, 114, 113);


--
-- Data for TOC entry 142 (OID 37966)
-- Name: stock_goods; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- TOC entry 97 (OID 36430)
-- Name: x_act; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE UNIQUE INDEX x_act ON "action" USING btree (ac_description);


--
-- TOC entry 95 (OID 36431)
-- Name: x_usr_jrn; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE UNIQUE INDEX x_usr_jrn ON user_sec_jrn USING btree (uj_login, uj_jrn_id);


--
-- TOC entry 91 (OID 36432)
-- Name: fk_centralized_c_jrn_def; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_centralized_c_jrn_def ON centralized USING btree (c_jrn_def);


--
-- TOC entry 92 (OID 36433)
-- Name: fk_centralized_c_poste; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_centralized_c_poste ON centralized USING btree (c_poste);


--
-- TOC entry 104 (OID 36434)
-- Name: fk_fiche_def_frd_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_fiche_def_frd_id ON fiche_def USING btree (frd_id);


--
-- TOC entry 111 (OID 36435)
-- Name: fk_attr_value_jft_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_attr_value_jft_id ON attr_value USING btree (jft_id);


--
-- TOC entry 107 (OID 36436)
-- Name: fk_attr_min_frd_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_attr_min_frd_id ON attr_min USING btree (frd_id);


--
-- TOC entry 106 (OID 36437)
-- Name: fk_attr_min_ad_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_attr_min_ad_id ON attr_min USING btree (ad_id);


--
-- TOC entry 109 (OID 36438)
-- Name: fk_fiche_fd_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_fiche_fd_id ON fiche USING btree (fd_id);


--
-- TOC entry 88 (OID 36439)
-- Name: fk_form_fo_fr_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_form_fo_fr_id ON form USING btree (fo_fr_id);


--
-- TOC entry 84 (OID 36440)
-- Name: fk_jrnx_j_poste; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_jrnx_j_poste ON jrnx USING btree (j_poste);


--
-- TOC entry 83 (OID 36441)
-- Name: fk_jrn_def; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_jrn_def ON jrnx USING btree (j_jrn_def);


--
-- TOC entry 100 (OID 36442)
-- Name: fk_jrn_action_ja_jrn_type; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_jrn_action_ja_jrn_type ON jrn_action USING btree (ja_jrn_type);


--
-- TOC entry 93 (OID 36443)
-- Name: fk_user_sec_jrn; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_user_sec_jrn ON user_sec_jrn USING btree (uj_jrn_id);


--
-- TOC entry 98 (OID 36444)
-- Name: fk_user_sec_act; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_user_sec_act ON user_sec_act USING btree (ua_act_id);


--
-- TOC entry 79 (OID 36445)
-- Name: fk_jrn_jr_def_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_jrn_jr_def_id ON jrn USING btree (jr_def_id);


--
-- TOC entry 80 (OID 36448)
-- Name: idx_jr_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE UNIQUE INDEX idx_jr_id ON jrn USING btree (jr_id);


--
-- TOC entry 114 (OID 37982)
-- Name: fk_stock_goods_j_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_stock_goods_j_id ON stock_goods USING btree (j_id);


--
-- TOC entry 113 (OID 37983)
-- Name: fk_stock_goods_f_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_stock_goods_f_id ON stock_goods USING btree (f_id);


--
-- TOC entry 72 (OID 36449)
-- Name: tmp_pcmn_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY tmp_pcmn
    ADD CONSTRAINT tmp_pcmn_pkey PRIMARY KEY (pcm_val);


--
-- TOC entry 73 (OID 36451)
-- Name: parm_money_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY parm_money
    ADD CONSTRAINT parm_money_pkey PRIMARY KEY (pm_code);


--
-- TOC entry 75 (OID 36453)
-- Name: parm_periode_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY parm_periode
    ADD CONSTRAINT parm_periode_pkey PRIMARY KEY (p_id);


--
-- TOC entry 74 (OID 36455)
-- Name: parm_periode_p_start_key; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY parm_periode
    ADD CONSTRAINT parm_periode_p_start_key UNIQUE (p_start);


--
-- TOC entry 76 (OID 36457)
-- Name: jrn_type_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_type
    ADD CONSTRAINT jrn_type_pkey PRIMARY KEY (jrn_type_id);


--
-- TOC entry 78 (OID 36459)
-- Name: jrn_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT jrn_def_pkey PRIMARY KEY (jrn_def_id);


--
-- TOC entry 77 (OID 36461)
-- Name: jrn_def_jrn_def_name_key; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT jrn_def_jrn_def_name_key UNIQUE (jrn_def_name);


--
-- TOC entry 143 (OID 36463)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT "$1" FOREIGN KEY (jrn_def_type) REFERENCES jrn_type(jrn_type_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 82 (OID 36467)
-- Name: jrn_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn
    ADD CONSTRAINT jrn_pkey PRIMARY KEY (jr_id, jr_def_id);


--
-- TOC entry 85 (OID 36469)
-- Name: jrnx_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrnx
    ADD CONSTRAINT jrnx_pkey PRIMARY KEY (j_id);


--
-- TOC entry 144 (OID 36471)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrnx
    ADD CONSTRAINT "$1" FOREIGN KEY (j_poste) REFERENCES tmp_pcmn(pcm_val) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 145 (OID 36475)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrnx
    ADD CONSTRAINT "$2" FOREIGN KEY (j_jrn_def) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 86 (OID 36479)
-- Name: user_pref_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_pref
    ADD CONSTRAINT user_pref_pkey PRIMARY KEY (pref_user);


--
-- TOC entry 87 (OID 36481)
-- Name: formdef_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY formdef
    ADD CONSTRAINT formdef_pkey PRIMARY KEY (fr_id);


--
-- TOC entry 89 (OID 36483)
-- Name: form_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY form
    ADD CONSTRAINT form_pkey PRIMARY KEY (fo_id);


--
-- TOC entry 146 (OID 36485)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY form
    ADD CONSTRAINT "$1" FOREIGN KEY (fo_fr_id) REFERENCES formdef(fr_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 90 (OID 36507)
-- Name: centralized_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY centralized
    ADD CONSTRAINT centralized_pkey PRIMARY KEY (c_id);


--
-- TOC entry 147 (OID 36509)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY centralized
    ADD CONSTRAINT "$1" FOREIGN KEY (c_jrn_def) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 148 (OID 36513)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY centralized
    ADD CONSTRAINT "$2" FOREIGN KEY (c_poste) REFERENCES tmp_pcmn(pcm_val) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 94 (OID 36517)
-- Name: user_sec_jrn_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_jrn
    ADD CONSTRAINT user_sec_jrn_pkey PRIMARY KEY (uj_id);


--
-- TOC entry 149 (OID 36519)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_jrn
    ADD CONSTRAINT "$1" FOREIGN KEY (uj_jrn_id) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 96 (OID 36523)
-- Name: action_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY "action"
    ADD CONSTRAINT action_pkey PRIMARY KEY (ac_id);


--
-- TOC entry 99 (OID 36525)
-- Name: user_sec_act_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_act
    ADD CONSTRAINT user_sec_act_pkey PRIMARY KEY (ua_id);


--
-- TOC entry 150 (OID 36527)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_act
    ADD CONSTRAINT "$1" FOREIGN KEY (ua_act_id) REFERENCES "action"(ac_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 101 (OID 36531)
-- Name: jrn_action_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_action
    ADD CONSTRAINT jrn_action_pkey PRIMARY KEY (ja_id);


--
-- TOC entry 151 (OID 36533)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_action
    ADD CONSTRAINT "$1" FOREIGN KEY (ja_jrn_type) REFERENCES jrn_type(jrn_type_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 102 (OID 36537)
-- Name: fiche_def_ref_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche_def_ref
    ADD CONSTRAINT fiche_def_ref_pkey PRIMARY KEY (frd_id);


--
-- TOC entry 103 (OID 36539)
-- Name: fiche_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche_def
    ADD CONSTRAINT fiche_def_pkey PRIMARY KEY (fd_id);


--
-- TOC entry 152 (OID 36541)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche_def
    ADD CONSTRAINT "$1" FOREIGN KEY (frd_id) REFERENCES fiche_def_ref(frd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 105 (OID 36545)
-- Name: attr_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_def
    ADD CONSTRAINT attr_def_pkey PRIMARY KEY (ad_id);


--
-- TOC entry 153 (OID 36547)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_min
    ADD CONSTRAINT "$1" FOREIGN KEY (frd_id) REFERENCES fiche_def_ref(frd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 154 (OID 36551)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_min
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 108 (OID 36555)
-- Name: fiche_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche
    ADD CONSTRAINT fiche_pkey PRIMARY KEY (f_id);


--
-- TOC entry 155 (OID 36557)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche
    ADD CONSTRAINT "$1" FOREIGN KEY (fd_id) REFERENCES fiche_def(fd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 110 (OID 36561)
-- Name: jnt_fic_att_value_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT jnt_fic_att_value_pkey PRIMARY KEY (jft_id);


--
-- TOC entry 156 (OID 36563)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT "$1" FOREIGN KEY (f_id) REFERENCES fiche(f_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 157 (OID 36567)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 158 (OID 36571)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_value
    ADD CONSTRAINT "$1" FOREIGN KEY (jft_id) REFERENCES jnt_fic_att_value(jft_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 159 (OID 36575)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_attr
    ADD CONSTRAINT "$1" FOREIGN KEY (fd_id) REFERENCES fiche_def(fd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 160 (OID 36579)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_attr
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 112 (OID 36593)
-- Name: jrn_rapt_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_rapt
    ADD CONSTRAINT jrn_rapt_pkey PRIMARY KEY (jra_id);


--
-- TOC entry 81 (OID 36595)
-- Name: jrn_jr_id_key; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn
    ADD CONSTRAINT jrn_jr_id_key UNIQUE (jr_id);


--
-- TOC entry 161 (OID 36597)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_rapt
    ADD CONSTRAINT "$1" FOREIGN KEY (jr_id) REFERENCES jrn(jr_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 162 (OID 36601)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_rapt
    ADD CONSTRAINT "$2" FOREIGN KEY (jra_concerned) REFERENCES jrn(jr_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 115 (OID 37972)
-- Name: stock_goods_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY stock_goods
    ADD CONSTRAINT stock_goods_pkey PRIMARY KEY (sg_id);


--
-- TOC entry 163 (OID 37974)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY stock_goods
    ADD CONSTRAINT "$1" FOREIGN KEY (j_id) REFERENCES jrnx(j_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 164 (OID 37978)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY stock_goods
    ADD CONSTRAINT "$2" FOREIGN KEY (f_id) REFERENCES fiche(f_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


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

SELECT pg_catalog.setval ('s_jrn_op', 402, true);


--
-- TOC entry 13 (OID 34914)
-- Name: s_jrn; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrn', 119, true);


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
-- TOC entry 21 (OID 34973)
-- Name: s_idef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_idef', 2, true);


--
-- TOC entry 23 (OID 34993)
-- Name: s_centralized; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_centralized', 1, false);


--
-- TOC entry 25 (OID 35002)
-- Name: s_user_jrn; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_user_jrn', 12, true);


--
-- TOC entry 27 (OID 35004)
-- Name: s_user_act; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_user_act', 13, true);


--
-- TOC entry 29 (OID 35023)
-- Name: s_jrnaction; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrnaction', 7, true);


--
-- TOC entry 31 (OID 35038)
-- Name: s_fiche; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_fiche', 17, true);


--
-- TOC entry 33 (OID 35040)
-- Name: s_fiche_def_ref; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_fiche_def_ref', 14, true);


--
-- TOC entry 35 (OID 35042)
-- Name: s_fdef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_fdef', 9, true);


--
-- TOC entry 37 (OID 35044)
-- Name: s_attr_def; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_attr_def', 19, true);


--
-- TOC entry 39 (OID 35046)
-- Name: s_jnt_fic_att_value; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jnt_fic_att_value', 120, true);


--
-- TOC entry 41 (OID 35094)
-- Name: s_jrn_rapt; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrn_rapt', 10, true);


--
-- TOC entry 43 (OID 37964)
-- Name: s_stock_goods; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_stock_goods', 3, true);


