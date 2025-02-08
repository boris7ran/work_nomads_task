## Overview

Symfony API for authentication using FusionAuth with React as a client

## Perquisites

### Docker

Implemented with docker v27, earlier versions should be fine also

## Set up

1. Copy [compose.yml.dist](compose.yml.dist) to [compose.yml](compose.yml)
2. Create docker network using `docker network create work_nomads_task`
3. Build images and spin up the containers using `docker compose up -d`
4. Following [FusionAuth guide](https://fusionauth.io/docs/quickstarts/5-minute-docker#2-complete-the-setup-wizard) setup the app using wizard on [localhost:9011](http://localhost:9011)
5. Create an application on FusionAuth
   1. For application to work JWT should be enabled in the application with refresh token ![JWT Config](/docs/jwt_config.png "JWT Config ")
6. Add the application ID to [.env](.env) variable `FUSIONAUTH_APPLICATION_ID`
7. Add a new API Key and copy the Key field in FusionAuth [http://localhost:9011/admin/api-key/add](http://localhost:9011/admin/api-key/add) to `FUSIONAUTH_API_KEY` [.env](.env) variable
8. Run `docker exec -it work_nomads_task-php composer install`
9. Run commands for creating a Webhook and Google IDP in FusionAuth
    1. Create Webhook: `docker exec -it work_nomads_task-php ./bin/console app:create-fusionauth-webhook`
       1. The webhook will process only `user.create` and `user.login.failed` FusionAuth event
    2. Create Google IDP: `docker exec -it work_nomads_task-php ./bin/console app:create-fusionauth-google-idp`
       1. The IDP is configured to run with my Google Cloud Client credentials, this is not necessary but providing correct redirect uris is mandatory `http://localhost:8080/oauth/check`
    3. Allow applications to authenticate using IDP http://localhost:9011/admin/identity-provider/
    4. Alternatively enter the docker container `docker exec -it work_nomads_task-php bash` and execute the commands there

## Notes

- If some of the hosts ports are occupied/changed the changes will need to reflect some of the .env vars
   - FusionAuth .env vars are stored in [/docker/fusionauth/.env](docker/fusionauth/.env)
- The authentication is done using cookies
- If files cannot be changed on host machine change the owner using `sudo chown -R <user>:<group> .` from project root
- You can find the Postman collection [here](Work Nomads.postman_collection.json)

## Link

- [Symfony app](http://localhost:8080)
- [Symfony Swagger Docs](http://localhost:8080/api/doc)
- [FusionAuth](http://localhost:9011)
- [React](http://localhost:3000)
- [Mailhog](http://localhost:8025)