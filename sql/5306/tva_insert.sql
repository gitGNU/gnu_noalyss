-- Function: tva_insert(int4, text, numeric, text, text)

-- DROP FUNCTION tva_insert(int4, text, "numeric", text, text);

CREATE OR REPLACE FUNCTION tva_insert(int4, text, "numeric", text, text)
  RETURNS int4 AS
$BODY$declare
p_tva_id alias for $1;
p_tva_label alias for $2;
p_tva_rate alias for $3;
p_tva_comment alias for $4;
p_tva_poste alias for $5;
debit text;
credit text;
nCount integer;
begin
-- verify that label is not null
if length(trim(p_tva_label)) = 0 then
	return 3;
end if;
select count(*) into nCount from tva_rate 
	where tva_id=p_tva_id;
if nCount != 0 then
	return 5;
end if;
-- check is poste exists
if length(trim(p_tva_poste)) != 0 then
-- check if it is a comma list
	if position (',' in p_tva_poste) = 0 then return 4; end if;
-- separate "credit" and "debit"
	debit  = split_part(p_tva_poste,',',1);
	credit  = split_part(p_tva_poste,',',2);
-- check if those account exist
	select count(*) into nCount from tmp_pcmn where pcm_val=debit;
	if nCount = 0 then return 4; end if;
	select count(*) into nCount from tmp_pcmn where pcm_val=credit;
	if nCount = 0 then return 4; end if;
 
end if;
insert into tva_rate(tva_id,tva_label,tva_rate,tva_comment,tva_poste)
	values (p_tva_id,p_tva_label,p_tva_rate,p_tva_comment,p_tva_poste);
return 0;
end;$BODY$
  LANGUAGE 'plpgsql' VOLATILE;
ALTER FUNCTION tva_insert(int4, text, "numeric", text, text) OWNER TO phpcompta;
