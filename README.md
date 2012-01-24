# SilverStripe FULLTEXT search function
=====================================

## Introduction

This behemoth handles extensive and sorts on relevance.

## Maintainer Contacts

* Simon "Sphere" Erkelens (simon[at]casa-laguna[dot]net)

##Features

* Fulltextsearch enabled objects are stored in a separate table.
* Searches ALL Fulltextsearch enabled objects.
* Returns a DataObjectSet which can be easily handled

## Installation

 1.  Download the module from the link above. 
 2.  Extract the file (if you are on windows try 7-zip for extracting tar.gz files
 3.  Make sure the folder after being extracted is named 'userforms' 
 4.  Place this directory in your sites root directory. This is the one with sapphire and cms in it.
 5.  Run in your browser - `/dev/build` to rebuild the database. 
 6.  You should see a new PageType in the CMS 'Search Results Page'.

## Future plans

* Integrate a highlighter for searchresults.

## Known issues:

* It's assumed, the searchable classes have a Title and Content to search. This is hopefully soon fixed.