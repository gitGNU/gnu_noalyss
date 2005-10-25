
create table user_global_pref (
        user_id text,
        parameter_type text,
        parameter_value text
);
comment on table user_global_pref is 'The user''s global parameter ';
comment on column user_global_pref.user_id is 'user''s login ';
comment on column user_global_pref.parameter_type is 'the type of parameter ';
comment on column user_global_pref.parameter_value is 'the value of parameter ';

alter table user_global_pref add constraint pk_user_global_pref primary key (user_id,parameter_type);

insert into user_global_pref select use_login,'PAGESIZE','50' from ac_users;
update version set val=7;

