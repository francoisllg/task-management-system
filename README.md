
# Challenge: Task Manager Service with DDD  

Dockeried Laravel app with DDD to create, update and list tasks and assign users to them.


## Installation


- Install both [Docker](https://docs.docker.com/engine/install/ubuntu/) and [Docker-compose](https://docs.docker.com/compose/install/).


- Now from the root project folder we launch 

```"docker-compose up -d"```

Update composer first and then migrate the dabasae , running this in the docker project php shell 

   ```composer update```

   ```php artisan migrate:fresh --seed```

(if you are using WSL2 instead of native Windows or MacOW, you may need to change the file permissions of the project folder from the docker php shell)

```chmod -R 777 .```

- We have everything ready, now we go from the browser to "http://localhost:8082" and we will see the laravel welcome page.

## Usage


First, run the tests in order to check if everything is ok.

Docker Php Shell:

```
php artisan test
```


## API Routes

```
Public routes
Login         => POST api/v1/login

Protected routes
Create a task => POST api/v1/tasks
Update a task => PATCH api/v1/tasks/{task_id}
Get a task    => GET api/v1/tasks/{task_id}
Delete a task => DELETE api/v1/tasks/{task_id}
Get all tasks => GET api/v1/tasks
Get all Tasks by User ID => GET /user/{user_id}/tasks
```
To be able to use the routes in postman, you must log in first and use the bearer token in the calls as authentication method.

The authentication email is admin@example.com and the password is password. 

The front end could be a little slow when creating or deleting elements depending on the computer where it is executed.
