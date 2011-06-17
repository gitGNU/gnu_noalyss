delete from fiche_detail where jft_id in (
    select a.jft_id
from fiche_detail as a ,fiche_detail as b
where
a.f_id=b.f_id
and a.ad_id = b.ad_id
and a.jft_id > b.jft_id);


create unique index fiche_Detail_f_id_ad_id on fiche_detail (f_id,ad_id);

