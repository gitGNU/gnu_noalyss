#!/bin/bash
# clean all phpcompta related DB.

dropdb -U phpcompta -h localhost account_repository
dropdb -U phpcompta -h localhost dossier1
dropdb -U phpcompta -h localhost dossier13
dropdb -U phpcompta -h localhost mod1
dropdb -U phpcompta -h localhost mod2

