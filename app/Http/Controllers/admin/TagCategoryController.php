<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Mtagcategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TagCategoryController extends Controller
{
    #this method is use for show the listing of tag category
    public function index(Request $request)
    {
        $query = Mtagcategory::where('isActive', 1);

        if ($request->has('search') && $request->input('search') != '') {
            $keywords = explode(' ', $request->input('search'));
            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhere('tagCategoryInEnglish', 'LIKE', "%{$keyword}%")
                    ->orWhere('tagCategoryInUnicode', 'LIKE', "%{$keyword}%");
                }
            });
        }

        $mtagcategorys = $query->paginate(30);

        return view('admin.tagcategory.tag-category-listing', compact('mtagcategorys'));
    }

    #this method is use for show the page of tag category register
    public function tagCategoryRegister()
    {
        return view('admin.tagcategory.tag-category-register');
    }

    #this method is use for store tag category register
    public function tagCategoryStore(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'tagCategoryInEnglish' => 'required|string|max:255',
            'tagCategoryInUnicode' => 'required|string|max:255',
        ]);

        if($validator->passes())
        {
            $currentDateTime = getUserCurrentTime();
            $mtagcategorys = new Mtagcategory();
            $mtagcategorys->tagCategoryInEnglish = $request->tagCategoryInEnglish;
            $mtagcategorys->tagCategoryInUnicode = $request->tagCategoryInUnicode;
            $mtagcategorys->isActive = 1;
            $mtagcategorys->created_at = $currentDateTime;
            $mtagcategorys->created_by = Auth::guard('admin')->user()->userId;
            $mtagcategorys->save();
            return redirect()->route('admin.tag-category-listing')->with('success', 'Tag Category created successfully.');
        }else{
            return redirect()->route('admin.tag-category-register')
                ->withErrors($validator)
                ->withInput();
        }
    }
    #this method is use for delete specific tag category
    public function tagCategoryDelete($id)
    {
        try {
            $currentDateTime = getUserCurrentTime();
            $mtagcategory = Mtagcategory::findOrFail($id);
            $mtagcategory->isActive = 0;
            $mtagcategory->updated_at = $currentDateTime;
            $mtagcategory->updated_by = Auth::guard('admin')->user()->userId;
            $mtagcategory->save();

            return redirect()->route('admin.tag-category-listing')->with('success', 'User soft deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.tag-category-listing')->with('error', 'There was an error deleting the user: ' . $e->getMessage());
        }
    }

    #this method is use for edit tag category page
    public function tagCategoryEdit($id)
    {
        $mtagcategory = Mtagcategory::findOrFail($id);
        return view('admin.tagcategory.tag-category-edit' , compact('mtagcategory'));
    }

    #this method is use for update tag category data
    public function tagCategoryUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tagCategoryInEnglish' => 'required|string|max:255',
            'tagCategoryInUnicode' => 'required|string|max:255',
        ]);

        if ($validator->passes()) {
            $mtagcategory = Mtagcategory::find($id);

            if ($mtagcategory) {
                $currentDateTime = getUserCurrentTime();

                $mtagcategory->update([
                    'tagCategoryInUnicode' => $request->tagCategoryInUnicode,
                    'tagCategoryInEnglish' => $request->tagCategoryInEnglish,
                    'updated_at' => $currentDateTime,
                    'updated_by' => Auth::guard('admin')->user()->userId,
                ]);

                return redirect()->route('admin.tag-category-listing')->with('success', 'Region updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Region not found.');
            }
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
    }
}
