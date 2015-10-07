DROP TRIGGER fiche_detail_upd_trg ON fiche_detail;

CREATE TRIGGER fiche_detail_upd_trg
  after UPDATE
  ON fiche_detail
  FOR EACH ROW
  EXECUTE PROCEDURE comptaproc.fiche_detail_qcode_upd();
