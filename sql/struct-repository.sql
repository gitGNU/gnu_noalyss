--
-- PostgreSQL database dump
--

SET search_path = public, pg_catalog;

--
-- TOC entry 2 (OID 21938)
-- Name: users_id; Type: SEQUENCE; Schema: public; Owner: webcompta
--

CREATE SEQUENCE users_id
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 10 (OID 21940)
-- Name: ac_users; Type: TABLE; Schema: public; Owner: webcompta
--

CREATE TABLE ac_users (
    use_id integer DEFAULT nextval('users_id'::text) NOT NULL,
    use_first_name text,
    use_name text,
    use_login text NOT NULL,
    use_active integer DEFAULT 0,
    use_pass text,
    use_admin integer,
    CHECK (((use_active = 0) OR (use_active = 1)))
);


--
-- TOC entry 4 (OID 21955)
-- Name: dossier_id; Type: SEQUENCE; Schema: public; Owner: webcompta
--

CREATE SEQUENCE dossier_id
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 11 (OID 21957)
-- Name: ac_dossier; Type: TABLE; Schema: public; Owner: webcompta
--

CREATE TABLE ac_dossier (
    dos_id integer DEFAULT nextval('dossier_id'::text) NOT NULL,
    dos_name text NOT NULL,
    dos_description text,
    dos_jnt_user integer DEFAULT 0
);


--
-- TOC entry 12 (OID 21964)
-- Name: jnt_use_dos; Type: TABLE; Schema: public; Owner: webcompta
--

CREATE TABLE jnt_use_dos (
    jnt_id integer DEFAULT nextval('seq_jnt_use_dos'::text) NOT NULL,
    use_id integer NOT NULL,
    dos_id integer NOT NULL
);


--
-- TOC entry 13 (OID 21967)
-- Name: priv_user; Type: TABLE; Schema: public; Owner: webcompta
--

CREATE TABLE priv_user (
    priv_id integer DEFAULT nextval('seq_priv_user'::text) NOT NULL,
    priv_jnt integer NOT NULL,
    priv_priv text
);


--
-- TOC entry 6 (OID 21973)
-- Name: seq_jnt_use_dos; Type: SEQUENCE; Schema: public; Owner: webcompta
--

CREATE SEQUENCE seq_jnt_use_dos
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 8 (OID 21975)
-- Name: seq_priv_user; Type: SEQUENCE; Schema: public; Owner: webcompta
--

CREATE SEQUENCE seq_priv_user
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- Data for TOC entry 21 (OID 21940)
-- Name: ac_users; Type: TABLE DATA; Schema: public; Owner: webcompta
--

COPY ac_users (use_id, use_first_name, use_name, use_login, use_active, use_pass, use_admin) FROM stdin;
1	\N	\N	webcompta	1	486348d8b9ac03742368d8736244e958	1
\.


--
-- Data for TOC entry 22 (OID 21957)
-- Name: ac_dossier; Type: TABLE DATA; Schema: public; Owner: webcompta
--

COPY ac_dossier (dos_id, dos_name, dos_description, dos_jnt_user) FROM stdin;
\.


--
-- Data for TOC entry 23 (OID 21964)
-- Name: jnt_use_dos; Type: TABLE DATA; Schema: public; Owner: webcompta
--

COPY jnt_use_dos (jnt_id, use_id, dos_id) FROM stdin;
\.


--
-- Data for TOC entry 24 (OID 21967)
-- Name: priv_user; Type: TABLE DATA; Schema: public; Owner: webcompta
--

COPY priv_user (priv_id, priv_jnt, priv_priv) FROM stdin;
\.


--
-- TOC entry 18 (OID 22497)
-- Name: jnt_idx; Type: INDEX; Schema: public; Owner: webcompta
--

CREATE INDEX jnt_idx ON jnt_use_dos USING btree (use_id, dos_id);


--
-- TOC entry 14 (OID 22498)
-- Name: ac_users_pkey; Type: CONSTRAINT; Schema: public; Owner: webcompta
--

ALTER TABLE ONLY ac_users
    ADD CONSTRAINT ac_users_pkey PRIMARY KEY (use_id);


--
-- TOC entry 15 (OID 22500)
-- Name: ac_users_use_login_key; Type: CONSTRAINT; Schema: public; Owner: webcompta
--

ALTER TABLE ONLY ac_users
    ADD CONSTRAINT ac_users_use_login_key UNIQUE (use_login);


--
-- TOC entry 17 (OID 22504)
-- Name: ac_dossier_pkey; Type: CONSTRAINT; Schema: public; Owner: webcompta
--

ALTER TABLE ONLY ac_dossier
    ADD CONSTRAINT ac_dossier_pkey PRIMARY KEY (dos_id);


--
-- TOC entry 16 (OID 22506)
-- Name: ac_dossier_dos_name_key; Type: CONSTRAINT; Schema: public; Owner: webcompta
--

ALTER TABLE ONLY ac_dossier
    ADD CONSTRAINT ac_dossier_dos_name_key UNIQUE (dos_name);


--
-- TOC entry 19 (OID 22508)
-- Name: jnt_use_dos_pkey; Type: CONSTRAINT; Schema: public; Owner: webcompta
--

ALTER TABLE ONLY jnt_use_dos
    ADD CONSTRAINT jnt_use_dos_pkey PRIMARY KEY (jnt_id);


--
-- TOC entry 25 (OID 22510)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: webcompta
--

ALTER TABLE ONLY jnt_use_dos
    ADD CONSTRAINT "$2" FOREIGN KEY (use_id) REFERENCES ac_users(use_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 26 (OID 22514)
-- Name: $3; Type: CONSTRAINT; Schema: public; Owner: webcompta
--

ALTER TABLE ONLY jnt_use_dos
    ADD CONSTRAINT "$3" FOREIGN KEY (dos_id) REFERENCES ac_dossier(dos_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 20 (OID 22518)
-- Name: priv_user_pkey; Type: CONSTRAINT; Schema: public; Owner: webcompta
--

ALTER TABLE ONLY priv_user
    ADD CONSTRAINT priv_user_pkey PRIMARY KEY (priv_id);


--
-- TOC entry 27 (OID 22520)
-- Name: fk_jnt; Type: CONSTRAINT; Schema: public; Owner: webcompta
--

ALTER TABLE ONLY priv_user
    ADD CONSTRAINT fk_jnt FOREIGN KEY (priv_jnt) REFERENCES jnt_use_dos(jnt_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 3 (OID 21938)
-- Name: users_id; Type: SEQUENCE SET; Schema: public; Owner: webcompta
--

SELECT pg_catalog.setval ('users_id', 29, true);


--
-- TOC entry 5 (OID 21955)
-- Name: dossier_id; Type: SEQUENCE SET; Schema: public; Owner: webcompta
--

SELECT pg_catalog.setval ('dossier_id', 22, true);


--
-- TOC entry 7 (OID 21973)
-- Name: seq_jnt_use_dos; Type: SEQUENCE SET; Schema: public; Owner: webcompta
--

SELECT pg_catalog.setval ('seq_jnt_use_dos', 72, true);


--
-- TOC entry 9 (OID 21975)
-- Name: seq_priv_user; Type: SEQUENCE SET; Schema: public; Owner: webcompta
--

SELECT pg_catalog.setval ('seq_priv_user', 72, true);


