<template>
    <div>
      
    </div>
</template>

<script>
    import axios from '../axiosConfig';
    import PopupModal from './PopupModal.vue';
    
    export default{

        created(){
            this.fetchStudents();
            this.channel = new BroadcastChannel('modalTrigger');
        },
        data(){
            return{
                categories: [],
                selectedUser: {
                  user_id: '',
                  username: '',
                }
            }
        },
        components: {
          PopupModal
        },

        /* Note: 
        you cannot send data using the GET method and receive it as a request in Laravel 
        without modifying the URL.
        */

        methods: {
            // functions that call laravel api
            fetchStudents() {
                const data = {
                    user_id: localStorage.getItem('user_id')
                };
                const url = '/api/viewCategoryByUserId' + '?' + new URLSearchParams(data).toString();
                axios.get(url)
                  .then(response => {
                    this.categories = response.data;
                  })
                  .catch(error => {
                    console.error(error);
                  });
            },

            resetTb() {
                axios.delete('/api/deleteAllUser')
                    .catch(error => {
                        console.error(error);
                    });
            },
            // local functions
            editUser(user){
              this.selectedUser.user_id = user.user_id;
              this.selectedUser.username = user.username;
              this.emitter.emit("editUserModal");
            },
            updateUserInfo(){
              if(this.$refs.password.value == ''){
                alert('Password is Required');
                return;
              }

              axios.put('/api/updateUserByUser_id',{
                user_id:this.selectedUser.user_id,
                username:this.selectedUser.username,
                password:this.$refs.password.value
              }).then(response => {
                  if(response.data == 'success'){
                    alert('update success!');
                  }
                })
                .catch(error => {
                  console.error(error);
                });
            },

            // Axios does not support sending request bodies with DELETE requests by default.
            deleteUser(user) {
            axios.delete('/api/deleteUserByUser_id', { 
              data: { user_id: user.user_id } 
            })
              .then(response => {
                // Success: handle the response
                alert(response.data);
              })
              .catch(error => {
                // Error: handle the error
                console.error(error);
              });
}

        },

        mounted(){
        window.Echo.channel('channel-user_tb_data').listen('user_tb_data',(e) => {
            this.users = e.result;
        });
        },


        
    }
</script>