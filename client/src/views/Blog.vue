<template>
  <v-container>
    <v-img :src="blog.image" height="300px" cover>
    </v-img>
    <v-card-title>{{ blog.title }}</v-card-title>
    <v-card-text>
      {{ blog.content }}
    </v-card-text>
    <v-form class="py-5">
      <div class="text-h5 pb-3">Yorum Ekle</div>
      <v-alert class="mb-3" v-if="message != null" density="compact" type="error" title="Hata Mesajı" :text="message"></v-alert>
      <v-textarea label="Yorum" v-model="comment" required type="text"></v-textarea>
      <v-btn color="green" variant="tonal" @click="addComment">Ekle</v-btn>
    </v-form>
    <v-divider class="py-3"></v-divider>
    <div class="text-h5">{{ comments.length }} Adet Yorum</div>
    <v-list>
      <v-list-item class="py-2" v-for="comment in comments" :key="comment.id" :title="comment.name" :subtitle="comment.comment">
        <template v-slot:prepend>
          <v-avatar color="grey-lighten-1">
            <v-img src="https://api.muhammetkoklu.com/static/avatar.svg"></v-img>
          </v-avatar>
        </template>
      </v-list-item>
    </v-list>
  </v-container>
</template>

<script>
import axios from 'axios'
export default {
  name: 'BlogView',
  data() {
    return {
      blog: {
        title: '',
        content: '',
        image: '',
      },
      comment: null,
      comments: [],
      message: null,
    }
  },
  methods: {
    addComment() {
      if(this.comment){
        // add comment with axios
        axios.post('http://localhost:8000/api/blog/comment', {
          comment: this.comment,
          blog_id: this.$route.params.id
        }, {
          headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
          }
        })
          .then(response => {
            this.comments.push(response.data)
            this.comment = null
          })
          .catch(error => {
            if (error.response.status == 401) {
              this.message = 'Lütfen giriş yapınız.'
            }else{
              this.message = error.response.data.message
            }
          })
      }
    }
  },
  mounted() {
    console.log(this.$route.params.id)
    axios.get('http://localhost:8000/api/blog/' + this.$route.params.id)
      .then(res => {
        console.log(res.data)
        res.data.image = 'http://localhost:8000/images/' + res.data.image
        this.blog = res.data
        // get comments
        axios.get(`http://localhost:8000/api/blog/${this.$route.params.id}/comments`)
          .then(res => {
            console.log(res.data)
            this.comments = res.data
          })
          .catch(err => {
            console.log(err)
          })
      })
      .catch(err => {
        console.log(err)
      })
  }

}
</script>

<style>
#app .v-container {
  max-width: 1200px;
}
</style>