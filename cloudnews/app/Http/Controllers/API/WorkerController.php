<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class WorkerController extends Controller
{
    public function index(){
        $workers = Worker::query()->orderBy('id')->paginate(10);

        return response($workers->jsonSerialize());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required|min:4|max:50',
            'date'=> 'required',
            'post'=> 'required|min:4|max:150'
        ]);

        $worker = new Worker;

        $worker->name = $request->input('name');
        $worker->date = $request->input('date');
        $worker->post = $request->input('post');

        $worker->save();

        return response($worker->jsonSerialize(), Response::HTTP_CREATED);
    }

    public function update(Request $request, $id){

        $request->validate([
            'name'=> 'required|min:4|max:50',
            'date'=> 'required',
            'post'=> 'required|min:4|max:150'
        ]);

        $worker = Worker::query()->findOrFail($id);

        $worker->name = $request->get('name');
        $worker->date = $request->get('date');
        $worker->post = $request->get('post');

        $worker->save();

        return response(null, Response::HTTP_OK);
    }

    public function destroy($id){
        $worker = Worker::query()->findOrFail($id);
        $worker->delete();

        return response(null, Response::HTTP_OK);
    }
}
