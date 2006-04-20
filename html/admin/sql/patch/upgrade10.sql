begin;
-- Function: proc_check_balance()

-- DROP FUNCTION proc_check_balance();

CREATE OR REPLACE FUNCTION proc_check_balance()
  RETURNS "trigger" AS
$BODY$
declare 
	diff numeric;
	tt integer;
begin
	if TG_OP = 'INSERT' then
	tt=NEW.jr_grpt_id;
	diff:=check_balance(tt);
	if diff != 0 then
		raise exception 'Rounded error %',diff ;
	end if;
	return NEW;
	end if;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE;


-- Function: check_balance(p_grpt text)

DROP FUNCTION check_balance(text);

CREATE OR REPLACE FUNCTION check_balance(p_grpt integer)
  RETURNS "numeric" AS
$BODY$
declare 
	amount_jrnx_debit numeric;
	amount_jrnx_credit numeric;
	amount_jrn numeric;
begin
	select sum (j_montant) into amount_jrnx_credit 
	from jrnx 
		where 
	j_grpt=p_grpt
	and j_debit=false;

	select sum (j_montant) into amount_jrnx_debit 
	from jrnx 
		where 
	j_grpt=p_grpt
	and j_debit=true;

	select jr_montant into amount_jrn 
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
  LANGUAGE plpgsql VOLATILE;




update version set val=11;
commit;