-- Table: public.ac_users

-- DROP TABLE public.ac_users;

CREATE TABLE public.ac_users
(
  use_id int4 NOT NULL DEFAULT nextval('users_id'::text),
  use_first_name text,
  use_name text,
  use_login text NOT NULL,
  use_active int4 DEFAULT 0,
  use_pass text,
  use_admin int4 DEFAULT 0,
  use_theme text DEFAULT 'Light',
  use_usertype text NOT NULL,
  CONSTRAINT ac_users_pkey PRIMARY KEY (use_id),
  CONSTRAINT ac_users_use_login_key UNIQUE (use_login),
  CONSTRAINT "$1" CHECK ((use_active = 0) OR (use_active = 1))
) WITH OIDS;


