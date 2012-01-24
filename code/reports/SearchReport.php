<?php


class SearchReport extends SS_Report {

    public function title() {
        return(_t($this->class . '.TITLE', 'Searchreport *NYT*'));
    }

    public function description() {
        return(_t($this->class . '.DESCRIPTION', 'Show searchqueries *NYT*'));
    }

    public function sourceRecords($params, $sort, $limit) {
        fb($params, 'params');
        fb($sort, 'sort');
        fb($limit, 'limit');
        $where = '';
        if (isset($params['Query']) && ($params['Query'] != '')) {
            $where = 'Query LIKE \'%' . Convert::raw2sql($params['Query']) . '%\'';
        }
        $queries = DataObject::get('SearchQuery', $where, $sort, '', $limit);
        return($queries);
    }

    public function columns() {
        $fields = array(
            'Query' => array(
                'title' => _t($this->class . '.QUERY', 'Searchquery *NYT*'),
                'formatting' => '$value',
            ),
            'FromURL' => array(
                'title' => _t($this->class . '.FROM_URL', 'Search requested from this URL *NYT*'),
                'formatting' => '$value',
            ),
            'Created' => array(
                'title' => _t($this->class . '.CREATED', 'Search time/date *NYT*'),
                'formatting' => '$value',
            ),
        );

        return($fields);
    }

    public function parameterFields() {
        return(new FieldSet(
            new TextField('Query', _t($this->class . '.QUERY', 'Searchquery *NYT*'))
        ));
    }
}
