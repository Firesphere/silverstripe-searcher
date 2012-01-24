<?php
/**
 * this adds a search-tab with a searchkeywords-field to every page
 */
class SqualSearcherDecorator extends SiteTreeDecorator {

    function extraStatics() {
        return array(
            'db' => array(
                'SearchKeywords' => 'Text',
            ),
/*
            'defaults' => array(
                'SearchKeywords' => '',
            ),
 */
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
