-- Table: public.modeledef

-- DROP TABLE public.modeledef;

CREATE TABLE public.modeledef
(
  mod_id int4 NOT NULL DEFAULT nextval('s_modid'::text),
  mod_name text NOT NULL,
  mod_desc text,
  CONSTRAINT modeledef_pkey PRIMARY KEY (mod_id)
) WITH OIDS;


