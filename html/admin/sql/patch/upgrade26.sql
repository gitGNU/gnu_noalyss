begin;
CREATE or replace FUNCTION account_compute(p_f_id integer) RETURNS poste_comptable
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

update version set val=27;
commit;
