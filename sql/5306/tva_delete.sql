-- Function: tva_delete(p_tva_id int4)

-- DROP FUNCTION tva_delete(int4);

CREATE OR REPLACE FUNCTION tva_delete(int4)
  RETURNS void AS
$BODY$declare
	p_tva_id alias for $1;
	nCount integer;
	
begin
	nCount=0;
	select count(*) into nCount from quant_sold where qs_vat_code=p_tva_id;
	if nCount = 0 then
		delete from tva_rate where tva_id=p_tva_id;
	end if;
	return;
end;$BODY$
  LANGUAGE 'plpgsql' VOLATILE;
ALTER FUNCTION tva_delete(int4) OWNER TO phpcompta;
