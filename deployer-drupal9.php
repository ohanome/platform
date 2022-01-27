<?php
namespace Deployer;

require 'recipe/drupal8.php';

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

//Drupal 8 shared dirs
set('shared_dirs', [
  'web/sites/{{drupal_site}}/files',
]);

//Drupal 8 shared files
set('shared_files', [
  'web/sites/{{drupal_site}}/settings.php',
  'web/sites/{{drupal_site}}/services.yml',
  '.env',
]);

//Drupal 8 Writable dirs
set('writable_dirs', [
  'web/sites/{{drupal_site}}/files',
]);

set('writable_mode', 'chmod');

set('clear_paths', [
  'README.md',
  'COPYRIGHT.md',
  'deploy.php',
  '.editorconfig',
  'phpunit.xml.dist',
  'db',
  'export',
  '.gitattributes',
  '.gitignore',
  'composer.json',
  'composer.lock',
  'phpunit.xml.dist',
  '.env.example',
  '.lando.yml',
  'web/sites/development.services.yml',
  'web/sites/sites.local.php',
  'web/sites/{{drupal_site}}/settings.local.php',
]);

task('deploy:update_code', function () {
  run('git archive --remote {{repository}} --format tar {{branch}} | (cd {{release_path}} && tar xf -)');
});

/**
 * We replace the default release_name with a date based.
 */
set('release_name', function () {
  return date('Y-m-d-H-i-s');
});

task('deploy', [
  'deploy:info',
  'deploy:prepare',
  'deploy:lock',
  'deploy:release',
  'deploy:update_code',
  'deploy:vendors',
  'deploy:shared',
  'deploy:writable',
  'deploy:clear_paths',
  'deploy:symlink',
  'deploy:unlock',
  'cleanup'
]);

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
