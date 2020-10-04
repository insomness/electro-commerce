<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::query()->latest();
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-warning updateButton">
                                <i class="fa fa-pen-alt"></i>
                                Edit
                            </a>
                            <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger deleteButton">
                                <i class="fas fa-trash"></i>
                                Delete
                            </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.categories.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $plainNames = json_decode($request->getContent());
            $rows = [];
            foreach (explode('&', $plainNames->data)  as $keyValuePair) {
                list($key, $value) = explode('=', $keyValuePair);
                $rows[$key][] = $value;
            }

            $names = $rows['name'];
            // check if array names's value is empty
            foreach ($names as $key => $value) {
                if (empty($value)) {
                    unset($names[$key]);
                }
            }
            if (empty($names)) {
                return response()->json(['status' => false]);
            }

            $params = [];
            foreach ($names as $name) {
                $name = urldecode($name);
                $params[] = [
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            Category::insert($params);
            return response()->json(['status' => true]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $plainNames = json_decode($request->getContent());
            $rows = [];
            foreach (explode('&', $plainNames->data)  as $keyValuePair) {
                list($key, $value) = explode('=', $keyValuePair);
                $rows[$key][] = $value;
            }

            $name = $rows['name'][0];
            // check if array names's value is empty
            if (empty($name)) {
                return response()->json(['status' => false]);
            }

            $param = [
                'name' => $name,
                'slug' => Str::slug($name),
            ];

            DB::table('categories')
                ->where('id', $id)
                ->update($param);
            return response()->json(['status' => true]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }
    }
}
