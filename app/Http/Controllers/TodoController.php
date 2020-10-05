<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    function is_user_allowed($id){

        $todo = ToDo::findOrFail($id);
        $user = Auth::user();
        $todolist = TodoList::find('id',$todo->todo_list_id);

        return $todolist  && $user->id==$todolist->user_id;
    }

    public function store(){

        $todo_list_id=request('todo_list_id');

        $user = Auth::user();
        $todolist = TodoList::find($todo_list_id);

        if($todolist && $user->id==$todolist->user_id){
            $todo = new Todo();
            $todo->name=request('name');
            $todo->todo_list_id=$todo_list_id;
            $todo->is_finished=false;
            $todo->save();
            return response()->json($todo,201);
        }
        else{
            return response()->json('Invalid User',401);
        }
    }
    public function index($list_id){
        return response()->json(Todo::where('todo_list_id',$list_id)->get());
    }
    public function show($id){
        return response()->json(Todo::findOrFail($id));
    }

    public function update(){
        $id = request('id');
        if($this->is_user_allowed($id)){
            $todo = Todo::findOrFail($id);
            $todo->name=request('name');
            $todo->is_finished=request('is_finished');
            $todo->save();
            return response()->json($todo);
        }
        else{
            return response()->json('Invalid User',401);
        }
    }

    public function destroy($id){
        if($this->is_user_allowed($id)){
            $todo = Todo::findOrFail($id);
            $todo->delete();
            return response()->json('',204);
        }
        else{
            return response()->json('Invalid User',401);
        }
    }
}
