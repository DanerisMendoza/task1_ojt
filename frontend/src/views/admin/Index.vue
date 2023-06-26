<template>
    <div>
        <Navbar :navType="'admin'"/>
        <h1>Index Page</h1>
        <h1>Hi, {{username}}!</h1>
    </div>
</template>

<script>
 import Navbar from '../../components/Navbar.vue';

 export default {
  data() {
    return {
      username: localStorage.getItem('username')
    }
  },
  mounted() {
    window.addEventListener('storage', this.handleStorageChange);
  },
  destroyed() {
    window.removeEventListener('storage', this.handleStorageChange);
  },
  methods: {
    handleStorageChange(event) {
      if (event.key === 'username') {
        const newUsername = event.newValue;
        this.username = newUsername
        localStorage.setItem('username',newUsername);
        if(newUsername == 'null'){
          this.$router.push('/');
        }
      }
    }
  },

    name: 'App',
    components: { Navbar},
  }
</script>