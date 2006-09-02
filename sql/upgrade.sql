CREATE or replace  FUNCTION insert_quant_sold
	(p_internal text, 
	p_fiche character varying, 
	p_quant integer, 
	p_price numeric, 
	p_vat numeric, 
	p_vat_code integer, 
	p_client character varying) 
RETURNS void
    AS $$
declare 
	fid_client integer;
	fid_good   integer;
begin
	select f_id into fid_client from 
		attr_value join jnt_fic_att_value using (jft_id) where ad_id=23 and av_text=upper(p_client);

	select f_id into fid_good from 
		attr_value join jnt_fic_att_value using (jft_id) where ad_id=23 and av_text=upper(p_fiche);


	insert into quant_sold
		(qs_internal,qs_fiche,qs_quantite,qs_price,qs_vat,qs_vat_code,qs_client) 
	values
		(p_internal,fid_good,p_quant,p_price,p_vat,p_vat_code,fid_client);
	return;
end;	
$$
    LANGUAGE plpgsql;

