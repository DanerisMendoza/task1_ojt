<template>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">Login</div>
            <div class="card-body">
                <div class="form-group">
                  <label for="username">Username</label>
                  <input type="text" class="form-control" ref="username"  required>
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" ref="password" required>
                </div>
                <button type="submit" class="btn btn-primary" @click="login">Login</button>
                <button type="submit" class="btn btn-secondary" @click="registration">Registration</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
    <script>
    import axios from '../axiosConfig';

    export default {

      methods: {
            data(){
              return{
                username: null,
                role: null,
                user_id: null,
              }
            },

            login() {
                const data = {
                    username: this.$refs.username.value,
                    password: this.$refs.password.value
                };

                axios.post('/api/loginUser',  data )
                .then(response => {
                    // Handle successful login
                    const result = response.data;
                    if(result == 'invalid'){
                      alert(result);
                    }
                    else{
                      //login success
                      this.username = response.data.username;
                      this.role = response.data.role;
                      this.user_id = response.data.user_id;

                      localStorage.setItem('username',this.username);
                      localStorage.setItem('role',this.role);
                      localStorage.setItem('user_id',this.user_id);

                      //admin 
                      if(this.role == 'admin'){
                        this.$router.push('/adminIndex');
                      }
                      //user
                      else if(this.role == 'user'){
                        this.$router.push('/userIndex');
                      }
                      alert('valid');
                    }
                })
                .catch(error => {
                    // Handle login error
                    console.error('Error:', error.response);
                });
            },

            registration(){
              this.$router.push('/userRegistration');
            }
        },
    };
    </script>
  
    <style scoped>
    .container {
        margin-top: 100px;
    }
    </style>
  