<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Page;
use Auth;
use App\PageOldUri;

class PageController extends Controller
{

    private $opened = [];

    public function index()
    {
        return view('admin/index');
    }

    public function loadBranch(Request $request)
    {
        $id = $request->get('id');
        $compiled = [];
        if ($id == 'root') {
            $compiled['data'] = ['id' => 'root', 'name' => 'root', 'locked_content' => 1, 'locked_move' => 1, 'active' => 0, 'has_children' => 1, 'children' => []];
            $compiled['data']['children'] = Page::whereNull('parent_id')->orderBy('_lft')->get(['id', 'name', 'title']);
        }
        else {
            $parent = Page::find($id);
            $compiled['data']['children'] = $parent->sortChildren()->get(['id', 'name', 'title']);
        }
        $compiled['msg'] = __('admin-panel.branch_loaded');
        return response()->json($compiled, 200);
    }

    public function tree(Request $request)
    {
        if ($opened = $request->get('opened')) {
            $this->opened = $opened;
        }
        $compiled['data'] = ['id' => 'root', 'name' => 'root', 'locked_content' => 1, 'locked_move' => 1, 'active' => 0, 'has_children' => 1];
        $tree = $this->subTree();
        $compiled['data']['children'] = $tree;
        $compiled['msg'] = __('admin-panel.tree_loaded');
        return response()->json($compiled, 200);
    }

    private function subTree($items = false)
    {
        // $this->opened

        if (!$items) {
            $items = Page::whereNull('parent_id')->orderBy('_lft')->get();
        }
        $result = [];
        foreach ($items as $item) {
            $resultItem = [];
            $resultItem['id'] = $item->id;
            $resultItem['name'] = $item->name;
            $resultItem['locked_content'] = $item->locked_content;
            $resultItem['locked_move'] = $item->locked_move;
            $resultItem['active'] = $item->active;
            $resultItem['has_children'] = ($item->children->count() > 0);
            if ($resultItem['has_children'] && in_array($item->id, $this->opened)) {
                $resultItem['initOpen'] = true;
                $resultItem['children'] = $this->subTree($item->sortChildren()->get());
            }
            $result[] = $resultItem;
        }
        return $result;
    }

    public function deletePage(Request $request)
    {
        $pageId = $request->get('id');
        $page = Page::findOrFail($pageId);
        $page->oldUris()->delete();
        $page->delete();
        return response(['msg' => __('admin-panel.page_deleted')]);
    }

    public function treeMove(Request $request)
    {
//        abort(403, 'Unauthorized action. yoyo');
        /*
            "fromParentId" => 3
            "toParentId" => 3
            "newIndex" => 3
         */
//        dd($request->all());
        $nodeId = $request->get('nodeId');
        $newIndex = $request->get('newIndex');
        $toParentId = $request->get('toParentId');
        $movedNode = Page::find($nodeId);

        // if newPosition is odd (no siblings) - just apply appendNode
        if ($newIndex != intval($newIndex)) {
            if ($toParentId == 'root') {
                $movedNode->saveAsRoot();
            }
            else {
                $parent = Page::find($toParentId);
                $parent->appendNode($movedNode);
            }
        }
        else {
            if ($toParentId == 'root') {
                $neighbors = Page::whereNull('parent_id')->orderBy('_lft')->get();
            }
            else {
                $neighbors = Page::find($toParentId)->sortChildren()->get();
            }

            // if newPosition is 0 - get first children and insert before them
            if ($newIndex == 0) {
                $firstChildren = $neighbors[0];
                $movedNode->beforeNode($firstChildren)->save();
            }
            // else - insert afterNode
            else {
                $neighbor = $neighbors[$newIndex-1];
                $movedNode->afterNode($neighbor)->save();
            }
        }
        return response(['msg' => __('admin-panel.page_moved')]);
    }

    public function page(Request $request)
    {
        $id = request('id');
        $page = Page::findOrFail($id, ['name', 'title', 'content', 'slug', 'active', 'locked_content', 'locked_move', 'template']);
        if ($page) return response(['msg' => __('admin-panel.page_loaded'), 'page' => $page]);
    }

    public function createPage(Request $request)
    {
        // name: '', title: '', content: '', slug: ''
        $request->validate([
            'name' => 'required|max:20',
            'title' => 'required|max:255',
            'content' => 'required',
            'slug' => 'required',
            'parent_id' => 'required',
        ]);

        $newPageData = $request->all('name', 'title', 'content', 'slug');
        $parentId = $request->get('parent_id');
        if ($parentId == 'root') {
            $newPage = Page::create($newPageData);
        }
        else {
            $parent = Page::find($parentId);
            $newPage = $parent->children()->create($newPageData);
        }
        PageOldUri::where('uri', '=', $newPage->uri)->delete();
        return response()->json(['msg' => __('admin-panel.page_saved'), 'id' => $newPage->id], 200);

    }

    public function savePage(Request $request)
    {
        // name: '', title: '', content: '', slug: ''
        $validatedData = $request->validate([
            'id' => 'required',
            'name' => 'required|max:20',
            'title' => 'required|max:255',
            'content' => 'required',
            'slug' => 'required',
        ]);


        $pageData = $request->all('name', 'title', 'content', 'slug');
        $page = Page::findOrFail($request->get('id'));
        if ($page->locked) {
            unset($pageData['content']);
        }
        if ($page->update($pageData)) {
            return response()->json(['msg' => __('admin-panel.page_saved')], 200);
        }
//        $newPage = Page::create($newPageData);
//        $newPage->appendToNode($parent)->save();
    }

    public function imageUpload(Request $request)
    {
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $page = Page::findOrFail($request->get('page_id'));
        $file->move(public_path() . '/' . $page->getImageFolderInPublic(), $filename);
        return '/' . $page->getImageFolderInPublic() . '/' . $filename;
//        return public_path() . '/' . $page->getImageFolderInPublic() . '/' . $filename;
    }
}
