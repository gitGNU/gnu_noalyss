CREATE OR REPLACE FUNCTION comptaproc.check_balance(p_grpt integer)
  RETURNS numeric AS
$BODY$
declare
	amount_jrnx_debit numeric;
	amount_jrnx_credit numeric;
	amount_jrn numeric;
begin
	select coalesce(sum (j_montant),0) into amount_jrnx_credit
	from jrnx
		where
	j_grpt=p_grpt
	and j_debit=false;

	select coalesce(sum (j_montant),0) into amount_jrnx_debit
	from jrnx
		where
	j_grpt=p_grpt
	and j_debit=true;

	select coalesce(jr_montant,0) into amount_jrn
	from jrn
	where
	jr_grpt_id=p_grpt;

	if ( amount_jrnx_debit != amount_jrnx_credit )
		then
		return abs(amount_jrnx_debit-amount_jrnx_credit);
		end if;
	if ( amount_jrn != amount_jrnx_credit)
		then
		return -1*abs(amount_jrn - amount_jrnx_credit);
		end if;
	return 0;
end;
$BODY$
  LANGUAGE plpgsql;

update op_predef set od_direct='t' where od_jrn_type='ODS';



INSERT INTO menu_ref(
            me_code, me_menu, me_file, me_url, me_description, me_parameter,
            me_javascript, me_type)
    VALUES ('BK', 'Banque', 'bank.inc.php', null, 'Information Banque', null,null,'ME');

INSERT INTO profile_menu(
             me_code, me_code_dep, p_id, p_order, p_type_display, pm_default)
    VALUES ('BK', 'GESTION', 1, 4, 'E', 0);
INSERT INTO profile_menu(
             me_code, me_code_dep, p_id, p_order, p_type_display, pm_default)
    VALUES ('BK', 'GESTION', 2, 4, 'E', 0);

update menu_ref set me_description='Grand livre analytique' where me_code='ANCGL';

alter table action_gestion add ag_remind_date date;

drop table jrn_action;

update action_gestion set ag_dest=null;
 alter table action_gestion alter ag_dest type bigint using ag_dest::numeric;
 alter table action_gestion alter ag_dest set default null;
COMMENT ON COLUMN action_gestion.ag_dest IS ' is the profile which has to take care of this action ';
ALTER TABLE action_gestion
  ADD CONSTRAINT profile_fkey FOREIGN KEY (ag_dest)
      REFERENCES profile (p_id) MATCH SIMPLE
      ON UPDATE SET NULL ON DELETE SET NULL;

CREATE TABLE action_gestion_comment
(
  agc_id bigserial NOT NULL, -- PK
  ag_id bigint, -- FK to action_gestion
  agc_date timestamp with time zone,
  agc_comment text, -- comment
  tech_user text, -- user_login
  CONSTRAINT action_gestion_comment_pkey PRIMARY KEY (agc_id ),
  CONSTRAINT action_gestion_comment_ag_id_fkey FOREIGN KEY (ag_id)
      REFERENCES action_gestion (ag_id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
);
ALTER TABLE action_gestion_comment ALTER COLUMN agc_date SET DEFAULT now();
COMMENT ON COLUMN action_gestion_comment.agc_id IS 'PK';
COMMENT ON COLUMN action_gestion_comment.ag_id IS 'FK to action_gestion';
COMMENT ON COLUMN action_gestion_comment.agc_comment IS 'comment';
COMMENT ON COLUMN action_gestion_comment.tech_user IS 'user_login';


insert into action_gestion_comment (ag_id,agc_date,agc_comment,tech_user) select ag_id,ag_timestamp,ag_comment,ag_owner from action_gestion;
ALTER TABLE action_gestion drop COLUMN ag_comment;

CREATE TABLE action_gestion_operation
(
  ago_id bigserial NOT NULL, -- pk
  ag_id bigint, -- fk to action_gestion
  jr_id bigint, -- fk to jrn
  CONSTRAINT action_comment_operation_pkey PRIMARY KEY (ago_id ),
  CONSTRAINT action_comment_operation_ag_id_fkey FOREIGN KEY (ag_id)
      REFERENCES action_gestion (ag_id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT action_comment_operation_jr_id_fkey FOREIGN KEY (jr_id)
      REFERENCES jrn (jr_id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
);
COMMENT ON COLUMN action_gestion_operation.ago_id IS 'pk';
COMMENT ON COLUMN action_gestion_operation.ag_id IS 'fk to action_gestion';
COMMENT ON COLUMN action_gestion_operation.jr_id IS 'fk to jrn';

CREATE TABLE link_action_type
(
  l_id bigserial NOT NULL, -- PK
  l_desc character varying,
  CONSTRAINT link_action_type_pkey PRIMARY KEY (l_id )
);


CREATE TABLE action_gestion_related
(
  aga_id bigserial NOT NULL, -- pk
  aga_least bigint NOT NULL, -- fk to action_gestion, smallest ag_id
  aga_greatest bigint NOT NULL, -- fk to action_gestion greatest ag_id
  aga_type bigint, -- Type de liens
  CONSTRAINT action_gestion_related_pkey PRIMARY KEY (aga_id ),
  CONSTRAINT action_gestion_related_aga_greatest_fkey FOREIGN KEY (aga_greatest)
      REFERENCES action_gestion (ag_id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT action_gestion_related_aga_least_fkey FOREIGN KEY (aga_least)
      REFERENCES action_gestion (ag_id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT action_gestion_related_aga_type_fkey FOREIGN KEY (aga_type)
      REFERENCES link_action_type (l_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT ux_aga_least_aga_greatest UNIQUE (aga_least , aga_greatest )
);

COMMENT ON COLUMN action_gestion_related.aga_id IS 'pk';
COMMENT ON COLUMN action_gestion_related.aga_least IS 'fk to action_gestion, smallest ag_id';
COMMENT ON COLUMN action_gestion_related.aga_greatest IS 'fk to action_gestion greatest ag_id';
COMMENT ON COLUMN action_gestion_related.aga_type IS 'Type de liens';

CREATE INDEX link_action_type_fki
  ON action_gestion_related
  USING btree
  (aga_type );

-- Trigger: trg_action_gestion_related on action_gestion_related
CREATE OR REPLACE FUNCTION comptaproc.action_gestion_related_ins_up()
  RETURNS trigger AS
$BODY$
declare
	nTmp bigint;
begin

if NEW.aga_least > NEW.aga_greatest then
	nTmp := NEW.aga_least;
	NEW.aga_least := NEW.aga_greatest;
	NEW.aga_greatest := nTmp;
end if;

if NEW.aga_least = NEW.aga_greatest then
	return NULL;
end if;

return NEW;

end;
$BODY$
  LANGUAGE plpgsql ;
-- DROP TRIGGER trg_action_gestion_related ON action_gestion_related;

CREATE TRIGGER trg_action_gestion_related
  BEFORE INSERT OR UPDATE
  ON action_gestion_related
  FOR EACH ROW
  EXECUTE PROCEDURE comptaproc.action_gestion_related_ins_up();


insert into action_gestion_related(aga_least,aga_greatest) select ag_id,ag_ref_ag_id from action_gestion where ag_ref_ag_id<>0;

update menu_ref set me_menu='Action Gestion' where me_code='FOLLOW';

DROP FUNCTION comptaproc.action_get_tree(bigint);

insert into menu_ref(me_code,me_menu,me_type) values ('CSV:ActionGestion','Export Action Gestion','PR');
insert into profile_menu(me_code,p_id,p_type_display,pm_default) values ('CSV:ActionGestion',1,'P',0);


ALTER TABLE document_type ADD COLUMN dt_prefix text;
COMMENT ON COLUMN document_type.dt_prefix IS 'Prefix for ag_ref';

update document_type set dt_prefix= upper(substr(replace(dt_value,' ',''),0,7))||dt_id::text

CREATE TABLE user_sec_action_profile
(
  ua_id bigserial NOT NULL, -- pk
  p_id bigint, -- fk to profile
  p_granted bigint, -- fk to profile
  ua_right character(1), -- Type of right : R for readonly W for write
  CONSTRAINT user_sec_action_profile_pkey PRIMARY KEY (ua_id ),
  CONSTRAINT user_sec_action_profile_p_id_fkey FOREIGN KEY (p_id)
   REFERENCES profile (p_id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT user_sec_action_profile_p_granted_fkey FOREIGN KEY (p_granted)
      REFERENCES profile (p_id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT user_sec_action_profile_ua_right_check CHECK (ua_right = ANY (ARRAY['R'::bpchar, 'W'::bpchar]))
);
COMMENT ON TABLE user_sec_action_profile  IS 'Available profile for user';
COMMENT ON COLUMN user_sec_action_profile.ua_id IS 'pk';
COMMENT ON COLUMN user_sec_action_profile.p_id IS 'fk to profile';
COMMENT ON COLUMN user_sec_action_profile.ua_right IS 'Type of right : R for readonly W for write';
INSERT INTO profile (p_name, p_id, p_desc, with_calc, with_direct_form) VALUES ('Public', -1, 'faux groupe', NULL, NULL);
insert into user_sec_action_profile(p_id,p_granted,ua_right) select 1,p_id,'W' from profile;
insert into user_sec_action_profile(p_id,p_granted ,ua_right) select 2,p_id,'W' from profile;
insert into parameter values('MY_STOCK','N');

INSERT INTO menu_ref(me_code, me_menu, me_file, me_url, me_description, me_parameter,
            me_javascript, me_type)
    VALUES ('CFGSTOCK', 'Configuration des dépôts', 'stock_cfg.inc.php', null, 'Configuration dépôts', null,null,'ME');

INSERT INTO profile_menu(me_code, me_code_dep, p_id, p_order, p_type_display, pm_default)
    VALUES ('CFGSTOCK', 'PARAM', 1, 40, 'E', 0);

CREATE TABLE stock_repository
(
  r_id bigserial NOT NULL, -- pk
  r_name text, -- name of the stock
  r_adress text, -- adress of the stock
  r_country text, -- country of the stock
  r_city text, -- City of the stock
  r_phone text, -- City of the stock
  CONSTRAINT stock_repository_pkey PRIMARY KEY (r_id )
);

COMMENT ON TABLE stock_repository  IS 'stock repository';
COMMENT ON COLUMN stock_repository.r_id IS 'pk';
COMMENT ON COLUMN stock_repository.r_name IS 'name of the stock';
COMMENT ON COLUMN stock_repository.r_adress IS 'adress of the stock';
COMMENT ON COLUMN stock_repository.r_country IS 'country of the stock';
COMMENT ON COLUMN stock_repository.r_city IS 'City of the stock';
COMMENT ON COLUMN stock_repository.r_phone  IS 'Phone number';

insert into stock_repository(r_name) values ('Dépôt par défaut');

CREATE TABLE user_sec_repository
(
  ur_id bigserial NOT NULL, -- pk
  p_id bigint, -- fk to profile
  r_id bigint,
  ur_right character(1), -- Type of right : R for readonly W for write
  CONSTRAINT user_sec_repository_pkey PRIMARY KEY (ur_id ),
  CONSTRAINT user_sec_repository_p_id_fkey FOREIGN KEY (p_id)
      REFERENCES profile (p_id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT user_sec_repository_r_id_fkey FOREIGN KEY (r_id)
      REFERENCES stock_repository (r_id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT user_sec_profile_ur_right_check CHECK (ur_right = ANY (ARRAY['R'::bpchar, 'W'::bpchar]))
);
COMMENT ON TABLE user_sec_repository  IS 'Available profile for user';
COMMENT ON COLUMN user_sec_repository.ur_id IS 'pk';
COMMENT ON COLUMN user_sec_repository.p_id IS 'fk to profile';
COMMENT ON COLUMN user_sec_repository.r_id IS 'fk to stock_repository';
COMMENT ON COLUMN user_sec_repository.ur_right IS 'Type of right : R for readonly W for write';

alter table user_sec_repository add constraint user_sec_repository_r_id_p_id_u unique (r_id,p_id);
alter table user_sec_action_profile add constraint user_sec_action_profile_p_id_p_granted_u unique (p_id,p_granted);