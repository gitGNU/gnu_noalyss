--
-- PostgreSQL database dump
--

SET search_path = public, pg_catalog;

--
-- TOC entry 2 (OID 51704)
-- Name: tva_rate; Type: TABLE; Schema: public; Owner: phpcompta
--

CREATE TABLE tva_rate (
    tva_id integer NOT NULL,
    tva_label text NOT NULL,
    tva_rate double precision DEFAULT 0.0 NOT NULL,
    tva_comment text,
    tva_poste text
);


--
-- Data for TOC entry 3 (OID 51704)
-- Name: tva_rate; Type: TABLE DATA; Schema: public; Owner: phpcompta
--

INSERT INTO tva_rate VALUES (1, '21%', 0.21, 'Tva applicable à tout ce qui bien et service divers', '411,451');
INSERT INTO tva_rate VALUES (3, '0%', 0, 'Tva applicable lors de vente/achat intracommunautaire', '411,451');
INSERT INTO tva_rate VALUES (2, '6%', 0.06, 'Tva applicable aux journaux et livres', '411,451');


