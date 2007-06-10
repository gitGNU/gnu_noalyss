-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: postgres
-- Name: FUNCTION correct_sequence(p_sequence text, p_col text, p_table text); Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON FUNCTION correct_sequence(p_sequence text, p_col text, p_table text) IS ' Often the primary key is a sequence number and sometimes the value of the sequence is not synchronized with the primary key ( p_sequence : sequence name, p_col : col of the pk,p_table : concerned table';
-- Name: TABLE "action"; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE "action" IS 'The different privileges';
-- Name: TABLE action_gestion; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE action_gestion IS 'Action for Managing';
-- Name: TABLE attr_def; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE attr_def IS 'The available attributs for the cards';
-- Name: TABLE attr_min; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE attr_min IS 'The value of  attributs for the cards';
-- Name: TABLE centralized; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE centralized IS 'The centralized journal';
-- Name: TABLE document; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE document IS 'This table contains all the documents : summary and lob files';
-- Name: TABLE document_modele; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE document_modele IS ' contains all the template for the  documents';
-- Name: SEQUENCE document_seq; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON SEQUENCE document_seq IS 'Sequence for the sequence bound to the document modele';
-- Name: TABLE document_state; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE document_state IS 'State of the document';
-- Name: TABLE document_type; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE document_type IS 'Type of document : meeting, invoice,...';
-- Name: TABLE fiche; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE fiche IS 'Cards';
-- Name: TABLE fiche_def; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE fiche_def IS 'Cards definition';
-- Name: TABLE fiche_def_ref; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE fiche_def_ref IS 'Family Cards definition';
-- Name: TABLE form; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE form IS 'Forms content';
-- Name: TABLE jnt_fic_att_value; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE jnt_fic_att_value IS 'join between the card and the attribut definition';
-- Name: TABLE jnt_fic_attr; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE jnt_fic_attr IS 'join between the family card and the attribut definition';
-- Name: TABLE jrn; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE jrn IS 'Journal: content one line for a group of accountancy writing';
-- Name: TABLE jrn_action; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE jrn_action IS 'Possible action when we are in journal (menu)';
-- Name: TABLE jrn_def; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE jrn_def IS 'Definition of a journal, his properties';
-- Name: TABLE jrn_rapt; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE jrn_rapt IS 'Rapprochement between operation';
-- Name: TABLE jrn_type; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE jrn_type IS 'Type of journal (Sell, Buy, Financial...)';
-- Name: TABLE jrnx; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE jrnx IS 'Journal: content one line for each accountancy writing';
-- Name: TABLE parm_money; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE parm_money IS 'Currency conversion';
-- Name: TABLE parm_periode; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE parm_periode IS 'Periode definition';
-- Name: TABLE quant_sold; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE quant_sold IS 'Contains about invoice for customer';
-- Name: TABLE stock_goods; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE stock_goods IS 'About the goods';
-- Name: TABLE tmp_pcmn; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE tmp_pcmn IS 'Plan comptable minimum normalisé';
-- Name: TABLE tva_rate; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE tva_rate IS 'Rate of vat';
-- Name: TABLE user_local_pref; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE user_local_pref IS 'The user''s local parameter ';
-- Name: COLUMN user_local_pref.user_id; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON COLUMN user_local_pref.user_id IS 'user''s login ';
-- Name: COLUMN user_local_pref.parameter_type; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON COLUMN user_local_pref.parameter_type IS 'the type of parameter ';
-- Name: COLUMN user_local_pref.parameter_value; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON COLUMN user_local_pref.parameter_value IS 'the value of parameter ';
-- Name: VIEW vw_fiche_def; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON VIEW vw_fiche_def IS 'all the attributs for  card family';
-- Name: VIEW vw_fiche_min; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON VIEW vw_fiche_min IS 'minimum attribut for reference card';
