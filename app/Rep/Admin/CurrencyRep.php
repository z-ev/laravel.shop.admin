<?php


namespace App\Rep\Admin;

use App\Models\Admin\Currency;
use App\Models\Admin\Currency as Model;
use App\Rep\CoreRep;

class CurrencyRep extends CoreRep
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClass()
    {
        return Model::class;

    }

    /** Get Info by Id */
    public function getInfoProduct($id)
    {
        $product = $this->startConditions()
            ->find($id);
        return $product;
    }

    /** All Currency */
    public function getAllCurrency()
    {
        $curr = $this->startConditions()::all();
        return $curr;
    }

    /** Switch Base Currency = 0  */
    public function switchBaseCurr()
    {


        $id = \DB::table('currencies')
            ->where('base','=','1')
            ->get()
            ->first();

        if ($id) {
            $id = $id->id;
            $new = Currency::find($id);
            $new->base = '0';
            $new->save();

       } else  {

            return back()->withErrors(['msg'=>'Базовая валюта установленна!'])
                ->withInput();
       }

    }


    /** Delete Currency */
    public function deleteCurrency($id)
    {
        $delete = $this->startConditions()->where('id', $id)->forceDelete();
        return $delete;
    }

}
