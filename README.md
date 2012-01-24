This behemoth handles extensive and keyword-sensitive searches.
Usage: add to the SS-root directory and do a dev/build. Now, there should be a database with searchable objects.

This is needed because the FullTextSearchable::get_searchable_classes() does not return the later added Object::add_extension(class, FullTextSearchable('Title,Content');

What does it do?
This searcher searches all classes that have FullTextSearchable extension enabled. (those stored in the SearchObject class)
These are then sorted by relevance based on the result of the query.


Known issues:
It's assumed, the searchable classes have a Title and Content to search. This is hopefully soon fixed.