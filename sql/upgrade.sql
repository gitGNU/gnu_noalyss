-- alter table formdef add fr_type char(1) default null;
-- comment on column formdef.fr_type is 'Give the type of the form : Normal, User page';

alter table form drop constraint "$1";
alter table form add constraint    formdef_fk foreign key(fo_fr_id) references formdef(fr_id) on delete cascade;