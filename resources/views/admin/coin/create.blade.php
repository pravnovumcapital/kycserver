@extends('admin.base.app')
@section('content')
<div class="row">
  <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Create Payment Method</h3>
            </div>

            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" method="POST" action="{{route('admin.coin.store')}}">
              {{csrf_field()}}
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Name</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter name of payment" required="" name="name" value="{{old('name')}}">
                </div>
                <div class="form-group">
                      <label>Type</label>
                      <select class="form-control" onchange="addPaymentMode(this)" required="" name="type">
                        <option value="">select</option>
                        <option value="coin">Coin</option>
                        <option value="bank">Bank</option>
                      </select>
                    </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>      
</div>
@endsection