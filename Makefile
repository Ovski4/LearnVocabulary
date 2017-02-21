nginx_container_name = learn-vocabulary-nginx
php_container_name = learn-vocabulary-php
mysql_container_name =  learn-vocabulary-mysql

.PHONY: pac bash composer-add-github-token composer-update mysql-export mysql-import command

pac:
	docker exec -it $(php_container_name) bash -c "php app/console $(cmd); exit $$?"

bash:
	docker exec -it $(php_container_name) bash

composer-add-github-token:
	docker exec -t $(php_container_name) bash -c "composer config --global github-oauth.github.com $(token); exit $$?"

composer-update:
	docker exec -it $(php_container_name) bash -c "composer update; exit $$?"

mysql-export:
	docker exec -it $(mysql_container_name) bash -c "mysqldump -p'learn_vocabulary' -u learn_vocabulary learn_vocabulary" > $(path)

mysql-import:
	docker exec -i $(mysql_container_name) bash -c "mysql -p'learn_vocabulary' -u learn_vocabulary learn_vocabulary" < $(path)

command:
	docker exec -it $(php_container_name) bash -c "$(cmd); exit $$?"

default: pac
