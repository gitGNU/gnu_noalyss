--
-- PostgreSQL database dump
--

\connect - phpcompta

SET search_path = public, pg_catalog;

--
-- TOC entry 42 (OID 20867)
-- Name: tmp_pcmn; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE tmp_pcmn (
    pcm_val integer NOT NULL,
    pcm_lib text,
    pcm_val_parent integer DEFAULT 0,
    pcm_country character(2) DEFAULT 'BE' NOT NULL
);


--
-- TOC entry 43 (OID 20874)
-- Name: version; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE "version" (
    val integer
);


--
-- TOC entry 2 (OID 20876)
-- Name: s_periode; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_periode
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 4 (OID 20878)
-- Name: s_currency; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_currency
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 44 (OID 20880)
-- Name: parm_money; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE parm_money (
    pm_id integer DEFAULT nextval('s_currency'::text),
    pm_code character(3) NOT NULL,
    pm_rate double precision
);


--
-- TOC entry 45 (OID 20883)
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
-- TOC entry 6 (OID 20891)
-- Name: s_jrn_def; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_def
    START 5
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 8 (OID 20893)
-- Name: s_grpt; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_grpt
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 10 (OID 20895)
-- Name: s_jrn_op; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_op
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 12 (OID 20897)
-- Name: s_jrn; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 46 (OID 20899)
-- Name: jrn_type; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jrn_type (
    jrn_type_id character(3) NOT NULL,
    jrn_desc text
);


--
-- TOC entry 47 (OID 20904)
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
-- TOC entry 14 (OID 20913)
-- Name: s_jrnx; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrnx
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 48 (OID 20915)
-- Name: jrn; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jrn (
    jr_id integer DEFAULT nextval('s_jrn'::text) NOT NULL,
    jr_def_id integer NOT NULL,
    jr_montant double precision NOT NULL,
    jr_comment text,
    jr_date date,
    jr_grpt_id integer NOT NULL,
    jr_rapt integer,
    jr_internal text,
    jr_tech_date timestamp without time zone DEFAULT now() NOT NULL,
    jr_tech_per integer NOT NULL,
    jrn_ech date,
    jr_ech date
);


--
-- TOC entry 49 (OID 20922)
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
-- TOC entry 50 (OID 20933)
-- Name: user_pref; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE user_pref (
    pref_user text NOT NULL,
    pref_periode integer NOT NULL
);


--
-- TOC entry 16 (OID 20938)
-- Name: s_formdef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_formdef
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 18 (OID 20940)
-- Name: s_form; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_form
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 51 (OID 20942)
-- Name: formdef; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE formdef (
    fr_id integer DEFAULT nextval('s_formdef'::text) NOT NULL,
    fr_label text
);


--
-- TOC entry 52 (OID 20948)
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
-- TOC entry 20 (OID 20954)
-- Name: s_isup; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_isup
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 22 (OID 20956)
-- Name: s_idef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_idef
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 24 (OID 20958)
-- Name: s_centralized; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_centralized
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 53 (OID 20960)
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
-- TOC entry 26 (OID 20967)
-- Name: s_user_jrn; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_user_jrn
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 28 (OID 20969)
-- Name: s_user_act; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_user_act
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 54 (OID 20971)
-- Name: user_sec_jrn; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE user_sec_jrn (
    uj_id integer DEFAULT nextval('s_user_jrn'::text) NOT NULL,
    uj_login text,
    uj_jrn_id integer,
    uj_priv text
);


--
-- TOC entry 55 (OID 20977)
-- Name: action; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE "action" (
    ac_id integer NOT NULL,
    ac_description text NOT NULL
);


--
-- TOC entry 56 (OID 20982)
-- Name: user_sec_act; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE user_sec_act (
    ua_id integer DEFAULT nextval('s_user_act'::text) NOT NULL,
    ua_login text,
    ua_act_id integer
);


--
-- TOC entry 30 (OID 20988)
-- Name: s_jrnaction; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrnaction
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 57 (OID 20990)
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
-- TOC entry 58 (OID 20997)
-- Name: fiche_ref_def; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE fiche_ref_def (
    frd_id integer,
    frd_text text
);


--
-- TOC entry 59 (OID 21002)
-- Name: attr_valeur; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_valeur (
    f_id integer,
    ad_id integer,
    av_text text
);


--
-- TOC entry 60 (OID 21007)
-- Name: jnt_fic_def_att_def; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jnt_fic_def_att_def (
    fd_id integer,
    ad_id integer
);


--
-- TOC entry 61 (OID 21009)
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
-- TOC entry 32 (OID 21015)
-- Name: s_fiche; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fiche
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 34 (OID 21017)
-- Name: s_fiche_def_ref; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fiche_def_ref
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 36 (OID 21019)
-- Name: s_fdef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fdef
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 38 (OID 21021)
-- Name: s_attr_def; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_attr_def
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 40 (OID 21023)
-- Name: s_jnt_fic_att_value; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jnt_fic_att_value
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 62 (OID 21025)
-- Name: fiche_def_ref; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE fiche_def_ref (
    frd_id integer DEFAULT nextval('s_fiche_def_ref'::text) NOT NULL,
    frd_text text,
    frd_class_base integer
);


--
-- TOC entry 63 (OID 21031)
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
-- TOC entry 64 (OID 21038)
-- Name: attr_def; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_def (
    ad_id integer DEFAULT nextval('s_attr_def'::text) NOT NULL,
    ad_text text
);


--
-- TOC entry 65 (OID 21044)
-- Name: attr_min; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_min (
    frd_id integer,
    ad_id integer
);


--
-- TOC entry 66 (OID 21046)
-- Name: fiche; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE fiche (
    f_id integer DEFAULT nextval('s_fiche'::text) NOT NULL,
    fd_id integer
);


--
-- TOC entry 67 (OID 21049)
-- Name: jnt_fic_att_value; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jnt_fic_att_value (
    jft_id integer DEFAULT nextval('s_jnt_fic_att_value'::text) NOT NULL,
    f_id integer,
    ad_id integer
);


--
-- TOC entry 68 (OID 21052)
-- Name: attr_value; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_value (
    jft_id integer,
    av_text text
);


--
-- TOC entry 69 (OID 21057)
-- Name: jnt_fic_attr; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jnt_fic_attr (
    fd_id integer,
    ad_id integer
);


--
-- Data for TOC entry 94 (OID 20867)
-- Name: tmp_pcmn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY tmp_pcmn (pcm_val, pcm_lib, pcm_val_parent, pcm_country) FROM stdin;
10	Capital 	1	BE
100	 Capital souscrit	10	BE
11	Prime d'�mission 	1	BE
12	Plus Value de r��valuation 	1	BE
13	R�serve 	1	BE
130	R�serve l�gale	13	BE
131	R�serve indisponible	13	BE
1310	R�serve pour actions propres	131	BE
1311	 Autres r�serves indisponibles	131	BE
132	 R�serves immunis�es	13	BE
133	R�serves disponibles	13	BE
14	B�n�fice ou perte report�e	1	BE
140	B�n�fice report�	14	BE
141	Perte report�e	14	BE
15	Subside en capital	1	BE
16	Provisions pour risques et charges	1	BE
160	Provisions pour pensions et obligations similaires	16	BE
161	Provisions pour charges fiscales	16	BE
162	Provisions pour grosses r�paration et gros entretien	16	BE
17	 Dettes � plus d'un an	1	BE
170	Emprunts subordonn�s	17	BE
1700	convertibles	170	BE
1701	non convertibles	170	BE
171	Emprunts subordonn�s	17	BE
1710	convertibles	170	BE
1711	non convertibles	170	BE
172	 Dettes de locations financement	17	BE
173	 Etablissement de cr�dit	17	BE
1730	Dettes en comptes	173	BE
1731	Promesses	173	BE
1732	Cr�dits d'acceptation	173	BE
174	Autres emprunts	17	BE
175	Dettes commerciales	17	BE
1750	Fournisseurs	175	BE
1751	Effets � payer	175	BE
176	Acomptes re�us sur commandes	17	BE
178	Cautionnement re�us en num�raires	17	BE
179	Dettes diverses	17	BE
20	Frais d'�tablissement	2	BE
200	Frais de constitution et d'augmentation de capital	20	BE
201	 Frais d'�mission d'emprunts et primes de remboursement	20	BE
202	Autres frais d'�tablissement	20	BE
204	Frais de restructuration	20	BE
21	Immobilisations incorporelles	2	BE
210	Frais de recherche et de d�veloppement	21	BE
211	Concessions, brevet, licence savoir faire, marque et droit similaires	21	BE
212	Goodwill	21	BE
213	Acomptes vers�s	21	BE
22	Terrains et construction	2	BE
220	Terrains	22	BE
221	Construction	22	BE
222	Terrains b�tis	22	BE
223	Autres droits r�els sur des immeubles	22	BE
23	 Installations, machines et outillages	2	BE
24	Mobilier et Mat�riel roulant	2	BE
25	Immobilisations d�tenus en location-financement et droits similaires	2	BE
250	Terrains	25	BE
251	Construction	25	BE
252	Terrains b�tis	25	BE
253	Mobilier et mat�riels roulants	25	BE
26	Autres immobilisations corporelles	2	BE
27	Immobilisations corporelles en cours et acomptes vers�s	2	BE
28	Immobilisations financi�res	2	BE
280	Participation dans des entreprises li�es	28	BE
2800	Valeur d'acquisition	280	BE
2801	Montants non-appel�s(-)	280	BE
2808	Plus-values act�es	280	BE
2809	R�ductions de valeurs act�es	280	BE
281	Cr�ance sur  des entreprises li�es	28	BE
2810	Cr�ance en compte	281	BE
2811	Effets � recevoir	281	BE
2812	Titre � reveny fixe	281	BE
2817	Cr�ances douteuses	281	BE
2819	R�duction de valeurs act�es	281	BE
282	Participations dans des entreprises avec lesquelles il existe un lien de participation	28	BE
2820	Valeur d'acquisition	282	BE
2821	Montants non-appel�s(-)	282	BE
2828	Plus-values act�es	282	BE
2829	R�ductions de valeurs act�es	282	BE
283	Cr�ances sur des entreprises avec lesquelles existe un lien de participation	28	BE
2830	Cr�ance en compte	283	BE
2831	Effets � recevoir	283	BE
2832	Titre � revenu fixe	283	BE
2837	Cr�ances douteuses	283	BE
2839	R�duction de valeurs act�es	283	BE
284	Autres actions et parts	28	BE
2840	Valeur d'acquisition	284	BE
2841	Montants non-appel�s(-)	284	BE
2848	Plus-values act�es	284	BE
2849	R�ductions de valeurs act�es	284	BE
285	Autres cr�ances	28	BE
2850	Cr�ance en compte	285	BE
2851	Effets � recevoir	285	BE
2852	Titre � revenu fixe	285	BE
2857	Cr�ances douteuses	285	BE
2859	R�ductions de valeurs act�es	285	BE
288	Cautionnements vers�s en num�raires	28	BE
29	Cr�ances � plus d'un an	2	BE
290	Cr�ances commerciales	29	BE
2900	Clients	290	BE
2901	Effets � recevoir	290	BE
2906	Acomptes vers�s	290	BE
2907	Cr�ances douteuses	290	BE
2909	R�ductions de valeurs act�es	290	BE
291	Autres cr�ances	29	BE
2910	Cr�ances en comptes	291	BE
2911	Effets � recevoir	291	BE
2917	Cr�ances douteuses	291	BE
2919	R�ductions de valeurs act�es(-)	291	BE
30	Approvisionements - Mati�res premi�res	3	BE
300	Valeur d'acquisition	30	BE
309	R�ductions de valeur act�es	30	BE
31	Approvisionnements - fournitures	3	BE
310	Valeur d'acquisition	31	BE
319	R�ductions de valeurs act�es(-)	31	BE
32	En-cours de fabrication	3	BE
320	Valeurs d'acquisition	32	BE
329	R�ductions de valeur act�es	32	BE
33	Produits finis	3	BE
330	Valeur d'acquisition	33	BE
339	R�ductions de valeur act�es	33	BE
34	Marchandises	3	BE
340	Valeur d'acquisition	34	BE
349	R�ductions de valeur act�es	34	BE
35	Immeubles destin�s � la vente	3	BE
350	Valeur d'acquisition	35	BE
359	R�ductions de valeur act�es	35	BE
36	Acomptes vers�s sur achats pour stocks	3	BE
360	Valeur d'acquisition	36	BE
369	R�ductions de valeur act�es	36	BE
37	Commandes en cours �x�cution	3	BE
370	Valeur d'acquisition	37	BE
371	B�n�fice pris en compte 	37	BE
379	R�ductions de valeur act�es	37	BE
40	Cr�ances commerciales	4	BE
400	Clients	40	BE
401	Effets � recevoir	40	BE
404	Produits � recevoir	40	BE
406	Acomptes vers�s	40	BE
407	Cr�ances douteuses	40	BE
409	R�ductions de valeur act�es	40	BE
41	Autres cr�ances	4	BE
410	Capital appel� non vers�	41	BE
411	TVA � r�cup�rer	41	BE
412	Imp�ts et pr�comptes � r�cup�rer	41	BE
4120	Imp�t belge sur le r�sultat	412	BE
4121	Imp�t belge sur le r�sultat	412	BE
4122	Imp�t belge sur le r�sultat	412	BE
4123	Imp�t belge sur le r�sultat	412	BE
4124	Imp�t belge sur le r�sultat	412	BE
4125	Autres imp�ts et taxes belges	412	BE
4126	Autres imp�ts et taxes belges	412	BE
4127	Autres imp�ts et taxes belges	412	BE
4128	Imp�ts et taxes �trangers	412	BE
414	Produits � recevoir	41	BE
416	Cr�ances diverses	41	BE
4160	Comptes de l'exploitant	416	BE
417	Cr�ances douteuses	41	BE
418	Cautionnements vers�s en num�raires	41	BE
419	R�ductions de valeur act�es	41	BE
42	Dettes � plus dun an �ch�ant dans l'ann�e	4	BE
420	Emprunts subordonn�s	42	BE
4200	convertibles	420	BE
4201	non convertibles	420	BE
421	Emprunts subordonn�s	42	BE
4210	convertibles	420	BE
4211	non convertibles	420	BE
422	 Dettes de locations financement	42	BE
423	 Etablissement de cr�dit	42	BE
4230	Dettes en comptes	423	BE
4231	Promesses	423	BE
4232	Cr�dits d'acceptation	423	BE
424	Autres emprunts	42	BE
425	Dettes commerciales	42	BE
4250	Fournisseurs	425	BE
4251	Effets � payer	425	BE
426	Acomptes re�us sur commandes	42	BE
428	Cautionnement re�us en num�raires	42	BE
429	Dettes diverses	42	BE
43	Dettes financi�res	4	BE
430	Etablissements de cr�dit - Emprunts � compte � terme fixe	43	BE
431	Etablissements de cr�dit - Promesses	43	BE
432	 Etablissements de cr�dit - Cr�dits d'acceptation	43	BE
433	Etablissements de cr�dit -Dettes en comptes courant	43	BE
439	Autres emprunts	43	BE
44	Dettes commerciales	4	BE
440	Fournisseurs	44	BE
441	Effets � payer	44	BE
444	Factures � recevoir	44	BE
45	Dettes fiscales, salariales et sociales	4	BE
450	Dettes fiscales estim�es	45	BE
4500	Imp�ts belges sur le r�sultat	450	BE
4501	Imp�ts belges sur le r�sultat	450	BE
4502	Imp�ts belges sur le r�sultat	450	BE
4503	Imp�ts belges sur le r�sultat	450	BE
4504	Imp�ts belges sur le r�sultat	450	BE
4505	Autres imp�ts et taxes belges	450	BE
4506	Autres imp�ts et taxes belges	450	BE
4507	Autres imp�ts et taxes belges	450	BE
4508	Imp�ts et taxes �trangers	450	BE
451	TVA � payer	45	BE
452	Imp�ts et taxes � payer	45	BE
4520	Imp�ts belges sur le r�sultat	452	BE
4521	Imp�ts belges sur le r�sultat	452	BE
4522	Imp�ts belges sur le r�sultat	452	BE
4523	Imp�ts belges sur le r�sultat	452	BE
4524	Imp�ts belges sur le r�sultat	452	BE
4525	Autres imp�ts et taxes belges	452	BE
4526	Autres imp�ts et taxes belges	452	BE
4527	Autres imp�ts et taxes belges	452	BE
4528	Imp�ts et taxes �trangers	452	BE
453	Pr�comptes retenus	45	BE
454	Office National de la S�curit� Sociales	45	BE
455	R�mun�rations	45	BE
456	P�cules de vacances	45	BE
459	Autres dettes sociales	45	BE
46	Acomptes re�us sur commandes	4	BE
47	Dettes d�coulant de l'affectation du r�sultat	4	BE
470	Dividendes et tanti�mes d'exercices ant�rieurs	47	BE
471	Dividendes de l'exercice	47	BE
472	Tanti�mes de l'exercice	47	BE
473	Autres allocataires	47	BE
48	Dettes diverses	4	BE
480	Obligations et coupons �chus	48	BE
488	Cautionnements re�us en num�raires	48	BE
489	Autres dettes diverses	48	BE
4890	Compte de l'exploitant	489	BE
49	Comptes de r�gularisation	4	BE
490	Charges � reporter	49	BE
491	Produits acquis	49	BE
492	Charges � imputer	49	BE
493	Produits � reporter	49	BE
499	Comptes d'attentes	49	BE
50	Actions propres	5	BE
51	Actions et parts	5	BE
510	Valeur d'acquisition	51	BE
511	Montant non appel�s	51	BE
519	R�ductions de valeur act�es	51	BE
52	Titres � revenu fixe	5	BE
520	Valeur d'acquisition	52	BE
529	R�ductions de valeur act�es	52	BE
53	D�p�ts � terme	5	BE
530	de plus d'un an	53	BE
531	de plus d'un mois et d'un an au plus	53	BE
532	d'un mois au plus	53	BE
539	R�ductions de valeur act�es	53	BE
54	Valeurs �chues � l'encaissement	5	BE
55	Etablissement de cr�dit	5	BE
550	Banque 1	55	BE
5500	Comptes courants	550	BE
5501	Ch�ques �mis (-)	550	BE
5509	R�duction de valeur act�e	550	BE
5510	Comptes courants	551	BE
5511	Ch�ques �mis (-)	551	BE
5519	R�duction de valeur act�e	551	BE
5520	Comptes courants	552	BE
5521	Ch�ques �mis (-)	552	BE
5529	R�duction de valeur act�e	552	BE
5530	Comptes courants	553	BE
5531	Ch�ques �mis (-)	553	BE
5539	R�duction de valeur act�e	553	BE
5540	Comptes courants	554	BE
5541	Ch�ques �mis (-)	554	BE
5549	R�duction de valeur act�e	554	BE
5550	Comptes courants	555	BE
5551	Ch�ques �mis (-)	555	BE
5559	R�duction de valeur act�e	555	BE
5560	Comptes courants	556	BE
5561	Ch�ques �mis (-)	556	BE
5569	R�duction de valeur act�e	556	BE
5570	Comptes courants	557	BE
5571	Ch�ques �mis (-)	557	BE
5579	R�duction de valeur act�e	557	BE
5580	Comptes courants	558	BE
5581	Ch�ques �mis (-)	558	BE
5589	R�duction de valeur act�e	558	BE
5590	Comptes courants	559	BE
5591	Ch�ques �mis (-)	559	BE
5599	R�duction de valeur act�e	559	BE
56	Office des ch�ques postaux	5	BE
560	Compte courant	56	BE
561	Ch�ques �mis	56	BE
57	Caisses	5	BE
578	Caisse timbre	57	BE
58	Virement interne	5	BE
60	Approvisionnement et marchandises	6	BE
600	Achats de mati�res premi�res	60	BE
601	Achats de fournitures	60	BE
602	Achats de services, travaux et �tudes	60	BE
603	Sous-traitances g�n�rales	60	BE
604	Achats de marchandises	60	BE
605	Achats d'immeubles destin�s � la vente	60	BE
608	Remises, ristournes et rabais obtenus(-)	60	BE
609	Variation de stock	60	BE
6090	de mati�res premi�res	609	BE
6091	de fournitures	609	BE
6094	de marchandises	609	BE
6095	immeubles achet�s destin�s � la vente	609	BE
61	Services et biens divers	6	BE
62	R�mun�rations, charges sociales et pensions	6	BE
620	R�mun�rations et avantages sociaux directs	62	BE
6200	Administrateurs ou g�rants	620	BE
6201	Personnel de directions	620	BE
6202	Employ�s,620	6202	BE
6203	Ouvriers	620	BE
6204	Autres membres du personnel	620	BE
621	Cotisations patronales d'assurances sociales	62	BE
622	Primes partonales pour assurances extra-l�gales	62	BE
623	Autres frais de personnel	62	BE
624	Pensions de retraite et de survie	62	BE
6240	Administrateurs ou g�rants	624	BE
6241	Personnel	624	BE
63	Amortissements, r�ductions de valeurs et provisions pour risques et charges	6	BE
630	Dotations aux amortissements et r�duction de valeurs sur immobilisations	63	BE
6300	 Dotations aux amortissements sur frais d'�tablissement	630	BE
705	Ventes et prestations de services	70	BE
6301	Dotations aux amortissements sur immobilisations incorporelles	630	BE
6302	Dotations aux amortissements sur immobilisations corporelles	630	BE
6308	Dotations aux r�ductions de valeur sur immobilisations incorporelles	630	BE
6309	Dotations aux r�ductions de valeur sur immobilisations corporelles	630	BE
631	R�ductions de valeur sur stocks	63	BE
6310	Dotations	631	BE
6311	Reprises(-)	631	BE
632	R�ductions de valeur sur commande en cours d'�x�cution	63	BE
6320	Dotations	632	BE
6321	Reprises(-)	632	BE
633	R�ductions de valeurs sur cr�ances commerciales � plus d'un an	63	BE
6330	Dotations	633	BE
6331	Reprises(-)	633	BE
634	R�ductions de valeur sur cr�ances commerciales � un an au plus	63	BE
6340	Dotations	634	BE
6341	Reprise	634	BE
635	Provisions pour pensions et obligations similaires	63	BE
6350	Dotations	635	BE
6351	Utilisation et reprises	635	BE
636	Provisions pour grosses r�parations et gros entretien	63	BE
6360	Dotations	636	BE
6361	Reprises(-)	636	BE
637	Provisions pour autres risques et charges	63	BE
6370	Dotations	637	BE
6371	Reprises(-)	637	BE
64	Autres charges d'exploitation	6	BE
640	Charges fiscales d'exploitation	64	BE
641	Moins-values sur r�alisations courantes d'immobilisations corporelles	64	BE
642	Moins-value sur r�alisation de cr�ances commerciales	64	BE
643	Charges d'exploitations	64	BE
644	Charges d'exploitations	64	BE
645	Charges d'exploitations	64	BE
646	Charges d'exploitations	64	BE
647	Charges d'exploitations	64	BE
648	Charges d'exploitations	64	BE
649	Charges d'exploitation port�es � l'actif au titre de frais de restructuration(-)	64	BE
65	Charges financi�res	6	BE
650	Charges des dettes	65	BE
6500	Int�r�ts, commmissions et frais aff�rents aux dettes	650	BE
6501	Amortissements des frais d'�missions d'emrunts et des primes de remboursement	650	BE
6502	Autres charges des dettes	650	BE
6503	Int�r�ts intercalaires port�s � l'actif(-)	650	BE
651	R�ductions de valeur sur actifs circulants	65	BE
6510	Dotations	651	BE
6511	Reprises(-)	651	BE
652	Moins-value sur r�alisation d'actifs circulants	65	BE
653	Charges d'escompte de cr�ances	65	BE
654	Diff�rences de changes	65	BE
655	Ecarts de conversion des devises	65	BE
656	Charges financi�res diverses	65	BE
657	Charges financi�res diverses	65	BE
658	Charges financi�res diverses	65	BE
659	Charges financi�res diverses	65	BE
66	Charges exceptionnelles	6	BE
660	Amortissements et r�ductions de valeur exceptionnels (dotations)	66	BE
6600	sur frais d'�tablissement	660	BE
6601	sur immobilisations incorporelles	660	BE
6602	sur immobilisations corporelles	660	BE
661	R�ductions de valeur sur immobilisations financi�res (dotations)	66	BE
662	Provisions pour risques et charges exceptionnels	66	BE
663	Moins-values sur r�alisations d'actifs immobilis�s	66	BE
664	Autres charges exceptionnelles	66	BE
665	Autres charges exceptionnelles	66	BE
666	Autres charges exceptionnelles	66	BE
667	Autres charges exceptionnelles	66	BE
668	Autres charges exceptionnelles	66	BE
669	 Charges exceptionnelles port�es � l'actif au titre de frais de restructuration	66	BE
67	imp�ts sur le r�sultat	6	BE
670	Imp�ts belge sur le r�sultat de l'exercice	67	BE
6700	Imp�ts et pr�comptes dus ou vers�s	670	BE
6701	Exc�dents de versement d'imp�ts et de pr�comptes port�s � l'actifs (-)	670	BE
6702	Charges fiscales estim�es	670	BE
671	Imp�ts belges sur le r�sultats d'exercices ant�rieures	67	BE
6710	Suppl�ments d'imp�t dus ou vers�s	671	BE
6711	Suppl�ments d'imp�ts estim�s	671	BE
6712	Provisions fiscales constitu�es	671	BE
672	Imp�ts �trangers sur le r�sultat de l'exercice	67	BE
673	Imp�ts �trangers sur le r�sultat d'exercice ant�rieures	67	BE
68	Transferts aux r�serves immunis�es	6	BE
69	Affectations et pr�l�vements	6	BE
690	Perte report�e de l'exercice pr�c�dent	69	BE
691	Dotation � la r�serve l�gale	69	BE
692	Dotation aux autres r�serves	69	BE
693	B�n�fice � reporter	69	BE
694	R�mun�ration du capital	69	BE
695	Administrateurs ou g�rants	69	BE
696	Autres allocataires	69	BE
70	Chiffre d'affaire	7	BE
700	Ventes et prestations de services	70	BE
701	Ventes et prestations de services	70	BE
702	Ventes et prestations de services	70	BE
703	Ventes et prestations de services	70	BE
704	Ventes et prestations de services	70	BE
706	Ventes et prestations de services	70	BE
707	Ventes et prestations de services	70	BE
709	Remises, ristournes et rabais accord�s(-)	70	BE
71	Variations des stocks et commandes en cours d'�x�cution	7	BE
712	des en-cours de fabrication	71	BE
713	des produits finis	71	BE
715	des immeubles construits destin�s � la vente	71	BE
717	 des commandes  en cours d'�x�cution	71	BE
7170	Valeur d'acquisition	717	BE
7171	B�n�fice pris en compte	717	BE
72	Production immobilis�e	7	BE
74	Autres produits d'exploitation	7	BE
740	Subsides d' exploitation  et montants compensatoires	74	BE
741	Plus-values sur r�alisation courantes d' immobilisations corporelles	74	BE
742	Plus-values sur r�alisations de cr�ances commerciales	74	BE
743	Produits d'exploitations divers	74	BE
744	Produits d'exploitations divers	74	BE
745	Produits d'exploitations divers	74	BE
746	Produits d'exploitations divers	74	BE
747	Produits d'exploitations divers	74	BE
748	Produits d'exploitations divers	74	BE
749	Produits d'exploitations divers	74	BE
75	Produits financiers	7	BE
750	Produits sur immobilisations financi�res	75	BE
751	Produits des actifs circulants	75	BE
752	Plus-value sur r�alisations d'actis circulants	75	BE
753	Subsides en capital et int�r�ts	75	BE
754	Diff�rences de change	75	BE
755	Ecarts de conversion des devises	75	BE
756	Produits financiers divers	75	BE
757	Produits financiers divers	75	BE
758	Produits financiers divers	75	BE
759	Produits financiers divers	75	BE
76	Produits exceptionnels	7	BE
760	Reprise d'amortissements et de r�ductions de valeur	76	BE
7601	sur immobilisations corporelles	760	BE
7602	sur immobilisations incorporelles	760	BE
761	Reprises de r�ductions de valeur sur immobilisations financi�res	76	BE
762	Reprises de provisions pour risques et charges exceptionnels	76	BE
763	Plus-value sur r�alisation d'actifs immobilis�	76	BE
764	Autres produits exceptionnels	76	BE
765	Autres produits exceptionnels	76	BE
766	Autres produits exceptionnels	76	BE
767	Autres produits exceptionnels	76	BE
768	Autres produits exceptionnels	76	BE
769	Autres produits exceptionnels	76	BE
77	R�gularisations d'imp�ts et reprises de provisions fiscales	7	BE
771	imp�ts belges sur le r�sultat	77	BE
7710	R�gularisations d'imp�ts dus ou vers�	771	BE
7711	R�gularisations d'imp�ts estim�s	771	BE
7712	Reprises de provisions fiscales	771	BE
773	Imp�ts �trangers sur le r�sultats	77	BE
79	Affectations et pr�l�vements	7	BE
790	B�n�fice report� de l'exercice pr�c�dent	79	BE
791	Pr�l�vement sur le capital et les primes d'�mission	79	BE
792	Pr�l�vement sur les r�serves	79	BE
793	Perte � reporter	79	BE
794	Intervention d'associ�s (ou du propri�taire) dans la perte	79	BE
1	Fonds propres, provisions pour risques et charges � plus d'un an	0	BE
2	Frais d'�tablissement, actifs immobilis�s et cr�ances � plus d'un an	0	BE
3	Stocks et commandes en cours d'�x�cution	0	BE
4	Cr�ances et dettes � un an au plus	0	BE
5	Placements de tr�sorerie et valeurs disponibles	0	BE
6	Charges	0	BE
7	Produits	0	BE
\.


--
-- Data for TOC entry 95 (OID 20874)
-- Name: version; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY "version" (val) FROM stdin;
3
\.


--
-- Data for TOC entry 96 (OID 20880)
-- Name: parm_money; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY parm_money (pm_id, pm_code, pm_rate) FROM stdin;
1	EUR	1
\.


--
-- Data for TOC entry 97 (OID 20883)
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
14	2004-01-01	2004-01-31	2004	f
15	2004-01-02	2004-02-28	2004	f
16	2004-01-03	2004-03-31	2004	f
17	2004-01-04	2004-04-30	2004	f
18	2004-01-05	2004-05-31	2004	f
19	2004-01-06	2004-06-30	2004	f
20	2004-01-07	2004-07-31	2004	f
21	2004-01-08	2004-08-31	2004	f
22	2004-01-09	2004-09-30	2004	f
23	2004-01-10	2004-10-30	2004	f
24	2004-01-11	2004-11-30	2004	f
25	2004-01-12	2004-12-31	2004	f
26	2004-12-31	\N	2004	f
\.


--
-- Data for TOC entry 98 (OID 20899)
-- Name: jrn_type; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY jrn_type (jrn_type_id, jrn_desc) FROM stdin;
FIN	Financier
VEN	Vente
ACH	Achat
OD 	Op�rations Diverses
\.


--
-- Data for TOC entry 99 (OID 20904)
-- Name: jrn_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY jrn_def (jrn_def_id, jrn_def_name, jrn_def_class_deb, jrn_def_class_cred, jrn_def_fiche_deb, jrn_def_fiche_cred, jrn_deb_max_line, jrn_cred_max_line, jrn_def_ech, jrn_def_ech_lib, jrn_def_type, jrn_def_code) FROM stdin;
1	Financier	5* 	5*	\N	\N	5	5	f	\N	FIN	FIN-01
2	Vente	7*	4*	\N	\N	1	3	t	�ch�ance	VEN	VEN-01
3	Achat	6*	4*	\N	\N	1	3	t	�ch�ance	ACH	ACH-01
4	Op�ration Diverses	\N	\N	\N	\N	5	5	f	\N	OD 	OD-01
\.


--
-- Data for TOC entry 100 (OID 20915)
-- Name: jrn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY jrn (jr_id, jr_def_id, jr_montant, jr_comment, jr_date, jr_grpt_id, jr_rapt, jr_internal, jr_tech_date, jr_tech_per, jrn_ech, jr_ech) FROM stdin;
\.


--
-- Data for TOC entry 101 (OID 20922)
-- Name: jrnx; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY jrnx (j_id, j_date, j_montant, j_poste, j_grpt, j_rapt, j_jrn_def, j_debit, j_text, j_centralized, j_internal, j_tech_user, j_tech_date, j_tech_per) FROM stdin;
\.


--
-- Data for TOC entry 102 (OID 20933)
-- Name: user_pref; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY user_pref (pref_user, pref_periode) FROM stdin;
\.


--
-- Data for TOC entry 103 (OID 20942)
-- Name: formdef; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY formdef (fr_id, fr_label) FROM stdin;
\.


--
-- Data for TOC entry 104 (OID 20948)
-- Name: form; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY form (fo_id, fo_fr_id, fo_pos, fo_label, fo_formula) FROM stdin;
\.


--
-- Data for TOC entry 105 (OID 20960)
-- Name: centralized; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY centralized (c_id, c_j_id, c_date, c_internal, c_montant, c_debit, c_jrn_def, c_poste, c_description, c_grp, c_comment, c_rapt, c_periode) FROM stdin;
\.


--
-- Data for TOC entry 106 (OID 20971)
-- Name: user_sec_jrn; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY user_sec_jrn (uj_id, uj_login, uj_jrn_id, uj_priv) FROM stdin;
\.


--
-- Data for TOC entry 107 (OID 20977)
-- Name: action; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY "action" (ac_id, ac_description) FROM stdin;
1	Journaux
2	Facturation
3	Fiche
4	Impression
5	Formulaire
6	Mise � jour Plan Comptable
7	Gestion Journaux
8	Param�tres
9	S�curit�
10	Centralise
\.


--
-- Data for TOC entry 108 (OID 20982)
-- Name: user_sec_act; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY user_sec_act (ua_id, ua_login, ua_act_id) FROM stdin;
\.


--
-- Data for TOC entry 109 (OID 20990)
-- Name: jrn_action; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY jrn_action (ja_id, ja_name, ja_desc, ja_url, ja_action, ja_lang, ja_jrn_type) FROM stdin;
2	Voir	Voir toutes les factures	user_jrn.php	action=voir_jrn	FR	VEN
4	Voir Impay�s	Voir toutes les factures non pay�es	user_jrn.php	action=voir_jrn_non_paye	FR	VEN
6	Recherche	Recherche dans le journal	user_jrn.php	action=search	FR	VEN
1	Nouvelle	Cr�ation d'une facture	user_jrn.php	action=insert_vente&blank	FR	VEN
5	Impression	Impression du journal	impress.php	filter=1&action=viewpdf&type=jrn	FR	VEN
10	Nouveau	Encode un nouvel achat (mat�riel, marchandises, services et biens divers)	user_jrn.php	action=new&blank	FR	ACH
12	Voir	Voir toutes les factures	user_jrn.php	action=voir_jrn	FR	ACH
14	Voir Impay�s	Voir toutes les factures non pay�es	user_jrn.php	action=voir_jrn_non_paye	FR	ACH
16	Impression	Impression du journal	impress.php	filter=1&action=viewpdf&type=jrn	FR	ACH
18	Recherche	Recherche dans le journal	user_jrn.php	action=search	FR	ACH
20	Nouveau	Encode un nouvel achat (mat�riel, marchandises, services et biens divers)	user_jrn.php	action=new&blank	FR	FIN
22	Voir	Voir toutes les factures	user_jrn.php	action=voir_jrn	FR	FIN
24	Impression	Impression du journal	impress.php	filter=1&action=viewpdf&type=jrn	FR	FIN
26	Recherche	Recherche dans le journal	user_jrn.php	action=search	FR	FIN
30	Nouveau	\N	user_jrn.php	action=new&blank	FR	OD 
32	Voir	Voir toutes les factures	user_jrn.php	action=voir_jrn	FR	OD 
34	Impression	Impression du journal	impress.php	filter=1&action=viewpdf&type=jrn	FR	OD 
36	Recherche	Recherche dans le journal	user_jrn.php	action=search	FR	OD 
\.


--
-- Data for TOC entry 110 (OID 20997)
-- Name: fiche_ref_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY fiche_ref_def (frd_id, frd_text) FROM stdin;
\.


--
-- Data for TOC entry 111 (OID 21002)
-- Name: attr_valeur; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY attr_valeur (f_id, ad_id, av_text) FROM stdin;
\.


--
-- Data for TOC entry 112 (OID 21007)
-- Name: jnt_fic_def_att_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY jnt_fic_def_att_def (fd_id, ad_id) FROM stdin;
\.


--
-- Data for TOC entry 113 (OID 21009)
-- Name: tva_rate; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY tva_rate (tva_id, tva_label, tva_rate, tva_comment, tva_poste) FROM stdin;
1	21%	0.21	Tva applicable � tout ce qui bien et service divers	411,451
3	0%	0	Tva applicable lors de vente/achat intracommunautaire	411,451
2	6%	0.06	Tva applicable aux journaux et livres	411,451
\.


--
-- Data for TOC entry 114 (OID 21025)
-- Name: fiche_def_ref; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY fiche_def_ref (frd_id, frd_text, frd_class_base) FROM stdin;
1	Vente Service	700
2	Achat Marchandises	604
3	Achat Service et biens divers	61
4	Banque	5500
5	Pr�t > a un an	17
6	Pr�t < a un an	430
8	Fournisseurs	440
9	Clients	400
10	Salaire Administrateur	6200
11	Salaire Ouvrier	6203
12	Salaire Employ�	6202
13	D�penses non admises	674
7	Mat�riel � amortir	24
\.


--
-- Data for TOC entry 115 (OID 21031)
-- Name: fiche_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY fiche_def (fd_id, fd_class_base, fd_label, fd_create_account, frd_id) FROM stdin;
\.


--
-- Data for TOC entry 116 (OID 21038)
-- Name: attr_def; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY attr_def (ad_id, ad_text) FROM stdin;
1	Nom
2	Taux TVA
3	Num�ro de compte
4	Nom de la banque
5	Poste Comptable
6	Prix vente
7	Prix achat
8	Dur�e Amortissement
9	Description
10	Date d�but
11	Montant initial
12	Personne de contact 
13	num�ro de tva 
14	Adresse 
15	code postale 
16	pays 
17	t�l�phone 
18	email 
\.


--
-- Data for TOC entry 117 (OID 21044)
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
\.


--
-- Data for TOC entry 118 (OID 21046)
-- Name: fiche; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY fiche (f_id, fd_id) FROM stdin;
\.


--
-- Data for TOC entry 119 (OID 21049)
-- Name: jnt_fic_att_value; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY jnt_fic_att_value (jft_id, f_id, ad_id) FROM stdin;
\.


--
-- Data for TOC entry 120 (OID 21052)
-- Name: attr_value; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY attr_value (jft_id, av_text) FROM stdin;
\.


--
-- Data for TOC entry 121 (OID 21057)
-- Name: jnt_fic_attr; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY jnt_fic_attr (fd_id, ad_id) FROM stdin;
\.


--
-- TOC entry 86 (OID 21668)
-- Name: x_act; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE UNIQUE INDEX x_act ON "action" USING btree (ac_description);


--
-- TOC entry 84 (OID 21669)
-- Name: x_usr_jrn; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE UNIQUE INDEX x_usr_jrn ON user_sec_jrn USING btree (uj_login, uj_jrn_id);


--
-- TOC entry 70 (OID 21670)
-- Name: tmp_pcmn_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY tmp_pcmn
    ADD CONSTRAINT tmp_pcmn_pkey PRIMARY KEY (pcm_val);


--
-- TOC entry 71 (OID 21672)
-- Name: parm_money_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY parm_money
    ADD CONSTRAINT parm_money_pkey PRIMARY KEY (pm_code);


--
-- TOC entry 73 (OID 21674)
-- Name: parm_periode_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY parm_periode
    ADD CONSTRAINT parm_periode_pkey PRIMARY KEY (p_id);


--
-- TOC entry 72 (OID 21676)
-- Name: parm_periode_p_start_key; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY parm_periode
    ADD CONSTRAINT parm_periode_p_start_key UNIQUE (p_start);


--
-- TOC entry 74 (OID 21678)
-- Name: jrn_type_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_type
    ADD CONSTRAINT jrn_type_pkey PRIMARY KEY (jrn_type_id);


--
-- TOC entry 76 (OID 21680)
-- Name: jrn_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT jrn_def_pkey PRIMARY KEY (jrn_def_id);


--
-- TOC entry 75 (OID 21682)
-- Name: jrn_def_jrn_def_name_key; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT jrn_def_jrn_def_name_key UNIQUE (jrn_def_name);


--
-- TOC entry 122 (OID 21684)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT "$1" FOREIGN KEY (jrn_def_type) REFERENCES jrn_type(jrn_type_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 123 (OID 21688)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn
    ADD CONSTRAINT "$1" FOREIGN KEY (jr_def_id) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 77 (OID 21692)
-- Name: jrn_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn
    ADD CONSTRAINT jrn_pkey PRIMARY KEY (jr_id, jr_def_id);


--
-- TOC entry 78 (OID 21694)
-- Name: jrnx_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrnx
    ADD CONSTRAINT jrnx_pkey PRIMARY KEY (j_id);


--
-- TOC entry 124 (OID 21696)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrnx
    ADD CONSTRAINT "$1" FOREIGN KEY (j_poste) REFERENCES tmp_pcmn(pcm_val) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 125 (OID 21700)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrnx
    ADD CONSTRAINT "$2" FOREIGN KEY (j_jrn_def) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 79 (OID 21704)
-- Name: user_pref_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_pref
    ADD CONSTRAINT user_pref_pkey PRIMARY KEY (pref_user);


--
-- TOC entry 80 (OID 21706)
-- Name: formdef_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY formdef
    ADD CONSTRAINT formdef_pkey PRIMARY KEY (fr_id);


--
-- TOC entry 81 (OID 21708)
-- Name: form_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY form
    ADD CONSTRAINT form_pkey PRIMARY KEY (fo_id);


--
-- TOC entry 126 (OID 21710)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY form
    ADD CONSTRAINT "$1" FOREIGN KEY (fo_fr_id) REFERENCES formdef(fr_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 82 (OID 21714)
-- Name: centralized_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY centralized
    ADD CONSTRAINT centralized_pkey PRIMARY KEY (c_id);


--
-- TOC entry 127 (OID 21716)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY centralized
    ADD CONSTRAINT "$1" FOREIGN KEY (c_jrn_def) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 128 (OID 21720)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY centralized
    ADD CONSTRAINT "$2" FOREIGN KEY (c_poste) REFERENCES tmp_pcmn(pcm_val) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 83 (OID 21724)
-- Name: user_sec_jrn_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_jrn
    ADD CONSTRAINT user_sec_jrn_pkey PRIMARY KEY (uj_id);


--
-- TOC entry 129 (OID 21726)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_jrn
    ADD CONSTRAINT "$1" FOREIGN KEY (uj_jrn_id) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 85 (OID 21730)
-- Name: action_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY "action"
    ADD CONSTRAINT action_pkey PRIMARY KEY (ac_id);


--
-- TOC entry 87 (OID 21732)
-- Name: user_sec_act_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_act
    ADD CONSTRAINT user_sec_act_pkey PRIMARY KEY (ua_id);


--
-- TOC entry 130 (OID 21734)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_act
    ADD CONSTRAINT "$1" FOREIGN KEY (ua_act_id) REFERENCES "action"(ac_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 88 (OID 21738)
-- Name: jrn_action_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_action
    ADD CONSTRAINT jrn_action_pkey PRIMARY KEY (ja_id);


--
-- TOC entry 131 (OID 21740)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_action
    ADD CONSTRAINT "$1" FOREIGN KEY (ja_jrn_type) REFERENCES jrn_type(jrn_type_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 89 (OID 21744)
-- Name: fiche_def_ref_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche_def_ref
    ADD CONSTRAINT fiche_def_ref_pkey PRIMARY KEY (frd_id);


--
-- TOC entry 90 (OID 21746)
-- Name: fiche_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche_def
    ADD CONSTRAINT fiche_def_pkey PRIMARY KEY (fd_id);


--
-- TOC entry 132 (OID 21748)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche_def
    ADD CONSTRAINT "$1" FOREIGN KEY (frd_id) REFERENCES fiche_def_ref(frd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 91 (OID 21752)
-- Name: attr_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_def
    ADD CONSTRAINT attr_def_pkey PRIMARY KEY (ad_id);


--
-- TOC entry 133 (OID 21754)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_min
    ADD CONSTRAINT "$1" FOREIGN KEY (frd_id) REFERENCES fiche_def_ref(frd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 134 (OID 21758)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_min
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 92 (OID 21762)
-- Name: fiche_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche
    ADD CONSTRAINT fiche_pkey PRIMARY KEY (f_id);


--
-- TOC entry 135 (OID 21764)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche
    ADD CONSTRAINT "$1" FOREIGN KEY (fd_id) REFERENCES fiche_def(fd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 93 (OID 21768)
-- Name: jnt_fic_att_value_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT jnt_fic_att_value_pkey PRIMARY KEY (jft_id);


--
-- TOC entry 136 (OID 21770)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT "$1" FOREIGN KEY (f_id) REFERENCES fiche(f_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 137 (OID 21774)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 138 (OID 21778)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_value
    ADD CONSTRAINT "$1" FOREIGN KEY (jft_id) REFERENCES jnt_fic_att_value(jft_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 139 (OID 21782)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_attr
    ADD CONSTRAINT "$1" FOREIGN KEY (fd_id) REFERENCES fiche_def(fd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 140 (OID 21786)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_attr
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 3 (OID 20876)
-- Name: s_periode; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_periode', 26, true);


--
-- TOC entry 5 (OID 20878)
-- Name: s_currency; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_currency', 1, true);


--
-- TOC entry 7 (OID 20891)
-- Name: s_jrn_def; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrn_def', 5, false);


--
-- TOC entry 9 (OID 20893)
-- Name: s_grpt; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_grpt', 1, false);


--
-- TOC entry 11 (OID 20895)
-- Name: s_jrn_op; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrn_op', 1, false);


--
-- TOC entry 13 (OID 20897)
-- Name: s_jrn; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrn', 1, false);


--
-- TOC entry 15 (OID 20913)
-- Name: s_jrnx; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrnx', 1, false);


--
-- TOC entry 17 (OID 20938)
-- Name: s_formdef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_formdef', 1, false);


--
-- TOC entry 19 (OID 20940)
-- Name: s_form; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_form', 1, false);


--
-- TOC entry 21 (OID 20954)
-- Name: s_isup; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_isup', 1, false);


--
-- TOC entry 23 (OID 20956)
-- Name: s_idef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_idef', 1, false);


--
-- TOC entry 25 (OID 20958)
-- Name: s_centralized; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_centralized', 1, false);


--
-- TOC entry 27 (OID 20967)
-- Name: s_user_jrn; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_user_jrn', 1, false);


--
-- TOC entry 29 (OID 20969)
-- Name: s_user_act; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_user_act', 1, false);


--
-- TOC entry 31 (OID 20988)
-- Name: s_jrnaction; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jrnaction', 5, true);


--
-- TOC entry 33 (OID 21015)
-- Name: s_fiche; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_fiche', 1, false);


--
-- TOC entry 35 (OID 21017)
-- Name: s_fiche_def_ref; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_fiche_def_ref', 1, false);


--
-- TOC entry 37 (OID 21019)
-- Name: s_fdef; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_fdef', 1, false);


--
-- TOC entry 39 (OID 21021)
-- Name: s_attr_def; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_attr_def', 1, false);


--
-- TOC entry 41 (OID 21023)
-- Name: s_jnt_fic_att_value; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_jnt_fic_att_value', 1, false);


