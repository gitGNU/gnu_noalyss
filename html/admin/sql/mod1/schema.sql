--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;



--
-- Name: poste_comptable; Type: DOMAIN; Schema: public; Owner: phpcompta
--

CREATE DOMAIN poste_comptable AS numeric(25,0);


--
-- Name: account_add(poste_comptable, character varying); Type: FUNCTION; Schema: public; Owner: phpcompta
--

SET default_tablespace = '';

SET default_with_oids = true;

--
-- Name: action; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE "action" (
    ac_id integer NOT NULL,
    ac_description text NOT NULL
);




--
-- Name: TABLE "action"; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE "action" IS 'The different privileges';


--
-- Name: action_gestion; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE action_gestion (
    ag_id serial NOT NULL,
    ag_type integer,
    f_id_dest integer NOT NULL,
    f_id_exp integer NOT NULL,
    ag_title character varying(70),
    ag_timestamp timestamp without time zone DEFAULT now(),
    ag_cal character(1) DEFAULT 'C'::bpchar,
    ag_ref_ag_id integer,
    ag_comment text,
    ag_ref text
);




--
-- Name: TABLE action_gestion; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE action_gestion IS 'Action for Managing';


--
-- Name: attr_def; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE attr_def (
    ad_id integer DEFAULT nextval('s_attr_def'::text) NOT NULL,
    ad_text text
);




--
-- Name: TABLE attr_def; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE attr_def IS 'The available attributs for the cards';


--
-- Name: attr_min; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE attr_min (
    frd_id integer,
    ad_id integer
);




--
-- Name: TABLE attr_min; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE attr_min IS 'The value of  attributs for the cards';


--
-- Name: attr_value; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE attr_value (
    jft_id integer,
    av_text text
);




--
-- Name: centralized; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE centralized (
    c_id integer DEFAULT nextval('s_centralized'::text) NOT NULL,
    c_j_id integer,
    c_date date NOT NULL,
    c_internal text NOT NULL,
    c_montant numeric(20,4) NOT NULL,
    c_debit boolean DEFAULT true,
    c_jrn_def integer NOT NULL,
    c_poste poste_comptable,
    c_description text,
    c_grp integer NOT NULL,
    c_comment text,
    c_rapt text,
    c_periode integer,
    c_order integer
);




--
-- Name: TABLE centralized; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE centralized IS 'The centralized journal';


--
-- Name: document; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE document (
    d_id serial NOT NULL,
    ag_id integer NOT NULL,
    d_lob oid,
    d_number bigint NOT NULL,
    d_filename text,
    d_mimetype text,
    d_state integer
);




--
-- Name: TABLE document; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE document IS 'This table contains all the documents : summary and lob files';


--
-- Name: document_modele; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE document_modele (
    md_id serial NOT NULL,
    md_name text NOT NULL,
    md_lob oid,
    md_type integer NOT NULL,
    md_filename text,
    md_mimetype text
);




--
-- Name: TABLE document_modele; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE document_modele IS ' contains all the template for the  documents';


--
-- Name: document_seq; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE document_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: SEQUENCE document_seq; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON SEQUENCE document_seq IS 'Sequence for the sequence bound to the document modele';


--
-- Name: document_state; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE document_state (
    s_id serial NOT NULL,
    s_value character varying(50) NOT NULL
);




--
-- Name: TABLE document_state; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE document_state IS 'State of the document';


--
-- Name: document_type; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE document_type (
    dt_id serial NOT NULL,
    dt_value character varying(80)
);




--
-- Name: TABLE document_type; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE document_type IS 'Type of document : meeting, invoice,...';


--
-- Name: fiche; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE fiche (
    f_id integer DEFAULT nextval('s_fiche'::text) NOT NULL,
    fd_id integer
);




--
-- Name: TABLE fiche; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE fiche IS 'Cards';


--
-- Name: fiche_def; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE fiche_def (
    fd_id integer DEFAULT nextval('s_fdef'::text) NOT NULL,
    fd_class_base poste_comptable,
    fd_label text NOT NULL,
    fd_create_account boolean DEFAULT false,
    frd_id integer NOT NULL
);




--
-- Name: TABLE fiche_def; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE fiche_def IS 'Cards definition';


--
-- Name: fiche_def_ref; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE fiche_def_ref (
    frd_id integer DEFAULT nextval('s_fiche_def_ref'::text) NOT NULL,
    frd_text text,
    frd_class_base integer
);




--
-- Name: TABLE fiche_def_ref; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE fiche_def_ref IS 'Family Cards definition';


--
-- Name: form; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE form (
    fo_id integer DEFAULT nextval('s_form'::text) NOT NULL,
    fo_fr_id integer,
    fo_pos integer,
    fo_label text,
    fo_formula text
);




--
-- Name: TABLE form; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE form IS 'Forms content';


--
-- Name: format_csv_banque; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE format_csv_banque (
    name text NOT NULL,
    include_file text NOT NULL
);




--
-- Name: formdef; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE formdef (
    fr_id integer DEFAULT nextval('s_formdef'::text) NOT NULL,
    fr_label text
);




--
-- Name: import_tmp; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE import_tmp (
    code text,
    date_exec date,
    date_valeur date,
    montant text,
    devise text,
    compte_ordre text,
    detail text,
    num_compte text,
    poste_comptable text,
    status char(1) default 'w' not null check (status in ('t','d','w')),
    bq_account integer NOT NULL,
    jrn integer NOT NULL

);




--
-- Name: invoice; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE invoice (
    iv_id integer DEFAULT nextval('s_invoice'::text) NOT NULL,
    iv_name text NOT NULL,
    iv_file oid
);




--
-- Name: jnt_fic_att_value; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE jnt_fic_att_value (
    jft_id integer DEFAULT nextval('s_jnt_fic_att_value'::text) NOT NULL,
    f_id integer,
    ad_id integer
);




--
-- Name: TABLE jnt_fic_att_value; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE jnt_fic_att_value IS 'join between the card and the attribut definition';


--
-- Name: jnt_fic_attr; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE jnt_fic_attr (
    fd_id integer,
    ad_id integer
);




--
-- Name: TABLE jnt_fic_attr; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE jnt_fic_attr IS 'join between the family card and the attribut definition';


--
-- Name: jrn; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE jrn (
    jr_id integer DEFAULT nextval('s_jrn'::text) NOT NULL,
    jr_def_id integer NOT NULL,
    jr_montant numeric(20,4) NOT NULL,
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
-- Name: TABLE jrn; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE jrn IS 'Journal: content one line for a group of accountancy writing';


--
-- Name: jrn_action; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
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
-- Name: TABLE jrn_action; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE jrn_action IS 'Possible action when we are in journal (menu)';


--
-- Name: jrn_def; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
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
-- Name: TABLE jrn_def; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE jrn_def IS 'Definition of a journal, his properties';


--
-- Name: jrn_rapt; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE jrn_rapt (
    jra_id integer DEFAULT nextval('s_jrn_rapt'::text) NOT NULL,
    jr_id integer NOT NULL,
    jra_concerned integer NOT NULL
);




--
-- Name: TABLE jrn_rapt; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE jrn_rapt IS 'Rapprochement between operation';


--
-- Name: jrn_type; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE jrn_type (
    jrn_type_id character(3) NOT NULL,
    jrn_desc text
);




--
-- Name: TABLE jrn_type; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE jrn_type IS 'Type of journal (Sell, Buy, Financial...)';


--
-- Name: jrnx; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE jrnx (
    j_id integer DEFAULT nextval('s_jrn_op'::text) NOT NULL,
    j_date date DEFAULT now(),
    j_montant numeric(20,4) DEFAULT 0,
    j_poste poste_comptable NOT NULL,
    j_grpt integer NOT NULL,
    j_rapt text,
    j_jrn_def integer NOT NULL,
    j_debit boolean DEFAULT true,
    j_text text,
    j_centralized boolean DEFAULT false,
    j_internal text,
    j_tech_user text NOT NULL,
    j_tech_date timestamp without time zone DEFAULT now() NOT NULL,
    j_tech_per integer NOT NULL,
    j_qcode text
);




--
-- Name: TABLE jrnx; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE jrnx IS 'Journal: content one line for each accountancy writing';


--
-- Name: parameter; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE parameter (
    pr_id text NOT NULL,
    pr_value text
);




--
-- Name: parm_code; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE parm_code (
    p_code text NOT NULL,
    p_value text,
    p_comment text
);




--
-- Name: parm_money; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE parm_money (
    pm_id integer DEFAULT nextval('s_currency'::text),
    pm_code character(3) NOT NULL,
    pm_rate numeric(20,4)
);




--
-- Name: TABLE parm_money; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE parm_money IS 'Currency conversion';


--
-- Name: parm_periode; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE parm_periode (
    p_id integer DEFAULT nextval('s_periode'::text) NOT NULL,
    p_start date NOT NULL,
    p_end date,
    p_exercice text DEFAULT to_char(now(), 'YYYY'::text) NOT NULL,
    p_closed boolean DEFAULT false,
    p_central boolean DEFAULT false,
    CONSTRAINT parm_periode_check CHECK ((p_end >= p_start))
);




--
-- Name: TABLE parm_periode; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE parm_periode IS 'Periode definition';


--
-- Name: quant_sold; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE quant_sold (
    qs_id integer DEFAULT nextval('s_quantity'::text) NOT NULL,
    qs_internal text NOT NULL,
    qs_fiche integer NOT NULL,
    qs_quantite integer NOT NULL,
    qs_price numeric(20,4),
    qs_vat numeric(20,4),
    qs_vat_code integer,
    qs_client integer NOT NULL
);




--
-- Name: TABLE quant_sold; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE quant_sold IS 'Contains about invoice for customer';


--
-- Name: s_attr_def; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_attr_def
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE s_cbc
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;



--
-- Name: s_central; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_central
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_central_order; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_central_order
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_centralized; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_centralized
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_currency; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_currency
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_fdef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fdef
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_fiche; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fiche
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_fiche_def_ref; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fiche_def_ref
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_form; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_form
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_formdef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_formdef
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_grpt; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_grpt
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_idef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_idef
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_internal; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_internal
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_invoice; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_invoice
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_isup; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_isup
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_jnt_fic_att_value; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jnt_fic_att_value
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_jrn; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;





--
-- Name: s_jrn_def; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_def
    START WITH 5
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_jrn_op; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_op
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_jrn_rapt; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_rapt
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_jrnaction; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrnaction
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_jrnx; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrnx
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_periode; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_periode
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_quantity; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_quantity
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_stock_goods; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_stock_goods
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_user_act; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_user_act
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;




--
-- Name: s_user_jrn; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_user_jrn
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;








--
-- Name: stock_goods; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
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
-- Name: TABLE stock_goods; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE stock_goods IS 'About the goods';


--
-- Name: tmp_pcmn; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE tmp_pcmn (
    pcm_val poste_comptable NOT NULL,
    pcm_lib text,
    pcm_val_parent poste_comptable DEFAULT 0,
    pcm_country character(2) DEFAULT 'BE'::bpchar NOT NULL
);




--
-- Name: TABLE tmp_pcmn; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE tmp_pcmn IS 'Plan comptable minimum normalisé';


--
-- Name: tva_rate; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE tva_rate (
    tva_id integer NOT NULL,
    tva_label text NOT NULL,
    tva_rate numeric(8,4) DEFAULT 0.0 NOT NULL,
    tva_comment text,
    tva_poste text
);




--
-- Name: TABLE tva_rate; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE tva_rate IS 'Rate of vat';


--
-- Name: user_local_pref; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE user_local_pref (
    user_id text NOT NULL,
    parameter_type text NOT NULL,
    parameter_value text
);




--
-- Name: TABLE user_local_pref; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE user_local_pref IS 'The user''s local parameter ';


--
-- Name: COLUMN user_local_pref.user_id; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON COLUMN user_local_pref.user_id IS 'user''s login ';


--
-- Name: COLUMN user_local_pref.parameter_type; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON COLUMN user_local_pref.parameter_type IS 'the type of parameter ';


--
-- Name: COLUMN user_local_pref.parameter_value; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON COLUMN user_local_pref.parameter_value IS 'the value of parameter ';


--
-- Name: user_sec_act; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE user_sec_act (
    ua_id integer DEFAULT nextval('s_user_act'::text) NOT NULL,
    ua_login text,
    ua_act_id integer
);




--
-- Name: user_sec_jrn; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE user_sec_jrn (
    uj_id integer DEFAULT nextval('s_user_jrn'::text) NOT NULL,
    uj_login text,
    uj_jrn_id integer,
    uj_priv text
);




--
-- Name: version; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE version (
    val integer
);




--
-- Name: vw_client; Type: VIEW; Schema: public; Owner: phpcompta
--

CREATE VIEW vw_client AS
    SELECT a.f_id, a.av_text AS name, a1.av_text AS quick_code, b.av_text AS tva_num, c.av_text AS poste_comptable, d.av_text AS rue, e.av_text AS code_postal, f.av_text AS pays, g.av_text AS telephone, h.av_text AS email FROM (((((((((SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 1)) a JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 13)) b USING (f_id)) JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 23)) a1 USING (f_id)) JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 5)) c USING (f_id)) JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 14)) d USING (f_id)) JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 15)) e USING (f_id)) JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 16)) f USING (f_id)) JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 17)) g USING (f_id)) LEFT JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 18)) h USING (f_id)) WHERE (a.frd_id = 9);




--
-- Name: vw_fiche_attr; Type: VIEW; Schema: public; Owner: phpcompta
--

CREATE VIEW vw_fiche_attr AS
    SELECT a.f_id, a.fd_id, a.av_text AS vw_name, b.av_text AS vw_sell, c.av_text AS vw_buy, d.av_text AS tva_code, tva_rate.tva_id, tva_rate.tva_rate, tva_rate.tva_label, e.av_text AS vw_addr, f.av_text AS vw_cp, j.av_text AS quick_code, fiche_def.frd_id FROM (((((((((SELECT fiche.f_id, fiche.fd_id, attr_value.av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (jnt_fic_att_value.ad_id = 1)) a LEFT JOIN (SELECT fiche.f_id, attr_value.av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (jnt_fic_att_value.ad_id = 6)) b ON ((a.f_id = b.f_id))) LEFT JOIN (SELECT fiche.f_id, attr_value.av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (jnt_fic_att_value.ad_id = 7)) c ON ((a.f_id = c.f_id))) LEFT JOIN (SELECT fiche.f_id, attr_value.av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (jnt_fic_att_value.ad_id = 2)) d ON ((a.f_id = d.f_id))) LEFT JOIN (SELECT fiche.f_id, attr_value.av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (jnt_fic_att_value.ad_id = 14)) e ON ((a.f_id = e.f_id))) LEFT JOIN (SELECT fiche.f_id, attr_value.av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (jnt_fic_att_value.ad_id = 15)) f ON ((a.f_id = f.f_id))) LEFT JOIN (SELECT fiche.f_id, attr_value.av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (jnt_fic_att_value.ad_id = 23)) j ON ((a.f_id = j.f_id))) LEFT JOIN tva_rate ON ((d.av_text = (tva_rate.tva_id)::text))) JOIN fiche_def USING (fd_id));




--
-- Name: vw_fiche_def; Type: VIEW; Schema: public; Owner: phpcompta
--

CREATE VIEW vw_fiche_def AS
    SELECT jnt_fic_attr.fd_id, jnt_fic_attr.ad_id, attr_def.ad_text, attr_value.av_text, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def.frd_id FROM (((((jnt_fic_att_value JOIN attr_value USING (jft_id)) JOIN fiche USING (f_id)) JOIN jnt_fic_attr USING (fd_id)) JOIN attr_def ON ((attr_def.ad_id = jnt_fic_attr.ad_id))) JOIN fiche_def USING (fd_id));




--
-- Name: VIEW vw_fiche_def; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON VIEW vw_fiche_def IS 'all the attributs for  card family';


--
-- Name: vw_fiche_min; Type: VIEW; Schema: public; Owner: phpcompta
--

CREATE VIEW vw_fiche_min AS
    SELECT attr_min.frd_id, attr_min.ad_id, attr_def.ad_text, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base FROM ((attr_min JOIN attr_def USING (ad_id)) JOIN fiche_def_ref USING (frd_id));




--
-- Name: VIEW vw_fiche_min; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON VIEW vw_fiche_min IS 'minimum attribut for reference card';


--
-- Name: vw_poste_qcode; Type: VIEW; Schema: public; Owner: phpcompta
--

CREATE VIEW vw_poste_qcode AS
    SELECT a.f_id, a.av_text AS j_poste, b.av_text AS j_qcode FROM ((SELECT jnt_fic_att_value.f_id, attr_value.av_text FROM (attr_value JOIN jnt_fic_att_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 5)) a JOIN (SELECT jnt_fic_att_value.f_id, attr_value.av_text FROM (attr_value JOIN jnt_fic_att_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 23)) b USING (f_id));




--
-- Name: vw_supplier; Type: VIEW; Schema: public; Owner: phpcompta
--

CREATE VIEW vw_supplier AS
    SELECT a.f_id, a.av_text AS name, a1.av_text AS quick_code, b.av_text AS tva_num, c.av_text AS poste_comptable, d.av_text AS rue, e.av_text AS code_postal, f.av_text AS pays, g.av_text AS telephone, h.av_text AS email FROM (((((((((SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 1)) a JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 13)) b USING (f_id)) JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 23)) a1 USING (f_id)) JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 5)) c USING (f_id)) JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 14)) d USING (f_id)) JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 15)) e USING (f_id)) JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 16)) f USING (f_id)) JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 17)) g USING (f_id)) LEFT JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 18)) h USING (f_id)) WHERE (a.frd_id = 8);




--
-- Name: attr_value_jft_id; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE UNIQUE INDEX attr_value_jft_id ON attr_value USING btree (jft_id);




--
-- Name: fk_stock_goods_f_id; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE INDEX fk_stock_goods_f_id ON stock_goods USING btree (f_id);




--
-- Name: fk_stock_goods_j_id; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE INDEX fk_stock_goods_j_id ON stock_goods USING btree (j_id);




--
-- Name: idx_case; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE UNIQUE INDEX idx_case ON format_csv_banque USING btree (upper(name));




--
-- Name: idx_qs_internal; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE INDEX idx_qs_internal ON quant_sold USING btree (qs_internal);




--
-- Name: ix_iv_name; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE UNIQUE INDEX ix_iv_name ON invoice USING btree (upper(iv_name));




--
-- Name: k_ag_ref; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE UNIQUE INDEX k_ag_ref ON action_gestion USING btree (ag_ref);




--
-- Name: x_jrn_jr_id; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE UNIQUE INDEX x_jrn_jr_id ON jrn USING btree (jr_id);




--
-- Name: x_poste; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE INDEX x_poste ON jrnx USING btree (j_poste);


CREATE FUNCTION account_add(p_id poste_comptable, p_name character varying) RETURNS void
    AS $$
declare
	nParent tmp_pcmn.pcm_val_parent%type;
	nCount integer;
begin
	select count(*) into nCount from tmp_pcmn where pcm_val=p_id;
	if nCount = 0 then
		nParent=account_parent(p_id);
		insert into tmp_pcmn (pcm_val,pcm_lib,pcm_val_parent)
			values (p_id, p_name,nParent);
	end if;
return;
end ;
$$
    LANGUAGE plpgsql;




--
-- Name: account_auto(integer); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION account_auto(p_fd_id integer) RETURNS boolean
    AS $$
declare
	l_auto bool;
begin

	select fd_create_account into l_auto from fiche_def where fd_id=p_fd_id;
	if l_auto is null then
		l_auto:=false;
	end if;
	return l_auto;
end;
$$
    LANGUAGE plpgsql;




--
-- Name: account_compute(integer); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION account_compute(p_f_id integer) RETURNS poste_comptable
    AS $$
declare
	class_base poste_comptable;
	maxcode int8;
begin
 -- Get the class base
	select fd_class_base into class_base 
	from
		fiche_def join fiche using (fd_id)
	where 
		f_id=p_f_id;
	raise notice 'class base %',class_base;
	select max(pcm_val) into maxcode from tmp_pcmn where pcm_val = class_base;
	if maxcode = class_base then
		maxcode=class_base*1000+1;
	end if;
	raise notice 'Max code %',maxcode;
return maxcode+1;
end;
$$
    LANGUAGE plpgsql;




--
-- Name: account_insert(integer, poste_comptable); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION account_insert(p_f_id integer, p_account poste_comptable) RETURNS integer
    AS $$
declare
nParent tmp_pcmn.pcm_val_parent%type;
sName varchar;
nNew tmp_pcmn.pcm_val%type;
bAuto bool;
nFd_id integer;
nCount integer;
begin
	
	-- if p_value empty
	if length(trim(p_account)) != 0 then
	-- does the account exist ?
		select *  into nCount from tmp_pcmn where pcm_val=p_account;
		if nCount !=0  then
			-- retrieve name
			select av_text into sName from 
				attr_value join jnt_fic_att_value using (jft_id)
			where	
			ad_id=1 and f_id=p_f_id;
			-- get parent
			nParent:=account_parent(p_account);
			-- account doesn't exist we need to add id
			insert into tmp_pcmn(pcm_val,pcm_lib,pcm_val_parent) 
				values (p_account,sName,nParent);
			-- insert as card's attribute
			perform attribut_insert(p_f_id,5,to_char(nNew,'999999999999'));
	
		end if;		
	else 
		select fd_id into nFd_id from fiche where f_id=p_f_id;
		bAuto:= account_auto(nFd_id);

		if bAuto = true then
			-- create automatically the account
			-- compute the next account
			nNew:=account_compute(p_f_id);
raise debug 'nNew %', nNew;
			-- retrieve name
			select av_text into sName from 
			attr_value join jnt_fic_att_value using (jft_id)
			where
			ad_id=1 and f_id=p_f_id;

			-- get parent
			nParent:=account_parent(nNew);
			-- account doesn't exist we need to add id
			perform account_add  (nNew,sName);
			-- insert as card's attribute
			perform attribut_insert(p_f_id,5,to_char(nNew,'999999999999'));
	
		else 
			 perform attribut_insert(p_f_id,5,null);
		end if;

	end if;
		
return 0;
end;
$$
    LANGUAGE plpgsql;




--
-- Name: account_parent(poste_comptable); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION account_parent(p_account poste_comptable) RETURNS poste_comptable
    AS $$
declare
	nParent tmp_pcmn.pcm_val_parent%type;
	sParent varchar;
	nCount integer;
begin
	sParent:=to_char(p_account,'9999999999999999');
	sParent:=trim(sParent);
	nParent:=0;
	while nParent = 0 loop
		select count(*) into nCount
		from tmp_pcmn
		where
		pcm_val = to_number(sParent,'9999999999999999');
		if nCount != 0 then
			nParent:=to_number(sParent,'9999999999999999');
		end if;
		sParent:= substr(sParent,1,length(sParent)-1);
		if length(sParent) <= 0 then	
			raise exception 'Impossible de trouver le compte parent pour %',p_account;
		end if;

	end loop;

	return nParent;
end;
$$
    LANGUAGE plpgsql;




--
-- Name: account_update(integer, poste_comptable); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION account_update(p_f_id integer, p_account poste_comptable) RETURNS integer
    AS $$
declare
nMax fiche.f_id%type;
nCount integer;
nParent tmp_pcmn.pcm_val_parent%type;
sName varchar;
nJft_id attr_value.jft_id%type;
begin
	
	-- if p_value empty
	if length(trim(p_account)) != 0 then
	-- does the account exist ?
		select count(*) into nCount from tmp_pcmn where pcm_val=p_account;
		if nCount = 0 then
		-- retrieve name
		select av_text into sName from 
			attr_value join jnt_fic_att_value using (jft_id)
			where
			ad_id=1 and f_id=p_f_id;
		-- get parent
		nParent:=fiche_account_parent(p_f_id);
		-- account doesn't exist we need to add id
		insert into tmp_pcmn(pcm_val,pcm_lib,pcm_val_parent) values (p_account,sName,nParent);
		end if;		
	end if;
	-- we retrieve jft_id
	select jft_id into njft_id from jnt_fic_att_value where f_id=p_f_id and ad_id=5;
	-- we update the account
	update attr_value set av_text=p_account where jft_id=njft_id;
		
return njft_id;
end;
$$
    LANGUAGE plpgsql;




--
-- Name: attribut_insert(integer, integer, character varying); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION attribut_insert(p_f_id integer, p_ad_id integer, p_value character varying) RETURNS void
    AS $$
declare 
	n_jft_id integer;
begin
	select nextval('s_jnt_fic_att_value') into n_jft_id;
	 insert into jnt_fic_att_value (jft_id,f_id,ad_id) values (n_jft_id,p_f_id,p_ad_id);
	 insert into attr_value (jft_id,av_text) values (n_jft_id,p_value);
return;
end;
$$
    LANGUAGE plpgsql;




--
-- Name: card_class_base(integer); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION card_class_base(p_f_id integer) RETURNS fiche_def.fd_class_base%type
    AS $$

declare
	n_poste fiche_def.fd_class_base%type;
begin

	select fd_class_base into n_poste from fiche_def join fiche using (fd_id)
	where f_id=p_f_id;
	if not FOUND then 
		raise exception 'Invalid fiche card_class_base(%)',p_f_id;
	end if;
return nposte;
end;
$$
    LANGUAGE plpgsql;




--
-- Name: check_balance(integer); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION check_balance(p_grpt integer) RETURNS numeric
    AS $$
declare 
	amount_jrnx_debit numeric;
	amount_jrnx_credit numeric;
	amount_jrn numeric;
begin
	select sum (j_montant) into amount_jrnx_credit 
	from jrnx 
		where 
	j_grpt=p_grpt
	and j_debit=false;

	select sum (j_montant) into amount_jrnx_debit 
	from jrnx 
		where 
	j_grpt=p_grpt
	and j_debit=true;

	select jr_montant into amount_jrn 
	from jrn
	where
	jr_grpt_id=p_grpt;

	if ( amount_jrnx_debit != amount_jrnx_credit ) 
		then
		return abs(amount_jrnx_debit-amount_jrnx_credit);
		end if;
	if ( amount_jrn != amount_jrnx_credit)
		then
		return -1*abs(amount_jrn - amount_jrnx_credit);
		end if;
	return 0;
end;
$$
    LANGUAGE plpgsql;




--
-- Name: fiche_account_parent(integer); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION fiche_account_parent(p_f_id integer) RETURNS poste_comptable
    AS $$
declare
ret poste_comptable;
begin
	select fd_class_base into ret from fiche_def join fiche using (fd_id) where f_id=p_f_id;
	if not FOUND then
		raise exception '% N''existe pas',p_f_id;
	end if;
	return ret;
end;
$$
    LANGUAGE plpgsql;




--
-- Name: insert_jrnx(character varying, numeric, integer, integer, integer, boolean, text, integer, text); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION insert_jrnx(p_date character varying, p_montant numeric, p_poste integer, p_grpt integer, p_jrn_def integer, p_debit boolean, p_tech_user text, p_tech_per integer, p_qcode text) RETURNS void
    AS $$
declare
	sCode varchar;
	nCount_qcode integer;
begin
	sCode=trim(p_qcode);

	-- if p_qcode is empty try to find one	
	if length(sCode) = 0 or p_qcode is null then

		select count(*) into nCount_qcode 	
			from vw_poste_qcode where j_poste=p_poste;
	-- if we find only one q_code for a accountancy account
	-- then retrieve it
		if nCount_qcode = 1 then
			select j_qcode into sCode 
			from vw_poste_qcode where j_poste=p_poste;
		else 
		 sCode=NULL;
		end if;
		
	end if;
	if p_montant = 0.0 then 
		return;	
	end if;
	insert into jrnx 
	(
		j_date,
		j_montant, 	
		j_poste,
		j_grpt, 
		j_jrn_def,
		j_debit,
		j_tech_user,
		j_tech_per,
		j_qcode	
	) values 
	(
		to_date(p_date,'DD.MM.YYYY'),
		p_montant,
		p_poste,	
		p_grpt,
		p_jrn_def,
		p_debit,
		p_tech_user,
		p_tech_per,
		sCode
	);

return;
end;
$$
    LANGUAGE plpgsql;




--
-- Name: insert_quant_sold(text, character varying, integer, numeric, numeric, integer, character varying); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION insert_quant_sold(p_internal text, p_fiche character varying, p_quant integer, p_price numeric, p_vat numeric, p_vat_code integer, p_client character varying) RETURNS void
    AS $$
declare 
	fid_client integer;
	fid_good   integer;
begin
	select f_id into fid_client from 
		attr_value join jnt_fic_att_value using (jft_id) where ad_id=23 and av_text=p_client;

	select f_id into fid_good from 
		attr_value join jnt_fic_att_value using (jft_id) where ad_id=23 and av_text=p_fiche;


	insert into quant_sold
		(qs_internal,qs_fiche,qs_quantite,qs_price,qs_vat,qs_vat_code,qs_client) 
	values
		(p_internal,fid_good,p_quant,p_price,p_vat,p_vat_code,fid_client);
	return;
end;	
$$
    LANGUAGE plpgsql;




--
-- Name: insert_quick_code(integer, text); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION insert_quick_code(nf_id integer, tav_text text) RETURNS integer
    AS $$
	declare
	ns integer;
	nExist integer;
	tText text;
	begin
	tText := upper(trim(tav_text));
	tText := replace(tText,' ','');
	
	loop
		-- take the next sequence
		select nextval('s_jnt_fic_att_value') into ns;
		if length (tText) = 0 or tText is null then
			tText := 'FID'||ns;
		end if;
		-- av_text already used ?
		select count(*) into nExist 
			from jnt_fic_att_value join attr_value using (jft_id) 
		where 
			ad_id=23 and  av_text=upper(tText);

		if nExist = 0 then
			exit;
		end if;
		tText:='FID'||ns;
	end loop;
	-- insert into table jnt_fic_att_value
	insert into jnt_fic_att_value values (ns,nf_id,23);
	-- insert value into attr_value
	insert into attr_value values (ns,upper(tText));
	return ns;
	end;
$$
    LANGUAGE plpgsql;




--
-- Name: proc_check_balance(); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION proc_check_balance() RETURNS "trigger"
    AS $$
declare 
	diff numeric;
	tt integer;
begin
	if TG_OP = 'INSERT' then
	tt=NEW.jr_grpt_id;
	diff:=check_balance(tt);
	if diff != 0 then
		raise exception 'balance error %',diff ;
	end if;
	return NEW;
	end if;
end;
$$
    LANGUAGE plpgsql;




--
-- Name: t_document_type_insert(); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION t_document_type_insert() RETURNS "trigger"
    AS $$
    BEGIN
        execute  'create sequence seq_doc_type_'||NEW.dt_id;
raise notice 'Creating sequence seq_doc_type_%',NEW.dt_id;
        RETURN NEW;
    END;
$$
    LANGUAGE plpgsql;




--
-- Name: t_jrn_def_sequence(); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION t_jrn_def_sequence() RETURNS "trigger"
    AS $$
    BEGIN
        execute  'create sequence s_jrn_'||NEW.jrn_def_id;
raise notice 'Creating sequence s_jrn_%',NEW.jrn_def_id;
        RETURN NEW;
    END;
$$
    LANGUAGE plpgsql;




--
-- Name: trim_cvs_quote(); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION trim_cvs_quote() RETURNS "trigger"
    AS $$
declare
        modified import_tmp%ROWTYPE;
begin
	modified:=NEW;
	modified.devise=replace(new.devise,'"','');
	modified.poste_comptable=replace(new.poste_comptable,'"','');
        modified.compte_ordre=replace(NEW.COMPTE_ORDRE,'"','');
        modified.detail=replace(NEW.DETAIL,'"','');
        modified.num_compte=replace(NEW.NUM_COMPTE,'"','');
        return modified;
end;
$$
    LANGUAGE plpgsql;




--
-- Name: trim_space_format_csv_banque(); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION trim_space_format_csv_banque() RETURNS "trigger"
    AS $$
declare
        modified format_csv_banque%ROWTYPE;
begin
        modified.name=trim(NEW.NAME);
        modified.include_file=trim(new.include_file);
		if ( length(modified.name) = 0 ) then
			modified.name=null;
		end if;
		if ( length(modified.include_file) = 0 ) then
			modified.include_file=null;
		end if;

        return modified;
end;
$$
    LANGUAGE plpgsql;




--
-- Name: tva_delete(integer); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION tva_delete(integer) RETURNS void
    AS $_$ 
declare
	p_tva_id alias for $1;
	nCount integer;
begin
	nCount=0;
	select count(*) into nCount from quant_sold where qs_vat_code=p_tva_id;
	if nCount = 0 then
		delete from tva_rate where tva_id=p_tva_id;
	end if;
	return;
end;
$_$
    LANGUAGE plpgsql;




--
-- Name: tva_insert(integer, text, numeric, text, text); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION tva_insert(integer, text, numeric, text, text) RETURNS integer
    AS $_$
declare
p_tva_id alias for $1;
p_tva_label alias for $2;
p_tva_rate alias for $3;
p_tva_comment alias for $4;
p_tva_poste alias for $5;
debit text;
credit text;
nCount integer;
begin
if length(trim(p_tva_label)) = 0 then
	return 3;
end if;
select count(*) into nCount from tva_rate 
	where tva_id=p_tva_id;
if nCount != 0 then
	return 5;
end if;
if length(trim(p_tva_poste)) != 0 then
	if position (',' in p_tva_poste) = 0 then return 4; end if;
	debit  = split_part(p_tva_poste,',',1);
	credit  = split_part(p_tva_poste,',',2);
	select count(*) into nCount from tmp_pcmn where pcm_val=debit;
	if nCount = 0 then return 4; end if;
	select count(*) into nCount from tmp_pcmn where pcm_val=credit;
	if nCount = 0 then return 4; end if;
 
end if;
insert into tva_rate(tva_id,tva_label,tva_rate,tva_comment,tva_poste)
	values (p_tva_id,p_tva_label,p_tva_rate,p_tva_comment,p_tva_poste);
return 0;
end;
$_$
    LANGUAGE plpgsql;




--
-- Name: tva_modify(integer, text, numeric, text, text); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION tva_modify(integer, text, numeric, text, text) RETURNS integer
    AS $_$declare
p_tva_id alias for $1;
p_tva_label alias for $2;
p_tva_rate alias for $3;
p_tva_comment alias for $4;
p_tva_poste alias for $5;
debit text;
credit text;
nCount integer;
begin
if length(trim(p_tva_label)) = 0 then
	return 3;
end if;

if length(trim(p_tva_poste)) != 0 then
	if position (',' in p_tva_poste) = 0 then return 4; end if;
	debit  = split_part(p_tva_poste,',',1);
	credit  = split_part(p_tva_poste,',',2);
	select count(*) into nCount from tmp_pcmn where pcm_val=debit;
	if nCount = 0 then return 4; end if;
	select count(*) into nCount from tmp_pcmn where pcm_val=credit;
	if nCount = 0 then return 4; end if;
 
end if;
update tva_rate set tva_label=p_tva_label,tva_rate=p_tva_rate,tva_comment=p_tva_comment,tva_poste=p_tva_poste
	where tva_id=p_tva_id;
return 0;
end;
$_$
    LANGUAGE plpgsql;




--
-- Name: update_quick_code(integer, text); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION update_quick_code(njft_id integer, tav_text text) RETURNS integer
    AS $$
	declare
	ns integer;
	nExist integer;
	tText text;
	old_qcode varchar;
	begin
	-- get current value
	select av_text into old_qcode from attr_value where jft_id=njft_id;
	-- av_text didn't change so no update
	if tav_text = upper( trim(old_qcode)) then
		return 0;
	end if;
	
	tText := trim(upper(tav_text));
	tText := replace(tText,' ','');
	if length ( tText) = 0 or tText is null then
		return 0;
	end if;
		
	ns := njft_id;

	loop
		-- av_text already used ?
		select count(*) into nExist 
			from jnt_fic_att_value join attr_value using (jft_id) 
		where 
			ad_id=23 and av_text=upper(tText);

		if nExist = 0 then
			exit;
		end if;	
		if tText = 'FID'||ns then
			-- take the next sequence
			select nextval('s_jnt_fic_att_value') into ns;
		end if;
		tText  :='FID'||ns;
		
	end loop;
	update attr_value set av_text = tText where jft_id=njft_id;
	update jrnx set j_qcode=tText where j_qcode = old_qcode;
	return ns;
	end;
$$
    LANGUAGE plpgsql;






--
-- Name: tr_jrn_check_balance; Type: TRIGGER; Schema: public; Owner: phpcompta
--

CREATE TRIGGER tr_jrn_check_balance
    AFTER INSERT ON jrn
    FOR EACH ROW
    EXECUTE PROCEDURE proc_check_balance();


--
-- Name: trigger_document_type_i; Type: TRIGGER; Schema: public; Owner: phpcompta
--

CREATE TRIGGER trigger_document_type_i
    AFTER INSERT ON document_type
    FOR EACH ROW
    EXECUTE PROCEDURE t_document_type_insert();


--
-- Name: trigger_jrn_def_sequence_i; Type: TRIGGER; Schema: public; Owner: phpcompta
--

CREATE TRIGGER trigger_jrn_def_sequence_i
    AFTER INSERT ON jrn_def
    FOR EACH ROW
    EXECUTE PROCEDURE t_jrn_def_sequence();


--
-- Name: trim_quote; Type: TRIGGER; Schema: public; Owner: phpcompta
--

CREATE TRIGGER trim_quote
    BEFORE INSERT OR UPDATE ON import_tmp
    FOR EACH ROW
    EXECUTE PROCEDURE trim_cvs_quote();


--
-- Name: trim_space; Type: TRIGGER; Schema: public; Owner: phpcompta
--

CREATE TRIGGER trim_space
    BEFORE INSERT OR UPDATE ON format_csv_banque
    FOR EACH ROW
    EXECUTE PROCEDURE trim_space_format_csv_banque();

