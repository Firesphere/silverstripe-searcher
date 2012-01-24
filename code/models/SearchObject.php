<?php
/**
 * Container of which objects are searchable
 *
 * @author Sphere
 */
class SearchObject extends DataObject {
	
	public static $db = array(
		'Title' => 'Varchar(255)'
	);
	

	/**
	 * We built a database of all searchables, because the searchable class doesn't give everything back if 
	 * classes are added via add_extension(); 
	 */
	public function requireDefaultRecords() {
		foreach(ClassInfo::allClasses() as $key => $value){
			if(ClassInfo::hasTable($value) && ($extra = Object::get_Extensions($value, true))){
				foreach($extra as $id => $class){
					if(strpos($class, 'FulltextSearchable') !== false){
						if(!$exists = DataObject::get_one('SearchObject', 'Title LIKE \'' . $value . '\'')){
							$new = new SearchObject();
							$new->Title = $value;
							$new->write();
						}
					}
				}
			}
		}
		parent::requireDefaultRecords();
	}
}

