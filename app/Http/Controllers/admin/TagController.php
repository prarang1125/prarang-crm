<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Mtag;
use App\Models\Mtagcategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    #this method is use for show the listing of tag
    public function index(Request $request)
    {
        $query = Mtag::with('tagcategory')->where('isActive', 1);

        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('tagInEnglish', 'LIKE', "%{$search}%")
                ->orWhere('tagInUnicode', 'LIKE', "%{$search}%");
            });
        }

        $mtags = $query->paginate(5);
        return view('admin.tag.tag-listing', compact('mtags'));
    }

    #this method is use for create/register new tag form page
    public function tagRegister(){
        $mtagcategorys = Mtagcategory::all();
        return view('admin.tag.tag-register', compact('mtagcategorys'));
    }

    #this method is use for store tag data
    public function tagStore(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'tagInEnglish' => 'required|string|max:255',
            'tagInUnicode' => 'required|string|max:255',
            'tagCategoryId' => 'required',
        ]);

        if($validator->passes())
        {
            $currentDateTime = getUserCurrentTime();
            $mtag = new Mtag();
            $mtag->tagInEnglish = $request->tagInEnglish;
            $mtag->tagInUnicode = $request->tagInUnicode;
            $mtag->tagCategoryId = $request->tagCategoryId;
            $mtag->isActive = 1;
            $mtag->created_at = $currentDateTime;
            $mtag->created_by = Auth::guard('admin')->user()->userId;
            $mtag->save();
            return redirect()->route('admin.tag-listing')->with('success', 'Tag created successfully.');
        }else{
            return redirect()->route('admin.tag-register')
                ->withErrors($validator)
                ->withInput();
        }
    }

    #this method is use for delete specific tag
    public function tagDelete($id)
    {
        try {
            $currentDateTime = getUserCurrentTime();
            $mtagcategory = Mtag::findOrFail($id);
            $mtagcategory->isActive = 0;
            $mtagcategory->updated_at = $currentDateTime;
            $mtagcategory->updated_by = Auth::guard('admin')->user()->userId;
            $mtagcategory->save();

            return redirect()->route('admin.tag-listing')->with('success', 'User soft deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.tag-listing')->with('error', 'There was an error deleting the user: ' . $e->getMessage());
        }
    }

    #this method is use for edit tag data
    public function tagEdit($id)
    {
        $mtags = Mtag::findOrFail($id);
        $mtagcategorys = Mtagcategory::all();
        return view('admin.tag.tag-edit' , compact('mtags', 'mtagcategorys'));
    }

    public function tagUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tagInEnglish' => 'required|string|max:255',
            'tagInUnicode' => 'required|string|max:255',
            'tagCategoryId' => 'required',
        ]);

        if ($validator->passes()) {
            $mtag = Mtag::findOrFail($id);
            $currentDateTime = getUserCurrentTime();
            // Update the user with additional fields
            $mtag->update([
                'tagInEnglish' => $request->tagInEnglish,
                'tagInUnicode' => $request->tagInUnicode,
                'tagCategoryId' => $request->tagCategoryId,
                'isActive' => 1,
                'updated_at' => $currentDateTime,
                'updated_by' => Auth::guard('admin')->user()->userId,
            ]);

            return redirect()->route('admin.tag-listing')->with('success', 'tag updated successfully.');
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
    }
}
