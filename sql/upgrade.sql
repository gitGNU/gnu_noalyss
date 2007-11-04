CREATE or replace FUNCTION t_document_modele_validate() RETURNS "trigger"
    AS $$declare 
    lText text;
    modified document_modele%ROWTYPE;
begin
    modified=NEW;

    if length(trim(modified.md_filename)) = 0 or modified.md_filename is NULL then
	raise EXCEPTION 'Erreur nom de fichier invalide';
    end if;
modified.md_filename=replace(NEW.md_filename,' ','');
return modified;
end;$$
    LANGUAGE plpgsql;



CREATE  or replace FUNCTION t_document_validate() RETURNS "trigger"
    AS $$declare
  lText text;
    modified document%ROWTYPE;
begin
    modified=NEW;
    if length(trim(modified.d_filename)) = 0 or modified.d_filename is NULL then
	raise EXCEPTION 'Erreur nom de fichier invalide';
    end if;
modified.d_filename=replace(NEW.d_filename,' ','');
return modified;
end;$$
    LANGUAGE plpgsql;


CREATE TRIGGER document_validate
    BEFORE INSERT OR UPDATE ON document
    FOR EACH ROW
    EXECUTE PROCEDURE t_document_validate();

CREATE TRIGGER document_modele_validate
    BEFORE INSERT OR UPDATE ON document_modele
    FOR EACH ROW
    EXECUTE PROCEDURE t_document_modele_validate();
