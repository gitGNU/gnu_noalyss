-- Name: TABLE tmp_pcmn; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE tmp_pcmn IS 'Plan comptable minimum normalisé';
-- Name: TABLE parm_money; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE parm_money IS 'Currency conversion';
-- Name: TABLE parm_periode; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE parm_periode IS 'Periode definition';
-- Name: TABLE jrn_type; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE jrn_type IS 'Type of journal (Sell, Buy, Financial...)';
-- Name: TABLE jrn_def; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE jrn_def IS 'Definition of a journal, his properties';
-- Name: TABLE jrnx; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE jrnx IS 'Journal: content one line for each accountancy writing';
-- Name: TABLE form; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE form IS 'Forms content';
-- Name: TABLE centralized; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE centralized IS 'The centralized journal';
-- Name: TABLE "action"; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE "action" IS 'The different privileges';
-- Name: TABLE jrn_action; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE jrn_action IS 'Possible action when we are in journal (menu)';
-- Name: TABLE tva_rate; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE tva_rate IS 'Rate of vat';
-- Name: TABLE fiche_def_ref; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE fiche_def_ref IS 'Family Cards definition';
-- Name: TABLE fiche_def; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE fiche_def IS 'Cards definition';
-- Name: TABLE attr_def; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE attr_def IS 'The available attributs for the cards';
-- Name: TABLE fiche; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE fiche IS 'Cards';
-- Name: TABLE jnt_fic_att_value; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE jnt_fic_att_value IS 'join between the card and the attribut definition';
-- Name: TABLE jnt_fic_attr; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE jnt_fic_attr IS 'join between the family card and the attribut definition';
-- Name: TABLE jrn_rapt; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE jrn_rapt IS 'Rapprochement between operation';
-- Name: TABLE jrn; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE jrn IS 'Journal: content one line for a group of accountancy writing';
-- Name: TABLE stock_goods; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE stock_goods IS 'About the goods';
-- Name: TABLE attr_min; Type: COMMENT; Schema: public; Owner: phpcompta
COMMENT ON TABLE attr_min IS 'The value of  attributs for the cards';
