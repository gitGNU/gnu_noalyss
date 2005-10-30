#!/bin/bash
# clean all phpcompta related DB.

dropdb -U phpcompta -h localhost dev_account_repository
dropdb -U phpcompta -h localhost dev_dossier1
dropdb -U phpcompta -h localhost dev_mod1
dropdb -U phpcompta -h localhost dev_mod2

