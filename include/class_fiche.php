<?
class fiche {
  var $cn;// database connection
  var $a_attribut; // array of attribut
  var $id; // fiche.f_id

  function fiche($p_cn,$p_id=0) {
    $this->cn=$p_cn;
    $this->id=$p_id;
  }

  function getAttribut() {
    $sql="select * 
          from jnt_fic_att_value 
              natural join fiche 
              natural join attr_value natural 
              join attr_def where f_id=".$this->id;
    $Ret=ExecSql($this->cn,$sql);
    if ( ($Max=pg_NumRows($Ret)) == 0 )
      return null;
    for ($i=0;$i<$Max;$i++) {
      $row=pg_fetch_array($Ret,$i);
      $this->ad_id[$i]=$row['ad_id'];
      $this->jft_id[$i]=$row['jft_id'];
      $this->fd_id[$i]=$row['fd_id'];
      $this->description[$i]=$row['av_text'];
      $this->libelle[$i]=$row['ad_text'];
    }
  }

  function size() {
    if ( isset ($this->ad_id))
      return sizeof($this->ad_id);
    else
      return 0;
  }
    
}
?>