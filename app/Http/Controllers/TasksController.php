<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Task;    // 追加
class TasksController extends Controller
{
    // getでtasks/にアクセスされた場合の「一覧表示処理」
    public function index()
    {
        if (\Auth::check()) { // 認証済みユーザを取得
            // \Auth::user() が所有するタスクを取得 
            $user = \Auth::user();
            $tasks = $user->tasks;
            
            return view('tasks.index', [
            'tasks' => $tasks,
             ]);
        }
        // Welcomeビューでそれらを表示
        return view('welcome');
    }
    
    // getでtasks/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        // コンテンツ作成ビューを表示
        $task = new Task;
        return view('tasks.create', [
            'task' => $task,
        ]);

    }
    // postでtasks/にアクセスされた場合の「新規登録処理」
    public function store(Request $request)
    {
        // バリデーション
        $this->validate($request, [
            'status' => 'required|max:10',   // 追加
            'content' => 'required|max:255',
        ]);
        // コンテンツを作成
        $task = new Task;
        $task->user_id = \Auth::id();
        $task->status = $request->status;    // 追加
        $task->content = $request->content;
        $task->save();
        // トップページへリダイレクトさせる
        return redirect('/');
    }
    // getでtasks/idにアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        // idの値でコンテンツを検索して取得
        $task = Task::findOrFail($id);

        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、「取得表示処理」
        if (\Auth::id() == $task->user_id) {
        // コンテンツ詳細ビューでそれを表示
        return view('tasks.show', [
            'task' => $task,
        ]);
        }
        // トップページへリダイレクトさせる
        return redirect('/');
    }
    // getでtasks/id/editにアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
        // idの値でコンテンツを検索して取得
        $task = Task::findOrFail($id);
        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、「更新画面表示処理」
        if (\Auth::id() == $task->user_id) {
        // コンテンツ編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
        }
        // トップページへリダイレクトさせる
        return redirect('/');
    }
    // putまたはpatchでtasks/idにアクセスされた場合の「更新処理」
    public function update(Request $request, $id)
    {
        // バリデーション
        $this->validate($request, [
            'status' => 'required|max:10',   // 追加
            'content' => 'required|max:255',
        ]);
        // idの値でコンテンツを検索して取得
        $task = Task::findOrFail($id);
        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を「更新処理」
        if (\Auth::id() == $task->user_id) {
        // コンテンツを更新     
        $task->status = $request->status;    // 追加
        $task->content = $request->content;
        $task->save();
        }
        // トップページへリダイレクトさせる
        return redirect('/');
    }
     // deleteでtasks/idにアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        // idの値でコンテンツを検索して取得
        $task = Task::findOrFail($id);
        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() == $task->user_id) {
        // コンテンツを削除
            $task->delete();
        }
        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
