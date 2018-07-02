<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use NodeTrait;
    use SoftDeletes;

    public $backup = false;
    protected $fillable = ['name', 'slug', 'title', 'content', 'locked', 'active'];
    protected $dates = ['deleted_at'];
    protected $appends = ['has_children'];

    public function getHasChildrenAttribute()
    {
        return ($this->children()->count()) ? true : false;
    }

    public function absUri()
    {
        $absUri = ($this->uri == '/') ? '/' : '/' . $this->uri;
        return $absUri;
    }
    public function sortChildren()
    {
        return $this->hasMany(get_class($this), $this->getParentIdName())->orderBy('_lft')
            ->setModel($this);
    }

    protected $table = 'pages';

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function oldUris()
    {
        return $this->hasMany('App\PageOldUri');
    }

    public function delete()
    {
        $this->backup = true;
        $this->save();
        foreach ($this->descendants()->get() as $descendant) {
            $descendant->backup = true;
            $descendant->uri_backup = $descendant->uri;
            $descendant->uri = null;
            $descendant->slug_backup = $descendant->slug;
            $descendant->slug = null;
            $descendant->save();
        }
        $this->uri_backup = $this->uri;
        $this->uri = null;
        $this->slug_backup = $this->slug;
        $this->slug = null;
        $this->save();
        parent::delete();
    }

    public function paragraphedContent()
    {
        $parts = explode("\n", $this->content);
        $paragraphedContent = '<p>';
        $count = count($parts);
//        dd($count);
        foreach ($parts as $index => $part) {
            if ($part) {
                if ($index < $count-1) {
                    $paragraphedContent .= "$part</p>\n<p>";
                }
                else {
                    $paragraphedContent .= "$part</p>";
                }
            }
        }

//        dd($paragraphedContent);
        return $paragraphedContent;
    }

    public function generateUri()
    {
        $slug = $this->slug;
//        $isRoot = $this->isRoot();
//        if (!$isRoot) $parentUri = $this->parent->uri;

        if (!$this->backup) $this->uri = $this->isRoot() ? $slug : $this->parent->uri . '/' . $slug;
//        $folder = $this->getImageFolder();
//        $folderInPublic = $this->getImageFolderInPublic();
//        foreach ($this->images()->get() as $image) {
//            $image->folder = $folder;
//            $image->folder_in_public = $folderInPublic;
//            $image->save();
//        }
        return $this;
    }

    public function updateDescendantsUri()
    {
        $id = $this->id;
        $descendants = Page::descendantsOf($id);
        foreach ($descendants as $page) {
            $page->generateUri()->save();
        }
    }

    public function getImageFolder($uri = '')
    {
        return ($uri) ? 'public/img/pages/' . $uri : 'public/img/pages/' . $this->uri;
    }

    public function getImageFolderInPublic($uri = '')
    {
        return ($uri) ? 'img/pages/' . $uri : 'img/pages/' . $this->uri;
    }

//    public function makeMenu($items = false, $page = false)
//    {
//        if (!$page) return false;
//        $menu = '';
//        if (!$items) {
//            $items = Page::withDepth()->having('depth', '=', 0)->get();
//        }
//        if (!$items) return false;
//
//        foreach ($items as $item) {
//            if ($item->children->count() < 1) {
//                $additionalClasses = ($item->id == $page->id) ? ' active' : '';
//                $menu .= "<li class=\"nav-item{$additionalClasses}\">
//        <a class=\"nav-link\" href=\"/{$item->uri}\">{$item->name}</a>
//    </li>";
//            }
//            else {
//                // dropdown dropdown-toggle
//                $additionalClasses = (!$page->isRoot() && $item->children->first()->parent->id == $page->parent->id) ? ' active' : '';
//                $menu .= "<li class=\"nav-item dropdown{$additionalClasses}\">
//        <a class=\"nav-link dropdown-toggle\" href=\"/{{ $item->uri }}\" id=\"navbarDropdown-{$item->id}\" role=\"button\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
//            {$item->name}
//        </a>";
//                $test = $item->children->first()->depth;
//                $menu .= "<div class=\"dropdown-menu\" aria-labelledby=\"navbarDropdown-{$item->id}\">
//            $test
//            <div class=\"dropdown-divider\"></div>
//        </div>";
//
////                $this->makeMenu($item->children, $page);
//                echo $item->withDepth()->children()->first->depth;
//                $menu .= "</li>";
//            }
//
//            // $root->id == $page->id
//            // !$page->isRoot() && $root->children->first()->parent->id == $page->parent->id
//
//        }
//        return $menu;
//    }

}
