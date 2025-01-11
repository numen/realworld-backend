<?php

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tag\TagCollection;
use Illuminate\Http\Request;
use App\Models\Tag as TagModel;

class GetTagsController extends Controller
{
    public function __invoke(Request $request) {
        $tags = TagModel::all();
        return response(new TagCollection($tags), 200);
    }
}
