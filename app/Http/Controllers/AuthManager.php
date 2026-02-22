<?php

namespace App\Http\Controllers;

use App\Models\User; //Μεσω terminal δημιουργησα το model User και το εκανα link με το πινακα user του database μου 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthManager extends Controller
{
    function login(){ // εδω γινεται κοντρολ για το return του login view
        if (Auth::check()) {
            return redirect(route('home'));
        }
        return view('login');
    }

    function registration(){
        return view('registration');
    }

    function loginPost(Request $request) { // Απο εδω θα παιρνουμε ενα request που θα εχει ολο το data που παιρνουμε απο το form
        $request->validate([
            'email' => 'required', // Απο εδω παιρνουμε το password και email απο login form
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password'); //Πάρε ΜΟΝΟ τα πεδία email και password από το request και βάλ’ τα σε έναν πίνακα. Η μέθοδος only() επιστρέφει έναν associative array
        if(Auth::attempt($credentials)){ // Κανει αυτοματα τη δοκιμη login. Το Auth είναι Facade της Laravel για το authentication system. Ανήκει στο σύστημα authentication της Laravel.
            return redirect()->intended('/');
        }
        else {
            return redirect()->intended(route('login'))->with("error", "Login details are not valid"); // Στο with() το error ειναι το κλειδι και το Login...valid ειναι το κλειδι που βρισκεται μεσα στο error
        }
    }

    function registrationPost(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users', // Απο εδω παιρνουμε το password και email απο login form. To 'required|email|u' βεβαιωνει οτι το email ειναι οντως μορφη email και ειναι unique
            'password' => 'required'
        ]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);
        if (!$user){
            return redirect()->intended(route('registration'))->with("error", "Registration failed, try again.");
        }
        return redirect()->intended(route('login'))->with("success", "Registration successful, Login to access the app"); 
    }

    function logout(){
        Session::flush();
        Auth::logout();
        return redirect(route('login'));
    }
}
