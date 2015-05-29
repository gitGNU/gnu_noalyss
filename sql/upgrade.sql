ALTER TABLE action    ALTER COLUMN ac_code TYPE character varying(30);

INSERT INTO action(ac_id, ac_description, ac_module, ac_code)
    VALUES (1110, 'Enlever une pièce justificative', 'compta', 'RMRECEIPT');
INSERT INTO action(ac_id, ac_description, ac_module, ac_code)
    VALUES (1120, 'Effacer une opération ', 'compta', 'RMOPER');
INSERT INTO action(ac_id, ac_description, ac_module, ac_code)
    VALUES (1210, 'Partager une note', 'note', 'SHARENOTE');
INSERT INTO action(ac_id, ac_description, ac_module, ac_code)
    VALUES (1220, 'Partager une note avec tout le monde ', 'note', 'SHARENOTEPUBLIC');
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
update menu_ref set me_menu = me_menu||' <span id="menu_'||lower(me_code)||'"><img src="image/empty.gif"></span>' where me_type='ME';
