-- Name: x_act; Type: INDEX; Schema: public; Owner: phpcompta
CREATE UNIQUE INDEX x_act ON "action" USING btree (ac_description);
-- Name: x_usr_jrn; Type: INDEX; Schema: public; Owner: phpcompta
CREATE UNIQUE INDEX x_usr_jrn ON user_sec_jrn USING btree (uj_login, uj_jrn_id);
-- Name: x_jrn_jr_id; Type: INDEX; Schema: public; Owner: phpcompta
CREATE UNIQUE INDEX x_jrn_jr_id ON jrn USING btree (jr_id);
-- Name: fk_stock_goods_j_id; Type: INDEX; Schema: public; Owner: phpcompta
CREATE INDEX fk_stock_goods_j_id ON stock_goods USING btree (j_id);
-- Name: fk_stock_goods_f_id; Type: INDEX; Schema: public; Owner: phpcompta
CREATE INDEX fk_stock_goods_f_id ON stock_goods USING btree (f_id);
-- Name: x_poste; Type: INDEX; Schema: public; Owner: phpcompta
CREATE INDEX x_poste ON jrnx USING btree (j_poste);
