<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

use User\Infrastructure\Models\ProfileModel;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'username' => 'Jacob',
            'email' => 'jake@jake.jake',
            'password' => bcrypt('jakejake'),
            'bio' => 'I work at statefarm',
            'image' => null,
        ]);
        $mozz = User::create([
            'username' => 'Maurice',
            'email' => 'moss@example.com',
            'password' => bcrypt('12345678'),
            'bio' => 'I work at statefarm',
            'image' => null,
        ]);
        /*
        $user = User::where('email','jake@jake.jake') -> first();
        ProfileModel::create([
            'name' => 'Jacob',
            'bio' => 'I work at statefarm',
            'image' => 'https://api.realworld.io/images/smiley-cyrus.jpg',
            'user_id' => $user->id,
        ]);
        */

        // Asociar el perfil al usuario
        //$user->profile()->save($profile);
    }
}
