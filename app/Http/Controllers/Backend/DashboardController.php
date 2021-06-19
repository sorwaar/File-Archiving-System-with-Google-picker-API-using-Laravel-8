<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $files = File::with(['user','categories'])->where(['user_id'=> Auth::id(), 'status'=>1])->get()->all();
        $categories = Category::with(['user','files','children'])->where(['created_by'=> Auth::id(),'parent_id'=>null])->get()->all();
        $uncategorised = File::with(['user','categories'])->where(['category_id'=>0,'user_id'=> Auth::id(), 'status'=>1,])->get()->all();
        //dd($uncategorised);
        //dd($categories->toArray());
        // dd(!empty($files->toArray()));
        $path = asset('storage/uploads/main/'.md5(Auth::id()));
        //dd($path);
        return view('backend.pages.dashboard.index',compact('files','categories','uncategorised','path'));
    }
}
