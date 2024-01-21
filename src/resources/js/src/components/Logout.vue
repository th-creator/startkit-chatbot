<template >
<button class="btn" @click="logout">logout</button>
</template>
<!-- <template> -->
  <!-- <div>
   
  
      
      <button @click="handleLogout">Logout</button>
    </div>
  
  
</template> -->






<script>
import axios from 'axios';
export default{
  
  methods:{
     logout (){
      // console.log(localStorage.getItem("access_token"))
      const token = localStorage.getItem("access_token")
      axios.get(`/api/logout/${token}`, {
         headers: {
           Authorization: `Bearer ${localStorage.getItem("access_token")}`,
         },
      }).then(() => {
    //  console.log(res)
     localStorage.removeItem("access_token");
     console.log(token)
     window.location.replace("/login")
       })
       .catch(err => {
        console.error(err);
      });
      
      
      
    },

},

};
// import { mapState, mapActions } from 'vuex';

// export default {
//   computed: {
//     ...mapState('auth', ['isAuthenticated']),
//   },
//   methods: {
//     ...mapActions('auth', ['logout']),
//     handleLogout() {
//       this.logout();
    
//     },
//   },
// };
 
</script>
<style>
.btn{
  margin-top: 200%;
  margin-left: 20%;

}
</style>
  
    
    


