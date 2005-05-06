CREATE TABLE ac_users (
    use_id integer DEFAULT nextval('users_id'::text) NOT NULL,
    use_first_name text,
    use_name text,
    use_login text NOT NULL,
    use_active integer DEFAULT 0,
    use_pass text,
    use_admin integer DEFAULT 0,
    use_theme text DEFAULT 'Light'::text,
    use_usertype text NOT NULL,
    CHECK (((use_active = 0) OR (use_active = 1)))
);
CREATE TABLE ac_dossier (
    dos_id integer DEFAULT nextval('dossier_id'::text) NOT NULL,
    dos_name text NOT NULL,
    dos_description text,
    dos_jnt_user integer DEFAULT 0
);
CREATE TABLE jnt_use_dos (
    jnt_id integer DEFAULT nextval('seq_jnt_use_dos'::text) NOT NULL,
    use_id integer NOT NULL,
    dos_id integer NOT NULL
);
CREATE TABLE "version" (
    val integer
);
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
CREATE TABLE modeledef (
    mod_id integer DEFAULT nextval('s_modid'::text) NOT NULL,
    mod_name text NOT NULL,
    mod_desc text
);
