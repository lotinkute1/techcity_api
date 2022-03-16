<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use DisableForeignKeys,TruncateTable;
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->disableForeignKeys();

        $this->truncate('users');
        User::factory(10)->create();
        $this->enableForeignKeys();
    }
}
