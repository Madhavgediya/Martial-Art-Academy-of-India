<?php
/**
 * Element
 *
 * Lets you determine whether an array index is set and whether it has a value.
 * If the element is empty it returns FALSE (or whatever you specify as the default value.)
 *
 * @access	public
 * @param	string
 * @param	array
 * @param	mixed
 * @return	mixed	depends on what the array contains
 */
if (!function_exists('element')) {

    function element($item, $array, $default = FALSE) {
        if (!isset($array[$item]) OR $array[$item] == "") {
            return $default;
        }

        return $array[$item];
    }

}

// ------------------------------------------------------------------------

/**
 * Random Element - Takes an array as input and returns a random element
 *
 * @access	public
 * @param	array
 * @return	mixed	depends on what the array contains
 */
if (!function_exists('random_element')) {

    function random_element($array) {
        if (!is_array($array)) {
            return $array;
        }

        return $array[array_rand($array)];
    }

}

// --------------------------------------------------------------------

/**
 * Elements
 *
 * Returns only the array items specified.  Will return a default value if
 * it is not set.
 *
 * @access	public
 * @param	array
 * @param	array
 * @param	mixed
 * @return	mixed	depends on what the array contains
 */
if (!function_exists('elements')) {

    function elements($items, $array, $default = FALSE) {
        $return = array();

        if (!is_array($items)) {
            $items = array($items);
        }

        foreach ($items as $item) {
            if (isset($array[$item])) {
                $return[$item] = $array[$item];
            } else {
                $return[$item] = $default;
            }
        }

        return $return;
    }

}

// --------------------------------------------------------------------

/**
 * When given an array of arrays (or objects) it will return the index of the
 * sub-array where $key == $value.
 *
 * <code>
 * $array = array(
 * 	array('value' => 1),
 * 	array('value' => 2),
 * );
 *
 * // Returns 1
 * array_index_by_key('value', 2, $array);
 * </code>
 *
 * @param $key mixed The key to search on.
 * @param $value The value the key should be
 * @param $array array The array to search through
 * @param $identical boolean Whether to perform a strict type-checked comparison
 *
 * @return false|int An INT that is the index of the sub-array, or false.
 */
if (!function_exists('array_index_by_key')) {

    function array_index_by_key($key = null, $value = null, $array = null, $identical = false) {
        if (empty($key) || empty($value) || !is_array($array)) {
            return false;
        }

        foreach ($array as $index => $sub_array) {
            $sub_array = (array) $sub_array;

            if (array_key_exists($key, $sub_array)) {
                if ($identical) {
                    if ($sub_array[$key] === $value) {
                        return $index;
                    }
                } else {
                    if ($sub_array[$key] == $value) {
                        return $index;
                    }
                }
            }
        }//end foreach

        return FALSE;
    }

//end array_index_by_key()
}

// --------------------------------------------------------------------

/**
 * Sort a multi-dimensional array by a column in the sub array
 *
 * @param array  $arr Array to sort
 * @param string $col The name of the column to sort by
 * @param int    $dir The sort directtion SORT_ASC or SORT_DESC
 *
 * @return void
 */
if (!function_exists('array_multi_sort_by_column')) {

    function array_multi_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
        if (empty($col) || !is_array($arr)) {
            return false;
        }

        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }

//end array_multi_sort_by_column()
}

/* End of file array_helper.php */