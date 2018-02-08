<?php

use Phinx\Seed\AbstractSeed;

class ProjectSeeder extends AbstractSeed
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
            [
                'title'    => 'Up Town',
                'about' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid reprehenderit eius, voluptas incidunt maxime! Culpa suscipit eius harum nihil. Blanditiis, porro consectetur aliquid totam animi neque nobis esse explicabo voluptatibus.',
            ],
            [
               'title'    => 'Barbosa',
                'about' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perspiciatis veniam veritatis autem voluptates quia cumque reiciendis, fuga unde, voluptatum ut, doloribus sequi quae magni dolores maxime, adipisci beatae corporis et.', 
            ]
        );
        $users = $this->table('projects');
        $users->insert($data)
              ->save();
    }
}
