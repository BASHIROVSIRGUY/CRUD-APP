<?php 
namespace App\Controllers;
use App\Database\dbHandler;

class dbController {
    protected $db = null;
    
    const SELECT_LAST_ID_PAGES = 'SELECT  MAX(`ID`) FROM `pages` WHERE 1;';
    const CREATE_USER = 'INSERT INTO `users` (`name`, `login`, `password`) VALUES (?, ?, ?);';
    const SELECT_ONE_USER = 'SELECT * FROM `users` WHERE `ID` = ?;';
    const SELECT_ALL_USERS = 'SELECT * FROM `users` WHERE 1;';  
    const UPDATE_USER = 'UPDATE `users` SET `login` = ?, SET `password` = ?, SET `name` = ? WHERE `ID` = ?;';
    const DELETE_USER = 'DELETE FROM `users` WHERE `ID` = ?;';

    const SELECT_LAST_ID_USERS = 'SELECT  MAX(`ID`) FROM `users` WHERE 1;';
    const CREATE_PAGE = 'INSERT INTO `pages` (`text`, `title`, `theme`) VALUES (?, ?, ?);';
    const SELECT_ONE_PAGE = 'SELECT * FROM `pages` WHERE `ID` = ?;';
    const SELECT_ALL_PAGES = 'SELECT * FROM `pages`';
    const UPDATE_PAGE = 'UPDATE  `pages` SET `title` = ?, SET `page_text` = ?, SET `theme` = ? WHERE `ID` = ?;';
    const DELETE_PAGE = 'DELETE FROM `pages` WHERE `ID` = ?;';


    function __construct(dbHandler $db){
        $this->db = $db;
    }

    public function saveTocken($tocken, $user_id){
        $tocken = '';
    }

    public function createUser($name, $login, $password){
        $max_id = $this->db->query(SELECT_LAST_ID_PAGES);
        $this->db->query_with_params(CREATE_USER, 'sss', $login, $password, $name);
        return ['id' => ++$max_id];
    }

    public function createPage($text, $title, $theme){
        $max_id = $this->db->query(SELECT_LAST_ID_PAGES);
        $this->db->query_with_params(CREATE_PAGE, 'sss', $text, $title, $theme);
        return ['id' => ++$max_id];
    }

    public function readUser($id){
        return $this->db->query_with_params(SELECT_ONE_USER, 'd', $id);
    }

    public function readPage($id){
        return $this->db->query_with_params(SELECT_ONE_PAGE, 'd', $id);
    }

    public function readUsers(){
        return $this->db->query(SELECT_ALL_USERS);
    }

    public function readPages(){
        return $this->db->query(SELECT_ALL_PAGES);
    }

    public function readPagesWithPagination($num_page, $limit){
        if(is_numeric($num_page) && is_numeric($limit)){
            return $this->db->query(SELECT_ALL_PAGES . " OFFSET " . $num_page*$limit . " LIMIT " . $limit);
        } else{
            return [];
        }
    }

    public function readPagesWithSorting($border){
        if(is_numeric($border)){
            return $this->db->query_with_params(SELECT_ALL_PAGES . " WHERE `ID` >= " . $border);
        } else{
            return [];
        }
    }

    public function updateUser($id, $login, $password, $name){
        $this->db->query_with_params(UPDATE_USER, 'sssd', $login, $password, $name, $id);
        return ['id' => $id];
    } 

    public function updatePage($id, $title, $page_text, $theme){
        $this->db->query_with_params(UPDATE_PAGE, 'sssd', $title, $page_text, $theme, $id);
        return ['id' => $id];
    }

    public function deleteUser($table, $id){
        $this->db->query_with_params(DELETE_USER, 'd', $id);
        return ['id' => $id];
    }

    public function deletePage($id){
        $this->db->query_with_params(DELETE_PAGE, 'd', $id);
        return ['id' => $id];
    }
}
