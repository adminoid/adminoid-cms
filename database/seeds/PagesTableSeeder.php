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
<h2>About Adminoid CMS</h2>
<p>When creating the Adminoid CMS, I was inspired by the <a href='https://modx.com/'>modx cms</a>, with which I used to have worked. Made with <a href='https://laravel.com/'>Laravel</a> and <a href='https://vuejs.org/'>Vue.js</a>.</p>

<p>
This is a simple but extensible SEO-friendly content management system,
based on tree-like data representation and management through
TreeView, in which you can simply drag the page nodes for them
moving.
</p>


<h2>Features</h2>

<ul>
    <li>Uri (page addresses) are automatically generated when you change or
    moving</li>
    <li>If the page moves, the system remembers all past uri and
    automatically redirects through 301 redirects to the current address</li>
    <li>Automatic generation of sitemap.xml</li>
    <li>All pages are one eloquent object, but they can be extended, added
    new page types with additional properties and methods</li>
    <li>The contents of the page are edited by default with wysiwyg
    summernote editor compatible with twitter bootstrap. Uploading images to
    editor occurs in a folder with the same name as the uri of the page to which
    owns the picture</li>
    <li>The default template is implementing using the twitter bootstrap framework</li>
</ul>
<p>
<strong>Demo:</strong> <a href='https://cms.adminoid.com/admin-panel/'>https://cms.adminoid.com/admin-panel/</a><br>
<strong>Login:</strong> admin@adminoid.com<br>
<strong>Password:</strong> password
</p>
<p><strong>Repo:</strong> <a href='https://github.com/adminoid/adminoid-cms'>https://github.com/adminoid/adminoid-cms</a></p>
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
