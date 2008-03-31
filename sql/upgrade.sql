create sequence s_tva start with 1000;
 alter table tva_rate alter tva_id set default nextval('s_tva');
 alter table form drop constraint "$1";
 alter table form add constraint   formdef_fk foreign key (fo_fr_id) references formdef(fr_id) on update cascade on delete cascade;
drop function tva_insert(integer,text,numeric,text,text);

CREATE or replace FUNCTION tva_insert( text, numeric, text, text) 
RETURNS integer
    AS $_$
declare
l_tva_id integer;
p_tva_label alias for $1;
p_tva_rate alias for $2;
p_tva_comment alias for $3;
p_tva_poste alias for $4;
debit text;
credit text;
nCount integer;
begin
if length(trim(p_tva_label)) = 0 then
	return 3;
end if;

if length(trim(p_tva_poste)) != 0 then
	if position (',' in p_tva_poste) = 0 then return 4; end if;
	debit  = split_part(p_tva_poste,',',1);
	credit  = split_part(p_tva_poste,',',2);
	select count(*) into nCount from tmp_pcmn where pcm_val=debit;
	if nCount = 0 then return 4; end if;
	select count(*) into nCount from tmp_pcmn where pcm_val=credit;
	if nCount = 0 then return 4; end if;
 
end if;
select into l_tva_id nextval('s_tva') ;
insert into tva_rate(tva_id,tva_label,tva_rate,tva_comment,tva_poste)
	values (l_tva_id,p_tva_label,p_tva_rate,p_tva_comment,p_tva_poste);
return 0;
end;
$_$
    LANGUAGE plpgsql;
