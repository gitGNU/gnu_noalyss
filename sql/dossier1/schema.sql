--
-- PostgreSQL database dump
--

\connect - phpcompta

SET search_path = public, pg_catalog;

--
-- TOC entry 23 (OID 46333)
-- Name: tmp_pcmn; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE tmp_pcmn (
    pcm_val integer NOT NULL,
    pcm_lib text,
    pcm_val_parent integer DEFAULT 0,
    pcm_country character(2) DEFAULT 'BE' NOT NULL
);


--
-- TOC entry 24 (OID 46340)
-- Name: version; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE "version" (
    val integer
);


--
-- TOC entry 2 (OID 46342)
-- Name: s_periode; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_periode
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 3 (OID 46344)
-- Name: s_currency; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_currency
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 25 (OID 46346)
-- Name: parm_money; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE parm_money (
    pm_id integer DEFAULT nextval('s_currency'::text),
    pm_code character(3) NOT NULL,
    pm_rate double precision
);


--
-- TOC entry 26 (OID 46349)
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
-- TOC entry 4 (OID 46357)
-- Name: s_jrn_def; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_def
    START 5
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 5 (OID 46359)
-- Name: s_grpt; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_grpt
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 6 (OID 46361)
-- Name: s_jrn_op; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_op
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 7 (OID 46363)
-- Name: s_jrn; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 27 (OID 46365)
-- Name: jrn_type; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jrn_type (
    jrn_type_id character(3) NOT NULL,
    jrn_desc text
);


--
-- TOC entry 28 (OID 46370)
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
-- TOC entry 8 (OID 46379)
-- Name: s_jrnx; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrnx
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 29 (OID 46381)
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
    j_tech_per integer,
    j_montant_n numeric(200,100)
);


--
-- TOC entry 30 (OID 46392)
-- Name: user_pref; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE user_pref (
    pref_user text NOT NULL,
    pref_periode integer NOT NULL
);


--
-- TOC entry 9 (OID 46397)
-- Name: s_formdef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_formdef
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 10 (OID 46399)
-- Name: s_form; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_form
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 31 (OID 46401)
-- Name: formdef; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE formdef (
    fr_id integer DEFAULT nextval('s_formdef'::text) NOT NULL,
    fr_label text
);


--
-- TOC entry 32 (OID 46407)
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
-- TOC entry 11 (OID 46413)
-- Name: s_idef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_idef
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 12 (OID 46415)
-- Name: s_centralized; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_centralized
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 33 (OID 46417)
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
-- TOC entry 13 (OID 46424)
-- Name: s_user_jrn; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_user_jrn
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 14 (OID 46426)
-- Name: s_user_act; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_user_act
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 34 (OID 46428)
-- Name: user_sec_jrn; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE user_sec_jrn (
    uj_id integer DEFAULT nextval('s_user_jrn'::text) NOT NULL,
    uj_login text,
    uj_jrn_id integer,
    uj_priv text
);


--
-- TOC entry 35 (OID 46434)
-- Name: action; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE "action" (
    ac_id integer NOT NULL,
    ac_description text NOT NULL
);


--
-- TOC entry 36 (OID 46439)
-- Name: user_sec_act; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE user_sec_act (
    ua_id integer DEFAULT nextval('s_user_act'::text) NOT NULL,
    ua_login text,
    ua_act_id integer
);


--
-- TOC entry 15 (OID 46445)
-- Name: s_jrnaction; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrnaction
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 37 (OID 46447)
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
-- TOC entry 38 (OID 46454)
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
-- TOC entry 16 (OID 46460)
-- Name: s_fiche; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fiche
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 17 (OID 46462)
-- Name: s_fiche_def_ref; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fiche_def_ref
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 18 (OID 46464)
-- Name: s_fdef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fdef
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 19 (OID 46466)
-- Name: s_attr_def; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_attr_def
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 20 (OID 46468)
-- Name: s_jnt_fic_att_value; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jnt_fic_att_value
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 39 (OID 46470)
-- Name: fiche_def_ref; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE fiche_def_ref (
    frd_id integer DEFAULT nextval('s_fiche_def_ref'::text) NOT NULL,
    frd_text text,
    frd_class_base integer
);


--
-- TOC entry 40 (OID 46476)
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
-- TOC entry 41 (OID 46483)
-- Name: attr_def; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_def (
    ad_id integer DEFAULT nextval('s_attr_def'::text) NOT NULL,
    ad_text text
);


--
-- TOC entry 42 (OID 46489)
-- Name: fiche; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE fiche (
    f_id integer DEFAULT nextval('s_fiche'::text) NOT NULL,
    fd_id integer
);


--
-- TOC entry 43 (OID 46492)
-- Name: jnt_fic_att_value; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jnt_fic_att_value (
    jft_id integer DEFAULT nextval('s_jnt_fic_att_value'::text) NOT NULL,
    f_id integer,
    ad_id integer
);


--
-- TOC entry 44 (OID 46495)
-- Name: attr_value; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_value (
    jft_id integer,
    av_text text
);


--
-- TOC entry 45 (OID 46500)
-- Name: jnt_fic_attr; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jnt_fic_attr (
    fd_id integer,
    ad_id integer
);


--
-- TOC entry 21 (OID 46502)
-- Name: s_jrn_rapt; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_rapt
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 46 (OID 46504)
-- Name: jrn_rapt; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jrn_rapt (
    jra_id integer DEFAULT nextval('s_jrn_rapt'::text) NOT NULL,
    jr_id integer NOT NULL,
    jra_concerned integer NOT NULL
);


--
-- TOC entry 47 (OID 46509)
-- Name: vw_fiche_attr; Type: VIEW; Schema: public; Owner: phpcompta
--

CREATE VIEW vw_fiche_attr AS
    SELECT a.f_id, fd_id, a.av_text AS vw_name, b.av_text AS vw_sell, c.av_text AS vw_buy, d.av_text AS tva_code, tva_id, tva_rate, tva_label, e.av_text AS vw_addr, f.av_text AS vw_cp, frd_id FROM ((((((((SELECT f_id, fd_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 1)) a LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 6)) b ON ((a.f_id = b.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 7)) c ON ((a.f_id = c.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 2)) d ON ((a.f_id = d.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 14)) e ON ((a.f_id = e.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 15)) f ON ((a.f_id = f.f_id))) LEFT JOIN tva_rate ON ((d.av_text = (tva_rate.tva_id)::text))) JOIN fiche_def USING (fd_id));


--
-- TOC entry 22 (OID 46511)
-- Name: s_stock_goods; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_stock_goods
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 48 (OID 46513)
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
-- TOC entry 49 (OID 46521)
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
-- TOC entry 50 (OID 46531)
-- Name: attr_min; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_min (
    frd_id integer,
    ad_id integer
);


\connect - dany

SET search_path = public, pg_catalog;

--
-- TOC entry 51 (OID 664498)
-- Name: jrnx_back; Type: TABLE; Schema: public; Owner: dany
--

CREATE TABLE jrnx_back (
    j_id integer,
    j_date date,
    j_montant double precision,
    j_poste integer,
    j_grpt integer,
    j_rapt text,
    j_jrn_def integer,
    j_debit boolean,
    j_text text,
    j_centralized boolean,
    j_internal text,
    j_tech_user text,
    j_tech_date timestamp without time zone,
    j_tech_per integer
);


\connect - phpcompta

SET search_path = public, pg_catalog;

--
-- TOC entry 52 (OID 679363)
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
-- TOC entry 74 (OID 47631)
-- Name: x_act; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE UNIQUE INDEX x_act ON "action" USING btree (ac_description);


--
-- TOC entry 72 (OID 47632)
-- Name: x_usr_jrn; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE UNIQUE INDEX x_usr_jrn ON user_sec_jrn USING btree (uj_login, uj_jrn_id);


--
-- TOC entry 68 (OID 47633)
-- Name: fk_centralized_c_jrn_def; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_centralized_c_jrn_def ON centralized USING btree (c_jrn_def);


--
-- TOC entry 69 (OID 47634)
-- Name: fk_centralized_c_poste; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_centralized_c_poste ON centralized USING btree (c_poste);


--
-- TOC entry 81 (OID 47635)
-- Name: fk_fiche_def_frd_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_fiche_def_frd_id ON fiche_def USING btree (frd_id);


--
-- TOC entry 86 (OID 47636)
-- Name: fk_attr_value_jft_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_attr_value_jft_id ON attr_value USING btree (jft_id);


--
-- TOC entry 84 (OID 47637)
-- Name: fk_fiche_fd_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_fiche_fd_id ON fiche USING btree (fd_id);


--
-- TOC entry 65 (OID 47638)
-- Name: fk_form_fo_fr_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_form_fo_fr_id ON form USING btree (fo_fr_id);


--
-- TOC entry 61 (OID 47639)
-- Name: fk_jrnx_j_poste; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_jrnx_j_poste ON jrnx USING btree (j_poste);


--
-- TOC entry 60 (OID 47640)
-- Name: fk_jrn_def; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_jrn_def ON jrnx USING btree (j_jrn_def);


--
-- TOC entry 77 (OID 47641)
-- Name: fk_jrn_action_ja_jrn_type; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_jrn_action_ja_jrn_type ON jrn_action USING btree (ja_jrn_type);


--
-- TOC entry 70 (OID 47642)
-- Name: fk_user_sec_jrn; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_user_sec_jrn ON user_sec_jrn USING btree (uj_jrn_id);


--
-- TOC entry 75 (OID 47643)
-- Name: fk_user_sec_act; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_user_sec_act ON user_sec_act USING btree (ua_act_id);


--
-- TOC entry 89 (OID 47644)
-- Name: x_jrn_jr_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE UNIQUE INDEX x_jrn_jr_id ON jrn USING btree (jr_id);


--
-- TOC entry 91 (OID 47645)
-- Name: fk_stock_goods_j_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_stock_goods_j_id ON stock_goods USING btree (j_id);


--
-- TOC entry 90 (OID 47646)
-- Name: fk_stock_goods_f_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_stock_goods_f_id ON stock_goods USING btree (f_id);


--
-- TOC entry 53 (OID 47647)
-- Name: tmp_pcmn_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY tmp_pcmn
    ADD CONSTRAINT tmp_pcmn_pkey PRIMARY KEY (pcm_val);


--
-- TOC entry 54 (OID 47649)
-- Name: parm_money_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY parm_money
    ADD CONSTRAINT parm_money_pkey PRIMARY KEY (pm_code);


--
-- TOC entry 56 (OID 47651)
-- Name: parm_periode_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY parm_periode
    ADD CONSTRAINT parm_periode_pkey PRIMARY KEY (p_id);


--
-- TOC entry 55 (OID 47653)
-- Name: parm_periode_p_start_key; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY parm_periode
    ADD CONSTRAINT parm_periode_p_start_key UNIQUE (p_start);


--
-- TOC entry 57 (OID 47655)
-- Name: jrn_type_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_type
    ADD CONSTRAINT jrn_type_pkey PRIMARY KEY (jrn_type_id);


--
-- TOC entry 59 (OID 47657)
-- Name: jrn_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT jrn_def_pkey PRIMARY KEY (jrn_def_id);


--
-- TOC entry 58 (OID 47659)
-- Name: jrn_def_jrn_def_name_key; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT jrn_def_jrn_def_name_key UNIQUE (jrn_def_name);


--
-- TOC entry 93 (OID 47661)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT "$1" FOREIGN KEY (jrn_def_type) REFERENCES jrn_type(jrn_type_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 62 (OID 47665)
-- Name: jrnx_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrnx
    ADD CONSTRAINT jrnx_pkey PRIMARY KEY (j_id);


--
-- TOC entry 94 (OID 47667)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrnx
    ADD CONSTRAINT "$1" FOREIGN KEY (j_poste) REFERENCES tmp_pcmn(pcm_val) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 95 (OID 47671)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrnx
    ADD CONSTRAINT "$2" FOREIGN KEY (j_jrn_def) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 63 (OID 47675)
-- Name: user_pref_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_pref
    ADD CONSTRAINT user_pref_pkey PRIMARY KEY (pref_user);


--
-- TOC entry 64 (OID 47677)
-- Name: formdef_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY formdef
    ADD CONSTRAINT formdef_pkey PRIMARY KEY (fr_id);


--
-- TOC entry 66 (OID 47679)
-- Name: form_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY form
    ADD CONSTRAINT form_pkey PRIMARY KEY (fo_id);


--
-- TOC entry 96 (OID 47681)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY form
    ADD CONSTRAINT "$1" FOREIGN KEY (fo_fr_id) REFERENCES formdef(fr_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 67 (OID 47685)
-- Name: centralized_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY centralized
    ADD CONSTRAINT centralized_pkey PRIMARY KEY (c_id);


--
-- TOC entry 97 (OID 47687)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY centralized
    ADD CONSTRAINT "$1" FOREIGN KEY (c_jrn_def) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 98 (OID 47691)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY centralized
    ADD CONSTRAINT "$2" FOREIGN KEY (c_poste) REFERENCES tmp_pcmn(pcm_val) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 71 (OID 47695)
-- Name: user_sec_jrn_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_jrn
    ADD CONSTRAINT user_sec_jrn_pkey PRIMARY KEY (uj_id);


--
-- TOC entry 99 (OID 47697)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_jrn
    ADD CONSTRAINT "$1" FOREIGN KEY (uj_jrn_id) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 73 (OID 47701)
-- Name: action_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY "action"
    ADD CONSTRAINT action_pkey PRIMARY KEY (ac_id);


--
-- TOC entry 76 (OID 47703)
-- Name: user_sec_act_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_act
    ADD CONSTRAINT user_sec_act_pkey PRIMARY KEY (ua_id);


--
-- TOC entry 100 (OID 47705)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_act
    ADD CONSTRAINT "$1" FOREIGN KEY (ua_act_id) REFERENCES "action"(ac_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 78 (OID 47709)
-- Name: jrn_action_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_action
    ADD CONSTRAINT jrn_action_pkey PRIMARY KEY (ja_id);


--
-- TOC entry 101 (OID 47711)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_action
    ADD CONSTRAINT "$1" FOREIGN KEY (ja_jrn_type) REFERENCES jrn_type(jrn_type_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 79 (OID 47715)
-- Name: fiche_def_ref_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche_def_ref
    ADD CONSTRAINT fiche_def_ref_pkey PRIMARY KEY (frd_id);


--
-- TOC entry 80 (OID 47717)
-- Name: fiche_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche_def
    ADD CONSTRAINT fiche_def_pkey PRIMARY KEY (fd_id);


--
-- TOC entry 102 (OID 47719)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche_def
    ADD CONSTRAINT "$1" FOREIGN KEY (frd_id) REFERENCES fiche_def_ref(frd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 82 (OID 47723)
-- Name: attr_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_def
    ADD CONSTRAINT attr_def_pkey PRIMARY KEY (ad_id);


--
-- TOC entry 83 (OID 47725)
-- Name: fiche_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche
    ADD CONSTRAINT fiche_pkey PRIMARY KEY (f_id);


--
-- TOC entry 103 (OID 47727)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche
    ADD CONSTRAINT "$1" FOREIGN KEY (fd_id) REFERENCES fiche_def(fd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 85 (OID 47731)
-- Name: jnt_fic_att_value_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT jnt_fic_att_value_pkey PRIMARY KEY (jft_id);


--
-- TOC entry 104 (OID 47733)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT "$1" FOREIGN KEY (f_id) REFERENCES fiche(f_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 105 (OID 47737)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 106 (OID 47741)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_value
    ADD CONSTRAINT "$1" FOREIGN KEY (jft_id) REFERENCES jnt_fic_att_value(jft_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 107 (OID 47745)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_attr
    ADD CONSTRAINT "$1" FOREIGN KEY (fd_id) REFERENCES fiche_def(fd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 108 (OID 47749)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_attr
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 87 (OID 47753)
-- Name: jrn_rapt_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_rapt
    ADD CONSTRAINT jrn_rapt_pkey PRIMARY KEY (jra_id);


--
-- TOC entry 109 (OID 47755)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn
    ADD CONSTRAINT "$1" FOREIGN KEY (jr_def_id) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 88 (OID 47759)
-- Name: jrn_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn
    ADD CONSTRAINT jrn_pkey PRIMARY KEY (jr_id, jr_def_id);


--
-- TOC entry 92 (OID 47761)
-- Name: stock_goods_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY stock_goods
    ADD CONSTRAINT stock_goods_pkey PRIMARY KEY (sg_id);


--
-- TOC entry 110 (OID 47763)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_min
    ADD CONSTRAINT "$1" FOREIGN KEY (frd_id) REFERENCES fiche_def_ref(frd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 111 (OID 47767)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_min
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


