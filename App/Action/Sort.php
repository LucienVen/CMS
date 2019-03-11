<?php

namespace App\Action;

use Core\Action;

class Sort extends Action
{
    // 生成长度为10的随机数组
    protected function randArray($len = 10)
    {
        $rand = [];
        for ($i = 0; $i <= $len-1; $i++) {
            $rand[] = \rand(1, 100);
        }
        return $rand;
    }

    // 快速排序实现
    protected function quickSortCode($arr)
    {
        $arr_len = count($arr);
        if ($arr_len <= 1) {
            return $arr;
        }

        // 基准元素
        $base = $arr[0];

        // 定义两个空数组，存放比较后的结果
        $left = [];
        $right = [];

        // 循环比较
        for ($i = 1; $i < $arr_len; $i++) {
            // 由小到大排序
            if ($arr[$i] > $base) {
                $left[] = $arr[$i];
            }else {
                $right[] = $arr[$i];
            }
        }

        $left = $this->quickSortCode($left);
        $right = $this->quickSortCode($right);

        return array_merge($left, [$base] ,$right);

    }
    // 快排调用
    public function quickSort()
    {
        $arr = $this->randArray();
        // $arr_len = count($arr);
        
        $res = $this->quickSortCode($arr);
        
        return $this->success(200, ['origin' => $arr, 'sorted' => $res]);
    }

    // 冒泡排序实现
    public function bubbleSort()
    {
        $arr = $this->randArray();
        $arr_len = count($arr);

        $res['orgin'] = $arr;

        for ($i = 0; $i < $arr_len - 1; $i++) { 
            for ($j = 0; $j < $arr_len - $i - 1; $j++) { 
                if ($arr[$j] > $arr[$j + 1]) {
                    $temp = $arr[$j+1];
                    $arr[$j + 1] = $arr[$j];
                    $arr[$j] = $temp;
                }
                // print_r($arr);
                // echo PHP_EOL;
            }
        }

        $res['sorted'] = $arr;
        return $this->success(200, $res);
    }


    // 选择排序实现
    public function selectSort()
    {
        $arr = $this->randArray();
        $arr_len = count($arr);

        $res['orgin'] = $arr;

        for ($i = 0; $i < $arr_len - 1; $i++) { 
            // 假设最小值的位置
            $p = $i;

            for ($j = $i + 1; $j < $arr_len; $j++) { 
                // $arr[$p] 为目前已知最小值
                if ($arr[$p] > $arr[$j]) {
                    // 若有比$arr[$p] 更小的值，则记录下标
                    $p = $j;
                }
            }

            // 若假设的最小值下标改变
            if ($p != $i) {
                $temp = $arr[$i];
                $arr[$i] = $arr[$p];
                $arr[$p] = $temp;
            }
        }

        $res['sorted'] = $arr;
        return $this->success(200, $res);
    }

    // test
    public function test()
    {
        var_dump($this->randArray());
        print_r($this->quickSort($this->randArray()));
    }
}
