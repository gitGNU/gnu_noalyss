begin;
alter table import_tmp alter montant numeric (20,4);
alter table import_tmp alter montant set default 0;
alter table import_tmp alter montant set not null;
alter table import_tmp alter code set not null;
alter table import_tmp alter date_exec set not null;
alter table import_tmp alter date_valeur set not null;

COMMENT ON TABLE import_tmp IS 'Table temporaire pour l''importation des banques en format CSV';
COMMENT ON COLUMN import_tmp.status IS 'Status w waiting, d delete t transfert'


alter table poste_analytique add column ga_id varchar (10);

CREATE or replace FUNCTION t_document_validate() RETURNS "trigger"
    AS $$
declare
  lText text;
  modified document%ROWTYPE;
begin
    	modified:=NEW;
	modified.d_filename:=replace(NEW.d_filename,' ','_');
	return modified;
end;
$$
    LANGUAGE plpgsql;


CREATE or replace FUNCTION t_document_type_insert() RETURNS "trigger"
    AS $$
declare
nCounter integer;
    BEGIN
select count(*) into nCounter from pg_class where relname='seq_doc_type_'||NEW.dt_id;
if nCounter = 0 then
        execute  'create sequence seq_doc_type_'||NEW.dt_id;
raise notice 'Creating sequence seq_doc_type_%',NEW.dt_id;
end if;
        RETURN NEW;
    END;
$$
    LANGUAGE plpgsql;

CREATE or replace FUNCTION t_document_modele_validate() RETURNS "trigger"
    AS $$
declare 
    lText text;
    modified document_modele%ROWTYPE;
begin
    modified:=NEW;

	modified.md_filename:=replace(NEW.md_filename,' ','_');
	return modified;
end;
$$
    LANGUAGE plpgsql;


CREATE TABLE groupe_analytique
(
  ga_id varchar(10) NOT NULL,
  ga_description text,
  CONSTRAINT pk_ga_id PRIMARY KEY (ga_id)
) ;


CREATE OR REPLACE FUNCTION group_analytic_ins_upd()
  RETURNS "trigger" AS
$BODY$
declare 
name text;
begin
raise notice 'poste_analytique_write';
name:=upper(NEW.ga_id);
name:=trim(name);
name:=replace(name,' ','');
NEW.ga_id:=name;
return NEW;
end;$BODY$
  LANGUAGE 'plpgsql' VOLATILE;

CREATE OR REPLACE FUNCTION group_analytique_del()
  RETURNS "trigger" AS
$BODY$
begin
update poste_analytique set ga_id=null
where ga_id=OLD.ga_id;
return OLD;
end;$BODY$
  LANGUAGE 'plpgsql' VOLATILE;

CREATE OR REPLACE FUNCTION poste_analytique_ins_upd()
  RETURNS "trigger" AS
$BODY$declare
name text;
rCount record;

begin
name:=upper(NEW.po_name);
name:=trim(name);
name:=replace(name,' ','');		
NEW.po_name:=name;

if NEW.ga_id is NULL then
return NEW;
end if;

if length(trim(NEW.ga_id)) = 0 then
  NEW.ga_id:=NULL;
  return NEW;
end if;
perform 'select ga_id from groupe_analytique where ga_id='||NEW.ga_id;
if NOT FOUND then
   raise exception' Inexistent Group Analytic %',NEW.ga_id;
end if;
return NEW;
end;$BODY$
  LANGUAGE 'plpgsql' VOLATILE;

CREATE OR REPLACE FUNCTION plan_analytic_ins_upd()
  RETURNS "trigger" AS
$BODY$
declare
   name text;
begin
   name:=upper(NEW.pa_name);
   name:=trim(name);
   name:=replace(name,' ','');
   NEW.pa_name:=name;
return NEW;
end;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE;

CREATE TRIGGER t_poste_analytique_ins_upd
  BEFORE INSERT OR UPDATE
  ON poste_analytique
  FOR EACH ROW
  EXECUTE PROCEDURE poste_analytique_ins_upd();

CREATE TRIGGER t_plan_analytique_ins_upd
  BEFORE INSERT OR UPDATE
  ON plan_analytique
  FOR EACH ROW
  EXECUTE PROCEDURE plan_analytic_ins_upd();

CREATE TRIGGER t_group_analytic_del
  before DELETE
  ON groupe_analytique
  FOR EACH ROW
  EXECUTE PROCEDURE group_analytique_del();

CREATE TRIGGER t_group_analytic_ins_upd
  BEFORE INSERT OR UPDATE
  ON groupe_analytique
  FOR EACH ROW
  EXECUTE PROCEDURE group_analytic_ins_upd();


drop TRIGGER t_upper_pa_name on plan_analytique;
drop TRIGGER t_upper_po_name on poste_analytique;
drop function upper_pa_name();
drop function upper_po_name();

CREATE TABLE bud_hypothese
(
  bh_id int4 NOT NULL,
  bh_name text NOT NULL,
  bh_saldo numeric(20,4) DEFAULT 0,
  bh_description text,
  pa_id int4,
  CONSTRAINT pk_bud_hypo PRIMARY KEY (bh_id),
  CONSTRAINT fk_bud_hypo_pa_id FOREIGN KEY (pa_id)
      REFERENCES plan_analytique (pa_id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
) 
WITHOUT OIDS;

create sequence seq_bud_hypothese_bh_id;

alter table bud_hypothese alter bh_id set default nextval('seq_bud_hypothese_bh_id');

--
-- Name: bud_card; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE bud_card (
    bc_id integer NOT NULL,
    bc_code character varying(10) NOT NULL,
    bc_description text,
    bc_price_unit numeric(20,4) DEFAULT 0.0 NOT NULL,
    bc_unit character varying(20),
    bh_id integer
);
--
-- Name: TABLE bud_card; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE bud_card IS 'card for budget module';


--
-- Name: COLUMN bud_card.bh_id; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON COLUMN bud_card.bh_id IS 'fk to  bud_hypothese';


--
-- Name: bud_card_bc_id_seq; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE bud_card_bc_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.bud_card_bc_id_seq OWNER TO phpcompta;

--
-- Name: bud_card_bc_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: phpcompta
--

ALTER SEQUENCE bud_card_bc_id_seq OWNED BY bud_card.bc_id;


--
-- Name: bc_id; Type: DEFAULT; Schema: public; Owner: phpcompta
--

ALTER TABLE bud_card ALTER COLUMN bc_id SET DEFAULT nextval('bud_card_bc_id_seq'::regclass);


--
-- Name: pk_bud_card_bc_id; Type: CONSTRAINT; Schema: public; Owner: phpcompta; Tablespace: 
--

ALTER TABLE ONLY bud_card
    ADD CONSTRAINT pk_bud_card_bc_id PRIMARY KEY (bc_id);


--
-- Name: uq_bud_card_bc_code; Type: CONSTRAINT; Schema: public; Owner: phpcompta; Tablespace: 
--

ALTER TABLE ONLY bud_card
    ADD CONSTRAINT uq_bud_card_bc_code_bh_id UNIQUE (bc_code,bh_id);

ALTER TABLE bud_card ADD CONSTRAINT fk_bud_hypo_bh_id FOREIGN KEY (bh_id) REFERENCES bud_hypothese (bh_id)
   ON UPDATE CASCADE ON DELETE CASCADE;
CREATE INDEX fki_bud_hypo_bh_id ON bud_card(bh_id);
ALTER TABLE bud_card ALTER COLUMN bh_id SET NOT NULL;



--
-- Name: bud_detail; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE bud_detail (
    bd_id integer NOT NULL,
    po_id integer,
    bc_id integer,
    bh_id integer,
    pcm_val poste_comptable
);


ALTER TABLE public.bud_detail OWNER TO phpcompta;

--
-- Name: TABLE bud_detail; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE bud_detail IS 'Detail for card ';


--
-- Name: COLUMN bud_detail.bd_id; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON COLUMN bud_detail.bd_id IS 'primary key';


--
-- Name: COLUMN bud_detail.po_id; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON COLUMN bud_detail.po_id IS 'FK to poste_analytique';


--
-- Name: COLUMN bud_detail.bc_id; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON COLUMN bud_detail.bc_id IS 'fk to bud_card';


--
-- Name: COLUMN bud_detail.pcm_val; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON COLUMN bud_detail.pcm_val IS 'fk to tmp_pcmn';


--
-- Name: bud_detail_bd_id_seq; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE bud_detail_bd_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.bud_detail_bd_id_seq OWNER TO phpcompta;

--
-- Name: bud_detail_bd_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: phpcompta
--

ALTER SEQUENCE bud_detail_bd_id_seq OWNED BY bud_detail.bd_id;


--
-- Name: bd_id; Type: DEFAULT; Schema: public; Owner: phpcompta
--

ALTER TABLE bud_detail ALTER COLUMN bd_id SET DEFAULT nextval('bud_detail_bd_id_seq'::regclass);


--
-- Name: pk_bud_detail; Type: CONSTRAINT; Schema: public; Owner: phpcompta; Tablespace: 
--

ALTER TABLE ONLY bud_detail
    ADD CONSTRAINT pk_bud_detail PRIMARY KEY (bd_id);


--
-- Name: fki_bud_card; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE INDEX fki_bud_card ON bud_detail USING btree (bc_id);


--
-- Name: fki_tmp_pcmn; Type: INDEX; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE INDEX fki_tmp_pcmn ON bud_detail USING btree (pcm_val);


--
-- Name: fk_bud_card; Type: FK CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY bud_detail
    ADD CONSTRAINT fk_bud_card FOREIGN KEY (bc_id) REFERENCES bud_card(bc_id) ON UPDATE CASCADE ON DELETE CASCADE;;

ALTER TABLE ONLY bud_detail
    add constraint fk_bud_hypothese_not_null FOREIGN KEY (bh_id) REFERENCES bud_hypothese(bh_id) ON UPDATE CASCADE ON DELETE CASCADE;;


--
-- Name: fk_tmp_pcmn; Type: FK CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY bud_detail
    ADD CONSTRAINT fk_tmp_pcmn FOREIGN KEY (pcm_val) REFERENCES tmp_pcmn(pcm_val) ON UPDATE CASCADE ON DELETE CASCADE;
--
-- Name: bud_detail_periode; Type: TABLE; Schema: public; Owner: phpcompta; Tablespace: 
--

CREATE TABLE bud_detail_periode (
    bdp_id integer NOT NULL,
    bdp_amount numeric(20,4) DEFAULT 0.0,
    p_id integer NOT NULL,
    bd_id integer
);


ALTER TABLE public.bud_detail_periode OWNER TO phpcompta;

--
-- Name: TABLE bud_detail_periode; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON TABLE bud_detail_periode IS 'Module budget detail by periode';


--
-- Name: COLUMN bud_detail_periode.p_id; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON COLUMN bud_detail_periode.p_id IS 'fk to parm_periode';


--
-- Name: COLUMN bud_detail_periode.bd_id; Type: COMMENT; Schema: public; Owner: phpcompta
--

COMMENT ON COLUMN bud_detail_periode.bd_id IS 'fk to bud_detail';


--
-- Name: bud_detail_periode_bdp_id_seq; Type: SEQUENCE; Schema: public; Owner: phpcompta
--

CREATE SEQUENCE bud_detail_periode_bdp_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.bud_detail_periode_bdp_id_seq OWNER TO phpcompta;

--
-- Name: bud_detail_periode_bdp_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: phpcompta
--

ALTER SEQUENCE bud_detail_periode_bdp_id_seq OWNED BY bud_detail_periode.bdp_id;


--
-- Name: bdp_id; Type: DEFAULT; Schema: public; Owner: phpcompta
--

ALTER TABLE bud_detail_periode ALTER COLUMN bdp_id SET DEFAULT nextval('bud_detail_periode_bdp_id_seq'::regclass);


--
-- Name: pk_budget_detail_period; Type: CONSTRAINT; Schema: public; Owner: phpcompta; Tablespace: 
--

ALTER TABLE ONLY bud_detail_periode
    ADD CONSTRAINT pk_budget_detail_period PRIMARY KEY (bdp_id);


--
-- Name: fk_bud_detail_bd_id; Type: FK CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY bud_detail_periode
    ADD CONSTRAINT fk_bud_detail_bd_id FOREIGN KEY (bd_id) REFERENCES bud_detail(bd_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: fk_parm_periode; Type: FK CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY bud_detail_periode
    ADD CONSTRAINT fk_parm_periode FOREIGN KEY (p_id) REFERENCES parm_periode(p_id) ON UPDATE CASCADE ON DELETE CASCADE;



CREATE OR REPLACE FUNCTION bud_detail_ins_upd()
  RETURNS "trigger" AS
$BODY$declare
mline bud_detail%ROWTYPE;
begin
mline:=NEW;
if mline.po_id = -1 then
   mline.po_id:=NULL;
end if;
return mline;
end;$BODY$
  LANGUAGE 'plpgsql' VOLATILE;


CREATE OR REPLACE FUNCTION bud_card_ins_upd()
  RETURNS "trigger" AS
$BODY$declare
 sCode text;
begin

sCode:=trim(upper(NEW.bc_code));
sCode:=replace(sCode,' ','_');
sCode:=substr(sCode,1,10);
raise notice 'sCode is %',sCode;
NEW.bc_code:=sCode;
return NEW;
end;$BODY$
  LANGUAGE 'plpgsql' VOLATILE;
ALTER FUNCTION bud_card_ins_upd() OWNER TO phpcompta;

--
-- Name: t_bud_card_ins_up; Type: TRIGGER; Schema: public; Owner: phpcompta
--

CREATE TRIGGER t_bud_card_ins_up
    BEFORE INSERT OR UPDATE ON bud_card
    FOR EACH ROW
    EXECUTE PROCEDURE bud_card_ins_upd();


--
-- Name: bud_hypothese_bh_id; Type: FK CONSTRAINT; Schema: public; Owner: phpcompta
--

ALTER TABLE ONLY bud_card
    ADD CONSTRAINT bud_hypothese_bh_id FOREIGN KEY (bh_id) REFERENCES bud_hypothese(bh_id) ON UPDATE CASCADE ON DELETE CASCADE;


CREATE TRIGGER t_bud_detail_ins_upd
  BEFORE INSERT OR UPDATE
  ON bud_detail
  FOR EACH ROW
  EXECUTE PROCEDURE bud_detail_ins_upd();



commit;	
