
> # READ THIS FIRST
> It is highly recommended for all contributors to update this file whenever there's a major update in source code. Use [this tool](https://stackedit.io/app#) for easy editing or [visit this page](https://help.github.com/articles/basic-writing-and-formatting-syntax/) for comprehensive guide on markdown syntax.

# Introduction
This package provides a command line utility to switch Laravel env files easily.

## Signature
`env:switch {new_env} {--force}`

## Supported Environments
Following values are acceptable for `new_env` parameter:
- dev
- prod
- testing

## Usage Examples
- `php artisan env:switch dev`
- `php artisan env:switch prod --force`

# How it works?
This utility supports 3 environments as mentioned above. It creates separate `.env` file for each environment e.g.:
- .env.dev
- .env.prod
- .env.testing

When an environment is activated, its content are copied to main `.env` file and the correspoding file gets a suffix `.active`. For example if `dev` environment is active, then `.env.dev` will be renamed to `.env.dev.active` and its content will be copied to main `.env` file.

In case the required version of `.env` file doesn't exist, its created automatically by copying `.env.example` file.

# To-dos
Following are the approved items:
[ ] Item-1

# Wishlist
Add the suggestions in this wishlist. Only approved wishlist items can be moved to To-dos list:
[ ] Item-1