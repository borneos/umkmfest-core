@extends('layouts.app')
@section('title', 'Management Coupons')
@section('content')
  @php
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $url = 'https://';
    } else {
        $url = 'http://';
    }
    $url .= $_SERVER['HTTP_HOST'];
    $url .= $_SERVER['REQUEST_URI'];
  @endphp

  <section id="list">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="flex justify-between items-center pb-6">
          <form action="{{ route('admin.coupons', request()->query()) }}">
            <div class="flex my-2">
              <input type="hidden" name="sortColumn" value="{{ $sortColumn }}" />
              <input type="hidden" name="sortDirection" value="{{ $sortDirection }}" />
              <input type="text" name="q" placeholder="Search" class="py-2 px-2 text-md border border-gray-200 rounded-l focus:outline-none" value="{{ $searchParam }}" />
              <button type="submit" class="btn btn-primary rounded-l-none">
                <x-bi-search class="h-6 w-6" />
              </button>
            </div>
          </form>
          <button class="btn btn-md btn-primary" onclick="modal_coupon.showModal()">Add</button>
        </div>

        <div class="grid grid-cols-3 bg-white">
          @foreach ($coupons as $coupon)
          <div class="flex justify-center items-center border border-gray-800 py-3">
            <div class="card bg-[url('https://res.cloudinary.com/borneos-co/image/upload/v1735054774/pktfest/r64wl2qqqq7qghcr5yog.webp')] bg-cover bg-center text-primary-content w-96 h-[175px] border-dashed border-blue-700 border">
              <div class="card-body px-2 gap-0">
                <div class="flex justify-between">
                  <div>
                    <h2 class="card-title text-orange-500 mt-4 mb-0 tracking-wide font-sans">Voucher Senilai</h2>
                    <div class="flex gap-1">
                      <p class="text-black text-3xl font-mono font-semibold tracking-normal">{{number_format($coupon->nominal, 0, '.', '.');}}</p>
                      <!-- <div class="flex items-center gap-1">
                        @if(!$coupon->complete_at)
                          <span class="circle-dot dot-green"></span>
                          <span class="text-xs text-green-600">Available</span>
                        @else
                          <span class="circle-dot dot-red"></span>
                          <span class="text-xs text-red-700">Used</span>
                        @endif
                      </div> -->
                    </div>
                    <div class="bg-white p-1 rounded-xl">
                      <p class="text-gray-600 text-[8px] font-mono tracking-wide">{{$coupon->code}}</p>
                    </div>
                  </div>
                  <div class="pt-4">
                    {!! QrCode::size(88)->generate($coupon->code) !!}
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        
        <div class="py-4">
          {{ $coupons->appends(['sortDirection' => request()->sortDirection, 'sortColumn' => request()->sortColumn, 'q' => request()->q])->onEachSide(9)->links() }}
        </div>
      </div>
    </div>
  </section>

  <dialog id="modal_coupon" class="modal">
    <form class="modal-box" action="{{ route('admin.coupons.store') }}" onsubmit="disableButton()" method="POST">
      @csrf
      <a href="{{ $url }}" class="btn btn-sm btn-circle absolute right-2 top-2">✕</a>
      <h3 class="font-semibold text-2xl pb-6 text-center">Generate Coupon</h3>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Total</span>
        </label>
        <input type="number" name="total" id="total" class="input input-bordered w-full {{ $errors->has('total') ? ' input-error' : '' }}" required>
        @if ($errors->has('total'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('total') }}</span>
          </label>
        @endif
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Total (Rp)</span>
        </label>
        <input type="number" name="nominal" id="nominal" class="input input-bordered w-full {{ $errors->has('nominal') ? ' input-error' : '' }}" required>
        @if ($errors->has('nominal'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('nominal') }}</span>
          </label>
        @endif
      </div>
      
      <x-form-action type="save" route="/admin/coupons" />
    </form>
  </dialog>

@endsection
@section('js')
  <script>
  </script>
@endsection
