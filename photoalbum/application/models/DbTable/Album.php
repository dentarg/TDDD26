<?php
class Application_Model_DbTable_Album extends Zend_Db_Table_Abstract
{
    protected $_name = 'album';
    
    public function getAlbum($id) 
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();    
    }
    
    public function addAlbum($author, $name)
    {
        $data = array(
            'author' => $author,
			'name' => $name,
			'date' => time(),
        );
        return $this->insert($data);
    }
    
    public function updateAlbum($id, $name, $cover)
    {
        $data = array(
            'name' => $name,
            'cover' => $cover,
			'date' => time(),
        );
        $this->update($data, 'id = '. (int)$id);
    }
    
    public function deleteAlbum($id)
    {
        $this->delete('id =' . (int)$id);
    }
	
	public function fetchAll_C( $where= '', $order = '' )
    {
		$select = $this->_db->select()
		->from(array('a'=>'album'),array('a.id','a.name','a.author'))
		->joinLeft(array('p'=>'photo'),'p.album=a.id',array('p.picture as cover'));

		$results = $this->getAdapter()->fetchAll($select);
		
		return($results);
    }

	public function setCover( $albums )
    {
		$photo = new Application_Model_DbTable_Photo();

		foreach ( $albums as $key => $album )
		{
			if ( !$album['picture'] )
			{
				$photos = $photo->getAlbumPhoto( $album['id'] );

				if ( count ($photos) > 0 )
				{
					$this->updateAlbum($album['id'], $album['name'], $photos[0]['id']);
					$albums[$key]['picture'] = $photos[0]['picture'];
				}
			}
		}
		
		return $albums;
    }
}
?>