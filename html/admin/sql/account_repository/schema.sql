SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

CREATE TABLE ac_dossier (
    dos_id integer DEFAULT nextval('dossier_id'::text) NOT NULL,
    dos_name text NOT NULL,
    dos_description text,
    dos_jnt_user integer DEFAULT 0
);

CREATE TABLE ac_users (
    use_id integer DEFAULT nextval('users_id'::text) NOT NULL,
    use_first_name text,
    use_name text,
    use_login text NOT NULL,
    use_active integer DEFAULT 0,
    use_pass text,
    use_admin integer DEFAULT 0,
    CONSTRAINT ac_users_use_active_check CHECK (((use_active = 0) OR (use_active = 1)))
);

CREATE SEQUENCE dossier_id
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE TABLE jnt_use_dos (
    jnt_id integer DEFAULT nextval('seq_jnt_use_dos'::text) NOT NULL,
    use_id integer NOT NULL,
    dos_id integer NOT NULL
);

CREATE TABLE modeledef (
    mod_id integer DEFAULT nextval('s_modid'::text) NOT NULL,
    mod_name text NOT NULL,
    mod_desc text
);

CREATE TABLE priv_user (
    priv_id integer DEFAULT nextval('seq_priv_user'::text) NOT NULL,
    priv_jnt integer NOT NULL,
    priv_priv text
);

CREATE SEQUENCE s_modid
    START WITH 6
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE seq_jnt_use_dos
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE SEQUENCE seq_priv_user
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE TABLE theme (
    the_name text NOT NULL,
    the_filestyle text,
    the_filebutton text
);

CREATE TABLE user_global_pref (
    user_id text NOT NULL,
    parameter_type text NOT NULL,
    parameter_value text
);

COMMENT ON TABLE user_global_pref IS 'The user''s global parameter ';
COMMENT ON COLUMN user_global_pref.user_id IS 'user''s login ';
COMMENT ON COLUMN user_global_pref.parameter_type IS 'the type of parameter ';
COMMENT ON COLUMN user_global_pref.parameter_value IS 'the value of parameter ';
CREATE SEQUENCE users_id
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE TABLE version (
    val integer
);

ALTER TABLE ONLY ac_dossier
    ADD CONSTRAINT ac_dossier_dos_name_key UNIQUE (dos_name);
ALTER INDEX public.ac_dossier_dos_name_key OWNER TO phpcompta;
ALTER TABLE ONLY ac_dossier
    ADD CONSTRAINT ac_dossier_pkey PRIMARY KEY (dos_id);
ALTER INDEX public.ac_dossier_pkey OWNER TO phpcompta;
ALTER TABLE ONLY ac_users
    ADD CONSTRAINT ac_users_pkey PRIMARY KEY (use_id);
ALTER INDEX public.ac_users_pkey OWNER TO phpcompta;
ALTER TABLE ONLY ac_users
    ADD CONSTRAINT ac_users_use_login_key UNIQUE (use_login);
ALTER INDEX public.ac_users_use_login_key OWNER TO phpcompta;
ALTER TABLE ONLY jnt_use_dos
    ADD CONSTRAINT jnt_use_dos_pkey PRIMARY KEY (use_id, dos_id);
ALTER INDEX public.jnt_use_dos_pkey OWNER TO phpcompta;
ALTER TABLE ONLY modeledef
    ADD CONSTRAINT modeledef_pkey PRIMARY KEY (mod_id);
ALTER INDEX public.modeledef_pkey OWNER TO phpcompta;
ALTER TABLE ONLY user_global_pref
    ADD CONSTRAINT pk_user_global_pref PRIMARY KEY (user_id, parameter_type);
ALTER INDEX public.pk_user_global_pref OWNER TO phpcompta;
CREATE INDEX fk_jnt_dos_id ON jnt_use_dos USING btree (dos_id);
ALTER INDEX public.fk_jnt_dos_id OWNER TO phpcompta;
CREATE INDEX fk_jnt_use_dos ON jnt_use_dos USING btree (use_id);
ALTER INDEX public.fk_jnt_use_dos OWNER TO phpcompta;
ALTER TABLE ONLY user_global_pref
    ADD CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES ac_users(use_login) ON UPDATE CASCADE ON DELETE CASCADE;
REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;
