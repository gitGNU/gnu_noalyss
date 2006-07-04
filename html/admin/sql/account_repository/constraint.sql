ALTER TABLE public.ac_dossier OWNER TO phpcompta;
ALTER TABLE public.ac_users OWNER TO phpcompta;
ALTER TABLE public.dossier_id OWNER TO phpcompta;
ALTER TABLE public.jnt_use_dos OWNER TO phpcompta;
ALTER TABLE public.modeledef OWNER TO phpcompta;
ALTER TABLE public.priv_user OWNER TO phpcompta;
ALTER TABLE public.s_modid OWNER TO phpcompta;
ALTER TABLE public.seq_jnt_use_dos OWNER TO phpcompta;
ALTER TABLE public.seq_priv_user OWNER TO phpcompta;
ALTER TABLE public.theme OWNER TO phpcompta;
ALTER TABLE public.user_global_pref OWNER TO phpcompta;
ALTER TABLE public.users_id OWNER TO phpcompta;
ALTER TABLE public.version OWNER TO phpcompta;
ALTER TABLE ONLY ac_dossier
    ADD CONSTRAINT ac_dossier_dos_name_key UNIQUE (dos_name);
ALTER TABLE ONLY ac_dossier
    ADD CONSTRAINT ac_dossier_pkey PRIMARY KEY (dos_id);
ALTER TABLE ONLY ac_users
    ADD CONSTRAINT ac_users_pkey PRIMARY KEY (use_id);
ALTER TABLE ONLY ac_users
    ADD CONSTRAINT ac_users_use_login_key UNIQUE (use_login);
ALTER TABLE ONLY jnt_use_dos
    ADD CONSTRAINT jnt_use_dos_pkey PRIMARY KEY (use_id, dos_id);
ALTER TABLE ONLY modeledef
    ADD CONSTRAINT modeledef_pkey PRIMARY KEY (mod_id);
ALTER TABLE ONLY user_global_pref
    ADD CONSTRAINT pk_user_global_pref PRIMARY KEY (user_id, parameter_type);
ALTER TABLE ONLY user_global_pref
    ADD CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES ac_users(use_login) ON UPDATE CASCADE ON DELETE CASCADE;
