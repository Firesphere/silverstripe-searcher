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
			),
		);
	}

	function updateCMSFields(&$fields) {
		$tabset = $fields->findOrMakeTab('Root.Content');
		$tabset->push(
			$addTab = new Tab(
				'Searcher',
				_t($this->class . '.TAB_SEARCHER', 'Search *NYT*'),
				new LiteralField(
					'SearcherIntro',
					'<p>' . _t(
								$this->class . '.SEARCHER_INTRO',
								'Specify keywords for the sitesearch *NYT*'
							) .
					'</p>'
				),
				new TextareaField('SearchKeywords', _t($this->class . '.KEYWORDS', 'Keywords *NYT*'))
			)
		);
	}

}
