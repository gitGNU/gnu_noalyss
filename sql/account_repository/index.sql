-- Name: fk_jnt_use_dos; Type: INDEX; Schema: public; Owner: phpcompta
CREATE INDEX fk_jnt_use_dos ON jnt_use_dos USING btree (use_id);
-- Name: fk_jnt_dos_id; Type: INDEX; Schema: public; Owner: phpcompta
CREATE INDEX fk_jnt_dos_id ON jnt_use_dos USING btree (dos_id);
