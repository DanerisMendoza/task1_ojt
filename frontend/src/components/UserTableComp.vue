<template>
  <div>
    <PopupModal>
      <template v-slot:title>
        <h2>EDIT USER</h2>
      </template>
      <template v-slot:content>
        <label for="username">Enter New Username(Optional):</label>
        <br />
        <input type="text" v-model="selectedUser.username" class="form-control"/>
        <br />
        <label for="password">Enter New or Current Password(Required):</label>
        <br />
        <input type="password" ref="password" placeholder="password" class="form-control"/>
        <br /><br />
        <button class="btn btn-warning" @click="updateUserInfo">Update</button>
      </template>
    </PopupModal>

    <button class="btn btn-danger" @click="resetTb">Reset Table</button>
    <button class="btn btn-secondary" @click="generateExcel">Export To Excel</button>

    <br><br>
    <v-text-field
      v-model="search"
      label="Search"
      clearable
      outlined
      dense
    ></v-text-field>

    <v-data-table
      :headers="tableHeaders"
      :items="filteredUsers"
      item-key="user_id"
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
      generateExcel() {
        const data = this.filteredUsers.map(user => {
        return {
          User_ID: user.user_id,
          Role: user.role,
          Username: user.username,
          Password: user.password,
          Created_At: new Date(user.created_at).toLocaleString(),
          Updated_At: new Date(user.updated_at).toLocaleString(),
        };
      });

      console.log(data);
      const workbook = new ExcelJS.Workbook();
      const worksheet = workbook.addWorksheet('Sheet1');
      const widthSize = 50;

      // Add headers
      worksheet.getCell('A1').value = 'User_ID';
      worksheet.getCell('B1').value = 'Role';
      worksheet.getCell('C1').value = 'Username';
      worksheet.getCell('D1').value = 'Password';
      worksheet.getCell('E1').value = 'Created_At';
      worksheet.getCell('F1').value = 'Updated_At';

      // Calculate maximum column width based on cell values
      const columnWidths = {};
      data.forEach(row => {
        Object.keys(row).forEach((key, columnIndex) => {
          const cellValue = row[key].toString();
          const columnLetter = String.fromCharCode(65 + columnIndex); // A=65, B=66, etc.

          if (!columnWidths[columnLetter] || cellValue.length > columnWidths[columnLetter]) {
            columnWidths[columnLetter] = cellValue.length;
          }
        });
      });

// Adjust column widths
Object.keys(columnWidths).forEach(column => {
  const maxCellWidth = Math.max(10, columnWidths[column] + 2); // Minimum width of 10 and add extra padding
  const columnWidth = Math.min(maxCellWidth, 80); // Limit the column width to a maximum of 30 characters

  worksheet.getColumn(column).width = columnWidth;
});


      // Adjust column widths
      // worksheet.getColumn('A').width = widthSize; // Set the width for column A
      // worksheet.getColumn('B').width = widthSize; // Set the width for column B
      // worksheet.getColumn('C').width = widthSize; // Set the width for column C
      // worksheet.getColumn('D').width = widthSize; // Set the width for column D
      // worksheet.getColumn('E').width = widthSize; // Set the width for column E
      // worksheet.getColumn('F').width = widthSize; // Set the width for column F

      // Add data rows
      data.forEach((row, rowIndex) => {
        worksheet.getCell(rowIndex + 2, 1).value = row.User_ID;
        worksheet.getCell(rowIndex + 2, 2).value = row.Role;
        worksheet.getCell(rowIndex + 2, 3).value = row.Username;
        worksheet.getCell(rowIndex + 2, 4).value = row.Password;
        worksheet.getCell(rowIndex + 2, 5).value = row.Created_At;
        worksheet.getCell(rowIndex + 2, 6).value = row.Updated_At;
        // const currentRow = worksheet.getRow(rowIndex + 2);
        // currentRow.height = 50; 
      });

      // Generate and download the Excel file
      workbook.xlsx.writeBuffer().then(buffer => {
        const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        const fileName = 'example.xlsx';

        // Use FileSaver.js to save the file
        FileSaver.saveAs(blob, fileName);
      });
      console.log('generate excel')
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
    this.$Echo.channel('channel-user_tb_data').listen('user_tb_data', e => {
      this.users = e.result;
    });
  },
};
</script>
