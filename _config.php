<?php 
/*
 * Moet waarschijnlijk ergens anders heen...
 */
// using our searchsystem, the searchables
Object::add_extension('SiteTree', 'SqualSearcherDecorator');
Object::add_extension('SiteTree', "FulltextSearchable('Title,SearchKeywords,MenuTitle,Content,MetaTitle,MetaDescription,MetaKeywords')");
