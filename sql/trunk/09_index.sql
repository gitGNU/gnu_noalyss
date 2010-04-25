-- Index: xx_grpt_id

-- DROP INDEX xx_grpt_id;

CREATE INDEX xx_grpt_id
  ON jrnx
  USING btree
  (j_grpt);
  CREATE INDEX x_grpt_id
  ON jrn
  USING btree
  (jr_grpt_id);
create index letter_deb_fkidx on letter_deb(j_id);
create index letter_cred_fkidx on letter_cred(j_id);