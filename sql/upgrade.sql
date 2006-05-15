
create or replace function account_auto (p_fd_id fiche_def.fd_id%type)
returns bool
as
$$
-- account_auto
-- param fd_id
-- return true if the card generate automatically an account
declare
	l_auto bool;
begin

	select fd_create_account into l_auto from fiche_def where fd_id=p_fd_id;
	if l_auto is null then
		l_auto:=false;
	end if;
	return l_auto;
end;
$$ language plpgsql;

create or replace function account_compute(p_f_id fiche.f_id%type)
returns integer
as
$body$
-- account_compute
-- param f_id
-- compute the next account
-- return new account
declare
	class_base int8;
	maxcode int8;
begin
 -- Get the class base
	select fd_class_base into class_base 
	from
		fiche_def join fiche using (fd_id)
	where 
		f_id=p_f_id;
	raise notice 'class base %',class_base;
	select max(pcm_val) into maxcode from tmp_pcmn where pcm_val_parent = class_base;
	if maxcode = class_base then
		maxcode=class_base*1000+1;
	end if;
	raise notice 'Max code %',maxcode;
return maxcode+1;
end;
$body$ language plpgsql;





CREATE OR REPLACE FUNCTION account_insert(p_f_id fiche.f_id%type,p_account tmp_pcmn.pcm_val%type)
  RETURNS int4 AS
$BODY$
declare
-- account_insert
-- parameter f_id,p_account label of account
-- purpose : create a new account for a card
-- check if the accound needs to be created automatically
-- if p_account is empty or null
-- into tables attr_value
nCount integer;
nParent tmp_pcmn.pcm_val_parent%type;
sName varchar;
nNew tmp_pcmn.pcm_val%type;
bAuto bool;
nFd_id integer;
begin
	
	-- if p_value empty
	if length(trim(p_account)) != 0 then
	-- does the account exist ?
		select count(*) into nCount from tmp_pcmn where pcm_val=p_account;
		if nCount = 0 then
			-- retrieve name
			select av_text into sName from 
				attr_value join jnt_fic_att_value using (jft_id)
			where	
			ad_id=1 and f_id=p_f_id;
			-- get parent
			nParent:=account_parent(p_account);
			-- account doesn't exist we need to add id
			insert into tmp_pcmn(pcm_val,pcm_lib,pcm_val_parent) 
				values (p_account,sName,nParent);
			-- insert as card's attribute
			attribut_insert(p_f_id,5,to_char(nNew,'999999999999'));
	
		end if;		
	else 
		select fd_id into nFd_id from fiche where f_id=p_f_id;
		bAuto:= account_auto(nFd_id);

		if bAuto = true then
			-- create automatically the account
			-- compute the next account
			nNew:=account_compute(p_f_id);
raise notice 'nNew %', nNew;
			-- retrieve name
			select av_text into sName from 
			attr_value join jnt_fic_att_value using (jft_id)
			where
			ad_id=1 and f_id=p_f_id;

			-- get parent
			nParent:=account_parent(nNew);
			-- account doesn't exist we need to add id
			insert into tmp_pcmn(pcm_val,pcm_lib,pcm_val_parent) values (nNew,sName,nParent);
			-- insert as card's attribute
			execute attribut_insert(p_f_id,5,to_char(nNew,'999999999999'));
	
		else 
			 execute attribut_insert(p_f_id,5,null);
		end if;

	end if;
		
return 0;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE;
create or replace function account_parent(p_account tmp_pcmn.pcm_val%type)
returns 
	-- account_parent
	-- parameter pcm_val%type;
	-- purpose compute the parent account	

	integer
as
$$
declare
	nParent tmp_pcmn.pcm_val_parent%type;
	sParent varchar;
	nCount integer;
begin
	sParent:=to_char(p_account,'9999999999999999');
	sParent:=trim(sParent);
	nParent:=0;
	while nParent = 0 loop
		select count(*) into nCount 
		from tmp_pcmn
		where
		pcm_val = to_number(sParent,'9999999999999999');
	if nCount <> 0 then
		nParent:=to_number(sParent,'9999999999999999');
	end if;
	sParent:= substr(sParent,1,length(sParent)-1);

	end loop;
	return nParent;
end;
$$ language plpgsql volatile;
-- Function: account_update()

-- DROP FUNCTION account_update();

CREATE OR REPLACE FUNCTION account_update(p_f_id fiche.f_id%type,p_account tmp_pcmn.pcm_val%type)
  RETURNS int4 AS
$BODY$
-- account_update
-- parameter f_id, pcm_val
-- purpose update the account of a card and create it into PCMN if it doesn't exist yet
-- 
declare
nMax fiche.f_id%type;
nCount integer;
nParent tmp_pcmn.pcm_val_parent%type;
sName varchar;
nJft_id attr_value.jft_id%type;
begin
	
	-- if p_value empty
	if length(trim(p_account)) != 0 then
	-- does the account exist ?
		select count(*) into nCount from tmp_pcmn where pcm_val=p_account;
		if nCount = 0 then
		-- retrieve name
		select av_text into sName from 
			attr_value join jnt_fic_att_value using (jft_id)
			where
			ad_id=1 and f_id=p_f_id;
		-- get parent
		nParent:=account_parent(p_account);
		-- account doesn't exist we need to add id
		insert into tmp_pcmn(pcm_val,pcm_lib,pcm_val_parent) values (p_account,sName,nParent);
		end if;		
	end if;
	-- we retrieve jft_id
	select jft_id into njft_id from jnt_fic_att_value where f_id=p_f_id and ad_id=5;
	-- we update the account
	update attr_value set av_text=p_account where jft_id=njft_id;
		
return njft_id;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE;
create or replace function attribut_insert ( p_f_id integer, p_ad_id integer, p_value varchar)
returns void
as
$$
-- attribut_integer
-- parameter : f_id, ad_id, p_value
-- purpose add an attribute to a card
-- it inserts a row into jnt_fic_att_value and attr_value
declare 
	n_jft_id integer;
begin
	select nextval('s_jnt_fic_att_value') into n_jft_id;
	 insert into jnt_fic_att_value (jft_id,f_id,ad_id) values (n_jft_id,p_f_id,p_ad_id);
	 insert into attr_value (jft_id,av_text) values (n_jft_id,p_value);
return;
end;
$$
language plpgsql volatile;
