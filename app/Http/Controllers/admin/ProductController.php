<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductImagseRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

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
                    $btn = '<a href="' . route("admin.products.images", $row->id) . '" class="btn btn-default">
                                <i class="fa fa-images"></i>
                                Images
                            </a>
                            <a href="/admin/products/' . $row->id . '/edit" class="btn btn-warning ">
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
                ->editColumn('price', function ($row) {
                    return formatRupiah($row->price);
                })
                ->rawColumns(['action', 'status', 'price'])
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
        $params['weight'] = cleanNumber($request->weight);
        $params['slug'] = Str::slug($params['name']);

        $product = DB::transaction(
            function () use ($params) {
                $product = Product::create($params);
                return $product;
            }
        );

        Session::flash('success', __('product.create'));
        return redirect()->route('admin.products.edit', $product->id);
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
        $params['weight'] = cleanNumber($request->weight);
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

    public function images($id)
    {
        $this->data['product'] = Product::findOrFail($id);
        return view('admin.products.images', $this->data);
    }

    public function imagesUpload(ProductImagseRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $files = $request->file('images');

        $images = [];
        foreach ($files as $key => $file) {

            $name = $product->slug . '_' . time() . $key;
            $fileName = $name . '.' . $file->getClientOriginalExtension();

            $file = Image::make($file)->fit(600, 600, function ($constraint) {
                $constraint->upsize();
            })->stream();

            $folder = ProductImage::UPLOAD_FOLDER;
            $filePath =  $folder . $fileName;
            Storage::put("public/" . $filePath, $file);

            $images[] = [
                'product_id' => $product->id,
                'path' => $filePath,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        ProductImage::insert($images);
        return redirect()->route('admin.products.images', $product->id)->with('success', 'image added successfully');
    }

    public function imageRemove($id)
    {
        $image = ProductImage::find($id);
        $id = $image->product_id;
        Storage::delete('public/' . $image->path);
        $image->delete();
        return redirect()->route('admin.products.images', $id)->with('success', 'image has been deleted!');
    }
}
