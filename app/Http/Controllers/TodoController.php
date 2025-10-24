<?php

namespace App\Http\Controllers;

use App\Models\TodoModel;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function addPost(Request $request){
        $post =TodoModel::create([
//            'title'=> $request->input('title'),
//            'body'=> $request->input('body'),
//            'is_done'=> $request->input('is_done'),
            'title'=>'hello',
            'body'=>'hello world',
            'is_done'=>false,
            'user_id'=>1,
        ]);

        return response()->json(['post'=>$post,'message'=>'Task created success'],200);
    }
    public function getPosts(){
        $posts = TodoModel::all();
        return response()->json(['post'=>$posts,'message'=>'Task created success'],200);

    }
    public function getPost($id){
        $post= TodoModel::where('id',$id)->first();
        return response()->json(['post'=>$post,'message'=>'Task created success'],200);
    }
    public function updatePost(Request $request,$id){
        $post=TodoModel::where('id',$id)->update([
            'title'=>'hello Messi',
            'body'=>'hello in Messi world',
            'is_done'=>true,

        ]);
        if(!$post){
            return response()->json(['post'=>$post,'message'=>'the task doesnt exists'],200);
        }
        return response()->json(['post'=>$post,'message'=>'Task updated success'],200);
    }
    public function deletePost($id){
        $post=TodoModel::where(['id'=>$id])->delete();
        return response()->json(['message'=>'Task deleted success'],200);
    }

}
