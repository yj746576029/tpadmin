<?php
if (!function_exists('pr')) {
    /**
     * 调试输出
     *
     * @param array $param
     */
    function pr($param)
    {
        echo '<pre>';
        print_r($param);
        echo '</pre>';
    }
}
if (!function_exists('prd')) {
    /**
     * 调试输出
     *
     * @param array $param
     */
    function prd($param)
    {
        pr($param);
        die;
    }
}
if (!function_exists('list_to_tree')) {
    /**
     * @param array $list 要转换的结果集
     * @param string $parent_id parent标记字段
     * @param string $child 子集合键名
     * @param int $root 顶级parent_id的值
     */
    function list_to_tree($list, $pk = 'id', $pid = 'parent_id', $child = 'children', $root = 0)
    {
        //创建Tree
        $tree = array();

        if (is_array($list)) {
            //创建基于主键的数组引用
            $refer = array();

            foreach ($list as $key => $data) {
                $refer[$data[$pk]] = &$list[$key];
            }

            foreach ($list as $key => $data) {
                //判断是否存在parent
                $parantId = $data[$pid];

                if ($root == $parantId) {
                    $tree[] = &$list[$key];
                } else {
                    if (isset($refer[$parantId])) {
                        $parent = &$refer[$parantId];
                        $parent[$child][] = &$list[$key];
                    }
                }
            }
        }

        return $tree;
    }
}
