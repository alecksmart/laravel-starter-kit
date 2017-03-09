@extends('layouts.app')

@section('content')

  <div class="container" id="manage-vue">
    <div class="row">
      <div class="col-lg-12 margin-tb">
        <div class="pull-left">
          <h2>Manage Comments</h2>
        </div>
      </div>
    </div>

  <div class="help-block"></div>

  <!-- New Item Controls  -->

  <!--div class="pull-right">
    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#create-item"><span class="glyphicon glyphicon-new-window"></span> New </button>
  </div-->

  <!-- Item Listing -->

  <div class="card">
    <div class="card-header"><i class="fa fa-align-justify"></i>
      List of Comments
    </div>
    <div class="card-block">
      <table class="table">
        <thead>
          <tr>
            <th>Comment</th>
            <th width="90px">Date</th>
            <th width="80px">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in items">
            <td>
                <p>
                    <strong>@{{ item.user.name }}</strong> in <a href="/post/@{{ item.post.post_slug }}" target="_blank">@{{ item.post.post_title }}</a>:
                </p>
                <p>
                    <small>@{{ nl2br(item.comment_body) }}</small>
                </p>
                <p v-if='item.deleted_at'>
                    <span v-if='item.deleted_at' class="label label-warning">Hidden</span>
                </p>
            </td>
            <td>@{{ item.created_at }}</td>
            <td>
              <p class="text-center">
                <!--button class="btn btn-primary btn-xs" data-toggle="tooltip" title="Edit" @click.prevent="editItem(item)"><span class="glyphicon glyphicon-pencil"></span></button-->
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
              <label for="comment_body">Comment Body:</label>
              <textarea name="comment_body" class="form-control" v-model="newItem.comment_body"></textarea>
              <span v-if="formErrors['comment_body']" class="error text-danger">@{{ formErrors['comment_body'] }}</span>
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
              <label for="comment_body">Post Body:</label>
              <textarea name="comment_body" class="form-control" v-model="fillItem.comment_body"></textarea>
              <span v-if="formErrorsUpdate['comment_body']" class="error text-danger">@{{ formErrorsUpdate['comment_body'] }}</span>
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
  window._managers.commentsManager
    && window._managers.commentsManager();
});
@endpush

@endsection
