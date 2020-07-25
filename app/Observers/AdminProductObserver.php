<?php

namespace App\Observers;

use App\Models\Admin\Product;
use Illuminate\Support\Carbon;
use Symfony\Component\Console\Input\Input;


class AdminProductObserver
{
    public function creating(Product $product)
    {
        $this->setAlias($product);
    }

    public function created(Product $product)
    {
        //
    }


    public function updated(Product $product)
    {
        //
    }


    public function deleted(Product $product)
    {
        //
    }


    public function restored(Product $product)
    {
        //
    }


    public function forceDeleted(Product $product)
    {
        //
    }

    public function setAlias(Product $product)
    {

        if (empty($product->alias)){
            $product->alias = \Str::slug($product->title);

            $check = Product::where('alias','=', $product->alias)->exists();

            if ($check){
                $product->alias = \Str::slug($product->title) . time();
            }
        }
    }

    public function saving(Product $product)
    {
        $this->setPublishedAt($product);
        //return false;
    }

    public function setPublishedAt(Product $product)
    {
        $needSetPublished = empty($product->updated_at) || !empty($product->updated_at);

        if ($needSetPublished){
            $product->updated_at = Carbon::now();
        }
    }

}
