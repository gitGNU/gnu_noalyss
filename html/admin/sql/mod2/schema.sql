--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: postgres
--



SET search_path = public, pg_catalog;

--
-- Name: plpgsql_call_handler(); Type: FUNCTION; Schema: public; Owner: dany
--

--
-- Name: check_balance(text); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION check_balance(p_internal text) RETURNS numeric AS $$
declare 
	amount_jrnx_debit numeric;
	amount_jrnx_credit numeric;
	amount_jrn numeric;
begin
	select sum (j_montant) into amount_jrnx_credit 
	from jrnx join jrn on (j_grpt=jr_grpt_id)
		where 
	jr_internal=p_internal
	and j_debit=false;

	select sum (j_montant) into amount_jrnx_debit 
	from jrnx join jrn on (j_grpt=jr_grpt_id)
		where 
	jr_internal=p_internal
	and j_debit=true;

	select jr_montant into amount_jrn 
	from jrn
	where
	jr_internal=p_internal;

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
$$ LANGUAGE plpgsql;


ALTER FUNCTION public.check_balance(p_internal text) OWNER TO phpcompta;

--
-- Name: proc_check_balance(); Type: FUNCTION; Schema: public; Owner: phpcompta
--

CREATE FUNCTION proc_check_balance() RETURNS "trigger"
    AS $$
declare 
	diff numeric;
	tt text;
begin
	raise notice 'tg_op is %', TG_OP;
	if TG_OP = 'INSERT' then
	tt=NEW.jr_internal;
	diff:=check_balance(tt);
	if diff != 0 then
		raise exception 'Rounded error %',diff ;
	end if;
	return NEW;
	end if;
end;
$$
    LANGUAGE plpgsql;


ALTER FUNCTION public.proc_check_balance() OWNER TO phpcompta;

SET default_tablespace = '';

SET default_with_oids = true;

--
-- Name: action; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE "action" (
    ac_id integer NOT NULL,
    ac_description text NOT NULL
);


ALTER TABLE public."action" OWNER TO phpcompta;

--
-- Name: TABLE "action"; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE "action" IS 'The different privileges';


--
-- Name: attr_def; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE attr_def (
    ad_id integer DEFAULT nextval('s_attr_def'::text) NOT NULL,
    ad_text text
);


ALTER TABLE public.attr_def OWNER TO phpcompta;

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


ALTER TABLE public.attr_min OWNER TO phpcompta;

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


ALTER TABLE public.attr_value OWNER TO phpcompta;

--
-- Name: centralized; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
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


ALTER TABLE public.centralized OWNER TO phpcompta;

--
-- Name: TABLE centralized; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE centralized IS 'The centralized journal';


--
-- Name: fiche; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE fiche (
    f_id integer DEFAULT nextval('s_fiche'::text) NOT NULL,
    fd_id integer
);


ALTER TABLE public.fiche OWNER TO phpcompta;

--
-- Name: TABLE fiche; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE fiche IS 'Cards';


--
-- Name: fiche_def; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE fiche_def (
    fd_id integer DEFAULT nextval('s_fdef'::text) NOT NULL,
    fd_class_base integer,
    fd_label text NOT NULL,
    fd_create_account boolean DEFAULT false,
    frd_id integer NOT NULL
);


ALTER TABLE public.fiche_def OWNER TO phpcompta;

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


ALTER TABLE public.fiche_def_ref OWNER TO phpcompta;

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


ALTER TABLE public.form OWNER TO phpcompta;

--
-- Name: TABLE form; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE form IS 'Forms content';


--
-- Name: formdef; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE formdef (
    fr_id integer DEFAULT nextval('s_formdef'::text) NOT NULL,
    fr_label text
);


ALTER TABLE public.formdef OWNER TO phpcompta;

--
-- Name: jnt_fic_att_value; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE jnt_fic_att_value (
    jft_id integer DEFAULT nextval('s_jnt_fic_att_value'::text) NOT NULL,
    f_id integer,
    ad_id integer
);


ALTER TABLE public.jnt_fic_att_value OWNER TO phpcompta;

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


ALTER TABLE public.jnt_fic_attr OWNER TO phpcompta;

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
    jr_montant numeric(8,4) NOT NULL,
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


ALTER TABLE public.jrn OWNER TO phpcompta;

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


ALTER TABLE public.jrn_action OWNER TO phpcompta;

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


ALTER TABLE public.jrn_def OWNER TO phpcompta;

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


ALTER TABLE public.jrn_rapt OWNER TO phpcompta;

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


ALTER TABLE public.jrn_type OWNER TO phpcompta;

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
    j_montant numeric(8,4) DEFAULT 0,
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
    j_tech_per integer NOT NULL
);


ALTER TABLE public.jrnx OWNER TO phpcompta;

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


ALTER TABLE public.parameter OWNER TO phpcompta;

--
-- Name: parm_money; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE parm_money (
    pm_id integer DEFAULT nextval('s_currency'::text),
    pm_code character(3) NOT NULL,
    pm_rate double precision
);


ALTER TABLE public.parm_money OWNER TO phpcompta;

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


ALTER TABLE public.parm_periode OWNER TO phpcompta;

--
-- Name: TABLE parm_periode; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE parm_periode IS 'Periode definition';


--
-- Name: s_attr_def; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_attr_def
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_attr_def OWNER TO phpcompta;

--
-- Name: s_central; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_central
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_central OWNER TO phpcompta;

--
-- Name: s_central_order; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_central_order
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_central_order OWNER TO phpcompta;

--
-- Name: s_centralized; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_centralized
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_centralized OWNER TO phpcompta;

--
-- Name: s_currency; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_currency
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_currency OWNER TO phpcompta;

--
-- Name: s_fdef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fdef
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_fdef OWNER TO phpcompta;

--
-- Name: s_fiche; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fiche
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_fiche OWNER TO phpcompta;

--
-- Name: s_fiche_def_ref; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_fiche_def_ref
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_fiche_def_ref OWNER TO phpcompta;

--
-- Name: s_form; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_form
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_form OWNER TO phpcompta;

--
-- Name: s_formdef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_formdef
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_formdef OWNER TO phpcompta;

--
-- Name: s_grpt; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_grpt
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_grpt OWNER TO phpcompta;

--
-- Name: s_idef; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_idef
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_idef OWNER TO phpcompta;

--
-- Name: s_internal; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_internal
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_internal OWNER TO phpcompta;

--
-- Name: s_isup; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_isup
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_isup OWNER TO phpcompta;

--
-- Name: s_jnt_fic_att_value; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jnt_fic_att_value
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_jnt_fic_att_value OWNER TO phpcompta;

--
-- Name: s_jrn; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_jrn OWNER TO phpcompta;

--
-- Name: s_jrn_1; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_1
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_jrn_1 OWNER TO phpcompta;

--
-- Name: s_jrn_2; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_2
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_jrn_2 OWNER TO phpcompta;

--
-- Name: s_jrn_3; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_3
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_jrn_3 OWNER TO phpcompta;

--
-- Name: s_jrn_4; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_4
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_jrn_4 OWNER TO phpcompta;

--
-- Name: s_jrn_def; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_def
    START WITH 5
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_jrn_def OWNER TO phpcompta;

--
-- Name: s_jrn_op; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_op
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_jrn_op OWNER TO phpcompta;

--
-- Name: s_jrn_rapt; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrn_rapt
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_jrn_rapt OWNER TO phpcompta;

--
-- Name: s_jrnaction; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrnaction
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_jrnaction OWNER TO phpcompta;

--
-- Name: s_jrnx; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_jrnx
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_jrnx OWNER TO phpcompta;

--
-- Name: s_periode; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_periode
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_periode OWNER TO phpcompta;

--
-- Name: s_stock_goods; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_stock_goods
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_stock_goods OWNER TO phpcompta;

--
-- Name: s_user_act; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_user_act
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_user_act OWNER TO phpcompta;

--
-- Name: s_user_jrn; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE s_user_jrn
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.s_user_jrn OWNER TO phpcompta;

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


ALTER TABLE public.stock_goods OWNER TO phpcompta;

--
-- Name: TABLE stock_goods; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE stock_goods IS 'About the goods';


--
-- Name: tmp_pcmn; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE tmp_pcmn (
    pcm_val integer NOT NULL,
    pcm_lib text,
    pcm_val_parent integer DEFAULT 0,
    pcm_country character(2) DEFAULT 'BE'::bpchar NOT NULL
);


ALTER TABLE public.tmp_pcmn OWNER TO phpcompta;

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
    tva_rate double precision DEFAULT 0.0 NOT NULL,
    tva_comment text,
    tva_poste text
);


ALTER TABLE public.tva_rate OWNER TO phpcompta;

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


ALTER TABLE public.user_local_pref OWNER TO phpcompta;

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
-- Name: user_pref; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE user_pref (
    pref_user text NOT NULL,
    pref_periode integer NOT NULL
);


ALTER TABLE public.user_pref OWNER TO phpcompta;

--
-- Name: user_sec_act; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE user_sec_act (
    ua_id integer DEFAULT nextval('s_user_act'::text) NOT NULL,
    ua_login text,
    ua_act_id integer
);


ALTER TABLE public.user_sec_act OWNER TO phpcompta;

--
-- Name: user_sec_jrn; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE user_sec_jrn (
    uj_id integer DEFAULT nextval('s_user_jrn'::text) NOT NULL,
    uj_login text,
    uj_jrn_id integer,
    uj_priv text
);


ALTER TABLE public.user_sec_jrn OWNER TO phpcompta;

--
-- Name: version; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE version (
    val integer
);


ALTER TABLE public.version OWNER TO phpcompta;

--
-- Name: vw_client; Type: VIEW; Schema: public; Owner: phpcompta
--

CREATE VIEW vw_client AS
    SELECT a.f_id, a.av_text AS name, b.av_text AS tva_num, c.av_text AS poste_comptable, d.av_text AS rue, e.av_text AS code_postal, f.av_text AS pays, g.av_text AS telephone, h.av_text AS email FROM ((((((((SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 1)) a LEFT JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 13)) b USING (f_id)) LEFT JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 5)) c USING (f_id)) LEFT JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 14)) d USING (f_id)) LEFT JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 15)) e USING (f_id)) LEFT JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 16)) f USING (f_id)) LEFT JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 17)) g USING (f_id)) LEFT JOIN (SELECT jnt_fic_att_value.jft_id, fiche.f_id, fiche_def.frd_id, fiche.fd_id, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base, jnt_fic_att_value.ad_id, attr_value.av_text FROM ((((fiche JOIN fiche_def USING (fd_id)) JOIN fiche_def_ref USING (frd_id)) JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) WHERE (jnt_fic_att_value.ad_id = 18)) h USING (f_id)) WHERE (a.frd_id = 9);


ALTER TABLE public.vw_client OWNER TO phpcompta;

--
-- Name: VIEW vw_client; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON VIEW vw_client IS 'minimum attribut for the customer (frd_id=9)';


--
-- Name: vw_fiche_attr; Type: VIEW; Schema: public; Owner: phpcompta
--

CREATE VIEW vw_fiche_attr AS
    SELECT a.f_id, a.fd_id, a.av_text AS vw_name, b.av_text AS vw_sell, c.av_text AS vw_buy, d.av_text AS tva_code, tva_rate.tva_id, tva_rate.tva_rate, tva_rate.tva_label, e.av_text AS vw_addr, f.av_text AS vw_cp, fiche_def.frd_id FROM ((((((((SELECT fiche.f_id, fiche.fd_id, attr_value.av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (jnt_fic_att_value.ad_id = 1)) a LEFT JOIN (SELECT fiche.f_id, attr_value.av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (jnt_fic_att_value.ad_id = 6)) b ON ((a.f_id = b.f_id))) LEFT JOIN (SELECT fiche.f_id, attr_value.av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (jnt_fic_att_value.ad_id = 7)) c ON ((a.f_id = c.f_id))) LEFT JOIN (SELECT fiche.f_id, attr_value.av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (jnt_fic_att_value.ad_id = 2)) d ON ((a.f_id = d.f_id))) LEFT JOIN (SELECT fiche.f_id, attr_value.av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (jnt_fic_att_value.ad_id = 14)) e ON ((a.f_id = e.f_id))) LEFT JOIN (SELECT fiche.f_id, attr_value.av_text FROM (((fiche JOIN jnt_fic_att_value USING (f_id)) JOIN attr_value USING (jft_id)) JOIN attr_def USING (ad_id)) WHERE (jnt_fic_att_value.ad_id = 15)) f ON ((a.f_id = f.f_id))) LEFT JOIN tva_rate ON ((d.av_text = (tva_rate.tva_id)::text))) JOIN fiche_def USING (fd_id));


ALTER TABLE public.vw_fiche_attr OWNER TO phpcompta;

--
-- Name: vw_fiche_def; Type: VIEW; Schema: public; Owner: phpcompta
--

CREATE VIEW vw_fiche_def AS
    SELECT jnt_fic_attr.fd_id, jnt_fic_attr.ad_id, attr_def.ad_text, fiche_def.fd_class_base, fiche_def.fd_label, fiche_def.fd_create_account, fiche_def.frd_id FROM ((jnt_fic_attr JOIN attr_def USING (ad_id)) JOIN fiche_def USING (fd_id));


ALTER TABLE public.vw_fiche_def OWNER TO phpcompta;

--
-- Name: VIEW vw_fiche_def; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON VIEW vw_fiche_def IS 'all the attributs for  card family';


--
-- Name: vw_fiche_min; Type: VIEW; Schema: public; Owner: phpcompta
--

CREATE VIEW vw_fiche_min AS
    SELECT attr_min.frd_id, attr_min.ad_id, attr_def.ad_text, fiche_def_ref.frd_text, fiche_def_ref.frd_class_base FROM ((attr_min JOIN attr_def USING (ad_id)) JOIN fiche_def_ref USING (frd_id));


ALTER TABLE public.vw_fiche_min OWNER TO phpcompta;

--
-- Name: VIEW vw_fiche_min; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON VIEW vw_fiche_min IS 'minimum attribut for reference card';


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

