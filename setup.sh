php ../composer.phar install
php ../composer.phar dump-autoload --optimize
php artisan optimize:clear
php artisan migrate 
wget -qO- https://cdn.rawgit.com/creationix/nvm/master/install.sh | bash
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"
nvm install 20
npm install
npm run build