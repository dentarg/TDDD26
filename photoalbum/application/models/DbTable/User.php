<?php
class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{
    protected $_name = 'user';
    
    public function getUser($id) 
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();    
    }
    public function getUserByEmail($email) 
    {
        $row = $this->fetchRow("email = '" . $email . "'");
        if (!$row) {
            throw new Exception("Count not find row $email");
        }
        return $row->toArray();    
    }    
    
    public function addUser($email, $password, $nickname)
    {
        $data = array(
            'email' => $email,
			'password' => md5($password),
			'nickname' => $nickname,
        );
        $this->insert($data);
    }
    
    public function updateUser($id, $password, $nickname)
    {
        $data = array(
            'name' => $name,
            'cover' => $cover,
			'date' => time(),
        );
        $this->update($data, 'id = '. (int)$id);
    }
    
    public function deleteUser($id)
    {
        $this->delete('id =' . (int)$id);
    }
}
?>