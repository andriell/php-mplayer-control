<?php namespace Deployer;

// Servers
server('dev', '192.168.175.135')
    ->set('env', 'dev')
    ->user('mediacenter')
    ->password('password')
    ->set('deploy_path', '/srv/www/mediacenter');
