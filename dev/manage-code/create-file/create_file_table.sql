SELECT 
  columns.column_name,columns.data_type
FROM 
  information_schema.columns
WHERE 
 columns.table_schema='syndicat'
 and columns.table_name='vw_historic';

