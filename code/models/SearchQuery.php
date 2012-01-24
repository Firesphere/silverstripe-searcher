<?php


class SearchQuery extends DataObject {

    public static $db = array(
        'Query' => 'Varchar(255)',
        'FromURL' => 'Varchar(255)',
    );

}
