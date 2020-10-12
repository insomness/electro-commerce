<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailOrderShipped;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Shipment::with(['order']);
            return DataTables::of($data)
                ->addColumn('code', function ($row) {
                    return $row->order->code;
                })
                ->addColumn('shipping_service', function ($row) {
                    return $row->order->shipping_service_name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="/admin/orders/' . $row->order->id . '/" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                                Details
                            </a>';
                    return $btn;
                })
                ->filterColumn('full_name', function ($query, $keyword) {
                    $sql = "CONCAT(first_name,last_name)  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.shipments.index', $this->data);
    }

    public function edit($id)
    {
        $shipment = Shipment::findOrFail($id);
        $this->data['shipment'] = $shipment;
        $this->data['provinces'] = $this->getProvinces();
        $this->data['cities'] = isset($shipment->province_id) ? $this->getCities($shipment->province_id) : [];

        return view('admin.shipments.edit', $this->data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'track_number' => 'required|max:255'
        ]);

        $shipment = Shipment::findOrFail($id);

        $order = DB::transaction(function () use ($shipment, $request) {
            $shipment->track_number = $request->track_number;
            $shipment->status = Shipment::SHIPPED;
            $shipment->shipped_at = now();
            $shipment->shipped_by = Auth::user()->id;

            if ($shipment->save()) {
                $shipment->order->status = Order::DELIVERED;
                $shipment->order->save();
            }

            return $shipment->order;
        });

        if ($order) {
            $this->_sendEmailOrderShipped($shipment->order);
        }

        Session::flash('success', 'The shipment has been updated');
        return redirect('admin/orders/' . $order->id);
    }

    private function _sendEmailOrderShipped($order)
    {
        SendEmailOrderShipped::dispatch($order);
    }
}
