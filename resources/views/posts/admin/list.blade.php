@extends('layouts.app')

@section('content')

  <div class="container" id="manage-vue">
    <div class="row">
      <div class="col-lg-12 margin-tb">
        <div class="pull-left">
          <h2>Manage Posts</h2>
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
      List of Posts
    </div>
    <div class="card-block">
      <table class="table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Date</th>
            <th width="160px">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in items">
            <td>
                <strong>@{{ item.post_title }}</strong>
                <p v-if='item.deleted_at || !item.is_approved'>
                    <span v-if='item.deleted_at' class="label label-warning">Hidden</span>
                    <span v-if='!item.is_approved' class="label label-warning">Not approved</span>
                </p>
            </td>
            <td>@{{ item.created_at }}</td>
            <td>
              <p class="text-center">
                <button class="btn btn-primary btn-xs" data-toggle="tooltip" title="Edit" @click.prevent="editItem(item)"><span class="glyphicon glyphicon-pencil"></span></button>
                <button class="btn btn-danger btn-xs" data-toggle="tooltip" title="Hide / Unhide" @click.prevent="deleteItem(item)"><span class="glyphicon glyphicon-trash"></span></button>
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

            <!--p v-if="formErrorsUpdate['_common']">
              <span class="error text-danger">@{{ formErrorsUpdate['_common'] }}</span>
            </p-->

            <div class="form-group">
              <label for="post_title">Title:</label>
              <input type="text" name="post_title" class="form-control" v-model="newItem.post_title" /> <span v-if="formErrors['post_title']" class="error text-danger">@{{ formErrors['post_title'] }}</span>
            </div>

            <div class="form-group">
              <label for="post_body">Post Body:</label>
              <textarea name="post_body" class="form-control" v-model="newItem.post_body"></textarea>
              <span v-if="formErrors['post_body']" class="error text-danger">@{{ formErrors['post_body'] }}</span>
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

            <!--p v-if="formErrorsUpdate['_common']">
              <span class="error text-danger">@{{ formErrorsUpdate['_common'] }}</span>
            </p-->

            <div class="form-group">
              <label for="post_title">Title:</label>
              <input type="text" name="post_title" class="form-control" v-model="fillItem.post_title" /> <span v-if="formErrorsUpdate['post_title']" class="error text-danger">@{{ formErrorsUpdate['post_title'] }}</span>
            </div>

            <div class="form-group">
              <label for="post_body">Post Body:</label>
              <textarea name="post_body" class="form-control" v-model="fillItem.post_body"></textarea>
              <span v-if="formErrorsUpdate['post_body']" class="error text-danger">@{{ formErrorsUpdate['post_body'] }}</span>
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
  window._managers.postsManager
    && window._managers.postsManager();
});
@endpush

@endsection
