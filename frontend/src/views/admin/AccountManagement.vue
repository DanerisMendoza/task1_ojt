<template>
    <div>
        <Navbar :navType="'admin'"/>
        <h1>Account Management Page</h1>
        <RegistrationComp :role="'admin'" />
        <UserTableComp/>
    </div>
</template>

<script>
 import Navbar from '../../components/Navbar.vue';
 import RegistrationComp from '../../components/RegistrationComp.vue';
 import UserTableComp from '../../components/UserTableComp.vue';

 export default {
    name: 'App',
    components: { Navbar,RegistrationComp,UserTableComp},
  }
</script>