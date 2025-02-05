<?php

namespace App\Controllers;
use App\Models\UserModel;

class ChatController extends BaseController {
    protected $agents ; 
    public function __construct() {
        
        $this->agents = new UserModel();
    }

    public function index() {
        $users = $this->agents->getAllUsers();
        $data['users'] = $users;
        echo view('chat', $data);
    }
}