-- Trigger: t_tmp_pcm_alphanum_ins_upd on tmp_pcmn

-- DROP TRIGGER t_tmp_pcm_alphanum_ins_upd ON tmp_pcmn;

CREATE TRIGGER t_tmp_pcm_alphanum_ins_upd
  BEFORE INSERT OR UPDATE
  ON tmp_pcmn
  FOR EACH ROW
  EXECUTE PROCEDURE comptaproc.tmp_pcmn_alphanum_ins_upd();
