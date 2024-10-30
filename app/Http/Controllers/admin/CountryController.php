<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Mcountry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CountryController extends Controller
{
    #this method is use for show country listing
    public function index()
    {
        $mcountrys = Mcountry::where('isActive', 1)->get();
        return view('admin.country.country-listing', compact('mcountrys'));
    }

    #this method is use for show country register page
    public function countryRegister()
    {
        return view('admin.country.country-register');
    }

    #this method is use for store country data
    public function countryStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'countryNameInEnglish' => 'required|string|max:255',
            'countryNameInUnicode' => 'required|string|max:255',
            'countryImage' => 'required|image|max:2048',
            'countryMap' => 'required|image|max:2048',
            'isCultureNature' => 'required|boolean',
            'content' => 'required|string',
        ]);

        if($validator->passes()){
            $currentDateTime = getUserCurrentTime();
            $countryImage = $request->file('countryImage');
            $countryImageName = time() . '_' . $countryImage->getClientOriginalName();
            $countryImage->move(public_path('uploads/country_images'), $countryImageName);

            $countryMap = $request->file('countryMap');
            $countryMapName = time() . '_' . $countryMap->getClientOriginalName();
            $countryMap->move(public_path('uploads/country_maps'), $countryMapName);

            $lastId = Mcountry::max('countryId');
            $newId = $lastId ? $lastId + 1 : 1;

            $mcountry = new Mcountry();
            $mcountry->countryCode = 'CON' . $newId;
            $mcountry->countryNameInUnicode = $request->countryNameInUnicode;
            $mcountry->countryNameInEnglish = $request->countryNameInEnglish;
            $mcountry->Image = $countryImageName;
            $mcountry->Map = $countryMapName;
            $mcountry->Image_Name   = $countryImageName;
            $mcountry->Map_Name     = $countryMapName;
            $mcountry->Culture_Nature = $request->isCultureNature;
            $mcountry->text = $request->content;
            $mcountry->isActive = 1;
            $mcountry->created_at = $currentDateTime;
            $mcountry->created_by = Auth::guard('admin')->user()->userId;
            $mcountry->save();
            return redirect()->route('admin.country-listing')->with('success', 'Country created successfully.');
        }else{
            return redirect()->route('admin.country-register')
                ->withErrors($validator)
                ->withInput();
        }
    }

    #this method is use for delete specific data
    public function countrytDelete($id)
    {
        try {
            $currentDateTime = getUserCurrentTime();
            $mcountry = Mcountry::findOrFail($id);
            $mcountry->isActive = 0;
            $mcountry->updated_at = $currentDateTime;
            $mcountry->updated_by = Auth::guard('admin')->user()->userId;
            $mcountry->save();

            return redirect()->route('admin.country-listing')->with('success', 'User soft deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.country-listing')->with('error', 'There was an error deleting the user: ' . $e->getMessage());
        }
    }

    #this method is use for edit mcountry data
    public function countryEdit($id)
    {
        $mcountry = Mcountry::findOrFail($id);
        return view('admin.country.country-edit' , compact('mcountry'));
    }

    #this method is use for update the country data
    public function countryUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'countryNameInUnicode' => 'required|string|max:255',
            'countryNameInEnglish' => 'required|string|max:255',
            'countryImage' => 'nullable|image|max:2048',
            'countryMap' => 'nullable|image|max:2048',
            'isCultureNature' => 'required|boolean',
            'content' => 'required|string',
        ]);

        if ($validator->passes()) {
            $mcountry = Mcountry::find($id);

            if ($mcountry) {
                $currentDateTime = getUserCurrentTime();

                // Handle countryImage upload if present
                if ($request->hasFile('countryImage')) {
                    $countryImage = $request->file('countryImage');
                    $countryImageName = time() . '_' . $countryImage->getClientOriginalName();
                    $countryImage->move(public_path('uploads/country_images'), $countryImageName);
                    // Debugging: Log the filename
                    Log::info("Generated image name: " . $countryImageName);
                    // Update the image field in the model
                    $mcountry->Image = $countryImageName;
                    $mcountry->Image_Name = $countryImageName;
                }

                // Handle countryMap upload if present
                if ($request->hasFile('countryMap')) {
                    $countryMap = $request->file('countryMap');
                    $countryMapName = time() . '_' . $countryMap->getClientOriginalName();
                    $countryMap->move(public_path('uploads/country_maps'), $countryMapName);
                    // Debugging: Log the filename
                    Log::info("Generated map name: " . $countryMapName);
                    // Update the map field in the model
                    $mcountry->Map = $countryMapName;
                    $mcountry->Map_Name = $countryMapName;
                }


                if (empty($request->file('countryImage')) || empty($request->file('countryMap'))) {
                    $countryImageName = $mcountry->Image;
                    $countryMapName = $mcountry->Map;
                }

                // Update other fields
                $mcountry->update([
                    'countryNameInUnicode' => $request->countryNameInUnicode,
                    'countryNameInEnglish' => $request->countryNameInEnglish,
                    'Image' => $countryImageName,
                    'Map' => $countryMapName,
                    'Image_Name' => $countryImageName,
                    'Map_Name' => $countryMapName,
                    'Culture_Nature' => $request->isCultureNature,
                    'text' => $request->content,
                    'isActive' => $request->isCultureNature,
                    'updated_at' => $currentDateTime,
                    'updated_by' => Auth::guard('admin')->user()->userId,
                ]);

                return redirect()->route('admin.country-listing')->with('success', 'Country updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Country not found.');
            }
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
    }

}
?>
