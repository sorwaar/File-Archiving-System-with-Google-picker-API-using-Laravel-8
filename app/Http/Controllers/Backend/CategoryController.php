<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();

      return view('backend.pages.categories.index')->with([
        'categories'  => $categories
      ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $this->validate($request, [
                'name'      => 'required|min:3|max:255|string',
                'parent_id' => 'sometimes|nullable|numeric'
        ]);

        Category::create($validatedData);

        return redirect()->route('category.index')->withSuccess('You have successfully created a Category!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
{
        $validatedData = $this->validate($request, [
            'name'  => 'required|min:3|max:255|string'
        ]);

        $category->update($validatedData);

        return redirect()->route('category.index')->withSuccess('You have successfully updated a Category!');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }

    public function getSubCategories(Request $request){

        $categoryId = $request->categoryId;
        $html = '';
        $html  .= "<option>Select Sub-Category</option>";
        $subCat = Category::with('children')->where('parent_id',$categoryId)->get();
        $hatData = isset($subCat[0])?true:false;

        foreach ($subCat as $key => $sub) {
            $html .= '<option value="'.$sub->id.'">'.$sub->name.'</option>';
        }
        $data = [
            'hasSub'   => $hatData,
            'html'      => $html

        ];
        return $data;
    }
}
