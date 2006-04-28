-- Function: tva_insert(p_tva_id text, p_tva_label text, p_tva_rate text, p_tva_comment text, p_tva_poste text)

-- DROP FUNCTION tva_insert(text, text, text, text, text);

CREATE OR REPLACE FUNCTION tva_modify(integer, text, numeric, text, text)
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
update tva_rate set tva_label=p_tva_label,tva_rate=p_tva_rate,tva_comment=p_tva_comment,tva_poste=p_tva_poste
	where tva_id=p_tva_id;
return 0;
end;$BODY$
  LANGUAGE 'plpgsql' VOLATILE;
ALTER FUNCTION tva_modify(integer, text, numeric, text, text) OWNER TO phpcompta;
