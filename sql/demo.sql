--
-- PostgreSQL database dump
--

\connect - phpcompta

SET search_path = public, pg_catalog;

--
-- TOC entry 46 (OID 21791)
-- Name: tmp_pcmn; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE tmp_pcmn (
    pcm_val integer NOT NULL,
    pcm_lib text,
    pcm_val_parent integer DEFAULT 0,
    pcm_country character(2) DEFAULT 'BE' NOT NULL
);


--
-- TOC entry 47 (OID 21798)
-- Name: version; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE "version" (
    val integer
);


--
-- TOC entry 2 (OID 21800)
-- Name: s_periode; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_periode
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 4 (OID 21802)
-- Name: s_currency; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_currency
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 48 (OID 21804)
-- Name: parm_money; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE parm_money (
    pm_id integer DEFAULT nextval('s_currency'::text),
    pm_code character(3) NOT NULL,
    pm_rate double precision
);


--
-- TOC entry 49 (OID 21807)
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
-- TOC entry 6 (OID 21815)
-- Name: s_jrn_def; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_def
    START 5
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 8 (OID 21817)
-- Name: s_grpt; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_grpt
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 10 (OID 21819)
-- Name: s_jrn_op; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_op
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 12 (OID 21821)
-- Name: s_jrn; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 50 (OID 21823)
-- Name: jrn_type; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jrn_type (
    jrn_type_id character(3) NOT NULL,
    jrn_desc text
);


--
-- TOC entry 51 (OID 21828)
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
-- TOC entry 14 (OID 21837)
-- Name: s_jrnx; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrnx
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 52 (OID 21839)
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
-- TOC entry 53 (OID 21846)
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
-- TOC entry 54 (OID 21857)
-- Name: user_pref; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE user_pref (
    pref_user text NOT NULL,
    pref_periode integer NOT NULL
);


--
-- TOC entry 16 (OID 21862)
-- Name: s_formdef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_formdef
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 18 (OID 21864)
-- Name: s_form; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_form
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 55 (OID 21866)
-- Name: formdef; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE formdef (
    fr_id integer DEFAULT nextval('s_formdef'::text) NOT NULL,
    fr_label text
);


--
-- TOC entry 56 (OID 21872)
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
-- TOC entry 20 (OID 21878)
-- Name: s_isup; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_isup
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 22 (OID 21880)
-- Name: s_idef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_idef
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 57 (OID 21882)
-- Name: fichedef; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE fichedef (
    fd_id integer DEFAULT nextval('s_fdef'::text) NOT NULL,
    fd_label text NOT NULL,
    fd_class_base text
);


--
-- TOC entry 58 (OID 21888)
-- Name: isupp_def; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE isupp_def (
    isd_id integer DEFAULT nextval('s_idef'::text) NOT NULL,
    isd_label text,
    isd_fd_id integer,
    isd_form boolean
);


--
-- TOC entry 59 (OID 21894)
-- Name: isupp; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE isupp (
    is_id integer DEFAULT nextval('s_isup'::text) NOT NULL,
    is_f_id integer,
    is_isd_id integer,
    is_value text
);


--
-- TOC entry 24 (OID 21900)
-- Name: s_centralized; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_centralized
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 60 (OID 21902)
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
-- TOC entry 26 (OID 21909)
-- Name: s_user_jrn; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_user_jrn
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 28 (OID 21911)
-- Name: s_user_act; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_user_act
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 61 (OID 21913)
-- Name: user_sec_jrn; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE user_sec_jrn (
    uj_id integer DEFAULT nextval('s_user_jrn'::text) NOT NULL,
    uj_login text,
    uj_jrn_id integer,
    uj_priv text
);


--
-- TOC entry 62 (OID 21919)
-- Name: action; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE "action" (
    ac_id integer NOT NULL,
    ac_description text NOT NULL
);


--
-- TOC entry 63 (OID 21924)
-- Name: user_sec_act; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE user_sec_act (
    ua_id integer DEFAULT nextval('s_user_act'::text) NOT NULL,
    ua_login text,
    ua_act_id integer
);


--
-- TOC entry 30 (OID 21930)
-- Name: s_jrnaction; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrnaction
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 64 (OID 21932)
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
-- TOC entry 65 (OID 21939)
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
-- TOC entry 32 (OID 21945)
-- Name: s_fiche; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fiche
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 34 (OID 21947)
-- Name: s_fiche_def_ref; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fiche_def_ref
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 36 (OID 21949)
-- Name: s_fdef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fdef
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 38 (OID 21951)
-- Name: s_attr_def; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_attr_def
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 40 (OID 21953)
-- Name: s_jnt_fic_att_value; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jnt_fic_att_value
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 66 (OID 21955)
-- Name: fiche_def_ref; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE fiche_def_ref (
    frd_id integer DEFAULT nextval('s_fiche_def_ref'::text) NOT NULL,
    frd_text text,
    frd_class_base integer
);


--
-- TOC entry 67 (OID 21961)
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
-- TOC entry 68 (OID 21968)
-- Name: attr_def; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_def (
    ad_id integer DEFAULT nextval('s_attr_def'::text) NOT NULL,
    ad_text text
);


--
-- TOC entry 69 (OID 21974)
-- Name: attr_min; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_min (
    frd_id integer,
    ad_id integer
);


--
-- TOC entry 70 (OID 21976)
-- Name: fiche; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE fiche (
    f_id integer DEFAULT nextval('s_fiche'::text) NOT NULL,
    fd_id integer
);


--
-- TOC entry 71 (OID 21979)
-- Name: jnt_fic_att_value; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jnt_fic_att_value (
    jft_id integer DEFAULT nextval('s_jnt_fic_att_value'::text) NOT NULL,
    f_id integer,
    ad_id integer
);


--
-- TOC entry 72 (OID 21982)
-- Name: attr_value; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_value (
    jft_id integer,
    av_text text
);


--
-- TOC entry 73 (OID 21987)
-- Name: jnt_fic_attr; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jnt_fic_attr (
    fd_id integer,
    ad_id integer
);


--
-- TOC entry 42 (OID 21989)
-- Name: s_stock_goods; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_stock_goods
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 74 (OID 21991)
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
-- TOC entry 75 (OID 21996)
-- Name: test; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE test (
    col1 text,
    col2 text
);


--
-- TOC entry 44 (OID 22001)
-- Name: s_jrn_rapt; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_rapt
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 76 (OID 22003)
-- Name: jrn_rapt; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jrn_rapt (
    jra_id integer DEFAULT nextval('s_jrn_rapt'::text) NOT NULL,
    jr_id integer NOT NULL,
    jra_concerned integer NOT NULL
);


--
-- TOC entry 77 (OID 22008)
-- Name: vw_fiche_attr; Type: VIEW; Schema: public; Owner: phpcompta
--

CREATE VIEW vw_fiche_attr AS
    SELECT a.f_id, fd_id, a.av_text AS vw_name, b.av_text AS vw_sell, c.av_text AS vw_buy, d.av_text AS tva_code, tva_id, tva_rate, tva_label, e.av_text AS vw_addr, f.av_text AS vw_cp, frd_id FROM ((((((((SELECT f_id, fd_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 1)) a LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 6)) b ON ((a.f_id = b.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 7)) c ON ((a.f_id = c.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 2)) d ON ((a.f_id = d.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 14)) e ON ((a.f_id = e.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 15)) f ON ((a.f_id = f.f_id))) LEFT JOIN tva_rate ON ((d.av_text = (tva_rate.tva_id)::text))) JOIN fiche_def USING (fd_id));


--
-- Data for TOC entry 125 (OID 21791)
-- Name: tmp_pcmn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) FROM stdin;
10	Capital 	1	BE
100	 Capital souscrit	10	BE
11	Prime d'émission 	1	BE
12	Plus Value de réévaluation 	1	BE
13	Réserve 	1	BE
130	Réserve légale	13	BE
131	Réserve indisponible	13	BE
1310	Réserve pour actions propres	131	BE
1311	 Autres réserves indisponibles	131	BE
132	 Réserves immunisées	13	BE
133	Réserves disponibles	13	BE
14	Bénéfice ou perte reportée	1	BE
140	Bénéfice reporté	14	BE
141	Perte reportée	14	BE
15	Subside en capital	1	BE
16	Provisions pour risques et charges	1	BE
160	Provisions pour pensions et obligations similaires	16	BE
161	Provisions pour charges fiscales	16	BE
162	Provisions pour grosses réparation et gros entretien	16	BE
17	 Dettes à plus d'un an	1	BE
170	Emprunts subordonnés	17	BE
1700	convertibles	170	BE
1701	non convertibles	170	BE
171	Emprunts subordonnés	17	BE
1710	convertibles	170	BE
1711	non convertibles	170	BE
172	 Dettes de locations financement	17	BE
173	 Etablissement de crédit	17	BE
1730	Dettes en comptes	173	BE
1731	Promesses	173	BE
1732	Crédits d'acceptation	173	BE
174	Autres emprunts	17	BE
175	Dettes commerciales	17	BE
1750	Fournisseurs	175	BE
1751	Effets à payer	175	BE
176	Acomptes reçus sur commandes	17	BE
178	Cautionnement reçus en numéraires	17	BE
179	Dettes diverses	17	BE
20	Frais d'établissement	2	BE
200	Frais de constitution et d'augmentation de capital	20	BE
201	 Frais d'émission d'emprunts et primes de remboursement	20	BE
202	Autres frais d'établissement	20	BE
204	Frais de restructuration	20	BE
21	Immobilisations incorporelles	2	BE
210	Frais de recherche et de développement	21	BE
211	Concessions, brevet, licence savoir faire, marque et droit similaires	21	BE
212	Goodwill	21	BE
213	Acomptes versés	21	BE
22	Terrains et construction	2	BE
220	Terrains	22	BE
221	Construction	22	BE
222	Terrains bâtis	22	BE
223	Autres droits réels sur des immeubles	22	BE
23	 Installations, machines et outillages	2	BE
24	Mobilier et Matériel roulant	2	BE
25	Immobilisations détenus en location-financement et droits similaires	2	BE
250	Terrains	25	BE
251	Construction	25	BE
252	Terrains bâtis	25	BE
253	Mobilier et matériels roulants	25	BE
26	Autres immobilisations corporelles	2	BE
27	Immobilisations corporelles en cours et acomptes versés	2	BE
28	Immobilisations financières	2	BE
280	Participation dans des entreprises liées	28	BE
2800	Valeur d'acquisition	280	BE
2801	Montants non-appelés(-)	280	BE
2808	Plus-values actées	280	BE
2809	Réductions de valeurs actées	280	BE
281	Créance sur  des entreprises liées	28	BE
2810	Créance en compte	281	BE
2811	Effets à recevoir	281	BE
2812	Titre à reveny fixe	281	BE
2817	Créances douteuses	281	BE
2819	Réduction de valeurs actées	281	BE
282	Participations dans des entreprises avec lesquelles il existe un lien de participation	28	BE
2820	Valeur d'acquisition	282	BE
2821	Montants non-appelés(-)	282	BE
2828	Plus-values actées	282	BE
2829	Réductions de valeurs actées	282	BE
283	Créances sur des entreprises avec lesquelles existe un lien de participation	28	BE
2830	Créance en compte	283	BE
2831	Effets à recevoir	283	BE
2832	Titre à revenu fixe	283	BE
2837	Créances douteuses	283	BE
2839	Réduction de valeurs actées	283	BE
284	Autres actions et parts	28	BE
2840	Valeur d'acquisition	284	BE
2841	Montants non-appelés(-)	284	BE
2848	Plus-values actées	284	BE
2849	Réductions de valeurs actées	284	BE
285	Autres créances	28	BE
2850	Créance en compte	285	BE
2851	Effets à recevoir	285	BE
2852	Titre à revenu fixe	285	BE
2857	Créances douteuses	285	BE
2859	Réductions de valeurs actées	285	BE
288	Cautionnements versés en numéraires	28	BE
29	Créances à plus d'un an	2	BE
290	Créances commerciales	29	BE
2900	Clients	290	BE
2901	Effets à recevoir	290	BE
2906	Acomptes versés	290	BE
2907	Créances douteuses	290	BE
2909	Réductions de valeurs actées	290	BE
291	Autres créances	29	BE
2910	Créances en comptes	291	BE
2911	Effets à recevoir	291	BE
2917	Créances douteuses	291	BE
2919	Réductions de valeurs actées(-)	291	BE
30	Approvisionements - Matières premières	3	BE
300	Valeur d'acquisition	30	BE
309	Réductions de valeur actées	30	BE
31	Approvisionnements - fournitures	3	BE
310	Valeur d'acquisition	31	BE
319	Réductions de valeurs actées(-)	31	BE
32	En-cours de fabrication	3	BE
320	Valeurs d'acquisition	32	BE
329	Réductions de valeur actées	32	BE
33	Produits finis	3	BE
330	Valeur d'acquisition	33	BE
339	Réductions de valeur actées	33	BE
34	Marchandises	3	BE
340	Valeur d'acquisition	34	BE
349	Réductions de valeur actées	34	BE
35	Immeubles destinés à la vente	3	BE
350	Valeur d'acquisition	35	BE
359	Réductions de valeur actées	35	BE
36	Acomptes versés sur achats pour stocks	3	BE
360	Valeur d'acquisition	36	BE
369	Réductions de valeur actées	36	BE
37	Commandes en cours éxécution	3	BE
370	Valeur d'acquisition	37	BE
371	Bénéfice pris en compte 	37	BE
379	Réductions de valeur actées	37	BE
40	Créances commerciales	4	BE
400	Clients	40	BE
401	Effets à recevoir	40	BE
404	Produits à recevoir	40	BE
406	Acomptes versés	40	BE
407	Créances douteuses	40	BE
409	Réductions de valeur actées	40	BE
41	Autres créances	4	BE
410	Capital appelé non versé	41	BE
411	TVA à récupérer	41	BE
412	Impôts et précomptes à récupérer	41	BE
4120	Impôt belge sur le résultat	412	BE
4121	Impôt belge sur le résultat	412	BE
4122	Impôt belge sur le résultat	412	BE
4123	Impôt belge sur le résultat	412	BE
4124	Impôt belge sur le résultat	412	BE
4125	Autres impôts et taxes belges	412	BE
4126	Autres impôts et taxes belges	412	BE
4127	Autres impôts et taxes belges	412	BE
4128	Impôts et taxes étrangers	412	BE
414	Produits à recevoir	41	BE
416	Créances diverses	41	BE
4160	Comptes de l'exploitant	416	BE
417	Créances douteuses	41	BE
418	Cautionnements versés en numéraires	41	BE
419	Réductions de valeur actées	41	BE
42	Dettes à plus dun an échéant dans l'année	4	BE
420	Emprunts subordonnés	42	BE
4200	convertibles	420	BE
4201	non convertibles	420	BE
421	Emprunts subordonnés	42	BE
4210	convertibles	420	BE
4211	non convertibles	420	BE
422	 Dettes de locations financement	42	BE
423	 Etablissement de crédit	42	BE
4230	Dettes en comptes	423	BE
4231	Promesses	423	BE
4232	Crédits d'acceptation	423	BE
424	Autres emprunts	42	BE
425	Dettes commerciales	42	BE
4250	Fournisseurs	425	BE
4251	Effets à payer	425	BE
426	Acomptes reçus sur commandes	42	BE
428	Cautionnement reçus en numéraires	42	BE
429	Dettes diverses	42	BE
43	Dettes financières	4	BE
430	Etablissements de crédit - Emprunts à compte à terme fixe	43	BE
431	Etablissements de crédit - Promesses	43	BE
432	 Etablissements de crédit - Crédits d'acceptation	43	BE
433	Etablissements de crédit -Dettes en comptes courant	43	BE
439	Autres emprunts	43	BE
44	Dettes commerciales	4	BE
440	Fournisseurs	44	BE
441	Effets à payer	44	BE
444	Factures à recevoir	44	BE
45	Dettes fiscales, salariales et sociales	4	BE
450	Dettes fiscales estimées	45	BE
4500	Impôts belges sur le résultat	450	BE
4501	Impôts belges sur le résultat	450	BE
4502	Impôts belges sur le résultat	450	BE
4503	Impôts belges sur le résultat	450	BE
4504	Impôts belges sur le résultat	450	BE
4505	Autres impôts et taxes belges	450	BE
4506	Autres impôts et taxes belges	450	BE
4507	Autres impôts et taxes belges	450	BE
4508	Impôts et taxes étrangers	450	BE
451	TVA à payer	45	BE
452	Impôts et taxes à payer	45	BE
4520	Impôts belges sur le résultat	452	BE
4521	Impôts belges sur le résultat	452	BE
4522	Impôts belges sur le résultat	452	BE
4523	Impôts belges sur le résultat	452	BE
4524	Impôts belges sur le résultat	452	BE
4525	Autres impôts et taxes belges	452	BE
4526	Autres impôts et taxes belges	452	BE
4527	Autres impôts et taxes belges	452	BE
4528	Impôts et taxes étrangers	452	BE
453	Précomptes retenus	45	BE
454	Office National de la Sécurité Sociales	45	BE
455	Rémunérations	45	BE
456	Pécules de vacances	45	BE
459	Autres dettes sociales	45	BE
46	Acomptes reçus sur commandes	4	BE
47	Dettes découlant de l'affectation du résultat	4	BE
470	Dividendes et tantièmes d'exercices antérieurs	47	BE
471	Dividendes de l'exercice	47	BE
472	Tantièmes de l'exercice	47	BE
473	Autres allocataires	47	BE
48	Dettes diverses	4	BE
480	Obligations et coupons échus	48	BE
488	Cautionnements reçus en numéraires	48	BE
489	Autres dettes diverses	48	BE
4890	Compte de l'exploitant	489	BE
49	Comptes de régularisation	4	BE
490	Charges à reporter	49	BE
491	Produits acquis	49	BE
492	Charges à imputer	49	BE
493	Produits à reporter	49	BE
499	Comptes d'attentes	49	BE
50	Actions propres	5	BE
51	Actions et parts	5	BE
510	Valeur d'acquisition	51	BE
511	Montant non appelés	51	BE
519	Réductions de valeur actées	51	BE
52	Titres à revenu fixe	5	BE
520	Valeur d'acquisition	52	BE
529	Réductions de valeur actées	52	BE
53	Dépôts à terme	5	BE
530	de plus d'un an	53	BE
531	de plus d'un mois et d'un an au plus	53	BE
532	d'un mois au plus	53	BE
539	Réductions de valeur actées	53	BE
54	Valeurs échues à l'encaissement	5	BE
55	Etablissement de crédit	5	BE
550	Banque 1	55	BE
5500	Comptes courants	550	BE
5501	Chèques émis (-)	550	BE
5509	Réduction de valeur actée	550	BE
5510	Comptes courants	551	BE
5511	Chèques émis (-)	551	BE
5519	Réduction de valeur actée	551	BE
5520	Comptes courants	552	BE
5521	Chèques émis (-)	552	BE
5529	Réduction de valeur actée	552	BE
5530	Comptes courants	553	BE
5531	Chèques émis (-)	553	BE
5539	Réduction de valeur actée	553	BE
5540	Comptes courants	554	BE
5541	Chèques émis (-)	554	BE
5549	Réduction de valeur actée	554	BE
5550	Comptes courants	555	BE
5551	Chèques émis (-)	555	BE
5559	Réduction de valeur actée	555	BE
5560	Comptes courants	556	BE
5561	Chèques émis (-)	556	BE
5569	Réduction de valeur actée	556	BE
5570	Comptes courants	557	BE
5571	Chèques émis (-)	557	BE
5579	Réduction de valeur actée	557	BE
5580	Comptes courants	558	BE
5581	Chèques émis (-)	558	BE
5589	Réduction de valeur actée	558	BE
5590	Comptes courants	559	BE
5591	Chèques émis (-)	559	BE
5599	Réduction de valeur actée	559	BE
56	Office des chèques postaux	5	BE
560	Compte courant	56	BE
561	Chèques émis	56	BE
57	Caisses	5	BE
578	Caisse timbre	57	BE
58	Virement interne	5	BE
60	Approvisionnement et marchandises	6	BE
600	Achats de matières premières	60	BE
601	Achats de fournitures	60	BE
602	Achats de services, travaux et études	60	BE
603	Sous-traitances générales	60	BE
604	Achats de marchandises	60	BE
605	Achats d'immeubles destinés à la vente	60	BE
608	Remises, ristournes et rabais obtenus(-)	60	BE
609	Variation de stock	60	BE
6090	de matières premières	609	BE
6091	de fournitures	609	BE
6094	de marchandises	609	BE
6095	immeubles achetés destinés à la vente	609	BE
61	Services et biens divers	6	BE
62	Rémunérations, charges sociales et pensions	6	BE
620	Rémunérations et avantages sociaux directs	62	BE
6200	Administrateurs ou gérants	620	BE
6201	Personnel de directions	620	BE
6202	Employés,620	6202	BE
6203	Ouvriers	620	BE
6204	Autres membres du personnel	620	BE
621	Cotisations patronales d'assurances sociales	62	BE
622	Primes partonales pour assurances extra-légales	62	BE
623	Autres frais de personnel	62	BE
624	Pensions de retraite et de survie	62	BE
6240	Administrateurs ou gérants	624	BE
6241	Personnel	624	BE
63	Amortissements, réductions de valeurs et provisions pour risques et charges	6	BE
630	Dotations aux amortissements et réduction de valeurs sur immobilisations	63	BE
6300	 Dotations aux amortissements sur frais d'établissement	630	BE
705	Ventes et prestations de services	70	BE
6301	Dotations aux amortissements sur immobilisations incorporelles	630	BE
6302	Dotations aux amortissements sur immobilisations corporelles	630	BE
6308	Dotations aux réductions de valeur sur immobilisations incorporelles	630	BE
6309	Dotations aux réductions de valeur sur immobilisations corporelles	630	BE
631	Réductions de valeur sur stocks	63	BE
6310	Dotations	631	BE
6311	Reprises(-)	631	BE
632	Réductions de valeur sur commande en cours d'éxécution	63	BE
6320	Dotations	632	BE
6321	Reprises(-)	632	BE
633	Réductions de valeurs sur créances commerciales à plus d'un an	63	BE
6330	Dotations	633	BE
6331	Reprises(-)	633	BE
634	Réductions de valeur sur créances commerciales à un an au plus	63	BE
6340	Dotations	634	BE
6341	Reprise	634	BE
635	Provisions pour pensions et obligations similaires	63	BE
6350	Dotations	635	BE
6351	Utilisation et reprises	635	BE
636	Provisions pour grosses réparations et gros entretien	63	BE
6360	Dotations	636	BE
6361	Reprises(-)	636	BE
637	Provisions pour autres risques et charges	63	BE
6370	Dotations	637	BE
6371	Reprises(-)	637	BE
64	Autres charges d'exploitation	6	BE
640	Charges fiscales d'exploitation	64	BE
641	Moins-values sur réalisations courantes d'immobilisations corporelles	64	BE
642	Moins-value sur réalisation de créances commerciales	64	BE
643	Charges d'exploitations	64	BE
644	Charges d'exploitations	64	BE
645	Charges d'exploitations	64	BE
646	Charges d'exploitations	64	BE
647	Charges d'exploitations	64	BE
648	Charges d'exploitations	64	BE
649	Charges d'exploitation portées à l'actif au titre de frais de restructuration(-)	64	BE
65	Charges financières	6	BE
650	Charges des dettes	65	BE
6500	Intérêts, commmissions et frais afférents aux dettes	650	BE
6501	Amortissements des frais d'émissions d'emrunts et des primes de remboursement	650	BE
6502	Autres charges des dettes	650	BE
6503	Intérêts intercalaires portés à l'actif(-)	650	BE
651	Réductions de valeur sur actifs circulants	65	BE
6510	Dotations	651	BE
6511	Reprises(-)	651	BE
652	Moins-value sur réalisation d'actifs circulants	65	BE
653	Charges d'escompte de créances	65	BE
654	Différences de changes	65	BE
655	Ecarts de conversion des devises	65	BE
656	Charges financières diverses	65	BE
657	Charges financières diverses	65	BE
658	Charges financières diverses	65	BE
659	Charges financières diverses	65	BE
66	Charges exceptionnelles	6	BE
660	Amortissements et réductions de valeur exceptionnels (dotations)	66	BE
6600	sur frais d'établissement	660	BE
6601	sur immobilisations incorporelles	660	BE
6602	sur immobilisations corporelles	660	BE
661	Réductions de valeur sur immobilisations financières (dotations)	66	BE
662	Provisions pour risques et charges exceptionnels	66	BE
663	Moins-values sur réalisations d'actifs immobilisés	66	BE
664	Autres charges exceptionnelles	66	BE
665	Autres charges exceptionnelles	66	BE
666	Autres charges exceptionnelles	66	BE
667	Autres charges exceptionnelles	66	BE
668	Autres charges exceptionnelles	66	BE
669	 Charges exceptionnelles portées à l'actif au titre de frais de restructuration	66	BE
67	impôts sur le résultat	6	BE
670	Impôts belge sur le résultat de l'exercice	67	BE
6700	Impôts et précomptes dus ou versés	670	BE
6701	Excédents de versement d'impôts et de précomptes portés à l'actifs (-)	670	BE
6702	Charges fiscales estimées	670	BE
671	Impôts belges sur le résultats d'exercices antérieures	67	BE
6710	Suppléments d'impôt dus ou versés	671	BE
6711	Suppléments d'impôts estimés	671	BE
6712	Provisions fiscales constituées	671	BE
672	Impôts étrangers sur le résultat de l'exercice	67	BE
673	Impôts étrangers sur le résultat d'exercice antérieures	67	BE
68	Transferts aux réserves immunisées	6	BE
69	Affectations et prélévements	6	BE
690	Perte reportée de l'exercice précédent	69	BE
691	Dotation à la réserve légale	69	BE
692	Dotation aux autres réserves	69	BE
693	Bénéfice à reporter	69	BE
694	Rémunération du capital	69	BE
695	Administrateurs ou gérants	69	BE
696	Autres allocataires	69	BE
70	Chiffre d'affaire	7	BE
700	Ventes et prestations de services	70	BE
701	Ventes et prestations de services	70	BE
702	Ventes et prestations de services	70	BE
703	Ventes et prestations de services	70	BE
704	Ventes et prestations de services	70	BE
706	Ventes et prestations de services	70	BE
707	Ventes et prestations de services	70	BE
709	Remises, ristournes et rabais accordés(-)	70	BE
71	Variations des stocks et commandes en cours d'éxécution	7	BE
712	des en-cours de fabrication	71	BE
713	des produits finis	71	BE
715	des immeubles construits destinés à la vente	71	BE
717	 des commandes  en cours d'éxécution	71	BE
7170	Valeur d'acquisition	717	BE
7171	Bénéfice pris en compte	717	BE
72	Production immobilisée	7	BE
74	Autres produits d'exploitation	7	BE
740	Subsides d' exploitation  et montants compensatoires	74	BE
741	Plus-values sur réalisation courantes d' immobilisations corporelles	74	BE
742	Plus-values sur réalisations de créances commerciales	74	BE
743	Produits d'exploitations divers	74	BE
744	Produits d'exploitations divers	74	BE
745	Produits d'exploitations divers	74	BE
746	Produits d'exploitations divers	74	BE
747	Produits d'exploitations divers	74	BE
748	Produits d'exploitations divers	74	BE
749	Produits d'exploitations divers	74	BE
75	Produits financiers	7	BE
750	Produits sur immobilisations financières	75	BE
751	Produits des actifs circulants	75	BE
752	Plus-value sur réalisations d'actis circulants	75	BE
753	Subsides en capital et intérêts	75	BE
754	Différences de change	75	BE
755	Ecarts de conversion des devises	75	BE
756	Produits financiers divers	75	BE
757	Produits financiers divers	75	BE
758	Produits financiers divers	75	BE
759	Produits financiers divers	75	BE
76	Produits exceptionnels	7	BE
760	Reprise d'amortissements et de réductions de valeur	76	BE
7601	sur immobilisations corporelles	760	BE
7602	sur immobilisations incorporelles	760	BE
761	Reprises de réductions de valeur sur immobilisations financières	76	BE
762	Reprises de provisions pour risques et charges exceptionnels	76	BE
763	Plus-value sur réalisation d'actifs immobilisé	76	BE
764	Autres produits exceptionnels	76	BE
765	Autres produits exceptionnels	76	BE
766	Autres produits exceptionnels	76	BE
767	Autres produits exceptionnels	76	BE
768	Autres produits exceptionnels	76	BE
769	Autres produits exceptionnels	76	BE
77	Régularisations d'impôts et reprises de provisions fiscales	7	BE
771	impôts belges sur le résultat	77	BE
7710	Régularisations d'impôts dus ou versé	771	BE
7711	Régularisations d'impôts estimés	771	BE
7712	Reprises de provisions fiscales	771	BE
773	Impôts étrangers sur le résultats	77	BE
79	Affectations et prélévements	7	BE
790	Bénéfice reporté de l'exercice précédent	79	BE
791	Prélévement sur le capital et les primes d'émission	79	BE
792	Prélévement sur les réserves	79	BE
793	Perte à reporter	79	BE
794	Intervention d'associés (ou du propriétaire) dans la perte	79	BE
1	Fonds propres, provisions pour risques et charges à plus d'un an	0	BE
2	Frais d'établissement, actifs immobilisés et créances à plus d'un an	0	BE
3	Stocks et commandes en cours d'éxécution	0	BE
4	Créances et dettes à un an au plus	0	BE
5	Placements de trésorerie et valeurs disponibles	0	BE
6	Charges	0	BE
7	Produits	0	BE
4000001	Client 1	400	BE
4000002	Client 2	400	BE
70001	Marchandise A	700	BE
70002	Marchandise B	700	BE
70003	Marchandise C	700	BE
70004	Marchandise D	700	BE
4400001	Fournisseur A	440	BE
4400002	Fournisseur B	440	BE
610001	fourniture A	61	BE
55000002	Argenta	5500	BE
55000001	Banque 1	5500	BE
4000003	Client fiche	400	BE
4000004	Toto	400	BE
4000005	NOUVEAU CLIENT	400	BE
610002	Loyer	61	BE
\.


--
-- Data for TOC entry 126 (OID 21798)
-- Name: version; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY "version" (val) FROM stdin;
3
\.


--
-- Data for TOC entry 127 (OID 21804)
-- Name: parm_money; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY parm_money (pm_id, pm_code, pm_rate) FROM stdin;
1	EUR	1
\.


--
-- Data for TOC entry 128 (OID 21807)
-- Name: parm_periode; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY parm_periode (p_id, p_start, p_end, p_exercice, p_closed) FROM stdin;
1	2003-01-01	2003-01-31	2003	f
2	2003-02-01	2003-02-28	2003	f
3	2003-03-01	2003-03-31	2003	f
4	2003-04-01	2003-04-30	2003	f
5	2003-05-01	2003-05-31	2003	f
6	2003-06-01	2003-06-30	2003	f
7	2003-07-01	2003-07-31	2003	f
8	2003-08-01	2003-08-31	2003	f
9	2003-09-01	2003-09-30	2003	f
10	2003-10-01	2003-10-30	2003	f
11	2003-11-01	2003-11-30	2003	f
12	2003-12-01	2003-12-31	2003	f
13	2003-12-31	\N	2003	f
27	2004-01-01	2004-01-31	2004	f
28	2004-02-01	2004-02-28	2004	f
29	2004-03-01	2004-03-31	2004	f
30	2004-04-01	2004-04-30	2004	f
31	2004-05-01	2004-05-31	2004	f
32	2004-06-01	2004-06-30	2004	f
33	2004-07-01	2004-07-31	2004	f
34	2004-08-01	2004-08-31	2004	f
35	2004-09-01	2004-09-30	2004	f
36	2004-10-01	2004-10-30	2004	f
37	2004-11-01	2004-11-30	2004	f
38	2004-12-01	2004-12-31	2004	f
39	2004-12-31	\N	2004	f
\.


--
-- Data for TOC entry 129 (OID 21823)
-- Name: jrn_type; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY jrn_type (jrn_type_id, jrn_desc) FROM stdin;
FIN	Financier
VEN	Vente
ACH	Achat
OD 	Opérations Diverses
\.


--
-- Data for TOC entry 130 (OID 21828)
-- Name: jrn_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) FROM stdin;
1	Financier	5* 	5*	1,2,4,9	1,2,4,9	5	5	f	\N	FIN	FIN-01
4	Opération Diverses	\N	\N	\N	\N	5	5	f	\N	OD 	OD-01
3	Achat	6*	4*	5	4	1	3	t	échéance	ACH	ACH-01
2	Vente	7*	4*	2	3	1	3	t	échéance	VEN	VEN-01
\.


--
-- Data for TOC entry 131 (OID 21839)
-- Name: jrn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY jrn (jr_id, jr_def_id, jr_montant, jr_comment, jr_date, jr_grpt_id, jr_internal, jr_tech_date, jr_tech_per, jr_ech, jr_rapt) FROM stdin;
54	2	1210	VEN-01-00054  client : NOUVEAU CLIENT	2004-04-01	56	VEN-01-00054	2004-03-25 18:28:58.14453	30	\N	\N
55	2	1210	VEN-01-00055  client : NOUVEAU CLIENT	2004-04-01	57	VEN-01-00055	2004-03-25 18:29:15.376252	30	\N	\N
1	2	121	VEN-01-00001	2003-01-01	3	VEN-01-00001	2004-03-23 13:47:24.537294	1	\N	\N
56	2	121	VEN-01-00056  client : NOUVEAU CLIENT	2004-04-01	58	VEN-01-00056	2004-03-25 18:38:11.552351	30	\N	\N
57	2	121	VEN-01-00057  client : NOUVEAU CLIENT	2004-04-01	59	VEN-01-00057	2004-03-25 18:38:13.281737	30	\N	\N
2	2	121	VEN-01-00002	2003-01-01	4	VEN-01-00002	2004-03-23 14:05:34.460249	1	\N	\N
58	2	121	VEN-01-00058  client : NOUVEAU CLIENT	2004-04-01	60	VEN-01-00058	2004-03-25 18:38:13.356227	30	\N	\N
59	2	121	VEN-01-00059  client : NOUVEAU CLIENT	2004-04-01	61	VEN-01-00059	2004-03-25 18:38:54.279845	30	\N	\N
3	2	121	VEN-01-00003	2003-01-01	5	VEN-01-00003	2004-03-23 14:14:35.24054	1	\N	\N
60	2	121	VEN-01-00060  client : NOUVEAU CLIENT	2004-04-01	62	VEN-01-00060	2004-03-25 18:38:54.341601	30	\N	\N
61	2	121	VEN-01-00061  client : NOUVEAU CLIENT	2004-04-01	63	VEN-01-00061	2004-03-25 18:39:08.103448	30	\N	\N
4	2	121	VEN-01-00004	2003-01-01	6	VEN-01-00004	2004-03-23 14:16:23.732411	1	\N	\N
62	2	121	VEN-01-00062  client : NOUVEAU CLIENT	2004-04-01	64	VEN-01-00062	2004-03-25 18:39:08.166298	30	\N	\N
63	2	121	VEN-01-00063  client : NOUVEAU CLIENT	2004-04-01	65	VEN-01-00063	2004-03-25 18:40:13.781818	30	\N	\N
5	2	121	VEN-01-00005	2003-01-01	7	VEN-01-00005	2004-03-23 14:17:50.124512	1	\N	\N
64	2	121	VEN-01-00064  client : NOUVEAU CLIENT	2004-04-01	66	VEN-01-00064	2004-03-25 18:40:13.840801	30	\N	\N
65	2	121	VEN-01-00065  client : NOUVEAU CLIENT	2004-04-01	67	VEN-01-00065	2004-03-25 18:40:18.669369	30	\N	\N
6	2	121	VEN-01-00006	2003-01-01	8	VEN-01-00006	2004-03-23 14:18:33.81708	1	\N	\N
66	2	121	VEN-01-00066  client : NOUVEAU CLIENT	2004-04-01	68	VEN-01-00066	2004-03-25 18:40:18.73078	30	\N	\N
67	2	121	VEN-01-00067  client : NOUVEAU CLIENT	2004-04-01	69	VEN-01-00067	2004-03-25 18:41:38.636524	30	\N	\N
7	2	605	VEN-01-00007	2003-01-01	9	VEN-01-00007	2004-03-23 14:18:43.862098	1	\N	\N
68	2	121	VEN-01-00068  client : NOUVEAU CLIENT	2004-04-01	70	VEN-01-00068	2004-03-25 18:41:38.696572	30	\N	\N
69	2	121	VEN-01-00069  client : NOUVEAU CLIENT	2004-04-01	71	VEN-01-00069	2004-03-25 18:43:06.044583	30	\N	\N
8	2	605	VEN-01-00008	2003-01-01	10	VEN-01-00008	2004-03-23 14:18:55.356075	1	\N	\N
70	2	121	VEN-01-00070  client : NOUVEAU CLIENT	2004-04-01	72	VEN-01-00070	2004-03-25 18:43:06.107978	30	\N	\N
71	2	605	VEN-01-00071  client : NOUVEAU CLIENT	2004-04-01	73	VEN-01-00071	2004-03-25 18:45:40.030576	30	\N	\N
9	2	605	VEN-01-00009	2003-01-01	11	VEN-01-00009	2004-03-23 14:24:19.78429	1	\N	\N
72	2	858.6	VEN-01-00072  client : Client 2	2004-04-01	74	VEN-01-00072	2004-03-25 22:44:24.649864	30	\N	\N
73	2	4116.5	VEN-01-00073  client : Toto	2004-04-01	75	VEN-01-00073	2004-03-25 22:46:04.176429	30	\N	\N
10	2	605	VEN-01-00010	2003-01-01	12	VEN-01-00010	2004-03-23 14:27:37.592925	1	\N	\N
74	2	4116.5	VEN-01-00074  client : Toto	2004-04-01	76	VEN-01-00074	2004-03-25 22:48:07.346032	30	\N	\N
75	2	4116.5	VEN-01-00075  client : Toto	2004-04-01	77	VEN-01-00075	2004-03-25 22:49:56.985635	30	\N	\N
11	2	605	VEN-01-00011	2003-01-01	13	VEN-01-00011	2004-03-23 14:31:13.983055	1	\N	\N
76	3	24.2	ACH-01-00001  client : Fournisseur B	2004-04-01	81	ACH-01-00001	2004-03-26 00:40:57.302856	30	\N	\N
77	3	0	ACH-01-00002  client : Fournisseur B	2004-04-01	82	ACH-01-00002	2004-03-26 00:41:13.024891	30	\N	\N
12	2	605	VEN-01-00012	2003-01-01	14	VEN-01-00012	2004-03-23 14:31:24.287667	1	\N	\N
78	3	380	ACH-01-00003  client : Fournisseur B	2004-04-01	83	ACH-01-00003	2004-03-26 00:42:55.879974	30	\N	\N
28	2	123	VEN-01-00028  client : Client 1	2004-03-01	30	VEN-01-00028	2004-03-25 17:41:27.129826	29	\N	\N
13	2	605	VEN-01-00013	2003-01-01	15	VEN-01-00013	2004-03-23 14:46:30.502689	1	\N	\N
29	2	3086.35	VEN-01-00029  client : Client 2	2004-03-01	31	VEN-01-00029	2004-03-25 17:48:09.18601	29	\N	\N
30	2	3086.35	VEN-01-00030  client : Client 2	2004-03-01	32	VEN-01-00030	2004-03-25 17:48:19.358459	29	\N	\N
14	2	605	VEN-01-00014client : Client 1	2003-01-01	16	VEN-01-00014	2004-03-23 14:49:56.676495	1	\N	\N
31	2	1113	VEN-01-00031  client : Client 1	2004-03-01	33	VEN-01-00031	2004-03-25 17:51:51.498222	29	\N	\N
32	2	484	VEN-01-00032  client : Client 1	2004-03-01	34	VEN-01-00032	2004-03-25 17:52:56.208691	29	\N	\N
15	2	1210	VEN-01-00015  client : Client 2	2003-01-01	17	VEN-01-00015	2004-03-23 14:54:29.095873	1	\N	\N
33	2	484	VEN-01-00033  client : Client 1	2004-03-01	35	VEN-01-00033	2004-03-25 17:53:27.832884	29	\N	\N
34	2	484	VEN-01-00034  client : Client 1	2004-03-01	36	VEN-01-00034	2004-03-25 17:53:33.310167	29	\N	\N
16	2	6050	VEN-01-00016  client : Client 1	2003-01-01	18	VEN-01-00016	2004-03-23 15:37:53.030342	1	2003-03-01	\N
35	2	484	VEN-01-00035  client : Client 1	2004-03-01	37	VEN-01-00035	2004-03-25 17:53:46.282913	29	\N	\N
36	2	484	VEN-01-00036  client : Client 1	2004-03-01	38	VEN-01-00036	2004-03-25 17:54:05.645273	29	\N	\N
17	2	4235	VEN-01-00017  client : Client 1	2003-01-11	19	VEN-01-00017	2004-03-23 15:38:24.412245	1	2003-04-01	\N
37	2	0	VEN-01-00037  client : NOUVEAU CLIENT	2004-03-01	39	VEN-01-00037	2004-03-25 17:58:05.877515	29	\N	\N
38	2	327.91	VEN-01-00038  client : Client 2	2004-04-01	40	VEN-01-00038	2004-03-25 18:11:43.392172	30	\N	\N
18	2	0	VEN-01-00018  client : Client 1	2003-01-01	20	VEN-01-00018	2004-03-23 21:50:46.189884	1	\N	\N
39	2	327.91	VEN-01-00039  client : Client 2	2004-04-01	41	VEN-01-00039	2004-03-25 18:11:56.798965	30	\N	\N
40	2	242	VEN-01-00040  client : Client 1	2004-04-01	42	VEN-01-00040	2004-03-25 18:12:44.228286	30	\N	\N
19	2	392	VEN-01-00019  client : Client 2	2004-03-01	21	VEN-01-00019	2004-03-25 14:21:09.142765	29	\N	\N
41	2	1027.25	VEN-01-00041  client : Client 1	2004-04-01	43	VEN-01-00041	2004-03-25 18:15:48.651263	30	\N	\N
20	2	1500	VEN-01-00020  client : NOUVEAU CLIENT	2004-03-01	22	VEN-01-00020	2004-03-25 17:31:56.928938	29	\N	\N
21	2	1012.6	VEN-01-00021  client : NOUVEAU CLIENT	2004-03-01	23	VEN-01-00021	2004-03-25 17:33:44.788229	29	\N	\N
22	2	1230	VEN-01-00022  client : NOUVEAU CLIENT	2004-03-01	24	VEN-01-00022	2004-03-25 17:34:55.560705	29	\N	\N
23	2	3085	VEN-01-00023  client : Client 2	2004-03-01	25	VEN-01-00023	2004-03-25 17:35:47.968167	29	\N	\N
24	2	3085	VEN-01-00024  client : Client 2	2004-03-01	26	VEN-01-00024	2004-03-25 17:36:00.362501	29	\N	\N
25	2	3085	VEN-01-00025  client : Client 2	2004-03-01	27	VEN-01-00025	2004-03-25 17:38:58.798242	29	\N	\N
26	2	3085	VEN-01-00026  client : Client 2	2004-03-01	28	VEN-01-00026	2004-03-25 17:39:12.093874	29	\N	\N
27	2	3085	VEN-01-00027  client : Client 2	2004-03-01	29	VEN-01-00027	2004-03-25 17:40:16.587902	29	\N	\N
79	1	0	FIN-01-00001  client : Unknown	2004-04-01	84	FIN-01-00001	2004-03-26 04:32:57.385686	30	\N	\N
42	2	1027.25	VEN-01-00042  client : Client 1	2004-04-01	44	VEN-01-00042	2004-03-25 18:16:38.79963	30	\N	\N
43	2	750	VEN-01-00043  client : Client 1	2004-04-01	45	VEN-01-00043	2004-03-25 18:19:13.953508	30	\N	\N
44	2	750	VEN-01-00044  client : Client 1	2004-04-01	46	VEN-01-00044	2004-03-25 18:20:09.977942	30	\N	\N
45	2	605	VEN-01-00045  client : NOUVEAU CLIENT	2004-04-01	47	VEN-01-00045	2004-03-25 18:24:16.882297	30	\N	\N
46	2	605	VEN-01-00046  client : NOUVEAU CLIENT	2004-04-01	48	VEN-01-00046	2004-03-25 18:24:45.611603	30	\N	\N
47	2	0	VEN-01-00047  client : NOUVEAU CLIENT	2004-04-01	49	VEN-01-00047	2004-03-25 18:25:00.296715	30	\N	\N
48	2	0	VEN-01-00048  client : NOUVEAU CLIENT	2004-04-01	50	VEN-01-00048	2004-03-25 18:26:41.078754	30	\N	\N
49	2	605	VEN-01-00049  client : NOUVEAU CLIENT	2004-04-01	51	VEN-01-00049	2004-03-25 18:26:57.773547	30	\N	\N
50	2	605	VEN-01-00050  client : NOUVEAU CLIENT	2004-04-01	52	VEN-01-00050	2004-03-25 18:27:00.823312	30	\N	\N
51	2	605	VEN-01-00051  client : NOUVEAU CLIENT	2004-04-01	53	VEN-01-00051	2004-03-25 18:27:01.037609	30	\N	\N
52	2	605	VEN-01-00052  client : NOUVEAU CLIENT	2004-04-01	54	VEN-01-00052	2004-03-25 18:28:33.699175	30	\N	\N
53	2	605	VEN-01-00053  client : NOUVEAU CLIENT	2004-04-01	55	VEN-01-00053	2004-03-25 18:28:33.770934	30	\N	\N
\.


--
-- Data for TOC entry 132 (OID 21846)
-- Name: jrnx; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY jrnx (j_id, j_date, j_montant, j_poste, j_grpt, j_rapt, j_jrn_def, j_debit, j_text, j_centralized, j_internal, j_tech_user, j_tech_date, j_tech_per) FROM stdin;
1	2003-01-01	484	4000002	1	\N	2	t	\N	f	\N	phpcompta	2004-03-23 13:44:08.837656	1
272	2004-04-01	20	610001	78	\N	3	t	\N	f	\N	dany	2004-03-26 00:40:11.388474	30
273	2004-04-01	24.2	4400002	79	\N	3	f	\N	f	\N	dany	2004-03-26 00:40:33.54117	30
4	2003-01-01	84	451	1	\N	2	f	\N	f	\N	phpcompta	2004-03-23 13:44:08.887007	1
5	2003-01-01	121	4000001	2	\N	2	t	\N	f	\N	phpcompta	2004-03-23 13:46:16.534052	1
6	2003-01-01	100	70001	2	\N	2	f	\N	f	\N	phpcompta	2004-03-23 13:46:16.549515	1
7	2003-01-01	21	451	2	\N	2	f	\N	f	\N	phpcompta	2004-03-23 13:46:16.575457	1
8	2003-01-01	121	4000001	3	\N	2	t	\N	f	\N	phpcompta	2004-03-23 13:47:24.505201	1
9	2003-01-01	100	70001	3	\N	2	f	\N	f	\N	phpcompta	2004-03-23 13:47:24.522298	1
10	2003-01-01	21	451	3	\N	2	f	\N	f	\N	phpcompta	2004-03-23 13:47:24.529054	1
11	2003-01-01	121	4000001	4	\N	2	t	\N	f	\N	phpcompta	2004-03-23 14:05:34.435455	1
12	2003-01-01	100	70001	4	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:05:34.450893	1
13	2003-01-01	21	451	4	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:05:34.456476	1
14	2003-01-01	121	4000001	5	\N	2	t	\N	f	\N	phpcompta	2004-03-23 14:14:35.206407	1
15	2003-01-01	100	70001	5	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:14:35.229875	1
16	2003-01-01	21	451	5	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:14:35.236637	1
17	2003-01-01	121	4000001	6	\N	2	t	\N	f	\N	phpcompta	2004-03-23 14:16:23.688185	1
18	2003-01-01	100	70001	6	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:16:23.704737	1
19	2003-01-01	21	451	6	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:16:23.726303	1
20	2003-01-01	121	4000001	7	\N	2	t	\N	f	\N	phpcompta	2004-03-23 14:17:50.035336	1
21	2003-01-01	100	70001	7	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:17:50.114145	1
22	2003-01-01	21	451	7	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:17:50.12039	1
23	2003-01-01	121	4000001	8	\N	2	t	\N	f	\N	phpcompta	2004-03-23 14:18:33.751592	1
24	2003-01-01	100	70001	8	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:18:33.776141	1
25	2003-01-01	21	451	8	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:18:33.784781	1
26	2003-01-01	605	4000001	9	\N	2	t	\N	f	\N	phpcompta	2004-03-23 14:18:43.833936	1
27	2003-01-01	500	70001	9	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:18:43.84982	1
28	2003-01-01	105	451	9	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:18:43.858151	1
29	2003-01-01	605	4000001	10	\N	2	t	\N	f	\N	phpcompta	2004-03-23 14:18:55.289723	1
30	2003-01-01	500	70001	10	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:18:55.340145	1
31	2003-01-01	105	451	10	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:18:55.351839	1
32	2003-01-01	605	4000001	11	\N	2	t	\N	f	\N	phpcompta	2004-03-23 14:24:19.718655	1
33	2003-01-01	500	70001	11	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:24:19.767005	1
34	2003-01-01	105	451	11	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:24:19.780372	1
35	2003-01-01	605	4000001	12	\N	2	t	\N	f	\N	phpcompta	2004-03-23 14:27:37.543869	1
36	2003-01-01	500	70001	12	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:27:37.56078	1
37	2003-01-01	105	451	12	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:27:37.588805	1
38	2003-01-01	605	4000001	13	\N	2	t	\N	f	\N	phpcompta	2004-03-23 14:31:13.958267	1
39	2003-01-01	500	70001	13	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:31:13.973454	1
40	2003-01-01	105	451	13	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:31:13.97933	1
41	2003-01-01	605	4000001	14	\N	2	t	\N	f	\N	phpcompta	2004-03-23 14:31:24.261385	1
42	2003-01-01	500	70001	14	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:31:24.27848	1
43	2003-01-01	105	451	14	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:31:24.283905	1
44	2003-01-01	605	4000001	15	\N	2	t	\N	f	\N	phpcompta	2004-03-23 14:46:30.458825	1
45	2003-01-01	500	70001	15	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:46:30.49216	1
46	2003-01-01	105	451	15	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:46:30.49841	1
47	2003-01-01	605	4000001	16	\N	2	t	\N	f	\N	phpcompta	2004-03-23 14:49:56.649896	1
48	2003-01-01	500	70001	16	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:49:56.667083	1
49	2003-01-01	105	451	16	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:49:56.672634	1
50	2003-01-01	1210	4000002	17	\N	2	t	\N	f	\N	phpcompta	2004-03-23 14:54:29.055972	1
51	2003-01-01	1000	70001	17	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:54:29.084167	1
52	2003-01-01	210	451	17	\N	2	f	\N	f	\N	phpcompta	2004-03-23 14:54:29.090254	1
53	2003-01-01	6050	4000001	18	\N	2	t	\N	f	\N	phpcompta	2004-03-23 15:37:53.003304	1
54	2003-01-01	5000	70001	18	\N	2	f	\N	f	\N	phpcompta	2004-03-23 15:37:53.020759	1
55	2003-01-01	1050	451	18	\N	2	f	\N	f	\N	phpcompta	2004-03-23 15:37:53.026419	1
56	2003-01-11	4235	4000001	19	\N	2	t	\N	f	\N	phpcompta	2004-03-23 15:38:24.349646	1
57	2003-01-11	3500	70001	19	\N	2	f	\N	f	\N	phpcompta	2004-03-23 15:38:24.385135	1
58	2003-01-11	3500	70002	19	\N	2	f	\N	f	\N	phpcompta	2004-03-23 15:38:24.403236	1
59	2003-01-11	735	451	19	\N	2	f	\N	f	\N	phpcompta	2004-03-23 15:38:24.408578	1
60	2003-01-01	0	4000001	20	\N	2	t	\N	f	\N	dany	2004-03-23 21:50:46.141907	1
61	2003-01-01	0	70001	20	\N	2	f	\N	f	\N	dany	2004-03-23 21:50:46.180221	1
62	2003-01-01	0	451	20	\N	2	f	\N	f	\N	dany	2004-03-23 21:50:46.186024	1
63	2004-03-01	392	4000002	21	\N	2	t	\N	f	\N	dany	2004-03-25 14:21:08.938766	29
64	2004-03-01	350	70004	21	\N	2	f	\N	f	\N	dany	2004-03-25 14:21:09.087367	29
65	2004-03-01	350	70001	21	\N	2	f	\N	f	\N	dany	2004-03-25 14:21:09.093922	29
66	2004-03-01	0	451	21	\N	2	f	\N	f	\N	dany	2004-03-25 14:21:09.099715	29
67	2004-03-01	42	451	21	\N	2	f	\N	f	\N	dany	2004-03-25 14:21:09.139044	29
68	2004-03-01	1500	4000005	22	\N	2	t	\N	f	\N	dany	2004-03-25 17:31:56.90032	29
69	2004-03-01	1500	70004	22	\N	2	f	\N	f	\N	dany	2004-03-25 17:31:56.919483	29
70	2004-03-01	0	451	22	\N	2	f	\N	f	\N	dany	2004-03-25 17:31:56.925079	29
71	2004-03-01	1012.6	4000005	23	\N	2	t	\N	f	\N	dany	2004-03-25 17:33:44.759556	29
72	2004-03-01	1000	70001	23	\N	2	f	\N	f	\N	dany	2004-03-25 17:33:44.775575	29
73	2004-03-01	12.6	451	23	\N	2	f	\N	f	\N	dany	2004-03-25 17:33:44.784297	29
74	2004-03-01	1230	4000005	24	\N	2	t	\N	f	\N	dany	2004-03-25 17:34:55.525884	29
75	2004-03-01	1230	70001	24	\N	2	f	\N	f	\N	dany	2004-03-25 17:34:55.550173	29
76	2004-03-01	0	451	24	\N	2	f	\N	f	\N	dany	2004-03-25 17:34:55.556599	29
77	2004-03-01	3085	4000002	25	\N	2	t	\N	f	\N	dany	2004-03-25 17:35:47.927244	29
78	2004-03-01	3085	70003	25	\N	2	f	\N	f	\N	dany	2004-03-25 17:35:47.943975	29
79	2004-03-01	3085	70002	25	\N	2	f	\N	f	\N	dany	2004-03-25 17:35:47.953398	29
80	2004-03-01	0	451	25	\N	2	f	\N	f	\N	dany	2004-03-25 17:35:47.959228	29
81	2004-03-01	0	451	25	\N	2	f	\N	f	\N	dany	2004-03-25 17:35:47.964411	29
82	2004-03-01	3085	4000002	26	\N	2	t	\N	f	\N	dany	2004-03-25 17:36:00.29272	29
83	2004-03-01	3085	70003	26	\N	2	f	\N	f	\N	dany	2004-03-25 17:36:00.334306	29
84	2004-03-01	3085	70002	26	\N	2	f	\N	f	\N	dany	2004-03-25 17:36:00.344229	29
85	2004-03-01	0	451	26	\N	2	f	\N	f	\N	dany	2004-03-25 17:36:00.352923	29
86	2004-03-01	0	451	26	\N	2	f	\N	f	\N	dany	2004-03-25 17:36:00.35823	29
87	2004-03-01	3085	4000002	27	\N	2	t	\N	f	\N	dany	2004-03-25 17:38:58.749028	29
88	2004-03-01	3085	70003	27	\N	2	f	\N	f	\N	dany	2004-03-25 17:38:58.766829	29
89	2004-03-01	3085	70002	27	\N	2	f	\N	f	\N	dany	2004-03-25 17:38:58.77984	29
90	2004-03-01	0	451	27	\N	2	f	\N	f	\N	dany	2004-03-25 17:38:58.789413	29
91	2004-03-01	0	451	27	\N	2	f	\N	f	\N	dany	2004-03-25 17:38:58.794563	29
92	2004-03-01	3085	4000002	28	\N	2	t	\N	f	\N	dany	2004-03-25 17:39:12.003188	29
93	2004-03-01	3085	70003	28	\N	2	f	\N	f	\N	dany	2004-03-25 17:39:12.071989	29
94	2004-03-01	3085	70002	28	\N	2	f	\N	f	\N	dany	2004-03-25 17:39:12.078619	29
95	2004-03-01	0	451	28	\N	2	f	\N	f	\N	dany	2004-03-25 17:39:12.084837	29
96	2004-03-01	0	451	28	\N	2	f	\N	f	\N	dany	2004-03-25 17:39:12.090142	29
97	2004-03-01	3085	4000002	29	\N	2	t	\N	f	\N	dany	2004-03-25 17:40:16.542875	29
98	2004-03-01	3085	70003	29	\N	2	f	\N	f	\N	dany	2004-03-25 17:40:16.56722	29
99	2004-03-01	3085	70002	29	\N	2	f	\N	f	\N	dany	2004-03-25 17:40:16.573743	29
100	2004-03-01	0	451	29	\N	2	f	\N	f	\N	dany	2004-03-25 17:40:16.579108	29
101	2004-03-01	0	451	29	\N	2	f	\N	f	\N	dany	2004-03-25 17:40:16.584154	29
102	2004-03-01	123	4000001	30	\N	2	t	\N	f	\N	dany	2004-03-25 17:41:27.103067	29
103	2004-03-01	123	70004	30	\N	2	f	\N	f	\N	dany	2004-03-25 17:41:27.120473	29
104	2004-03-01	0	451	30	\N	2	f	\N	f	\N	dany	2004-03-25 17:41:27.126042	29
105	2004-03-01	3086.35	4000002	31	\N	2	t	\N	f	\N	dany	2004-03-25 17:48:09.146159	29
106	2004-03-01	3085	70003	31	\N	2	f	\N	f	\N	dany	2004-03-25 17:48:09.164505	29
107	2004-03-01	3085	70002	31	\N	2	f	\N	f	\N	dany	2004-03-25 17:48:09.1713	29
108	2004-03-01	0.3	451	31	\N	2	f	\N	f	\N	dany	2004-03-25 17:48:09.176761	29
109	2004-03-01	1.05	451	31	\N	2	f	\N	f	\N	dany	2004-03-25 17:48:09.182277	29
110	2004-03-01	3086.35	4000002	32	\N	2	t	\N	f	\N	dany	2004-03-25 17:48:19.303845	29
111	2004-03-01	3085	70003	32	\N	2	f	\N	f	\N	dany	2004-03-25 17:48:19.334183	29
112	2004-03-01	3085	70002	32	\N	2	f	\N	f	\N	dany	2004-03-25 17:48:19.341581	29
113	2004-03-01	0.3	451	32	\N	2	f	\N	f	\N	dany	2004-03-25 17:48:19.34731	29
114	2004-03-01	1.05	451	32	\N	2	f	\N	f	\N	dany	2004-03-25 17:48:19.354467	29
115	2004-03-01	1113	4000001	33	\N	2	t	\N	f	\N	dany	2004-03-25 17:51:51.400326	29
116	2004-03-01	1050	70003	33	\N	2	f	\N	f	\N	dany	2004-03-25 17:51:51.479187	29
117	2004-03-01	63	451	33	\N	2	f	\N	f	\N	dany	2004-03-25 17:51:51.485468	29
118	2004-03-01	484	4000001	34	\N	2	t	\N	f	\N	dany	2004-03-25 17:52:56.175719	29
119	2004-03-01	400	70001	34	\N	2	f	\N	f	\N	dany	2004-03-25 17:52:56.19239	29
120	2004-03-01	400	70002	34	\N	2	f	\N	f	\N	dany	2004-03-25 17:52:56.19931	29
121	2004-03-01	84	451	34	\N	2	f	\N	f	\N	dany	2004-03-25 17:52:56.204819	29
122	2004-03-01	484	4000001	35	\N	2	t	\N	f	\N	dany	2004-03-25 17:53:27.769022	29
123	2004-03-01	400	70001	35	\N	2	f	\N	f	\N	dany	2004-03-25 17:53:27.797199	29
124	2004-03-01	400	70002	35	\N	2	f	\N	f	\N	dany	2004-03-25 17:53:27.822604	29
125	2004-03-01	84	451	35	\N	2	f	\N	f	\N	dany	2004-03-25 17:53:27.828296	29
126	2004-03-01	484	4000001	36	\N	2	t	\N	f	\N	dany	2004-03-25 17:53:33.275848	29
127	2004-03-01	400	70001	36	\N	2	f	\N	f	\N	dany	2004-03-25 17:53:33.292509	29
128	2004-03-01	400	70002	36	\N	2	f	\N	f	\N	dany	2004-03-25 17:53:33.300459	29
129	2004-03-01	84	451	36	\N	2	f	\N	f	\N	dany	2004-03-25 17:53:33.306057	29
130	2004-03-01	484	4000001	37	\N	2	t	\N	f	\N	dany	2004-03-25 17:53:46.226367	29
131	2004-03-01	400	70001	37	\N	2	f	\N	f	\N	dany	2004-03-25 17:53:46.263388	29
132	2004-03-01	400	70002	37	\N	2	f	\N	f	\N	dany	2004-03-25 17:53:46.271691	29
133	2004-03-01	84	451	37	\N	2	f	\N	f	\N	dany	2004-03-25 17:53:46.27735	29
134	2004-03-01	484	4000001	38	\N	2	t	\N	f	\N	dany	2004-03-25 17:54:05.612655	29
135	2004-03-01	400	70001	38	\N	2	f	\N	f	\N	dany	2004-03-25 17:54:05.629368	29
136	2004-03-01	400	70002	38	\N	2	f	\N	f	\N	dany	2004-03-25 17:54:05.636068	29
137	2004-03-01	84	451	38	\N	2	f	\N	f	\N	dany	2004-03-25 17:54:05.641499	29
138	2004-03-01	0	4000005	39	\N	2	t	\N	f	\N	dany	2004-03-25 17:58:05.84615	29
139	2004-03-01	0	70003	39	\N	2	f	\N	f	\N	dany	2004-03-25 17:58:05.868033	29
140	2004-03-01	0	451	39	\N	2	f	\N	f	\N	dany	2004-03-25 17:58:05.873694	29
141	2004-04-01	327.91	4000002	40	\N	2	t	\N	f	\N	dany	2004-03-25 18:11:43.354675	30
142	2004-04-01	120	70001	40	\N	2	f	\N	f	\N	dany	2004-03-25 18:11:43.373022	30
143	2004-04-01	151	70002	40	\N	2	f	\N	f	\N	dany	2004-03-25 18:11:43.382633	30
144	2004-04-01	56.91	451	40	\N	2	f	\N	f	\N	dany	2004-03-25 18:11:43.388231	30
145	2004-04-01	327.91	4000002	41	\N	2	t	\N	f	\N	dany	2004-03-25 18:11:56.762946	30
146	2004-04-01	120	70001	41	\N	2	f	\N	f	\N	dany	2004-03-25 18:11:56.78083	30
147	2004-04-01	151	70002	41	\N	2	f	\N	f	\N	dany	2004-03-25 18:11:56.788277	30
148	2004-04-01	56.91	451	41	\N	2	f	\N	f	\N	dany	2004-03-25 18:11:56.794537	30
149	2004-04-01	242	4000001	42	\N	2	t	\N	f	\N	dany	2004-03-25 18:12:44.171695	30
150	2004-04-01	100	70001	42	\N	2	f	\N	f	\N	dany	2004-03-25 18:12:44.212209	30
151	2004-04-01	100	70001	42	\N	2	f	\N	f	\N	dany	2004-03-25 18:12:44.218773	30
152	2004-04-01	42	451	42	\N	2	f	\N	f	\N	dany	2004-03-25 18:12:44.224427	30
153	2004-04-01	1027.25	4000001	43	\N	2	t	\N	f	\N	dany	2004-03-25 18:15:48.597011	30
154	2004-04-01	150	70004	43	\N	2	f	\N	f	\N	dany	2004-03-25 18:15:48.615691	30
155	2004-04-01	725	70002	43	\N	2	f	\N	f	\N	dany	2004-03-25 18:15:48.622252	30
156	2004-04-01	0	70001	43	\N	2	f	\N	f	\N	dany	2004-03-25 18:15:48.628711	30
157	2004-04-01	0	451	43	\N	2	f	\N	f	\N	dany	2004-03-25 18:15:48.634206	30
158	2004-04-01	152.25	451	43	\N	2	f	\N	f	\N	dany	2004-03-25 18:15:48.639264	30
159	2004-04-01	1027.25	4000001	44	\N	2	t	\N	f	\N	dany	2004-03-25 18:16:38.72783	30
160	2004-04-01	150	70004	44	\N	2	f	\N	f	\N	dany	2004-03-25 18:16:38.754528	30
161	2004-04-01	725	70002	44	\N	2	f	\N	f	\N	dany	2004-03-25 18:16:38.769099	30
162	2004-04-01	0	70001	44	\N	2	f	\N	f	\N	dany	2004-03-25 18:16:38.776759	30
163	2004-04-01	0	451	44	\N	2	f	\N	f	\N	dany	2004-03-25 18:16:38.783246	30
164	2004-04-01	152.25	451	44	\N	2	f	\N	f	\N	dany	2004-03-25 18:16:38.792241	30
165	2004-04-01	750	4000001	45	\N	2	t	\N	f	\N	dany	2004-03-25 18:19:13.915227	30
166	2004-04-01	750	70004	45	\N	2	f	\N	f	\N	dany	2004-03-25 18:19:13.943722	30
167	2004-04-01	0	451	45	\N	2	f	\N	f	\N	dany	2004-03-25 18:19:13.949548	30
168	2004-04-01	750	4000001	46	\N	2	t	\N	f	\N	dany	2004-03-25 18:20:09.946215	30
169	2004-04-01	750	70004	46	\N	2	f	\N	f	\N	dany	2004-03-25 18:20:09.967112	30
170	2004-04-01	0	451	46	\N	2	f	\N	f	\N	dany	2004-03-25 18:20:09.973592	30
171	2004-04-01	605	4000005	47	\N	2	t	\N	f	\N	dany	2004-03-25 18:24:16.855803	30
172	2004-04-01	500	70001	47	\N	2	f	\N	f	\N	dany	2004-03-25 18:24:16.87285	30
173	2004-04-01	105	451	47	\N	2	f	\N	f	\N	dany	2004-03-25 18:24:16.878369	30
174	2004-04-01	605	4000005	48	\N	2	t	\N	f	\N	dany	2004-03-25 18:24:45.582833	30
175	2004-04-01	500	70001	48	\N	2	f	\N	f	\N	dany	2004-03-25 18:24:45.60194	30
176	2004-04-01	105	451	48	\N	2	f	\N	f	\N	dany	2004-03-25 18:24:45.607414	30
177	2004-04-01	0	4000005	49	\N	2	t	\N	f	\N	dany	2004-03-25 18:25:00.269585	30
178	2004-04-01	0	70001	49	\N	2	f	\N	f	\N	dany	2004-03-25 18:25:00.287315	30
179	2004-04-01	0	451	49	\N	2	f	\N	f	\N	dany	2004-03-25 18:25:00.292919	30
180	2004-04-01	0	4000005	50	\N	2	t	\N	f	\N	dany	2004-03-25 18:26:41.051651	30
181	2004-04-01	0	70001	50	\N	2	f	\N	f	\N	dany	2004-03-25 18:26:41.069233	30
182	2004-04-01	0	451	50	\N	2	f	\N	f	\N	dany	2004-03-25 18:26:41.074665	30
183	2004-04-01	605	4000005	51	\N	2	t	\N	f	\N	dany	2004-03-25 18:26:57.746752	30
184	2004-04-01	500	70001	51	\N	2	f	\N	f	\N	dany	2004-03-25 18:26:57.764127	30
185	2004-04-01	105	451	51	\N	2	f	\N	f	\N	dany	2004-03-25 18:26:57.769766	30
186	2004-04-01	605	4000005	52	\N	2	t	\N	f	\N	dany	2004-03-25 18:27:00.794503	30
187	2004-04-01	500	70001	52	\N	2	f	\N	f	\N	dany	2004-03-25 18:27:00.811162	30
188	2004-04-01	105	451	52	\N	2	f	\N	f	\N	dany	2004-03-25 18:27:00.819053	30
189	2004-04-01	605	4000005	53	\N	2	t	\N	f	\N	dany	2004-03-25 18:27:00.986789	30
190	2004-04-01	500	70001	53	\N	2	f	\N	f	\N	dany	2004-03-25 18:27:01.016165	30
191	2004-04-01	105	451	53	\N	2	f	\N	f	\N	dany	2004-03-25 18:27:01.03292	30
192	2004-04-01	605	4000005	54	\N	2	t	\N	f	\N	dany	2004-03-25 18:28:33.650454	30
193	2004-04-01	500	70001	54	\N	2	f	\N	f	\N	dany	2004-03-25 18:28:33.66699	30
194	2004-04-01	105	451	54	\N	2	f	\N	f	\N	dany	2004-03-25 18:28:33.673293	30
195	2004-04-01	605	4000005	55	\N	2	t	\N	f	\N	dany	2004-03-25 18:28:33.7523	30
196	2004-04-01	500	70001	55	\N	2	f	\N	f	\N	dany	2004-03-25 18:28:33.760926	30
197	2004-04-01	105	451	55	\N	2	f	\N	f	\N	dany	2004-03-25 18:28:33.766592	30
198	2004-04-01	1210	4000005	56	\N	2	t	\N	f	\N	dany	2004-03-25 18:28:58.117617	30
199	2004-04-01	1000	70001	56	\N	2	f	\N	f	\N	dany	2004-03-25 18:28:58.1349	30
200	2004-04-01	210	451	56	\N	2	f	\N	f	\N	dany	2004-03-25 18:28:58.140719	30
201	2004-04-01	1210	4000005	57	\N	2	t	\N	f	\N	dany	2004-03-25 18:29:15.347207	30
202	2004-04-01	1000	70001	57	\N	2	f	\N	f	\N	dany	2004-03-25 18:29:15.365558	30
203	2004-04-01	210	451	57	\N	2	f	\N	f	\N	dany	2004-03-25 18:29:15.371801	30
204	2004-04-01	121	4000005	58	\N	2	t	\N	f	\N	dany	2004-03-25 18:38:11.524259	30
205	2004-04-01	100	70001	58	\N	2	f	\N	f	\N	dany	2004-03-25 18:38:11.543024	30
206	2004-04-01	21	451	58	\N	2	f	\N	f	\N	dany	2004-03-25 18:38:11.548557	30
207	2004-04-01	121	4000005	59	\N	2	t	\N	f	\N	dany	2004-03-25 18:38:13.253348	30
208	2004-04-01	100	70001	59	\N	2	f	\N	f	\N	dany	2004-03-25 18:38:13.27219	30
209	2004-04-01	21	451	59	\N	2	f	\N	f	\N	dany	2004-03-25 18:38:13.277619	30
210	2004-04-01	121	4000005	60	\N	2	t	\N	f	\N	dany	2004-03-25 18:38:13.334188	30
211	2004-04-01	100	70001	60	\N	2	f	\N	f	\N	dany	2004-03-25 18:38:13.34485	30
212	2004-04-01	21	451	60	\N	2	f	\N	f	\N	dany	2004-03-25 18:38:13.352391	30
213	2004-04-01	121	4000005	61	\N	2	t	\N	f	\N	dany	2004-03-25 18:38:54.227969	30
214	2004-04-01	100	70001	61	\N	2	f	\N	f	\N	dany	2004-03-25 18:38:54.26622	30
215	2004-04-01	21	451	61	\N	2	f	\N	f	\N	dany	2004-03-25 18:38:54.274833	30
216	2004-04-01	121	4000005	62	\N	2	t	\N	f	\N	dany	2004-03-25 18:38:54.321747	30
217	2004-04-01	100	70001	62	\N	2	f	\N	f	\N	dany	2004-03-25 18:38:54.331093	30
218	2004-04-01	21	451	62	\N	2	f	\N	f	\N	dany	2004-03-25 18:38:54.336902	30
219	2004-04-01	121	4000005	63	\N	2	t	\N	f	\N	dany	2004-03-25 18:39:08.075266	30
220	2004-04-01	100	70001	63	\N	2	f	\N	f	\N	dany	2004-03-25 18:39:08.092143	30
221	2004-04-01	21	451	63	\N	2	f	\N	f	\N	dany	2004-03-25 18:39:08.097662	30
222	2004-04-01	121	4000005	64	\N	2	t	\N	f	\N	dany	2004-03-25 18:39:08.146529	30
223	2004-04-01	100	70001	64	\N	2	f	\N	f	\N	dany	2004-03-25 18:39:08.157341	30
224	2004-04-01	21	451	64	\N	2	f	\N	f	\N	dany	2004-03-25 18:39:08.162515	30
225	2004-04-01	121	4000005	65	\N	2	t	\N	f	\N	dany	2004-03-25 18:40:12.634532	30
226	2004-04-01	100	70001	65	\N	2	f	\N	f	\N	dany	2004-03-25 18:40:13.770606	30
227	2004-04-01	21	451	65	\N	2	f	\N	f	\N	dany	2004-03-25 18:40:13.776812	30
228	2004-04-01	121	4000005	66	\N	2	t	\N	f	\N	dany	2004-03-25 18:40:13.823585	30
229	2004-04-01	100	70001	66	\N	2	f	\N	f	\N	dany	2004-03-25 18:40:13.831875	30
230	2004-04-01	21	451	66	\N	2	f	\N	f	\N	dany	2004-03-25 18:40:13.836918	30
231	2004-04-01	121	4000005	67	\N	2	t	\N	f	\N	dany	2004-03-25 18:40:18.573592	30
232	2004-04-01	100	70001	67	\N	2	f	\N	f	\N	dany	2004-03-25 18:40:18.607407	30
233	2004-04-01	21	451	67	\N	2	f	\N	f	\N	dany	2004-03-25 18:40:18.653411	30
234	2004-04-01	121	4000005	68	\N	2	t	\N	f	\N	dany	2004-03-25 18:40:18.712462	30
235	2004-04-01	100	70001	68	\N	2	f	\N	f	\N	dany	2004-03-25 18:40:18.720271	30
236	2004-04-01	21	451	68	\N	2	f	\N	f	\N	dany	2004-03-25 18:40:18.725734	30
237	2004-04-01	121	4000005	69	\N	2	t	\N	f	\N	dany	2004-03-25 18:41:38.602398	30
238	2004-04-01	100	70001	69	\N	2	f	\N	f	\N	dany	2004-03-25 18:41:38.625343	30
239	2004-04-01	21	451	69	\N	2	f	\N	f	\N	dany	2004-03-25 18:41:38.632719	30
240	2004-04-01	121	4000005	70	\N	2	t	\N	f	\N	dany	2004-03-25 18:41:38.677377	30
241	2004-04-01	100	70001	70	\N	2	f	\N	f	\N	dany	2004-03-25 18:41:38.687785	30
242	2004-04-01	21	451	70	\N	2	f	\N	f	\N	dany	2004-03-25 18:41:38.692839	30
243	2004-04-01	121	4000005	71	\N	2	t	\N	f	\N	dany	2004-03-25 18:43:06.015053	30
244	2004-04-01	100	70001	71	\N	2	f	\N	f	\N	dany	2004-03-25 18:43:06.034587	30
245	2004-04-01	21	451	71	\N	2	f	\N	f	\N	dany	2004-03-25 18:43:06.04071	30
246	2004-04-01	121	4000005	72	\N	2	t	\N	f	\N	dany	2004-03-25 18:43:06.089566	30
247	2004-04-01	100	70001	72	\N	2	f	\N	f	\N	dany	2004-03-25 18:43:06.097368	30
248	2004-04-01	21	451	72	\N	2	f	\N	f	\N	dany	2004-03-25 18:43:06.104071	30
249	2004-04-01	605	4000005	73	\N	2	t	\N	f	\N	dany	2004-03-25 18:45:39.986738	30
250	2004-04-01	500	70001	73	\N	2	f	\N	f	\N	dany	2004-03-25 18:45:40.007622	30
251	2004-04-01	105	451	73	\N	2	f	\N	f	\N	dany	2004-03-25 18:45:40.02647	30
252	2004-04-01	858.6	4000002	74	\N	2	t	\N	f	\N	dany	2004-03-25 22:44:24.614334	30
253	2004-04-01	400	70003	74	\N	2	f	\N	f	\N	dany	2004-03-25 22:44:24.633688	30
254	2004-04-01	410	70003	74	\N	2	f	\N	f	\N	dany	2004-03-25 22:44:24.640365	30
255	2004-04-01	48.6	451	74	\N	2	f	\N	f	\N	dany	2004-03-25 22:44:24.645956	30
256	2004-04-01	4116.5	4000004	75	\N	2	t	\N	f	\N	dany	2004-03-25 22:46:04.092868	30
257	2004-04-01	2000	70003	75	\N	2	f	\N	f	\N	dany	2004-03-25 22:46:04.112674	30
258	2004-04-01	1650	70002	75	\N	2	f	\N	f	\N	dany	2004-03-25 22:46:04.156596	30
259	2004-04-01	120	451	75	\N	2	f	\N	f	\N	dany	2004-03-25 22:46:04.166432	30
260	2004-04-01	346.5	451	75	\N	2	f	\N	f	\N	dany	2004-03-25 22:46:04.172696	30
261	2004-04-01	4116.5	4000004	76	\N	2	t	\N	f	\N	dany	2004-03-25 22:48:07.277552	30
262	2004-04-01	2000	70003	76	\N	2	f	\N	f	\N	dany	2004-03-25 22:48:07.297621	30
263	2004-04-01	1650	70002	76	\N	2	f	\N	f	\N	dany	2004-03-25 22:48:07.317444	30
264	2004-04-01	120	451	76	\N	2	f	\N	f	\N	dany	2004-03-25 22:48:07.328267	30
265	2004-04-01	346.5	451	76	\N	2	f	\N	f	\N	dany	2004-03-25 22:48:07.335682	30
266	2004-04-01	4116.5	4000004	77	\N	2	t	\N	f	\N	dany	2004-03-25 22:49:56.92508	30
267	2004-04-01	2000	70003	77	\N	2	f	\N	f	\N	dany	2004-03-25 22:49:56.943611	30
268	2004-04-01	1650	70002	77	\N	2	f	\N	f	\N	dany	2004-03-25 22:49:56.958568	30
269	2004-04-01	120	451	77	\N	2	f	\N	f	\N	dany	2004-03-25 22:49:56.973752	30
270	2004-04-01	346.5	451	77	\N	2	f	\N	f	\N	dany	2004-03-25 22:49:56.980578	30
271	2004-04-01	24.2	4400002	78	\N	3	f	\N	f	\N	dany	2004-03-26 00:40:11.153843	30
274	2004-04-01	20	610001	79	\N	3	t	\N	f	\N	dany	2004-03-26 00:40:33.564503	30
275	2004-04-01	24.2	4400002	80	\N	3	f	\N	f	\N	dany	2004-03-26 00:40:43.323548	30
276	2004-04-01	20	610001	80	\N	3	t	\N	f	\N	dany	2004-03-26 00:40:43.342466	30
277	2004-04-01	24.2	4400002	81	\N	3	f	\N	f	\N	dany	2004-03-26 00:40:56.418324	30
278	2004-04-01	20	610001	81	\N	3	t	\N	f	\N	dany	2004-03-26 00:40:57.286635	30
279	2004-04-01	4.2	411	81	\N	3	f	\N	f	\N	dany	2004-03-26 00:40:57.297247	30
280	2004-04-01	0	4400002	82	\N	3	f	\N	f	\N	dany	2004-03-26 00:41:12.965944	30
281	2004-04-01	0	610001	82	\N	3	t	\N	f	\N	dany	2004-03-26 00:41:13.00877	30
282	2004-04-01	0	411	82	\N	3	f	\N	f	\N	dany	2004-03-26 00:41:13.019858	30
283	2004-04-01	380	4400002	83	\N	3	f	\N	f	\N	dany	2004-03-26 00:42:55.842062	30
284	2004-04-01	380	610002	83	\N	3	t	\N	f	\N	dany	2004-03-26 00:42:55.862305	30
285	2004-04-01	0	411	83	\N	3	f	\N	f	\N	dany	2004-03-26 00:42:55.874789	30
\.


--
-- Data for TOC entry 133 (OID 21857)
-- Name: user_pref; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY user_pref (pref_user, pref_periode) FROM stdin;
france	1
dany	30
phpcompta	1
\.


--
-- Data for TOC entry 134 (OID 21866)
-- Name: formdef; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY formdef (fr_id, fr_label) FROM stdin;
\.


--
-- Data for TOC entry 135 (OID 21872)
-- Name: form; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY form (fo_id, fo_fr_id, fo_pos, fo_label, fo_formula) FROM stdin;
\.


--
-- Data for TOC entry 136 (OID 21882)
-- Name: fichedef; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY fichedef (fd_id, fd_label, fd_class_base) FROM stdin;
1	a	a
\.


--
-- Data for TOC entry 137 (OID 21888)
-- Name: isupp_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY isupp_def (isd_id, isd_label, isd_fd_id, isd_form) FROM stdin;
1	a	1	f
2	b	1	f
\.


--
-- Data for TOC entry 138 (OID 21894)
-- Name: isupp; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY isupp (is_id, is_f_id, is_isd_id, is_value) FROM stdin;
\.


--
-- Data for TOC entry 139 (OID 21902)
-- Name: centralized; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY centralized (c_id, c_j_id, c_date, c_internal, c_montant, c_debit, c_jrn_def, c_poste, c_description, c_grp, c_comment, c_rapt, c_periode) FROM stdin;
\.


--
-- Data for TOC entry 140 (OID 21913)
-- Name: user_sec_jrn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) FROM stdin;
1	phpcompta	1	
2	phpcompta	2	
3	phpcompta	3	
4	phpcompta	4	
5	dany	4	NO
6	dany	2	NO
7	dany	1	NO
8	dany	3	NO
9	france	4	W
10	france	2	W
11	france	1	R
12	france	3	R
\.


--
-- Data for TOC entry 141 (OID 21919)
-- Name: action; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY "action" (ac_id, ac_description) FROM stdin;
1	Journaux
2	Facturation
4	Impression
5	Formulaire
6	Mise à jour Plan Comptable
7	Gestion Journaux
8	Paramètres
9	Sécurité
10	Centralise
3	Fiche Read
14	Fiche écriture
\.


--
-- Data for TOC entry 142 (OID 21924)
-- Name: user_sec_act; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY user_sec_act (ua_id, ua_login, ua_act_id) FROM stdin;
1	france	2
2	france	1
3	france	3
4	demo	10
5	demo	2
6	demo	14
7	demo	3
8	demo	5
9	demo	7
10	demo	4
11	demo	1
12	demo	6
13	demo	8
\.


--
-- Data for TOC entry 143 (OID 21932)
-- Name: jrn_action; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) FROM stdin;
2	Voir	Voir toutes les factures	user_jrn.php	action=voir_jrn	FR	VEN
4	Voir Impayés	Voir toutes les factures non payées	user_jrn.php	action=voir_jrn_non_paye	FR	VEN
6	Recherche	Recherche dans le journal	user_jrn.php	action=search	FR	VEN
1	Nouvelle	Création d'une facture	user_jrn.php	action=insert_vente&blank	FR	VEN
5	Impression	Impression du journal	impress.php	filter=1&action=viewpdf&type=jrn	FR	VEN
10	Nouveau	Encode un nouvel achat (matériel, marchandises, services et biens divers)	user_jrn.php	action=new&blank	FR	ACH
12	Voir	Voir toutes les factures	user_jrn.php	action=voir_jrn	FR	ACH
14	Voir Impayés	Voir toutes les factures non payées	user_jrn.php	action=voir_jrn_non_paye	FR	ACH
16	Impression	Impression du journal	impress.php	filter=1&action=viewpdf&type=jrn	FR	ACH
18	Recherche	Recherche dans le journal	user_jrn.php	action=search	FR	ACH
20	Nouveau	Encode un nouvel achat (matériel, marchandises, services et biens divers)	user_jrn.php	action=new&blank	FR	FIN
22	Voir	Voir toutes les factures	user_jrn.php	action=voir_jrn	FR	FIN
24	Impression	Impression du journal	impress.php	filter=1&action=viewpdf&type=jrn	FR	FIN
26	Recherche	Recherche dans le journal	user_jrn.php	action=search	FR	FIN
30	Nouveau	\N	user_jrn.php	action=new&blank	FR	OD 
32	Voir	Voir toutes les factures	user_jrn.php	action=voir_jrn	FR	OD 
34	Impression	Impression du journal	impress.php	filter=1&action=viewpdf&type=jrn	FR	OD 
36	Recherche	Recherche dans le journal	user_jrn.php	action=search	FR	OD 
\.


--
-- Data for TOC entry 144 (OID 21939)
-- Name: tva_rate; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) FROM stdin;
1	21%	0.21	Tva applicable à tout ce qui bien et service divers	411,451
3	0%	0	Tva applicable lors de vente/achat intracommunautaire	411,451
2	6%	0.06	Tva applicable aux journaux et livres	411,451
\.


--
-- Data for TOC entry 145 (OID 21955)
-- Name: fiche_def_ref; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY fiche_def_ref (frd_id, frd_text, frd_class_base) FROM stdin;
1	Vente Service	700
2	Achat Marchandises	604
3	Achat Service et biens divers	61
4	Banque	5500
5	Prêt > a un an	17
6	Prêt < a un an	430
8	Fournisseurs	440
9	Clients	400
10	Salaire Administrateur	6200
11	Salaire Ouvrier	6203
12	Salaire Employé	6202
13	Dépenses non admises	674
7	Matériel à amortir	24
14	Administration des Finances	\N
\.


--
-- Data for TOC entry 146 (OID 21961)
-- Name: fiche_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) FROM stdin;
1	5500	Banque	t	4
2	400	Client	t	9
3	700	Vente	f	1
4	440	Fournisseur	t	8
5	61	Charges	t	3
6	604	Marchandise	t	2
7	604	a	t	2
8	700	b	f	1
9	\N	Taxes & impot	f	14
\.


--
-- Data for TOC entry 147 (OID 21968)
-- Name: attr_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY attr_def (ad_id, ad_text) FROM stdin;
1	Nom
2	Taux TVA
3	Numéro de compte
4	Nom de la banque
5	Poste Comptable
6	Prix vente
7	Prix achat
8	Durée Amortissement
9	Description
10	Date début
11	Montant initial
12	Personne de contact 
13	numéro de tva 
14	Adresse 
15	code postale 
16	pays 
17	téléphone 
18	email 
19	Gestion stock
\.


--
-- Data for TOC entry 148 (OID 21974)
-- Name: attr_min; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY attr_min (frd_id, ad_id) FROM stdin;
1	1
1	2
2	1
2	2
3	1
3	2
4	1
4	3
4	4
4	12
4	13
4	14
4	15
4	16
4	17
4	18
8	1
8	12
8	13
8	14
8	15
8	16
8	17
8	18
9	1
9	12
9	13
9	14
9	15
9	16
9	17
9	18
1	6
1	7
2	6
2	7
3	7
1	19
2	19
14	1
\.


--
-- Data for TOC entry 149 (OID 21976)
-- Name: fiche; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY fiche (f_id, fd_id) FROM stdin;
1	1
2	2
3	2
4	3
5	3
6	3
7	3
8	4
9	4
10	5
11	5
12	1
13	2
14	2
15	2
\.


--
-- Data for TOC entry 150 (OID 21979)
-- Name: jnt_fic_att_value; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY jnt_fic_att_value (jft_id, f_id, ad_id) FROM stdin;
1	1	5
2	1	1
3	1	3
4	1	4
5	1	12
6	1	13
7	1	14
8	1	15
9	1	16
10	1	17
11	1	18
12	2	5
13	2	1
14	2	12
15	2	13
16	2	14
17	2	15
18	2	16
19	2	17
20	2	18
21	3	5
22	3	1
23	3	12
24	3	13
25	3	14
26	3	15
27	3	16
28	3	17
29	3	18
30	4	1
31	4	2
32	4	6
33	4	7
34	5	1
35	5	2
36	5	6
37	5	7
38	6	1
39	6	2
40	6	6
41	6	7
42	7	1
43	7	2
44	7	6
45	7	7
46	4	5
47	5	5
48	6	5
49	7	5
50	8	5
51	8	1
52	8	12
53	8	13
54	8	14
55	8	15
56	8	16
57	8	17
58	8	18
59	9	5
60	9	1
61	9	12
62	9	13
63	9	14
64	9	15
65	9	16
66	9	17
67	9	18
68	10	5
69	10	1
70	10	2
71	10	7
72	11	5
73	11	1
74	11	2
75	11	7
76	12	5
77	12	1
78	12	3
79	12	4
80	12	12
81	12	13
82	12	14
83	12	15
84	12	16
85	12	17
86	12	18
87	13	5
88	14	5
89	14	1
90	14	12
91	14	13
92	14	14
93	14	15
94	14	16
95	14	17
96	14	18
97	15	5
98	15	1
99	15	12
100	15	13
101	15	14
102	15	15
103	15	16
104	15	17
105	15	18
\.


--
-- Data for TOC entry 151 (OID 21982)
-- Name: attr_value; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY attr_value (jft_id, av_text) FROM stdin;
12	4000001
13	Client 1
14	
15	
16	Rue du Client 1
17	99999
18	Belgique
19	
20	
21	4000002
22	Client 2
23	
24	
25	Rue du client 2
26	108000
27	Belgique
28	
29	
30	Marchandise A
31	1
46	70001
32	100
33	120
34	Marchandise B
35	1
47	70002
36	150
37	140
38	Marchandise C
39	2
48	70003
40	200
41	100
42	Marchandise D
43	3
49	70004
44	150
45	75
50	4400001
51	Fournisseur A
52	
53	
54	
55	
56	
57	
58	
59	4400002
60	Fournisseur B
61	
62	
63	
64	
65	
66	
67	
68	610001
69	fourniture A
70	1
71	
76	55000002
77	Argenta
78	
79	
80	
81	
82	
83	
84	
85	
86	
11	
10	a
9	
8	
7	
6	
5	
4	
3	
2	Banque 1
1	55000001
87	4000003
88	4000004
89	Toto
90	
91	
92	Maison de toto
93	cp
94	pays
95	
96	
97	4000005
98	NOUVEAU CLIENT
99	Toto
100	
101	Adresse
102	
103	
104	
105	
73	Loyer
74	3
72	610002
75	380
\.


--
-- Data for TOC entry 152 (OID 21987)
-- Name: jnt_fic_attr; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY jnt_fic_attr (fd_id, ad_id) FROM stdin;
1	5
1	1
1	3
1	4
1	12
1	13
1	14
1	15
1	16
1	17
1	18
2	5
2	1
2	12
2	13
2	14
2	15
2	16
2	17
2	18
3	1
3	2
3	6
3	7
3	5
4	5
4	1
4	12
4	13
4	14
4	15
4	16
4	17
4	18
5	5
5	1
5	2
5	7
1	19
2	19
6	5
6	1
6	2
6	6
6	7
6	19
7	5
7	1
7	2
7	6
7	7
7	19
8	1
8	2
8	6
8	7
8	19
9	1
9	5
9	9
9	12
9	14
9	15
9	17
9	18
9	16
\.


--
-- Data for TOC entry 153 (OID 21991)
-- Name: stock_goods; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY stock_goods (sg_id, j_id, f_id, sg_quantity_deb, sg_quantity_cred) FROM stdin;
3	267	6	0	10
4	268	5	0	11
\.


--
-- Data for TOC entry 154 (OID 21996)
-- Name: test; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY test (col1, col2) FROM stdin;
1toto	2
\.


--
-- Data for TOC entry 155 (OID 22003)
-- Name: jrn_rapt; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY jrn_rapt (jra_id, jr_id, jra_concerned) FROM stdin;
2	60	63
\.


--
-- TOC entry 106 (OID 23327)
-- Name: x_act; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE UNIQUE INDEX x_act ON "action" USING btree (ac_description);


--
-- TOC entry 104 (OID 23328)
-- Name: x_usr_jrn; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE UNIQUE INDEX x_usr_jrn ON user_sec_jrn USING btree (uj_login, uj_jrn_id);


--
-- TOC entry 100 (OID 23329)
-- Name: fk_centralized_c_jrn_def; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_centralized_c_jrn_def ON centralized USING btree (c_jrn_def);


--
-- TOC entry 101 (OID 23330)
-- Name: fk_centralized_c_poste; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_centralized_c_poste ON centralized USING btree (c_poste);


--
-- TOC entry 113 (OID 23331)
-- Name: fk_fiche_def_frd_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_fiche_def_frd_id ON fiche_def USING btree (frd_id);


--
-- TOC entry 120 (OID 23332)
-- Name: fk_attr_value_jft_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_attr_value_jft_id ON attr_value USING btree (jft_id);


--
-- TOC entry 116 (OID 23333)
-- Name: fk_attr_min_frd_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_attr_min_frd_id ON attr_min USING btree (frd_id);


--
-- TOC entry 115 (OID 23334)
-- Name: fk_attr_min_ad_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_attr_min_ad_id ON attr_min USING btree (ad_id);


--
-- TOC entry 118 (OID 23335)
-- Name: fk_fiche_fd_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_fiche_fd_id ON fiche USING btree (fd_id);


--
-- TOC entry 94 (OID 23336)
-- Name: fk_form_fo_fr_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_form_fo_fr_id ON form USING btree (fo_fr_id);


--
-- TOC entry 90 (OID 23337)
-- Name: fk_jrnx_j_poste; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_jrnx_j_poste ON jrnx USING btree (j_poste);


--
-- TOC entry 89 (OID 23338)
-- Name: fk_jrn_def; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_jrn_def ON jrnx USING btree (j_jrn_def);


--
-- TOC entry 109 (OID 23339)
-- Name: fk_jrn_action_ja_jrn_type; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_jrn_action_ja_jrn_type ON jrn_action USING btree (ja_jrn_type);


--
-- TOC entry 102 (OID 23340)
-- Name: fk_user_sec_jrn; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_user_sec_jrn ON user_sec_jrn USING btree (uj_jrn_id);


--
-- TOC entry 107 (OID 23341)
-- Name: fk_user_sec_act; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_user_sec_act ON user_sec_act USING btree (ua_act_id);


--
-- TOC entry 85 (OID 23342)
-- Name: fk_jrn_jr_def_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_jrn_jr_def_id ON jrn USING btree (jr_def_id);


--
-- TOC entry 122 (OID 23343)
-- Name: fk_stock_goods_j_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_stock_goods_j_id ON stock_goods USING btree (j_id);


--
-- TOC entry 121 (OID 23344)
-- Name: fk_stock_goods_f_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_stock_goods_f_id ON stock_goods USING btree (f_id);


--
-- TOC entry 86 (OID 23345)
-- Name: idx_jr_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE UNIQUE INDEX idx_jr_id ON jrn USING btree (jr_id);


--
-- TOC entry 78 (OID 23346)
-- Name: tmp_pcmn_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY tmp_pcmn
    ADD CONSTRAINT tmp_pcmn_pkey PRIMARY KEY (pcm_val);


--
-- TOC entry 79 (OID 23348)
-- Name: parm_money_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY parm_money
    ADD CONSTRAINT parm_money_pkey PRIMARY KEY (pm_code);


--
-- TOC entry 81 (OID 23350)
-- Name: parm_periode_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY parm_periode
    ADD CONSTRAINT parm_periode_pkey PRIMARY KEY (p_id);


--
-- TOC entry 80 (OID 23352)
-- Name: parm_periode_p_start_key; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY parm_periode
    ADD CONSTRAINT parm_periode_p_start_key UNIQUE (p_start);


--
-- TOC entry 82 (OID 23354)
-- Name: jrn_type_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_type
    ADD CONSTRAINT jrn_type_pkey PRIMARY KEY (jrn_type_id);


--
-- TOC entry 84 (OID 23356)
-- Name: jrn_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT jrn_def_pkey PRIMARY KEY (jrn_def_id);


--
-- TOC entry 83 (OID 23358)
-- Name: jrn_def_jrn_def_name_key; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT jrn_def_jrn_def_name_key UNIQUE (jrn_def_name);


--
-- TOC entry 156 (OID 23360)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT "$1" FOREIGN KEY (jrn_def_type) REFERENCES jrn_type(jrn_type_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 88 (OID 23364)
-- Name: jrn_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn
    ADD CONSTRAINT jrn_pkey PRIMARY KEY (jr_id, jr_def_id);


--
-- TOC entry 91 (OID 23366)
-- Name: jrnx_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrnx
    ADD CONSTRAINT jrnx_pkey PRIMARY KEY (j_id);


--
-- TOC entry 157 (OID 23368)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrnx
    ADD CONSTRAINT "$1" FOREIGN KEY (j_poste) REFERENCES tmp_pcmn(pcm_val) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 158 (OID 23372)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrnx
    ADD CONSTRAINT "$2" FOREIGN KEY (j_jrn_def) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 92 (OID 23376)
-- Name: user_pref_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_pref
    ADD CONSTRAINT user_pref_pkey PRIMARY KEY (pref_user);


--
-- TOC entry 93 (OID 23378)
-- Name: formdef_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY formdef
    ADD CONSTRAINT formdef_pkey PRIMARY KEY (fr_id);


--
-- TOC entry 95 (OID 23380)
-- Name: form_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY form
    ADD CONSTRAINT form_pkey PRIMARY KEY (fo_id);


--
-- TOC entry 159 (OID 23382)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY form
    ADD CONSTRAINT "$1" FOREIGN KEY (fo_fr_id) REFERENCES formdef(fr_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 96 (OID 23386)
-- Name: fichedef_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fichedef
    ADD CONSTRAINT fichedef_pkey PRIMARY KEY (fd_id);


--
-- TOC entry 97 (OID 23388)
-- Name: isupp_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY isupp_def
    ADD CONSTRAINT isupp_def_pkey PRIMARY KEY (isd_id);


--
-- TOC entry 160 (OID 23390)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY isupp_def
    ADD CONSTRAINT "$1" FOREIGN KEY (isd_fd_id) REFERENCES fichedef(fd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 98 (OID 23394)
-- Name: isupp_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY isupp
    ADD CONSTRAINT isupp_pkey PRIMARY KEY (is_id);


--
-- TOC entry 161 (OID 23396)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY isupp
    ADD CONSTRAINT "$1" FOREIGN KEY (is_f_id) REFERENCES tmp_pcmn(pcm_val) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 162 (OID 23400)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY isupp
    ADD CONSTRAINT "$2" FOREIGN KEY (is_isd_id) REFERENCES isupp_def(isd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 99 (OID 23404)
-- Name: centralized_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY centralized
    ADD CONSTRAINT centralized_pkey PRIMARY KEY (c_id);


--
-- TOC entry 163 (OID 23406)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY centralized
    ADD CONSTRAINT "$1" FOREIGN KEY (c_jrn_def) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 164 (OID 23410)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY centralized
    ADD CONSTRAINT "$2" FOREIGN KEY (c_poste) REFERENCES tmp_pcmn(pcm_val) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 103 (OID 23414)
-- Name: user_sec_jrn_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_jrn
    ADD CONSTRAINT user_sec_jrn_pkey PRIMARY KEY (uj_id);


--
-- TOC entry 165 (OID 23416)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_jrn
    ADD CONSTRAINT "$1" FOREIGN KEY (uj_jrn_id) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 105 (OID 23420)
-- Name: action_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY "action"
    ADD CONSTRAINT action_pkey PRIMARY KEY (ac_id);


--
-- TOC entry 108 (OID 23422)
-- Name: user_sec_act_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_act
    ADD CONSTRAINT user_sec_act_pkey PRIMARY KEY (ua_id);


--
-- TOC entry 166 (OID 23424)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_act
    ADD CONSTRAINT "$1" FOREIGN KEY (ua_act_id) REFERENCES "action"(ac_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 110 (OID 23428)
-- Name: jrn_action_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_action
    ADD CONSTRAINT jrn_action_pkey PRIMARY KEY (ja_id);


--
-- TOC entry 167 (OID 23430)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_action
    ADD CONSTRAINT "$1" FOREIGN KEY (ja_jrn_type) REFERENCES jrn_type(jrn_type_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 111 (OID 23434)
-- Name: fiche_def_ref_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche_def_ref
    ADD CONSTRAINT fiche_def_ref_pkey PRIMARY KEY (frd_id);


--
-- TOC entry 112 (OID 23436)
-- Name: fiche_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche_def
    ADD CONSTRAINT fiche_def_pkey PRIMARY KEY (fd_id);


--
-- TOC entry 168 (OID 23438)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche_def
    ADD CONSTRAINT "$1" FOREIGN KEY (frd_id) REFERENCES fiche_def_ref(frd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 114 (OID 23442)
-- Name: attr_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_def
    ADD CONSTRAINT attr_def_pkey PRIMARY KEY (ad_id);


--
-- TOC entry 169 (OID 23444)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_min
    ADD CONSTRAINT "$1" FOREIGN KEY (frd_id) REFERENCES fiche_def_ref(frd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 170 (OID 23448)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_min
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 117 (OID 23452)
-- Name: fiche_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche
    ADD CONSTRAINT fiche_pkey PRIMARY KEY (f_id);


--
-- TOC entry 171 (OID 23454)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche
    ADD CONSTRAINT "$1" FOREIGN KEY (fd_id) REFERENCES fiche_def(fd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 119 (OID 23458)
-- Name: jnt_fic_att_value_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT jnt_fic_att_value_pkey PRIMARY KEY (jft_id);


--
-- TOC entry 172 (OID 23460)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT "$1" FOREIGN KEY (f_id) REFERENCES fiche(f_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 173 (OID 23464)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 174 (OID 23468)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_value
    ADD CONSTRAINT "$1" FOREIGN KEY (jft_id) REFERENCES jnt_fic_att_value(jft_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 175 (OID 23472)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_attr
    ADD CONSTRAINT "$1" FOREIGN KEY (fd_id) REFERENCES fiche_def(fd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 176 (OID 23476)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_attr
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 123 (OID 23480)
-- Name: stock_goods_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY stock_goods
    ADD CONSTRAINT stock_goods_pkey PRIMARY KEY (sg_id);


--
-- TOC entry 177 (OID 23482)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY stock_goods
    ADD CONSTRAINT "$1" FOREIGN KEY (j_id) REFERENCES jrnx(j_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 178 (OID 23486)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY stock_goods
    ADD CONSTRAINT "$2" FOREIGN KEY (f_id) REFERENCES fiche(f_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 124 (OID 23490)
-- Name: jrn_rapt_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_rapt
    ADD CONSTRAINT jrn_rapt_pkey PRIMARY KEY (jra_id);


--
-- TOC entry 87 (OID 23492)
-- Name: jrn_jr_id_key; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn
    ADD CONSTRAINT jrn_jr_id_key UNIQUE (jr_id);


--
-- TOC entry 179 (OID 23494)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_rapt
    ADD CONSTRAINT "$1" FOREIGN KEY (jr_id) REFERENCES jrn(jr_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 180 (OID 23498)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_rapt
    ADD CONSTRAINT "$2" FOREIGN KEY (jra_concerned) REFERENCES jrn(jr_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 3 (OID 21800)
-- Name: s_periode; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_periode', 39, true);


--
-- TOC entry 5 (OID 21802)
-- Name: s_currency; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_currency', 1, true);


--
-- TOC entry 7 (OID 21815)
-- Name: s_jrn_def; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrn_def', 5, false);


--
-- TOC entry 9 (OID 21817)
-- Name: s_grpt; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_grpt', 1, false);


--
-- TOC entry 11 (OID 21819)
-- Name: s_jrn_op; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrn_op', 286, true);


--
-- TOC entry 13 (OID 21821)
-- Name: s_jrn; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrn', 79, true);


--
-- TOC entry 15 (OID 21837)
-- Name: s_jrnx; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrnx', 1, false);


--
-- TOC entry 17 (OID 21862)
-- Name: s_formdef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_formdef', 1, false);


--
-- TOC entry 19 (OID 21864)
-- Name: s_form; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_form', 1, false);


--
-- TOC entry 21 (OID 21878)
-- Name: s_isup; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_isup', 1, false);


--
-- TOC entry 23 (OID 21880)
-- Name: s_idef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_idef', 2, true);


--
-- TOC entry 25 (OID 21900)
-- Name: s_centralized; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_centralized', 1, false);


--
-- TOC entry 27 (OID 21909)
-- Name: s_user_jrn; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_user_jrn', 12, true);


--
-- TOC entry 29 (OID 21911)
-- Name: s_user_act; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_user_act', 13, true);


--
-- TOC entry 31 (OID 21930)
-- Name: s_jrnaction; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrnaction', 7, true);


--
-- TOC entry 33 (OID 21945)
-- Name: s_fiche; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_fiche', 15, true);


--
-- TOC entry 35 (OID 21947)
-- Name: s_fiche_def_ref; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_fiche_def_ref', 14, true);


--
-- TOC entry 37 (OID 21949)
-- Name: s_fdef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_fdef', 9, true);


--
-- TOC entry 39 (OID 21951)
-- Name: s_attr_def; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_attr_def', 19, true);


--
-- TOC entry 41 (OID 21953)
-- Name: s_jnt_fic_att_value; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jnt_fic_att_value', 105, true);


--
-- TOC entry 43 (OID 21989)
-- Name: s_stock_goods; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_stock_goods', 4, true);


--
-- TOC entry 45 (OID 22001)
-- Name: s_jrn_rapt; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrn_rapt', 2, true);


