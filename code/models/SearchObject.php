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
	 * classes are added via Object::add_extension(); In a _config.
	 * Only executed on dev/build. Quite heavy and tries to eliminate doubles.
	 */
	public function requireDefaultRecords() {
		$removeArray = array(
		    'Created',
		    'LastEdited',
		    'ShowInMenus',
		    'ShowInSearch',
		    'ShowInHTML',
		    'Status',
		    'CanViewType',
		    'CanEditType',
		    'HomepageForDomain',
		    'ProvideComments',
		    'Sort',
		    'HasBrokenFile',
		    'HasBrokenLink',
		    'ReportClass',
		    'ToDo',
		    'Version',
		    'Priority',
		    'ParentID',
		    'ChangeFreq',
		    'ShowInHtml'
		);
		foreach(ClassInfo::allClasses() as $key => $value){
			if(ClassInfo::hasTable($value) && ($extra = Object::get_Extensions($value, true))){
				foreach($extra as $id => $searchable){
					if(strpos($searchable, 'FulltextSearchable') !== false){
						//Remove useless DB-fields
						$dbFields = DataObject::database_fields($value);
						foreach($dbFields as $field => $fieldName){
							if(in_array($field, $removeArray)){
								unset($dbFields[$field]);
							}
							if(strpos($field, 'ID')){
								unset($dbFields[$field]);
							}
						}

						$fields = str_replace("FulltextSearchable(", "", $searchable);
						$fields = str_replace(")", '', $fields);
						$fields = str_replace("'", "", $fields);
						$fields = str_replace('"', '', $fields);
						$resultArray = explode(',', $fields);
						//Remove already known fields in case of a double
						if($new = DataObject::get_one('SearchObject', 'Title LIKE \'' . $value . '\'')){
							$existFields = explode(',',$new->Fulltextsearchable);
							foreach($existFields as $fieldID => $existing){
								if(in_array($existing, $resultArray)){
									unset($existFields[$fieldName]);
								}
							}
							$resultArray = array_merge($resultArray, $existFields);
						}
						else{
							$new = new SearchObject();
							$new->Title = $value;
							$new->Fields = implode(',',array_unique(array_keys($dbFields)));
						}
						$new->Fulltextsearchable = implode(',',array_unique($resultArray));
						$new->write();
					}
				}
			}
		}
		parent::requireDefaultRecords();
	}
}

