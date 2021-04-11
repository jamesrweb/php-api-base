# API Base

[![Infection MSI](https://img.shields.io/endpoint?style=for-the-badge&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fjamesrweb%2Fphp-api-base%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/jamesrweb/php-api-base/master)

This is a repository for starting a new project with Slim, Nginx, PHP-FPM & MySQL.

## Local development

All docker configurations are held within the `docker` directory. You can run the project using `docker-compose` from the project root with:

```sh
    docker-compose -f docker/docker-compose.yml up -d
```

This will setup `php-fpm`, `nginx` and `mysql` in containers while also mounting the project's app/ folder into a volume at `/var/www`.

You will be able to access the project via `localhost:80` when the containers are running.
