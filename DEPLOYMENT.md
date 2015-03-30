# Last Wishes - DDD Sample Application

## Deployment with Ansistrano

Last Wishes uses as an deployment example Ansistrano. For more info, please check: https://github.com/ansistrano

Current configuration is a deployment:

 * Using `rsync`
 * Deploying to `/var/www/lastwishes` folder
 * Deploying to a server called `quepimquepam.com` using `root` user

## Try it on your server

`ansible-playbook -i hosts deploy-with-ansistrano.yml`

