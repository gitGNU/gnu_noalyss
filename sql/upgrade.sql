ALTER TABLE action    ALTER COLUMN ac_code TYPE character varying(30);

INSERT INTO action(ac_id, ac_description, ac_module, ac_code)
    VALUES (1110, 'Enlever une pièce justificative', 'compta', 'RMRECEIPT');
INSERT INTO action(ac_id, ac_description, ac_module, ac_code)
    VALUES (1120, 'Effacer une opération ', 'compta', 'RMOPER');
INSERT INTO action(ac_id, ac_description, ac_module, ac_code)
    VALUES (1210, 'Partager une note', 'note', 'SHARENOTE');
INSERT INTO action(ac_id, ac_description, ac_module, ac_code)
    VALUES (1220, 'Créer une note publique', 'note', 'SHARENOTEPUBLIC');
INSERT INTO action(ac_id, ac_description, ac_module, ac_code)
    VALUES (1230, 'Effacer une note publique', 'note', 'SHARENOTEREMOVE');


CREATE TABLE todo_list_shared (id  serial primary key, todo_list_id int4 NOT NULL, use_login text NOT NULL, CONSTRAINT unique_todo_list_id_login 
    UNIQUE (todo_list_id, use_login));

ALTER TABLE todo_list_shared ADD CONSTRAINT fk_todo_list_shared_todo_list FOREIGN KEY (todo_list_id) REFERENCES todo_list (tl_id);

comment on table todo_list_shared is 'Note of todo list shared with other users';
comment on column todo_list_shared.todo_list_id is 'fk to todo_list';
comment on column todo_list_shared.use_login is 'user login';

alter table todo_list add is_public char(1) default 'N';
comment on column todo_list.is_public is 'Flag for the public parameter';
ALTER TABLE todo_list    ALTER COLUMN is_public SET NOT NULL;

ALTER TABLE todo_list ADD CONSTRAINT ck_is_public CHECK (is_public in ('Y','N'));

/**
Arbre dépendance
 with recursive t (ag_id,ag_ref_ag_id,ag_title,depth) as (
  select 
    ag_id , ag_ref_ag_id, ag_title , 1
  from 
    action_gestion
  where ag_id=55
  union all
  select 
    p2.ag_id,p2.ag_ref_ag_id,p2.ag_title,depth + 1
  from 
    t as p1, action_gestion as p2
  where
    p1.ag_ref_ag_id is not null and
    p1.ag_id = p2.ag_ref_ag_id
) select * from t;

*/
-- update menu_ref set me_menu = me_menu||' <span id="menu_'||lower(me_code)||'"><img src="image/empty.gif"></span>' where me_type='ME';
update menu_ref set me_menu = 'Favori &#9733; ' where me_code='BOOKMARK';
update menu_ref set me_menu = 'Sortie &#9094;' where me_code='LOGOUT'; 

insert into menu_ref(me_code,me_menu,me_file, me_url,me_description,me_parameter,me_javascript,me_type,me_description_etendue)
values
('BALAGE','Balance agée','balance_age.inc.php',null,'Balance agée',null,null,'ME','Balance agée pour les clients et fournisseurs') ,
('CSV:balance_age','Export Balance agée','export_balance_age_csv.php',null,'Balance agée',null,null,'PR','Balance agée pour les clients et fournisseurs') 

;

insert into profile_menu (me_code,me_code_dep,p_id,p_order, p_type_display,pm_default) 
values
('BALAGE','PRINT',1,550,'M',0),('BALAGE','PRINT',2,550,'M',0),
('CSV:balance_age',null,1,null,'P',0),('CSV:balance_age',null,2,null,'P',0)
;


/*
with m as (
    select jr_id,jr_grpt_id,
            coalesce(jr_ech,jr_date) as op_date ,
            jr_date_paid from jrn 
    where jr_date_paid is not null
),n as (
    select jr_id ,jr_date_paid - op_date    as delta,jr_grpt_id,jr_date_paid,op_date
    from m 
    where 
    jr_date_paid  - op_date < 30 
    ),solde as (
select sum(qp_price+qp_vat+qp_nd_amount+qp_nd_tva+qp_nd_tva_recup - qp_vat_sided), 
    qp_supplier 
from quant_purchase 
    join jrnx using (j_id) 
    join n on (j_grpt=n.jr_grpt_id) 
group by qp_supplier)
select * , 
    (select vw_name from vw_fiche_attr where f_id=qp_supplier) ,
    (select vw_first_name from vw_fiche_attr where f_id=qp_supplier) ,
(select quick_code from vw_fiche_attr where f_id=qp_supplier) 
from solde
;
*/
/*
CREATE TABLE tmp_bal_aged (
  id         SERIAL NOT NULL, 
  create_on timestamp default now(), 
  PRIMARY KEY (id));
COMMENT ON TABLE tmp_bal_aged IS 'Table temporaire pour le calcul des balances agées';

CREATE TABLE tmp_bal_aged_child (
  tmp_bal_agedid bigint NOT NULL, 
  id              SERIAL NOT NULL, 
  f_id           bigint NOT NULL, 
  amount         numeric(20,4) NOT NULL, 
  amount30       numeric(20,4)  NOT NULL, 
  amount60       numeric(20,4) NOT NULL, 
  amount90       numeric(20,4) NOT NULL, 
  PRIMARY KEY (id));
COMMENT ON TABLE tmp_bal_aged_child IS 'Table temporaire pour le calcul des balances agées';
*/
CREATE TABLE tmp_bal_aged (
  id         SERIAL NOT NULL, 
  create_on timestamp default now(), 
  PRIMARY KEY (id));
COMMENT ON TABLE tmp_bal_aged IS 'Table temporaire pour le calcul des balances agées';

CREATE TABLE tmp_bal_aged_child
(
  id serial primary key,
  j_id bigint,
  j_date date,
  j_date_fmt text,
  jr_pj_number text,
  j_montant numeric(20,4),
  j_debit boolean,
  jr_comment text,
  jr_internal text,
  jr_id integer,
  jr_def_id integer,
  letter bigint,
  letter_diff numeric,
  date_part double precision,
  tmp_bal_agedid bigint NOT NULL, 

);
COMMENT ON TABLE tmp_bal_aged_child IS 'Table temporaire pour le calcul des balances agées';

