FROM ovski/symfony2:prod
ADD .   /var/www
WORKDIR /var/www
RUN php app/console assets:install
RUN rm -r app/cache/*
RUN chmod 770 -R /var/www && chown www-data -R /var/www 
