<template>
  <v-app-bar>
      <router-link :to="{ name: 'Home'}" class="text-decoration-none">
        <v-toolbar-title class="pl-3">
            <span class="text-h5" color="green">Blog Uygulaması</span>
        </v-toolbar-title>
      </router-link>
    
    <v-spacer></v-spacer>
    <div class="pr-3 d-flex align-center">
      <div v-if="!logined">
        <v-btn class="mr-3" color="green" @click="loginDialogHandle" variant="tonal">
          Giriş Yap
        </v-btn>
        <v-btn class="mr-3" color="green" @click="registerDialogHandle" variant="tonal">
          Kayıt Ol
        </v-btn>
      </div>
      <router-link v-else :to="{ name: 'AddBlog'}">
        <v-btn class="mr-3" color="green" @click="addBlogHandle" variant="tonal">
          Blog Ekle
        </v-btn>
        <v-btn class="mr-3" color="green" @click="logOutgHandle" variant="tonal">
          Çıkış Yap
        </v-btn>
      </router-link>

      <v-switch @change="toggleTheme" label="Dark Mode" hide-details></v-switch>
    </div>
    <v-dialog v-model="loginDialog" persistent width="600">
      <v-card>
        <v-card-title>
          <span class="text-h5">Giriş Yap</span>
        </v-card-title>
        <v-alert v-if="login.message != null" class="mx-4" density="compact" type="error" title="Hata Mesajı" :text="login.message"></v-alert>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="6" md="6">
                <v-text-field label="Email*" type="email" placeholder="example@example.com" v-model="login.email"
                  required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="6">
                <v-text-field label="Şifre*" type="password" v-model="login.password" required></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue-darken-1" variant="text" @click="closeLoginDialog">
            Kapat
          </v-btn>
          <v-btn color="blue-darken-1" variant="text" @click="loginHandle">
            Giriş Yap
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="registerDialog" persistent width="600">
      <v-card>
        <v-card-title>
          <span class="text-h5">Kayıt Ol</span>
        </v-card-title>
        <v-alert v-if="register.message != null" class="mx-4" density="compact" type="error" title="Hata Mesajı" :text="register.message"></v-alert>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="6" md="6">
                <v-text-field label="Adınız*" placeholder="Jhon Doe" type="text" v-model="register.name" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="6">
                <v-text-field label="Email*" placeholder="example@example.com" v-model="register.email" type="email"
                  required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="6">
                <v-text-field label="Şifre*" type="password" v-model="register.password" required></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue-darken-1" variant="text" @click="closeRegisterDialog">
            Kapat
          </v-btn>
          <v-btn color="blue-darken-1" variant="text" @click="registerHandle">
            Kayıt Ol
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app-bar>
</template>

<script>
import axios from 'axios'
import { useTheme } from 'vuetify'
export default {
  setup() {
    const theme = useTheme()
    return {
      theme,
      toggleTheme: () => theme.global.name.value = theme.global.current.value.dark ? 'light' : 'dark',
    }
  },
  data() {
    return {
      loginDialog: false,
      registerDialog: false,
      logined: false,
      register: {
        name: '',
        email: '',
        password: '',
        message: null
      },
      login: {
        email: '',
        password: '',
        message: null
      }
    }
  },
  created(){
    if(localStorage.getItem('token')){
      // get user data from api with axios
      axios.get('http://localhost:8000/api/user', {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }).then(res => {
        this.logined = true
      }).catch(err => {
        localStorage.removeItem('token')
        this.logined = false
      })
    }
  },
  methods: {
    logOutgHandle(){
      localStorage.removeItem('token')
      this.logined = false
      this.$router.push({name: 'Home'})
    },
    closeRegisterDialog() {
      this.registerDialog = false
      this.register.name = ''
      this.register.email = ''
      this.register.password = ''
      this.register.message = null
    },
    closeLoginDialog() {
      this.loginDialog = false
      this.login.email = ''
      this.login.password = ''
      this.login.message = null
    },
    registerHandle() {
      const { name, email, password } = this.register
      if (name && email && password) {
        axios.post('http://localhost:8000/api/register', {
          name,
          email,
          password
        }).then(res => {
          this.login.email = email
          this.login.password = password
          this.registerDialog = false
          this.loginHandle()

        }).catch(err => {
          this.register.message = err.response.data.message
        })
      }
    },
    loginHandle() {
      const { email, password } = this.login
      if (email && password) {
        axios.post('http://localhost:8000/api/login', {
          email,
          password
        }).then(res => {
          console.log(res)
          this.loginDialog = false
          this.logined = true
          localStorage.setItem('token', res.data.token)
          localStorage.setItem('user', JSON.stringify(res.data.user))
          this.login.email = ''
          this.login.password = ''
          this.login.message = null
        }).catch(err => {
          this.login.message = err.response.data.message

        })
      }
    },
    loginDialogHandle() {
      this.loginDialog = true
    },
    registerDialogHandle() {
      this.registerDialog = true
    },
    addBlogHandle() {
      this.$router.push({ name: 'AddBlog' })
    }
  },
}
</script>
