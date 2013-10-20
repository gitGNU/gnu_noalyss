alter table jrn add column jr_date_paid date;

 set search_path=public,comptaproc;

create or replace function set_paiement_date() returns void
as
$body$
declare 
	row_jrn jrn%ROWTYPE;
	cursor_jrn cursor for select * from jrn where substr(jr_internal,1,1) in ('A','V') and jr_date_paid is null;
	n_rec jrn_rapt.jr_id%TYPE;
	nCount integer;
	jrn_date jrn.jr_date%TYPE;
begin
	for row_jrn in cursor_jrn
	loop
		select count(*) into nCount from jrn_rapt where jr_id=row_jrn.jr_id or jra_concerned=row_jrn.jr_id;
		if nCount = 1 then
			select jr_id into n_rec from jrn_rapt where  jra_concerned=row_jrn.jr_id;
			if NOT FOUND then
				select jra_concerned into n_rec from jrn_rapt where  jr_id=row_jrn.jr_id;
			end if;
			select jr_date into jrn_date from jrn where jr_id=n_rec;
			update jrn set jr_date_paid = jrn_date where current of cursor_jrn;
		end if;

	end loop;

end;
$body$
language plpgsql;

select set_paiement_date();