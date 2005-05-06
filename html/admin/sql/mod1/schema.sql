--
-- PostgreSQL database dump
--

CREATE TABLE tmp_pcmn (
    pcm_val integer NOT NULL,
    pcm_lib text,
    pcm_val_parent integer DEFAULT 0,
    pcm_country character(2) DEFAULT 'BE'::bpchar NOT NULL
);


--
-- TOC entry 33 (OID 57404)
-- Name: version; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE "version" (
    val integer
);


--
-- TOC entry 2 (OID 57406)
-- Name: s_periode; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_periode
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 3 (OID 57408)
-- Name: s_currency; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_currency
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 34 (OID 57410)
-- Name: parm_money; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE parm_money (
    pm_id integer DEFAULT nextval('s_currency'::text),
    pm_code character(3) NOT NULL,
    pm_rate double precision
);


--
-- TOC entry 36 (OID 57413)
-- Name: parm_periode; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE parm_periode (
    p_id integer DEFAULT nextval('s_periode'::text) NOT NULL,
    p_start date NOT NULL,
    p_end date,
    p_exercice text DEFAULT to_char(now(), 'YYYY'::text) NOT NULL,
    p_closed boolean DEFAULT false,
    p_central boolean DEFAULT false
);


--
-- TOC entry 4 (OID 57421)
-- Name: s_jrn_def; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_def
    START 5
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 5 (OID 57423)
-- Name: s_grpt; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_grpt
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 6 (OID 57425)
-- Name: s_jrn_op; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_op
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 7 (OID 57427)
-- Name: s_jrn; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 38 (OID 57429)
-- Name: jrn_type; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jrn_type (
    jrn_type_id character(3) NOT NULL,
    jrn_desc text
);


--
-- TOC entry 40 (OID 57434)
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
-- TOC entry 8 (OID 57443)
-- Name: s_jrnx; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrnx
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 42 (OID 57445)
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
-- TOC entry 44 (OID 57456)
-- Name: user_pref; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE user_pref (
    pref_user text NOT NULL,
    pref_periode integer NOT NULL
);


--
-- TOC entry 9 (OID 57461)
-- Name: s_formdef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_formdef
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 10 (OID 57463)
-- Name: s_form; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_form
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 45 (OID 57465)
-- Name: formdef; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE formdef (
    fr_id integer DEFAULT nextval('s_formdef'::text) NOT NULL,
    fr_label text
);


--
-- TOC entry 46 (OID 57471)
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
-- TOC entry 11 (OID 57477)
-- Name: s_isup; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_isup
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 12 (OID 57479)
-- Name: s_idef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_idef
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 13 (OID 57481)
-- Name: s_centralized; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_centralized
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 48 (OID 57483)
-- Name: centralized; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE centralized (
    c_id integer DEFAULT nextval('s_centralized'::text) NOT NULL,
    c_j_id integer,
    c_date date NOT NULL,
    c_internal text NOT NULL,
    c_montant double precision NOT NULL,
    c_debit boolean DEFAULT true,
    c_jrn_def integer NOT NULL,
    c_poste integer,
    c_description text,
    c_grp integer NOT NULL,
    c_comment text,
    c_rapt text,
    c_periode integer,
    c_order integer
);


--
-- TOC entry 14 (OID 57490)
-- Name: s_user_jrn; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_user_jrn
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 15 (OID 57492)
-- Name: s_user_act; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_user_act
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 50 (OID 57494)
-- Name: user_sec_jrn; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE user_sec_jrn (
    uj_id integer DEFAULT nextval('s_user_jrn'::text) NOT NULL,
    uj_login text,
    uj_jrn_id integer,
    uj_priv text
);


--
-- TOC entry 51 (OID 57500)
-- Name: action; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE "action" (
    ac_id integer NOT NULL,
    ac_description text NOT NULL
);


--
-- TOC entry 53 (OID 57505)
-- Name: user_sec_act; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE user_sec_act (
    ua_id integer DEFAULT nextval('s_user_act'::text) NOT NULL,
    ua_login text,
    ua_act_id integer
);


--
-- TOC entry 16 (OID 57511)
-- Name: s_jrnaction; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrnaction
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 54 (OID 57513)
-- Name: jrn_action; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jrn_action (
    ja_id integer DEFAULT nextval('s_jrnaction'::text) NOT NULL,
    ja_name text NOT NULL,
    ja_desc text,
    ja_url text NOT NULL,
    ja_action text NOT NULL,
    ja_lang text DEFAULT 'FR'::text,
    ja_jrn_type character(3)
);


--
-- TOC entry 56 (OID 57520)
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
-- TOC entry 17 (OID 57526)
-- Name: s_fiche; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fiche
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 18 (OID 57528)
-- Name: s_fiche_def_ref; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fiche_def_ref
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 19 (OID 57530)
-- Name: s_fdef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fdef
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 20 (OID 57532)
-- Name: s_attr_def; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_attr_def
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 21 (OID 57534)
-- Name: s_jnt_fic_att_value; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jnt_fic_att_value
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 58 (OID 57536)
-- Name: fiche_def_ref; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE fiche_def_ref (
    frd_id integer DEFAULT nextval('s_fiche_def_ref'::text) NOT NULL,
    frd_text text,
    frd_class_base integer
);


--
-- TOC entry 60 (OID 57542)
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
-- TOC entry 62 (OID 57549)
-- Name: attr_def; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_def (
    ad_id integer DEFAULT nextval('s_attr_def'::text) NOT NULL,
    ad_text text
);


--
-- TOC entry 64 (OID 57555)
-- Name: attr_min; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_min (
    frd_id integer,
    ad_id integer
);


--
-- TOC entry 66 (OID 57557)
-- Name: fiche; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE fiche (
    f_id integer DEFAULT nextval('s_fiche'::text) NOT NULL,
    fd_id integer
);


--
-- TOC entry 68 (OID 57560)
-- Name: jnt_fic_att_value; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jnt_fic_att_value (
    jft_id integer DEFAULT nextval('s_jnt_fic_att_value'::text) NOT NULL,
    f_id integer,
    ad_id integer
);


--
-- TOC entry 70 (OID 57563)
-- Name: attr_value; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE attr_value (
    jft_id integer,
    av_text text
);


--
-- TOC entry 71 (OID 57568)
-- Name: jnt_fic_attr; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jnt_fic_attr (
    fd_id integer,
    ad_id integer
);


--
-- TOC entry 73 (OID 57572)
-- Name: vw_fiche_attr; Type: VIEW; Schema: public; Owner: phpcompta
--

CREATE VIEW vw_fiche_attr AS
    SELECT a.f_id, fd_id, a.av_text AS vw_name, b.av_text AS vw_sell, c.av_text AS vw_buy, d.av_text AS tva_code, tva_id, tva_rate, tva_label, e.av_text AS vw_addr, f.av_text AS vw_cp, frd_id FROM ((((((((SELECT f_id, fd_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 1)) a LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 6)) b ON ((a.f_id = b.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 7)) c ON ((a.f_id = c.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 2)) d ON ((a.f_id = d.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 14)) e ON ((a.f_id = e.f_id))) LEFT JOIN (SELECT f_id, av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (ad_id = 15)) f ON ((a.f_id = f.f_id))) LEFT JOIN tva_rate ON ((d.av_text = (tva_rate.tva_id)::text))) JOIN fiche_def USING (fd_id));


--
-- TOC entry 22 (OID 57574)
-- Name: s_stock_goods; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_stock_goods
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 23 (OID 57576)
-- Name: s_jrn_rapt; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_rapt
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 74 (OID 57578)
-- Name: jrn_rapt; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE jrn_rapt (
    jra_id integer DEFAULT nextval('s_jrn_rapt'::text) NOT NULL,
    jr_id integer NOT NULL,
    jra_concerned integer NOT NULL
);


--
-- TOC entry 76 (OID 57581)
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
    jr_valid boolean DEFAULT true,
    jr_opid integer,
    jr_c_opid integer,
    jr_pj oid,
    jr_pj_name text,
    jr_pj_type text
);


--
-- TOC entry 78 (OID 57589)
-- Name: stock_goods; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE stock_goods (
    sg_id integer DEFAULT nextval('s_stock_goods'::text) NOT NULL,
    j_id integer,
    f_id integer NOT NULL,
    sg_code text,
    sg_quantity integer DEFAULT 0,
    sg_type character(1) DEFAULT 'c'::bpchar NOT NULL,
    sg_date date,
    sg_tech_date date DEFAULT now(),
    sg_tech_user text,
    CONSTRAINT stock_goods_sg_type CHECK (((sg_type = 'c'::bpchar) OR (sg_type = 'd'::bpchar)))
);


--
-- TOC entry 24 (OID 58781)
-- Name: s_central; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_central
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 25 (OID 58784)
-- Name: s_central_order; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_central_order
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 26 (OID 58786)
-- Name: s_internal; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_internal
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 27 (OID 58793)
-- Name: s_jrn_4; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_4
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 28 (OID 58795)
-- Name: s_jrn_1; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_1
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 29 (OID 58797)
-- Name: s_jrn_3; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_3
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 30 (OID 58799)
-- Name: s_jrn_2; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_2
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 104 (OID 57599)
-- Name: x_jrn_jr_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE UNIQUE INDEX x_jrn_jr_id ON jrn USING btree (jr_id);


--
-- TOC entry 106 (OID 57600)
-- Name: fk_stock_goods_j_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_stock_goods_j_id ON stock_goods USING btree (j_id);


--
-- TOC entry 105 (OID 57601)
-- Name: fk_stock_goods_f_id; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX fk_stock_goods_f_id ON stock_goods USING btree (f_id);


--
-- TOC entry 88 (OID 58783)
-- Name: x_poste; Type: INDEX; Schema: public; Owner: phpcompta
--

CREATE INDEX x_poste ON jrnx USING btree (j_poste);


--
-- TOC entry 80 (OID 57602)
-- Name: tmp_pcmn_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY tmp_pcmn
    ADD CONSTRAINT tmp_pcmn_pkey PRIMARY KEY (pcm_val);


--
-- TOC entry 81 (OID 57604)
-- Name: parm_money_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY parm_money
    ADD CONSTRAINT parm_money_pkey PRIMARY KEY (pm_code);


--
-- TOC entry 83 (OID 57606)
-- Name: parm_periode_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY parm_periode
    ADD CONSTRAINT parm_periode_pkey PRIMARY KEY (p_id);


--
-- TOC entry 82 (OID 57608)
-- Name: parm_periode_p_start_key; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY parm_periode
    ADD CONSTRAINT parm_periode_p_start_key UNIQUE (p_start);


--
-- TOC entry 84 (OID 57610)
-- Name: jrn_type_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_type
    ADD CONSTRAINT jrn_type_pkey PRIMARY KEY (jrn_type_id);


--
-- TOC entry 86 (OID 57612)
-- Name: jrn_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT jrn_def_pkey PRIMARY KEY (jrn_def_id);


--
-- TOC entry 85 (OID 57614)
-- Name: jrn_def_jrn_def_name_key; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT jrn_def_jrn_def_name_key UNIQUE (jrn_def_name);


--
-- TOC entry 87 (OID 57616)
-- Name: jrnx_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrnx
    ADD CONSTRAINT jrnx_pkey PRIMARY KEY (j_id);


--
-- TOC entry 89 (OID 57618)
-- Name: user_pref_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_pref
    ADD CONSTRAINT user_pref_pkey PRIMARY KEY (pref_user);


--
-- TOC entry 90 (OID 57620)
-- Name: formdef_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY formdef
    ADD CONSTRAINT formdef_pkey PRIMARY KEY (fr_id);


--
-- TOC entry 91 (OID 57622)
-- Name: form_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY form
    ADD CONSTRAINT form_pkey PRIMARY KEY (fo_id);


--
-- TOC entry 92 (OID 57624)
-- Name: centralized_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY centralized
    ADD CONSTRAINT centralized_pkey PRIMARY KEY (c_id);


--
-- TOC entry 93 (OID 57626)
-- Name: user_sec_jrn_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_jrn
    ADD CONSTRAINT user_sec_jrn_pkey PRIMARY KEY (uj_id);


--
-- TOC entry 94 (OID 57628)
-- Name: action_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY "action"
    ADD CONSTRAINT action_pkey PRIMARY KEY (ac_id);


--
-- TOC entry 95 (OID 57630)
-- Name: user_sec_act_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_act
    ADD CONSTRAINT user_sec_act_pkey PRIMARY KEY (ua_id);


--
-- TOC entry 96 (OID 57632)
-- Name: jrn_action_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_action
    ADD CONSTRAINT jrn_action_pkey PRIMARY KEY (ja_id);


--
-- TOC entry 97 (OID 57634)
-- Name: fiche_def_ref_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche_def_ref
    ADD CONSTRAINT fiche_def_ref_pkey PRIMARY KEY (frd_id);


--
-- TOC entry 98 (OID 57636)
-- Name: fiche_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche_def
    ADD CONSTRAINT fiche_def_pkey PRIMARY KEY (fd_id);


--
-- TOC entry 99 (OID 57638)
-- Name: attr_def_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_def
    ADD CONSTRAINT attr_def_pkey PRIMARY KEY (ad_id);


--
-- TOC entry 100 (OID 57640)
-- Name: fiche_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche
    ADD CONSTRAINT fiche_pkey PRIMARY KEY (f_id);


--
-- TOC entry 101 (OID 57642)
-- Name: jnt_fic_att_value_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT jnt_fic_att_value_pkey PRIMARY KEY (jft_id);


--
-- TOC entry 102 (OID 57644)
-- Name: jrn_rapt_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_rapt
    ADD CONSTRAINT jrn_rapt_pkey PRIMARY KEY (jra_id);


--
-- TOC entry 103 (OID 57646)
-- Name: jrn_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn
    ADD CONSTRAINT jrn_pkey PRIMARY KEY (jr_id, jr_def_id);


--
-- TOC entry 107 (OID 57648)
-- Name: stock_goods_pkey; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY stock_goods
    ADD CONSTRAINT stock_goods_pkey PRIMARY KEY (sg_id);


--
-- TOC entry 108 (OID 57650)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT "$1" FOREIGN KEY (jrn_def_type) REFERENCES jrn_type(jrn_type_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 109 (OID 57654)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrnx
    ADD CONSTRAINT "$1" FOREIGN KEY (j_poste) REFERENCES tmp_pcmn(pcm_val) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 110 (OID 57658)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrnx
    ADD CONSTRAINT "$2" FOREIGN KEY (j_jrn_def) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 111 (OID 57662)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY form
    ADD CONSTRAINT "$1" FOREIGN KEY (fo_fr_id) REFERENCES formdef(fr_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 112 (OID 57666)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY centralized
    ADD CONSTRAINT "$1" FOREIGN KEY (c_jrn_def) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 113 (OID 57670)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY centralized
    ADD CONSTRAINT "$2" FOREIGN KEY (c_poste) REFERENCES tmp_pcmn(pcm_val) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 114 (OID 57674)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_jrn
    ADD CONSTRAINT "$1" FOREIGN KEY (uj_jrn_id) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 115 (OID 57678)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY user_sec_act
    ADD CONSTRAINT "$1" FOREIGN KEY (ua_act_id) REFERENCES "action"(ac_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 116 (OID 57682)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn_action
    ADD CONSTRAINT "$1" FOREIGN KEY (ja_jrn_type) REFERENCES jrn_type(jrn_type_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 117 (OID 57686)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche_def
    ADD CONSTRAINT "$1" FOREIGN KEY (frd_id) REFERENCES fiche_def_ref(frd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 118 (OID 57690)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_min
    ADD CONSTRAINT "$1" FOREIGN KEY (frd_id) REFERENCES fiche_def_ref(frd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 119 (OID 57694)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_min
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 120 (OID 57698)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY fiche
    ADD CONSTRAINT "$1" FOREIGN KEY (fd_id) REFERENCES fiche_def(fd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 121 (OID 57702)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT "$1" FOREIGN KEY (f_id) REFERENCES fiche(f_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 122 (OID 57706)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 123 (OID 57710)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY attr_value
    ADD CONSTRAINT "$1" FOREIGN KEY (jft_id) REFERENCES jnt_fic_att_value(jft_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 124 (OID 57714)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_attr
    ADD CONSTRAINT "$1" FOREIGN KEY (fd_id) REFERENCES fiche_def(fd_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 125 (OID 57718)
-- Name: $2; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jnt_fic_attr
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 126 (OID 57722)
-- Name: $1; Type: CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY jrn
    ADD CONSTRAINT "$1" FOREIGN KEY (jr_def_id) REFERENCES jrn_def(jrn_def_id) ON UPDATE NO ACTION ON DELETE NO ACTION;


--
-- TOC entry 32 (OID 57397)
-- Name: TABLE tmp_pcmn; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE tmp_pcmn IS 'Plan comptable minimum normalisé';


--
-- TOC entry 35 (OID 57410)
-- Name: TABLE parm_money; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE parm_money IS 'Currency conversion';


--
-- TOC entry 37 (OID 57413)
-- Name: TABLE parm_periode; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE parm_periode IS 'Periode definition';


--
-- TOC entry 39 (OID 57429)
-- Name: TABLE jrn_type; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE jrn_type IS 'Type of journal (Sell, Buy, Financial...)';


--
-- TOC entry 41 (OID 57434)
-- Name: TABLE jrn_def; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE jrn_def IS 'Definition of a journal, his properties';


--
-- TOC entry 43 (OID 57445)
-- Name: TABLE jrnx; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE jrnx IS 'Journal: content one line for each accountancy writing';


--
-- TOC entry 47 (OID 57471)
-- Name: TABLE form; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE form IS 'Forms content';


--
-- TOC entry 49 (OID 57483)
-- Name: TABLE centralized; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE centralized IS 'The centralized journal';


--
-- TOC entry 52 (OID 57500)
-- Name: TABLE "action"; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE "action" IS 'The different privileges';


--
-- TOC entry 55 (OID 57513)
-- Name: TABLE jrn_action; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE jrn_action IS 'Possible action when we are in journal (menu)';


--
-- TOC entry 57 (OID 57520)
-- Name: TABLE tva_rate; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE tva_rate IS 'Rate of vat';


--
-- TOC entry 59 (OID 57536)
-- Name: TABLE fiche_def_ref; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE fiche_def_ref IS 'Family Cards definition';


--
-- TOC entry 61 (OID 57542)
-- Name: TABLE fiche_def; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE fiche_def IS 'Cards definition';


--
-- TOC entry 63 (OID 57549)
-- Name: TABLE attr_def; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE attr_def IS 'The available attributs for the cards';


--
-- TOC entry 65 (OID 57555)
-- Name: TABLE attr_min; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE attr_min IS 'The value of  attributs for the cards';


--
-- TOC entry 67 (OID 57557)
-- Name: TABLE fiche; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE fiche IS 'Cards';


--
-- TOC entry 69 (OID 57560)
-- Name: TABLE jnt_fic_att_value; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE jnt_fic_att_value IS 'join between the card and the attribut definition';


--
-- TOC entry 72 (OID 57568)
-- Name: TABLE jnt_fic_attr; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE jnt_fic_attr IS 'join between the family card and the attribut definition';


--
-- TOC entry 75 (OID 57578)
-- Name: TABLE jrn_rapt; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE jrn_rapt IS 'Rapprochement between operation';


--
-- TOC entry 77 (OID 57581)
-- Name: TABLE jrn; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE jrn IS 'Journal: content one line for a group of accountancy writing';


--
-- TOC entry 79 (OID 57589)
-- Name: TABLE stock_goods; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE stock_goods IS 'About the goods';


