FROM ovski/symfony2:prod
ADD .   /var/www
RUN chmod 770 -R /var/www && chown www-data -R /var/www
WORKDIR /var/www
RUN rm -r app/cache/*
RUN composer install
RUN php app/console assets:install
RUN chmod 770 -R /var/www && chown www-data -R /var/www 
