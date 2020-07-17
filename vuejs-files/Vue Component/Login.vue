<template>
<div>
  <login-layout>
    <section class="section hero is-fullheight is-error-section">
      <div class="hero-body">
        <div class="container">
          <div class="columns is-centered">
            <div class="column is-two-fifths">
              <div class="card-login card has-card-header-background">
                <header class="card-header">
                  <p class="card-header-title">
                    <span class="icon">
                      <i class="mdi mdi-lock default"></i>
                    </span>
                    <span>Login</span>
                  </p>
                  <!-- <a href="#/" class="button is-small router-link-active">Dashboard</a> -->
                </header>
                <div class="card-content-login">
                  <form method="POST" @submit.prevent="login">
                    <div class="field"> 
                      <label class="label">E-mail Address</label>
                      <div class="control is-clearfix">
                        <input
                          type="email"
                          autocomplete="on"
                          name="email"
                          required="required"
                          autofocus="autofocus"
                          class="input"
                          v-model="email"
                        />
                        <!---->
                        <!---->
                        <!---->
                      </div>
                      <!---->
                    </div>
                    <div class="field">
                      <label class="label">Password</label>
                      <div class="control is-clearfix">
                        <input
                          type="password"
                          autocomplete="on"
                          name="password"
                          required="required"
                          class="input"
                          v-model="password"
                        />
                        <!---->
                        <!---->
                        <!---->
                      </div>
                      <!---->
                    </div>
                    <div class="field">
                      <!---->
                      <label class="b-checkbox checkbox is-thin">
                        <input type="checkbox" true-value="true" value="false" />
                        <span class="check is-link"></span>
                        <span class="control-label">Remember me</span>
                      </label>
                      <!---->
                    </div>
                    <hr />
                    <div class="field is-grouped">
                      <!---->
                      <div class="control">
                        <button type="submit" class="button is-black">Login</button>
                      </div>
                      <div class="control">
                        <a
                          href="#/password-recovery"
                          class="button is-outlined is-black"
                        >Forgot Password?</a>
                      </div>
                      <!---->
                    </div>
                  </form>
                </div>
                <!---->
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="hero-foot has-text-centered">
        <div class="logo"></div>
      </div>
    </section>
  </login-layout>
</div>
</template>

<script>
import LoginLayout from '@/layout/login'
import { mapActions,mapGetters } from 'vuex'
export default{
  name: 'login',
  components:{
   LoginLayout
  },

  data(){
    return {
      email : '',
      password : ''
    }
  },

  methods: {
 
   login: function () {
     const { email, password } = this
     this.$store.dispatch('AUTH_REQUEST', { email, password }).then((resp) => {
       
      if(resp.data.status == 401){
        this.$buefy.snackbar.open({
          message: resp.data.msg,
          queue: false
        });
      }else if(resp.data.status == 200){
   
        this.$store.commit('user', resp.data.data.user)
        this.$router.push('/')
      }
     })
   }
  },

  created(){

     if(this.$store.getters.isAuthenticated){
      this.$router.push({name:'home'});
     }
  }
}
</script>

