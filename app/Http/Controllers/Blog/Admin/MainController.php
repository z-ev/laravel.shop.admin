<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use App\Rep\Admin\MainRep;
use App\Rep\Admin\OrderRep;
use App\Rep\Admin\ProductRep;
use Illuminate\Http\Request;
use MetaTag;




class MainController extends AdminBaseController
{
    private $orderRep;
    private $productRep;

    public function __construct()
    {
        parent::__construct();
        $this->orderRep = app(OrderRep::class);
        $this->productRep = app(ProductRep::class);

    }

    public function index()
    {
        MetaTag::setTags([
            'title' => 'Shop Admin Panel',
            'description' => 'Shop Admin Panel',
        ]);
        $countOrders = MainRep::getCountOrders();
        $countUsers = MainRep::getCountUsers();
        $countProducts = MainRep::getCountProducts();
        $countCategories = MainRep::getCountCategories();

        $perpage = 5;
        $last_orders = $this->orderRep->getAllOrders($perpage+3);
        $last_products = $this->productRep->getLastProducts($perpage);

        return view('blog.admin.main.index', compact('countOrders','countUsers', 'countProducts', 'countCategories','last_orders','last_products'));
    }
}
