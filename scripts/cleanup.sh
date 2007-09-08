#!/bin/bash
# clean all phpcompta related DB.
DOMAIN="testing"
export PGPASSWORD="dany"
export PGUSER="phpcompta"
export PGHOST=localhost
dropdb  ${DOMAIN}account_repository
dropdb  ${DOMAIN}dossier1
dropdb   ${DOMAIN}dossier3
dropdb   ${DOMAIN}dossier4
dropdb   ${DOMAIN}dossier5
dropdb   ${DOMAIN}dossier13

dropdb   ${DOMAIN}mod1
dropdb   ${DOMAIN}mod2
dropdb   ${DOMAIN}mod3
dropdb   ${DOMAIN}mod7

