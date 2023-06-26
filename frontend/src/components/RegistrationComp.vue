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
            },
        }
    }
</script>