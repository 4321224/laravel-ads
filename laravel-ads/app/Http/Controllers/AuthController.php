<?php
  
namespace App\Http\Controllers\Auth;
  
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use App\Models\Advertisement;
use Hash;
  
class AuthController extends Controller
{
    /**
     * Write code on Method
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('auth.login');
    }  
      
    /**
     * Write code on Method
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function registration()
    {
        return view('auth.registration');
    }
      
    /**
     * Write code on Method
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')
                        ->withSuccess('You have Successfully loggedin');
        }
  
        return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
    }
      
    /**
     * Write code on Method
     *
     * @return \Illuminate\Http\Response
     */
    public function postRegistration(Request $request)
    {  
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
           
        $data = $request->all();
        $check = $this->create($data);
        
        return redirect("dashboard")->withSuccess('Great! You have Successfully loggedin');
    }
    
    /**
     * Display the dashboard with pagination and search functionality.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function dashboard(Request $request)
    {
        if (Auth::check()) {
            $advertisements = Advertisement::where('user_id', Auth::id());
            if ($request->has('title')) {
                $advertisements->where('title', 'like', '%' . $request->input('title') . '%');
            }

            if ($request->has('type')) {
                $advertisements->where('type', $request->input('type'));
            }

            $advertisements = $advertisements->paginate(10);

            return view('dashboard', [
                'advertisements' => $advertisements,
                'title' => $request->input('title'),
                'type' => $request->input('type'),
            ]);
        }

        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    
    /**
     * Write code on Method
     *
     * @return \Illuminate\Http\Response
     */
    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }
    
    /**
     * Write code on Method
     *
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Http\RedirectResponse 
     */
    public function logout() {
        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }
}