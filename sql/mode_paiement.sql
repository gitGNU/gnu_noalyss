-- Mode de paiement par journaux


alter table mod_payment add jrn_def_id bigint;
update mod_payment set jrn_def_id=2 where mp_type='VEN';
update mod_payment set jrn_def_id=3 where mp_type='ACH';

alter table mod_payment drop mp_type;


alter table mod_payment add constraint mod_payment_jrn_def_id_fk foreign key (jrn_def_id) references jrn_def(jrn_def_id) on delete cascade on update cascade;

comment on column mod_payment.jrn_def_id is 'Ledger using this payment method';
