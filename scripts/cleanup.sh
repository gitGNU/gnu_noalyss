#! /bin/bash
# clean all phpcompta related DB.

dropdb -U phpcompta -h localhost account_repository
dropdb -U phpcompta -h localhost dossier1
