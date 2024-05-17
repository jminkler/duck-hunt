
## About Duck Hunt

Duck Hunt is the story of the Ducks behind the video game Duck Hunt, if they were alive inside the game. 

- Laravel 10 / php 8.2 / Sail 
- Mongodb duck and equipment collection

## Synopsis

- Commands to play with (assumes sail alias ./vendor/bin/sail)
  - ```sail artisan db:seed```  Seeds 100,000 Ducks at full health
  - ```sail artisan app:shoot-some-ducks {count}``` Shoot some ducks with random weapons, uses a Factory to generate a random shooting Strategy
  - ```sail artisan app:triage-ducks``` We have ```app:heal-all``` but that's no fun, we need to triage all these ducks and send them to the correct medical professional
  - ```sail artisan app:heal-all``` Heal all the ducks (reset)
- Shooting Ducks accounts for any armor equipment the duck may have 
  - Armor value reduced from incoming damage
  - ```/ducks``` first 10 ducks with > 80 health - json
  - ```/stats``` Some stats about the injuries of the Ducks and their equipment 
  - ```/explain-ducks-with-equipment/{equipmentId}``` I noticed the mongodb laravel query builder didn't have the explain() method, so I wrote an endpoint to see if the index for Duck equipment was working
  - ```/search-ducks``` by health, speed, date 
- Updated and published docker-compose.yml to install mongodb, and php mongodb  


