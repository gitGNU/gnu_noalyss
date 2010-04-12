--
-- PostgreSQL database dump
--

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
-- Name: account_add(public.poste_comptable, character varying); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION account_add(p_id public.poste_comptable, p_name character varying) RETURNS void
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
-- Name: account_auto(integer); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION account_auto(p_fd_id integer) RETURNS boolean
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
$$
    LANGUAGE plpgsql;


--
-- Name: account_compute(integer); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION account_compute(p_f_id integer) RETURNS public.poste_comptable
    AS $$
declare
        class_base poste_comptable;
        maxcode poste_comptable;
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
                maxcode:=class_base*1000;
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

CREATE FUNCTION account_insert(p_f_id integer, p_account text) RETURNS integer
    AS $$
declare
nParent tmp_pcmn.pcm_val_parent%type;
sName varchar;
nNew tmp_pcmn.pcm_val%type;
bAuto bool;
nFd_id integer;
nCount integer;
first text;
second text;
begin

	if length(trim(p_account)) != 0 then
	-- if there is coma in p_account, treat normally
		if position (',' in p_account) = 0 then
			raise info 'p_account is not empty';
				select count(*)  into nCount from tmp_pcmn where pcm_val=p_account::poste_comptable;
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

					nParent:=account_parent(p_account::poste_comptable);
					insert into tmp_pcmn(pcm_val,pcm_lib,pcm_val_parent) values (p_account::poste_comptable,sName,nParent);
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
		if bAuto = true then
			raise notice 'account generated automatically';
			nNew:=account_compute(p_f_id);
			raise notice 'nNew %', nNew;
			select av_text into sName from
			attr_value join jnt_fic_att_value using (jft_id)
			where
			ad_id=1 and f_id=p_f_id;
			nParent:=account_parent(nNew);
			perform account_add  (nNew,sName);
			perform attribut_insert(p_f_id,5,to_char(nNew,'999999999999999999999999'));

		else
		-- if there is an account_base then it is the default
		      select fd_class_base::text into nNew from fiche_def join fiche using (fd_id) where f_id=p_f_id;
			if nNew is null or length(trim(nNew)) = 0 then
				raise notice 'count is null';
				 perform attribut_insert(p_f_id,5,null);
			else
				 perform attribut_insert(p_f_id,5,to_char(nNew,'999999999999999999999999'));
			end if;
		end if;
	end if;

return 0;
end;
$$
    LANGUAGE plpgsql;


--
-- Name: account_parent(public.poste_comptable); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION account_parent(p_account public.poste_comptable) RETURNS public.poste_comptable
    AS $$
declare
	nParent tmp_pcmn.pcm_val_parent%type;
	sParent varchar;
	nCount integer;
begin
	sParent:=to_char(p_account,'9999999999999999');
	sParent:=trim(sParent::text);
	nParent:=0;
	while nParent = 0 loop
		select count(*) into nCount
		from tmp_pcmn
		where
		pcm_val = to_number(sParent,'9999999999999999');
		if nCount != 0 then
			nParent:=to_number(sParent,'9999999999999999');
			exit;
		end if;
		sParent:= substr(sParent,1,length(sParent)-1);
		if length(sParent) <= 0 then
			raise exception 'Impossible de trouver le compte parent pour %',p_account;
		end if;
	end loop;
	raise notice 'account_parent : Parent is %',nParent;
	return nParent;
end;
$$
    LANGUAGE plpgsql;


--
-- Name: account_update(integer, text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION account_update(p_f_id integer, p_account text) RETURNS integer
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
			select count(*) into nCount from tmp_pcmn where pcm_val=p_account::poste_comptable;
			if nCount = 0 then
			select av_text into sName from 
				attr_value join jnt_fic_att_value using (jft_id)
				where
				ad_id=1 and f_id=p_f_id;
			nParent:=account_parent(p_account::poste_comptable);
			insert into tmp_pcmn(pcm_val,pcm_lib,pcm_val_parent) values (p_account::poste_comptable,sName,nParent);
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
-- Name: action_gestion_ins_upd(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION action_gestion_ins_upd() RETURNS trigger
    AS $$
begin
NEW.ag_title := substr(trim(NEW.ag_title),1,70);
NEW.ag_hour := substr(trim(NEW.ag_hour),1,5);
return NEW;
end;
$$
    LANGUAGE plpgsql;


--
-- Name: attribut_insert(integer, integer, character varying); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION attribut_insert(p_f_id integer, p_ad_id integer, p_value character varying) RETURNS void
    AS $$
declare 
	n_jft_id integer;
begin
	select nextval('s_jnt_fic_att_value') into n_jft_id;
	 insert into jnt_fic_att_value (jft_id,f_id,ad_id) values (n_jft_id,p_f_id,p_ad_id);
	 insert into attr_value (jft_id,av_text) values (n_jft_id,trim(p_value));
return;
end;
$$
    LANGUAGE plpgsql;


--
-- Name: attribute_correct_order(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION attribute_correct_order() RETURNS void
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
$$
    LANGUAGE plpgsql;


--
-- Name: card_class_base(integer); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION card_class_base(p_f_id integer) RETURNS public.poste_comptable
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
$$
    LANGUAGE plpgsql;


--
-- Name: check_balance(integer); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION check_balance(p_grpt integer) RETURNS numeric
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
$$
    LANGUAGE plpgsql;


--
-- Name: correct_sequence(text, text, text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION correct_sequence(p_sequence text, p_col text, p_table text) RETURNS integer
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
$$
    LANGUAGE plpgsql;


--
-- Name: FUNCTION correct_sequence(p_sequence text, p_col text, p_table text); Type: COMMENT; Schema: comptaproc; Owner: -
--

COMMENT ON FUNCTION correct_sequence(p_sequence text, p_col text, p_table text) IS ' Often the primary key is a sequence number and sometimes the value of the sequence is not synchronized with the primary key ( p_sequence : sequence name, p_col : col of the pk,p_table : concerned table';


--
-- Name: create_missing_sequence(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION create_missing_sequence() RETURNS integer
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
$$
    LANGUAGE plpgsql;


--
-- Name: drop_index(character varying); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION drop_index(p_constraint character varying) RETURNS void
    AS $$
declare 
	nCount integer;
begin
	select count(*) into nCount from pg_indexes where indexname=p_constraint;
	if nCount = 1 then
	execute 'drop index '||p_constraint ;
	end if;
end;
$$
    LANGUAGE plpgsql;


--
-- Name: drop_it(character varying); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION drop_it(p_constraint character varying) RETURNS void
    AS $$
declare 
	nCount integer;
begin
	select count(*) into nCount from pg_constraint where conname=p_constraint;
	if nCount = 1 then
	execute 'alter table parm_periode drop constraint '||p_constraint ;
	end if;
end;
$$
    LANGUAGE plpgsql;


--
-- Name: extension_ins_upd(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION extension_ins_upd() RETURNS trigger
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

$$
    LANGUAGE plpgsql;


--
-- Name: fiche_account_parent(integer); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION fiche_account_parent(p_f_id integer) RETURNS public.poste_comptable
    AS $$
declare
ret poste_comptable;
begin
	select fd_class_base into ret from fiche_def join fiche using (fd_id) where f_id=p_f_id;
	if not FOUND then
		raise exception '% N''existe pas',p_f_id;
	end if;
	return ret;
end;
$$
    LANGUAGE plpgsql;


--
-- Name: fiche_attribut_synchro(integer); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION fiche_attribut_synchro(p_fd_id integer) RETURNS void
    AS $$
declare
	-- this sql gives the f_id and the missing attribute (ad_id)
	list_missing cursor for select f_id,fd_id,ad_id,jnt_order from jnt_fic_attr join fiche as A using (fd_id) where fd_id=p_fd_id and ad_id not in (select ad_id from fiche join jnt_fic_att_value using (f_id) where fd_id=jnt_fic_attr.fd_id and A.f_id=f_id);
	rec record;
	-- value of the last insert
	jnt jnt_fic_att_value%ROWTYPE;
begin
	open list_missing;
	loop
	
	fetch list_missing into rec;
	IF NOT FOUND then
		exit;
	end if;
	-- insert a value into jnt_fic_att_value
	insert 	into jnt_fic_att_value (f_id,ad_id) values (rec.f_id,rec.ad_id) returning * into jnt;

	-- now we insert into attr_value
	insert into attr_value values (jnt.jft_id,'');
	end loop;
	close list_missing;
end; 
$$
    LANGUAGE plpgsql;


--
-- Name: fiche_def_ins_upd(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION fiche_def_ins_upd() RETURNS trigger
    AS $$
begin

if position (',' in NEW.fd_class_base) != 0 then
   NEW.fd_create_account='f';

end if;
return NEW;
end;$$
    LANGUAGE plpgsql;


--
-- Name: find_pcm_type(numeric); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION find_pcm_type(pp_value numeric) RETURNS text
    AS $$
declare
        str_type text;
        str_value text;
        n_value numeric;
        nLength integer;
begin 
        str_value:=trim(to_char(pp_value,'99999999999999999999999999999'));
        nLength:=length(str_value);
	while nLength > 0 loop
		n_value:=to_number(str_value,'99999999999999999999999999999');
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
-- Name: group_analytic_ins_upd(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION group_analytic_ins_upd() RETURNS trigger
    AS $$
declare 
name text;
begin
name:=upper(NEW.ga_id);
name:=trim(name);
name:=replace(name,' ','');
NEW.ga_id:=name;
return NEW;
end;$$
    LANGUAGE plpgsql;


--
-- Name: group_analytique_del(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION group_analytique_del() RETURNS trigger
    AS $$
begin
update poste_analytique set ga_id=null
where ga_id=OLD.ga_id;
return OLD;
end;$$
    LANGUAGE plpgsql;


--
-- Name: html_quote(text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION html_quote(p_string text) RETURNS text
    AS $$
declare
	r text;
begin
	r:=p_string;
	r:=replace(r,'<','&lt;');
	r:=replace(r,'>','&gt;');
	r:=replace(r,'''','&quot;');
	return r;
end;$$
    LANGUAGE plpgsql;


--
-- Name: FUNCTION html_quote(p_string text); Type: COMMENT; Schema: comptaproc; Owner: -
--

COMMENT ON FUNCTION html_quote(p_string text) IS 'remove harmfull HTML char';


--
-- Name: info_def_ins_upd(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION info_def_ins_upd() RETURNS trigger
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
$$
    LANGUAGE plpgsql;


--
-- Name: insert_jrnx(character varying, numeric, public.poste_comptable, integer, integer, boolean, text, integer, text, text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION insert_jrnx(p_date character varying, p_montant numeric, p_poste public.poste_comptable, p_grpt integer, p_jrn_def integer, p_debit boolean, p_tech_user text, p_tech_per integer, p_qcode text, p_comment text) RETURNS void
    AS $$
declare
	sCode varchar;
	nCount_qcode integer;
begin
	sCode=trim(p_qcode);

	-- if p_qcode is empty try to find one
	if length(sCode) = 0 or p_qcode is null then
		select count(*) into nCount_qcode
			from vw_poste_qcode where j_poste=p_poste::text;
	-- if we find only one q_code for a accountancy account
	-- then retrieve it
		if nCount_qcode = 1 then
			select j_qcode::text into sCode
			from vw_poste_qcode where j_poste=p_poste::text;
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
-- Name: insert_quant_purchase(text, numeric, character varying, numeric, numeric, numeric, integer, numeric, numeric, numeric, numeric, character varying); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION insert_quant_purchase(p_internal text, p_j_id numeric, p_fiche character varying, p_quant numeric, p_price numeric, p_vat numeric, p_vat_code integer, p_nd_amount numeric, p_nd_tva numeric, p_nd_tva_recup numeric, p_dep_priv numeric, p_client character varying) RETURNS void
    AS $$
declare
	fid_client integer;
	fid_good   integer;
begin
	select f_id into fid_client from
		attr_value join jnt_fic_att_value using (jft_id) where ad_id=23 and av_text=upper(trim(p_client));
	select f_id into fid_good from
		attr_value join jnt_fic_att_value using (jft_id) where ad_id=23 and av_text=upper(trim(p_fiche));
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
 $$
    LANGUAGE plpgsql;


--
-- Name: insert_quant_sold(text, numeric, character varying, numeric, numeric, numeric, integer, character varying); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION insert_quant_sold(p_internal text, p_jid numeric, p_fiche character varying, p_quant numeric, p_price numeric, p_vat numeric, p_vat_code integer, p_client character varying) RETURNS void
    AS $$
declare
	fid_client integer;
	fid_good   integer;
begin

	select f_id into fid_client from
		attr_value join jnt_fic_att_value using (jft_id) where ad_id=23 and av_text=upper(trim(p_client));
	select f_id into fid_good from
		attr_value join jnt_fic_att_value using (jft_id) where ad_id=23 and av_text=upper(trim(p_fiche));
	insert into quant_sold
		(qs_internal,j_id,qs_fiche,qs_quantite,qs_price,qs_vat,qs_vat_code,qs_client,qs_valid)
	values
		(p_internal,p_jid,fid_good,p_quant,p_price,p_vat,p_vat_code,fid_client,'Y');
	return;
end;
 $$
    LANGUAGE plpgsql;


--
-- Name: insert_quick_code(integer, text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION insert_quick_code(nf_id integer, tav_text text) RETURNS integer
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
			from jnt_fic_att_value join attr_value using (jft_id) 
		where 
			ad_id=23 and  av_text=upper(tText);

		if nExist = 0 then
			exit;
		end if;
		tText:='FID'||ns;
	end loop;
	-- insert into table jnt_fic_att_value
	insert into jnt_fic_att_value values (ns,nf_id,23);
	-- insert value into attr_value
	insert into attr_value values (ns,upper(tText));
	return ns;
	end;
$$
    LANGUAGE plpgsql;


--
-- Name: jrn_check_periode(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION jrn_check_periode() RETURNS trigger
    AS $$
declare 
bClosed bool;
str_status text;
ljr_tech_per jrn.jr_tech_per%TYPE;
ljr_def_id jrn.jr_def_id%TYPE;
lreturn jrn%ROWTYPE;
begin
if TG_OP='INSERT' then
	ljr_tech_per :=NEW.jr_tech_per;
	ljr_def_id   :=NEW.jr_def_id;
        lreturn      :=NEW;
end if;

if TG_OP='DELETE' then
	ljr_tech_per :=OLD.jr_tech_per;
	ljr_def_id   :=OLD.jr_def_id;
        lreturn      :=OLD;
end if;

select p_closed into bClosed from parm_periode 
	where p_id=ljr_tech_per;

if bClosed = true then
	raise exception 'Periode fermee';
end if;

select status into str_status from jrn_periode 
       where p_id =ljr_tech_per and jrn_def_id=ljr_def_id;

if str_status <> 'OP' then
	raise exception 'Periode fermee';
end if;

return lreturn;
end;$$
    LANGUAGE plpgsql;


--
-- Name: jrn_def_add(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION jrn_def_add() RETURNS trigger
    AS $$begin
execute 'insert into jrn_periode(p_id,jrn_def_id,status) select p_id,'||NEW.jrn_def_id||',
	case when p_central=true then ''CE''
	      when p_closed=true then ''CL''
	else ''OP''
	end
from
parm_periode ';
return NEW;
end;$$
    LANGUAGE plpgsql;


--
-- Name: jrn_def_delete(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION jrn_def_delete() RETURNS trigger
    AS $$
declare 
nb numeric;
begin
select count(*) into nb from jrn where jr_def_id=OLD.jrn_def_id;

if nb <> 0 then
	raise exception 'EFFACEMENT INTERDIT: JOURNAL UTILISE';
end if;
return OLD;
end;$$
    LANGUAGE plpgsql;


--
-- Name: jrn_del(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION jrn_del() RETURNS trigger
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
$$
    LANGUAGE plpgsql;


--
-- Name: jrnx_del(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION jrnx_del() RETURNS trigger
    AS $$
declare
row jrnx%ROWTYPE;
begin
row:=OLD;
insert into del_jrnx select * from jrnx where j_id=row.j_id;
return row;
end;
$$
    LANGUAGE plpgsql;


--
-- Name: plan_analytic_ins_upd(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION plan_analytic_ins_upd() RETURNS trigger
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
$$
    LANGUAGE plpgsql;


--
-- Name: poste_analytique_ins_upd(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION poste_analytique_ins_upd() RETURNS trigger
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
end;$$
    LANGUAGE plpgsql;


--
-- Name: proc_check_balance(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION proc_check_balance() RETURNS trigger
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
$$
    LANGUAGE plpgsql;


--
-- Name: t_document_modele_validate(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION t_document_modele_validate() RETURNS trigger
    AS $$
declare 
    lText text;
    modified document_modele%ROWTYPE;
begin
    modified:=NEW;

	modified.md_filename:=replace(NEW.md_filename,' ','_');
	return modified;
end;
$$
    LANGUAGE plpgsql;


--
-- Name: t_document_type_insert(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION t_document_type_insert() RETURNS trigger
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
$$
    LANGUAGE plpgsql;


--
-- Name: t_document_validate(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION t_document_validate() RETURNS trigger
    AS $$
declare
  lText text;
  modified document%ROWTYPE;
begin
    	modified:=NEW;
	modified.d_filename:=replace(NEW.d_filename,' ','_');
	return modified;
end;
$$
    LANGUAGE plpgsql;


--
-- Name: t_jrn_def_sequence(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION t_jrn_def_sequence() RETURNS trigger
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


--
-- Name: tmp_pcmn_ins(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION tmp_pcmn_ins() RETURNS trigger
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
$$
    LANGUAGE plpgsql;


--
-- Name: trim_cvs_quote(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION trim_cvs_quote() RETURNS trigger
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
$$
    LANGUAGE plpgsql;


--
-- Name: trim_space_format_csv_banque(); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION trim_space_format_csv_banque() RETURNS trigger
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
$$
    LANGUAGE plpgsql;


--
-- Name: tva_delete(integer); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION tva_delete(integer) RETURNS void
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
$_$
    LANGUAGE plpgsql;


--
-- Name: tva_insert(text, numeric, text, text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION tva_insert(text, numeric, text, text) RETURNS integer
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
	select count(*) into nCount from tmp_pcmn where pcm_val=debit::poste_comptable;
	if nCount = 0 then return 4; end if;
	select count(*) into nCount from tmp_pcmn where pcm_val=credit::poste_comptable;
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

CREATE FUNCTION tva_modify(integer, text, numeric, text, text) RETURNS integer
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
	select count(*) into nCount from tmp_pcmn where pcm_val=debit::poste_comptable;
	if nCount = 0 then return 4; end if;
	select count(*) into nCount from tmp_pcmn where pcm_val=credit::poste_comptable;
	if nCount = 0 then return 4; end if;
 
end if;
update tva_rate set tva_label=p_tva_label,tva_rate=p_tva_rate,tva_comment=p_tva_comment,tva_poste=p_tva_poste
	where tva_id=p_tva_id;
return 0;
end;
$_$
    LANGUAGE plpgsql;


--
-- Name: update_quick_code(integer, text); Type: FUNCTION; Schema: comptaproc; Owner: -
--

CREATE FUNCTION update_quick_code(njft_id integer, tav_text text) RETURNS integer
    AS $$
	declare
	ns integer;
	nExist integer;
	tText text;
	old_qcode varchar;
	begin
	-- get current value
	select av_text into old_qcode from attr_value where jft_id=njft_id;
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
			from jnt_fic_att_value join attr_value using (jft_id) 
		where 
			ad_id=23 and av_text=tText;

		if nExist = 0 then
			exit;
		end if;	
		if tText = 'FID'||ns then
			-- take the next sequence
			select nextval('s_jnt_fic_att_value') into ns;
		end if;
		tText  :='FID'||ns;
		
	end loop;
	update attr_value set av_text = tText where jft_id=njft_id;

	-- update also the contact
	update attr_value set av_text = tText 
		where jft_id in 
			( select jft_id 
				from jnt_fic_att_value join attr_value using (jft_id) 
			where ad_id=25 and av_text=old_qcode);


	update jrnx set j_qcode=tText where j_qcode = old_qcode;
	return ns;
	end;
$$
    LANGUAGE plpgsql;


--
-- PostgreSQL database dump complete
--

