<?php
/**
 * Handler of the searches. And pushing it out offcourse
 * @author Simon "Sphere" Erkelens 
 */

class SearchResultsPage extends Page {

	protected $pagetype_allow_multiple = false;

	/**
	 * does a fulltextsearch but matches on certain fields first and makes those results more important
	 * also searches for dataobjects and merges the results and sorts like the queries
	 * see: http://stackoverflow.com/questions/547542/how-can-i-manipulate-mysql-fulltext-search-relevance-to-make-one-field-more-valu
	 * 
	 * @var searchStr contains the searchstring.
	 * @return A DataObjectSet with results
	 */
	public function searcher($searchStr = '') {
		if ($searchStr) {
			$searchItems = array();
			$searchStr = Convert::raw2sql($searchStr);

			$searchQuery = new SearchQuery();
			$searchQuery->Query = $searchStr;
			$searchQuery->FromURL = (isset($_SERVER) && isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : 'unknown';
			$searchQuery->write();


			$searchables = DataObject::get('SearchObject');
			foreach($searchables as $key => $searchable){
				$searchItems[] = $this->buildSearch($searchStr, 'Title', explode(',',$searchable->Fields), $searchable->Title, $searchable->Fulltextsearchable);
			}

			$this->searchResults = new DataObjectSet();
			foreach($searchItems as $searchResults){
				$this->searchResults->merge($searchResults);
			}

			$this->searchResults->sort('relevance', 'DESC');
			$this->searchResults->sort('keywordmatch', 'DESC');
			$this->searchResults->sort('titlematch', 'DESC');
			$this->searchResults->sort('searchmatch', 'DESC');
			/**
			 * This is future functionality. 
			 *
			if($this->SiteConfig()->Range > 0){
			 	$start = $_GET['start'];
				$length = $this->SiteConfig()->Range; 
			 
				//Fetch the slice
				$this->searchResults = $this->searchResults->getRange($start, $length);
				//Set the limit
				$this->searchResults->setPageLimits($start,$length,$this->searchResults->count());
				Session::set('SearchResults', $this->searchResults->getRange($length, $this->searchResults->count());			 
				//Set the length
				$this->searchResults->setPageLength($length);
			}
			/**/
		}
	}

	/**
	 * The actual search-function. It builds a query which gets results, but also 'counts' the result to relevance.
	 * @param type $searchStr The actual searchstring (Probably redundant, but here to be sure)
	 * @param type $Title The Title of the Object to search.
	 * @param type $Content An array of all fields in the Object.
	 * @param type $From From which object should this selection take place?
	 * @param type $fullTextSearch The actual fulltextsearchable fields.
	 * @return type A DataObjectSet of the results
	 */
	private function buildSearch($searchStr, $Title, $Content = array(), $From, $fullTextSearch){

		$today = date('Y-m-d');
		$ExtraSearch = array();
		$SearchKeywords = false;
		/**
		 * This is used, for example, to show events. Not showing the page after the event ended for example.
		 * The generic "PublishFrom" and "PublishUntil" is chosen because it made sense.
		 */
		if(in_array('PublishFrom', $Content)){
			$ExtraSearch[] = "PublishFrom <= '$today' OR PublishFrom IS NULL";
		}
		if(in_array('PublishUntil', $Content)){
			$ExtraSearch[] = "PublishUntil >= '$today' OR PublishUntil IS NULL";
		}

		if($From == 'SiteTree'){
			$ExtraSearch[] = "ShowInSearch = 1";
			$ExtraSearch[] = "Status = 'Published'";
		}

		$res = new SQLQuery();

		$res->select = array();
		$res->select[] = "*";
		foreach($Content as $key => $value){
			if($value != 'Title' && $value != 'SearchKeywords'){
				$res->select[] = "CASE WHEN " . $value . " LIKE '%" . $searchStr . "%' THEN 1 ELSE 0 END AS searchmatch";
			}
			elseif($value == 'Title'){
				$res->select[] = "CASE WHEN " . $Title . " LIKE '%" . $searchStr . "%' THEN 1 ELSE 0 END AS titlematch";		
			}
			elseif($value == 'SearchKeywords'){
				$SearchKeywords = true;
				$res->select[] = "CASE WHEN " . $value . " LIKE '%" . $searchStr . "%' THEN 1 ELSE 0 END AS keywordmatch";
			}
		} 
		$res->select[] = "MATCH (" . $fullTextSearch . ") AGAINST ('" . $searchStr . "') AS relevance";

		$res->from = array($From);
		$res->where = array();
		foreach($Content as $key => $value){
			$res->where[] = $value . " LIKE '%" . $searchStr . "%' OR ";
		}
		$res->where[] = "MATCH(" . $fullTextSearch . ") AGAINST ('" . $searchStr . "' IN BOOLEAN MODE)";
		$res->where = array(implode($res->where));

		$res->where = array_merge($ExtraSearch, $res->where);

		$res->having = array();
		if($SearchKeywords){
			$res->having[] = "searchmatch > 0 OR keywordmatch > 0 OR titlematch > 0 OR relevance > 0";
			$res->orderby  = "searchmatch DESC, keywordmatch DESC, titlematch DESC, relevance DESC";
		}
		else{
			$res->having[] = "searchmatch > 0 OR titlematch > 0 OR relevance > 0";
			$res->orderby  = "searchmatch DESC, titlematch DESC, relevance DESC";
		}

		$Items = singleton($From)->buildDataObjectSet($res->execute());
		return $Items;
		/**
		 * Thank you!
		 * (Always be polite. This behemoth puts some stress on the database!)
		 */
	}

}


class SearchResultsPage_Controller extends Page_Controller {

	public static $allowed_actions = array(
	);


	public function init() {
		parent::init();
		$req = $this->getRequest();
		$searchStr = $req->postVar('Search');
		$this->searcher($searchStr);
	}

}

