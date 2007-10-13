delete from jrn where jr_internal is null;
 delete from jrnx where j_grpt not in (select jr_grpt_id from jrn);
