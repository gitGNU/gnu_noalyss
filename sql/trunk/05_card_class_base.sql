-- Function: comptaproc.card_class_base(integer)

-- DROP FUNCTION comptaproc.card_class_base(integer);
DROP FUNCTION comptaproc.card_class_base(p_f_id integer);

CREATE OR REPLACE FUNCTION comptaproc.card_class_base(p_f_id integer)
  RETURNS fiche_def.fd_class_base%TYPE AS
$BODY$
declare
	n_poste fiche_def.fd_class_base%type;
begin

	select fd_class_base into n_poste from fiche_def join fiche using
(fd_id)
	where f_id=p_f_id;
	if not FOUND then
		raise exception 'Invalid fiche card_class_base(%)',p_f_id;
	end if;
return n_poste;
end;
$BODY$
  LANGUAGE 'plpgsql' ;
