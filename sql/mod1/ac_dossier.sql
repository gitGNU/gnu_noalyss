-- Table: public.ac_dossier

-- DROP TABLE public.ac_dossier;

CREATE TABLE public.ac_dossier
(
  dos_id int4 NOT NULL DEFAULT nextval('dossier_id'::text),
  dos_name text NOT NULL,
  dos_description text,
  dos_jnt_user int4 DEFAULT 0,
  CONSTRAINT ac_dossier_pkey PRIMARY KEY (dos_id),
  CONSTRAINT ac_dossier_dos_name_key UNIQUE (dos_name)
) WITH OIDS;


