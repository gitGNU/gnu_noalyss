dropdb trunk_testdossier13
dropdb trunk_testmod1
dropdb trunk_testmod2
createdb -O trunk trunk_testdossier13
pg_restore -U trunk -O -v -d trunk_testdossier13 ~/Desktop/base\ de\ données/alchimerysdossier2010.bin 
createdb -O trunk trunk_testmod1
createdb -O trunk trunk_testmod2
pg_restore -U trunk -O -v -d trunk_testmod1 mod1.dmp
pg_restore -U trunk -O -v -d trunk_testmod2 mod2.dmp



