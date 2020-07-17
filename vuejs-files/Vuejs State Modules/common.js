import {API_URL} from '@/common/config.js'
import {COUNTRIES_API} from "@/common/api";
const namespaced = true;
export const common = {
  namespaced,
  state: {
    countries: []
  },

  getters: {
    countries: state => state.countries,
  },

  actions: {
    COUNTRIES: ({commit, dispatch,getters}, user) => {
      return new Promise((resolve, reject) => {  
        if(getters.countries.length == 0){
          axios({url: API_URL+COUNTRIES_API, method: 'GET' })
            .then(resp => {
              commit('COUNTRIES', resp.data.data);
              resolve(true);
            })
          }else{
            resolve(true);
          }
        
      })
    }
  },

  // basic mutations, showing loading, success, error to reflect the api call status and the token when loaded

  mutations: {
    COUNTRIES: (state,c) => {
      state.countries = c
    }
  }
}