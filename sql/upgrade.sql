update action set ac_description = 'Effacer un document de la comptabilité ou du suivi' where ac_id=1020;


CREATE TABLE todo_list_shared (id  serial primary key, todo_list_id int4 NOT NULL, use_login int4 NOT NULL, CONSTRAINT unique_todo_list_id_login 
    UNIQUE (todo_list_id, use_login));

ALTER TABLE todo_list_shared ADD CONSTRAINT fk_todo_list_shared_todo_list FOREIGN KEY (todo_list_id) REFERENCES todo_list (tl_id);

comment on table todo_list_shared is 'Note of todo list shared with other users';
comment on column todo_list_shared.todo_list_id is 'fk to todo_list';
comment on column todo_list_shared.use_login is 'user login';


