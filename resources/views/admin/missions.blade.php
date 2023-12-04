@extends('layouts.app')
@section('title', 'Master Mission')
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
        <div class="flex justify-end items-center pb-6">
          <button class="btn btn-md btn-primary" onclick="addMissions()">Add</button>
        </div>
        <div class="card bg-white rounded-lg">
          <div class="card-body p-0">
            <div class="overflow-x-auto">
              <table class="table">
                <thead>
                  <tr>
                    <th width="3%">
                      #
                    </th>
                    <th>
                      Name
                    </th>
                    <th>
                      Description
                    </th>
                    <th>
                      Merchant
                    </th>
                    <th width="100">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($missions as $mission)
                    <tr>
                      <td>{{ $mission->id }}</td>
                      <td>
                        <div class="flex items-center space-x-3">
                          <div class="avatar">
                            <div class="mask mask-squircle w-9 h-9">
                              @if ($mission->image != '')
                                <img src="{{ $mission->image }}" alt="{{ $mission->name }}">
                              @else
                                <img src="https://placehold.co/100x100" alt="blank" />
                              @endif
                            </div>
                          </div>
                          <div>
                            <div class="font-bold">{{ $mission->name }}</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        {!! Str::limit($mission->description, 100, '...') !!}
                      </td>
                      <td>
                        {{ $mission->id_merchant }}
                      </td>
                      <td>
                        <div class="flex items-center justify-end gap-2">
                          <button onClick="handleEdit(`{{ $mission->id }}`)" class="btn btn-sm btn-square btn-ghost">
                            <x-bi-pencil-square class="w-4 h-4" />
                          </button>
                          <button onClick="handleDelete(`{{ $mission->id }}`)" class="btn btn-sm btn-square btn-ghost">
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
      </div>
    </div>
  </section>

  <section id="add" hidden>
    <div class="card bg-white">
      <form class="card-body p-4" action="{{ route('admin.missions.store') }}" onsubmit="disableButton()" method="POST" enctype="multipart/form-data">
        @csrf
        <h3 class="font-semibold text-2xl pb-6 text-center">Add New Mission For {{ $game->name }}</h3>
        <div class="form-control w-full mt-2">
          <label class="label">
            <span class="label-text text-base-content undefined">Merchant</span>
          </label>
          <input type="hidden" name="id_game" value="{{ $game->id }}">
          <select name="id_merchant" class="input input-bordered w-full {{ $errors->has('id_merchant') ? ' input-error' : '' }}" required>
            <option disabled selected>Pilih Merchant</option>
            @foreach ($merchants as $merchant)
              <option value="{{ $merchant->id }}">{{ $merchant->name }}</option>
            @endforeach
          </select>
          @if ($errors->has('id_game'))
            <label class="label">
              <span class="label-text-alt text-error">{{ $errors->first('name') }}</span>
            </label>
          @endif
        </div>
        <div class="form-control w-full mt-2">
          <label class="label">
            <span class="label-text text-base-content undefined">Name</span>
          </label>
          <input name="name" type="text" placeholder="Your mission name" class="input input-bordered w-full {{ $errors->has('name') ? ' input-error' : '' }}" />
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
          <img id="missionPreview" class="rounded-md mx-auto" hidden>
          <input name="image" id="image" type="file" accept="image/*" onchange="previewImageOnAdd()" class="file-input file-input-bordered w-full {{ $errors->has('name') ? ' input-error' : '' }}" required />
          @if ($errors->has('image'))
            <label class="label">
              <span class="label-text-alt text-error">{{ $errors->first('image') }}</span>
            </label>
          @endif
        </div>
        <x-form-action type="save" route="{{ $url }}" />
      </form>
    </div>
  </section>

  <section id="edit" hidden>
    <div class="card bg-white">
      <form class="card-body p-4" action="{{ route('admin.missions.update') }}" onsubmit="disableButton()" method="POST" enctype="multipart/form-data">
        @csrf
        <h3 class="font-semibold text-2xl pb-6 text-center">Edit Mission For {{ $game->name }}</h3>
        <div class="form-control w-full mt-2">
          <label class="label">
            <span class="label-text text-base-content undefined">Merchant</span>
          </label>
          <input type="hidden" name="id_mission" id="id_mission">
          <select name="id_merchant" id="id_merchant" class="input input-bordered w-full {{ $errors->has('id_merchant') ? ' input-error' : '' }}" required>
            <option disabled>Pilih Merchant</option>
            @foreach ($merchantsEdit as $merchantEdit)
              <option value="{{ $merchantEdit->id }}">{{ $merchantEdit->name }}</option>
            @endforeach
          </select>
          @if ($errors->has('id_game'))
            <label class="label">
              <span class="label-text-alt text-error">{{ $errors->first('name') }}</span>
            </label>
          @endif
        </div>
        <div class="form-control w-full mt-2">
          <label class="label">
            <span class="label-text text-base-content undefined">Name</span>
          </label>
          <input id="name" name="name" type="text" placeholder="Your mission name" class="input input-bordered w-full {{ $errors->has('name') ? ' input-error' : '' }}" />
          <input type="hidden" name="mission_id" id="mission_id" />
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
          <textarea name="description" id="descriptionEdit" rows="3" class="textarea textarea-bordered w-full {{ $errors->has('description') ? ' input-error' : '' }}" required></textarea>
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
          <img id="missionPreviewEdit" class="rounded-md mx-auto">
          <input name="image" id="image" type="file" accept="image/*" onchange="previewImageOnEdit()" class="file-input file-input-bordered w-full {{ $errors->has('name') ? ' input-error' : '' }}" />
          @if ($errors->has('image'))
            <label class="label">
              <span class="label-text-alt text-error">{{ $errors->first('image') }}</span>
            </label>
          @endif
        </div>
        <x-form-action type="update" route="{{ $url }}" />
      </form>
    </div>
  </section>

@endsection
@section('js')
  <script>
    CKEDITOR.replace('description');
    CKEDITOR.replace('descriptionEdit');

    function addMissions() {
      $("#list").hide();
      $("#add").show();
    }

    function previewImageOnAdd() {
      const file = event.target.files[0];
      const url = window.location.href;
      if (file.size > 3080000) {
        toastr.error("Your files to large, please resize!");
        setTimeout(() => {
          window.location.replace(url);
        }, 1500)
      } else {
        $('#missionPreview').show();
        missionPreview.src = URL.createObjectURL(event.target.files[0])
      }
    }

    function previewImageOnEdit() {
      const file = event.target.files[0];
      const url = window.location.href;
      if (file.size > 3080000) {
        toastr.error("Your files to large, please resize!");
        setTimeout(() => {
          window.location.replace(url);
        }, 1500)
      } else {
        $('#missionPreviewEdit').show();
        missionPreviewEdit.src = URL.createObjectURL(event.target.files[0])
      }
    }

    function handleEdit(id) {
      $("#list").hide();
      $("#edit").show();
      $.ajax({
        type: "GET",
        url: "/admin/games/missions/edit/" + id,
        success: function(response) {
          const mission = response?.mission || {};
          $("#mission_id").val(mission.id);
          $("#name").val(mission.name);
          $('#missionPreviewEdit').attr('src', mission.image || '');
          $("#id_merchant").val(mission.id_merchant);
          CKEDITOR.instances['descriptionEdit'].setData(mission.description);
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
            url: "/admin/games/missions/delete/" + id,
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
