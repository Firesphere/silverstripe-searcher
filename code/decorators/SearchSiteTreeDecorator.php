<?php
/**
 * Add searchables. Needs a few updates
 * @author Simon "Sphere" Erkelens 
 */

class SearcherDecorator extends SiteTreeDecorator {

	function extraStatics() {
		return array(
			'db' => array(
				'SearchKeywords' => 'Text',
//				I want to limit the range in the future, but not stressing the DB more by re-doing the gets.
//				'Range' => 'Int'
			),
//			'defaults' => array(
//				'Range' => 4,
//			),
		);
	}

	function updateCMSFields(&$fields) {
		$tabset = $fields->findOrMakeTab('Root.Content');
		$tabset->push(
			$addTab = new Tab(
				'Searcher',
				_t($this->class . '.TAB_SEARCHER', 'Search'),
				new LiteralField(
					'SearcherIntro',
					'<p>' . _t($this->class . '.SEARCHER_INTRO','Specify keywords for the sitesearch') .'</p>'
				),
				new TextareaField('SearchKeywords', _t($this->class . '.KEYWORDS', 'Search enhancement Keywords'))
//				This is for the future. Not yet implemented.
//				new NumericField(
//					'Range',
//					'Amount of results to show per page'
//				)
			)
		);
	}

}
