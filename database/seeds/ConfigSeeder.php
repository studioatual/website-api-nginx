<?php


use Phinx\Seed\AbstractSeed;

class ConfigSeeder extends AbstractSeed
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
        $table = $this->table('config');
        $table->truncate();

        $data = date("Y-m-d H:i:s");

        $config = [
            'company' => 'My Website',
            'email1' => 'contato@studioatual.com',
            'email2' => 'suporte@studioatual.com',
            'analytics' => '',

            'title' => 'My Website - Boilerplate with Api JWT Authentication',
            'description' => 'Base Website with Slim Framework and Api JWT Authentication',
            'keywords' => 'website, api, slim framework, jwt, authentication',
            'created_at' => $data,
            'updated_at' => $data
        ];

        $table->insert($config)->save();
    }
}
