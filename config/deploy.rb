lock "3.7.1"

set :application, "bourgeois"
set :repo_url, "git@github.com:bourgeois-site/bourgeois-site-symfony.git"

set :deploy_to, "/home/deploy/#{application}"

set :linked_dirs, %w(var/logs app/parameters.yml)

set :keep_releases, 5
