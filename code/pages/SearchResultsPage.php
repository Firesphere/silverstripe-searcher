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
			/**
			 * store the search for the report
			 */
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
			$this->searchResults->sort('titlematch', 'DESC');
			$this->searchResults->sort('keywordmatch', 'DESC');
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
		$Instances = $From;
		$ExtraSearch = array();
		/**
		 * This can't really be made generic. But if the PublishFrom/PublishUntil field exists, it will be taken into account.
		 * This is used, for example, to show events. Not showing the page after the event ended for example.
		 * The generic "PublishFrom" and "PublishUntil" is chosen because it made sense.
		 * If you use different wording, feel free to change them accordingly. Offcourse.
		 */
		if(in_array('PublishFrom', $Content)){
			$ExtraSearch[] = "PublishFrom <= '$today' OR PublishFrom IS NULL";
		}
		if(in_array('PublishUntil', $Content)){
			$ExtraSearch[] = "PublishUntil >= '$today' OR PublishUntil IS NULL";
		}
		/**
		 * And what about the SiteTree, only show if 1! Note, ShowInSearch is defaulted to 1, so uncheck the errorpages for example.
		 */
		if($From == 'SiteTree'){
			$ExtraSearch[] = "ShowInSearch = 1";
			$ExtraSearch[] = "Status = 'Published'";
		}
		/**
		 * Lets build our query ('res' stands for 'results')
		 */
		$res = new SQLQuery();

		$res->select = array();
		$res->select[] = "*";
		foreach($Content as $key => $value){
			if($value != 'Title'){
				$res->select[] = "CASE WHEN " . $value . " LIKE '%" . $searchStr . "%' THEN 1 ELSE 0 END AS keywordmatch";
			}
		}
		$res->select[] = "CASE WHEN " . $Title . " LIKE '%" . $searchStr . "%' THEN 1 ELSE 0 END AS titlematch";
		$res->select[] = "MATCH (" . $fullTextSearch . ") AGAINST ('" . $searchStr . "') AS relevance";

		$res->from = array($From);
		$res->where = array();
		foreach($Content as $key => $value){
			$res->where[] = $value . " LIKE '%" . $searchStr . "%' OR ";
		}
		$res->where[] = "MATCH(" . $fullTextSearch . ") AGAINST ('" . $searchStr . "' IN BOOLEAN MODE)";
		$res->where = array(implode($res->where));

		/**
		 * Merge the extra's with the default.
		 */
		$res->where = array_merge($ExtraSearch, $res->where);

		$res->having = array(
			"keywordmatch > 0 OR titlematch > 0 OR relevance > 0",
		);
		$res->orderby = "keywordmatch DESC, titlematch DESC, relevance DESC";

		/**
		 * Build the dataobject to return
		 */
		$Items = singleton($Instances)->buildDataObjectSet($res->execute());
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

		/**
		* do search
		*/
		$req = $this->getRequest();
		$searchStr = $req->postVar('Search');
		$this->searcher($searchStr);
	}

}

