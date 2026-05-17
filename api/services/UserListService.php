<?php
require_once __DIR__ . '/../models/UserList.php';

class UserListService {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserList();
    }

    public function listUsers() {
        return $this->userModel->getAllUsers();
    }
}
?>