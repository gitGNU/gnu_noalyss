--
-- PostgreSQL database dump
--

SET search_path = public, pg_catalog;

--
-- TOC entry 2 (OID 679385)
-- Name: log; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE log (
    lg_timestamp timestamp without time zone DEFAULT now(),
    lg_file text,
    lg_type text DEFAULT 'debug',
    lg_line text,
    lg_msg text
);


--
-- TOC entry 3 (OID 680272)
-- Name: last_log; Type: VIEW; Schema: public; Owner: dany
--

CREATE VIEW last_log AS
    SELECT log.lg_timestamp, log.lg_file, log.lg_type, log.lg_line, log.lg_msg FROM log WHERE ((log.lg_timestamp)::timestamp with time zone > (now() - (reltime('00:05'::interval))::interval));


