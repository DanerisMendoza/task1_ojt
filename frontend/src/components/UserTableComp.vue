<template>
  <div>
    <PopupModal>
      <template v-slot:title>
        <h2>EDIT USER</h2>
      </template>
      <template v-slot:content>
        <label for="username">Enter New Username(Optional):</label>
        <br />
        <input type="text" v-model="selectedUser.username" />
        <br />
        <label for="password">Enter New or Current Password(Required):</label>
        <br />
        <input type="password" ref="password" placeholder="password"/>
        <br /><br />
        <button class="btn btn-warning" @click="updateUserInfo">Update</button>
      </template>
    </PopupModal>

    <button class="btn btn-danger" @click="resetTb">Reset Table</button>

    <v-data-table
      :headers="tableHeaders"
      :items="users"
      :item-key="user => user.user_id"
    >
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
    };
  },
  components: {
    PopupModal,
  },
  methods: {
    // functions that call Laravel API
    fetchStudents() {
      axios
        .get('/api/viewUser')
        .then(response => {
          this.users = response.data;
          console.log(response.data);
        })
        .catch(error => {
          console.error(error);
        });
    },
    resetTb() {
      axios
        .delete('/api/deleteAllUser')
        .catch(error => {
          console.error(error);
        });
    },
    // local functions
    editUser(user) {
      this.selectedUser.user_id = user.user_id;
      this.selectedUser.username = user.username;
      this.$emitter.emit('editUserModal');
    },
    updateUserInfo() {
      if (this.$refs.password.value == '') {
        alert('Password is Required');
        return;
      }

      axios
        .put('/api/updateUserByUser_id', {
          user_id: this.selectedUser.user_id,
          username: this.selectedUser.username,
          password: this.$refs.password.value,
        })
        .then(response => {
          if (response.data == 'success') {
            alert('update success!');
          }
        })
        .catch(error => {
          console.error(error);
        });
    },

    // Axios does not support sending request bodies with DELETE requests by default.
    deleteUser(user) {
      axios
        .delete('/api/deleteUserByUser_id', {
          data: { user_id: user.user_id },
        })
        .then(response => {
          // Success: handle the response
          alert(response.data);
        })
        .catch(error => {
          // Error: handle the error
          console.error(error);
        });
    },
  },
  mounted() {
    this.$Echo.channel('channel-user_tb_data').listen('user_tb_data', e => {
      console.log(e.result);
      this.users = e.result;
    });
  },
};
</script>
