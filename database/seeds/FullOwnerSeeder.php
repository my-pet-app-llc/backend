<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Owner;
use App\Pet;
use App\Friend;

class FullOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'user' => [
                    'email' => 'mail@mail.com',
                    'password' => bcrypt('123qwe123')
                ],
                'owner' => [
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
                ],
                'pet' => [
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
                ]
            ],
            [
                'user' => [
                    'email' => 'user@mail.com',
                    'password' => bcrypt('123qwe123')
                ],
                'owner' => [
                    'first_name' => 'East',
                    'last_name' => 'Coast',
                    'gender' => 'male',
                    'age' => 11,
                    'birthday' => '1970-01-01',
                    'occupation' => 'Nigger',
                    'hobbies' => 'Kill',
                    'pets_owned' => '99 niggers',
                    'profile_picture' => '/storage/profile_picture/fsQt49M1K80ue8U2uyGFwUpGycpoCk.png',
                    'favorite_park' => 'Job',
                    'signup_step' => 0,
                ],
                'pet' => [
                    'name' => 'Nigger',
                    'gender' => 'male',
                    'size' => 'giant',
                    'primary_breed' => 'Nigger',
                    'secondary_breed' => 'Gang',
                    'age' => 21,
                    'profile_picture' => '/storage/profile_picture/fsQt49M1K80ue8U2uyGFwUpGycpoCk.png',
                    'friendliness' => 1,
                    'activity_level' => 1,
                    'noise_level' => 1,
                    'odebience_level' => 1,
                    'fetchability' => 1,
                    'swimability' => 1,
                    'city' => 'New-York',
                    'state' => 'NY',
                    'like' => 'Niggers and sleep',
                    'dislike' => 'Whites',
                    'favorite_toys' => 'Whites',
                    'fears' => 'Chicken',
                    'favorite_places' => 'Land',
                    'spayed' => false,
                    'birthday' => '1939-09-01'
                ]
            ],
        ];

        $toFriend = [];

        foreach ($data as $datum) {
            $user = User::query()->create($datum['user']);

            $owner = $datum['owner'];
            $owner['user_id'] = $user->id;
            $owner = Owner::query()->create($owner);

            $pet = $datum['pet'];
            $pet['owner_id'] = $owner->id;
            $pet = Pet::query()->create($pet);

            $toFriend[] = $pet->id;
        }

        Friend::query()->create(['pet_id' => $toFriend[0], 'friend_id' => $toFriend[1]]);
        Friend::query()->create(['pet_id' => $toFriend[1], 'friend_id' => $toFriend[0]]);
    }
}
