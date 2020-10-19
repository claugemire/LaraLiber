<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddInitialBooks extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $emails = [
            'jake@jmctyre.com',
            'jake+tester@jmctyre.com',
        ];

        foreach($emails as $email) {
            $user_id = DB::table('users')->where('email', $email)->first()->id;

            DB::table('books')->
                insert([
                    'isbn' => '1587332299',
                    'title' => 'Common Sense',
                    'author' => 'Thomas Paine',
                    'page_count' => 72,
                    'description' => 'Addressed to the Inhabitants of America, on the Following Interesting Subjects, viz.: I. Of the Origin and Design of Government in General, with Concise Remarks on the English Constitution. II. Of Monarchy and Hereditary Succession. III. Thoughts on the Present State of American Affairs. IV. Of the Present Ability of America, with some Miscellaneous Reflections',
                    'thumbnail' => 'http://books.google.com/books/content?id=ioykNFna-qMC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api',
                    'user_id' => $user_id,
                ]);

            DB::table('books')->
                insert([
                    'isbn' => '0300114516',
                    'title' => 'The Republic',
                    'author' => 'Plato',
                    'page_count' => 369,
                    'description' => 'R. E. Allen’s highly regarded translations of the dialogues of Plato have been praised for their faithfulness and readability. Many years in the making, his translation of The Republic has been eagerly awaited. It comes now to crown a distinguished classicist’s efforts to make Plato’s works available in readable and accurate translations. This new, lucid translation of Plato’s greatest dialogue is the first major translation in English since the publication of F. M. Cornford’s and G. M. A. Grube’s renditions more than a generation ago. It is likely to be the standard translation for years to come.',
                    'thumbnail' => 'http://books.google.com/books/content?id=OVhOAQAAMAAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api',
                    'user_id' => $user_id,
                ]);

            DB::table('books')->
                insert([
                    'isbn' => '0008202400',
                    'title' => 'Green Eggs and Ham',
                    'author' => 'Dr. Seuss',
                    'page_count' => 64,
                    'description' => 'The laugh-out-loud classic from the iconic Dr. Seuss that inspired the Netflix series – now available in ebook, with hilarious read-along narration performed by outstanding comic talent, Adrian Edmondson. Enjoy this rhyming roller-coaster ride of mayhem anytime, anywhere!',
                    'thumbnail' => 'http://books.google.com/books/content?id=ZK7xAwAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api',
                    'user_id' => $user_id,
                ]);
        }
    }

}
