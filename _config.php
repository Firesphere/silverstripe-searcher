<?php 
/*
 * Add extensions to SiteTree. The FulltextSearchable is 1 field more then the default, hence the extra declaration
 */
Object::add_extension('SiteTree', 'SearcherDecorator');
Object::add_extension('SiteTree', "FulltextSearchable('Title,SearchKeywords,MenuTitle,Content,MetaTitle,MetaDescription,MetaKeywords')");
