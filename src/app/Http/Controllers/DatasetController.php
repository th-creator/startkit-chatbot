<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use Illuminate\Http\Request;

class DatasetController extends Controller
{
    public function index() {
        $datasets = Dataset::where('user_id',auth('sanctum')->id())->get();
        return response()->json(['datasets'=>$datasets],200);
    }

    public function store(Request $Request, $id)
    {
        $dataset = $Request->validate([
            'name'=> 'required|string',
            'file'=>'required',
            'user_id'=>'required',
        ]);

        $dataset['file']=time().'.'. $request->file->extension();
        $request->image->move(public_path('profile') ,$dataset['file']);

        Dataset::create($dataset);
        return response()->json(['message'=>'user has been updated'], 200);
    }

    public function update($id ,Request $request)
    {
        $dataset = Dataset::findOrFail($id);

        $dataset->update([
            'name' => $request->input('name'),
        ]);
    
        return response()->json(['message' => 'Dataset updated successfully']);
    }

    public function destroy($id)
    {
        $dataset = Dataset::findOrFail($id);
        $dataset->delete();
        return response()->json(['message' => 'Dataset deleted successfully'], 200);
    }
}
