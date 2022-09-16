<?php 
namespace App\Controllers;

class PageController {
    protected $dbController = null;

    function __construct($db){
        $this->dbController = new dbController($db);;
    }

    public function createPage($text, $title, $theme){
        $result_info = $this->dbController->createPage($text, $title, $theme);
        return [
            'id' => $result_info['id'],
            'status' => true,
            'message' => 'Page is created'
        ];
    }

    public function getPageByID($id){
        return $this->dbController->readPage($id);
    }

    public function getAllPages(){
        return $this->dbController->readPages();
    }

    public function getPagesPagination($num_page, $count_elem){
        return $this->dbController->readPagesWithPagination($num_page, $count_elem);
    }

    public function getFilteredPages($border){
        return $this->dbController->readPagesWithSorting($border);
    }
    
    public function updatePage($id, $text, $title, $theme){
        $this->dbController->updatePage($id, $text, $title, $theme);
        return [
            'id' => $id,
            'status' => true,
            'message' => 'Page is updated'
        ];
    }
    
    public function deletePage($id){
        $this->dbController->deletePage($id);
        return [
            'id' => $id,
            'status' => true,
            'message' => 'Page is deleted'
        ];
    }

    public function logOut($id){
        session_start();
        session_destroy();
        set_cookie('auth_user_id', '');
        set_cookie('auth_login', '');
    }
}
