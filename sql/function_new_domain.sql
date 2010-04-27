CREATE or replace FUNCTION account_compute(p_f_id integer) RETURNS account_type
    AS $$
declare
        class_base account_type;
        maxcode account_type;
begin
        select fd_class_base::numeric into class_base
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
        maxcode:=maxcode::numeric+1;
        raise notice 'account_compute Max code %',maxcode;
        return maxcode::account_type;
end;
$$
    LANGUAGE plpgsql;
CREATE OR REPLACE FUNCTION account_insert(p_f_id integer, p_account account_type) RETURNS integer
    AS $$
declare
nParent tmp_pcmn.pcm_val_parent%type;
sName varchar;
nNew tmp_pcmn.pcm_val%type;
bAuto bool;
nFd_id integer;
nCount integer;
begin
	
	if length(trim(p_account::text)) != 0 then
	raise debug 'p_account is not empty';
		select count(*)  into nCount from tmp_pcmn where pcm_val=p_account;
		raise notice 'found in tmp_pcm %',nCount;
		if nCount !=0  then
			raise notice 'this account exists in tmp_pcmn ';
			perform attribut_insert(p_f_id,5,p_account::text);
                   else 
                       -- account doesn't exist, create it
			select av_text into sName from 
				attr_value join jnt_fic_att_value using (jft_id)
			where	
			ad_id=1 and f_id=p_f_id;

			nParent:=account_parent(p_account);
			insert into tmp_pcmn(pcm_val,pcm_lib,pcm_val_parent) values (p_account,sName,nParent);
			perform attribut_insert(p_f_id,5,p_account::text);
	
		end if;		
	else 
	raise notice 'p_account is  empty';
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
			perform attribut_insert(p_f_id,5,nNew::text);
	
		else 
                -- if there is an account_base then it is the default
 	              select fd_class_base::text into nNew from fiche_def join fiche using (fd_id) where f_id=p_f_id;
			if nNew is null or length(trim(nNew)) = 0 then	 
				raise notice 'count is null';
				 perform attribut_insert(p_f_id,5,null);
			else
				 perform attribut_insert(p_f_id,5,nNew::text);
			end if;
		end if;
	end if;
		
return 0;
end;
$$
    LANGUAGE plpgsql;
CREATE OR REPLACE FUNCTION account_parent(p_account account_type) RETURNS account_type
    AS $$
declare
	nParent tmp_pcmn.pcm_val_parent%type;
	sParent varchar;
	nCount integer;
begin
	sParent:=p_account::text;
	sParent:=trim(sParent::text);
	nParent:=0;
	while nParent = 0 loop
		select count(*) into nCount
		from tmp_pcmn
		where
		pcm_val = sParent::account_type;
		if nCount != 0 then
			nParent:=sParent::account_type;
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
CREATE OR REPLACE FUNCTION account_update(p_f_id integer, p_account account_type) RETURNS integer
    AS $$
declare
nMax fiche.f_id%type;
nCount integer;
nParent tmp_pcmn.pcm_val_parent%type;
sName varchar;
nJft_id attr_value.jft_id%type;
begin
	
	if length(trim(p_account::text)) != 0 then
		select count(*) into nCount from tmp_pcmn where pcm_val=p_account;
		if nCount = 0 then
		select av_text into sName from 
			attr_value join jnt_fic_att_value using (jft_id)
			where
			ad_id=1 and f_id=p_f_id;
		nParent:=account_parent(p_account);
		insert into tmp_pcmn(pcm_val,pcm_lib,pcm_val_parent) values (p_account,sName,nParent);
		end if;		
	end if;
	select jft_id into njft_id from jnt_fic_att_value where f_id=p_f_id and ad_id=5;
	update attr_value set av_text=p_account where jft_id=njft_id;
		
return njft_id;
end;
$$
    LANGUAGE plpgsql;

drop domain poste_comptable;
