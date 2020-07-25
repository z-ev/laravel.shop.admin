<?php


namespace App\Rep\Admin;

use App\Models\Admin\Order as Model;
use App\Rep\CoreRep;


class OrderRep extends MainRep
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getAllOrders($perpage)
    {

        $orders = $this->startConditions()::withTrashed()
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('order_products', 'order_products.order_id', '=', 'orders.id')
            ->select('orders.id', 'orders.user_id', 'orders.status', 'orders.created_at', 'orders.updated_at',
                'orders.currency', 'users.name',
                \DB::raw('ROUND(SUM(order_products.price), 2) AS sum'))
            ->groupBy('orders.id')
            ->orderBy('orders.status')
            ->orderBy('orders.id')
            ->toBase()
            ->paginate($perpage);

        return $orders;
    }

    public function getOneOrder($order_id)
    {
        $order = $this->startConditions()::withTrashed()
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('order_products', 'order_products.order_id', '=', 'orders.id')
            ->select('orders.*', 'users.name',
                \DB::raw('ROUND(SUM(order_products.price), 2) AS sum'))
            ->where('orders.id', $order_id)
            ->groupBy('orders.id')
            ->orderBy('orders.status')
            ->orderBy('orders.id')
            ->limit(1)
            ->first();
        return $order;
    }
    /** Get All Order Products Id */
    public function getAllOrderProductsId($order_id)
    {
        $orderProducts = \DB::table('order_products')
            ->where('order_id', $order_id)
            ->get();
        return $orderProducts;
    }

    /** Change Status for Order */
    public function changeStatusOrder($id)
    {
        $item = $this->getEditId($id);
        if (!$item) {
            abort(404);
        }
        $item->status = !empty($_GET['status']) ? '1' : '0';
        $result = $item->update();
        return $result;
    }

    /** Save Comment in Edit Order */
    public function saveOrderComment($id)
    {
        $item = $this->getEditId($id);
        if (!$item) {
            abort(404);
        }
        $note = !empty($_POST['comment']) ? $_POST['comment'] : null;
        $item->note = $note;
        $result = $item->update();
        return $result;
    }

    /** Soft Delete one Order */
    public function changeStatusOnDelete($id)
    {
        $item = $this->getEditId($id);
        if (!$item) {
            abort(404);
        }
        $item->status = '2';
        $result = $item->update();
        return $result;
    }




}
