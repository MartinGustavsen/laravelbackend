<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
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
        return response()->json($todolist,201);
    }
    public function index(){

        $user = Auth::user();
        return response()->json(TodoList::where('user_id', $user->id)->get());
    }
    public function show($id){
        if($this->is_user_allowed($id)){
            return response()->json(TodoList::findOrFail($id));
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
