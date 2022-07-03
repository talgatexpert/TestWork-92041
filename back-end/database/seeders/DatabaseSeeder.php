<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public array $localSeeders = [
      UserSeeder::class,
        DessertSeeder::class
    ];
    public array $productionSeeders = [
        UserSeeder::class
    ];
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() : void
    {
        // Отключаем логирование и проверку внешних ключей
        DB::disableQueryLog();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        switch (app()->environment()) {
            case 'local':
                foreach ($this->localSeeders as $seeder) {
                    //Очистка данных
                    $string = Str::remove(['database_seeders_', 'databases_', '_seeder', '\\'], str_replace('\\', '', Str::snake($seeder)));
                    $table = Str::plural($string);
                    DB::table($table)->truncate();
                    $this->call($seeder);
                }
                break;

            case 'production':
                foreach ($this->productionSeeders as $seeder) {
                    $string = Str::remove(['database_seeders_', 'databases_', '_seeder', '\\'], str_replace('\\', '', Str::snake($seeder)));
                    $table = Str::plural($string);
                    DB::table($table)->truncate();
                    $this->call($seeder);
                }
                break;
        }

        // Возвращаем все обратно
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        DB::enableQueryLog();
    }
}
