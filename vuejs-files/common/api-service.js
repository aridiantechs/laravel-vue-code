// import ApiService from '@/common/api-service'

import Vue from "vue";
import VueAxios from "vue-axios";
import JwtService from "@/common/jwt-service";
import { API_URL } from "@/common/config";

const ApiService = {
  init() {
    Vue.use(VueAxios, axios);
    Vue.axios.defaults.baseURL = API_URL;
  },

  formData(form,json_keys = []) {
      var formData = new FormData();

      for ( var key in form ) {

          // Keys thats need to be json
          if(json_keys.includes(key)){
              formData.append(key, JSON.stringify(form[key]));
          }else{
              formData.append(key, form[key]);
          }
      }

      return formData;
  },

  setHeader() {
    Vue.axios.defaults.headers.common[
      "Authorization"
    ] = `${JwtService.getToken()}`;
  },
  setHeaderMultipart() {
    Vue.axios.defaults.headers.common[
      "Content-Type"
    ] = 'multipart/form-data';
  },

  query(resource, params) {
    return Vue.axios.get(resource, params).catch(error => {
      throw new Error(`[RWV] ApiService ${error}`);
    });
  },

  get(resource, slug = "") {
    return Vue.axios.get(`${resource}/${slug}`).catch(error => {
      throw new Error(`[RWV] ApiService ${error}`);
    });
  },

  post(resource, params) {
    return Vue.axios.post(`${resource}`, params);
  },

  update(resource, slug, params) {
    return Vue.axios.put(`${resource}/${slug}`, params);
  },

  put(resource, params) {
    return Vue.axios.put(`${resource}`, params);
  },

  delete(resource) {
    return Vue.axios.delete(resource).catch(error => {
      throw new Error(`[RWV] ApiService ${error}`);
    });
  }
};

export default ApiService;
