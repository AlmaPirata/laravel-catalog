<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('groups')->exists()) {
            return;
        }

        $dump = file_get_contents(database_path('sql/dump/test.sql'));

        if ($dump === false) {
            throw new \RuntimeException('Не удалось прочитать тестовые данные каталога.');
        }

        preg_match_all(
            '/insert\s+into\s+`(?:groups|products|prices)`.*?;/is',
            $dump,
            $insertStatements,
        );

        foreach ($insertStatements[0] as $insertStatement) {
            DB::unprepared($insertStatement);
        }
    }
}
