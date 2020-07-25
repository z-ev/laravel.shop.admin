<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserEditRequest;
use App\Models\Role;
use App\Models\UserRole;
use App\Rep\Admin\MainRep;
use App\Rep\Admin\UserRep;
use App\Models\Admin\User;
use Illuminate\Http\Request;
use MetaTag;

class UserController extends AdminBaseController
{
    private $userRep;


    public function __construct()
    {
        parent::__construct();
        $this->userRep = app(UserRep::class);
    }


    public function index()
    {

        $perpage = 8;
        $countUsers = MainRep::getCountUsers();
        $paginator = $this->userRep->getAllUsers($perpage);

        MetaTag::setTags(['title' => 'Список пользователей']);
        return view('blog.admin.user.index',
            compact('countUsers', 'paginator'));

    }


    public function create()
    {
        MetaTag::setTags(['title' => 'Добавление пользователя']);
        return view('blog.admin.user.add');
    }

    public function store(AdminUserEditRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password'])
        ]);

        if (!$user) {
            return back()
                ->withErrors(['msg' => "Ошибка создания"])
                ->withInput();
        } else {
            $role = UserRole::create([
                'user_id' => $user->id,
                'role_id' => (int)$request['role'],
            ]);
            if (!$role) {
                return back()
                    ->withErrors(['msg' => "Ошибка создания Роли пользователя"])
                    ->withInput();
            } else {
                return redirect()
                    ->route('blog.admin.users.index', $user->id)
                    ->with(['success' => 'Успешно создан']);
            }
        }
    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $perpage = 10;
        $item = $this->userRep->getEditId($id);
        if (empty($item)) {
            abort(404);
        }
        $orders = $this->userRep->getUserOrders($id, $perpage);
        $role = $this->userRep->getUserRole($id);
        $count = $this->userRep->getCountOrdersPag($id);
        $count_orders = $this->userRep->getCountOrders($id, $perpage);

        MetaTag::setTags(['title' => "Редактирование профиля пользователя № {$item->id}"]);

        return view('blog.admin.user.edit',
            compact('item', 'orders', 'role', 'count_orders', 'count'));
    }


    public function update(AdminUserEditRequest $request, User $user, UserRole $role)
    {
        $user->name = $request['name'];
        $user->email = $request['email'];
        $request['password'] == null ?: $user->password = bcrypt($request['password']);
        $save = $user->save();
        if (!$save) {
            return back()
                ->withErrors(['msg' => "Ошибка сохранения"])
                ->withInput();
        } else {
            $role->where('user_id', $user->id)->update(['role_id' => (int)$request['role']]);
            return redirect()
                ->route('blog.admin.users.edit', $user->id)
                ->with(['success' => 'Успешно сохранено']);
        }

    }


    public function destroy(User $user)
    {
        $result = $user->forceDelete();
        if($result){
            return redirect()
                ->route('blog.admin.users.index')
                ->with(['success' => "Пользователь " . ucfirst($user->name) . " удален"]);
        } else {
            return back()->withErrors(['msg' => 'Ошибка удаления']);
        }
    }

}
