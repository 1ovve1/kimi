<?php

use App\Jobs\Telegram\RSS\RSSNotifierJob;

Schedule::job(RSSNotifierJob::class)->everyMinute();
