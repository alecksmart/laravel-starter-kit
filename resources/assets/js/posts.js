const toastr = require('toastr');

window._managers.postsManager = () => {
  new Vue({
    el: '#manage-vue',
    data: {
      items: [],
      pagination: {
        total: 0,
        per_page: 2,
        from: 1,
        to: 0,
        current_page: 1
      },
      offset: 4,
      formErrors: {},
      formErrorsUpdate: {},
      newItem: {
        'post_title': '',
        'post_body': ''
      },
      fillItem: {}
    },
    computed: {
      isActived: function () {
        return this.pagination.current_page;
      },
      pagesNumber: function () {
        if (!this.pagination.to) {
          return [];
        }
        var from = this.pagination.current_page - this.offset;
        if (from < 1) {
          from = 1;
        }
        var to = from + (this.offset * 2);
        if (to >= this.pagination.last_page) {
          to = this.pagination.last_page;
        }
        var pagesArray = [];
        while (from <= to) {
          pagesArray.push(from);
          from++;
        }
        return pagesArray;
      }
    },
    ready: function () {
      this.getVueItems(this.pagination.current_page);
    },
    methods: {
      getVueItems: function (page) {
        this.$http.get('/manage/posts?page=' + page).then((response) => {
          this.$set('items', response.data.data.data);
          this.$set('pagination', response.data.pagination);
        });
      },
      createItem: function () {
        var input = this.newItem;
        this.formErrors = {};
        this.$http.post('/manage/posts', input).then((response) => {
          this.changePage(this.pagination.current_page);
          this.newItem = {
            'post_title': '',
            'post_body': ''
          };
          $("#create-item").modal('hide');
          toastr.success('Item Created Successfully.', 'Success Alert', {
            timeOut: 5000
          });
        }, (response) => {
          this.formErrors = response.data;
          response.data['_common'] && toastr.error(response.data['_common'].join('<br>'), 'Error', {
            timeOut: 5000
          });
        });
      },
      deleteItem: function (item) {
        if (!confirm('Are you sure?')) {
          return false;
        }
        this.$http.delete('/manage/posts/' + item.id).then((response) => {
          this.changePage(this.pagination.current_page);
          toastr.success('Operation Successful.', 'Success Alert', {
            timeOut: 5000
          });
        }, (response) => {
          response.data['_common'] && toastr.error(response.data['_common'].join('<br>'), 'Error', {
            timeOut: 5000
          });
        });
      },
      editItem: function (item) {
        this.fillItem = {};
        this.formErrorsUpdate = {};
        this.fillItem.id = item.id;
        this.fillItem.post_title = item.post_title;
        this.fillItem.post_body = item.post_body;
        $("#edit-item").modal('show');
      },
      updateItem: function (id) {
        var input = this.fillItem;
        this.$http.put('/manage/posts/' + id, input).then((response) => {
          this.changePage(this.pagination.current_page);
          this.fillItem = {
            'post_title': '',
            'post_body': ''
          };
          $("#edit-item").modal('hide');
          toastr.success('Item Updated Successfully.', 'Success Alert', {
            timeOut: 5000
          });
        }, (response) => {
          this.formErrorsUpdate = response.data;
          response.data['_common'] && toastr.error(response.data['_common'].join('<br>'), 'Error', {
            timeOut: 5000
          });
        });
      },
      changePage: function (page) {
        this.pagination.current_page = page;
        this.getVueItems(page);
      }
    }
  });
}
