set :deploy_to, "/home/maxim/#{fetch(:application)}"

role :web, %w(maxim@bourgeois.makkuzin.name)
