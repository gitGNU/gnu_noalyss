alter table jrn_def add jrn_def_description text;

CREATE OR REPLACE FUNCTION comptaproc.t_jrn_def_description()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
    declare
        str varchar(200);
    BEGIN
        str := substr(NEW.jrn_def_description,1,200);
        NEW.jrn_def_description := str;

        RETURN NEW;
    END;
$function$
;
create  trigger jrn_def_description_ins_upd before insert or update on jrn_def for each row execute procedure comptaproc.t_jrn_def_description();