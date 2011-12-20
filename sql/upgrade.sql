update jrnx set j_text = null from jrn where jr_grpt_id=j_grpt and j_text=jr_comment;
insert into parameter (pr_id,pr_value) values ('MY_UPDLAB','N');
