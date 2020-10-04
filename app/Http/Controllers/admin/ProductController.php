<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->data['statuses'] = Product::STATUSES;
        $this->data['categories'] = Category::pluck('name', 'id');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::query();
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    $btn = '<a href="/admin/products/' . $row->id . '/edit" class="btn btn-warning ">
                                <i class="fa fa-pen-alt"></i>
                                Edit
                            </a>
                            <a href="/admin/products/' . $row->id . '" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                                Detail
                            </a>
                            <a href="javascript:void(0)" title="Delete Product" data-id="' . $row->id . '" onclick="swalDelete();" class="btn btn-danger deleteButton">
                                <i class="fas fa-trash"></i>
                                Delete
                            </a>';
                    return $btn;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge badge-success">Active</span>';
                    } elseif ($row->status == 2) {
                        return '<span class="badge badge-danger">Inactive</span>';
                    } else {
                        return '<span class="badge badge-warning">Draft</span>';
                    }
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['products'] = null;
        return view('admin.products.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $params = $request->except('_token');
        $params['price'] = cleanNumber($request->price);
        $params['slug'] = Str::slug($params['name']);

        $product = DB::transaction(
            function () use ($params) {
                $product = Product::create($params);
                return $product;
            }
        );

        Session::flash('success', __('product.create'));
        return redirect()->route('admin.products.show', $product->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $this->data['product'] = $product;
        return view('admin.products.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $this->data['product'] = $product;
        return view('admin.products.create', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $params = $request->except('_token');
        $params['price'] = cleanNumber($request->price);
        $params['slug'] = Str::slug($params['name']);

        $product = Product::findOrFail($id);

        $saved = false;
        $saved = DB::transaction(function () use ($params, $product) {
            $product->update($params);
            return true;
        });

        if ($saved) {
            Session::flash('success', __('product.update'));
        } else {
            Session::flash('error', __('product.error', ['status' => 'updating']));
        }

        return redirect()->route('admin.products.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        if ($request->ajax()) {
            Session::flash('success', __('product.delete'));
            return response()->json(['href' => route('admin.products.index')]);
        }
        return redirect()->route('admin.products.index')->with('success', __('product.delete'));
    }
}
