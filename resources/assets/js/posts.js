const modalSpinner = '<div class="centered"><i class="fa fa-spinner fa-spin" style="font-size:24px"></' +
    'i></div>';

const postActions = {

  filterForm: (e, target) => {
    let join = $(target).data('url').indexOf('?') === -1 ? '?' : '&'
    window.location.href = $(target).data('url') + join + 'filter=' + $.trim($(target).val());
  },

  showEditForm: (e, target) => {
    $("#admin-post-edit-modal .modal-body").html(modalSpinner);
    $("#admin-post-edit-modal").modal('show');
    $.ajax({
      url: '/posts/' + $(target).data('id') + '/edit'
    }).done((result) => {
      $("#admin-post-edit-modal .modal-body").html(result);
    });
  },

  updateEntry: (e, target) => {
    e.preventDefault(e);
    var url = '/posts/' + $('#post_id').val();
    var data = $('.modal-body form').serialize();
    $("#admin-post-edit-modal .modal-body .form-errors").html('');
    var htmlSaved = $("#admin-post-edit-modal .modal-body").html();
    $("#admin-post-edit-modal .modal-body").html(modalSpinner);
    $.ajax({
      type: "POST",
      url: url,
      data: data,
      dataType: 'json',
      success: (result) => {
        $("#admin-post-edit-modal").modal('hide');
        window.location.reload();
      },
      error: (result) => {
        if (typeof result.responseJSON.message === 'string') {
          alert(result.responseJSON.message);
          $("#admin-post-edit-modal").modal('hide');
        } else {
          $("#admin-post-edit-modal .modal-body").html(htmlSaved);
          $.each(result.responseJSON.message, (i, v) => {
            $("#admin-post-edit-modal .modal-body .form-errors").append('<div class="alert alert-danger">' + v + '</div>');
          });
        }
      }
    });
  },

  deleteEntry: (e, target, isSoft = false) => {
    if (!confirm(isSoft
      ? 'Hide this entry?'
      : 'Delete entry? This will also delete all related comments...')) {
      return false;
    }
    var url = '/posts/' + $(target).data('id');
    var data = {
      '_token': window.Laravel.csrfToken
    };
    if (isSoft) {
      data.isSoft = 1;
    }
    $.ajax({
      type: "DELETE",
      url: url,
      data: data,
      dataType: 'json',
      success: (result) => {
        window.location.reload();
      },
      error: (result) => {
        alert(result.responseJSON.message);
      }
    });
  },

  approveEntry: (e, target, flag) => {
    if (!confirm(!flag
      ? 'Unapprove this entry?'
      : 'Approve this entry?')) {
      return false;
    }
    var url = '/posts/approve';
    var data = {
      'id': $(target).data('id'),
      'flag' : flag,
      '_method': 'PATCH',
      '_token': window.Laravel.csrfToken
    };
    $.ajax({
      type: "POST",
      url: url,
      data: data,
      dataType: 'json',
      success: (result) => {
        window.location.reload();
      },
      error: (result) => {
        alert(result.responseJSON.message);
      }
    });
  },

  unhideEntry: (e, target, fullDelete = 0) => {
    if (!confirm(!fullDelete
      ? 'Unhide this entry?'
      : 'Fully delete the entry?')) {
      return false;
    }
    var url = '/posts/unhide';
    var data = {
      'id': $(target).data('id'),
      '_method': 'PATCH',
      '_token': window.Laravel.csrfToken
    };
    if (fullDelete) {
      data.fullDelete = 1;
    }
    $.ajax({
      type: "POST",
      url: url,
      data: data,
      dataType: 'json',
      success: (result) => {
        window.location.reload();
      },
      error: (result) => {
        alert(result.responseJSON.message);
      }
    });
  }

};

$(document).on('click', '#btn-ok', (e) => {
  postActions.updateEntry(e, this);
});

$(document).on('click', '.admin-post-edit', function (e) {
  postActions.showEditForm(e, this);
});

$(document).on('click', '.admin-post-hide', function (e) {
  postActions.deleteEntry(e, this, true);
});

$(document).on('click', '.admin-post-delete', function (e) {
  postActions.deleteEntry(e, this);
});

$(document).on('click', '.admin-post-unapprove', function (e) {
  postActions.approveEntry(e, this, 0);
});

$(document).on('click', '.admin-post-approve', function (e) {
  postActions.approveEntry(e, this, 1);
});

$(document).on('click', '.admin-post-unhide', function (e) {
  postActions.unhideEntry(e, this);
});

$(document).on('click', '.admin-post-deletehidden', function (e) {
  postActions.unhideEntry(e, this, true);
});

$(document).on('keyup', '#search-filter', function (e) {
  if (e.which !== 13 || !$.trim($(this).val())) {
    return;
  }
  postActions.filterForm(e, this);
});
