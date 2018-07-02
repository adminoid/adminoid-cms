<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        App\Page::create([
            'name' => 'Main page',
            'title' => 'Adminoid CMS',
            'content' => "
                <p>Built with Laravel and Vue.js frameworks.</p>
                <p>Universal SEO friendly content management system based on extendable tree data management and drag&drop TreeView.</p>
                <p>Futures:</p>
                <ul>
                    <li>Uris automatic generated with nested pages
</li>
                    <li>If pages moves, system remember all old uri and make automatic 301 redirect all their to new location</li>
                    <li>Pages is one eloquent object, but it available for extend</li>
                    <li>Page content is being edited with summernote wysiwyg editor</li>
                    <li>Images uploading to folder in public like uri of his page</li>
                </ul>
            ",
            'slug' => '/',
            'template' => 'pages.simple',
            'active' => true,
        ]);

        $pageWithChildren1 = factory(App\Page::class)->make();
        $pageWithChildren1->save();
        $childrenPages1 = factory(App\Page::class, random_int(3,7))->make();
        foreach ($childrenPages1 as $childrenPage) {
            $pageWithChildren1->appendNode($childrenPage);
        }

        $pages1 = factory(App\Page::class, 3)->make();
        foreach ($pages1 as $item) {
            $item->save();
        }
        $pageWithChildren2 = factory(App\Page::class)->make();
        $pageWithChildren2->save();
        $childrenPages2 = factory(App\Page::class, random_int(3,7))->make();
        foreach ($childrenPages2 as $childrenPage) {
            $pageWithChildren2->appendNode($childrenPage);
        }

        $pages2 = factory(App\Page::class, 2)->make();
        foreach ($pages2 as $item) {
            $item->save();
        }
    }
}
