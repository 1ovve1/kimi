<?php

namespace App\Providers;

use App\Models\Channel;
use App\Models\Group;
use App\Models\Supergroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Map chat types morphs relations
         */
        Relation::enforceMorphMap([
            'private' => User::class,
            'group' => Group::class,
            'supergroup' => Supergroup::class,
            'channel' => Channel::class,
        ]);
    }
}
