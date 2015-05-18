INSERT INTO action(
            ac_id, ac_description, ac_module, ac_code)
    VALUES (1110, 'Enlever une pièce justificative', 'compta', 'RMRECEIPT');
INSERT INTO action(
            ac_id, ac_description, ac_module, ac_code)
    VALUES (1120, 'Effacer une opération ', 'compta', 'RMOPER');


CREATE TABLE todo_list_shared (id  serial primary key, todo_list_id int4 NOT NULL, use_login int4 NOT NULL, CONSTRAINT unique_todo_list_id_login 
    UNIQUE (todo_list_id, use_login));

ALTER TABLE todo_list_shared ADD CONSTRAINT fk_todo_list_shared_todo_list FOREIGN KEY (todo_list_id) REFERENCES todo_list (tl_id);

comment on table todo_list_shared is 'Note of todo list shared with other users';
comment on column todo_list_shared.todo_list_id is 'fk to todo_list';
comment on column todo_list_shared.use_login is 'user login';


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