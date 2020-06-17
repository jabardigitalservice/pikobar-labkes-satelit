# pikobar-labkes-satelit

[![Maintainability](https://api.codeclimate.com/v1/badges/988338d3d796c34a74ee/maintainability)](https://codeclimate.com/repos/5ee0a3949df705017700cdef/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/988338d3d796c34a74ee/test_coverage)](https://codeclimate.com/repos/5ee0a3949df705017700cdef/test_coverage)

# pikobar-app-boilerplate

# Origin
Original Source: https://github.com/cretueusebiu/laravel-nuxt

## cretueusebiu/laravel-nuxt Features

- Nuxt 2.11
- Laravel 6
- SPA or SSR
- Socialite integration
- VueI18n + ESlint + Bootstrap 4 + Font Awesome 5
- Login, register, email verification and password reset

## Modified

- Upgrade to Laravel 7 based on laravel 6
- Improved Handler Exception
- Added API Responser
- Improved Cors


## Installation Backend

- `composer install`
- Copy `.env.example` to `.env`, then edit `.env` to set your database connection details
- `php artisan migrate`

## Installation Frontend

- `npm install -g nuxt`
- Copy `.env.example` to `.env`, then edit `.env` to set your `APP_URL` (the url to your Laravel application)
- `npm install`

## Usage

### Development

on frontend directory
```bash
npm run dev
```

on backend directory
```bash
php artisan serve
```

You can access your application at `http://localhost:3000`.

### Production

```bash
npm run build
```

You can access your application the url you set `APP_URL` to.

### Enable SSR

- Remove `mode: 'spa'` and `'~plugins/nuxt-client-init'` from `client/nuxt.config.js` 
- Edit `.env` to set `APP_URL=http://api.example.com` and `CLIENT_URL=http://example.com`
- Run `npm run build` and `npm run start`

#### Nginx Proxy

For Nginx you can add a proxy using the follwing location block:

```
server {
    location / {
        proxy_pass http://127.0.0.1:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }
}
```

#### Process Manager

In production you need a process manager to keep the Node server alive forever:

```bash
# install pm2 process manager
npm install -g pm2

# startup script
pm2 startup

# start process
pm2 start npm --name "laravel-nuxt" -- run start

# save process list
pm2 save

# list all processes
pm2 l
```

After each deploy you'll need to restart the process:

```bash
pm2 restart laravel-nuxt 
```

Make sure to read the [Nuxt docs](https://nuxtjs.org/).

## Socialite

This project comes with GitHub as an example for [Laravel Socialite](https://laravel.com/docs/5.8/socialite).

To enable the provider create a new GitHub application and use `https://example.com/api/oauth/github/callback` as the Authorization callback URL.

Edit `.env` and set `GITHUB_CLIENT_ID` and `GITHUB_CLIENT_SECRET` with the keys form your GitHub application.

For other providers you may need to set the appropriate keys in `config/services.php` and redirect url in `OAuthController.php`.

## Email Verification

To enable email verification make sure that your `App\User` model implements the `Illuminate\Contracts\Auth\MustVerifyEmail` contract.

## Notes

- This project uses [router-module](https://github.com/nuxt-community/router-module), so you have to add the routes manually in `client/router.js`.
- If you want to separate this in two projects (client and server api), move `package.json` into `client/` and remove config path option from the scripts section. Also make sure to add the env variables in `client/.env`.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.
