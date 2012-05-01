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

