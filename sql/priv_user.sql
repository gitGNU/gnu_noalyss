-- Table: public.priv_user

-- DROP TABLE public.priv_user;

CREATE TABLE public.priv_user
(
  priv_id int4 NOT NULL DEFAULT nextval('seq_priv_user'::text),
  priv_jnt int4 NOT NULL,
  priv_priv text
) WITH OIDS;


