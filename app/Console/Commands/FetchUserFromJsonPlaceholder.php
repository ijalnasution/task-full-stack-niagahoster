<?php

namespace App\Console\Commands;

use App\Profile;
use App\User;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class FetchUserFromJsonPlaceholder extends Command
{
    const USER_ACTIVE = 1;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch User from JsonPlaceholder.com';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $baseUrl = env('JSON_PLACEHOLDER_URL', 'https://jsonplaceholder.typicode.com/');
        $endpoint = $baseUrl.'users';

        $response = $this->client->request('GET', $endpoint);
        $content = $response->getBody()->getContents();
        $content = json_decode($content, true);
        $key = array_rand($content);

        $profile = $content[$key];

        // Create User and Profile
        try {
            $user = $this->createUser($profile);
            $profile = $this->createProfile($profile, $user['id']);

            var_dump('Profile with name = '.$profile->name.' has been created. (Password = sudah_tak_ganti!!!)');
        } catch (\Throwable $th) {
            throw($th);
        }
    }

    /**
     * Create User based on data that got from jsonplaceholder.com
     *
     * @return User
     */

    private function createUser($array)
    {
        $user = new User();
        $user->name = $array['username'];
        $user->email = $array['email'];
        $user->password = bcrypt('sudah_tak_ganti!!!');
        $user->is_active = self::USER_ACTIVE;
        $user->save();

        $token = JWTAuth::fromUser($user);

        $user = [
            'id' => $user->id,
            'token' => $token
        ];

        return $user;
    }

    /**
     * Create Profile based on data that got from jsonplaceholder.com
     *
     * @return Profile
     */
    private function createProfile($array, $userId)
    {
        $profile = new Profile();
        $profile->name = $array['name'];
        $profile->email = $array['email'];
        $profile->address_street = $array['address']['street'];
        $profile->suite = $array['address']['suite'];
        $profile->city = $array['address']['city'];
        $profile->zipcode = $array['address']['zipcode'];
        $profile->lat = $array['address']['geo']['lat'];
        $profile->lang = $array['address']['geo']['lng'];
        $profile->phone = $array['phone'];
        $profile->website = $array['website'];
        $profile->company_name = $array['company']['name'];
        $profile->company_catch_phrase = $array['company']['catchPhrase'];
        $profile->company_bs = $array['company']['bs'];
        $profile->user_id = $userId;
        $profile->save();

        return $profile;
    }
}
