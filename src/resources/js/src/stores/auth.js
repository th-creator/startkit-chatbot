

// const auth = {
//   state: {
//     isAuthenticated: false,
//     accessToken: null,
//   },
//   mutations: {
//     setAuthenticated(state, isAuthenticated) {
//       state.isAuthenticated = isAuthenticated;
//     },
//     setAccessToken(state, accessToken) {
//       state.accessToken = accessToken;
//     },
//     logout(state) {
//       state.isAuthenticated = false;
//       state.accessToken = null;
//     },
//   },
//   actions: {
//     login({ commit }, { accessToken }) {
    
//       commit('setAuthenticated', true);
//       commit('setAccessToken', accessToken);
//     },
//     logout({ commit }) {
  
//       commit('logout');
//     },
//   },
// };

export default auth;
