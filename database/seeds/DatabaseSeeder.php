<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		Model::unguard();

       
        // Roles Table Seeding
		DB::table('roles')->insert([
            ['role' => 'Admin', 'created_at' => date("Y-m-d H:i:s")],
            ['role' => 'Editor', 'created_at' => date("Y-m-d H:i:s")],
            ['role' => 'Security', 'created_at' => date("Y-m-d H:i:s")],
            ['role' => 'Commentor', 'created_at' => date("Y-m-d H:i:s")],
            ['role' => 'Guest', 'created_at' => date("Y-m-d H:i:s")]
        ]);

		$this->command->info('roles table seeded!');
         
        
    }
}
