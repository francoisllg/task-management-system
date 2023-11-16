
# Challenge: Task Manager Service with DDD  

Dockeried Laravel app to create, update and list tasks and assign users to them.


## Installation


- Install both [Docker](https://docs.docker.com/engine/install/ubuntu/) and [Docker-compose](https://docs.docker.com/compose/install/).

- Once this is done, go to the folder "docker/" and copy the file ".unix.conf", ".windows.conf" or ".mac-arm.conf" (depending on the operating system you use) and paste it with the name ".env" in the same folder.

- Now from the docker folder we launch 
```"docker-compose up -d"```

Update composer first and then migrate the dabasae , running this in the docker php shell 
   ```composer update```
   ```php artisan migrate:fresh --seed```

Or with this command in the native terminal of your OS:
```docker exec -it task-manager-app composer update```
```docker exec -it task-manager-app php artisan migrate:fresh --seed```


- We have everything ready, now we go from the browser to "http://localhost:8082" and we will see the laravel welcome page.

## Usage


First, run the tests in order to check if everything is ok.

Php Shell:
```
php artisan test
```
Native Terminal:
```
docker exec -it task_manager_app php artisan test
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
