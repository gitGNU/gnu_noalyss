-- Check: valid_state
 ALTER TABLE audit_connect DROP CONSTRAINT valid_state;

ALTER TABLE audit_connect
  ADD CONSTRAINT valid_state CHECK (ac_state = 'FAIL'::text OR ac_state = 'SUCCESS'::text or ac_state='AUDIT');
