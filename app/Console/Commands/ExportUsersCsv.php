<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class ExportUsersCsv extends Command
{
    protected $signature = 'users:export {filename=users.csv}';

    protected $description = 'Exporta tots els usuaris en un fitxer CSV';

    public function handle()
    {
        $users = User::all();
        if ($users->isEmpty()) {
            $this->warn('No hi ha usuaris per exportar.');

            return;
        }

        $csv = Writer::createFromString('');
        $csv->insertOne([
            'ID', 'Username', 'First Name', 'Last Name', 'Email', 'Phone',
            'Status', 'Gender', 'Location', 'Birth Date', 'Level', 'CV Path',
            'Portfolio URL',
        ]);

        foreach ($users as $user) {
            $csv->insertOne([
                $user->id,
                $user->username,
                $user->first_name,
                $user->last_name,
                $user->email,
                $user->phone,
                $user->status,
                $user->gender,
                $user->location,
                $user->birth_date,
                $user->level,
                $user->cv_path,
                $user->portfolio_url,
            ]);
        }

        $filename = $this->argument('filename');
        Storage::disk('local')->put($filename, $csv->toString());

        $this->info("El CSV s'ha generat correctament: storage/app/$filename");
    }
}
