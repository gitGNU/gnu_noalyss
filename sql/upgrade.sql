alter table formdef add fr_type char(1) default null;
comment on column formdef.fr_type is 'Give the type of the form : Normal, User page';
