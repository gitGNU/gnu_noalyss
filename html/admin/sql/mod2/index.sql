ALTER INDEX public.action_pkey OWNER TO phpcompta;
ALTER INDEX public.attr_def_pkey OWNER TO phpcompta;
ALTER INDEX public.centralized_pkey OWNER TO phpcompta;
ALTER INDEX public.fiche_def_pkey OWNER TO phpcompta;
ALTER INDEX public.fiche_def_ref_pkey OWNER TO phpcompta;
ALTER INDEX public.fiche_pkey OWNER TO phpcompta;
ALTER INDEX public.form_pkey OWNER TO phpcompta;
ALTER INDEX public.formdef_pkey OWNER TO phpcompta;
ALTER INDEX public.jnt_fic_att_value_pkey OWNER TO phpcompta;
ALTER INDEX public.jrn_action_pkey OWNER TO phpcompta;
ALTER INDEX public.jrn_def_jrn_def_name_key OWNER TO phpcompta;
ALTER INDEX public.jrn_def_pkey OWNER TO phpcompta;
ALTER INDEX public.jrn_pkey OWNER TO phpcompta;
ALTER INDEX public.jrn_rapt_pkey OWNER TO phpcompta;
ALTER INDEX public.jrn_type_pkey OWNER TO phpcompta;
ALTER INDEX public.jrnx_pkey OWNER TO phpcompta;
ALTER INDEX public.parameter_pkey OWNER TO phpcompta;
ALTER INDEX public.parm_money_pkey OWNER TO phpcompta;
ALTER INDEX public.parm_periode_p_start_key OWNER TO phpcompta;
ALTER INDEX public.parm_periode_pkey OWNER TO phpcompta;
ALTER INDEX public.pk_user_local_pref OWNER TO phpcompta;
ALTER INDEX public.stock_goods_pkey OWNER TO phpcompta;
ALTER INDEX public.tmp_pcmn_pkey OWNER TO phpcompta;
ALTER INDEX public.user_pref_pkey OWNER TO phpcompta;
ALTER INDEX public.user_sec_act_pkey OWNER TO phpcompta;
ALTER INDEX public.user_sec_jrn_pkey OWNER TO phpcompta;
-- Name: fk_stock_goods_f_id; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE INDEX fk_stock_goods_f_id ON stock_goods USING btree (f_id);
ALTER INDEX public.fk_stock_goods_f_id OWNER TO phpcompta;
-- Name: fk_stock_goods_j_id; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE INDEX fk_stock_goods_j_id ON stock_goods USING btree (j_id);
ALTER INDEX public.fk_stock_goods_j_id OWNER TO phpcompta;
-- Name: x_jrn_jr_id; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE UNIQUE INDEX x_jrn_jr_id ON jrn USING btree (jr_id);
ALTER INDEX public.x_jrn_jr_id OWNER TO phpcompta;
-- Name: x_poste; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE INDEX x_poste ON jrnx USING btree (j_poste);
ALTER INDEX public.x_poste OWNER TO phpcompta;
