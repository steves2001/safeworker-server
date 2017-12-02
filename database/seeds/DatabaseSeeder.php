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

        // Sources Table Seeding
		DB::table('sources')->insert([
            ['sourcename' => 'General', 'created_at' => date("Y-m-d H:i:s")],
            ['sourcename' => 'Library', 'created_at' => date("Y-m-d H:i:s")],
            ['sourcename' => 'HE', 'created_at' => date("Y-m-d H:i:s")],
            ['sourcename' => 'Security', 'created_at' => date("Y-m-d H:i:s")],
            ['sourcename' => 'Admin', 'created_at' => date("Y-m-d H:i:s")]
        ]);

		$this->command->info('sources table seeded!');

        //  Announcements Table Seeding
		DB::table('announcements')->insert([
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 1, 'title' => 'General Announcements', 'content' => 'Announcements placed here are from anyone in the college and the student union and contain information about events or offers.', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 2, 'title' => 'Library Announcements', 'content' => 'Announcements placed here are from library staff to advise you of things going on in the library and any support they have on offer.', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 3, 'title' => 'HE Announcements', 'content' => 'Announcements placed here are from HE staff within the college and detail any important meetings and events relating to the college.', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 4, 'title' => 'Security Announcements', 'content' => 'Announcements within this section are essential alerts by the college security team and may contain messages about safety on campus.', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 5, 'title' => 'Admin Announcements', 'content' => 'Announcements in this section are from administrators of this app and may include instructions on updating the app or information on service status.', 'visible' => 'Y']
        ]);

		$this->command->info('announcements table seeded!');
        
        
        // $this->call(UsersTableSeeder::class);
    }
}
