@extends('layouts.app')
@section('title', 'Master Event')
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
          <form action="{{ route('admin.events', request()->query()) }}">
            <div class="flex my-2">
              <input type="hidden" name="sortColumn" value="{{ $sortColumn }}" />
              <input type="hidden" name="sortDirection" value="{{ $sortDirection }}" />
              <input type="text" name="q" placeholder="Search" class="py-2 px-2 text-md border border-gray-200 rounded-l focus:outline-none" value="{{ $searchParam }}" />
              <button type="submit" class="btn btn-primary rounded-l-none">
                <x-bi-search class="h-6 w-6" />
              </button>
            </div>
          </form>
          <button class="btn btn-md btn-primary" onclick="modal_event.showModal()">Add</button>
        </div>
        <div class="card bg-white rounded-lg">
          <div class="card-body p-0">
            <div class="overflow-x-auto">
              <table class="table">
                <thead>
                  <tr>
                    <th width="3%">
                      <x-column-header dataRoute="admin.events" column-name="id" :sort-column="$sortColumn" :sortDirection="$sortDirection">#</x-column-header>
                    </th>
                    <th>
                      <x-column-header dataRoute="admin.events" column-name="name" :sort-column="$sortColumn" :sortDirection="$sortDirection">Name</x-column-header>
                    </th>
                    <th>
                      <x-column-header dataRoute="admin.events" column-name="presenter_name" :sort-column="$sortColumn" :sortDirection="$sortDirection">Presenter Name</x-column-header>
                    </th>
                    <th>
                      <x-column-header dataRoute="admin.events" column-name="category" :sort-column="$sortColumn" :sortDirection="$sortDirection">Category</x-column-header>
                    </th>
                    <th>
                      <x-column-header dataRoute="admin.events" column-name="location" :sort-column="$sortColumn" :sortDirection="$sortDirection">Location</x-column-header>
                    </th>
                    <th width="100">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($events as $event)
                    <tr>
                      <td>{{ $event->id }}</td>
                      <td>
                        <div class="flex items-center space-x-3">
                          <div class="avatar">
                            <div class="mask mask-squircle w-9 h-9">
                              @if ($event->image != '')
                                <img src="{{ $event->image }}" alt="{{ $event->name }}">
                              @else
                                <img src="https://placehold.co/100x100" alt="blank" />
                              @endif
                            </div>
                          </div>
                          <div>
                            <div class="font-bold">{{ $event->name }}</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <a href="{{ $event->presenter_name }}" target="_blank">{{ $event->category }}</a>
                      </td>
                      <td>
                        <a href="{{ $event->category }}" target="_blank">{{ $event->category }}</a>
                      </td>
                      <td>
                        <a href="{{ $event->location }}" target="_blank">{{ $event->location }}</a>
                      </td>
                      <td>
                        <div class="flex items-center justify-end gap-2">
                          <button onClick="handleEdit(`{{ $event->id }}`)" class="btn btn-sm btn-square btn-ghost">
                            <x-bi-pencil-square class="w-4 h-4" />
                          </button>
                          <button onClick="handleDelete(`{{ $event->id }}`)" class="btn btn-sm btn-square btn-ghost">
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
        {{ $events->appends(['sortDirection' => request()->sortDirection, 'sortColumn' => request()->sortColumn, 'q' => request()->q])->onEachSide(5)->links() }}
      </div>
    </div>
  </section>

  <dialog id="modal_event" class="modal">
    <form class="modal-box w-11/12 max-w-5xl" action="{{ route('admin.events.store') }}" onsubmit="disableButton()" method="POST" enctype="multipart/form-data">
      @csrf
      <a href="{{ $url }}" class="btn btn-sm btn-circle absolute right-2 top-2">✕</a>
      <h3 class="font-semibold text-2xl pb-6 text-center">Add New Event</h3>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Name</span>
        </label>
        <input name="name" type="text" placeholder="Your event name" class="input input-bordered w-full {{ $errors->has('name') ? ' input-error' : '' }}" required />
        @if ($errors->has('name'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('name') }}</span>
          </label>
        @endif
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Category</span>
        </label>
        <input name="category" type="text" placeholder="Your event category" class="input input-bordered w-full {{ $errors->has('category') ? ' input-error' : '' }}" required />
        @if ($errors->has('category'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('category') }}</span>
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
        <img id="eventPreview" class="rounded-md mx-auto" hidden>
        <input name="image" id="image" type="file" accept="image/*" onchange="previewImageOnAdd()" class="file-input file-input-bordered w-full {{ $errors->has('image') ? ' input-error' : '' }}" required />
        @if ($errors->has('image'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('image') }}</span>
          </label>
        @endif
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Presenter Name</span>
        </label>
        <input name="presenter_name" type="text" placeholder="Your event presenter" class="input input-bordered w-full {{ $errors->has('presenter_name') ? ' input-error' : '' }}" required />
        @if ($errors->has('presenter_name'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('presenter_name') }}</span>
          </label>
        @endif
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Presenter Position</span>
        </label>
        <input name="presenter_position" type="text" placeholder="Your event presenter position" class="input input-bordered w-full {{ $errors->has('presenter_position') ? ' input-error' : '' }}" required />
        @if ($errors->has('presenter_position'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('presenter_position') }}</span>
          </label>
        @endif
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Presenter Image</span>
        </label>
        <img id="eventPreviewPresenter" class="rounded-md mx-auto" hidden>
        <input name="presenter_image" id="presenter_image" type="file" accept="image/*" onchange="previewImageOnAddPresenter()" class="file-input file-input-bordered w-full {{ $errors->has('presenter_image') ? ' input-error' : '' }}" required />
        @if ($errors->has('presenter_image'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('presenter_image') }}</span>
          </label>
        @endif
      </div>
      <div class="flex flex-row space-x-4">
        <div class="flex-auto">
          <div class="form-control w-full mt-2">
            <label class="label">
              <span class="label-text text-base-content undefined">Date</span>
            </label>
            <input name="date" type="date" class="input input-bordered w-full join-item {{ $errors->has('date') ? ' input-error' : '' }}" required />
            @if ($errors->has('date'))
              <label class="label">
                <span class="label-text-alt text-error">{{ $errors->first('date') }}</span>
              </label>
            @endif
          </div>
        </div>
        <div class="flex-auto">
          <div class="form-control w-full mt-2">
            <label class="label">
              <span class="label-text text-base-content undefined">Start Time</span>
            </label>
            <input name="start_time" type="time" class="input input-bordered w-full join-item {{ $errors->has('start_time') ? ' input-error' : '' }}" required />
            @if ($errors->has('start_time'))
              <label class="label">
                <span class="label-text-alt text-error">{{ $errors->first('start_time') }}</span>
              </label>
            @endif
          </div>
        </div>
        <div class="flex-auto">
          <div class="form-control w-full mt-2">
            <label class="label">
              <span class="label-text text-base-content undefined">End Time</span>
            </label>
            <input name="end_time" type="time" class="input input-bordered w-full join-item {{ $errors->has('end_time') ? ' input-error' : '' }}" required />
            @if ($errors->has('end_time'))
              <label class="label">
                <span class="label-text-alt text-error">{{ $errors->first('end_time') }}</span>
              </label>
            @endif
          </div>
        </div>
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Location</span>
        </label>
        <input name="location" type="text" placeholder="Your event location" class="input input-bordered w-full {{ $errors->has('location') ? ' input-error' : '' }}" required />
        @if ($errors->has('location'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('location') }}</span>
          </label>
        @endif
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Location Link</span>
        </label>
        <input name="location_link" type="text" placeholder="Your event location link" class="input input-bordered w-full {{ $errors->has('location_link') ? ' input-error' : '' }}" required />
        @if ($errors->has('location_link'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('location_link') }}</span>
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
      <x-form-action type="save" route="/admin/events" />
    </form>
  </dialog>

  <dialog id="modal_event_edit" class="modal">
    <form class="modal-box w-11/12 max-w-5xl" action="{{ route('admin.events.update') }}" onsubmit="disableButton()" method="POST" enctype="multipart/form-data">
      @csrf
      <a href="{{ $url }}" class="btn btn-sm btn-circle absolute right-2 top-2">✕</a>
      <h3 class="font-semibold text-2xl pb-6 text-center">Edit Event</h3>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Name</span>
        </label>
        <input id="name" name="name" type="text" placeholder="Your event name" class="input input-bordered w-full {{ $errors->has('name') ? ' input-error' : '' }}" required />
        <input type="hidden" name="event_id" id="event_id" />
        @if ($errors->has('name'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('name') }}</span>
          </label>
        @endif
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Category</span>
        </label>
        <input id="category" name="category" type="text" placeholder="Your event category" class="input input-bordered w-full {{ $errors->has('category') ? ' input-error' : '' }}" required />
        @if ($errors->has('category'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('category') }}</span>
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
        <img id="eventPreviewEdit" class="rounded-md mx-auto">
        <input name="image" id="image" type="file" accept="image/*" onchange="previewImageOnEdit()" class="file-input file-input-bordered w-full {{ $errors->has('name') ? ' input-error' : '' }}" />
        @if ($errors->has('image'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('image') }}</span>
          </label>
        @endif
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Presenter Name</span>
        </label>
        <input name="presenter_name" id="presenter_name" type="text" placeholder="Your event presenter" class="input input-bordered w-full {{ $errors->has('presenter_name') ? ' input-error' : '' }}" required />
        @if ($errors->has('presenter_name'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('presenter_name') }}</span>
          </label>
        @endif
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Presenter Position</span>
        </label>
        <input name="presenter_position" id="presenter_position" type="text" placeholder="Your event presenter position" class="input input-bordered w-full {{ $errors->has('presenter_position') ? ' input-error' : '' }}" required />
        @if ($errors->has('presenter_position'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('presenter_position') }}</span>
          </label>
        @endif
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Presenter Image</span>
        </label>
        <img id="eventPreviewEditPresenter" class="rounded-md mx-auto">
        <input name="presenter_image" id="presenter_image" type="file" accept="image/*" onchange="previewImageOnEditPresenter()" class="file-input file-input-bordered w-full {{ $errors->has('presenter_image') ? ' input-error' : '' }}" />
        @if ($errors->has('presenter_image'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('presenter_image') }}</span>
          </label>
        @endif
      </div>
      <div class="flex flex-row space-x-4">
        <div class="flex-auto">
          <div class="form-control w-full mt-2">
            <label class="label">
              <span class="label-text text-base-content undefined">Date</span>
            </label>
            <input name="date" id="date" type="date" class="input input-bordered w-full join-item {{ $errors->has('date') ? ' input-error' : '' }}" required />
            @if ($errors->has('date'))
              <label class="label">
                <span class="label-text-alt text-error">{{ $errors->first('date') }}</span>
              </label>
            @endif
          </div>
        </div>
        <div class="flex-auto">
          <div class="form-control w-full mt-2">
            <label class="label">
              <span class="label-text text-base-content undefined">Start Time</span>
            </label>
            <input name="start_time" id="start_time" type="time" class="input input-bordered w-full join-item {{ $errors->has('start_time') ? ' input-error' : '' }}" required />
            @if ($errors->has('start_time'))
              <label class="label">
                <span class="label-text-alt text-error">{{ $errors->first('start_time') }}</span>
              </label>
            @endif
          </div>
        </div>
        <div class="flex-auto">
          <div class="form-control w-full mt-2">
            <label class="label">
              <span class="label-text text-base-content undefined">End Time</span>
            </label>
            <input name="end_time" id="end_time" type="time" class="input input-bordered w-full join-item {{ $errors->has('end_time') ? ' input-error' : '' }}" required />
            @if ($errors->has('end_time'))
              <label class="label">
                <span class="label-text-alt text-error">{{ $errors->first('end_time') }}</span>
              </label>
            @endif
          </div>
        </div>
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Location</span>
        </label>
        <input name="location" id="location" type="text" placeholder="Your event location" class="input input-bordered w-full {{ $errors->has('location') ? ' input-error' : '' }}" required />
        @if ($errors->has('location'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('location') }}</span>
          </label>
        @endif
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Location Link</span>
        </label>
        <input name="location_link" id="location_link" type="text" placeholder="Your event location link" class="input input-bordered w-full {{ $errors->has('location_link') ? ' input-error' : '' }}" required />
        @if ($errors->has('location_link'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('location_link') }}</span>
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
      <x-form-action type="update" route="/admin/events" />
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
          window.location.replace("/admin/events");
        }, 1500)
      } else {
        $('#eventPreview').show();
        eventPreview.src = URL.createObjectURL(event.target.files[0])
      }
    }

    function previewImageOnAddPresenter() {
      const file = event.target.files[0];
      if (file.size > 3080000) {
        toastr.error("Your files to large, please resize!");
        setTimeout(() => {
          window.location.replace("/admin/events");
        }, 1500)
      } else {
        $('#eventPreviewPresenter').show();
        eventPreviewPresenter.src = URL.createObjectURL(event.target.files[0])
      }
    }

    function previewImageOnEdit() {
      const file = event.target.files[0];
      if (file.size > 3080000) {
        toastr.error("Your files to large, please resize!");
        setTimeout(() => {
          window.location.replace("/admin/events");
        }, 1500)
      } else {
        $('#eventPreviewEdit').show();
        eventPreviewEdit.src = URL.createObjectURL(event.target.files[0])
      }
    }

    function previewImageOnEditPresenter() {
      const file = event.target.files[0];
      if (file.size > 3080000) {
        toastr.error("Your files to large, please resize!");
        setTimeout(() => {
          window.location.replace("/admin/events");
        }, 1500)
      } else {
        $('#eventPreviewEditPresenter').show();
        eventPreviewEditPresenter.src = URL.createObjectURL(event.target.files[0])
      }
    }

    function handleEdit(id) {
      modal_event_edit.showModal();
      $.ajax({
        type: "GET",
        url: "/admin/events/edit/" + id,
        success: function(response) {
          $("#event_id").val(response.event.id);
          $("#name").val(response.event.name);
          $("#category").val(response.event.category);
          $("#description").val(response.event.description);
          $("#presenter_name").val(response.event.presenter_name);
          $("#presenter_position").val(response.event.presenter_position);
          $("#date").val(response.event.date);
          $("#start_time").val(response.event.start_time);
          $("#end_time").val(response.event.end_time);
          $("#location").val(response.event.location);
          $("#location_link").val(response.event.location_link);
          response.event.status == 1 ? $("#status").prop("checked", true) : "";
          image ? $('#eventPreviewEdit').attr('src', response.event.image || '') : null;
          presenter_image ? $('#eventPreviewEditPresenter').attr('src', response.event.presenter_image || '') : null;
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
            url: "/admin/events/delete/" + id,
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
