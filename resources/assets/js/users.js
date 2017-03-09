/**
 * @todo load on demand for the page from blade stack
 */

const toastr = require('toastr');

window._managers.usersManager = () => {
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
        'title': '',
        'description': ''
      },
      fillItem: {
        'title': '',
        'description': '',
        'id': ''
      }
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
        this.$http.get('/manage/users?page=' + page).then((response) => {
          this.$set('items', response.data.data.data);
          this.$set('pagination', response.data.pagination);
        });
      },
      createItem: function () {
        var input = this.newItem;
        this.formErrors = {};
        this.$http.post('/manage/users', input).then((response) => {
          this.changePage(this.pagination.current_page);
          this.newItem = {
            'name': '',
            'email': '',
            'password': ''
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
        this.$http.delete('/manage/users/' + item.id).then((response) => {
          this.changePage(this.pagination.current_page);
          toastr.success('Item Deleted Successfully.', 'Success Alert', {
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
        this.fillItem.name = item.name;
        this.fillItem.email = item.email;
        this.fillItem.password = item.password;
        $("#edit-item").modal('show');
      },
      updateItem: function (id) {
        var input = this.fillItem;
        this.$http.put('/manage/users/' + id, input).then((response) => {
          this.changePage(this.pagination.current_page);
          this.fillItem = {
            'id': '',
            'name': '',
            'email': '',
            'password': ''
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
