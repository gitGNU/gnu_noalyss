begin;
  /*
 CREATE OR REPLACE FUNCTION create_missing_sequence()
  RETURNS integer AS
$BODY$
declare
p_sequence text;
nSeq integer;
c1 cursor for select jrn_def_id from jrn_def;
begin
	open c1;
	loop
	   fetch c1 into nSeq;
	   if not FOUND THEN
	   	close c1;
	   	return 0;
	   end if;
	   p_sequence:='s_jrn_pj'||nSeq::text;
	execute 'create sequence '||p_sequence||' minvalue 0';
	end loop;
close c1;
return 0;

end;
$BODY$
LANGUAGE 'plpgsql';

select create_missing_sequence();  

 CREATE OR REPLACE FUNCTION correct_missing_sequence()
  RETURNS integer AS
$BODY$
declare
p_sequence text;
nSeq integer;
c1 cursor for select jrn_def_id from jrn_def;
begin
	open c1;
	loop
	   fetch c1 into nSeq;
	   if not FOUND THEN
	   	close c1;
	   	return 0;
	   end if;
	   p_sequence:='s_jrn_pj'||nSeq::text;
	execute 'alter sequence '||p_sequence||' minvalue 0';
	end loop;
close c1;
return 0;

end;
$BODY$
LANGUAGE 'plpgsql';
  
select correct_missing_sequence();  

drop function correct_missing_sequence;
*/
 insert into parameter(pr_id,pr_value) values ('MY_CHECK_PERIODE','Y');
 commit;
