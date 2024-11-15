<?php
// 应用公共文件
/**
 * 转换为tree
 * @param $parentId
 * @param $source
 * @param $parentKey
 * @param $childKey
 * @return array
 */
function trans_tree($parentId, $source, $parentKey = 'parent_id', $childKey= 'children')
{
    $result = array();
    foreach ($source as $item) {
        if ($item[$parentKey] == $parentId) {
            $item[$childKey] = trans_tree($item[$parentKey], $source);
            $result[] = $item;
        }
    }
    return $result;
}