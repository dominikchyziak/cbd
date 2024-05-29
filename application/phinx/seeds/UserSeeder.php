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
        $data = array(
            array(
                'email' => 'duonet',
                'password' => 'bc7a2cd1408052a2839064a6c3d5f97b',
                'name' => 'duonet'
            )
        );

        $this->table('duo_users')->insert($data)->save();
    }
}
