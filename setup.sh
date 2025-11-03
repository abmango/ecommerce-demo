php_82="/opt/cloudlinux/alt-php82/root/usr/bin/php"
git pull origin master
$php_82 ../composer.phar install
$php_82 ../composer.phar dump-autoload --optimize
$php_82 artisan optimize:clear
$php_82 artisan migrate 
wget -qO- https://cdn.rawgit.com/creationix/nvm/master/install.sh | bash
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"
nvm install 20
npm install
npm run build