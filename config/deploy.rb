lock "3.7.1"

set :application, "bourgeois"
set :repo_url, "git@github.com:bourgeois-site/bourgeois-site-symfony.git"

set :deploy_to, "/home/deploy/#{fetch(:application)}"

set :linked_files, %w(app/config/parameters.yml)
set :linked_dirs, %w(var vendor)

set :keep_releases, 5

set :symfony_env, 'prod'
set :symfony_directory_structure, 3

set :app_config_path, 'app/config'
set :log_path, 'var/logs'
set :cache_path, 'var/cache'

set :symfony_console_path, 'bin/console'
set :symfony_console_flags, '--no-debug'
set :controllers_to_clear, ['app_*.php', 'config.php']

after 'deploy:symlink:release', 'symfony:clear_cache'
before 'deploy:cleanup', 'symfony:dump_assets'

namespace :symfony do
  task :clear_cache do
    on roles(:web) do
      symfony_console "cache:clear", "--env=prod --no-debug"
    end
  end

  task :dump_assets do
    on roles(:web) do
      symfony_console "assetic:dump", "--env=prod"
    end
  end
end
