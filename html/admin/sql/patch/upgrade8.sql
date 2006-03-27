begin;

insert into action values (21,'Import et export des écritures d''ouverture');
create sequence s_quantity;

create index idx_qs_internal on quant_sold(qs_internal);
CREATE TABLE quant_sold (
    qs_id integer DEFAULT nextval('s_quantity'::text),
    qs_internal text NOT NULL,
    qs_fiche integer NOT NULL,
    qs_quantite integer NOT NULL,
    qs_price numeric(20,4),
    qs_vat numeric(20,4),
    qs_vat_code integer
);
create table format_csv_banque 
(
	name text primary key,
	include_file text not null
);
-- drop trigger trim_space on format_csv_banque;
-- 
-- drop function trim_space_format_csv_banque();

create function trim_space_format_csv_banque() returns trigger as $trim$
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
$trim$ language plpgsql;

create trigger trim_space before insert or update on format_csv_banque FOR EACH ROW execute procedure trim_space_format_csv_banque();

create unique index idx_case on format_csv_banque (upper(name));
INSERT INTO format_csv_banque VALUES ('Fortis', 'fortis_be.inc.php');
INSERT INTO format_csv_banque VALUES ('EUB', 'eub_be.inc.php');
INSERT INTO format_csv_banque VALUES ('ING', 'ing_be.inc.php');
INSERT INTO format_csv_banque VALUES ('CBC', 'cbc_be.inc.php');

CREATE TABLE import_tmp (
    code text,
    date_exec date,
    date_valeur date,
    montant text,
    devise text,
    compte_ordre text,
    detail text,
    num_compte text,
    poste_comptable text,
    ok boolean DEFAULT false,
    bq_account integer not null,
	jrn integer not null
);
create function trim_cvs_quote() returns trigger as $trim$
declare
        modified import_tmp%ROWTYPE;
begin
		modified.code=new.code;
		modified.montant=new.montant;
		modified.date_exec=new.date_exec;
		modified.date_valeur=new.date_valeur;
		modified.devise=replace(new.devise,'"','');
		modified.poste_comptable=replace(new.poste_comptable,'"','');
        modified.compte_ordre=replace(NEW.COMPTE_ORDRE,'"','');
        modified.detail=replace(NEW.DETAIL,'"','');
        modified.num_compte=replace(NEW.NUM_COMPTE,'"','');
		modified.bq_account=NEW.bq_account;
		modified.jrn=NEW.jrn;
		modified.ok=new.ok;
        return modified;
end;
$trim$ language plpgsql;

create trigger trim_quote before insert or update on import_tmp FOR EACH ROW execute procedure trim_cvs_quote();


update version set val=9;


commit;
