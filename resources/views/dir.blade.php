@extends('layouts.app')

@section('content')
<explorer></explorer>
<rc></rc>
<rename></rename>

<ul class="tree" role="tree" id="myTree">
    <li class="tree-branch hide" data-template="treebranch" role="treeitem" aria-expanded="false">
        <div class="tree-branch-header">
            <button type="button" class="tree-branch-name">
                <span class="glyphicon icon-caret glyphicon-play"></span>
                <span class="glyphicon icon-folder glyphicon-folder-close"></span>
                <span class="tree-label"></span>
            </button>
        </div>
        <ul class="tree-branch-children" role="group"></ul>
        <div class="tree-loader" role="alert">Loading...</div>
    </li>
    <li class="tree-item hide" data-template="treeitem" role="treeitem">
        <button type="button" class="tree-item-name">
            <span class="glyphicon icon-item fueluxicon-bullet"></span>
            <span class="tree-label"></span>
        </button>
    </li>
</ul>
@endsection

