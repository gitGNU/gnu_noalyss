--
-- PostgreSQL database dump
--

\connect - phpcompta

SET search_path = public, pg_catalog;

--
-- TOC entry 2 (OID 20779)
-- Name: users_id; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE users_id
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 12 (OID 20781)
-- Name: ac_users; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE ac_users (
    use_id integer DEFAULT nextval('users_id'::text) NOT NULL,
    use_first_name text,
    use_name text,
    use_login text NOT NULL,
    use_active integer DEFAULT 0,
    use_pass text,
    use_admin integer DEFAULT 0,
    use_theme text DEFAULT 'Light',
    use_usertype text NOT NULL,
    CHECK (((use_active = 0) OR (use_active = 1)))
);


--
-- TOC entry 4 (OID 20791)
-- Name: dossier_id; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE dossier_id
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 13 (OID 20793)
-- Name: ac_dossier; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE ac_dossier (
    dos_id integer DEFAULT nextval('dossier_id'::text) NOT NULL,
    dos_name text NOT NULL,
    dos_description text,
    dos_jnt_user integer DEFAULT 0
);


--
-- TOC entry 6 (OID 20800)
-- Name: seq_jnt_use_dos; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE seq_jnt_use_dos
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 14 (OID 20802)
-- Name: jnt_use_dos; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jnt_use_dos (
    jnt_id integer DEFAULT nextval('seq_jnt_use_dos'::text) NOT NULL,
    use_id integer NOT NULL,
    dos_id integer NOT NULL
);


--
-- TOC entry 15 (OID 20805)
-- Name: version; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE "version" (
    val integer
);


--
-- TOC entry 8 (OID 20807)
-- Name: seq_priv_user; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE seq_priv_user
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 16 (OID 20809)
-- Name: priv_user; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE priv_user (
    priv_id integer DEFAULT nextval('seq_priv_user'::text) NOT NULL,
    priv_jnt integer NOT NULL,
    priv_priv text
);


--
-- TOC entry 17 (OID 20815)
-- Name: theme; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE theme (
    the_name text NOT NULL,
    the_filestyle text,
    the_filebutton text
);


--
-- TOC entry 10 (OID 20820)
-- Name: s_modid; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_modid
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 18 (OID 20822)
-- Name: modeledef; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE modeledef (
    mod_id integer DEFAULT nextval('s_modid'::text) NOT NULL,
    mod_name text NOT NULL,
    mod_desc text
);


--
-- Data for TOC entry 27 (OID 20781)
-- Name: ac_users; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY ac_users (use_id, use_first_name, use_name, use_login, use_active, use_pass, use_admin, use_theme, use_usertype) FROM stdin;
1	\N	\N	phpcompta	1	b1cc88e1907cde80cb2595fa793b3da9	1	Light	user
4	demo	demo	demo	1	fe01ce2a7fbac8fafaed7c982a04e229	0	Light	user
\.


--
-- Data for TOC entry 28 (OID 20793)
-- Name: ac_dossier; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY ac_dossier (dos_id, dos_name, dos_description, dos_jnt_user) FROM stdin;
1	Demo	Base de données pour développement & démo	0
\.


--
-- Data for TOC entry 29 (OID 20802)
-- Name: jnt_use_dos; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY jnt_use_dos (jnt_id, use_id, dos_id) FROM stdin;
1	1	1
2	1	2
3	1	5
4	1	6
13	4	1
\.


--
-- Data for TOC entry 30 (OID 20805)
-- Name: version; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY "version" (val) FROM stdin;
3
\.


--
-- Data for TOC entry 31 (OID 20809)
-- Name: priv_user; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY priv_user (priv_id, priv_jnt, priv_priv) FROM stdin;
1	5	NO
2	6	NO
3	7	NO
4	8	NO
5	9	NO
6	10	NO
7	11	W
8	12	NO
9	13	W
\.


--
-- Data for TOC entry 32 (OID 20815)
-- Name: theme; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY theme (the_name, the_filestyle, the_filebutton) FROM stdin;
classic	style.css	\N
Aqua	style-aqua.css	\N
Elegant	style-elegant.css	\N
Light	style-light.css	\N
\.


--
-- Data for TOC entry 33 (OID 20822)
-- Name: modeledef; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

COPY modeledef (mod_id, mod_name, mod_desc) FROM stdin;
1	(BE) Basique	Comptabilité Belge, tout doit être adaptée
\.


--
-- TOC entry 24 (OID 20848)
-- Name: fk_jnt_use_dos; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_jnt_use_dos ON jnt_use_dos USING btree (use_id);


--
-- TOC entry 23 (OID 20849)
-- Name: fk_jnt_dos_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_jnt_dos_id ON jnt_use_dos USING btree (dos_id);


--
-- TOC entry 19 (OID 20850)
-- Name: ac_users_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY ac_users
    ADD CONSTRAINT ac_users_pkey PRIMARY KEY (use_id);


--
-- TOC entry 20 (OID 20852)
-- Name: ac_users_use_login_key; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY ac_users
    ADD CONSTRAINT ac_users_use_login_key UNIQUE (use_login);


--
-- TOC entry 22 (OID 20854)
-- Name: ac_dossier_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY ac_dossier
    ADD CONSTRAINT ac_dossier_pkey PRIMARY KEY (dos_id);


--
-- TOC entry 21 (OID 20856)
-- Name: ac_dossier_dos_name_key; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY ac_dossier
    ADD CONSTRAINT ac_dossier_dos_name_key UNIQUE (dos_name);


--
-- TOC entry 25 (OID 20858)
-- Name: jnt_use_dos_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_use_dos
    ADD CONSTRAINT jnt_use_dos_pkey PRIMARY KEY (use_id, dos_id);


--
-- TOC entry 34 (OID 20860)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_use_dos
    ADD CONSTRAINT "$1" FOREIGN KEY (use_id) REFERENCES ac_users(use_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 26 (OID 20864)
-- Name: modeledef_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY modeledef
    ADD CONSTRAINT modeledef_pkey PRIMARY KEY (mod_id);


--
-- TOC entry 3 (OID 20779)
-- Name: users_id; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('users_id', 4, true);


--
-- TOC entry 5 (OID 20791)
-- Name: dossier_id; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('dossier_id', 6, true);


--
-- TOC entry 7 (OID 20800)
-- Name: seq_jnt_use_dos; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('seq_jnt_use_dos', 13, true);


--
-- TOC entry 9 (OID 20807)
-- Name: seq_priv_user; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('seq_priv_user', 9, true);


--
-- TOC entry 11 (OID 20820)
-- Name: s_modid; Type: SEQUENCE SET; Schema: public; Owner: phpcompta
--

SELECT pg_catalog.setval ('s_modid', 1, false);


