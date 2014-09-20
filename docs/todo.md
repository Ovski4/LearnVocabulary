## Todo ##

### View ### 
 * Learning leanguages to the left
 * Paginated translations to the right

### features ###
 * edit a translation
 * delete a translation
 * parameter for the number of translation in 1 page
 * filter by word type, creation date
 * starred translations
 * filter search
 * csv export
 * learn pronoun for names (list all word languages)
 * rename LnguageBundle to VocabularyBundle
 * improve checkLeaningUnicity function, etc..
### details ###
 * Set parameter max_translations_per_page in database
 * DoctrineOrmAdapter instead of arrayAdapter
 * Rename $entity -> $translation in controllers and views
 * Edit table name in entites (no capitals) and attributes name (underscores)
 * Name by default in select box instead of preposition
 * Unicity constraint in word
 * Unicity constraint in translation
 * Check if there is no article with a non name word
 * Check if there is an article with a name word (except for english)
 * Handle exceptions in a better way
 * Rename routes with ovski_prefix
 * Add users on entities and constraints (not create 2 translations identicals, use exisiting translations, etc)

### at the very end ###
use cdn to load jquery/bootsrap, etc, else modernizer and load files
