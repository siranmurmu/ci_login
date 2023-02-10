<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Image;

class CategoryController extends Controller
{
    public function AllCategory(){
        $categories = Category::latest()->get();
        //dd($categories);
        return view('category.all', compact('categories'));
    }

    public function AddCategory(){
        return view('category.add');
    }

    public function StoreCategory(Request $request){
        //dd($request->all());
        $image = $request->file('category_image');
        $new_name = time().'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(300,300)->save('upload/category/'.$new_name);
        $save_url = 'upload/category/'.$new_name;

        Category::insert([
            'category_name' => $request->category_name,
            'category_image' => $save_url,
            'category_status' => $request->category_status,
        ]);

        return redirect()->route('all.category');

    }

    public function EditCategory($id){
        //dd($id);
        $category = Category::find($id);
        //dd($category);
        return view('category.edit', compact('category'));
    }

    public function UpdateCategory(Request $request){
        //dd($request->all());
        $category_id = $request->id;
        $old_img = $request->old_image;

        if($request->file('category_image')){
            $image = $request->file('category_image');
            $new_name = time().'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(300,300)->save('upload/category/'.$new_name);
            $save_url = 'upload/category/'.$new_name;

            if(file_exists($old_img)){
                unlink($old_img);
            }

            Category::find($category_id)->update([
                'category_name' => $request->category_name,
                'category_image' => $save_url,
                'category_status' => $request->category_status,
            ]);

            return redirect()->route('all.category');
        } else{
            Category::find($category_id)->update([
                'category_name' => $request->category_name,
                'category_status' => $request->category_status,
            ]);

            return redirect()->route('all.category');
        }
     }

     public function DeleteCategory($id){
        $category = Category::find($id);
        $img = $category->category_image;
        unlink($img);
        Category::find($id)->delete();
        return redirect()->route('all.category');
     }
}
