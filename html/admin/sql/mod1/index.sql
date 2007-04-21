-- Name: attr_value_jft_id; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE UNIQUE INDEX attr_value_jft_id ON attr_value USING btree (jft_id);
-- Name: fd_id_ad_id_x; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE UNIQUE INDEX fd_id_ad_id_x ON jnt_fic_attr USING btree (fd_id, ad_id);
-- Name: fk_stock_goods_f_id; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE INDEX fk_stock_goods_f_id ON stock_goods USING btree (f_id);
-- Name: fk_stock_goods_j_id; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE INDEX fk_stock_goods_j_id ON stock_goods USING btree (j_id);
-- Name: idx_case; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE UNIQUE INDEX idx_case ON format_csv_banque USING btree (upper(name));
-- Name: idx_qs_internal; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE INDEX idx_qs_internal ON quant_sold USING btree (qs_internal);
-- Name: ix_iv_name; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE UNIQUE INDEX ix_iv_name ON invoice USING btree (upper(iv_name));
-- Name: k_ag_ref; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE UNIQUE INDEX k_ag_ref ON action_gestion USING btree (ag_ref);
-- Name: x_jrn_jr_id; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE UNIQUE INDEX x_jrn_jr_id ON jrn USING btree (jr_id);
-- Name: x_periode; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE UNIQUE INDEX x_periode ON parm_periode USING btree (p_start, p_end);
-- Name: x_poste; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
CREATE INDEX x_poste ON jrnx USING btree (j_poste);
