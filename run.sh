#!/bin/bash

# A script to run a php-mysql project quickly
# Edit the uppercase words

##########
# Colors #
##########

red='\033[0;31m'
nocolor='\033[0m'
black='\033[0;30m'
blue='\033[0;34m'
green='\033[0;32m'
cyan='\033[0;36m'
purple='\033[0;35m'
orange='\033[0;33m'
lightgray='\033[0;37m'
darkgray='\033[1;30m'
lightblue='\033[1;34m'
lightgreen='\033[1;32m'
lightcyan='\033[1;36m'
lightred='\033[1;31m'
lightpurple='\033[1;35m'
yellow='\033[1;33m'
white='\033[1;37m'

#############
# Variables #
#############

#global
scriptpath="`dirname \"$0\"`"
scriptpath="`( cd \"$scriptpath\" && pwd )`"

# mysql
dbuser="ovski_lv"
userpass="ovski_lv"
dbname="ovski_lv"
dumppath="$scriptpath/docs/dumps/sf2_learn_voc.sql"
mysqlvolume="/var/docker/mysql/learn-vocabulary"

# elk
elkvolume="/var/docker/elk/learn-vocabulary"
elkcontainer="learnvocabulary_elk_1"
kibanajson="$scriptpath/kibana.json"

# The main function
main() {
    echo "---------------------------------------------------"
    addHostEntry "dev.learn-vocabulary.com"
    addHostEntry "dev.adminer.learn-vocabulary.com"
    addHostEntry "dev.logs.learn-vocabulary.com"
    initMysql
    initELK
    docker-compose up -d
    echo "---------------------------------------------------"
    printf "Your root directory is at ${orange}$scriptpath${nocolor}\n"
    printf "You can access your project at ${orange}http://dev.learn-vocabulary.com/app_dev.php${nocolor}\n"
    printf "You can see your logs at ${orange}http://dev.logs.learn-vocabulary.com${nocolor}\n"
    printf "You can access adminer at ${orange}http://dev.adminer.learn-vocabulary.com${nocolor}\n"
    printf "Adminer credentials :\n"
    printf "    server   : ${orange}mysql${nocolor}\n"
    printf "    login    : ${orange}root${nocolor}\n"
    printf "    password : ${orange}dev${nocolor}\n"
}

#############
# Functions #
#############

# Add an host entry
# $1 : domain
addHostEntry () {
    domain=$1;
    success=0
    filename=/etc/hosts
    hostline="127.0.0.1        $domain"
    # Determine if the line already exists in /etc/hosts
    grep -q "$domain" "$filename"  # -q is for quiet

    # Grep's return error code can then be checked. No error=success
    if ! [ $? -eq $success ]; then
      # If the line wasn't found, add it using an printf append >>
      sudo bash -c "echo '$hostline' >> $filename"
      printf "The entry $domain was added to $filename\n"
    fi
}

# insert visualization and dashboard in elasticsearch cluster
initELK () {
    if ! [ -d "$elkvolume/logstash" ]; then
        printf "Creating temporary elk container...\n"
        createELKContainer > /dev/null 2>&1
        printf " * Inserting Kibana dashboard and visualizations\n"
        insertKibanaData > /dev/null 2>&1
        printf "Removing temporary container\n"
        removeELKContainer > /dev/null 2>&1
    fi
}

# create a temporary elk container
createELKContainer () {
    docker run -d --name elkcontainer -v $elkvolume:/data -v $kibanajson:/kibana.json ovski/elk:elasticdump
    sleep 20 # wait for elasticsearch process to start
}

insertKibanaData () {
    # echo | --> http://stackoverflow.com/questions/6264596/simulating-enter-keypress-in-bash-script
    echo |docker exec -i elkcontainer bash -c "curl -XPUT 'http://localhost:9200/.kibana/'"
    echo |docker exec -i elkcontainer bash -c "elasticdump --input=/kibana.json --output=http://localhost:9200/.kibana --type=data"
}

# remove the temporary mysql container
removeELKContainer() {
    docker stop elkcontainer
    docker rm elkcontainer
}


# init mysql container to add data in a volume
initMysql () {
    if ! [ -d "$mysqlvolume/$dbname" ]; then
        printf "Creating temporary mysql container...\n"
        createMysqlContainer > /dev/null 2>&1
        printf "Creating a database\n"
        createDatabase > /dev/null 2>&1
        printf "Importing a dump\n"
        importDump > /dev/null 2>&1
        #printf "Editing wordpress url\n"
        #editWordpressUrl "http://www.PROJECT_NAME.fr" "http://dev.PROJECT_NAME.fr" > /dev/null 2>&1
        printf "Removing temporary container\n"
        removeMysqlContainer > /dev/null 2>&1
    fi
}

# create a temporary mysql container
createMysqlContainer () {
    docker run -d -p 33069:3306 -e MYSQL_ROOT_PASSWORD=dev --name mysqlcontainer -v $mysqlvolume:/var/lib/mysql mysql:latest
    sleep 20
}

# remove the temporary mysql container
removeMysqlContainer() {
    docker stop mysqlcontainer > /dev/null
    docker rm mysqlcontainer > /dev/null
}

# create a database
createDatabase () {
    mysql -h 127.0.0.1 -u root -P 33069 -p'dev' -e "CREATE USER '$dbuser'@'%' IDENTIFIED BY '$userpass';"
    mysql -h 127.0.0.1 -u root -P 33069 -p'dev' -e "CREATE DATABASE IF NOT EXISTS $dbname DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;"
    mysql -h 127.0.0.1 -u root -P 33069 -p'dev' -e "GRANT SELECT, LOCK TABLES, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER ON $dbname.* TO '$dbuser'@'%';"
}

# import a dump
importDump () {
    mysql -h 127.0.0.1 -u root -P 33069 -p'dev' $dbname < $dumppath
}

# edit the wordpress website url
# $1 old url
# $2 new url
editWordpressUrl () {
    oldurl=$1
    newurl=$2
    mysql -h 127.0.0.1 -u root -P 33069 -p'dev' $dbname -e "UPDATE wp_options SET option_value = replace(option_value, '$oldurl', '$newurl') WHERE option_name = 'home' OR option_name = 'siteurl';"
    mysql -h 127.0.0.1 -u root -P 33069 -p'dev' $dbname -e "UPDATE wp_posts SET guid = replace(guid, '$oldurl','$newurl');"
    mysql -h 127.0.0.1 -u root -P 33069 -p'dev' $dbname -e "UPDATE wp_posts SET post_content = replace(post_content, '$oldurl', '$newurl');"
    mysql -h 127.0.0.1 -u root -P 33069 -p'dev' $dbname -e "UPDATE wp_postmeta SET meta_value = replace(meta_value,'$oldurl','$newurl');"
}

####################
# Start the script #
####################

main

