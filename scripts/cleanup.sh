#!/bin/bash
# clean all phpcompta related DB.

dropdb -U phpcompta -h localhost test_account_repository
dropdb -U phpcompta -h localhost test_dossier1
dropdb -U phpcompta -h localhost test_dossier13
dropdb -U phpcompta -h localhost test_mod1
dropdb -U phpcompta -h localhost test_mod2

