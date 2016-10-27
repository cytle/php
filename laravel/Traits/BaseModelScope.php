<?php

namespace ModelTrait59;

trait BaseModelScope {

	/**
	 * 使用 withOnly 只查询目标关联的部分字段
	 * 使用 $topics = Topic::limit(2)->withOnly('user', ['username'])->get();
	 *
	 * @return builder
	 */
	public function scopeWithOnly($query, $relation) {
		$relations = call_user_func_array([$this, "translateRelation"], func_get_args());
		return $query->with($relations);
	}

	/**
	 * 使用 loadOnly 只查询目标关联的部分字段
	 * 使用 $topics = Topic::limit(2)->loadOnly('user', ['username'])->get();
	 *
	 * @return model
	 */
	public function scopeLoadOnly($query, $relation) {
		$relations = call_user_func_array([$this, "translateRelation"], func_get_args());
		return $this->load($relations);
	}

	/**
	 * 转换"关系"
	 *
	 * @return array
	 */
	private function translateRelation($query, $relation) {
		$relations = [];
		// 判断是否为单个
		if (is_string($relation)) {

			$columns = (array) func_get_arg(2);

			$relations = [$relation => function ($query) use ($columns) {
				$query->select($columns);
			}];
		} else {
			foreach ((array) $relation as $key => $columns) {
				$relations[$key] = function ($query) use ($columns) {
					$query->select($columns);
				};
			}
		}
		return $relations;
	}

	/**
	 * whereArray()：填补where()方法不能以数组方式传值的空白
	 * @param  [Builder] $query [Builder]
	 * @param  [array] $where [条件数组]
	 * @return [Builder]        [Builder]
	 */
	public function scopeWhereArray($query, $where) {
		if (!is_array($where) || empty($where)) {
			return $query;
		}
		foreach ($where as $key => $value) {
			if (!is_array($value)) {
				$query->where($key, $value);
			} else {
				$query->where($key, $value[0], $value[1]);
			}
		}
		return $query;
	}

	/**
	 * 用于返回数据列表，当 $limit > 0 时，进行分页，否则取所有符合的数据
	 *
	 * @param int $limit 每页数量
	 * @return Array
	 */
	public function scopePaginateList($query, $limit = 0) {

		$limit = intval($limit);

		if ($limit <= 0) {

			$paginate = [
				'data' => $query->get()->toArray(),
			];

		} else {

			//注意！！！ paginate 方法第二个参数是 $columns(默认为['*']) 表示将select的字段，使用[]参数表示不在此添加字段
			$paginate = $query->paginate($limit, [])->toArray();
			// unset($paginate['next_page_url']); // 这个链接参数不太对
			// unset($paginate['prev_page_url']);

		}

		return $paginate;
	}

}
