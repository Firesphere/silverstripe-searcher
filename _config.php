<?php 
/*
 * Add decorators
 */
// using our searchsystem, the searchables
Object::add_extension('SiteTree', 'SearcherDecorator');
Object::add_extension('SiteTree', "FulltextSearchable('Title,SearchKeywords,MenuTitle,Content,MetaTitle,MetaDescription,MetaKeywords')");
