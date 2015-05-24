VocabularyLearner
=================

http://learn-vocabulary.com

Requirements
------------

 * linux (tested on ubuntu 14.04)
 * docker
 * docker-compose

Getting started
---------------

./run.sh
docker exec -it learnvocabulary_app_1 /usr/local/bin/composer self-update && composer update
sudo chmod 775 . -R && sudo chown $USER:www-data . -R
php app/console assets:install --symlink
