<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Logout extends BaseController
{
    public function logout()
    {
        // $this->session->remove('user');
        // $this->session->remove('token');
        session()->destroy();
        return redirect()->to('/login')->with('popMessage', 'You have been logged out');
        // return view('login',['popMessage'=>'You have been logged out']);
    }
}