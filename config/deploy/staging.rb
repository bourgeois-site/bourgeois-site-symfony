set :deploy_to, "/home/deploy/#{fetch(:application)}"

role :web, %w(deploy@pubvag)
