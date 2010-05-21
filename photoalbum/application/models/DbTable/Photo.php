<?php
class Application_Model_DbTable_Photo extends Zend_Db_Table_Abstract
{
    protected $_name = 'photo';
        
    public function getPhoto($id) 
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();    
    }

    public function getAlbumPhoto( $albumId )
    {
        $albumId = (int)$albumId;
        $rows = $this->fetchAll('album = ' . $albumId);
        if (!$rows) {
            throw new Exception("Count not find album $albumId");
        }
        return $rows->toArray();    
    }
    
    public function addPhoto($name, $picture, $album)
    {
        $data = array(
            'name' => $name,
			'picture' => $picture,
			'album' => $album,
			'date' => time(),
        );
        $this->insert($data);
    }
    
    public function updatePhoto($id, $name, $picture, $album)
    {
        $data = array(
            'name' => $name,
            'picture' => $picture,
			'date' => time(),
			'album' => $album,
        );
        $this->update($data, 'id = '. (int)$id);
    }
    
    public function deletePhoto($id)
    {
        $this->delete('id =' . (int)$id);
    }
}
?>