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
