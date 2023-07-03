# P8-TodoList 
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/6e390f555ca04f129835ca3b6ee31231)](https://app.codacy.com/gh/AureLey/ToDoList/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)
_Améliorez une application existante de ToDo & Co_



## Setup

Set PHP in 8.1

Get git repository and clone it

```
git clone https://github.com/AureLey/ToDoList.git
```

### Get composer dependencies

```
composer install
```

### Database creation, migrations and fixtures

#### Database :
```
php bin/console doctrine:database:create
```
#### Migration : 
```
php bin/console doctrine:migrations:migrate
```
#### Fixtures
```
php bin/console doctrine:fixtures:load
```

## Launch local server 
```
symfony server:start
```

## Default Credentials

### Admin: 
```
root
```
### Password : 
```
root
```
_To testing a basic user, all users are created randomly but they got the same password "root"_
### Example: 
```
example_username
```
### Password : 
```
root
```
## Tests:

Testing code:
```
php bin/phpunit
```

