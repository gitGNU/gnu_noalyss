--
-- PostgreSQL database dump
--

\connect - phpcompta

SET search_path = public, pg_catalog;

--
-- TOC entry 46 (OID 34100)
-- Name: tmp_pcmn; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE tmp_pcmn (
    pcm_val integer NOT NULL,
    pcm_lib text,
    pcm_val_parent integer DEFAULT 0,
    pcm_country character(2) DEFAULT 'BE' NOT NULL
);


--
-- TOC entry 47 (OID 34107)
-- Name: version; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE "version" (
    val integer
);


--
-- TOC entry 2 (OID 34109)
-- Name: s_periode; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_periode
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 4 (OID 34111)
-- Name: s_currency; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_currency
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 48 (OID 34113)
-- Name: parm_money; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE parm_money (
    pm_id integer DEFAULT nextval('s_currency'::text),
    pm_code character(3) NOT NULL,
    pm_rate double precision
);


--
-- TOC entry 49 (OID 34116)
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
-- TOC entry 6 (OID 34124)
-- Name: s_jrn_def; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_def
    START 5
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 8 (OID 34126)
-- Name: s_grpt; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_grpt
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 10 (OID 34128)
-- Name: s_jrn_op; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_op
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 12 (OID 34130)
-- Name: s_jrn; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 50 (OID 34132)
-- Name: jrn_type; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jrn_type (
    jrn_type_id character(3) NOT NULL,
    jrn_desc text
);


--
-- TOC entry 51 (OID 34137)
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
-- TOC entry 14 (OID 34146)
-- Name: s_jrnx; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrnx
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 52 (OID 34148)
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
-- TOC entry 53 (OID 34159)
-- Name: user_pref; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE user_pref (
    pref_user text NOT NULL,
    pref_periode integer NOT NULL
);


--
-- TOC entry 16 (OID 34164)
-- Name: s_formdef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_formdef
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 18 (OID 34166)
-- Name: s_form; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_form
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 54 (OID 34168)
-- Name: formdef; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE formdef (
    fr_id integer DEFAULT nextval('s_formdef'::text) NOT NULL,
    fr_label text
);


--
-- TOC entry 55 (OID 34174)
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
-- TOC entry 20 (OID 34180)
-- Name: s_isup; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_isup
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 22 (OID 34182)
-- Name: s_idef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_idef
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 24 (OID 34184)
-- Name: s_centralized; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_centralized
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 56 (OID 34186)
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
-- TOC entry 26 (OID 34193)
-- Name: s_user_jrn; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_user_jrn
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 28 (OID 34195)
-- Name: s_user_act; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_user_act
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 57 (OID 34197)
-- Name: user_sec_jrn; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE user_sec_jrn (
    uj_id integer DEFAULT nextval('s_user_jrn'::text) NOT NULL,
    uj_login text,
    uj_jrn_id integer,
    uj_priv text
);


--
-- TOC entry 58 (OID 34203)
-- Name: action; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE "action" (
    ac_id integer NOT NULL,
    ac_description text NOT NULL
);


--
-- TOC entry 59 (OID 34208)
-- Name: user_sec_act; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE user_sec_act (
    ua_id integer DEFAULT nextval('s_user_act'::text) NOT NULL,
    ua_login text,
    ua_act_id integer
);


--
-- TOC entry 30 (OID 34214)
-- Name: s_jrnaction; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrnaction
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 60 (OID 34216)
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
-- TOC entry 61 (OID 34228)
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
-- TOC entry 32 (OID 34234)
-- Name: s_fiche; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fiche
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 34 (OID 34236)
-- Name: s_fiche_def_ref; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fiche_def_ref
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 36 (OID 34238)
-- Name: s_fdef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fdef
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 38 (OID 34240)
-- Name: s_attr_def; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_attr_def
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 40 (OID 34242)
-- Name: s_jnt_fic_att_value; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jnt_fic_att_value
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 62 (OID 34244)
-- Name: fiche_def_ref; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE fiche_def_ref (
    frd_id integer DEFAULT nextval('s_fiche_def_ref'::text) NOT NULL,
    frd_text text,
    frd_class_base integer
);


--
-- TOC entry 63 (OID 34250)
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
-- TOC entry 64 (OID 34257)
-- Name: attr_def; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_def (
    ad_id integer DEFAULT nextval('s_attr_def'::text) NOT NULL,
    ad_text text
);


--
-- TOC entry 65 (OID 34263)
-- Name: attr_min; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_min (
    frd_id integer,
    ad_id integer
);


--
-- TOC entry 66 (OID 34265)
-- Name: fiche; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE fiche (
    f_id integer DEFAULT nextval('s_fiche'::text) NOT NULL,
    fd_id integer
);


--
-- TOC entry 67 (OID 34268)
-- Name: jnt_fic_att_value; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jnt_fic_att_value (
    jft_id integer DEFAULT nextval('s_jnt_fic_att_value'::text) NOT NULL,
    f_id integer,
    ad_id integer
);


--
-- TOC entry 68 (OID 34271)
-- Name: attr_value; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_value (
    jft_id integer,
    av_text text
);


--
-- TOC entry 69 (OID 34276)
-- Name: jnt_fic_attr; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jnt_fic_attr (
    fd_id integer,
    ad_id integer
);


--
-- TOC entry 70 (OID 34280)
-- Name: vw_fiche_attr; Type: VIEW; Schema: public; Owner: phpcompta
--

CREATE VIEW vw_fiche_attr AS
    SELECT a.f_id, fd_id, a.av_text AS vw_name, b.av_text AS vw_sell, c.av_text AS vw_buy, d.av_text AS tva_code, tva_id, tva_rate, tva_label, e.av_text AS vw_addr, f.av_text AS vw_cp, frd_id FROM ((((((((SELECT f_id, fd_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 1)) a LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 6)) b ON ((a.f_id = b.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 7)) c ON ((a.f_id = c.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 2)) d ON ((a.f_id = d.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 14)) e ON ((a.f_id = e.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 15)) f ON ((a.f_id = f.f_id))) LEFT JOIN tva_rate ON ((d.av_text = (tva_rate.tva_id)::text))) JOIN fiche_def USING (fd_id));


--
-- TOC entry 42 (OID 34282)
-- Name: s_stock_goods; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_stock_goods
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 44 (OID 34284)
-- Name: s_jrn_rapt; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_rapt
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 71 (OID 34286)
-- Name: jrn_rapt; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jrn_rapt (
    jra_id integer DEFAULT nextval('s_jrn_rapt'::text) NOT NULL,
    jr_id integer NOT NULL,
    jra_concerned integer NOT NULL
);


--
-- TOC entry 72 (OID 34289)
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
    jrn_ech date,
    jr_ech date,
    jr_rapt text,
    jr_valid boolean DEFAULT true
);


--
-- TOC entry 73 (OID 34297)
-- Name: stock_goods; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE stock_goods (
    sg_id integer DEFAULT nextval('s_stock_goods'::text) NOT NULL,
    j_id integer,
    f_id integer NOT NULL,
    sg_code text,
    sg_quantity integer DEFAULT 0,
    sg_type character(1) DEFAULT 'c' NOT NULL,
    sg_date date,
    sg_tech_date date DEFAULT now(),
    sg_tech_user text,
    CONSTRAINT stock_goods_sg_type CHECK (((sg_type = 'c'::bpchar) OR (sg_type = 'd'::bpchar)))
);


--
-- Data for TOC entry 103 (OID 34100)
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
INSERT INTO tmp_pcmn VALUES (4111, 'TVA à récupérer 21%', 411, 'BE');
INSERT INTO tmp_pcmn VALUES (4112, 'TVA à récupérer 12%', 411, 'BE');
INSERT INTO tmp_pcmn VALUES (4113, 'TVA à récupérer 6% ', 411, 'BE');
INSERT INTO tmp_pcmn VALUES (4114, 'TVA à récupérer 0%', 411, 'BE');
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
INSERT INTO tmp_pcmn VALUES (4511, 'TVA à payer 21%', 451, 'BE');
INSERT INTO tmp_pcmn VALUES (4512, 'TVA à payer 12%', 451, 'BE');
INSERT INTO tmp_pcmn VALUES (4513, 'TVA à payer 6%', 451, 'BE');
INSERT INTO tmp_pcmn VALUES (4514, 'TVA à payer 0%', 451, 'BE');
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
INSERT INTO tmp_pcmn VALUES (4000003, 'Client 3', 400, 'BE');
INSERT INTO tmp_pcmn VALUES (6040001, 'Electricité', 604, 'BE');
INSERT INTO tmp_pcmn VALUES (6040002, 'Loyer', 604, 'BE');
INSERT INTO tmp_pcmn VALUES (6040003, 'Petit matériel', 604, 'BE');
INSERT INTO tmp_pcmn VALUES (6040004, 'Assurance', 604, 'BE');
INSERT INTO tmp_pcmn VALUES (55000001, 'Caisse', 5500, 'BE');
INSERT INTO tmp_pcmn VALUES (57, 'Caisse', 5, 'BE');
INSERT INTO tmp_pcmn VALUES (55000002, 'Banque 1', 5500, 'BE');
INSERT INTO tmp_pcmn VALUES (55000003, 'Banque 2', 5500, 'BE');
INSERT INTO tmp_pcmn VALUES (4400001, 'Fournisseur 1', 440, 'BE');
INSERT INTO tmp_pcmn VALUES (4400002, 'Fournisseur 2', 440, 'BE');
INSERT INTO tmp_pcmn VALUES (4400003, 'Fournisseur 4', 440, 'BE');
INSERT INTO tmp_pcmn VALUES (610001, 'Electricité', 61, 'BE');
INSERT INTO tmp_pcmn VALUES (610002, 'Loyer', 61, 'BE');
INSERT INTO tmp_pcmn VALUES (610003, 'Assurance', 61, 'BE');
INSERT INTO tmp_pcmn VALUES (610004, 'Matériel bureau', 61, 'BE');
INSERT INTO tmp_pcmn VALUES (7000002, 'Marchandise A', 700, 'BE');
INSERT INTO tmp_pcmn VALUES (7000001, 'Prestation', 700, 'BE');
INSERT INTO tmp_pcmn VALUES (7000003, 'Déplacement', 700, 'BE');
INSERT INTO tmp_pcmn VALUES (101, 'Capital non appelé', 10, 'BE');


--
-- Data for TOC entry 104 (OID 34107)
-- Name: version; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO "version" VALUES (3);


--
-- Data for TOC entry 105 (OID 34113)
-- Name: parm_money; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO parm_money VALUES (1, 'EUR', 1);


--
-- Data for TOC entry 106 (OID 34116)
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
INSERT INTO parm_periode VALUES (40, '2004-01-01', '2004-01-31', '2004', false);
INSERT INTO parm_periode VALUES (41, '2004-02-01', '2004-02-28', '2004', false);
INSERT INTO parm_periode VALUES (42, '2004-03-01', '2004-03-31', '2004', false);
INSERT INTO parm_periode VALUES (43, '2004-04-01', '2004-04-30', '2004', false);
INSERT INTO parm_periode VALUES (44, '2004-05-01', '2004-05-31', '2004', false);
INSERT INTO parm_periode VALUES (45, '2004-06-01', '2004-06-30', '2004', false);
INSERT INTO parm_periode VALUES (46, '2004-07-01', '2004-07-31', '2004', false);
INSERT INTO parm_periode VALUES (47, '2004-08-01', '2004-08-31', '2004', false);
INSERT INTO parm_periode VALUES (48, '2004-09-01', '2004-09-30', '2004', false);
INSERT INTO parm_periode VALUES (49, '2004-10-01', '2004-10-30', '2004', false);
INSERT INTO parm_periode VALUES (50, '2004-11-01', '2004-11-30', '2004', false);
INSERT INTO parm_periode VALUES (51, '2004-12-01', '2004-12-31', '2004', false);
INSERT INTO parm_periode VALUES (52, '2004-12-31', NULL, '2004', false);


--
-- Data for TOC entry 107 (OID 34132)
-- Name: jrn_type; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn_type VALUES ('FIN', 'Financier');
INSERT INTO jrn_type VALUES ('VEN', 'Vente');
INSERT INTO jrn_type VALUES ('ACH', 'Achat');
INSERT INTO jrn_type VALUES ('OD ', 'Opérations Diverses');


--
-- Data for TOC entry 108 (OID 34137)
-- Name: jrn_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jrn_def VALUES (4, 'Opération Diverses', NULL, NULL, NULL, NULL, 5, 5, false, NULL, 'OD ', 'OD-01');
INSERT INTO jrn_def VALUES (1, 'Financier', '5* ', '5*', '3,2,4', '3,2,4', 5, 5, false, NULL, 'FIN', 'FIN-01');
INSERT INTO jrn_def VALUES (3, 'Achat', '6*', '4*', '5', '4', 1, 3, true, 'échéance', 'ACH', 'ACH-01');
INSERT INTO jrn_def VALUES (2, 'Vente', '4*', '7*', '2', '6', 2, 1, true, 'échéance', 'VEN', 'VEN-01');


--
-- Data for TOC entry 109 (OID 34148)
-- Name: jrnx; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 110 (OID 34159)
-- Name: user_pref; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO user_pref VALUES ('dany', 30);


--
-- Data for TOC entry 111 (OID 34168)
-- Name: formdef; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 112 (OID 34174)
-- Name: form; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 113 (OID 34186)
-- Name: centralized; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 114 (OID 34197)
-- Name: user_sec_jrn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 115 (OID 34203)
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
-- Data for TOC entry 116 (OID 34208)
-- Name: user_sec_act; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 117 (OID 34216)
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
-- Data for TOC entry 118 (OID 34228)
-- Name: tva_rate; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO tva_rate VALUES (1, '21%', 0.21, 'Tva applicable à tout ce qui bien et service divers', '4111,4511');
INSERT INTO tva_rate VALUES (2, '12%', 0.12, 'Tva ', '4112,4512');
INSERT INTO tva_rate VALUES (3, '6%', 0.06, 'Tva applicable aux journaux et livres', '4113,4513');
INSERT INTO tva_rate VALUES (4, '0%', 0, 'Tva applicable lors de vente/achat intracommunautaire', '4114,4514');


--
-- Data for TOC entry 119 (OID 34244)
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
INSERT INTO fiche_def_ref VALUES (15, 'Autres fiches', NULL);


--
-- Data for TOC entry 120 (OID 34250)
-- Name: fiche_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO fiche_def VALUES (2, 400, 'Client', true, 9);
INSERT INTO fiche_def VALUES (1, 604, 'Marchandises', true, 2);
INSERT INTO fiche_def VALUES (3, 5500, 'Banque', true, 4);
INSERT INTO fiche_def VALUES (4, 440, 'Fournisseur', true, 8);
INSERT INTO fiche_def VALUES (5, 61, 'S & B D', true, 3);
INSERT INTO fiche_def VALUES (6, 700, 'Vente', true, 1);


--
-- Data for TOC entry 121 (OID 34257)
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
-- Data for TOC entry 122 (OID 34263)
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
INSERT INTO attr_min VALUES (5, 1);
INSERT INTO attr_min VALUES (5, 4);
INSERT INTO attr_min VALUES (5, 5);
INSERT INTO attr_min VALUES (5, 10);
INSERT INTO attr_min VALUES (5, 12);
INSERT INTO attr_min VALUES (6, 1);
INSERT INTO attr_min VALUES (6, 4);
INSERT INTO attr_min VALUES (6, 5);
INSERT INTO attr_min VALUES (6, 10);
INSERT INTO attr_min VALUES (6, 12);
INSERT INTO attr_min VALUES (10, 1);
INSERT INTO attr_min VALUES (10, 12);
INSERT INTO attr_min VALUES (10, 5);
INSERT INTO attr_min VALUES (11, 1);
INSERT INTO attr_min VALUES (11, 12);
INSERT INTO attr_min VALUES (11, 5);
INSERT INTO attr_min VALUES (12, 1);
INSERT INTO attr_min VALUES (12, 12);
INSERT INTO attr_min VALUES (12, 5);
INSERT INTO attr_min VALUES (13, 1);
INSERT INTO attr_min VALUES (13, 9);
INSERT INTO attr_min VALUES (7, 1);
INSERT INTO attr_min VALUES (7, 8);
INSERT INTO attr_min VALUES (7, 5);
INSERT INTO attr_min VALUES (7, 9);
INSERT INTO attr_min VALUES (7, 10);
INSERT INTO attr_min VALUES (13, 5);
INSERT INTO attr_min VALUES (5, 11);
INSERT INTO attr_min VALUES (6, 11);
INSERT INTO attr_min VALUES (1, 15);
INSERT INTO attr_min VALUES (9, 15);
INSERT INTO attr_min VALUES (15, 1);
INSERT INTO attr_min VALUES (15, 9);


--
-- Data for TOC entry 123 (OID 34265)
-- Name: fiche; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO fiche VALUES (1, 1);
INSERT INTO fiche VALUES (2, 1);
INSERT INTO fiche VALUES (3, 1);
INSERT INTO fiche VALUES (4, 1);
INSERT INTO fiche VALUES (5, 2);
INSERT INTO fiche VALUES (6, 2);
INSERT INTO fiche VALUES (7, 2);
INSERT INTO fiche VALUES (8, 3);
INSERT INTO fiche VALUES (9, 3);
INSERT INTO fiche VALUES (10, 3);
INSERT INTO fiche VALUES (11, 4);
INSERT INTO fiche VALUES (12, 4);
INSERT INTO fiche VALUES (13, 4);
INSERT INTO fiche VALUES (14, 5);
INSERT INTO fiche VALUES (15, 5);
INSERT INTO fiche VALUES (16, 5);
INSERT INTO fiche VALUES (17, 5);
INSERT INTO fiche VALUES (18, 6);
INSERT INTO fiche VALUES (19, 6);
INSERT INTO fiche VALUES (20, 6);


--
-- Data for TOC entry 124 (OID 34268)
-- Name: jnt_fic_att_value; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jnt_fic_att_value VALUES (1, 1, 5);
INSERT INTO jnt_fic_att_value VALUES (2, 1, 1);
INSERT INTO jnt_fic_att_value VALUES (3, 1, 2);
INSERT INTO jnt_fic_att_value VALUES (4, 1, 6);
INSERT INTO jnt_fic_att_value VALUES (5, 1, 7);
INSERT INTO jnt_fic_att_value VALUES (6, 1, 19);
INSERT INTO jnt_fic_att_value VALUES (7, 2, 5);
INSERT INTO jnt_fic_att_value VALUES (8, 2, 1);
INSERT INTO jnt_fic_att_value VALUES (9, 2, 2);
INSERT INTO jnt_fic_att_value VALUES (10, 2, 6);
INSERT INTO jnt_fic_att_value VALUES (11, 2, 7);
INSERT INTO jnt_fic_att_value VALUES (12, 2, 19);
INSERT INTO jnt_fic_att_value VALUES (13, 3, 5);
INSERT INTO jnt_fic_att_value VALUES (14, 3, 1);
INSERT INTO jnt_fic_att_value VALUES (15, 3, 2);
INSERT INTO jnt_fic_att_value VALUES (16, 3, 6);
INSERT INTO jnt_fic_att_value VALUES (17, 3, 7);
INSERT INTO jnt_fic_att_value VALUES (18, 3, 19);
INSERT INTO jnt_fic_att_value VALUES (19, 4, 5);
INSERT INTO jnt_fic_att_value VALUES (20, 4, 1);
INSERT INTO jnt_fic_att_value VALUES (21, 4, 2);
INSERT INTO jnt_fic_att_value VALUES (22, 4, 6);
INSERT INTO jnt_fic_att_value VALUES (23, 4, 7);
INSERT INTO jnt_fic_att_value VALUES (24, 4, 19);
INSERT INTO jnt_fic_att_value VALUES (25, 5, 5);
INSERT INTO jnt_fic_att_value VALUES (26, 5, 1);
INSERT INTO jnt_fic_att_value VALUES (27, 5, 12);
INSERT INTO jnt_fic_att_value VALUES (28, 5, 13);
INSERT INTO jnt_fic_att_value VALUES (29, 5, 14);
INSERT INTO jnt_fic_att_value VALUES (30, 5, 15);
INSERT INTO jnt_fic_att_value VALUES (31, 5, 16);
INSERT INTO jnt_fic_att_value VALUES (32, 5, 17);
INSERT INTO jnt_fic_att_value VALUES (33, 5, 18);
INSERT INTO jnt_fic_att_value VALUES (34, 6, 5);
INSERT INTO jnt_fic_att_value VALUES (35, 6, 1);
INSERT INTO jnt_fic_att_value VALUES (36, 6, 12);
INSERT INTO jnt_fic_att_value VALUES (37, 6, 13);
INSERT INTO jnt_fic_att_value VALUES (38, 6, 14);
INSERT INTO jnt_fic_att_value VALUES (39, 6, 15);
INSERT INTO jnt_fic_att_value VALUES (40, 6, 16);
INSERT INTO jnt_fic_att_value VALUES (41, 6, 17);
INSERT INTO jnt_fic_att_value VALUES (42, 6, 18);
INSERT INTO jnt_fic_att_value VALUES (43, 7, 5);
INSERT INTO jnt_fic_att_value VALUES (44, 7, 1);
INSERT INTO jnt_fic_att_value VALUES (45, 7, 12);
INSERT INTO jnt_fic_att_value VALUES (46, 7, 13);
INSERT INTO jnt_fic_att_value VALUES (47, 7, 14);
INSERT INTO jnt_fic_att_value VALUES (48, 7, 15);
INSERT INTO jnt_fic_att_value VALUES (49, 7, 16);
INSERT INTO jnt_fic_att_value VALUES (50, 7, 17);
INSERT INTO jnt_fic_att_value VALUES (51, 7, 18);
INSERT INTO jnt_fic_att_value VALUES (52, 8, 5);
INSERT INTO jnt_fic_att_value VALUES (53, 8, 1);
INSERT INTO jnt_fic_att_value VALUES (54, 8, 3);
INSERT INTO jnt_fic_att_value VALUES (55, 8, 4);
INSERT INTO jnt_fic_att_value VALUES (56, 8, 12);
INSERT INTO jnt_fic_att_value VALUES (57, 8, 13);
INSERT INTO jnt_fic_att_value VALUES (58, 8, 14);
INSERT INTO jnt_fic_att_value VALUES (59, 8, 15);
INSERT INTO jnt_fic_att_value VALUES (60, 8, 16);
INSERT INTO jnt_fic_att_value VALUES (61, 8, 17);
INSERT INTO jnt_fic_att_value VALUES (62, 8, 18);
INSERT INTO jnt_fic_att_value VALUES (63, 9, 5);
INSERT INTO jnt_fic_att_value VALUES (64, 9, 1);
INSERT INTO jnt_fic_att_value VALUES (65, 9, 3);
INSERT INTO jnt_fic_att_value VALUES (66, 9, 4);
INSERT INTO jnt_fic_att_value VALUES (67, 9, 12);
INSERT INTO jnt_fic_att_value VALUES (68, 9, 13);
INSERT INTO jnt_fic_att_value VALUES (69, 9, 14);
INSERT INTO jnt_fic_att_value VALUES (70, 9, 15);
INSERT INTO jnt_fic_att_value VALUES (71, 9, 16);
INSERT INTO jnt_fic_att_value VALUES (72, 9, 17);
INSERT INTO jnt_fic_att_value VALUES (73, 9, 18);
INSERT INTO jnt_fic_att_value VALUES (74, 10, 5);
INSERT INTO jnt_fic_att_value VALUES (75, 10, 1);
INSERT INTO jnt_fic_att_value VALUES (76, 10, 3);
INSERT INTO jnt_fic_att_value VALUES (77, 10, 4);
INSERT INTO jnt_fic_att_value VALUES (78, 10, 12);
INSERT INTO jnt_fic_att_value VALUES (79, 10, 13);
INSERT INTO jnt_fic_att_value VALUES (80, 10, 14);
INSERT INTO jnt_fic_att_value VALUES (81, 10, 15);
INSERT INTO jnt_fic_att_value VALUES (82, 10, 16);
INSERT INTO jnt_fic_att_value VALUES (83, 10, 17);
INSERT INTO jnt_fic_att_value VALUES (84, 10, 18);
INSERT INTO jnt_fic_att_value VALUES (85, 11, 5);
INSERT INTO jnt_fic_att_value VALUES (86, 11, 1);
INSERT INTO jnt_fic_att_value VALUES (87, 11, 12);
INSERT INTO jnt_fic_att_value VALUES (88, 11, 13);
INSERT INTO jnt_fic_att_value VALUES (89, 11, 14);
INSERT INTO jnt_fic_att_value VALUES (90, 11, 15);
INSERT INTO jnt_fic_att_value VALUES (91, 11, 16);
INSERT INTO jnt_fic_att_value VALUES (92, 11, 17);
INSERT INTO jnt_fic_att_value VALUES (93, 11, 18);
INSERT INTO jnt_fic_att_value VALUES (94, 12, 5);
INSERT INTO jnt_fic_att_value VALUES (95, 12, 1);
INSERT INTO jnt_fic_att_value VALUES (96, 12, 12);
INSERT INTO jnt_fic_att_value VALUES (97, 12, 13);
INSERT INTO jnt_fic_att_value VALUES (98, 12, 14);
INSERT INTO jnt_fic_att_value VALUES (99, 12, 15);
INSERT INTO jnt_fic_att_value VALUES (100, 12, 16);
INSERT INTO jnt_fic_att_value VALUES (101, 12, 17);
INSERT INTO jnt_fic_att_value VALUES (102, 12, 18);
INSERT INTO jnt_fic_att_value VALUES (103, 13, 5);
INSERT INTO jnt_fic_att_value VALUES (104, 13, 1);
INSERT INTO jnt_fic_att_value VALUES (105, 13, 12);
INSERT INTO jnt_fic_att_value VALUES (106, 13, 13);
INSERT INTO jnt_fic_att_value VALUES (107, 13, 14);
INSERT INTO jnt_fic_att_value VALUES (108, 13, 15);
INSERT INTO jnt_fic_att_value VALUES (109, 13, 16);
INSERT INTO jnt_fic_att_value VALUES (110, 13, 17);
INSERT INTO jnt_fic_att_value VALUES (111, 13, 18);
INSERT INTO jnt_fic_att_value VALUES (112, 14, 5);
INSERT INTO jnt_fic_att_value VALUES (113, 14, 1);
INSERT INTO jnt_fic_att_value VALUES (114, 14, 2);
INSERT INTO jnt_fic_att_value VALUES (115, 14, 7);
INSERT INTO jnt_fic_att_value VALUES (116, 15, 5);
INSERT INTO jnt_fic_att_value VALUES (117, 15, 1);
INSERT INTO jnt_fic_att_value VALUES (118, 15, 2);
INSERT INTO jnt_fic_att_value VALUES (119, 15, 7);
INSERT INTO jnt_fic_att_value VALUES (120, 16, 5);
INSERT INTO jnt_fic_att_value VALUES (121, 16, 1);
INSERT INTO jnt_fic_att_value VALUES (122, 16, 2);
INSERT INTO jnt_fic_att_value VALUES (123, 16, 7);
INSERT INTO jnt_fic_att_value VALUES (124, 17, 5);
INSERT INTO jnt_fic_att_value VALUES (125, 17, 1);
INSERT INTO jnt_fic_att_value VALUES (126, 17, 2);
INSERT INTO jnt_fic_att_value VALUES (127, 17, 7);
INSERT INTO jnt_fic_att_value VALUES (128, 18, 5);
INSERT INTO jnt_fic_att_value VALUES (129, 18, 1);
INSERT INTO jnt_fic_att_value VALUES (130, 18, 2);
INSERT INTO jnt_fic_att_value VALUES (131, 18, 6);
INSERT INTO jnt_fic_att_value VALUES (132, 18, 7);
INSERT INTO jnt_fic_att_value VALUES (133, 18, 19);
INSERT INTO jnt_fic_att_value VALUES (134, 19, 5);
INSERT INTO jnt_fic_att_value VALUES (135, 19, 1);
INSERT INTO jnt_fic_att_value VALUES (136, 19, 2);
INSERT INTO jnt_fic_att_value VALUES (137, 19, 6);
INSERT INTO jnt_fic_att_value VALUES (138, 19, 7);
INSERT INTO jnt_fic_att_value VALUES (139, 19, 19);
INSERT INTO jnt_fic_att_value VALUES (140, 20, 5);
INSERT INTO jnt_fic_att_value VALUES (141, 20, 1);
INSERT INTO jnt_fic_att_value VALUES (142, 20, 2);
INSERT INTO jnt_fic_att_value VALUES (143, 20, 6);
INSERT INTO jnt_fic_att_value VALUES (144, 20, 7);
INSERT INTO jnt_fic_att_value VALUES (145, 20, 19);


--
-- Data for TOC entry 125 (OID 34271)
-- Name: attr_value; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO attr_value VALUES (25, '4000001');
INSERT INTO attr_value VALUES (26, 'Client 1');
INSERT INTO attr_value VALUES (27, '');
INSERT INTO attr_value VALUES (28, '');
INSERT INTO attr_value VALUES (29, '');
INSERT INTO attr_value VALUES (30, '');
INSERT INTO attr_value VALUES (31, '');
INSERT INTO attr_value VALUES (32, '');
INSERT INTO attr_value VALUES (33, '');
INSERT INTO attr_value VALUES (34, '4000002');
INSERT INTO attr_value VALUES (35, 'Client 2');
INSERT INTO attr_value VALUES (36, '');
INSERT INTO attr_value VALUES (37, '');
INSERT INTO attr_value VALUES (38, '');
INSERT INTO attr_value VALUES (39, '');
INSERT INTO attr_value VALUES (40, '');
INSERT INTO attr_value VALUES (41, '');
INSERT INTO attr_value VALUES (42, '');
INSERT INTO attr_value VALUES (43, '4000003');
INSERT INTO attr_value VALUES (44, 'Client 3');
INSERT INTO attr_value VALUES (45, '');
INSERT INTO attr_value VALUES (46, '');
INSERT INTO attr_value VALUES (47, '');
INSERT INTO attr_value VALUES (48, '');
INSERT INTO attr_value VALUES (49, '');
INSERT INTO attr_value VALUES (50, '');
INSERT INTO attr_value VALUES (51, '');
INSERT INTO attr_value VALUES (2, 'Marchandise A');
INSERT INTO attr_value VALUES (3, '1');
INSERT INTO attr_value VALUES (1, '6040001');
INSERT INTO attr_value VALUES (4, '');
INSERT INTO attr_value VALUES (5, '');
INSERT INTO attr_value VALUES (6, '');
INSERT INTO attr_value VALUES (8, 'Marchandise B');
INSERT INTO attr_value VALUES (9, '3');
INSERT INTO attr_value VALUES (7, '6040002');
INSERT INTO attr_value VALUES (10, '');
INSERT INTO attr_value VALUES (11, '');
INSERT INTO attr_value VALUES (12, '');
INSERT INTO attr_value VALUES (14, 'Marchandise C');
INSERT INTO attr_value VALUES (15, '1');
INSERT INTO attr_value VALUES (13, '6040003');
INSERT INTO attr_value VALUES (16, '');
INSERT INTO attr_value VALUES (17, '');
INSERT INTO attr_value VALUES (18, '');
INSERT INTO attr_value VALUES (20, 'Marchandise D');
INSERT INTO attr_value VALUES (21, '3');
INSERT INTO attr_value VALUES (19, '6040004');
INSERT INTO attr_value VALUES (22, '');
INSERT INTO attr_value VALUES (23, '');
INSERT INTO attr_value VALUES (24, '');
INSERT INTO attr_value VALUES (53, 'Caisse');
INSERT INTO attr_value VALUES (54, '');
INSERT INTO attr_value VALUES (55, '');
INSERT INTO attr_value VALUES (52, '57');
INSERT INTO attr_value VALUES (56, '');
INSERT INTO attr_value VALUES (57, '');
INSERT INTO attr_value VALUES (58, '');
INSERT INTO attr_value VALUES (59, '');
INSERT INTO attr_value VALUES (60, '');
INSERT INTO attr_value VALUES (61, '');
INSERT INTO attr_value VALUES (62, '');
INSERT INTO attr_value VALUES (63, '55000002');
INSERT INTO attr_value VALUES (64, 'Banque 1');
INSERT INTO attr_value VALUES (65, '');
INSERT INTO attr_value VALUES (66, '');
INSERT INTO attr_value VALUES (67, '');
INSERT INTO attr_value VALUES (68, '');
INSERT INTO attr_value VALUES (69, '');
INSERT INTO attr_value VALUES (70, '');
INSERT INTO attr_value VALUES (71, '');
INSERT INTO attr_value VALUES (72, '');
INSERT INTO attr_value VALUES (73, '');
INSERT INTO attr_value VALUES (74, '55000003');
INSERT INTO attr_value VALUES (75, 'Banque 2');
INSERT INTO attr_value VALUES (76, '');
INSERT INTO attr_value VALUES (77, '');
INSERT INTO attr_value VALUES (78, '');
INSERT INTO attr_value VALUES (79, '');
INSERT INTO attr_value VALUES (80, '');
INSERT INTO attr_value VALUES (81, '');
INSERT INTO attr_value VALUES (82, '');
INSERT INTO attr_value VALUES (83, '');
INSERT INTO attr_value VALUES (84, '');
INSERT INTO attr_value VALUES (85, '4400001');
INSERT INTO attr_value VALUES (86, 'Fournisseur 1');
INSERT INTO attr_value VALUES (87, '');
INSERT INTO attr_value VALUES (88, '');
INSERT INTO attr_value VALUES (89, '');
INSERT INTO attr_value VALUES (90, '');
INSERT INTO attr_value VALUES (91, '');
INSERT INTO attr_value VALUES (92, '');
INSERT INTO attr_value VALUES (93, '');
INSERT INTO attr_value VALUES (94, '4400002');
INSERT INTO attr_value VALUES (95, 'Fournisseur 2');
INSERT INTO attr_value VALUES (96, '');
INSERT INTO attr_value VALUES (97, '');
INSERT INTO attr_value VALUES (98, '');
INSERT INTO attr_value VALUES (99, '');
INSERT INTO attr_value VALUES (100, '');
INSERT INTO attr_value VALUES (101, '');
INSERT INTO attr_value VALUES (102, '');
INSERT INTO attr_value VALUES (103, '4400003');
INSERT INTO attr_value VALUES (104, 'Fournisseur 4');
INSERT INTO attr_value VALUES (105, '');
INSERT INTO attr_value VALUES (106, '');
INSERT INTO attr_value VALUES (107, '');
INSERT INTO attr_value VALUES (108, '');
INSERT INTO attr_value VALUES (109, '');
INSERT INTO attr_value VALUES (110, '');
INSERT INTO attr_value VALUES (111, '');
INSERT INTO attr_value VALUES (112, '610001');
INSERT INTO attr_value VALUES (113, 'Electricité');
INSERT INTO attr_value VALUES (114, '1');
INSERT INTO attr_value VALUES (115, '');
INSERT INTO attr_value VALUES (117, 'Loyer');
INSERT INTO attr_value VALUES (118, '3');
INSERT INTO attr_value VALUES (116, '610002');
INSERT INTO attr_value VALUES (119, '');
INSERT INTO attr_value VALUES (121, 'Assurance');
INSERT INTO attr_value VALUES (122, '3');
INSERT INTO attr_value VALUES (120, '610003');
INSERT INTO attr_value VALUES (123, '');
INSERT INTO attr_value VALUES (124, '610004');
INSERT INTO attr_value VALUES (125, 'Matériel bureau');
INSERT INTO attr_value VALUES (126, '1');
INSERT INTO attr_value VALUES (127, '');
INSERT INTO attr_value VALUES (134, '7000002');
INSERT INTO attr_value VALUES (135, 'Marchandise A');
INSERT INTO attr_value VALUES (136, '');
INSERT INTO attr_value VALUES (137, '200');
INSERT INTO attr_value VALUES (138, '');
INSERT INTO attr_value VALUES (139, '');
INSERT INTO attr_value VALUES (129, 'Prestation');
INSERT INTO attr_value VALUES (130, '1');
INSERT INTO attr_value VALUES (128, '7000001');
INSERT INTO attr_value VALUES (131, '15');
INSERT INTO attr_value VALUES (132, '');
INSERT INTO attr_value VALUES (133, '');
INSERT INTO attr_value VALUES (140, '7000003');
INSERT INTO attr_value VALUES (141, 'Déplacement');
INSERT INTO attr_value VALUES (142, '');
INSERT INTO attr_value VALUES (143, '50');
INSERT INTO attr_value VALUES (144, '');
INSERT INTO attr_value VALUES (145, '');


--
-- Data for TOC entry 126 (OID 34276)
-- Name: jnt_fic_attr; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO jnt_fic_attr VALUES (1, 5);
INSERT INTO jnt_fic_attr VALUES (1, 1);
INSERT INTO jnt_fic_attr VALUES (1, 2);
INSERT INTO jnt_fic_attr VALUES (1, 6);
INSERT INTO jnt_fic_attr VALUES (1, 7);
INSERT INTO jnt_fic_attr VALUES (1, 19);
INSERT INTO jnt_fic_attr VALUES (2, 5);
INSERT INTO jnt_fic_attr VALUES (2, 1);
INSERT INTO jnt_fic_attr VALUES (2, 12);
INSERT INTO jnt_fic_attr VALUES (2, 13);
INSERT INTO jnt_fic_attr VALUES (2, 14);
INSERT INTO jnt_fic_attr VALUES (2, 15);
INSERT INTO jnt_fic_attr VALUES (2, 16);
INSERT INTO jnt_fic_attr VALUES (2, 17);
INSERT INTO jnt_fic_attr VALUES (2, 18);
INSERT INTO jnt_fic_attr VALUES (3, 5);
INSERT INTO jnt_fic_attr VALUES (3, 1);
INSERT INTO jnt_fic_attr VALUES (3, 3);
INSERT INTO jnt_fic_attr VALUES (3, 4);
INSERT INTO jnt_fic_attr VALUES (3, 12);
INSERT INTO jnt_fic_attr VALUES (3, 13);
INSERT INTO jnt_fic_attr VALUES (3, 14);
INSERT INTO jnt_fic_attr VALUES (3, 15);
INSERT INTO jnt_fic_attr VALUES (3, 16);
INSERT INTO jnt_fic_attr VALUES (3, 17);
INSERT INTO jnt_fic_attr VALUES (3, 18);
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
INSERT INTO jnt_fic_attr VALUES (6, 5);
INSERT INTO jnt_fic_attr VALUES (6, 1);
INSERT INTO jnt_fic_attr VALUES (6, 2);
INSERT INTO jnt_fic_attr VALUES (6, 6);
INSERT INTO jnt_fic_attr VALUES (6, 7);
INSERT INTO jnt_fic_attr VALUES (6, 19);


--
-- Data for TOC entry 127 (OID 34286)
-- Name: jrn_rapt; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 128 (OID 34289)
-- Name: jrn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- Data for TOC entry 129 (OID 34297)
-- Name: stock_goods; Type: TABLE DATA; Schema: public; Owner: phpcompta
--



--
-- TOC entry 89 (OID 35343)
-- Name: x_act; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE UNIQUE INDEX x_act ON "action" USING btree (ac_description);


--
-- TOC entry 87 (OID 35344)
-- Name: x_usr_jrn; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE UNIQUE INDEX x_usr_jrn ON user_sec_jrn USING btree (uj_login, uj_jrn_id);


--
-- TOC entry 99 (OID 35345)
-- Name: x_jrn_jr_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE UNIQUE INDEX x_jrn_jr_id ON jrn USING btree (jr_id);


--
-- TOC entry 101 (OID 35346)
-- Name: fk_stock_goods_j_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_stock_goods_j_id ON stock_goods USING btree (j_id);


--
-- TOC entry 100 (OID 35347)
-- Name: fk_stock_goods_f_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_stock_goods_f_id ON stock_goods USING btree (f_id);


--
-- TOC entry 74 (OID 35348)
-- Name: tmp_pcmn_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY tmp_pcmn
    ADD CONSTRAINT tmp_pcmn_pkey PRIMARY KEY (pcm_val);


--
-- TOC entry 75 (OID 35350)
-- Name: parm_money_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY parm_money
    ADD CONSTRAINT parm_money_pkey PRIMARY KEY (pm_code);


--
-- TOC entry 77 (OID 35352)
-- Name: parm_periode_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY parm_periode
    ADD CONSTRAINT parm_periode_pkey PRIMARY KEY (p_id);


--
-- TOC entry 76 (OID 35354)
-- Name: parm_periode_p_start_key; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY parm_periode
    ADD CONSTRAINT parm_periode_p_start_key UNIQUE (p_start);


--
-- TOC entry 78 (OID 35356)
-- Name: jrn_type_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_type
    ADD CONSTRAINT jrn_type_pkey PRIMARY KEY (jrn_type_id);


--
-- TOC entry 80 (OID 35358)
-- Name: jrn_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT jrn_def_pkey PRIMARY KEY (jrn_def_id);


--
-- TOC entry 79 (OID 35360)
-- Name: jrn_def_jrn_def_name_key; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT jrn_def_jrn_def_name_key UNIQUE (jrn_def_name);


--
-- TOC entry 130 (OID 35362)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT "$1" FOREIGN KEY (jrn_def_type) REFERENCES jrn_type(jrn_type_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 81 (OID 35366)
-- Name: jrnx_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrnx
    ADD CONSTRAINT jrnx_pkey PRIMARY KEY (j_id);


--
-- TOC entry 131 (OID 35368)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrnx
    ADD CONSTRAINT "$1" FOREIGN KEY (j_poste) REFERENCES tmp_pcmn(pcm_val) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 132 (OID 35372)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrnx
    ADD CONSTRAINT "$2" FOREIGN KEY (j_jrn_def) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 82 (OID 35376)
-- Name: user_pref_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_pref
    ADD CONSTRAINT user_pref_pkey PRIMARY KEY (pref_user);


--
-- TOC entry 83 (OID 35378)
-- Name: formdef_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY formdef
    ADD CONSTRAINT formdef_pkey PRIMARY KEY (fr_id);


--
-- TOC entry 84 (OID 35380)
-- Name: form_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY form
    ADD CONSTRAINT form_pkey PRIMARY KEY (fo_id);


--
-- TOC entry 133 (OID 35382)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY form
    ADD CONSTRAINT "$1" FOREIGN KEY (fo_fr_id) REFERENCES formdef(fr_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 85 (OID 35386)
-- Name: centralized_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY centralized
    ADD CONSTRAINT centralized_pkey PRIMARY KEY (c_id);


--
-- TOC entry 134 (OID 35388)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY centralized
    ADD CONSTRAINT "$1" FOREIGN KEY (c_jrn_def) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 135 (OID 35392)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY centralized
    ADD CONSTRAINT "$2" FOREIGN KEY (c_poste) REFERENCES tmp_pcmn(pcm_val) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 86 (OID 35396)
-- Name: user_sec_jrn_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_jrn
    ADD CONSTRAINT user_sec_jrn_pkey PRIMARY KEY (uj_id);


--
-- TOC entry 136 (OID 35398)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_jrn
    ADD CONSTRAINT "$1" FOREIGN KEY (uj_jrn_id) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 88 (OID 35402)
-- Name: action_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY "action"
    ADD CONSTRAINT action_pkey PRIMARY KEY (ac_id);


--
-- TOC entry 90 (OID 35404)
-- Name: user_sec_act_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_act
    ADD CONSTRAINT user_sec_act_pkey PRIMARY KEY (ua_id);


--
-- TOC entry 137 (OID 35406)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_act
    ADD CONSTRAINT "$1" FOREIGN KEY (ua_act_id) REFERENCES "action"(ac_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 91 (OID 35410)
-- Name: jrn_action_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_action
    ADD CONSTRAINT jrn_action_pkey PRIMARY KEY (ja_id);


--
-- TOC entry 138 (OID 35412)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_action
    ADD CONSTRAINT "$1" FOREIGN KEY (ja_jrn_type) REFERENCES jrn_type(jrn_type_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 92 (OID 35416)
-- Name: fiche_def_ref_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche_def_ref
    ADD CONSTRAINT fiche_def_ref_pkey PRIMARY KEY (frd_id);


--
-- TOC entry 93 (OID 35418)
-- Name: fiche_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche_def
    ADD CONSTRAINT fiche_def_pkey PRIMARY KEY (fd_id);


--
-- TOC entry 139 (OID 35420)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche_def
    ADD CONSTRAINT "$1" FOREIGN KEY (frd_id) REFERENCES fiche_def_ref(frd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 94 (OID 35424)
-- Name: attr_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_def
    ADD CONSTRAINT attr_def_pkey PRIMARY KEY (ad_id);


--
-- TOC entry 140 (OID 35426)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_min
    ADD CONSTRAINT "$1" FOREIGN KEY (frd_id) REFERENCES fiche_def_ref(frd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 141 (OID 35430)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_min
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 95 (OID 35434)
-- Name: fiche_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche
    ADD CONSTRAINT fiche_pkey PRIMARY KEY (f_id);


--
-- TOC entry 142 (OID 35436)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche
    ADD CONSTRAINT "$1" FOREIGN KEY (fd_id) REFERENCES fiche_def(fd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 96 (OID 35440)
-- Name: jnt_fic_att_value_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT jnt_fic_att_value_pkey PRIMARY KEY (jft_id);


--
-- TOC entry 143 (OID 35442)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT "$1" FOREIGN KEY (f_id) REFERENCES fiche(f_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 144 (OID 35446)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 145 (OID 35450)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_value
    ADD CONSTRAINT "$1" FOREIGN KEY (jft_id) REFERENCES jnt_fic_att_value(jft_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 146 (OID 35454)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_attr
    ADD CONSTRAINT "$1" FOREIGN KEY (fd_id) REFERENCES fiche_def(fd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 147 (OID 35458)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_attr
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 97 (OID 35462)
-- Name: jrn_rapt_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_rapt
    ADD CONSTRAINT jrn_rapt_pkey PRIMARY KEY (jra_id);


--
-- TOC entry 148 (OID 35464)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn
    ADD CONSTRAINT "$1" FOREIGN KEY (jr_def_id) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 98 (OID 35468)
-- Name: jrn_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn
    ADD CONSTRAINT jrn_pkey PRIMARY KEY (jr_id, jr_def_id);


--
-- TOC entry 102 (OID 35470)
-- Name: stock_goods_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY stock_goods
    ADD CONSTRAINT stock_goods_pkey PRIMARY KEY (sg_id);


--
-- TOC entry 3 (OID 34109)
-- Name: s_periode; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_periode', 52, true);


--
-- TOC entry 5 (OID 34111)
-- Name: s_currency; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_currency', 1, true);


--
-- TOC entry 7 (OID 34124)
-- Name: s_jrn_def; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrn_def', 5, false);


--
-- TOC entry 9 (OID 34126)
-- Name: s_grpt; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_grpt', 1, false);


--
-- TOC entry 11 (OID 34128)
-- Name: s_jrn_op; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrn_op', 1, false);


--
-- TOC entry 13 (OID 34130)
-- Name: s_jrn; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrn', 1, false);


--
-- TOC entry 15 (OID 34146)
-- Name: s_jrnx; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrnx', 1, false);


--
-- TOC entry 17 (OID 34164)
-- Name: s_formdef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_formdef', 1, false);


--
-- TOC entry 19 (OID 34166)
-- Name: s_form; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_form', 1, false);


--
-- TOC entry 21 (OID 34180)
-- Name: s_isup; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_isup', 1, false);


--
-- TOC entry 23 (OID 34182)
-- Name: s_idef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_idef', 1, false);


--
-- TOC entry 25 (OID 34184)
-- Name: s_centralized; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_centralized', 1, false);


--
-- TOC entry 27 (OID 34193)
-- Name: s_user_jrn; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_user_jrn', 1, false);


--
-- TOC entry 29 (OID 34195)
-- Name: s_user_act; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_user_act', 1, false);


--
-- TOC entry 31 (OID 34214)
-- Name: s_jrnaction; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrnaction', 5, true);


--
-- TOC entry 33 (OID 34234)
-- Name: s_fiche; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_fiche', 20, true);


--
-- TOC entry 35 (OID 34236)
-- Name: s_fiche_def_ref; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_fiche_def_ref', 1, false);


--
-- TOC entry 37 (OID 34238)
-- Name: s_fdef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_fdef', 6, true);


--
-- TOC entry 39 (OID 34240)
-- Name: s_attr_def; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_attr_def', 1, false);


--
-- TOC entry 41 (OID 34242)
-- Name: s_jnt_fic_att_value; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jnt_fic_att_value', 145, true);


--
-- TOC entry 43 (OID 34282)
-- Name: s_stock_goods; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_stock_goods', 1, false);


--
-- TOC entry 45 (OID 34284)
-- Name: s_jrn_rapt; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrn_rapt', 1, false);


