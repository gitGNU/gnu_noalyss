begin;
create or replace function correct_sequence ( p_sequence text,p_col text, p_table text )
returns integer
as
$body$
declare
-- fonction description
-- Often the primary key is a sequence number and sometimes 
-- the value of the sequence is not synchronized with the 
-- primary key
-- parameter p_sequence : sequence name
-- parameter p_col : col of the pk
-- parameter p_table : concerned table
-- variable
-- last value of the sequence
last_sequence int8;
-- max value of the pk
max_sequence int8;
-- n integer
n integer;
begin
-- the sequence exist ?
	select count(*) into n from pg_class where relkind='S' and relname=lower(p_sequence);
	if n = 0 then
		raise exception  ' Unknow sequence  % ',p_sequence;
	end if;
	select count(*) into n from pg_class where relkind='r' and relname=lower(p_table);
	if n = 0 then
		raise exception ' Unknow table  % ',p_table;
	end if;

	execute 'select last_value   from '||p_sequence into last_sequence;
	raise notice 'Last value of the sequence is %', last_sequence;

	execute 'select max('||p_col||')  from '||p_table into max_sequence;
	raise notice 'Max value of the sequence is %', max_sequence;
	max_sequence:= max_sequence +1;	
	execute 'alter sequence '||p_sequence||' restart with '||max_sequence;
return 0;

end;
$body$ language plpgsql;

comment on function correct_sequence (text,text,text) is ' Often the primary key is a sequence number and sometimes 
 the value of the sequence is not synchronized with the primary key ( p_sequence : sequence name, p_col : col of the pk,p_table : concerned table';

select correct_sequence('s_jnt_fic_att_value','jft_id','jnt_fic_att_value');



commit;