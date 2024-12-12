<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Mrole;

class RoleController extends Controller
{
    #this method show the role listing data
    public function index(Request $request)
    {
        $query = Mrole::where('status', 1);

        // Apply search if input exists
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('roleName', 'LIKE', "%{$search}%")
                ->orWhere('roleName', 'LIKE', "%{$search}%");
            });
        }

        $roles = $query->paginate(5);
        return view('admin.role.role-listing', compact('roles'));
    }


    #this method is use for show role register page
    public function roleRegister()
    {
        return view('admin.role.role-register');
    }

    #this method is use for new role created
    public function roleStore(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'roleName' => 'required|string|max:255|unique:mrole,roleName',
        ]);

        if($validator->passes()){
            $currentDateTime = getUserCurrentTime();
            $role = new Mrole();
            $role->roleName = $request->input('roleName');
            $role->status = 1;
            $role->created_at = $currentDateTime;
            $role->created_by = Auth::guard('admin')->user()->userId;
            $role->save();
            return redirect()->route('admin.role-listing');
        }else{
            return redirect()->route('admin.role-register')
                ->withInput()
                ->withErrors($validator);
        }
    }

    #this method is use for delete specific role
    public function roleDelete($id)
    {
        try {
            $currentDateTime = getUserCurrentTime();
            $role = Mrole::findOrFail($id);
            $role->status = 0;
            $role->updated_at = $currentDateTime;
            $role->updated_by = Auth::guard('admin')->user()->userId;
            $role->save();

            return redirect()->route('admin.role-listing')->with('success', 'User soft deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.role-listing')->with('error', 'There was an error deleting the user: ' . $e->getMessage());
        }
    }

    #this method is use for role edit of update data
    public function roleEdit($id)
    {
        $role = Mrole::findOrFail($id);
        return view('admin.role.role-edit' , compact('role'));
    }

    #this method is use for update/change role data
    public function roleUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'roleName' => 'required|string|max:255|unique:mrole,roleName',
        ]);

        if ($validator->passes()) {
            $role = Mrole::findOrFail($id);

            $currentDateTime = getUserCurrentTime();

            $role->update([
                'roleName' => $request->roleName,
                'updated_at' => $currentDateTime,
                'updated_by' => Auth::guard('admin')->user()->userId,
            ]);

            return redirect()->route('admin.role-listing')->with('success', 'User updated successfully.');
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
    }
}
