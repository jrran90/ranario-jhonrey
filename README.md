### Installation

Install dependencies

```shell
composer install && npm i
```

Setup config
```shell
cp .env.example .env
```
> **Note:** Update accordingly the DB credentials in `.env`

Run migration & seeder

```shell
php artisan migrate && php artisan db:seed
```

Run project

```shell
npm run dev
```

> **Note:** I'm currently using Laravel Herd for running my project. You may use ```php artisan serve```
> and ```npm run dev```

### A brief description of your solution.

- Check the store's status, if it's open at the moment.
- lunch break handling, should be marked as closed during those hours.
- finds the next available opening using date picker, and calculates the difference.

### Any assumptions or challenges you faced.

- Initially, when selecting a future date (e.g., March 9, Sunday), the system incorrectly counted backward from the
  selected date to the current date (March 7), instead of calculating forward. But it was fixed with some tweaking using Carbon.
