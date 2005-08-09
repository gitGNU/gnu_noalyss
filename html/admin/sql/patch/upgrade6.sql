
--make sure that p_start < p_end 
ALTER TABLE parm_periode ADD CHECK (p_end >= p_start);


update  version set val=7;
