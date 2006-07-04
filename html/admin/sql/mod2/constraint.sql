ALTER TABLE public."action" OWNER TO phpcompta;
ALTER TABLE public.action_gestion OWNER TO phpcompta;
ALTER TABLE public.attr_def OWNER TO phpcompta;
ALTER TABLE public.attr_min OWNER TO phpcompta;
ALTER TABLE public.attr_value OWNER TO phpcompta;
ALTER TABLE public.centralized OWNER TO phpcompta;
ALTER TABLE public.document OWNER TO phpcompta;
ALTER TABLE public.document_modele OWNER TO phpcompta;
ALTER TABLE public.document_seq OWNER TO phpcompta;
ALTER TABLE public.document_state OWNER TO phpcompta;
ALTER TABLE public.document_type OWNER TO phpcompta;
ALTER TABLE public.fiche OWNER TO phpcompta;
ALTER TABLE public.fiche_def OWNER TO phpcompta;
ALTER TABLE public.fiche_def_ref OWNER TO phpcompta;
ALTER TABLE public.form OWNER TO phpcompta;
ALTER TABLE public.format_csv_banque OWNER TO phpcompta;
ALTER TABLE public.formdef OWNER TO phpcompta;
ALTER TABLE public.import_tmp OWNER TO phpcompta;
ALTER TABLE public.invoice OWNER TO phpcompta;
ALTER TABLE public.jnt_fic_att_value OWNER TO phpcompta;
ALTER TABLE public.jnt_fic_attr OWNER TO phpcompta;
ALTER TABLE public.jrn OWNER TO phpcompta;
ALTER TABLE public.jrn_action OWNER TO phpcompta;
ALTER TABLE public.jrn_def OWNER TO phpcompta;
ALTER TABLE public.jrn_rapt OWNER TO phpcompta;
ALTER TABLE public.jrn_type OWNER TO phpcompta;
ALTER TABLE public.jrnx OWNER TO phpcompta;
ALTER TABLE public.parameter OWNER TO phpcompta;
ALTER TABLE public.parm_code OWNER TO phpcompta;
ALTER TABLE public.parm_money OWNER TO phpcompta;
ALTER TABLE public.parm_periode OWNER TO phpcompta;
ALTER TABLE public.quant_sold OWNER TO phpcompta;
ALTER TABLE public.s_attr_def OWNER TO phpcompta;
ALTER TABLE public.s_central OWNER TO phpcompta;
ALTER TABLE public.s_central_order OWNER TO phpcompta;
ALTER TABLE public.s_centralized OWNER TO phpcompta;
ALTER TABLE public.s_currency OWNER TO phpcompta;
ALTER TABLE public.s_fdef OWNER TO phpcompta;
ALTER TABLE public.s_fiche OWNER TO phpcompta;
ALTER TABLE public.s_fiche_def_ref OWNER TO phpcompta;
ALTER TABLE public.s_form OWNER TO phpcompta;
ALTER TABLE public.s_formdef OWNER TO phpcompta;
ALTER TABLE public.s_grpt OWNER TO phpcompta;
ALTER TABLE public.s_idef OWNER TO phpcompta;
ALTER TABLE public.s_internal OWNER TO phpcompta;
ALTER TABLE public.s_invoice OWNER TO phpcompta;
ALTER TABLE public.s_isup OWNER TO phpcompta;
ALTER TABLE public.s_jnt_fic_att_value OWNER TO phpcompta;
ALTER TABLE public.s_jrn OWNER TO phpcompta;
ALTER TABLE public.s_jrn_1 OWNER TO phpcompta;
ALTER TABLE public.s_jrn_2 OWNER TO phpcompta;
ALTER TABLE public.s_jrn_3 OWNER TO phpcompta;
ALTER TABLE public.s_jrn_4 OWNER TO phpcompta;
ALTER TABLE public.s_jrn_def OWNER TO phpcompta;
ALTER TABLE public.s_jrn_op OWNER TO phpcompta;
ALTER TABLE public.s_jrn_rapt OWNER TO phpcompta;
ALTER TABLE public.s_jrnaction OWNER TO phpcompta;
ALTER TABLE public.s_jrnx OWNER TO phpcompta;
ALTER TABLE public.s_periode OWNER TO phpcompta;
ALTER TABLE public.s_quantity OWNER TO phpcompta;
ALTER TABLE public.s_stock_goods OWNER TO phpcompta;
ALTER TABLE public.s_user_act OWNER TO phpcompta;
ALTER TABLE public.s_user_jrn OWNER TO phpcompta;
ALTER TABLE public.seq_doc_type_1 OWNER TO phpcompta;
ALTER TABLE public.seq_doc_type_2 OWNER TO phpcompta;
ALTER TABLE public.seq_doc_type_3 OWNER TO phpcompta;
ALTER TABLE public.seq_doc_type_4 OWNER TO phpcompta;
ALTER TABLE public.seq_doc_type_5 OWNER TO phpcompta;
ALTER TABLE public.seq_doc_type_6 OWNER TO phpcompta;
ALTER TABLE public.seq_doc_type_7 OWNER TO phpcompta;
ALTER TABLE public.seq_doc_type_8 OWNER TO phpcompta;
ALTER TABLE public.seq_doc_type_9 OWNER TO phpcompta;
ALTER TABLE public.stock_goods OWNER TO phpcompta;
ALTER TABLE public.tmp_pcmn OWNER TO phpcompta;
ALTER TABLE public.tva_rate OWNER TO phpcompta;
ALTER TABLE public.user_local_pref OWNER TO phpcompta;
ALTER TABLE public.user_sec_act OWNER TO phpcompta;
ALTER TABLE public.user_sec_jrn OWNER TO phpcompta;
ALTER TABLE public.version OWNER TO phpcompta;
ALTER TABLE public.vw_client OWNER TO phpcompta;
ALTER TABLE public.vw_fiche_attr OWNER TO phpcompta;
ALTER TABLE public.vw_fiche_def OWNER TO phpcompta;
ALTER TABLE public.vw_fiche_min OWNER TO phpcompta;
ALTER TABLE public.vw_poste_qcode OWNER TO phpcompta;
ALTER TABLE public.vw_supplier OWNER TO phpcompta;
ALTER TABLE ONLY action_gestion
    ADD CONSTRAINT action_gestion_pkey PRIMARY KEY (ag_id);
ALTER TABLE ONLY "action"
    ADD CONSTRAINT action_pkey PRIMARY KEY (ac_id);
ALTER TABLE ONLY attr_def
    ADD CONSTRAINT attr_def_pkey PRIMARY KEY (ad_id);
ALTER TABLE ONLY centralized
    ADD CONSTRAINT centralized_pkey PRIMARY KEY (c_id);
ALTER TABLE ONLY document_modele
    ADD CONSTRAINT document_modele_pkey PRIMARY KEY (md_id);
ALTER TABLE ONLY document
    ADD CONSTRAINT document_pkey PRIMARY KEY (d_id);
ALTER TABLE ONLY document_state
    ADD CONSTRAINT document_state_pkey PRIMARY KEY (s_id);
ALTER TABLE ONLY document_type
    ADD CONSTRAINT document_type_pkey PRIMARY KEY (dt_id);
ALTER TABLE ONLY fiche_def
    ADD CONSTRAINT fiche_def_pkey PRIMARY KEY (fd_id);
ALTER TABLE ONLY fiche_def_ref
    ADD CONSTRAINT fiche_def_ref_pkey PRIMARY KEY (frd_id);
ALTER TABLE ONLY fiche
    ADD CONSTRAINT fiche_pkey PRIMARY KEY (f_id);
ALTER TABLE ONLY form
    ADD CONSTRAINT form_pkey PRIMARY KEY (fo_id);
ALTER TABLE ONLY format_csv_banque
    ADD CONSTRAINT format_csv_banque_pkey PRIMARY KEY (name);
ALTER TABLE ONLY formdef
    ADD CONSTRAINT formdef_pkey PRIMARY KEY (fr_id);
ALTER TABLE ONLY invoice
    ADD CONSTRAINT invoice_pkey PRIMARY KEY (iv_id);
ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT jnt_fic_att_value_pkey PRIMARY KEY (jft_id);
ALTER TABLE ONLY jrn_action
    ADD CONSTRAINT jrn_action_pkey PRIMARY KEY (ja_id);
ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT jrn_def_jrn_def_name_key UNIQUE (jrn_def_name);
ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT jrn_def_pkey PRIMARY KEY (jrn_def_id);
ALTER TABLE ONLY jrn
    ADD CONSTRAINT jrn_pkey PRIMARY KEY (jr_id, jr_def_id);
ALTER TABLE ONLY jrn_rapt
    ADD CONSTRAINT jrn_rapt_pkey PRIMARY KEY (jra_id);
ALTER TABLE ONLY jrn_type
    ADD CONSTRAINT jrn_type_pkey PRIMARY KEY (jrn_type_id);
ALTER TABLE ONLY jrnx
    ADD CONSTRAINT jrnx_pkey PRIMARY KEY (j_id);
ALTER TABLE ONLY parameter
    ADD CONSTRAINT parameter_pkey PRIMARY KEY (pr_id);
ALTER TABLE ONLY parm_code
    ADD CONSTRAINT parm_code_pkey PRIMARY KEY (p_code);
ALTER TABLE ONLY parm_money
    ADD CONSTRAINT parm_money_pkey PRIMARY KEY (pm_code);
ALTER TABLE ONLY parm_periode
    ADD CONSTRAINT parm_periode_p_start_key UNIQUE (p_start);
ALTER TABLE ONLY parm_periode
    ADD CONSTRAINT parm_periode_pkey PRIMARY KEY (p_id);
ALTER TABLE ONLY user_local_pref
    ADD CONSTRAINT pk_user_local_pref PRIMARY KEY (user_id, parameter_type);
ALTER TABLE ONLY quant_sold
    ADD CONSTRAINT qs_id_pk PRIMARY KEY (qs_id);
ALTER TABLE ONLY stock_goods
    ADD CONSTRAINT stock_goods_pkey PRIMARY KEY (sg_id);
ALTER TABLE ONLY tmp_pcmn
    ADD CONSTRAINT tmp_pcmn_pkey PRIMARY KEY (pcm_val);
ALTER TABLE ONLY user_sec_act
    ADD CONSTRAINT user_sec_act_pkey PRIMARY KEY (ua_id);
ALTER TABLE ONLY user_sec_jrn
    ADD CONSTRAINT user_sec_jrn_pkey PRIMARY KEY (uj_id);
ALTER TABLE ONLY jrn_def
    ADD CONSTRAINT "$1" FOREIGN KEY (jrn_def_type) REFERENCES jrn_type(jrn_type_id);
ALTER TABLE ONLY form
    ADD CONSTRAINT "$1" FOREIGN KEY (fo_fr_id) REFERENCES formdef(fr_id);
ALTER TABLE ONLY centralized
    ADD CONSTRAINT "$1" FOREIGN KEY (c_jrn_def) REFERENCES jrn_def(jrn_def_id);
ALTER TABLE ONLY user_sec_jrn
    ADD CONSTRAINT "$1" FOREIGN KEY (uj_jrn_id) REFERENCES jrn_def(jrn_def_id);
ALTER TABLE ONLY user_sec_act
    ADD CONSTRAINT "$1" FOREIGN KEY (ua_act_id) REFERENCES "action"(ac_id);
ALTER TABLE ONLY jrn_action
    ADD CONSTRAINT "$1" FOREIGN KEY (ja_jrn_type) REFERENCES jrn_type(jrn_type_id);
ALTER TABLE ONLY attr_min
    ADD CONSTRAINT "$1" FOREIGN KEY (frd_id) REFERENCES fiche_def_ref(frd_id);
ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT "$1" FOREIGN KEY (f_id) REFERENCES fiche(f_id);
ALTER TABLE ONLY attr_value
    ADD CONSTRAINT "$1" FOREIGN KEY (jft_id) REFERENCES jnt_fic_att_value(jft_id);
ALTER TABLE ONLY jnt_fic_attr
    ADD CONSTRAINT "$1" FOREIGN KEY (fd_id) REFERENCES fiche_def(fd_id);
ALTER TABLE ONLY jrn
    ADD CONSTRAINT "$1" FOREIGN KEY (jr_def_id) REFERENCES jrn_def(jrn_def_id);
ALTER TABLE ONLY fiche
    ADD CONSTRAINT "$1" FOREIGN KEY (fd_id) REFERENCES fiche_def(fd_id);
ALTER TABLE ONLY fiche_def
    ADD CONSTRAINT "$1" FOREIGN KEY (frd_id) REFERENCES fiche_def_ref(frd_id);
ALTER TABLE ONLY jrnx
    ADD CONSTRAINT "$1" FOREIGN KEY (j_poste) REFERENCES tmp_pcmn(pcm_val);
ALTER TABLE ONLY jrnx
    ADD CONSTRAINT "$2" FOREIGN KEY (j_jrn_def) REFERENCES jrn_def(jrn_def_id);
ALTER TABLE ONLY attr_min
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id);
ALTER TABLE ONLY jnt_fic_att_value
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id);
ALTER TABLE ONLY jnt_fic_attr
    ADD CONSTRAINT "$2" FOREIGN KEY (ad_id) REFERENCES attr_def(ad_id);
ALTER TABLE ONLY centralized
    ADD CONSTRAINT "$2" FOREIGN KEY (c_poste) REFERENCES tmp_pcmn(pcm_val);
ALTER TABLE ONLY document_modele
    ADD CONSTRAINT md_type FOREIGN KEY (md_type) REFERENCES document_type(dt_id);
