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
                    'thumbnail' => 'data:image/jpeg;base64,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',
                    'user_id' => $user_id,
                ]);

            DB::table('books')->
                insert([
                    'isbn' => '0300114516',
                    'title' => 'The Republic',
                    'author' => 'Plato',
                    'page_count' => 369,
                    'description' => 'R. E. Allen’s highly regarded translations of the dialogues of Plato have been praised for their faithfulness and readability. Many years in the making, his translation of The Republic has been eagerly awaited. It comes now to crown a distinguished classicist’s efforts to make Plato’s works available in readable and accurate translations. This new, lucid translation of Plato’s greatest dialogue is the first major translation in English since the publication of F. M. Cornford’s and G. M. A. Grube’s renditions more than a generation ago. It is likely to be the standard translation for years to come.',
                    'thumbnail' => 'data:image/jpeg;base64,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',
                    'user_id' => $user_id,
                ]);

            DB::table('books')->
                insert([
                    'isbn' => '0008202400',
                    'title' => 'Green Eggs and Ham',
                    'author' => 'Dr. Seuss',
                    'page_count' => 64,
                    'description' => 'The laugh-out-loud classic from the iconic Dr. Seuss that inspired the Netflix series – now available in ebook, with hilarious read-along narration performed by outstanding comic talent, Adrian Edmondson. Enjoy this rhyming roller-coaster ride of mayhem anytime, anywhere!',
                    'thumbnail' => 'data:image/jpeg;base64,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',
                    'user_id' => $user_id,
                ]);
        }
    }

}
