@extends('layouts.app')

@section('content')

  <div class="container" id="manage-vue">
    <div class="row">
      <div class="col-lg-12 margin-tb">
        <div class="pull-left">
          <h2>Manage Users</h2>
        </div>
      </div>
    </div>

  <div class="help-block"></div>

  <!-- New Item Controls  -->

  <div class="pull-right">
    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#create-item"><span class="glyphicon glyphicon-new-window"></span> New </button>
  </div>

  <!-- Item Listing -->

  <div class="card">
    <div class="card-header"><i class="fa fa-align-justify"></i>
      List of Users
    </div>
    <div class="card-block">
      <table class="table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th width="160px">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in items">
            <td>@{{ item.name }}</td>
            <td>@{{ item.email }}</td>
            <td>@{{ item.role }}</td>
            <td>
              <p class="text-center">
                <button class="btn btn-primary btn-xs" data-title="Edit" @click.prevent="editItem(item)"><span class="glyphicon glyphicon-pencil"></span></button>
                <button class="btn btn-danger btn-xs" data-title="Delete" @click.prevent="deleteItem(item)"><span class="glyphicon glyphicon-trash"></span></button>
              </p>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <nav>
        <ul class="pagination">
          <li v-if="pagination.current_page > 1">
            <a href="#" aria-label="Previous" @click.prevent="changePage(pagination.current_page - 1)"> <span aria-hidden="true">«</span> </a>
          </li>
          <li v-for="page in pagesNumber" v-bind:class="[ page == isActived ? 'active' : '']"> <a href="#" @click.prevent="changePage(page)">@{{ page }}</a>
          </li>
          <li v-if="pagination.current_page < pagination.last_page">
            <a href="#" aria-label="Next" @click.prevent="changePage(pagination.current_page + 1)"> <span aria-hidden="true">»</span> </a>
          </li>
        </ul>
      </nav>

    </div>
  </div>

  <!-- Create Item Modal -->
  <div class="modal fade" id="create-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title" id="myModalLabel">Create New</h4> </div>
        <div class="modal-body">
          <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createItem">

            <div class="form-group">
              <label for="name">Name:</label>
              <input type="text" name="name" class="form-control" v-model="newItem.name" /> <span v-if="formErrors['name']" class="error text-danger">@{{ formErrors['name'] }}</span>
            </div>

            <div class="form-group">
              <label for="email">Email:</label>
              <input type="text" name="email" class="form-control" v-model="newItem.email" /> <span v-if="formErrors['email']" class="error text-danger">@{{ formErrors['email'] }}</span>
            </div>

            <div class="form-group">
              <label for="password">Password:</label>
              <input type="text" name="password" class="form-control" v-model="newItem.password" /> <span v-if="formErrors['password']" class="error text-danger">@{{ formErrors['password'] }}</span>
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-success">Submit</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Item Modal -->
  <div class="modal fade" id="edit-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title" id="myModalLabel">Edit</h4> </div>

        <div class="modal-body">
          <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateItem(fillItem.id)">

            <p v-if="formErrorsUpdate['_common']">
              <span class="error text-danger">@{{ formErrorsUpdate['_common'] }}</span>
            </p>

            <div class="form-group">
              <label for="name">Name:</label>
              <input type="text" name="name" class="form-control" v-model="fillItem.name" /> <span v-if="formErrorsUpdate['name']" class="error text-danger">@{{ formErrorsUpdate['name'] }}</span>
            </div>

            <div class="form-group">
              <label for="email">Email:</label>
              <input type="text" name="email" class="form-control" v-model="fillItem.email" /> <span v-if="formErrorsUpdate['email']" class="error text-danger">@{{ formErrorsUpdate['email'] }}</span>
            </div>

            <div class="form-group">
              <label for="password">Password (leave blank for no change):</label>
              <input type="text" name="password" class="form-control" v-model="fillItem.password" /> <span v-if="formErrorsUpdate['password']" class="error text-danger">@{{ formErrorsUpdate['password'] }}</span>
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-success">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
$(document).ready(function(){
  window._managers.usersManager
    && window._managers.usersManager();
});
@endpush

@endsection
