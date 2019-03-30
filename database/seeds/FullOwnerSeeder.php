<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Owner;
use App\Pet;

class FullOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            'email' => 'mail@mail.com',
            'password' => bcrypt('123qwe123')
        ];

        $owner = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'gender' => 'male',
            'age' => 78,
            'birthday' => '2001-04-01',
            'occupation' => 'P.I.M.P.',
            'hobbies' => 'Fuck',
            'pets_owned' => '1 chicken, 33 cows',
            'profile_picture' => '/storage/profile_picture/fsQt49M1K80ue8U2uyGFwUpGycpoCk.png',
            'favorite_park' => 'Tarasa Shevchenka',
            'signup_step' => 0,
        ];

        $pet = [
            'name' => 'Apple',
            'gender' => 'male',
            'size' => 'giant',
            'primary_breed' => 'Human',
            'secondary_breed' => 'Apple',
            'age' => 18,
            'profile_picture' => '/storage/profile_picture/fsQt49M1K80ue8U2uyGFwUpGycpoCk.png',
            'friendliness' => 1,
            'activity_level' => 1,
            'noise_level' => 1,
            'odebience_level' => 1,
            'fetchability' => 1,
            'swimability' => 1,
            'city' => 'New-York',
            'state' => 'NY',
            'like' => 'Apples and sleep',
            'dislike' => 'Human',
            'favorite_toys' => 'My friend Tom :(',
            'fears' => 'I am',
            'favorite_places' => 'Land',
            'spayed' => true,
            'birthday' => '1939-09-01'
        ];

        $user = User::query()->create($user);

        $owner['user_id'] = $user->id;
        $owner = Owner::query()->create($owner);

        $pet['owner_id'] = $owner->id;
        Pet::query()->create($pet);
    }
}
