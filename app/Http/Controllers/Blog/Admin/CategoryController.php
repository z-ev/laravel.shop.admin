<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Models\Admin\Category;
use App\Rep\Admin\CategoryRep;
use Illuminate\Http\Request;
use MetaTag;
use mysql_xdevapi\Exception;

class CategoryController extends AdminBaseController
{
    private $categoryRep;

    public function __construct()
    {
        parent::__construct();
        $this->categoryRep = app(CategoryRep::class);
    }



    public function index()
    {
        $arrMenu = Category::all();
        $menu = $this->categoryRep->buildMenu($arrMenu);

        MetaTag::setTags(['title'=>'Список категорий']);
        return view('blog.admin.category.index', ['menu' => $menu]);
    }

    /** @throws \Exception */
    public function mydel() {
        $id = $this->categoryRep->getRequestID();
            if (!$id) {return back()->withErrors(['mag'=>'ощибка с ID']);}
            $children =  $this->categoryRep->checkChildren($id);

            if ($children) {
                return back()->withErrors(['mag'=>'Удаление не возможно! Есть вложенные категории']);
            }
        $parents =$this->categoryRep->checkParentsProducts($id);
        if ($parents) {
            return back()->withErrors(['mag'=>'Удаление не возможно! В категории есть товары']);
        }
        $delete = $this->categoryRep->deleteCategory($id);
        if ($delete) {
            return redirect()->route('blog.admin.categories.index')
            ->with(['success'=>"Запись id {$id} удалена"]);
        } else {
            return back()->withErrors(['msg'=>'Ошибка удаления']);

        }

    }

    public function create()
    {
        $item = new Category();
        $categoryList = $this->categoryRep->getComboBoxCategories();


        MetaTag::settags(['title'=>'Создание новой категории']);
        return view('blog.admin.category.create', ['categories'=>Category::with('children')->
            where('parent_id', 0)
            ->get(),
            'delimiter' => '-',
            'item' => $item,
            ]);
    }


    public function store(BlogCategoryUpdateRequest $request)
    {
        $name = $this->categoryRep->checkUniqueName($request->title,$request->parent_id);

        if($name){
            return back()
                ->withErrors(['msg'=>'Не может быть в одной и той же Категории двух одинаковых. Выбирите другую Категорию.'])
                ->withInput();
        }

        $data = $request->input();
        $item = new Category();
        $item->fill($data)->save();

        if ($item) {
            return redirect()
                ->route('blog.admin.categories.create', [$item->id])
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg'=>'Ошибка сохранения'])
                ->withInput();
        }
    }


    public function show($id)
    {
        //
    }


    public function edit($id, CategoryRep $categoryRep)
    {
        $item = $this->categoryRep->getEditId($id);
        if (empty($item)){
            abort(404);
        }

        $categoryList = $this->categoryRep->getComboBoxCategories();

        MetaTag::setTags(['title' => 'Редактирование категории']);
        return view('blog.admin.category.edit',[
            'categories' => Category::with('children')->where('parent_id','0')->get(),
            'delimiter' => '-',
            'item' => $item,
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(BlogCategoryUpdateRequest $request, $id)
    {
        $item = $this->categoryRep->getEditId($id);
        if (empty($item)){
            return back()
                ->withErrors(['msg' => "Запись = [{$id}] не найдена"])
                ->withInput();
        }

        $data = $request->all();
        $result = $item->update($data);
        if ($result){
            return redirect()
                ->route('blog.admin.categories.edit', $item->id)
                ->with(['success' => "Успешно сохранено"]);
        } else {
            return back()
                ->withErrors(['msg' => 'Ошибка сохранения!'])
                ->withInput();
        }
    }


    public function destroy($id)
    {
        //
    }
}
