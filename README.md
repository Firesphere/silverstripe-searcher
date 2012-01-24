# SilverStripe FULLTEXT search function
=====================================

## Introduction

This behemoth handles extensive search and sorts on relevance.

## Maintainer Contacts

* Simon "Sphere" Erkelens `simon[at]casa-laguna[dot]net`

##Features

* Fulltextsearch enabled objects are stored in a separate table.
* Searches ALL Fulltextsearch enabled objects.
* Returns a DataObjectSet which can be easily handled.

## Installation

 1.  Make a fork of this module.
 2.  In your site-root, do `git clone https://{your username}@github.com/{your username}/silverstripe-searcher.git`. 
 3.  Add `FulltextSearchable::enable();` to your _config in mysite. 
 4.  Run in your browser - `www.example.com/dev/build` to rebuild the database. 
 5.  You should see a new PageType in the CMS 'Search Results Page'.

## Best practices

* Use 'Content' as the default for a DataObject's main content. See known issues.
* Use 'PublishFrom' and 'PublishUntil' as custom published-limits for the extra filter which is currently only applied to my own news-module.

## Configuration

* Check if a page from the SiteTree should be shown in search under Behaviour-tab to have it show in search (defaults to true/checked)
* The better the keywords, the more chance they end up high in search results. Choose wisely.
* Any DataObject that should be searched should be set in the _config with `DataObject::add_extension('ObjectName', 'FulltextSearchable('Title,Content,Extrafield,etc.'));`.
* When adding a FulltextSearchable, you need to run a dev/build for it to be recorded in the SearchObject and have the fulltext enabled in the database.

## Future plans

* Integrate a highlighter for searchresults.
* Give a range-option for the results. So pages won't go waaaaay down.

## Known issues:

* It's assumed, the searchable classes have a Title to search. Can't be fixed since I can't look into your brain on what you are using as Title. Title is required, since it is keyworded, not just keymatched.

## Requests

* Please create a translation in your language if it doesn't exist yet!