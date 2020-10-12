<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request request params
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::query();
            return DataTables::of($data)
                ->editColumn('grand_total', function ($row) {
                    return formatRupiah($row->grand_total);
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="/admin/orders/' . $row->id . '" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                                Detail
                            </a>';
                    return $btn;
                })
                ->filterColumn('customer_full_name', function ($query, $keyword) {
                    $sql = "CONCAT(customer_first_name,customer_last_name)  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.orders.index', $this->data);
    }

    /**
     * Display the trashed orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function trashed(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::onlyTrashed();
            return DataTables::eloquent($data)
                ->editColumn('grand_total', function ($row) {
                    return formatRupiah($row->grand_total);
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="/admin/orders/' . $row->id . '" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                                Detail
                            </a>';
                    return $btn;
                })
                ->filterColumn('customer_full_name', function ($query, $keyword) {
                    $sql = "CONCAT(customer_first_name,customer_last_name)  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.orders.trashed');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id order ID
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::withTrashed()->findOrFail($id);

        $this->data['order'] = $order;

        return view('admin.orders.show', $this->data);
    }

    /**
     * Display cancel order form
     *
     * @param int $id order ID
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        $order = Order::where('id', $id)
            ->whereIn('status', [Order::CREATED, Order::CONFIRMED])
            ->firstOrFail();

        $this->data['order'] = $order;

        return view('admin.orders.cancel', $this->data);
    }

    /**
     * Doing the cancel process
     *
     * @param Request $request request params
     * @param int     $id      order ID
     *
     * @return \Illuminate\Http\Response
     */
    public function doCancel(Request $request, $id)
    {
        $request->validate(
            [
                'cancellation_note' => 'required|max:255',
            ]
        );

        $order = Order::findOrFail($id);

        DB::transaction(function () use ($order, $request) {
            $params = [
                'status' => Order::CANCELLED,
                'cancelled_by' => Auth::user()->id,
                'cancelled_at' => now(),
                'cancelled_note' => $request->input('cancellation_note'),
            ];

            return $order->update($params);
        });

        Session::flash('success', 'The order has been cancelled');

        return redirect('admin/orders');
    }

    /**
     * Marking order as completed
     *
     * @param Request $request request params
     * @param int     $id      order ID
     *
     * @return \Illuminate\Http\Response
     */
    public function doComplete(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if (!$order->isDelivered()) {
            Session::flash('error', 'Mark as complete the order can be done if the latest status is delivered');
            return redirect('admin/orders');
        }

        $order->status = Order::COMPLETED;
        $order->approved_by = Auth::user()->id;
        $order->approved_at = now();

        if ($order->save()) {
            Session::flash('success', 'The order has been marked as completed!');
            return redirect('admin/orders');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id order ID
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::withTrashed()->findOrFail($id);

        if ($order->trashed()) {
            $canDestroy = DB::transaction(
                function () use ($order) {
                    OrderItem::where('order_id', $order->id)->delete();
                    $order->payment->forceDelete();
                    $order->shipment->delete();
                    $order->forceDelete();

                    return true;
                }
            );

            if ($canDestroy) {
                Session::flash('success', 'The order has been removed permanently');
            } else {
                Session::flash('success', 'The order could not be removed permanently');
            }

            return redirect('admin/orders/trashed');
        } else {

            $canDestroy = DB::transaction(function () use ($order) {
                $order->payment->delete();
                $order->delete();

                return true;
            });

            if ($canDestroy) {
                Session::flash('success', 'The order has been removed');
            } else {
                Session::flash('success', 'The order could not be removed');
            }

            return redirect('admin/orders');
        }
    }

    /**
     * Restoring the soft deleted order
     *
     * @param int $id order ID
     *
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);

        $canRestore = $order->restore();

        if ($canRestore) {
            Session::flash('success', 'The order has been restored');
            return redirect('admin/orders');
        } else {
            Session::flash('error', 'The order could not be restored');
            return redirect('admin/orders/trashed');
        }
    }
}
