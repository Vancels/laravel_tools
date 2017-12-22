<?php

namespace Vancels\Tools\Service;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

/**
 * Class ToolServiceInterface
 * @package Vancels\Administrator\Service
 */
class ToolServiceInterface
{
    /**
     * DatabaseService
     * @return \Illuminate\Foundation\Application|mixed|DatabaseService
     */
    public function database_service()
    {
        return app(DatabaseService::class);
    }


    /**
     * 样式文件 - label
     * label-primary 绿
     * label-success 蓝
     * label-warning 黄
     * label-danger  红
     *
     *
     * @param $status
     * @param $name
     *
     * @return string
     */
    public function field_view_status_label($status, $name, $true_class = 'label-primary', $false_class = 'label-default')
    {
        $status = $status ? $true_class : $false_class;

        return '<span class="label ' . $status . '">' . $name . '</span> ';
    }


    //生成订单号
    public function get_rand_number($pre = '')
    {
        return $pre . date('ym') . substr(time(), 4) . substr(microtime(), 2, 6) . rand(10, 99);
    }

    /**
     * 快速选择
     *
     * @param mixed  $a
     * @param mixed  $b
     * @param string $c
     * @param string $d
     *
     * @return string
     */
    public function a2bc($a, $b, $c = '', $d = '')
    {
        return $a == $b ? $c : $d;
    }

    /**
     * 分页获取
     *
     * @param array  $results 数据
     * @param int    $total 总数
     * @param int    $perPage 每页显示条数 [默认:15]
     * @param string $pageName 分页参数名 [默认:page]
     *
     * @return LengthAwarePaginator
     */
    public function page($results = [], $total = 0, $perPage = 15, $pageName = 'page')
    {
        $page = Paginator::resolveCurrentPage($pageName);

        return new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path'     => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }

    public function pageFromSQL($results = [], $total = 0, $perPage = 15, $pageName = 'page')
    {
    }

    /**
     * 共用返回状态
     *
     * @param bool   $status
     * @param string $msg
     * @param array  $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnData($status = true, $msg = '', $data = [])
    {
        return \Response::json([
            'status' => $status,
            'msg'    => $msg,
            'data'   => $data,
        ]);
    }

    /**
     * 返回callable的内容
     *
     * @param callable $callable
     *
     * @return mixed
     */
    public function callReturn(callable $callable)
    {
        return $callable();
    }

    /**
     * 获得最后一条SQL
     *      需要开启 \DB::enableQueryLog();
     *
     * @return mixed
     */
    public function getLastSql($end = true)
    {
        $sql = \DB::getQueryLog();
        if (!$end) {
            return $sql;
        }

        return end($sql);
    }

    /**
     * 返回combox_value值
     *
     * @param      $key
     * @param null $param
     *
     * @return mixed
     */
    public static function combox_value($key, $param = null)
    {
        if ($param == null) {
            $param = \Input::all();
        }
        if (isset($param[$key . '_primary_key'])) {
            return $param[$key . '_primary_key'];
        }

        return false;
    }

    public function asset_url($url)
    {
        return \URL::asset($url);
    }

    public function current_full_url()
    {
        $url = \Request::getSchemeAndHttpHost() . $_SERVER['REQUEST_URI'];

        return $url;
    }

    /**
     * 格式化商品价格
     *
     * @access  public
     *
     * @param   float $price 商品价格
     *
     * @return  string
     */
    public function price_format($price, $change_price = true, $price_format = 0, $currency_format = "%s")
    {
        //$currency_format = "%s元";

        if ($change_price) {
            switch ($price_format) {
                case 0:
                    $price = number_format($price, 2, '.', '');
                    break;
                case 1: // 保留不为 0 的尾数
                    $price = preg_replace('/(.*)(\\.)([0-9]*?)0+$/', '\1\2\3', number_format($price, 2, '.', ''));

                    if (substr($price, -1) == '.') {
                        $price = substr($price, 0, -1);
                    }
                    break;
                case 2: // 不四舍五入，保留1位
                    $price = substr(number_format($price, 2, '.', ''), 0, -1);
                    break;
                case 3: // 直接取整
                    $price = intval($price);
                    break;
                case 4: // 四舍五入，保留 1 位
                    $price = number_format($price, 1, '.', '');
                    break;
                case 5: // 先四舍五入，不保留小数
                    $price = round($price);
                    break;
            }
        } else {
            if (!$price) {
                $price = 0;
            }

            $price = number_format($price, 2, '.', '');
        }

        return sprintf($currency_format, $price);
    }
}