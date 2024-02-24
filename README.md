# Laravel Project Setup

This document provides a step-by-step guide to setting up a Laravel project.

## Step 1: Composer Install

Run the following command to install the dependencies in the local vendor folder.
```sh
Composer Install
```
## Step 2: Database Migration
The command below will drop all tables from the database and then execute the migrate command:
```sh
php artisan migrate:fresh
```
## Step 3: Database Seeding
This command will populate the database with seed data defined in your seed classes:
```sh
php artisan db:seed
```
## Step 4: Generate Passport Keys
The following command will create the encryption keys needed to generate secure access tokens:

```sh
php artisan passport:keys
```