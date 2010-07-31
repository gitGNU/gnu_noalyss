-- Function: comptaproc.insert_jrnx(character varying, numeric, account_type, integer, integer, boolean, text, integer, text, text)

-- DROP FUNCTION comptaproc.insert_jrnx(character varying, numeric, account_type, integer, integer, boolean, text, integer, text, text);

CREATE OR REPLACE FUNCTION comptaproc.insert_jrnx(p_date character varying, p_montant numeric, p_poste account_type, p_grpt integer, p_jrn_def integer, p_debit boolean, p_tech_user text, p_tech_per integer, p_qcode text, p_comment text)
  RETURNS void AS
$BODY$
declare
begin

	insert into jrnx
	(
		j_date,
		j_montant,
		j_poste,
		j_grpt,
		j_jrn_def,
		j_debit,
		j_text,
		j_tech_user,
		j_tech_per,
		j_qcode
	) values
	(
		to_date(p_date,'DD.MM.YYYY'),
		p_montant,
		p_poste,
		p_grpt,
		p_jrn_def,
		p_debit,
		p_comment,
		p_tech_user,
		p_tech_per,
		p_code
	);

return;
end;
$BODY$
  LANGUAGE 'plpgsql';


