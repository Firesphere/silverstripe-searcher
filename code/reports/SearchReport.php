<?php
/**
 * Report of searches
 * @author Simon "Sphere" Erkelens 
 */
class SearchReport extends SS_Report {

	public function title() {
		return(_t($this->class . '.TITLE', 'Searchreport'));
	}

	public function description() {
		return(_t($this->class . '.DESCRIPTION', 'Show searchqueries'));
	}

	public function sourceRecords($params, $sort, $limit) {
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
				'title' => _t($this->class . '.QUERY', 'Searchquery'),
				'formatting' => '$value',
			),
			'FromURL' => array(
				'title' => _t($this->class . '.FROM_URL', 'Search requested from this URL'),
				'formatting' => '$value',
			),
			'Created' => array(
				'title' => _t($this->class . '.CREATED', 'Search time/date'),
				'formatting' => '$value',
			),
		);

		return($fields);
	}

	public function parameterFields() {
		return(new FieldSet(
			new TextField('Query', _t($this->class . '.QUERY', 'Searchquery'))
		));
	}
}
