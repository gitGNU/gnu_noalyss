-- Function: comptaproc.jrnx_del()


ALTER TABLE del_jrnx ADD COLUMN f_id bigint;

DROP FUNCTION comptaproc.jrnx_del();

CREATE OR REPLACE FUNCTION comptaproc.jrnx_del()
  RETURNS trigger AS
$BODY$
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
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION comptaproc.jrnx_del() OWNER TO dany;
