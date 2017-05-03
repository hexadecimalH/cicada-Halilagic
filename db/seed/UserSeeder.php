<?php

use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $encryption_key = openssl_random_pseudo_bytes(32);
        $iv = openssl_random_pseudo_bytes(16);

        $data = array(
            array(
                'username'    => 'admin',
                'password_salt' => 'adminadmin',
                'first_name'    => 'Haris',
                'last_name' => 'Zenovic',
                'email'    => 'haris@live.com',
            )
        );

        $users = $this->table('users');
        $users->insert($data)
              ->save();
    }
}
