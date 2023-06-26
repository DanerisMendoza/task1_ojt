<template>
    <div>
        <h1>New Category</h1>
        <input type="text" placeholder="Category" ref="category">
        <button @click="submit" class="btn btn-success">Create</button>
    </div>
</template>

<script>
    import axios from '../axiosConfig';

    export default {
        data(){
            return{
                user_id:  localStorage.getItem('user_id'),
            }
        },
        methods: {
            submit() {
                const data = {
                    user_id: this.user_id,
                    category: this.$refs.category.value,
                };

                axios.post('/api/createCategory', data)
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