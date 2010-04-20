dropdb trunk_testdossier13
createdb -O trunk trunk_testdossier13
pg_restore -U trunk -O -v -d trunk_testdossier13 ~/Desktop/base\ de\ données/alchimerysdossier2009.bin 

