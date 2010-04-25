drop table if exists letter_deb;
drop table if exists letter_cred;
drop table if exists jnt_letter cascade;
create table jnt_letter(
	jl_id serial not null,
	jl_amount_deb numeric(20,4),
	constraint jnt_letter_pk primary key (jl_id)
	);
create table letter_deb (
	ld_id serial,
	j_id bigint not null,
	jl_id bigint not null,
	constraint letter_deb_pk primary key (ld_id),
	constraint letter_deb_fk foreign key (j_id) references jrnx(j_id) on update cascade on delete cascade,
	constraint jnt_deb_fk foreign key (jl_id) references jnt_letter(jl_id) on update cascade on delete cascade
	);

create table letter_cred (
	lc_id serial,
	j_id bigint not null,
	jl_id bigint not null,
	constraint letter_cred_pk primary key (lc_id),
	constraint letter_cred_fk foreign key (j_id) references jrnx(j_id) on update cascade on delete cascade,
	constraint jnt_cred_fk foreign key (jl_id) references jnt_letter(jl_id) on update cascade on delete cascade
	);
	

alter table jnt_letter owner to trunk;
alter table letter_deb owner to trunk;
alter table letter_cred owner to trunk;