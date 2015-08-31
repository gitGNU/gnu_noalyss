update menu_ref set me_file = null where me_code='EXT';
update op_predef_detail set opd_poste=trim(opd_poste) ;

CREATE OR REPLACE FUNCTION comptaproc.fiche_detail_qcode_upd()
  RETURNS trigger AS
$BODY$
declare
	i record;
begin
	if NEW.ad_id=23 and NEW.ad_value != OLD.ad_value then
		RAISE NOTICE 'new qcode [%] old qcode [%]',NEW.ad_value,OLD.ad_value;
		update jrnx set j_qcode=NEW.ad_value where j_qcode = OLD.ad_value;    
	        update op_predef_detail set opd_poste=NEW.ad_value where opd_poste=OLD.ad_value;
	        raise notice 'TRG fiche_detail update op_predef_detail set opd_poste=% where opd_poste=%;',NEW.ad_value,OLD.ad_value;
		for i in select ad_id from attr_def where ad_type = 'card' or ad_id=25 loop
			update fiche_detail set ad_value=NEW.ad_value where ad_value=OLD.ad_value and ad_id=i.ad_id;
			RAISE NOTICE 'change for ad_id [%] ',i.ad_id;
			if i.ad_id=19 then
				RAISE NOTICE 'Change in stock_goods OLD[%] by NEW[%]',OLD.ad_value,NEW.ad_value;
				update stock_goods set sg_code=NEW.ad_value where sg_code=OLD.ad_value;
			end if;

		end loop;
	end if;
return NEW;
end;
$BODY$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION comptaproc.update_quick_code(njft_id integer, tav_text text)
  RETURNS integer AS
$BODY$
	declare
	ns integer;
	nExist integer;
	tText text;
	old_qcode varchar;
	num_rows_jrnx integer;
	num_rows_predef integer;
	begin
	-- get current value
	select ad_value into old_qcode from fiche_detail where jft_id=njft_id;
	-- av_text didn't change so no update
	if tav_text = upper( trim(old_qcode)) then
		raise notice 'nothing to change % %' , tav_text,old_qcode;
		return 0;
	end if;

	tText := trim(lower(tav_text));
	tText := replace(tText,' ','');
        -- valid alpha is [ . : - _ ]
	tText := translate(tText,E' $€µ£%+/\\!(){}(),;&|"#''^<>*','');
	tText := translate(tText,E'éèêëàâäïîüûùöôç','eeeeaaaiiuuuooc');
	tText := upper(tText);
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


	return ns;
	end;
$BODY$
  LANGUAGE plpgsql ;


CREATE OR REPLACE FUNCTION comptaproc.insert_quick_code(nf_id integer, tav_text text)
  RETURNS integer AS
$BODY$
	declare
	ns integer;
	nExist integer;
	tText text;
	tBase text;
	tName text;
	nCount Integer;
	nDuplicate Integer;
	begin
	tText := lower(trim(tav_text));
	tText := replace(tText,' ','');
        tName:= translate(tName,E' $€µ£%+/\\!(){}(),;&|"#''^<>*','');
	tText := translate(tText,E'éèêëàâäïîüûùöôç','eeeeaaaiiuuuooc');
	nDuplicate := 0;
	tBase := tText;
	loop
		-- take the next sequence
		select nextval('s_jnt_fic_att_value') into ns;
		if length (tText) = 0 or tText is null then
			select count(*) into nCount from fiche_detail where f_id=nf_id and ad_id=1;
			if nCount = 0 then
				tText := 'FICHE'||ns::text;
			else
				select ad_value into tName from fiche_detail where f_id=nf_id and ad_id=1;
				
				tName := lower(trim(tName));
				tName := substr(tName,1,6);
				tName := replace(tName,' ','');
				tName:= translate(tName,E' $€µ£%+/\\!(){}(),;&|"#''^<>*','');
				tName := translate(tName,E'éèêëàâäïîüûùöôç','eeeeaaaiiuuuooc');
				tBase := tName;
				if nDuplicate = 0 then
					tText := tName;
				else
					tText := tName||nDuplicate::text;
				end if;
			end if;
		end if;
		-- av_text already used ?
		select count(*) into nExist
			from fiche_detail
		where
			ad_id=23 and  ad_value=upper(tText);

		if nExist = 0 then
			exit;
		end if;
		nDuplicate := nDuplicate + 1 ;
		tText := tBase || nDuplicate::text;
		
		if nDuplicate > 9999 then
			raise Exception 'too many duplicate % duplicate# %',tText,nDuplicate;
		end if;
	end loop;


	insert into fiche_detail(jft_id,f_id,ad_id,ad_value) values (ns,nf_id,23,upper(tText));
	return ns;
	end;
$BODY$
LANGUAGE plpgsql;

create or replace function insert_menu() 
returns void as
$BODY$
declare 
    n_count integer := 0;
begin
    select count(*) into n_count from menu_ref where me_code='CONTACT';
    if n_count = 0 then
        insert into menu_ref(me_code,
                    me_file,
                    me_menu,
                    me_description,me_type,me_description_etendue)
        values     
        ('CONTACT',
        'contact.inc.php',
          'Contact','Liste de vos contacts','ME','Liste de vos contacts normalement liée à des fiches de sociétés');

        insert into profile_menu(me_code,me_code_dep,p_id,p_order,p_type_display,pm_default,pm_id_dep) select 'CONTACT','GESTION',1,22,'E',0,(select pm_id from profile_menu where me_code='GESTION' and p_id=1);
        insert into profile_menu(me_code,me_code_dep,p_id,p_order,p_type_display,pm_default,pm_id_dep) select 'CONTACT','GESTION',2,22,'E',0,(select pm_id from profile_menu where me_code='GESTION' and p_id=2);
    end if;
end;
$BODY$
language plpgsql;