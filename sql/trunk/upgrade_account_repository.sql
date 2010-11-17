--
-- PostgreSQL database dump
--

SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: audit_connect; Type: TABLE; Schema: public; Owner: danydb; Tablespace: 
--

CREATE TABLE audit_connect (
    ac_id integer NOT NULL,
    ac_user text,
    ac_date timestamp without time zone DEFAULT now(),
    ac_ip text,
    ac_module text,
    ac_state text,
    CONSTRAINT valid_state CHECK (((ac_state = 'FAIL'::text) OR (ac_state = 'SUCCESS'::text)))
);


ALTER TABLE public.audit_connect OWNER TO danydb;

--
-- Name: audit_connect_ac_id_seq; Type: SEQUENCE; Schema: public; Owner: danydb
--

CREATE SEQUENCE audit_connect_ac_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.audit_connect_ac_id_seq OWNER TO danydb;

--
-- Name: audit_connect_ac_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: danydb
--

ALTER SEQUENCE audit_connect_ac_id_seq OWNED BY audit_connect.ac_id;


--
-- Name: ac_id; Type: DEFAULT; Schema: public; Owner: danydb
--

ALTER TABLE audit_connect ALTER COLUMN ac_id SET DEFAULT nextval('audit_connect_ac_id_seq'::regclass);


--
-- Name: audit_connect_pkey; Type: CONSTRAINT; Schema: public; Owner: danydb; Tablespace: 
--

ALTER TABLE ONLY audit_connect
    ADD CONSTRAINT audit_connect_pkey PRIMARY KEY (ac_id);


--
-- Name: audit_connect_ac_user; Type: INDEX; Schema: public; Owner: danydb; Tablespace: 
--

CREATE INDEX audit_connect_ac_user ON audit_connect USING btree (ac_user);


--
-- Name: cut_user_trg; Type: TRIGGER; Schema: public; Owner: danydb
--

CREATE TRIGGER cut_user_trg
    BEFORE INSERT OR UPDATE ON audit_connect
    FOR EACH ROW
    EXECUTE PROCEDURE limit_user();


--
-- Name: audit_connect; Type: ACL; Schema: public; Owner: danydb
--

REVOKE ALL ON TABLE audit_connect FROM PUBLIC;
REVOKE ALL ON TABLE audit_connect FROM danydb;
GRANT ALL ON TABLE audit_connect TO danydb;
GRANT ALL ON TABLE audit_connect TO phpcompta;


--
-- Name: audit_connect_ac_id_seq; Type: ACL; Schema: public; Owner: danydb
--

REVOKE ALL ON SEQUENCE audit_connect_ac_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE audit_connect_ac_id_seq FROM danydb;
GRANT ALL ON SEQUENCE audit_connect_ac_id_seq TO danydb;
GRANT ALL ON SEQUENCE audit_connect_ac_id_seq TO phpcompta;


--
-- PostgreSQL database dump complete
--

