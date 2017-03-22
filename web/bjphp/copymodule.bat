mkdir %1
cd %1
copy d:\kailebao_svn\bjphp\index.php
copy d:\kailebao_svn\bjphp\.htaccess

mkdir api
mkdir feature
mkdir meta
mkdir policy
mkdir static
mkdir tpl
mkdir web

junction log d:\kailebao-runtime\log
junction attachment d:\kailebao-runtime\attachment
junction core d:\kailebao_svn\bjphp\core
junction bjui d:\kailebao_svn\bjphp\bjui
junction vendor d:\kailebao_svn\bjphp\vendor

copy d:\kailebao_svn\bjphp\web\index.php web\index.php

junction api\article d:\kailebao_svn\module\article\api\article
junction feature\article d:\kailebao_svn\module\article\feature\article
junction meta\article d:\kailebao_svn\module\article\meta\article
junction web\article d:\kailebao_svn\module\article\web\article
junction tpl\article d:\kailebao_svn\module\article\tpl\article

junction api\console d:\kailebao_svn\module\console\api\console
junction meta\console d:\kailebao_svn\module\console\meta\console
junction web\console d:\kailebao_svn\module\console\web\console
junction tpl\console d:\kailebao_svn\module\console\tpl\console

junction api\org d:\kailebao_svn\module\org\api\org
junction meta\org d:\kailebao_svn\module\org\meta\org
junction web\org d:\kailebao_svn\module\org\web\org
junction tpl\org d:\kailebao_svn\module\org\tpl\org

junction api\basedata d:\kailebao_svn\module\basedata\api\basedata
junction meta\basedata d:\kailebao_svn\module\basedata\meta\basedata
junction web\basedata d:\kailebao_svn\module\basedata\web\basedata
junction tpl\basedata d:\kailebao_svn\module\basedata\tpl\basedata

cd d:\kailebao_svn\bjphp