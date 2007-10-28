CREATE DOMAIN poste_comptable AS numeric(25,0);
CREATE TABLE "action" (
    ac_id integer NOT NULL,
    ac_description text NOT NULL
);
CREATE TABLE action_gestion (
    ag_id integer NOT NULL,
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
CREATE TABLE attr_def (
    ad_id integer DEFAULT nextval(('s_attr_def'::text)::regclass) NOT NULL,
    ad_text text
);
CREATE TABLE attr_min (
    frd_id integer,
    ad_id integer
);
CREATE TABLE attr_value (
    jft_id integer,
    av_text text
);
CREATE TABLE bilan (
    b_id integer DEFAULT nextval('bilan_b_id_seq'::regclass) NOT NULL,
    b_name text NOT NULL,
    b_file_template text NOT NULL,
    b_file_form text,
    b_type text NOT NULL
);
CREATE TABLE centralized (
    c_id integer DEFAULT nextval(('s_centralized'::text)::regclass) NOT NULL,
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
CREATE TABLE document (
    d_id integer NOT NULL,
    ag_id integer NOT NULL,
    d_lob oid,
    d_number bigint NOT NULL,
    d_filename text,
    d_mimetype text,
    d_state integer
);
CREATE TABLE document_modele (
    md_id integer NOT NULL,
    md_name text NOT NULL,
    md_lob oid,
    md_type integer NOT NULL,
    md_filename text,
    md_mimetype text
);
CREATE TABLE document_state (
    s_id integer NOT NULL,
    s_value character varying(50) NOT NULL
);
CREATE TABLE document_type (
    dt_id integer NOT NULL,
    dt_value character varying(80)
);
CREATE TABLE fiche (
    f_id integer DEFAULT nextval(('s_fiche'::text)::regclass) NOT NULL,
    fd_id integer
);
CREATE TABLE fiche_def (
    fd_id integer DEFAULT nextval(('s_fdef'::text)::regclass) NOT NULL,
    fd_class_base poste_comptable,
    fd_label text NOT NULL,
    fd_create_account boolean DEFAULT false,
    frd_id integer NOT NULL
);
CREATE TABLE fiche_def_ref (
    frd_id integer DEFAULT nextval(('s_fiche_def_ref'::text)::regclass) NOT NULL,
    frd_text text,
    frd_class_base integer
);
CREATE TABLE form (
    fo_id integer DEFAULT nextval(('s_form'::text)::regclass) NOT NULL,
    fo_fr_id integer,
    fo_pos integer,
    fo_label text,
    fo_formula text
);
CREATE TABLE format_csv_banque (
    name text NOT NULL,
    include_file text NOT NULL
);
CREATE TABLE formdef (
    fr_id integer DEFAULT nextval(('s_formdef'::text)::regclass) NOT NULL,
    fr_label text
);
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
    status character varying(1) DEFAULT 'n'::character varying NOT NULL,
    bq_account integer NOT NULL,
    jrn integer NOT NULL,
    jr_rapt text,
    CONSTRAINT import_tmp_status_check CHECK ((((((status)::text = 'n'::text) OR ((status)::text = 't'::text)) OR ((status)::text = 'd'::text)) OR ((status)::text = 'w'::text)))
);
CREATE TABLE invoice (
    iv_id integer DEFAULT nextval(('s_invoice'::text)::regclass) NOT NULL,
    iv_name text NOT NULL,
    iv_file oid
);
CREATE TABLE jnt_fic_att_value (
    jft_id integer DEFAULT nextval(('s_jnt_fic_att_value'::text)::regclass) NOT NULL,
    f_id integer,
    ad_id integer
);
CREATE TABLE jnt_fic_attr (
    fd_id integer,
    ad_id integer,
    jnt_id bigint DEFAULT nextval('s_jnt_id'::regclass) NOT NULL
);
CREATE TABLE jrn (
    jr_id integer DEFAULT nextval(('s_jrn'::text)::regclass) NOT NULL,
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
CREATE TABLE jrn_action (
    ja_id integer DEFAULT nextval(('s_jrnaction'::text)::regclass) NOT NULL,
    ja_name text NOT NULL,
    ja_desc text,
    ja_url text NOT NULL,
    ja_action text NOT NULL,
    ja_lang text DEFAULT 'FR'::text,
    ja_jrn_type character(3)
);
CREATE TABLE jrn_def (
    jrn_def_id integer DEFAULT nextval(('s_jrn_def'::text)::regclass) NOT NULL,
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
CREATE TABLE jrn_rapt (
    jra_id integer DEFAULT nextval(('s_jrn_rapt'::text)::regclass) NOT NULL,
    jr_id integer NOT NULL,
    jra_concerned integer NOT NULL
);
CREATE TABLE jrn_type (
    jrn_type_id character(3) NOT NULL,
    jrn_desc text
);
CREATE TABLE jrnx (
    j_id integer DEFAULT nextval(('s_jrn_op'::text)::regclass) NOT NULL,
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
CREATE TABLE op_predef (
    od_id integer DEFAULT nextval('op_def_op_seq'::regclass) NOT NULL,
    jrn_def_id integer NOT NULL,
    od_name text NOT NULL,
    od_item integer NOT NULL,
    od_jrn_type text NOT NULL,
    od_direct boolean NOT NULL
);
CREATE TABLE op_predef_detail (
    opd_id integer DEFAULT nextval('op_predef_detail_opd_id_seq'::regclass) NOT NULL,
    od_id integer NOT NULL,
    opd_poste text NOT NULL,
    opd_amount numeric(20,4),
    opd_tva_id integer,
    opd_quantity numeric(20,4),
    opd_debit boolean NOT NULL,
    opd_tva_amount numeric(20,4),
    opd_comment text,
    opd_qc boolean
);
CREATE TABLE operation_analytique (
    oa_id integer DEFAULT nextval('historique_analytique_ha_id_seq'::regclass) NOT NULL,
    po_id integer NOT NULL,
    pa_id integer NOT NULL,
    oa_amount numeric(20,4) NOT NULL,
    oa_description text,
    oa_debit boolean DEFAULT true NOT NULL,
    j_id integer,
    oa_group integer DEFAULT nextval('s_oa_group'::regclass) NOT NULL,
    oa_date date NOT NULL,
    oa_row integer
);
CREATE TABLE parameter (
    pr_id text NOT NULL,
    pr_value text
);
CREATE TABLE parm_code (
    p_code text NOT NULL,
    p_value text,
    p_comment text
);
CREATE TABLE parm_money (
    pm_id integer DEFAULT nextval(('s_currency'::text)::regclass),
    pm_code character(3) NOT NULL,
    pm_rate numeric(20,4)
);
CREATE TABLE parm_periode (
    p_id integer DEFAULT nextval(('s_periode'::text)::regclass) NOT NULL,
    p_start date NOT NULL,
    p_end date NOT NULL,
    p_exercice text DEFAULT to_char(now(), 'YYYY'::text) NOT NULL,
    p_closed boolean DEFAULT false,
    p_central boolean DEFAULT false,
    CONSTRAINT parm_periode_check CHECK ((p_end >= p_start))
);
CREATE TABLE plan_analytique (
    pa_id integer DEFAULT nextval('plan_analytique_pa_id_seq'::regclass) NOT NULL,
    pa_name text DEFAULT 'Sans Nom'::text NOT NULL,
    pa_description text
);
CREATE TABLE poste_analytique (
    po_id integer DEFAULT nextval('poste_analytique_po_id_seq'::regclass) NOT NULL,
    po_name text NOT NULL,
    pa_id integer NOT NULL,
    po_amount numeric(20,4) DEFAULT 0.0 NOT NULL,
    po_description text
);
CREATE TABLE quant_purchase (
    qp_id integer DEFAULT nextval(('s_quantity'::text)::regclass) NOT NULL,
    qp_internal text NOT NULL,
    j_id integer NOT NULL,
    qp_fiche integer NOT NULL,
    qp_quantite numeric(20,4) NOT NULL,
    qp_price numeric(20,4),
    qp_vat numeric(20,4) DEFAULT 0.0,
    qp_vat_code integer,
    qp_nd_amount numeric(20,4) DEFAULT 0.0,
    qp_nd_tva numeric(20,4) DEFAULT 0.0,
    qp_nd_tva_recup numeric(20,4) DEFAULT 0.0,
    qp_supplier integer NOT NULL,
    qp_valid character(1) DEFAULT 'Y'::bpchar NOT NULL
);
CREATE TABLE quant_sold (
    qs_id integer DEFAULT nextval(('s_quantity'::text)::regclass) NOT NULL,
    qs_internal text NOT NULL,
    qs_fiche integer NOT NULL,
    qs_quantite numeric(20,4) NOT NULL,
    qs_price numeric(20,4),
    qs_vat numeric(20,4),
    qs_vat_code integer,
    qs_client integer NOT NULL,
    qs_valid character(1) DEFAULT 'Y'::bpchar NOT NULL,
    j_id integer NOT NULL
);
CREATE TABLE stock_goods (
    sg_id integer DEFAULT nextval(('s_stock_goods'::text)::regclass) NOT NULL,
    j_id integer,
    f_id integer NOT NULL,
    sg_code text,
    sg_quantity numeric(8,4) DEFAULT 0,
    sg_type character(1) DEFAULT 'c'::bpchar NOT NULL,
    sg_date date,
    sg_tech_date date DEFAULT now(),
    sg_tech_user text,
    sg_comment character varying(80),
    sg_exercice character varying(4),
    CONSTRAINT stock_goods_sg_type CHECK (((sg_type = 'c'::bpchar) OR (sg_type = 'd'::bpchar)))
);
CREATE TABLE tmp_pcmn (
    pcm_val poste_comptable NOT NULL,
    pcm_lib text,
    pcm_val_parent poste_comptable DEFAULT 0
);
CREATE TABLE tva_rate (
    tva_id integer NOT NULL,
    tva_label text NOT NULL,
    tva_rate numeric(8,4) DEFAULT 0.0 NOT NULL,
    tva_comment text,
    tva_poste text
);
CREATE TABLE user_local_pref (
    user_id text NOT NULL,
    parameter_type text NOT NULL,
    parameter_value text
);
CREATE TABLE user_sec_act (
    ua_id integer DEFAULT nextval(('s_user_act'::text)::regclass) NOT NULL,
    ua_login text,
    ua_act_id integer
);
CREATE TABLE user_sec_jrn (
    uj_id integer DEFAULT nextval(('s_user_jrn'::text)::regclass) NOT NULL,
    uj_login text,
    uj_jrn_id integer,
    uj_priv text
);
CREATE TABLE version (
    val integer
);
