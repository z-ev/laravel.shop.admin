<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminOrderSaveRequest;
use App\Models\Admin\Order;
use App\Rep\CoreRep;
use App\Rep\Admin\MainRep;
use App\Rep\Admin\OrderRep;
use Illuminate\Http\Request;
use MetaTag;

class OrderController extends AdminBaseController
{
    private $orderRep;
    public function __construct()
    {
        parent::__construct();
        $this->orderRep = app(OrderRep::class);

    }


    public function index()
    {
        $perpage = 5;
        $countOrders = MainRep::getCountOrders();
        $paginator = $this->orderRep->getAllOrders(5);

        MetaTag::setTags(['title' => 'Список заказов']);
        return view('blog.admin.order.index',
            compact('countOrders', 'paginator'));

    }


    public function edit($id)
    {
        $item = $this->orderRep->getEditId($id);
        if (empty($item)) {
            abort(404);
        }

        $order = $this->orderRep->getOneOrder($item->id);
        if (!$order) {
            abort(404);
        }
        $order_products = $this->orderRep->getAllOrderProductsId($item->id);

        MetaTag::setTags(['title' => "Заказ № {$item->id}"]);

        return view('blog.admin.order.edit',
            compact('item', 'order', 'order_products'));

    }

    public function change($id)
    {
        $result = $this->orderRep->changeStatusOrder($id);

        if ($result) {
            return redirect()
                ->route('blog.admin.orders.edit', $id)
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => "Ошибка сохранения"]);
        }

    }

    public function save(AdminOrderSaveRequest $request, $id)
    {
        $result = $this->orderRep->saveOrderComment($id);
        if ($result) {
            return redirect()
                ->route('blog.admin.orders.edit', $id)
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => "Ошибка сохранения"]);
        }
    }

    /**
     * Софт удаление
     * @param $id
     * @return $this
     */
    public function destroy($id)
    {
        $st = $this->orderRep->changeStatusOnDelete($id);
        if ($st) {
            $result = Order::destroy($id);
            if ($result) {
                return redirect()
                    ->route('blog.admin.orders.index')
                    ->with(['success' => "Запись id [$id] удалена"]);
            } else {
                return back()->withErrors(['msg' => 'Ошибка удаления']);
            }
        } else {
            return back()->withErrors(['msg' => 'Статут не изменился']);
        }
    }

    /**
     * Полное удаление
     * @param $id
     * @return $this
     */
    public function forcedestroy($id)
    {
        if (empty($id)){
            return back()->withErrors(['msg' => 'Запись не найдена']);
        }

        $res = \DB::table('orders')
            ->delete($id);

        if ($res) {
            return redirect()
                ->route('blog.admin.orders.index')
                ->with(['success' => "Запись id [$id] удалена из БД"]);
        } else {
            return back()->withErrors(['msg' => 'Ошибка удаления']);
        }
    }






}
