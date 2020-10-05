<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoListController extends Controller
{
    function is_user_allowed($id){
        $user = Auth::user();
        $todolist = TodoList::findOrFail($id);

        return $user->id==$todolist->user_id;
    }

    public function store(){

        $user = Auth::user();

        $todolist = new TodoList();
        $todolist->name=request('name');
        $todolist->user_id=$user->id;
        $todolist->save();

        foreach (request('todos') as $todo_request) {
            if($todolist){
                $todo = new Todo();
                $todo->name=$todo_request['name'];
                $todo->todo_list_id=$todolist->id;
                $todo->is_finished=false;
                $todo->save();
            }
        }
        
        return response()->json($todolist,201);
    }
    public function index(){
        $user = Auth::user();

        $data = TodoList::where('user_id', $user->id)->get();
        return response()->json(TodoList::with('todos')->get());
        // return response()->json(TodoList::where('user_id', $user->id)->get());
    }
    public function show($id){
        if($this->is_user_allowed($id)){
            $data = TodoList::findOrFail($id);
            return response()->json( $data->loadMissing('todos'));
        }
        else{
            return response()->json('Invalid User',401);
        }
    }

    public function update(){
        $id = request('id');
        if($this->is_user_allowed($id)){
            $todolist = TodoList::findOrFail($id);
            $todolist->name=request('name');
            $todolist->save();

            foreach (request('todos') as $todo_request) {
                if($todolist){
                    $todo = Todo::find($todo_request['id']);
                    if($todo==null){
                        $todo = new Todo();
                        $todo->todo_list_id=$todolist->id;
                    }
                    $todo->name=$todo_request['name'];
                    $todo->is_finished=$todo_request['is_finished'];
                    $todo->save();
                }
            }

            return response()->json($todolist);
        }
        else{
            return response()->json('Invalid User',401);
        }

    }

    public function destroy($id){
        if($this->is_user_allowed($id)){
            $todolist = TodoList::findOrFail($id);
            $todolist->delete();
            return response()->json('',204);
        }
        else{
            return response()->json('Invalid User',401);
        }
    }


}
