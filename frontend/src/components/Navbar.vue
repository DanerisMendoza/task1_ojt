<template>
  <div>
    <nav>
      <ul v-if="navType == 'admin'">
        <li @click="navigateTo('adminIndex')" :class="{ active: currentPage === 'adminIndex' }">Index</li>
        <li @click="navigateTo('AccountManagement')" :class="{ active: currentPage === 'AccountManagement' }">AccountManagement</li>
        <li @click="clearUserInfo(); navigateTo('Login'); " >Logout</li>
      </ul>
      <ul v-if="navType == 'user'">
        <li @click="navigateTo('userIndex')" :class="{ active: currentPage === 'userIndex' }">Index</li>
        <li @click="navigateTo('deck')" :class="{ active: currentPage === 'deck' }">Deck</li>
        <li @click="clearUserInfo(); navigateTo('Login'); " >Logout</li>
      </ul>
    </nav>
  </div>
  </template>
  
  <script>
  export default {
    props: {
        navType: {
        type: String,
        required: true
        }
    },

    data() {
      return {
        currentPage: null
      };
    },
    created() {
      this.currentPage = this.$route.name;
    },
    watch: {
      $route(to) {
        this.currentPage = to.name;
      }
    },
    methods: {
      navigateTo(page) {
        this.$router.push({ name: page });
      },
      clearUserInfo(){
        localStorage.setItem('username',null);
        localStorage.setItem('role',null);
        localStorage.setItem('user_id',null);

        this.$store.commit('setUserId', null);
        this.$store.commit('setUsername', null);
        this.$store.commit('setRole', null);
      }
    }
  };
  </script>
  
  <style scoped>
  nav {
    background-color: #f2f2f2;
  }
  
  ul {
    list-style: none;
    display: flex;
    padding: 0;
  }
  
  li {
    margin-right: 10px;
    cursor: pointer;
  }
  
  li.active {
    font-weight: bold;
  }
  </style>
  