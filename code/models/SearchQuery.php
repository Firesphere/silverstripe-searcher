<?php
/**
 * Container for the searches and the source.
 * @author Simon "Sphere" Erkelens 
 */
class SearchQuery extends DataObject {

	public static $db = array(
		'Query' => 'Varchar(255)',
		'FromURL' => 'Varchar(255)',
	);

}
