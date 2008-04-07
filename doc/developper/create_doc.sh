doxygen
cd html; sed -i "s/iso-8859-1/utf-8/g"
postgresql_autodoc -h localhost -d mod1
postgresql_autodoc -h localhost -d account_repository
