<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\UserCity;
use App\Models\UserCountry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserCityController extends Controller
{
    #this method is use for show the listing of user city
    public function index(Request $request)
    {
        $search = $request->input('search');
        if ($search) {
            $usercitys = UserCity::where('isActive', 1)
                ->where(function ($query) use ($search) {
                    $query->where('cityNameInEnglish', 'like', "%$search%")
                    ->orWhere('cityNameInHindi', 'like', "%$search%");
                })
                ->paginate(5);
        } else {
            // If no search term, just get active cities
            $usercitys = UserCity::where('isActive', 1)->paginate(5);
        }
        return view('admin.usercity.user-city-listing', compact('usercitys'));
    }

    #this method is use for show the register page
    public function userCityRegister()
    {
        $userCountries = UserCountry::where('isActive', 1)->get();
        return view('admin.usercity.user-city-register', compact('userCountries'));
    }

    #this method is use for store user city data
    public function userCityStore(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'cityNameInEnglish' => 'required|string|max:255',
            'cityNameInHindi' => 'required|string|max:255',
            'countryId' => 'required',
        ]);

        if($validator->passes()){
            $currentDateTime = getUserCurrentTime();
            UserCity::create([
                'cityNameInHindi' => $request->cityNameInHindi,
                'cityNameInEnglish' => $request->cityNameInEnglish,
                'countryId' => $request->countryId,
                'isActive' => 1,
                'created_at' => $currentDateTime,
                'created_by' => Auth::guard('admin')->user()->userId,

            ]);
            return redirect()->route('admin.user-city-listing');
        }else{
            return redirect()->route('admin.user-city-register')
                ->withInput()
                ->withErrors($validator);
        }
    }

    #this method is use for delete specific data from user city
    public function userCityDelete($id)
    {
        try {
            $currentDateTime = getUserCurrentTime();
            $userCity = UserCity::findOrFail($id);
            $userCity->isActive = 0;
            $userCity->updated_at = $currentDateTime;
            $userCity->updated_by = Auth::guard('admin')->user()->userId;
            $userCity->save();

            return redirect()->route('admin.user-city-listing')->with('success', 'User soft deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.user-city-listing')->with('error', 'There was an error deleting the user: ' . $e->getMessage());
        }
    }

    #this method is use for show the existing data in field and also we change it.
    public function userCityEdit($id){
        $userCitys = UserCity::findOrFail($id);
        $userCountries = UserCountry::all();
        return view('admin.usercity.user-city-edit', compact('userCitys', 'userCountries'));
    }

    public function userCityUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'cityNameInEnglish' => 'required|string|max:255',
            'cityNameInHindi' => 'required|string|max:255',
            'countryId' => 'required',
        ]);

        if ($validator->passes()) {
            $user = UserCity::findOrFail($id);
            $currentDateTime = getUserCurrentTime();
            // Update the user with additional fields
            $user->update([
                'cityNameInHindi' => $request->cityNameInHindi,
                'cityNameInEnglish' => $request->cityNameInEnglish,
                'countryId' => $request->countryId,
                'isActive' => 1,
                'updated_at' => $currentDateTime,
                'updated_by' => Auth::guard('admin')->user()->userId,
            ]);

            return redirect()->route('admin.user-city-listing')->with('success', 'User updated successfully.');
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
    }
}
