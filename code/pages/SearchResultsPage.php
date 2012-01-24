<?php

class SearchResultsPage extends Page {

    protected $pagetype_allow_multiple = false;

    /**
     * does a fulltextsearch but matches on certain fields first and makes those results more important
     * also searches for dataobjects and merges the results and sorts like the queries
     * see: http://stackoverflow.com/questions/547542/how-can-i-manipulate-mysql-fulltext-search-relevance-to-make-one-field-more-valu
     */
    public function searcher($searchStr = '') {
        if ($searchStr) {
            $this->QueryXML = Convert::raw2xml($searchStr);
            $searchItems = array();
            
            /**
             * store the search for the report
             */
            $searchQuery = new SearchQuery();
            $searchQuery->Query = $searchStr;
            $searchQuery->FromURL = (isset($_SERVER) && isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : 'unknown';
            $searchQuery->write();

            $searchStr = Convert::raw2sql($searchStr);

            $searchables = DataObject::get('SearchObject');
            foreach($searchables as $key => $searchable){
                $searchItems[] = $this->buildSearch($searchStr, 'Title', 'Content', $searchable->Title);
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

    private function buildSearch($searchStr, $Title, $Content, $From){
            /**
             * Some exceptions are there
             * Sooo... Instances are default, as well as ExtraSearch and searchID... BUT!
             */
             $today = date('Y-m-d');
             $Instances = $From;
             $ExtraSearch = array();
             /**
              * Like our newsadmin, we need to add the publish-limit as Extra Search-information
              */
             if($From == 'News'){
                 $ExtraSearch = array(
                     "PublishFrom <= '$today' OR PublishFrom IS NULL",
                     "PublishUntil >= '$today' OR PublishUntil IS NULL",
                 );
             }
             /**
              * And what about the SiteTree, only show if 1! The $searchID is a tricky one!
              * Ow, and lets not forget the Instance... otherwise we'll get some error back.
              */
             if($From == 'SiteTree'){
                 $ExtraSearch = array(
                    "ShowInSearch = 1",
                    "Status = 'Published'",
                 );
             }
             /**
              * Okies, lets fetch the fullTextSearch parts. Here, the definition of SiteTree $searchID is important.
              */
            $fullTextSearch = Object::get_Extensions($Instances, true);
            $resultArray = array();
            foreach($fullTextSearch as $key => $value){
                    if(strpos($value, "FulltextSearchable") !== false){
                        $fields = str_replace("FulltextSearchable('", "", $value);
                        $fields = str_replace("')", '', $fields);
                        $resultArray = array_merge(explode(',', $fields), $resultArray);
                    }
            }
            $fullTextSearch = implode(',',array_unique($resultArray));

             /**
              * Lets build our query
              */
             $res = new SQLQuery();
             /*
              * Fetch everything! And match it baby.
              */
             $res->select = array();
             $res->select[] = "*";
             $res->select[] = "CASE WHEN " . $Content . " LIKE '%" . $searchStr . "%' THEN 1 ELSE 0 END AS keywordmatch";
             $res->select[] = "CASE WHEN " . $Title . " LIKE '%" . $searchStr . "%' THEN 1 ELSE 0 END AS titlematch";
             $res->select[] = "MATCH (" . $fullTextSearch . ") AGAINST ('" . $searchStr . "') AS relevance";
             
             $res->from = array($From);
             $res->where = array();
             if($Content != ''){
                $res->where[] = $Content . " LIKE '%" . $searchStr . "%' OR ";
             }
             if($Title != ''){
                $res->where[] = $Title . " LIKE '%" . $searchStr . "%' OR ";
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

