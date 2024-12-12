<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\UserCountry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserCountryController extends Controller
{
    #this method is use for show the listing of user country
    public function index(Request $request)
    {
        $query = UserCountry::where('isActive', 1);

        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('countryNameInEnglish', 'LIKE', "%{$search}%")
                ->orWhere('countryNameInHindi', 'LIKE', "%{$search}%");
            });
        }

        $userCountrys = $query->paginate(2);

        return view('admin.usercountry.user-country-listing', compact('userCountrys'));
    }


    #this method is use for show register for of user country
    public function userCountryRegister()
    {
        return view('admin.usercountry.user-country-register');
    }

    #this method is use for store user country data
    public function userCountryStore(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'countryNameInEnglish' => 'required|string|max:255',
            'countryNameInHindi' => 'required|string|max:255',
        ]);

        if($validator->passes())
        {
            $lastId = UserCountry::max('countryId');
            $newId = $lastId ? $lastId + 1 : 1;

            $currentDateTime = getUserCurrentTime();
            $userCountry = new UserCountry();
            $userCountry->countryCode = 'UCON'.$newId;
            $userCountry->countryNameInHindi  = $request->countryNameInHindi;
            $userCountry->countryNameInEnglish = $request->countryNameInEnglish;
            $userCountry->isActive = 1;
            $userCountry->created_at = $currentDateTime;
            $userCountry->created_by = Auth::guard('admin')->user()->userId;
            $userCountry->save();
            return redirect()->route('admin.user-country-listing')->with('success', 'Tag Category created successfully.');
        }else{
            return redirect()->route('admin.user-country-register')
                ->withErrors($validator)
                ->withInput();
        }
    }

    #this method is use for delete specific user country
    public function userCountryDelete($id)
    {
        try {
            $currentDateTime = getUserCurrentTime();
            $mtagcategory = UserCountry::findOrFail($id);
            $mtagcategory->isActive = 0;
            $mtagcategory->updated_at = $currentDateTime;
            $mtagcategory->updated_by = Auth::guard('admin')->user()->userId;
            $mtagcategory->save();

            return redirect()->route('admin.user-country-listing')->with('success', 'User soft deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.user-country-listing')->with('error', 'There was an error deleting the user: ' . $e->getMessage());
        }
    }

    #this method is use for edit user country
    public function userCountryEdit($id)
    {
        $userCountry = UserCountry::findOrFail($id);
        return view('admin.usercountry.user-country-edit' , compact('userCountry'));
    }

    #this method is use for update data user country
    public function userCountryUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'countryNameInEnglish' => 'required|string|max:255',
            'countryNameInHindi' => 'required|string|max:255',
        ]);

        if ($validator->passes()) {
            $userCountry = UserCountry::find($id);

            if ($userCountry) {
                $currentDateTime = getUserCurrentTime();

                $userCountry->update([
                    'countryNameInHindi' => $request->countryNameInHindi,
                    'countryNameInEnglish' => $request->countryNameInEnglish,
                    'updated_at' => $currentDateTime,
                    'updated_by' => Auth::guard('admin')->user()->userId,
                ]);

                return redirect()->route('admin.user-country-listing')->with('success', 'Region updated successfully.');
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
