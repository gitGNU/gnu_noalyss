create or replace function today_hour() returns text as '
declare
hour text ;
begin
select to_char(now(),\'HHMI\') into hour;
return hour;
end;
' LANGUAGE PLPgSql;
