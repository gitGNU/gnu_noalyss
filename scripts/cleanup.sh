#!/bin/bash
# clean all phpcompta related DB.
DOMAIN="test_"
dropdb -U phpcompta -h localhost ${DOMAIN}account_repository
dropdb -U phpcompta -h localhost ${DOMAIN}dossier1
dropdb -U phpcompta -h localhost ${DOMAIN}dossier3
dropdb -U phpcompta -h localhost ${DOMAIN}dossier4
dropdb -U phpcompta -h localhost ${DOMAIN}dossier5
dropdb -U phpcompta -h localhost ${DOMAIN}dossier13

dropdb -U phpcompta -h localhost ${DOMAIN}mod1
dropdb -U phpcompta -h localhost ${DOMAIN}mod2
dropdb -U phpcompta -h localhost ${DOMAIN}mod3
dropdb -U phpcompta -h localhost ${DOMAIN}mod7

