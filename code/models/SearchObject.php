<?php
/**
 * Container of which objects are searchable
 *
 * @author Sphere
 */
class SearchObject extends DataObject {
	
	public static $db = array(
		'Title' => 'Varchar(255)',
		'Fields' => 'Text',
		'Fulltextsearchable' => 'Text'
	);
	

	/**
	 * We build a database of all searchables, because the searchable class doesn't give everything back if 
	 * classes are added via Object::add_extension(); In a _config. Which is kinda what we like to search huh?
	 * This function is quite heavy. But luckily only runs when a dev/build is required.
	 * If you have tips to make it more efficient, please let me know?
	 */
	public function requireDefaultRecords() {
		foreach(ClassInfo::allClasses() as $key => $value){
			if(ClassInfo::hasTable($value) && ($extra = Object::get_Extensions($value, true))){
				foreach($extra as $id => $searchable){
					if(strpos($searchable, 'FulltextSearchable') !== false){
						if(!$exists = DataObject::get_one('SearchObject', 'Title LIKE \'' . $value . '\'')){

							$resultArray = array();
							$fields = str_replace("FulltextSearchable(", "", $searchable);
							$fields = str_replace(")", '', $fields);
							$fields = str_replace("'", "", $fields);
							$fields = str_replace('"', '', $fields);
							$resultArray = array_merge(explode(',', $fields), $resultArray);
							 
							$new = new SearchObject();
							$new->Title = $value;
							$new->Fields = implode(',',array_keys(DataObject::database_fields($value)));
							$new->Fulltextsearchable = implode(',',array_unique($resultArray));
							$new->write();
						}
					}
				}
			}
		}
		parent::requireDefaultRecords();
	}
}

