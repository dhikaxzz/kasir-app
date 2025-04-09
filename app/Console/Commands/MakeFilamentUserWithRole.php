<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Filament\Facades\Filament;
use Filament\Commands\MakeUserCommand;
use App\Models\User;
use Spatie\Permission\Models\Role;

class MakeFilamentUserWithRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-filament-user-with-role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Filament user and assign a role automatically';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->ask('Enter email:');
        $password = $this->secret('Enter password:');

        // Buat user
        $user = User::create([
            'name' => 'Super Admin',
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        // Beri role super_admin
        $role = Role::firstOrCreate(['name' => 'super_admin']);
        $user->assignRole($role);

        $this->info('User berhasil dibuat dengan role super_admin!');
    }
}