Symfony Playground
==================

This is a stack for running Symfony 5.4, PHP 8.1 and for other containers (build prepared for ELK, RabbitMQ and XDebug).

# Installation

First, clone this repository:

```bash
$ git clone https://github.com/VenzDev/symfony-playground.git
```

Next, put your Symfony application into `symfony` (there is already playground application).


Change the database parameters in docker-compose.yml. You can also customize the ports as per your needs.

Then, run:

```bash
$ docker-compose up
```

Finally, your Symfony application is ready. `localhost:8000`

# XDebug


By default, `XDebug` runs in debug mode and on port `5902` (make sure the `IDE` is listening on this port).
You can change the XDebug parameters in the `build/php/XDebug.ini` file.

Note: Docker versions below 18.03.1 don't support the Docker variable `host.docker.internal`.  
