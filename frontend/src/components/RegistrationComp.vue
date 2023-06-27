<template>
    <div>
        <h1>Register Here</h1>
        <input type="text" placeholder="username" ref="username">
        <input type="password" placeholder="password" ref="password">
        <button @click="submit" class="btn btn-success">Submit</button>
    </div>
</template>

<script>
    import axios from '../axiosConfig';

    export default {
        props: {
            role: {
            type: String,
            required: true
            }
        },
        methods: {
            submit() {
                const data = {
                    username: this.$refs.username.value,
                    password: this.$refs.password.value,
                    role:this.role,
                };
                
                if(this.role == 'admin'){
                    this.$swal
                    .fire({
                    title: 'Are you sure to new create account?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No!',
                    })
                    .then(result => {
                    if (result.isConfirmed) {
                        axios.post('/api/createUser', data)
                        .then(response => {
                            const result = response.data;
                            if(result != 'success'){
                                this.$swal.fire({
                                    title: response.data,
                                });
                            }
                            else{
                                this.$swal.fire({
                                icon: 'success',
                                title: 'Create Account Success!',
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error.response);
                        });
                    } 
                    });
                }
                else if(this.role == 'user'){
                    axios.post('/api/createUser', data)
                    .then(response => {
                        const result = response.data;
                        if(result != 'success'){
                            alert(result);
                        }
                        else{
                            alert('success');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error.response);
                    });
                }

           
                
            },
        }
    }
</script>