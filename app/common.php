<?php
const EMPTY_DATETIME = '1970-01-01 00:00:00';
const EMPTY_DATE = '1970-01-01';

// 应用公共文件
/**
 * 转换为tree
 * @param int $parentId
 * @param array $source
 * @param string $pKey
 * @param string $parentKey
 * @param string $childKey
 * @return array
 */
function trans_tree(int $parentId, array $source, string $pKey = 'id', string $parentKey = 'parent_id', string $childKey= 'children')
{
    $result = array();
    foreach ($source as $item) {
        if ($item[$parentKey] == $parentId) {
            $item[$childKey] = trans_tree($item[$pKey], $source, $pKey, $parentKey, $childKey);
            $result[] = $item;
        }
    }
    return $result;
}

/**
 * aes ecb加密
 * @param $data
 * @param $key
 * @return false|string
 */
function aes_ecb_encrypt($data, $key)
{
    return openssl_encrypt ($data, 'aes-256-ecb', $key);
}

/**
 * aes ecb解密
 * @param $data
 * @param $key
 * @return false|string
 */
function aes_ecb_decrypt ($data, $key)
{
    return openssl_decrypt ($data, 'aes-256-ecb', $key);
}
