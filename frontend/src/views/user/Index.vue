<template>
    <div>
        <Navbar :navType="'user'"/>

        <h1>Index Page</h1>
        <h1>Hi, {{username}}!</h1>
    </div>
</template>

<script>
    import Navbar from '../../components/Navbar.vue';
    export default{
        components: { Navbar},
        data() {
            return {
            username: localStorage.getItem('username')
            }
        },
    }
</script>