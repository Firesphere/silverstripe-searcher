<?php
/**
 * Report of the top searches
 * @author Simon "Sphere" Erkelens 
 */
class TopSearchesReport extends SS_Report {

	public function title() {
		return(_t($this->class . '.TITLE', 'Top searches report'));
	}

	public function description() {
		return(_t($this->class . '.DESCRIPTION', 'Show top searchquery\'s'));
	}

	public function sourceRecords($params, $sort, $limit) {
		$where = '';
		if ($sort == '') $sort = 'Occurences DESC';
		if (isset($params['Period']) && ($params['Period'] != '')) {
			if ($params['Period'] == 'LAST7DAYS') {
				$where .= 'WHERE Created >= DATE_SUB(CURDATE(), INTERVAL 15 DAY)';
			}
		}
		$res = DB::query("SELECT Query, COUNT(Query) AS Occurences FROM SearchQuery $where GROUP BY Query ORDER BY $sort");
		$queries = new DataObjectSet();
		while ($item = $res->nextRecord()) {
			$queries->push(new DataObject($item));
		}

		return($queries);
	}

	public function columns() {
		$fields = array(
			'Query' => array(
				'title' => _t($this->class . '.QUERY', 'Searchquery'),
				'formatting' => '$value',
			),
			'Occurences' => array(
				'title' => _t($this->class . '.OCCURENCES', 'Occurences'),
				'formatting' => '$value',
			),
		);

		return($fields);
	}

	public function parameterFields() {
		return(new FieldSet(
			new DropdownField('Period', _t($this->class . '.PERIOD', 'Period'), array(
				'ALL' => _t($this->class . '.ALL', 'All'),
				'LAST7DAYS' => _t($this->class . '.LAST7DAYS', 'Last 7 days'),
			))
		));
	}
}

