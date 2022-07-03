
## For run frontend

change directory to frontend and run command

`npm install`

Next step

`npm run dev`

## For run backend

**PHP VERSION 8**

**DATABASE DRIVER mysql**

change directory to back-end

copy .env.example as .env


In your .env file please write domain where is frontend 

`SANCTUM_STATEFUL_DOMAINS=localhost:8080
`

Nex step

`php artisan migrate --seed
`

Next step for the run development mode  

`php artisan serve
`