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
* Return is sorted by relevance, not just a list of results.
* Reports for most used searches so you can optimize your website-design maybe.
* Reports for searches in general.
* Adds a default search keywords function to all pages for better searchresults.

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

## Other

This module is given "as is" and I am not responsible for any damage it might do to your brain, dog, cat, house, computer or website.

## Actual license

This module is published under BSD 2-clause license, although these are not in the actual classes, the license does apply:

http://www.opensource.org/licenses/BSD-2-Clause

Copyright (c) 2012, Simon "Sphere" Erkelens

All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

    Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
    Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

Great thanks to DasPlan for finding and rudimentary implementing the query in the very first version we ever build.

# The actual query

SELECT *, 
CASE WHEN ClassName LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN Created LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN LastEdited LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN URLSegment LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN Title LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS titlematch, 
CASE WHEN MenuTitle LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN Content LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN MetaTitle LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN MetaDescription LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN MetaKeywords LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN ExtraMeta LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN ShowInMenus LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN ShowInSearch LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN HomepageForDomain LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN ProvideComments LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN Sort LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN HasBrokenFile LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN HasBrokenLink LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN Status LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN ReportClass LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN CanViewType LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN CanEditType LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN ToDo LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN Version LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN SearchKeywords LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS keywordmatch, 
CASE WHEN Priority LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN ChangeFreq LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN ShowInHTML LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
CASE WHEN ParentID LIKE '%{searchquery}%' THEN 1 ELSE 0 END AS searchmatch, 
MATCH (Title,SearchKeywords,MenuTitle,Content,MetaTitle,MetaDescription,MetaKeywords) AGAINST ('{searchquery}') AS relevance FROM SiteTree 
WHERE (ShowInSearch = 1) AND (Status = 'Published') AND (ClassName LIKE '%{searchquery}%' OR Created LIKE '%{searchquery}%' OR LastEdited LIKE '%{searchquery}%' OR URLSegment LIKE '%{searchquery}%' OR Title LIKE '%{searchquery}%' OR MenuTitle LIKE '%{searchquery}%' OR Content LIKE '%{searchquery}%' OR MetaTitle LIKE '%{searchquery}%' OR MetaDescription LIKE '%{searchquery}%' OR MetaKeywords LIKE '%{searchquery}%' OR ExtraMeta LIKE '%{searchquery}%' OR ShowInMenus LIKE '%{searchquery}%' OR ShowInSearch LIKE '%{searchquery}%' OR HomepageForDomain LIKE '%{searchquery}%' OR ProvideComments LIKE '%{searchquery}%' OR Sort LIKE '%{searchquery}%' OR HasBrokenFile LIKE '%{searchquery}%' OR HasBrokenLink LIKE '%{searchquery}%' OR Status LIKE '%{searchquery}%' OR ReportClass LIKE '%{searchquery}%' OR CanViewType LIKE '%{searchquery}%' OR CanEditType LIKE '%{searchquery}%' OR ToDo LIKE '%{searchquery}%' OR Version LIKE '%{searchquery}%' OR SearchKeywords LIKE '%{searchquery}%' OR Priority LIKE '%{searchquery}%' OR ChangeFreq LIKE '%{searchquery}%' OR ShowInHTML LIKE '%{searchquery}%' OR ParentID LIKE '%{searchquery}%' 
OR MATCH(Title,SearchKeywords,MenuTitle,Content,MetaTitle,MetaDescription,MetaKeywords) AGAINST ('{searchquery}' IN BOOLEAN MODE)) 
HAVING ( searchmatch > 0 OR keywordmatch > 0 OR titlematch > 0 OR relevance > 0 ) 
ORDER BY searchmatch DESC, keywordmatch DESC, titlematch DESC, relevance DESC