doxygen
cd html; sed -i "s/utf-8/utf-8/g" *
postgresql_autodoc -h localhost -d mod1
postgresql_autodoc -h localhost -d account_repository
