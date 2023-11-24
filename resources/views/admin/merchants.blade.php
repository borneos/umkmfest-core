@extends('layouts.app')
@section('title', 'Master Merchant')
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
          <form action="{{ route('admin.merchants', request()->query()) }}">
            <div class="flex my-2">
              <input type="hidden" name="sortColumn" value="{{ $sortColumn }}" />
              <input type="hidden" name="sortDirection" value="{{ $sortDirection }}" />
              <input type="text" name="q" placeholder="Search" class="py-2 px-2 text-md border border-gray-200 rounded-l focus:outline-none" value="{{ $searchParam }}" />
              <button type="submit" class="btn btn-primary rounded-l-none">
                <x-bi-search class="h-6 w-6" />
              </button>
            </div>
          </form>
          <button class="btn btn-md btn-primary" onclick="modal_merchant.showModal()">Add</button>
        </div>
        <div class="card bg-white rounded-lg">
          <div class="card-body p-0">
            <div class="overflow-x-auto">
              <table class="table">
                <thead>
                  <tr>
                    <th width="3%">
                      <x-column-header dataRoute="admin.merchants" column-name="id" :sort-column="$sortColumn" :sortDirection="$sortDirection">#</x-column-header>
                    </th>
                    <th>
                      <x-column-header dataRoute="admin.merchants" column-name="name" :sort-column="$sortColumn" :sortDirection="$sortDirection">Name</x-column-header>
                    </th>
                    <th>
                      Description
                    </th>
                    <th width="100">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($merchants as $merchant)
                    <tr>
                      <td>{{ $merchant->id }}</td>
                      <td>
                        <div class="flex items-center space-x-3">
                          <div class="avatar">
                            <div class="mask mask-squircle w-9 h-9">
                              @if ($merchant->image != '')
                                <img src="{{ $merchant->image }}" alt="{{ $merchant->name }}">
                              @else
                                <img src="https://placehold.co/100x100" alt="blank" />
                              @endif
                            </div>
                          </div>
                          <div>
                            <div class="font-bold">{{ $merchant->name }}</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        {{ Str::limit($merchant->description, 70, '...') }}
                      </td>
                      <td>
                        <div class="flex items-center justify-end gap-2">
                          <button onClick="handleEdit(`{{ $merchant->id }}`)" class="btn btn-sm btn-square btn-ghost">
                            <x-bi-pencil-square class="w-4 h-4" />
                          </button>
                          <button onClick="handleDelete(`{{ $merchant->id }}`)" class="btn btn-sm btn-square btn-ghost">
                            <x-bi-trash class="w-4 h-4" />
                          </button>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        {{ $merchants->appends(['sortDirection' => request()->sortDirection, 'sortColumn' => request()->sortColumn, 'q' => request()->q])->onEachSide(5)->links() }}
      </div>
    </div>
  </section>

  <dialog id="modal_merchant" class="modal">
    <form class="modal-box" action="{{ route('admin.merchants.store') }}" onsubmit="disableButton()" method="POST" enctype="multipart/form-data">
      @csrf
      <a href="{{ $url }}" class="btn btn-sm btn-circle absolute right-2 top-2">✕</a>
      <h3 class="font-semibold text-2xl pb-6 text-center">Add New Merchant</h3>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Name</span>
        </label>
        <input name="name" type="text" placeholder="Your merchant name" class="input input-bordered w-full {{ $errors->has('name') ? ' input-error' : '' }}" required />
        @if ($errors->has('name'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('name') }}</span>
          </label>
        @endif
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Description</span>
        </label>
        <textarea name="description" rows="3" class="textarea textarea-bordered w-full {{ $errors->has('description') ? ' input-error' : '' }}" required></textarea>
        @if ($errors->has('description'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('description') }}</span>
          </label>
        @endif
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Image</span>
        </label>
        <img id="merchantPreview" class="rounded-md mx-auto" hidden>
        <input name="image" id="image" type="file" accept="image/*" onchange="previewImageOnAdd()" class="file-input file-input-bordered w-full {{ $errors->has('name') ? ' input-error' : '' }}" required />
        @if ($errors->has('image'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('image') }}</span>
          </label>
        @endif
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Status</span>
        </label>
        <input type="checkbox" name="status" class="toggle" checked />
        @if ($errors->has('link'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('status') }}</span>
          </label>
        @endif
      </div>
      <x-form-action type="save" route="/admin/merchants" />
    </form>
  </dialog>

  <dialog id="modal_merchant_edit" class="modal">
    <form class="modal-box" action="{{ route('admin.merchants.update') }}" onsubmit="disableButton()" method="POST" enctype="multipart/form-data">
      @csrf
      <a href="{{ $url }}" class="btn btn-sm btn-circle absolute right-2 top-2">✕</a>
      <h3 class="font-semibold text-2xl pb-6 text-center">Edit Merchant</h3>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Name</span>
        </label>
        <input id="name" name="name" type="text" placeholder="Your merchant name" class="input input-bordered w-full {{ $errors->has('name') ? ' input-error' : '' }}" />
        <input type="hidden" name="merchant_id" id="merchant_id" />
        @if ($errors->has('name'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('name') }}</span>
          </label>
        @endif
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Description</span>
        </label>
        <textarea name="description" id="description" rows="3" class="textarea textarea-bordered w-full {{ $errors->has('description') ? ' input-error' : '' }}" required></textarea>
        @if ($errors->has('description'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('description') }}</span>
          </label>
        @endif
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Image</span>
        </label>
        <img id="merchantPreviewEdit" class="rounded-md mx-auto">
        <input name="image" id="image" type="file" accept="image/*" onchange="previewImageOnEdit()" class="file-input file-input-bordered w-full {{ $errors->has('name') ? ' input-error' : '' }}" />
        @if ($errors->has('image'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('image') }}</span>
          </label>
        @endif
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Status</span>
        </label>
        <input id="status" type="checkbox" name="status" class="toggle" />
        @if ($errors->has('link'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('status') }}</span>
          </label>
        @endif
      </div>
      <x-form-action type="update" route="/admin/merchants" />
    </form>
  </dialog>

@endsection

@section('js')
  <script>
    function previewImageOnAdd() {
      const file = event.target.files[0];
      if (file.size > 3080000) {
        toastr.error("Your files to large, please resize!");
        setTimeout(() => {
          window.location.replace("/admin/merchants");
        }, 1500)
      } else {
        $('#merchantPreview').show();
        merchantPreview.src = URL.createObjectURL(event.target.files[0])
      }
    }

    function previewImageOnEdit() {
      const file = event.target.files[0];
      if (file.size > 3080000) {
        toastr.error("Your files to large, please resize!");
        setTimeout(() => {
          window.location.replace("/admin/merchants");
        }, 1500)
      } else {
        $('#merchantPreviewEdit').show();
        merchantPreviewEdit.src = URL.createObjectURL(event.target.files[0])
      }
    }

    function handleEdit(id) {
      modal_merchant_edit.showModal();
      $.ajax({
        type: "GET",
        url: "/admin/merchants/edit/" + id,
        success: function(response) {
          $("#merchant_id").val(response.merchant.id);
          $("#name").val(response.merchant.name);
          $("#description").val(response.merchant.description);
          response.merchant.status == 1 ? $("#status").prop("checked", true) : "";
          image ? $('#merchantPreviewEdit').attr('src', response.merchant.image || '') : null;
        }
      })
    }

    function handleDelete(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to delete this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          let _token = $('meta[name="csrf-token"]').attr('content');
          const url = window.location.href;
          $.ajax({
            type: "DELETE",
            url: "/admin/merchants/delete/" + id,
            data: {
              _token: _token,
              id: id
            },
            success: function(response) {
              if (response.status == 200) {
                Swal.fire(
                  'Deleted!',
                  'Your file has been deleted.',
                  'success'
                ).then(function() {
                  window.location = url;
                });
              }
            }
          });
        }
      })
    }

    function disableButton() {
      var add = document.getElementById('submitAdd');
      var edit = document.getElementById('submitEdit');
      add.disabled = true;
      edit.disabled = true;
      $('#loadingAdd').show();
      $('#loadingEdit').show();
    }
  </script>
@endsection
