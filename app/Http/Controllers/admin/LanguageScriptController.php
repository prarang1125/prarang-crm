<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Mlanguagescript;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LanguageScriptController extends Controller
{
    #this method is for show the listing of language script data
    public function index()
    {
        $languagescripts = Mlanguagescript::where('isActive', 1)->get();
        return view('admin.languagescript.languagescript-listing', compact('languagescripts'));
    }

    #this method is use for show add new language regist
    public function languagescriptRegister()
    {
        return view('admin.languagescript.languagescript-register');
    }

    #this method is use for store the language script data
    public function languagescriptStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'language' => 'required|string|max:255',
            'languageInUnicode' => 'required|string|max:255',
            'languageUnicode' => 'required|string|max:255',
        ]);

        if($validator->passes())
        {
            $currentDateTime = getUserCurrentTime();
            $languageScript = new Mlanguagescript();
            $languageScript->language = $request->language;
            $languageScript->languageInUnicode = $request->languageInUnicode;
            $languageScript->languageUnicode = $request->languageUnicode;
            $languageScript->isActive = 1;
            $languageScript->created_at = $currentDateTime;
            $languageScript->created_by = Auth::guard('admin')->user()->userId;
            $languageScript->save();
            return redirect()->route('admin.languagescript-listing');
        }else{
            return redirect()->route('admin.languagescript-register')
                ->withErrors($validator);
        }
    }

    #this method is use for delete specific language script
    public function languagescriptDelete($id)
    {
        try {
            $currentDateTime = getUserCurrentTime();
            $languagescript = Mlanguagescript::findOrFail($id);
            $languagescript->isActive = 0;
            $languagescript->updated_at = $currentDateTime;
            $languagescript->updated_by = Auth::guard('admin')->user()->userId;
            $languagescript->save();

            return redirect()->route('admin.languagescript-listing')->with('success', 'User soft deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.languagescript-listing')->with('error', 'There was an error deleting the user: ' . $e->getMessage());
        }
    }

    #this method is use for edit language script
    public function languagescriptEdit($id)
    {
        $languagescript = Mlanguagescript::findOrFail($id);
        return view('admin.languagescript.languagescript-edit' , compact('languagescript'));
    }

    #this method is use for update language script
    public function languagescriptUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'language' => 'required|string|max:255',
            'languageInUnicode' => 'required|string|max:255',
            'languageUnicode' => 'required|string|max:255',
        ]);

        if ($validator->passes()) {

            $languagescript = Mlanguagescript::findOrFail($id);
            $currentDateTime = getUserCurrentTime();

            $languagescript->update([
                'language' => $request->language,
                'languageInUnicode' => $request->languageInUnicode,
                'languageUnicode' => $request->languageUnicode,
                'updated_at' => $currentDateTime,
                'updated_by' => Auth::guard('admin')->user()->userId,
            ]);

            return redirect()->route('admin.languagescript-listing')->with('success', 'User updated successfully.');
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
    }

}
