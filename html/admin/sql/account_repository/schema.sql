\connect - phpcompta
SET search_path = public, pg_catalog;
CREATE SEQUENCE users_id
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;
CREATE TABLE ac_users (
    use_id integer DEFAULT nextval('users_id'::text) NOT NULL,
    use_first_name text,
    use_name text,
    use_login text NOT NULL,
    use_active integer DEFAULT 0,
    use_pass text,
    use_admin integer DEFAULT 0,
    use_theme text DEFAULT 'Light',
    use_usertype text NOT NULL,
    CHECK (((use_active = 0) OR (use_active = 1)))
);
CREATE TABLE ac_dossier (
    dos_id integer DEFAULT nextval('dossier_id'::text) NOT NULL,
    dos_name text NOT NULL,
    dos_description text,
    dos_jnt_user integer DEFAULT 0
);
CREATE SEQUENCE seq_jnt_use_dos
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;
CREATE TABLE jnt_use_dos (
    jnt_id integer DEFAULT nextval('seq_jnt_use_dos'::text) NOT NULL,
    use_id integer NOT NULL,
    dos_id integer NOT NULL
);
CREATE TABLE "version" (
    val integer
);
CREATE SEQUENCE seq_priv_user
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;
CREATE TABLE priv_user (
    priv_id integer DEFAULT nextval('seq_priv_user'::text) NOT NULL,
    priv_jnt integer NOT NULL,
    priv_priv text
);
CREATE TABLE theme (
    the_name text NOT NULL,
    the_filestyle text,
    the_filebutton text
);
CREATE SEQUENCE s_modid
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;
CREATE TABLE modeledef (
    mod_id integer DEFAULT nextval('s_modid'::text) NOT NULL,
    mod_name text NOT NULL,
    mod_desc text
);
CREATE SEQUENCE dossier_id
    START 3
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 3
    CACHE 1;
CREATE TABLE log (
    lg_timestamp timestamp without time zone DEFAULT now(),
    lg_file text,
    lg_type text DEFAULT 'debug',
    lg_line text,
    lg_msg text
);
CREATE INDEX fk_jnt_use_dos ON jnt_use_dos USING btree (use_id);
CREATE INDEX fk_jnt_dos_id ON jnt_use_dos USING btree (dos_id);
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
