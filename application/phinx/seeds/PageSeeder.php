<?php

use Phinx\Seed\AbstractSeed;

class PageSeeder extends AbstractSeed
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
                'id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'id' => 2,
                'created_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'id' => 3,
                'created_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'id' => 4,
                'created_at' => date('Y-m-d H:i:s'),
            )
        );

        $this->table('duo_pages')->insert($data)->save();

        $data = array(
            array(
                'page_id' => 1,
                'lang' => 'pl',
                'title' => 'O firmie',
                'created_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'page_id' => 1,
                'lang' => 'de',
                'title' => '[DE] O firmie',
                'created_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'page_id' => 1,
                'lang' => 'en',
                'title' => '[EN] O firmie',
                'created_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'page_id' => 1,
                'lang' => 'ru',
                'title' => '[RU] O firmie',
                'created_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'page_id' => 2,
                'lang' => 'pl',
                'title' => 'Zamówienie',
                'created_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'page_id' => 2,
                'lang' => 'de',
                'title' => '[DE] Zamówienie',
                'created_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'page_id' => 2,
                'lang' => 'en',
                'title' => '[EN] Zamówienie',
                'created_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'page_id' => 2,
                'lang' => 'ru',
                'title' => '[RU] Zamówienie',
                'created_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'page_id' => 3,
                'lang' => 'pl',
                'title' => 'Gdzie kupic',
                'created_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'page_id' => 3,
                'lang' => 'de',
                'title' => '[DE] Gdzie kupic',
                'created_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'page_id' => 3,
                'lang' => 'en',
                'title' => '[EN] Gdzie kupic',
                'created_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'page_id' => 3,
                'lang' => 'ru',
                'title' => '[RU] Gdzie kupic',
                'created_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'page_id' => 4,
                'lang' => 'pl',
                'title' => 'Kontakt',
                'created_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'page_id' => 4,
                'lang' => 'de',
                'title' => '[DE] Kontakt',
                'created_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'page_id' => 4,
                'lang' => 'en',
                'title' => '[EN] Kontakt',
                'created_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'page_id' => 4,
                'lang' => 'ru',
                'title' => '[RU] Kontakt',
                'created_at' => date('Y-m-d H:i:s'),
            )
        );

        $this->table('duo_pages_translations')->insert($data)->save();
    }
}
