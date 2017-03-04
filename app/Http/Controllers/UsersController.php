<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Redirect;
use Validator;
use Input;

class UsersController extends Controller
{

    protected $redirectTo = '/myaccount';

    public function account()
    {

        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        return view('users.personal.account', ['user' => $user]);
    }

    public function save(Request $request)
    {

        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $rules = [];
        $hasUpdates = false;

        if ($request->get('name') !== $user->name) {
            $rules['name'] = 'required|max:255';
            $hasUpdates = true;
        }
        if ($request->get('email') !== $user->email) {
            $rules['email'] = 'required|email|max:255|unique:users';
            $hasUpdates = true;
        }
        if ($request->get('password')) {
            $rules['password'] = 'required|min:6|confirmed';
            $rules['password_confirmation'] = 'required|min:6';
            $hasUpdates = true;
        }

        $this->validate($request, $rules);

        if ($hasUpdates) {
            try {
                if ($request->get('name') !== $user->name) {
                    $user->name = $request->get('name');
                }
                if ($request->get('email') !== $user->email) {
                    $user->email = $request->get('email');
                }
                if ($request->get('password')) {
                    $user->password = bcrypt($request->get('password'));
                }
                $user->save();
            } catch (Exception $e) {
                return redirect('/myaccount')->withErors(['Error saving user data']);
            }
        }

        return redirect('/myaccount');
    }

    public function avatar(Request $request)
    {

        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        /**
         * @var Symfony\Component\HttpFoundation\File\UploadedFile
         */
        $this->validate($request, [
            'image' => 'required',
        ]);

        $file = $request->file('image');
        $extension = $file->guessExtension();
        if (!$request->file('image')->isValid() || !in_array($extension, ['png', 'jpeg', 'gif'])) {
            return redirect('/myaccount')->withErrors(['image' => 'The uploaded file is invalid']);
        }

        $destinationPath = Config::get('constants.AVATAR_FS_PATH');
        $destinationName = sha1($user->id) . '.' .$extension;

        try {
            // may be another extension, remove it
            $avatarFile = $destinationPath . DIRECTORY_SEPARATOR . $user->avatar_filename ;
            if ($user->avatar_filename && file_exists($avatarFile)) {
                @unlink($avatarFile);
            }
            $file->move($destinationPath, $destinationName);
        } catch (Exception $e) {
            return redirect('/myaccount')->withErrors(['image' => 'Cannot move uploaded file']);
        }

        try {
            $user->avatar_filename = $destinationName;
            $user->save();
        } catch (Exception $e) {
            return redirect('/myaccount')->withErrors(['image' => 'Cannot save account values']);
        }

        return redirect('/myaccount');
    }
}
