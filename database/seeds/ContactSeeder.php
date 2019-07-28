<?php


use Phinx\Seed\AbstractSeed;

class ContactSeeder extends AbstractSeed
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
        $table = $this->table('contacts');
        $table->truncate();

        $faker = Faker\Factory::create('pt_BR');
        $contacts = [];

        for ($i=0; $i<10; $i++) {
            $int= mt_rand(1262055681, 1262055681);
            $data = date("Y-m-d H:i:s", $int);

            $contacts[] = [
                'name' => $faker->name,
                'email' => $faker->email,
                'phone' => $faker->areaCode.preg_replace("/\D/", '', $faker->phone),
                'city' => $faker->city,
                'state' => $faker->stateAbbr,
                'subject' => $faker->text,
                'message' => $faker->text,
                'created_at' => $data,
                'updated_at' => $data
            ];
        }

        $table->insert($contacts)->save();
    }
}
