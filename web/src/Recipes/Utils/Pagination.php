<?php

/**
 * @author Fernando Henrique <fernandimgts@gmail.com>
 *
 */

namespace Recipes\Utils;

class Pagination
{
    const PAGINATION_LIMITE_IL = 2;

    public static function makePagination($totalRows, $currentPage, $limit)
    {
        $result = array();

        $first = 1;
        $prev = $currentPage - 1;
        $next = $currentPage + 1;
        $last = ceil($totalRows / $limit);

        $result['total'] = $totalRows;
        $result['current'] = $currentPage;
        $result['next'] = ($next > $last) ? $last : $next;
        $result['prev'] = ($prev < $first) ? $first : $prev;
        $result['first'] = $first;
        $result['last'] = $last;

        return $result;
    }

    public static function getOffset($page, $limit)
    {

        if ($page <= 1) {
            return 0;
        }

        return ($page - 1) * $limit;
    }
}