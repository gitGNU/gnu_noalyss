--
-- PostgreSQL database dump
--
begin;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;


SET search_path = comptaproc, pg_catalog,public;

drop function account_add (p_id poste_comptable,t character varying) ;
drop function account_parent (a poste_comptable) ;
drop function insert_jrnx (p_date character varying, p_montant numeric, p_poste poste_comptable, p_grpt integer, p_jrn_def integer, p_debit boolean, p_tech_user text, p_tech_per integer, p_qcode text, p_comment text);
drop function  account_update(integer,text);
drop function  account_compute(p_f_id integer) ;
drop function find_pcmn_type(numeric);
drop function account_compute(integer);
--
-- Name: account_add(public.account_type, character varying); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE OR REPLACE FUNCTION comptaproc.account_add(p_id tmp_pcmn.pcm_val_parent%type , p_name character varying) RETURNS void
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
$$
    LANGUAGE plpgsql;



--
-- Name: account_compute(integer); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE OR REPLACE FUNCTION account_compute(p_f_id integer) RETURNS tmp_pcmn.pcm_val_parent%type
    AS $$
declare
        class_base tmp_pcmn.pcm_val_parent%type;
        maxcode tmp_pcmn.pcm_val_parent%type;
begin
        select fd_class_base into class_base
        from
                fiche_def join fiche using (fd_id)
        where
                f_id=p_f_id;
        raise notice 'account_compute class base %',class_base;
        select count (pcm_val) into maxcode from tmp_pcmn where pcm_val_parent = class_base;
        if maxcode = 0  then
                maxcode:=class_base;
        else
                select max (pcm_val) into maxcode from tmp_pcmn where pcm_val_parent = class_base;
        end if;
        if maxcode = class_base then
                maxcode:=class_base::numeric*1000;
        end if;
        maxcode:=maxcode+1;
        raise notice 'account_compute Max code %',maxcode;
        return maxcode;
end;
$$
    LANGUAGE plpgsql;


--
-- Name: account_insert(integer, text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE OR REPLACE FUNCTION account_insert(p_f_id integer, p_account text) RETURNS integer
    AS $$
declare
	nParent tmp_pcmn.pcm_val_parent%type;
	sName varchar;
	nNew tmp_pcmn.pcm_val%type;
	bAuto bool;
	nFd_id integer;
	sClass_Base tmp_pcmn.pcm_val_parent%type;
	nCount integer;
	first text;
	second text;
begin

	if length(trim(p_account)) != 0 then
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
					select av_text into sName from
						attr_value join jnt_fic_att_value using (jft_id)
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

		select fd_class_base into sClass_base from fiche_def where fd_id=nFid_id;

		if bAuto = true and sClass_base ~'^\\d*$'  then
			raise notice 'account generated automatically';
			nNew:=account_compute(p_f_id);
			raise notice 'nNew %', nNew;
			select av_text into sName from
			attr_value join jnt_fic_att_value using (jft_id)
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
$$
    LANGUAGE plpgsql;


--
-- Name: account_parent(public.account_type); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE OR REPLACE FUNCTION comptaproc.account_parent(p_account public.account_type) RETURNS public.account_type
    AS $$
declare
	sSubParent tmp_pcmn.pcm_val_parent%type;
	sResult tmp_pcmn.pcm_val_parent%type;
	nCount integer;
begin
	nParent:=0;
	while nParent = 0 loop
		select count(*) into nCount
		from tmp_pcmn
		where
		pcm_val = sSubParent
		if nCount != 0 then
			sResult:= sSubParent;
			exit;
		end if;
		sSubParent:= substr(sSubParent,1,length(sSubParent)-1);
		if length(sSubParent) <= 0 then
			raise exception 'Impossible de trouver le compte parent pour %',p_account;
		end if;
	end loop;
	raise notice 'account_parent : Parent is %',nParent;
	return sSubParent;
end;
$$
    LANGUAGE plpgsql;


--
-- Name: account_update(integer, text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE OR REPLACE FUNCTION account_update(p_f_id integer, p_account tmp_pcmn.pcm_val_parent%type ) RETURNS integer
    AS $$
declare
	nMax fiche.f_id%type;
	nCount integer;
	nParent tmp_pcmn.pcm_val_parent%type;
	sName varchar;
	nJft_id attr_value.jft_id%type;
	first text;
	second text;
begin
	
	if length(trim(p_account)) != 0 then
		if position (',' in p_account) = 0 then
			select count(*) into nCount from tmp_pcmn where pcm_val=p_account;
			if nCount = 0 then
			select av_text into sName from 
				attr_value join jnt_fic_att_value using (jft_id)
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
	select jft_id into njft_id from jnt_fic_att_value where f_id=p_f_id and ad_id=5;
	update attr_value set av_text=p_account where jft_id=njft_id;
		
return njft_id;
end;
$$
    LANGUAGE plpgsql;



--
-- Name: find_pcm_type(numeric); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE OR REPLACE FUNCTION find_pcm_type(pp_value tmp_pcmn.pcm_val_parent%type) RETURNS text
    AS $$
declare
        str_type text;
        str_value text;
        n_value numeric;
        nLength integer;
begin 
        str_value:=pp_value;
        nLength:=length(str_value);
	while nLength > 0 loop
		n_value:=str_value::numeric;
      		select p_type into str_type from parm_poste where p_value=n_value;
		if FOUND then
			return str_type;
		end if;
		nLength:=nLength-1;
		str_value:=substring(str_value from 1 for nLength);	
	end loop;
return 'CON';
end;
$$
    LANGUAGE plpgsql;

--
-- Name: insert_jrnx(character varying, numeric, public.account_type, integer, integer, boolean, text, integer, text, text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE OR REPLACE FUNCTION insert_jrnx(p_date character varying, p_montant numeric, p_poste tmp_pcmn.pcm_val_parent%type, p_grpt integer, p_jrn_def integer, p_debit boolean, p_tech_user text, p_tech_per integer, p_qcode text, p_comment text) RETURNS void
    AS $$
declare
	sCode varchar;
	nCount_qcode integer;
begin
	sCode=trim(p_qcode);

	-- if p_qcode is empty try to find one
	if length(sCode) = 0 or p_qcode is null then
		select count(*) into nCount_qcode
			from vw_poste_qcode where j_poste=p_poste;
	-- if we find only one q_code for a accountancy account
	-- then retrieve it
		if nCount_qcode = 1 then
			select j_qcode::text into sCode
			from vw_poste_qcode where j_poste=p_poste;
		else
		 sCode=NULL;
		end if;

	end if;

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
		sCode
	);

return;
end;
$$
    LANGUAGE plpgsql;


--
-- Name: tva_insert(text, numeric, text, text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE OR REPLACE FUNCTION tva_insert(text, numeric, text, text) RETURNS integer
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
$_$
    LANGUAGE plpgsql;


--
-- Name: tva_modify(integer, text, numeric, text, text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE OR REPLACE FUNCTION tva_modify(integer, text, numeric, text, text) RETURNS integer
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
	credit  = split_part(p_tva_poste,',',2);
	select count(*) into nCount from tmp_pcmn where pcm_val=debit::account_type;
	if nCount = 0 then return 4; end if;
	select count(*) into nCount from tmp_pcmn where pcm_val=credit::account_type;
	if nCount = 0 then return 4; end if;
 
end if;
update tva_rate set tva_label=p_tva_label,tva_rate=p_tva_rate,tva_comment=p_tva_comment,tva_poste=p_tva_poste
	where tva_id=p_tva_id;
return 0;
end;
$_$
    LANGUAGE plpgsql;

commit;
