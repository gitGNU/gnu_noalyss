ALTER INDEX public.action_gestion_pkey OWNER TO phpcompta;
ALTER INDEX public.action_pkey OWNER TO phpcompta;
ALTER INDEX public.attr_def_pkey OWNER TO phpcompta;
ALTER INDEX public.centralized_pkey OWNER TO phpcompta;
ALTER INDEX public.document_modele_pkey OWNER TO phpcompta;
ALTER INDEX public.document_pkey OWNER TO phpcompta;
ALTER INDEX public.document_state_pkey OWNER TO phpcompta;
ALTER INDEX public.document_type_pkey OWNER TO phpcompta;
ALTER INDEX public.fiche_def_pkey OWNER TO phpcompta;
ALTER INDEX public.fiche_def_ref_pkey OWNER TO phpcompta;
ALTER INDEX public.fiche_pkey OWNER TO phpcompta;
ALTER INDEX public.form_pkey OWNER TO phpcompta;
ALTER INDEX public.format_csv_banque_pkey OWNER TO phpcompta;
ALTER INDEX public.formdef_pkey OWNER TO phpcompta;
ALTER INDEX public.invoice_pkey OWNER TO phpcompta;
ALTER INDEX public.jnt_fic_att_value_pkey OWNER TO phpcompta;
ALTER INDEX public.jrn_action_pkey OWNER TO phpcompta;
ALTER INDEX public.jrn_def_jrn_def_name_key OWNER TO phpcompta;
ALTER INDEX public.jrn_def_pkey OWNER TO phpcompta;
ALTER INDEX public.jrn_pkey OWNER TO phpcompta;
ALTER INDEX public.jrn_rapt_pkey OWNER TO phpcompta;
ALTER INDEX public.jrn_type_pkey OWNER TO phpcompta;
ALTER INDEX public.jrnx_pkey OWNER TO phpcompta;
ALTER INDEX public.parameter_pkey OWNER TO phpcompta;
ALTER INDEX public.parm_code_pkey OWNER TO phpcompta;
ALTER INDEX public.parm_money_pkey OWNER TO phpcompta;
ALTER INDEX public.parm_periode_p_start_key OWNER TO phpcompta;
ALTER INDEX public.parm_periode_pkey OWNER TO phpcompta;
ALTER INDEX public.pk_user_local_pref OWNER TO phpcompta;
ALTER INDEX public.qs_id_pk OWNER TO phpcompta;
ALTER INDEX public.stock_goods_pkey OWNER TO phpcompta;
ALTER INDEX public.tmp_pcmn_pkey OWNER TO phpcompta;
ALTER INDEX public.user_sec_act_pkey OWNER TO phpcompta;
ALTER INDEX public.user_sec_jrn_pkey OWNER TO phpcompta;
-- Name: attr_value_jft_id; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE UNIQUE INDEX attr_value_jft_id ON attr_value USING btree (jft_id);
ALTER INDEX public.attr_value_jft_id OWNER TO phpcompta;
-- Name: fk_stock_goods_f_id; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE INDEX fk_stock_goods_f_id ON stock_goods USING btree (f_id);
ALTER INDEX public.fk_stock_goods_f_id OWNER TO phpcompta;
-- Name: fk_stock_goods_j_id; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE INDEX fk_stock_goods_j_id ON stock_goods USING btree (j_id);
ALTER INDEX public.fk_stock_goods_j_id OWNER TO phpcompta;
-- Name: idx_case; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE UNIQUE INDEX idx_case ON format_csv_banque USING btree (upper(name));
ALTER INDEX public.idx_case OWNER TO phpcompta;
-- Name: idx_qs_internal; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE INDEX idx_qs_internal ON quant_sold USING btree (qs_internal);
ALTER INDEX public.idx_qs_internal OWNER TO phpcompta;
-- Name: ix_iv_name; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE UNIQUE INDEX ix_iv_name ON invoice USING btree (upper(iv_name));
ALTER INDEX public.ix_iv_name OWNER TO phpcompta;
-- Name: k_ag_ref; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE UNIQUE INDEX k_ag_ref ON action_gestion USING btree (ag_ref);
ALTER INDEX public.k_ag_ref OWNER TO phpcompta;
-- Name: x_jrn_jr_id; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE UNIQUE INDEX x_jrn_jr_id ON jrn USING btree (jr_id);
ALTER INDEX public.x_jrn_jr_id OWNER TO phpcompta;
-- Name: x_poste; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE INDEX x_poste ON jrnx USING btree (j_poste);
ALTER INDEX public.x_poste OWNER TO phpcompta;
