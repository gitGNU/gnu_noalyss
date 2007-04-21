-- Name: document_seq; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE document_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: SEQUENCE document_seq; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON SEQUENCE document_seq IS 'Sequence for the sequence bound to the document modele';
-- Name: s_jnt_id; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_jnt_id
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_attr_def; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_attr_def
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_cbc; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_cbc
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_central; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_central
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_central_order; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_central_order
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_centralized; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_centralized
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_currency; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_currency
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_fdef; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_fdef
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_fiche; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_fiche
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_fiche_def_ref; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_fiche_def_ref
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_form; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_form
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_formdef; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_formdef
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_grpt; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_grpt
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_idef; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_idef
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_internal; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_internal
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_invoice; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_invoice
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_isup; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_isup
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_jnt_fic_att_value; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_jnt_fic_att_value
    START WITH 366
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_jrn; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_jrn
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_jrn_1; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_jrn_1
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_jrn_2; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_jrn_2
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_jrn_3; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_jrn_3
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_jrn_4; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_jrn_4
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_jrn_def; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_jrn_def
    START WITH 5
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_jrn_op; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_jrn_op
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_jrn_rapt; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_jrn_rapt
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_jrnaction; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_jrnaction
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_jrnx; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_jrnx
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_periode; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_periode
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_quantity; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_quantity
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_stock_goods; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_stock_goods
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_user_act; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_user_act
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: s_user_jrn; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE s_user_jrn
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: seq_doc_type_1; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE seq_doc_type_1
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: seq_doc_type_2; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE seq_doc_type_2
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: seq_doc_type_3; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE seq_doc_type_3
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: seq_doc_type_4; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE seq_doc_type_4
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: seq_doc_type_5; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE seq_doc_type_5
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: seq_doc_type_6; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE seq_doc_type_6
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: seq_doc_type_7; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE seq_doc_type_7
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: seq_doc_type_8; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE seq_doc_type_8
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
-- Name: seq_doc_type_9; Type: SEQUENCE; Schema: public; Owner: phpcompta
CREATE SEQUENCE seq_doc_type_9
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;
