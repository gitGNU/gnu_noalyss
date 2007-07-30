COMMENT ON FUNCTION correct_sequence(p_sequence text, p_col text, p_table text) IS ' Often the primary key is a sequence number and sometimes the value of the sequence is not synchronized with the primary key ( p_sequence : sequence name, p_col : col of the pk,p_table : concerned table';
COMMENT ON TABLE "action" IS 'The different privileges';
COMMENT ON TABLE action_gestion IS 'Action for Managing';
COMMENT ON TABLE attr_def IS 'The available attributs for the cards';
COMMENT ON TABLE attr_min IS 'The value of  attributs for the cards';
COMMENT ON TABLE centralized IS 'The centralized journal';
COMMENT ON TABLE document IS 'This table contains all the documents : summary and lob files';
COMMENT ON TABLE document_modele IS ' contains all the template for the  documents';
COMMENT ON SEQUENCE document_seq IS 'Sequence for the sequence bound to the document modele';
COMMENT ON TABLE document_state IS 'State of the document';
COMMENT ON TABLE document_type IS 'Type of document : meeting, invoice,...';
COMMENT ON TABLE fiche IS 'Cards';
COMMENT ON TABLE fiche_def IS 'Cards definition';
COMMENT ON TABLE fiche_def_ref IS 'Family Cards definition';
COMMENT ON TABLE form IS 'Forms content';
COMMENT ON TABLE jnt_fic_att_value IS 'join between the card and the attribut definition';
COMMENT ON TABLE jnt_fic_attr IS 'join between the family card and the attribut definition';
COMMENT ON TABLE jrn IS 'Journal: content one line for a group of accountancy writing';
COMMENT ON TABLE jrn_action IS 'Possible action when we are in journal (menu)';
COMMENT ON TABLE jrn_def IS 'Definition of a journal, his properties';
COMMENT ON TABLE jrn_rapt IS 'Rapprochement between operation';
COMMENT ON TABLE jrn_type IS 'Type of journal (Sell, Buy, Financial...)';
COMMENT ON TABLE jrnx IS 'Journal: content one line for each accountancy writing';
COMMENT ON TABLE parm_money IS 'Currency conversion';
COMMENT ON TABLE parm_periode IS 'Periode definition';
COMMENT ON TABLE quant_sold IS 'Contains about invoice for customer';
COMMENT ON TABLE stock_goods IS 'About the goods';
COMMENT ON TABLE tmp_pcmn IS 'Plan comptable minimum normalisť';
COMMENT ON TABLE tva_rate IS 'Rate of vat';
COMMENT ON TABLE user_local_pref IS 'The user''s local parameter ';
COMMENT ON COLUMN user_local_pref.user_id IS 'user''s login ';
COMMENT ON COLUMN user_local_pref.parameter_type IS 'the type of parameter ';
COMMENT ON COLUMN user_local_pref.parameter_value IS 'the value of parameter ';
COMMENT ON VIEW vw_fiche_def IS 'all the attributs for  card family';
COMMENT ON VIEW vw_fiche_min IS 'minimum attribut for reference card';
