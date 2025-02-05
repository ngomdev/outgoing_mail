<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'https://github.com/SOSEN-Afrique/orbus_courier_server.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('https://vps90542.serveur-vps.net:8004')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '~/server_orbus_courier');

// Hooks

after('deploy:failed', 'deploy:unlock');
