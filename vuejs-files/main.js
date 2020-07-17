require('./bootstrap');
/* eslint-disable */
/* Styles */
import './scss/main.scss'
import '@mdi/font/css/materialdesignicons.css'

/* Core */
import Vue from 'vue'
import Buefy from 'buefy'

/* Router & Store */
import router from './router'
import store from './store'

/* Service Worker */
import './registerServiceWorker'

/* Vue. Main component */
import App from './App.vue'

/* Vue. Component in recursion */
import AsideMenuList from './components/AsideMenuList'
import ApiService from "./common/api-service";
ApiService.init();
/* Collapse mobile aside menu on route change */
router.afterEach(() => {
  store.commit('asideMobileStateToggle', false)
})


Vue.config.productionTip = true

/* These components are used in recursion algorithm */
Vue.component('AsideMenuList', AsideMenuList)


// Global
import DashboardLayout from '@/layout/dashboard'
import CardComponent from '@/components/CardComponent'
import HeroBar from '@/components/HeroBar'
import TitleBar from '@/components/TitleBar'
import Tiles from '@/components/Tiles'
import Notification from '@/components/Notification'
import TableHoverAction from '@/components/common/TableHoverAction'
import {mapState} from 'vuex'
Vue.component('dashboard-layout',DashboardLayout)
Vue.component('card-component',CardComponent)
Vue.component('hero-bar',HeroBar)
Vue.component('title-bar',TitleBar)
Vue.component('tiles',Tiles)
Vue.component('notification',Notification)
Vue.component('table-hover-action',TableHoverAction)
Vue.use(Buefy)

 
Vue.mixin({
	computed:{
		...mapState({
	      authUser: state => state.auth.user
	    }),
	},
	methods:{
	   	Notify(msg){
	   		this.$buefy.snackbar.open({
	      	  message: msg,
	          queue: false,
	          position:'is-top'
	   		});
	   	},
	   	getCountries(){
	   		this.$store.dispatch('common/COUNTRIES');
	   	},
	   	$can(permissions) {
	   		if(!permissions){
		        return true;
		      }else{
		      	
		      	if(this.authUser.permissions){
		      		if(permissions.filter(e => this.authUser.permissions.indexOf(e) !== -1).length > 0){
		      			return true;
		      		}else{
		      			return false;
		      		}
		      	}
		      	return false;
		      }
	   		
        	// return this.authUser.permissions.indexOf(permissionName) !== -1;
	    },

	    $hasRole(roleName) {
	        return window.User_roles.indexOf(roleName) !== -1;
	    },
	}
})

new Vue({
  mounted(){
  	this.getCountries();
  },
  router,
  store,
  render: h => h(App)
}).$mount('#app')
