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
        //进行排序
        $tree = array_sort($tree);
        foreach($tree as $k => $v){
            $tree[$k]['children']=array_sort($v['children']);
        }
        foreach($tree as $k => $v){
            foreach($v['children'] as $kk => $vv){
                $tree[$k]['children'][$kk]['children']=array_sort($vv['children']);
            }
        }
        return $tree;
    }
}
if (!function_exists('array_sort')) {
    /**
     * 二维数组排序
     *
     * @param array $array
     * @param string $key1
     * @param string $key2
     * @return void
     */
    function array_sort($array, $key1='sort', $key2 = 'id')
    {
        foreach ($array as $k => $v) {
            $sort[$k]  = $v[$key1];
            $id[$k] = $v[$key2];
        }
        array_multisort($sort, SORT_DESC, $id, SORT_ASC, $array); //$sort第一排序降序，$id第二排序升序
        return $array;
    }
}
