import {API_URL} from '@/common/config.js'
import {ID_TOKEN_KEY} from '@/common/jwt-service'
import ApiService from "@/common/api-service";
import {AUTH_PROFILE_UPDATE_API,AUTH_PROFILE_API,AUTH_LOGIN_API} from "@/common/api";
export const auth = {

  state: {
    token: localStorage.getItem(ID_TOKEN_KEY) || '',
    status: '',
    user: {}
  },

  getters: {
    isAuthenticated: state => !!state.token,
    authStatus: state => state.status,
    authUser: state => state.user
  },

  actions: {
    AUTH_REQUEST: ({commit, dispatch}, user) => {
      return new Promise((resolve, reject) => { // The Promise used for router redirect in login
        commit('AUTH_REQUEST');
      
        axios({url: API_URL+AUTH_LOGIN_API, data: user, method: 'POST' })
          .then(resp => {
           
            if(resp.data.status == 400){
               resolve(resp);
            }else if(resp.data.status == 200){
              commit('AUTH_PROFILE', resp.data.data.user);
              var token = resp.data.data.token;
              localStorage.setItem(ID_TOKEN_KEY, token) // store the token in localstorage
              commit('AUTH_SUCCESS', token);
              
             
            }
            resolve(resp);
          })
        .catch(err => {
          commit('AUTH_ERROR', err)
          localStorage.removeItem(ID_TOKEN_KEY) // if the request fails, remove any possible user token if possible
          reject(err)
        })
      })
    },

    AUTH_PROFILE: ({commit, dispatch}) => {
      return new Promise((resolve, reject) => { // The Promise used for router redirect in login
   
        axios({url: API_URL+AUTH_PROFILE_API, headers: ApiService.setHeader(), method: 'POST' })
          .then(resp => {
           
            if(resp.data.status == 400){
               resolve(resp);
            }else if(resp.data.status == 200){
               
              commit('AUTH_PROFILE', resp.data.data);
              // dispatch('USER_REQUEST');
            }
            resolve(resp);
          })
        .catch(err => {
           
        })
      })
    },

    AUTH_PROFILE_UPDATE: ({commit, dispatch},data) => {
      return new Promise((resolve, reject) => { // The Promise used for router redirect in login
        var form = ApiService.formData(data);
        ApiService.setHeader();
        ApiService.post(API_URL+AUTH_PROFILE_UPDATE_API,form)
          .then(resp => {
            if(resp.data.status == 200){
              commit('AUTH_PROFILE', resp.data.data);
            }
            resolve(resp);
          })
        .catch(err => {
          
        })
      })
    },

    AUTH_LOGOUT: ({commit, dispatch}) => {
      return new Promise((resolve, reject) => {
        commit('AUTH_LOGOUT')
        localStorage.removeItem(ID_TOKEN_KEY) // clear your user's token from localstorage
        resolve()
      })
    }
    
  },


  // basic mutations, showing loading, success, error to reflect the api call status and the token when loaded

  mutations: {
    AUTH_REQUEST: (state) => {
      state.status = 'loading'
    },
    AUTH_LOGOUT: (state) => {
      state.status = 'loading'
      state.token = ''
    },
    AUTH_SUCCESS: (state, token) => {
      state.status = 'success'
      state.token = token
    },
    AUTH_ERROR: (state) => {
      state.status = 'error'
    },

    AUTH_PROFILE: (state,user) => {
     state.user = user;
    },
  }
}