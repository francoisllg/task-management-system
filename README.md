
# Challenge: Task Manager Service with DDD  

Dockeried Laravel app to create, update and list tasks and assign users to them.


## Installation


- Install both [Docker](https://docs.docker.com/engine/install/ubuntu/) and [Docker-compose](https://docs.docker.com/compose/install/).

- Once this is done, go to the folder "docker/" and copy the file ".unix.conf", ".windows.conf" or ".mac-arm.conf" (depending on the operating system you use) and paste it with the name ".env" in the same folder.

- Now from the docker folder we launch 
```"docker-compose up -d"```

- Then, in the root of the project copy the example env file (.env.example) and make the required configuration changes in the .env file

    ```cp .env.example .env```

Migrate the dabasae , running this in the docker php shell 

   ```php artisan migrate:fresh --seed```

Or with this command in the native terminal of your OS:

```docker exec -it task_manager_php php artisan migrate:fresh --seed```


- We have everything ready, now we go from the browser to "http://127.0.7.14" if we are in Linux, to "http://localhost:87" if we are in Windows, or "http://127.0.0.1.1:80" if we are using a Mac with ARM processor.

## Usage


First, run the tests in order to check if everything is ok.

Php Shell:
```
php artisan test
```
Native Terminal:
```
docker exec -it task_manager_php php artisan test
```


If you want to test the routes with postman, you have to use real User IDs that you can find in the file storage/app/csv/data.csv.

## API Routes

```
Public routes

Create a task => POST api/v1/tasks
Update a task => PATCH api/v1/tasks/{task_id}
Get a task    => GET api/v1/tasks/{task_id}
Delete a task => DELETE api/v1/tasks/{task_id}
Get all tasks => GET api/v1/tasks
Get all Tasks by User ID => GET /user/{user_id}/tasks
```
