-- Name: plpgsql_call_handler (); Type: FUNCTION; Schema: public; Owner: dany
--

CREATE FUNCTION plpgsql_call_handler () RETURNS language_handler
    AS '$libdir/plpgsql', 'plpgsql_call_handler'
    LANGUAGE c;
-- Name: today_hour (); Type: FUNCTION; Schema: public; Owner: dany
--

CREATE FUNCTION today_hour () RETURNS text
    AS '
declare
hour text ;
begin
select to_char(now(),''HHMI'') into hour;
return hour;
end;
'
    LANGUAGE plpgsql;
