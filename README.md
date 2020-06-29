# Simple MVC application example

This is an example of a simple application using Model-View-Controller pattern.  The app requires a number of services, defined in `docker-compose.yml` and you can run it using docker-compose.

## docker-compose
`docker-compose` will look for `docker-compose.yml` file in the current directory, so make sure you execute the command from the right directory, when you bring the stack up and also down.

To bring the stack up, open terminal and navigate to this directory.

Execute: `docker-compose up -d`

This will start containers for all the services defined in `docker-compose.yml` file and create a custom network to interconnect them. However, you can see the output that services running in those containers produce by executing `docker-compose logs`. You can add `-f` flag to follow the logs as they are produced. To terminate this command, just press ctrl-c.

You can also check that the containers are running by executing `docker ps`.

Now you can navigate your browser to `http://localhost:8000` and see the web app running. If you want to watch only web server's logs, you can specify the service: `docker-compose logs -f web` (again ctrl-c to exit)..

You can also open `http://localhost:8080` to access PhpMyAdmin tool and explore the database. Alternatively, you can launch the command line mysql client on the **db** service: `docker-compose exec db mysql -proot`.
