--
-- PostgreSQL database dump
--

\connect - phpcompta

SET search_path = public, pg_catalog;

--
-- TOC entry 2 (OID 44864)
-- Name: users_id; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE users_id
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 7 (OID 44866)
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
-- TOC entry 8 (OID 44876)
-- Name: ac_dossier; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE ac_dossier (
    dos_id integer DEFAULT nextval('dossier_id'::text) NOT NULL,
    dos_name text NOT NULL,
    dos_description text,
    dos_jnt_user integer DEFAULT 0
);


--
-- TOC entry 3 (OID 44883)
-- Name: seq_jnt_use_dos; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE seq_jnt_use_dos
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 9 (OID 44885)
-- Name: jnt_use_dos; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jnt_use_dos (
    jnt_id integer DEFAULT nextval('seq_jnt_use_dos'::text) NOT NULL,
    use_id integer NOT NULL,
    dos_id integer NOT NULL
);


--
-- TOC entry 10 (OID 44888)
-- Name: version; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE "version" (
    val integer
);


--
-- TOC entry 4 (OID 44890)
-- Name: seq_priv_user; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE seq_priv_user
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 11 (OID 44892)
-- Name: priv_user; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE priv_user (
    priv_id integer DEFAULT nextval('seq_priv_user'::text) NOT NULL,
    priv_jnt integer NOT NULL,
    priv_priv text
);


--
-- TOC entry 12 (OID 44898)
-- Name: theme; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE theme (
    the_name text NOT NULL,
    the_filestyle text,
    the_filebutton text
);


--
-- TOC entry 5 (OID 44903)
-- Name: s_modid; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_modid
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 13 (OID 44905)
-- Name: modeledef; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE modeledef (
    mod_id integer DEFAULT nextval('s_modid'::text) NOT NULL,
    mod_name text NOT NULL,
    mod_desc text
);


--
-- TOC entry 6 (OID 44911)
-- Name: dossier_id; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE dossier_id
    START 3
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 3
    CACHE 1;


--
-- TOC entry 14 (OID 679377)
-- Name: log; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE log (
    lg_timestamp timestamp without time zone DEFAULT now(),
    lg_file text,
    lg_type text DEFAULT 'debug',
    lg_line text,
    lg_msg text
);


--
-- TOC entry 20 (OID 44942)
-- Name: fk_jnt_use_dos; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_jnt_use_dos ON jnt_use_dos USING btree (use_id);


--
-- TOC entry 19 (OID 44943)
-- Name: fk_jnt_dos_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_jnt_dos_id ON jnt_use_dos USING btree (dos_id);


--
-- TOC entry 15 (OID 44944)
-- Name: ac_users_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY ac_users
    ADD CONSTRAINT ac_users_pkey PRIMARY KEY (use_id);


--
-- TOC entry 16 (OID 44946)
-- Name: ac_users_use_login_key; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY ac_users
    ADD CONSTRAINT ac_users_use_login_key UNIQUE (use_login);


--
-- TOC entry 18 (OID 44948)
-- Name: ac_dossier_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY ac_dossier
    ADD CONSTRAINT ac_dossier_pkey PRIMARY KEY (dos_id);


--
-- TOC entry 17 (OID 44950)
-- Name: ac_dossier_dos_name_key; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY ac_dossier
    ADD CONSTRAINT ac_dossier_dos_name_key UNIQUE (dos_name);


--
-- TOC entry 21 (OID 44952)
-- Name: jnt_use_dos_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_use_dos
    ADD CONSTRAINT jnt_use_dos_pkey PRIMARY KEY (use_id, dos_id);


--
-- TOC entry 23 (OID 44954)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_use_dos
    ADD CONSTRAINT "$1" FOREIGN KEY (use_id) REFERENCES ac_users(use_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 22 (OID 44958)
-- Name: modeledef_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY modeledef
    ADD CONSTRAINT modeledef_pkey PRIMARY KEY (mod_id);


