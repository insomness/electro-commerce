<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $this->data['provinces'] = $this->getProvinces();
        $this->data['cities'] = isset(Auth::user()->province_id) ? $this->getCities(Auth::user()->province_id) : [];
        $this->data['user'] = $user;

        return $this->loadTheme('profiles.index', $this->data);
    }

    public function update(Request $request)
    {
        $params = $request->except('_token');

        $user = Auth::user();

        if ($user->update($params)) {
            Session::flash('success', 'Your profile have been updated!');
            return redirect('profiles');
        }
    }
}
