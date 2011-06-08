--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- Name: comptaproc; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA comptaproc;


SET search_path = comptaproc, pg_catalog;

--
-- Name: account_add(public.account_type, character varying); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION account_add(p_id public.account_type, p_name character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
declare
	nParent tmp_pcmn.pcm_val_parent%type;
	nCount integer;
begin
	select count(*) into nCount from tmp_pcmn where pcm_val=p_id;
	if nCount = 0 then
		nParent=account_parent(p_id);
		insert into tmp_pcmn (pcm_val,pcm_lib,pcm_val_parent)
			values (p_id, p_name,nParent);
	end if;
return;
end ;
$$;


--
-- Name: account_auto(integer); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION account_auto(p_fd_id integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
declare
	l_auto bool;
begin

	select fd_create_account into l_auto from fiche_def where fd_id=p_fd_id;
	if l_auto is null then
		l_auto:=false;
	end if;
	return l_auto;
end;
$$;


--
-- Name: account_compute(integer); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION account_compute(p_f_id integer) RETURNS public.account_type
    LANGUAGE plpgsql
    AS $$
declare
	class_base fiche_def.fd_class_base%type;
	maxcode numeric;
	sResult account_type;
begin
	select fd_class_base into class_base
	from
		fiche_def join fiche using (fd_id)
	where
		f_id=p_f_id;
	raise notice 'account_compute class base %',class_base;
	select count (pcm_val) into maxcode from tmp_pcmn where pcm_val_parent = class_base;
	if maxcode = 0	then
		maxcode:=class_base::numeric;
	else
		select max (pcm_val) into maxcode from tmp_pcmn where pcm_val_parent = class_base;
		maxcode:=maxcode::numeric;
	end if;
	if maxcode::text = class_base then
		maxcode:=class_base::numeric*1000;
	end if;
	maxcode:=maxcode+1;
	raise notice 'account_compute Max code %',maxcode;
	sResult:=maxcode::account_type;
	return sResult;
end;
$$;


--
-- Name: account_insert(integer, text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION account_insert(p_f_id integer, p_account text) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
declare
	nParent tmp_pcmn.pcm_val_parent%type;
	sName varchar;
	nNew tmp_pcmn.pcm_val%type;
	bAuto bool;
	nFd_id integer;
	sClass_Base fiche_def.fd_class_base%TYPE;
	nCount integer;
	first text;
	second text;
begin

	if p_account is not null and length(trim(p_account)) != 0 then
	-- if there is coma in p_account, treat normally
		if position (',' in p_account) = 0 then
			raise info 'p_account is not empty';
				select count(*)  into nCount from tmp_pcmn where pcm_val=p_account::account_type;
				raise notice 'found in tmp_pcm %',nCount;
				if nCount !=0  then
					raise info 'this account exists in tmp_pcmn ';
					perform attribut_insert(p_f_id,5,p_account);
				   else
				       -- account doesn't exist, create it
					select ad_value into sName from
						fiche_detail
					where
					ad_id=1 and f_id=p_f_id;

					nParent:=account_parent(p_account::account_type);
					insert into tmp_pcmn(pcm_val,pcm_lib,pcm_val_parent) values (p_account::account_type,sName,nParent);
					perform attribut_insert(p_f_id,5,p_account);

				end if;
		else
		raise info 'presence of a comma';
		-- there is 2 accounts separated by a comma
		first := split_part(p_account,',',1);
		second := split_part(p_account,',',2);
		-- check there is no other coma
		raise info 'first value % second value %', first, second;

		if  position (',' in first) != 0 or position (',' in second) != 0 then
			raise exception 'Too many comas, invalid account';
		end if;
		perform attribut_insert(p_f_id,5,p_account);
		end if;
	else
	raise info 'p_account is  empty';
		select fd_id into nFd_id from fiche where f_id=p_f_id;
		bAuto:= account_auto(nFd_id);

		select fd_class_base into sClass_base from fiche_def where fd_id=nFd_id;
raise info 'sClass_Base : %',sClass_base;
		if bAuto = true and sClass_base similar to '^[[:digit:]]*$'  then
			raise info 'account generated automatically';
			nNew:=account_compute(p_f_id);
			raise info 'nNew %', nNew;
			select ad_value into sName from
				fiche_detail
			where
				ad_id=1 and f_id=p_f_id;
			nParent:=account_parent(nNew);
			perform account_add  (nNew,sName);
			perform attribut_insert(p_f_id,5,nNew);

		else
		-- if there is an account_base then it is the default
		      select fd_class_base::account_type into nNew from fiche_def join fiche using (fd_id) where f_id=p_f_id;
			if nNew is null or length(trim(nNew)) = 0 then
				raise notice 'count is null';
				 perform attribut_insert(p_f_id,5,null);
			else
				 perform attribut_insert(p_f_id,5,nNew);
			end if;
		end if;
	end if;

return 0;
end;
$_$;


--
-- Name: account_parent(public.account_type); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION account_parent(p_account public.account_type) RETURNS public.account_type
    LANGUAGE plpgsql
    AS $$
declare
	sSubParent tmp_pcmn.pcm_val_parent%type;
	sResult tmp_pcmn.pcm_val_parent%type;
	nCount integer;
begin
	if p_account is NULL then
		return NULL;
	end if;
	sSubParent:=p_account;
	while true loop
		select count(*) into nCount
		from tmp_pcmn
		where
		pcm_val = sSubParent;
		if nCount != 0 then
			sResult:= sSubParent;
			exit;
		end if;
		sSubParent:= substr(sSubParent,1,length(sSubParent)-1);
		if length(sSubParent) <= 0 then
			raise exception 'Impossible de trouver le compte parent pour %',p_account;
		end if;
		raise notice 'sSubParent % % ',sSubParent,length(sSubParent);
	end loop;
	raise notice 'account_parent : Parent is %',sSubParent;
	return sSubParent;
end;
$$;


--
-- Name: account_update(integer, public.account_type); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION account_update(p_f_id integer, p_account public.account_type) RETURNS integer
    LANGUAGE plpgsql
    AS $$
declare
	nMax fiche.f_id%type;
	nCount integer;
	nParent tmp_pcmn.pcm_val_parent%type;
	sName varchar;
	first text;
	second text;
begin

	if length(trim(p_account)) != 0 then
		if position (',' in p_account) = 0 then
			select count(*) into nCount from tmp_pcmn where pcm_val=p_account;
			if nCount = 0 then
			select ad_value into sName from
				fiche_detail
				where
				ad_id=1 and f_id=p_f_id;
			nParent:=account_parent(p_account);
			insert into tmp_pcmn(pcm_val,pcm_lib,pcm_val_parent) values (p_account,sName,nParent);
			end if;
		else
		raise info 'presence of a comma';
		-- there is 2 accounts separated by a comma
		first := split_part(p_account,',',1);
		second := split_part(p_account,',',2);
		-- check there is no other coma
		raise info 'first value % second value %', first, second;

		if  position (',' in first) != 0 or position (',' in second) != 0 then
			raise exception 'Too many comas, invalid account';
		end if;
		end if;
	end if;
	
	update fiche_detail set ad_value=p_account where f_id=p_f_id and ad_id=5 ;

return 0;
end;
$$;


--
-- Name: action_gestion_ins_upd(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION action_gestion_ins_upd() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
begin
NEW.ag_title := substr(trim(NEW.ag_title),1,70);
NEW.ag_hour := substr(trim(NEW.ag_hour),1,5);
return NEW;
end;
$$;


--
-- Name: action_get_tree(bigint); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION action_get_tree(p_id bigint) RETURNS SETOF bigint
    LANGUAGE plpgsql
    AS $$

declare
   e bigint;
   i bigint;
begin
   for e in select ag_id from action_gestion where ag_ref_ag_id=p_id
   loop
        for i in select action_get_tree from  comptaproc.action_get_tree(e)
        loop
                raise notice ' == i %', i;
                return next i;
        end loop;
    raise notice ' = e %', e;
    return next e;
   end loop;
   return;

end;
$$;


--
-- Name: attribut_insert(integer, integer, character varying); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION attribut_insert(p_f_id integer, p_ad_id integer, p_value character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
begin
	insert into fiche_detail (f_id,ad_id, ad_value) values (p_f_id,p_ad_id,p_value);
	
return;
end;
$$;


--
-- Name: attribute_correct_order(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION attribute_correct_order() RETURNS void
    LANGUAGE plpgsql
    AS $$
declare
    crs_correct cursor for select A.jnt_id,A.jnt_order from jnt_fic_attr as A join jnt_fic_attr as B using (fd_id) where A.jnt_order=B.jnt_order and A.jnt_id > B.jnt_id;
    rec record;
begin
	open crs_correct;
	loop
	fetch crs_correct into rec;
	if NOT FOUND then
		close crs_correct;
		return;
	end if;
	update jnt_fic_attr set jnt_order=jnt_order + 1 where jnt_id = rec.jnt_id;
	end loop;
	close crs_correct;
	perform attribute_correct_order ();
end;
$$;


--
-- Name: card_after_delete(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION card_after_delete() RETURNS trigger
    LANGUAGE plpgsql
    AS $$

begin

	delete from action_gestion where f_id_dest = OLD.f_id;
	return OLD;

end;
$$;


--
-- Name: card_class_base(integer); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION card_class_base(p_f_id integer) RETURNS text
    LANGUAGE plpgsql
    AS $$
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
$$;


--
-- Name: check_balance(integer); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION check_balance(p_grpt integer) RETURNS numeric
    LANGUAGE plpgsql
    AS $$
declare 
	amount_jrnx_debit numeric;
	amount_jrnx_credit numeric;
	amount_jrn numeric;
begin
	select sum (j_montant) into amount_jrnx_credit 
	from jrnx 
		where 
	j_grpt=p_grpt
	and j_debit=false;

	select sum (j_montant) into amount_jrnx_debit 
	from jrnx 
		where 
	j_grpt=p_grpt
	and j_debit=true;

	select jr_montant into amount_jrn 
	from jrn
	where
	jr_grpt_id=p_grpt;

	if ( amount_jrnx_debit != amount_jrnx_credit ) 
		then
		return abs(amount_jrnx_debit-amount_jrnx_credit);
		end if;
	if ( amount_jrn != amount_jrnx_credit)
		then
		return -1*abs(amount_jrn - amount_jrnx_credit);
		end if;
	return 0;
end;
$$;


--
-- Name: correct_sequence(text, text, text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION correct_sequence(p_sequence text, p_col text, p_table text) RETURNS integer
    LANGUAGE plpgsql
    AS $$
declare
last_sequence int8;
max_sequence int8;
n integer;
begin
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
	if  max_sequence is null then
		max_sequence := 0;
	end if;
	raise notice 'Max value of the sequence is %', max_sequence;
	max_sequence:= max_sequence +1;	
	execute 'alter sequence '||p_sequence||' restart with '||max_sequence;
return 0;

end;
$$;


--
-- Name: FUNCTION correct_sequence(p_sequence text, p_col text, p_table text); Type: COMMENT; Schema: comptaproc; Owner: -
--

COMMENT ON FUNCTION correct_sequence(p_sequence text, p_col text, p_table text) IS ' Often the primary key is a sequence number and sometimes the value of the sequence is not synchronized with the primary key ( p_sequence : sequence name, p_col : col of the pk,p_table : concerned table';


--
-- Name: create_missing_sequence(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION create_missing_sequence() RETURNS integer
    LANGUAGE plpgsql
    AS $$
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
	execute 'create sequence '||p_sequence;
	end loop;
close c1;
return 0;

end;
$$;


--
-- Name: drop_index(character varying); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION drop_index(p_constraint character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
declare 
	nCount integer;
begin
	select count(*) into nCount from pg_indexes where indexname=p_constraint;
	if nCount = 1 then
	execute 'drop index '||p_constraint ;
	end if;
end;
$$;


--
-- Name: drop_it(character varying); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION drop_it(p_constraint character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
declare 
	nCount integer;
begin
	select count(*) into nCount from pg_constraint where conname=p_constraint;
	if nCount = 1 then
	execute 'alter table parm_periode drop constraint '||p_constraint ;
	end if;
end;
$$;


--
-- Name: extension_ins_upd(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION extension_ins_upd() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare
 sCode text;
 sFile text;
begin
sCode:=trim(upper(NEW.ex_code));
sCode:=replace(sCode,' ','_');
sCode:=substr(sCode,1,15);
sCode=upper(sCode);
NEW.ex_code:=sCode;
sFile:=NEW.ex_file;
sFile:=replace(sFile,';','_');
sFile:=replace(sFile,'<','_');
sFile:=replace(sFile,'>','_');
sFile:=replace(sFile,'..','');
sFile:=replace(sFile,'&','');
sFile:=replace(sFile,'|','');



return NEW;

end;

$$;


--
-- Name: fiche_account_parent(integer); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION fiche_account_parent(p_f_id integer) RETURNS public.account_type
    LANGUAGE plpgsql
    AS $$
declare
ret tmp_pcmn.pcm_val%TYPE;
begin
	select fd_class_base into ret from fiche_def join fiche using (fd_id) where f_id=p_f_id;
	if not FOUND then
		raise exception '% N''existe pas',p_f_id;
	end if;
	return ret;
end;
$$;


--
-- Name: fiche_attribut_synchro(integer); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION fiche_attribut_synchro(p_fd_id integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
declare
	-- this sql gives the f_id and the missing attribute (ad_id)
	list_missing cursor for select f_id,fd_id,ad_id,jnt_order from jnt_fic_attr join fiche as A using (fd_id) where fd_id=p_fd_id and ad_id not in (select ad_id from fiche join fiche_detail using (f_id) where fd_id=jnt_fic_attr.fd_id and A.f_id=f_id);
	rec record;
begin
	open list_missing;
	loop
	
	fetch list_missing into rec;
	IF NOT FOUND then
		exit;
	end if;
	
	-- now we insert into attr_value
	insert into fiche_detail (f_id,ad_id,ad_value) values (rec.f_id,rec.ad_id,null);
	end loop;
	close list_missing;
end; 
$$;


--
-- Name: fiche_def_ins_upd(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION fiche_def_ins_upd() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
begin

if position (',' in NEW.fd_class_base) != 0 then
   NEW.fd_create_account='f';

end if;
return NEW;
end;$$;


--
-- Name: fill_quant_fin(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION fill_quant_fin() RETURNS void
    LANGUAGE plpgsql
    AS $$
declare
   sBank text;
   sCassa text;
   sCustomer text;
   sSupplier text;
   rec record;
   recBank record;
   recSupp_Cust record;
   nCount integer;
   nAmount numeric;
   nBank integer;
   nOther integer;
   nSupp_Cust integer;
begin
	select p_value into sBank from parm_code where p_code='BANQUE';
	select p_value into sCassa from parm_code where p_code='CAISSE';
	select p_value into sSupplier from parm_code where p_code='SUPPLIER';
	select p_value into sCustomer from parm_code where p_code='CUSTOMER';
	
	for rec in select jr_id,jr_grpt_id from jrn 
	    where jr_def_id in (select jrn_def_id from jrn_def where jrn_def_type='FIN')
		and jr_id not in (select jr_id from quant_fin)
	loop
		-- there are only 2 lines for bank operations
		-- first debit
		select count(j_id) into nCount from jrnx where j_grpt=rec.jr_grpt_id;
		if nCount > 2 then 
			raise notice 'Trop de valeur pour jr_grpt_id % count %',rec.jr_grpt_id,nCount;
			return;
		end if;
		nBank := 0; nOther:=0;
		for recBank in select  j_id, j_montant,j_debit,j_qcode,j_poste from jrnx where j_grpt=rec.jr_grpt_id
		loop
		if recBank.j_poste like sBank||'%' then
			-- retrieve f_id for bank
			select f_id into nBank from vw_poste_qcode where j_qcode=recBank.j_qcode;
			if recBank.j_debit = false then
				nAmount=recBank.j_montant*(-1);
			else 
				nAmount=recBank.j_montant;
			end if;
		else
			select f_id into nOther from vw_poste_qcode where j_qcode=recBank.j_qcode;
		end if;
		end loop;
		if nBank != 0 and nOther != 0 then
			insert into quant_fin (jr_id,qf_bank,qf_other,qf_amount) values (rec.jr_id,nBank,nOther,nAmount);
		end if;
	end loop;
	for rec in select jr_id,jr_grpt_id from jrn 
	    where jr_def_id in (select jrn_def_id from jrn_def where jrn_def_type='FIN') and jr_id not in (select jr_id from quant_fin)
	loop
		-- there are only 2 lines for bank operations
		-- first debit
		select count(j_id) into nCount from jrnx where j_grpt=rec.jr_grpt_id;
		if nCount > 2 then 
			raise notice 'Trop de valeur pour jr_grpt_id % count %',rec.jr_grpt_id,nCount;
			return;
		end if;
		nBank := 0; nOther:=0;
		for recBank in select  j_id, j_montant,j_debit,j_qcode,j_poste from jrnx where j_grpt=rec.jr_grpt_id
		loop
		if recBank.j_poste like sCassa||'%' then
			-- retrieve f_id for bank
			select f_id into nBank from vw_poste_qcode where j_qcode=recBank.j_qcode;
			if recBank.j_debit = false then
				nAmount=recBank.j_montant*(-1);
			else 
				nAmount=recBank.j_montant;
			end if;
		else
			select f_id into nOther from vw_poste_qcode where j_qcode=recBank.j_qcode;
		end if;
		end loop;
		if nBank != 0 and nOther != 0 then
			insert into quant_fin (jr_id,qf_bank,qf_other,qf_amount) values (rec.jr_id,nBank,nOther,nAmount);
		end if;
	end loop;

	for rec in select jr_id,jr_grpt_id from jrn 
	    where jr_def_id in (select jrn_def_id from jrn_def where jrn_def_type='FIN') and jr_id not in (select jr_id from quant_fin)
	loop
		-- there are only 2 lines for bank operations
		-- first debit
		select count(j_id) into nCount from jrnx where j_grpt=rec.jr_grpt_id;
		if nCount > 2 then 
			raise notice 'Trop de valeur pour jr_grpt_id % count %',rec.jr_grpt_id,nCount;
			return;
		end if;
		nSupp_Cust := 0; nOther:=0;
		for recSupp_Cust in select  j_id, j_montant,j_debit,j_qcode,j_poste from jrnx where j_grpt=rec.jr_grpt_id
		loop
		if recSupp_Cust.j_poste like sSupplier||'%'  then
			-- retrieve f_id for bank
			select f_id into nSupp_Cust from vw_poste_qcode where j_qcode=recSupp_Cust.j_qcode;
			if recSupp_Cust.j_debit = true then
				nAmount=recSupp_Cust.j_montant*(-1);
			else 
				nAmount=recSupp_Cust.j_montant;
			end if;
		else if  recSupp_Cust.j_poste like sCustomer||'%' then
			select f_id into nSupp_Cust from vw_poste_qcode where j_qcode=recSupp_Cust.j_qcode;
			if recSupp_Cust.j_debit = false then
				nAmount=recSupp_Cust.j_montant*(-1);
			else 
				nAmount=recSupp_Cust.j_montant;
			end if;
			else
			select f_id into nOther from vw_poste_qcode where j_qcode=recSupp_Cust.j_qcode;
			
			end if;
		end if;
		end loop;
		if nSupp_Cust != 0 and nOther != 0 then
			insert into quant_fin (jr_id,qf_bank,qf_other,qf_amount) values (rec.jr_id,nOther,nSupp_Cust,nAmount);
		end if;
	end loop;
	for rec in select jr_id,jr_grpt_id from jrn 
	    where jr_def_id in (select jrn_def_id from jrn_def where jrn_def_type='FIN') and jr_id not in (select jr_id from quant_fin)
	loop
		-- there are only 2 lines for bank operations
		-- first debit
		select count(j_id) into nCount from jrnx where j_grpt=rec.jr_grpt_id;
		if nCount > 2 then 
			raise notice 'Trop de valeur pour jr_grpt_id % count %',rec.jr_grpt_id,nCount;
			return;
		end if;
		nSupp_Cust := 0; nOther:=0;
		for recSupp_Cust in select  j_id, j_montant,j_debit,j_qcode,j_poste from jrnx where j_grpt=rec.jr_grpt_id
		loop
		if recSupp_Cust.j_poste like '441%'  then
			-- retrieve f_id for bank
			select f_id into nSupp_Cust from vw_poste_qcode where j_qcode=recSupp_Cust.j_qcode;
			if recSupp_Cust.j_debit = false then
				nAmount=recSupp_Cust.j_montant*(-1);
			else 
				nAmount=recSupp_Cust.j_montant;
			end if;
			else
			select f_id into nOther from vw_poste_qcode where j_qcode=recSupp_Cust.j_qcode;
			
			
		end if;
		end loop;
		if nSupp_Cust != 0 and nOther != 0 then
			insert into quant_fin (jr_id,qf_bank,qf_other,qf_amount) values (rec.jr_id,nOther,nSupp_Cust,nAmount);
		end if;
	end loop;
	return;
end;
$$;


--
-- Name: find_pcm_type(public.account_type); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION find_pcm_type(pp_value public.account_type) RETURNS text
    LANGUAGE plpgsql
    AS $$
declare
	str_type parm_poste.p_type%TYPE;
	str_value parm_poste.p_type%TYPE;
	nLength integer;
begin
	str_value:=pp_value;
	nLength:=length(str_value::text);
	while nLength > 0 loop
		select p_type into str_type from parm_poste where p_value=str_value;
		if FOUND then
			return str_type;
		end if;
		nLength:=nLength-1;
		str_value:=substring(str_value::text from 1 for nLength)::account_type;
	end loop;
return 'CON';
end;
$$;


--
-- Name: find_periode(text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION find_periode(p_date text) RETURNS integer
    LANGUAGE plpgsql
    AS $$

declare n_p_id int4;
begin

select p_id into n_p_id
	from parm_periode
	where
		p_start <= to_date(p_date,'DD.MM.YYYY')
		and
		p_end >= to_date(p_date,'DD.MM.YYYY');

if NOT FOUND then
	return -1;
end if;

return n_p_id;

end;$$;


--
-- Name: get_letter_jnt(bigint); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION get_letter_jnt(a bigint) RETURNS bigint
    LANGUAGE plpgsql
    AS $$
declare
 nResult bigint;
begin
   select jl_id into nResult from jnt_letter join letter_deb using (jl_id) where j_id = a;
   if NOT FOUND then
	select jl_id into nResult from jnt_letter join letter_cred using (jl_id) where j_id = a;
	if NOT found then
		return null;
	end if;
    end if;
return nResult;
end;
$$;


--
-- Name: get_pcm_tree(public.account_type); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION get_pcm_tree(source public.account_type) RETURNS SETOF public.account_type
    LANGUAGE plpgsql
    AS $$
declare
	i account_type;
	e account_type;
begin
	for i in select pcm_val from tmp_pcmn where pcm_val_parent=source
	loop
		return next i;
		for e in select get_pcm_tree from get_pcm_tree(i)
		loop
			return next e;
		end loop;

	end loop;
	return;
end;
$$;


--
-- Name: group_analytic_ins_upd(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION group_analytic_ins_upd() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare 
name text;
begin
name:=upper(NEW.ga_id);
name:=trim(name);
name:=replace(name,' ','');
NEW.ga_id:=name;
return NEW;
end;$$;


--
-- Name: group_analytique_del(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION group_analytique_del() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
begin
update poste_analytique set ga_id=null
where ga_id=OLD.ga_id;
return OLD;
end;$$;


--
-- Name: html_quote(text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION html_quote(p_string text) RETURNS text
    LANGUAGE plpgsql
    AS $$
declare
	r text;
begin
	r:=p_string;
	r:=replace(r,'<','&lt;');
	r:=replace(r,'>','&gt;');
	r:=replace(r,'''','&quot;');
	return r;
end;$$;


--
-- Name: FUNCTION html_quote(p_string text); Type: COMMENT; Schema: comptaproc; Owner: -
--

COMMENT ON FUNCTION html_quote(p_string text) IS 'remove harmfull HTML char';


--
-- Name: info_def_ins_upd(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION info_def_ins_upd() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare 
	row_info_def info_def%ROWTYPE;
	str_type text;
begin
row_info_def:=NEW;
str_type:=upper(trim(NEW.id_type));
str_type:=replace(str_type,' ','');
str_type:=replace(str_type,',','');
str_type:=replace(str_type,';','');
if  length(str_type) =0 then
	raise exception 'id_type cannot be null';
end if;
row_info_def.id_type:=str_type;
return row_info_def;
end;
$$;


--
-- Name: insert_jrnx(character varying, numeric, public.account_type, integer, integer, boolean, text, integer, text, text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION insert_jrnx(p_date character varying, p_montant numeric, p_poste public.account_type, p_grpt integer, p_jrn_def integer, p_debit boolean, p_tech_user text, p_tech_per integer, p_qcode text, p_comment text) RETURNS void
    LANGUAGE plpgsql
    AS $$
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
		p_qcode
	);

return;
end;
$$;


--
-- Name: insert_quant_purchase(text, numeric, character varying, numeric, numeric, numeric, integer, numeric, numeric, numeric, numeric, character varying); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION insert_quant_purchase(p_internal text, p_j_id numeric, p_fiche character varying, p_quant numeric, p_price numeric, p_vat numeric, p_vat_code integer, p_nd_amount numeric, p_nd_tva numeric, p_nd_tva_recup numeric, p_dep_priv numeric, p_client character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
declare
	fid_client integer;
	fid_good   integer;
begin
	select f_id into fid_client from
		fiche_detail where ad_id=23 and ad_value=upper(trim(p_client));
	select f_id into fid_good from
		 fiche_detail where ad_id=23 and ad_value=upper(trim(p_fiche));
	insert into quant_purchase
		(qp_internal,
		j_id,
		qp_fiche,
		qp_quantite,
		qp_price,
		qp_vat,
		qp_vat_code,
		qp_nd_amount,
		qp_nd_tva,
		qp_nd_tva_recup,
		qp_supplier,
		qp_dep_priv)
	values
		(p_internal,
		p_j_id,
		fid_good,
		p_quant,
		p_price,
		p_vat,
		p_vat_code,
		p_nd_amount,
		p_nd_tva,
		p_nd_tva_recup,
		fid_client,
		p_dep_priv);
	return;
end;
 $$;


--
-- Name: insert_quant_sold(text, numeric, character varying, numeric, numeric, numeric, integer, character varying); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION insert_quant_sold(p_internal text, p_jid numeric, p_fiche character varying, p_quant numeric, p_price numeric, p_vat numeric, p_vat_code integer, p_client character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
declare
	fid_client integer;
	fid_good   integer;
begin

	select f_id into fid_client from
		fiche_detail where ad_id=23 and ad_value=upper(trim(p_client));
	select f_id into fid_good from
		fiche_detail where ad_id=23 and ad_value=upper(trim(p_fiche));
	insert into quant_sold
		(qs_internal,j_id,qs_fiche,qs_quantite,qs_price,qs_vat,qs_vat_code,qs_client,qs_valid)
	values
		(p_internal,p_jid,fid_good,p_quant,p_price,p_vat,p_vat_code,fid_client,'Y');
	return;
end;
 $$;


--
-- Name: insert_quick_code(integer, text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION insert_quick_code(nf_id integer, tav_text text) RETURNS integer
    LANGUAGE plpgsql
    AS $$
	declare
	ns integer;
	nExist integer;
	tText text;
	begin
	tText := upper(trim(tav_text));
	tText := replace(tText,' ','');
	
	loop
		-- take the next sequence
		select nextval('s_jnt_fic_att_value') into ns;
		if length (tText) = 0 or tText is null then
			tText := 'FID'||ns;
		end if;
		-- av_text already used ?
		select count(*) into nExist 
			from fiche_detail
		where 
			ad_id=23 and  ad_value=upper(tText);

		if nExist = 0 then
			exit;
		end if;
		tText:='FID'||ns;
	end loop;


	insert into fiche_detail(jft_id,f_id,ad_id,ad_value) values (ns,nf_id,23,upper(tText));
	return ns;
	end;
$$;


--
-- Name: is_closed(integer, integer); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION is_closed(p_periode integer, p_jrn_def_id integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
declare
bClosed bool;
str_status text;
begin
select p_closed into bClosed from parm_periode
	where p_id=p_periode;

if bClosed = true then
	return bClosed;
end if;

select status into str_status from jrn_periode
       where p_id =p_periode and jrn_def_id=p_jrn_def_id;

if str_status <> 'OP' then
   return bClosed;
end if;
return false;
end;
$$;


--
-- Name: jnt_fic_attr_ins(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION jnt_fic_attr_ins() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare
   r_record jnt_fic_attr%ROWTYPE;
   i_max integer;
begin
r_record=NEW;
perform comptaproc.fiche_attribut_synchro(r_record.fd_id);
select coalesce(max(jnt_order),0) into i_max from jnt_fic_attr where fd_id=r_record.fd_id;
i_max := i_max + 10;
NEW.jnt_order=i_max;
return NEW;
end;
$$;


--
-- Name: jrn_add_note(bigint, text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION jrn_add_note(p_jrid bigint, p_note text) RETURNS void
    LANGUAGE plpgsql
    AS $$
declare
	tmp bigint;
begin
	if length(trim(p_note)) = 0 then
	   delete from jrn_note where jr_id= p_jrid;
	   return;
	end if;
	
	select n_id into tmp from jrn_note where jr_id = p_jrid;
	
	if FOUND then
	   update jrn_note set n_text=trim(p_note) where jr_id = p_jrid;
	else 
	   insert into jrn_note (jr_id,n_text) values ( p_jrid, p_note);

	end if;
	
	return;
end;
$$;


--
-- Name: jrn_check_periode(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION jrn_check_periode() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare
bClosed bool;
str_status text;
ljr_tech_per jrn.jr_tech_per%TYPE;
ljr_def_id jrn.jr_def_id%TYPE;
lreturn jrn%ROWTYPE;
begin
if TG_OP='UPDATE' then
	ljr_tech_per :=OLD.jr_tech_per ;
	NEW.jr_tech_per := comptaproc.find_periode(to_char(NEW.jr_date,'DD.MM.YYYY'));
	ljr_def_id   :=OLD.jr_def_id;
	lreturn      :=NEW;
	if NEW.jr_date = OLD.jr_date then
		return NEW;
	end if;
	if comptaproc.is_closed(NEW.jr_tech_per,NEW.jr_def_id) = true then
	      	raise exception 'Periode fermee';
	end if;
end if;

if TG_OP='INSERT' then
	NEW.jr_tech_per := comptaproc.find_periode(to_char(NEW.jr_date,'DD.MM.YYYY'));
	ljr_tech_per :=NEW.jr_tech_per ;
	ljr_def_id   :=NEW.jr_def_id;
	lreturn      :=NEW;
end if;

if TG_OP='DELETE' then
	ljr_tech_per :=OLD.jr_tech_per;
	ljr_def_id   :=OLD.jr_def_id;
	lreturn      :=OLD;
end if;

if comptaproc.is_closed (ljr_def_id,ljr_def_id) = true then
   	raise exception 'Periode fermee';
end if;

return lreturn;
end;$$;


--
-- Name: jrn_def_add(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION jrn_def_add() RETURNS trigger
    LANGUAGE plpgsql
    AS $$begin
execute 'insert into jrn_periode(p_id,jrn_def_id,status) select p_id,'||NEW.jrn_def_id||',
	case when p_central=true then ''CE''
	      when p_closed=true then ''CL''
	else ''OP''
	end
from
parm_periode ';
return NEW;
end;$$;


--
-- Name: jrn_def_delete(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION jrn_def_delete() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare 
nb numeric;
begin
select count(*) into nb from jrn where jr_def_id=OLD.jrn_def_id;

if nb <> 0 then
	raise exception 'EFFACEMENT INTERDIT: JOURNAL UTILISE';
end if;
return OLD;
end;$$;


--
-- Name: jrn_del(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION jrn_del() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare
row jrn%ROWTYPE;
begin
row:=OLD;
insert into del_jrn ( jr_id,
       jr_def_id,
       jr_montant,
       jr_comment,
       jr_date,
       jr_grpt_id,
       jr_internal,
       jr_tech_date,
       jr_tech_per,
       jrn_ech,
       jr_ech,
       jr_rapt,
       jr_valid,
       jr_opid,
       jr_c_opid,
       jr_pj,
       jr_pj_name,
       jr_pj_type,
       jr_pj_number,
       del_jrn_date) 
       select  jr_id,
	      jr_def_id,
	      jr_montant,
	      jr_comment,
	      jr_date,
	      jr_grpt_id,
	      jr_internal,
	      jr_tech_date,
	      jr_tech_per,
	      jrn_ech,
	      jr_ech,
	      jr_rapt,
	      jr_valid,
	      jr_opid,
	      jr_c_opid,
	      jr_pj,
	      jr_pj_name,
	      jr_pj_type,
	      jr_pj_number
	      ,now() from jrn where jr_id=row.jr_id;
return row;
end;
$$;


--
-- Name: jrnx_del(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION jrnx_del() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare
row jrnx%ROWTYPE;
begin
row:=OLD;


insert into del_jrnx(
            j_id, j_date, j_montant, j_poste, j_grpt, j_rapt, j_jrn_def, 
            j_debit, j_text, j_centralized, j_internal, j_tech_user, j_tech_date, 
            j_tech_per, j_qcode, f_id)  SELECT j_id, j_date, j_montant, j_poste, j_grpt, j_rapt, j_jrn_def, 
       j_debit, j_text, j_centralized, j_internal, j_tech_user, j_tech_date, 
       j_tech_per, j_qcode, f_id from jrnx where j_id=row.j_id;
return row;
end;
$$;


--
-- Name: jrnx_ins(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION jrnx_ins() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare
n_fid bigint;
begin

NEW.j_tech_per := comptaproc.find_periode(to_char(NEW.j_date,'DD.MM.YYYY'));
if NEW.j_tech_per = -1 then
	raise exception 'Période invalide';
end if;

if NEW.j_qcode is NULL then
   return NEW;
end if;

NEW.j_qcode=trim(upper(NEW.j_qcode));

if length (NEW.j_qcode) = 0 then
    NEW.j_qcode=NULL;
    else
   select f_id into n_fid from fiche_detail  where ad_id=23 and ad_value=NEW.j_qcode;
	if NOT FOUND then
		raise exception 'La fiche dont le quick code est % n''existe pas',NEW.j_qcode;
	end if;
end if;
NEW.f_id:=n_fid;
return NEW;
end;
$$;


--
-- Name: jrnx_letter_del(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION jrnx_letter_del() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare
row jrnx%ROWTYPE;
begin
row:=OLD;
delete from jnt_letter 
	where (jl_id in (select jl_id from letter_deb) and jl_id not in(select jl_id from letter_cred )) 
		or (jl_id not in (select jl_id from letter_deb  ) and jl_id  in(select jl_id from letter_cred ));
return row;
end;
$$;


--
-- Name: plan_analytic_ins_upd(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION plan_analytic_ins_upd() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare
   name text;
begin
   name:=upper(NEW.pa_name);
   name:=trim(name);
   name:=replace(name,' ','');
   NEW.pa_name:=name;
return NEW;
end;
$$;


--
-- Name: poste_analytique_ins_upd(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION poste_analytique_ins_upd() RETURNS trigger
    LANGUAGE plpgsql
    AS $$declare
name text;
rCount record;

begin
name:=upper(NEW.po_name);
name:=trim(name);
name:=replace(name,' ','');		
NEW.po_name:=name;

if NEW.ga_id is NULL then
return NEW;
end if;

if length(trim(NEW.ga_id)) = 0 then
  NEW.ga_id:=NULL;
  return NEW;
end if;
perform 'select ga_id from groupe_analytique where ga_id='||NEW.ga_id;
if NOT FOUND then
   raise exception' Inexistent Group Analytic %',NEW.ga_id;
end if;
return NEW;
end;$$;


--
-- Name: proc_check_balance(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION proc_check_balance() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare 
	diff numeric;
	tt integer;
begin
	if TG_OP = 'INSERT' or TG_OP='UPDATE' then
	tt=NEW.jr_grpt_id;
	diff:=check_balance(tt);
	if diff != 0 then
		raise exception 'balance error %',diff ;
	end if;
	return NEW;
	end if;
end;
$$;


--
-- Name: quant_purchase_ins_upd(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION quant_purchase_ins_upd() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
	begin
		if NEW.qp_price < 0 OR NEW.qp_quantite <0 THEN
			NEW.qp_price := abs (NEW.qp_price)*(-1);
			NEW.qp_quantite := abs (NEW.qp_quantite)*(-1);
		end if;
return NEW;
end;
$$;


--
-- Name: quant_sold_ins_upd(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION quant_sold_ins_upd() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
	begin
		if NEW.qs_price < 0 OR NEW.qs_quantite <0 THEN
			NEW.qs_price := abs (NEW.qs_price)*(-1);
			NEW.qs_quantite := abs (NEW.qs_quantite)*(-1);
		end if;
return NEW;
end;
$$;


--
-- Name: t_document_modele_validate(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION t_document_modele_validate() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare 
    lText text;
    modified document_modele%ROWTYPE;
begin
    modified:=NEW;

	modified.md_filename:=replace(NEW.md_filename,' ','_');
	return modified;
end;
$$;


--
-- Name: t_document_type_insert(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION t_document_type_insert() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare
nCounter integer;
    BEGIN
select count(*) into nCounter from pg_class where relname='seq_doc_type_'||NEW.dt_id;
if nCounter = 0 then
        execute  'create sequence seq_doc_type_'||NEW.dt_id;
end if;
        RETURN NEW;
    END;
$$;


--
-- Name: t_document_validate(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION t_document_validate() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare
  lText text;
  modified document%ROWTYPE;
begin
    	modified:=NEW;
	modified.d_filename:=replace(NEW.d_filename,' ','_');
	return modified;
end;
$$;


--
-- Name: t_jrn_def_sequence(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION t_jrn_def_sequence() RETURNS trigger
    LANGUAGE plpgsql
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
$$;


--
-- Name: table_analytic_account(text, text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION table_analytic_account(p_from text, p_to text) RETURNS SETOF public.anc_table_account_type
    LANGUAGE plpgsql
    AS $$
declare
	ret ANC_table_account_type%ROWTYPE;
	sql_from text:='';
	sql_to text:='';
	sWhere text:='';
	sAnd text:='';
	sResult text:='';
begin
if p_from <> '' and p_from is not null then
	sql_from:='oa_date >= to_date('''||p_from::text||''',''DD.MM.YYYY'')';
	sWhere:=' where ';
end if;

if p_to <> '' and p_to is not null then
	sql_to=' oa_date <= to_date('''||p_to::text||''',''DD.MM.YYYY'')';
	sWhere := ' where ';
end if;

if sql_to <> '' and sql_from <> '' then
	sAnd:=' and ';
end if;

sResult := sWhere || sql_from || sAnd || sql_to;

for ret in EXECUTE 'SELECT po.po_id,
			    po.pa_id, po.po_name, 
			    po.po_description,sum(
        CASE
            WHEN operation_analytique.oa_debit = true THEN operation_analytique.oa_amount * (-1)::numeric
            ELSE operation_analytique.oa_amount
        END) AS sum_amount, jrnx.j_poste, tmp_pcmn.pcm_lib AS name
   FROM operation_analytique
   JOIN poste_analytique po USING (po_id)
   JOIN jrnx USING (j_id)
   JOIN tmp_pcmn ON jrnx.j_poste::text = tmp_pcmn.pcm_val::text
'|| sResult ||'
  GROUP BY po.po_id, po.po_name, po.pa_id, jrnx.j_poste, tmp_pcmn.pcm_lib, po.po_description
 HAVING sum(
CASE
    WHEN operation_analytique.oa_debit = true THEN operation_analytique.oa_amount * (-1)::numeric
    ELSE operation_analytique.oa_amount
END) <> 0::numeric '
	loop
	return next ret;
end loop;
end;
$$;


--
-- Name: table_analytic_card(text, text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION table_analytic_card(p_from text, p_to text) RETURNS SETOF public.anc_table_card_type
    LANGUAGE plpgsql
    AS $$
declare
	ret ANC_table_card_type%ROWTYPE;
	sql_from text:='';
	sql_to text:='';
	sWhere text:='';
	sAnd text:='';
	sResult text:='';
begin
if p_from <> '' and p_from is not null then
	sql_from:='oa_date >= to_date('''||p_from::text||''',''DD.MM.YYYY'')';
	sWhere:=' where ';
end if;

if p_to <> '' and p_to is not null then
	sql_to=' oa_date <= to_date('''||p_to::text||''',''DD.MM.YYYY'')';
	sWhere := ' where ';
end if;

if sql_to <> '' and sql_from <> '' then
	sAnd :=' and ';
end if;

sResult := sWhere || sql_from || sAnd || sql_to;

for ret in EXECUTE ' SELECT po.po_id, po.pa_id, po.po_name, po.po_description,  sum(
        CASE
            WHEN operation_analytique.oa_debit = true THEN operation_analytique.oa_amount * (-1)::numeric
            ELSE operation_analytique.oa_amount
        END) AS sum_amount, jrnx.f_id, jrnx.j_qcode, ( SELECT fiche_detail.ad_value
           FROM fiche_detail
          WHERE fiche_detail.ad_id = 1 AND fiche_detail.f_id = jrnx.f_id) AS name
   FROM operation_analytique
   JOIN poste_analytique po USING (po_id)
   JOIN jrnx USING (j_id)'|| sResult ||'
  GROUP BY po.po_id, po.po_name, po.pa_id, jrnx.f_id, jrnx.j_qcode, ( SELECT fiche_detail.ad_value
   FROM fiche_detail
  WHERE fiche_detail.ad_id = 1 AND fiche_detail.f_id = jrnx.f_id), po.po_description
 HAVING sum(
CASE
    WHEN operation_analytique.oa_debit = true THEN operation_analytique.oa_amount * (-1)::numeric
    ELSE operation_analytique.oa_amount
END) <> 0::numeric;'


	loop
	return next ret;
end loop;
end;
$$;


--
-- Name: tmp_pcmn_ins(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION tmp_pcmn_ins() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare
   r_record tmp_pcmn%ROWTYPE;
begin
r_record=NEW;
if  length(trim(r_record.pcm_type))=0 or r_record.pcm_type is NULL then 
   r_record.pcm_type:=find_pcm_type(NEW.pcm_val);
   return r_record;
end if;
return NEW;
end;
$$;


--
-- Name: trim_cvs_quote(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION trim_cvs_quote() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare
        modified import_tmp%ROWTYPE;
begin
	modified:=NEW;
	modified.devise=replace(new.devise,'"','');
	modified.poste_comptable=replace(new.poste_comptable,'"','');
        modified.compte_ordre=replace(NEW.COMPTE_ORDRE,'"','');
        modified.detail=replace(NEW.DETAIL,'"','');
        modified.num_compte=replace(NEW.NUM_COMPTE,'"','');
        return modified;
end;
$$;


--
-- Name: trim_space_format_csv_banque(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION trim_space_format_csv_banque() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare
        modified format_csv_banque%ROWTYPE;
begin
        modified.name=trim(NEW.NAME);
        modified.include_file=trim(new.include_file);
		if ( length(modified.name) = 0 ) then
			modified.name=null;
		end if;
		if ( length(modified.include_file) = 0 ) then
			modified.include_file=null;
		end if;

        return modified;
end;
$$;


--
-- Name: tva_delete(integer); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION tva_delete(integer) RETURNS void
    LANGUAGE plpgsql
    AS $_$ 
declare
	p_tva_id alias for $1;
	nCount integer;
begin
	nCount=0;
	select count(*) into nCount from quant_sold where qs_vat_code=p_tva_id;
	if nCount != 0 then
                 return;
		
	end if;
	select count(*) into nCount from quant_purchase where qp_vat_code=p_tva_id;
	if nCount != 0 then
                 return;
		
	end if;

delete from tva_rate where tva_id=p_tva_id;
	return;
end;
$_$;


--
-- Name: tva_insert(text, numeric, text, text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION tva_insert(text, numeric, text, text) RETURNS integer
    LANGUAGE plpgsql
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
	credit	= split_part(p_tva_poste,',',2);
	select count(*) into nCount from tmp_pcmn where pcm_val=debit::account_type;
	if nCount = 0 then return 4; end if;
	select count(*) into nCount from tmp_pcmn where pcm_val=credit::account_type;
	if nCount = 0 then return 4; end if;

end if;
select into l_tva_id nextval('s_tva') ;
insert into tva_rate(tva_id,tva_label,tva_rate,tva_comment,tva_poste)
	values (l_tva_id,p_tva_label,p_tva_rate,p_tva_comment,p_tva_poste);
return 0;
end;
$_$;


--
-- Name: tva_modify(integer, text, numeric, text, text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION tva_modify(integer, text, numeric, text, text) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
declare
	p_tva_id alias for $1;
	p_tva_label alias for $2;
	p_tva_rate alias for $3;
	p_tva_comment alias for $4;
	p_tva_poste alias for $5;
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
	credit	= split_part(p_tva_poste,',',2);
	select count(*) into nCount from tmp_pcmn where pcm_val=debit::account_type;
	if nCount = 0 then return 4; end if;
	select count(*) into nCount from tmp_pcmn where pcm_val=credit::account_type;
	if nCount = 0 then return 4; end if;

end if;
update tva_rate set tva_label=p_tva_label,tva_rate=p_tva_rate,tva_comment=p_tva_comment,tva_poste=p_tva_poste
	where tva_id=p_tva_id;
return 0;
end;
$_$;


--
-- Name: update_quick_code(integer, text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION update_quick_code(njft_id integer, tav_text text) RETURNS integer
    LANGUAGE plpgsql
    AS $$
	declare
	ns integer;
	nExist integer;
	tText text;
	old_qcode varchar;
	begin
	-- get current value
	select ad_value into old_qcode from fiche_detail where jft_id=njft_id;
	-- av_text didn't change so no update
	if tav_text = upper( trim(old_qcode)) then
		return 0;
	end if;
	
	tText := trim(upper(tav_text));
	tText := replace(tText,' ','');
	if length ( tText) = 0 or tText is null then
		return 0;
	end if;
		
	ns := njft_id;

	loop
		-- av_text already used ?
		select count(*) into nExist 
			from fiche_detail
		where 
			ad_id=23 and ad_value=tText;

		if nExist = 0 then
			exit;
		end if;	
		if tText = 'FID'||ns then
			-- take the next sequence
			select nextval('s_jnt_fic_att_value') into ns;
		end if;
		tText  :='FID'||ns;
		
	end loop;
	update fiche_detail set ad_value = tText where jft_id=njft_id;

	-- update also the contact
	update fiche_detail set ad_value = tText 
		where jft_id in 
			( select jft_id 
				from fiche_detail 
			where ad_id=25 and ad_value=old_qcode);


	update jrnx set j_qcode=tText where j_qcode = old_qcode;
	return ns;
	end;
$$;


--
-- PostgreSQL database dump complete
--

