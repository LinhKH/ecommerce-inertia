@extends('admin.layout')
@section('title','Orders')
@section('content')
<div class="content-wrapper">
    <div class="message"></div>
    <div class="container">
         <!-- Content Header (Page header) -->
        @component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard','Orders'=>'admin/orders']])
            @slot('title') Order @endslot
            @slot('add_btn')  @endslot
            @slot('active') All Orders @endslot
        @endcomponent
        <!-- /.content-header -->
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <tbody class="cart-data">
                            <tr class="active">
                                <th colspan="4"><h5><b>ORDER No. :  {{'ODR00'.$order->id}} </b></h5></th>
                                <th width="250px"><b>Order Placed : {{date('d M, Y',strtotime($order->created_at))}}</b></th>
                                <th>Action</th>
                            </tr>
                            @foreach($products as $row)
                                <input type="hidden" name="order_id" value="{{$row->order_id}}">
                                <tr>
                                    <td><img class="img-thumbnail" src="{{asset('/products/'.$row->thumbnail_img)}}" alt="" width="100px"></td>
                                    <td colspan="2">
                                        <span><b>Product Code :</b> PDR00{{$row->id}}</span><br>
                                        <span><b>Product Name :</b> {{substr($row->product_name,0,30).'...'}}</span><br>
                                        @if($row->product_color != '')
                                            @foreach($colors as $color)
                                                @if($color->id == $row->product_color)
                                                <span><b>Color : </b> {{$color->color_name}}</span></br>
                                                @endif
                                            @endforeach
                                        @endif
                                        @if($row->product_attr != '')
                                            @php $p_attr = array_filter(explode(',',$row->product_attr)); @endphp
                                            @for($i=0;$i<count($p_attr);$i++)
                                                @php $atr_val = array_filter(explode(':',$p_attr[$i]));  @endphp
                                                <span>
                                                    @foreach($attributes as $attr_array)
                                                    @if(!empty($atr_val) && $attr_array->id == $atr_val[0])
                                                    <span><b>{{$attr_array->title}}:</b></span>
                                                    @endif
                                                    @endforeach
                                                    @foreach($attrvalues as $attr_vals)
                                                    @if(!empty($atr_val) && ($attr_vals->id == $atr_val[1] && $atr_val[0] == $attr_vals->attribute))
                                                    <span>{{$attr_vals->value}}</span>
                                                    @endif
                                                    @endforeach
                                                </span></br>
                                            @endfor
                                        @endif
                                        <span><b>Qty : </b>{{$row->product_qty}}</span>
                                    </td>
                                    <td>
                                        <b>Sub Total : {{site_settings()->currency}}{{$row->product_amount}}</b> 
                                    </td>
                                    <td>
                                        @php 
                                            $date = $row->created_at;
                                            $totalDuration = +$row->shipping_days ;
                                        @endphp
                                        <b>Delivery Expected By : </b>{{date('d F, Y',strtotime($date.$totalDuration.'day'))}}
                                    </td>
                                    <td>
                                    @if($row->product_delivery == '1')
                                        <span>Delivered</span>
                                    @else
                                        <button class="btn btn-info btn-sm deliverConfirm" data-qty="{{$row->product_qty}}" data-id="{{$row->product_id}}" data-order-id="{{$order->id}}">Deliver</button>
                                    @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" align="right"><b>Total Amount ($)</b></td>
                                <td>{{site_Settings()->currency}}{{$order->amount}}</td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop