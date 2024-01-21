import {createStore} from "vuex"
import axios from "axios"

export default createStore({
  state: {
    auth: {},
    isAdmin: false,
  },
  
  mutations: {
    login(state, payload) {
      state.auth = payload;
    },
    logout(state) {
      state.auth = null
    },
    isAuth(state,payload) {
      state.auth = payload
    },
    refreshReq(state) {
      state.reqInstance = axios.create({
        headers: {
          Authorization : `Bearer ${localStorage.getItem("access_token")}`
          }
      })
    },
  },
  actions: {
    async isAuth({ commit, state }) {
      try {
        const response = await axios.get("/api/user", {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("access_token")}`,
          },
        });
  
        if (response.status === 200) {
          commit("isAuth", response.data);
          
        }
      } catch (error) {
        console.log(error);
        if (error.response && error.response.status === 401) {
          // Refresh the page if the status is 401 Unauthorized
          window.location.reload();
        }
      }
    },
  },
  
})