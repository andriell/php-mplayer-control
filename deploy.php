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

add('shared_files', [
    'config/local/users.php',
]);
add('shared_dirs', [
    //'node_modules',
    'public/dir-img',
]);
add('writable_dirs', [
    'public/dir-img',
]);

// deploy:failed
after('deploy:failed', 'deploy:unlock');

// deploy:release
task('deploy:release_path', function () {
    writeln('Release path: ' . get('release_path'));
});
after('deploy:release', 'deploy:release_path');

// deploy:shared
task('deploy:shared:my', function () {
    $stage = input()->getArgument('stage');
    if ($stage == 'prod') {
        run('ln -s {{release_path}}/shell/mplayer_run_prod.sh {{release_path}}/shell/mplayer_run.sh');
    } elseif ($stage == 'dev') {
        run('ln -s {{release_path}}/shell/mplayer_run_dev.sh {{release_path}}/shell/mplayer_run.sh');
    }
});
after('deploy:shared', 'deploy:shared:my');

// deploy:vendors
task('npm:install', function () {
    run('cd {{release_path}} && npm install');
});
task('npm:build', function () {
    $stage = input()->getArgument('stage');
    if ($stage == 'prod') {
        run('cd {{release_path}} && npm run production');
    } elseif ($stage == 'dev') {
        run('cd {{release_path}} && npm run development');
    }
});
//after('deploy:vendors', 'npm:install');
after('npm:install', 'npm:build');

include('deploy_servers.php');
