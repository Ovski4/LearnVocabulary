## Todo ##

 

### features ###
 * use local storage to let left column hidden on next page
	Si la page n'est pas /page/2 ou + > clean local storage
	Si la page est page 1 > set values
 * email with mailgun? > test send email and domain name used
 * google analytics
 * learn how to use assetic
 * validate html5
 * Add titles in pages
 * remove createAction in translation and set it in editonAction with if methode = get or if methode = post
 * link to password forgot
 * contact page
 * help page
 * demo user cron to reset everything
 * delete user delete everything
 * delete translation older than
 * front page
 * Translate the website in en/fr
 * add flashbags message when success edit delete etc
 * improve csv import & export
 * rename LnguageBundle to VocabularyBundle
 * improve checkLeaningUnicity function, etc..
### details ###
 * Unicity constraint in word
 * Unicity constraint in translation
 * Check if there is no article with a non name word
 * Check if there is an article with a name word (except for english)
 * Handle exceptions in a better way
 * Rename routes with ovski_prefix
 * Add users on entities and constraints (not create 2 translations identicals, use exisiting translations, etc)
 * check that no entities are created twice or more (word, translations, etc)
 * check not used actions
 * novalidate everywhere (etc-> la, le, l' -> deesde)
### at the very end ###
 * use cdn to load jquery/bootsrap, etc, else modernizer and load files
