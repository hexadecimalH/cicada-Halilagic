<?php

use Phinx\Seed\AbstractSeed;

class ProjectPictureSeeder extends AbstractSeed
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
                'project_id'    => 1,
                'url' => '/uploads/uptown/1.jpg',
                'type' => 'normal',
                'data_title' => 'Interior Design',
                'data_light_box' => 'Up Town'
            ],
            [
                'project_id'    => 1,
                'url' => '/uploads/uptown/2.jpg',
                'type' => 'normal',
                'data_title' => 'Interior Design',
                'data_light_box' => 'Up Town'
            ],
            [
                'project_id'    => 1,
                'url' => '/uploads/uptown/3.jpg',
                'type' => 'normal',
                'data_title' => 'Interior Design',
                'data_light_box' => 'Up Town'
            ],
            [
                'project_id'    => 1,
                'url' => '/uploads/uptown/4.jpg',
                'type' => 'normal',
                'data_title' => 'Interior Design',
                'data_light_box' => 'Up Town'
            ],
            [
                'project_id'    => 1,
                'url' => '/uploads/barbosa/thumb/1.png',
                'type' => 'thumb',
                'data_title' => 'Interior Design',
                'data_light_box' => 'Up Town'
            ],
            [
                'project_id'    => 2,
                'url' => '/uploads/barbosa/1.jpg',
                'type' => 'normal',
                'data_title' => 'Interior Design',
                'data_light_box' => 'Barbosa'
            ],
            [
                'project_id'    => 2,
                'url' => '/uploads/barbosa/2.jpg',
                'type' => 'normal',
                'data_title' => 'Interior Design',
                'data_light_box' => 'Barbosa'
            ],
            [
                'project_id'    => 2,
                'url' => '/uploads/barbosa/3.jpg',
                'type' => 'normal',
                'data_title' => 'Interior Design',
                'data_light_box' => 'Barbosa'
            ],
            [
                'project_id'    => 2,
                'url' => '/uploads/barbosa/4.jpg',
                'type' => 'normal',
                'data_title' => 'Interior Design',
                'data_light_box' => 'Barbosa'
            ],
            [
                'project_id'    => 2,
                'url' => '/uploads/uptown/thumb/1.png',
                'type' => 'thumb',
                'data_title' => 'Interior Design',
                'data_light_box' => 'Barbosa'
            ]
        );
        $users = $this->table('project_pics');
        $users->insert($data)
              ->save();
    }
}
