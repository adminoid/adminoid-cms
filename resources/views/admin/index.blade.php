@extends('admin/admin')

@section('title', 'Admin panel')

@section('content')

    <div class="row">
        <loading :active.sync="isLoading"></loading>
        <div class="col-sm-4">
            <sortable-tree-ajax :data="treeData" attr="name" id="id">
                <template slot-scope="{item}">
                    <span>@{{item.name}}</span>
                </template>
            </sortable-tree-ajax>
            <div id="modal-place">
                <div id="modals"></div>
            </div>
        </div>
        <div class="col-sm-8">
            <router-view></router-view>
        </div>
    </div>

@endsection
