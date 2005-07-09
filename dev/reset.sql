\t
\o reset_seq_jrn.sql
select 'select setval (''s_jrn_'||jrn_def_id||''',1,false);' from jrn_def;
\o
\t

\i reset_seq_jrn.sql
select setval('s_centralized',1,false);
select setval('s_central',1,false);
delete from centralized;
update jrnx set j_centralized='f';
select setval('s_central_order',1,false);

