<template>
    <div>
        <button type="submit" class="btn btn-secondary" @click="registration">Back</button>

        <RegistrationComp :role="'user'" />
    </div>
</template>

<script>
 import RegistrationComp from '../../components/RegistrationComp.vue';

 export default {
    name: 'App',
    components: { RegistrationComp},
    methods:{
        registration(){
            this.$router.push('/Login');
        }
    }
  }
</script>