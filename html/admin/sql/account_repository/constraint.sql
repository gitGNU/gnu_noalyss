ALTER TABLE ONLY ac_users
    ADD CONSTRAINT ac_users_pkey PRIMARY KEY (use_id);
ALTER TABLE ONLY ac_users
    ADD CONSTRAINT ac_users_use_login_key UNIQUE (use_login);
ALTER TABLE ONLY ac_dossier
    ADD CONSTRAINT ac_dossier_pkey PRIMARY KEY (dos_id);
ALTER TABLE ONLY ac_dossier
    ADD CONSTRAINT ac_dossier_dos_name_key UNIQUE (dos_name);
ALTER TABLE ONLY jnt_use_dos
    ADD CONSTRAINT jnt_use_dos_pkey PRIMARY KEY (use_id, dos_id);
ALTER TABLE ONLY jnt_use_dos
    ADD CONSTRAINT "$1" FOREIGN KEY (use_id) REFERENCES ac_users(use_id) ON UPDATE NO ACTION ON DELETE NO ACTION;
ALTER TABLE ONLY modeledef
    ADD CONSTRAINT modeledef_pkey PRIMARY KEY (mod_id);
