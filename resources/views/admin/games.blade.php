@extends('layouts.app')
@section('title', 'Master Game')
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
          <form action="{{ route('admin.games', request()->query()) }}">
            <div class="flex my-2">
              <input type="hidden" name="sortColumn" value="{{ $sortColumn }}" />
              <input type="hidden" name="sortDirection" value="{{ $sortDirection }}" />
              <input type="text" name="q" placeholder="Search" class="py-2 px-2 text-md border border-gray-200 rounded-l focus:outline-none" value="{{ $searchParam }}" />
              <button type="submit" class="btn btn-primary rounded-l-none">
                <x-bi-search class="h-6 w-6" />
              </button>
            </div>
          </form>
          <button class="btn btn-md btn-primary" onclick="modal_game.showModal()">Add</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-10">
          @foreach ($games as $game)
            @php
              $img = json_decode($game->image);
            @endphp
            <div class="card bg-base-100 shadow-xl">
              <div class="flex">
                <figure class="rounded-l-xl w-[130px] max-w-[130px]">
                  @if ($game->image != '')
                    <img src="{{ $game->image }}" alt="{{ $game->name }}">
                  @else
                    <img src="https://placehold.co/200x280" alt="blank" />
                  @endif
                </figure>
                <div class="card-body p-4">
                  <div class="card-actions justify-end">
                    <a href="{{ route('admin.missions', $game->id) }}" class="btn btn-sm btn-square btn-ghost">
                      <x-bi-list-task class="w-4 h-4" />
                    </a>
                    <button onClick="handleEdit('{{ $game->id }}')" class="btn btn-sm btn-square btn-ghost">
                      <x-bi-pencil class="w-4 h-4" />
                    </button>
                    <button onClick="handleDelete('{{ $game->id }}')" class="btn btn-sm btn-square btn-ghost">
                      <x-bi-trash3 class="w-4 h-4" />
                    </button>
                  </div>
                  <h2 class="card-title">{{ $game->name }}</h2>
                  <span class="text-gray-500 text-base">{{ $game->slug }} | {{ $game->code }}</span>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <dialog id="modal_game" class="modal">
    <form class="modal-box" action="{{ route('admin.games.store') }}" onsubmit="disableButton()" method="POST" enctype="multipart/form-data">
      @csrf
      <a href="{{ $url }}" class="btn btn-sm btn-circle absolute right-2 top-2">✕</a>
      <h3 class="font-semibold text-2xl pb-6 text-center">Add New Game</h3>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Name</span>
        </label>
        <input name="name" type="text" placeholder="Your game name" class="input input-bordered w-full {{ $errors->has('name') ? ' input-error' : '' }}" required />
        @if ($errors->has('name'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('name') }}</span>
          </label>
        @endif
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Code</span>
        </label>
        <input name="code" type="text" placeholder="Your game code" class="input input-bordered w-full {{ $errors->has('code') ? ' input-error' : '' }}" required />
        @if ($errors->has('code'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('code') }}</span>
          </label>
        @endif
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">PIN</span>
        </label>
        <input name="pin" type="number" placeholder="Your game pin number" class="input input-bordered w-full [&::-webkit-inner-spin-button]:appearance-none {{ $errors->has('pin') ? ' input-error' : '' }}" required />
        @if ($errors->has('pin'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('pin') }}</span>
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
        <img id="gamePreview" class="rounded-md mx-auto" hidden>
        <input name="image" id="image" type="file" accept="image/*" onchange="previewImageOnAdd()" class="file-input file-input-bordered w-full {{ $errors->has('name') ? ' input-error' : '' }}" />
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
      <x-form-action type="save" route="/admin/games" />
    </form>
  </dialog>

  <dialog id="modal_game_edit" class="modal">
    <form class="modal-box" action="{{ route('admin.games.update') }}" onsubmit="disableButton()" method="POST" enctype="multipart/form-data">
      @csrf
      <a href="{{ $url }}" class="btn btn-sm btn-circle absolute right-2 top-2">✕</a>
      <h3 class="font-semibold text-2xl pb-6 text-center">Edit Game</h3>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Name</span>
        </label>
        <input id="name" name="name" type="text" placeholder="Your game name" class="input input-bordered w-full {{ $errors->has('name') ? ' input-error' : '' }}" required />
        <input type="hidden" name="game_id" id="game_id" />
        @if ($errors->has('name'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('name') }}</span>
          </label>
        @endif
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">Code</span>
        </label>
        <input id="code" name="code" type="text" placeholder="Your game code" class="input input-bordered w-full [&::-webkit-inner-spin-button]:appearance-none {{ $errors->has('code') ? ' input-error' : '' }}" required />
        @if ($errors->has('code'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('code') }}</span>
          </label>
        @endif
      </div>
      <div class="form-control w-full mt-2">
        <label class="label">
          <span class="label-text text-base-content undefined">PIN</span>
        </label>
        <input id="pin" name="pin" type="text" placeholder="Your game pin" class="input input-bordered w-full {{ $errors->has('pin') ? ' input-error' : '' }}" required />
        @if ($errors->has('pin'))
          <label class="label">
            <span class="label-text-alt text-error">{{ $errors->first('pin') }}</span>
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
        <img id="gamePreviewEdit" class="rounded-md mx-auto">
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
      <x-form-action type="update" route="/admin/games" />
    </form>
  </dialog>

  <section id="listMission" hidden>
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="flex justify-between items-center pb-6">
          <form action="{{ route('admin.games', request()->query()) }}">
            <div class="flex my-2">
              <input type="hidden" name="sortColumn" value="{{ $sortColumn }}" />
              <input type="hidden" name="sortDirection" value="{{ $sortDirection }}" />
              <input type="text" name="q" placeholder="Search" class="py-2 px-2 text-md border border-gray-200 rounded-l focus:outline-none" value="{{ $searchParam }}" />
              <button type="submit" class="btn btn-primary rounded-l-none">
                <x-bi-search class="h-6 w-6" />
              </button>
            </div>
          </form>
          <button class="btn btn-md btn-primary" onclick="modal_game.showModal()">Add</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-10">
          @foreach ($games as $game)
            @php
              $img = json_decode($game->image);
            @endphp
            <div class="card bg-base-100 shadow-xl">
              <div class="flex">
                <figure class="rounded-l-xl w-[130px] max-w-[130px]">
                  @if ($game->image != '')
                    <img src="{{ $game->image }}" alt="{{ $game->name }}">
                  @else
                    <img src="https://placehold.co/200x280" alt="blank" />
                  @endif
                </figure>
                <div class="card-body p-4">
                  <div class="card-actions justify-end">
                    <button onClick="photogame('{{ $game->id }}')" class="btn btn-sm btn-square btn-ghost">
                      <x-bi-list-task class="w-4 h-4" />
                    </button>
                    <button onClick="handleEdit('{{ $game->id }}')" class="btn btn-sm btn-square btn-ghost">
                      <x-bi-pencil class="w-4 h-4" />
                    </button>
                    <button onClick="handleDelete('{{ $game->id }}')" class="btn btn-sm btn-square btn-ghost">
                      <x-bi-trash3 class="w-4 h-4" />
                    </button>
                  </div>
                  <h2 class="card-title">{{ $game->name }}</h2>
                  <span class="text-gray-500 text-base">{{ $game->slug }} | {{ $game->code }}</span>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>

@endsection

@section('js')
  <script>
    function previewImageOnAdd() {
      const file = event.target.files[0];
      if (file.size > 3080000) {
        toastr.error("Your files to large, please resize!");
        setTimeout(() => {
          window.location.replace("/admin/games");
        }, 1500)
      } else {
        $('#gamePreview').show();
        gamePreview.src = URL.createObjectURL(event.target.files[0])
      }
    }

    function previewImageOnEdit() {
      const file = event.target.files[0];
      if (file.size > 3080000) {
        toastr.error("Your files to large, please resize!");
        setTimeout(() => {
          window.location.replace("/admin/games");
        }, 1500)
      } else {
        $('#gamePreviewEdit').show();
        gamePreviewEdit.src = URL.createObjectURL(event.target.files[0])
      }
    }

    function handleEdit(id) {
      modal_game_edit.showModal();
      $.ajax({
        type: "GET",
        url: "/admin/games/edit/" + id,
        success: function(response) {
          $("#game_id").val(response.game.id);
          $("#name").val(response.game.name);
          $("#code").val(response.game.code);
          $("#pin").val(response.game.pin);
          $("#description").val(response.game.description);
          response.game.status == 1 ? $("#status").prop("checked", true) : "";
          image ? $('#gamePreviewEdit').attr('src', response.game.image || '') : null;
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
            url: "/admin/games/delete/" + id,
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

    $(document).ready(function() {
      $("input[type=number]").on("focus", function() {
        $(this).on("keydown", function(event) {
          if (event.keyCode === 38 || event.keyCode === 40) {
            event.preventDefault();
          }
        });
      });
    });
  </script>
@endsection
