<?php namespace Deployer;

include('recipe/laravel.php');

// Configuration
set('keep_releases', 10);
set('ssh_type', 'phpseclib');
set('ssh_multiplexing', false);
set('writable_mode', 'chmod');
set('writable_chmod_mode', '0777');

set('repository', 'https://github.com/andriell/php-mplayer-control');
set('composer_options', '{{composer_action}} --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader');

after('deploy:failed', 'deploy:unlock');

task('npm:build', function () {
    run('npm install');
    run('npm run production');
});
after('deploy:writable', 'npm:build');

include('deploy_servers.php');
