# Heroku Dotenv

⌨️ Copy php `.env` variables to or from [Heroku](https://www.heroku.com) environment variables.

## Why creating this package?

I've created this package because I often use PHP projects/frameworks that use [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) package to handle PHP environment values in a `.env` file.

You can't create/modify files in your Heroku server using Heroku CLI (ok, in fact [you can](https://gist.github.com/btakita/7853541)), and you can't create your production `.env` file, so you have to manually create your environment variables using Heroku CLI or on your Heroku dashboard.

This PHP script can automatically copy environment variables in a .env file to and from Heroku.

## Requirements

### Heroku CLI

This PHP script uses [Heroku CLI](https://devcenter.heroku.com/articles/heroku-cli). You must have installed it on your machine.

### Composer

Make sure [Composer](https://getcomposer.org/download/) is installed globally.

## Install

```bash
$ composer global require cba85/heroku-dotenv
```

Then make sure you have the global Composer binaries directory in your PATH.

This directory is platform-dependent, see [Composer documentation](https://getcomposer.org/doc/03-cli.md#composer-home) for details.

### Update

```bash
$ composer global update cba85/heroku-dotenv
```

## Usage

Go to your project folder that contains a `.env` file.

### Send .env file to Heroku environment

```bash
$ heroku-dotenv push -a heroku_app_name
```

### Save Heroku environment to .env file

```bash
$ heroku-dotenv pull heroku_app_name
```

## Options

### -f, --file

Name or path of your project `.env` file.

```bash
$ heroku-dotenv push heroku_app_name -f .env.production
$ heroku-dotenv pull heroku_app_name -f .env.production
```

## Tests

The package contains a dotenv file for testing, located in `example/` folder.

```bash
$ ./heroku-dotenv push heroku_app_name -f example/.env
$ ./heroku-dotenv pull heroku_app_name -f example/.env
```

> Where `heroku_app_name` is a valid Heroku app.