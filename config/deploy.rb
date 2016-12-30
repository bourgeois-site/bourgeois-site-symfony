lock "3.7.1"

set :application, "bourgeois"
set :repo_url, "git@github.com:bourgeois-site/bourgeois-site-symfony.git"

set :deploy_to, "/home/deploy/#{fetch(:application)}"

set :linked_files, %w(app/config/parameters.yml)
set :linked_dirs, %w(var vendor)

set :keep_releases, 5
