-- Name: x_act; Type: INDEX; Schema: public; Owner: phpcompta
CREATE UNIQUE INDEX x_act ON "action" USING btree (ac_description);
-- Name: x_usr_jrn; Type: INDEX; Schema: public; Owner: phpcompta
CREATE UNIQUE INDEX x_usr_jrn ON user_sec_jrn USING btree (uj_login, uj_jrn_id);
-- Name: fk_centralized_c_jrn_def; Type: INDEX; Schema: public; Owner: phpcompta
CREATE INDEX fk_centralized_c_jrn_def ON centralized USING btree (c_jrn_def);
-- Name: fk_centralized_c_poste; Type: INDEX; Schema: public; Owner: phpcompta
CREATE INDEX fk_centralized_c_poste ON centralized USING btree (c_poste);
-- Name: fk_fiche_def_frd_id; Type: INDEX; Schema: public; Owner: phpcompta
CREATE INDEX fk_fiche_def_frd_id ON fiche_def USING btree (frd_id);
-- Name: fk_attr_value_jft_id; Type: INDEX; Schema: public; Owner: phpcompta
CREATE INDEX fk_attr_value_jft_id ON attr_value USING btree (jft_id);
-- Name: fk_fiche_fd_id; Type: INDEX; Schema: public; Owner: phpcompta
CREATE INDEX fk_fiche_fd_id ON fiche USING btree (fd_id);
-- Name: fk_form_fo_fr_id; Type: INDEX; Schema: public; Owner: phpcompta
CREATE INDEX fk_form_fo_fr_id ON form USING btree (fo_fr_id);
-- Name: fk_jrnx_j_poste; Type: INDEX; Schema: public; Owner: phpcompta
CREATE INDEX fk_jrnx_j_poste ON jrnx USING btree (j_poste);
-- Name: fk_jrn_def; Type: INDEX; Schema: public; Owner: phpcompta
CREATE INDEX fk_jrn_def ON jrnx USING btree (j_jrn_def);
-- Name: fk_jrn_action_ja_jrn_type; Type: INDEX; Schema: public; Owner: phpcompta
CREATE INDEX fk_jrn_action_ja_jrn_type ON jrn_action USING btree (ja_jrn_type);
-- Name: fk_user_sec_jrn; Type: INDEX; Schema: public; Owner: phpcompta
CREATE INDEX fk_user_sec_jrn ON user_sec_jrn USING btree (uj_jrn_id);
-- Name: fk_user_sec_act; Type: INDEX; Schema: public; Owner: phpcompta
CREATE INDEX fk_user_sec_act ON user_sec_act USING btree (ua_act_id);
-- Name: x_jrn_jr_id; Type: INDEX; Schema: public; Owner: phpcompta
CREATE UNIQUE INDEX x_jrn_jr_id ON jrn USING btree (jr_id);
-- Name: fk_stock_goods_j_id; Type: INDEX; Schema: public; Owner: phpcompta
CREATE INDEX fk_stock_goods_j_id ON stock_goods USING btree (j_id);
-- Name: fk_stock_goods_f_id; Type: INDEX; Schema: public; Owner: phpcompta
CREATE INDEX fk_stock_goods_f_id ON stock_goods USING btree (f_id);
