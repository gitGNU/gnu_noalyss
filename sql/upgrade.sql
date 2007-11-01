begin;

CREATE or replace FUNCTION t_jrn_def_sequence() RETURNS "trigger"
    AS $$
declare
nCounter integer;

    BEGIN
    select count(*) into nCounter 
       from pg_class where relname='s_jrn_'||NEW.jrn_def_id;
       if nCounter = 0 then
       	   execute  'create sequence s_jrn_'||NEW.jrn_def_id;
	   raise notice 'Creating sequence s_jrn_%',NEW.jrn_def_id;
	 end if;

        RETURN NEW;
    END;
$$
    LANGUAGE plpgsql;

create or replace function correct_sequence_jrn () returns void
as $$
declare
	nCounter integer;
	nJrn_id record;
begin
	for nJrn_id in select jrn_Def_id from jrn_def loop
	    select count(*) into nCounter 
       	    	   from pg_class where relname='s_jrn_'||nJrn_id.jrn_def_id;
            if nCounter = 0 then
         	   execute  'create sequence s_jrn_'||nJrn_id.jrn_def_id;
	          raise notice 'Creating sequence s_jrn_%',nJrn_id.jrn_def_id;
	     end if;


	end loop;
end;
$$
	LANGUAGE plpgsql;
select correct_sequence_jrn();
commit;