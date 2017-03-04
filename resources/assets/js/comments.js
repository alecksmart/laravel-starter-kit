const modalSpinner = '<div class="centered"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></div>';

const commentActions = {

  filterForm: (e, target) =>
  {
    let join = $(target).data('url').indexOf('?') === -1 ? '?' : '&'
    window.location.href = $(target).data('url') + join + 'filter=' + $.trim($(target).val());
  },

  showEditForm: (e, target) =>
  {
    $("#admin-comment-edit-modal .modal-body").html(modalSpinner);
    $("#admin-comment-edit-modal").modal('show');
    $.ajax(
    {
      url: '/comments/' + $(target).data('id') + '/edit'
    }).done((result) =>
    {
      $("#admin-comment-edit-modal .modal-body").html(result);
    });
  },

  updateEntry: (e, target) =>
  {
    e.preventDefault(e);
    var url = '/comments/' + $('#comment_id').val();
    var data = $('.modal-body form').serialize();
    $("#admin-comment-edit-modal .modal-body .form-errors").html('');
    var htmlSaved = $("#admin-comment-edit-modal .modal-body").html();
    $("#admin-comment-edit-modal .modal-body").html(modalSpinner);
    $.ajax(
    {
      type: "POST",
      url: url,
      data: data,
      dataType: 'json',
      success: (result) =>
      {
        $("#admin-comment-edit-modal").modal('hide');
        window.location.reload();
      },
      error: (result) =>
      {
        if (typeof result.responseJSON.message === 'string')
        {
          alert(result.responseJSON.message);
          $("#admin-comment-edit-modal").modal('hide');
        }
        else
        {
          $("#admin-comment-edit-modal .modal-body").html(htmlSaved);
          $.each(result.responseJSON.message, (i, v) =>
          {
            $("#admin-comment-edit-modal .modal-body .form-errors").append('<div class="alert alert-danger">'
              + v + '</div>');
          });
        }
      }
    });
  },

  deleteEntry: (e, target, isSoft = false) =>
  {
    if (!confirm(isSoft ?
        'Hide this entry?' :
        'Delete entry? This will also delete all related comments...'))
    {
      return false;
    }
    var url = '/comments/' + $(target).data('id');
    var data = {
      '_token': window.Laravel.csrfToken
    };
    if (isSoft)
    {
      data.isSoft = 1;
    }
    $.ajax(
    {
      type: "DELETE",
      url: url,
      data: data,
      dataType: 'json',
      success: (result) =>
      {
        window.location.reload();
      },
      error: (result) =>
      {
        alert(result.responseJSON.message);
      }
    });
  },

  unhideEntry: (e, target, fullDelete = 0) =>
  {
    if (!confirm(!fullDelete ?
        'Unhide this entry?' :
        'Fully delete the entry?'))
    {
      return false;
    }
    var url = '/comments/unhide';
    var data = {
      'id': $(target).data('id'),
      '_method': 'PATCH',
      '_token': window.Laravel.csrfToken
    };
    if (fullDelete)
    {
      data.fullDelete = 1;
    }
    $.ajax(
    {
      type: "POST",
      url: url,
      data: data,
      dataType: 'json',
      success: (result) =>
      {
        window.location.reload();
      },
      error: (result) =>
      {
        alert(result.responseJSON.message);
      }
    });
  }

};

$(document).on('click', '#btn-ok', (e) =>
{
  commentActions.updateEntry(e, this);
});

$(document).on('click', '.admin-comment-edit', function (e)
{
  commentActions.showEditForm(e, this);
});

$(document).on('click', '.admin-comment-hide', function (e)
{
  commentActions.deleteEntry(e, this, true);
});

$(document).on('click', '.admin-comment-delete', function (e)
{
  commentActions.deleteEntry(e, this);
});

$(document).on('click', '.admin-comment-unhide', function (e)
{
  commentActions.unhideEntry(e, this);
});

$(document).on('click', '.admin-comment-deletehidden', function (e)
{
  commentActions.unhideEntry(e, this, true);
});

$(document).on('keyup', '#search-filter', function (e)
{
  if (e.which !== 13 || !$.trim($(this).val()))
  {
    return;
  }
  commentActions.filterForm(e, this);
});