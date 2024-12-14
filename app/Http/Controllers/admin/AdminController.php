<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Validate;
use App\Models\Mrole;
use App\Models\Mlanguagescript;
use App\Models\Muser;
use Illuminate\Validation\Rule;
use App\Mail\UserRegisteredMail;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\Mcountry;
use App\Models\Mregion;
use App\Models\Mcity;
use App\Models\Chitti;


class AdminController extends Controller
{
    #this method is use for show the admin dashboard pages;
    public function index()
    {
        $upperLimit = 100;
        // Current data counts
        $totalCountries = Mcountry::where('isActive', 1)->count();
        $totalRegions   = Mregion::where('isActive', 1)->count();
        $totalMcitys    = Mcity::where('isActive', 1)->count();
        $totalLanguagescripts = Mlanguagescript::where('isActive', 1)->count();
        $totalChitti = Chitti::where('finalStatus', '!=', 'deleted')->count();
        $totalMakers = Muser::whereHas('role', function($query) {
            $query->where('roleName', 'Maker');
        })->where('isActive', 1)->count();
        $totalChecker = Muser::whereHas('role', function($query) {
            $query->where('roleName', 'Checker');
        })->where('isActive', 1)->count();
        $totalUploader = Muser::whereHas('role', function($query) {
            $query->where('roleName', 'Uploader');
        })->where('isActive', 1)->count();

        // Calculate growth percentages based on the upper limit (100)
        $growthCountries = calculatePercentage($totalCountries, $upperLimit);
        $growthRegions   = calculatePercentage($totalRegions, $upperLimit);
        $growthMcitys    = calculatePercentage($totalMcitys, $upperLimit);
        $growthLanguagescripts = calculatePercentage($totalLanguagescripts, $upperLimit);
        $growthChitti    = calculatePercentage($totalChitti, $upperLimit);
        $growthMakers    = calculatePercentage($totalMakers, $upperLimit);
        $growthChecker   = calculatePercentage($totalChecker, $upperLimit);
        $growthUploader  = calculatePercentage($totalUploader, $upperLimit);

        // Pass these values to the view
        return view('admin.dashboard', compact(
            'totalCountries', 'totalRegions', 'totalMcitys', 'totalLanguagescripts','totalChitti', 'totalMakers', 'totalChecker', 'totalUploader', 'growthCountries','growthRegions', 'growthMcitys', 'growthLanguagescripts', 'growthChitti','growthMakers', 'growthChecker', 'growthUploader'
        ));
    }
    public function userProfile(){

        $user     = Auth::guard('admin')->user();


        $roleName   = $user->role ? $user->role->roleName : 'No Role Assigned';
        $roleId     = $user->role ? $user->role->roleID : null;

        $language   = $user->languageScript ? $user->languageScript->language : 'No Language Assigned';
        $languageId = $user->languageScript ? $user->languageScript->id : null;

        return view('admin.user-profile', compact('user', 'roleName', 'language', 'roleId', 'languageId'));
    }

    #this method is use for update admin user profile
    // public function updateProfile(Request $request)
        // {
        //     $user = Auth::guard('admin')->user();

        //     $user->firstName = explode(' ', $request->input('fullName'))[0];
        //     $user->lastName = explode(' ', $request->input('fullName'))[1];
        //     $user->emailId = $request->input('emailId');
        //     $user->languageId = $request->input('languageId');
        //     $user->save();

        //     return response()->json(['success' => true]);
    // }

    #this method is use for show user listing data
    // public function userListing(Request $request) {
    //     $role = $request->query('role');
    //     if ($role) {
    //         $users = Muser::whereHas('role', function($query) use ($role) {
    //             $query->where('roleName', $role);
    //         })->get();
    //     } else {
    //         $users = Muser::with('role')->get();
    //     }
    //     return view('admin.user-listing', compact('users'));
    // }

    public function userListing(Request $request)
    {
        $search = $request->query('search');
        $role = $request->query('role');
        $language = $request->query('language');

        $query = Muser::with('role');

        // Filter by role if provided
        if ($role) {
            $query->whereHas('role', function ($q) use ($role) {
                $q->where('roleName', $role);
            });
        }

        // Search by user name or email
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('firstName', 'like', "%$search%")
                ->orWhere('lastName', 'like', "%$search%")
                ->orWhere('emailId', 'like', "%$search%");
            });
        }

        // Filter by language (1 for English, 0 for Hindi, based on your current logic)
        if ($language) {
            $query->where('languageId', $language === 'English' ? 1 : 0);
        }

        // Paginate results
        $users = $query->paginate(5);

        return view('admin.user-listing', compact('users'));
    }


    #this method is use for create/register new user form page
    public function useruRegister(){
        $roles = Mrole::all();
        $languagescripts = Mlanguagescript::all();
        return view('admin.user-register', compact('roles', 'languagescripts'));
    }

    #this method is use for store/save data in db
    public function userStore(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'emailId' => 'required|email|unique:muser,emailId',
            'empPassword' => 'required|string|min:5',
            'roleId' => 'required|exists:mrole,roleID',
            'languageScriptId' => 'required|exists:mlanguagescript,id',
        ]);

        if($validator->passes()){
            $currentDateTime = getUserCurrentTime();
            Muser::create([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'emailId' => $request->emailId,
                'empPassword' => bcrypt($request->empPassword),
                'roleId' => $request->roleId,
                'languageId' => $request->languageScriptId,
                'created_at' => $currentDateTime,
                'created_by' => Auth::guard('admin')->user()->userId,
                'isActive' => 1
            ]);

            // Determine login link based on roleId
            $loginUrl = $request->roleId == 1 ? url('/admin/login') : url('/accounts/login');
            // dd($request->roleId);
            // dd($loginUrl);
            // Mail::to('rohit.kprarang@gmail.com')->send(new UserRegisteredMail(
            //     $request->firstName,
            //     $request->emailId,
            //     $request->empPassword,
            //     $loginUrl
            // ));

            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'rohit.kprarang@gmail.com';
                $mail->Password   = 'mdtgebvpenwxccor';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                //Recipients
                $mail->setFrom('rohit.kprarang@gmail.com', 'Admin');
                $mail->addAddress($request->emailId);

                //Content
                $mail->isHTML(true);
                $mail->Subject = 'User Registration';
                $mail->Body    = "<h1>Welcome {$request->firstName}</h1>
                                  <p>You have been registered successfully. You can log in using the following credentials:</p>
                                  <p><b>Email:</b> {$request->emailId}</p>
                                  <p><b>Password:</b> {$request->empPassword}</p>
                                  <p>Login here: <a href='{$loginUrl}'>Login</a></p>";

                $mail->send();
                return redirect()->route('admin.user-listing');

            } catch (Exception $e) {
                return back()->with('error', "Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            }

            return redirect()->route('admin.user-listing');

        }else{
            return redirect()->route('admin.user-register')
                ->withInput()
                ->withErrors($validator);
        }
    }

    #this method is use for delete specific user id
    public function userDelete($id)
    {
        try {
            $currentDateTime = getUserCurrentTime();
            $user = Muser::findOrFail($id);
            $user->isActive = 0;
            $user->updated_at = $currentDateTime;
            $user->updated_by = Auth::guard('admin')->user()->userId;
            $user->save();

            return redirect()->route('admin.user-listing')->with('success', 'User soft deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.user-listing')->with('error', 'There was an error deleting the user: ' . $e->getMessage());
        }
    }

    #this method is use for show the existing data in field and also we change it.
    public function userEdit($id){
        $user = Muser::findOrFail($id);
        $languagescripts = Mlanguagescript::all();
        $roles = Mrole::all();

        return view('admin.user-edit', compact('user', 'roles', 'languagescripts'));
    }

    #this method is use for update the user data
    public function userUpdate(Request $request, $id)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('muser', 'emailId')->ignore($id, 'userId'),
            ],
            'password' => 'sometimes|confirmed',
            'roleId' => 'required|exists:mrole,roleID',
            'languageId' => 'required|boolean',
            'isActive' => 'required|boolean',
        ]);

        if ($validator->passes()) {
            $user = Muser::findOrFail($id);
            $currentDateTime = getUserCurrentTime();
            // dd('hi');
            // Update the user with additional fields
            $user->update([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'emailId' => $request->email,
                'empPassword' => bcrypt($request->password),
                'roleId' => $request->roleId,
                'languageId' => $request->languageId,
                'isActive' => $request->isActive,
                'updated_at' => $currentDateTime,
                'updated_by' => Auth::guard('admin')->user()->userId,
            ]);

            return redirect()->route('admin.user-listing')->with('success', 'User updated successfully.');
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
    }

    #this method is use for update user profile or password reset
    public function userProfileUpdate(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            // 'first_last_name' => 'required',
            // 'email_id'       => 'required',
            // 'role_name'      => 'required',
            // 'role_id'        => 'nullable',
            // 'language'       => 'required',
            // 'language_id'    => 'nullable',
            'password'       => 'required|confirmed',
            // 'reset_password' => 'required'
        ]);

        // $fullName = $request->first_last_name;
        // $names = explode(' ', $fullName);

        // $first_name = $names[0] ?? '';
        // $last_name = $names[1] ?? '';

        if ($validator->passes()) {
            $user = Muser::findOrFail($id);
            $currentDateTime = getUserCurrentTime();
            // Update the user with additional fields
            $user->update([
                // 'firstName' => $first_name,
                // 'lastName'  => $last_name,
                // 'emailId'   => $request->email_id,
                'empPassword' => bcrypt($request->password),
                // 'roleId'      => $request->role_id,
                // 'languageId' => $request->language_id,
                'updated_at' => $currentDateTime,
                'updated_by' => Auth::guard('admin')->user()->userId,
            ]);

            return redirect()->back()->with('success', 'Profile Updated Successfully.');
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
    }
}
