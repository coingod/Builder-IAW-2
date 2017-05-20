@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card white-grey">
                <div class="card-content">
                    <span class="card-title">Usuarios de la aplicacion</span>

                      <table>
                        <thead>
                          <tr>
                              <th>user_id</th>
                              <th>user_name</th>
                              <th>user_email</th>
                              <th>Admin?</th>
                          </tr>
                        </thead>

                        <tbody>
                        @foreach ($users as $user)
                          <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>@if ($user->hasRole('admin')) Si @else No @endif</td>
                          </tr>
                        @endforeach
                        </tbody>
                      </table>

                </div>
                <div class="card-action">
                  <a href="#">This is a link</a>
                  <a href="#">This is a link</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
