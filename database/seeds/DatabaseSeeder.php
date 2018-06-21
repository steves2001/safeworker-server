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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('announcements')->truncate();
		DB::table('sources')->truncate();
        DB::table('roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        DB::table('sources')->insert([
            ['sourcename' => 'General', 'created_at' => date("Y-m-d H:i:s")],
            ['sourcename' => 'Library', 'created_at' => date("Y-m-d H:i:s")],
            ['sourcename' => 'HE', 'created_at' => date("Y-m-d H:i:s")],
            ['sourcename' => 'Security', 'created_at' => date("Y-m-d H:i:s")],
            ['sourcename' => 'Admin', 'created_at' => date("Y-m-d H:i:s")],
            ['sourcename' => 'Contacts', 'created_at' => date("Y-m-d H:i:s")],
            ['sourcename' => 'Transport', 'created_at' => date("Y-m-d H:i:s")],
            ['sourcename' => 'Emergency', 'created_at' => date("Y-m-d H:i:s")]
        ]);

		$this->command->info('sources table seeded!');

        //  Announcements Table Seeding
        
		DB::table('announcements')->insert([
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 1, 'title' => 'General Announcements', 'content' => 'Announcements placed here are from anyone in the college and the student union and contain information about events or offers.', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 2, 'title' => 'Library Announcements', 'content' => 'Announcements placed here are from library staff to advise you of things going on in the library and any support they have on offer.', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 3, 'title' => 'HE Announcements', 'content' => 'Announcements placed here are from HE staff within the college and detail any important meetings and events relating to the college.', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 4, 'title' => 'Security Announcements', 'content' => 'Announcements within this section are essential alerts by the college security team and may contain messages about safety on campus.', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 5, 'title' => 'Admin Announcements', 'content' => 'Announcements in this section are from administrators of this app and may include instructions on updating the app or information on service status.', 'visible' => 'Y'],

            ['created_at' => date("Y-m-d H:i:s"), 'source' => 6, 'title' => 'Safeguarding', 'content' => 'There is a range of specific safeguarding needs that our trained staff can offer support with or investigate, including issues around your personal safety, forms of abuse, e-safety, physical wellbeing, homelessness, bullying or harassment, hate crime or PREVENT. You can report any safeguarding concerns to us via the request support link or, contact the College Safeguarding Team by phone or email.', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 6, 'title' => 'Hate Crime', 'content' => 'All hate crimes are incidents which have been motivated by hate, prejudice or discrimination against protected human characteristics such as gender-identity, sexual orientation, religion or belief, disability or race. If you have experienced a hate crime or incident, you can report this to our College Safeguarding Team via the text box below or contact us by phone or email. ', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 6, 'title' => 'Bullying and Harassment', 'content' => 'Bullying, harassment and hate crimes, by their nature are corrosive, tormenting and distressing and can have a significant physical and emotional impact on groups and individuals.  They can take many forms from name calling or offensive language/gestures to direct physical assault. Bullying, harassment or hate crimes can also occur in an online environment and include both Cyberbullying and Cyberstalking, which entail the malicious use of technology to harass, threaten, maliciously pressure or embarrass an individual online. You can report this via the text box below, or, contact the College Safeguarding Team by phone or email.', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 6, 'title' => 'PREVENT', 'content' => 'PREVENT is our duty to safeguard people from being drawn into terrorism or supporting extremist activities. It is not about stopping people from having their own views, but making sure that discriminatory or derogatory views which incite hatred or violence, or oppose values of democracy, rule of law, individual liberty or mutual respect are challenged.<br>If you have any concerns that the views and/or behaviour of a student is of an offensive or extremist nature, please alert our College’s Safeguarding Team. You can report this via the text box below, or, contact the College Safeguarding Team by phone or email.', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 6, 'title' => 'Mental Health', 'content' => 'Our Assessment Team offer a wide range of support and services for students experiencing varying levels of mental ill-health. We have a trained mental health co-ordinator who works as part of the Assessment Team offering internal and external support for students.<br>To arrange an appointment with an Assessment and Support Co-ordinator, you can contact the Assessment Team confidentially on 01522 876225, or email your enquiry to the team using the text box below', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 6, 'title' => 'Counselling', 'content' => 'There may be a time when you experience emotional or personal difficulties whilst studying at College. In Student Services, we have a number of professional counsellors who can offer varying levels of support. Please use the text box below to contact our counsellors or call 01522 876220 to make an appointment.', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 6, 'title' => 'Welfare', 'content' => 'We know that there may be times when you require additional support for matters of welfare including finance, transport, free college meals or childcare. Our Welfare Team offer a range of services relating to your welfare and you can contact the team via the request support option in this app.', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 6, 'title' => 'Additional Learning Support', 'content' => 'The College can offer support if you have a learning difficulty, disability, medical condition or mental health difficulty including hearing and visual impairment, specific learning difficulties relating to Dyslexia, Autistic Spectrum Disorder or Asperger Syndrome and emotional or behaviour difficulties.<br>To arrange an appointment with an Assessment and Support Co-ordinator for any support you may need, you can contact the Assessment Team confidentially on 01522 876225, or contact the team via the request support option in this app.', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 6, 'title' => 'Youth and Wellbeing', 'content' => 'The Youth and Wellbeing Team offer a wide range of services and support including sexual health advice, help and advice to develop personal and social skills, workshops related to wellbeing and information about volunteering and fundraising. If you have an enquiry, please contact us via the request support option in this app.', 'visible' => 'Y'],

            ['created_at' => date("Y-m-d H:i:s"), 'source' => 8, 'title' => 'Safeguarding', 'content' => '<ul><li>Safeguarding Team<br>safeguarding@lincolncollege.ac.uk<br>01522 86495 or 01522 864219<br>during office hours<br>&nbsp;</li><li>Out of hours duty emergency teams<br>01522 782333 (Lincolnshire)<br>03004 564546 (Nottinghamshire)<br>&nbsp;</li><li>Lincolnshire or Nottinghamshire Police<br>Telephone 101 to report a crime<br>Telephone 999 in an emergency</li></ul>', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 8, 'title' => 'Hate Crime', 'content' => '<ul><li>Safeguarding Team<br>safeguarding@lincolncollege.ac.uk<br>01522 86495 or 01522 864219<br>during office hours<br>&nbsp;</li><li>Out of hours duty emergency teams<br>01522 782333 (Lincolnshire)<br>03004 564546 (Nottinghamshire)<br>&nbsp;</li><li>Lincolnshire or Nottinghamshire Police<br>Telephone 101 to report a crime<br>Telephone 999 in an emergency<br>&nbsp;</li><li><a href="https://www.stophateuk.org/">Stop Hate UK</a></li></ul>', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 8, 'title' => 'Bullying and Harassment', 'content' => '<ul><li>Safeguarding Team<br>safeguarding@lincolncollege.ac.uk<br>01522 86495 or 01522 864219<br>during office hours<br>&nbsp;</li><li>Out of hours duty emergency teams<br>01522 782333 (Lincolnshire)<br>03004 564546 (Nottinghamshire)</li></ul>', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 8, 'title' => 'PREVENT', 'content' => '<ul><li>Safeguarding Team<br>safeguarding@lincolncollege.ac.uk<br>01522 86495 or 01522 864219<br>during office hours<br>&nbsp;</li><li>Out of hours duty emergency teams<br>01522 782333 (Lincolnshire)<br>03004 564546 (Nottinghamshire)<br>&nbsp;</li><li>Lincolnshire or Nottinghamshire Police<br>Telephone 101 to report a crime<br>Telephone 999 in an emergency</li></ul>', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 8, 'title' => 'Mental Health', 'content' => '<ul><li>Assessment Team<br>assessmentofficer<br>@lincolncollege.ac.uk<br>01522 876225<br>during office hours<br>&nbsp;</li></ul>', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 8, 'title' => 'Counselling', 'content' => '<ul><li>Student Services<br>studentservices<br>@lincolncollege.ac.uk<br>01522 876220<br>during office hours<br>&nbsp;</li></ul>', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 8, 'title' => 'Welfare', 'content' => '<ul><li>Welfare Team<br>welfare@lincolncollege.ac.uk<br>01522 876220<br>during office hours<br>&nbsp;</li></ul>', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 8, 'title' => 'Additional Learning Support', 'content' => '<ul><li>Assessment Team<br>assessmentofficer<br>@lincolncollege.ac.uk<br>01522 876225<br>during office hours<br>&nbsp;</li></ul>', 'visible' => 'Y'],
            ['created_at' => date("Y-m-d H:i:s"), 'source' => 8, 'title' => 'Youth and Wellbeing', 'content' => '<ul><li>Youth and Wellbeing Team<br>youthworker<br>@lincolncollege.ac.uk<br>01522 876220<br>during office hours<br>&nbsp;</li></ul>', 'visible' => 'Y']
        ]);

		$this->command->info('announcements table seeded!');
        
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
