# About

Kimi - interactive chatbot based on ChatGPT and Telegram.

# How it works?

During the communication process, the chatbot collects information (text messages) and subsequently generates a response that corresponds to the context.
THIS IS NOT A SPY APP - all text messages are encrypted in database, so its can be access only inside of app.

# Requirements

For start up all you need is:

- up docker containers from /docker (don't forget to use ```cp .env.example .env```);
- set up migrations inside of fpm container (if it's not):
```php 
php artisan mgirate --seed 
```
- fill the .env file params:

```env
TELEGRAM_TOKEN="api-telegram-token"

GPT_MODEL="gpt-4o"
GPT_KEY=""
```
- run bot in get-update mode (or webhook):
```php
php artisan nutgram:run
```

That`s all.

# Usage

In bot menu use /start command for settings.

# Supported Features

- interactive mode;
- multiple characters;
- permissions.

# Documentation (RU)

[Here](docs/dev/cookbook-ru.md)
