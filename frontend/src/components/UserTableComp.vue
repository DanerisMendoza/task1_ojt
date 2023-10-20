<template>
  <div>
    <PopupModal>
      <template v-slot:title>
        <h2>EDIT USER</h2>
      </template>
      <template v-slot:content>
        <label for="username">Enter New Username(Optional):</label>
        <br />
        <input type="text" v-model="selectedUser.username" class="form-control" />
        <br />
        <label for="password">Enter New or Current Password(Required):</label>
        <br />
        <input type="password" ref="password" placeholder="password" class="form-control" />
        <br /><br />
        <button class="btn btn-warning" @click="updateUserInfo">Update</button>
      </template>
    </PopupModal>

    <button class="btn btn-danger" @click="resetTb">Reset Table</button>
    <button class="btn btn-secondary" @click="generateExcel">Export To Excel</button>
    <button class="btn btn-secondary" @click="sendHello">Send Hello to WS</button>

    <br><br>
    <v-text-field v-model="search" label="Search" clearable outlined dense></v-text-field>

    <v-data-table :headers="tableHeaders" :items="filteredUsers" item-key="user_id" @pagination="onPageChange">
      <template v-slot:item="{ item }">
        <tr>
          <td>{{ item.user_id }}</td>
          <td>*{{ item.role }}</td>
          <td>{{ item.username }}</td>
          <td>{{ item.password }}</td>
          <td>{{ new Date(item.created_at).toLocaleString() }}</td>
          <td>{{ new Date(item.updated_at).toLocaleString() }}</td>
          <td>
            <button @click="editUser(item)" class="btn btn-warning">Edit</button>
          </td>
          <td>
            <button @click="deleteUser(item)" class="btn btn-danger">Delete</button>
          </td>
        </tr>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import axios from '../axiosConfig';
import PopupModal from './PopupModal.vue';
import ExcelJS from 'exceljs';
import FileSaver from 'file-saver';

export default {
  created() {
    this.fetchStudents();
    this.channel = new BroadcastChannel('modalTrigger');
  },
  data() {
    return {
      users: [],
      selectedUser: {
        user_id: '',
        username: '',
      },
      tableHeaders: [
        { text: 'user_id', value: 'user_id' },
        { text: 'role', value: 'role' },
        { text: 'username', value: 'username' },
        { text: 'Password', value: 'password' },
        { text: 'created_at', value: 'created_at' },
        { text: 'updated_at', value: 'updated_at' },
        { text: 'Actions', sortable: false },
      ],
      search: '', // Search input value
      pagination: {
        page: 1,
        itemsPerPage: 10,
      },
    };
  },

  computed: {
    filteredUsers() {
      if (!this.search) {
        return this.users; // Return all users if search input is empty
      }

      const searchTerm = this.search.toLowerCase();

      // Filter the users based on the search input
      return this.users.filter(user =>
        user.username.toLowerCase().includes(searchTerm)
      );
    },
  },

  components: {
    PopupModal,
  },
  methods: {
    sendHello() {
      const messageObject = { channel: 'channel1', message: 'hello from button' };
      const jsonString = JSON.stringify(messageObject);
      this.$ws.send(jsonString);
    },

    // functions that call Laravel API
    fetchStudents() {
      axios
        .get('/api/viewUser')
        .then(response => {
          this.users = response.data;
        })
        .catch(error => {
          console.error(error);
        });
    },
    resetTb() {
      this.$swal
        .fire({
          title: 'Are you sure you want to Reset Database?',
          text: "You won't be able to revert this!",
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'No, cancel!',
        })
        .then(result => {
          if (result.isConfirmed) {
            axios
              .delete('/api/deleteAllUser')
              .then(response => {
                this.$swal.fire({
                  icon: 'success',
                  title: 'Database Reset Success!',
                });
              })
              .catch(error => {
                console.error(error);
              });
          }
        });

    },
    // local functions
    onPageChange(page) {
      this.pagination = page;
    },

    generateExcel() {
      const startIndex = (this.pagination.page - 1) * this.pagination.itemsPerPage;
      let endIndex = startIndex + this.pagination.itemsPerPage;

      // Check if endIndex exceeds the length of filteredUsers
      if (endIndex > this.filteredUsers.length) {
        endIndex = this.filteredUsers.length;
      }

      const slicedUsers = this.filteredUsers.slice(startIndex, endIndex);


      const data = slicedUsers.map(user => ({
        User_ID: user.user_id,
        Role: user.role,
        Username: user.username,
        Created_At: new Date(user.created_at).toLocaleString(),
        Updated_At: new Date(user.updated_at).toLocaleString(),
      }));

      const workbook = new ExcelJS.Workbook();
      const worksheet = workbook.addWorksheet('Sheet1');
      const widthSize = 30;

      worksheet.columns = [
        { header: 'User_ID', key: 'User_ID', width: widthSize },
        { header: 'Role', key: 'Role', width: widthSize },
        { header: 'Username', key: 'Username', width: widthSize },
        { header: 'Created_At', key: 'Created_At', width: widthSize },
        { header: 'Updated_At', key: 'Updated_At', width: widthSize },
      ];

      worksheet.addRows(data);

      worksheet.eachRow(row => {
        row.alignment = { vertical: 'middle', horizontal: 'left' };
      });

      workbook.xlsx.writeBuffer().then(buffer => {
        const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        const fileName = 'example.xlsx';
        FileSaver.saveAs(blob, fileName);
      });

    },

    editUser(user) {
      this.selectedUser.user_id = user.user_id;
      this.selectedUser.username = user.username;
      this.$emitter.emit('editUserModal', true);
    },

    updateUserInfo() {
      if (this.$refs.password.value == '') {
        this.$swal.fire({
          icon: 'error',
          title: 'Password is Required',
          text: 'Please enter a password.',
        });

        return;
      }

      this.$swal
        .fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Yes, Update it!',
          cancelButtonText: 'No, cancel!',
        })
        .then(result => {
          if (result.isConfirmed) {
            this.$emitter.emit('editUserModal', false);
            axios
              .put('/api/updateUserByUser_id', {
                user_id: this.selectedUser.user_id,
                username: this.selectedUser.username,
                password: this.$refs.password.value,
              })
              .then(response => {
                if (response.data == 'success') {
                  this.$swal.fire({
                    icon: 'success',
                    title: 'Update Success!',
                  });
                }
              })
              .catch(error => {
                console.error(error);
              });
          }
        });


    },

    // Axios does not support sending request bodies with DELETE requests by default.
    deleteUser(user) {

      this.$swal
        .fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'No, cancel!',
        })
        .then(result => {
          if (result.isConfirmed) {
            axios
              .delete('/api/deleteUserByUser_id', {
                data: { user_id: user.user_id },
              })
              .then(response => {
                // Success: handle the response
                this.$swal.fire({
                  icon: 'success',
                  title: response.data,
                });
              })
              .catch(error => {
                // Error: handle the error
                console.error(error);
              });
          }
        });


    },
  },
  mounted() {
    // this.$Echo.channel('channel-user_tb_data').listen('user_tb_data', e => {
    //   this.users = e.result;
    // });
    this.$ws.addEventListener('message', (event) => {
      console.log(JSON.parse(event.data));
    });
    // this.$ws.addEventListener('message', (event) => {
    //   console.log(JSON.parse(event.data));
    // });
    // this.$ws.on('connection', (socket) => {
    //   this.$ws.on('message', (message) => {
    //     try {
          // console.log(message)
          // Parsing the received JSON string
          // const receivedObject = JSON.parse(message);

          // Accessing the 'channel1' property
          // const channel1Message = receivedObject.channel1;

          // console.log('Received message from client:', channel1Message);
    //     } catch (error) {
    //       console.error('Error parsing JSON:', error);
    //     }
    //   });
    // });

  },
};
</script>
