SELECT 
  columns.column_name,columns.data_type
FROM 
  information_schema.columns
WHERE 
 columns.table_name='member_record';

