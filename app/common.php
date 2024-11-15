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
